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
        Schema::create('student_survey_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_id');
            $table->string('reg_no', 15);
            $table->tinyInteger('pace_score'); // 1, 2, 3
            $table->tinyInteger('clarity_score'); // 1, 2, 3
            $table->tinyInteger('interaction_score'); // 1, 2, 3
            $table->tinyInteger('practicality_score'); // 1, 2, 3
            $table->tinyInteger('evaluation_score'); // 1, 2, 3
            $table->timestamps();

            $table->foreign('survey_id')->references('id')->on('mid_semester_surveys')->onDelete('cascade');
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            $table->unique(['survey_id', 'reg_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_survey_responses');
    }
};
