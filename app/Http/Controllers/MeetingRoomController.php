<?php

namespace App\Http\Controllers;

use App\Models\MeetingRoomBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingRoomController extends Controller
{
    public function index()
    {
        return view('frontend.services.layanan-pendukung-bisnis.sewa-meeting-room');
    }

    public function order()
    {
        return view('meeting-room.order');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'jam'           => 'required',
            'peserta'       => 'required|integer|min:1',
            'durasi'        => 'required|integer|min:1',
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        MeetingRoomBooking::create([
            'user_id'        => Auth::id(),
            'name'           => $request->nama,
            'date'           => $request->tanggal,
            'start_time'     => $request->jam,
            'duration'       => $request->durasi,
            'participants'   => $request->peserta,
            'status'         => 'pending',
            'payment_proof'  => $path,
            'payment_status' => 'pending',
        ]);

        return redirect()->route('meeting-room.order')->with('success', 'Reservasi berhasil dibuat! Menunggu konfirmasi pembayaran dari admin.');
    }

    public function adminIndex()
    {
        $bookings = MeetingRoomBooking::latest()->get();
        return view('admin.meeting-room.index', compact('bookings'));
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

        $totalMinutes = $booking->duration * 60;
        $remainingRaw = $totalMinutes - (int) $booking->total_used_minutes;

        if ($remainingRaw <= 0) {
            return redirect()->back()->with('error', 'Waktu reservasi sudah habis.');
        }

        $booking->update([
            'status'      => 'checkin',
            'checkin_at'  => now(),
            'checkout_at' => null,
        ]);

        return redirect()->back()->with('success', 'Berhasil Check In.');
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
        $sessionMinutes = (int) floor($sessionSeconds / 60);

        $prevUsed     = (int) $booking->total_used_minutes;
        $newTotalUsed = $prevUsed + $sessionMinutes;
        $totalMinutes = (int) ($booking->duration * 60);

        $newStatus = ($newTotalUsed >= $totalMinutes) ? 'selesai' : 'paused';

        $booking->update([
            'status'             => $newStatus,
            'total_used_minutes' => $newTotalUsed,
            'checkout_at'        => now(),
            'checkin_at'         => null,
        ]);

        return redirect()->back()->with('success', 'Berhasil Check Out. Waktu terpakai sesi ini: ' . $sessionMinutes . ' menit.');
    }
}
