<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remedial_rooms', function (Blueprint $table) {
            $table->uuid('room_id')->primary();
            $table->uuid('classroom_id');
            $table->string('subject_code');
            $table->string('created_by_mobile'); // Staff mobile no
            $table->string('status')->default('active'); // active, archived
            $table->timestamps();
        });

        Schema::create('remedial_students', function (Blueprint $table) {
            $table->id();
            $table->uuid('room_id');
            $table->string('reg_no');
            $table->timestamp('added_at')->useCurrent();
            
            $table->unique(['room_id', 'reg_no']);
        });

        Schema::create('remedial_session_logs', function (Blueprint $table) {
            $table->uuid('log_id')->primary();
            $table->uuid('room_id');
            $table->date('session_date');
            $table->integer('duration_minutes')->default(60);
            $table->string('topic_covered')->nullable();
            $table->json('attendance_data')->nullable(); // Array of reg_nos present
            $table->timestamps();
        });

        Schema::create('remedial_assessments', function (Blueprint $table) {
            $table->uuid('assessment_id')->primary();
            $table->uuid('room_id');
            $table->string('type'); // 'online' or 'manual'
            $table->string('title');
            $table->integer('max_marks');
            $table->json('questions_payload')->nullable(); // For online tests
            $table->timestamps();
        });

        Schema::create('remedial_assessment_scores', function (Blueprint $table) {
            $table->id();
            $table->uuid('assessment_id');
            $table->string('reg_no');
            $table->decimal('score', 8, 2);
            $table->timestamps();

            $table->unique(['assessment_id', 'reg_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remedial_assessment_scores');
        Schema::dropIfExists('remedial_assessments');
        Schema::dropIfExists('remedial_session_logs');
        Schema::dropIfExists('remedial_students');
        Schema::dropIfExists('remedial_rooms');
    }
};
