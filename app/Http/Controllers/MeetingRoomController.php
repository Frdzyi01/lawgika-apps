<?php

namespace App\Http\Controllers;

use App\Models\MeetingRoomBooking;
use App\Models\RoomUsageLog;
use App\Models\UserRoomQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingRoomController extends Controller
{
    public function index()
    {
        return view('frontend.services.layanan-pendukung-bisnis.sewa-meeting-room');
    }

    public function order(Request $request)
    {
        $quota = UserRoomQuota::where('user_id', Auth::id())->first();

        return view('meeting-room.order', [
            'tanggal'  => $request->get('tanggal'),
            'jam'      => $request->get('jam'),
            'durasi'   => $request->get('durasi', 1),
            'package'  => $request->get('package', 'reservasi'),
            'quota'    => $quota,
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

    public function store(Request $request)
    {
        $rules = [
            'nama'          => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'jam'           => 'required',
            'peserta'       => 'required|integer|min:1',
            'durasi'        => 'required|integer|min:1',
            'use_quota'     => 'nullable|boolean',
        ];

        // Jika tidak pakai quota, payment proof wajib
        if (!$request->input('use_quota')) {
            $rules['payment_proof'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        }

        $request->validate($rules);

        if ($request->input('use_quota')) {
            $quota = UserRoomQuota::where('user_id', Auth::id())->first();
            if (!$quota) {
                return back()->withInput()->withErrors(['quota' => 'Anda tidak memiliki quota.']);
            }
            if (now()->greaterThan($quota->expired_at)) {
                return back()->withInput()->withErrors(['quota' => 'Quota Anda sudah expired.']);
            }
            $reqSeconds = $request->durasi * 3600;
            if ($quota->remaining_seconds < $reqSeconds) {
                return back()->withInput()->withErrors(['quota' => 'Sisa waktu quota tidak mencukupi untuk durasi ini.']);
            }
        }

        // ── Double-booking guard ──────────────────────────────────────────────
        $conflict = MeetingRoomBooking::where('date', $request->tanggal)
            ->where('start_time', 'like', $request->jam . '%')
            ->whereNotIn('payment_status', ['rejected'])
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->withErrors(['jam' => 'Slot waktu tersebut sudah dipesan. Silakan pilih waktu lain.']);
        }

        $path = null;
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        MeetingRoomBooking::create([
            'user_id'        => Auth::id(),
            'name'           => $request->nama,
            'date'           => $request->tanggal,
            'start_time'     => $request->jam,
            'duration'       => $request->durasi,
            'participants'   => $request->peserta,
            'status'         => 'pending',
            'payment_proof'  => $path,
            'payment_status' => $request->input('use_quota') ? 'approved' : 'pending',
        ]);

        $msg = $request->input('use_quota') 
            ? 'Reservasi menggunakan quota berhasil dibuat! Status langsung disetujui.' 
            : 'Reservasi berhasil dibuat! Menunggu konfirmasi pembayaran dari admin.';

        return redirect()->route('customer.meeting-room.index')->with('success', $msg);
    }

    public function adminIndex()
    {
        $bookings = MeetingRoomBooking::latest()->get();
        return view('admin.meeting-room.index', compact('bookings'));
    }

    public function adminDetail($id)
    {
        $booking = MeetingRoomBooking::with('user')->findOrFail($id);
        $logs = RoomUsageLog::where('reservation_id', $id)
            ->where('room_type', 'meeting_room')
            ->orderBy('timestamp', 'asc')
            ->get();
            
        return view('admin.meeting-room.detail', compact('booking', 'logs'));
    }

    public function customerIndex()
    {
        $bookings = MeetingRoomBooking::where('user_id', Auth::id())->latest()->get();
        return view('customer.meeting-room.index', compact('bookings'));
    }

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

    public function checkin($id)
    {
        $booking = MeetingRoomBooking::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->payment_status !== 'approved') {
            return redirect()->back()->with('error', 'Pembayaran belum dikonfirmasi admin. Check In tidak bisa dilakukan.');
        }

        if ($booking->status === 'checkin') {
            return redirect()->back()->with('error', 'Booking ini sudah di Check In.');
        }

        if ($booking->is_expired) {
            return redirect()->back()->with('error', 'Masa berlaku reservasi sudah expired (lebih dari 1 tahun).');
        }

        $totalSeconds = $booking->duration * 3600;
        $remainingRaw = $totalSeconds - $booking->used_seconds;

        if ($remainingRaw <= 0) {
            return redirect()->back()->with('error', 'Waktu reservasi sudah habis.');
        }

        $booking->update([
            'status'      => 'checkin',
            'checkin_at'  => now(),
            'checkout_at' => null,
        ]);

        RoomUsageLog::create([
            'reservation_id' => $booking->id,
            'room_type'      => 'meeting_room',
            'type'           => 'checkin',
            'timestamp'      => now(),
        ]);

        return redirect()->back()->with('success', 'User berhasil Check In ke ruangan.');
    }

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
        $sessionSeconds = $checkinAt->diffInSeconds(now());

        // fallback if total_used_seconds was 0 but total_used_minutes wasn't
        $prevUsed     = $booking->total_used_seconds > 0 ? $booking->total_used_seconds : ($booking->total_used_minutes * 60);
        $newTotalUsed = $prevUsed + $sessionSeconds;
        $totalSeconds = $booking->duration * 3600;

        $newStatus = ($newTotalUsed >= $totalSeconds) ? 'selesai' : 'paused';

        $booking->update([
            'status'             => $newStatus,
            'total_used_seconds' => $newTotalUsed,
            'checkout_at'        => now(),
            'checkin_at'         => null,
        ]);

        RoomUsageLog::create([
            'reservation_id' => $booking->id,
            'room_type'      => 'meeting_room',
            'type'           => 'checkout',
            'timestamp'      => now(),
        ]);

        // ── Deduct Shared Quota if exists ─────────────────────────────────────
        $quota = UserRoomQuota::where('user_id', $booking->user_id)->first();
        if ($quota && !now()->greaterThan($quota->expired_at) && empty($booking->payment_proof)) {
            $quota->used_seconds += $sessionSeconds;
            $quota->remaining_seconds = max(0, $quota->total_seconds - $quota->used_seconds);
            $quota->save();

            \App\Models\QuotaLog::create([
                'user_id'   => $booking->user_id,
                'room_type' => 'meeting_room',
                'duration'  => $sessionSeconds,
                'tanggal'   => now(),
            ]);
        }

        return redirect()->back()->with('success', 'User berhasil Check Out dari ruangan. Durasi: ' . $booking->formatSeconds($sessionSeconds) . '.');
    }
}
