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
        Schema::create('room_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Rooms::class, 'room_id')->constrained('rooms');
            $table->string('status')->default(\App\Models\RoomKeys::AVAILABLE);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_keys');
    }
};
