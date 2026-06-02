<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\DocumentService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected DocumentService $documentService) {}

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
        $order->load(['service.documentRequirements', 'documents']);
        $documentSummary = $this->documentService->getDocumentSummary($order);

        return view('customer.orders.show', compact('order', 'documentSummary'));
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

        return back()->with('success', __('flash.payment_proof_submitted_success'));
    }
}
