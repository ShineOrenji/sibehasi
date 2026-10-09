<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BukuKas;

class BukuKasController extends Controller
{
    public function index()
    {
        // Hitung Saldo Total (Pemasukan - Pengeluaran)
        $pemasukan = BukuKas::where('jenis', 'pemasukan')->sum('nominal');
        $pengeluaran = BukuKas::where('jenis', 'pengeluaran')->sum('nominal');
        $saldo = $pemasukan - $pengeluaran;

        // Ambil riwayat transaksi terbaru
        $transaksi = BukuKas::with(['kategori', 'admin', 'pembayaran.anggota.user'])
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('admin.kas.index', compact('saldo', 'pemasukan', 'pengeluaran', 'transaksi'));
    }
}
