<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds benefit_id to both booking tables.
 *
 * This enables:
 * - source_type = 'benefit' → benefit-based reservation
 * - source_type = 'manual'  → existing manual flow (unchanged)
 *
 * NO existing columns or logic are modified.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meeting_room_bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('benefit_id')->nullable()->after('source_type');
            $table->foreign('benefit_id')->references('id')->on('room_benefits')->onDelete('set null');
        });

        Schema::table('podcast_room_bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('benefit_id')->nullable()->after('source_type');
            $table->foreign('benefit_id')->references('id')->on('room_benefits')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('meeting_room_bookings', function (Blueprint $table) {
            $table->dropForeign(['benefit_id']);
            $table->dropColumn('benefit_id');
        });

        Schema::table('podcast_room_bookings', function (Blueprint $table) {
            $table->dropForeign(['benefit_id']);
            $table->dropColumn('benefit_id');
        });
    }
};
