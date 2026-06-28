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
        Schema::table('student_board_grades', function (Blueprint $table) {
            $table->string('exam_month_year', 50)->nullable();
            $table->integer('chances_taken')->default(1);
            $table->integer('internal_marks')->nullable();
            $table->integer('external_marks')->nullable();
            $table->integer('total_marks')->nullable();
            $table->boolean('passed')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_board_grades', function (Blueprint $table) {
            $table->dropColumn([
                'exam_month_year',
                'chances_taken',
                'internal_marks',
                'external_marks',
                'total_marks',
                'passed'
            ]);
        });
    }
};
