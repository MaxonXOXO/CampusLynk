<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('executive_flash_notices', function (Blueprint $table) {
            $table->id();
            $table->string('sender_id', 100)->nullable();
            $table->string('sender_role', 50)->default('Principal');
            $table->string('sender_name', 150)->default('Executive Desk');
            $table->string('title', 255);
            $table->text('content');
            $table->string('priority', 20)->default('Normal'); // Normal, Urgent, Circular
            $table->string('target_audience', 50)->default('ALL_CAMPUS'); // ALL_CAMPUS, STAFF_ALL, STAFF_DEPT, STUDENTS_ALL, STUDENTS_DEPT_SEM
            $table->string('target_department', 50)->default('ALL'); // ALL, EL, ME, CE, EEE, CT, AU, GEN_AIDED, GEN_SF
            $table->string('target_semester', 10)->default('ALL'); // ALL, 1, 2, 3, 4, 5, 6
            $table->string('attachment_path', 255)->nullable();
            $table->string('attachment_type', 20)->default('none'); // image, pdf, none
            $table->string('dispatch_type', 20)->default('immediate'); // immediate, scheduled
            $table->timestamp('scheduled_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('executive_flash_notices');
    }
};
