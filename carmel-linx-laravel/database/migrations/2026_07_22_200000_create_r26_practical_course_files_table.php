<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('r26_practical_course_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id')->unique();
            $table->string('syllabus_pdf_path')->nullable(); // Storage path to uploaded PDF
            $table->string('course_title')->nullable();      // Parsed from PDF
            $table->string('course_code')->nullable();       // Parsed from PDF
            $table->tinyInteger('credits')->default(1);
            $table->string('teaching_scheme', 20)->default('0:0:2:0');  // L:T:P:R
            $table->smallInteger('cie_marks')->default(60);
            $table->smallInteger('ese_marks')->default(40);
            $table->smallInteger('total_hours')->default(30);
            $table->longText('parsed_cos')->nullable();        // JSON array of CO objects
            $table->longText('parsed_copo')->nullable();       // JSON mapping matrix + scheme info
            $table->longText('parsed_experiments')->nullable();// JSON array of experiments from PDF
            $table->longText('manual_experiments')->nullable();// JSON array of manually edited experiments
            $table->string('status', 20)->default('Draft');
            $table->timestamps();

            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('r26_practical_course_files');
    }
};
