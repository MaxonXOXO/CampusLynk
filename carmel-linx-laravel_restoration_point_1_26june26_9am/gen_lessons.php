<?php
$out = ''; 
$day=1; 
$cos = ['CO1'=>13, 'CO2'=>16, 'CO3'=>19, 'CO4'=>10]; 
$topics = [
    'CO1' => ['Describe embedded system', 'Classify embedded systems', 'Distinguish Hardware components', 'Distinguish Software components', 'Describe the basic blocks', 'Memory, Sensors, Actuators', 'I/O sub-systems', 'Communication Interfaces'],
    'CO2' => ['Familiarize AVR controllers family members', 'Criteria to select a microcontroller', 'Explain block diagram of Atmega32', 'Illustrate Registers, Memory organization', 'Status register, Program counter', 'Timers in AVR', 'Embedded C programs for logic operations', 'Time delay calculation', 'Interrupts handling'],
    'CO3' => ['Need for interfacing', 'Types of interfacing devices', 'Interfacing of LED', 'Push button, Relay', 'Optocoupler with AVR', 'Sensors and Seven segment Display', 'LCD and Keyboard interfacing', 'DC motor, Servo motor and stepper motor'],
    'CO4' => ['Familiarize RTOS', 'Tasks, Threads', 'Multiprocessing and Multitasking', 'Task Scheduling', 'Inter-process Communication', 'Shared memory', 'Message passing', 'RTOS Examples']
];

foreach($cos as $co=>$hrs) { 
    $topicList = $topics[$co];
    for($i=1; $i<=$hrs; $i++) { 
        $topic = $topicList[($i-1) % count($topicList)] . " (Part " . ceil($i/count($topicList)) . ")";
        $out .= "                    ['day_no' => $day, 'co_id' => '$co', 'topic_content' => '$topic', 'allocated_hours' => 1, 'pedagogy' => 'Lecture'],\n"; 
        $day++; 
    } 
} 
file_put_contents('temp_lessons.txt', $out);
