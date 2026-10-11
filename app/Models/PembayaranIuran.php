<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranIuran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_iuran';

    protected $fillable = [
        'periode_id', 
        'anggota_id', 
        'nominal_bayar',
        'tanggal_bayar', 
        'metode_pembayaran', 
        'status', 
        'admin_id',
        'bukti_bayar' 
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeIuran::class, 'periode_id')->withTrashed();
    }
}