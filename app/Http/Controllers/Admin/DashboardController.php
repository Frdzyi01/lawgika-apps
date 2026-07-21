<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingOrders = Order::whereIn('status', ['draft', 'waiting_verification', 'pending'])->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalServices = Service::count();
        $recentOrders = Order::with(['user', 'service'])->latest()->take(5)->get();

        // New CRM Stats
        $pendingMeetings = \App\Models\MeetingRoomBooking::where('status', 'pending')->count();
        $pendingPodcasts = \App\Models\PodcastRoomBooking::where('status', 'pending')->count();
        $pendingSurat = \App\Models\Correspondence::whereNull('parent_id')->where('status', 'pending')->count();
        
        $activeCheckins = \App\Models\MeetingRoomBooking::where('status', 'checkin')->count() + 
                          \App\Models\PodcastRoomBooking::where('status', 'checkin')->count();

        return view('admin.dashboard.index', compact(
            'pendingOrders', 'totalCustomers', 'totalServices', 'recentOrders',
            'pendingMeetings', 'pendingPodcasts', 'pendingSurat', 'activeCheckins'
        ));
    }
}
