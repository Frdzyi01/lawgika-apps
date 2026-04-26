<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomUsageLog extends Model
{
    protected $fillable = [
        'reservation_id',
        'room_type',
        'type',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];
}
