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

    // ── Eligibility Rules ─────────────────────────────────────────────────────

    /**
     * Package slugs that qualify for room benefits.
     * Only these two packages are allowed — for ANY service.
     * (The service must also match ELIGIBLE_SERVICES below.)
     */
    public const ELIGIBLE_PACKAGES = ['eksklusif', 'enterprise'];

    /**
     * Service slugs / name fragments that are eligible.
     * Benefit ONLY applies to "Pendirian PT" (reguler).
     * PT Perorangan, CV, Yayasan, Firma, PT PMA → NOT eligible.
     */
    public const ELIGIBLE_SERVICES = [
        'pendirian-pt',    // slug in orders.form_data['service']
        'pendirian pt',    // human-readable substring in service_name
    ];

    /**
     * ✅  CANONICAL eligibility check — use this everywhere.
     *
     * An order is eligible only when:
     *   - service is exactly "Pendirian PT" (not PT Perorangan / CV / etc.), AND
     *   - package is "Eksklusif" or "Enterprise"
     *
     * Accepts an Order model and reads form_data + service_name.
     */
    public static function isEligibleForOrder(\App\Models\Order $order): bool
    {
        $formData = $order->form_data ?? [];

        // ── 1. Resolve package slug ───────────────────────────────────────────
        $packageSlug = strtolower(trim($formData['package'] ?? ''));  // "eksklusif"

        // ── 2. Resolve service slug ───────────────────────────────────────────
        $serviceSlug = strtolower(trim($formData['service'] ?? ''));  // "pendirian-pt"
        $serviceName = strtolower(trim(
            $order->service_name ?? ($order->service?->name ?? '')
        ));  // "pendirian pt – paket eksklusif"

        // ── 3. Check package ──────────────────────────────────────────────────
        $packageOk = self::isEligiblePackage($packageSlug);
        if (!$packageOk) return false;

        // ── 4. Check service: must be exactly "pendirian-pt" slug ─────────────
        //      or the service_name contains "pendirian pt" but NOT followed by
        //      "perorangan", "pma", "cv", "yayasan", "firma".
        $serviceOk = self::isEligibleService($serviceSlug, $serviceName);

        return $serviceOk;
    }

    /**
     * Check if package slug is in the allowed list.
     * (Used internally — prefer isEligibleForOrder() for full validation.)
     */
    public static function isEligiblePackage(string $packageSlug): bool
    {
        $normalized = strtolower(trim($packageSlug));
        foreach (self::ELIGIBLE_PACKAGES as $pkg) {
            if (str_contains($normalized, $pkg)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if service slug/name refers to "Pendirian PT" (reguler) only.
     * Explicitly rejects: pt-perorangan, pt-pma, cv, yayasan, firma.
     */
    public static function isEligibleService(string $serviceSlug, string $serviceName = ''): bool
    {
        // Rejected slugs — regardless of package
        $rejectedSlugs = [
            'pt-perorangan', 'pendirian-pt-perorangan',
            'pt-pma',        'pendirian-pt-pma',
            'cv',            'pendirian-cv',
            'yayasan',       'pendirian-yayasan',
            'firma',         'pendirian-firma',
        ];

        if (in_array($serviceSlug, $rejectedSlugs, true)) {
            return false;
        }

        // Allowed slug: exactly "pendirian-pt"
        if ($serviceSlug === 'pendirian-pt') {
            return true;
        }

        // Fallback: check service_name string for legacy orders without form_data
        // Must contain "pendirian pt" but NOT contain disqualifying words
        if (str_contains($serviceName, 'pendirian pt')) {
            $disqualifiers = ['perorangan', 'pma', ' cv', 'yayasan', 'firma'];
            foreach ($disqualifiers as $d) {
                if (str_contains($serviceName, $d)) {
                    return false;
                }
            }
            return true;
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
