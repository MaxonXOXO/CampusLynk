<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seminar_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('reg_no', 50);
            $table->string('assessor_mobile_no', 50); // The staff member grading
            $table->decimal('relevance', 5, 2)->default(0.00); // Max 5
            $table->decimal('literature', 5, 2)->default(0.00); // Max 5
            $table->decimal('presentation', 5, 2)->default(0.00); // Max 25
            $table->decimal('interaction', 5, 2)->default(0.00); // Max 5
            $table->decimal('report', 5, 2)->default(0.00); // Max 5
            $table->decimal('attendance', 5, 2)->default(0.00); // Max 5
            $table->decimal('total_score', 5, 2)->default(0.00); // Max 50
            $table->timestamps();

            $table->unique(['batch_subject_id', 'reg_no', 'assessor_mobile_no'], 'seminar_eval_unique');
            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            $table->foreign('assessor_mobile_no')->references('mobile_no')->on('staff_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_evaluations');
    }
};
