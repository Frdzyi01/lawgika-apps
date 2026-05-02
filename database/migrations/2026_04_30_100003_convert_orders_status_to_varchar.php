<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Tambahkan status baru untuk order document workflow:
 *   draft             → order dibuat, dokumen belum lengkap
 *   waiting_verification → semua dokumen sudah ada (meski masih pending)
 *   revision          → ada dokumen yang di-reject, perlu revisi
 *   verified          → semua dokumen sudah approved
 *
 * Status lama (pending, processing, completed, cancelled, approved, rejected)
 * tetap dipertahankan agar tidak ada data lama yang rusak.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom status orders menjadi VARCHAR agar lebih fleksibel
        // (sama seperti strategi yang sudah ada di documents table)
        DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Reset unsupported statuses to 'pending' to prevent truncation errors
        DB::table('orders')
            ->whereNotIn('status', ['pending', 'processing', 'completed', 'cancelled'])
            ->update(['status' => 'pending']);

        // Revert ke ENUM awal
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending'");
    }
};
