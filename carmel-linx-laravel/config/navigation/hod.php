<?php

return [
    'role' => 'hod',
    'inherits' => null,
    'subtitle' => 'HOD Console',
    'items' => [
        [
            'id' => 'batches',
            'label' => 'Batch Management',
            'icon' => 'school',
            'url' => '/dashboard/hod?panel=batches',
        ],
        [
            'id' => 'directory',
            'label' => 'User Directory',
            'icon' => 'users',
            'url' => '/dashboard/hod?panel=directory',
        ],
        [
            'id' => 'subjects',
            'label' => 'Subject Allocation',
            'icon' => 'book-open',
            'url' => '/dashboard/hod?panel=subjects',
        ],
        [
            'id' => 'audit',
            'label' => 'Audit Trail',
            'icon' => 'receipt',
            'url' => '/dashboard/hod?panel=audit',
        ],
        [
            'id' => 'my_leave',
            'label' => 'My Leave & Attendance',
            'icon' => 'calendar-check-2',
            'url' => '/staff/my-leave',
        ],
        [
            'id' => 'leave_ledger',
            'label' => 'Staff Leave Ledger',
            'icon' => 'calendar-range',
            'url' => '/dashboard/hod?panel=leave_ledger',
        ],
        [
            'id' => 'report_centre',
            'label' => 'Report Centre',
            'icon' => 'bar-chart-3',
            'url' => '/dashboard/hod?panel=report_centre',
        ],
        [
            'id' => 'prof_activities',
            'label' => 'Professional Activities',
            'icon' => 'award',
            'url' => '/dashboard/hod?panel=prof_activities',
        ],
        [
            'id' => 'my_batches',
            'label' => 'My Batches (Teaching)',
            'icon' => 'presentation',
            'url' => '/dashboard/lecturer',
        ],
        [
            'id' => 'profile',
            'label' => 'My Profile',
            'icon' => 'user-cog',
            'url' => '/dashboard/hod?panel=profile',
        ],
    ],
];
