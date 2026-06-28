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
        Schema::create('course_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('syllabus_pdf_path')->nullable();
            $table->json('parsed_modules')->nullable();
            $table->json('parsed_cos')->nullable();
            $table->json('parsed_textbooks')->nullable();
            $table->timestamps();

            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_files');
    }
};
