<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('r26_series_exam_qps')) {
            Schema::create('r26_series_exam_qps', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_subject_id');
                $table->string('series_no', 20)->default('Series 1'); // 'Series 1', 'Series 2', 'Series 3', 'Series 4'
                $table->string('co_tag', 10)->default('CO1'); // 'CO1'..'CO4'
                $table->string('pattern_type', 50)->default('table_4_1_standard'); // 'table_4_1_standard', 'table_4_2_design', 'table_4_3_drawing'
                $table->integer('max_marks')->default(50);
                $table->integer('duration_minutes')->default(120);
                $table->json('qp_data')->nullable(); // Part A, Part B, Part C questions
                $table->json('scheme_data')->nullable(); // Step-by-step marking rubrics
                $table->json('answer_key')->nullable(); // Detailed solutions
                $table->string('created_by', 50)->nullable();
                $table->timestamps();

                $table->unique(['batch_subject_id', 'series_no'], 'r26_qp_unique');
                $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('r26_series_exam_qps');
    }
};
