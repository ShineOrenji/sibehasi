<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BukuKas;
use App\Models\Anggota;
use App\Models\PembayaranIuran;
use App\Models\PeriodeIuran;

class DashboardController extends Controller
{
    // Pengatur arah otomatis setelah login
    public function index()
    {
        $role = Auth::user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'ketua') return redirect()->route('ketua.dashboard');
        return redirect()->route('mahasiswa.dashboard');
    }

    public function admin()
    {
        // Hitung Total dari Buku Kas (Otomatis real-time)
        $totalPemasukan = \App\Models\BukuKas::where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = \App\Models\BukuKas::where('jenis', 'pengeluaran')->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Hitung Pemasukan Bulan Ini
        $pemasukanBulanIni = \App\Models\BukuKas::where('jenis', 'pemasukan')
                                ->whereMonth('tanggal_transaksi', now()->month)
                                ->whereYear('tanggal_transaksi', now()->year)
                                ->sum('nominal');

        // Generate Data untuk Grafik (7 Hari Terakhir)
        $chartDates = collect();
        $chartPemasukan = collect();
        $chartPengeluaran = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartDates->push(\Carbon\Carbon::parse($date)->translatedFormat('d M'));
            
            $chartPemasukan->push(\App\Models\BukuKas::where('jenis', 'pemasukan')->whereDate('tanggal_transaksi', $date)->sum('nominal'));
            $chartPengeluaran->push(\App\Models\BukuKas::where('jenis', 'pengeluaran')->whereDate('tanggal_transaksi', $date)->sum('nominal'));
        }

        return view('dashboard.admin', compact(
            'saldoAkhir', 'totalPemasukan', 'totalPengeluaran', 'pemasukanBulanIni',
            'chartDates', 'chartPemasukan', 'chartPengeluaran'
        ));
    }

    public function mahasiswa()
    {
        $anggota = Auth::user()->anggota;
        
        // Cek periode aktif
        $periodeAktif = PeriodeIuran::where('status', 'aktif')->first();
        
        // Cek status pembayaran bulan ini
        $statusIuran = null;
        if ($periodeAktif && $anggota) {
            $statusIuran = PembayaranIuran::where('anggota_id', $anggota->id)
                                          ->where('periode_id', $periodeAktif->id)
                                          ->first();
        }

        // Riwayat 5 pembayaran terakhir
        $riwayat = [];
        if ($anggota) {
            $riwayat = PembayaranIuran::with('periode')
                ->where('anggota_id', $anggota->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)->get();
        }

        return view('dashboard.mahasiswa', compact('periodeAktif', 'statusIuran', 'riwayat'));
    }

    public function ketua()
    {
        // Ketua melihat hal yang sama dengan admin, tapi read-only
        $pemasukan = BukuKas::where('jenis', 'pemasukan')->sum('nominal');
        $pengeluaran = BukuKas::where('jenis', 'pengeluaran')->sum('nominal');
        $saldo = $pemasukan - $pengeluaran;
        $totalAnggota = Anggota::count();
        $transaksiTerbaru = BukuKas::with('kategori')->orderBy('created_at', 'desc')->limit(5)->get();

        return view('dashboard.ketua', compact('saldo', 'pemasukan', 'pengeluaran', 'totalAnggota', 'transaksiTerbaru'));
    }
}