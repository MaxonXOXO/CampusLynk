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
        Schema::create('student_semester_summary', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 50);
            $table->integer('semester');
            $table->decimal('sgpa', 4, 2)->nullable();
            $table->decimal('cgpa', 4, 2)->nullable();
            $table->integer('activity_points')->default(0);
            $table->timestamps();

            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_semester_summary');
    }
};
