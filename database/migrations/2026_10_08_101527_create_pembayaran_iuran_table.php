<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_iuran', function (Blueprint $table) {
            $table->id();
            // Relasi
            $table->foreignId('anggota_id')->constrained('anggota')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periode_iuran')->onDelete('cascade');
            
            // Detail Transaksi Bersih (Tanpa Midtrans)
            $table->integer('nominal_bayar');
            $table->dateTime('tanggal_bayar');
            $table->string('metode_pembayaran')->default('qris'); // 'qris' atau 'cash'
            $table->string('bukti_bayar')->nullable(); // Untuk menyimpan nama file foto SS
            $table->enum('status', ['pending', 'sukses', 'tolak'])->default('pending');
            
            // Admin yang mengkonfirmasi
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_iuran');
    }
};