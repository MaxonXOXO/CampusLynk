<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('r26_student_lab_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('reg_no', 50);
            $table->string('lab_batch', 20); // 'Batch A', 'Batch B'
            $table->timestamps();

            $table->unique(['batch_subject_id', 'reg_no'], 'r26_student_lab_batch_unique');
            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('r26_student_lab_batches');
    }
};
