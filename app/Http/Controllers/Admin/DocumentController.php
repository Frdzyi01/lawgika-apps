<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Order;
use App\Services\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct(protected DocumentService $documentService) {}

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
}
