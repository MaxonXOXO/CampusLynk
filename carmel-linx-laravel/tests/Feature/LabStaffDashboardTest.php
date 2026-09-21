<?php

namespace Tests\Feature;

use Tests\TestCase;

class LabStaffDashboardTest extends TestCase
{
    /**
     * Test Demonstrator dashboard renders successfully.
     */
    public function test_demonstrator_dashboard_renders_for_demonstrator_role(): void
    {
        $response = $this->withSession([
            'userId' => 'DEMO001',
            'userRole' => 'Demonstrator',
            'userName' => 'Lab Demonstrator'
        ])->get('/dashboard/demonstrator');

        $response->assertStatus(200);
        $response->assertSee('Demonstrator Console');
        $response->assertSee('id="panelDashboard"', false);
    }

    /**
     * Test Trade Instructor dashboard renders successfully.
     */
    public function test_trade_instructor_dashboard_renders_for_trade_instructor_role(): void
    {
        $response = $this->withSession([
            'userId' => 'TRADE001',
            'userRole' => 'Trade_Instructor',
            'userName' => 'Workshop Trade Instructor'
        ])->get('/dashboard/tradeinstructor');

        $response->assertStatus(200);
        $response->assertSee('Trade &amp; Workshop Tasks', false);
        $response->assertSee('id="panelDashboard"', false);
    }

    /**
     * Test Workshop Superintendent dashboard renders successfully.
     */
    public function test_workshop_superintendent_dashboard_renders_for_workshop_role(): void
    {
        $response = $this->withSession([
            'userId' => 'WS001',
            'userRole' => 'Workshop_Superintendent',
            'userName' => 'Workshop Superintendent'
        ])->get('/dashboard/workshop');

        $response->assertStatus(200);
        $response->assertSee('Workshop Superintendent Desk');
        $response->assertSee('id="panelOverview"', false);
        $response->assertSee('id="panelStaff"', false);
        $response->assertSee('id="panelStudents"', false);
    }

    /**
     * Test unauthorized student cannot access trade instructor dashboard.
     */
    public function test_unauthorized_student_cannot_access_tradeinstructor_dashboard(): void
    {
        $response = $this->withSession([
            'userId' => 'STUDENT001',
            'userRole' => 'Student'
        ])->get('/dashboard/tradeinstructor');

        $response->assertStatus(302);
    }
}
