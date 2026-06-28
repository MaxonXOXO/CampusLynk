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
        Schema::create('test_attempts', function (Blueprint $table) {
            $table->uuid('attempt_id')->primary()->default(DB::raw('(UUID())'));
            $table->string('reg_no', 50);
            $table->uuid('test_id');
            $table->integer('attempt_number')->default(1);
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->decimal('total_score', 5, 2)->default(0.00);
            $table->string('status', 20)->default('in_progress'); // in_progress, completed, auto_submitted
            $table->timestamps();

            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            $table->foreign('test_id')->references('test_id')->on('test_configs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_attempts');
    }
};
