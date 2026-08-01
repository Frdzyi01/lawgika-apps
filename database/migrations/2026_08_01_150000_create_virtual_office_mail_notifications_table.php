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
        Schema::create('virtual_office_mail_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('virtual_office_id');
            $table->unsignedBigInteger('client_id');
            $table->date('received_date');
            $table->string('received_time', 20);
            $table->string('sender_name');
            $table->string('document_type');
            $table->string('tracking_number')->nullable();
            $table->text('internal_note')->nullable();
            $table->string('whatsapp_status', 50)->default('PENDING');
            $table->string('botcake_message_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('virtual_office_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_office_mail_notifications');
    }
};
