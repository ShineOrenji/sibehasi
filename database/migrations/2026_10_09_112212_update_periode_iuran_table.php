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
        Schema::table('periode_iuran', function (Blueprint $table) {
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_transaksi')->after('id');
            $table->date('tanggal_mulai')->nullable()->after('nominal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
