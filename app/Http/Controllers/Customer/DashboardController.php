<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

use App\Models\SptBadan;

class DashboardController extends Controller
{
    public function index()
    {
        // 5 pesanan terbaru untuk tabel
        $orders = Order::where('user_id', auth()->id())->latest()->paginate(5);

        // Stats ringkasan (query terpisah)
        $stats = [
            'total'      => Order::where('user_id', auth()->id())->count(),
            'pending'    => Order::where('user_id', auth()->id())->where('status', 'pending')->count(),
            'approved'   => Order::where('user_id', auth()->id())->whereIn('status', ['approved', 'completed'])->count(),
            'rejected'   => Order::where('user_id', auth()->id())->where('status', 'rejected')->count(),
        ];

        $quota = \App\Models\UserRoomQuota::where('user_id', auth()->id())->first();

        return view('customer.dashboard.index', compact('orders', 'stats', 'quota'));
    }
}
