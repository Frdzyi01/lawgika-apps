<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new statuses for the benefit flow
        DB::statement("ALTER TABLE meeting_room_bookings MODIFY status ENUM('pending', 'pending_approval', 'approved', 'rejected', 'checkin', 'paused', 'selesai') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE podcast_room_bookings MODIFY status ENUM('pending', 'pending_approval', 'approved', 'rejected', 'checkin', 'paused', 'selesai') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This down migration might fail if there's data using the new enums. It's safer not to revert enum column removal if data is present, but for completeness:
        DB::statement("ALTER TABLE meeting_room_bookings MODIFY status ENUM('pending', 'checkin', 'paused', 'selesai') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE podcast_room_bookings MODIFY status ENUM('pending', 'checkin', 'paused', 'selesai') NOT NULL DEFAULT 'pending'");
    }
};
