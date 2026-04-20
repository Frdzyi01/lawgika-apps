<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->with('service')->latest()->paginate(10);
        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id != auth()->id()) {
            abort(403);
        }
        $order->load(['service', 'documents']);
        return view('customer.orders.show', compact('order'));
    }

    public function uploadPaymentProof(Request $request, Order $order)
    {
        if ($order->user_id != auth()->id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $file     = $request->file('payment_proof');
        $filename = time() . '_payment_' . $order->id . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('payments/order_' . $order->id, $filename, 'public');

        $order->update([
            'payment_proof'  => $path,
            'payment_status' => 'pending_verification',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil dikirim. Tim kami akan memverifikasi segera.');
    }
}
