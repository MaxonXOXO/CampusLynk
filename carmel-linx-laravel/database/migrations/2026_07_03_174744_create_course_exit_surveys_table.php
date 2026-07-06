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
        // 1. Add action taken provisions for mid-semester survey (tutor & HOD manually entered notes)
        Schema::table('mid_semester_surveys', function (Blueprint $table) {
            $table->text('action_taken_by_tutor')->nullable()->after('action_taken');
            $table->text('action_taken_by_hod')->nullable()->after('action_taken_by_tutor');
        });

        // 2. Create course_exit_surveys configuration table
        Schema::create('course_exit_surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('status', 20)->default('Active'); // Active, Completed
            $table->string('faculty_name', 150)->nullable();
            $table->timestamp('initiated_at')->useCurrent();
            $table->timestamps();

            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
        });

        // 3. Create student responses to Course Exit Survey
        Schema::create('student_course_exit_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exit_survey_id');
            $table->string('reg_no', 15);
            // 10 CO Attainment specific questions (Low=1, Medium=2, High=3)
            $table->tinyInteger('co1_q1');
            $table->tinyInteger('co1_q2');
            $table->tinyInteger('co2_q3');
            $table->tinyInteger('co2_q4');
            $table->tinyInteger('co3_q5');
            $table->tinyInteger('co3_q6');
            $table->tinyInteger('co4_q7');
            $table->tinyInteger('co4_q8');
            $table->tinyInteger('co4_q9');
            $table->tinyInteger('co_overall_q10');
            $table->timestamps();

            $table->foreign('exit_survey_id')->references('id')->on('course_exit_surveys')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_exit_surveys');
    }
};
