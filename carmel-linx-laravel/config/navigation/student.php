<?php

return [
    'role' => 'student',
    'inherits' => null,
    'subtitle' => 'Student Desk',
    'items' => [
        [
            'id' => 'exams',
            'label' => 'Works To Do',
            'icon' => 'clipboard-check',
            'onclick' => "handleStudentSidebarNav('exams')",
        ],
        [
            'id' => 'marks',
            'label' => 'Academic Stats',
            'icon' => 'bar-chart-3',
            'onclick' => "handleStudentSidebarNav('marks')",
        ],
        [
            'id' => 'profile',
            'label' => 'My Profile',
            'icon' => 'user-round',
            'onclick' => "handleStudentSidebarNav('profile')",
        ],
        [
            'id' => 'mentoring',
            'label' => 'Mentoring Diary',
            'icon' => 'book-open',
            'onclick' => "handleStudentSidebarNav('mentoring')",
        ],
        [
            'id' => 'activity',
            'label' => 'Activity Points',
            'icon' => 'award',
            'onclick' => "handleStudentSidebarNav('activity')",
        ],
        [
            'id' => 'seminar',
            'label' => 'My Seminar',
            'icon' => 'presentation',
            'onclick' => "handleStudentSidebarNav('seminar')",
        ],
        [
            'id' => 'attendance',
            'label' => 'Attendance Review',
            'icon' => 'calendar-check-2',
            'onclick' => "handleStudentSidebarNav('attendance')",
        ],
        [
            'id' => 'mock_test',
            'label' => 'Practice Test',
            'icon' => 'rocket',
            'onclick' => "handleStudentSidebarNav('mock_test')",
        ],
    ],
];
