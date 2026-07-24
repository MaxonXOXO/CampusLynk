<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Practicum Integration Breakpoint - 2026-07-24
     */
    public function up(): void
    {
        // 1. Practicum Course File Registry
        if (!Schema::hasTable('r26_practicum_course_files')) {
            Schema::create('r26_practicum_course_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id')->unique();
                $table->string('syllabus_pdf_path', 255)->nullable();
                $table->text('program')->nullable();
                $table->string('course_title', 255)->nullable();
                $table->string('course_code', 50)->nullable();
                $table->string('semester', 20)->nullable();
                $table->string('type_of_course', 255)->default('Practicum');
                $table->string('teaching_scheme', 50)->default('3:0:3:0'); // L:T:P:R
                $table->integer('contact_hours')->default(90);
                $table->decimal('credits', 4, 1)->default(4.5);
                $table->integer('cie_marks')->default(40);
                $table->integer('ese_marks')->default(100); // 100 for Standard, 60 for BS/HS
                $table->json('parsed_cos')->nullable();
                $table->json('parsed_modules')->nullable(); // Theory Modules
                $table->json('parsed_experiments')->nullable(); // Practical Experiments List
                $table->json('parsed_copo')->nullable(); // CO-PO Matrix & Metadata
                $table->json('parsed_textbooks')->nullable();
                $table->json('self_learning_configs')->nullable(); // Module SL configs
                $table->timestamps();

                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            });
        }

        // 2. Practicum Continuous Practical Experiment Evaluation (Table 2.2 Rubrics - Max 50, converted to 10 CIA marks)
        if (!Schema::hasTable('r26_practicum_experiment_evaluations')) {
            Schema::create('r26_practicum_experiment_evaluations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('experiment_no', 50);
                $table->string('experiment_title', 255)->nullable();
                $table->string('reg_no', 50);
                $table->decimal('prep_punctuality', 5, 2)->default(0.00); // Max 10 (Sl 1)
                $table->decimal('setup_procedure', 5, 2)->default(0.00);  // Max 10 (Sl 2)
                $table->decimal('observation_recording', 5, 2)->default(0.00); // Max 5 (Sl 3)
                $table->decimal('analysis_interpretation', 5, 2)->default(0.00); // Max 10 (Sl 4)
                $table->decimal('viva_voce', 5, 2)->default(0.00); // Max 10 (Sl 5)
                $table->decimal('workmanship_discipline', 5, 2)->default(0.00); // Max 5 (Sl 6)
                $table->decimal('total_score_50', 5, 2)->default(0.00); // Max 50
                $table->string('assessor_mobile_no', 50)->nullable();
                $table->timestamps();

                $table->unique(['batch_subject_id', 'experiment_no', 'reg_no'], 'r26_practicum_exp_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }

        // 3. Practicum Series Exam - Theory (Max 50 converted to 10 CIA marks)
        if (!Schema::hasTable('r26_practicum_series_theory')) {
            Schema::create('r26_practicum_series_theory', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('series_no', 20)->default('Series 1'); // 'Series 1', 'Series 2'
                $table->string('reg_no', 50);
                $table->decimal('part_a_score', 5, 2)->default(0.00); // Part A (Max 4 or 15)
                $table->decimal('part_b_score', 5, 2)->default(0.00); // Part B (Max 18 or 20)
                $table->decimal('part_c_score', 5, 2)->default(0.00); // Part C (Max 28 or 15)
                $table->decimal('total_score_50', 5, 2)->default(0.00); // Max 50
                $table->boolean('is_absent')->default(false);
                $table->timestamps();

                $table->unique(['batch_subject_id', 'series_no', 'reg_no'], 'r26_practicum_st_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }

        // 4. Practicum Series Exam - Practical (Table 3.1 Rubrics - Max 40 converted to 10 CIA marks)
        if (!Schema::hasTable('r26_practicum_series_practical')) {
            Schema::create('r26_practicum_series_practical', function (Blueprint $table) {
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
                $table->boolean('is_absent')->default(false);
                $table->timestamps();

                $table->unique(['batch_subject_id', 'series_no', 'reg_no'], 'r26_practicum_sp_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }

        // 5. Practicum End Semester Examination (ESE) Marks (Theory 60 + Practical 40)
        if (!Schema::hasTable('r26_practicum_ese_marks')) {
            Schema::create('r26_practicum_ese_marks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('reg_no', 50);
                $table->decimal('ese_theory_marks', 5, 2)->default(0.00); // Max 60 (Theory Board Exam)
                $table->decimal('ese_practical_marks', 5, 2)->default(0.00); // Max 40 (Institutional Practical Exam)
                $table->decimal('total_ese_marks', 5, 2)->default(0.00); // Max 100 or 60
                $table->boolean('theory_absent')->default(false);
                $table->boolean('practical_absent')->default(false);
                $table->timestamps();

                $table->unique(['batch_subject_id', 'reg_no'], 'r26_practicum_ese_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('r26_practicum_ese_marks');
        Schema::dropIfExists('r26_practicum_series_practical');
        Schema::dropIfExists('r26_practicum_series_theory');
        Schema::dropIfExists('r26_practicum_experiment_evaluations');
        Schema::dropIfExists('r26_practicum_course_files');
    }
};
