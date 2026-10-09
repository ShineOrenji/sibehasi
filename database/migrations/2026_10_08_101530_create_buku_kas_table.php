<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku_kas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_transaksi')->restrictOnDelete();
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->unsignedBigInteger('nominal');
            $table->date('tanggal_transaksi');
            $table->text('keterangan')->nullable();
            
            // Jika pemasukan berasal dari iuran, link ke tabel pembayaran_iuran
            $table->foreignId('pembayaran_iuran_id')->nullable()->constrained('pembayaran_iuran')->nullOnDelete();
            
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_kas');
    }
};
