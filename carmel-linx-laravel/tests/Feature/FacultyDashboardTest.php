<?php

namespace Tests\Feature;

use Tests\TestCase;

class FacultyDashboardTest extends TestCase
{
    /**
     * Test HOD dashboard renders successfully.
     */
    public function test_hod_dashboard_renders_for_hod_role(): void
    {
        $response = $this->withSession([
            'userId' => 'HOD001',
            'userRole' => 'HOD',
            'userName' => 'Head of Department'
        ])->get('/dashboard/hod');

        $response->assertStatus(200);
        $response->assertSee('Head of Department Desk');
        $response->assertSee('id="panelBatches"', false);
        $response->assertSee('id="panelDirectory"', false);
        $response->assertSee('id="panelSubjects"', false);
    }

    /**
     * Test Principal viewing HOD department dashboard via branch override.
     */
    public function test_principal_department_view_renders(): void
    {
        $response = $this->withSession([
            'userId' => '9946847236',
            'userRole' => 'Principal',
            'userName' => 'Fr. Antony Varghese CMI'
        ])->get('/dashboard/principal/department/ME');

        $response->assertStatus(200);
        $response->assertSee('Department Overview (ME)');
    }

    /**
     * Test Lecturer dashboard renders successfully.
     */
    public function test_lecturer_dashboard_renders_for_lecturer_role(): void
    {
        $response = $this->withSession([
            'userId' => 'LEC001',
            'userRole' => 'Lecturer',
            'userName' => 'Faculty Lecturer'
        ])->get('/dashboard/lecturer');

        $response->assertStatus(200);
        $response->assertSee('Faculty Batches &amp; Classroom', false);
        $response->assertSee('id="panelDashboard"', false);
        $response->assertSee('id="panelClassroom"', false);
    }

    /**
     * Test Tutor dashboard renders successfully.
     */
    public function test_tutor_dashboard_renders_for_tutor_role(): void
    {
        $response = $this->withSession([
            'userId' => 'TUTOR001',
            'userRole' => 'Lecturer',
            'userName' => 'Class Tutor'
        ])->get('/dashboard/tutor');

        $response->assertStatus(200);
        $response->assertSee('Tutor Console');
        $response->assertSee('id="panelRoster"', false);
        $response->assertSee('id="panelMentoring"', false);
    }

    /**
     * Test Academic Coordinator dashboard renders successfully.
     */
    public function test_academic_coordinator_dashboard_renders(): void
    {
        $response = $this->withSession([
            'userId' => 'ACAD001',
            'userRole' => 'Academic_Coordinator',
            'userName' => 'Academic Coordinator'
        ])->get('/dashboard/academic-coordinator');

        $response->assertStatus(200);
        $response->assertSee('Academic Coordinator Portal');
        $response->assertSee('id="panelDashboard"', false);
        $response->assertSee('id="panelDirectory"', false);
    }

    /**
     * Test General Coordinator (Aided) dashboard renders successfully.
     */
    public function test_general_coordinator_aided_dashboard_renders(): void
    {
        $response = $this->withSession([
            'userId' => 'GEN_AIDED001',
            'userRole' => 'Gen_Dept_Coordinator_Aided',
            'userName' => 'General Coordinator Aided'
        ])->get('/dashboard/general-coordinator-aided');

        $response->assertStatus(200);
        $response->assertSee('General Coordinator (Aided) Desk');
        $response->assertSee('id="panelDashboard"', false);
        $response->assertSee('id="panelDirectory"', false);
    }

    /**
     * Test General Coordinator (Self-Finance) dashboard renders successfully.
     */
    public function test_general_coordinator_sf_dashboard_renders(): void
    {
        $response = $this->withSession([
            'userId' => 'GEN_SF001',
            'userRole' => 'Gen_Dept_Coordinator_Self_Finance',
            'userName' => 'General Coordinator SF'
        ])->get('/dashboard/general-coordinator-sf');

        $response->assertStatus(200);
        $response->assertSee('General Coordinator (Self-Finance) Desk');
        $response->assertSee('id="panelDashboard"', false);
        $response->assertSee('id="panelDirectory"', false);
    }

    /**
     * Test unauthorized access redirects.
     */
    public function test_unauthorized_student_cannot_access_hod_dashboard(): void
    {
        $response = $this->withSession([
            'userId' => 'STUDENT001',
            'userRole' => 'Student'
        ])->get('/dashboard/hod');

        $response->assertStatus(302);
    }
}
