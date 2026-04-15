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
        Schema::table('event_up_comings', function (Blueprint $table) {
            // Waktu event detail (misal: 15.00 - 17.00 WIB)
            $table->string('waktu_event')->nullable()->after('tanggal_selesai');

            // Kapasitas peserta
            $table->integer('kapasitas')->nullable()->after('waktu_event');

            // Narasumber / moderator (bisa lebih dari 1, simpan sebagai JSON)
            $table->json('narasumber')->nullable()->after('kapasitas');

            // Harga event (dalam Rupiah, 0 = gratis)
            $table->integer('harga')->default(0)->after('narasumber');

            // Tipe event: gratis / berbayar
            $table->enum('tipe_event', ['gratis', 'berbayar'])->default('gratis')->after('harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_up_comings', function (Blueprint $table) {
            $table->dropColumn(['waktu_event', 'kapasitas', 'narasumber', 'harga', 'tipe_event']);
        });
    }
};
