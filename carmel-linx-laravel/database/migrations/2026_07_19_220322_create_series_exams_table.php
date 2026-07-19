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
        Schema::create('series_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id')->index();
            $table->string('exam_name');
            $table->string('mode'); // single_co or combined_co
            $table->text('co_tags')->nullable(); // JSON array
            $table->integer('max_marks')->default(25);
            $table->integer('duration_minutes')->default(60);
            $table->longText('questions')->nullable(); // JSON structure of questions by Part A, B, C
            $table->boolean('locked')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series_exams');
    }
};
