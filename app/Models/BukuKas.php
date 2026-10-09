<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuKas extends Model
{
    protected $table = 'buku_kas';
    protected $fillable = [
        'kategori_id', 'jenis', 'nominal', 'tanggal_transaksi', 
        'keterangan', 'pembayaran_iuran_id', 'admin_id'
    ];

    public function kategori() { return $this->belongsTo(KategoriTransaksi::class); }
    public function admin() { return $this->belongsTo(User::class, 'admin_id'); }
    public function pembayaran() { return $this->belongsTo(PembayaranIuran::class, 'pembayaran_iuran_id'); }
}
