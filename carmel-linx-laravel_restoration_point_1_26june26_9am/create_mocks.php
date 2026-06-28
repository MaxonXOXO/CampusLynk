<?php
$mockMCQs = [
    'CO1' => [
        ['q' => 'Which of the following is a primary feature of an embedded system?', 'options' => ['High power consumption', 'General purpose computing', 'Real-time performance constraints', 'Requires a monitor'], 'ans' => 'Real-time performance constraints'],
        ['q' => 'What is the function of a watchdog timer?', 'options' => ['Keep real time', 'Reset the system on software hang', 'Manage battery life', 'Increase CPU speed'], 'ans' => 'Reset the system on software hang'],
        ['q' => 'Which memory is typically used to store the application firmware?', 'options' => ['SRAM', 'EEPROM', 'Flash', 'DRAM'], 'ans' => 'Flash'],
        ['q' => 'An embedded system must be...', 'options' => ['Application specific', 'Tightly constrained', 'Reactive to environment', 'All of the above'], 'ans' => 'All of the above'],
        ['q' => 'Which bus architecture uses separate paths for data and instructions?', 'options' => ['Von Neumann', 'Harvard', 'PCI', 'USB'], 'ans' => 'Harvard'],
        ['q' => 'What is the most important characteristic of a hard real-time system?', 'options' => ['High throughput', 'Low cost', 'Strict timing deadlines', 'Large memory'], 'ans' => 'Strict timing deadlines'],
        ['q' => 'Which processor architecture is most commonly used in mobile embedded systems?', 'options' => ['x86', 'ARM', 'MIPS', 'PowerPC'], 'ans' => 'ARM'],
        ['q' => 'What type of memory is volatile?', 'options' => ['SRAM', 'EEPROM', 'Flash', 'ROM'], 'ans' => 'SRAM'],
        ['q' => 'An RTOS is required when...', 'options' => ['System needs a GUI', 'System has strict timing constraints', 'System uses a lot of memory', 'System is connected to the internet'], 'ans' => 'System has strict timing constraints'],
        ['q' => 'Which interface is typically used for debugging embedded systems?', 'options' => ['HDMI', 'JTAG', 'PCIe', 'SATA'], 'ans' => 'JTAG']
    ],
    'CO2' => [
        ['q' => 'What is the width of an AVR general purpose register?', 'options' => ['8-bit', '16-bit', '32-bit', '64-bit'], 'ans' => '8-bit'],
        ['q' => 'Which register holds the status flags in AVR?', 'options' => ['PC', 'SP', 'SREG', 'TCNT'], 'ans' => 'SREG'],
        ['q' => 'What is the size of Flash memory in Atmega32?', 'options' => ['8 KB', '16 KB', '32 KB', '64 KB'], 'ans' => '32 KB'],
        ['q' => 'How many I/O pins are available in Atmega32?', 'options' => ['16', '32', '40', '64'], 'ans' => '32'],
        ['q' => 'Which flag is set when an arithmetic operation results in a zero?', 'options' => ['Carry Flag', 'Zero Flag', 'Sign Flag', 'Overflow Flag'], 'ans' => 'Zero Flag'],
        ['q' => 'What is the function of the Program Counter (PC)?', 'options' => ['Store data', 'Point to the next instruction', 'Store status flags', 'Manage stack'], 'ans' => 'Point to the next instruction'],
        ['q' => 'Which register is used to configure a pin as input or output in AVR?', 'options' => ['PORT', 'PIN', 'DDR', 'SREG'], 'ans' => 'DDR'],
        ['q' => 'What does the VCC pin do?', 'options' => ['Ground', 'Power supply', 'Clock input', 'Reset'], 'ans' => 'Power supply'],
        ['q' => 'Which feature allows the microcontroller to save power?', 'options' => ['Sleep modes', 'High clock speed', 'More RAM', 'External interrupts'], 'ans' => 'Sleep modes'],
        ['q' => 'What is the maximum operating frequency of Atmega32?', 'options' => ['1 MHz', '8 MHz', '16 MHz', '32 MHz'], 'ans' => '16 MHz']
    ],
    'CO3' => [
        ['q' => 'What does PWM stand for?', 'options' => ['Power Width Measurement', 'Pulse Width Modulation', 'Phase Wave Modulation', 'Periodic Width Modulation'], 'ans' => 'Pulse Width Modulation'],
        ['q' => 'Which component is used to isolate high voltage circuits from microcontrollers?', 'options' => ['Capacitor', 'Inductor', 'Optocoupler', 'Resistor'], 'ans' => 'Optocoupler'],
        ['q' => 'A stepper motor is preferred for...', 'options' => ['High speed rotation', 'Precise angular positioning', 'High torque at high speeds', 'Continuous unmonitored rotation'], 'ans' => 'Precise angular positioning'],
        ['q' => 'Which pins are used for I2C communication?', 'options' => ['TX, RX', 'MOSI, MISO', 'SDA, SCL', 'PWM, ADC'], 'ans' => 'SDA, SCL'],
        ['q' => 'Debouncing is primarily required when interfacing...', 'options' => ['LEDs', 'Motors', 'Mechanical Switches', 'LCDs'], 'ans' => 'Mechanical Switches'],
        ['q' => 'What is the purpose of an ADC?', 'options' => ['Convert analog signals to digital', 'Convert digital signals to analog', 'Amplify signals', 'Filter noise'], 'ans' => 'Convert analog signals to digital'],
        ['q' => 'Which communication protocol is full-duplex?', 'options' => ['SPI', 'I2C', '1-Wire', 'CAN'], 'ans' => 'SPI'],
        ['q' => 'What does UART stand for?', 'options' => ['Universal Asynchronous Receiver/Transmitter', 'Uniform Analog Routing Technology', 'Universal Active Radio Transmission', 'None of the above'], 'ans' => 'Universal Asynchronous Receiver/Transmitter'],
        ['q' => 'A pull-up resistor is used to...', 'options' => ['Increase current', 'Define a default HIGH state', 'Filter high frequencies', 'Protect from overvoltage'], 'ans' => 'Define a default HIGH state'],
        ['q' => 'Which sensor is commonly used to measure temperature?', 'options' => ['LDR', 'LM35', 'Ultrasonic', 'PIR'], 'ans' => 'LM35']
    ],
    'CO4' => [
        ['q' => 'What is the core function of an RTOS?', 'options' => ['Providing a GUI', 'File management', 'Meeting real-time deadlines', 'Network routing'], 'ans' => 'Meeting real-time deadlines'],
        ['q' => 'What is a semaphore used for?', 'options' => ['Speeding up execution', 'Synchronizing tasks/protecting resources', 'Memory allocation', 'Storing task context'], 'ans' => 'Synchronizing tasks/protecting resources'],
        ['q' => 'Priority inversion is solved by...', 'options' => ['Priority inheritance', 'Round robin scheduling', 'Disabling interrupts', 'Increasing clock speed'], 'ans' => 'Priority inheritance'],
        ['q' => 'A task in an RTOS that is waiting for a timer to expire is in which state?', 'options' => ['Running', 'Ready', 'Blocked', 'Suspended'], 'ans' => 'Blocked'],
        ['q' => 'Which scheduling algorithm runs tasks for a fixed time slice?', 'options' => ['Rate Monotonic', 'Earliest Deadline First', 'Round Robin', 'First Come First Serve'], 'ans' => 'Round Robin'],
        ['q' => 'What is context switching?', 'options' => ['Changing power states', 'Saving current task state and loading another', 'Switching hardware ports', 'Updating firmware'], 'ans' => 'Saving current task state and loading another'],
        ['q' => 'Which of the following is a type of IPC?', 'options' => ['Message Queues', 'ADC', 'PWM', 'Watchdog'], 'ans' => 'Message Queues'],
        ['q' => 'A mutex is similar to a binary semaphore but includes...', 'options' => ['Priority inheritance', 'Multiple counts', 'Faster execution', 'Less memory usage'], 'ans' => 'Priority inheritance'],
        ['q' => 'What does preemptive scheduling mean?', 'options' => ['Tasks run until completion', 'Higher priority tasks can interrupt lower priority tasks', 'Tasks are scheduled randomly', 'Tasks share CPU equally'], 'ans' => 'Higher priority tasks can interrupt lower priority tasks'],
        ['q' => 'Which state is a task in when it is first created but not yet scheduled?', 'options' => ['Running', 'Ready', 'Blocked', 'Suspended'], 'ans' => 'Ready']
    ]
];
file_put_contents('expanded_mocks.php', '<?php return ' . var_export($mockMCQs, true) . ';');
