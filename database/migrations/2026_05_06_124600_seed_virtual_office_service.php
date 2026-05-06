<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ensure 'virtual-office' service exists
        $serviceId = DB::table('services')->updateOrInsert(
            ['slug' => 'virtual-office'],
            [
                'name' => 'Virtual Office',
                'description' => 'Miliki alamat bisnis prestisius tanpa perlu menyewa kantor fisik. Hemat biaya operasional hingga 90% dengan layanan Virtual Office dari Lawgika.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Get the ID (updateOrInsert doesn't return ID)
        $serviceId = DB::table('services')->where('slug', 'virtual-office')->value('id');

        // 2. Add document requirements for Virtual Office
        $requirements = [
            ['service_id' => $serviceId, 'document_type' => 'ktp', 'label' => 'KTP Pemilik', 'min_required' => 1],
            ['service_id' => $serviceId, 'document_type' => 'npwp', 'label' => 'NPWP Pribadi/Perusahaan', 'min_required' => 1],
            ['service_id' => $serviceId, 'document_type' => 'akta_pendirian', 'label' => 'Akta Pendirian (Opsional)', 'min_required' => 0],
            ['service_id' => $serviceId, 'document_type' => 'other', 'label' => 'Dokumen Pendukung Lainnya', 'min_required' => 0],
        ];

        foreach ($requirements as $req) {
            DB::table('document_requirements')->updateOrInsert(
                ['service_id' => $req['service_id'], 'document_type' => $req['document_type']],
                array_merge($req, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: remove the service and requirements
        // DB::table('services')->where('slug', 'virtual-office')->delete();
    }
};
