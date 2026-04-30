<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('document_type');          // e.g. KTP_DIREKTUR
            $table->string('label');                  // e.g. KTP Direktur
            $table->unsignedTinyInteger('min_required')->default(1);
            $table->unsignedTinyInteger('max_allowed')->default(10);
            $table->timestamps();

            $table->unique(['service_id', 'document_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_requirements');
    }
};
