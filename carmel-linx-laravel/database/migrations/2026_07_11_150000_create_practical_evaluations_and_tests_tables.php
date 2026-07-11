<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Practical Experiments table
        Schema::create('practical_experiments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('experiment_no', 10);
            $table->string('title');
            $table->string('co_tag', 10); // CO1, CO2, CO3, CO4
            $table->date('conducted_date')->nullable();
            $table->timestamps();

            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
        });

        // 2. Practical Experiment Marks table (37.5 Marks Breakdown)
        Schema::create('practical_experiment_marks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('practical_experiment_id');
            $table->string('reg_no', 50);
            $table->string('assessor_mobile_no', 50);
            $table->decimal('prerequisites', 5, 2)->default(0.00); // Max 7.5
            $table->decimal('work_done', 5, 2)->default(0.00);     // Max 10.0
            $table->decimal('result', 5, 2)->default(0.00);        // Max 5.0
            $table->decimal('rough_record', 5, 2)->default(0.00);   // Max 7.5
            $table->decimal('fair_record', 5, 2)->default(0.00);    // Max 7.5
            $table->decimal('total_mark', 5, 2)->default(0.00);     // Max 37.5
            $table->timestamps();

            $table->unique(['practical_experiment_id', 'reg_no'], 'pract_exp_marks_unique');
            $table->foreign('practical_experiment_id')->references('id')->on('practical_experiments')->onDelete('cascade');
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            $table->foreign('assessor_mobile_no')->references('mobile_no')->on('staff_profiles')->onDelete('cascade');
        });

        // 3. Consolidated Practical Evaluations table
        Schema::create('practical_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('reg_no', 50);
            $table->string('assessor_mobile_no', 50);
            $table->decimal('micro_project', 5, 2)->default(0.00); // Max 7.5
            $table->decimal('attendance_marks', 5, 2)->default(0.00); // Max 15.0
            $table->decimal('board_exam_marks', 5, 2)->nullable(); // Max 50.0
            $table->timestamps();

            $table->unique(['batch_subject_id', 'reg_no'], 'pract_eval_unique');
            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            $table->foreign('assessor_mobile_no')->references('mobile_no')->on('staff_profiles')->onDelete('cascade');
        });

        // 4. Practical Tests table (Summative configuration)
        Schema::create('practical_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('test_name', 50); // e.g., 'Test 1', 'Test 2'
            $table->json('questions'); // JSON array of questions and mapped COs
            $table->timestamps();

            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
        });

        // 5. Practical Test Marks table (Summative student marks per CO)
        Schema::create('practical_test_marks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('practical_test_id');
            $table->string('reg_no', 50);
            $table->string('co_tag', 10); // e.g. CO1, CO2, CO3, CO4
            $table->decimal('marks_obtained', 5, 2)->default(0.00); // Max 7.5
            $table->timestamps();

            $table->unique(['practical_test_id', 'reg_no', 'co_tag'], 'pract_test_marks_unique');
            $table->foreign('practical_test_id')->references('id')->on('practical_tests')->onDelete('cascade');
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practical_test_marks');
        Schema::dropIfExists('practical_tests');
        Schema::dropIfExists('practical_evaluations');
        Schema::dropIfExists('practical_experiment_marks');
        Schema::dropIfExists('practical_experiments');
    }
};
