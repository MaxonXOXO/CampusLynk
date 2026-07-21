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
        Schema::create('r26_course_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('academic_year')->default('2026-2027');
            $table->string('status')->default('Draft'); // Draft, Complete
            $table->string('generated_pdf_path')->nullable();
            $table->timestamps();

            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
        });

        Schema::create('r26_course_file_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('r26_course_file_id')->constrained('r26_course_files')->onDelete('cascade');
            $table->integer('document_number');
            $table->string('document_name');
            $table->boolean('is_checked')->default(false);
            $table->string('remarks')->nullable();
            $table->text('data_payload')->nullable(); // For storing custom notes/data for this document
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r26_course_file_documents');
        Schema::dropIfExists('r26_course_files');
    }
};
