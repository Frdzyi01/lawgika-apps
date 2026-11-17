<?php

namespace App\Services;

use App\Models\Order;
use App\Models\RoomBenefit;
use App\Models\RoomBenefitLog;
use App\Models\MeetingRoomBooking;
use App\Models\PodcastRoomBooking;
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
     * Uses the order's transaction_date (not submit/approval time) as benefit start date.
     *
     * @throws \RuntimeException when a benefit already exists or order is ineligible
     */
    public function approve(Order $order, ?int $approverId = null): array
    {
        if (! $order->isValidated()) {
            throw new \RuntimeException('Pesanan harus disetujui (Approved) oleh Admin 3 / SPV sebelum benefit dapat diaktifkan.');
        }

        if ($order->payment_status !== 'verified' && $order->status !== 'approved') {
            throw new \RuntimeException('Silakan selesaikan pembayaran terlebih dahulu.');
        }

        // ── Guard: already approved (idempotency guard against duplicate hours) ─
        $expectedCount = RoomBenefit::benefitTypeForOrder($order) === 'meeting_only_12' ? 1 : 2;
        if (RoomBenefit::where('order_id', $order->id)->count() >= $expectedCount) {
            throw new \RuntimeException('Benefit untuk pesanan ini sudah pernah disetujui sebelumnya.');
        }

        return $this->activateOrSyncForOrder($order, $approverId);
    }

    /**
     * Idempotent benefit activation/synchronization used when Admin 3 / SPV approves an order.
     * Never creates duplicate benefit rows or resets already consumed minutes.
     */
    public function activateOrSyncForOrder(Order $order, ?int $approverId = null): array
    {
        if (! RoomBenefit::isEligibleForOrder($order)) {
            throw new \RuntimeException(
                'Paket "' . ($order->service_name ?? '-') . '" tidak memenuhi syarat untuk benefit ruangan. ' .
                'Benefit hanya untuk Business Package (semua layanan Pendirian Badan Usaha).'
            );
        }

        $formData    = $order->form_data ?? [];
        $rawSlug     = strtolower(trim($formData['package'] ?? ''));
        $serviceSlug = strtolower(trim($formData['service'] ?? ''));

        $packageLabelMap = [
            'enterprise'   => 'Bundling',
            'professional' => 'Bundling',
            'eksklusif'    => 'Eksklusif',
            'premium'      => 'Premium',
            'basic'        => 'Izin',
        ];

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

        $pkgLabel    = $packageLabelMap[$rawSlug] ?? ucfirst($rawSlug ?: 'Bundling');
        $svcLabel    = $serviceNameMap[$serviceSlug] ?? '';
        $paketLabel  = $svcLabel !== '' ? "{$pkgLabel} {$svcLabel}" : $pkgLabel;
        $benefitType = RoomBenefit::benefitTypeForOrder($order);
        $years       = max(1, $order->benefit_years);
        $effectiveMonths = max(12, $order->effective_duration_months);

        return DB::transaction(function () use ($order, $paketLabel, $benefitType, $approverId, $years, $effectiveMonths) {
            // Transaction date & effective duration control benefit start/end dates & base-year quota
            $startedAt  = $order->benefit_start_date;
            $expiredAt  = $startedAt->copy()->addMonthsNoOverflow($effectiveMonths);
            $approvedAt = $order->approved_at ?? now();
            $approvedBy = $approverId ?? $order->approved_by ?? auth()->id();

            $meetingTargetMinutes = ($benefitType === 'meeting_only_12' ? 720 : 2880) * $years;
            $podcastTargetMinutes = 720 * $years;

            $existing = RoomBenefit::where('order_id', $order->id)->get()->keyBy('type');
            $results  = [];

            if ($existing->isNotEmpty()) {
                foreach ($existing as $benefit) {
                    $targetMinutes = $benefit->type === 'podcast' ? $podcastTargetMinutes : $meetingTargetMinutes;
                    $targetHours   = (int) intdiv($targetMinutes, 60);
                    $benefit->update([
                        'is_active'     => true,
                        'total_minutes' => max((int) $benefit->used_minutes, $targetMinutes),
                        'started_at'    => $startedAt,
                        'expired_at'    => $expiredAt,
                        'approved_by'   => $approvedBy,
                        'approved_at'   => $approvedAt,
                        'paket'         => $paketLabel . ($benefit->type === 'podcast' ? ' – Podcast Room' : ' – Meeting Room'),
                    ]);

                    if ($benefit->type === 'meeting' && $benefit->meeting_room_booking_id) {
                        MeetingRoomBooking::where('id', $benefit->meeting_room_booking_id)
                            ->where('source_type', 'benefit')
                            ->update(['duration' => $targetHours]);
                    } elseif ($benefit->type === 'podcast' && $benefit->podcast_room_booking_id) {
                        PodcastRoomBooking::where('id', $benefit->podcast_room_booking_id)
                            ->where('source_type', 'benefit')
                            ->update(['duration' => $targetHours]);
                    }

                    $results[] = $benefit->fresh();
                }

                $benefitIds = $existing->pluck('id')->all();

                MeetingRoomBooking::whereIn('benefit_id', $benefitIds)
                    ->where('source_type', 'benefit')
                    ->update(['status' => 'approved', 'payment_status' => 'approved']);

                PodcastRoomBooking::whereIn('benefit_id', $benefitIds)
                    ->where('source_type', 'benefit')
                    ->update(['status' => 'approved', 'payment_status' => 'approved']);

                return $results;
            }

            $clientName  = $order->user->company_name ?? ($order->user->pic_name ?? ($order->user->name ?? 'Client'));
            $companyName = $order->user->company_name ?? ($order->form_data['company_name'] ?? null);

            if ($benefitType === 'meeting_only_12') {
                $meetingBenefit = RoomBenefit::create([
                    'user_id'       => $order->user_id,
                    'order_id'      => $order->id,
                    'paket'         => $paketLabel . ' – Meeting Room',
                    'total_minutes' => $meetingTargetMinutes,
                    'used_minutes'  => 0,
                    'type'          => 'meeting',
                    'is_active'     => true,
                    'started_at'    => $startedAt,
                    'expired_at'    => $expiredAt,
                    'approved_by'   => $approvedBy,
                    'approved_at'   => $approvedAt,
                ]);

                $meetingBooking = MeetingRoomBooking::create([
                    'user_id'            => $order->user_id,
                    'source_type'        => 'benefit',
                    'benefit_id'         => $meetingBenefit->id,
                    'order_number'       => $order->order_number,
                    'name'               => $clientName,
                    'nama_perusahaan'    => $companyName,
                    'email'              => $order->user?->email,
                    'date'               => null,
                    'start_time'         => null,
                    'end_time'           => null,
                    'duration'           => 12 * $years,
                    'total_used_seconds' => 0,
                    'participants'       => 1,
                    'package'            => $meetingBenefit->paket,
                    'status'             => 'approved',
                    'payment_status'     => 'approved',
                    'total_price'        => 0,
                    'notes'              => 'Benefit otomatis dari Order ' . $order->order_number,
                ]);

                $meetingBenefit->update(['meeting_room_booking_id' => $meetingBooking->id]);

                return [$meetingBenefit];
            }

            // 1. Meeting Room — 48 jam/tahun = 2880 menit * base_years
            $meetingBenefit = RoomBenefit::create([
                'user_id'       => $order->user_id,
                'order_id'      => $order->id,
                'paket'         => $paketLabel . ' – Meeting Room',
                'total_minutes' => $meetingTargetMinutes,
                'used_minutes'  => 0,
                'type'          => 'meeting',
                'is_active'     => true,
                'started_at'    => $startedAt,
                'expired_at'    => $expiredAt,
                'approved_by'   => $approvedBy,
                'approved_at'   => $approvedAt,
            ]);

            $meetingBooking = MeetingRoomBooking::create([
                'user_id'            => $order->user_id,
                'source_type'        => 'benefit',
                'benefit_id'         => $meetingBenefit->id,
                'order_number'       => $order->order_number,
                'name'               => $clientName,
                'nama_perusahaan'    => $companyName,
                'email'              => $order->user?->email,
                'date'               => null,
                'start_time'         => null,
                'end_time'           => null,
                'duration'           => 48 * $years,
                'total_used_seconds' => 0,
                'participants'       => 1,
                'package'            => $meetingBenefit->paket,
                'status'             => 'approved',
                'payment_status'     => 'approved',
                'total_price'        => 0,
                'notes'              => 'Benefit otomatis dari Order ' . $order->order_number,
            ]);

            $meetingBenefit->update(['meeting_room_booking_id' => $meetingBooking->id]);

            // 2. Podcast Room — 12 jam/tahun = 720 menit * base_years
            $podcastBenefit = RoomBenefit::create([
                'user_id'       => $order->user_id,
                'order_id'      => $order->id,
                'paket'         => $paketLabel . ' – Podcast Room',
                'total_minutes' => $podcastTargetMinutes,
                'used_minutes'  => 0,
                'type'          => 'podcast',
                'is_active'     => true,
                'started_at'    => $startedAt,
                'expired_at'    => $expiredAt,
                'approved_by'   => $approvedBy,
                'approved_at'   => $approvedAt,
            ]);

            $podcastBooking = PodcastRoomBooking::create([
                'user_id'            => $order->user_id,
                'source_type'        => 'benefit',
                'benefit_id'         => $podcastBenefit->id,
                'order_number'       => $order->order_number,
                'name'               => $clientName,
                'email'              => $order->user?->email,
                'date'               => null,
                'start_time'         => null,
                'end_time'           => null,
                'duration'           => 12 * $years,
                'total_used_seconds' => 0,
                'participants'       => 1,
                'package'            => $podcastBenefit->paket,
                'status'             => 'approved',
                'payment_status'     => 'approved',
                'total_price'        => 0,
                'notes'              => 'Benefit otomatis dari Order ' . $order->order_number,
            ]);

            $podcastBenefit->update(['podcast_room_booking_id' => $podcastBooking->id]);

            return [$meetingBenefit, $podcastBenefit];
        });
    }

    /**
     * Synchronize benefit activation & expiry dates when an approved order's transaction_date is edited.
     */
    public function syncDatesFromOrder(Order $order): void
    {
        $startedAt = $order->benefit_start_date;
        $expiredAt = $startedAt->copy()->addMonthsNoOverflow($order->effective_duration_months);

        RoomBenefit::where('order_id', $order->id)->update([
            'started_at' => $startedAt,
            'expired_at' => $expiredAt,
        ]);
    }

    /**
     * Deactivate linked benefits when an order is rejected or soft-deleted.
     */
    public function deactivateForOrder(Order $order): void
    {
        $benefitIds = RoomBenefit::where('order_id', $order->id)->pluck('id')->all();

        if (empty($benefitIds)) {
            return;
        }

        RoomBenefit::whereIn('id', $benefitIds)->update(['is_active' => false]);

        MeetingRoomBooking::whereIn('benefit_id', $benefitIds)
            ->where('source_type', 'benefit')
            ->update(['status' => 'rejected']);

        PodcastRoomBooking::whereIn('benefit_id', $benefitIds)
            ->where('source_type', 'benefit')
            ->update(['status' => 'rejected']);
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
