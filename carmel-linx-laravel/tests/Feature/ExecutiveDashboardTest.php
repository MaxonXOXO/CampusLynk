<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExecutiveDashboardTest extends TestCase
{
    /**
     * Test admin dashboard route renders successfully for Admin role.
     */
    public function test_admin_dashboard_renders_for_admin_role(): void
    {
        $response = $this->withSession([
            'userId' => 'ADMIN001',
            'userRole' => 'Admin',
            'userName' => 'System Administrator'
        ])->get('/dashboard/admin');

        $response->assertStatus(200);
        $response->assertSee('CampusLynk - Executive Control Desk');
        $response->assertSee('id="panelDashboard"', false);
        $response->assertSee('id="panelDirectory"', false);
        $response->assertSee('id="panelBackups"', false);
    }

    /**
     * Test superadmin dashboard renders successfully for Super_Admin role.
     */
    public function test_superadmin_dashboard_renders_for_superadmin_role(): void
    {
        $response = $this->withSession([
            'userId' => 'SUPER001',
            'userRole' => 'Super_Admin',
            'userName' => 'Root Administrator'
        ])->get('/dashboard/superadmin');

        $response->assertStatus(200);
        $response->assertSee('CampusLynk - Executive Control Desk');
        $response->assertSee('id="panelDashboard"', false);
    }

    /**
     * Test principal dashboard renders successfully for Principal role.
     */
    public function test_principal_dashboard_renders_for_principal_role(): void
    {
        $response = $this->withSession([
            'userId' => '9946847236',
            'userRole' => 'Principal',
            'userName' => 'Fr. Antony Varghese CMI'
        ])->get('/dashboard/principal');

        $response->assertStatus(200);
        $response->assertSee('CampusLynk - Executive Control Desk');
        $response->assertSee('id="panelDashboard"', false);
    }

    /**
     * Test chairman dashboard renders successfully for Chairman role.
     */
    public function test_chairman_dashboard_renders_for_chairman_role(): void
    {
        $response = $this->withSession([
            'userId' => 'CHAIR001',
            'userRole' => 'Chairman',
            'userName' => 'Executive Chairman'
        ])->get('/dashboard/chairman');

        $response->assertStatus(200);
        $response->assertSee('CampusLynk - Chairman Desk');
        $response->assertSee('id="panelDashboard"', false);
        $response->assertSee('id="panelDirectory"', false);
        $response->assertSee('id="panelAudit"', false);
    }

    /**
     * Test unauthorized role redirects away from admin dashboard.
     */
    public function test_unauthorized_role_redirects_from_admin_dashboard(): void
    {
        $response = $this->withSession([
            'userId' => 'STUDENT001',
            'userRole' => 'Student'
        ])->get('/dashboard/admin');

        $response->assertStatus(302);
    }

    /**
     * Test guest user redirects to login gate.
     */
    public function test_guest_redirects_to_login(): void
    {
        $response = $this->get('/dashboard/admin');
        $response->assertStatus(302);
    }
}
