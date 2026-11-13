<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use App\Models\Promo;
use App\Models\EventUpComing;
use App\Models\peraturanKBLI;
use App\Models\Berita;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Data Khusus Admin Konten (Admin 2) ───────────────────────────────────
        $totalPromo   = Promo::count();
        $totalEvents  = EventUpComing::count();
        $totalKbli    = peraturanKBLI::count();
        $totalBerita  = Berita::count();

        $recentBeritas = Berita::latest()->take(5)->get();
        $recentPromos  = Promo::latest()->take(5)->get();
        $recentEvents  = EventUpComing::latest()->take(5)->get();

        // ── Data CRM & Client Operasional (SPV, Admin 1, Admin 3) ────────────────
        $pendingOrders         = Order::whereIn('status', ['pending_approval', 'draft', 'waiting_verification', 'pending'])->count();
        $pendingApprovalOrders = Order::where('status', 'pending_approval')->count();
        $rejectedOrdersCount   = Order::where('status', 'rejected')->count();
        $pendingChangeRequests = \App\Models\OrderChangeRequest::where('status', 'pending')->count();
        $totalCustomers        = User::where('role', 'customer')->count();
        $totalServices         = Service::count();
        $recentOrders          = Order::with(['user', 'service'])->latest()->take(5)->get();

        $pendingMeetings = \App\Models\MeetingRoomBooking::where('status', 'pending')->count();
        $pendingPodcasts = \App\Models\PodcastRoomBooking::where('status', 'pending')->count();
        $pendingSurat    = \App\Models\Correspondence::whereNull('parent_id')->where('status', 'pending')->count();
        
        $activeCheckins  = \App\Models\MeetingRoomBooking::where('status', 'checkin')->count() + 
                           \App\Models\PodcastRoomBooking::where('status', 'checkin')->count();

        return view('admin.dashboard.index', compact(
            'pendingOrders', 'pendingApprovalOrders', 'rejectedOrdersCount', 'pendingChangeRequests',
            'totalCustomers', 'totalServices', 'recentOrders',
            'pendingMeetings', 'pendingPodcasts', 'pendingSurat', 'activeCheckins',
            'totalPromo', 'totalEvents', 'totalKbli', 'totalBerita',
            'recentBeritas', 'recentPromos', 'recentEvents'
        ));
    }
}
