<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\WorkLog;

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

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function workLogs()
    {
        return $this->hasMany(\App\Models\WorkLog::class);
    }

    public function latestWorkLog()
    {
        return $this->hasOne(WorkLog::class)->latestOfMany('jam_mulai');
    }

}
