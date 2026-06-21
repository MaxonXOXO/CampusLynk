<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentoring_batches', function (Blueprint $table) {
            $table->id();
            $table->string('classroom_id', 50);
            $table->string('reg_no', 50);
            $table->string('mentor_no', 15);       // The mentor's mobile_no (staff)
            $table->enum('batch_label', ['A', 'B']); // A = Mentor-1 (Tutor), B = Mentor-2
            $table->string('assigned_by', 15)->nullable(); // Tutor mobile_no who did the split
            $table->timestamps();

            $table->foreign('classroom_id')->references('classroom_id')->on('class_management')->onDelete('cascade');
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            $table->foreign('mentor_no')->references('mobile_no')->on('staff_profiles')->onDelete('cascade');

            // Each student can only be in one batch per classroom
            $table->unique(['classroom_id', 'reg_no'], 'unique_student_batch');
            $table->index(['mentor_no', 'classroom_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentoring_batches');
    }
};
