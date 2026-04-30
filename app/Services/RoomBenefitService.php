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
 *  - One order → one benefit (enforced by DB unique + guard here)
 *  - Shared 60-hour pool across Meeting Room + Podcast Room
 *  - Time only deducted at CHECK-OUT, never at booking creation
 */
class RoomBenefitService
{
    /**
     * Approve (create) a room benefit for the given order.
     *
     * @throws \RuntimeException when a benefit already exists or order is ineligible
     */
    public function approve(Order $order): RoomBenefit
    {
        // ── Guard: already approved ───────────────────────────────────────────
        if (RoomBenefit::where('order_id', $order->id)->exists()) {
            throw new \RuntimeException('Benefit untuk pesanan ini sudah pernah disetujui sebelumnya.');
        }

        // ── Guard: eligible package + service ─────────────────────────────────
        //
        // CRITICAL FIX: isEligibleForOrder() validates BOTH:
        //   ✅ Service  = Pendirian PT (reguler) only
        //   ✅ Package  = eksklusif or enterprise
        //
        // This prevents CV Eksklusif, Yayasan Enterprise, etc. from getting benefit.
        if (! RoomBenefit::isEligibleForOrder($order)) {
            throw new \RuntimeException(
                'Paket "' . ($order->service_name ?? '-') . '" tidak memenuhi syarat untuk benefit ruangan. ' .
                'Benefit HANYA untuk Pendirian PT – Paket Eksklusif atau Enterprise.'
            );
        }

        // Build a clean, human-readable label to store in the benefit record.
        $formData = $order->form_data ?? [];
        $rawSlug  = strtolower(trim($formData['package'] ?? ''));
        
        $paketLabel = $rawSlug !== ''
            ? ucfirst($rawSlug)                             // "Eksklusif" / "Enterprise"
            : ($order->service_name ?? 'PT Package');       // fallback for legacy

        return DB::transaction(function () use ($order, $paketLabel) {
            return RoomBenefit::create([
                'user_id'       => $order->user_id,
                'order_id'      => $order->id,
                'paket'         => $paketLabel,
                'total_minutes' => 3600,        // 60 hours
                'used_minutes'  => 0,
                'type'          => 'shared',
                'is_active'     => true,
                'expired_at'    => Carbon::now()->addYear(),
            ]);
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
     * @param  int $durationMinutes  How many minutes this session consumed
     * @throws \RuntimeException on validation failure
     */
    public function checkout(RoomBenefit $benefit, string $roomType, int $durationMinutes): void
    {
        $this->assertUsable($benefit, allowExhausted: true); // allow checkout even if pool is at 0

        DB::transaction(function () use ($benefit, $roomType, $durationMinutes) {
            // Deduct from pool (clamp at total)
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
