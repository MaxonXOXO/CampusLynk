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
        Schema::create('class_logs_attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->date('date');
            $table->integer('period');
            $table->unsignedBigInteger('lesson_plan_id')->nullable();
            $table->text('topics_covered')->nullable();
            $table->text('present_students')->nullable(); // JSON string of registration numbers
            $table->text('absent_students')->nullable();  // JSON string of registration numbers
            $table->string('recorded_by');
            $table->timestamps();

            // Set up foreign keys
            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            $table->foreign('lesson_plan_id')->references('id')->on('lesson_plans')->onDelete('set null');

            // Compound index for performance
            $table->index(['batch_subject_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_logs_attendance');
    }
};
