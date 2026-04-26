<?php

namespace App\Http\Controllers;

use App\Models\PodcastRoomBooking;
use App\Models\RoomUsageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PodcastRoomController extends Controller
{
    /** Harga paket (Rp) per durasi (jam) */
    public static array $packages = [
        2 => ['label' => '2 Jam',  'price' => 500000],
    ];

    // ── Frontend Landing Page ─────────────────────────────────────────────────

    public function index()
    {
        return view('frontend.services.layanan-pendukung-bisnis.sewa-ruang-podcast');
    }

    // ── Booked Slots API ─────────────────────────────────────────────────────

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

    // ── Order Form ───────────────────────────────────────────────────────────

    public function order(Request $request)
    {
        return view('podcast-room.order', [
            'tanggal' => $request->get('tanggal'),
            'jam'     => $request->get('jam'),
            'durasi'  => $request->get('durasi', 2),
            'packages'=> self::$packages,
        ]);
    }

    // ── Store Booking ────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'podcast_title' => 'nullable|string|max:255',
            'tanggal'       => 'required|date',
            'jam'           => 'required',
            'durasi'        => 'required|integer|min:2',
            'peserta'       => 'required|integer|min:1',
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Double-booking guard
        $conflict = PodcastRoomBooking::where('date', $request->tanggal)
            ->where('start_time', 'like', $request->jam . '%')
            ->whereNotIn('payment_status', ['rejected'])
            ->exists();

        if ($conflict) {
            return back()->withInput()
                ->withErrors(['jam' => 'Slot waktu tersebut sudah dipesan. Silakan pilih waktu lain.']);
        }

        $path       = $request->file('payment_proof')->store('payment_proofs/podcast', 'public');
        $durasi     = (int) $request->durasi;
        if ($durasi < 2 || $durasi % 2 !== 0) $durasi = 2; // Guard for multiples of 2
        $price      = ($durasi / 2) * 500000;
        $orderNum   = 'PODCAST-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        PodcastRoomBooking::create([
            'user_id'        => Auth::id(),
            'order_number'   => $orderNum,
            'name'           => $request->nama,
            'podcast_title'  => $request->podcast_title,
            'date'           => $request->tanggal,
            'start_time'     => $request->jam,
            'duration'       => $durasi,
            'participants'   => $request->peserta,
            'package'        => $durasi . 'jam',
            'total_price'    => $price,
            'status'         => 'pending',
            'payment_proof'  => $path,
            'payment_status' => 'pending',
        ]);

        return redirect()->route('customer.podcast-room.index')
            ->with('success', "Reservasi Ruang Podcast berhasil! Nomor Order: {$orderNum}. Menunggu konfirmasi pembayaran admin.");
    }

    // ── Admin ─────────────────────────────────────────────────────────────────

    public function adminIndex()
    {
        $bookings = PodcastRoomBooking::with('user')->latest()->get();
        return view('admin.podcast-room.index', compact('bookings'));
    }

    public function adminDetail($id)
    {
        $booking = PodcastRoomBooking::with('user')->findOrFail($id);
        $logs = RoomUsageLog::where('reservation_id', $id)
            ->where('room_type', 'podcast_room')
            ->orderBy('timestamp', 'asc')
            ->get();
            
        return view('admin.podcast-room.detail', compact('booking', 'logs'));
    }

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

    public function checkin($id)
    {
        $booking = PodcastRoomBooking::findOrFail($id);

        if ($booking->payment_status !== 'approved') {
            return back()->with('error', 'Pembayaran belum dikonfirmasi. Check In tidak bisa dilakukan.');
        }
        if ($booking->status === 'checkin') {
            return back()->with('error', 'Booking ini sudah di Check In.');
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

        return back()->with('success', 'User berhasil Check In ke ruangan.');
    }

    public function checkout($id)
    {
        $booking = PodcastRoomBooking::findOrFail($id);

        if ($booking->status !== 'checkin' || !$booking->checkin_at) {
            return back()->with('error', 'Booking ini belum di Check In.');
        }

        $sessionSeconds = $booking->checkin_at->diffInSeconds(now());
        $prevUsed       = $booking->total_used_seconds > 0 ? $booking->total_used_seconds : ($booking->total_used_minutes * 60);
        $newTotalUsed   = $prevUsed + $sessionSeconds;
        $totalSeconds   = $booking->duration * 3600;
        $newStatus      = ($newTotalUsed >= $totalSeconds) ? 'selesai' : 'paused';

        $booking->update([
            'status'             => $newStatus,
            'total_used_seconds' => $newTotalUsed,
            'checkout_at'        => now(),
            'checkin_at'         => null,
        ]);

        RoomUsageLog::create([
            'reservation_id' => $booking->id,
            'room_type'      => 'podcast_room',
            'type'           => 'checkout',
            'timestamp'      => now(),
        ]);

        return back()->with('success', "User berhasil Check Out dari ruangan. Durasi: " . $booking->formatSeconds($sessionSeconds) . ".");
    }

    // ── Customer ──────────────────────────────────────────────────────────────

    public function customerIndex()
    {
        $bookings = PodcastRoomBooking::where('user_id', Auth::id())->latest()->get();
        return view('customer.podcast-room.index', compact('bookings'));
    }
}
