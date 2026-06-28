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
        Schema::table('remedial_session_logs', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('session_date');
        });

        Schema::table('remedial_assessments', function (Blueprint $table) {
            $table->string('linked_test_id')->nullable()->after('type');
            $table->json('co_structure')->nullable()->after('title');
        });

        Schema::table('remedial_assessment_scores', function (Blueprint $table) {
            $table->json('co_scores')->nullable()->after('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remedial_session_logs', function (Blueprint $table) {
            $table->dropColumn('start_time');
        });

        Schema::table('remedial_assessments', function (Blueprint $table) {
            $table->dropColumn(['linked_test_id', 'co_structure']);
        });

        Schema::table('remedial_assessment_scores', function (Blueprint $table) {
            $table->dropColumn('co_scores');
        });
    }
};
