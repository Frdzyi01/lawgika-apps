<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambahkan kolom-kolom yang diperlukan sistem dokumen baru ke tabel documents.
 * - document_type  : standarized key (KTP_DIREKTUR, NPWP_DIREKTUR, dll.)
 * - service_id     : relasi ke services (agar bisa filter per-layanan)
 * - rejection_reason, approved_by, approved_at : untuk approval flow
 *
 * Kolom yang SUDAH ADA tetap dipertahankan (type, status, filename, path, dsb.)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Tambah document_type (string standar) jika belum ada
            if (!Schema::hasColumn('documents', 'document_type')) {
                $table->string('document_type')->nullable()->after('order_id');
            }
            // Tambah service_id (nullable) untuk memudahkan query per-layanan
            if (!Schema::hasColumn('documents', 'service_id')) {
                $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete()->after('document_type');
            }
            // Kolom approval
            if (!Schema::hasColumn('documents', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('documents', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('rejection_reason');
            }
            if (!Schema::hasColumn('documents', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['document_type', 'service_id', 'rejection_reason', 'approved_by', 'approved_at']);
        });
    }
};
