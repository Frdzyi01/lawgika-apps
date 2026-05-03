<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingRoomPackage extends Model
{
    protected $table = 'meeting_room_packages';

    protected $guarded = ['id'];

    protected $casts = [
        'expired_at' => 'datetime',
        'last_used_date' => 'datetime',
    ];
}
