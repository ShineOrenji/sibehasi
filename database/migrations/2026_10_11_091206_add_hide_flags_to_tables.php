<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('buku_kas', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('admin_id');
        });
        Schema::table('pembayaran_iuran', function (Blueprint $table) {
            $table->boolean('is_hidden_mhs')->default(false)->after('status');
        });
    }
    public function down(): void {
        Schema::table('buku_kas', function (Blueprint $table) { $table->dropColumn('is_hidden'); });
        Schema::table('pembayaran_iuran', function (Blueprint $table) { $table->dropColumn('is_hidden_mhs'); });
    }
};