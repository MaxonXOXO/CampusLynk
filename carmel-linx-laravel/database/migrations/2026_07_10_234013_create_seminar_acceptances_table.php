<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seminar_acceptances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seminar_registration_id');
            $table->string('staff_mobile_no', 50);
            $table->string('status', 20)->default('accepted');
            $table->timestamps();

            $table->foreign('seminar_registration_id')->references('id')->on('student_seminar_registrations')->onDelete('cascade');
            $table->foreign('staff_mobile_no')->references('mobile_no')->on('staff_profiles')->onDelete('cascade');
            $table->unique(['seminar_registration_id', 'staff_mobile_no'], 'sem_reg_staff_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_acceptances');
    }
};
