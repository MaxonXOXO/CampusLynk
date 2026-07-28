<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * R2026 Virtual Drawing Hall (Lab Model) Tables - 2026-07-29
     */
    public function up(): void
    {
        // 1. Drawing Course File Registry
        if (!Schema::hasTable('r26_drawing_course_files')) {
            Schema::create('r26_drawing_course_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id')->unique();
                $table->string('syllabus_pdf_path', 255)->nullable();
                $table->text('program')->nullable();
                $table->string('course_title', 255)->nullable();
                $table->string('course_code', 50)->nullable();
                $table->string('semester', 20)->nullable();
                $table->string('type_of_course', 255)->default('Lab');
                $table->string('teaching_scheme', 50)->default('0:0:3:0'); // L:T:P:R
                $table->integer('contact_hours')->default(45);
                $table->decimal('credits', 4, 1)->default(1.5);
                $table->integer('cie_marks')->default(60);
                $table->integer('ese_marks')->default(40);
                $table->json('parsed_cos')->nullable();
                $table->json('parsed_modules')->nullable();
                $table->json('parsed_exercises')->nullable();
                $table->json('parsed_copo')->nullable();
                $table->json('parsed_textbooks')->nullable();
                $table->json('self_learning_configs')->nullable();
                $table->timestamps();

                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            });
        }

        // 2. Continuous Practical Evaluation (CE - Slot wise Rubrics - Max 50 -> Converted to 30 CIE Marks)
        if (!Schema::hasTable('r26_drawing_slot_evaluations')) {
            Schema::create('r26_drawing_slot_evaluations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('exercise_no', 50);
                $table->string('exercise_title', 255)->nullable();
                $table->string('reg_no', 50);
                $table->decimal('prep_punctuality', 5, 2)->default(0.00); // Max 10
                $table->decimal('setup_procedure', 5, 2)->default(0.00);  // Max 10
                $table->decimal('observation_recording', 5, 2)->default(0.00); // Max 5
                $table->decimal('analysis_interpretation', 5, 2)->default(0.00); // Max 10
                $table->decimal('viva_voce', 5, 2)->default(0.00); // Max 10
                $table->decimal('workmanship_discipline', 5, 2)->default(0.00); // Max 5
                $table->decimal('total_score_50', 5, 2)->default(0.00); // Max 50
                $table->string('assessor_mobile_no', 50)->nullable();
                $table->timestamps();

                $table->unique(['batch_subject_id', 'exercise_no', 'reg_no'], 'r26_drawing_slot_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }

        // 3. Practical Tests CA1 & CA2 (Rubrics - Max 40 -> Converted to 15 CIE Marks)
        if (!Schema::hasTable('r26_drawing_practical_tests')) {
            Schema::create('r26_drawing_practical_tests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('test_no', 20)->default('CA1'); // 'CA1' (Descriptive Week 7), 'CA2' (CAD Week 14)
                $table->string('reg_no', 50);
                $table->decimal('writeup_procedure', 5, 2)->default(0.00); // Max 10
                $table->decimal('setup_execution', 5, 2)->default(0.00); // Max 10
                $table->decimal('observation_result', 5, 2)->default(0.00); // Max 8
                $table->decimal('viva_voce', 5, 2)->default(0.00); // Max 8
                $table->decimal('record_completion', 5, 2)->default(0.00); // Max 4
                $table->decimal('total_score_40', 5, 2)->default(0.00); // Max 40
                $table->boolean('is_absent')->default(false);
                $table->timestamps();

                $table->unique(['batch_subject_id', 'test_no', 'reg_no'], 'r26_drawing_test_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }

        // 4. Open-Ended Experiment Evaluation (OEE - Max 50 -> Converted to 10 CIE Marks)
        if (!Schema::hasTable('r26_drawing_oee_evaluations')) {
            Schema::create('r26_drawing_oee_evaluations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('reg_no', 50);
                $table->decimal('originality_relevance', 5, 2)->default(0.00); // Max 10
                $table->decimal('objectives_plan', 5, 2)->default(0.00); // Max 10
                $table->decimal('execution_recording', 5, 2)->default(0.00); // Max 10
                $table->decimal('analysis_presentation', 5, 2)->default(0.00); // Max 10
                $table->decimal('teamwork_innovation', 5, 2)->default(0.00); // Max 10
                $table->decimal('total_score_50', 5, 2)->default(0.00); // Max 50
                $table->timestamps();

                $table->unique(['batch_subject_id', 'reg_no'], 'r26_drawing_oee_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }

        // 5. End Semester Examination (ESE - CAD Practical - Max 40 Marks)
        if (!Schema::hasTable('r26_drawing_ese_marks')) {
            Schema::create('r26_drawing_ese_marks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('reg_no', 50);
                $table->decimal('part_a_mcq', 5, 2)->default(0.00); // Max 10 (CO1 & CO2)
                $table->decimal('part_b_cad', 5, 2)->default(0.00); // Max 18 (CO3 & CO4 CAD Drawing)
                $table->decimal('part_c_viva', 5, 2)->default(0.00); // Max 8 (Viva)
                $table->decimal('part_d_record', 5, 2)->default(0.00); // Max 4 (Record)
                $table->decimal('total_ese_40', 5, 2)->default(0.00); // Max 40
                $table->boolean('is_absent')->default(false);
                $table->timestamps();

                $table->unique(['batch_subject_id', 'reg_no'], 'r26_drawing_ese_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('r26_drawing_ese_marks');
        Schema::dropIfExists('r26_drawing_oee_evaluations');
        Schema::dropIfExists('r26_drawing_practical_tests');
        Schema::dropIfExists('r26_drawing_slot_evaluations');
        Schema::dropIfExists('r26_drawing_course_files');
    }
};
