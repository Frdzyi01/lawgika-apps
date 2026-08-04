<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RoomBenefit;
use App\Models\RoomBenefitLog;
use App\Services\RoomBenefitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * RoomBenefitController (Admin)
 *
 * Handles benefit approval, check-in, check-out, and the new detail view.
 */
class RoomBenefitController extends Controller
{
    public function __construct(private readonly RoomBenefitService $benefitService) {}

    // ── Approve ───────────────────────────────────────────────────────────────

    /**
     * POST admin/orders/{order}/approve-benefit
     */
    public function approve(Order $order): RedirectResponse
    {
        if ($order->payment_status !== 'verified') {
            return redirect()
                ->back()
                ->with('error', 'Silakan selesaikan pembayaran terlebih dahulu.');
        }

        try {
            $this->benefitService->approve($order);

            return redirect()
                ->back()
                ->with('success', '✅ Benefit ruangan berhasil diaktifkan: Meeting Room (48 Jam) & Podcast Room (12 Jam).');

        } catch (\RuntimeException $e) {
            return redirect()
                ->back()
                ->with('error', '❌ ' . $e->getMessage());
        }
    }

    // ── Check-In ──────────────────────────────────────────────────────────────

    /**
     * POST admin/benefits/{benefit}/checkin/{roomType}
     */
    public function checkin(RoomBenefit $benefit, string $roomType): RedirectResponse
    {
        try {
            $this->benefitService->checkin($benefit, $roomType);

            return redirect()
                ->back()
                ->with('success', 'Check In benefit berhasil dicatat.');

        } catch (\RuntimeException $e) {
            return redirect()
                ->back()
                ->with('error', '❌ ' . $e->getMessage());
        }
    }

    // ── Check-Out ─────────────────────────────────────────────────────────────

    /**
     * POST admin/benefits/{benefit}/checkout/{roomType}
     *
     * Finds the most recent open check-in log (no checkout following it),
     * calculates duration, deducts from the shared pool, and stores the log.
     */
    public function checkout(RoomBenefit $benefit, string $roomType): RedirectResponse
    {
        // Find the most recent open check-in (last log = checkin → session is open)
        $lastLog = $benefit->logs()
            ->latest('action_at')
            ->first();

        if (!$lastLog || $lastLog->action !== 'checkin') {
            return redirect()
                ->back()
                ->with('error', '❌ Tidak ada sesi Check In aktif yang ditemukan untuk benefit ini.');
        }

        // ── BILLING ADJUSTMENT: Calculate actual and rounded duration ─────────
        $actualMinutes = (int) ceil($lastLog->action_at->diffInMinutes(now()));
        
        // Round up to nearest hour (minimum 1 hour)
        $billingHours = (int) ceil($actualMinutes / 60);
        if ($billingHours < 1) {
            $billingHours = 1; // Enforce minimum 1 hour
        }
        
        $billingMinutes = $billingHours * 60;

        try {
            // Pass ROUNDED minutes to service for deduction
            $this->benefitService->checkout($benefit, $roomType, $billingMinutes);

            return redirect()
                ->back()
                ->with('success', sprintf(
                    'Check Out berhasil. Durasi aktual: %s (Ditagih: %d jam). Sisa benefit: %s.',
                    RoomBenefit::formatMinutes($actualMinutes),
                    $billingHours,
                    RoomBenefit::formatMinutes($benefit->fresh()->remaining_minutes)
                ));

        } catch (\RuntimeException $e) {
            return redirect()
                ->back()
                ->with('error', '❌ ' . $e->getMessage());
        }
    }

    // ── Detail View ───────────────────────────────────────────────────────────

    /**
     * GET admin/benefits/{benefit}/detail
     *
     * Shows the detail page for a single benefit:
     * - Informasi Benefit  (basic data)
     * - Riwayat Penggunaan (real logs from room_benefit_logs)
     */
    public function showDetail(RoomBenefit $benefit): View
    {
        // Eager-load all relationships needed for the view
        $benefit->load(['user', 'order']);

        // Fetch ALL logs for this benefit ordered chronologically
        $logs = RoomBenefitLog::where('benefit_id', $benefit->id)
            ->orderBy('action_at', 'asc')
            ->get();

        // Build paired check-in / check-out sessions from raw logs
        // Each "session" is one row in the Riwayat table:
        //   checkin  → open session (no paired checkout yet)
        //   checkout → closes session; duration is stored on the checkout log
        $sessions = $this->buildSessions($logs);

        return view('admin.benefits.detail', compact('benefit', 'logs', 'sessions'));
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /**
     * Pair check-in / check-out logs into display-ready session rows.
     *
     * Returns a Collection of objects with properties:
     *   room_type, checkin_at, checkout_at (nullable), duration_minutes, is_active
     */
    private function buildSessions($logs): \Illuminate\Support\Collection
    {
        $sessions = collect();
        $openCheckins = []; // keyed by room_type

        foreach ($logs as $log) {
            if ($log->action === 'checkin') {
                // Open a new session slot (overwrite if already open — safety)
                $openCheckins[$log->room_type] = $log;

            } elseif ($log->action === 'checkout') {
                $checkinLog = $openCheckins[$log->room_type] ?? null;

                $sessions->push((object) [
                    'room_type'        => $log->room_type,
                    'checkin_at'       => $checkinLog?->action_at,
                    'checkout_at'      => $log->action_at,
                    'duration_minutes' => $log->duration_minutes,
                    'is_active'        => false,
                ]);

                unset($openCheckins[$log->room_type]);
            }
        }

        // Any remaining open check-ins = currently active session
        foreach ($openCheckins as $roomType => $checkinLog) {
            $elapsed = (int) ceil($checkinLog->action_at->diffInMinutes(now()));

            $sessions->push((object) [
                'room_type'        => $roomType,
                'checkin_at'       => $checkinLog->action_at,
                'checkout_at'      => null,     // not yet
                'duration_minutes' => $elapsed, // live elapsed
                'is_active'        => true,
            ]);
        }

        return $sessions->sortBy('checkin_at')->values();
    }
}
