<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentRequirement;
use App\Models\Order;

/**
 * DocumentService
 *
 * Core service untuk logika dokumen:
 * - checkCompletion  : periksa apakah semua requirement sudah terpenuhi
 * - syncOrderStatus  : sinkronisasi status order berdasarkan kondisi dokumen
 * - canUpload        : validasi apakah client boleh upload tipe dokumen tertentu
 */
class DocumentService
{
    // ─────────────────────────────────────────────────────────────────────────
    // CHECK COMPLETION
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Periksa status kelengkapan dokumen untuk sebuah order.
     *
     * @param int $orderId
     * @return array{
     *   status: 'COMPLETE'|'INCOMPLETE',
     *   details: array<string, array{requirement: DocumentRequirement, approved_count: int, total_count: int, fulfilled: bool}>
     * }
     */
    public function checkCompletion(int $orderId): array
    {
        $order = Order::with(['documents', 'service.documentRequirements'])->findOrFail($orderId);

        // Jika service tidak ada atau tidak punya requirements, kembalikan INCOMPLETE
        if (!$order->service_id || !$order->service) {
            return ['status' => 'INCOMPLETE', 'details' => []];
        }

        $requirements = $order->service->documentRequirements;

        if ($requirements->isEmpty()) {
            return ['status' => 'INCOMPLETE', 'details' => []];
        }

        $details    = [];
        $allFulfilled = true;

        foreach ($requirements as $req) {
            $approvedCount = $order->documents
                ->where('document_type', $req->document_type)
                ->whereIn('status', ['approved', 'verified'])
                ->count();

            $totalCount = $order->documents
                ->where('document_type', $req->document_type)
                ->count();

            $fulfilled = $approvedCount >= $req->min_required;

            if (!$fulfilled) {
                $allFulfilled = false;
            }

            $details[$req->document_type] = [
                'requirement'   => $req,
                'approved_count'=> $approvedCount,
                'total_count'   => $totalCount,
                'fulfilled'     => $fulfilled,
            ];
        }

        return [
            'status'  => $allFulfilled ? 'COMPLETE' : 'INCOMPLETE',
            'details' => $details,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SYNC ORDER STATUS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Hitung ulang dan update status order berdasarkan kondisi dokumen.
     *
     * Status logic:
     *   1. Ada dokumen rejected  → revision
     *   2. Semua requirement ada (count >= min, meski pending) → waiting_verification
     *   3. Semua requirement approved → verified
     *   4. Belum lengkap → draft
     *
     * @param Order $order
     * @return string status baru
     */
    public function syncOrderStatus(Order $order): string
    {
        // Reload relasi agar data fresh
        $order->load(['documents', 'service.documentRequirements']);

        if (!$order->service_id || !$order->service) {
            return $order->status; // Tidak ada perubahan
        }

        $requirements = $order->service->documentRequirements;

        if ($requirements->isEmpty()) {
            return $order->status;
        }

        $documents = $order->documents;

        // --- Cek apakah ada dokumen yang di-reject ---
        $hasRejected = $documents->where('status', 'rejected')->isNotEmpty();

        // --- Hitung per document_type ---
        $allPresent  = true;
        $allApproved = true;

        foreach ($requirements as $req) {
            $allDocsOfType = $documents->where('document_type', $req->document_type);
            $totalCount    = $allDocsOfType->count();
            $approvedCount = $allDocsOfType->whereIn('status', ['approved', 'verified'])->count();

            // Cukup satu file per document_type yang ada (terlepas statusnya)
            if ($totalCount < $req->min_required) {
                $allPresent = false;
            }

            if ($approvedCount < $req->min_required) {
                $allApproved = false;
            }
        }

        // --- Tentukan status baru ---
        $newStatus = match (true) {
            $allApproved                    => 'verified',
            $hasRejected                    => 'revision',
            $allPresent && !$hasRejected    => 'waiting_verification',
            default                         => 'draft',
        };

        // Hanya update jika berubah (hindari unnecessary DB write)
        if ($order->status !== $newStatus) {
            $order->update(['status' => $newStatus]);
        }

        return $newStatus;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // UPLOAD VALIDATION
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Cek apakah client masih boleh upload dokumen tipe tertentu.
     *
     * @param int    $orderId
     * @param string $documentType   Standar key, e.g. KTP_DIREKTUR
     * @return array{allowed: bool, reason: string|null, current_count: int, max_allowed: int}
     */
    public function canUpload(int $orderId, string $documentType): array
    {
        $order = Order::with('service.documentRequirements')->findOrFail($orderId);

        if (!$order->service_id || !$order->service) {
            return ['allowed' => false, 'reason' => 'Layanan tidak ditemukan.', 'current_count' => 0, 'max_allowed' => 0];
        }

        $requirement = $order->service->documentRequirements
            ->where('document_type', $documentType)
            ->first();

        if (!$requirement) {
            return ['allowed' => false, 'reason' => 'Tipe dokumen tidak valid untuk layanan ini.', 'current_count' => 0, 'max_allowed' => 0];
        }

        $currentCount = Document::where('order_id', $orderId)
            ->where('document_type', $documentType)
            ->count();

        if ($currentCount >= $requirement->max_allowed) {
            return [
                'allowed'       => false,
                'reason'        => "Batas maksimal upload untuk dokumen ini adalah {$requirement->max_allowed} file.",
                'current_count' => $currentCount,
                'max_allowed'   => $requirement->max_allowed,
            ];
        }

        return [
            'allowed'       => true,
            'reason'        => null,
            'current_count' => $currentCount,
            'max_allowed'   => $requirement->max_allowed,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SUMMARY PER ORDER (untuk UI)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Ambil ringkasan dokumen per document_type untuk ditampilkan di UI client/admin.
     *
     * @return array<string, array{
     *   requirement: DocumentRequirement,
     *   documents: \Illuminate\Support\Collection,
     *   approved_count: int,
     *   total_count: int,
     *   is_fulfilled: bool,
     *   can_upload_more: bool,
     * }>
     */
    public function getDocumentSummary(Order $order): array
    {
        $order->loadMissing(['documents', 'service.documentRequirements']);

        if (!$order->service_id || !$order->service) {
            return [];
        }

        $summary = [];

        foreach ($order->service->documentRequirements as $req) {
            $docsOfType    = $order->documents->where('document_type', $req->document_type)->values();
            $approvedCount = $docsOfType->whereIn('status', ['approved', 'verified'])->count();
            $totalCount    = $docsOfType->count();

            $summary[$req->document_type] = [
                'requirement'   => $req,
                'documents'     => $docsOfType,
                'approved_count'=> $approvedCount,
                'total_count'   => $totalCount,
                'is_fulfilled'  => $approvedCount >= $req->min_required,
                'can_upload_more'=> $totalCount < $req->max_allowed,
            ];
        }

        return $summary;
    }
}
