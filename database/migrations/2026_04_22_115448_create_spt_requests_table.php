<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spt_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('nama');
            $table->string('perusahaan');
            $table->string('jenis_usaha');
            $table->year('tahun_pajak');
            $table->string('laporan_keuangan');
            $table->string('status_lapor');
            
            $table->string('status')->default('pending'); // pending / diproses / selesai
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spt_requests');
    }
};
