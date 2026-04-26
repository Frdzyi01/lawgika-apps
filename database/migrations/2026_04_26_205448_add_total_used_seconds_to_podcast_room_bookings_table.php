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
        Schema::table('podcast_room_bookings', function (Blueprint $table) {
            $table->integer('total_used_seconds')->default(0)->after('total_used_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('podcast_room_bookings', function (Blueprint $table) {
            $table->dropColumn('total_used_seconds');
        });
    }
};
