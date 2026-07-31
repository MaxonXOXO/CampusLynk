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
        Schema::create('staff_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->string('leave_code', 30)->unique();
            $table->string('staff_mobile', 20);
            $table->string('staff_name', 150);
            $table->string('designation', 100);
            $table->string('department', 100);
            $table->string('leave_type', 50); // Casual Leave, Duty Leave, Medical Leave, LOP, Special Leave
            $table->date('from_date');
            $table->date('to_date');
            $table->string('session_type', 20)->default('Full Day'); // Full Day, FN, AN
            $table->decimal('total_days', 4, 1)->default(1.0);
            $table->text('reason');
            
            // Work Arrangement details stored as JSON
            $table->json('work_arrangement')->nullable();

            // Digital Signatures / Identifiers
            $table->string('staff_signature_hash', 100)->nullable();
            $table->dateTime('submitted_at')->nullable();

            // Stage 1: HOD Approval
            $table->string('hod_status', 20)->default('Pending'); // Pending, Approved, Rejected
            $table->string('hod_mobile', 20)->nullable();
            $table->string('hod_name', 150)->nullable();
            $table->text('hod_remarks')->nullable();
            $table->dateTime('hod_action_at')->nullable();

            // Stage 2: Academic Coordinator (Self Financing) Approval
            $table->string('coordinator_status', 20)->default('Pending');
            $table->string('coordinator_mobile', 20)->nullable();
            $table->string('coordinator_name', 150)->nullable();
            $table->text('coordinator_remarks')->nullable();
            $table->dateTime('coordinator_action_at')->nullable();

            // Stage 3: Principal Approval
            $table->string('principal_status', 20)->default('Pending');
            $table->string('principal_mobile', 20)->nullable();
            $table->string('principal_name', 150)->nullable();
            $table->text('principal_remarks')->nullable();
            $table->dateTime('principal_action_at')->nullable();

            // Overall Workflow Status
            $table->string('overall_status', 30)->default('Pending_HOD'); // Pending_HOD, Pending_Coordinator, Pending_Principal, Approved, Rejected

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_leave_requests');
    }
};
