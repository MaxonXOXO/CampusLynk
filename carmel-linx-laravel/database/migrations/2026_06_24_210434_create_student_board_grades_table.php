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
        Schema::create('student_board_grades', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 50);
            $table->integer('semester');
            $table->string('subject_code', 50);
            $table->string('grade', 10)->nullable();
            $table->timestamps();

            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_board_grades');
    }
};
