<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Table 2.2: Continuous Evaluation Log (Per Experiment)
        // Marks out of 50 (Criteria 1-6), normalized to 30 CIA marks
        Schema::create('r26_practical_experiment_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('experiment_no', 20);
            $table->string('title', 255)->nullable();
            $table->string('reg_no', 50);
            $table->decimal('prep_punctuality', 5, 2)->default(0.00); // Max 10 (Sl 1)
            $table->decimal('setup_procedure', 5, 2)->default(0.00);  // Max 10 (Sl 2)
            $table->decimal('observation_recording', 5, 2)->default(0.00); // Max 5 (Sl 3)
            $table->decimal('analysis_interpretation', 5, 2)->default(0.00); // Max 10 (Sl 4)
            $table->decimal('viva_voce', 5, 2)->default(0.00); // Max 10 (Sl 5)
            $table->decimal('teamwork_discipline', 5, 2)->default(0.00); // Max 5 (Sl 6)
            $table->decimal('total_score_50', 5, 2)->default(0.00); // Max 50
            $table->string('assessor_mobile_no', 50)->nullable();
            $table->timestamps();

            $table->unique(['batch_subject_id', 'experiment_no', 'reg_no'], 'r26_pract_exp_unique');
            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
        });

        // 2. Table 2.3: Open-Ended Experiment Evaluation
        // Marks out of 50 (Criteria 1-5), normalized to 10 CIA marks
        Schema::create('r26_open_ended_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('project_title', 255)->nullable();
            $table->string('reg_no', 50);
            $table->decimal('originality_relevance', 5, 2)->default(0.00); // Max 10 (Sl 1)
            $table->decimal('objectives_plan', 5, 2)->default(0.00); // Max 10 (Sl 2)
            $table->decimal('execution_recording', 5, 2)->default(0.00); // Max 10 (Sl 3)
            $table->decimal('analysis_presentation', 5, 2)->default(0.00); // Max 10 (Sl 4)
            $table->decimal('teamwork_innovation', 5, 2)->default(0.00); // Max 10 (Sl 5)
            $table->decimal('total_score_50', 5, 2)->default(0.00); // Max 50
            $table->string('assessor_mobile_no', 50)->nullable();
            $table->timestamps();

            $table->unique(['batch_subject_id', 'reg_no'], 'r26_open_ended_unique');
            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
        });

        // 3. Table 3.1: Practical Series Exam Marks
        // Marks out of 40 (Criteria 1-5), normalized to 15 CIA marks
        Schema::create('r26_practical_series_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('series_no', 20)->default('Series 1'); // 'Series 1', 'Series 2'
            $table->string('reg_no', 50);
            $table->decimal('writeup_procedure', 5, 2)->default(0.00); // Max 10 (Sl 1)
            $table->decimal('setup_execution', 5, 2)->default(0.00); // Max 10 (Sl 2)
            $table->decimal('observation_result', 5, 2)->default(0.00); // Max 8 (Sl 3)
            $table->decimal('viva_voce', 5, 2)->default(0.00); // Max 8 (Sl 4)
            $table->decimal('record_completion', 5, 2)->default(0.00); // Max 4 (Sl 5)
            $table->decimal('total_score_40', 5, 2)->default(0.00); // Max 40
            $table->string('assessor_mobile_no', 50)->nullable();
            $table->timestamps();

            $table->unique(['batch_subject_id', 'series_no', 'reg_no'], 'r26_series_pract_unique');
            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('r26_practical_series_evaluations');
        Schema::dropIfExists('r26_open_ended_evaluations');
        Schema::dropIfExists('r26_practical_experiment_evaluations');
    }
};
