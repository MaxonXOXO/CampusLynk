<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_configs', function (Blueprint $table) {
            $table->uuid('test_id')->primary()->default(DB::raw('(UUID())'));
            $table->string('subject_code', 50);
            $table->string('classroom_id', 50);
            $table->string('test_name');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('duration'); // in minutes
            $table->json('selected_cos'); // array of COs, e.g. ["CO1", "CO2"]
            $table->integer('mcq_count')->default(0);
            $table->integer('descriptive_count')->default(0);
            $table->integer('target_percentage')->default(50);
            $table->integer('pass_threshold')->default(40);
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            // Constraints
            $table->foreign('subject_code')->references('subject_code')->on('syllabus_registry')->onDelete('cascade');
            $table->foreign('classroom_id')->references('classroom_id')->on('class_management')->onDelete('cascade');
            $table->index('classroom_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_configs');
    }
};
