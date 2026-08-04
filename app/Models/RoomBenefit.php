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
        'renewal_reminder_h30_sent_at',
        'renewal_reminder_h7_sent_at',
        'renewal_reminder_expired_sent_at',
    ];

    protected $casts = [
        'is_active'                        => 'boolean',
        'expired_at'                       => 'datetime',
        'renewal_reminder_h30_sent_at'     => 'datetime',
        'renewal_reminder_h7_sent_at'      => 'datetime',
        'renewal_reminder_expired_sent_at' => 'datetime',
    ];

    // ── Eligibility Rules ─────────────────────────────────────────────────────

    /**
     * Package slugs that qualify for room benefits (Meeting 48 Jam + Podcast 12 Jam).
     * - 'enterprise'   → Bundling paket untuk PT, CV, Firma, Yayasan, PT PMA
     * - 'professional' → Bundling paket untuk PT Perorangan
     * - 'eksklusif'    → Legacy (lama), backward-compat
     */
    public const ELIGIBLE_PACKAGES = ['eksklusif', 'enterprise', 'professional'];

    /**
     * All service slugs eligible for room benefits.
     * Any service with Business Package (enterprise/professional) is now eligible.
     */
    public const ELIGIBLE_SERVICES = [
        'pendirian-pt',
        'pendirian-cv',
        'pendirian-firma',
        'pendirian-yayasan',
        'pendirian-pt-pma',
        'pt-perorangan',
        'pendirian-pt-perorangan',
        'virtual-office',
        // human-readable fallback substrings
        'pendirian pt',
        'pendirian cv',
        'pendirian firma',
        'pendirian yayasan',
        'pt perorangan',
        'virtual office',
    ];

    /**
     * ✅  CANONICAL eligibility check — use this everywhere.
     *
     * Eligible combinations:
     *   - virtual-office + premium   → Meeting Room 12 jam ONLY
     *   - Any service  + enterprise  → Meeting 48 jam + Podcast 12 jam
     *   - Any service  + professional → Meeting 48 jam + Podcast 12 jam
     *   - Any service  + eksklusif   → Meeting 48 jam + Podcast 12 jam (legacy)
     */
    public static function isEligibleForOrder(\App\Models\Order $order): bool
    {
        $formData    = $order->form_data ?? [];
        $packageSlug = strtolower(trim($formData['package'] ?? ''));
        $serviceSlug = strtolower(trim($formData['service'] ?? ''));
        $serviceName = strtolower(trim(
            $order->service_name ?? ($order->service?->name ?? '')
        ));

        // ── Special case: Virtual Office Premium → meeting-only benefit ─────────
        $isVirtualOffice = $serviceSlug === 'virtual-office'
            || str_contains($serviceName, 'virtual office');

        if ($isVirtualOffice && $packageSlug === 'premium') {
            return true;
        }

        // ── Standard Bundling packages ────────────────────────────────────────
        if (! self::isEligiblePackage($packageSlug)) return false;

        return self::isEligibleService($serviceSlug, $serviceName);
    }

    /**
     * Returns the benefit type for this order:
     *   'meeting_only_12'  → Virtual Office Premium (meeting room 12 jam)
     *   'meeting_podcast'  → Bundling semua layanan (meeting 48 + podcast 12 jam)
     *   null               → Not eligible
     */
    public static function benefitTypeForOrder(\App\Models\Order $order): ?string
    {
        if (! self::isEligibleForOrder($order)) return null;

        $formData    = $order->form_data ?? [];
        $packageSlug = strtolower(trim($formData['package'] ?? ''));
        $serviceSlug = strtolower(trim($formData['service'] ?? ''));
        $serviceName = strtolower(trim(
            $order->service_name ?? ($order->service?->name ?? '')
        ));

        $isVirtualOffice = $serviceSlug === 'virtual-office'
            || str_contains($serviceName, 'virtual office');

        if ($isVirtualOffice && $packageSlug === 'premium') {
            return 'meeting_only_12';
        }

        return 'meeting_podcast';
    }

    /**
     * Check if package slug is in the allowed list.
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
     * Check if service slug/name is any eligible pendirian badan usaha service.
     * All services are now eligible as long as they have the right package.
     */
    public static function isEligibleService(string $serviceSlug, string $serviceName = ''): bool
    {
        // Allowed slugs (all pendirian badan usaha + virtual office)
        $allowedSlugs = [
            'pendirian-pt',
            'pendirian-cv',
            'pendirian-firma',
            'pendirian-yayasan',
            'pendirian-pt-pma',
            'pt-perorangan',
            'pendirian-pt-perorangan',
            'virtual-office',
            // Universal order slugs (as stored in form_data['service'])
            'cv', 'firma', 'yayasan', 'pt-pma',
        ];

        if (in_array($serviceSlug, $allowedSlugs, true)) {
            return true;
        }

        // Fallback: check service_name string for legacy orders without form_data
        $allowedFragments = [
            'pendirian pt', 'pendirian cv', 'pendirian firma',
            'pendirian yayasan', 'pt perorangan', 'virtual office',
        ];
        foreach ($allowedFragments as $fragment) {
            if (str_contains($serviceName, $fragment)) {
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
