<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\ClassManagement;
use App\Models\R26ClassManagement;
use App\Models\BatchSubject;
use Illuminate\Support\Facades\DB;

class TestDrawingBatchSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure staff profiles exist
        $staff1 = StaffProfile::firstOrCreate(
            ['mobile_no' => '9000000005'],
            [
                'name' => 'Faculty Member',
                'email' => 'faculty@carmelpoly.in',
                'branch' => 'ME',
                'designation' => 'Lecturer',
                'password' => 'faculty123',
                'account_status' => 'Approved',
            ]
        );

        $staff2 = StaffProfile::firstOrCreate(
            ['mobile_no' => '5000000002'],
            [
                'name' => 'HOD Mechanical Engineering',
                'email' => 'hod.me@carmelpoly.in',
                'branch' => 'ME',
                'designation' => 'HOD',
                'password' => 'hod123',
                'account_status' => 'Approved',
            ]
        );

        // 2. Create R26 Classroom
        $classId = 'ME_2026_2029';
        ClassManagement::firstOrCreate(
            ['classroom_id' => $classId],
            [
                'branch' => 'ME',
                'batch_year' => 2026,
                'tutor_mobile_no' => '5000000002',
                'mentor_mobile_no' => '5000000002',
            ]
        );

        R26ClassManagement::firstOrCreate(
            ['classroom_id' => $classId],
            [
                'branch' => 'ME',
                'batch_year' => 2026,
                'current_semester' => 1,
                'tutor_mobile_no' => '5000000002',
                'mentor_mobile_no' => '5000000002',
            ]
        );

        // 3. Create R26 Drawing Lab Subject
        $subject = BatchSubject::firstOrCreate(
            [
                'classroom_id' => $classId,
                'subject_code' => 'ME-2005D'
            ],
            [
                'semester' => 1,
                'subject_name' => 'Engineering Graphics & CAD Drawing Lab',
                'subject_type' => 'Drawing Lab',
                'syllabus_revision_code' => 'REV2026',
            ]
        );

        // 4. Assign Staff
        DB::table('subject_staff_assignments')->updateOrInsert(
            ['batch_subject_id' => $subject->id, 'staff_mobile_no' => '9000000005'],
            ['updated_at' => now(), 'created_at' => now()]
        );
        DB::table('subject_staff_assignments')->updateOrInsert(
            ['batch_subject_id' => $subject->id, 'staff_mobile_no' => '5000000002'],
            ['updated_at' => now(), 'created_at' => now()]
        );

        // 5. Create Enrolled Students
        for ($i = 1; $i <= 5; $i++) {
            Student::firstOrCreate(
                ['reg_no' => '26ME100' . $i],
                [
                    'adm_no' => 'ADM2026100' . $i,
                    'name' => 'Student ' . $i . ' (Mechanical)',
                    'email' => 'student' . $i . '.me@carmelpoly.in',
                    'password' => 'student123',
                    'phone' => '987654321' . $i,
                    'branch' => 'ME',
                    'admission_year' => 2026,
                    'admission_type' => 'Regular',
                    'classroom_id' => $classId,
                    'semester' => 1,
                    'roll_no' => $i,
                    'status' => 'Approved',
                    'sbte_reg_no' => 'SBTE26ME00' . $i
                ]
            );
        }

        echo "Test Drawing Batch Seeded Successfully!\n";
        echo "Batch Classroom: {$classId}\n";
        echo "Subject Name: Engineering Graphics & CAD Drawing Lab (ME-2005D)\n";
        echo "Subject ID: {$subject->id}\n";
        echo "Direct Route: /r26/classroom/drawing/{$subject->id}\n";
    }
}
