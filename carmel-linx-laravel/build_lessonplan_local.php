<?php
/**
 * Build lesson plans directly from the extracted Embedded Systems PDF text.
 * Uses the exact module outcomes (M1.01-M4.05) with their exact topics and hours.
 */
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$subjectId = 13;  // Embedded Systems EL-5041 in LOCAL DB (AWS uses ID:10)

// ── Exact data extracted from the PDF text ────────────────────────────────────

$extractedCos = [
    ['id' => 'CO1', 'description' => 'Explain the basics of embedded systems and its architecture', 'duration' => 13, 'cognitive_level' => 'Understanding'],
    ['id' => 'CO2', 'description' => 'Make use of AVR Microcontrollers to develop embedded programs using embedded C', 'duration' => 16, 'cognitive_level' => 'Applying'],
    ['id' => 'CO3', 'description' => 'Make use of AVR microcontroller to interface with various peripheral devices.', 'duration' => 19, 'cognitive_level' => 'Applying'],
    ['id' => 'CO4', 'description' => 'Familiarize RTOS', 'duration' => 10, 'cognitive_level' => 'Understanding'],
];

$extractedModules = [
    ['module_id' => 'I',   'content' => 'Embedded Systems - Definition, difference from general purpose computers - Classification of embedded systems, Application areas, Components of embedded system hardware, and Software embedded into the system. Architecture of embedded system – Building blocks of an embedded system, Core of embedded system – categories, Memory –ROM and RAM, Sensors, actuators and I/O sub-systems (LED, Opto coupler, Relay, Stepper motor). On board (I2C, SPI, UART) and external communication interfaces (RS 232, USB, Bluetooth, Wifi)'],
    ['module_id' => 'II',  'content' => 'AVR Microcontroller Architecture - Comparison of AVR family members and Selection of a microcontroller, ATMega32- Simplified Block diagram, Registers, data memory, I/O memory, SFRs, internal data SRAM, Status register, Program Counter and Program ROM space, I/O ports, Registers associated with I/O ports, Timers-0,1,2, associated registers, Interrupts. AVR programming using embedded C: Data types, I/O programming, logic operations, data conversion programs, time delays, programming of timers 0,1,2, AVR interrupts, programming of timer interrupts, programming external hardware interrupts, interrupt priority in AVR microcontroller.'],
    ['module_id' => 'III', 'content' => 'Interfacing of LED, Push button, Relay, Opto Coupler, Sensors (Temperature sensor, IR sensor), Seven segment Display, LCD, Keyboard Interfacing, Motor (DC, Servo, Stepper), RTC Interfacing, ADC interfacing. On-board communication interfaces with AVR – I2C interfacing (like real time clock interfacing) and basics of SPI interfacing with AVR.'],
    ['module_id' => 'IV',  'content' => 'Operating system basics: Kernel, types of operating systems – GPOS, RTOS. Real time operating systems: Tasks, process, threads, multiprocessing and multi-tasking, task scheduling, types, threads and process scheduling, task communication, task synchronization, device drivers. Selection criteria for RTOS. Overview of Micro C/OS-II and its services. List popular Real Time Operating Systems (any 10).'],
];

$extractedCoPo = [
    'CO1' => ['PO1' => 2, 'PO2' => null, 'PO3' => null, 'PO4' => null, 'PO5' => null, 'PO6' => null, 'PO7' => null, 'PO8' => null, 'PO9' => null, 'PO10' => null, 'PO11' => null, 'PO12' => null],
    'CO2' => ['PO1' => 3, 'PO2' => 3,    'PO3' => null, 'PO4' => null, 'PO5' => null, 'PO6' => null, 'PO7' => null, 'PO8' => null, 'PO9' => null, 'PO10' => null, 'PO11' => null, 'PO12' => null],
    'CO3' => ['PO1' => 3, 'PO2' => 3,    'PO3' => null, 'PO4' => null, 'PO5' => null, 'PO6' => null, 'PO7' => null, 'PO8' => null, 'PO9' => null, 'PO10' => null, 'PO11' => null, 'PO12' => null],
    'CO4' => ['PO1' => 3, 'PO2' => null, 'PO3' => null, 'PO4' => null, 'PO5' => null, 'PO6' => null, 'PO7' => null, 'PO8' => null, 'PO9' => null, 'PO10' => null, 'PO11' => null, 'PO12' => null],
];

$extractedTextbooks = [
    'K.V. Shibu, Introduction to Embedded Systems, 2e, McGraw Hill Education India, 2016.',
    'Rajkamal, Embedded Systems Architecture, Programming and Design, TMH, 2003',
    'Muhammad Ali Mazidi, Sarmad Naimi & Sepehr Naimi, The AVR Microcontroller and Embedded Systems Using Assembly and C, Pearson Education',
    'Michael J. Pont, Embedded C, Pearson Education, Second Edition',
];

// ── Module outcomes directly from the Course Outline table ───────────────────
// Each entry: [co_id, topic, allocated_hours]
$lessonPlanRaw = [
    // CO1 – 13 hrs
    ['CO1', 'Describe embedded system, illustrate difference from general purpose computer', 2],
    ['CO1', 'Classify embedded systems, explain application areas and summarize purpose of embedded systems', 2],
    ['CO1', 'Distinguish Hardware and software components of embedded systems', 2],
    ['CO1', 'Describe the basic blocks in a typical embedded system', 2],
    ['CO1', 'Describe Memory, Sensors, Actuators and I/O sub-systems', 2],
    ['CO1', 'Distinguish Communication Interfaces – On board and external interfaces', 3],
    // CO2 – 16 hrs
    ['CO2', 'Familiarize AVR controllers family members and criteria to select a microcontroller', 2],
    ['CO2', 'Explain block diagram of Atmega32 and its blocks', 2],
    ['CO2', 'Illustrate Registers, Memory organization, Status register, Program counter, I/O ports and its registers, Interrupts, priority of interrupts', 3],
    ['CO2', 'Illustrate Timers in AVR', 3],
    ['CO2', 'Develop embedded C programs for logic operations, data conversions and I/O operations', 2],
    ['CO2', 'Develop embedded C programs for time delay, time delay calculation, Timer programming and timer interrupts handling', 2],
    ['CO2', 'Develop Programs to handle external hardware interrupts and programming based on priority of interrupts', 2],
    // CO3 – 19 hrs
    ['CO3', 'Illustrate the need for interfacing, and types of interfacing devices', 2],
    ['CO3', 'Illustrate the interfacing of LED, Push button, Relay and Optocoupler with AVR', 2],
    ['CO3', 'Illustrate Sensors and Seven segment Display interfacing with AVR', 2],
    ['CO3', 'Make use of AVR to realize LCD and Keyboard interfacing', 3],
    ['CO3', 'Make use of AVR microcontroller to interface DC motor, Servo motor and stepper motor', 3],
    ['CO3', 'Make use of AVR microcontroller to interface RTC and ADC', 2],
    ['CO3', 'Realize I2C interfacing with AVR', 3],
    ['CO3', 'Understand SPI interface with AVR', 2],
    // CO4 – 10 hrs
    ['CO4', 'Describe Kernel, Operating System Architecture and types', 2],
    ['CO4', 'Explain RTOS Kernel functions', 3],
    ['CO4', 'List Selection criteria for RTOS', 2],
    ['CO4', 'Outline Micro C/OS-II and its services', 2],
    ['CO4', 'List popular Real Time Operating Systems', 1],
];

// ── Atomic split helper ───────────────────────────────────────────────────────
// Strategy:
//  1. Try to split the module outcome by MEANINGFUL comma-separated parts
//     (each part must be >= 18 chars to be a standalone day entry).
//  2. If not enough clean splits found, use the FULL topic text + revision padding.
//     This ensures Day 9 shows "Describe Memory, Sensors, Actuators and I/O sub-systems"
//     NOT just "Actuators and I/O sub-systems".
function splitTopicIntoAtomicDays(string $topic, int $hours): array {
    if ($hours <= 1) return [$topic];

    // Comma-split: each fragment must be >= 18 chars to count as standalone
    $commaParts = array_values(array_filter(array_map('trim', explode(',', $topic)), fn($s) => strlen($s) >= 18));

    // Merge any very short fragments back into the previous one
    $merged = []; $buffer = '';
    foreach ($commaParts as $part) {
        if (strlen($buffer) > 0 && strlen($part) < 20) {
            $buffer .= ', ' . $part;
        } else {
            if ($buffer !== '') $merged[] = ucfirst($buffer);
            $buffer = $part;
        }
    }
    if ($buffer !== '') $merged[] = ucfirst($buffer);

    // If we found enough clean, distinct topic splits, use them
    if (count($merged) >= $hours) {
        return array_slice($merged, 0, $hours);
    }

    // Semicolon/period split (secondary strategy)
    $sp = array_values(array_filter(preg_split('/[.;]\s+/', $topic, -1, PREG_SPLIT_NO_EMPTY), fn($s) => strlen(trim($s)) > 15));
    $sp = array_map('trim', $sp);
    if (count($sp) >= $hours) {
        return array_slice(array_map('ucfirst', $sp), 0, $hours);
    }

    // FALLBACK: Not enough clean splits.
    // Use FULL TOPIC TEXT for first day, then pad with revision sessions.
    // This is much better than showing partial phrases like "Actuators and I/O sub-systems".
    $pads = ['Revision & Problem Solving', 'Practice Problems & Exercises', 'Doubt Clearing & Discussion', 'Worked Examples', 'Tutorial Session'];
    $result = [$topic]; // Full topic for Day 1
    $needed = $hours - 1;
    for ($i = 0; $i < $needed; $i++) {
        $result[] = $topic . ' – ' . $pads[$i % count($pads)];
    }
    return $result;
}

// ── Expand lesson plans ───────────────────────────────────────────────────────
$expanded = [];
$dayNo = 1;
foreach ($lessonPlanRaw as [$coId, $topic, $hours]) {
    $atomics = splitTopicIntoAtomicDays($topic, $hours);
    foreach ($atomics as $atomicTopic) {
        $expanded[] = [
            'day_no' => $dayNo++,
            'co_id' => $coId,
            'topic_content' => $atomicTopic,
            'allocated_hours' => 1,
            'pedagogy' => 'Lecture',
            'remarks' => null,
        ];
    }
}

// Append 4 Series Test days
$expanded[] = [
    'day_no' => $dayNo++,
    'co_id' => null,
    'topic_content' => 'Series Test - I / Internal Assessment',
    'allocated_hours' => 1,
    'pedagogy' => 'Test',
    'remarks' => 'Series Test - I',
];
$expanded[] = [
    'day_no' => $dayNo++,
    'co_id' => null,
    'topic_content' => 'Series Test - II / Internal Assessment',
    'allocated_hours' => 1,
    'pedagogy' => 'Test',
    'remarks' => 'Series Test - II',
];
$expanded[] = [
    'day_no' => $dayNo++,
    'co_id' => null,
    'topic_content' => 'Series Test - III / Internal Assessment',
    'allocated_hours' => 1,
    'pedagogy' => 'Test',
    'remarks' => 'Series Test - III',
];
$expanded[] = [
    'day_no' => $dayNo++,
    'co_id' => null,
    'topic_content' => 'Series Test - IV / Internal Assessment',
    'allocated_hours' => 1,
    'pedagogy' => 'Test',
    'remarks' => 'Series Test - IV',
];

// ── Show preview ──────────────────────────────────────────────────────────────
echo "═══ LESSON PLAN PREVIEW (" . count($expanded) . " rows) ═══════════════════════════════\n\n";
foreach ($expanded as $r) {
    $co = $r['co_id'] ?? 'TEST';
    echo "Day " . str_pad($r['day_no'], 3) . " | " . str_pad($co, 4) . " | " . $r['topic_content'] . "\n";
}

// ── Save to DB ────────────────────────────────────────────────────────────────
echo "\n💾 Saving to database...\n";

// Save PDF path (keep existing or use a placeholder)
$existingCf = DB::table('course_files')->where('batch_subject_id', $subjectId)->first();
$pdfStoragePath = $existingCf->syllabus_pdf_path ?? '/storage/syllabi/el5041.pdf';

// Copy actual PDF to storage if possible
$destDir = storage_path('app/public/syllabi');
if (!is_dir($destDir)) mkdir($destDir, 0755, true);
$destPath = $destDir . '/el5041_' . time() . '.pdf';
copy('C:/Users/fotonlabz/Downloads/5041 (1).pdf', $destPath);
$pdfStoragePath = '/storage/syllabi/' . basename($destPath);
echo "   ✅ PDF copied to: $pdfStoragePath\n";

// syllabus_registry
DB::table('syllabus_registry')->updateOrInsert(
    ['subject_code' => 'EL-5041'],
    ['subject_name' => 'Embedded Systems', 'revision_year' => 2021, 'co_count' => 4, 'updated_at' => now(), 'created_at' => now()]
);

// course_files
DB::table('course_files')->updateOrInsert(
    ['batch_subject_id' => $subjectId],
    [
        'syllabus_pdf_path' => $pdfStoragePath,
        'parsed_cos'        => json_encode($extractedCos),
        'parsed_copo'       => json_encode($extractedCoPo),
        'parsed_modules'    => json_encode($extractedModules),
        'parsed_textbooks'  => json_encode($extractedTextbooks),
        'updated_at'        => now(),
        'created_at'        => now(),
    ]
);
echo "   ✅ course_files saved\n";

// lesson_plans
DB::table('lesson_plans')->where('batch_subject_id', $subjectId)->delete();
$now = now();
foreach ($expanded as $lp) {
    DB::table('lesson_plans')->insert([
        'batch_subject_id' => (int)$subjectId,
        'day_no'           => $lp['day_no'],
        'co_id'            => $lp['co_id'],
        'topic_content'    => $lp['topic_content'],
        'allocated_hours'  => 1,
        'pedagogy'         => $lp['pedagogy'],
        'remarks'          => $lp['remarks'],
        'proposed_date'    => null,
        'actual_date'      => null,
        'actual_hours'     => null,
        'status'           => 'Pending',
        'created_at'       => $now,
        'updated_at'       => $now,
    ]);
}
$count = DB::table('lesson_plans')->where('batch_subject_id', $subjectId)->count();
echo "   ✅ lesson_plans saved: $count rows\n";
echo "\n✅ DONE! Refresh the virtual classroom → Lesson Plan tab.\n";
