<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriTransaksi;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = KategoriTransaksi::orderBy('jenis', 'asc')->orderBy('nama_kategori', 'asc')->get();
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
        $kategori = KategoriTransaksi::findOrFail($id);
        
        // Proteksi jika kategori sudah dipakai di transaksi atau tagihan
        if (\App\Models\BukuKas::where('kategori_id', $id)->exists() || \App\Models\PeriodeIuran::where('kategori_id', $id)->exists()) {
            return back()->with('error', 'Gagal! Kategori ini sedang digunakan dalam transaksi atau tagihan.');
        }

        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
