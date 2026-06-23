<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TestEngineController extends Controller
{
    // MOCK MCQ POOL for Demo Purposes
    private $mockMCQs = [
        'CO1' => [
            ['q' => 'Which of the following is a primary feature of an embedded system?', 'options' => ['High power consumption', 'General purpose computing', 'Real-time performance constraints', 'Requires a monitor'], 'ans' => 'Real-time performance constraints'],
            ['q' => 'What is the function of a watchdog timer?', 'options' => ['Keep real time', 'Reset the system on software hang', 'Manage battery life', 'Increase CPU speed'], 'ans' => 'Reset the system on software hang'],
            ['q' => 'Which memory is typically used to store the application firmware?', 'options' => ['SRAM', 'EEPROM', 'Flash', 'DRAM'], 'ans' => 'Flash'],
            ['q' => 'An embedded system must be...', 'options' => ['Application specific', 'Tightly constrained', 'Reactive to environment', 'All of the above'], 'ans' => 'All of the above'],
            ['q' => 'Which bus architecture uses separate paths for data and instructions?', 'options' => ['Von Neumann', 'Harvard', 'PCI', 'USB'], 'ans' => 'Harvard']
        ],
        'CO2' => [
            ['q' => 'What is the width of an AVR general purpose register?', 'options' => ['8-bit', '16-bit', '32-bit', '64-bit'], 'ans' => '8-bit'],
            ['q' => 'Which register holds the status flags in AVR?', 'options' => ['PC', 'SP', 'SREG', 'TCNT'], 'ans' => 'SREG'],
            ['q' => 'What is the size of Flash memory in Atmega32?', 'options' => ['8 KB', '16 KB', '32 KB', '64 KB'], 'ans' => '32 KB'],
            ['q' => 'How many I/O pins are available in Atmega32?', 'options' => ['16', '32', '40', '64'], 'ans' => '32'],
            ['q' => 'Which flag is set when an arithmetic operation results in a zero?', 'options' => ['Carry Flag', 'Zero Flag', 'Sign Flag', 'Overflow Flag'], 'ans' => 'Zero Flag']
        ],
        'CO3' => [
            ['q' => 'What does PWM stand for?', 'options' => ['Power Width Measurement', 'Pulse Width Modulation', 'Phase Wave Modulation', 'Periodic Width Modulation'], 'ans' => 'Pulse Width Modulation'],
            ['q' => 'Which component is used to isolate high voltage circuits from microcontrollers?', 'options' => ['Capacitor', 'Inductor', 'Optocoupler', 'Resistor'], 'ans' => 'Optocoupler'],
            ['q' => 'A stepper motor is preferred for...', 'options' => ['High speed rotation', 'Precise angular positioning', 'High torque at high speeds', 'Continuous unmonitored rotation'], 'ans' => 'Precise angular positioning'],
            ['q' => 'Which pins are used for I2C communication?', 'options' => ['TX, RX', 'MOSI, MISO', 'SDA, SCL', 'PWM, ADC'], 'ans' => 'SDA, SCL'],
            ['q' => 'Debouncing is primarily required when interfacing...', 'options' => ['LEDs', 'Motors', 'Mechanical Switches', 'LCDs'], 'ans' => 'Mechanical Switches']
        ],
        'CO4' => [
            ['q' => 'What is the core function of an RTOS?', 'options' => ['Providing a GUI', 'File management', 'Meeting real-time deadlines', 'Network routing'], 'ans' => 'Meeting real-time deadlines'],
            ['q' => 'What is a semaphore used for?', 'options' => ['Speeding up execution', 'Synchronizing tasks/protecting resources', 'Memory allocation', 'Storing task context'], 'ans' => 'Synchronizing tasks/protecting resources'],
            ['q' => 'Priority inversion is solved by...', 'options' => ['Priority inheritance', 'Round robin scheduling', 'Disabling interrupts', 'Increasing clock speed'], 'ans' => 'Priority inheritance'],
            ['q' => 'A task in an RTOS that is waiting for a timer to expire is in which state?', 'options' => ['Running', 'Ready', 'Blocked', 'Suspended'], 'ans' => 'Blocked'],
            ['q' => 'Which scheduling algorithm runs tasks for a fixed time slice?', 'options' => ['Rate Monotonic', 'Earliest Deadline First', 'Round Robin', 'First Come First Serve'], 'ans' => 'Round Robin']
        ]
    ];

    // Lecturer: Publish a new Online Test
    public function publishOnlineTest(Request $request, $subjectId)
    {
        $role = Session::get('userRole');
        if ($role !== 'Lecturer') return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);

        // classroom_id is required
        $classroomId = DB::table('class_management')->where('subject_code', $subjectId)->value('classroom_id');
        if (!$classroomId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Classroom not found.']);
        }

        $cos = $request->input('cos', []);
        $attempts = $request->input('attempts', 1);
        $duration = $request->input('duration', 30);
        $start = $request->input('start');
        $end = $request->input('end');

        if (empty($cos)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Please select at least one CO.']);
        }

        // Generate MCQ payload based on selected COs
        $payload = [];
        $totalMcq = 0;
        foreach ($cos as $co) {
            if (isset($this->mockMCQs[$co])) {
                $pool = $this->mockMCQs[$co];
                shuffle($pool);
                $selected = array_slice($pool, 0, 3); // Grab 3 MCQs per CO for demo
                foreach ($selected as $q) {
                    $q['co'] = $co;
                    $payload[] = $q;
                    $totalMcq++;
                }
            }
        }

        // Save to test_configs
        DB::table('test_configs')->insert([
            'test_id' => DB::raw('(UUID())'),
            'subject_code' => $subjectId,
            'classroom_id' => $classroomId,
            'test_name' => 'Online MCQ Test - ' . implode(', ', $cos),
            'start_time' => $start ?: now(),
            'end_time' => $end,
            'duration' => $duration,
            'selected_cos' => json_encode($cos),
            'mcq_count' => $totalMcq,
            'target_percentage' => 50,
            'pass_threshold' => 40,
            'is_active' => true,
            'max_attempts' => $attempts,
            'is_auto_scheduled' => $start ? true : false,
            'questions_payload' => json_encode($payload),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['status' => 'SUCCESS', 'message' => 'Online Test Published successfully.']);
    }

    // Lecturer: Get Active Online Tests
    public function getActiveTestsLecturer(Request $request, $subjectId)
    {
        $tests = DB::table('test_configs')
            ->where('subject_code', $subjectId)
            ->where('test_name', 'like', 'Online MCQ Test%')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($tests as $t) {
            $t->student_count = DB::table('test_attempts')->where('test_id', $t->test_id)->distinct('reg_no')->count('reg_no');
            $t->completed_count = DB::table('test_attempts')->where('test_id', $t->test_id)->where('status', 'completed')->count();
        }

        return response()->json(['status' => 'SUCCESS', 'data' => $tests]);
    }

    // Lecturer: Generate PDF Data Report
    public function generateTestReport(Request $request, $testId)
    {
        $test = DB::table('test_configs')->where('test_id', $testId)->first();
        if (!$test) return response()->json(['status' => 'ERROR', 'message' => 'Test not found']);

        // Get attempts (best score per student)
        $attempts = DB::table('test_attempts')
            ->join('students', 'test_attempts.reg_no', '=', 'students.reg_no')
            ->where('test_id', $testId)
            ->where('status', 'completed')
            ->select('students.reg_no', 'students.name', 'test_attempts.total_score', 'test_attempts.start_time', 'test_attempts.end_time', 'test_attempts.attempt_number')
            ->orderBy('test_attempts.total_score', 'desc')
            ->get();

        return response()->json([
            'status' => 'SUCCESS',
            'test_info' => $test,
            'report' => $attempts
        ]);
    }

    // Student: Get Available Tests
    public function getAvailableTests(Request $request)
    {
        $regNo = Session::get('userId');
        
        // Find student's classroom logic (simplified, assuming they are in the class if active)
        $tests = DB::table('test_configs')
            ->where('test_name', 'like', 'Online MCQ Test%')
            ->where('is_active', true)
            ->get();

        $activeTests = [];
        foreach ($tests as $t) {
            $attemptsCount = DB::table('test_attempts')
                ->where('test_id', $t->test_id)
                ->where('reg_no', $regNo)
                ->count();
            
            $bestScore = DB::table('test_attempts')
                ->where('test_id', $t->test_id)
                ->where('reg_no', $regNo)
                ->where('status', 'completed')
                ->max('total_score');

            $t->my_attempts = $attemptsCount;
            $t->best_score = $bestScore;
            $t->can_take = ($attemptsCount < $t->max_attempts);

            // Filter out tests that haven't started or already ended
            $now = now();
            if ($t->start_time && $now < $t->start_time) continue;
            if ($t->end_time && $now > $t->end_time) $t->can_take = false; // Overdue but show for reference

            $activeTests[] = $t;
        }

        return response()->json(['status' => 'SUCCESS', 'data' => $activeTests]);
    }

    // Student: Start Test
    public function startTest(Request $request, $testId)
    {
        $regNo = Session::get('userId');
        $test = DB::table('test_configs')->where('test_id', $testId)->first();
        if (!$test) return response()->json(['status' => 'ERROR', 'message' => 'Test not found']);

        $attemptsCount = DB::table('test_attempts')->where('test_id', $testId)->where('reg_no', $regNo)->count();
        if ($attemptsCount >= $test->max_attempts) {
            return response()->json(['status' => 'ERROR', 'message' => 'Maximum attempts reached.']);
        }

        // Create attempt
        $attemptId = DB::table('test_attempts')->insertGetId([
            'attempt_id' => DB::raw('(UUID())'),
            'reg_no' => $regNo,
            'test_id' => $testId,
            'attempt_number' => $attemptsCount + 1,
            'start_time' => now(),
            'status' => 'in_progress',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $fullPayload = json_decode($test->questions_payload, true);
        
        // Strip answers before sending to client
        $safePayload = array_map(function($q) {
            unset($q['ans']);
            return $q;
        }, $fullPayload);

        return response()->json([
            'status' => 'SUCCESS', 
            'attempt_id' => $attemptId, // UUID isn't returned by insertGetId easily, but we can query it
            'duration' => $test->duration,
            'questions' => $safePayload
        ]);
    }

    // Student: Submit Test
    public function submitTest(Request $request, $testId)
    {
        $regNo = Session::get('userId');
        $answers = $request->input('answers', []); // ['0' => 'option A', '1' => 'option B'] array by index
        
        $test = DB::table('test_configs')->where('test_id', $testId)->first();
        if (!$test) return response()->json(['status' => 'ERROR', 'message' => 'Test not found']);

        // Find active attempt
        $attempt = DB::table('test_attempts')->where('test_id', $testId)->where('reg_no', $regNo)->where('status', 'in_progress')->orderBy('start_time', 'desc')->first();
        if (!$attempt) return response()->json(['status' => 'ERROR', 'message' => 'No active attempt found.']);

        $payload = json_decode($test->questions_payload, true);
        $score = 0;
        $total = count($payload);
        $results = []; // Detailed results for summary

        foreach ($payload as $index => $q) {
            $studentAns = $answers[$index] ?? null;
            $isCorrect = ($studentAns === $q['ans']);
            if ($isCorrect) $score++;

            $results[] = [
                'q' => $q['q'],
                'student_ans' => $studentAns,
                'correct_ans' => $q['ans'],
                'is_correct' => $isCorrect,
                'co' => $q['co']
            ];
        }

        // Update attempt
        DB::table('test_attempts')->where('attempt_id', $attempt->attempt_id)->update([
            'end_time' => now(),
            'total_score' => $score,
            'status' => 'completed',
            'updated_at' => now()
        ]);

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Test submitted successfully.',
            'summary' => [
                'score' => $score,
                'total' => $total,
                'percentage' => round(($score / $total) * 100, 2),
                'details' => $results
            ]
        ]);
    }
}
