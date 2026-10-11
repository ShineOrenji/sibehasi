<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

    class PeriodeIuran extends Model
    {
        use HasFactory, SoftDeletes;

        protected $table = 'periode_iuran';
        protected $fillable = ['nama_periode', 'nominal', 'denda', 'deadline_pembayaran', 'status', 'kategori_id'];

        public function kategori()
        {
            return $this->belongsTo(KategoriTransaksi::class, 'kategori_id');
        }

        // Untuk menghitung siapa saja yang sudah bayar
        public function pembayaran()
        {
            return $this->hasMany(PembayaranIuran::class, 'periode_id');
        }

        // Fungsi ajaib untuk menghitung denda otomatis
        public function getNominalAkhirAttribute()
        {
            // PADA BAGIAN INI KITA GUNAKAN deadline_pembayaran
            if ($this->deadline_pembayaran && now()->startOfDay()->greaterThan($this->deadline_pembayaran) && $this->denda > 0) {
                return $this->nominal + $this->denda;
            }
            return $this->nominal;
        }
    }