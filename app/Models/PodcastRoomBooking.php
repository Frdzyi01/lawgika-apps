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
        'checkin_at', 'checkout_at', 'total_used_minutes', 'total_used_seconds',
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

    /** Total quota in seconds */
    public function getTotalSecondsAttribute(): int
    {
        return (int) $this->duration * 3600;
    }

    /** Seconds already used (including active session if checked-in) */
    public function getUsedSecondsAttribute(): int
    {
        // Migrasi fallback: jika total_used_seconds 0 tapi total_used_minutes ada
        $used = $this->total_used_seconds > 0 ? $this->total_used_seconds : ($this->total_used_minutes * 60);

        if ($this->status === 'checkin' && $this->checkin_at) {
            $used += $this->checkin_at->diffInSeconds(now());
        }

        return $used;
    }

    /** Remaining seconds */
    public function getRemainingSecondsAttribute(): int
    {
        return max(0, $this->total_seconds - $this->used_seconds);
    }

    /**
     * Accessor: Apakah masa berlaku (1 tahun) sudah habis?
     */
    public function getIsExpiredAttribute()
    {
        return now()->greaterThan($this->created_at->addYear());
    }

    /** Human-readable used time */
    public function getFormattedUsedTimeAttribute(): string
    {
        return $this->formatSeconds($this->used_seconds);
    }

    /** Human-readable remaining time */
    public function getFormattedRemainingTimeAttribute(): string
    {
        if ($this->is_expired) {
            return 'Expired';
        }

        $rem = $this->remaining_seconds;
        if ($rem <= 0) return 'Waktu habis';
        
        return $this->formatSeconds($rem);
    }

    /** Format helper for seconds → human */
    public function formatSeconds(int $seconds): string
    {
        $seconds = (int) max(0, floor($seconds));
        $h = (int) floor($seconds / 3600);
        $m = (int) floor(($seconds % 3600) / 60);
        $s = $seconds % 60;
        return "{$h} jam {$m} menit {$s} detik";
    }
}
