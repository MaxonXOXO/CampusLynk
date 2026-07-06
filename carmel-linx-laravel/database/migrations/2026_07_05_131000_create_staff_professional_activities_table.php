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
        Schema::create('staff_professional_activities', function (Blueprint $table) {
            $table->id();
            $table->string('lecturer_mobile_no', 15);
            $table->string('academic_year');
            $table->string('activity_type');
            $table->json('details');
            $table->timestamps();

            $table->foreign('lecturer_mobile_no')->references('mobile_no')->on('staff_profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_professional_activities');
    }
};
