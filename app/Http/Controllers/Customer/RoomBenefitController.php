<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\RoomBenefit;
use App\Models\RoomBenefitLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * RoomBenefitController (Customer)
 *
 * Read-only detail view for the authenticated customer's own benefit.
 */
class RoomBenefitController extends Controller
{
    /**
     * GET dashboard/benefits/{benefit}/detail
     *
     * Customers can ONLY view benefits that belong to them.
     */
    public function showDetail(RoomBenefit $benefit): View
    {
        // Ownership guard — customer may not view other customers' data
        abort_unless($benefit->user_id === Auth::id(), 403, 'Anda tidak berhak mengakses benefit ini.');

        $benefit->load(['user', 'order']);

        $logs = RoomBenefitLog::where('benefit_id', $benefit->id)
            ->orderBy('action_at', 'asc')
            ->get();

        $sessions = $this->buildSessions($logs);

        $benefitBookings = \App\Models\MeetingRoomBooking::where('benefit_id', $benefit->id)
            ->whereNotNull('date')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('customer.benefits.detail', compact('benefit', 'logs', 'sessions', 'benefitBookings'));
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function buildSessions($logs): \Illuminate\Support\Collection
    {
        $sessions    = collect();
        $openCheckins = [];

        foreach ($logs as $log) {
            if ($log->action === 'checkin') {
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

        foreach ($openCheckins as $roomType => $checkinLog) {
            $elapsed = (int) ceil($checkinLog->action_at->diffInMinutes(now()));
            $sessions->push((object) [
                'room_type'        => $roomType,
                'checkin_at'       => $checkinLog->action_at,
                'checkout_at'      => null,
                'duration_minutes' => $elapsed,
                'is_active'        => true,
            ]);
        }

        return $sessions->sortBy('checkin_at')->values();
    }
}
