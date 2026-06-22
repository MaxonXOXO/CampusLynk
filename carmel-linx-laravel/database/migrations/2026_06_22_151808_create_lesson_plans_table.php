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
        Schema::create('lesson_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('co_id', 10)->nullable(); // e.g., CO1, CO2
            $table->text('topic_content');
            $table->integer('allocated_hours')->default(1);
            $table->date('proposed_date')->nullable();
            $table->date('actual_date')->nullable();
            $table->integer('actual_hours')->nullable();
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
            $table->timestamps();

            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_plans');
    }
};
