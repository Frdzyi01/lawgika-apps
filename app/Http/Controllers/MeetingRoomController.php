<?php

namespace App\Http\Controllers;

use App\Models\MeetingRoomBooking;
use App\Models\RoomBenefit;
use App\Models\RoomBenefitLog;
use App\Models\RoomUsageLog;
use App\Models\UserRoomQuota;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingRoomController extends Controller
{
    public function __construct(
        protected WhatsAppService $whatsAppService
    ) {}

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
            // Check if user has an existing package booking
            $parentBooking = null;
            if ($benefit->order_id) {
                $parentBooking = MeetingRoomBooking::find($benefit->order_id);
            }
            if (!$parentBooking) {
                $parentBooking = MeetingRoomBooking::where('user_id', Auth::id())
                    ->whereIn('payment_status', ['approved'])
                    ->latest()
                    ->first();
            }

            if ($parentBooking) {
                $parentBooking->update([
                    'date'            => $request->tanggal,
                    'start_time'      => $request->jam,
                    'participants'    => $request->peserta,
                    'nama_perusahaan' => $request->nama_perusahaan ?? $parentBooking->nama_perusahaan,
                    'email'           => $request->email ?? $parentBooking->email,
                    'alamat_usaha'    => $request->alamat_usaha ?? $parentBooking->alamat_usaha,
                    'bidang_usaha'    => $request->bidang_usaha ?? $parentBooking->bidang_usaha,
                    'keperluan'       => $request->keperluan ?? $parentBooking->keperluan,
                    'status'          => 'pending', // Set to pending so Admin receives pending reservation request notification
                ]);

                return redirect()->route('customer.meeting-room.index')
                    ->with('success', 'Pengajuan reservasi Meeting Room berhasil dikirim! Menunggu konfirmasi admin.');
            }

            // Create initial benefit booking if no parent package booking exists
            MeetingRoomBooking::create([
                'user_id'        => Auth::id(),
                'source_type'    => 'benefit',
                'benefit_id'     => $benefit->id,
                'name'           => $request->nama,
                'date'           => $request->tanggal,
                'start_time'     => $request->jam,
                'duration'       => $request->durasi,
                'participants'   => $request->peserta,
                'status'         => 'approved',
                'payment_status' => 'approved',
                'payment_proof'  => null,
                'nama_perusahaan' => $request->nama_perusahaan,
                'email'           => $request->email,
                'alamat_usaha'    => $request->alamat_usaha,
                'bidang_usaha'    => $request->bidang_usaha,
                'keperluan'       => $request->keperluan,
            ]);

            return redirect()->route('customer.meeting-room.index')
                ->with('success', '✅ Reservasi Meeting Room berhasil diajukan!');
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

    public function adminIndex(Request $request)
    {
        $search = $request->input('search');

        // Load all bookings (both benefit and manual) for the unified index table
        $query = MeetingRoomBooking::with(['user', 'benefit'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('room_name', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(10);
        $bookings->appends(['search' => $search]);

        $benefits = RoomBenefit::with(['user', 'order'])
            ->whereIn('type', ['meeting', 'shared'])
            ->latest()->get();

        $pendingReservations = MeetingRoomBooking::with(['user', 'benefit'])
            ->where('status', 'pending')
            ->whereNotNull('date')
            ->latest()
            ->get();

        return view('admin.meeting-room.index', compact('bookings', 'benefits', 'pendingReservations'));
    }

    // ── Admin: Create & Store (CRM Flow) ──────────────────────────────────────

    public function adminCreate()
    {
        $occupiedRooms = MeetingRoomBooking::where('status', 'checkin')
            ->whereNotNull('room_name')
            ->pluck('room_name')
            ->unique()
            ->toArray();

        return view('admin.meeting-room.create', compact('occupiedRooms'));
    }

    public function adminStore(Request $request)
    {
        $rules = [
            'user_id'         => 'required|exists:users,id',
            'room_name'       => 'nullable|string',
            'date'            => 'required|date',
            'start_time'      => 'nullable',
            'participants'    => 'required|integer|min:1',
            'source_type'     => 'required|in:manual,benefit',
            'benefit_id'      => 'nullable|exists:room_benefits,id',
        ];

        $request->validate($rules);

        // Guard: Jika room_name diisi, cegah memilih jika sedang Check-In
        if ($request->filled('room_name')) {
            $currentlyOccupied = MeetingRoomBooking::where('room_name', $request->room_name)
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
        
        // Paket Meeting Room adalah sistem kuota tahunan (60 Jam)
        $durationHours = 60;

        // Get user for fallback data
        $user = \App\Models\User::find($request->user_id);

        $booking = MeetingRoomBooking::create([
            'user_id'         => $user->id,
            'created_by'      => Auth::id(),
            'name'            => $user->name,
            'nama_perusahaan' => $user->company_name,
            'email'           => $user->email,
            'alamat_usaha'    => $user->address,
            'bidang_usaha'    => $user->business_type,
            'keperluan'       => $request->notes, // used as notes internally
            'notes'           => $request->notes,
            'room_name'       => $request->room_name,
            'date'            => $request->date,
            'start_time'      => $request->start_time,
            'end_time'        => $end,
            'duration'        => $durationHours,
            'participants'    => $request->participants,
            'source_type'     => $request->source_type,
            'benefit_id'      => $request->source_type === 'benefit' ? $request->benefit_id : null,
            'status'          => 'approved',
            'payment_status'  => 'approved',
            'payment_method'  => $request->payment_method,
        ]);

        return redirect('admin/meeting-room')->with('success', '✅ Reservasi Meeting Room berhasil ditambahkan oleh Admin.');
    }

    // ── Admin: Approve benefit reservation (status: pending → approved) ───────

    public function approveBenefitReservation($id)
    {
        $booking = MeetingRoomBooking::findOrFail($id);
        $booking->update([
            'status'         => 'approved',
            'payment_status' => 'approved',
        ]);

        return redirect()->back()->with('success', '✅ Reservasi disetujui! Client dapat melakukan Check In.');
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
        $bookings = MeetingRoomBooking::with('meetingRoomPackage')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        // Only meeting-type benefits for this user
        $benefits = RoomBenefit::with('order')
            ->where('user_id', Auth::id())
            ->whereIn('type', ['meeting', 'shared'])
            ->latest()
            ->get();

        return view('customer.meeting-room.index', compact('bookings', 'benefits'));
    }

    // ── Payment actions (manual only — existing untouched) ────────────────────

    public function approvePayment($id)
    {
        $booking = MeetingRoomBooking::findOrFail($id);
        $booking->update([
            'payment_status' => 'approved',
            'status'         => 'approved',
        ]);

        if ($booking->user_id && ($booking->duration >= 10 || empty($booking->date))) {
            RoomBenefit::firstOrCreate([
                'user_id'                 => $booking->user_id,
                'meeting_room_booking_id' => $booking->id,
                'type'                    => 'meeting',
            ], [
                'paket'         => 'Paket Meeting Room (' . ($booking->duration ?: 60) . ' Jam)',
                'total_minutes' => ($booking->duration ?: 60) * 60,
                'used_minutes'  => round($booking->used_seconds / 60),
                'is_active'     => true,
                'expired_at'    => \Carbon\Carbon::parse($booking->created_at)->addYear(),
            ]);
        }

        return redirect()->back()->with('success', 'Pembayaran telah disetujui.');
    }

    public function rejectPayment($id)
    {
        $booking = MeetingRoomBooking::findOrFail($id);
        $booking->update(['payment_status' => 'rejected']);
        return redirect()->back()->with('success', 'Pembayaran telah ditolak.');
    }

    // ── Check-In ──────────────────────────────────────────────────────────────

    public function checkin(Request $request, $id)
    {
        $booking = MeetingRoomBooking::findOrFail($id);

        if (!Auth::user()->hasAdminAccess() && $booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $roomName  = $request->input('room_name', $booking->room_name ?: 'Ruang Meetingroom 1');
        $dateInput = $request->input('date', $booking->date ? \Carbon\Carbon::parse($booking->date)->format('Y-m-d') : date('Y-m-d'));
        $startTime = $request->input('start_time', $booking->start_time ?: date('H:i'));
        $endTime   = $request->input('end_time');

        // Guard: Cegah Check In jika ruangan tersebut sedang digunakan (Check In) oleh booking/client lain
        $occupiedByOther = MeetingRoomBooking::where('room_name', $roomName)
            ->where('status', 'checkin')
            ->where('id', '!=', $booking->id)
            ->exists();

        if ($occupiedByOther) {
            return redirect()->back()->with('error', "🚫 Gagal Check In: {$roomName} saat ini sedang digunakan (Check In) oleh client lain. Selesaikan sesi Check Out pada client sebelumnya terlebih dahulu.");
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

        // Always write to room_usage_logs (existing)
        RoomUsageLog::create([
            'reservation_id' => $booking->id,
            'room_type'      => 'meeting_room',
            'type'           => 'checkin',
            'timestamp'      => now(),
        ]);

        // NO room_benefit_logs entry on check-in — only on check-out

        // ── WhatsApp Notification ─────────────────────────────────────────────
        $waMessage = '';
        try {
            if (Auth::user()->hasAdminAccess()) {
                $waLog = app(\App\Services\WhatsAppService::class)->notifyMeetingRoomCheckIn($booking);
                if ($waLog && $waLog->status === \App\Models\WhatsappLog::STATUS_SUCCESS) {
                    $waMessage = ' WhatsApp notifikasi berhasil dikirim.';
                } elseif ($waLog) {
                    $waMessage = ' Tetapi WhatsApp gagal dikirim.';
                }
            }
        } catch (\Exception $e) {
            $waMessage = ' Tetapi WhatsApp gagal dikirim.';
        }

        return redirect()->back()->with('success', 'User berhasil Check In ke ruangan.' . $waMessage);
    }

    // ── Check-Out ─────────────────────────────────────────────────────────────

    public function checkout($id)
    {
        $booking = MeetingRoomBooking::findOrFail($id);

        if (!Auth::user()->hasAdminAccess() && $booking->user_id !== Auth::id()) {
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
            'room_type'      => 'meeting_room',
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
                'room_type'        => 'meeting',
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
                    'room_type' => 'meeting_room',
                    'duration'  => $billingSeconds,
                    'tanggal'   => $checkoutAt,
                ]);
            }
        }

        // Display actual duration but billing is based on rounded hours
        $actualDuration = $booking->formatSeconds($sessionSeconds);
        $billingInfo = ($billingHours > 1) ? " (Ditagih: {$billingHours} jam)" : " (Ditagih: {$billingHours} jam)";
        
        // ── WhatsApp Notification ─────────────────────────────────────────────
        $waMessage = '';
        try {
            $waLog = app(\App\Services\WhatsAppService::class)->notifyMeetingRoomCheckOut($booking, $actualDuration, $billingHours, $checkinAt, $checkoutAt);
            if ($waLog && $waLog->status === \App\Models\WhatsappLog::STATUS_SUCCESS) {
                $waMessage = ' WhatsApp notifikasi berhasil dikirim.';
            } elseif ($waLog) {
                $waMessage = ' Tetapi WhatsApp gagal dikirim.';
            }
        } catch (\Exception $e) {
            Log::error('MeetingRoomController::checkout - Exception WA: ' . $e->getMessage());
            $waMessage = ' Tetapi WhatsApp gagal dikirim.';
        }

        return redirect()->back()->with('success', "User berhasil Check Out dari ruangan. Durasi aktual: {$actualDuration}{$billingInfo}." . $waMessage);
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function getActiveBenefit(): ?RoomBenefit
    {
        if (!Auth::check()) return null;

        $benefit = RoomBenefit::where('user_id', Auth::id())
            ->where('is_active', true)
            ->whereIn('type', ['meeting', 'shared'])   // meeting-specific benefit
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

        // Check if user has an approved standalone package purchase (e.g. 60 Jam) in MeetingRoomBooking
        $packageBooking = MeetingRoomBooking::where('user_id', Auth::id())
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
                'type'     => 'meeting',
            ], [
                'paket'         => 'Paket Meeting Room (' . ($packageBooking->duration ?: 60) . ' Jam)',
                'total_minutes' => ($packageBooking->duration ?: 60) * 60,
                'used_minutes'  => round($packageBooking->used_seconds / 60),
                'is_active'     => true,
                'expired_at'    => \Carbon\Carbon::parse($packageBooking->created_at)->addYear(),
            ]);
        }

        return null;
    }
}
