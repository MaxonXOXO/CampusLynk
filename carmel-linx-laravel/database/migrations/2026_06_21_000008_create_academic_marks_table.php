<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_marks', function (Blueprint $table) {
            $table->uuid('mark_id')->primary()->default(DB::raw('(UUID())'));
            $table->string('reg_no', 50);
            $table->string('subject_code', 50);
            $table->string('category', 50); // e.g. Model Exam, Assignment, Lab
            $table->string('co_tag', 10);
            $table->integer('max_marks');
            $table->decimal('marks_obtained', 5, 2);
            $table->string('entered_by', 15)->nullable();
            $table->timestamps();

            // Set up constraints
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            $table->foreign('subject_code')->references('subject_code')->on('syllabus_registry')->onDelete('cascade');
            $table->foreign('entered_by')->references('mobile_no')->on('staff_profiles')->onDelete('set null');
            
            // Search indexes
            $table->index(['reg_no', 'subject_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_marks');
    }
};
