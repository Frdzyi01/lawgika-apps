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
            $table->string('payment_method')->nullable()->after('payment_status');
        });

        Schema::table('podcast_room_bookings', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meeting_room_bookings', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });

        Schema::table('podcast_room_bookings', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};
