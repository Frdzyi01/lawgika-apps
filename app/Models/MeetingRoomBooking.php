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
     * Helper: Format menit ke string "X jam Y menit"
     */
    public function formatMinutes($minutes)
    {
        $minutes = (int) max(0, floor($minutes));

        $hours = intdiv($minutes, 60);
        $mins  = $minutes % 60;

        if ($hours > 0 && $mins > 0) {
            return "{$hours} jam {$mins} menit";
        } elseif ($hours > 0) {
            return "{$hours} jam";
        } else {
            return "{$mins} menit";
        }
    }

    /**
     * Accessor: Menit yang sudah terpakai (termasuk sesi yg sedang berjalan)
     * Menggunakan raw total_used_minutes dari DB + sesi aktif jika sedang checkin
     */
    public function getUsedMinutesAttribute()
    {
        $used = (int) $this->total_used_minutes;

        if ($this->status === 'checkin' && $this->checkin_at) {
            $used += (int) floor($this->checkin_at->diffInSeconds(now()) / 60);
        }

        return $used;
    }

    /**
     * Accessor: Sisa menit (tidak pernah negatif)
     */
    public function getRemainingMinutesAttribute()
    {
        $totalMinutes = (int) ($this->duration * 60);
        return max(0, $totalMinutes - $this->used_minutes);
    }

    /**
     * Accessor: Sisa waktu dalam format string
     */
    public function getFormattedRemainingTimeAttribute()
    {
        $remaining = $this->remaining_minutes;

        if ($remaining <= 0) {
            return 'Waktu habis';
        }

        return $this->formatMinutes($remaining);
    }
}
