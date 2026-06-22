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
        Schema::create('subject_staff_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_subject_id')->constrained('batch_subjects')->onDelete('cascade');
            $table->string('staff_mobile_no', 20);
            $table->timestamps();

            $table->foreign('staff_mobile_no')->references('mobile_no')->on('staff_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_staff_assignments');
    }
};
