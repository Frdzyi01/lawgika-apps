<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRoomQuota extends Model
{
    protected $fillable = [
        'user_id',
        'total_seconds',
        'used_seconds',
        'remaining_seconds',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formatSeconds($seconds)
    {
        $seconds = (int) max(0, floor($seconds));
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds % 60;
        return "{$h} jam {$m} menit {$s} detik";
    }

    public function getFormattedTotalTimeAttribute()
    {
        return $this->formatSeconds($this->total_seconds);
    }

    public function getFormattedUsedTimeAttribute()
    {
        return $this->formatSeconds($this->used_seconds);
    }

    public function getFormattedRemainingTimeAttribute()
    {
        return $this->formatSeconds($this->remaining_seconds);
    }
}
