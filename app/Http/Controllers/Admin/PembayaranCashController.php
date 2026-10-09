<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\PeriodeIuran;
use App\Models\KategoriTransaksi;
use App\Models\PembayaranIuran;
use App\Models\BukuKas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\NotifPembayaran;

class PembayaranCashController extends Controller
{
    public function create()
    {
        $anggota = Anggota::with('user')->get();
        $periode = PeriodeIuran::where('status', 'aktif')->get();
        return view('admin.pembayaran.create', compact('anggota', 'periode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'periode_id' => 'required|exists:periode_iuran,id',
        ]);

        $periode = PeriodeIuran::findOrFail($request->periode_id);
        
        // Cari kategori "Iuran Anggota" dari seeder kita
        $kategori = KategoriTransaksi::where('nama_kategori', 'Iuran Anggota')->firstOrFail();

        // Gunakan DB Transaction demi keamanan data keuangan
        try {
            DB::transaction(function () use ($request, $periode, $kategori) {
                
                // 1. Catat di tabel pembayaran_iuran
                $pembayaran = PembayaranIuran::create([
                    'anggota_id' => $request->anggota_id,
                    'periode_id' => $periode->id,
                    'nominal_bayar' => $periode->nominal,
                    'tanggal_bayar' => now(),
                    'metode_pembayaran' => 'cash',
                    'status' => 'sukses',
                    'admin_id' => Auth::id(),
                ]);

                // 2. Otomatis masukkan ke Buku Kas sebagai pemasukan
                BukuKas::create([
                    'kategori_id' => $kategori->id,
                    'jenis' => 'pemasukan',
                    'nominal' => $periode->nominal,
                    'tanggal_transaksi' => now()->toDateString(),
                    'keterangan' => 'Pembayaran iuran cash periode ' . $periode->nama_periode,
                    'pembayaran_iuran_id' => $pembayaran->id,
                    'admin_id' => Auth::id(),
                ]);
            });

            // Kirim Notifikasi ke Mahasiswa (KODE BARU)
            $anggota = Anggota::with('user')->find($request->anggota_id);
            if ($anggota && $anggota->user) {
                $anggota->user->notify(new NotifPembayaran(
                    'Pembayaran Cash Berhasil 🟢',
                    'Pembayaran iuran tunai periode ' . $periode->nama_periode . ' sebesar Rp' . number_format($periode->nominal,0,',','.') . ' telah dikonfirmasi oleh Admin.'
                ));
            }

            // Untuk notifikasi Admin di bawahnya, ganti variabel $mahasiswaUser->name menjadi $anggota->user->name
            Auth::user()->notify(new NotifPembayaran(
                'Kas Bertambah 💰',
                'Anda telah mencatat pemasukan cash dari ' . $anggota->user->name . ' untuk periode ' . $periode->nama_periode . '.'
            ));

            return redirect()->route('admin.kas.index')->with('success', 'Pembayaran cash berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
