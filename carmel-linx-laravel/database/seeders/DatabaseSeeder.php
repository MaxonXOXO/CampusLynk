<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\ClassManagement;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Staff Profiles (so foreign keys exist)
        StaffProfile::create([
            'mobile_no' => '9000000000',
            'name' => 'Super Admin User',
            'email' => 'superadmin@carmelpoly.in',
            'branch' => 'Administration',
            'designation' => 'Super_Admin',
            'password' => 'admin123',
            'account_status' => 'Approved',
        ]);

        StaffProfile::create([
            'mobile_no' => '9000000001',
            'name' => 'Dr. Principal',
            'email' => 'principal@carmelpoly.in',
            'branch' => 'Administration',
            'designation' => 'Principal',
            'password' => 'admin123',
            'account_status' => 'Approved',
        ]);

        StaffProfile::create([
            'mobile_no' => '9000000002',
            'name' => 'HOD Electronics',
            'email' => 'hod.el@carmelpoly.in',
            'branch' => 'EL',
            'designation' => 'HOD',
            'password' => 'hod123',
            'account_status' => 'Approved',
        ]);

        StaffProfile::create([
            'mobile_no' => '9000000003',
            'name' => 'Tutor Electronics',
            'email' => 'tutor.el@carmelpoly.in',
            'branch' => 'EL',
            'designation' => 'Tutor',
            'password' => 'tutor123',
            'account_status' => 'Approved',
        ]);

        StaffProfile::create([
            'mobile_no' => '9000000004',
            'name' => 'Academic Admin',
            'email' => 'admin@carmelpoly.in',
            'branch' => 'Administration',
            'designation' => 'Admin',
            'password' => 'admin123',
            'account_status' => 'Approved',
        ]);

        StaffProfile::create([
            'mobile_no' => '9999999999',
            'name' => 'Chairman',
            'email' => 'chairman@carmelpoly.in',
            'branch' => 'Administration',
            'designation' => 'Chairman',
            'password' => 'chairman',
            'account_status' => 'Approved',
        ]);

        StaffProfile::create([
            'mobile_no' => '9000000005',
            'name' => 'Faculty Member',
            'email' => 'faculty@carmelpoly.in',
            'branch' => 'EL',
            'designation' => 'Lecturer',
            'password' => 'faculty123',
            'account_status' => 'Approved',
        ]);

        StaffProfile::create([
            'mobile_no' => '9000000006',
            'name' => 'Lab Demonstrator',
            'email' => 'demonstrator@carmelpoly.in',
            'branch' => 'EL',
            'designation' => 'Demonstrator',
            'password' => 'demo123',
            'account_status' => 'Approved',
        ]);

        StaffProfile::create([
            'mobile_no' => '9000000007',
            'name' => 'Trade Instructor User',
            'email' => 'instructor@carmelpoly.in',
            'branch' => 'EL',
            'designation' => 'Trade_Instructor',
            'password' => 'trade123',
            'account_status' => 'Approved',
        ]);



        // Seed 5 HODs as requested:
        // Computer (CT): 8000000002
        StaffProfile::create([
            'mobile_no' => '8000000002',
            'name' => 'HOD Computer Engineering',
            'email' => 'hod.ct@carmelpoly.in',
            'branch' => 'CT',
            'designation' => 'HOD',
            'password' => 'hod123',
            'account_status' => 'Approved',
        ]);

        // Automobile (AU): 7000000002
        StaffProfile::create([
            'mobile_no' => '7000000002',
            'name' => 'HOD Automobile Engineering',
            'email' => 'hod.au@carmelpoly.in',
            'branch' => 'AU',
            'designation' => 'HOD',
            'password' => 'hod123',
            'account_status' => 'Approved',
        ]);

        // Civil (CE): 6000000002
        StaffProfile::create([
            'mobile_no' => '6000000002',
            'name' => 'HOD Civil Engineering',
            'email' => 'hod.ce@carmelpoly.in',
            'branch' => 'CE',
            'designation' => 'HOD',
            'password' => 'hod123',
            'account_status' => 'Approved',
        ]);

        // Mechanical (ME): 5000000002
        StaffProfile::create([
            'mobile_no' => '5000000002',
            'name' => 'HOD Mechanical Engineering',
            'email' => 'hod.me@carmelpoly.in',
            'branch' => 'ME',
            'designation' => 'HOD',
            'password' => 'hod123',
            'account_status' => 'Approved',
        ]);

        // Electrical & Electronics (EEE): 4000000002
        StaffProfile::create([
            'mobile_no' => '4000000002',
            'name' => 'HOD Electrical Engineering',
            'email' => 'hod.eee@carmelpoly.in',
            'branch' => 'EEE',
            'designation' => 'HOD',
            'password' => 'hod123',
            'account_status' => 'Approved',
        ]);

        // Seed departmental staff in each of the 6 branches (EL, CT, AU, CE, ME, EEE)
        // Each has HOD (1) + Lecturers (5) + Demonstrators (2) + Tradesman (1) + Trade Instructor (1) = 10 staff per department
        $branches = ['EL', 'CT', 'AU', 'CE', 'ME', 'EEE'];
        $baseMobile = 9100000000;
        foreach ($branches as $brIdx => $branch) {
            // 5 Lecturers
            for ($i = 1; $i <= 5; $i++) {
                $mob = $baseMobile + ($brIdx * 20) + $i;
                StaffProfile::create([
                    'mobile_no' => (string)$mob,
                    'name' => "Lecturer {$i} {$branch}",
                    'email' => "lecturer{$i}.{$branch}@carmelpoly.in",
                    'branch' => $branch,
                    'designation' => 'Lecturer',
                    'password' => 'lecturer123',
                    'account_status' => 'Approved',
                ]);
            }
            // 3 Demonstrators
            for ($i = 1; $i <= 3; $i++) {
                $mob = $baseMobile + ($brIdx * 20) + 5 + $i;
                StaffProfile::create([
                    'mobile_no' => (string)$mob,
                    'name' => "Demonstrator {$i} {$branch}",
                    'email' => "demonstrator{$i}.{$branch}@carmelpoly.in",
                    'branch' => $branch,
                    'designation' => 'Demonstrator',
                    'password' => 'demo123',
                    'account_status' => 'Approved',
                ]);
            }
            // 1 Tradesman
            $mob = $baseMobile + ($brIdx * 20) + 9;
            StaffProfile::create([
                'mobile_no' => (string)$mob,
                'name' => "Tradesman 1 {$branch}",
                'email' => "tradesman1.{$branch}@carmelpoly.in",
                'branch' => $branch,
                'designation' => 'Tradesman',
                'password' => 'tradesman123',
                'account_status' => 'Approved',
            ]);
            // 1 Trade Instructor
            $mob = $baseMobile + ($brIdx * 20) + 10;
            StaffProfile::create([
                'mobile_no' => (string)$mob,
                'name' => "Trade Instructor 1 {$branch}",
                'email' => "tradeinstructor1.{$branch}@carmelpoly.in",
                'branch' => $branch,
                'designation' => 'Trade_Instructor',
                'password' => 'trade123',
                'account_status' => 'Approved',
            ]);
        }

        // Seed General Departments:
        // GEN_AIDED: 4 lecturers total, with 1 being Coordinator
        StaffProfile::create([
            'mobile_no' => '9000000101',
            'name' => 'General Aided Coord',
            'email' => 'aided.coord@carmelpoly.in',
            'branch' => 'GEN_AIDED',
            'designation' => 'Gen_Dept_Coordinator_Aided',
            'password' => 'staff123',
            'account_status' => 'Approved',
        ]);
        for ($i = 1; $i <= 3; $i++) {
            StaffProfile::create([
                'mobile_no' => "900000010" . (1 + $i),
                'name' => "General Aided Lecturer {$i}",
                'email' => "aided.lecturer{$i}@carmelpoly.in",
                'branch' => 'GEN_AIDED',
                'designation' => 'Lecturer',
                'password' => 'staff123',
                'account_status' => 'Approved',
            ]);
        }

        // GEN_AIDED Physical Instructor
        StaffProfile::firstOrCreate(
            ['mobile_no' => '9000000105'],
            [
                'name' => 'Physical Instructor Aided',
                'email' => 'physical.aided@carmelpoly.in',
                'branch' => 'GEN_AIDED',
                'designation' => 'Physical_Instructor',
                'password' => 'staff123',
                'account_status' => 'Approved',
            ]
        );

        // GEN_SF: 4 lecturers total, with 1 being Coordinator
        StaffProfile::create([
            'mobile_no' => '9000000201',
            'name' => 'General SF Coord',
            'email' => 'sf.coord@carmelpoly.in',
            'branch' => 'GEN_SF',
            'designation' => 'Gen_Dept_Coordinator_Self_Finance',
            'password' => 'staff123',
            'account_status' => 'Approved',
        ]);
        for ($i = 1; $i <= 3; $i++) {
            StaffProfile::create([
                'mobile_no' => "900000020" . (1 + $i),
                'name' => "General SF Lecturer {$i}",
                'email' => "sf.lecturer{$i}@carmelpoly.in",
                'branch' => 'GEN_SF',
                'designation' => 'Lecturer',
                'password' => 'staff123',
                'account_status' => 'Approved',
            ]);
        }
        // GEN_SF Physical Instructor
        StaffProfile::firstOrCreate(
            ['mobile_no' => '9000000205'],
            [
                'name' => 'Physical Instructor SF',
                'email' => 'physical.sf@carmelpoly.in',
                'branch' => 'GEN_SF',
                'designation' => 'Physical_Instructor',
                'password' => 'staff123',
                'account_status' => 'Approved',
            ]
        );

        // Seed specific mechanical workshop staff:
        // 1 workshop superintendent
        StaffProfile::create([
            'mobile_no' => '5000000099',
            'name' => 'Workshop Superintendent User',
            'email' => 'workshop.superintendent@carmelpoly.in',
            'branch' => 'ME',
            'designation' => 'Workshop_Superintendent',
            'password' => 'workshop123',
            'account_status' => 'Approved',
        ]);
        // 2 workshop instructors
        for ($i = 1; $i <= 2; $i++) {
            StaffProfile::create([
                'mobile_no' => "500000010{$i}",
                'name' => "Workshop Instructor {$i}",
                'email' => "workshop.inst{$i}@carmelpoly.in",
                'branch' => 'ME',
                'designation' => 'Workshop_Instructor',
                'password' => 'workshop123',
                'account_status' => 'Approved',
            ]);
        }
        // 2 trade instructors
        for ($i = 1; $i <= 2; $i++) {
            StaffProfile::create([
                'mobile_no' => "500000011{$i}",
                'name' => "Workshop Trade Instructor {$i}",
                'email' => "workshop.trade{$i}@carmelpoly.in",
                'branch' => 'ME',
                'designation' => 'Trade_Instructor',
                'password' => 'trade123',
                'account_status' => 'Approved',
            ]);
        }
        // 2 lab assistants
        for ($i = 1; $i <= 2; $i++) {
            StaffProfile::create([
                'mobile_no' => "500000012{$i}",
                'name' => "Workshop Lab Assistant {$i}",
                'email' => "workshop.lab{$i}@carmelpoly.in",
                'branch' => 'ME',
                'designation' => 'Laboratory_Assistant',
                'password' => 'lab123',
                'account_status' => 'Approved',
            ]);
        }

        // 2. Create Classrooms (referencing Tutor mobile_no)
        ClassManagement::create([
            'classroom_id' => 'EL_2025_2028',
            'branch' => 'EL',
            'batch_year' => 2025,
            'tutor_mobile_no' => '9000000003',
            'mentor_mobile_no' => '9000000003',
        ]);

        // 3. Create Students (referencing classroom_id)
        Student::create([
            'reg_no' => '25EL1001',
            'adm_no' => 'A20251001',
            'name' => 'Test Student',
            'email' => 'student@carmelpoly.in',
            'password' => 'student123',
            'phone' => '9999999999',
            'branch' => 'EL',
            'admission_year' => 2025,
            'admission_type' => 'Regular',
            'classroom_id' => 'EL_2025_2028',
            'status' => 'Approved',
        ]);
    }
}
