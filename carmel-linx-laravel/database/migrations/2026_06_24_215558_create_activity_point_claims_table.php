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
        Schema::create('activity_point_claims', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reg_no', 50);
            $table->string('activity_segment');
            $table->string('activity_name');
            $table->string('level');
            $table->integer('points_claimed');
            $table->integer('points_awarded')->default(0);
            $table->string('status')->default('Pending'); // Pending, Verified, Rejected
            $table->text('document_reference')->nullable();
            $table->string('verified_by', 50)->nullable(); // Tutor/Mentor ID
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_point_claims');
    }
};
