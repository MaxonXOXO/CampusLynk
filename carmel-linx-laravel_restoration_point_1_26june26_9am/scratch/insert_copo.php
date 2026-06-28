<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$mapping = [
    [
        'co' => 'CO1',
        'description' => 'Explain the basics of embedded systems and its architecture',
        'po1' => 2, 'po2' => '', 'po3' => '', 'po4' => '', 'po5' => '', 'po6' => '', 'po7' => '', 'po8' => '', 'po9' => '', 'po10' => '', 'po11' => '',
        'pso1' => 2, 'pso2' => '', 'pso3' => ''
    ],
    [
        'co' => 'CO2',
        'description' => 'Make use of AVR Microcontrollers to develop embedded programs using embedded C',
        'po1' => 3, 'po2' => 3, 'po3' => '', 'po4' => '', 'po5' => '', 'po6' => '', 'po7' => '', 'po8' => '', 'po9' => '', 'po10' => '', 'po11' => '',
        'pso1' => '', 'pso2' => '', 'pso3' => ''
    ],
    [
        'co' => 'CO3',
        'description' => 'Make use of AVR microcontroller to interface with various peripheral devices.',
        'po1' => 3, 'po2' => 3, 'po3' => '', 'po4' => '', 'po5' => '', 'po6' => '', 'po7' => '', 'po8' => '', 'po9' => '', 'po10' => '', 'po11' => '',
        'pso1' => '', 'pso2' => '', 'pso3' => 3
    ],
    [
        'co' => 'CO4',
        'description' => 'Familiarize RTOS',
        'po1' => 3, 'po2' => '', 'po3' => '', 'po4' => '', 'po5' => '', 'po6' => '', 'po7' => '', 'po8' => '', 'po9' => '', 'po10' => '', 'po11' => '',
        'pso1' => '', 'pso2' => '', 'pso3' => ''
    ]
];

\Illuminate\Support\Facades\DB::table('syllabus_registry')
    ->where('subject_code', 'EL-5041')
    ->update(['co_po_mapping' => json_encode($mapping)]);

echo "CO-PO mapping inserted for EL-5041.\n";
