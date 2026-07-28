<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\ClassManagement;
use App\Models\R26ClassManagement;
use App\Models\BatchSubject;
use App\Models\R26PracticumCourseFile;
use Illuminate\Support\Facades\DB;

class TestPracticumBatchSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure staff profiles exist
        $staff1 = StaffProfile::firstOrCreate(
            ['mobile_no' => '9000000005'],
            [
                'name' => 'Faculty Member',
                'email' => 'faculty@carmelpoly.in',
                'branch' => 'CT',
                'designation' => 'Lecturer',
                'password' => 'faculty123',
                'account_status' => 'Approved',
            ]
        );

        $staff2 = StaffProfile::firstOrCreate(
            ['mobile_no' => '5000000002'],
            [
                'name' => 'HOD Computer Engineering',
                'email' => 'hod.ct@carmelpoly.in',
                'branch' => 'CT',
                'designation' => 'HOD',
                'password' => 'hod123',
                'account_status' => 'Approved',
            ]
        );

        // 2. Create R26 Classroom
        $classId = 'CT_2026_2029';
        ClassManagement::firstOrCreate(
            ['classroom_id' => $classId],
            [
                'branch' => 'CT',
                'batch_year' => 2026,
                'tutor_mobile_no' => '5000000002',
                'mentor_mobile_no' => '5000000002',
            ]
        );

        R26ClassManagement::firstOrCreate(
            ['classroom_id' => $classId],
            [
                'branch' => 'CT',
                'batch_year' => 2026,
                'current_semester' => 1,
                'tutor_mobile_no' => '5000000002',
                'mentor_mobile_no' => '5000000002',
            ]
        );

        // 3. Create R26 Practicum Subject
        $subject = BatchSubject::firstOrCreate(
            [
                'classroom_id' => $classId,
                'subject_code' => 'CT-2004P'
            ],
            [
                'semester' => 1,
                'subject_name' => 'Computer Programming & Networking Practicum',
                'subject_type' => 'Practicum',
                'syllabus_revision_code' => 'REV2026',
            ]
        );

        // 4. Create R26 Practicum Course File entry
        R26PracticumCourseFile::firstOrCreate(
            ['batch_subject_id' => $subject->id],
            [
                'course_code' => 'CT-2004P',
                'course_title' => 'Computer Programming & Networking Practicum',
                'type_of_course' => 'Practicum',
                'credits' => 4.0,
                'teaching_scheme' => '2:0:4:0',
                'cie_marks' => 60,
                'ese_marks' => 40,
                'contact_hours' => 90,
                'parsed_cos' => [
                    ['id' => 'CO1', 'description' => 'Understand fundamental programming concepts and data structures.', 'cognitive_level' => 'Understand'],
                    ['id' => 'CO2', 'description' => 'Develop modular programs and implement network socket connections.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO3', 'description' => 'Configure LAN hardware, routers, switches, and network protocols.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO4', 'description' => 'Perform troubleshooting, security auditing, and hardware diagnostics.', 'cognitive_level' => 'Analyze']
                ]
            ]
        );

        // 5. Assign Staff
        DB::table('subject_staff_assignments')->updateOrInsert(
            ['batch_subject_id' => $subject->id, 'staff_mobile_no' => '9000000005'],
            ['updated_at' => now(), 'created_at' => now()]
        );
        DB::table('subject_staff_assignments')->updateOrInsert(
            ['batch_subject_id' => $subject->id, 'staff_mobile_no' => '5000000002'],
            ['updated_at' => now(), 'created_at' => now()]
        );

        // 6. Create Enrolled Students
        for ($i = 1; $i <= 5; $i++) {
            Student::firstOrCreate(
                ['reg_no' => '26CT100' . $i],
                [
                    'adm_no' => 'ADM2026200' . $i,
                    'name' => 'Student ' . $i . ' (Computer Engg)',
                    'email' => 'student' . $i . '.ct@carmelpoly.in',
                    'password' => 'student123',
                    'phone' => '987654322' . $i,
                    'branch' => 'CT',
                    'admission_year' => 2026,
                    'admission_type' => 'Regular',
                    'classroom_id' => $classId,
                    'semester' => 1,
                    'status' => 'Approved',
                    'sbte_reg_no' => 'SBTE2026CT00' . $i,
                ]
            );
        }

        echo "Test R26 Practicum Batch Seeded Successfully!\n";
        echo "Batch Subject ID: " . $subject->id . " | Subject: CT-2004P Computer Programming & Networking Practicum\n";
    }
}
