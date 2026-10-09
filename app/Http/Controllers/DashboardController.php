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
        $pemasukan = BukuKas::where('jenis', 'pemasukan')->sum('nominal');
        $pengeluaran = BukuKas::where('jenis', 'pengeluaran')->sum('nominal');
        $saldo = $pemasukan - $pengeluaran;
        $totalAnggota = Anggota::count();
        
        $transaksiTerbaru = BukuKas::with('kategori')->orderBy('created_at', 'desc')->limit(5)->get();

        return view('dashboard.admin', compact('saldo', 'pemasukan', 'pengeluaran', 'totalAnggota', 'transaksiTerbaru'));
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