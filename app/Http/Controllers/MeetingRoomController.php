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
        $date      = $request->get('date');
        $roomName  = $request->get('room_name');
        $excludeId = $request->get('exclude_id');

        $occupied = $this->calculateOccupiedHours($date, $roomName, $excludeId);
        return response()->json($occupied);
    }

    public function calculateOccupiedHours($date, $roomName = null, $excludeId = null)
    {
        if (!$date) return [];
        $occupiedHours = [];

        // 1. Active / Approved bookings with date & start_time
        $bookings = MeetingRoomBooking::whereDate('date', $date)
            ->whereNotNull('start_time')
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->when($roomName, fn($q) => $q->where('room_name', $roomName))
            ->get();

        foreach ($bookings as $b) {
            $sh = (int) substr($b->start_time, 0, 2);
            if ($b->end_time) {
                $eh = (int) \Carbon\Carbon::parse($b->end_time)->format('H');
                if ($eh === 0 && $sh > 0) {
                    $eh = 24;
                } elseif ($eh === 0 && \Carbon\Carbon::parse($b->end_time)->day > \Carbon\Carbon::parse($b->date)->day) {
                    $eh = 24;
                }
                if ($eh <= $sh) {
                    $eh = $sh + 1;
                }
            } else {
                $eh = $sh + min($b->duration, 2);
            }

            for ($h = $sh; $h < $eh; $h++) {
                $occupiedHours[] = sprintf('%02d:00', $h);
            }
        }

        // 2. Completed / checkout logs on that date
        $logsQuery = RoomUsageLog::where('room_type', 'meeting_room')
            ->whereDate('timestamp', $date)
            ->when($excludeId, fn($q) => $q->where('reservation_id', '!=', $excludeId))
            ->orderBy('reservation_id')
            ->orderBy('timestamp');

        if ($roomName) {
            $logsQuery->whereIn('reservation_id', function($sub) use ($roomName) {
                $sub->select('id')->from('meeting_room_bookings')->where('room_name', $roomName);
            });
        }

        $logs = $logsQuery->get();

        $checkins = [];
        foreach ($logs as $log) {
            if ($log->type === 'checkin') {
                $checkins[$log->reservation_id] = $log;
            } elseif ($log->type === 'checkout' && isset($checkins[$log->reservation_id])) {
                $cIn  = $checkins[$log->reservation_id]->timestamp;
                $cOut = $log->timestamp;
                $sh   = (int) $cIn->format('H');
                $outH = (int) $cOut->format('H');
                $outM = (int) $cOut->format('i');
                $outS = (int) $cOut->format('s');

                $eh = ($outM > 0 || $outS > 0) ? ($outH + 1) : $outH;
                if ($eh <= $sh) {
                    $eh = $sh + 1;
                }
                if ($eh > 24) $eh = 24;

                for ($h = $sh; $h < $eh; $h++) {
                    $occupiedHours[] = sprintf('%02d:00', $h);
                }
                unset($checkins[$log->reservation_id]);
            }
        }

        return array_values(array_unique($occupiedHours));
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
            // Check if user has sufficient remaining benefit quota
            $remainingMinutes = $benefit->total_minutes - $benefit->used_minutes;
            $requestedMinutes = $request->durasi * 60;
            if ($remainingMinutes < $requestedMinutes) {
                $remHours = floor($remainingMinutes / 60);
                return back()->withInput()->withErrors(['durasi' => "Sisa kuota Meeting Room Anda ({$remHours} jam) tidak mencukupi untuk durasi yang diajukan ({$request->durasi} jam)."]);
            }

            // Check if user already has a master package booking
            $masterBooking = MeetingRoomBooking::where('user_id', Auth::id())
                ->where(function($q) use ($benefit) {
                    $q->where('duration', '>=', 10);
                    if ($benefit && $benefit->meeting_room_booking_id) {
                        $q->orWhere('id', $benefit->meeting_room_booking_id);
                    }
                })
                ->where('status', '!=', 'selesai')
                ->latest()
                ->first();

            if ($masterBooking) {
                $masterBooking->update([
                    'date'            => $request->tanggal,
                    'start_time'      => $request->jam,
                    'room_name'       => $request->room_name ?: ($masterBooking->room_name ?: 'Ruang Meetingroom Utama'),
                    'nama_perusahaan' => $request->nama_perusahaan ?: $masterBooking->nama_perusahaan,
                    'keperluan'       => $request->keperluan ?: $masterBooking->keperluan,
                    'participants'    => $request->peserta ?: $masterBooking->participants,
                    'status'          => 'pending', // Menunggu approval admin
                ]);

                return redirect()->route('customer.meeting-room.index')
                    ->with('success', "✅ Pengajuan reservasi Meeting Room ({$request->durasi} Jam) berhasil diajukan! Menunggu persetujuan admin.");
            }

            $createdBooking = MeetingRoomBooking::create([
                'user_id'         => Auth::id(),
                'source_type'     => 'benefit',
                'benefit_id'      => $benefit->id,
                'name'            => $request->nama,
                'date'            => $request->tanggal,
                'start_time'      => $request->jam,
                'duration'        => round($benefit->total_minutes / 60) ?: 60,
                'participants'    => $request->peserta,
                'status'          => 'pending', // Menunggu approval admin
                'payment_status'  => 'approved', // Bebas biaya (Benefit)
                'payment_proof'   => null,
                'nama_perusahaan' => $request->nama_perusahaan,
                'email'           => $request->email,
                'alamat_usaha'    => $request->alamat_usaha,
                'bidang_usaha'    => $request->bidang_usaha,
                'keperluan'       => $request->keperluan,
            ]);

            $benefit->update(['meeting_room_booking_id' => $createdBooking->id]);

            return redirect()->route('customer.meeting-room.index')
                ->with('success', '✅ Pengajuan reservasi Meeting Room berhasil dikirim! Menunggu konfirmasi persetujuan admin.');
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

        // Auto-sync: Ensure every active meeting RoomBenefit has a corresponding MeetingRoomBooking
        $activeMeetingBenefits = RoomBenefit::with(['user', 'order'])
            ->whereIn('type', ['meeting', 'shared'])
            ->where('is_active', true)
            ->get();

        foreach ($activeMeetingBenefits as $bnf) {
            $hasBooking = MeetingRoomBooking::where('benefit_id', $bnf->id)->exists();
            if (!$hasBooking) {
                $user = $bnf->user;
                $clientName = $user?->company_name ?? ($user?->pic_name ?? ($user?->name ?? 'Client'));
                $companyName = $user?->company_name ?? null;

                $mb = MeetingRoomBooking::create([
                    'user_id'            => $bnf->user_id,
                    'source_type'        => 'benefit',
                    'benefit_id'         => $bnf->id,
                    'order_number'       => $bnf->order?->order_number ?? ('#MR-BNF-' . str_pad($bnf->id, 5, '0', STR_PAD_LEFT)),
                    'name'               => $clientName,
                    'nama_perusahaan'    => $companyName,
                    'email'              => $user?->email,
                    'date'               => null,
                    'start_time'         => null,
                    'end_time'           => null,
                    'duration'           => round($bnf->total_minutes / 60) ?: 48,
                    'total_used_seconds' => $bnf->used_minutes * 60,
                    'participants'       => 1,
                    'package'            => $bnf->paket,
                    'status'             => 'approved',
                    'payment_status'     => 'approved',
                    'total_price'        => 0,
                    'notes'              => 'Benefit kuota dari ' . ($bnf->order?->order_number ?? 'Order'),
                ]);

                $bnf->update(['meeting_room_booking_id' => $mb->id]);
            }
        }

        // Load only approved, active, or completed bookings for Table 2 (exclude pending approvals)
        $query = MeetingRoomBooking::with(['user', 'benefit'])
            ->where(function ($q) {
                $q->whereIn('status', ['approved', 'checkin', 'paused', 'selesai'])
                  ->where('payment_status', 'approved');
            })
            ->latest();

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
            ->where(function ($q) {
                $q->where('status', 'pending')
                  ->orWhere('payment_status', 'pending');
            })
            ->latest()
            ->get();

        return view('admin.meeting-room.index', compact('bookings', 'benefits', 'pendingReservations'));
    }

    // ── Admin: Calendar (Read-Only Visualization) ─────────────────────────────

    public function calendar()
    {
        return view('admin.meeting-room.calendar');
    }

    public function calendarEvents(Request $request)
    {
        $start = $request->input('start');
        $end   = $request->input('end');

        if (!$start || !$end) {
            return response()->json([]);
        }

        $events = collect();

        // 1. Scheduled / Active Bookings (Pending, Approved, Check In)
        $bookings = MeetingRoomBooking::with('user')
            ->whereNotNull('date')
            ->whereNotNull('start_time')
            ->whereBetween('date', [$start, $end])
            ->whereNotIn('status', ['rejected'])
            ->get();

        foreach ($bookings as $b) {
            $startTime = $b->start_time ? \Carbon\Carbon::parse($b->start_time)->format('H:i') : null;
            $endTime   = $b->end_time ? \Carbon\Carbon::parse($b->end_time)->format('H:i') : null;

            // Fallback: if no end_time, calculate from start_time + duration (max 4h for display)
            if (!$endTime && $startTime) {
                $displayDuration = min($b->duration ?: 2, 2);
                $eh = (int) substr($startTime, 0, 2) + $displayDuration;
                $endTime = $eh >= 24 ? '24:00' : sprintf('%02d:00', $eh);
            }

            // If currently checked in and current time is past scheduled end_time (ngaret sedang berlangsung)
            if ($b->status === 'checkin' && $b->checkin_at && $startTime) {
                $nowH = (int) now()->format('H');
                $nowM = (int) now()->format('i');
                $currentEndH = ($nowM > 0) ? ($nowH + 1) : $nowH;
                $schedEndH = (int) substr($endTime, 0, 2);
                if ($currentEndH > $schedEndH) {
                    $endTime = $currentEndH >= 24 ? '24:00' : sprintf('%02d:00', $currentEndH);
                }
            }

            if ($startTime) {
                $events->push([
                    'id'             => $b->id,
                    'title'          => !empty($b->name) ? $b->name : ($b->user->name ?? 'Reservasi Meeting'),
                    'date'           => \Carbon\Carbon::parse($b->date)->format('Y-m-d'),
                    'start_time'     => $startTime,
                    'end_time'       => $endTime,
                    'order_number'   => $b->id,
                    'room_name'      => $b->room_name ?: 'Ruang Meeting',
                    'keperluan'      => $b->keperluan,
                    'status'         => $b->status,
                    'payment_status' => $b->payment_status,
                    'detail_url'     => url('admin/meeting-room/' . $b->id . '/detail'),
                ]);
            }
        }

        // 2. Completed / Checked-out Sessions History from RoomUsageLog
        $logs = RoomUsageLog::where('room_type', 'meeting_room')
            ->whereDate('timestamp', '>=', $start)
            ->whereDate('timestamp', '<=', $end)
            ->orderBy('reservation_id')
            ->orderBy('timestamp')
            ->get();

        $checkins = [];
        $bookingMap = MeetingRoomBooking::with('user')
            ->whereIn('id', $logs->pluck('reservation_id')->unique())
            ->get()
            ->keyBy('id');

        foreach ($logs as $log) {
            if ($log->type === 'checkin') {
                $checkins[$log->reservation_id] = $log;
            } elseif ($log->type === 'checkout' && isset($checkins[$log->reservation_id])) {
                $checkinLog  = $checkins[$log->reservation_id];
                $checkoutLog = $log;
                $booking     = $bookingMap->get($log->reservation_id);

                if ($booking) {
                    $cIn  = $checkinLog->timestamp;
                    $cOut = $checkoutLog->timestamp;

                    // Jam Mulai: ambil dari reservasi awal jika ada, atau jam check-in riil
                    $startTime = $booking->start_time 
                        ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') 
                        : $cIn->format('H:i');
                    
                    // Jam Selesai: ambil waktu checkout riil saat admin klik checkout, dibulatkan ke depan (.00)
                    $outH = (int) $cOut->format('H');
                    $outM = (int) $cOut->format('i');
                    $outS = (int) $cOut->format('s');
                    $endH = ($outM > 0 || $outS > 0) ? ($outH + 1) : $outH;

                    // Jika ada jadwal reservasi awal (misal 13:00), ambil yang lebih besar (jika ngaret, ikuti checkout riil)
                    if ($booking->end_time) {
                        $reservedEndH = (int) \Carbon\Carbon::parse($booking->end_time)->format('H');
                        if ($reservedEndH === 0 && \Carbon\Carbon::parse($booking->end_time)->day > \Carbon\Carbon::parse($booking->date)->day) {
                            $reservedEndH = 24;
                        }
                        $endH = max($endH, $reservedEndH);
                    }

                    $sh = (int) substr($startTime, 0, 2);
                    if ($endH <= $sh) {
                        $endH = $sh + 1;
                    }
                    if ($endH >= 24) {
                        $endTime = '24:00';
                    } else {
                        $endTime = sprintf('%02d:00', $endH);
                    }

                    $events->push([
                        'id'             => $booking->id,
                        'title'          => !empty($booking->name) ? $booking->name : ($booking->user->name ?? 'Reservasi Meeting'),
                        'date'           => $booking->date ? \Carbon\Carbon::parse($booking->date)->format('Y-m-d') : $cIn->format('Y-m-d'),
                        'start_time'     => $startTime,
                        'end_time'       => $endTime,
                        'order_number'   => $booking->id,
                        'room_name'      => $booking->room_name ?: 'Ruang Meeting',
                        'keperluan'      => $booking->keperluan,
                        'status'         => 'selesai',
                        'payment_status' => 'approved',
                        'detail_url'     => url('admin/meeting-room/' . $booking->id . '/detail'),
                    ]);
                }

                unset($checkins[$log->reservation_id]);
            }
        }

        return response()->json($events->values());
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

        $benefitId = $request->benefit_id;
        if ($request->source_type === 'manual') {
            $benefit = RoomBenefit::create([
                'user_id'       => $user->id,
                'paket'         => 'Paket Meeting Room (60 Jam / 1 Tahun)',
                'total_minutes' => 60 * 60,
                'used_minutes'  => 0,
                'type'          => 'meeting',
                'is_active'     => true,
                'expired_at'    => now()->addYear(),
            ]);
            $benefitId = $benefit->id;
        }

        $booking = MeetingRoomBooking::create([
            'user_id'         => $user->id,
            'created_by'      => Auth::id(),
            'name'            => $user->name,
            'nama_perusahaan' => $user->company_name,
            'email'           => $user->email,
            'alamat_usaha'    => $user->address,
            'bidang_usaha'    => $user->business_type,
            'keperluan'       => $request->notes,
            'notes'           => $request->notes,
            'room_name'       => $request->room_name ?: 'Ruang Meetingroom Utama',
            'date'            => null, // null for package pool
            'start_time'      => null,
            'end_time'        => $end,
            'duration'        => $durationHours,
            'participants'    => $request->participants,
            'source_type'     => $request->source_type,
            'benefit_id'      => $benefitId,
            'status'          => 'approved',
            'payment_status'  => 'approved',
            'payment_method'  => $request->payment_method ?: 'Payment WA',
        ]);

        return redirect('admin/meeting-room')->with('success', "✅ Paket Meeting Room (60 Jam) untuk {$user->name} berhasil ditambahkan! Kuota 60 Jam aktif.");
    }

    // ── Admin: Setup Specific Reservation Session on Booking ─────────────────

    public function createSession(Request $request)
    {
        $request->validate([
            'booking_id'      => 'required|exists:meeting_room_bookings,id',
            'date'            => 'required|date',
            'start_time'      => 'required',
            'end_time'        => 'nullable',
            'room_name'       => 'required|string',
            'nama_perusahaan' => 'nullable|string|max:255',
            'keperluan'       => 'nullable|string',
            'participants'    => 'nullable|integer|min:1',
            'notes'           => 'nullable|string',
        ]);

        $booking = MeetingRoomBooking::findOrFail($request->booking_id);

        // Room occupancy guard for entire requested duration
        $occupiedSlots = $this->calculateOccupiedHours($request->date, $request->room_name, $booking->id);
        $sh = (int) substr($request->start_time, 0, 2);
        $eh = $request->filled('end_time') ? (int) substr($request->end_time, 0, 2) : ($sh + 1);
        if ($eh <= $sh) {
            $eh = ($request->end_time === '24:00') ? 24 : ($sh + 1);
        }
        for ($h = $sh; $h < $eh; $h++) {
            $reqSlot = sprintf('%02d:00', $h);
            if (in_array($reqSlot, $occupiedSlots)) {
                return back()->withInput()->with('error', "🚫 Jam {$reqSlot} pada ruangan {$request->room_name} sudah digunakan atau dibooking oleh client lain.");
            }
        }

        $bookingDate = \Carbon\Carbon::parse($request->date)->format('Y-m-d');
        $endTimeVal = $request->filled('end_time') 
            ? \Carbon\Carbon::parse($bookingDate . ' ' . $request->end_time) 
            : ($request->filled('start_time') ? \Carbon\Carbon::parse($bookingDate . ' ' . $request->start_time)->addHours(2) : null);

        $booking->update([
            'date'            => $request->date,
            'start_time'      => $request->start_time,
            'end_time'        => $endTimeVal,
            'room_name'       => $request->room_name ?: $booking->room_name,
            'nama_perusahaan' => $request->nama_perusahaan ?: $booking->nama_perusahaan,
            'keperluan'       => $request->keperluan ?: $booking->keperluan,
            'participants'    => (int) ($request->participants ?: $booking->participants ?: 1),
            'notes'           => $request->notes ?: $booking->notes,
            'status'          => 'approved',
        ]);

        return redirect()->back()->with('success', "✅ Reservasi Check-In untuk {$booking->name} pada " . \Carbon\Carbon::parse($request->date)->format('d M Y') . " {$request->start_time} WIB berhasil disimpan! Tombol Check In sekarang aktif.");
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
        $booking = MeetingRoomBooking::with(['user', 'benefit'])->findOrFail($id);
        $logs    = RoomUsageLog::where('reservation_id', $id)
            ->where('room_type', 'meeting_room')
            ->orderBy('timestamp', 'asc')
            ->get();

        return view('admin.meeting-room.detail', compact('booking', 'logs'));
    }

    // ── Customer Index ────────────────────────────────────────────────────────

    public function customerDetail($id)
    {
        $booking = MeetingRoomBooking::with(['user', 'benefit'])->findOrFail($id);
        
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

        if ($startTime) {
            $occupiedSlots = $this->calculateOccupiedHours($dateInput, $roomName, $booking->id);
            $sh = (int) substr($startTime, 0, 2);
            $eh = $endTime ? (int) substr($endTime, 0, 2) : ($sh + 1);
            if ($eh <= $sh) {
                $eh = ($endTime === '24:00') ? 24 : ($sh + 1);
            }
            for ($h = $sh; $h < $eh; $h++) {
                $reqSlot = sprintf('%02d:00', $h);
                if (in_array($reqSlot, $occupiedSlots)) {
                    return back()->withInput()->with('error', "🚫 Jam {$reqSlot} pada ruangan {$roomName} sudah digunakan atau dibooking oleh client lain.");
                }
            }
        }

        if ($booking->status === 'selesai') {
            return redirect()->back()->with('error', '🚫 Sesi reservasi ini sudah selesai (sudah pernah Check Out). Silakan ajukan atau buat reservasi baru untuk sesi berikutnya.');
        }

        // Authorization check for non-admin client
        if (!Auth::user()->hasAdminAccess()) {
            if ($booking->source_type === 'benefit') {
                if ($booking->status !== 'approved') {
                    return redirect()->back()->with('error', 'Reservasi benefit belum disetujui admin.');
                }
            } else {
                if ($booking->payment_status !== 'approved') {
                    return redirect()->back()->with('error', 'Pembayaran belum dikonfirmasi admin. Check In tidak bisa dilakukan.');
                }
            }
        }

        if ($booking->status === 'checkin') {
            return redirect()->back()->with('error', 'Booking ini sedang berjalan (sudah di Check In).');
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
            'room_name'      => $roomName,
            'date'           => $bookingDate,
            'start_time'     => $startTime,
            'end_time'       => $endTimeVal,
            'status'         => 'checkin',
            'payment_status' => 'approved', // Auto-approve on admin checkin
            'checkin_at'     => now(),
            'checkout_at'    => null,
        ]);

        // Always write to room_usage_logs (existing)
        RoomUsageLog::create([
            'reservation_id' => $booking->id,
            'room_type'      => 'meeting_room',
            'type'           => 'checkin',
            'timestamp'      => now(),
            'notes'          => $booking->notes,
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
        $totalQuotaSecs = $booking->duration * 3600;
        $hasRemainingQuota = ($booking->duration >= 10) ? ($totalQuotaSecs > $newTotalUsed) : false;
        
        $sessionNotes = $booking->notes;
        
        $booking->update([
            'status'             => $hasRemainingQuota ? 'approved' : 'selesai',
            'total_used_seconds' => $newTotalUsed,
            'start_time'         => null, // Reset start_time so it returns to "Reservasi Check In" state!
            'date'               => $hasRemainingQuota ? null : $booking->date,
            'room_name'          => $hasRemainingQuota ? null : $booking->room_name,
            'keperluan'          => $hasRemainingQuota ? null : $booking->keperluan,
            'participants'       => $hasRemainingQuota ? 1 : $booking->participants,
            'notes'              => $hasRemainingQuota ? null : $booking->notes, // Reset notes on checkout!
            'checkout_at'        => $checkoutAt,
            'checkin_at'         => null,
        ]);

        RoomUsageLog::create([
            'reservation_id' => $booking->id,
            'room_type'      => 'meeting_room',
            'type'           => 'checkout',
            'timestamp'      => $checkoutAt,
            'notes'          => $sessionNotes,
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

        // Display actual duration and quota deduction
        $actualDuration = $booking->formatSeconds($sessionSeconds);
        $billingInfo    = " (Pemakaian Kuota: {$billingHours} Jam)";
        
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
            \Illuminate\Support\Facades\Log::error('MeetingRoomController::checkout - Exception WA: ' . $e->getMessage());
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
