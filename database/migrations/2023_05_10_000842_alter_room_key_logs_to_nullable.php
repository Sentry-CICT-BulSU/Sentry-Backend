<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('room_key_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('room_key_id')->nullable()->change();
            $table->unsignedBigInteger('faculty_id')->nullable()->change();
            $table->unsignedBigInteger('subject_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_key_logs', function (Blueprint $table) {
            //
        });
    }
};
