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
            $table->unsignedBigInteger('order_id')->nullable()->change();
            $table->unsignedBigInteger('meeting_room_booking_id')->nullable()->after('order_id');
            $table->unsignedBigInteger('podcast_room_booking_id')->nullable()->after('meeting_room_booking_id');

            $table->foreign('meeting_room_booking_id')->references('id')->on('meeting_room_bookings')->onDelete('cascade');
            $table->foreign('podcast_room_booking_id')->references('id')->on('podcast_room_bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_benefits', function (Blueprint $table) {
            $table->dropForeign(['meeting_room_booking_id']);
            $table->dropForeign(['podcast_room_booking_id']);
            $table->dropColumn(['meeting_room_booking_id', 'podcast_room_booking_id']);
            $table->unsignedBigInteger('order_id')->nullable(false)->change();
        });
    }
};
