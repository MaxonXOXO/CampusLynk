<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Revision 2026 Health and Physical Education Virtual Classroom Tables (S1 Unique Paper)
     */
    public function up(): void
    {
        // 1. Health & Physical Course File Registry
        if (!Schema::hasTable('r26_health_physical_course_files')) {
            Schema::create('r26_health_physical_course_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id')->unique();
                $table->string('syllabus_pdf_path', 255)->nullable();
                $table->text('program')->nullable();
                $table->string('course_title', 255)->nullable();
                $table->string('course_code', 50)->nullable();
                $table->string('semester', 20)->default('I');
                $table->string('type_of_course', 255)->default('Health & Physical');
                $table->string('teaching_scheme', 50)->default('0:0:2:0'); // L:T:P:R
                $table->integer('contact_hours')->default(30);
                $table->decimal('credits', 4, 1)->default(1.0);
                $table->integer('cie_marks')->default(60);
                $table->integer('ese_marks')->default(40);
                $table->json('parsed_cos')->nullable();
                $table->json('parsed_modules')->nullable();
                $table->json('parsed_activities')->nullable();
                $table->json('parsed_copo')->nullable();
                $table->json('parsed_eval_scheme')->nullable(); // Extracted dynamic evaluation split-up titles & marks
                $table->json('parsed_textbooks')->nullable();
                $table->timestamps();

                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            });
        }

        // 2. Continuous Fitness & Activity Log Evaluations
        if (!Schema::hasTable('r26_health_physical_evaluations')) {
            Schema::create('r26_health_physical_evaluations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('activity_no', 50);
                $table->string('activity_title', 255)->nullable();
                $table->string('reg_no', 50);
                $table->json('criteria_json')->nullable(); // Dynamic criteria scores from PDF splitup
                $table->decimal('c1', 5, 2)->default(0.00);
                $table->decimal('c2', 5, 2)->default(0.00);
                $table->decimal('c3', 5, 2)->default(0.00);
                $table->decimal('c4', 5, 2)->default(0.00);
                $table->decimal('c5', 5, 2)->default(0.00);
                $table->decimal('c6', 5, 2)->default(0.00);
                $table->decimal('total_score_50', 5, 2)->default(0.00);
                $table->string('assessor_mobile_no', 50)->nullable();
                $table->timestamps();

                $table->unique(['batch_subject_id', 'activity_no', 'reg_no'], 'r26_hp_eval_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }

        // 3. Physical Fitness & Skill Tests (CA1 & CA2)
        if (!Schema::hasTable('r26_health_physical_fitness_tests')) {
            Schema::create('r26_health_physical_fitness_tests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('test_no', 20)->default('CA1'); // CA1 (Mid-Sem Test), CA2 (End-Sem Test)
                $table->string('reg_no', 50);
                $table->json('criteria_json')->nullable();
                $table->decimal('total_score_40', 5, 2)->default(0.00);
                $table->boolean('is_absent')->default(false);
                $table->timestamps();

                $table->unique(['batch_subject_id', 'test_no', 'reg_no'], 'r26_hp_test_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }

        // 4. End Semester Exam Marks (ESE - Max 40)
        if (!Schema::hasTable('r26_health_physical_ese_marks')) {
            Schema::create('r26_health_physical_ese_marks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('reg_no', 50);
                $table->decimal('fitness_test_score', 5, 2)->default(0.00);
                $table->decimal('skill_demo_score', 5, 2)->default(0.00);
                $table->decimal('viva_score', 5, 2)->default(0.00);
                $table->decimal('record_score', 5, 2)->default(0.00);
                $table->decimal('total_ese_40', 5, 2)->default(0.00);
                $table->boolean('is_absent')->default(false);
                $table->timestamps();

                $table->unique(['batch_subject_id', 'reg_no'], 'r26_hp_ese_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
                $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('r26_health_physical_ese_marks');
        Schema::dropIfExists('r26_health_physical_fitness_tests');
        Schema::dropIfExists('r26_health_physical_evaluations');
        Schema::dropIfExists('r26_health_physical_course_files');
    }
};
