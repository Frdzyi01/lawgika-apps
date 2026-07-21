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
            $table->date('date')->nullable()->change();
            $table->time('start_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('podcast_room_bookings', function (Blueprint $table) {
            $table->date('date')->nullable(false)->change();
            $table->time('start_time')->nullable(false)->change();
        });
    }
};
