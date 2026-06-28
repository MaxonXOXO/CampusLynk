<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_responses', function (Blueprint $table) {
            $table->uuid('response_id')->primary()->default(DB::raw('(UUID())'));
            $table->string('reg_no', 50);
            $table->uuid('test_id');
            $table->uuid('question_id');
            $table->string('selected_option', 10)->nullable();
            $table->text('descriptive_text')->nullable();
            $table->decimal('marks_obtained', 5, 2)->default(0.00);
            $table->string('evaluated_by', 15)->nullable();
            $table->string('status', 50)->default('Submitted'); // Submitted, Saved, Evaluated
            $table->timestamps();

            // Set up constraints
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            $table->foreign('test_id')->references('test_id')->on('test_configs')->onDelete('cascade');
            $table->foreign('question_id')->references('question_id')->on('question_bank')->onDelete('cascade');
            $table->foreign('evaluated_by')->references('mobile_no')->on('staff_profiles')->onDelete('set null');

            // Unique submission constraint
            $table->unique(['reg_no', 'test_id', 'question_id'], 'unique_student_response');
            
            // Fast lookup index
            $table->index(['reg_no', 'test_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_responses');
    }
};
