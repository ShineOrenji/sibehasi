<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\PeriodeIuran;
use App\Models\PembayaranIuran;
use App\Models\BukuKas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

        // Ambil data periode & anggota
        $periode = PeriodeIuran::with('kategori')->findOrFail($request->periode_id);
        $anggota = Anggota::with('user')->findOrFail($request->anggota_id);

        // KODE PENYELAMAT (Agar kategori_id tidak pernah NULL)
        $kategoriFallback = \App\Models\KategoriTransaksi::where('nama_kategori', 'Uang kas')->first();
        $kategoriId = $periode->kategori_id ?? ($kategoriFallback ? $kategoriFallback->id : null);

        // Jika tetap null, blokir dan kasih tau admin
        if (!$kategoriId) {
            return back()->with('error', 'Gagal Input Cash: Tagihan ini tidak punya kategori, dan kategori cadangan "Uang kas" juga tidak ditemukan di Master Kategori.');
        }

        try {
            DB::transaction(function () use ($periode, $anggota, $kategoriId) {
                // 1. Simpan Pembayaran
                $pembayaran = PembayaranIuran::create([
                    'anggota_id' => $anggota->id,
                    'periode_id' => $periode->id,
                    'nominal_bayar' => $periode->nominal_akhir,
                    'tanggal_bayar' => now(),
                    'metode_pembayaran' => 'cash',
                    'status' => 'sukses',
                    'admin_id' => Auth::id(),
                ]);

                // 2. Otomatis masuk Buku Kas dengan kategori yang aman
                BukuKas::create([
                    'kategori_id' => $kategoriId, // SEKARANG DIJAMIN AMAN TIDAK NULL
                    'jenis' => 'pemasukan',
                    'nominal' => $periode->nominal_akhir,
                    'tanggal_transaksi' => now()->toDateString(),
                    'keterangan' => 'Pembayaran ' . $periode->nama_periode . ' dari ' . $anggota->user->name . ' (Cash)',
                    'pembayaran_iuran_id' => $pembayaran->id,
                    'admin_id' => Auth::id(),
                ]);
            });

            // Notifikasi ke Mahasiswa
            if ($anggota->user) {
                $anggota->user->notify(new \App\Notifications\NotifPembayaran(
                    'LUNAS: ' . $periode->nama_periode,
                    'Halo ' . $anggota->user->name . ', pembayaran tunai Rp' . number_format($periode->nominal,0,',','.') . ' sudah kami terima!'
                ));
            }

            // Notifikasi ke Admin
            Auth::user()->notify(new \App\Notifications\NotifPembayaran(
                'Kas Masuk 💰',
                $anggota->user->name . ' baru saja membayar cash untuk ' . $periode->nama_periode
            ));

            return redirect()->route('admin.kas.index')->with('success', 'Pembayaran cash dari ' . $anggota->user->name . ' berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error Sistem: ' . $e->getMessage());
        }
    }
}