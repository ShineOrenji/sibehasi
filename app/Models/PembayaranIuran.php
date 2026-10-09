<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranIuran extends Model
{
    protected $table = 'pembayaran_iuran';
    protected $fillable = [
        'anggota_id', 'periode_id', 'nominal_bayar', 'tanggal_bayar', 
        'metode_pembayaran', 'status', 'midtrans_order_id', 
        'midtrans_transaction_id', 'admin_id'
    ];

    public function anggota() { return $this->belongsTo(Anggota::class); }
    public function periode() { return $this->belongsTo(PeriodeIuran::class); }
    public function admin() { return $this->belongsTo(User::class, 'admin_id'); }
}
