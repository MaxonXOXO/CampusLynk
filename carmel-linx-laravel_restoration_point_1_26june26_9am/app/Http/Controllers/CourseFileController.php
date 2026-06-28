<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\SubjectStaffAssignment;
use App\Models\BatchSubject;
use App\Models\CfCourseFile;
use App\Models\CfSectionAPlanning;
use App\Models\CfSectionBMaterials;
use App\Models\CfSectionCAssessments;
use App\Models\CfSectionDAttainments;
use App\Models\Subject;

class CourseFileController extends Controller
{
    /**
     * Get the list of subjects assigned to the logged-in staff member.
     */
    public function getStaffSubjects(Request $request)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 401);
        }

        // Get assigned subjects
        $assignments = SubjectStaffAssignment::with(['batchSubject.classroom'])
            ->where('staff_mobile_no', $mobileNo)
            ->get();

        $tree = [];
        
        foreach ($assignments as $assignment) {
            $bs = $assignment->batchSubject;
            if (!$bs || !$bs->classroom) continue;

            $batchYear = $bs->classroom->batch_year;
            $branch = $bs->classroom->branch;
            $batchKey = $batchYear . '|' . $branch;
            
            $semester = $bs->semester;
            
            // Generate Course File record
            $academicYear = '2026-2027'; 
            $courseFile = CfCourseFile::firstOrCreate(
                ['batch_subject_id' => $bs->id, 'academic_year' => $academicYear],
                ['status' => 'Draft']
            );

            if (!isset($tree[$batchKey])) {
                $tree[$batchKey] = [
                    'batch_year' => $batchYear,
                    'branch' => $branch,
                    'semesters' => []
                ];
            }

            if (!isset($tree[$batchKey]['semesters'][$semester])) {
                $tree[$batchKey]['semesters'][$semester] = [
                    'semester' => $semester,
                    'courses' => []
                ];
            }

            $tree[$batchKey]['semesters'][$semester]['courses'][] = [
                'batch_subject_id' => $bs->id,
                'course_file_id'   => $courseFile->id,
                'subject_code'     => $bs->subject_code,
                'subject_name'     => $bs->subject_name,
                'status'           => $courseFile->status,
                'pdf_url'          => $courseFile->generated_pdf_path ? url($courseFile->generated_pdf_path) : null
            ];
        }

        // Re-index associative arrays to sequential for JSON
        $formattedTree = [];
        foreach ($tree as $bk => $batchData) {
            $sems = [];
            foreach ($batchData['semesters'] as $sk => $semData) {
                $sems[] = $semData;
            }
            // sort by semester
            usort($sems, function($a, $b) { return $a['semester'] <=> $b['semester']; });
            
            $batchData['semesters'] = $sems;
            $formattedTree[] = $batchData;
        }

        // sort by batch year descending
        usort($formattedTree, function($a, $b) { return $b['batch_year'] <=> $a['batch_year']; });

        return response()->json(['status' => 'SUCCESS', 'batches' => $formattedTree]);
    }

    /**
     * Get the details of a specific Course File (Sections A, B, C, D)
     */
    public function getCourseFile($id)
    {
        $cf = CfCourseFile::with(['sectionA', 'sectionB', 'sectionC', 'sectionD', 'batchSubject.classroom', 'documents'])->find($id);
        
        if (!$cf) {
            return response()->json(['status' => 'ERROR', 'message' => 'Course File not found'], 404);
        }

        // Return the structured data. In a real app we'd also pull the dynamic data (Syllabus, etc.) here
        // But the front-end can also just display it, or we merge it.
        return response()->json([
            'status' => 'SUCCESS',
            'course_file' => [
                'id' => $cf->id,
                'status' => $cf->status,
                'subject_name' => $cf->batchSubject->subject_name ?? '',
                'section_a' => $cf->sectionA ?? [],
                'section_b' => $cf->sectionB ?? [],
                'section_c' => $cf->sectionC ?? [],
                'section_d' => $cf->sectionD ?? [],
            ]
        ]);
    }

    /**
     * Save the details of a specific Course File (Sections A, B, C, D)
     */
    public function saveCourseFile(Request $request, $id)
    {
        $cf = CfCourseFile::find($id);
        if (!$cf) {
            return response()->json(['status' => 'ERROR', 'message' => 'Course File not found'], 404);
        }

        // Section A
        if ($request->has('section_a')) {
            CfSectionAPlanning::updateOrCreate(
                ['cf_id' => $id],
                $request->input('section_a')
            );
        }

        // Section B
        if ($request->has('section_b')) {
            CfSectionBMaterials::updateOrCreate(
                ['cf_id' => $id],
                $request->input('section_b')
            );
        }

        // Section C
        if ($request->has('section_c')) {
            CfSectionCAssessments::updateOrCreate(
                ['cf_id' => $id],
                $request->input('section_c')
            );
        }

        // Section D
        if ($request->has('section_d')) {
            CfSectionDAttainments::updateOrCreate(
                ['cf_id' => $id],
                $request->input('section_d')
            );
        }

        if ($request->has('status')) {
            $cf->status = $request->input('status');
            $cf->save();
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Course File saved successfully']);
    }

    /**
     * Generate the A4 PDF for the Course File
     */
    public function generatePdf($id)
    {
        $cf = CfCourseFile::with(['sectionA', 'sectionB', 'sectionC', 'sectionD', 'batchSubject.classroom', 'documents'])->find($id);
        if (!$cf) {
            return response()->json(['status' => 'ERROR', 'message' => 'Course File not found'], 404);
        }

        try {
            // using Barryvdh\DomPDF\Facade\Pdf
            $pdf = \PDF::loadView('course_file_a4', ['courseFile' => $cf]);
            $pdf->setPaper('a4', 'portrait');

            // Generate a unique filename
            $fileName = 'CourseFile_' . ($cf->batchSubject->subject_code ?? 'Sub') . '_' . $cf->id . '.pdf';
            $path = 'public/course_files/' . $fileName;
            
            // Save to storage
            \Storage::put($path, $pdf->output());

            // Update database record
            $cf->generated_pdf_path = 'storage/course_files/' . $fileName;
            $cf->status = 'Complete';
            $cf->save();

            // Download directly or redirect to URL
            return $pdf->download($fileName);
            
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'PDF Generation Failed: ' . $e->getMessage()], 500);
        }
    }

    public function previewDocument($id, $docNo)
    {
        $cf = CfCourseFile::find($id);
        if (!$cf) {
            return response()->json(['status' => 'ERROR', 'message' => 'Course file not found']);
        }

        if ($docNo == 3) {
            $html = $this->previewDocument3($cf);
            return response()->json(['status' => 'SUCCESS', 'html' => $html]);
        }

        if ($docNo == 4) {
            $html = $this->previewDocument4($cf);
            return response()->json(['status' => 'SUCCESS', 'html' => $html]);
        }

        if ($docNo == 5) {
            $html = $this->previewDocument5($cf);
            return response()->json(['status' => 'SUCCESS', 'html' => $html]);
        }

        if ($docNo == 6) {
            $html = $this->previewDocument6($cf);
            return response()->json(['status' => 'SUCCESS', 'html' => $html]);
        }

        if ($docNo == 8) {
            $html = $this->previewDocument8($cf);
            return response()->json(['status' => 'SUCCESS', 'html' => $html]);
        }

        if ($docNo == 11) {
            $html = $this->previewDocument11($cf);
            return response()->json(['status' => 'SUCCESS', 'html' => $html]);
        }

        $html = "<div class='p-8 text-center'>
            <h2 class='text-2xl font-black mb-4'>Document ".$docNo." Preview</h2>
            <p class='text-gray-600 mb-8'>This is a live preview of Document ".$docNo.". The exact A4 print layout will be integrated here once the models are provided.</p>
            <div class='border-4 border-dashed border-gray-200 rounded-2xl p-12 bg-gray-50 text-gray-400 font-bold'>
                A4 Document Layout Placeholder
            </div>
        </div>";

        return response()->json(['status' => 'SUCCESS', 'html' => $html]);
    }


    /**
     * Save custom payload data for a specific document (e.g., student list remarks).
     */
    public function saveDocumentPayload(Request $request, $id, $docNo)
    {
        $cf = CfCourseFile::find($id);
        if (!$cf) {
            return response()->json(['status' => 'ERROR', 'message' => 'Course file not found']);
        }

        $doc = \App\Models\CfCourseFileDocument::firstOrCreate(
            ['course_file_id' => $cf->id, 'document_number' => $docNo],
            ['document_name' => 'Document ' . $docNo]
        );

        $doc->data_payload = $request->input('payload');
        // Auto-check it when saving payload
        $doc->is_checked = true;
        $doc->save();

        return response()->json(['status' => 'SUCCESS', 'message' => 'Document saved successfully']);
    }

    /**
     * Helper to render Document 3: Student List
     */
    private function previewDocument3($cf)
    {
        $doc = \App\Models\CfCourseFileDocument::where('course_file_id', $cf->id)->where('document_number', 3)->first();
        $payload = $doc && $doc->data_payload ? json_decode($doc->data_payload, true) : null;

        if (!$payload) {
            // Generate initial payload
            $bs = $cf->batchSubject;
            if (!$bs || !$bs->classroom_id) {
                return "<div class='text-red-500 font-bold p-8'>Classroom mapping not found for this subject.</div>";
            }
            $students = \App\Models\Student::where('classroom_id', $bs->classroom_id)->orderBy('name')->get();
            
            $payload = [];
            $roll = 1;
            foreach ($students as $s) {
                $payload[] = [
                    'roll_no' => $roll++,
                    'reg_no' => $s->reg_no,
                    'name' => $s->name,
                    'type' => $s->admission_type ?? 'Regular',
                    'remarks' => ''
                ];
            }
        }

        return view('course_files.preview_doc_3', compact('payload', 'cf'))->render();
    }

    /**
     * Helper to render Document 4: Course Syllabus
     */
    private function previewDocument4($cf)
    {
        $oldCourseFile = \Illuminate\Support\Facades\DB::table('course_files')
            ->where('batch_subject_id', $cf->batch_subject_id)
            ->first();

        $pdfUrl = null;
        if ($oldCourseFile && !empty($oldCourseFile->syllabus_pdf_path)) {
            $pdfUrl = url($oldCourseFile->syllabus_pdf_path);
        }

        return view('course_files.preview_doc_4', compact('pdfUrl', 'cf'))->render();
    }

    /**
     * Handle Course Information Sheet (CIS) PDF upload for Document 5
     */
    public function uploadCisPdf(Request $request, $id)
    {
        $request->validate([
            'cis_pdf' => 'required|mimes:pdf|max:10240'
        ]);

        $cf = \App\Models\CfCourseFile::find($id);
        if (!$cf || !$cf->batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Invalid course file']);
        }

        $subjectCode = $cf->batchSubject->subject_code;

        if ($request->hasFile('cis_pdf')) {
            $file = $request->file('cis_pdf');
            $filename = time() . '_' . $subjectCode . '_CIS.pdf';
            $path = $file->move(public_path('uploads/cis'), $filename);
            
            // Save to syllabus_registry to reuse across batches
            \Illuminate\Support\Facades\DB::table('syllabus_registry')
                ->updateOrInsert(
                    ['subject_code' => $subjectCode],
                    ['cis_pdf_path' => 'uploads/cis/' . $filename, 'updated_at' => now()]
                );

            return response()->json(['status' => 'SUCCESS', 'message' => 'CIS PDF Uploaded']);
        }
        return response()->json(['status' => 'ERROR', 'message' => 'Upload failed']);
    }

    /**
     * Helper to render Document 5: Course Information Sheet
     */
    private function previewDocument5($cf)
    {
        $subjectCode = $cf->batchSubject->subject_code ?? null;
        $registry = \Illuminate\Support\Facades\DB::table('syllabus_registry')->where('subject_code', $subjectCode)->first();
        
        $pdfUrl = null;
        if ($registry && !empty($registry->cis_pdf_path)) {
            $pdfUrl = url($registry->cis_pdf_path);
        }

        return view('course_files.preview_doc_5', compact('pdfUrl', 'cf'))->render();
    }

    /**
     * Handle saving the CO-PO Mapping for Document 6
     */
    public function saveCoPoMapping(Request $request, $id)
    {
        $cf = \App\Models\CfCourseFile::find($id);
        if (!$cf || !$cf->batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Invalid course file']);
        }

        $subjectCode = $cf->batchSubject->subject_code;
        $mappingData = $request->input('co_po_mapping');

        // Save to syllabus_registry to reuse across batches
        \Illuminate\Support\Facades\DB::table('syllabus_registry')
            ->updateOrInsert(
                ['subject_code' => $subjectCode],
                ['co_po_mapping' => json_encode($mappingData), 'updated_at' => now()]
            );

        // Auto check document 6
        $doc = \App\Models\CfCourseFileDocument::firstOrCreate(
            ['course_file_id' => $cf->id, 'document_number' => 6],
            ['document_name' => 'Document 6']
        );
        $doc->is_checked = true;
        $doc->save();

        return response()->json(['status' => 'SUCCESS', 'message' => 'CO-PO Mapping saved']);
    }

    /**
     * Helper to render Document 6: Course Outcomes & CO-PO Mapping
     */
    private function previewDocument6($cf)
    {
        $subjectCode = $cf->batchSubject->subject_code ?? null;
        $registry = \Illuminate\Support\Facades\DB::table('syllabus_registry')->where('subject_code', $subjectCode)->first();
        
        $mapping = null;
        $cisPdfUrl = null;
        $coCount = 4; // Default to 4 COs

        if ($registry) {
            if (!empty($registry->co_po_mapping)) {
                $mapping = json_decode($registry->co_po_mapping, true);
            }
            if (!empty($registry->cis_pdf_path)) {
                $cisPdfUrl = url($registry->cis_pdf_path);
            }
            if (!empty($registry->co_count)) {
                $coCount = $registry->co_count;
            }
        }

        // Generate an empty mapping grid if none exists (Dynamic COs, PO1-PO11, PSO1-PSO3)
        if (!$mapping) {
            $mapping = [];
            for ($i = 1; $i <= $coCount; $i++) {
                $row = ['co' => 'CO' . $i, 'description' => ''];
                for ($j = 1; $j <= 11; $j++) $row['po' . $j] = '';
                for ($k = 1; $k <= 3; $k++) $row['pso' . $k] = '';
                $mapping[] = $row;
            }
        }

        return view('course_files.preview_doc_6', compact('mapping', 'cf', 'cisPdfUrl', 'coCount'))->render();
    }

    /**
     * Helper to render Document 8: Course Plan
     */
    private function previewDocument8($cf)
    {
        $batchSubjectId = $cf->batch_subject_id;
        
        $lessonPlans = \Illuminate\Support\Facades\DB::table('lesson_plans')
            ->where('batch_subject_id', $batchSubjectId)
            ->orderBy('day_no')
            ->get();

        return view('course_files.preview_doc_8', compact('lessonPlans', 'cf'))->render();
    }

    /**
     * Helper to render Document 11: Internal Examination Result Analysis
     */
    private function previewDocument11($cf)
    {
        $subjectCode = $cf->batchSubject->subject_code ?? null;
        
        // Fetch all itemized marks
        $rawMarks = \Illuminate\Support\Facades\DB::table('academic_marks')
            ->join('students', 'academic_marks.reg_no', '=', 'students.reg_no')
            ->select('students.name', 'academic_marks.reg_no', 'academic_marks.category', 'academic_marks.co_tag', 'academic_marks.marks_obtained', 'academic_marks.max_marks')
            ->where('academic_marks.subject_code', $subjectCode)
            ->get();

        $maxMarks = [
            'tests' => ['CO1' => 0, 'CO2' => 0, 'CO3' => 0, 'CO4' => 0],
            'assignments' => ['CO1' => 0, 'CO2' => 0, 'CO3' => 0, 'CO4' => 0],
            'online' => ['CO1' => 0, 'CO2' => 0, 'CO3' => 0, 'CO4' => 0],
        ];

        // Restructure data by student
        $students = [];
        foreach ($rawMarks as $mark) {
            $reg = $mark->reg_no;
            if (!isset($students[$reg])) {
                $students[$reg] = [
                    'reg_no' => $reg,
                    'name' => $mark->name,
                    'tests' => ['CO1' => '-', 'CO2' => '-', 'CO3' => '-', 'CO4' => '-'],
                    'assignments' => ['CO1' => '-', 'CO2' => '-', 'CO3' => '-', 'CO4' => '-'],
                    'online' => ['CO1' => '-', 'CO2' => '-', 'CO3' => '-', 'CO4' => '-'],
                    'total' => 0
                ];
            }
            
            $val = floatval($mark->marks_obtained);
            $students[$reg]['total'] += $val;
            
            $co = $mark->co_tag ?? 'CO1';
            $maxVal = floatval($mark->max_marks);
            
            if ($mark->category == 'Written Test') {
                $students[$reg]['tests'][$co] = $val;
                $maxMarks['tests'][$co] = max($maxMarks['tests'][$co], $maxVal);
            }
            elseif ($mark->category == 'Assignment') {
                $students[$reg]['assignments'][$co] = $val;
                $maxMarks['assignments'][$co] = max($maxMarks['assignments'][$co], $maxVal);
            }
            elseif ($mark->category == 'Online Test') {
                $students[$reg]['online'][$co] = $val;
                $maxMarks['online'][$co] = max($maxMarks['online'][$co], $maxVal);
            }
        }

        $grandMax = array_sum($maxMarks['tests']) + array_sum($maxMarks['assignments']) + array_sum($maxMarks['online']);
        if ($grandMax == 0) $grandMax = 1; // prevent div zero

        // Calculate Out of 100
        foreach ($students as $reg => $s) {
            $students[$reg]['out_of_100'] = round(($s['total'] / $grandMax) * 100, 1);
        }

        // Sort alphabetically
        usort($students, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return view('course_files.preview_doc_11', compact('students', 'cf', 'maxMarks', 'grandMax'))->render();
    }
}
