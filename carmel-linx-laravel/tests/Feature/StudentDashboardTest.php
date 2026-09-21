<?php

namespace Tests\Feature;

use Tests\TestCase;

class StudentDashboardTest extends TestCase
{
    /**
     * Test Student dashboard renders successfully for Student role.
     */
    public function test_student_dashboard_renders_for_student_role(): void
    {
        $response = $this->withSession([
            'userId' => '210101',
            'userRole' => 'Student',
            'userName' => 'Adithya Kumar'
        ])->get('/dashboard/student');

        $response->assertStatus(200);
        $response->assertSee('Student Learning Portal');
        $response->assertSee('id="panelExams"', false);
        $response->assertSee('id="panelMarks"', false);
        $response->assertSee('id="panelMentoring"', false);
    }

    /**
     * Test /student/attendance redirects to student dashboard with attendance tab.
     */
    public function test_student_attendance_redirects_to_tab(): void
    {
        $response = $this->withSession([
            'userId' => '210101',
            'userRole' => 'Student'
        ])->get('/student/attendance');

        $response->assertRedirect('/dashboard/student?tab=attendance');
    }

    /**
     * Test /student/mentoring-diary redirects to student dashboard with mentoring tab.
     */
    public function test_student_mentoring_diary_redirects_to_tab(): void
    {
        $response = $this->withSession([
            'userId' => '210101',
            'userRole' => 'Student'
        ])->get('/student/mentoring-diary');

        $response->assertRedirect('/dashboard/student?tab=mentoring');
    }

    /**
     * Test unauthorized non-student cannot access student dashboard.
     */
    public function test_unauthorized_staff_cannot_access_student_dashboard(): void
    {
        $response = $this->withSession([
            'userId' => 'LEC001',
            'userRole' => 'Lecturer'
        ])->get('/dashboard/student');

        $response->assertStatus(302);
    }
}
