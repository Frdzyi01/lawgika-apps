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
        2  => ['label' => '2 Jam', 'price' => 700000],
        20 => ['label' => 'Paket 20 Jam / 1 Tahun', 'price' => 5000000],
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

        $isPackage = $request->get('package') === 'paket';
        $package   = $isPackage ? 'paket' : 'reservasi';
        $durasi    = $isPackage ? 20 : (int) $request->get('durasi', 2);
        if ($durasi < 1) $durasi = 2;

        return view('podcast-room.order', [
            'tanggal'       => $request->get('tanggal'),
            'jam'           => $request->get('jam'),
            'durasi'        => $durasi,
            'package'       => $package,
            'packages'      => self::$packages,
            'quota'         => $quota,
            'activeBenefit' => $isPackage ? null : $benefit,
        ]);
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        if (auth()->check()) {
            $request->merge(['nama' => auth()->user()->name]);
        }

        $isPackage = ($request->input('package') === 'paket' || $request->query('package') === 'paket');

        $durasi = (int) $request->input('durasi', 2);
        if ($durasi < 1) $durasi = 1;

        // Add-On values & calculations (Strict limits: max 1 mic, max 1 headphone, max 2 camera, max 1 operator)
        $addonMicQty       = min(1, max(0, (int) $request->input('addon_mic', 0)));
        $addonHeadphoneQty = min(1, max(0, (int) $request->input('addon_headphone', 0)));
        $addonCameraQty    = min(2, max(0, (int) $request->input('addon_camera', 0)));
        $addonOperatorQty  = min(1, max(0, (int) $request->input('addon_operator', 0)));

        $addonMicCost       = $addonMicQty * 50000 * $durasi;
        $addonHeadphoneCost = $addonHeadphoneQty * 50000 * $durasi;
        $addonCameraCost    = $addonCameraQty * 150000 * $durasi;
        $addonOperatorCost  = $addonOperatorQty * 100000 * $durasi;

        $totalAddon = $addonMicCost + $addonHeadphoneCost + $addonCameraCost + $addonOperatorCost;

        $addonNotes = [];
        if ($addonMicQty > 0)       $addonNotes[] = "Mikrofon {$addonMicQty} unit (Rp " . number_format($addonMicCost, 0, ',', '.') . ")";
        if ($addonHeadphoneQty > 0) $addonNotes[] = "Headphone {$addonHeadphoneQty} unit (Rp " . number_format($addonHeadphoneCost, 0, ',', '.') . ")";
        if ($addonCameraQty > 0)    $addonNotes[] = "Kamera {$addonCameraQty} unit (Rp " . number_format($addonCameraCost, 0, ',', '.') . ")";
        if ($addonOperatorQty > 0)  $addonNotes[] = "Operator Podcast (Rp " . number_format($addonOperatorCost, 0, ',', '.') . ")";

        $notesText = !empty($addonNotes) ? "Add-On: " . implode(', ', $addonNotes) : null;

        $rules = [
            'nama'          => 'required|string|max:255',
            'podcast_title' => 'nullable|string|max:255',
            'tanggal'       => $isPackage ? 'nullable|date' : 'required|date',
            'jam'           => $isPackage ? 'nullable' : 'required',
            'durasi'        => 'required|integer|min:1|max:20',
            'use_quota'     => 'nullable|boolean',
            'addon_mic'     => 'nullable|integer|min:0|max:1',
            'addon_headphone'=> 'nullable|integer|min:0|max:1',
            'addon_camera'  => 'nullable|integer|min:0|max:2',
            'addon_operator'=> 'nullable|integer|min:0|max:1',
        ];

        $benefit = $this->getActiveBenefit();
        $isPayManual = ($request->input('pay_manually') == '1' || $request->input('benefit_choice') === 'pay_manual');

        if ($isPackage) {
            $rules['payment_proof'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        } else {
            if ($benefit && !$isPayManual) {
                // If using benefit and has add-on cost, require payment proof
                if ($totalAddon > 0) {
                    $rules['payment_proof'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
                }
            } else {
                // Manual flow
                if (!$request->input('use_quota')) {
                    $rules['payment_proof'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
                }
            }
        }

        $request->validate($rules);

        // Double-booking guard (hanya jika tanggal dan jam diisi)
        if ($request->tanggal && $request->jam) {
            $conflict = PodcastRoomBooking::where('date', $request->tanggal)
                ->where('start_time', 'like', $request->jam . '%')
                ->whereNotIn('status', ['rejected'])
                ->exists();

            if ($conflict) {
                return back()->withInput()
                    ->withErrors(['jam' => 'Slot waktu tersebut sudah dipesan. Silakan pilih waktu lain.']);
            }
        }

        // Upload payment proof if provided
        $path = null;
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs/podcast', 'public');
        }

        // ── 1. BENEFIT FLOW (Gunakan Benefit / Kuota Paket) ────────────────────────
        if ($benefit && !$isPayManual && !$isPackage) {
            // Validate that requested duration does not exceed remaining benefit quota
            $remainingMinutes = $benefit->total_minutes - $benefit->used_minutes;
            $requestedMinutes = $durasi * 60;
            if ($remainingMinutes < $requestedMinutes) {
                $remHours = floor($remainingMinutes / 60);
                return back()->withInput()->withErrors(['durasi' => "Sisa kuota Ruang Podcast Anda ({$remHours} jam) tidak mencukupi untuk durasi yang diajukan ({$durasi} jam)."]);
            }

            // Check if user already has a master package booking
            $masterBooking = PodcastRoomBooking::where('user_id', Auth::id())
                ->where(function($q) use ($benefit) {
                    $q->where('duration', '>=', 10);
                    if ($benefit && $benefit->podcast_room_booking_id) {
                        $q->orWhere('id', $benefit->podcast_room_booking_id);
                    }
                })
                ->where('status', '!=', 'selesai')
                ->latest()
                ->first();

            if ($masterBooking) {
                $masterBooking->update([
                    'date'           => $request->tanggal,
                    'start_time'     => $request->jam,
                    'room_name'      => $request->room_name ?: ($masterBooking->room_name ?: 'Podcast Studio Utama'),
                    'podcast_title'  => $request->podcast_title ?: $masterBooking->podcast_title,
                    'participants'   => (int) ($request->peserta ?: $request->participants ?: 1),
                    'status'         => 'pending', // Menunggu persetujuan admin
                    'payment_proof'  => $path ?: $masterBooking->payment_proof,
                    'notes'          => $notesText ?: $masterBooking->notes,
                ]);

                return redirect()->route('customer.podcast-room.index')
                    ->with('success', "✅ Pengajuan reservasi Ruang Podcast ({$durasi} Jam) berhasil diajukan untuk Order {$masterBooking->order_number}! Menunggu persetujuan admin.");
            }

            $orderNum = 'PODCAST-BNF-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $createdBooking = PodcastRoomBooking::create([
                'user_id'        => Auth::id(),
                'source_type'    => 'benefit',
                'benefit_id'     => $benefit->id,
                'order_number'   => $orderNum,
                'name'           => $request->nama,
                'podcast_title'  => $request->podcast_title,
                'room_name'      => $request->room_name ?: 'Podcast Studio Utama',
                'date'           => $request->tanggal,
                'start_time'     => $request->jam,
                'duration'       => round($benefit->total_minutes / 60) ?: 20,
                'participants'   => (int) ($request->peserta ?: $request->participants ?: 1),
                'package'        => $benefit->paket,
                'total_price'    => 0,
                'status'         => 'pending',
                'payment_status' => 'approved',
                'payment_proof'  => $path,
                'notes'          => $notesText,
            ]);

            $benefit->update(['podcast_room_booking_id' => $createdBooking->id]);

            return redirect()->route('customer.podcast-room.index')
                ->with('success', "✅ Pengajuan reservasi Ruang Podcast berhasil dikirim! Menunggu persetujuan admin.");
        }

        // ── 2. MANUAL / DIRECT FLOW ──────────────────────────────────────────
        if ($request->input('use_quota')) {
            $quota = UserRoomQuota::where('user_id', Auth::id())->first();
            if (!$quota) {
                return back()->withInput()->withErrors(['quota' => 'Anda tidak memiliki quota.']);
            }
            if (now()->greaterThan($quota->expired_at)) {
                return back()->withInput()->withErrors(['quota' => 'Quota Anda sudah expired.']);
            }
            if ($quota->remaining_seconds < $durasi * 3600) {
                return back()->withInput()->withErrors(['quota' => 'Sisa waktu quota tidak mencukupi untuk durasi ini.']);
            }
        }

        // Podcast Pricing: 20 jam = 5.000.000, 1 jam = 500.000, 2 jam = 700.000, >2 jam = 700.000 + (n-2) * 300.000
        if ($durasi === 20 || $isPackage) {
            $basePrice = 5000000;
        } elseif ($durasi === 1) {
            $basePrice = 500000;
        } elseif ($durasi === 2) {
            $basePrice = 700000;
        } else {
            $basePrice = 700000 + (($durasi - 2) * 300000);
        }

        $subtotal   = $basePrice + $totalAddon;
        $ppn        = (int) round($subtotal * 0.11);
        $totalPrice = $subtotal + $ppn;

        $orderNum = 'PODCAST-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        PodcastRoomBooking::create([
            'user_id'        => Auth::id(),
            'source_type'    => 'manual',
            'benefit_id'     => null,
            'order_number'   => $orderNum,
            'name'           => $request->nama,
            'podcast_title'  => $request->podcast_title,
            'date'           => $isPackage ? null : $request->tanggal,
            'start_time'     => $isPackage ? null : $request->jam,
            'duration'       => $durasi,
            'participants'   => 1,
            'package'        => $isPackage ? 'Podcast Room Package (20 Jam / 1 Tahun)' : ($durasi . ' Jam'),
            'total_price'    => $totalPrice,
            'status'         => 'pending',
            'payment_proof'  => $path,
            'payment_status' => $request->input('use_quota') ? 'approved' : 'pending',
            'notes'          => $notesText,
        ]);

        $msg = $request->input('use_quota')
            ? "Reservasi Ruang Podcast menggunakan quota berhasil! Nomor Order: {$orderNum}. Status langsung disetujui."
            : ($isPackage 
                ? "Pembelian Paket Podcast Room (20 Jam / 1 Tahun) berhasil! Nomor Order: {$orderNum}. Menunggu konfirmasi pembayaran admin." 
                : "Reservasi Ruang Podcast berhasil! Nomor Order: {$orderNum}. Menunggu konfirmasi pembayaran admin.");

        return redirect()->route('customer.podcast-room.index')->with('success', $msg);
    }

    // ── Admin Index ───────────────────────────────────────────────────────────

    public function adminIndex(Request $request)
    {
        $search = $request->input('search');

        // Load only approved, active, or completed bookings for Table 2 (exclude pending approvals)
        $query = PodcastRoomBooking::with(['user', 'benefit'])
            ->where(function ($q) {
                $q->whereIn('status', ['approved', 'checkin', 'paused', 'selesai'])
                  ->where('payment_status', 'approved');
            })
            ->latest();

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
            ->where(function ($q) {
                $q->where('status', 'pending')
                  ->orWhere('payment_status', 'pending');
            })
            ->latest()
            ->get();

        return view('admin.podcast-room.index', compact('bookings', 'pendingReservations'));
    }

    // ── Admin: Calendar (Read-Only Visualization) ─────────────────────────────

    public function calendar()
    {
        return view('admin.podcast-room.calendar');
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
        $bookings = PodcastRoomBooking::with('user')
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
                $displayDuration = min($b->duration, 4);
                $endTime = \Carbon\Carbon::parse($b->start_time)->addHours($displayDuration)->format('H:i');
            }

            if ($startTime) {
                $events->push([
                    'id'             => $b->id,
                    'title'          => !empty($b->name) ? $b->name : ($b->user->name ?? 'Reservasi Podcast'),
                    'date'           => \Carbon\Carbon::parse($b->date)->format('Y-m-d'),
                    'start_time'     => $startTime,
                    'end_time'       => $endTime,
                    'order_number'   => $b->order_number,
                    'room_name'      => $b->room_name ?: 'Studio Podcast',
                    'status'         => $b->status,
                    'payment_status' => $b->payment_status,
                    'detail_url'     => url('admin/podcast-room/' . $b->id . '/detail'),
                ]);
            }
        }

        // 2. Completed / Checked-out Sessions History from RoomUsageLog
        $logs = RoomUsageLog::where('room_type', 'podcast_room')
            ->whereDate('timestamp', '>=', $start)
            ->whereDate('timestamp', '<=', $end)
            ->orderBy('reservation_id')
            ->orderBy('timestamp')
            ->get();

        $checkins = [];
        $bookingMap = PodcastRoomBooking::with('user')
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

                    $startTime = $cIn->format('H:i');
                    if ($cOut->diffInMinutes($cIn) < 30) {
                        $endTime = $cIn->copy()->addHour()->format('H:i');
                    } else {
                        $endTime = $cOut->format('H:i');
                    }

                    $events->push([
                        'id'             => $booking->id,
                        'title'          => !empty($booking->name) ? $booking->name : ($booking->user->name ?? 'Reservasi Podcast'),
                        'date'           => $cIn->format('Y-m-d'),
                        'start_time'     => $startTime,
                        'end_time'       => $endTime,
                        'order_number'   => $booking->order_number,
                        'room_name'      => $booking->room_name ?: 'Studio Podcast',
                        'status'         => 'selesai',
                        'payment_status' => 'approved',
                        'detail_url'     => url('admin/podcast-room/' . $booking->id . '/detail'),
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
        
        // Parse durasi jam dari input paket atau durasi
        $paketInput = $request->input('paket');
        if (is_numeric($paketInput)) {
            $durationHours = (int) $paketInput;
        } elseif (preg_match('/(\d+)\s*Jam/i', (string) $paketInput, $m)) {
            $durationHours = (int) $m[1];
        } else {
            $durationHours = (int) $request->input('durasi', 20);
        }
        if ($durationHours < 1) $durationHours = 20;

        $packageLabel = $durationHours === 20 ? 'Podcast Room Package (20 Jam / 1 Tahun)' : 'Sewa Sesi (' . $durationHours . ' Jam)';

        // Get user for fallback data
        $user = \App\Models\User::find($request->user_id);

        $orderNum = 'PODCAST-' . ($request->source_type === 'benefit' ? 'BNF-' : '') . date('Ymd') . '-' . strtoupper(Str::random(5));

        // Podcast Pricing logic (same as store)
        $price = 0;
        if ($request->source_type === 'manual') {
            if ($durationHours === 20) {
                $price = 5000000;
            } elseif ($durationHours === 1) {
                $price = 500000;
            } elseif ($durationHours === 2) {
                $price = 700000;
            } else {
                $price = 700000 + (($durationHours - 2) * 300000);
            }
        }

        $isPackagePurchase = ($durationHours === 20);

        // If package purchase (20 Jam), activate RoomBenefit pool
        $benefitId = $request->benefit_id;
        if ($isPackagePurchase && $request->source_type === 'manual') {
            $benefit = RoomBenefit::create([
                'user_id'       => $user->id,
                'paket'         => 'Paket Podcast Room (20 Jam / 1 Tahun)',
                'total_minutes' => 20 * 60,
                'used_minutes'  => 0,
                'type'          => 'podcast',
                'is_active'     => true,
                'expired_at'    => now()->addYear(),
            ]);
            $benefitId = $benefit->id;
        }

        $booking = PodcastRoomBooking::create([
            'user_id'         => $user->id,
            'created_by'      => Auth::id(),
            'name'            => $user->name,
            'order_number'    => $orderNum,
            'podcast_title'   => $request->podcast_title,
            'notes'           => $request->notes,
            'room_name'       => $request->room_name ?: 'Podcast Studio Utama',
            'date'            => $isPackagePurchase ? null : $request->date, // null for package so it requires reservation session first
            'start_time'      => $isPackagePurchase ? null : $request->start_time,
            'end_time'        => $end,
            'duration'        => $durationHours,
            'participants'    => $request->participants,
            'package'         => $packageLabel,
            'total_price'     => $price,
            'source_type'     => $request->source_type,
            'benefit_id'      => $benefitId,
            'status'          => 'approved',
            'payment_status'  => 'approved',
            'payment_method'  => $request->payment_method ?: 'Payment WA',
        ]);

        $msg = $isPackagePurchase 
            ? "✅ Paket Podcast Room (20 Jam) untuk {$user->name} berhasil ditambahkan! Kuota 20 Jam aktif. Silakan buat reservasi sesi saat client ingin menggunakan studio."
            : "✅ Reservasi Ruang Podcast ({$durationHours} Jam) untuk {$user->name} berhasil ditambahkan!";

        return redirect('admin/podcast-room')->with('success', $msg);
    }

    // ── Admin: Setup Specific Reservation Session on Booking ─────────────────

    public function createSession(Request $request)
    {
        $request->validate([
            'booking_id'           => 'required|exists:podcast_room_bookings,id',
            'date'                 => 'required|date',
            'start_time'           => 'required',
            'room_name'            => 'required|string',
            'podcast_title'        => 'nullable|string|max:255',
            'participants'         => 'nullable|integer|min:1',
            'addon_mic'            => 'nullable|integer|min:0|max:1',
            'addon_headphone'      => 'nullable|integer|min:0|max:1',
            'addon_camera'         => 'nullable|integer|min:0|max:2',
            'addon_operator'       => 'nullable|integer|min:0|max:1',
            'addon_payment_method' => 'nullable|string',
            'notes'                => 'nullable|string',
        ]);

        $booking = PodcastRoomBooking::findOrFail($request->booking_id);

        // Room occupancy guard
        $occupied = PodcastRoomBooking::where('room_name', $request->room_name)
            ->where('date', $request->date)
            ->where('start_time', 'like', $request->start_time . '%')
            ->where('status', 'checkin')
            ->where('id', '!=', $booking->id)
            ->exists();

        if ($occupied) {
            return back()->with('error', "🚫 {$request->room_name} sedang digunakan (Check In) oleh client lain pada slot tersebut.");
        }

        // Build Add-On notes
        $addonList = [];
        if ((int)$request->input('addon_mic', 0) > 0) {
            $addonList[] = 'Mikrofon (1 Unit)';
        }
        if ((int)$request->input('addon_headphone', 0) > 0) {
            $addonList[] = 'Headphone (1 Unit)';
        }
        $camQty = (int)$request->input('addon_camera', 0);
        if ($camQty > 0) {
            $addonList[] = "Kamera ({$camQty} Unit)";
        }
        if ((int)$request->input('addon_operator', 0) > 0) {
            $addonList[] = 'Operator Podcast (1 Orang)';
        }

        $notesContent = '';
        if (!empty($addonList)) {
            $notesContent = 'Add-On: ' . implode(', ', $addonList);
            if ($request->input('addon_payment_method')) {
                $notesContent .= ' [Payment: ' . $request->input('addon_payment_method') . ']';
            }
        }
        if ($request->filled('notes')) {
            $notesContent = $notesContent ? ($notesContent . ' | ' . $request->notes) : $request->notes;
        }

        $booking->update([
            'date'          => $request->date,
            'start_time'    => $request->start_time,
            'room_name'     => $request->room_name ?: $booking->room_name,
            'podcast_title' => $request->podcast_title ?: $booking->podcast_title,
            'participants'  => (int) ($request->participants ?: $booking->participants ?: 1),
            'notes'         => $notesContent ?: $booking->notes,
            'status'        => 'approved',
        ]);

        return redirect()->back()->with('success', "✅ Reservasi Check-In untuk {$booking->name} pada " . \Carbon\Carbon::parse($request->date)->format('d M Y') . " {$request->start_time} WIB berhasil disimpan! Tombol Check In sekarang aktif.");
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
                'paket'         => 'Paket Podcast Room (' . ($booking->duration ?: 20) . ' Jam)',
                'total_minutes' => ($booking->duration ?: 20) * 60,
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

        if ($booking->status === 'selesai') {
            return back()->with('error', '🚫 Sesi reservasi podcast ini sudah selesai (sudah pernah Check Out). Silakan ajukan atau buat reservasi baru untuk sesi berikutnya.');
        }

        // Authorization check for non-admin client
        if (!Auth::user()->hasAdminAccess()) {
            if ($booking->source_type === 'benefit') {
                if ($booking->status !== 'approved') {
                    return back()->with('error', 'Reservasi benefit belum disetujui admin.');
                }
            } else {
                if ($booking->payment_status !== 'approved') {
                    return back()->with('error', 'Pembayaran belum dikonfirmasi. Check In tidak bisa dilakukan.');
                }
            }
        }

        if ($booking->status === 'checkin') {
            return back()->with('error', 'Booking studio ini sedang berjalan (sudah di Check In).');
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
            'room_name'      => $roomName,
            'date'           => $bookingDate,
            'start_time'     => $startTime,
            'end_time'       => $endTimeVal,
            'status'         => 'checkin',
            'payment_status' => 'approved', // Auto-approve on admin checkin
            'checkin_at'     => now(),
            'checkout_at'    => null,
        ]);

        RoomUsageLog::create([
            'reservation_id' => $booking->id,
            'room_type'      => 'podcast_room',
            'type'           => 'checkin',
            'timestamp'      => now(),
            'notes'          => $booking->notes,
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
        $totalQuotaSecs = $booking->duration * 3600;
        $hasRemainingQuota = ($booking->duration >= 10) ? ($totalQuotaSecs > $newTotalUsed) : false;
        
        $sessionNotes = $booking->notes;
        
        $booking->update([
            'status'             => $hasRemainingQuota ? 'approved' : 'selesai',
            'total_used_seconds' => $newTotalUsed,
            'start_time'         => null, // Reset start_time so it returns to "Reservasi Check In" state!
            'date'               => $hasRemainingQuota ? null : $booking->date,
            'room_name'          => $hasRemainingQuota ? null : $booking->room_name,
            'podcast_title'      => $hasRemainingQuota ? null : $booking->podcast_title,
            'participants'       => $hasRemainingQuota ? 1 : $booking->participants,
            'notes'              => $hasRemainingQuota ? null : $booking->notes, // Reset Add-On and notes on checkout!
            'checkout_at'        => $checkoutAt,
            'checkin_at'         => null,
        ]);

        RoomUsageLog::create([
            'reservation_id' => $booking->id,
            'room_type'      => 'podcast_room',
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

        // Display actual duration and quota deduction
        $actualDuration = $booking->formatSeconds($sessionSeconds);
        $quotaInfo      = " (Pemakaian Kuota: {$billingHours} Jam)";
        
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
            \Illuminate\Support\Facades\Log::error('PodcastRoomController::checkout - Exception WA: ' . $e->getMessage());
            $waMessage = ' Tetapi WhatsApp gagal dikirim.';
        }

        return back()->with('success', "User berhasil Check Out dari ruangan. Durasi aktual: {$actualDuration}{$quotaInfo}." . $waMessage);
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
                'user_id'                 => Auth::id(),
                'podcast_room_booking_id' => $packageBooking->id,
                'type'                    => 'podcast',
            ], [
                'paket'         => 'Paket Podcast Room (' . ($packageBooking->duration ?: 20) . ' Jam)',
                'total_minutes' => ($packageBooking->duration ?: 20) * 60,
                'used_minutes'  => round($packageBooking->used_seconds / 60),
                'is_active'     => true,
                'expired_at'    => \Carbon\Carbon::parse($packageBooking->created_at)->addYear(),
            ]);
        }

        return null;
    }
}
