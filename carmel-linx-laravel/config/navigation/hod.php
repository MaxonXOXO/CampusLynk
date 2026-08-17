<?php

return [
    'role' => 'hod',
    'inherits' => 'faculty',
    'subtitle' => 'HOD Console',
    'items' => [
        [
            'id' => 'hod_console',
            'label' => 'HOD Console',
            'icon' => 'shield-check',
            'url' => '/dashboard/hod',
            'position' => 'after:my_batches',
        ],
    ],
];
