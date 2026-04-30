<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DocumentRequirement;

/**
 * DocumentRequirementSeeder
 *
 * Mengisi document_requirements untuk semua layanan pendirian badan usaha.
 * Menggunakan firstOrCreate → aman dijalankan berkali-kali (idempotent).
 *
 * Services yang dicakup:
 *   1. Pendirian PT Perorangan  → 2 requirements
 *   2. Pendirian PT             → 6 requirements
 *   3. Pendirian PT PMA         → 6 requirements
 *   4. Pendirian CV             → 4 requirements
 *   5. Pendirian Yayasan        → 6 requirements
 *   6. Pendirian Firma          → 2 requirements
 */
class DocumentRequirementSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. PT PERORANGAN ─────────────────────────────────────────────────
        // Hanya direktur (sekaligus pemegang saham tunggal). Tidak ada komisaris.
        $this->seed('Pendirian PT Perorangan', [
            ['document_type' => 'KTP_DIREKTUR',  'label' => 'KTP Direktur',  'min_required' => 1, 'max_allowed' => 3],
            ['document_type' => 'NPWP_DIREKTUR', 'label' => 'NPWP Direktur', 'min_required' => 1, 'max_allowed' => 3],
        ]);

        // ── 2. PT REGULER ────────────────────────────────────────────────────
        $this->seed('Pendirian PT', [
            ['document_type' => 'KTP_PEMEGANG_SAHAM',  'label' => 'KTP Pemegang Saham',  'min_required' => 2, 'max_allowed' => 10],
            ['document_type' => 'NPWP_PEMEGANG_SAHAM', 'label' => 'NPWP Pemegang Saham', 'min_required' => 2, 'max_allowed' => 10],
            ['document_type' => 'KTP_DIREKTUR',         'label' => 'KTP Direktur',         'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_DIREKTUR',        'label' => 'NPWP Direktur',        'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'KTP_KOMISARIS',        'label' => 'KTP Komisaris',        'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_KOMISARIS',       'label' => 'NPWP Komisaris',       'min_required' => 1, 'max_allowed' => 5],
        ]);

        // ── 3. PT PMA ────────────────────────────────────────────────────────
        $this->seed('Pendirian PT PMA', [
            ['document_type' => 'KTP_PEMEGANG_SAHAM',  'label' => 'KTP Pemegang Saham',  'min_required' => 2, 'max_allowed' => 10],
            ['document_type' => 'NPWP_PEMEGANG_SAHAM', 'label' => 'NPWP Pemegang Saham', 'min_required' => 2, 'max_allowed' => 10],
            ['document_type' => 'KTP_DIREKTUR',         'label' => 'KTP Direktur',         'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_DIREKTUR',        'label' => 'NPWP Direktur',        'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'KTP_KOMISARIS',        'label' => 'KTP Komisaris',        'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_KOMISARIS',       'label' => 'NPWP Komisaris',       'min_required' => 1, 'max_allowed' => 5],
        ]);

        // ── 4. CV ─────────────────────────────────────────────────────────────
        $this->seed('Pendirian CV', [
            ['document_type' => 'KTP_SEKUTU_AKTIF',  'label' => 'KTP Sekutu Aktif',  'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_SEKUTU_AKTIF', 'label' => 'NPWP Sekutu Aktif', 'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'KTP_SEKUTU_PASIF',  'label' => 'KTP Sekutu Pasif',  'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_SEKUTU_PASIF', 'label' => 'NPWP Sekutu Pasif', 'min_required' => 1, 'max_allowed' => 5],
        ]);

        // ── 5. YAYASAN ───────────────────────────────────────────────────────
        $this->seed('Pendirian Yayasan', [
            ['document_type' => 'KTP_PEMBINA',   'label' => 'KTP Pembina',   'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_PEMBINA',  'label' => 'NPWP Pembina',  'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'KTP_PENGURUS',  'label' => 'KTP Pengurus',  'min_required' => 1, 'max_allowed' => 10],
            ['document_type' => 'NPWP_PENGURUS', 'label' => 'NPWP Pengurus', 'min_required' => 1, 'max_allowed' => 10],
            ['document_type' => 'KTP_PENGAWAS',  'label' => 'KTP Pengawas',  'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_PENGAWAS', 'label' => 'NPWP Pengawas', 'min_required' => 1, 'max_allowed' => 5],
        ]);

        // ── 6. FIRMA ─────────────────────────────────────────────────────────
        $this->seed('Pendirian Firma', [
            ['document_type' => 'KTP_SEKUTU',  'label' => 'KTP Sekutu',  'min_required' => 2, 'max_allowed' => 10],
            ['document_type' => 'NPWP_SEKUTU', 'label' => 'NPWP Sekutu', 'min_required' => 2, 'max_allowed' => 10],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helper: seed requirements untuk satu service berdasarkan name
    // ─────────────────────────────────────────────────────────────────────────

    private function seed(string $serviceName, array $requirements): void
    {
        $service = Service::where('name', $serviceName)->first();

        if (!$service) {
            $this->command->warn("⚠️  Service tidak ditemukan: {$serviceName} — di-skip.");
            return;
        }

        $inserted = 0;
        $skipped  = 0;

        foreach ($requirements as $req) {
            $existing = DocumentRequirement::firstOrCreate(
                [
                    'service_id'    => $service->id,
                    'document_type' => $req['document_type'],
                ],
                [
                    'label'        => $req['label'],
                    'min_required' => $req['min_required'],
                    'max_allowed'  => $req['max_allowed'],
                ]
            );

            if ($existing->wasRecentlyCreated) {
                $inserted++;
            } else {
                // Update label & min/max jika sudah ada (agar tetap sinkron)
                $existing->update([
                    'label'        => $req['label'],
                    'min_required' => $req['min_required'],
                    'max_allowed'  => $req['max_allowed'],
                ]);
                $skipped++;
            }
        }

        $total = $inserted + $skipped;
        $this->command->info("✅ {$serviceName}: {$inserted} inserted, {$skipped} updated ({$total} total requirements)");
    }
}
