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
        Schema::create('room_key_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\RoomKeys::class, 'room_key_id')->constrained('room_keys');
            $table->foreignIdFor(\App\Models\User::class, 'faculty_id')->constrained('users');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_key_logs');
    }
};
