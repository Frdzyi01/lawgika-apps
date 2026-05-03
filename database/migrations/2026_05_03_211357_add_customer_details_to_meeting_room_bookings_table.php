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
            $table->string('nama_perusahaan')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat_usaha')->nullable();
            $table->string('bidang_usaha')->nullable();
            $table->text('keperluan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meeting_room_bookings', function (Blueprint $table) {
            $table->dropColumn(['nama_perusahaan', 'email', 'alamat_usaha', 'bidang_usaha', 'keperluan']);
        });
    }
};
