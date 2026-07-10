<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_seminar_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id');
            $table->string('reg_no', 50)->unique();
            $table->string('topic', 255);
            $table->date('presentation_date');
            $table->string('guide_mobile_no', 50);
            $table->timestamps();

            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            $table->foreign('guide_mobile_no')->references('mobile_no')->on('staff_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_seminar_registrations');
    }
};
