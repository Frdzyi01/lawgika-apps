<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('correspondences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('note');
            $table->string('file_path');
            $table->string('sender_role'); // 'admin' | 'customer'
            $table->string('status')->default('pending'); // pending | replied | done
            $table->foreignId('parent_id')->nullable()->constrained('correspondences')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('correspondences');
    }
};
