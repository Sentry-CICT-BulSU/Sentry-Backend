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
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropIndex('subjects_section_id_foreign');
            $table->dropColumn(['section_id']);
            // $table->dropForeignIdFor(\App\Models\Sections::class, 'section_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Sections::class, 'section_id')->constrained('subjects');
        });
    }
};
