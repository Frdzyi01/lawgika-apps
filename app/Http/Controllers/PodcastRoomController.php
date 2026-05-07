<?php

namespace App\Http\Controllers;

use App\Models\PodcastRoomBooking;
use App\Models\RoomBenefit;
use App\Models\RoomBenefitLog;
use App\Models\RoomUsageLog;
use App\Models\UserRoomQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PodcastRoomController extends Controller
{
    public static array $packages = [
        2 => ['label' => '2 Jam', 'price' => 500000],
    ];

    public function index()
    {
        return view('frontend.services.layanan-pendukung-bisnis.sewa-ruang-podcast');
    }

    public function getBookedSlots(Request $request)
    {
        $date   = $request->get('date');
        $booked = PodcastRoomBooking::whereDate('date', $date)
            ->whereNotIn('payment_status', ['rejected'])
            ->pluck('start_time')
            ->map(fn($t) => substr($t, 0, 5))
            ->values()
            ->toArray();

        return response()->json($booked);
    }

    public function order(Request $request)
    {
        $quota   = UserRoomQuota::where('user_id', Auth::id())->first();
        $benefit = $this->getActiveBenefit();

        return view('podcast-room.order', [
            'tanggal'       => $request->get('tanggal'),
            'jam'           => $request->get('jam'),
            'durasi'        => $request->get('durasi', 2),
            'packages'      => self::$packages,
            'quota'         => $quota,
            'activeBenefit' => $benefit,
        ]);
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        if (auth()->check()) {
            $request->merge(['nama' => auth()->user()->name]);
        }

        $rules = [
            'nama'          => 'required|string|max:255',
            'podcast_title' => 'nullable|string|max:255',
            'tanggal'       => 'required|date',
            'jam'           => 'required',
            'durasi'        => 'required|integer|min:1|max:12',
            'use_quota'     => 'nullable|boolean',
        ];

        if (!$request->input('use_quota') && !$this->getActiveBenefit()) {
            $rules['payment_proof'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        }

        $request->validate($rules);

        // Double-booking guard
        $conflict = PodcastRoomBooking::where('date', $request->tanggal)
            ->where('start_time', 'like', $request->jam . '%')
            ->whereNotIn('status', ['rejected'])
            ->exists();

        if ($conflict) {
            return back()->withInput()
                ->withErrors(['jam' => 'Slot waktu tersebut sudah dipesan. Silakan pilih waktu lain.']);
        }

        $benefit = $this->getActiveBenefit();

        if ($benefit) {
            // ── BENEFIT FLOW ──────────────────────────────────────────────────
            // Tag booking as benefit. Status = 'pending' (waits for admin approval).
            // NO time deducted. NO log created. Payment not required.
            $durasi   = (int) $request->durasi;
            $orderNum = 'PODCAST-BNF-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            PodcastRoomBooking::create([
                'user_id'        => Auth::id(),
                'source_type'    => 'benefit',
                'benefit_id'     => $benefit->id,
                'order_number'   => $orderNum,
                'name'           => $request->nama,
                'podcast_title'  => $request->podcast_title,
                'date'           => $request->tanggal,
                'start_time'     => $request->jam,
                'duration'       => $durasi,
                'participants'   => 1,
                'package'        => $durasi . 'jam',
                'total_price'    => 0,
                'status'         => 'pending',         // admin must approve first
                'payment_status' => 'approved',        // no payment needed
                'payment_proof'  => null,
            ]);

            return redirect()->route('customer.podcast-room.index')
                ->with('success', "✅ Reservasi Ruang Podcast (Benefit Paket PT) berhasil diajukan! Order: {$orderNum}. Menunggu persetujuan admin.");
        }

        // ── MANUAL FLOW (existing — untouched) ────────────────────────────────
        if ($request->input('use_quota')) {
            $quota = UserRoomQuota::where('user_id', Auth::id())->first();
            if (!$quota) {
                return back()->withInput()->withErrors(['quota' => 'Anda tidak memiliki quota.']);
            }
            if (now()->greaterThan($quota->expired_at)) {
                return back()->withInput()->withErrors(['quota' => 'Quota Anda sudah expired.']);
            }
            if ($quota->remaining_seconds < $request->durasi * 3600) {
                return back()->withInput()->withErrors(['quota' => 'Sisa waktu quota tidak mencukupi untuk durasi ini.']);
            }
        }

        $path = null;
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs/podcast', 'public');
        }

        $durasi = (int) $request->durasi;
        if ($durasi < 1) $durasi = 1;

        // Podcast Pricing: 1 jam = 500.000, 2 jam = 800.000, >2 jam = 800.000 + (n-2) * 300.000
        if ($durasi === 1) {
            $price = 500000;
        } elseif ($durasi === 2) {
            $price = 800000;
        } else {
            $price = 800000 + (($durasi - 2) * 300000);
        }
        $orderNum = 'PODCAST-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        PodcastRoomBooking::create([
            'user_id'        => Auth::id(),
            'source_type'    => 'manual',
            'benefit_id'     => null,
            'order_number'   => $orderNum,
            'name'           => $request->nama,
            'podcast_title'  => $request->podcast_title,
            'date'           => $request->tanggal,
            'start_time'     => $request->jam,
            'duration'       => $durasi,
            'participants'   => 1,
            'package'        => $durasi . 'jam',
            'total_price'    => $price,
            'status'         => 'pending',
            'payment_proof'  => $path,
            'payment_status' => $request->input('use_quota') ? 'approved' : 'pending',
        ]);

        $msg = $request->input('use_quota')
            ? "Reservasi Ruang Podcast menggunakan quota berhasil! Nomor Order: {$orderNum}. Status langsung disetujui."
            : "Reservasi Ruang Podcast berhasil! Nomor Order: {$orderNum}. Menunggu konfirmasi pembayaran admin.";

        return redirect()->route('customer.podcast-room.index')->with('success', $msg);
    }

    // ── Admin Index ───────────────────────────────────────────────────────────

    public function adminIndex()
    {
        // All benefit-tagged reservations (blade filters by status)
        $benefitBookings = PodcastRoomBooking::with(['user', 'benefit'])
            ->where('source_type', 'benefit')
            ->latest()
            ->get();

        // Manual reservations only
        $bookings = PodcastRoomBooking::with('user')
            ->where(function ($q) {
                $q->where('source_type', 'manual')
                  ->orWhereNull('source_type');   // legacy rows
            })
            ->latest()
            ->get();

        // Benefit pool cards — only podcast-type benefits
        $benefits = RoomBenefit::with(['user', 'order'])
            ->whereIn('type', ['podcast', 'shared'])
            ->latest()->get();

        return view('admin.podcast-room.index', compact('bookings', 'benefits', 'benefitBookings'));
    }

    // ── Admin: Approve / Reject benefit reservation ───────────────────────────

    public function approveBenefitReservation($id)
    {
        $booking = PodcastRoomBooking::where('source_type', 'benefit')
            ->where('status', 'pending')
            ->findOrFail($id);

        $booking->update(['status' => 'approved']);

        return redirect()->back()->with('success', '✅ Reservasi benefit Ruang Podcast disetujui.');
    }

    public function rejectBenefitReservation($id)
    {
        $booking = PodcastRoomBooking::where('source_type', 'benefit')
            ->where('status', 'pending')
            ->findOrFail($id);

        $booking->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Reservasi benefit Ruang Podcast ditolak.');
    }

    // ── Admin Detail ──────────────────────────────────────────────────────────

    public function adminDetail($id)
    {
        $booking = PodcastRoomBooking::with('user')->findOrFail($id);
        $logs    = RoomUsageLog::where('reservation_id', $id)
            ->where('room_type', 'podcast_room')
            ->orderBy('timestamp', 'asc')
            ->get();

        return view('admin.podcast-room.detail', compact('booking', 'logs'));
    }

    // ── Payment (manual only — existing) ─────────────────────────────────────

    public function approvePayment($id)
    {
        PodcastRoomBooking::findOrFail($id)->update(['payment_status' => 'approved']);
        return back()->with('success', 'Pembayaran disetujui.');
    }

    public function rejectPayment($id)
    {
        PodcastRoomBooking::findOrFail($id)->update(['payment_status' => 'rejected']);
        return back()->with('success', 'Pembayaran ditolak.');
    }

    // ── Check-In ──────────────────────────────────────────────────────────────

    public function checkin($id)
    {
        $booking = PodcastRoomBooking::findOrFail($id);

        // Benefit booking: must be admin-approved
        if ($booking->source_type === 'benefit') {
            if ($booking->status !== 'approved' && $booking->status !== 'paused') {
                return back()->with('error', 'Reservasi benefit belum disetujui admin.');
            }
        } else {
            // Manual: payment must be approved
            if ($booking->payment_status !== 'approved') {
                return back()->with('error', 'Pembayaran belum dikonfirmasi. Check In tidak bisa dilakukan.');
            }
            if ($booking->status === 'checkin') {
                return back()->with('error', 'Booking ini sudah di Check In.');
            }
        }

        if ($booking->is_expired) {
            return back()->with('error', 'Masa berlaku reservasi sudah expired (lebih dari 1 tahun).');
        }
        if ($booking->remaining_seconds <= 0) {
            return back()->with('error', 'Waktu reservasi sudah habis.');
        }

        $booking->update([
            'status'      => 'checkin',
            'checkin_at'  => now(),
            'checkout_at' => null,
        ]);

        RoomUsageLog::create([
            'reservation_id' => $booking->id,
            'room_type'      => 'podcast_room',
            'type'           => 'checkin',
            'timestamp'      => now(),
        ]);

        // NO room_benefit_logs entry on check-in — only on check-out

        return back()->with('success', 'User berhasil Check In ke ruangan.');
    }

    // ── Check-Out ─────────────────────────────────────────────────────────────

    public function checkout($id)
    {
        $booking = PodcastRoomBooking::findOrFail($id);

        if ($booking->status !== 'checkin' || !$booking->checkin_at) {
            return back()->with('error', 'Booking ini belum di Check In.');
        }

        $checkinAt      = $booking->checkin_at;
        $checkoutAt     = now();
        
        // ── VALIDATION: Prevent invalid duration ──────────────────────────────
        if ($checkoutAt->lessThan($checkinAt)) {
            return back()->with('error', 'Checkout time tidak boleh lebih awal dari checkin time.');
        }
        
        $sessionSeconds = $checkinAt->diffInSeconds($checkoutAt);
        
        // Prevent negative duration (additional safety check)
        if ($sessionSeconds < 0) {
            return back()->with('error', 'Durasi tidak valid. Silakan hubungi administrator.');
        }
        
        // ── BILLING ADJUSTMENT: Calculate rounded hours and adjusted price ────
        $billingHours = $booking->calculateBillingHours($sessionSeconds);
        $billingSeconds = $billingHours * 3600;
        $adjustedPrice = $booking->calculateAdjustedBilling($sessionSeconds);
        
        // Use ROUNDED billing seconds for all deductions and storage
        $prevUsed       = $booking->total_used_seconds > 0 ? $booking->total_used_seconds : ($booking->total_used_minutes * 60);
        $newTotalUsed   = $prevUsed + $billingSeconds;  // ✅ Use rounded seconds, not actual
        $totalSeconds   = $booking->duration * 3600;
        $newStatus      = ($newTotalUsed >= $totalSeconds) ? 'selesai' : 'paused';
        
        $booking->update([
            'status'             => $newStatus,
            'total_used_seconds' => $newTotalUsed,  // ✅ Now stores rounded seconds
            'checkout_at'        => $checkoutAt,
            'checkin_at'         => null,
        ]);

        RoomUsageLog::create([
            'reservation_id' => $booking->id,
            'room_type'      => 'podcast_room',
            'type'           => 'checkout',
            'timestamp'      => $checkoutAt,
        ]);

        // ── Deduct from benefit pool + write session log (ONLY at checkout) ───
        if ($booking->source_type === 'benefit' && $booking->benefit_id) {
            $benefit = RoomBenefit::find($booking->benefit_id);
            if ($benefit) {
                // Use rounded-up billing hours for benefit deduction
                $billingMinutes = $billingHours * 60;
                $benefit->used_minutes = min($benefit->total_minutes, $benefit->used_minutes + $billingMinutes);
                $benefit->save();

                // ONE row per session — contains both checkin and checkout times
                RoomBenefitLog::create([
                    'benefit_id'       => $benefit->id,
                    'room_type'        => 'podcast',
                    'duration_minutes' => $billingMinutes,
                    'action'           => 'checkout',
                    'action_at'        => $checkoutAt,
                    'checkin_at'       => $checkinAt,
                    'checkout_at'      => $checkoutAt,
                ]);
            }
        } else {
            // Existing: deduct UserRoomQuota if applicable
            $quota = UserRoomQuota::where('user_id', $booking->user_id)->first();
            if ($quota && !now()->greaterThan($quota->expired_at) && empty($booking->payment_proof)) {
                // Use rounded-up billing hours for quota deduction
                $billingSeconds = $billingHours * 3600;
                $quota->used_seconds     += $billingSeconds;
                $quota->remaining_seconds = max(0, $quota->total_seconds - $quota->used_seconds);
                $quota->save();

                \App\Models\QuotaLog::create([
                    'user_id'   => $booking->user_id,
                    'room_type' => 'podcast_room',
                    'duration'  => $billingSeconds,
                    'tanggal'   => $checkoutAt,
                ]);
            }
        }

        // Display actual duration but billing is based on rounded hours
        $actualDuration = $booking->formatSeconds($sessionSeconds);
        $billingInfo = ($billingHours > 1) ? " (Ditagih: {$billingHours} jam - Rp " . number_format($adjustedPrice, 0, ',', '.') . ")" : " (Ditagih: {$billingHours} jam - Rp " . number_format($adjustedPrice, 0, ',', '.') . ")";
        
        return back()->with('success', "User berhasil Check Out dari ruangan. Durasi aktual: {$actualDuration}{$billingInfo}");
    }

    // ── Customer Index ────────────────────────────────────────────────────────

    public function customerIndex()
    {
        $benefitBookings = PodcastRoomBooking::where('user_id', Auth::id())
            ->where('source_type', 'benefit')
            ->latest()
            ->get();

        $manualBookings = PodcastRoomBooking::where('user_id', Auth::id())
            ->where(function ($q) {
                $q->where('source_type', 'manual')
                  ->orWhereNull('source_type');
            })
            ->latest()
            ->get();

        // Only podcast-type benefits for this user
        $benefits = RoomBenefit::with('order')
            ->where('user_id', Auth::id())
            ->whereIn('type', ['podcast', 'shared'])
            ->latest()
            ->get();

        return view('customer.podcast-room.index', compact('benefitBookings', 'manualBookings', 'benefits'));
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function getActiveBenefit(): ?RoomBenefit
    {
        if (!Auth::check()) return null;

        return RoomBenefit::where('user_id', Auth::id())
            ->where('is_active', true)
            ->whereIn('type', ['podcast', 'shared'])   // podcast-specific benefit
            ->where(function ($q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>', now());
            })
            ->whereRaw('used_minutes < total_minutes')
            ->latest()
            ->first();
    }
}
