<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$subjectId = 5; // Electric Circuits

// The exact Electric Circuits topics (same as in ClassroomController custom profile)
$rawTopics = [
    ['co_id' => 'CO1', 'topic_content' => 'Transistor biasing - need for biasing, load line concepts', 'allocated_hours' => 2],
    ['co_id' => 'CO1', 'topic_content' => 'Operating point and stabilization of operating point', 'allocated_hours' => 2],
    ['co_id' => 'CO1', 'topic_content' => 'Biasing circuits requirements, fixed bias and voltage divider bias circuits', 'allocated_hours' => 3],
    ['co_id' => 'CO1', 'topic_content' => 'Single Stage CE Amplifier with voltage divider biasing - operation and parameters', 'allocated_hours' => 3],
    ['co_id' => 'CO1', 'topic_content' => 'Emitter follower - circuit diagram, features, and applications', 'allocated_hours' => 2],
    ['co_id' => 'CO1', 'topic_content' => 'Multistage amplifier - RC coupled, transformer coupled and direct coupled gain calculation', 'allocated_hours' => 2],
    ['co_id' => 'CO2', 'topic_content' => 'Tuned Amplifier - resonant circuits, resonance curves, quality factor', 'allocated_hours' => 3],
    ['co_id' => 'CO2', 'topic_content' => 'Relation between resonant frequency, Q, and bandwidth', 'allocated_hours' => 2],
    ['co_id' => 'CO2', 'topic_content' => 'Single tuned amplifier - operation, frequency response, and limitations', 'allocated_hours' => 3],
    ['co_id' => 'CO2', 'topic_content' => 'Double tuned amplifier - frequency response for different degrees of coupling', 'allocated_hours' => 3],
    ['co_id' => 'CO2', 'topic_content' => 'Power Amplifier - comparison of voltage and power amplifier, classification', 'allocated_hours' => 4],
    ['co_id' => 'CO3', 'topic_content' => 'Feedback Amplifiers - concept of feedback, classification, effects of negative feedback', 'allocated_hours' => 4],
    ['co_id' => 'CO3', 'topic_content' => 'Oscillators - positive feedback and Barkhausen criteria', 'allocated_hours' => 2],
    ['co_id' => 'CO3', 'topic_content' => 'RC Phase shift and Wein Bridge Oscillators - operation and frequency derivation', 'allocated_hours' => 3],
    ['co_id' => 'CO3', 'topic_content' => 'Hartley and Colpitts Oscillators - working principle and frequency of oscillation', 'allocated_hours' => 3],
    ['co_id' => 'CO3', 'topic_content' => 'Crystal Oscillators - equivalent circuit, working, and stability advantages', 'allocated_hours' => 3],
    ['co_id' => 'CO4', 'topic_content' => 'Wave shaping circuits - clipping and clamping circuits', 'allocated_hours' => 3],
    ['co_id' => 'CO4', 'topic_content' => 'Astable Multivibrator using transistors - working and frequency', 'allocated_hours' => 3],
    ['co_id' => 'CO4', 'topic_content' => 'Monostable and Bistable Multivibrators using transistors', 'allocated_hours' => 3],
    ['co_id' => 'CO4', 'topic_content' => 'Schmitt Trigger - operation and hysteresis curve', 'allocated_hours' => 3],
    ['co_id' => 'CO4', 'topic_content' => 'UJT Relaxation Oscillator - working and applications', 'allocated_hours' => 2],
];

// Expand to 1-hour-per-day
$expanded = [];
$dayNo = 1;
foreach ($rawTopics as $lp) {
    $hours = max(1, (int)($lp['allocated_hours'] ?? 1));
    for ($h = 0; $h < $hours; $h++) {
        $suffix = ($hours > 1) ? " (Part " . ($h + 1) . "/{$hours})" : '';
        $expanded[] = [
            'batch_subject_id' => $subjectId,
            'day_no'           => $dayNo++,
            'co_id'            => $lp['co_id'],
            'topic_content'    => $lp['topic_content'] . $suffix,
            'allocated_hours'  => 1,
            'pedagogy'         => 'Lecture',
            'remarks'          => null,
        ];
    }
}

// Append 2 test days
$expanded[] = [
    'batch_subject_id' => $subjectId,
    'day_no'           => $dayNo++,
    'co_id'            => null,
    'topic_content'    => 'Series Test / Internal Assessment',
    'allocated_hours'  => 1,
    'pedagogy'         => 'Test',
    'remarks'          => 'Series Test Day 1',
];
$expanded[] = [
    'batch_subject_id' => $subjectId,
    'day_no'           => $dayNo++,
    'co_id'            => null,
    'topic_content'    => 'Series Test / Internal Assessment',
    'allocated_hours'  => 1,
    'pedagogy'         => 'Test',
    'remarks'          => 'Series Test Day 2',
];

// Clear old lesson plans and insert new ones
DB::table('lesson_plans')->where('batch_subject_id', $subjectId)->delete();
foreach ($expanded as $row) {
    DB::table('lesson_plans')->insert(array_merge($row, [
        'created_at' => now(),
        'updated_at' => now(),
    ]));
}

echo "✅ Done! Generated " . count($expanded) . " lesson plan days for Electric Circuits (subject ID: {$subjectId}).\n";
echo "   Last day: Day " . ($dayNo - 1) . " (includes 2 test days at end).\n";
