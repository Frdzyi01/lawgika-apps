<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PodcastRoomBooking;
use App\Models\MeetingRoomBooking;
use App\Models\RoomBenefit;
use App\Models\UserRoomQuota;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // 5 pesanan terbaru untuk tabel
        $orders = Order::where('user_id', $userId)->latest()->paginate(5);

        // Stats ringkasan gabungan (Order + Room Bookings)
        $totalOrders = Order::where('user_id', $userId)->count() 
            + PodcastRoomBooking::where('user_id', $userId)->count() 
            + MeetingRoomBooking::where('user_id', $userId)->count();

        $pendingOrders = Order::where('user_id', $userId)->where('status', 'pending')->count()
            + PodcastRoomBooking::where('user_id', $userId)->where('status', 'pending')->count()
            + MeetingRoomBooking::where('user_id', $userId)->where('status', 'pending')->count();

        $approvedOrders = Order::where('user_id', $userId)->whereIn('status', ['approved', 'completed'])->count()
            + PodcastRoomBooking::where('user_id', $userId)->whereIn('status', ['approved', 'checkin', 'selesai'])->count()
            + MeetingRoomBooking::where('user_id', $userId)->whereIn('status', ['approved', 'checkin', 'selesai'])->count();

        $rejectedOrders = Order::where('user_id', $userId)->where('status', 'rejected')->count()
            + PodcastRoomBooking::where('user_id', $userId)->where('status', 'rejected')->count()
            + MeetingRoomBooking::where('user_id', $userId)->where('status', 'rejected')->count();

        $stats = [
            'total'    => $totalOrders,
            'pending'  => $pendingOrders,
            'approved' => $approvedOrders,
            'rejected' => $rejectedOrders,
        ];

        $quota = UserRoomQuota::where('user_id', $userId)->first();
        $roomBenefits = RoomBenefit::where('user_id', $userId)
            ->where('is_active', true)
            ->latest()
            ->get();

        // Active running sessions (Check-In)
        $activePodcast = PodcastRoomBooking::where('user_id', $userId)
            ->where('status', 'checkin')
            ->first();

        $activeMeeting = MeetingRoomBooking::where('user_id', $userId)
            ->where('status', 'checkin')
            ->first();

        return view('customer.dashboard.index', compact('orders', 'stats', 'quota', 'roomBenefits', 'activePodcast', 'activeMeeting'));
    }
}

