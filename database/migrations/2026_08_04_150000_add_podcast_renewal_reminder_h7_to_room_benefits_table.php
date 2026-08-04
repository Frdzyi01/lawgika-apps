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
        Schema::table('room_benefits', function (Blueprint $table) {
            if (!Schema::hasColumn('room_benefits', 'renewal_reminder_h7_sent_at')) {
                $table->dateTime('renewal_reminder_h7_sent_at')->nullable()->after('renewal_reminder_h30_sent_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_benefits', function (Blueprint $table) {
            // No-op or drop if specific
        });
    }
};
