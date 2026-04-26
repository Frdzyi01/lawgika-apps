<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah kolom type dan status menjadi VARCHAR karena enum menyebabkan error ketika ada value baru (seperti rejected atau ktp_direktur)
        DB::statement("ALTER TABLE documents MODIFY COLUMN type VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE documents MODIFY COLUMN status VARCHAR(255) NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert kembali ke enum (dengan opsi default aslinya)
        DB::statement("ALTER TABLE documents MODIFY COLUMN type ENUM('ktp', 'npwp', 'company_nib', 'other') NOT NULL");
        DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM('pending', 'verified') NOT NULL DEFAULT 'pending'");
    }
};
