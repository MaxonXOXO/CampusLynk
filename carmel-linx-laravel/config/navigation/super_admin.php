<?php

return [
    'role' => 'super_admin',
    'inherits' => 'admin',
    'subtitle' => 'Super Admin Console',
    'items' => [
        [
            'id' => 'user_credentials',
            'label' => 'User Credentials',
            'icon' => 'key',
            'url' => '/superadmin/show-users',
        ],
    ],
];
