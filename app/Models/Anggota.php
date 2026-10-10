<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggota';
    protected $fillable = ['user_id', 'nim', 'angkatan', 'no_hp', 'status_aktif'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembayaran_iuran()
    {
        return $this->hasMany(PembayaranIuran::class);
    }
}
