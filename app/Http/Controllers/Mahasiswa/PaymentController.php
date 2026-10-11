<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PembayaranIuran;
use App\Models\PeriodeIuran;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        // Validasi input upload SS
        $request->validate([
            'periode_id' => 'required|exists:periode_iuran,id',
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        $periode = PeriodeIuran::findOrFail($request->periode_id);

        // Upload File Bukti dengan Hash
        $file = $request->file('bukti_bayar');
        $nama_file = md5(time() . '_' . uniqid()) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/bukti'), $nama_file);

        // Simpan data (TAMBAHKAN NOMINAL BAYAR DI SINI)
        PembayaranIuran::create([
            'periode_id' => $periode->id,
            'anggota_id' => Auth::user()->anggota->id,
            'nominal_bayar' => $periode->nominal_akhir,
            'tanggal_bayar' => now(),
            'metode_pembayaran' => 'qris',
            'status' => 'pending',
            'bukti_bayar' => $nama_file,
        ]);

        return back()->with('success', 'Bukti transfer berhasil diunggah! Menunggu konfirmasi dari Admin.');
    }
}