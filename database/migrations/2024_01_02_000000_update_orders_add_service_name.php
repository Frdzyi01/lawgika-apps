<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // service_id bisa null (untuk order via frontend tanpa service DB)
            $table->foreignId('service_id')->nullable()->change();
            // simpan nama service sebagai string
            $table->string('service_name')->nullable()->after('service_id');
            // update enum status agar include approved & rejected
            $table->enum('status', ['pending', 'processing', 'approved', 'rejected', 'completed', 'cancelled'])
                  ->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('service_name');
            $table->foreignId('service_id')->nullable(false)->change();
        });
    }
};
