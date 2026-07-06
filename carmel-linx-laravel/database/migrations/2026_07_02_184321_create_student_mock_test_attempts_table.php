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
        Schema::create('student_mock_test_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 15);
            $table->string('subject_code', 20);
            $table->date('attempted_date');
            $table->timestamps();

            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            $table->index(['reg_no', 'subject_code', 'attempted_date'], 'student_mock_limit_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_mock_test_attempts');
    }
};
