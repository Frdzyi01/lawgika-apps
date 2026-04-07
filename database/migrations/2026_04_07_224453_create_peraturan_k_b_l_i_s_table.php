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
        Schema::create('peraturan_k_b_l_i_s', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kbli');
            $table->string('judul_peraturan');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_berlaku');
            $table->enum('status', ['aktif', 'direvisi', 'dicabut'])->default('aktif');
            $table->string('file_dokumen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peraturan_k_b_l_i_s');
    }
};
