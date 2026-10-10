<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PembayaranCashController;
use App\Http\Controllers\Admin\BukuKasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Mahasiswa\PaymentController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

// =============================
// ROUTER UTAMA DASHBOARD (Otomatis redirect sesuai role)
// MENGGANTIKAN DASHBOARD BAWAAN BREEZE
// =============================
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// =============================
// DASHBOARD MAHASISWA
// =============================
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'mahasiswa'])->name('dashboard');
    // Tambahkan cukup '/pay' dan name('pay')
    Route::post('/pay', [App\Http\Controllers\Mahasiswa\PaymentController::class, 'pay'])->name('pay');
    // Tambahkan di dalam route group Mahasiswa:
    Route::post('/mahasiswa/pay', [PaymentController::class, 'pay'])->name('mahasiswa.pay');
    // Tambahkan di luar grup (di bagian paling bawah file, bisa diakses publik oleh Midtrans)
    Route::post('/midtrans/webhook', [WebhookController::class, 'handle']);
});

// =============================
// ROUTE KHUSUS ADMIN (Digabungkan dengan Phase 3)
// =============================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin (Baru Phase 4)
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Fitur Pembayaran Cash (Lanjutan Phase 3)
    Route::get('/pembayaran-cash', [PembayaranCashController::class, 'create'])->name('pembayaran-cash.create');
    Route::post('/pembayaran-cash', [PembayaranCashController::class, 'store'])->name('pembayaran-cash.store');

    // Fitur Buku Kas / Riwayat Keuangan (Lanjutan Phase 3)
    Route::get('/buku-kas', [BukuKasController::class, 'index'])->name('kas.index');

    // Fitur Kelola Periode Iuran
    Route::get('/periode', [\App\Http\Controllers\Admin\PeriodeIuranController::class, 'index'])->name('periode.index');
    Route::post('/periode', [\App\Http\Controllers\Admin\PeriodeIuranController::class, 'store'])->name('periode.store');
    Route::post('/periode/{id}/update', [\App\Http\Controllers\Admin\PeriodeIuranController::class, 'update'])->name('periode.update');
    Route::delete('/periode/{id}', [\App\Http\Controllers\Admin\PeriodeIuranController::class, 'destroy'])->name('periode.destroy');
    Route::post('/periode/{id}/toggle', [\App\Http\Controllers\Admin\PeriodeIuranController::class, 'toggleStatus'])->name('periode.toggle');
    Route::get('/pengeluaran', [\App\Http\Controllers\Admin\PengeluaranController::class, 'create'])->name('pengeluaran.create');
    Route::post('/pengeluaran', [\App\Http\Controllers\Admin\PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::get('/periode/{id}/pembayar', [\App\Http\Controllers\Admin\PeriodeIuranController::class, 'show'])->name('periode.show');

    // Fitur Laporan Keuangan
    Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [\App\Http\Controllers\Admin\LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    
    // Fitur Master Kategori
    Route::get('/kategori', [\App\Http\Controllers\Admin\KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [\App\Http\Controllers\Admin\KategoriController::class, 'store'])->name('kategori.store');
    Route::delete('/kategori/{id}', [\App\Http\Controllers\Admin\KategoriController::class, 'destroy'])->name('kategori.destroy');
});

// =============================
// KELOLA AKUN (Bisa diakses Admin & Ketua)
// =============================
Route::middleware(['auth'])->group(function () {
    Route::get('/kelola-akun', [\App\Http\Controllers\Admin\AkunController::class, 'index'])->name('admin.akun.index');
    Route::post('/kelola-akun', [\App\Http\Controllers\Admin\AkunController::class, 'store'])->name('admin.akun.store');
    Route::get('/kelola-akun/{id}/edit', [\App\Http\Controllers\Admin\AkunController::class, 'edit'])->name('admin.akun.edit');
    Route::put('/kelola-akun/{id}', [\App\Http\Controllers\Admin\AkunController::class, 'update'])->name('admin.akun.update');
    Route::delete('/kelola-akun/{id}', [\App\Http\Controllers\Admin\AkunController::class, 'destroy'])->name('admin.akun.destroy');
    Route::post('/kelola-akun/{id}/reset', [\App\Http\Controllers\Admin\AkunController::class, 'resetPassword'])->name('admin.akun.reset');
});

// =============================
// DASHBOARD KETUA
// =============================
Route::middleware(['auth', 'role:ketua'])->prefix('ketua')->name('ketua.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'ketua'])->name('dashboard');
});

// =============================
// PROFILE BAWAAN BREEZE
// =============================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =============================
// NOTIFIKASI UMUM (Bisa diakses Admin & Mahasiswa)
// =============================
Route::middleware('auth')->group(function () {
    Route::post('/notifikasi/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifikasi.read');
    Route::delete('/notifikasi/{id}', [NotificationController::class, 'destroy'])->name('notifikasi.destroy');
});

require __DIR__.'/auth.php';