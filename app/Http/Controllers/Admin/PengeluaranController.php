<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriTransaksi;
use App\Models\BukuKas;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    public function create()
    {
        $kategori = KategoriTransaksi::where('jenis', 'pengeluaran')->get();
        return view('admin.pengeluaran.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_transaksi,id',
            'nominal' => 'required|numeric|min:100',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'required|string|max:255',
        ]);

        BukuKas::create([
            'kategori_id' => $request->kategori_id,
            'jenis' => 'pengeluaran',
            'nominal' => $request->nominal,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'keterangan' => $request->keterangan,
            'admin_id' => Auth::id(),
        ]);

        return redirect()->route('admin.kas.index')->with('success', 'Pengeluaran berhasil dicatat!');
    }
}
