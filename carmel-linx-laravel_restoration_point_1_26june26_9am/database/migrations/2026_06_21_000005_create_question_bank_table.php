<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_bank', function (Blueprint $table) {
            $table->uuid('question_id')->primary()->default(DB::raw('(UUID())')); // Or autoincrement ID if standard, but UUID is great
            $table->string('branch_code', 10);
            $table->string('subject_code', 50);
            $table->string('type', 20); // MCQ, Descriptive
            $table->text('question_text');
            $table->json('options')->nullable(); // Store options list for MCQs as JSON array
            $table->text('correct_answer')->nullable();
            $table->string('co_tag', 10); // e.g. CO1, CO2
            $table->integer('marks');
            $table->timestamps();

            // Set up constraint
            $table->foreign('subject_code')->references('subject_code')->on('syllabus_registry')->onDelete('cascade');
            $table->index('subject_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_bank');
    }
};
