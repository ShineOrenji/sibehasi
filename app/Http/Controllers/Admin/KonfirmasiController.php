<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranIuran;
use App\Models\BukuKas;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NotifPembayaran;

class KonfirmasiController extends Controller
{
    public function index()
    {
        $pending = PembayaranIuran::with(['anggota.user', 'periode.kategori'])
                    ->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        return view('admin.konfirmasi.index', compact('pending'));
    }

    public function acc($id)
    {
        $pembayaran = PembayaranIuran::with(['periode', 'anggota.user'])->findOrFail($id);
        
        // Cari Kategori "Uang kas" sebagai *fallback* (cadangan)
        $kategoriFallback = \App\Models\KategoriTransaksi::where('nama_kategori', 'Uang kas')->first();
        
        $kategoriId = $pembayaran->periode->kategori_id ?? ($kategoriFallback ? $kategoriFallback->id : null);

        if (!$kategoriId) {
            return back()->with('error', 'Gagal ACC: Kategori "Uang kas" tidak ditemukan di Master Kategori.');
        }

        $pembayaran->update(['status' => 'sukses']);

        BukuKas::create([
            'kategori_id' => $kategoriId, // Sekarang tidak akan null lagi
            'jenis' => 'pemasukan',
            'nominal' => $pembayaran->periode->nominal,
            'tanggal_transaksi' => now(),
            'keterangan' => 'Pembayaran ' . $pembayaran->periode->nama_periode . ' dari ' . $pembayaran->anggota->user->name . ' via QRIS',
            'admin_id' => Auth::id(),
        ]);

        // LOGIKA PENGHAPUSAN FILE OTOMATIS (Taruh di method ACC dan TOLAK)
        if ($pembayaran->bukti_bayar && file_exists(public_path('uploads/bukti/' . $pembayaran->bukti_bayar))) {
            unlink(public_path('uploads/bukti/' . $pembayaran->bukti_bayar));
            $pembayaran->update(['bukti_bayar' => null]);
        }

        // Notif Mahasiswa
        if ($pembayaran->anggota->user) {
            $pembayaran->anggota->user->notify(new \App\Notifications\NotifPembayaran(
                'Pembayaran di-ACC ✅',
                'Halo ' . $pembayaran->anggota->user->name . ', bukti QRIS ' . $pembayaran->periode->nama_periode . ' sudah kami konfirmasi!'
            ));
        }

        return back()->with('success', 'Pembayaran di-ACC! Bukti gambar otomatis dihapus untuk hemat storage.');
    }

    public function tolak($id)
    {
        $pembayaran = PembayaranIuran::with('anggota.user')->findOrFail($id);
        
        // HAPUS GAMBAR
        if ($pembayaran->bukti_bayar && file_exists(public_path('uploads/bukti/' . $pembayaran->bukti_bayar))) {
            unlink(public_path('uploads/bukti/' . $pembayaran->bukti_bayar));
        }

        if ($pembayaran->anggota->user) {
            $pembayaran->anggota->user->notify(new NotifPembayaran(
                'Pembayaran DITOLAK ❌',
                'Maaf ' . $pembayaran->anggota->user->name . ', bukti transfer Anda tidak valid. Silakan upload ulang.'
            ));
        }

        $pembayaran->delete();
        return back()->with('error', 'Pembayaran ditolak & bukti dihapus dari server.');
    }
}