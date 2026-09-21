<?php

namespace Tests\Unit;

use App\Models\AuditLog;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    /**
     * Test that AuditLog model can be instantiated and attributes assigned via fillable.
     */
    public function test_audit_log_model_can_be_instantiated_with_fillable_attributes(): void
    {
        $log = new AuditLog([
            'performed_by' => '9876543210',
            'performed_by_name' => 'Dr. Administrator',
            'target_id' => 'STU-2026-001',
            'target_name' => 'John Doe',
            'action' => 'Approved',
            'details' => 'Student registration verified and approved.',
            'ip_address' => '192.168.1.100',
        ]);

        $this->assertInstanceOf(AuditLog::class, $log);
        $this->assertEquals('9876543210', $log->performed_by);
        $this->assertEquals('Dr. Administrator', $log->performed_by_name);
        $this->assertEquals('STU-2026-001', $log->target_id);
        $this->assertEquals('John Doe', $log->target_name);
        $this->assertEquals('Approved', $log->action);
        $this->assertEquals('Student registration verified and approved.', $log->details);
        $this->assertEquals('192.168.1.100', $log->ip_address);
    }

    /**
     * Test that the underlying table name is 'audit_logs'.
     */
    public function test_audit_log_table_name_is_audit_logs(): void
    {
        $log = new AuditLog();
        $this->assertEquals('audit_logs', $log->getTable());
    }

    /**
     * Test that the fillable whitelist strictly matches the approved specification.
     */
    public function test_audit_log_fillable_attributes_match_specification(): void
    {
        $expectedFillable = [
            'performed_by',
            'performed_by_name',
            'target_id',
            'target_name',
            'action',
            'details',
            'ip_address',
        ];

        $log = new AuditLog();
        $this->assertEquals($expectedFillable, $log->getFillable());
    }

    /**
     * Test that timestamps are enabled by default on the model.
     */
    public function test_audit_log_timestamps_are_enabled(): void
    {
        $log = new AuditLog();
        $this->assertTrue($log->usesTimestamps());
    }

    /**
     * Test mass assignment protection against unapproved attributes.
     */
    public function test_audit_log_mass_assignment_protection(): void
    {
        $log = new AuditLog([
            'id' => 9999,
            'is_admin' => true,
            'unauthorized_field' => 'injection_attempt',
            'action' => 'Suspended',
            'target_id' => 'STU-002',
            'target_name' => 'Jane Doe',
        ]);

        $this->assertNull($log->id);
        $this->assertNull($log->is_admin);
        $this->assertNull($log->unauthorized_field);
        $this->assertEquals('Suspended', $log->action);
        $this->assertEquals('STU-002', $log->target_id);
    }

    /**
     * Test architectural boundary: AuditLog model does not modify or couple to User model in M1.1.
     */
    public function test_audit_log_user_model_isolation_boundary(): void
    {
        $log = new AuditLog();
        // AuditLog uses target_id / performed_by strings rather than tight foreign key model coupling in M1.1
        $this->assertFalse(method_exists($log, 'user'));
    }
}
