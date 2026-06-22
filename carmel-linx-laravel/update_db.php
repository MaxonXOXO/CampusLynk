<?php
namespace App\Http\Controllers;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\LessonPlan;

$lessonPlans = [
                    ['day_no' => 1, 'co_id' => 'CO1', 'topic_content' => 'Describe embedded system (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 2, 'co_id' => 'CO1', 'topic_content' => 'Classify embedded systems (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 3, 'co_id' => 'CO1', 'topic_content' => 'Distinguish Hardware components (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 4, 'co_id' => 'CO1', 'topic_content' => 'Distinguish Software components (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 5, 'co_id' => 'CO1', 'topic_content' => 'Describe the basic blocks (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 6, 'co_id' => 'CO1', 'topic_content' => 'Memory, Sensors, Actuators (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 7, 'co_id' => 'CO1', 'topic_content' => 'I/O sub-systems (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 8, 'co_id' => 'CO1', 'topic_content' => 'Communication Interfaces (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 9, 'co_id' => 'CO1', 'topic_content' => 'Describe embedded system (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 10, 'co_id' => 'CO1', 'topic_content' => 'Classify embedded systems (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 11, 'co_id' => 'CO1', 'topic_content' => 'Distinguish Hardware components (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 12, 'co_id' => 'CO1', 'topic_content' => 'Distinguish Software components (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 13, 'co_id' => 'CO1', 'topic_content' => 'Describe the basic blocks (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 14, 'co_id' => 'CO2', 'topic_content' => 'Familiarize AVR controllers family members (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 15, 'co_id' => 'CO2', 'topic_content' => 'Criteria to select a microcontroller (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 16, 'co_id' => 'CO2', 'topic_content' => 'Explain block diagram of Atmega32 (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 17, 'co_id' => 'CO2', 'topic_content' => 'Illustrate Registers, Memory organization (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 18, 'co_id' => 'CO2', 'topic_content' => 'Status register, Program counter (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 19, 'co_id' => 'CO2', 'topic_content' => 'Timers in AVR (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 20, 'co_id' => 'CO2', 'topic_content' => 'Embedded C programs for logic operations (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 21, 'co_id' => 'CO2', 'topic_content' => 'Time delay calculation (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 22, 'co_id' => 'CO2', 'topic_content' => 'Interrupts handling (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 23, 'co_id' => 'CO2', 'topic_content' => 'Familiarize AVR controllers family members (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 24, 'co_id' => 'CO2', 'topic_content' => 'Criteria to select a microcontroller (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 25, 'co_id' => 'CO2', 'topic_content' => 'Explain block diagram of Atmega32 (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 26, 'co_id' => 'CO2', 'topic_content' => 'Illustrate Registers, Memory organization (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 27, 'co_id' => 'CO2', 'topic_content' => 'Status register, Program counter (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 28, 'co_id' => 'CO2', 'topic_content' => 'Timers in AVR (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 29, 'co_id' => 'CO2', 'topic_content' => 'Embedded C programs for logic operations (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 30, 'co_id' => 'CO3', 'topic_content' => 'Need for interfacing (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 31, 'co_id' => 'CO3', 'topic_content' => 'Types of interfacing devices (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 32, 'co_id' => 'CO3', 'topic_content' => 'Interfacing of LED (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 33, 'co_id' => 'CO3', 'topic_content' => 'Push button, Relay (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 34, 'co_id' => 'CO3', 'topic_content' => 'Optocoupler with AVR (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 35, 'co_id' => 'CO3', 'topic_content' => 'Sensors and Seven segment Display (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 36, 'co_id' => 'CO3', 'topic_content' => 'LCD and Keyboard interfacing (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 37, 'co_id' => 'CO3', 'topic_content' => 'DC motor, Servo motor and stepper motor (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 38, 'co_id' => 'CO3', 'topic_content' => 'Need for interfacing (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 39, 'co_id' => 'CO3', 'topic_content' => 'Types of interfacing devices (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 40, 'co_id' => 'CO3', 'topic_content' => 'Interfacing of LED (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 41, 'co_id' => 'CO3', 'topic_content' => 'Push button, Relay (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 42, 'co_id' => 'CO3', 'topic_content' => 'Optocoupler with AVR (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 43, 'co_id' => 'CO3', 'topic_content' => 'Sensors and Seven segment Display (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 44, 'co_id' => 'CO3', 'topic_content' => 'LCD and Keyboard interfacing (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 45, 'co_id' => 'CO3', 'topic_content' => 'DC motor, Servo motor and stepper motor (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 46, 'co_id' => 'CO3', 'topic_content' => 'Need for interfacing (Part 3)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 47, 'co_id' => 'CO3', 'topic_content' => 'Types of interfacing devices (Part 3)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 48, 'co_id' => 'CO3', 'topic_content' => 'Interfacing of LED (Part 3)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 49, 'co_id' => 'CO4', 'topic_content' => 'Familiarize RTOS (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 50, 'co_id' => 'CO4', 'topic_content' => 'Tasks, Threads (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 51, 'co_id' => 'CO4', 'topic_content' => 'Multiprocessing and Multitasking (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 52, 'co_id' => 'CO4', 'topic_content' => 'Task Scheduling (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 53, 'co_id' => 'CO4', 'topic_content' => 'Inter-process Communication (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 54, 'co_id' => 'CO4', 'topic_content' => 'Shared memory (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 55, 'co_id' => 'CO4', 'topic_content' => 'Message passing (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 56, 'co_id' => 'CO4', 'topic_content' => 'RTOS Examples (Part 1)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 57, 'co_id' => 'CO4', 'topic_content' => 'Familiarize RTOS (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 58, 'co_id' => 'CO4', 'topic_content' => 'Tasks, Threads (Part 2)', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],
                    ['day_no' => 59, 'co_id' => null, 'topic_content' => 'Internal Assessment Test 1', 'allocated_hours' => 1, 'pedagogy' => 'Assessment'],
                    ['day_no' => 60, 'co_id' => null, 'topic_content' => 'Internal Assessment Test 2', 'allocated_hours' => 1, 'pedagogy' => 'Assessment'],
];

LessonPlan::truncate();
foreach ($lessonPlans as $lp) {
    LessonPlan::create([
        'batch_subject_id' => 1, // Assume subject 1 for demo
        'day_no' => $lp['day_no'],
        'co_id' => $lp['co_id'],
        'topic_content' => $lp['topic_content'],
        'allocated_hours' => $lp['allocated_hours'],
        'pedagogy' => $lp['pedagogy']
    ]);
}
echo "Database updated to 60 plans.";
