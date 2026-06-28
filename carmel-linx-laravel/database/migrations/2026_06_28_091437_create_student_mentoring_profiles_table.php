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
        Schema::create('student_mentoring_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no')->unique();
            $table->string('gender')->nullable();
            $table->string('caste')->nullable();
            $table->string('religion')->nullable();
            $table->string('special_category')->nullable();
            $table->string('reservation')->nullable();
            $table->string('quota')->nullable();
            $table->boolean('is_physically_disabled')->default(false);
            $table->string('disability_category')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('monthly_family_income')->nullable();
            $table->boolean('has_vehicle_pass')->default(false);
            $table->string('vehicle_pass_id')->nullable();
            $table->text('communication_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_mentoring_profiles');
    }
};
