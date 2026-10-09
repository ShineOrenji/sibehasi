<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeriodeIuran;
use App\Models\KategoriTransaksi;

class PeriodeIuranController extends Controller
{
    public function index()
    {
        // Ambil periode beserta kategori dan hitung jumlah yang sudah lunas
        $periode = PeriodeIuran::with('kategori')
            ->withCount(['pembayaran as lunas_count' => function ($query) {
                $query->where('status', 'sukses');
            }])
            ->orderBy('created_at', 'desc')->get();
            
        // Ambil kategori khusus pemasukan untuk form
        $kategori = KategoriTransaksi::where('jenis', 'pemasukan')->get();
        
        return view('admin.periode.index', compact('periode', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_transaksi,id',
            'nama_periode' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'deadline_pembayaran' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        PeriodeIuran::create([
            'kategori_id' => $request->kategori_id,
            'nama_periode' => $request->nama_periode, // Contoh: "Uang Kas Bulan Nov", "Pembayaran MABIM"
            'nominal' => $request->nominal,
            'tanggal_mulai' => $request->tanggal_mulai,
            'deadline_pembayaran' => $request->deadline_pembayaran,
            'status' => 'aktif',
        ]);

        return back()->with('success', 'Tagihan baru berhasil dibuat!');
    }

    // Fitur Baru: Lihat Detail Pembayar
    public function show($id)
    {
        $periode = PeriodeIuran::findOrFail($id);
        $pembayaran = \App\Models\PembayaranIuran::with('anggota.user')
                        ->where('periode_id', $id)
                        ->where('status', 'sukses')
                        ->get();

        return view('admin.periode.show', compact('periode', 'pembayaran'));
    }

    public function destroy($id)
    {
        $periode = \App\Models\PeriodeIuran::findOrFail($id);
        
        // Proteksi: Jangan izinkan hapus jika sudah ada mahasiswa yang bayar!
        // (Sesuai prinsip keamanan data keuangan)
        if (\App\Models\PembayaranIuran::where('periode_id', $id)->where('status', 'sukses')->exists()) {
            return back()->with('error', 'Gagal! Tagihan ini sudah memiliki data pembayaran (Lunas) dari mahasiswa.');
        }
        
        // Hapus data pembayaran yang masih 'pending' (jika ada) agar tidak jadi sampah database
        \App\Models\PembayaranIuran::where('periode_id', $id)->where('status', 'pending')->delete();

        $periode->delete();
        return back()->with('success', 'Tagihan berhasil dihapus.');
    }
}