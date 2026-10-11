<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PembayaranCashController;
use App\Http\Controllers\Admin\BukuKasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Mahasiswa\PaymentController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () { return view('welcome'); });

// DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// MAHASISWA
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'mahasiswa'])->name('dashboard');
    Route::post('/pay', [PaymentController::class, 'pay'])->name('pay');
    Route::get('/riwayat', function () { return view('mahasiswa.riwayat'); })->name('riwayat');
    Route::post('/riwayat/hapus-semua', function () {
        \App\Models\PembayaranIuran::where('anggota_id', \Illuminate\Support\Facades\Auth::user()->anggota->id)->update(['is_hidden_mhs' => true]);
        return back()->with('success', 'Semua riwayat dibersihkan.');
    })->name('riwayat.destroyAll');
    Route::delete('/riwayat/{id}', function ($id) {
        \App\Models\PembayaranIuran::where('id', $id)->update(['is_hidden_mhs' => true]);
        return back()->with('success', 'Riwayat berhasil disembunyikan.');
    })->name('riwayat.destroy');
});

// ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::get('/pembayaran-cash', [PembayaranCashController::class, 'create'])->name('pembayaran-cash.create');
    Route::post('/pembayaran-cash', [PembayaranCashController::class, 'store'])->name('pembayaran-cash.store');
    
    Route::get('/buku-kas', [BukuKasController::class, 'index'])->name('kas.index');
    Route::post('/buku-kas/hapus-semua', [BukuKasController::class, 'destroyAll'])->name('kas.destroyAll');
    Route::delete('/buku-kas/{id}', [BukuKasController::class, 'destroy'])->name('kas.destroy');

    Route::get('/pengeluaran', [\App\Http\Controllers\Admin\PengeluaranController::class, 'create'])->name('pengeluaran.create');
    Route::post('/pengeluaran', [\App\Http\Controllers\Admin\PengeluaranController::class, 'store'])->name('pengeluaran.store');

    Route::get('/periode', [\App\Http\Controllers\Admin\PeriodeIuranController::class, 'index'])->name('periode.index');
    Route::post('/periode', [\App\Http\Controllers\Admin\PeriodeIuranController::class, 'store'])->name('periode.store');
    Route::post('/periode/{id}/update', [\App\Http\Controllers\Admin\PeriodeIuranController::class, 'update'])->name('periode.update');
    Route::delete('/periode/{id}', [\App\Http\Controllers\Admin\PeriodeIuranController::class, 'destroy'])->name('periode.destroy');
    Route::post('/periode/{id}/toggle', [\App\Http\Controllers\Admin\PeriodeIuranController::class, 'toggleStatus'])->name('periode.toggle');
    Route::get('/periode/{id}/pembayar', [\App\Http\Controllers\Admin\PeriodeIuranController::class, 'show'])->name('periode.show');

    Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [\App\Http\Controllers\Admin\LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [\App\Http\Controllers\Admin\LaporanController::class, 'exportExcel'])->name('laporan.excel');
    
    Route::get('/kategori', [\App\Http\Controllers\Admin\KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [\App\Http\Controllers\Admin\KategoriController::class, 'store'])->name('kategori.store');
    Route::delete('/kategori/{id}', [\App\Http\Controllers\Admin\KategoriController::class, 'destroy'])->name('kategori.destroy');
});

// KONFIRMASI (ADMIN)
Route::middleware(['auth'])->group(function () {
    Route::get('/konfirmasi-pembayaran', [\App\Http\Controllers\Admin\KonfirmasiController::class, 'index'])->name('admin.konfirmasi.index');
    Route::post('/konfirmasi-pembayaran/{id}/acc', [\App\Http\Controllers\Admin\KonfirmasiController::class, 'acc'])->name('admin.konfirmasi.acc');
    Route::post('/konfirmasi-pembayaran/{id}/tolak', [\App\Http\Controllers\Admin\KonfirmasiController::class, 'tolak'])->name('admin.konfirmasi.tolak');
});

// KELOLA AKUN
Route::middleware(['auth'])->group(function () {
    Route::get('/kelola-akun', [\App\Http\Controllers\Admin\AkunController::class, 'index'])->name('admin.akun.index');
    Route::post('/kelola-akun', [\App\Http\Controllers\Admin\AkunController::class, 'store'])->name('admin.akun.store');
    Route::get('/kelola-akun/{id}/edit', [\App\Http\Controllers\Admin\AkunController::class, 'edit'])->name('admin.akun.edit');
    Route::put('/kelola-akun/{id}', [\App\Http\Controllers\Admin\AkunController::class, 'update'])->name('admin.akun.update');
    Route::delete('/kelola-akun/{id}', [\App\Http\Controllers\Admin\AkunController::class, 'destroy'])->name('admin.akun.destroy');
    Route::post('/kelola-akun/{id}/reset', [\App\Http\Controllers\Admin\AkunController::class, 'resetPassword'])->name('admin.akun.reset');
});

// KETUA & LAINNYA
Route::middleware(['auth', 'role:ketua'])->prefix('ketua')->name('ketua.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'ketua'])->name('dashboard');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/notifikasi/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifikasi.read');
    Route::delete('/notifikasi/{id}', [NotificationController::class, 'destroy'])->name('notifikasi.destroy');
});
require __DIR__.'/auth.php';