<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('performed_by', 50)->nullable(); // Mobile number or reg_no of executor, or 'System'
            $table->string('performed_by_name', 255)->nullable();
            $table->string('target_id', 50); // Mobile number of staff, or reg_no of student
            $table->string('target_name', 255);
            $table->string('action', 100); // e.g. 'Approved', 'Suspended', 'Reset Password', 'Registered', 'Role Changed', 'Deleted'
            $table->text('details')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            // Indexes for fast lookup
            $table->index('performed_by');
            $table->index('target_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
