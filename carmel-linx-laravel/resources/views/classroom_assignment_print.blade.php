<x-layouts.report-layout 
    title="Assignment Printout - {{ $coTag }} - {{ $subject->subject_code }}" 
    orientation="portrait" 
    documentNo="{{ $subject->subject_code }}/ASSIGN/{{ $coTag }}">

    <style>
        .meta-row { display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 11px; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; font-size: 11px; }
        .data-table th, .data-table td { border: 1px solid #1e293b; padding: 6px 8px; }
        .data-table th { background-color: #f1f5f9; font-weight: bold; }
        .rubrics-table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 25px; font-size: 11px; }
        .rubrics-table th, .rubrics-table td { border: 1px solid #1e293b; padding: 8px 10px; vertical-align: top; }
        .rubrics-table th { background-color: #f1f5f9; text-align: center; font-weight: bold; }
    </style>

    {{-- PAGE 1: QUESTION PAPER --}}
    <div>
        @include('reports.partials.header', [
            'title' => "ASSIGNMENT – " . (substr($coTag, 2) ?: '1') . " ({$coTag})",
            'subtitle' => "Topic: {$topicName}",
            'department' => $fullDepartment,
            'academicYear' => $assessmentYear,
            'semester' => "Semester {$romanSem}",
            'documentNo' => "{$subject->subject_code}/ASSIGN/{$coTag}"
        ])

        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 mb-4 text-xs">
            <div class="meta-row">
                <div><strong>Subject:</strong> {{ $subject->subject_code }} - {{ $subject->subject_name }}</div>
                <div><strong>Batch:</strong> {{ $cleanedBatch }} @if(str_contains($subject->classroom_id, 'LET')) <span class="bg-purple-100 text-purple-800 text-[10px] font-bold px-1.5 py-0.5 rounded border border-purple-300">LET</span> @endif</div>
            </div>
            <div class="meta-row">
                <div><strong>Last Date of Submission:</strong> {{ $dueDate }}</div>
                <div><strong>Maximum Marks:</strong> 20</div>
            </div>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%; text-align: center;">Q. No.</th>
                    <th style="width: 67%;">Questions</th>
                    <th style="width: 15%; text-align: center;">BT Level</th>
                    <th style="width: 10%; text-align: center;">Marks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $q)
                    <tr>
                        <td class="text-center font-bold" style="vertical-align: top;">{{ $q['q_no'] }}.</td>
                        <td style="vertical-align: top; line-height: 1.4;">{{ $q['question'] }}</td>
                        <td class="text-center font-semibold" style="vertical-align: top;">{{ $q['bt_level'] }}</td>
                        <td class="text-center font-bold" style="vertical-align: top;">{{ $q['marks'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-slate-500">No assignment questions generated for this outcome.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="text-xs font-bold uppercase tracking-wider text-slate-800 text-center mt-4 mb-2">Cognitive level wise Question Analysis</div>
        <table class="data-table max-w-lg mx-auto">
            <thead>
                <tr>
                    <th colspan="3" class="text-center">Cognitive Level</th>
                    <th rowspan="2" class="text-center align-middle" style="width: 30%;">No. of Questions</th>
                </tr>
                <tr>
                    <th class="text-center">Remember</th>
                    <th class="text-center">Understand</th>
                    <th class="text-center">Apply</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center font-bold">{{ $rememberCount > 0 ? $rememberCount : '-' }}</td>
                    <td class="text-center font-bold">{{ $understandCount > 0 ? $understandCount : '-' }}</td>
                    <td class="text-center font-bold">{{ $applyCount > 0 ? $applyCount : '-' }}</td>
                    <td class="text-center font-bold">{{ $totalQuestions }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right font-bold">Total Marks =</td>
                    <td class="text-center font-bold">20</td>
                </tr>
            </tbody>
        </table>

        @include('reports.partials.signatures', [
            'facultyLabel' => 'Course Coordinator',
            'hodLabel' => 'Module Coordinator',
            'principalLabel' => 'HOD'
        ])
    </div>

    {{-- PAGE 2: RUBRICS --}}
    <div class="page-break pt-8">
        @include('reports.partials.header', [
            'title' => "ASSIGNMENT RUBRICS – {$coTag}",
            'subtitle' => "Continuous Internal Evaluation Criterion",
            'department' => $fullDepartment,
            'academicYear' => $assessmentYear,
            'semester' => "Semester {$romanSem}",
            'documentNo' => "{$subject->subject_code}/RUBRIC/{$coTag}"
        ])

        <table class="rubrics-table">
            <thead>
                <tr>
                    <th style="width: 20%;">Criteria</th>
                    <th style="width: 26.6%;">Excellent<br>(20 marks)</th>
                    <th style="width: 26.6%;">Good<br>(15 &ndash; 19 marks)</th>
                    <th style="width: 26.6%;">Satisfactory<br>(10 &ndash; 14 marks)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-bold bg-slate-50 text-center align-middle">Content</td>
                    <td>Covers the given topic in depth with details and examples<br><strong>(10 marks)</strong></td>
                    <td>Includes essential knowledge about the topic<br><strong>(7.5 marks)</strong></td>
                    <td>Contents are minimal or with factual errors<br><strong>(5 marks)</strong></td>
                </tr>
                <tr>
                    <td class="font-bold bg-slate-50 text-center align-middle">Organization</td>
                    <td>Well organization of the contents<br><strong>(6 marks)</strong></td>
                    <td>Contents organized partially<br><strong>(4.5 marks)</strong></td>
                    <td>Not clearly organized<br><strong>(3 marks)</strong></td>
                </tr>
                <tr>
                    <td class="font-bold bg-slate-50 text-center align-middle">Timely Submission</td>
                    <td>Student submitted the assignment within the due date<br><strong>(4 marks)</strong></td>
                    <td>Student submitted the assignment next day after the due date<br><strong>(3 marks)</strong></td>
                    <td>Student submitted the assignment long days after the due date<br><strong>(2 marks)</strong></td>
                </tr>
            </tbody>
        </table>

        @include('reports.partials.signatures', [
            'facultyLabel' => 'Course Coordinator',
            'hodLabel' => 'Module Coordinator',
            'principalLabel' => 'HOD'
        ])
    </div>

    {{-- PAGE 3: SCHEME OF EVALUATION --}}
    <div class="page-break pt-8">
        @include('reports.partials.header', [
            'title' => "SCHEME OF EVALUATION – {$coTag}",
            'subtitle' => "Model Answers & Scoring Key",
            'department' => $fullDepartment,
            'academicYear' => $assessmentYear,
            'semester' => "Semester {$romanSem}",
            'documentNo' => "{$subject->subject_code}/SCHEME/{$coTag}"
        ])

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 10%; text-align: center;">Q. No.</th>
                    <th style="width: 50%;">Answer Outline / Key Points</th>
                    <th style="width: 25%; text-align: center;">Detailed Mark Split-up</th>
                    <th style="width: 15%; text-align: center;">Total Marks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $q)
                    <tr>
                        <td class="text-center font-bold" style="vertical-align: top;">{{ $q['q_no'] }}.</td>
                        <td style="vertical-align: top; line-height: 1.5;">
                            <strong>Key Points for question:</strong><br>
                            {{ $q['question'] }}<br><br>
                            - Correct definition, definition/explanation of key terms.<br>
                            - Relevant block diagram/circuit diagram/equations (where applicable).<br>
                            - Appropriate examples and descriptions.
                        </td>
                        <td style="vertical-align: top; line-height: 1.5;">
                            - Explanation/Theory: {{ $q['marks'] - 2 > 0 ? $q['marks'] - 2 : 1 }} Marks<br>
                            - Diagram/Equations: {{ $q['marks'] > 2 ? 2 : 0 }} Marks
                        </td>
                        <td class="text-center font-bold" style="vertical-align: top;">{{ $q['marks'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-slate-500">No questions available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @include('reports.partials.signatures', [
            'facultyLabel' => 'Course Coordinator',
            'hodLabel' => 'Module Coordinator',
            'principalLabel' => 'HOD'
        ])
    </div>
</x-layouts.report-layout>
