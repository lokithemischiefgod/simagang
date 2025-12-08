<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'status',
        'jam_masuk',
        'jam_keluar',
        'keterangan',
    ];

    // Relasi ke user (peserta)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
