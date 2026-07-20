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
        Schema::table('mid_semester_surveys', function (Blueprint $table) {
            $table->text('custom_questions')->nullable()->after('status');
        });

        Schema::table('course_exit_surveys', function (Blueprint $table) {
            $table->text('custom_questions')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mid_semester_surveys', function (Blueprint $table) {
            $table->dropColumn('custom_questions');
        });

        Schema::table('course_exit_surveys', function (Blueprint $table) {
            $table->dropColumn('custom_questions');
        });
    }
};
