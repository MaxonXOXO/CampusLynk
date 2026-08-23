<?php

return [
    'role' => 'principal',
    'inherits' => null, // Explicit: Principal does NOT automatically inherit Super Admin permissions
    'subtitle' => 'Principal Desk',
    'items' => [
        [
            'id' => 'dashboard',
            'label' => 'Dashboard Overview',
            'icon' => 'layout-dashboard',
            'onclick' => "handleAdminSidebarNav('dashboard')",
        ],
        [
            'id' => 'all_timetables',
            'label' => 'All-Dept Timetables',
            'icon' => 'calendar-days',
            'onclick' => "handleAdminSidebarNav('all_timetables')",
        ],
        [
            'id' => 'directory',
            'label' => 'User Directory',
            'icon' => 'users',
            'onclick' => "handleAdminSidebarNav('directory')",
        ],
        [
            'id' => 'backups',
            'label' => 'Drive Backups',
            'icon' => 'database',
            'onclick' => "handleAdminSidebarNav('backups')",
        ],
        [
            'id' => 'audit',
            'label' => 'Audit Trail',
            'icon' => 'receipt',
            'onclick' => "handleAdminSidebarNav('audit')",
        ],
        [
            'id' => 'settings',
            'label' => 'System Settings',
            'icon' => 'settings',
            'onclick' => "handleAdminSidebarNav('settings')",
        ],
        [
            'id' => 'prof_activities',
            'label' => 'Professional Activities',
            'icon' => 'award',
            'onclick' => "handleAdminSidebarNav('prof_activities')",
        ],
        [
            'id' => 'leave_ledger',
            'label' => 'Master Leave Ledger',
            'icon' => 'calendar-range',
            'onclick' => "handleAdminSidebarNav('leave_ledger')",
        ],
        [
            'id' => 'sf_attendance',
            'label' => 'SF Staff Attendance',
            'icon' => 'user-check',
            'onclick' => "handleAdminSidebarNav('sf_attendance')",
        ],
        [
            'id' => 'my_batches',
            'label' => 'My Batches (Teaching)',
            'icon' => 'presentation',
            'url' => '/dashboard/lecturer',
        ],
        [
            'id' => 'profile',
            'label' => 'Executive Profile',
            'icon' => 'user-cog',
            'onclick' => "handleAdminSidebarNav('profile')",
        ],
    ],
];
