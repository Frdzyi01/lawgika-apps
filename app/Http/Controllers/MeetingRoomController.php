<?php

namespace App\Http\Controllers;

use App\Models\MeetingRoomBooking;
use App\Models\RoomBenefit;
use App\Models\RoomBenefitLog;
use App\Models\RoomUsageLog;
use App\Models\UserRoomQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingRoomController extends Controller
{
    public function index()
    {
        $hasBenefit = $this->getActiveBenefit() ? true : false;
        return view('frontend.services.layanan-pendukung-bisnis.sewa-meeting-room', compact('hasBenefit'));
    }

    public function order(Request $request)
    {
        $quota   = UserRoomQuota::where('user_id', Auth::id())->first();
        $benefit = $this->getActiveBenefit();

        $package = $request->get('package', 'reservasi');

        // Jika user tidak memiliki paket benefit (Paket Badan Usaha) dan mencoba akses 'reservasi', arahkan ke beli paket
        if ($package === 'reservasi' && !$benefit) {
            return redirect()->route('meeting-room.order', [
                'package' => 'paket',
                'tanggal' => $request->get('tanggal'),
                'jam'     => $request->get('jam'),
                'durasi'  => $request->get('durasi', 1)
            ])->with('error', 'Anda belum memiliki Paket Badan Usaha aktif. Silakan Beli Paket Meeting Room.');
        }

        $ptOrder = $benefit ? $benefit->order : null;
        $ptData = $ptOrder ? $ptOrder->form_data : [];

        return view('meeting-room.order', [
            'tanggal'       => $request->get('tanggal'),
            'jam'           => $request->get('jam'),
            'durasi'        => $request->get('durasi', 1),
            'package'       => $package,
            'quota'         => $quota,
            'activeBenefit' => $benefit,
            'ptData'        => $ptData,
        ]);
    }

    public function getBookedSlots(Request $request)
    {
        $date   = $request->get('date');
        $booked = MeetingRoomBooking::whereDate('date', $date)
            ->whereNotIn('payment_status', ['rejected'])
            ->pluck('start_time')
            ->map(fn($t) => substr($t, 0, 5))
            ->values()
            ->toArray();

        return response()->json($booked);
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        if (auth()->check()) {
            $request->merge(['nama' => auth()->user()->name]);
        }

        // ── DETECT PACKAGE MODE (from query string OR form input) ────────────
        $isPackage = $request->query('package') === 'paket' || $request->input('package') === 'paket';

        // ── PACKAGE PURCHASE FLOW ─────────────────────────────────────────────
        if ($isPackage) {
            $rules = [
                'nama'                   => 'required|string|max:255',
                'identitas_perusahaan'   => 'nullable|string|max:255',
                'nama_perusahaan'        => 'required|string|max:255',
                'email'                  => 'required|email|max:255',
                'alamat_usaha'           => 'required|string',
                'bidang_usaha'           => 'required|string|max:255',
                'keperluan'              => 'required|string',
                'payment_proof'          => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ];

            $request->validate($rules);

            // Set default values for package purchase
            $request->merge([
                'durasi'   => 60,
                'tanggal'  => null,
                'jam'      => null,
                'peserta'  => 1,
            ]);

            // Create booking for package purchase (no scheduling needed)
            MeetingRoomBooking::create([
                'user_id'        => Auth::id(),
                'source_type'    => 'manual',
                'benefit_id'     => null,
                'name'           => $request->nama,
                'date'           => null,
                'start_time'     => null,
                'duration'       => 60,
                'participants'   => 1,
                'status'         => 'pending',
                'payment_proof'  => $request->file('payment_proof')->store('payment_proofs', 'public'),
                'payment_status' => 'pending',
                'nama_perusahaan' => $request->nama_perusahaan ?? $request->identitas_perusahaan,
                'email'           => $request->email,
                'alamat_usaha'    => $request->alamat_usaha,
                'bidang_usaha'    => $request->bidang_usaha,
                'keperluan'       => $request->keperluan,
            ]);

            return redirect()->route('customer.meeting-room.index')
                ->with('success', 'Pembelian Paket Meeting Room (60 Jam) berhasil! Menunggu konfirmasi pembayaran dari admin.');
        }

        // ── REGULAR RESERVATION FLOW ──────────────────────────────────────────
        $rules = [
            'nama'            => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'alamat_usaha'    => 'required|string',
            'bidang_usaha'    => 'required|string|max:255',
            'keperluan'       => 'required|string',
            'tanggal'         => 'required|date',
            'jam'             => 'required',
            'peserta'         => 'required|integer|min:1',
            'durasi'          => 'required|integer|min:1',
            'use_quota'       => 'nullable|boolean',
        ];

        // Payment proof only required for manual reservations without quota/benefit
        if (!$request->input('use_quota') && !$this->getActiveBenefit()) {
            $rules['payment_proof'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        }

        $request->validate($rules);

        // Double-booking guard (all types)
        $conflict = MeetingRoomBooking::where('date', $request->tanggal)
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
            MeetingRoomBooking::create([
                'user_id'        => Auth::id(),
                'source_type'    => 'benefit',
                'benefit_id'     => $benefit->id,
                'name'           => $request->nama,
                'date'           => $request->tanggal,
                'start_time'     => $request->jam,
                'duration'       => $request->durasi,
                'participants'   => $request->peserta,
                'status'         => 'pending',         // admin must approve first
                'payment_status' => 'approved',        // no payment needed
                'payment_proof'  => null,
                'nama_perusahaan' => $request->nama_perusahaan,
                'email'           => $request->email,
                'alamat_usaha'    => $request->alamat_usaha,
                'bidang_usaha'    => $request->bidang_usaha,
                'keperluan'       => $request->keperluan,
            ]);

            return redirect()->route('customer.meeting-room.index')
                ->with('success', '✅ Reservasi Meeting Room (Benefit Paket PT) berhasil diajukan! Menunggu persetujuan admin.');
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
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        MeetingRoomBooking::create([
            'user_id'        => Auth::id(),
            'source_type'    => 'manual',
            'benefit_id'     => null,
            'name'           => $request->nama,
            'date'           => $request->tanggal,
            'start_time'     => $request->jam,
            'duration'       => $request->durasi,
            'participants'   => $request->peserta,
            'status'         => 'pending',
            'payment_proof'  => $path,
            'payment_status' => $request->input('use_quota') ? 'approved' : 'pending',
            'nama_perusahaan' => $request->nama_perusahaan,
            'email'           => $request->email,
            'alamat_usaha'    => $request->alamat_usaha,
            'bidang_usaha'    => $request->bidang_usaha,
            'keperluan'       => $request->keperluan,
        ]);

        $msg = $request->input('use_quota')
            ? 'Reservasi menggunakan quota berhasil dibuat! Status langsung disetujui.'
            : 'Reservasi berhasil dibuat! Menunggu konfirmasi pembayaran dari admin.';

        return redirect()->route('customer.meeting-room.index')->with('success', $msg);
    }

    // ── Admin Index ───────────────────────────────────────────────────────────

    public function adminIndex()
    {
        // TABLE 1 sub: All benefit-tagged reservations (filter in blade by status)
        $benefitBookings = MeetingRoomBooking::with(['user', 'benefit'])
            ->where('source_type', 'benefit')
            ->latest()
            ->get();

        // TABLE 2: Manual reservations only (existing — untouched)
        $bookings = MeetingRoomBooking::with('user')
            ->where(function ($q) {
                $q->where('source_type', 'manual')
                  ->orWhereNull('source_type');   // legacy rows
            })
            ->latest()
            ->get();

        // Benefit pool cards — only meeting-type benefits
        $benefits = RoomBenefit::with(['user', 'order'])
            ->whereIn('type', ['meeting', 'shared'])
            ->latest()->get();

        return view('admin.meeting-room.index', compact('bookings', 'benefits', 'benefitBookings'));
    }

    // ── Admin: Approve benefit reservation (status: pending → approved) ───────

    public function approveBenefitReservation($id)
    {
        $booking = MeetingRoomBooking::where('source_type', 'benefit')
            ->where('status', 'pending')
            ->findOrFail($id);

        $booking->update(['status' => 'approved']);

        return redirect()->back()->with('success', '✅ Reservasi benefit disetujui. Customer sudah bisa menggunakan ruangan.');
    }

    public function rejectBenefitReservation($id)
    {
        $booking = MeetingRoomBooking::where('source_type', 'benefit')
            ->where('status', 'pending')
            ->findOrFail($id);

        $booking->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Reservasi benefit ditolak.');
    }

    // ── Admin Detail ──────────────────────────────────────────────────────────

    public function adminDetail($id)
    {
        $booking = MeetingRoomBooking::with('user')->findOrFail($id);
        $logs    = RoomUsageLog::where('reservation_id', $id)
            ->where('room_type', 'meeting_room')
            ->orderBy('timestamp', 'asc')
            ->get();

        return view('admin.meeting-room.detail', compact('booking', 'logs'));
    }

    // ── Customer Index ────────────────────────────────────────────────────────

    public function customerDetail($id)
    {
        $booking = MeetingRoomBooking::with('user')->findOrFail($id);
        
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $logs = RoomUsageLog::where('reservation_id', $id)
            ->where('room_type', 'meeting_room')
            ->orderBy('timestamp', 'asc')
            ->get();

        return view('customer.meeting-room.detail', compact('booking', 'logs'));
    }

    public function customerIndex()
    {
        $benefitBookings = MeetingRoomBooking::where('user_id', Auth::id())
            ->where('source_type', 'benefit')
            ->latest()
            ->get();

        $manualBookings = MeetingRoomBooking::with('meetingRoomPackage')
            ->where('user_id', Auth::id())
            ->where(function ($q) {
                $q->where('source_type', 'manual')
                  ->orWhereNull('source_type');
            })
            ->latest()
            ->get();

        // Only meeting-type benefits for this user
        $benefits = RoomBenefit::with('order')
            ->where('user_id', Auth::id())
            ->whereIn('type', ['meeting', 'shared'])
            ->latest()
            ->get();

        return view('customer.meeting-room.index', compact('benefitBookings', 'manualBookings', 'benefits'));
    }

    // ── Payment actions (manual only — existing untouched) ────────────────────

    public function approvePayment($id)
    {
        $booking = MeetingRoomBooking::findOrFail($id);
        $booking->update(['payment_status' => 'approved']);
        return redirect()->back()->with('success', 'Pembayaran telah disetujui.');
    }

    public function rejectPayment($id)
    {
        $booking = MeetingRoomBooking::findOrFail($id);
        $booking->update(['payment_status' => 'rejected']);
        return redirect()->back()->with('success', 'Pembayaran telah ditolak.');
    }

    // ── Check-In ──────────────────────────────────────────────────────────────

    public function checkin($id)
    {
        $booking = MeetingRoomBooking::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Benefit booking: must be admin-approved
        if ($booking->source_type === 'benefit') {
            if ($booking->status !== 'approved' && $booking->status !== 'paused') {
                return redirect()->back()->with('error', 'Reservasi benefit belum disetujui admin.');
            }
        } else {
            // Manual: payment must be approved
            if ($booking->payment_status !== 'approved') {
                return redirect()->back()->with('error', 'Pembayaran belum dikonfirmasi admin. Check In tidak bisa dilakukan.');
            }
            if ($booking->status === 'checkin') {
                return redirect()->back()->with('error', 'Booking ini sudah di Check In.');
            }
        }

        if ($booking->is_expired) {
            return redirect()->back()->with('error', 'Masa berlaku reservasi sudah expired (lebih dari 1 tahun).');
        }

        if (($booking->duration * 3600 - $booking->used_seconds) <= 0) {
            return redirect()->back()->with('error', 'Waktu reservasi sudah habis.');
        }

        $booking->update([
            'status'      => 'checkin',
            'checkin_at'  => now(),
            'checkout_at' => null,
        ]);

        // Always write to room_usage_logs (existing)
        RoomUsageLog::create([
            'reservation_id' => $booking->id,
            'room_type'      => 'meeting_room',
            'type'           => 'checkin',
            'timestamp'      => now(),
        ]);

        // NO room_benefit_logs entry on check-in — only on check-out

        return redirect()->back()->with('success', 'User berhasil Check In ke ruangan.');
    }

    // ── Check-Out ─────────────────────────────────────────────────────────────

    public function checkout($id)
    {
        $booking = MeetingRoomBooking::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status !== 'checkin' || !$booking->checkin_at) {
            return redirect()->back()->with('error', 'Booking ini belum di Check In.');
        }

        $checkinAt      = $booking->checkin_at;
        $checkoutAt     = now();
        
        // ── VALIDATION: Prevent invalid duration ──────────────────────────────
        if ($checkoutAt->lessThan($checkinAt)) {
            return redirect()->back()->with('error', 'Checkout time tidak boleh lebih awal dari checkin time.');
        }
        
        $sessionSeconds = $checkinAt->diffInSeconds($checkoutAt);
        
        // Prevent negative duration (additional safety check)
        if ($sessionSeconds < 0) {
            return redirect()->back()->with('error', 'Durasi tidak valid. Silakan hubungi administrator.');
        }
        
        // ── BILLING ADJUSTMENT: Calculate rounded hours ───────────────────────
        $billingHours = $booking->calculateBillingHours($sessionSeconds);
        $billingSeconds = $billingHours * 3600;
        
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
            'room_type'      => 'meeting_room',
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
                    'room_type'        => 'meeting',
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
                    'room_type' => 'meeting_room',
                    'duration'  => $billingSeconds,
                    'tanggal'   => $checkoutAt,
                ]);
            }
        }

        // Display actual duration but billing is based on rounded hours
        $actualDuration = $booking->formatSeconds($sessionSeconds);
        $billingInfo = ($billingHours > 1) ? " (Ditagih: {$billingHours} jam)" : " (Ditagih: {$billingHours} jam)";
        
        return redirect()->back()->with('success', "User berhasil Check Out dari ruangan. Durasi aktual: {$actualDuration}{$billingInfo}");
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function getActiveBenefit(): ?RoomBenefit
    {
        if (!Auth::check()) return null;

        return RoomBenefit::where('user_id', Auth::id())
            ->where('is_active', true)
            ->whereIn('type', ['meeting', 'shared'])   // meeting-specific benefit
            ->where(function ($q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>', now());
            })
            ->whereRaw('used_minutes < total_minutes')
            ->latest()
            ->first();
    }
}
