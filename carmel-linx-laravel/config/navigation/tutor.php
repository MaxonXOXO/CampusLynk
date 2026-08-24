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
    ],
];
