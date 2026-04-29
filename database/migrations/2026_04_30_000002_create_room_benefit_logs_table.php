<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Stores per-session check-in / check-out audit logs for room benefit usage.
     */
    public function up(): void
    {
        Schema::create('room_benefit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('benefit_id');
            $table->string('room_type');          // 'meeting' | 'podcast'
            $table->unsignedInteger('duration_minutes')->default(0); // minutes consumed this session
            $table->string('action');             // 'checkin' | 'checkout'
            $table->timestamp('action_at');       // when the action happened
            $table->timestamps();

            $table->foreign('benefit_id')->references('id')->on('room_benefits')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_benefit_logs');
    }
};
