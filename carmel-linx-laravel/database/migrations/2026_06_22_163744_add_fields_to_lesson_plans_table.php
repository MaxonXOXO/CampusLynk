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
        Schema::table('lesson_plans', function (Blueprint $table) {
            $table->integer('day_no')->nullable()->after('id');
            $table->string('pedagogy')->nullable()->after('actual_hours');
            $table->string('remarks')->nullable()->after('pedagogy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_plans', function (Blueprint $table) {
            $table->dropColumn(['day_no', 'pedagogy', 'remarks']);
        });
    }
};
