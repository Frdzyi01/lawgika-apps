<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Expand the documents.type enum to include PT Perorangan Professional document types.
     */
    public function up(): void
    {
        // MySQL ALTER TABLE to expand ENUM values
        DB::statement("
            ALTER TABLE `documents`
            MODIFY COLUMN `type`
            ENUM(
                'ktp',
                'npwp',
                'company_nib',
                'other',
                'akta_pendirian',
                'npwp_perusahaan',
                'sk_kemenkumham',
                'ktp_direktur',
                'npwp_direktur'
            ) NOT NULL DEFAULT 'other'
        ");
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE `documents`
            MODIFY COLUMN `type`
            ENUM(
                'ktp',
                'npwp',
                'company_nib',
                'other'
            ) NOT NULL DEFAULT 'other'
        ");
    }
};
