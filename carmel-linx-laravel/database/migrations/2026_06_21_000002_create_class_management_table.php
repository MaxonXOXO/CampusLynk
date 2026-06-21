<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_management', function (Blueprint $table) {
            $table->string('classroom_id', 50)->primary(); // e.g. 'EL_2025_S3'
            $table->string('branch', 50);
            $table->integer('batch_year');
            $table->string('tutor_mobile_no', 15)->nullable();
            $table->string('mentor_mobile_no', 15)->nullable();
            $table->timestamps();

            // Set up relationship index lookup references
            $table->foreign('tutor_mobile_no')->references('mobile_no')->on('staff_profiles')->onDelete('set null');
            $table->foreign('mentor_mobile_no')->references('mobile_no')->on('staff_profiles')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_management');
    }
};
