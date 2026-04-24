<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PodcastRoomBooking extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'name', 'podcast_title',
        'date', 'start_time', 'duration', 'participants', 'package',
        'total_price', 'payment_proof', 'payment_status', 'status',
        'checkin_at', 'checkout_at', 'total_used_minutes',
    ];

    protected $casts = [
        'date'       => 'date',
        'checkin_at' => 'datetime',
        'checkout_at'=> 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Total quota in minutes */
    public function getTotalMinutesAttribute(): int
    {
        return (int) $this->duration * 60;
    }

    /** Minutes already used (including active session if checked-in) */
    public function getUsedMinutesAttribute(): int
    {
        $used = (int) $this->total_used_minutes;
        if ($this->status === 'checkin' && $this->checkin_at) {
            $used += (int) floor($this->checkin_at->diffInSeconds(now()) / 60);
        }
        return $used;
    }

    /** Remaining minutes */
    public function getRemainingMinutesAttribute(): int
    {
        return max(0, $this->total_minutes - $this->used_minutes);
    }

    /** Human-readable remaining time */
    public function getFormattedRemainingTimeAttribute(): string
    {
        $rem = $this->remaining_minutes;
        if ($rem <= 0) return 'Waktu habis';
        $h = intdiv($rem, 60);
        $m = $rem % 60;
        return $h > 0 ? "{$h} jam {$m} menit" : "{$m} menit";
    }

    /** Format helper for minutes → human */
    public function formatMinutes(int $minutes): string
    {
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return $h > 0 ? "{$h} jam {$m} menit" : "{$m} menit";
    }
}
