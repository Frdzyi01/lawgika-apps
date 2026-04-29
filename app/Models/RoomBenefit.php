<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomBenefit extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'paket',
        'total_minutes',
        'used_minutes',
        'type',
        'is_active',
        'expired_at',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'expired_at' => 'datetime',
    ];

    // ── Eligible PT packages ──────────────────────────────────────────────────

    /** PT package names that qualify for room benefits */
    public const ELIGIBLE_PACKAGES = ['eksklusif', 'enterprise'];

    /**
     * Check whether a service/package name is eligible for a room benefit.
     */
    public static function isEligiblePackage(string $packageName): bool
    {
        $normalized = strtolower(trim($packageName));
        foreach (self::ELIGIBLE_PACKAGES as $pkg) {
            if (str_contains($normalized, $pkg)) {
                return true;
            }
        }
        return false;
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(RoomBenefitLog::class, 'benefit_id');
    }

    // ── Computed attributes ───────────────────────────────────────────────────

    /** Remaining minutes (never negative) */
    public function getRemainingMinutesAttribute(): int
    {
        return max(0, $this->total_minutes - $this->used_minutes);
    }

    /** Is this benefit expired? */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expired_at && now()->greaterThan($this->expired_at);
    }

    /**
     * Detect whether there is an open (un-matched) check-in log.
     *
     * A session is "active" when the most recent log entry (across ALL room types)
     * has action = 'checkin' — meaning admin checked in but has not yet checked out.
     */
    public function getHasActiveSessionAttribute(): bool
    {
        $lastLog = $this->logs()->latest('action_at')->first();
        return $lastLog && $lastLog->action === 'checkin';
    }

    /**
     * Dynamic status — computed from live data, NEVER stored as a column.
     *
     * Priority order:
     *   1. Expired  → if now() > expired_at
     *   2. Selesai  → if used_minutes >= total_minutes
     *   3. Sedang Digunakan → if there's an open check-in (no matching checkout)
     *   4. Siap Digunakan   → default ready state
     *   5. Nonaktif → if is_active = false (safety fallback)
     */
    public function getStatusLabelAttribute(): string
    {
        if (!$this->is_active)               return 'Nonaktif';
        if ($this->is_expired)               return 'Expired';
        if ($this->used_minutes >= $this->total_minutes) return 'Selesai';
        if ($this->has_active_session)       return 'Sedang Digunakan';
        return 'Siap Digunakan';
    }

    /**
     * Badge Bootstrap color class matching the dynamic status.
     *
     * Siap Digunakan  → info  (blue)
     * Sedang Digunakan → warning (orange)
     * Selesai          → success (green)
     * Expired          → danger (red)
     * Nonaktif         → secondary (grey)
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status_label) {
            'Siap Digunakan'   => 'info',
            'Sedang Digunakan' => 'warning',
            'Selesai'          => 'success',
            'Expired'          => 'danger',
            default            => 'secondary',
        };
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /** Format minutes as "X jam Y menit" */
    public static function formatMinutes(int $minutes): string
    {
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return "{$h} jam {$m} menit";
    }

    /** Active + non-expired scope */
    public function scopeUsable($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('expired_at')
                           ->orWhere('expired_at', '>', now());
                     });
    }
}
