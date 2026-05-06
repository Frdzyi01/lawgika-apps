<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use App\Notifications\OrderStatusUpdated;

class OrderController extends Controller
{
    public function __construct(
        protected DocumentService $documentService,
        protected \App\Services\RoomBenefitService $benefitService
    ) {}

    public function index(Request $request)
    {
        $query = Order::with(['user', 'service', 'roomBenefit'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('service')) {
            $serviceSlug = $request->service;
            $query->where(function($q) use ($serviceSlug) {
                $q->whereHas('service', function($sq) use ($serviceSlug) {
                    $sq->where('slug', $serviceSlug);
                })->orWhere('form_data->service', $serviceSlug);
            });
        }

        $orders = $query->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'service.documentRequirements', 'documents']);
        $documentSummary = $this->documentService->getDocumentSummary($order);
        return view('admin.orders.show', compact('order', 'documentSummary'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status'      => 'required|string|max:50',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $order->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        // Kirim notifikasi email ke customer jika status berubah
        if ($order->wasChanged('status')) {
            // ── Benefit Activation ───────────────────────────────────────────
            // Activate benefit automatically if status changed to 'approved'
            if ($order->status === 'approved' && \App\Models\RoomBenefit::isEligibleForOrder($order)) {
                try {
                    $this->benefitService->approve($order);
                } catch (\Exception $e) {
                    // Benefit might already exist or other validation error
                }
            }

            if ($order->user) {
                try {
                    $order->user->notify(new OrderStatusUpdated($order));
                } catch (\Exception $e) {
                    // Silence mail errors in dev environment
                }
            }
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui menjadi: ' . ucfirst($request->status));
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,pending_verification,verified,rejected',
        ]);

        $order->update([
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->back()->with('success', 'Status pembayaran pesanan berhasil diperbarui menjadi: ' . $order->payment_status_label);
    }
}