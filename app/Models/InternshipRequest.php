<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipRequest extends Model
{
    protected $fillable = [
        'nama_pengaju',
        'email_pengaju',
        'no_wa',
        'tipe',
        'instansi',
        'surat_pengantar',
        'status',
        'alasan_penolakan',
        'tanggal_mulai',
        'tanggal_selesai',
    ];
}
