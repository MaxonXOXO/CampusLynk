<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('r26_practical_ese_marks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_subject_id')->index();
            $table->string('reg_no', 50);
            $table->decimal('ese_score', 5, 2)->default(0.00); // Max 40
            $table->string('assessor_mobile_no', 50)->nullable();
            $table->timestamps();

            $table->unique(['batch_subject_id', 'reg_no']);
            $table->foreign('batch_subject_id')->references('id')->on('batch_subjects')->onDelete('cascade');
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('r26_practical_ese_marks');
    }
};
