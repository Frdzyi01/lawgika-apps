<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah field Master Client ke tabel users.
     * Semua nullable agar data existing tidak terpengaruh.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pic_name')->nullable()->after('company_name');
            $table->string('npwp')->nullable()->after('pic_name');
            $table->string('business_type')->nullable()->after('npwp');
            $table->text('notes')->nullable()->after('business_type');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pic_name', 'npwp', 'business_type', 'notes']);
        });
    }
};
