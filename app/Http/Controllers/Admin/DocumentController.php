<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Order;
use App\Models\OrderAuditLog;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function __construct(protected DocumentService $documentService) {}

    /**
     * Upload dokumen baru untuk sebuah order.
     * POST admin/orders/{order}/documents
     */
    public function store(Request $request, Order $order)
    {
        if ($order->trashed()) {
            return back()->with('error', 'Pesanan sudah dihapus, dokumen tidak dapat ditambahkan.');
        }

        $validated = $request->validate([
            'document_type' => 'required|string|max:100',
            'file'          => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'reason'        => 'nullable|string|max:500',
        ], [
            'document_type.required' => 'Tipe dokumen wajib dipilih.',
            'file.required'          => 'File dokumen wajib dipilih.',
            'file.mimes'             => 'Format file harus PDF, JPG, PNG, DOC, atau DOCX.',
            'file.max'               => 'Ukuran file maksimal 10 MB.',
        ]);

        $docType = trim($validated['document_type']);
        $stored = $this->storeUploadedFile($request->file('file'), $order->id);

        $document = Document::create([
            'order_id'      => $order->id,
            'user_id'       => $order->user_id,
            'service_id'    => $order->service_id,
            'document_type' => $docType,
            'type'          => strtolower($docType),
            'filename'      => $stored['filename'],
            'original_name' => $stored['original_name'],
            'path'          => $stored['path'],
            'status'        => Document::STATUS_PENDING,
        ]);

        $this->recordDocumentAudit($order, 'document_upload', null, [
            'document_id'   => $document->id,
            'document_type' => $document->document_type,
            'original_name' => $document->original_name,
            'path'          => $document->path,
            'status'        => $document->status,
        ], $validated['reason'] ?? 'Penambahan dokumen persyaratan oleh ' . (auth()->user()?->name ?? 'Admin'));

        return back()->with('success', 'Dokumen "' . $document->original_name . '" berhasil diunggah.');
    }

    /**
     * Edit / ganti file atau tipe dokumen yang sudah ada.
     * PUT|PATCH admin/documents/{document}
     */
    public function update(Request $request, Document $document)
    {
        $order = $document->order()->withTrashed()->first();
        if (! $order || $order->trashed()) {
            return back()->with('error', 'Pesanan terkait tidak ditemukan atau sudah dihapus.');
        }

        $validated = $request->validate([
            'document_type' => 'nullable|string|max:100',
            'file'          => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'reason'        => 'nullable|string|max:500',
        ], [
            'file.mimes' => 'Format file harus PDF, JPG, PNG, DOC, atau DOCX.',
            'file.max'   => 'Ukuran file maksimal 10 MB.',
        ]);

        $hasFile = $request->hasFile('file');
        $newType = isset($validated['document_type']) && trim($validated['document_type']) !== ''
            ? trim($validated['document_type'])
            : $document->document_type;
        $typeChanged = $newType !== $document->document_type;

        if (! $hasFile && ! $typeChanged) {
            return back()->withErrors([
                'file' => 'Pilih file pengganti atau ubah kategori dokumen terlebih dahulu.',
            ]);
        }

        $oldValues = [
            'document_id'   => $document->id,
            'document_type' => $document->document_type,
            'original_name' => $document->original_name,
            'filename'      => $document->filename,
            'path'          => $document->path,
            'status'        => $document->status,
        ];

        $updateData = [
            'document_type' => $newType,
            'type'          => strtolower((string) $newType),
        ];

        if ($hasFile) {
            if ($document->path && Storage::disk('public')->exists($document->path)) {
                Storage::disk('public')->delete($document->path);
            }

            $stored = $this->storeUploadedFile($request->file('file'), $order->id);
            $updateData = array_merge($updateData, [
                'filename'         => $stored['filename'],
                'original_name'    => $stored['original_name'],
                'path'             => $stored['path'],
                'status'           => Document::STATUS_PENDING,
                'rejection_reason' => null,
                'approved_by'      => null,
                'approved_at'      => null,
            ]);
        }

        $document->update($updateData);

        $newValues = [
            'document_id'   => $document->id,
            'document_type' => $document->document_type,
            'original_name' => $document->original_name,
            'filename'      => $document->filename,
            'path'          => $document->path,
            'status'        => $document->status,
        ];

        $this->recordDocumentAudit(
            $order,
            'document_update',
            $oldValues,
            $newValues,
            $validated['reason'] ?? 'Perubahan dokumen persyaratan (' . $document->document_type . ')'
        );

        return back()->with('success', 'Dokumen "' . $document->original_name . '" berhasil diperbarui.');
    }

    /**
     * Approve sebuah dokumen.
     * POST admin/documents/{document}/approve
     */
    public function approve(Request $request, Document $document)
    {
        $document->update([
            'status'          => 'approved',
            'rejection_reason'=> null,
            'approved_by'     => auth()->id(),
            'approved_at'     => now(),
        ]);

        // Sinkronisasi status order
        if ($document->order_id) {
            $this->documentService->syncOrderStatus(Order::find($document->order_id));
        }

        return back()->with('success', 'Dokumen "' . $document->original_name . '" berhasil disetujui.');
    }

    /**
     * Reject sebuah dokumen — alasan WAJIB diisi.
     * POST admin/documents/{document}/reject
     */
    public function reject(Request $request, Document $document)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:5|max:1000',
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.min'      => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $document->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by'      => null,
            'approved_at'      => null,
        ]);

        // Sinkronisasi status order
        if ($document->order_id) {
            $this->documentService->syncOrderStatus(Order::find($document->order_id));
        }

        return back()->with('success', 'Dokumen "' . $document->original_name . '" berhasil ditolak.');
    }

    /**
     * Reset dokumen ke pending.
     * POST admin/documents/{document}/reset
     */
    public function reset(Request $request, Document $document)
    {
        $document->update([
            'status'           => 'pending',
            'rejection_reason' => null,
            'approved_by'      => null,
            'approved_at'      => null,
        ]);

        if ($document->order_id) {
            $this->documentService->syncOrderStatus(Order::find($document->order_id));
        }

        return back()->with('success', 'Dokumen "' . $document->original_name . '" berhasil direset ke pending.');
    }

    /**
     * Backward-compat: updateStatus (dipakai oleh route lama).
     * POST admin/documents/{document}/status
     */
    public function updateStatus(Request $request, Document $document)
    {
        $request->validate([
            'status'           => 'required|in:approved,verified,rejected,pending',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|min:5|max:1000',
        ], [
            'rejection_reason.required_if' => 'Alasan penolakan wajib diisi saat menolak dokumen.',
        ]);

        $newStatus = $request->status === 'verified' ? 'approved' : $request->status;

        $updateData = ['status' => $newStatus];

        if ($newStatus === 'approved') {
            $updateData['rejection_reason'] = null;
            $updateData['approved_by']      = auth()->id();
            $updateData['approved_at']      = now();
        } elseif ($newStatus === 'rejected') {
            $updateData['rejection_reason'] = $request->rejection_reason;
            $updateData['approved_by']      = null;
            $updateData['approved_at']      = null;
        } else {
            // pending
            $updateData['rejection_reason'] = null;
            $updateData['approved_by']      = null;
            $updateData['approved_at']      = null;
        }

        $document->update($updateData);

        if ($document->order_id) {
            $this->documentService->syncOrderStatus(Order::find($document->order_id));
        }

        $label = match ($newStatus) {
            'approved' => 'disetujui',
            'rejected' => 'ditolak',
            default    => 'direset ke pending',
        };

        return back()->with('success', 'Dokumen "' . $document->original_name . '" berhasil ' . $label . '.');
    }

    /**
     * Simpan file upload dokumen ke disk public dan kembalikan metadata file.
     */
    private function storeUploadedFile(UploadedFile $file, int $orderId): array
    {
        $cleanName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $ext       = $file->getClientOriginalExtension();
        $filename  = time() . '_' . ($cleanName ?: 'dokumen') . ($ext ? '.' . $ext : '');
        $path      = $file->storeAs('documents/' . $orderId, $filename, 'public');

        return [
            'filename'      => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path'          => $path,
        ];
    }

    /**
     * Catat perubahan dokumen ke audit trail pesanan.
     */
    private function recordDocumentAudit(
        Order $order,
        string $action,
        ?array $oldValues,
        ?array $newValues,
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
