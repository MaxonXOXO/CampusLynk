<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('department_semester_pass_stats');

        Schema::create('department_semester_pass_stats', function (Blueprint $table) {
            $table->id();
            $table->string('branch', 50);
            $table->string('academic_year', 20)->default('2025-2026');
            $table->string('semester', 10)->default('S5');
            $table->integer('total_students')->default(0);
            $table->integer('appeared_count')->default(0);
            $table->integer('passed_count')->default(0);
            $table->decimal('pass_percentage', 5, 2)->default(0.00);
            $table->string('uploaded_by', 100)->nullable();
            $table->timestamps();

            $table->unique(['branch', 'academic_year', 'semester'], 'dept_sem_pass_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_semester_pass_stats');
    }
};
