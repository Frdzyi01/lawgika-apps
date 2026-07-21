<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PodcastRoomBooking;
use App\Models\MeetingRoomBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        $orders = Order::where('user_id', $userId)->latest()->take(50)->get()->map(function($item) {
            return [
                'title' => 'Layanan: ' . ($item->service_name ?? 'Order Baru'),
                'time' => $item->created_at,
                'url' => url('dashboard/orders/' . $item->id),
                'icon' => 'cart-outline',
                'color' => 'primary',
                'desc' => $item->name . ' (' . $item->order_number . ')'
            ];
        });

        $podcasts = PodcastRoomBooking::where('user_id', $userId)->latest()->take(50)->get()->map(function($item) {
            return [
                'title' => 'Podcast Room',
                'time' => $item->created_at,
                'url' => url('dashboard/podcast-room?search=' . $item->id),
                'icon' => 'mic-outline',
                'color' => 'success',
                'desc' => $item->name . ' (' . ($item->order_number ?? '#'.$item->id) . ')'
            ];
        });

        $meetings = MeetingRoomBooking::where('user_id', $userId)->latest()->take(50)->get()->map(function($item) {
            return [
                'title' => 'Meeting Room',
                'time' => $item->created_at,
                'url' => url('dashboard/meeting-room'), // Assuming dashboard/meeting-room
                'icon' => 'business-outline',
                'color' => 'info',
                'desc' => $item->name . ' (#' . $item->id . ')'
            ];
        });

        $allNotifications = $orders->concat($podcasts)->concat($meetings)->sortByDesc('time')->values();

        // Paginate the collection
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 50;
        $currentItems = $allNotifications->slice(($currentPage - 1) * $perPage, $perPage)->all();
        
        $notifications = new LengthAwarePaginator(
            $currentItems,
            $allNotifications->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('customer.notifications.index', compact('notifications'));
    }
}
