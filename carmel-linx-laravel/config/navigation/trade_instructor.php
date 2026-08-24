<?php

return [
    'role' => 'trade_instructor',
    'inherits' => null,
    'subtitle' => 'Instructor Console',
    'items' => [
        [
            'id' => 'my_batches',
            'label' => 'Workshop Tasks',
            'icon' => 'wrench',
            'url' => '/dashboard/tradeinstructor',
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
            'id' => 'prof_activities',
            'label' => 'Professional Activities',
            'icon' => 'award',
            'url' => '/staff/professional-activities',
        ],
        [
            'id' => 'profile',
            'label' => 'My Profile',
            'icon' => 'user-round',
            'url' => '/dashboard/tradeinstructor?panel=security',
        ],
    ],
];
