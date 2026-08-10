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
        Schema::create('principal_scheduled_events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('event_category', 50)->default('Academic'); // Academic, Exam, Meeting, Cultural, Sports, Workshop, Holiday, Other
            $table->string('venue', 255)->default('Main Auditorium');
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('is_full_day')->default(false);

            // Scope & Targeting fields
            $table->string('target_audience', 50)->default('ALL_CAMPUS'); // ALL_CAMPUS, DEPT_SPECIFIC, STAFF_ONLY, STUDENTS_ONLY, SPECIAL_GROUP
            $table->string('target_department', 50)->default('ALL'); // ALL, CT, ME, CE, EEE, EL, AU
            $table->string('target_semester', 10)->default('ALL'); // ALL, S1, S2, S3, S4, S5, S6
            $table->string('target_role', 50)->default('ALL'); // ALL, HOD, Lecturer, Non-Teaching
            $table->string('special_group_name', 100)->nullable(); // e.g., Placement Cell, NSS/NCC, Sports Council, IQAC, Anti-Ragging Cell

            // Interactivity & Attachments
            $table->boolean('requires_rsvp')->default(false);
            $table->string('attachment_path', 255)->nullable();
            $table->string('attachment_type', 20)->default('none'); // pdf, image, none
            
            $table->string('dispatch_type', 20)->default('immediate'); // immediate, scheduled
            $table->timestamp('scheduled_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->string('created_by', 100)->default('Principal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('principal_scheduled_events');
    }
};
