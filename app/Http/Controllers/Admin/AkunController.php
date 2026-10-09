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
        // Fitur ini hanya boleh diakses Admin atau Ketua
        if (Auth::user()->role === 'mahasiswa') abort(403);
        
        $users = User::with('anggota')->where('role', 'mahasiswa')->orderBy('name', 'asc')->get();
        return view('admin.akun.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'nim' => 'required|string|min:7|max:8|unique:anggota,nim', // Validasi 7-8 digit
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        \App\Models\Anggota::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'status_aktif' => 'aktif',
        ]);

        return back()->with('success', 'Akun mahasiswa berhasil dibuat!');
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate(['password' => 'required|min:8']);
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make($request->password)]);
        
        return back()->with('success', 'Password untuk ' . $user->name . ' berhasil direset!');
    }
}
