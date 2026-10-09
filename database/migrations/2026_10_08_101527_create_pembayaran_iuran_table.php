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
        Schema::create('pembayaran_iuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->foreignId('periode_id')->constrained('periode_iuran')->cascadeOnDelete();
            $table->unsignedBigInteger('nominal_bayar');
            $table->dateTime('tanggal_bayar')->nullable();
            $table->enum('metode_pembayaran', ['cash', 'online']);
            $table->enum('status', ['pending', 'sukses', 'gagal', 'kadaluarsa'])->default('pending');
            
            // Data dari Midtrans
            $table->string('midtrans_order_id')->unique()->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            
            // Admin yang mencatat jika bayar cash (Set null jika admin dihapus, transaksi tetap aman)
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_iuran');
    }
};
