<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Order;
use App\Models\PodcastRoomBooking;
use App\Models\MeetingRoomBooking;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (isset($_COOKIE['lw_language']) && in_array($_COOKIE['lw_language'], ['id', 'en', 'zh'])) {
            app()->setLocale($_COOKIE['lw_language']);
        }

        View::composer('layouts-admin.admin', function ($view) {
            $orders = Order::latest()->take(5)->get()->map(function($item) {
                return [
                    'title' => 'Layanan: ' . ($item->service_name ?? 'Order Baru'),
                    'time' => $item->created_at,
                    'url' => url('admin/orders/' . $item->id),
                    'icon' => 'cart-outline',
                    'color' => 'primary',
                    'desc' => $item->name . ' (' . $item->order_number . ')'
                ];
            });

            $podcasts = PodcastRoomBooking::latest()->take(5)->get()->map(function($item) {
                return [
                    'title' => 'Podcast Room',
                    'time' => $item->created_at,
                    'url' => url('admin/podcast-room?search=' . $item->id),
                    'icon' => 'mic-outline',
                    'color' => 'success',
                    'desc' => $item->name . ' (' . ($item->order_number ?? '#'.$item->id) . ')'
                ];
            });

            $meetings = MeetingRoomBooking::latest()->take(5)->get()->map(function($item) {
                return [
                    'title' => 'Meeting Room',
                    'time' => $item->created_at,
                    'url' => url('admin/meeting-room?search=' . $item->id),
                    'icon' => 'business-outline',
                    'color' => 'info',
                    'desc' => $item->name . ' (#' . $item->id . ')'
                ];
            });

            $notifications = $orders->concat($podcasts)->concat($meetings)
                                   ->sortByDesc('time')
                                   ->take(5);

            $view->with('admin_notifications', $notifications);
        });

        View::composer('layouts-customer.app', function ($view) {
            $userId = Auth::id();
            
            if ($userId) {
                $orders = Order::where('user_id', $userId)->latest()->take(5)->get()->map(function($item) {
                    return [
                        'title' => 'Layanan: ' . ($item->service_name ?? 'Order Baru'),
                        'time' => $item->created_at,
                        'url' => url('dashboard/orders/' . $item->id), // Will adjust if route is different
                        'icon' => 'cart-outline',
                        'color' => 'primary',
                        'desc' => $item->name . ' (' . $item->order_number . ')'
                    ];
                });

                $podcasts = PodcastRoomBooking::where('user_id', $userId)->latest()->take(5)->get()->map(function($item) {
                    return [
                        'title' => 'Podcast Room',
                        'time' => $item->created_at,
                        'url' => url('dashboard/podcast-room?search=' . $item->id), // Will adjust if route is different
                        'icon' => 'mic-outline',
                        'color' => 'success',
                        'desc' => $item->name . ' (' . ($item->order_number ?? '#'.$item->id) . ')'
                    ];
                });

                $meetings = MeetingRoomBooking::where('user_id', $userId)->latest()->take(5)->get()->map(function($item) {
                    return [
                        'title' => 'Meeting Room',
                        'time' => $item->created_at,
                        'url' => url('dashboard/meeting-room'), // Assuming dashboard/meeting-room for customer doesn't have ?search filter handling yet, maybe I'll add search filter to customer too if needed, or just link to the page
                        'icon' => 'business-outline',
                        'color' => 'info',
                        'desc' => $item->name . ' (#' . $item->id . ')'
                    ];
                });

                $notifications = $orders->concat($podcasts)->concat($meetings)
                                       ->sortByDesc('time')
                                       ->take(5);
            } else {
                $notifications = collect([]);
            }

            $view->with('customer_notifications', $notifications);
        });
    }
}
