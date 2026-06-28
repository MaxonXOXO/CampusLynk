<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tutor_diaries', function (Blueprint $table) {
            // Track if a student or a staff member wrote the entry
            $table->enum('entry_source', ['Staff', 'Student'])->default('Staff')->after('logged_by');
            // Approval workflow for student-authored entries
            $table->enum('approval_status', ['Pending', 'Approved', 'Rejected'])->default('Approved')->after('entry_source');
            $table->string('approved_by', 15)->nullable()->after('approval_status');
            $table->text('student_remarks')->nullable()->after('approved_by'); // Student's own notes/self-reflection

            $table->foreign('approved_by')->references('mobile_no')->on('staff_profiles')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tutor_diaries', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['entry_source', 'approval_status', 'approved_by', 'student_remarks']);
        });
    }
};
