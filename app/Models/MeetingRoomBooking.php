<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingRoomBooking extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'date',
        'start_time',
        'duration',
        'participants',
        'status',
        'checkin_at',
        'checkout_at',
        'total_used_minutes',
        'total_used_seconds',
        'payment_proof',
        'payment_status',
    ];

    protected $casts = [
        'date'       => 'date',
        'checkin_at' => 'datetime',
        'checkout_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper: Format detik ke string "X jam Y menit Z detik"
     */
    public function formatSeconds($seconds)
    {
        $seconds = (int) max(0, floor($seconds));

        $hours   = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs    = $seconds % 60;

        return "{$hours} jam {$minutes} menit {$secs} detik";
    }

    /**
     * Accessor: Detik yang sudah terpakai
     */
    public function getUsedSecondsAttribute()
    {
        // Migrasi fallback: jika total_used_seconds 0 tapi total_used_minutes ada
        $used = $this->total_used_seconds > 0 ? $this->total_used_seconds : ($this->total_used_minutes * 60);

        if ($this->status === 'checkin' && $this->checkin_at) {
            $used += $this->checkin_at->diffInSeconds(now());
        }

        return $used;
    }

    /**
     * Accessor: Sisa detik (tidak pernah negatif)
     */
    public function getRemainingSecondsAttribute()
    {
        $totalSeconds = (int) ($this->duration * 3600);
        return max(0, $totalSeconds - $this->used_seconds);
    }

    /**
     * Accessor: Apakah masa berlaku (1 tahun) sudah habis?
     */
    public function getIsExpiredAttribute()
    {
        return now()->greaterThan($this->created_at->addYear());
    }

    /**
     * Accessor: Waktu terpakai string format
     */
    public function getFormattedUsedTimeAttribute()
    {
        return $this->formatSeconds($this->used_seconds);
    }

    /**
     * Accessor: Sisa waktu dalam format string
     */
    public function getFormattedRemainingTimeAttribute()
    {
        if ($this->is_expired) {
            return 'Expired';
        }

        $remaining = $this->remaining_seconds;

        if ($remaining <= 0) {
            return 'Waktu habis';
        }

        return $this->formatSeconds($remaining);
    }
}
