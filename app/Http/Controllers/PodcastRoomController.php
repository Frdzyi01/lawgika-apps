<?php

namespace App\Http\Controllers;

use App\Models\PodcastRoomBooking;
use App\Models\RoomBenefit;
use App\Models\RoomBenefitLog;
use App\Models\RoomUsageLog;
use App\Models\UserRoomQuota;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PodcastRoomController extends Controller
{
    public function __construct(
        protected WhatsAppService $whatsAppService
    ) {}

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

        // Jika user punya benefit tapi memilih bayar mandiri
        if (!$request->input('use_quota') && $request->input('pay_manually') == '1') {
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

        // Jika user sengaja memilih bayar mandiri (skip benefit)
        if ($request->input('pay_manually') == '1') {
            $benefit = null; // Paksa masuk manual flow
        }

        if ($benefit) {
            // Check if user has an existing package booking
            $parentBooking = null;
            if ($benefit->order_id) {
                $parentBooking = PodcastRoomBooking::find($benefit->order_id);
            }
            if (!$parentBooking) {
                $parentBooking = PodcastRoomBooking::where('user_id', Auth::id())
                    ->whereIn('payment_status', ['approved'])
                    ->latest()
                    ->first();
            }

            if ($parentBooking) {
                $parentBooking->update([
                    'date'          => $request->tanggal,
                    'start_time'    => $request->jam,
                    'podcast_title' => $request->podcast_title ?? $parentBooking->podcast_title,
                    'status'        => 'approved',
                ]);

                return redirect()->route('customer.podcast-room.index')
                    ->with('success', 'Jadwal reservasi Ruang Podcast berhasil disimpan!');
            }

            // Create initial benefit booking if no parent package booking exists
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
                'status'         => 'approved',
                'payment_status' => 'approved',
                'payment_proof'  => null,
            ]);

            return redirect()->route('customer.podcast-room.index')
                ->with('success', "✅ Reservasi Ruang Podcast berhasil diajukan! Order: {$orderNum}.");
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

    public function adminIndex(Request $request)
    {
        $search = $request->input('search');

        // All reservations (manual and benefit)
        $query = PodcastRoomBooking::with(['user', 'benefit'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('room_name', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(10);
        $bookings->appends(['search' => $search]);

        $pendingReservations = PodcastRoomBooking::with(['user', 'benefit'])
            ->where('status', 'pending')
            ->whereNotNull('date')
            ->latest()
            ->get();

        return view('admin.podcast-room.index', compact('bookings', 'pendingReservations'));
    }

    // ── Admin: Create & Store (CRM Flow) ──────────────────────────────────────

    public function adminCreate()
    {
        $occupiedRooms = PodcastRoomBooking::where('status', 'checkin')
            ->whereNotNull('room_name')
            ->pluck('room_name')
            ->unique()
            ->toArray();

        return view('admin.podcast-room.create', [
            'packages'      => self::$packages,
            'occupiedRooms' => $occupiedRooms,
        ]);
    }

    public function adminStore(Request $request)
    {
        $rules = [
            'user_id'         => 'required|exists:users,id',
            'room_name'       => 'nullable|string',
            'podcast_title'   => 'nullable|string|max:255',
            'date'            => 'required|date',
            'start_time'      => 'nullable',
            'participants'    => 'required|integer|min:1',
            'source_type'     => 'required|in:manual,benefit',
            'benefit_id'      => 'nullable|exists:room_benefits,id',
        ];

        $request->validate($rules);

        // Guard: Jika room_name diisi, cegah memilih jika sedang Check-In
        if ($request->filled('room_name')) {
            $currentlyOccupied = PodcastRoomBooking::where('room_name', $request->room_name)
                ->where('status', 'checkin')
                ->exists();

            if ($currentlyOccupied) {
                return back()->withInput()->withErrors([
                    'room_name' => "🚫 {$request->room_name} saat ini sedang digunakan (Check In) oleh client lain."
                ]);
            }
        }

        $start = $request->filled('start_time') ? \Carbon\Carbon::parse($request->date . ' ' . $request->start_time) : null;
        $end = $start ? $start->copy()->addHour() : null;
        
        // Paket Podcast Room adalah sistem kuota tahunan (12 Jam)
        $durationHours = 12;

        // Get user for fallback data
        $user = \App\Models\User::find($request->user_id);

        $orderNum = 'PODCAST-' . ($request->source_type === 'benefit' ? 'BNF-' : '') . date('Ymd') . '-' . strtoupper(Str::random(5));

        // Podcast Pricing logic (same as store)
        $price = 0;
        if ($request->source_type === 'manual') {
            if ($durationHours === 1) {
                $price = 500000;
            } elseif ($durationHours === 2) {
                $price = 800000;
            } else {
                $price = 800000 + (($durationHours - 2) * 300000);
            }
        }

        $booking = PodcastRoomBooking::create([
            'user_id'         => $user->id,
            'created_by'      => Auth::id(),
            'name'            => $user->name,
            'order_number'    => $orderNum,
            'podcast_title'   => $request->podcast_title,
            'notes'           => $request->notes,
            'room_name'       => $request->room_name,
            'date'            => $request->date,
            'start_time'      => $request->start_time,
            'end_time'        => $end,
            'duration'        => $durationHours,
            'participants'    => $request->participants,
            'package'         => $durationHours . 'jam',
            'total_price'     => $price,
            'source_type'     => $request->source_type,
            'benefit_id'      => $request->source_type === 'benefit' ? $request->benefit_id : null,
            'status'          => 'approved',
            'payment_status'  => 'approved',
            'payment_method'  => $request->payment_method,
        ]);

        return redirect('admin/podcast-room')->with('success', '✅ Reservasi Ruang Podcast berhasil ditambahkan oleh Admin.');
    }

    // ── Admin: Approve / Reject benefit reservation ───────────────────────────

    public function approveBenefitReservation($id)
    {
        $booking = PodcastRoomBooking::find($id);
        if ($booking) {
            $booking->update([
                'status'         => 'approved',
                'payment_status' => 'approved',
            ]);
        }

        return redirect('admin/podcast-room')->with('success', '✅ Reservasi Ruang Podcast disetujui!');
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
        $booking = PodcastRoomBooking::findOrFail($id);
        $booking->update([
            'payment_status' => 'approved',
            'status'         => 'approved',
        ]);

        if ($booking->user_id && ($booking->duration >= 10 || empty($booking->date))) {
            RoomBenefit::firstOrCreate([
                'user_id'                 => $booking->user_id,
                'podcast_room_booking_id' => $booking->id,
                'type'                    => 'podcast',
            ], [
                'paket'         => 'Paket Podcast Room (' . ($booking->duration ?: 60) . ' Jam)',
                'total_minutes' => ($booking->duration ?: 60) * 60,
                'used_minutes'  => round($booking->used_seconds / 60),
                'is_active'     => true,
                'expired_at'    => \Carbon\Carbon::parse($booking->created_at)->addYear(),
            ]);
        }

        return back()->with('success', 'Pembayaran disetujui.');
    } 

    public function rejectPayment($id)
    {
        PodcastRoomBooking::findOrFail($id)->update(['payment_status' => 'rejected']);
        return back()->with('success', 'Pembayaran ditolak.');
    }

    // ── Check-In ──────────────────────────────────────────────────────────────

    public function checkin(Request $request, $id)
    {
        $booking = PodcastRoomBooking::findOrFail($id);

        if (!Auth::user()->hasAdminAccess() && $booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $roomName  = $request->input('room_name', $booking->room_name ?: 'Podcast Studio Lawgika');
        $dateInput = $request->input('date', $booking->date ? \Carbon\Carbon::parse($booking->date)->format('Y-m-d') : date('Y-m-d'));
        $startTime = $request->input('start_time', $booking->start_time ?: date('H:i'));
        $endTime   = $request->input('end_time');

        // Guard: Cegah Check In jika ruangan tersebut sedang digunakan (Check In) oleh booking/client lain
        $occupiedByOther = PodcastRoomBooking::where('room_name', $roomName)
            ->where('status', 'checkin')
            ->where('id', '!=', $booking->id)
            ->exists();

        if ($occupiedByOther) {
            return back()->with('error', "🚫 Gagal Check In: {$roomName} saat ini sedang digunakan (Check In) oleh client lain. Selesaikan sesi Check Out pada client sebelumnya terlebih dahulu.");
        }

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

        $bookingDate = \Carbon\Carbon::parse($dateInput)->format('Y-m-d');
        $endTimeVal  = $endTime ? \Carbon\Carbon::parse($bookingDate . ' ' . $endTime) : ($startTime ? \Carbon\Carbon::parse($bookingDate . ' ' . $startTime)->addHour() : null);

        $booking->update([
            'room_name'   => $roomName,
            'date'        => $bookingDate,
            'start_time'  => $startTime,
            'end_time'    => $endTimeVal,
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

        // ── WhatsApp Notification ─────────────────────────────────────────────
        $waMessage = '';
        try {
            if (Auth::user()->hasAdminAccess()) {
                $waLog = app(\App\Services\WhatsAppService::class)->notifyPodcastRoomCheckIn($booking->fresh());
                if ($waLog && $waLog->status === \App\Models\WhatsappLog::STATUS_SUCCESS) {
                    $waMessage = ' WhatsApp notifikasi berhasil dikirim.';
                } elseif ($waLog) {
                    $waMessage = ' Tetapi WhatsApp gagal dikirim.';
                }
            }
        } catch (\Exception $e) {
            $waMessage = ' Tetapi WhatsApp gagal dikirim.';
        }

        return back()->with('success', 'User berhasil Check In ke ' . $roomName . '.' . $waMessage);
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
        
        $billingHours = $booking->calculateBillingHours($sessionSeconds);
        $billingSeconds = $billingHours * 3600;
        
        $prevUsed       = $booking->total_used_seconds > 0 ? $booking->total_used_seconds : ($booking->total_used_minutes * 60);
        $newTotalUsed   = $prevUsed + $billingSeconds;
        
        $booking->update([
            'status'             => 'paused', // Set to paused so client can check in again using remaining quota
            'total_used_seconds' => $newTotalUsed,
            'checkout_at'        => $checkoutAt,
            'checkin_at'         => null,
        ]);

        RoomUsageLog::create([
            'reservation_id' => $booking->id,
            'room_type'      => 'podcast_room',
            'type'           => 'checkout',
            'timestamp'      => $checkoutAt,
        ]);

        // ── Deduct from benefit pool ──────────────────────────────────────────
        $benefit = $booking->benefit_id ? RoomBenefit::find($booking->benefit_id) : RoomBenefit::where('user_id', $booking->user_id)->first();
        if ($benefit) {
            $billingMinutes = $billingHours * 60;
            $benefit->used_minutes = min($benefit->total_minutes, $benefit->used_minutes + $billingMinutes);
            $benefit->save();

            RoomBenefitLog::create([
                'benefit_id'       => $benefit->id,
                'room_type'        => 'podcast',
                'duration_minutes' => $billingMinutes,
                'action'           => 'checkout',
                'action_at'        => $checkoutAt,
                'checkin_at'       => $checkinAt,
                'checkout_at'      => $checkoutAt,
            ]);
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
        
        // ── WhatsApp Notification ─────────────────────────────────────────────
        $waMessage = '';
        try {
            $waLog = app(\App\Services\WhatsAppService::class)->notifyPodcastRoomCheckOut($booking, $actualDuration, $billingHours, $checkinAt, $checkoutAt);
            if ($waLog && $waLog->status === \App\Models\WhatsappLog::STATUS_SUCCESS) {
                $waMessage = ' WhatsApp notifikasi berhasil dikirim.';
            } elseif ($waLog) {
                $waMessage = ' Tetapi WhatsApp gagal dikirim.';
            }
        } catch (\Exception $e) {
            Log::error('PodcastRoomController::checkout - Exception WA: ' . $e->getMessage());
            $waMessage = ' Tetapi WhatsApp gagal dikirim.';
        }

        return back()->with('success', "User berhasil Check Out dari ruangan. Durasi aktual: {$actualDuration}{$billingInfo}." . $waMessage);
    }

    // ── Customer Index ────────────────────────────────────────────────────────

    public function customerIndex()
    {
        $bookings = PodcastRoomBooking::where('user_id', Auth::id())
            ->latest()
            ->get();

        // Only podcast-type benefits for this user
        $benefits = RoomBenefit::with('order')
            ->where('user_id', Auth::id())
            ->whereIn('type', ['podcast', 'shared'])
            ->latest()
            ->get();

        return view('customer.podcast-room.index', compact('bookings', 'benefits'));
    }

    public function customerDetail($id)
    {
        $booking = PodcastRoomBooking::with('user')->findOrFail($id);
        
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $logs = \App\Models\RoomUsageLog::where('reservation_id', $id)
            ->where('room_type', 'podcast_room')
            ->orderBy('timestamp', 'asc')
            ->get();

        return view('customer.podcast-room.detail', compact('booking', 'logs'));
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function getActiveBenefit(): ?RoomBenefit
    {
        if (!Auth::check()) return null;

        $benefit = RoomBenefit::where('user_id', Auth::id())
            ->where('is_active', true)
            ->whereIn('type', ['podcast', 'shared'])   // podcast-specific benefit
            ->where(function ($q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>', now());
            })
            ->whereRaw('used_minutes < total_minutes')
            ->latest()
            ->first();

        if ($benefit) {
            return $benefit;
        }

        // Check if user has an approved standalone package purchase (e.g. 60 Jam) in PodcastRoomBooking
        $packageBooking = PodcastRoomBooking::where('user_id', Auth::id())
            ->whereIn('payment_status', ['approved'])
            ->where('status', '!=', 'rejected')
            ->where(function($q) {
                $q->whereNull('date')->orWhere('duration', '>=', 10);
            })
            ->latest()
            ->first();

        if ($packageBooking && $packageBooking->remaining_seconds > 0) {
            return RoomBenefit::firstOrCreate([
                'user_id'  => Auth::id(),
                'order_id' => $packageBooking->id,
                'type'     => 'podcast',
            ], [
                'paket'         => 'Paket Podcast Room (' . ($packageBooking->duration ?: 60) . ' Jam)',
                'total_minutes' => ($packageBooking->duration ?: 60) * 60,
                'used_minutes'  => round($packageBooking->used_seconds / 60),
                'is_active'     => true,
                'expired_at'    => \Carbon\Carbon::parse($packageBooking->created_at)->addYear(),
            ]);
        }

        return null;
    }
}
