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
        Schema::create('batch_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('classroom_id', 50);
            $table->integer('semester');
            $table->string('subject_code', 50);
            $table->string('subject_name');
            $table->string('subject_type', 100);
            $table->timestamps();

            $table->foreign('classroom_id')->references('classroom_id')->on('class_management')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_subjects');
    }
};
