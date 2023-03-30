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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class, 'adviser_id')->constrained('users');
            $table->foreignIdFor(\App\Models\Subjects::class, 'subject_id')->constrained('subjects');
            $table->foreignIdFor(\App\Models\Semesters::class, 'semester_id')->constrained('semesters');
            $table->foreignIdFor(\App\Models\Rooms::class, 'room_id')->constrained('rooms');
            $table->foreignIdFor(\App\Models\Sections::class, 'section_id')->constrained('sections');

            $table->string('date_start');
            $table->string('date_end');
            $table->string('time_start');
            $table->string('time_end');
            $table->longText('active_days');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
