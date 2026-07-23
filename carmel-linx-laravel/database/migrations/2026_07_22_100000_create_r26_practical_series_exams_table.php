<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('r26_practical_series_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id')->index();
            $table->string('exam_name'); // 'Series 1', 'Series 2'
            $table->text('co_tags')->nullable(); // JSON array
            $table->integer('max_marks')->default(40);
            $table->integer('duration_minutes')->default(120);
            $table->longText('question_outline')->nullable(); // Questions or instructions
            $table->boolean('locked')->default(false);
            $table->timestamps();

            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('r26_practical_series_exams');
    }
};
