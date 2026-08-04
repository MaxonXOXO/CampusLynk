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
        Schema::dropIfExists('student_material_read_receipts');
        Schema::dropIfExists('virtual_learning_materials');

        Schema::create('virtual_learning_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('subject_code', 50);
            $table->string('classroom_id', 50);
            $table->enum('room_type', ['Theory', 'Practical', 'Practicum', 'Drawing'])->default('Theory');
            
            // Flexible Indexing: "Module I - Day 12" for Theory or "Experiment 03" for Labs
            $table->string('experiment_or_topic_no', 100);
            $table->string('title', 255);
            $table->text('pre_class_instruction')->nullable();
            
            // Resource Payload
            $table->enum('material_type', ['pdf', 'video', 'image', 'document', 'link'])->default('pdf');
            $table->string('file_path', 500)->nullable();
            $table->string('video_url', 500)->nullable();
            
            // Notification & Scheduling
            $table->boolean('is_pre_class_notice')->default(true);
            $table->date('target_date')->nullable();
            $table->string('uploaded_by', 50);
            
            $table->timestamps();

            $table->index(['batch_subject_id', 'experiment_or_topic_no'], 'vlm_subject_topic_idx');
            $table->index(['classroom_id', 'target_date'], 'vlm_class_date_idx');
        });

        Schema::create('student_material_read_receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_id');
            $table->string('reg_no', 50);
            $table->timestamp('read_at')->nullable();
            
            $table->unique(['material_id', 'reg_no'], 'smrr_material_reg_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_material_read_receipts');
        Schema::dropIfExists('virtual_learning_materials');
    }
};
