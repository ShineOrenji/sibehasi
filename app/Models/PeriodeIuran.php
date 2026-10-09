<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

    class PeriodeIuran extends Model
    {
        protected $table = 'periode_iuran';
        protected $fillable = ['kategori_id', 'nama_periode', 'nominal', 'tanggal_mulai', 'deadline_pembayaran', 'status'];

        public function kategori()
        {
            return $this->belongsTo(KategoriTransaksi::class, 'kategori_id');
        }

        // Untuk menghitung siapa saja yang sudah bayar
        public function pembayaran()
        {
            return $this->hasMany(PembayaranIuran::class, 'periode_id');
        }
    }