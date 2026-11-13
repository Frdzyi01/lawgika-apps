<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UniversalOrderController;
use App\Models\Document;
use App\Models\Order;
use App\Models\OrderAuditLog;
use App\Models\OrderChangeRequest;
use App\Models\OrderHistory;
use App\Models\RoomBenefit;
use App\Models\Service;
use App\Models\User;
use App\Models\WhatsappLog;
use App\Notifications\OrderStatusUpdated;
use App\Services\DocumentService;
use App\Services\RoomBenefitService;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public const PAYMENT_METHODS = [
        'Transfer Bank (BCA)',
        'Transfer Bank (Mandiri)',
        'Transfer Bank (BRI)',
        'Transfer Bank (BNI)',
        'Virtual Account / QRIS',
        'Cash / Tunai',
        'Lainnya',
    ];

    public function __construct(
        protected DocumentService $documentService,
        protected RoomBenefitService $benefitService,
        protected WhatsAppService $whatsAppService
    ) {}

    public function index(Request $request)
    {
        $tab = $request->input('tab', 'all');

        $counts = [
            'all'              => Order::count(),
            'pending_approval' => Order::whereIn('status', [Order::DOC_STATUS_PENDING_APPROVAL, Order::DOC_STATUS_PENDING])->count(),
            'rejected'         => Order::where('status', Order::DOC_STATUS_REJECTED)->count(),
            'approved'         => Order::whereIn('status', [Order::DOC_STATUS_APPROVED, Order::DOC_STATUS_PROCESSING, Order::DOC_STATUS_VERIFIED, Order::DOC_STATUS_COMPLETED])->count(),
            'change_requests'  => OrderChangeRequest::where('status', 'pending')->count(),
            'trashed'          => Order::onlyTrashed()->count(),
        ];

        $changeRequests = collect();
        if ($tab === 'change_requests') {
            $changeRequests = OrderChangeRequest::with(['order', 'requester', 'reviewer'])
                ->latest()
                ->paginate(20)
                ->withQueryString();
        }

        if ($tab === 'trashed') {
            abort_unless(auth()->user()->canApproveOrders(), 403, 'Hanya Admin 3 atau SPV yang dapat melihat riwayat pesanan terhapus.');
            $query = Order::onlyTrashed()->with(['user', 'service', 'deleter', 'approver', 'rejector'])->latest('deleted_at');
        } else {
            $query = Order::with(['user', 'service', 'roomBenefit', 'roomBenefits', 'creator', 'approver', 'rejector', 'pendingChangeRequests'])
                ->latest();
        }

        if ($tab === 'pending_approval') {
            $query->whereIn('status', [Order::DOC_STATUS_PENDING_APPROVAL, Order::DOC_STATUS_PENDING]);
        } elseif ($tab === 'rejected') {
            $query->where('status', Order::DOC_STATUS_REJECTED);
        } elseif ($tab === 'approved') {
            $query->whereIn('status', [Order::DOC_STATUS_APPROVED, Order::DOC_STATUS_PROCESSING, Order::DOC_STATUS_VERIFIED, Order::DOC_STATUS_COMPLETED]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('service')) {
            $serviceSlug = $request->service;
            $query->where(function ($q) use ($serviceSlug) {
                $q->whereHas('service', function ($sq) use ($serviceSlug) {
                    $sq->where('slug', $serviceSlug);
                })->orWhere('form_data->service', $serviceSlug);
            });
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('invoice_number', 'like', "%{$search}%")
                    ->orWhere('service_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('company_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(20)->withQueryString();
        $services = UniversalOrderController::$services;
        $packages = UniversalOrderController::$packages;

        return view('admin.orders.index', compact('orders', 'counts', 'tab', 'changeRequests', 'services', 'packages'));
    }

    public function show(string $orderId)
    {
        $query = auth()->user()->canApproveOrders()
            ? Order::withTrashed()
            : Order::query();

        $order = $query->with([
            'user',
            'service.documentRequirements',
            'documents',
            'roomBenefits.approver',
            'creator',
            'approver',
            'rejector',
            'deleter',
            'histories.actor',
            'auditLogs.user',
            'changeRequests.requester',
            'changeRequests.reviewer',
        ])->findOrFail($orderId);

        $documentSummary = $this->documentService->getDocumentSummary($order);
        $services = UniversalOrderController::$services;
        $packages = UniversalOrderController::$packages;
        $paymentMethods = self::PAYMENT_METHODS;

        return view('admin.orders.show', compact(
            'order',
            'documentSummary',
            'services',
            'packages',
            'paymentMethods'
        ));
    }

    public function edit(Order $order)
    {
        abort_unless(auth()->user()->canDirectlyModifyOrders(), 403, 'Hanya Admin 3 atau SPV yang berhak mengedit pesanan secara langsung.');

        $order->load(['user', 'service', 'roomBenefits']);
        $services = UniversalOrderController::$services;
        $packages = UniversalOrderController::$packages;
        $activePackages = UniversalOrderController::getActivePackagesPerService();
        $paymentMethods = self::PAYMENT_METHODS;

        return view('admin.orders.edit', compact('order', 'services', 'packages', 'activePackages', 'paymentMethods'));
    }

    public function update(Request $request, Order $order)
    {
        abort_unless(auth()->user()->canDirectlyModifyOrders(), 403, 'Hanya Admin 3 atau SPV yang berhak mengedit pesanan secara langsung.');

        $request->validate([
            'invoice_number'   => ['nullable', 'string', 'max:100', Rule::unique('orders', 'invoice_number')->ignore($order->id)],
            'transaction_date' => 'sometimes|required|date',
            'service'          => 'sometimes|required|string|max:100',
            'package'          => 'sometimes|required|string|max:100',
            'total_price'      => 'nullable',
            'payment_method'   => 'nullable|string|max:100',
            'payment_status'   => 'nullable|in:unpaid,pending_verification,verified,rejected',
            'payment_proof'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'status'           => 'sometimes|required|string|max:50',
            'notes'            => 'nullable|string|max:2000',
            'admin_notes'      => 'nullable|string|max:1000',
            'edit_reason'      => 'nullable|string|max:1000',
            'rejection_reason' => 'nullable|string|max:2000',
        ]);

        if ($request->input('status') === Order::DOC_STATUS_REJECTED && ! $request->filled('rejection_reason')) {
            return redirect()->back()->withInput()->withErrors(['rejection_reason' => 'Alasan penolakan wajib diisi jika status diubah menjadi Ditolak.']);
        }

        $oldSnapshot = $this->makeOrderSnapshot($order);

        DB::transaction(function () use ($request, $order, $oldSnapshot) {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            $serviceKey = strtolower($request->input('service', $lockedOrder->form_data['service'] ?? ''));
            $packageKey = strtolower($request->input('package', $lockedOrder->form_data['package'] ?? ''));
            $modalDasar = $request->input('modal_dasar', $lockedOrder->form_data['modal_dasar'] ?? null);

            $resolved = $this->resolveServiceAndPrice(
                $serviceKey,
                $packageKey,
                $modalDasar,
                $request->has('total_price') ? $request->input('total_price') : $lockedOrder->total_price
            );

            $formData = $lockedOrder->form_data ?? [];
            if ($serviceKey !== '') {
                $formData['service'] = $serviceKey;
            }
            if ($packageKey !== '') {
                $formData['package'] = $packageKey;
            }
            if ($modalDasar !== null) {
                $formData['modal_dasar'] = $modalDasar;
            }

            $newStatus = $request->input('status', $lockedOrder->status);
            $txDate = $request->filled('transaction_date')
                ? Carbon::parse($request->input('transaction_date'))->toDateString()
                : ($lockedOrder->transaction_date?->toDateString() ?? $lockedOrder->created_at->toDateString());

            $updateData = [
                'invoice_number'   => $request->filled('invoice_number') ? trim($request->invoice_number) : $lockedOrder->invoice_number,
                'transaction_date' => $txDate,
                'service_id'       => $resolved['service_id'] ?? $lockedOrder->service_id,
                'service_name'     => ($request->filled('service') || $request->filled('package')) ? $resolved['service_name'] : $lockedOrder->service_name,
                'total_price'      => $request->has('total_price') || $request->filled('service') || $request->filled('package') ? $resolved['total_price'] : $lockedOrder->total_price,
                'payment_method'   => $request->filled('payment_method') ? $request->payment_method : $lockedOrder->payment_method,
                'payment_status'   => $request->filled('payment_status') ? $request->payment_status : $lockedOrder->payment_status,
                'status'           => $newStatus,
                'notes'            => $request->has('notes') ? $request->notes : $lockedOrder->notes,
                'admin_notes'      => $request->has('admin_notes') ? $request->admin_notes : $lockedOrder->admin_notes,
                'form_data'        => $formData,
            ];

            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = time() . '_proof_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $updateData['payment_proof'] = $file->storeAs('payment_proofs/' . $lockedOrder->id, $filename, 'public');
            }

            if ($newStatus === Order::DOC_STATUS_APPROVED && $oldSnapshot['status'] !== Order::DOC_STATUS_APPROVED) {
                $updateData['approved_by'] = auth()->id();
                $updateData['approved_at'] = now();
            } elseif ($newStatus === Order::DOC_STATUS_REJECTED) {
                $updateData['rejected_by'] = auth()->id();
                $updateData['rejected_at'] = now();
                $updateData['rejection_reason'] = $request->rejection_reason;
            }

            $lockedOrder->update($updateData);
            $lockedOrder->refresh();

            // Benefit orchestration: never auto-activate during pending_approval/rejected
            if ($lockedOrder->isValidated()) {
                if (RoomBenefit::where('order_id', $lockedOrder->id)->exists()) {
                    $this->benefitService->syncDatesFromOrder($lockedOrder);
                } elseif (RoomBenefit::isEligibleForOrder($lockedOrder)) {
                    try {
                        $this->benefitService->activateOrSyncForOrder($lockedOrder, auth()->id());
                    } catch (\Throwable $e) {
                        // Ineligible package ignored gracefully
                    }
                }
            } elseif (in_array($lockedOrder->status, [Order::DOC_STATUS_REJECTED, Order::DOC_STATUS_CANCELLED, Order::DOC_STATUS_PENDING_APPROVAL], true)) {
                $this->benefitService->deactivateForOrder($lockedOrder);
            }

            $newSnapshot = $this->makeOrderSnapshot($lockedOrder);
            $reason = $request->input('edit_reason') ?: ('Perubahan transaksi langsung oleh ' . auth()->user()->roleLabel());

            $this->recordHistory(
                $lockedOrder,
                'edited',
                $oldSnapshot['status'],
                $lockedOrder->status,
                $reason,
                $newSnapshot
            );

            $this->recordAudit($lockedOrder, 'update', $oldSnapshot, $newSnapshot, $reason);
        });

        $order->refresh();
        if ($order->user && $oldSnapshot['status'] !== $order->status) {
            try {
                $order->user->notify(new OrderStatusUpdated($order));
            } catch (\Exception $e) {
                // Silence mail errors in dev environment
            }
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Data transaksi & pesanan berhasil diperbarui dan tercatat di Audit Trail.');
    }

    public function destroy(Request $request, Order $order)
    {
        abort_unless(auth()->user()->canDirectlyModifyOrders(), 403, 'Hanya Admin 3 atau SPV yang berhak menghapus pesanan secara langsung.');

        $reason = trim((string) $request->input('delete_reason', ''));
        if ($reason === '') {
            $reason = 'Dihapus oleh ' . auth()->user()->roleLabel();
        }

        DB::transaction(function () use ($order, $reason) {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $oldSnapshot = $this->makeOrderSnapshot($lockedOrder);

            $this->benefitService->deactivateForOrder($lockedOrder);

            $this->recordHistory(
                $lockedOrder,
                'deleted',
                $lockedOrder->status,
                'deleted',
                $reason,
                $oldSnapshot
            );

            $this->recordAudit($lockedOrder, 'delete', $oldSnapshot, null, $reason);

            $lockedOrder->update([
                'deleted_by'    => auth()->id(),
                'delete_reason' => $reason,
            ]);
            $lockedOrder->delete();
        });

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus (soft-delete) dengan riwayat & audit trail tetap tersimpan.');
    }

    public function approve(Request $request, Order $order)
    {
        abort_unless(auth()->user()->canApproveOrders(), 403, 'Hanya Admin 3 atau SPV yang berhak menyetujui pesanan.');

        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        try {
            $benefitResult = DB::transaction(function () use ($request, $order) {
                $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

                if ($lockedOrder->status === Order::DOC_STATUS_APPROVED) {
                    throw new \RuntimeException('Pesanan ini sudah berstatus Disetujui (Approved).');
                }

                if ($lockedOrder->status === Order::DOC_STATUS_REJECTED) {
                    throw new \RuntimeException('Pesanan berstatus Ditolak. Admin 1 harus melakukan Revisi Pesanan terlebih dahulu sebelum dapat disetujui.');
                }

                $oldSnapshot = $this->makeOrderSnapshot($lockedOrder);
                $approvedAt = now();

                // Transaction date MUST NOT be changed during approval
                $lockedOrder->update([
                    'status'      => Order::DOC_STATUS_APPROVED,
                    'approved_by' => auth()->id(),
                    'approved_at' => $approvedAt,
                    'admin_notes' => $request->filled('admin_notes') ? $request->admin_notes : $lockedOrder->admin_notes,
                ]);

                $lockedOrder->refresh();

                $benefits = [];
                if (RoomBenefit::isEligibleForOrder($lockedOrder)) {
                    $benefits = $this->benefitService->activateOrSyncForOrder($lockedOrder, auth()->id());
                }

                $newSnapshot = $this->makeOrderSnapshot($lockedOrder);
                $note = $request->input('admin_notes') ?: ('Disetujui oleh ' . auth()->user()->roleLabel() . ' (tgl aktivasi: ' . $lockedOrder->benefit_start_date->format('d M Y') . ')');

                $this->recordHistory($lockedOrder, 'approved', $oldSnapshot['status'], Order::DOC_STATUS_APPROVED, $note, $newSnapshot);
                $this->recordAudit($lockedOrder, 'approve', $oldSnapshot, $newSnapshot, $note);

                return $benefits;
            });
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $order->refresh();
        if ($order->user) {
            try {
                $order->user->notify(new OrderStatusUpdated($order));
            } catch (\Exception $e) {
                // Silence mail errors in dev environment
            }
        }

        $benefitMsg = '';
        if (! empty($benefitResult)) {
            $firstBenefit = $benefitResult[0];
            $statusTxt = $firstBenefit->is_expired ? 'Expired (sesuai tanggal transaksi)' : 'Aktif';
            $benefitMsg = " Benefit diaktifkan periode {$firstBenefit->started_at?->format('d M Y')} s.d. {$firstBenefit->expired_at?->format('d M Y')} ({$statusTxt}).";
        }

        return redirect()->back()->with('success', 'Pesanan berhasil disetujui (Approved).' . $benefitMsg);
    }

    public function reject(Request $request, Order $order)
    {
        abort_unless(auth()->user()->canApproveOrders(), 403, 'Hanya Admin 3 atau SPV yang berhak menolak pesanan.');

        $request->validate([
            'rejection_reason' => 'required|string|min:3|max:2000',
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        DB::transaction(function () use ($request, $order) {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $oldSnapshot = $this->makeOrderSnapshot($lockedOrder);

            $lockedOrder->update([
                'status'           => Order::DOC_STATUS_REJECTED,
                'rejected_by'      => auth()->id(),
                'rejected_at'      => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            $this->benefitService->deactivateForOrder($lockedOrder);
            $lockedOrder->refresh();

            $newSnapshot = $this->makeOrderSnapshot($lockedOrder);

            $this->recordHistory(
                $lockedOrder,
                'rejected',
                $oldSnapshot['status'],
                Order::DOC_STATUS_REJECTED,
                $request->rejection_reason,
                $newSnapshot
            );

            $this->recordAudit($lockedOrder, 'reject', $oldSnapshot, $newSnapshot, $request->rejection_reason);
        });

        $order->refresh();
        if ($order->user) {
            try {
                $order->user->notify(new OrderStatusUpdated($order));
            } catch (\Exception $e) {
                // Silence mail errors in dev environment
            }
        }

        return redirect()->back()->with('success', 'Pesanan berhasil ditolak dan alasan penolakan telah dicatat.');
    }

    public function resubmit(Request $request, Order $order)
    {
        abort_unless(auth()->user()->isAdmin1() || auth()->user()->isSPV(), 403, 'Hanya Admin 1 atau SPV yang dapat mengajukan revisi pesanan.');

        if (! $order->isRejected()) {
            return redirect()->back()->with('error', 'Revisi pesanan hanya dapat diajukan untuk pesanan berstatus Ditolak (Rejected).');
        }

        $request->validate([
            'invoice_number'   => ['required', 'string', 'max:100', Rule::unique('orders', 'invoice_number')->ignore($order->id)],
            'transaction_date' => 'required|date',
            'service'          => 'required|string|max:100',
            'package'          => 'required|string|max:100',
            'total_price'      => 'required',
            'payment_method'   => 'required|string|max:100',
            'payment_status'   => 'required|in:unpaid,pending_verification,verified,rejected',
            'payment_proof'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'notes'            => 'nullable|string|max:2000',
            'revision_note'    => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $order) {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $oldSnapshot = $this->makeOrderSnapshot($lockedOrder);

            $serviceKey = strtolower($request->service);
            $packageKey = strtolower($request->package);
            $modalDasar = $request->input('modal_dasar', $lockedOrder->form_data['modal_dasar'] ?? null);
            $resolved = $this->resolveServiceAndPrice($serviceKey, $packageKey, $modalDasar, $request->total_price);

            $formData = $lockedOrder->form_data ?? [];
            $formData['service'] = $serviceKey;
            $formData['package'] = $packageKey;
            if ($modalDasar !== null) {
                $formData['modal_dasar'] = $modalDasar;
            }

            $updateData = [
                'invoice_number'   => trim($request->invoice_number),
                'transaction_date' => Carbon::parse($request->transaction_date)->toDateString(),
                'service_id'       => $resolved['service_id'],
                'service_name'     => $resolved['service_name'],
                'total_price'      => $resolved['total_price'],
                'payment_method'   => $request->payment_method,
                'payment_status'   => $request->payment_status,
                'notes'            => $request->notes,
                'form_data'        => $formData,
                'status'           => Order::DOC_STATUS_PENDING_APPROVAL,
                'submitted_at'     => now(),
                'last_revised_at'  => now(),
                'revision_count'   => ($lockedOrder->revision_count ?? 0) + 1,
                'approved_by'      => null,
                'approved_at'      => null,
            ];

            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = time() . '_proof_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $updateData['payment_proof'] = $file->storeAs('payment_proofs/' . $lockedOrder->id, $filename, 'public');
            }

            $lockedOrder->update($updateData);
            $lockedOrder->refresh();

            $newSnapshot = $this->makeOrderSnapshot($lockedOrder);
            $note = $request->input('revision_note') ?: ('Revisi ke-' . $lockedOrder->revision_count . ' disubmit ulang oleh ' . auth()->user()->roleLabel());

            // Prior rejection records in order_histories remain immutable; we append resubmitted
            $this->recordHistory($lockedOrder, 'resubmitted', $oldSnapshot['status'], Order::DOC_STATUS_PENDING_APPROVAL, $note, $newSnapshot);
            $this->recordAudit($lockedOrder, 'resubmit', $oldSnapshot, $newSnapshot, $note);
        });

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Revisi pesanan berhasil disubmit ulang dan berstatus Pending Approval.');
    }

    public function storeChangeRequest(Request $request, Order $order)
    {
        abort_unless(auth()->user()->isAdmin1() || auth()->user()->isSPV(), 403, 'Akses pengajuan perubahan ditolak.');

        $request->validate([
            'request_type'     => 'required|in:edit,delete',
            'reason'           => 'required|string|min:3|max:2000',
            'invoice_number'   => ['nullable', 'string', 'max:100', Rule::unique('orders', 'invoice_number')->ignore($order->id)],
            'transaction_date' => 'nullable|date',
            'total_price'      => 'nullable',
            'payment_method'   => 'nullable|string|max:100',
            'payment_status'   => 'nullable|in:unpaid,pending_verification,verified,rejected',
            'notes'            => 'nullable|string|max:2000',
        ]);

        $proposed = null;
        if ($request->request_type === 'edit') {
            $proposed = array_filter([
                'invoice_number'   => $request->filled('invoice_number') ? trim($request->invoice_number) : null,
                'transaction_date' => $request->filled('transaction_date') ? Carbon::parse($request->transaction_date)->toDateString() : null,
                'total_price'      => $request->filled('total_price') ? (int) preg_replace('/[^\d]/', '', (string) $request->total_price) : null,
                'payment_method'   => $request->filled('payment_method') ? $request->payment_method : null,
                'payment_status'   => $request->filled('payment_status') ? $request->payment_status : null,
                'notes'            => $request->has('notes') ? $request->notes : null,
            ], static fn ($v) => $v !== null);
        }

        DB::transaction(function () use ($request, $order, $proposed) {
            $cr = OrderChangeRequest::create([
                'order_id'      => $order->id,
                'requested_by'  => auth()->id(),
                'request_type'  => $request->request_type,
                'status'        => 'pending',
                'reason'        => $request->reason,
                'proposed_data' => $proposed,
            ]);

            $this->recordHistory(
                $order,
                'change_requested',
                $order->status,
                $order->status,
                strtoupper($cr->request_type) . ' diajukan: ' . $cr->reason,
                ['change_request_id' => $cr->id, 'request_type' => $cr->request_type, 'proposed_data' => $proposed]
            );

            $this->recordAudit(
                $order,
                'change_request_create',
                $this->makeOrderSnapshot($order),
                ['change_request_id' => $cr->id, 'request_type' => $cr->request_type, 'proposed_data' => $proposed],
                $cr->reason
            );
        });

        return redirect()->back()->with('success', 'Pengajuan ' . ($request->request_type === 'delete' ? 'hapus' : 'edit') . ' berhasil dikirim ke Admin 3 / SPV untuk persetujuan.');
    }

    public function approveChangeRequest(Request $request, OrderChangeRequest $changeRequest)
    {
        abort_unless(auth()->user()->canApproveOrders(), 403, 'Hanya Admin 3 atau SPV yang berhak menyetujui pengajuan perubahan.');

        $request->validate([
            'review_note' => 'nullable|string|max:1000',
        ]);

        try {
            DB::transaction(function () use ($request, $changeRequest) {
                $lockedCr = OrderChangeRequest::whereKey($changeRequest->id)->lockForUpdate()->firstOrFail();
                if (! $lockedCr->isPending()) {
                    throw new \RuntimeException('Pengajuan perubahan ini sudah diproses sebelumnya.');
                }

                $lockedOrder = Order::withTrashed()->whereKey($lockedCr->order_id)->lockForUpdate()->firstOrFail();
                $oldSnapshot = $this->makeOrderSnapshot($lockedOrder);
                $reviewNote = $request->input('review_note') ?: 'Disetujui oleh ' . auth()->user()->roleLabel();

                $lockedCr->update([
                    'status'      => 'approved',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'review_note' => $reviewNote,
                ]);

                if ($lockedCr->request_type === 'delete') {
                    $this->benefitService->deactivateForOrder($lockedOrder);
                    $this->recordHistory($lockedOrder, 'change_approved', $oldSnapshot['status'], 'deleted', $reviewNote, $oldSnapshot);
                    $this->recordHistory($lockedOrder, 'deleted', $oldSnapshot['status'], 'deleted', $lockedCr->reason, $oldSnapshot);
                    $this->recordAudit($lockedOrder, 'change_request_approve', $oldSnapshot, null, $reviewNote);
                    $this->recordAudit($lockedOrder, 'delete', $oldSnapshot, null, $lockedCr->reason);

                    $lockedOrder->update([
                        'deleted_by'    => auth()->id(),
                        'delete_reason' => $lockedCr->reason . ' (Pengajuan #' . $lockedCr->id . ')',
                    ]);
                    $lockedOrder->delete();
                } else {
                    $proposed = $lockedCr->proposed_data ?? [];
                    $updates = [];
                    foreach (['invoice_number', 'transaction_date', 'total_price', 'payment_method', 'payment_status', 'notes'] as $field) {
                        if (array_key_exists($field, $proposed)) {
                            $updates[$field] = $proposed[$field];
                        }
                    }

                    if (! empty($updates)) {
                        $lockedOrder->update($updates);
                        $lockedOrder->refresh();

                        if ($lockedOrder->isValidated() && RoomBenefit::where('order_id', $lockedOrder->id)->exists()) {
                            $this->benefitService->syncDatesFromOrder($lockedOrder);
                        }
                    }

                    $newSnapshot = $this->makeOrderSnapshot($lockedOrder);
                    $this->recordHistory($lockedOrder, 'change_approved', $oldSnapshot['status'], $lockedOrder->status, $reviewNote, $newSnapshot);
                    $this->recordAudit($lockedOrder, 'change_request_approve', $oldSnapshot, $newSnapshot, $reviewNote);
                    $this->recordAudit($lockedOrder, 'update', $oldSnapshot, $newSnapshot, 'Edit disetujui dari pengajuan #' . $lockedCr->id . ': ' . $lockedCr->reason);
                }
            });
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Pengajuan perubahan berhasil disetujui dan dijalankan dengan Audit Trail lengkap.');
    }

    public function rejectChangeRequest(Request $request, OrderChangeRequest $changeRequest)
    {
        abort_unless(auth()->user()->canApproveOrders(), 403, 'Hanya Admin 3 atau SPV yang berhak menolak pengajuan perubahan.');

        $request->validate([
            'review_note' => 'required|string|min:3|max:1000',
        ], [
            'review_note.required' => 'Catatan penolakan pengajuan wajib diisi.',
        ]);

        try {
            DB::transaction(function () use ($request, $changeRequest) {
                $lockedCr = OrderChangeRequest::whereKey($changeRequest->id)->lockForUpdate()->firstOrFail();
                if (! $lockedCr->isPending()) {
                    throw new \RuntimeException('Pengajuan perubahan ini sudah diproses sebelumnya.');
                }

                $lockedOrder = Order::withTrashed()->whereKey($lockedCr->order_id)->lockForUpdate()->firstOrFail();

                $lockedCr->update([
                    'status'      => 'rejected',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'review_note' => $request->review_note,
                ]);

                $this->recordHistory($lockedOrder, 'change_rejected', $lockedOrder->status, $lockedOrder->status, $request->review_note, ['change_request_id' => $lockedCr->id]);
                $this->recordAudit($lockedOrder, 'change_request_reject', ['change_request_id' => $lockedCr->id, 'status' => 'pending'], ['change_request_id' => $lockedCr->id, 'status' => 'rejected'], $request->review_note);
            });
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Pengajuan perubahan berhasil ditolak.');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status'           => 'required|in:unpaid,pending_verification,verified,rejected',
            'payment_rejection_reason' => 'nullable|string|max:1000',
        ]);

        $oldSnapshot = $this->makeOrderSnapshot($order);

        $updateData = [
            'payment_status' => $request->payment_status,
        ];

        if ($request->payment_status === 'rejected') {
            $updateData['payment_rejection_reason'] = $request->payment_rejection_reason;
        } else {
            $updateData['payment_rejection_reason'] = null;
        }

        $order->update($updateData);
        $order->refresh();

        $this->recordAudit($order, 'payment_update', $oldSnapshot, $this->makeOrderSnapshot($order), $request->payment_rejection_reason);

        // Only activate benefit if order is ALREADY approved/validated (never during pending_approval/rejected)
        if ($order->payment_status === 'verified' && $order->isValidated() && RoomBenefit::isEligibleForOrder($order)) {
            try {
                $this->benefitService->activateOrSyncForOrder($order, auth()->id());
            } catch (\Exception $e) {
                // Benefit might already exist or other error
            }
        }

        return redirect()->back()->with('success', 'Status pembayaran pesanan berhasil diperbarui menjadi: ' . $order->payment_status_label);
    }

    public function sendPaymentReminder(Order $order, WhatsAppService $whatsAppService)
    {
        if ($order->payment_status !== 'unpaid') {
            return redirect()->back()->with('error', 'Reminder pembayaran hanya dapat dikirim jika status pembayaran Belum Bayar.');
        }

        try {
            $log = $whatsAppService->notifyInvoiceDueReminder($order);

            $clientName = $order->user->company_name ?? ($order->user->name ?? 'Client');

            if ($log && ($log->status === 'SUCCESS' || $log->status === 'FAILED')) {
                return redirect()->back()->with('success', '📲 Reminder pembayaran WhatsApp berhasil dikirim ke client (' . $clientName . ').');
            }

            return redirect()->back()->with('error', '❌ Gagal mengirim reminder WhatsApp. Pastikan konfigurasi BOTCAKE_TEMPLATE_INVOICE_DUE_REMINDER sudah diisi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ── Admin Create & Store (CRM) ────────────────────────────────────────────

    public function create(Request $request)
    {
        $services = UniversalOrderController::$services;
        $packages = UniversalOrderController::$packages;
        $activePackages = UniversalOrderController::getActivePackagesPerService();
        $paymentMethods = self::PAYMENT_METHODS;

        $renewOrder = null;
        $preselectedClient = null;

        if ($request->filled('renew_order_id')) {
            $renewOrder = Order::with(['user', 'roomBenefits'])->find($request->renew_order_id);
            if ($renewOrder) {
                $preselectedClient = $renewOrder->user;
            }
        } elseif ($request->filled('client_id')) {
            $preselectedClient = User::find($request->client_id);
        }

        return view('admin.orders.create', compact('services', 'packages', 'activePackages', 'paymentMethods', 'renewOrder', 'preselectedClient'));
    }

    public function getDocumentRequirements($serviceKey)
    {
        $serviceInfo = UniversalOrderController::$services[$serviceKey] ?? ['db_slug' => $serviceKey];
        $dbService   = Service::where('slug', $serviceInfo['db_slug'] ?? $serviceKey)->first();
        if (! $dbService) {
            return response()->json([]);
        }

        return response()->json($dbService->documentRequirements);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'service'          => 'required|string',
            'package'          => 'required|string|max:100',
            'transaction_date' => 'required|date',
            'invoice_number'   => ['nullable', 'string', 'max:100', Rule::unique('orders', 'invoice_number')],
            'payment_method'   => 'required|string|max:100',
            'payment_status'   => 'required|in:unpaid,pending_verification,verified,rejected',
            'payment_proof'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'status'           => 'nullable|string',
            'notes'            => 'nullable|string|max:2000',
            'documents.*.*'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $serviceKey = strtolower($request->service);
        $packageKey = strtolower($request->package);
        $resolved   = $this->resolveServiceAndPrice(
            $serviceKey,
            $packageKey,
            $request->input('modal_dasar'),
            $request->input('total_price')
        );

        $client = User::findOrFail($request->user_id);

        $formData = [
            'service'             => $serviceKey,
            'package'             => $packageKey,
            'director_name'       => $client->pic_name ?: $client->name,
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

        $txDate = Carbon::parse($request->transaction_date);
        $invoiceNumber = $request->filled('invoice_number')
            ? trim($request->invoice_number)
            : Order::generateInvoiceNumber($txDate);

        // Requirement B: Admin 1 CANNOT approve or activate benefits directly; always saved as pending_approval
        $user = auth()->user();
        $requestedStatus = $request->input('status', Order::DOC_STATUS_PENDING_APPROVAL);
        if (! $user->canApproveOrders()) {
            $finalStatus = Order::DOC_STATUS_PENDING_APPROVAL;
        } else {
            $finalStatus = in_array($requestedStatus, [Order::DOC_STATUS_PENDING_APPROVAL, Order::DOC_STATUS_APPROVED], true)
                ? $requestedStatus
                : Order::DOC_STATUS_PENDING_APPROVAL;
        }

        $order = DB::transaction(function () use (
            $request,
            $serviceKey,
            $resolved,
            $formData,
            $txDate,
            $invoiceNumber,
            $finalStatus
        ) {
            $isApprovedImmediately = ($finalStatus === Order::DOC_STATUS_APPROVED);

            $order = Order::create([
                'order_number'     => 'ORD-' . strtoupper(substr($serviceKey, 0, 3)) . '-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'invoice_number'   => $invoiceNumber,
                'transaction_date' => $txDate->toDateString(),
                'user_id'          => $request->user_id,
                'created_by'       => auth()->id(),
                'submitted_at'     => now(),
                'approved_by'      => $isApprovedImmediately ? auth()->id() : null,
                'approved_at'      => $isApprovedImmediately ? now() : null,
                'service_id'       => $resolved['service_id'],
                'service_name'     => $resolved['service_name'],
                'status'           => $finalStatus,
                'payment_method'   => $request->payment_method,
                'payment_status'   => $request->payment_status,
                'total_price'      => $resolved['total_price'],
                'notes'            => $request->notes,
                'form_data'        => $formData,
            ]);

            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = time() . '_proof_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $proofPath = $file->storeAs('payment_proofs/' . $order->id, $filename, 'public');
                $order->update(['payment_proof' => $proofPath]);
            }

            // Handle Document Uploads
            $uploadedDocuments = $request->file('documents');
            if (! empty($uploadedDocuments) && is_array($uploadedDocuments)) {
                foreach ($uploadedDocuments as $docType => $files) {
                    if (is_array($files)) {
                        foreach ($files as $file) {
                            if ($file->isValid()) {
                                $cleanName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                                $ext       = $file->getClientOriginalExtension();
                                $filename  = time() . "_{$cleanName}.{$ext}";
                                $path      = $file->storeAs('documents/' . $order->id, $filename, 'public');

                                Document::create([
                                    'order_id'      => $order->id,
                                    'user_id'       => $order->user_id,
                                    'service_id'    => $order->service_id,
                                    'document_type' => $docType,
                                    'type'          => strtolower($docType),
                                    'filename'      => $filename,
                                    'original_name' => $file->getClientOriginalName(),
                                    'path'          => $path,
                                    'status'        => 'approved',
                                    'approved_at'   => now(),
                                    'approved_by'   => auth()->id(),
                                ]);
                            }
                        }
                    }
                }
            }

            if ($isApprovedImmediately && RoomBenefit::isEligibleForOrder($order)) {
                try {
                    $this->benefitService->activateOrSyncForOrder($order, auth()->id());
                } catch (\Exception $e) {
                    // Ignore ineligible errors
                }
            }

            $snapshot = $this->makeOrderSnapshot($order);
            $this->recordHistory(
                $order,
                'submitted',
                null,
                $finalStatus,
                'Pesanan disubmit oleh ' . auth()->user()->roleLabel() . ' (No. Invoice: ' . $order->invoice_number . ')',
                $snapshot
            );
            $this->recordAudit($order, 'create', null, $snapshot, 'Pembuatan transaksi baru');

            return $order;
        });

        // WhatsApp notification (safely skipped for pending approval / virtual-office or errors caught)
        if ($serviceKey !== 'virtual-office' && $order->isValidated()) {
            try {
                $this->whatsAppService->notifyOrderCreated($order);
            } catch (\Exception $e) {
                // Keep store response clean
            }
        }

        $successText = $order->isPendingApproval()
            ? 'Pesanan berhasil disubmit dengan status Pending Approval (No. Invoice: ' . $order->invoice_number . '). Menunggu persetujuan Admin 3 / SPV.'
            : 'Data pelanggan dan transaksi berhasil ditambahkan dan disetujui.';

        if ($serviceKey === 'virtual-office' && $order->isValidated()) {
            return redirect()->route('admin.virtual-office.index')->with('success', $successText);
        }

        return redirect()->route('admin.orders.index')->with('success', $successText);
    }

    public function generateQr(Order $order)
    {
        $order->generateQrToken();

        return redirect()->back()->with('success', 'QR Code layanan berhasil di-generate untuk order #' . $order->order_number);
    }

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

    // ── Shared Helpers ────────────────────────────────────────────────────────

    private function resolveServiceAndPrice(string $serviceKey, string $packageKey, ?string $modalDasar, mixed $priceOverride): array
    {
        $serviceInfo  = UniversalOrderController::$services[$serviceKey] ?? ['db_slug' => $serviceKey];
        $dbService    = $serviceKey !== '' ? Service::where('slug', $serviceInfo['db_slug'] ?? $serviceKey)->first() : null;
        $packageLabel = UniversalOrderController::$packages[$packageKey] ?? Str::title(str_replace('-', ' ', $packageKey));
        $serviceName  = ($serviceInfo['label'] ?? Str::title(str_replace('-', ' ', $serviceKey))) . ' – ' . $packageLabel;

        $totalBiaya = 0;
        if ($serviceKey === 'pendirian-pt' && $modalDasar) {
            $totalBiaya = UniversalOrderController::$ptPricing[$modalDasar][$packageKey] ?? 0;
        } elseif (isset(UniversalOrderController::$otherPricing[$serviceKey][$packageKey])) {
            $totalBiaya = UniversalOrderController::$otherPricing[$serviceKey][$packageKey];
        }

        if ($priceOverride !== null && $priceOverride !== '') {
            $totalBiaya = (int) preg_replace('/[^\d]/', '', (string) $priceOverride);
        }

        return [
            'service_id'   => $dbService?->id,
            'service_name' => $serviceName,
            'total_price'  => $totalBiaya,
        ];
    }

    private function makeOrderSnapshot(Order $order): array
    {
        return [
            'order_number'     => $order->order_number,
            'invoice_number'   => $order->invoice_number,
            'transaction_date' => $order->transaction_date?->toDateString(),
            'service_name'     => $order->service_name,
            'total_price'      => (int) $order->total_price,
            'payment_method'   => $order->payment_method,
            'payment_status'   => $order->payment_status,
            'status'           => $order->status,
            'notes'            => $order->notes,
            'admin_notes'      => $order->admin_notes,
            'rejection_reason' => $order->rejection_reason,
            'revision_count'   => (int) ($order->revision_count ?? 0),
            'approved_by'      => $order->approved_by,
            'approved_at'      => $order->approved_at?->toIso8601String(),
        ];
    }

    private function recordHistory(
        Order $order,
        string $action,
        ?string $fromStatus,
        ?string $toStatus,
        ?string $notes = null,
        ?array $snapshot = null
    ): void {
        OrderHistory::create([
            'order_id'    => $order->id,
            'action'      => $action,
            'from_status' => $fromStatus,
            'to_status'   => $toStatus,
            'actor_id'    => auth()->id(),
            'actor_role'  => auth()->user()?->role,
            'notes'       => $notes,
            'snapshot'    => $snapshot,
        ]);
    }

    private function recordAudit(
        Order $order,
        string $action,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $reason = null
    ): void {
        OrderAuditLog::create([
            'order_id'   => $order->id,
            'user_id'    => auth()->id(),
            'user_role'  => auth()->user()?->role,
            'action'     => $action,
            'reason'     => $reason,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
        ]);
    }
}
