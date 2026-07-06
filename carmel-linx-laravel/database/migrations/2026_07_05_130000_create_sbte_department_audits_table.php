<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sbte_department_audits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('academic_year', 20);
            $table->string('branch', 50);
            
            // Part C fields
            $table->boolean('nba_accredited')->default(false);
            $table->json('enrollment_data')->nullable();
            $table->json('academic_perf_no_backlog')->nullable();
            $table->json('academic_perf_with_backlog')->nullable();
            $table->json('placement_data')->nullable();
            $table->json('professional_activities')->nullable();
            $table->json('sfr_data')->nullable();
            $table->json('infrastructure_data')->nullable();
            $table->json('vision_mission_data')->nullable();
            $table->json('teaching_learning_data')->nullable();
            $table->json('course_files_data')->nullable();
            $table->json('faculty_training_data')->nullable();
            $table->json('fdp_conducted_data')->nullable();
            $table->json('consultancy_data')->nullable();
            $table->json('achievements_data')->nullable();
            
            $table->timestamps();

            $table->unique(['academic_year', 'branch']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sbte_department_audits');
    }
};
