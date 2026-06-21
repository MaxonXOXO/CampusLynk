<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->string('reg_no', 50)->primary();
            $table->string('adm_no', 50)->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone', 15)->nullable();
            $table->string('branch', 50);
            $table->integer('admission_year');
            $table->string('admission_type', 50)->default('Regular'); // Regular, LET
            $table->text('photo_url')->nullable();
            $table->string('classroom_id', 50)->nullable();
            $table->string('status', 50)->default('Pending'); // Pending, Approved, Suspended
            $table->string('sbte_reg_no', 50)->nullable();
            $table->string('mentor_mobile_no', 15)->nullable();
            $table->timestamps();

            // Setup relationships
            $table->foreign('classroom_id')->references('classroom_id')->on('class_management')->onDelete('set null');
            $table->foreign('mentor_mobile_no')->references('mobile_no')->on('staff_profiles')->onDelete('set null');
            
            // Fast lookup index
            $table->index('classroom_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
