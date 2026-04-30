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
            $table->string('source_type')->default('manual')->after('id');
        });

        Schema::table('podcast_room_bookings', function (Blueprint $table) {
            $table->string('source_type')->default('manual')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meeting_room_bookings', function (Blueprint $table) {
            $table->dropColumn('source_type');
        });

        Schema::table('podcast_room_bookings', function (Blueprint $table) {
            $table->dropColumn('source_type');
        });
    }
};
