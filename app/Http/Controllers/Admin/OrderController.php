<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Notifications\OrderStatusUpdated;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'service'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'service', 'documents']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status'      => 'required|in:pending,approved,processing,rejected,completed,cancelled',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $order->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        // Kirim notifikasi email ke customer jika status berubah
        if ($order->wasChanged('status') && $order->user) {
            try {
                $order->user->notify(new OrderStatusUpdated($order));
            } catch (\Exception $e) {
                // Silence mail errors in dev environment
            }
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui menjadi: ' . ucfirst($request->status));
    }
}
