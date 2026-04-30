<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add checkin_at to room_benefit_logs so we can store a full session
 * (checkin + checkout) in ONE row — no paired-log parsing needed.
 *
 * A row is created on CHECK-OUT only (not on check-in).
 * - checkin_at  = timestamp when the user checked in
 * - checkout_at = timestamp when the user checked out
 * - duration_minutes = calculated from the diff
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_benefit_logs', function (Blueprint $table) {
            $table->timestamp('checkin_at')->nullable()->after('action_at');
            $table->timestamp('checkout_at')->nullable()->after('checkin_at');
        });
    }

    public function down(): void
    {
        Schema::table('room_benefit_logs', function (Blueprint $table) {
            $table->dropColumn(['checkin_at', 'checkout_at']);
        });
    }
};
