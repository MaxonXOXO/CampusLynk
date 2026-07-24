<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Scheme & Answer Key - {{ $batchSubject->subject_code }} {{ $seriesNo }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
            @page { size: A4 portrait; margin: 15mm 12mm 15mm 12mm; }
        }
        body { font-family: 'Times New Roman', Times, serif; background: #f4f6f8; }
        .print-page { background: white; }
        .part-header { background: #1e3a5f; color: white; padding: 4px 10px; font-weight: bold; font-size: 13px; }
        .q-block { border: 1px solid #cbd5e1; border-radius: 6px; margin-bottom: 8px; overflow: hidden; }
        .q-question { background: #f8fafc; padding: 6px 10px; font-weight: bold; font-size: 13px; border-bottom: 1px solid #e2e8f0; }
        .key-item { padding: 4px 14px; font-size: 12px; color: #1e293b; list-style: disc; }
        .mark-split { background: #f0fdf4; border-left: 3px solid #22c55e; padding: 3px 8px; font-size: 11px; color: #166534; }
        .total-mark-badge { background: #1e3a5f; color: white; font-weight: bold; font-size: 12px; padding: 2px 8px; border-radius: 4px; float: right; }
    </style>
</head>
<body class="p-8 max-w-3xl mx-auto">

    <!-- Action Bar -->
    <div class="no-print mb-6 flex items-center justify-between bg-slate-100 p-4 rounded-xl border border-slate-300">
        <div>
            <h2 class="font-bold text-slate-800 text-lg">Evaluation Scheme & Answer Key — {{ $seriesNo }}</h2>
            <p class="text-slate-600 text-sm">{{ $subjectType['label'] ?? '📄 Standard (Table 4.1)' }} | Max 50 Marks | Strictly Confidential</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="window.print()" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow transition-all flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Scheme & Answer Key</span>
            </button>
            <button onclick="window.close()" class="px-5 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-bold rounded-lg shadow transition-all flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>Close Window</span>
            </button>
        </div>
    </div>

    <div class="print-page p-8 border border-slate-300 rounded-xl shadow-sm">
        <!-- Header -->
        <div class="border-b-2 border-slate-900 pb-3 mb-4 text-center space-y-0.5">
            <h1 class="text-lg font-bold uppercase tracking-wider text-slate-900">CARMEL POLYTECHNIC COLLEGE, PUNNAPRA</h1>
            <h2 class="text-sm font-bold text-slate-700 uppercase">DEPARTMENT OF {{ strtoupper($departmentName) }}</h2>
            <h3 class="text-xs font-bold text-red-800 uppercase">⚠ STRICTLY CONFIDENTIAL — EVALUATION SCHEME & ANSWER KEY ⚠</h3>
        </div>

        <!-- Metadata -->
        <div class="border border-slate-400 p-3 mb-5 rounded-lg bg-slate-50 grid grid-cols-2 gap-2 text-sm">
            <div><span class="font-bold">Subject:</span> {{ $practicumCourseFile->course_title ?: $batchSubject->subject_name }}</div>
            <div><span class="font-bold">Code:</span> {{ $batchSubject->subject_code }}</div>
            <div><span class="font-bold">Batch:</span> {{ $batchName }}</div>
            <div><span class="font-bold">Series Exam:</span> {{ $seriesNo }} | Max Marks: 50</div>
            <div><span class="font-bold">Pattern:</span> {{ $qpRecord->pattern_type === 'table_4_2_design' ? 'Table 4.2 Design Paper' : 'Table 4.1 Standard' }}</div>
            <div><span class="font-bold">Lecturer Name:</span> {{ $lecturerName }}</div>
        </div>

        @php $qp = $qpRecord->qp_data ?? []; @endphp

        @if ($qpRecord->pattern_type === 'table_4_2_design')
            <!-- Part A Scheme -->
            <div class="mb-4">
                <div class="part-header">PART A — 6 × 5 = 30 Marks</div>
                @foreach ($qp['part_a'] ?? [] as $q)
                <div class="q-block mt-2">
                    <div class="q-question">
                        Q{{ $q['q_no'] }}: {{ $q['text'] }}
                        <span class="total-mark-badge">{{ $q['marks'] }} M</span>
                    </div>
                    <ul class="py-2">
                        <li class="key-item">Identify the core design requirement and establish governing equations.</li>
                        <li class="key-item">List key assumptions, constraints, and safety criteria applicable.</li>
                        <li class="key-item">Provide accurate dimensional or mathematical statements with appropriate units.</li>
                    </ul>
                    <div class="mark-split">Marks: Concept Identification (2M) + Accurate Statement (2M) + Application context (1M)</div>
                </div>
                @endforeach
            </div>

            <!-- Part B Scheme -->
            <div class="mb-4">
                <div class="part-header">PART B — 2 × 10 = 20 Marks</div>
                @foreach ($qp['part_b'] ?? [] as $q)
                <div class="q-block mt-2">
                    <div class="q-question">
                        Q{{ $q['q_no'] }}: {{ $q['text'] }}
                        <span class="total-mark-badge">{{ $q['marks'] }} M</span>
                    </div>
                    <ul class="py-2">
                        <li class="key-item">Step 1: Define design criteria, problem statement, material specifications.</li>
                        <li class="key-item">Step 2: Perform systematic calculations or create detailed CAD layout.</li>
                        <li class="key-item">Step 3: Validate results against standards (BIS/ISO) and draw conclusions.</li>
                    </ul>
                    <div class="mark-split">Marks: Problem Setup (2M) + Procedure/Calculation (5M) + Validation/Diagram (3M)</div>
                </div>
                @endforeach
            </div>

        @else
            <!-- Part A Scheme -->
            <div class="mb-4">
                <div class="part-header">PART A — 4 × 1 = 4 Marks</div>
                @foreach ($qp['part_a'] ?? [] as $q)
                <div class="q-block mt-2">
                    <div class="q-question">
                        Q{{ $q['q_no'] }}: {{ $q['text'] }}
                        <span class="total-mark-badge">{{ $q['marks'] }} M</span>
                    </div>
                    <ul class="py-2">
                        <li class="key-item">State the correct definition / formula / value as per standard textbook. (1 Mark for correct answer)</li>
                    </ul>
                    <div class="mark-split">Marks: Correct Answer = 1M | Half/Partial response = 0M</div>
                </div>
                @endforeach
            </div>

            <!-- Part B Scheme -->
            <div class="mb-4">
                <div class="part-header">PART B — 6 × 3 = 18 Marks</div>
                @foreach ($qp['part_b'] ?? [] as $q)
                <div class="q-block mt-2">
                    <div class="q-question">
                        Q{{ $q['q_no'] }}: {{ $q['text'] }}
                        <span class="total-mark-badge">{{ $q['marks'] }} M</span>
                    </div>
                    <ul class="py-2">
                        <li class="key-item">Key concept / definition (1M)</li>
                        <li class="key-item">Relevant supporting explanation, steps or diagram (2M)</li>
                    </ul>
                    <div class="mark-split">Marks: Core Definition (1M) + Explanation / Steps / Diagram (2M)</div>
                </div>
                @endforeach
            </div>

            <!-- Part C Scheme -->
            <div class="mb-4">
                <div class="part-header">PART C — 4 × 7 = 28 Marks</div>
                @foreach ($qp['part_c'] ?? [] as $q)
                <div class="q-block mt-2">
                    <div class="q-question">
                        Q{{ $q['q_no'] }}: {{ $q['text'] }}
                        <span class="total-mark-badge">{{ $q['marks'] }} M</span>
                    </div>
                    <ul class="py-2">
                        <li class="key-item">Problem/Circuit/Algorithm setup and identification of parameters (1M)</li>
                        <li class="key-item">Systematic step-by-step solution / derivation / code logic (4M)</li>
                        <li class="key-item">Final result, circuit diagram, output, or conclusion with units (2M)</li>
                    </ul>
                    <div class="mark-split">Marks: Setup (1M) + Solution Steps (4M) + Output/Diagram/Conclusion (2M)</div>
                </div>
                @endforeach
            </div>
        @endif

        <!-- Marks Summary -->
        <div class="border-t-2 border-slate-900 pt-3 mt-5 flex justify-between text-sm font-bold">
            @if ($qpRecord->pattern_type === 'table_4_2_design')
            <span>Part A (30M) + Part B (20M) = 50 Marks | 2 Hours</span>
            @else
            <span>Part A (2M) + Part B (9M) + Part C (2 of 3 × 7 = 14M) = 25 Marks | 1½ Hours</span>
            @endif
            <span>Scaled CIA: {{ $qpRecord->max_marks ?? ($qpRecord->pattern_type === 'table_4_2_design' ? 50 : 25) }}M → 10 CIA Marks</span>
        </div>

        <!-- Signature Block -->
        <div class="grid grid-cols-3 gap-8 mt-10 text-sm text-center">
            <div class="border-t border-slate-500 pt-2 font-medium text-slate-600">Prepared by<br><span class="text-slate-800 font-bold">{{ $lecturerName }}</span></div>
            <div class="border-t border-slate-500 pt-2 font-medium text-slate-600">Verified by HOD</div>
            <div class="border-t border-slate-500 pt-2 font-medium text-slate-600">Date: {{ date('d/m/Y') }}</div>
        </div>
    </div>
</body>
</html>
