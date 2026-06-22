<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Extend kolom `role` dari enum(['admin','customer']) menjadi varchar(20)
     * agar mendukung role baru: admin1 (Admin Order), admin2 (Admin Konten), spv.
     *
     * Mapping role:
     *  - 'admin'    → SPV / Super Admin (backward compatible, user lama tetap bisa login)
     *  - 'admin1'   → Admin Order
     *  - 'admin2'   → Admin Konten
     *  - 'customer' → Pelanggan (tidak berubah)
     */
    public function up(): void
    {
        // MySQL tidak bisa langsung mengubah ENUM values, kita ubah ke VARCHAR
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(20) NOT NULL DEFAULT 'customer'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pastikan tidak ada data dengan role baru sebelum rollback
        DB::statement("UPDATE users SET role = 'admin' WHERE role IN ('admin1', 'admin2', 'spv')");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer'");
    }
};
