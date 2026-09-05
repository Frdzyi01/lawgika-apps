<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\DocumentService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use App\Notifications\OrderStatusUpdated;

class OrderController extends Controller
{
    public function __construct(
        protected DocumentService $documentService,
        protected \App\Services\RoomBenefitService $benefitService,
        protected WhatsAppService $whatsAppService
    ) {}

    public function index(Request $request)
    {
        $query = Order::with(['user', 'service', 'roomBenefit', 'roomBenefits'])->latest();

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
        $order->load(['user', 'service.documentRequirements', 'documents', 'roomBenefits']);
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
            'payment_status'           => 'required|in:unpaid,pending_verification,verified,rejected',
            'payment_rejection_reason' => 'nullable|string|max:1000',
        ]);

        $updateData = [
            'payment_status' => $request->payment_status,
        ];

        if ($request->payment_status === 'rejected') {
            $updateData['payment_rejection_reason'] = $request->payment_rejection_reason;
        } else {
            $updateData['payment_rejection_reason'] = null;
        }

        $order->update($updateData);

        if ($order->payment_status === 'verified' && \App\Models\RoomBenefit::isEligibleForOrder($order)) {
            try {
                $this->benefitService->approve($order);
            } catch (\Exception $e) {
                // Benefit might already exist or other error
            }
        }

        return redirect()->back()->with('success', 'Status pembayaran pesanan berhasil diperbarui menjadi: ' . $order->payment_status_label);
    }

    /**
     * Kirim manual WhatsApp Payment Reminder ke client.
     *
     * HANYA diizinkan jika payment_status === 'unpaid'.
     */
    public function sendPaymentReminder(Order $order, \App\Services\WhatsAppService $whatsAppService)
    {
        if ($order->payment_status !== 'unpaid') {
            return redirect()->back()->with('error', 'Reminder pembayaran hanya dapat dikirim jika status pembayaran Belum Bayar.');
        }

        try {
            $log = $whatsAppService->notifyInvoiceDueReminder($order);

            $clientName = $order->user->company_name ?? ($order->user->name ?? 'Client');

            if ($log && ($log->status === 'SUCCESS' || $log->status === 'FAILED')) {
                // If template ID is present and API processed the request
                return redirect()->back()->with('success', '📲 Reminder pembayaran WhatsApp berhasil dikirim ke client (' . $clientName . ').');
            } else {
                return redirect()->back()->with('error', '❌ Gagal mengirim reminder WhatsApp. Pastikan konfigurasi BOTCAKE_TEMPLATE_INVOICE_DUE_REMINDER sudah diisi.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ── Admin Create & Store (CRM) ────────────────────────────────────────────

    public function create(Request $request)
    {
        $services = \App\Http\Controllers\UniversalOrderController::$services;
        $packages = \App\Http\Controllers\UniversalOrderController::$packages;
        $activePackages = \App\Http\Controllers\UniversalOrderController::getActivePackagesPerService();

        $renewOrder = null;
        $preselectedClient = null;

        if ($request->filled('renew_order_id')) {
            $renewOrder = \App\Models\Order::with(['user', 'roomBenefits'])->find($request->renew_order_id);
            if ($renewOrder) {
                $preselectedClient = $renewOrder->user;
            }
        } elseif ($request->filled('client_id')) {
            $preselectedClient = \App\Models\User::find($request->client_id);
        }

        return view('admin.orders.create', compact('services', 'packages', 'activePackages', 'renewOrder', 'preselectedClient'));
    }

    public function getDocumentRequirements($serviceKey)
    {
        $serviceInfo = \App\Http\Controllers\UniversalOrderController::$services[$serviceKey] ?? ['db_slug' => $serviceKey];
        $dbService   = \App\Models\Service::where('slug', $serviceInfo['db_slug'] ?? $serviceKey)->first();
        if (!$dbService) return response()->json([]);
        
        return response()->json($dbService->documentRequirements);
    }

    public function store(Request $request)
    {
        $rules = [
            'user_id'             => 'required|exists:users,id',
            'service'             => 'required|string',
            'package'             => 'required|string|max:100',
            'status'              => 'required|string',
            'payment_status'      => 'required|string',
            'notes'               => 'nullable|string|max:2000',
            'documents.*.*'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ];

        $request->validate($rules);

        $serviceKey  = strtolower($request->service);
        $packageKey  = strtolower($request->package);
        $serviceInfo = \App\Http\Controllers\UniversalOrderController::$services[$serviceKey] ?? ['db_slug' => $serviceKey];
        $dbService   = \App\Models\Service::where('slug', $serviceInfo['db_slug'] ?? $serviceKey)->first();

        // Resolve labels
        $packageLabel = \App\Http\Controllers\UniversalOrderController::$packages[$packageKey] ?? \Illuminate\Support\Str::title(str_replace('-', ' ', $packageKey));
        $serviceName  = ($serviceInfo['label'] ?? \Illuminate\Support\Str::title(str_replace('-', ' ', $serviceKey))) . ' – ' . $packageLabel;

        // Fetch client to auto-fill profile data
        $client = \App\Models\User::find($request->user_id);

        // Build form_data using Client data
        $formData = [
            'service'             => $serviceKey,
            'package'             => $packageKey,
            'director_name'       => $client->pic_name ?: $client->name, // fallback to name
            'director_phone'      => $client->phone,
            'company_name'        => $client->company_name,
            'pic_name'            => $client->pic_name ?: $client->name,
            'pic_phone'           => $client->phone,
            'company_email'       => $client->email,
            'operational_address' => $client->address,
            'business_field'      => $client->business_type,
        ];
        if ($request->has('modal_dasar')) {
            $formData['modal_dasar'] = $request->input('modal_dasar');
        }

        $totalBiaya = 0;
        if ($serviceKey === 'pendirian-pt' && $request->has('modal_dasar')) {
            $md = $request->input('modal_dasar');
            $totalBiaya = \App\Http\Controllers\UniversalOrderController::$ptPricing[$md][$packageKey] ?? 0;
        } elseif (isset(\App\Http\Controllers\UniversalOrderController::$otherPricing[$serviceKey][$packageKey])) {
            $totalBiaya = \App\Http\Controllers\UniversalOrderController::$otherPricing[$serviceKey][$packageKey];
        }

        // Allow overriding total_price if provided
        if ($request->filled('total_price')) {
            $totalBiaya = (int) str_replace(['.', ','], '', $request->total_price);
        }

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(substr($serviceKey, 0, 3)) . '-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5)),
            'user_id'      => $request->user_id,
            'created_by'   => auth()->id(),
            'service_id'   => $dbService?->id,
            'service_name' => $serviceName,
            'status'       => $request->status,
            'payment_status'=> $request->payment_status,
            'total_price'  => $totalBiaya,
            'notes'        => $request->notes,
            'form_data'    => $formData,
        ]);

        // ── Handle Upload Dokumen (Admin) ────────────────────────────────────────
        $uploadedDocuments = $request->file('documents');
        if (!empty($uploadedDocuments) && is_array($uploadedDocuments)) {
            foreach ($uploadedDocuments as $docType => $files) {
                if (is_array($files)) {
                    foreach ($files as $file) {
                        if ($file->isValid()) {
                            $cleanName = \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                            $ext       = $file->getClientOriginalExtension();
                            $filename  = time() . "_{$cleanName}.{$ext}";
                            $path      = $file->storeAs('documents/' . $order->id, $filename, 'public');

                            \App\Models\Document::create([
                                'order_id'      => $order->id,
                                'user_id'       => $order->user_id,
                                'service_id'    => $order->service_id,
                                'document_type' => $docType, // Maps to KTP_DIREKTUR, dll
                                'type'          => strtolower($docType),
                                'filename'      => $filename,
                                'original_name' => $file->getClientOriginalName(),
                                'path'          => $path,
                                'status'        => 'approved', // Admin uploads are automatically approved
                                'approved_at'   => now(),
                                'approved_by'   => auth()->id(),
                            ]);
                        }
                    }
                }
            }
        }

        // Auto-approve benefit if status is approved
        if ($order->status === 'approved' && \App\Models\RoomBenefit::isEligibleForOrder($order)) {
            try {
                $this->benefitService->approve($order);
            } catch (\Exception $e) {
                // Ignore
            }
        }

        // ── WhatsApp Notification ─────────────────────────────────────────────
        $waMessage = '';
        if ($serviceKey !== 'virtual-office') {
            try {
                $waLog = $this->whatsAppService->notifyOrderCreated($order);
                if ($waLog && $waLog->status === \App\Models\WhatsappLog::STATUS_SUCCESS) {
                    $waMessage = ' WhatsApp notifikasi berhasil dikirim.';
                } elseif ($waLog) {
                    $waMessage = ' Tetapi WhatsApp gagal dikirim.';
                }
            } catch (\Exception $e) {
                $waMessage = ' Tetapi WhatsApp gagal dikirim.';
            }
        }

        if ($serviceKey === 'virtual-office') {
            return redirect()->route('admin.virtual-office.index')
                ->with('success', 'Data Virtual Office baru berhasil ditambahkan oleh Admin.');
        }

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan baru berhasil ditambahkan oleh Admin.' . $waMessage)
            ->with($waMessage && str_contains($waMessage, 'gagal') ? 'warning' : 'info', $waMessage ?: null);
    }

    /**
     * Generate atau regenerate QR Code token untuk order.
     */
    public function generateQr(Order $order)
    {
        $order->generateQrToken();

        return redirect()->back()->with('success', 'QR Code layanan berhasil di-generate untuk order #' . $order->order_number);
    }

    /**
     * Download QR Code order sebagai file SVG.
     */
    public function downloadQr(Order $order)
    {
        if (empty($order->qr_token)) {
            $order->generateQrToken();
        }

        $targetUrl = $order->qr_url;

        try {
            $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(400)->margin(2)->generate($targetUrl);
            return response($svg, 200, [
                'Content-Type'        => 'image/svg+xml',
                'Content-Disposition' => 'attachment; filename="qr-' . $order->order_number . '.svg"',
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal men-download QR Code: ' . $e->getMessage());
        }
    }
}