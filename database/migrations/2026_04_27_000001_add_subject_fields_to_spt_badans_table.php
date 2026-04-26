<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spt_badans', function (Blueprint $table) {
            // Unified subject type: pribadi | badan
            $table->string('subject_type')->default('badan')->after('user_id');

            // For pribadi
            $table->string('nama_lengkap')->nullable()->after('subject_type');
            $table->string('nik')->nullable()->after('nama_lengkap');

            // For badan — 'perusahaan' column already exists
            $table->string('npwp_perusahaan')->nullable()->after('perusahaan');

            // Make old required fields optional (for pribadi, jenis_usaha not needed)
            $table->string('jenis_usaha')->nullable()->change();
            $table->string('perusahaan')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('spt_badans', function (Blueprint $table) {
            $table->dropColumn(['subject_type', 'nama_lengkap', 'nik', 'npwp_perusahaan']);
            $table->string('jenis_usaha')->nullable(false)->change();
            $table->string('perusahaan')->nullable(false)->change();
        });
    }
};
