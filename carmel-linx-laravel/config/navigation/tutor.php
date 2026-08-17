<?php

return [
    'role' => 'tutor',
    'inherits' => 'faculty',
    'subtitle' => 'Tutor Desk',
    'items' => [
        [
            'id' => 'tutor_console',
            'label' => 'Tutor Console',
            'icon' => 'user-check',
            'url' => '/dashboard/tutor',
            'position' => 'after:my_batches',
        ],
        [
            'id' => 'my_mentoring',
            'label' => 'My Mentoring',
            'icon' => 'heart-handshake',
            'url' => '/dashboard/tutor',
            'onclick' => "sessionStorage.setItem('openMentoring', 'true'); window.location.href='/dashboard/tutor';",
            'position' => 'after:tutor_console',
        ],
    ],
];
