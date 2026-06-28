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
        Schema::create('student_attendance', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 50);
            $table->string('subject_code', 50);
            $table->date('date');
            $table->enum('status', ['Present', 'Absent', 'Late'])->default('Present');
            $table->unsignedBigInteger('lesson_plan_id')->nullable();
            $table->timestamps();

            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendance');
    }
};
