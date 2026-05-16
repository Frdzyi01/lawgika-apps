<?php

namespace App\Services;

use App\Models\Order;
use App\Models\RoomBenefit;
use App\Models\RoomBenefitLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * RoomBenefitService
 *
 * Isolated service layer for the Room Benefit system.
 * Contains ALL business logic — controllers remain thin.
 *
 * Rules:
 *  - One order → two benefit records (meeting + podcast, separate quotas)
 *  - Meeting Room: 48 jam (2880 menit)
 *  - Podcast Room: 12 jam (720 menit)
 *  - Time only deducted at CHECK-OUT, never at booking creation
 */
class RoomBenefitService
{
    /**
     * Approve (create) a room benefit for the given order.
     *
     * @throws \RuntimeException when a benefit already exists or order is ineligible
     */
    public function approve(Order $order): array
    {
        // ── Guard: already approved (2 records = meeting + podcast) ──────────
        if (RoomBenefit::where('order_id', $order->id)->count() >= 2) {
            throw new \RuntimeException('Benefit untuk pesanan ini sudah pernah disetujui sebelumnya.');
        }

        // ── Guard: eligible package + service ─────────────────────────────────
        if (! RoomBenefit::isEligibleForOrder($order)) {
            throw new \RuntimeException(
                'Paket "' . ($order->service_name ?? '-') . '" tidak memenuhi syarat untuk benefit ruangan. ' .
                'Benefit hanya untuk Business Package (semua layanan Pendirian Badan Usaha).'
            );
        }

        // ── Build a descriptive, human-readable label ─────────────────────────
        $formData    = $order->form_data ?? [];
        $rawSlug     = strtolower(trim($formData['package'] ?? ''));
        $serviceSlug = strtolower(trim($formData['service'] ?? ''));

        // Map package slug → friendly label
        $packageLabelMap = [
            'enterprise'   => 'Bundling',
            'professional' => 'Bundling',
            'eksklusif'    => 'Eksklusif',
            'premium'      => 'Premium',
            'basic'        => 'Izin',
        ];

        // Map service slug → friendly service name
        $serviceNameMap = [
            'pendirian-pt'            => 'PT',
            'pendirian-cv'            => 'CV',
            'cv'                      => 'CV',
            'pendirian-firma'         => 'Firma',
            'firma'                   => 'Firma',
            'pendirian-yayasan'       => 'Yayasan',
            'yayasan'                 => 'Yayasan',
            'pendirian-pt-pma'        => 'PT PMA',
            'pt-pma'                  => 'PT PMA',
            'pt-perorangan'           => 'PT Perorangan',
            'pendirian-pt-perorangan' => 'PT Perorangan',
            'virtual-office'          => 'Virtual Office',
        ];

        $pkgLabel   = $packageLabelMap[$rawSlug] ?? ucfirst($rawSlug ?: 'Bundling');
        $svcLabel   = $serviceNameMap[$serviceSlug] ?? '';
        $paketLabel = $svcLabel !== '' ? "{$pkgLabel} {$svcLabel}" : $pkgLabel;

        // ── Determine benefit type ────────────────────────────────────────────
        $benefitType = RoomBenefit::benefitTypeForOrder($order);

        // ── Create benefit records in one transaction ─────────────────────────
        return DB::transaction(function () use ($order, $paketLabel, $benefitType) {

            $expiredAt = Carbon::now()->addYear();

            if ($benefitType === 'meeting_only_12') {
                // Virtual Office Premium → Meeting Room ONLY 12 jam
                $meetingBenefit = RoomBenefit::create([
                    'user_id'       => $order->user_id,
                    'order_id'      => $order->id,
                    'paket'         => $paketLabel . ' – Meeting Room',
                    'total_minutes' => 720,         // 12 jam
                    'used_minutes'  => 0,
                    'type'          => 'meeting',
                    'is_active'     => true,
                    'expired_at'    => $expiredAt,
                ]);

                return [$meetingBenefit];
            }

            // Default: Bundling (enterprise / professional / eksklusif)
            // → Meeting Room 48 jam + Podcast Room 12 jam

            // 1. Meeting Room — 48 jam = 2880 menit
            $meetingBenefit = RoomBenefit::create([
                'user_id'       => $order->user_id,
                'order_id'      => $order->id,
                'paket'         => $paketLabel . ' – Meeting Room',
                'total_minutes' => 2880,        // 48 jam
                'used_minutes'  => 0,
                'type'          => 'meeting',
                'is_active'     => true,
                'expired_at'    => $expiredAt,
            ]);

            // 2. Podcast Room — 12 jam = 720 menit
            $podcastBenefit = RoomBenefit::create([
                'user_id'       => $order->user_id,
                'order_id'      => $order->id,
                'paket'         => $paketLabel . ' – Podcast Room',
                'total_minutes' => 720,         // 12 jam
                'used_minutes'  => 0,
                'type'          => 'podcast',
                'is_active'     => true,
                'expired_at'    => $expiredAt,
            ]);

            return [$meetingBenefit, $podcastBenefit];
        });
    }

    /**
     * Record a check-in event against a benefit.
     * Validates the benefit is still usable before storing.
     *
     * @throws \RuntimeException on validation failure
     */
    public function checkin(RoomBenefit $benefit, string $roomType): void
    {
        $this->assertUsable($benefit);

        RoomBenefitLog::create([
            'benefit_id'       => $benefit->id,
            'room_type'        => $roomType,
            'duration_minutes' => 0,
            'action'           => 'checkin',
            'action_at'        => now(),
        ]);
    }

    /**
     * Record a check-out event and deduct duration from the shared pool.
     *
     * @param  int $durationMinutes  How many minutes this session consumed (MUST be pre-rounded by caller)
     * @throws \RuntimeException on validation failure
     */
    public function checkout(RoomBenefit $benefit, string $roomType, int $durationMinutes): void
    {
        $this->assertUsable($benefit, allowExhausted: true); // allow checkout even if pool is at 0

        DB::transaction(function () use ($benefit, $roomType, $durationMinutes) {
            // Deduct from pool (clamp at total)
            // NOTE: $durationMinutes should already be rounded up to nearest hour by the caller
            $benefit->used_minutes = min(
                $benefit->total_minutes,
                $benefit->used_minutes + $durationMinutes
            );
            $benefit->save();

            RoomBenefitLog::create([
                'benefit_id'       => $benefit->id,
                'room_type'        => $roomType,
                'duration_minutes' => $durationMinutes,
                'action'           => 'checkout',
                'action_at'        => now(),
            ]);
        });
    }

    /**
     * Return the active, non-expired benefit for a user (or null).
     */
    public function getActiveBenefit(int $userId): ?RoomBenefit
    {
        return RoomBenefit::where('user_id', $userId)
            ->usable()
            ->latest()
            ->first();
    }

    /**
     * Return all benefits for a user (for display in dashboard).
     */
    public function getAllBenefitsForUser(int $userId)
    {
        return RoomBenefit::with('order')
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /**
     * @throws \RuntimeException
     */
    private function assertUsable(RoomBenefit $benefit, bool $allowExhausted = false): void
    {
        if (!$benefit->is_active) {
            throw new \RuntimeException('Benefit ini sudah tidak aktif.');
        }

        if ($benefit->is_expired) {
            throw new \RuntimeException('Benefit ini sudah expired (lebih dari 1 tahun).');
        }

        if (!$allowExhausted && $benefit->remaining_minutes <= 0) {
            throw new \RuntimeException('Sisa waktu benefit sudah habis.');
        }
    }
}
