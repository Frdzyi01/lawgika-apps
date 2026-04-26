<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotaLog extends Model
{
    protected $fillable = [
        'user_id',
        'room_type',
        'duration',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];
}
