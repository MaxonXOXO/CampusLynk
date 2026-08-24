<?php

return [
    'role' => 'faculty',
    'inherits' => null,
    'subtitle' => 'Faculty Platform',
    'items' => [
        [
            'id' => 'my_batches',
            'label' => 'My Batches',
            'icon' => 'presentation',
            'url' => '/dashboard/lecturer',
            'onclick' => "handleFacultySidebarNav('dashboard')",
        ],
        [
            'id' => 'my_leave',
            'label' => 'My Leave & Attendance',
            'icon' => 'calendar-check-2',
            'url' => '/staff/my-leave',
        ],
        [
            'id' => 'attendance_log',
            'label' => 'Class Attendance Log',
            'icon' => 'clipboard-check',
            'url' => '/staff/attendance-log',
        ],
        [
            'id' => 'remedial',
            'label' => 'Remedial Sessions',
            'icon' => 'activity',
            'url' => '/remedial-sessions',
        ],
        [
            'id' => 'course_files',
            'label' => 'Course Files',
            'icon' => 'folder-open',
            'url' => '/course-files',
        ],
        [
            'id' => 'prof_activities',
            'label' => 'Professional Activities',
            'icon' => 'award',
            'url' => '/staff/professional-activities',
        ],
        [
            'id' => 'profile',
            'label' => 'My Profile',
            'icon' => 'user-round',
            'url' => '/dashboard/lecturer?panel=security',
            'onclick' => "handleFacultySidebarNav('security')",
        ],
    ],
];
