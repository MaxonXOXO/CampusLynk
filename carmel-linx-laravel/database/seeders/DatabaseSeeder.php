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
            'branch' => 'GEN',
            'designation' => 'Super_Admin',
            'password' => 'admin123',
            'account_status' => 'Approved',
        ]);

        StaffProfile::create([
            'mobile_no' => '9000000001',
            'name' => 'Dr. Principal',
            'email' => 'principal@carmelpoly.in',
            'branch' => 'GEN',
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
            'branch' => 'GEN',
            'designation' => 'Admin',
            'password' => 'admin123',
            'account_status' => 'Approved',
        ]);

        StaffProfile::create([
            'mobile_no' => '9000000005',
            'name' => 'Faculty Member',
            'email' => 'faculty@carmelpoly.in',
            'branch' => 'EL',
            'designation' => 'Faculty',
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
