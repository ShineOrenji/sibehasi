<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Anggota;
use App\Models\KategoriTransaksi;
use App\Models\PeriodeIuran;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin
        User::create([
            'name' => 'Admin Bendahara',
            'email' => 'admin@sibehasi.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Buat User Ketua
        User::create([
            'name' => 'Ketua HIMASI',
            'email' => 'ketua@sibehasi.com',
            'password' => Hash::make('password'),
            'role' => 'ketua',
        ]);

        // 3. Buat User Mahasiswa + Profil Anggota
        $mahasiswa = User::create([
            'name' => 'Fahmi Mahasiswa',
            'email' => 'fahmi@sibehasi.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        Anggota::create([
            'user_id' => $mahasiswa->id,
            'nim' => '24050003',
            'angkatan' => '2024',
            'no_hp' => '08123456789',
        ]);

        // 4. Buat Master Kategori Transaksi
        KategoriTransaksi::create(['nama_kategori' => 'Iuran Anggota', 'jenis' => 'pemasukan']);
        KategoriTransaksi::create(['nama_kategori' => 'Dana Sponsor', 'jenis' => 'pemasukan']);
        KategoriTransaksi::create(['nama_kategori' => 'Konsumsi Rapat', 'jenis' => 'pengeluaran']);

        // 5. Buat Master Periode Iuran
        PeriodeIuran::create([
            'nama_periode' => 'Oktober 2026',
            'nominal' => 10000,
            'deadline_pembayaran' => '2026-10-31',
            'status' => 'aktif',
        ]);
    }
}
