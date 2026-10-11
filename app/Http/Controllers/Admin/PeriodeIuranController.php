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
        $periode = \App\Models\PeriodeIuran::latest()->get();
        $kategori = \App\Models\KategoriTransaksi::where('nama_kategori', '!=', 'Tidak Dikategorikan')->get();
        
        return view('admin.periode.index', compact('periode', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required',
            'nominal' => 'required|numeric',
            'deadline_pembayaran' => 'required|date',
        ]);

        $kategori = \App\Models\KategoriTransaksi::findOrFail($request->kategori_id);
        
        $namaOtomatis = $kategori->nama_kategori;

        \App\Models\PeriodeIuran::create([
            'nama_periode' => $namaOtomatis,
            'kategori_id' => $request->kategori_id,
            'nominal' => $request->nominal,
            'denda' => $request->denda ?? 0,
            'deadline_pembayaran' => $request->deadline_pembayaran,
            'status' => 'aktif',
        ]);

        return back()->with('success', 'Tagihan berhasil dibuat!');
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
        \App\Models\PeriodeIuran::findOrFail($id)->delete();
        
        return back()->with('success', 'Tagihan berhasil dihapus.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_id' => 'required',
            'nominal' => 'required|numeric',
            'deadline_pembayaran' => 'required|date',
        ]);

        $periode = \App\Models\PeriodeIuran::findOrFail($id);
        $kategori = \App\Models\KategoriTransaksi::findOrFail($request->kategori_id);
        
        // Murni pakai nama kategori saja
        $namaOtomatis = $kategori->nama_kategori;

        $periode->update([
            'nama_periode' => $namaOtomatis,
            'kategori_id' => $request->kategori_id,
            'nominal' => $request->nominal,
            'denda' => $request->denda ?? 0,
            'deadline_pembayaran' => $request->deadline_pembayaran,
        ]);

        return back()->with('success', 'Tagihan berhasil diperbarui!');
    }
}