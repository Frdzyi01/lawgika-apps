<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DocumentRequirement;

/**
 * Seed services + document requirements untuk semua jenis badan usaha.
 * Document type menggunakan key standar yang konsisten.
 */
class ServiceDocumentRequirementSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Upsert semua layanan ──────────────────────────────────────────
        $services = [
            ['name' => 'Pendirian PT Perorangan', 'slug' => 'pendirian-pt-perorangan', 'category' => 'pendirian-badan-usaha'],
            ['name' => 'Pendirian PT',             'slug' => 'pendirian-pt',             'category' => 'pendirian-badan-usaha'],
            ['name' => 'Pendirian PT PMA',         'slug' => 'pendirian-pt-pma',         'category' => 'pendirian-badan-usaha'],
            ['name' => 'Pendirian CV',             'slug' => 'pendirian-cv',             'category' => 'pendirian-badan-usaha'],
            ['name' => 'Pendirian Yayasan',        'slug' => 'pendirian-yayasan',        'category' => 'pendirian-badan-usaha'],
            ['name' => 'Pendirian Firma',          'slug' => 'pendirian-firma',          'category' => 'pendirian-badan-usaha'],
        ];

        foreach ($services as $s) {
            Service::updateOrCreate(
                ['slug' => $s['slug']],
                array_merge($s, ['is_active' => true, 'created_at' => now(), 'updated_at' => now()])
            );
        }

        // ── 2. Document Requirements ──────────────────────────────────────────
        $this->seedPtPerorangan();
        $this->seedPt();
        $this->seedPtPma();
        $this->seedCv();
        $this->seedYayasan();
        $this->seedFirma();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PT PERORANGAN
    // Catatan: PT Perorangan = 1 orang (direktur sekaligus pemegang saham).
    // TIDAK ada komisaris / banyak pemegang saham.
    // ─────────────────────────────────────────────────────────────────────────
    private function seedPtPerorangan(): void
    {
        $service = Service::where('slug', 'pendirian-pt-perorangan')->first();
        if (!$service) return;

        $this->upsert($service->id, [
            ['document_type' => 'KTP_DIREKTUR',  'label' => 'KTP Direktur',  'min_required' => 1, 'max_allowed' => 3],
            ['document_type' => 'NPWP_DIREKTUR', 'label' => 'NPWP Direktur', 'min_required' => 1, 'max_allowed' => 3],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PT REGULER
    // ─────────────────────────────────────────────────────────────────────────
    private function seedPt(): void
    {
        $service = Service::where('slug', 'pendirian-pt')->first();
        if (!$service) return;

        $this->upsert($service->id, [
            ['document_type' => 'KTP_PEMEGANG_SAHAM',  'label' => 'KTP Pemegang Saham',  'min_required' => 2, 'max_allowed' => 10],
            ['document_type' => 'NPWP_PEMEGANG_SAHAM', 'label' => 'NPWP Pemegang Saham', 'min_required' => 2, 'max_allowed' => 10],
            ['document_type' => 'KTP_DIREKTUR',         'label' => 'KTP Direktur',         'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_DIREKTUR',        'label' => 'NPWP Direktur',        'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'KTP_KOMISARIS',        'label' => 'KTP Komisaris',        'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_KOMISARIS',       'label' => 'NPWP Komisaris',       'min_required' => 1, 'max_allowed' => 5],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PT PMA (sama seperti PT reguler)
    // ─────────────────────────────────────────────────────────────────────────
    private function seedPtPma(): void
    {
        $service = Service::where('slug', 'pendirian-pt-pma')->first();
        if (!$service) return;

        $this->upsert($service->id, [
            ['document_type' => 'KTP_PEMEGANG_SAHAM',  'label' => 'KTP / Paspor Pemegang Saham', 'min_required' => 2, 'max_allowed' => 10],
            ['document_type' => 'NPWP_PEMEGANG_SAHAM', 'label' => 'NPWP Pemegang Saham',         'min_required' => 2, 'max_allowed' => 10],
            ['document_type' => 'KTP_DIREKTUR',         'label' => 'KTP / Paspor Direktur',       'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_DIREKTUR',        'label' => 'NPWP Direktur',               'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'KTP_KOMISARIS',        'label' => 'KTP / Paspor Komisaris',      'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_KOMISARIS',       'label' => 'NPWP Komisaris',              'min_required' => 1, 'max_allowed' => 5],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CV
    // ─────────────────────────────────────────────────────────────────────────
    private function seedCv(): void
    {
        $service = Service::where('slug', 'pendirian-cv')->first();
        if (!$service) return;

        $this->upsert($service->id, [
            ['document_type' => 'KTP_SEKUTU_AKTIF',  'label' => 'KTP Sekutu Aktif',  'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_SEKUTU_AKTIF', 'label' => 'NPWP Sekutu Aktif', 'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'KTP_SEKUTU_PASIF',  'label' => 'KTP Sekutu Pasif',  'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_SEKUTU_PASIF', 'label' => 'NPWP Sekutu Pasif', 'min_required' => 1, 'max_allowed' => 5],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // YAYASAN
    // ─────────────────────────────────────────────────────────────────────────
    private function seedYayasan(): void
    {
        $service = Service::where('slug', 'pendirian-yayasan')->first();
        if (!$service) return;

        $this->upsert($service->id, [
            ['document_type' => 'KTP_PEMBINA',   'label' => 'KTP Pembina',    'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_PEMBINA',  'label' => 'NPWP Pembina',   'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'KTP_PENGURUS',  'label' => 'KTP Pengurus',   'min_required' => 1, 'max_allowed' => 10],
            ['document_type' => 'NPWP_PENGURUS', 'label' => 'NPWP Pengurus',  'min_required' => 1, 'max_allowed' => 10],
            ['document_type' => 'KTP_PENGAWAS',  'label' => 'KTP Pengawas',   'min_required' => 1, 'max_allowed' => 5],
            ['document_type' => 'NPWP_PENGAWAS', 'label' => 'NPWP Pengawas',  'min_required' => 1, 'max_allowed' => 5],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // FIRMA
    // ─────────────────────────────────────────────────────────────────────────
    private function seedFirma(): void
    {
        $service = Service::where('slug', 'pendirian-firma')->first();
        if (!$service) return;

        $this->upsert($service->id, [
            ['document_type' => 'KTP_SEKUTU',  'label' => 'KTP Sekutu',  'min_required' => 2, 'max_allowed' => 10],
            ['document_type' => 'NPWP_SEKUTU', 'label' => 'NPWP Sekutu', 'min_required' => 2, 'max_allowed' => 10],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helper: upsert requirements
    // ─────────────────────────────────────────────────────────────────────────
    private function upsert(int $serviceId, array $requirements): void
    {
        foreach ($requirements as $req) {
            DocumentRequirement::updateOrCreate(
                ['service_id' => $serviceId, 'document_type' => $req['document_type']],
                array_merge($req, ['service_id' => $serviceId, 'updated_at' => now(), 'created_at' => now()])
            );
        }
    }
}
