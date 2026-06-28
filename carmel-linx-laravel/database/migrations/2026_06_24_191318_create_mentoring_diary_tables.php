<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update existing students table with new profile fields
        Schema::table('students', function (Blueprint $table) {
            $table->string('annual_income', 50)->nullable();
            $table->enum('residential_status', ['Day Scholar', 'Hosteller'])->default('Day Scholar');
            $table->string('guardian_name', 100)->nullable();
            $table->text('guardian_address')->nullable();
            $table->string('guardian_relationship', 50)->nullable();
            $table->string('guardian_mobile', 20)->nullable();
            $table->text('scholarships')->nullable();
            $table->boolean('is_fee_waiver')->default(false);
        });

        // 2. Family Details Table
        Schema::create('student_family_details', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 50);
            $table->string('name', 100);
            $table->string('relationship', 50);
            $table->string('education', 100)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('contact_no', 20)->nullable();
            $table->timestamps();

            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
        });

        // 3. Prior Education Table
        Schema::create('student_prior_education', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 50);
            $table->string('course', 50); // SSLC, +2, VHSE, etc.
            $table->string('institution', 150);
            $table->string('year_of_completion', 10)->nullable();
            $table->string('maths_marks', 20)->nullable();
            $table->string('physics_marks', 20)->nullable();
            $table->string('chemistry_marks', 20)->nullable();
            $table->string('total_percentage', 20)->nullable();
            $table->timestamps();

            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
        });

        // 4. Fee Records Table
        Schema::create('student_fee_records', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 50);
            $table->string('academic_year', 20);
            $table->decimal('fees_to_pay', 10, 2)->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->date('date_paid')->nullable();
            $table->decimal('total_paid', 10, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
        });

        // 5. Extra-Curricular Activities Table
        Schema::create('extracurricular_activities', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 50);
            $table->integer('semester');
            $table->string('activity_name', 150);
            $table->string('achievement', 100)->nullable();
            $table->integer('points_awarded')->default(0);
            $table->string('verified_by', 15)->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->timestamps();

            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            $table->foreign('verified_by')->references('mobile_no')->on('staff_profiles')->onDelete('set null');
        });

        // 6. Leave Records Table
        Schema::create('leave_records', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 50);
            $table->integer('semester');
            $table->date('leave_date');
            $table->string('reason', 255);
            $table->boolean('parent_informed')->default(false);
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->string('approved_by', 15)->nullable();
            $table->timestamps();

            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            $table->foreign('approved_by')->references('mobile_no')->on('staff_profiles')->onDelete('set null');
        });

        // 7. Disciplinary Actions Table
        Schema::create('disciplinary_actions', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 50);
            $table->date('date');
            $table->text('description');
            $table->text('action_taken')->nullable();
            $table->string('reported_by', 15)->nullable();
            $table->timestamps();

            $table->foreign('reg_no')->references('reg_no')->on('students')->onDelete('cascade');
            $table->foreign('reported_by')->references('mobile_no')->on('staff_profiles')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disciplinary_actions');
        Schema::dropIfExists('leave_records');
        Schema::dropIfExists('extracurricular_activities');
        Schema::dropIfExists('student_fee_records');
        Schema::dropIfExists('student_prior_education');
        Schema::dropIfExists('student_family_details');

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'annual_income',
                'residential_status',
                'guardian_name',
                'guardian_address',
                'guardian_relationship',
                'guardian_mobile',
                'scholarships',
                'is_fee_waiver'
            ]);
        });
    }
};
