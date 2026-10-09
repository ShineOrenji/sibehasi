<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembayaranIuran;
use App\Models\BukuKas;
use App\Models\KategoriTransaksi;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        $orderId = $payload['order_id'];
        $statusCode = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];
        $serverKey = config('midtrans.server_key');
        $signatureKey = $payload['signature_key'];

        // 1. Verifikasi Keaslian Webhook (Keamanan)
        $validSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);
        if ($validSignature !== $signatureKey) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pembayaran = PembayaranIuran::where('midtrans_order_id', $orderId)->first();
        if (!$pembayaran) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $payload['transaction_status'];

        // 2. Gunakan DB Transaction untuk update status dan masukkan ke buku kas
        DB::beginTransaction();
        try {
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                if ($pembayaran->status !== 'sukses') {
                    $pembayaran->update([
                        'status' => 'sukses',
                        'tanggal_bayar' => now(),
                        'midtrans_transaction_id' => $payload['transaction_id']
                    ]);

                    $kategori = KategoriTransaksi::where('nama_kategori', 'Iuran Anggota')->first();

                    BukuKas::create([
                        'kategori_id' => $kategori->id,
                        'jenis' => 'pemasukan',
                        'nominal' => $pembayaran->nominal_bayar,
                        'tanggal_transaksi' => now()->toDateString(),
                        'keterangan' => 'Pembayaran online iuran via Midtrans (Order: ' . $orderId . ')',
                        'pembayaran_iuran_id' => $pembayaran->id,
                    ]);
                }
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $pembayaran->update(['status' => 'gagal']);
            }

            DB::commit();
            return response()->json(['message' => 'Success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error processing webhook'], 500);
        }
    }
}