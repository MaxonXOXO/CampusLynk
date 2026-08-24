<?php

return [
    'role' => 'demonstrator',
    'inherits' => null,
    'subtitle' => 'Demonstrator Console',
    'items' => [
        [
            'id' => 'my_batches',
            'label' => 'Lab Workspaces',
            'icon' => 'presentation',
            'url' => '/dashboard/demonstrator',
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
            'url' => '/dashboard/demonstrator?panel=security',
        ],
    ],
];
