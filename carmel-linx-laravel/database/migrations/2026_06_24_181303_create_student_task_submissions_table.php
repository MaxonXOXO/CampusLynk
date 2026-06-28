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
        Schema::create('student_task_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 15);
            $table->string('subject_code', 20);
            $table->string('category', 50)->default('Assignment'); // e.g. Assignment
            $table->string('co_tag', 10);
            $table->string('status', 20)->default('Submitted'); // Submitted
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_task_submissions');
    }
};
