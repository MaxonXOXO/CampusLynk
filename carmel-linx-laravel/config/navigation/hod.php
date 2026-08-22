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
            'onclick' => "handleHodSidebarNav('batches')",
        ],
        [
            'id' => 'directory',
            'label' => 'User Directory',
            'icon' => 'users',
            'onclick' => "handleHodSidebarNav('directory')",
        ],
        [
            'id' => 'subjects',
            'label' => 'Subject Allocation',
            'icon' => 'book-open',
            'onclick' => "handleHodSidebarNav('subjects')",
        ],
        [
            'id' => 'audit',
            'label' => 'Audit Trail',
            'icon' => 'receipt',
            'onclick' => "handleHodSidebarNav('audit')",
        ],
        [
            'id' => 'my_batches',
            'label' => 'My Batches',
            'icon' => 'presentation',
            'url' => '/dashboard/lecturer',
        ],
        [
            'id' => 'report_centre',
            'label' => 'Report Centre',
            'icon' => 'bar-chart-3',
            'onclick' => "handleHodSidebarNav('report_centre')",
        ],
        [
            'id' => 'leave_ledger',
            'label' => 'Staff Leave Ledger',
            'icon' => 'calendar-range',
            'onclick' => "handleHodSidebarNav('leave_ledger')",
        ],
        [
            'id' => 'prof_activities',
            'label' => 'Professional Activities',
            'icon' => 'award',
            'onclick' => "handleHodSidebarNav('prof_activities')",
        ],
        [
            'id' => 'profile',
            'label' => 'My Profile',
            'icon' => 'user-cog',
            'onclick' => "handleHodSidebarNav('profile')",
        ],
    ],
];

