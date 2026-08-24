<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CampusLynk Modular Navigation Configuration
    |--------------------------------------------------------------------------
    |
    | Maps institutional user roles to their corresponding navigation definition
    | files. Role inheritance is resolved through the NavigationService layer.
    |
    */
    'roles' => [
        'faculty'     => require __DIR__ . '/navigation/faculty.php',
        'hod'         => require __DIR__ . '/navigation/hod.php',
        'tutor'       => require __DIR__ . '/navigation/tutor.php',
        'principal'   => require __DIR__ . '/navigation/principal.php',
        'admin'       => require __DIR__ . '/navigation/admin.php',
        'super_admin' => require __DIR__ . '/navigation/super_admin.php',
        'student'     => require __DIR__ . '/navigation/student.php',
        'demonstrator'     => require __DIR__ . '/navigation/demonstrator.php',
        'trade_instructor' => require __DIR__ . '/navigation/trade_instructor.php',
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Aliases & Mapping
    |--------------------------------------------------------------------------
    */
    'aliases' => [
        'lecturer'                          => 'faculty',
        'demonstrator'                      => 'demonstrator',
        'trade_instructor'                  => 'trade_instructor',
        'tradesman'                         => 'trade_instructor',
        'tradeinstructor'                   => 'trade_instructor',
        'workshop_instructor'               => 'trade_instructor',
        'physical_instructor'               => 'faculty',
        'physical instructor'               => 'faculty',
        'workshop_superintendent'           => 'faculty',
        'workshop superintendent'           => 'faculty',
        'academic_coordinator'              => 'admin',
        'academic coordinator'              => 'admin',
        'academic_coordinator_sf'           => 'admin',
        'gen_dept_coordinator_self_finance' => 'admin',
        'chairman'                          => 'admin',
        'executive'                         => 'principal',
    ],
];
