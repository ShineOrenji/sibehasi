<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin
        User::create([
            'name' => 'Admin Bendahara',
            'email' => 'admin@himasi.com',
            'password' => Hash::make('himasi26'),
            'role' => 'admin',
        ]);

        // 1. Buat User Admin 2
        User::create([
            'name' => 'Admin Bendahara 2',
            'email' => 'admin2@himasi.com',
            'password' => Hash::make('himasi26'),
            'role' => 'admin',
        ]);

        // 2. Buat User Ketua
        User::create([
            'name' => 'Ketua HIMASI',
            'email' => 'yokai@himasi.com',
            'password' => Hash::make('yokai123'),
            'role' => 'ketua',
        ]);
    }
}
