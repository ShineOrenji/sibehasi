<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BukuKas;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter bulan & tahun, default ke bulan dan tahun ini
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $transaksi = BukuKas::with('kategori', 'admin')
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->orderBy('tanggal_transaksi', 'asc')
            ->get();

        $pemasukan = $transaksi->where('jenis', 'pemasukan')->sum('nominal');
        $pengeluaran = $transaksi->where('jenis', 'pengeluaran')->sum('nominal');
        $saldo = $pemasukan - $pengeluaran;

        return view('admin.laporan.index', compact('transaksi', 'bulan', 'tahun', 'pemasukan', 'pengeluaran', 'saldo'));
    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $transaksi = BukuKas::with('kategori', 'admin')
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->orderBy('tanggal_transaksi', 'asc')
            ->get();

        $pemasukan = $transaksi->where('jenis', 'pemasukan')->sum('nominal');
        $pengeluaran = $transaksi->where('jenis', 'pengeluaran')->sum('nominal');
        $saldo = $pemasukan - $pengeluaran;
        
        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('transaksi', 'namaBulan', 'pemasukan', 'pengeluaran', 'saldo'));
        
        return $pdf->download('Laporan_Keuangan_HIMASI_' . $namaBulan . '.pdf');
    }
}