<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BukuKas;

    class BukuKasController extends Controller
        {
            public function index() {
                // Hanya hitung yang is_hidden = false
                $saldo = \App\Models\BukuKas::where('is_hidden', false)->where('jenis', 'pemasukan')->sum('nominal') - \App\Models\BukuKas::where('is_hidden', false)->where('jenis', 'pengeluaran')->sum('nominal');
                $pemasukan = \App\Models\BukuKas::where('is_hidden', false)->where('jenis', 'pemasukan')->sum('nominal');
                $pengeluaran = \App\Models\BukuKas::where('is_hidden', false)->where('jenis', 'pengeluaran')->sum('nominal');
                $transaksi = \App\Models\BukuKas::where('is_hidden', false)->latest()->get();
                return view('admin.kas.index', compact('saldo', 'pemasukan', 'pengeluaran', 'transaksi'));
            }
            public function destroy($id) {
                \App\Models\BukuKas::where('id', $id)->update(['is_hidden' => true]);
                return back()->with('success', 'Transaksi disembunyikan dari Buku Kas (Tetap aman di Laporan).');
            }
            public function destroyAll() {
                \App\Models\BukuKas::query()->update(['is_hidden' => true]);
                return back()->with('success', 'Semua transaksi disembunyikan (Tetap aman di Laporan).');
            }
}
