<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\R26ClassManagement;
use App\Models\Student;
use App\Models\CourseFile;
use App\Models\LessonPlan;

class R26ClassroomController extends Controller
{
    /**
     * View Virtual Classroom (Theory) for Revision 2026.
     */
    public function viewTheoryClassroom($subjectId)
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        // Fetch classroom (check standard table first, then fallback to R26)
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }

        if (!$classroom) {
            abort(404, 'Classroom association not found.');
        }

        // Get enrolled students
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        // Course file data
        $courseFile = CourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            [
                'parsed_cos' => json_encode([
                    ['id' => 'CO1', 'description' => 'Understand and explain the core concepts of the subject.', 'duration' => 15, 'cognitive_level' => 'Understanding'],
                    ['id' => 'CO2', 'description' => 'Apply theoretical methodologies to solve problems.', 'duration' => 15, 'cognitive_level' => 'Applying'],
                    ['id' => 'CO3', 'description' => 'Analyze systems and evaluate outcomes.', 'duration' => 15, 'cognitive_level' => 'Analyzing'],
                    ['id' => 'CO4', 'description' => 'Formulate, design, or optimize solutions.', 'duration' => 15, 'cognitive_level' => 'Creating']
                ]),
                'parsed_modules' => json_encode([
                    ['module_id' => 'I', 'content' => 'Module 1 Course Contents'],
                    ['module_id' => 'II', 'content' => 'Module 2 Course Contents'],
                    ['module_id' => 'III', 'content' => 'Module 3 Course Contents'],
                    ['module_id' => 'IV', 'content' => 'Module 4 Course Contents']
                ]),
                'parsed_textbooks' => json_encode([
                    'Textbook Reference 1',
                    'Textbook Reference 2'
                ])
            ]
        );

        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get();

        return view('r26.virtual_classroom_theory', compact('batchSubject', 'classroom', 'students', 'courseFile', 'lessonPlans'));
    }

    /**
     * Upload and parse Revision 2026 Syllabus PDF locally.
     */
    public function uploadSyllabus(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
        }

        $request->validate([
            'syllabus_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            $file = $request->file('syllabus_file');
            $path = $file->store('r26_syllabi', 'public');

            // Parse raw text locally using Smalot Parser
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile(storage_path('app/public/' . $path));
            $text = $pdf->getText();

            // Extract metadata via Regex
            $credit = 3; // default
            if (preg_match('/(?:Credits?|Credit\s*Value)\s*[:\-]?\s*(\d+)/i', $text, $matches)) {
                $credit = (int)$matches[1];
            }

            $cie = 40; // default
            if (preg_match('/(?:CIE|Continuous\s*Internal\s*(?:Assessment|Evaluation))\s*[:\-]?\s*(\d+)/i', $text, $matches)) {
                $cie = (int)$matches[1];
            }

            $ese = 60; // default
            if (preg_match('/(?:ESE|End\s*Semester\s*(?:Examination|Evaluation))\s*[:\-]?\s*(\d+)/i', $text, $matches)) {
                $ese = (int)$matches[1];
            }

            $ltpr = '3:0:0:0'; // default
            if (preg_match('/L\s*[:\-]?\s*T\s*[:\-]?\s*P\s*[:\-]?\s*R\s*[:\-]?\s*(\d+\s*[:\-]?\s*\d+\s*[:\-]?\s*\d+\s*[:\-]?\s*\d+)/i', $text, $matches)) {
                $ltpr = str_replace(' ', '', trim($matches[1]));
            } elseif (preg_match('/(?:L\s*-\s*T\s*-\s*P\s*-\s*R)\s*[:\-]?\s*(\d+\s*-\s*\d+\s*-\s*\d+\s*-\s*\d+)/i', $text, $matches)) {
                $ltpr = str_replace('-', ':', str_replace(' ', '', trim($matches[1])));
            }

            $totalHours = 60; // default
            if (preg_match('/(?:Instructional\s*Hours|Total\s*(?:Instructional\s*)?Hours)\s*[:\-]?\s*(\d+)/i', $text, $matches)) {
                $totalHours = (int)$matches[1];
            }

            // Extract Course Outcomes (CO1 to CO10)
            $cos = [];
            $lines = explode("\n", $text);
            $currentCo = null;
            $currentDesc = '';
            $currentCog = 'Apply';

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                if (preg_match('/^(CO\d+)\s*(.*)$/i', $line, $m)) {
                    if ($currentCo && $currentDesc) {
                        $cos[$currentCo] = [
                            'id' => $currentCo,
                            'description' => trim($currentDesc),
                            'cognitive_level' => $currentCog
                        ];
                    }

                    $coId = strtoupper($m[1]);
                    $desc = trim($m[2]);

                    if (preg_match('/^[\d\- \t]+$/', $desc)) {
                        $currentCo = null;
                        $currentDesc = '';
                        continue;
                    }

                    $currentCo = $coId;
                    $currentCog = 'Apply';
                    if (preg_match('/(Remember|Understand|Apply|Analyze|Evaluate|Create)$/i', $desc, $cogM)) {
                        $currentCog = $cogM[1];
                        $desc = trim(substr($desc, 0, -strlen($currentCog)));
                    }
                    $currentDesc = $desc;
                } else if ($currentCo) {
                    if (preg_match('/^(Module|Detailed|Suggested|TEXT|Sl\.|Suggested|Learning|Assessment|Series|End|Duration|Exam|Converted|Total|CO-PO|COURSE|ARTICULATION|MATRIX|PROGRAM|OUTCOMES|Legends)/i', $line) || preg_match('/^[\d\- \t]+$/', $line)) {
                        if ($currentDesc) {
                            $cos[$currentCo] = [
                                'id' => $currentCo,
                                'description' => trim($currentDesc),
                                'cognitive_level' => $currentCog
                            ];
                        }
                        $currentCo = null;
                        $currentDesc = '';
                    } else {
                        $cogLevel = 'Apply';
                        if (preg_match('/(Remember|Understand|Apply|Analyze|Evaluate|Create)$/i', $line, $cogM)) {
                            $cogLevel = $cogM[1];
                            $line = trim(substr($line, 0, -strlen($cogLevel)));
                        }
                        if ($cogLevel !== 'Apply') {
                            $currentCog = $cogLevel;
                        }
                        $currentDesc .= ' ' . $line;
                    }
                }
            }
            if ($currentCo && $currentDesc) {
                $cos[$currentCo] = [
                    'id' => $currentCo,
                    'description' => trim($currentDesc),
                    'cognitive_level' => $currentCog
                ];
            }

            // Make sure CO array keys are sequential
            ksort($cos);
            $cos = array_values($cos);

            // Calculate durations
            $coCount = count($cos) ?: 4;
            $avgDuration = floor($totalHours / $coCount);
            foreach ($cos as &$co) {
                $co['duration'] = $avgDuration;
            }
            unset($co);

            // Extract CO-PO mappings (PO1 to PO11)
            $coPo = [];
            foreach ($cos as $co) {
                $row = [];
                for ($p = 1; $p <= 11; $p++) {
                    $row["PO$p"] = '-';
                }
                $coPo[$co['id']] = $row;
            }

            preg_match_all('/(CO\d+)\s+([\d\- \t]+)/i', $text, $mapMatches, PREG_SET_ORDER);
            foreach ($mapMatches as $match) {
                $coId = strtoupper($match[1]);
                if (isset($coPo[$coId])) {
                    $vals = preg_split('/[\s\t]+/', trim($match[2]));
                    for ($p = 1; $p <= min(count($vals), 11); $p++) {
                        $val = trim($vals[$p - 1]);
                        if ($val !== '-') {
                            $coPo[$coId]["PO$p"] = $val;
                        }
                    }
                }
            }

            // Extract Modules (major topics)
            $modules = [];
            if (preg_match('/Syllabus\s*–\s*Major\s*Topics(.*?)Detailed\s*Syllabus/is', $text, $majorTopicsMatch)) {
                $subtext = $majorTopicsMatch[1];
                $modParts = preg_split('/Module\s+([IVX]+)/i', $subtext);
                preg_match_all('/Module\s+([IVX]+)/i', $subtext, $modHeaders);

                for ($i = 0; $i < count($modHeaders[1]); $i++) {
                    $modId = $modHeaders[1][$i];
                    $contentBlock = trim($modParts[$i + 1]);

                    $hours = floor($totalHours / 4);
                    if (preg_match('/(\d+)\s+(\d+)\s*$/', $contentBlock, $hourMatches)) {
                        $hours = (int)$hourMatches[1] + (int)$hourMatches[2];
                        $contentBlock = trim(substr($contentBlock, 0, -strlen($hourMatches[0])));
                    }

                    $modLines = explode("\n", $contentBlock);
                    $titleParts = [];
                    $descParts = [];
                    foreach ($modLines as $l) {
                        $l = trim($l);
                        if (empty($l)) continue;
                        if (empty($titleParts) || (count($titleParts) < 2 && strlen($l) < 35 && !str_contains($l, ','))) {
                            $titleParts[] = $l;
                        } else {
                            $descParts[] = $l;
                        }
                    }

                    $title = implode(' ', $titleParts);
                    $description = implode(' ', $descParts);

                    if (!$description && $title) {
                        $description = $title;
                        $title = "Module " . $modId;
                    }

                    $modules[] = [
                        'module_id' => $modId,
                        'title' => $title,
                        'content' => $description,
                        'hours' => $hours
                    ];
                }
            }

            if (empty($modules)) {
                $modules = [
                    ['module_id' => 'I', 'title' => 'Matrices and Determinants', 'content' => 'Introduction, basic concepts and foundational definitions.', 'hours' => floor($totalHours / 4)],
                    ['module_id' => 'II', 'title' => 'Trigonometry', 'content' => 'Core analytical methodologies, modeling frameworks, and algorithms.', 'hours' => floor($totalHours / 4)],
                    ['module_id' => 'III', 'title' => 'Coordinate Geometry', 'content' => 'Advanced diagnostics, optimizations, validation, and design patterns.', 'hours' => floor($totalHours / 4)],
                    ['module_id' => 'IV', 'title' => 'Differential Calculus', 'content' => 'System integration, case studies, future scopes, and evaluation.', 'hours' => floor($totalHours / 4)]
                ];
            }

            // Pack credit, ltpr, cie, ese, totalHours and mappings inside parsed_copo JSON
            $packedCopo = [
                'credit' => $credit,
                'l_t_p_r' => $ltpr,
                'cie_marks' => $cie,
                'ese_marks' => $ese,
                'total_hours' => $totalHours,
                'mappings' => $coPo
            ];

            // Save details to course_files table
            $courseFile = CourseFile::updateOrCreate(
                ['batch_subject_id' => $subjectId],
                [
                    'syllabus_pdf_path' => $path,
                    'parsed_cos' => json_encode($cos),
                    'parsed_modules' => json_encode($modules),
                    'parsed_copo' => json_encode($packedCopo),
                    'parsed_textbooks' => json_encode(['Textbook Reference 1', 'Textbook Reference 2'])
                ]
            );

            // Populate Lesson Plans sequentially up to total hours
            LessonPlan::where('batch_subject_id', $subjectId)->delete();
            $coKeys = array_column($cos, 'id');
            for ($day = 1; $day <= $totalHours; $day++) {
                $coTag = $coKeys[($day - 1) % count($coKeys)];
                $modIndex = min(floor(($day - 1) / ($totalHours / count($modules))), count($modules) - 1);
                $modId = $modules[$modIndex]['module_id'];
                
                LessonPlan::create([
                    'batch_subject_id' => $subjectId,
                    'day_no'           => $day,
                    'co_id'            => $coTag,
                    'topic_content'    => "Topic covering Module {$modId} elements under {$coTag}",
                    'allocated_hours'  => 1,
                    'pedagogy'         => 'Lecture',
                    'status'           => 'Pending'
                ]);
            }

            return response()->json([
                'status' => 'SUCCESS', 
                'message' => 'Syllabus uploaded and parsed locally successfully. Lesson planner initialized.',
                'course_file' => $courseFile
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }
}
