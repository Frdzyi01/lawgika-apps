<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the room_benefits table for PT package (Eksklusif & Enterprise) customers.
     * This is a NEW table — no existing tables are modified.
     */
    public function up(): void
    {
        Schema::create('room_benefits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id')->unique(); // 1 order = 1 benefit, enforced at DB level
            $table->string('paket');                          // e.g. "Eksklusif", "Enterprise"
            $table->unsignedInteger('total_minutes')->default(3600);  // 60 hours
            $table->unsignedInteger('used_minutes')->default(0);
            $table->string('type')->default('shared');        // always 'shared' (pool meeting+podcast)
            $table->boolean('is_active')->default(true);
            $table->timestamp('expired_at')->nullable();      // 1 year from approval
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_benefits');
    }
};
