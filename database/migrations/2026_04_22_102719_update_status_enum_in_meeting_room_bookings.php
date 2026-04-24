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
        Schema::table('meeting_room_bookings', function (Blueprint $table) {
            $table->enum('status', ['pending', 'checkin', 'paused', 'selesai'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update any 'paused' status to 'pending' so it fits the previous enum definition
        \Illuminate\Support\Facades\DB::table('meeting_room_bookings')
            ->where('status', 'paused')
            ->update(['status' => 'pending']);

        Schema::table('meeting_room_bookings', function (Blueprint $table) {
            $table->enum('status', ['pending', 'checkin', 'checkout', 'selesai'])->default('pending')->change();
        });
    }
};
