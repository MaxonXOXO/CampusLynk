<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutor_diaries', function (Blueprint $table) {
            $table->uuid('diary_id')->primary()->default(DB::raw('(UUID())'));
            $table->string('reg_no', 50);
            $table->date('date');
            $table->string('category', 100); // Academic Performance, Behavioral Issues, etc.
            $table->text('discussion_notes');
            $table->text('action_taken')->nullable();
            $table->text('remarks')->nullable();
            $table->string('logged_by', 15)->nullable();
            $table->timestamps();

            // Setup relations
            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            $table->foreign('logged_by')->references('mobile_no')->on('staff_profiles')->onDelete('set null');
            
            $table->index('reg_no');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_diaries');
    }
};
