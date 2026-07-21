<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah field untuk reservasi admin-centric ke podcast_room_bookings.
     * Semua nullable agar data existing tidak terpengaruh.
     */
    public function up(): void
    {
        Schema::table('podcast_room_bookings', function (Blueprint $table) {
            $table->string('room_name')->nullable()->after('total_used_seconds');
            $table->time('end_time')->nullable()->after('start_time');
            $table->text('notes')->nullable()->after('room_name');
            $table->unsignedBigInteger('created_by')->nullable()->after('notes');

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('podcast_room_bookings', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['room_name', 'end_time', 'notes', 'created_by']);
        });
    }
};
