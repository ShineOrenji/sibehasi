<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AkunController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'mahasiswa') abort(403);
        
        // Tampilkan akun Mahasiswa/Anggota dan Ketua (Admin tidak ditampilkan agar tidak tak sengaja dihapus)
        $users = User::with('anggota')->whereIn('role', ['ketua', 'mahasiswa'])->orderBy('name', 'asc')->get();
        return view('admin.akun.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'nim' => 'nullable|string|min:7|max:8|unique:anggota,nim',
            'angkatan' => 'required|integer',
            'role' => 'required|in:admin,mahasiswa,ketua',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Anggota::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'angkatan' => $request->angkatan,
        ]);

        return back()->with('success', 'Akun berhasil dibuat!');
    }

    public function edit($id)
    {
        $user = \App\Models\User::findOrFail($id); 
        $akun = $user; 
        return view('admin.akun.edit', compact('user', 'akun'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nim' => 'nullable|string|min:7|max:8|unique:anggota,nim,' . $user->id . ',user_id',
            'angkatan' => 'required|integer',
            'role' => 'required|in:mahasiswa,ketua',
        ]);

        // 1. Update data akun login
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // 2. Update ATAU Buat Baru data profil anggota (Penyelamat untuk akun lama)
        \App\Models\Anggota::updateOrCreate(
            ['user_id' => $user->id], // Cari berdasarkan user_id ini
            [
                'nim' => $request->nim,       // Jika ketemu (atau baru), isi NIM ini
                'angkatan' => $request->angkatan, // Isi Angkatan ini
            ]
        );

        return redirect()->route('admin.akun.index')->with('success', 'Data akun berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::with('anggota')->findOrFail($id);
        
        // PROTEKSI KEUANGAN: Cek apakah user ini sudah pernah bayar kas
        if ($user->anggota && \App\Models\PembayaranIuran::where('anggota_id', $user->anggota->id)->where('status', 'sukses')->exists()) {
            return back()->with('error', 'Gagal! Akun ini tidak bisa dihapus karena sudah memiliki riwayat pembayaran LUNAS. (Data keuangan harus permanen).');
        }

        // Hapus data
        if ($user->anggota) {
            $user->anggota()->delete();
        }
        $user->delete();

        return back()->with('success', 'Akun berhasil dihapus permanen.');
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate(['password' => 'required|min:8']);
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make($request->password)]);
        
        return back()->with('success', 'Password untuk ' . $user->name . ' berhasil direset!');
    }
}