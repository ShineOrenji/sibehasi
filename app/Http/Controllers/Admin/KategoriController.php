<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriTransaksi;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = \App\Models\KategoriTransaksi::where('nama_kategori', '!=', 'Tidak Dikategorikan')->latest()->get();
        
        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'jenis' => 'required|in:pemasukan,pengeluaran',
        ]);

        KategoriTransaksi::create([
            'nama_kategori' => $request->nama_kategori,
            'jenis' => $request->jenis,
        ]);

        return back()->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        // 1. Buat atau ambil Kategori Penyelamat secara otomatis
        $fallback = \App\Models\KategoriTransaksi::firstOrCreate([
            'nama_kategori' => 'Tidak Dikategorikan'
        ]);

        // Cegah admin menghapus kategori penyelamat ini
        if ($id == $fallback->id) {
            return back()->with('error', 'Gagal! Kategori sistem "Tidak Dikategorikan" tidak boleh dihapus.');
        }

        // 2. Pindahkan semua data ke kategori penyelamat (Bukan di-NULL-kan)
        \App\Models\BukuKas::where('kategori_id', $id)->update(['kategori_id' => $fallback->id]);
        \App\Models\PeriodeIuran::where('kategori_id', $id)->update(['kategori_id' => $fallback->id]);
        
        // 3. Baru hapus kategorinya dengan aman
        \App\Models\KategoriTransaksi::destroy($id);
        
        return back()->with('success', 'Kategori dihapus! Riwayat transaksi sebelumnya diamankan ke "Tidak Dikategorikan".');
    }
}
