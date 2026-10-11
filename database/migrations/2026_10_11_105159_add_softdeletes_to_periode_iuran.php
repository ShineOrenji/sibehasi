<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('periode_iuran', function (Blueprint $table) {
            $table->softDeletes(); // Ini kolom ajaibnya
        });
    }
    public function down(): void {
        Schema::table('periode_iuran', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};