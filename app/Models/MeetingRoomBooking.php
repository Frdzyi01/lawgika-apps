<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingRoomBooking extends Model
{
    protected $fillable = [
        'user_id',
        'source_type',
        'benefit_id',
        'name',
        'date',
        'start_time',
        'end_time',
        'duration',
        'participants',
        'status',
        'checkin_at',
        'checkout_at',
        'total_used_minutes',
        'total_used_seconds',
        'payment_proof',
        'payment_status',
        'nama_perusahaan',
        'email',
        'alamat_usaha',
        'bidang_usaha',
        'keperluan',
        'room_name',
        'notes',
        'created_by',
        'payment_method',
    ];

    protected $casts = [
        'date'       => 'date',
        'checkin_at' => 'datetime',
        'checkout_at' => 'datetime',
        'end_time'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function benefit()
    {
        return $this->belongsTo(\App\Models\RoomBenefit::class, 'benefit_id');
    }

    public function meetingRoomPackage()
    {
        return $this->belongsTo('App\Models\MeetingRoomPackage', 'meeting_room_package_id');
    }

    /**
     * Helper: Format detik ke string "X jam Y menit" (tanpa detik)
     */
    public function formatSeconds($seconds)
    {
        $seconds = (int) max(0, floor($seconds));

        $hours   = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        return "{$hours} jam {$minutes} menit";
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

    /**
     * Calculate billing duration based on booking start time (jam mulai ditentukan admin)
     * and rounded-up checkout time (ceiling ke jam berikutnya).
     *
     * @param \Carbon\Carbon|null $checkinAt Actual checkin timestamp
     * @param \Carbon\Carbon|null $checkoutAt Actual checkout timestamp
     * @return array
     */
    public function calculateBillingDuration(?\Carbon\Carbon $checkinAt = null, ?\Carbon\Carbon $checkoutAt = null): array
    {
        $checkin  = $checkinAt ?? $this->checkin_at ?? now();
        $checkout = $checkoutAt ?? $this->checkout_at ?? now();

        $bookingDate = $this->date ? \Carbon\Carbon::parse($this->date)->format('Y-m-d') : $checkin->format('Y-m-d');

        // Jam Mulai: Selalu gunakan jam mulai booking yang ditentukan admin jika tersedia
        $startCarbon = $this->start_time
            ? \Carbon\Carbon::parse($bookingDate . ' ' . \Carbon\Carbon::parse($this->start_time)->format('H:i:s'))
            : $checkin->copy();

        // Jam Selesai: Bulatkan ke atas ke jam berikutnya (.00) jika ada kelebihan menit/detik
        $outH = (int) $checkout->format('H');
        $outM = (int) $checkout->format('i');
        $outS = (int) $checkout->format('s');

        if ($outM > 0 || $outS > 0) {
            $roundedCheckout = $checkout->copy()->startOfHour()->addHour();
        } else {
            $roundedCheckout = $checkout->copy()->startOfHour();
        }

        // Hitung selisih jam dari Jam Mulai Booking sampai Jam Checkout yang sudah dibulatkan
        $diffSeconds  = $startCarbon->diffInSeconds($roundedCheckout, false);
        $billingHours = max(1, (int) ceil($diffSeconds / 3600));

        return [
            'start_carbon'     => $startCarbon,
            'rounded_checkout' => $roundedCheckout,
            'billing_hours'    => $billingHours,
            'billing_seconds'  => $billingHours * 3600,
        ];
    }

    /**
     * Calculate rounded-up duration in hours for billing purposes.
     * Rounds up to nearest hour with minimum of 1 hour.
     * 
     * @param int $durationSeconds Duration in seconds
     * @return int Rounded hours (minimum 1)
     */
    public function calculateBillingHours(int $durationSeconds): int
    {
        if ($durationSeconds <= 0) {
            return 1; // Minimum 1 hour
        }

        $durationMinutes = $durationSeconds / 60;
        $durationHours = (int) ceil($durationMinutes / 60);

        return max(1, $durationHours); // Enforce minimum 1 hour
    }
}
