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
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalServices = Service::count();
        $recentOrders = Order::with(['user', 'service'])->latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'pendingOrders', 'totalCustomers', 'totalServices', 'recentOrders'
        ));
    }
}
