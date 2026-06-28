<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Main Record
        Schema::create('cf_course_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('academic_year'); // e.g. 2026-2027
            $table->string('status')->default('Draft'); // Draft, Complete
            $table->string('generated_pdf_path')->nullable();
            $table->timestamps();

            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
        });

        // Section A: Planning (Supplemental)
        Schema::create('cf_section_a_planning', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cf_id');
            $table->text('gaps_identified')->nullable();
            $table->text('bridge_topics')->nullable();
            $table->string('faculty_timetable_ref')->nullable();
            $table->string('class_timetable_ref')->nullable();
            $table->timestamps();

            $table->foreign('cf_id')->references('id')->on('cf_course_files')->onDelete('cascade');
        });

        // Section B: Materials (Supplemental)
        Schema::create('cf_section_b_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cf_id');
            $table->text('nptel_swayam_links')->nullable();
            $table->text('other_resources')->nullable();
            $table->timestamps();

            $table->foreign('cf_id')->references('id')->on('cf_course_files')->onDelete('cascade');
        });

        // Section C: Assessments (Supplemental)
        Schema::create('cf_section_c_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cf_id');
            $table->text('evaluation_scheme')->nullable();
            $table->timestamps();

            $table->foreign('cf_id')->references('id')->on('cf_course_files')->onDelete('cascade');
        });

        // Section D: Attainments (Supplemental)
        Schema::create('cf_section_d_attainments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cf_id');
            $table->text('action_taken_report')->nullable();
            $table->text('committee_minutes')->nullable();
            $table->timestamps();

            $table->foreign('cf_id')->references('id')->on('cf_course_files')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cf_section_d_attainments');
        Schema::dropIfExists('cf_section_c_assessments');
        Schema::dropIfExists('cf_section_b_materials');
        Schema::dropIfExists('cf_section_a_planning');
        Schema::dropIfExists('cf_course_files');
    }
};
