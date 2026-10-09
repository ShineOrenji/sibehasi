<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeriodeIuran;
use App\Models\PembayaranIuran;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $periode = PeriodeIuran::findOrFail($request->periode_id);
        $anggota = Auth::user()->anggota;

        // Cek apakah sudah ada tagihan sebelumnya biar gak dobel
        $pembayaran = PembayaranIuran::where('anggota_id', $anggota->id)
                                     ->where('periode_id', $periode->id)
                                     ->first();

        // Bikin Order ID unik baru setiap kali request Snap
        $orderId = 'IURAN-' . $anggota->id . '-' . $periode->id . '-' . time();

        if (!$pembayaran) {
            $pembayaran = PembayaranIuran::create([
                'anggota_id' => $anggota->id,
                'periode_id' => $periode->id,
                'nominal_bayar' => $periode->nominal,
                'metode_pembayaran' => 'online',
                'status' => 'pending',
                'midtrans_order_id' => $orderId,
            ]);
        } else {
            // Kalau udah ada, update aja Midtrans Order ID-nya
            $pembayaran->update([
                'midtrans_order_id' => $orderId,
                'status' => 'pending'
            ]);
        }

        // Setup Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $periode->nominal,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => $anggota->no_hp ?? '0800000000',
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            // Tangkap pesan error dari Midtrans ke frontend
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
