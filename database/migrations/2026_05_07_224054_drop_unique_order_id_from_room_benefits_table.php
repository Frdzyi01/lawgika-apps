<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop the unique constraint on order_id so that one order can have
     * TWO separate benefit records: one for Meeting Room (48 jam)
     * and one for Podcast Room (12 jam).
     *
     * MySQL requires dropping the FK before dropping the unique index.
     */
    public function up(): void
    {
        Schema::table('room_benefits', function (Blueprint $table) {
            // 1. Drop the FK that references the unique index
            $table->dropForeign(['order_id']);
            // 2. Drop the unique index itself
            $table->dropUnique('room_benefits_order_id_unique');
            // 3. Re-add a regular (non-unique) index + FK
            $table->index('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Restore the unique constraint and FK (rollback).
     */
    public function down(): void
    {
        Schema::table('room_benefits', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropIndex(['order_id']);
            $table->unique('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }
};
