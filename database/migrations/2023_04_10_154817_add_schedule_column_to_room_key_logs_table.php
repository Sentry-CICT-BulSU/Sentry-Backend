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
            $table->foreignIdFor(\App\Models\Subjects::class, 'subject_id')->nullable()->constrained('subjects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_key_logs', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
        });
    }
};
