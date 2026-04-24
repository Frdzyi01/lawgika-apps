<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('podcast_room_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('order_number')->unique()->nullable();
            $table->string('name');
            $table->string('podcast_title')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->integer('duration'); // in hours
            $table->integer('participants')->default(1);
            $table->string('package')->default('regular'); // e.g. 1jam, 2jam, 3jam
            $table->bigInteger('total_price')->default(0);
            $table->string('payment_proof')->nullable();
            $table->enum('payment_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status', ['pending', 'checkin', 'paused', 'selesai'])->default('pending');
            $table->timestamp('checkin_at')->nullable();
            $table->timestamp('checkout_at')->nullable();
            $table->integer('total_used_minutes')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('podcast_room_bookings');
    }
};
