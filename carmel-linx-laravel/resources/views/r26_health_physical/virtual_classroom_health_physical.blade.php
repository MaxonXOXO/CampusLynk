<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Health and Physical Education (S1) - Virtual Classroom | Carmel Linx R2026</title>
    
    <!-- Google Fonts & Tailwind CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            900: '#064e3b',
                        },
                        emeraldGlow: '#00f5a0'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        .glass-panel {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glow-emerald {
            box-shadow: 0 0 25px rgba(16, 185, 129, 0.25);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans min-h-screen antialiased">

    <!-- Top Navigation Header -->
    <header class="sticky top-0 z-50 glass-panel border-b border-slate-800/80 px-6 py-4">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <a href="/dashboard/lecturer" class="p-2.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white transition">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <div class="flex items-center space-x-3">
                        <h1 class="text-xl font-bold font-display tracking-tight text-white">
                            {{ $hpCourseFile->course_title }}
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            R2026 S1 Unique Paper
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Course Code: <span class="font-mono text-slate-200 font-medium">{{ $hpCourseFile->course_code }}</span> | 
                        Semester: <span class="text-emerald-400 font-semibold">{{ $hpCourseFile->semester }}</span> | 
                        Credits: <span class="text-slate-200">{{ $hpCourseFile->credits }}</span> | 
                        CIE: 60M | ESE: 40M
                    </p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <button onclick="document.getElementById('uploadSyllabusModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-xs flex items-center space-x-2 shadow-lg shadow-emerald-600/20 transition">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Upload Syllabus PDF</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Workspace Container -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        
        <!-- Tab Controls -->
        <div class="flex space-x-2 border-b border-slate-800 pb-3 mb-8 overflow-x-auto">
            <button onclick="switchTab('tab-overview')" id="btn-tab-overview" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold transition bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                <i class="fa-solid fa-heart-pulse mr-2"></i>Course Overview & Rubrics
            </button>
            <button onclick="switchTab('tab-copo')" id="btn-tab-copo" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-slate-200 transition">
                <i class="fa-solid fa-table-cells mr-2"></i>CO-PO Matrix
            </button>
            <button onclick="switchTab('tab-lesson')" id="btn-tab-lesson" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-slate-200 transition">
                <i class="fa-solid fa-calendar-days mr-2"></i>30-Hour Plan
            </button>
            <button onclick="switchTab('tab-activity')" id="btn-tab-activity" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-slate-200 transition">
                <i class="fa-solid fa-person-running mr-2"></i>Continuous Fitness Log (30M)
            </button>
            <button onclick="switchTab('tab-fitness')" id="btn-tab-fitness" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-slate-200 transition">
                <i class="fa-solid fa-dumbbell mr-2"></i>Fitness & Skill Tests (15M)
            </button>
            <button onclick="switchTab('tab-summary')" id="btn-tab-summary" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-slate-200 transition">
                <i class="fa-solid fa-trophy mr-2"></i>Consolidated CIE & ESE (100M)
            </button>
        </div>

        <!-- TAB 1: Overview & Dynamic Rubric Titles from PDF -->
        <div id="tab-overview" class="tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Course Info Box -->
                <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center">
                        <i class="fa-solid fa-circle-info text-emerald-400 mr-2"></i>Course Specifications
                    </h3>
                    <dl class="space-y-3 text-xs">
                        <div class="flex justify-between py-1.5 border-b border-slate-800">
                            <dt class="text-slate-400">Course Code</dt>
                            <dd class="font-mono text-emerald-300 font-semibold">{{ $hpCourseFile->course_code }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-800">
                            <dt class="text-slate-400">Teaching Scheme (L:T:P:R)</dt>
                            <dd class="font-mono text-slate-200">{{ $hpCourseFile->teaching_scheme }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-800">
                            <dt class="text-slate-400">Total Instructional Hours</dt>
                            <dd class="font-bold text-white">{{ $hpCourseFile->contact_hours }} Hours</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-800">
                            <dt class="text-slate-400">Credits</dt>
                            <dd class="font-bold text-emerald-400">{{ $hpCourseFile->credits }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5">
                            <dt class="text-slate-400">Assessment Breakdown</dt>
                            <dd class="text-slate-200 font-medium">60% CIE + 40% ESE</dd>
                        </div>
                    </dl>
                </div>

                <!-- Parsed Assessment Criteria (Dynamic PDF Split-Up) -->
                <div class="lg:col-span-2 glass-panel p-6 rounded-2xl border border-slate-800 glow-emerald">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                            <i class="fa-solid fa-sliders text-emerald-400 mr-2"></i>Continuous Evaluation Criteria (Extracted from PDF)
                        </h3>
                        <span class="text-xs bg-emerald-500/20 text-emerald-300 px-2.5 py-1 rounded-full font-medium border border-emerald-500/30">
                            Dynamic Table Headers Active
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($evalScheme['day_work'] as $crit)
                        <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-emerald-400 font-mono uppercase">{{ strtoupper($crit['key']) }}</span>
                                <h4 class="text-xs font-medium text-slate-200 mt-0.5">{{ $crit['title'] }}</h4>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-emerald-950 text-emerald-300 border border-emerald-800/50">
                                {{ $crit['max_marks'] }} Marks
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Course Outcomes Table -->
            <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center">
                    <i class="fa-solid fa-graduation-cap text-emerald-400 mr-2"></i>Course Outcomes (COs)
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-900 text-slate-300 uppercase border-b border-slate-800">
                                <th class="p-3 w-20">CO ID</th>
                                <th class="p-3">Course Outcome Description</th>
                                <th class="p-3 w-32 text-center">Cognitive Level</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($hpCourseFile->parsed_cos as $co)
                            <tr class="hover:bg-slate-900/50">
                                <td class="p-3 font-bold font-mono text-emerald-400">{{ $co['id'] }}</td>
                                <td class="p-3 text-slate-300">{{ $co['description'] }}</td>
                                <td class="p-3 text-center font-medium">
                                    <span class="px-2.5 py-1 rounded-full bg-slate-800 text-slate-200 text-xs">
                                        {{ $co['cognitive_level'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 2: CO-PO Matrix -->
        <div id="tab-copo" class="tab-content hidden">
            <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">CO-PO Articulation Matrix</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs border border-slate-800">
                        <thead>
                            <tr class="bg-slate-900 text-slate-300 font-bold border-b border-slate-800">
                                <th class="p-3 text-left">CO / PO</th>
                                @for($p=1; $p<=11; $p++)
                                <th class="p-2 text-center">PO{{ $p }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                            <tr>
                                <td class="p-3 font-bold font-mono text-emerald-400 bg-slate-900/50">{{ $coTag }}</td>
                                @for($p=1; $p<=11; $p++)
                                @php $val = $mappings[$coTag]["PO{$p}"] ?? '-'; @endphp
                                <td class="p-2 text-center font-mono font-semibold {{ $val !== '-' ? 'text-emerald-300 bg-emerald-950/20' : 'text-slate-600' }}">
                                    {{ $val }}
                                </td>
                                @endfor
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 3: 30-Hour Plan -->
        <div id="tab-lesson" class="tab-content hidden">
            <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">30-Hour Physical Activity Schedule</h3>
                    <button onclick="saveLessonPlan()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-xs">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i>Save Plan Updates
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-900 text-slate-300 uppercase border-b border-slate-800">
                                <th class="p-3 w-16 text-center">Hour</th>
                                <th class="p-3 w-24 text-center">CO Tag</th>
                                <th class="p-3">Topic / Activity Description</th>
                                <th class="p-3 w-36 text-center">Actual Date</th>
                                <th class="p-3 w-28 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($lessonPlans as $lp)
                            <tr class="hover:bg-slate-900/50">
                                <td class="p-3 text-center font-bold font-mono text-slate-400">{{ $lp->day_no }}</td>
                                <td class="p-3 text-center font-mono text-emerald-400 font-semibold">{{ $lp->co_id }}</td>
                                <td class="p-3">
                                    <input type="text" value="{{ $lp->topic_content }}" id="topic_{{ $lp->id }}" class="w-full bg-slate-900 text-slate-200 border border-slate-800 rounded px-2.5 py-1 text-xs focus:border-emerald-500">
                                </td>
                                <td class="p-3 text-center">
                                    <input type="date" value="{{ $lp->actual_date }}" id="date_{{ $lp->id }}" class="bg-slate-900 text-slate-200 border border-slate-800 rounded px-2 py-1 text-xs focus:border-emerald-500">
                                </td>
                                <td class="p-3 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $lp->status === 'Completed' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                                        {{ $lp->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 4: Continuous Fitness & Activity Log (Dynamic Titles from Uploaded PDF) -->
        <div id="tab-activity" class="tab-content hidden">
            <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider">Continuous Activity & Fitness Log</h3>
                        <p class="text-xs text-slate-400 mt-1">Headers & Criteria titles are dynamically rendered from the uploaded syllabus PDF.</p>
                    </div>
                    <button onclick="saveActivityMarks()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-xs">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i>Save Evaluation Marks
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border border-slate-800">
                        <thead>
                            <tr class="bg-slate-900 text-slate-200 font-bold uppercase border-b border-slate-800">
                                <th class="p-3 w-12 text-center">#</th>
                                <th class="p-3 w-32 font-mono">Reg No</th>
                                <th class="p-3 w-48">Student Name</th>
                                @foreach($evalScheme['day_work'] as $crit)
                                <th class="p-3 text-center" title="{{ $crit['title'] }}">
                                    {{ $crit['title'] }} <br>
                                    <span class="text-emerald-400 font-normal">({{ $crit['max_marks'] }}M)</span>
                                </th>
                                @endforeach
                                <th class="p-3 text-center bg-emerald-950/50 text-emerald-300 w-24">Total (50M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($students as $idx => $student)
                            @php
                                $stEval = $activityEvals->get($student->reg_no, collect())->first();
                            @endphp
                            <tr class="hover:bg-slate-900/50">
                                <td class="p-3 text-center text-slate-500 font-mono">{{ $idx + 1 }}</td>
                                <td class="p-3 font-mono font-medium text-emerald-400">{{ $student->reg_no }}</td>
                                <td class="p-3 text-slate-200 font-medium">{{ $student->name }}</td>
                                @foreach($evalScheme['day_work'] as $crit)
                                @php $k = $crit['key']; @endphp
                                <td class="p-2 text-center">
                                    <input type="number" step="0.5" max="{{ $crit['max_marks'] }}" min="0"
                                           id="m_{{ $student->reg_no }}_{{ $k }}"
                                           value="{{ $stEval ? ($stEval->$k ?? 0) : 0 }}"
                                           onchange="calcTotal('{{ $student->reg_no }}')"
                                           class="w-16 bg-slate-900 text-center rounded border border-slate-700 text-xs py-1 text-slate-100 focus:border-emerald-500 crit-input-{{ $student->reg_no }}"
                                           data-max="{{ $crit['max_marks'] }}">
                                </td>
                                @endforeach
                                <td class="p-3 text-center font-bold text-emerald-400 bg-emerald-950/20" id="tot_{{ $student->reg_no }}">
                                    {{ $stEval ? number_format($stEval->total_score_50, 1) : '0.0' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 5: Physical Fitness Tests CA1 & CA2 -->
        <div id="tab-fitness" class="tab-content hidden">
            <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Physical Fitness & Skill Tests (CA1 / CA2)</h3>
                    <button onclick="saveFitnessTestMarks()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-xs">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i>Save Test Scores
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border border-slate-800">
                        <thead>
                            <tr class="bg-slate-900 text-slate-200 font-bold uppercase border-b border-slate-800">
                                <th class="p-3 w-12 text-center">#</th>
                                <th class="p-3 w-32 font-mono">Reg No</th>
                                <th class="p-3">Student Name</th>
                                <th class="p-3 text-center w-36">CA1 Fitness Test (40M)</th>
                                <th class="p-3 text-center w-36">CA2 Skill Demo (40M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($students as $idx => $student)
                            @php
                                $stTests = $fitnessTests->get($student->reg_no, collect());
                                $ca1 = $stTests->where('test_no', 'CA1')->first();
                                $ca2 = $stTests->where('test_no', 'CA2')->first();
                            @endphp
                            <tr class="hover:bg-slate-900/50">
                                <td class="p-3 text-center text-slate-500 font-mono">{{ $idx + 1 }}</td>
                                <td class="p-3 font-mono font-medium text-emerald-400">{{ $student->reg_no }}</td>
                                <td class="p-3 text-slate-200 font-medium">{{ $student->name }}</td>
                                <td class="p-2 text-center">
                                    <input type="number" step="0.5" max="40" min="0" id="ca1_{{ $student->reg_no }}" value="{{ $ca1 ? $ca1->total_score_40 : 0 }}" class="w-20 bg-slate-900 text-center rounded border border-slate-700 py-1 text-xs text-slate-100 focus:border-emerald-500">
                                </td>
                                <td class="p-2 text-center">
                                    <input type="number" step="0.5" max="40" min="0" id="ca2_{{ $student->reg_no }}" value="{{ $ca2 ? $ca2->total_score_40 : 0 }}" class="w-20 bg-slate-900 text-center rounded border border-slate-700 py-1 text-xs text-slate-100 focus:border-emerald-500">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 6: Consolidated CIE & ESE Summary -->
        <div id="tab-summary" class="tab-content hidden">
            <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-6">Consolidated CIE (60M) + ESE (40M) Marksheet</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border border-slate-800">
                        <thead>
                            <tr class="bg-slate-900 text-slate-200 font-bold uppercase border-b border-slate-800">
                                <th class="p-3 w-12 text-center">#</th>
                                <th class="p-3 w-32 font-mono">Reg No</th>
                                <th class="p-3">Student Name</th>
                                <th class="p-3 text-center">Att (5M)</th>
                                <th class="p-3 text-center">Continuous (30M)</th>
                                <th class="p-3 text-center">Tests (15M)</th>
                                <th class="p-3 text-center font-bold text-emerald-400 bg-slate-900">Total CIE (60M)</th>
                                <th class="p-3 text-center">ESE (40M)</th>
                                <th class="p-3 text-center font-bold text-white bg-slate-900">Grand Total (100M)</th>
                                <th class="p-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($studentResults as $idx => $res)
                            <tr class="hover:bg-slate-900/50">
                                <td class="p-3 text-center text-slate-500 font-mono">{{ $idx + 1 }}</td>
                                <td class="p-3 font-mono font-medium text-emerald-400">{{ $res['reg_no'] }}</td>
                                <td class="p-3 text-slate-200 font-medium">{{ $res['name'] }}</td>
                                <td class="p-3 text-center text-slate-300 font-mono">{{ $res['att_marks'] }}</td>
                                <td class="p-3 text-center text-slate-300 font-mono">{{ $res['activity_marks'] }}</td>
                                <td class="p-3 text-center text-slate-300 font-mono">{{ $res['test_marks'] }}</td>
                                <td class="p-3 text-center font-bold text-emerald-400 font-mono bg-emerald-950/20">{{ $res['total_cie_marks'] }}</td>
                                <td class="p-3 text-center text-slate-300 font-mono">{{ $res['total_ese'] }}</td>
                                <td class="p-3 text-center font-bold text-white font-mono bg-slate-900">{{ $res['total_course_marks'] }}</td>
                                <td class="p-3 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $res['is_passed'] ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30' }}">
                                        {{ $res['is_passed'] ? 'PASS' : 'FAIL' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <!-- Upload Syllabus PDF Modal -->
    <div id="uploadSyllabusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden">
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 max-w-md w-full mx-4 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-file-pdf text-emerald-400 mr-2"></i>Upload Health & Physical PDF
                </h3>
                <button onclick="document.getElementById('uploadSyllabusModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form id="uploadSyllabusForm" onsubmit="uploadSyllabusPdf(event)" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Select Syllabus PDF File</label>
                    <input type="file" name="syllabus_file" accept=".pdf" required class="w-full text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-500 bg-slate-900 border border-slate-800 rounded-xl">
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="document.getElementById('uploadSyllabusModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-medium hover:bg-slate-700">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-medium shadow-lg shadow-emerald-600/20">
                        Upload & Extract Splitup Titles
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript Handlers -->
    <script>
        const subjectId = "{{ $batchSubject->id }}";

        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.className = "tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-slate-200 transition";
            });

            document.getElementById(tabId).classList.remove('hidden');
            const activeBtn = document.getElementById('btn-' + tabId);
            activeBtn.className = "tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold transition bg-emerald-500/20 text-emerald-300 border border-emerald-500/30";
        }

        function calcTotal(regNo) {
            let inputs = document.querySelectorAll('.crit-input-' + regNo);
            let sum = 0;
            inputs.forEach(inp => {
                sum += parseFloat(inp.value || 0);
            });
            document.getElementById('tot_' + regNo).innerText = sum.toFixed(1);
        }

        async function uploadSyllabusPdf(e) {
            e.preventDefault();
            const form = document.getElementById('uploadSyllabusForm');
            const formData = new FormData(form);

            try {
                const res = await fetch(`/api/r26/classroom/health-physical/${subjectId}/syllabus`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                alert('Upload failed: ' + err.message);
            }
        }

        async function saveActivityMarks() {
            const students = @json($students->pluck('reg_no'));
            const keys = @json(collect($evalScheme['day_work'])->pluck('key'));

            let marksData = [];
            students.forEach(regNo => {
                let row = { reg_no: regNo };
                keys.forEach(k => {
                    const el = document.getElementById(`m_${regNo}_${k}`);
                    row[k] = el ? parseFloat(el.value || 0) : 0;
                });
                marksData.push(row);
            });

            try {
                const res = await fetch(`/api/r26/classroom/health-physical/${subjectId}/evaluate/activity`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        activity_no: 'ACT-LOG',
                        activity_title: 'Continuous Fitness & Activity Evaluation',
                        marks_data: marksData
                    })
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                alert('Save failed: ' + err.message);
            }
        }

        async function saveFitnessTestMarks() {
            const students = @json($students->pluck('reg_no'));
            let ca1Data = [], ca2Data = [];

            students.forEach(regNo => {
                const ca1El = document.getElementById(`ca1_${regNo}`);
                const ca2El = document.getElementById(`ca2_${regNo}`);
                ca1Data.push({ reg_no: regNo, total_score_40: ca1El ? parseFloat(ca1El.value || 0) : 0 });
                ca2Data.push({ reg_no: regNo, total_score_40: ca2El ? parseFloat(ca2El.value || 0) : 0 });
            });

            try {
                await fetch(`/api/r26/classroom/health-physical/${subjectId}/evaluate/fitness-test`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ test_no: 'CA1', marks_data: ca1Data })
                });

                await fetch(`/api/r26/classroom/health-physical/${subjectId}/evaluate/fitness-test`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ test_no: 'CA2', marks_data: ca2Data })
                });

                alert('Physical Fitness Test scores saved successfully!');
                window.location.reload();
            } catch (err) {
                alert('Save failed: ' + err.message);
            }
        }

        async function saveLessonPlan() {
            const plans = {};
            @foreach($lessonPlans as $lp)
            plans[{{ $lp->id }}] = {
                topic_content: document.getElementById('topic_{{ $lp->id }}').value,
                actual_date: document.getElementById('date_{{ $lp->id }}').value,
                co_tag: '{{ $lp->co_id }}'
            };
            @endforeach

            try {
                const res = await fetch(`/api/r26/classroom/health-physical/${subjectId}/lesson-plan/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ plans: plans })
                });
                const data = await res.json();
                alert(data.message || 'Lesson plan updated!');
                window.location.reload();
            } catch (err) {
                alert('Save failed: ' + err.message);
            }
        }
    </script>
</body>
</html>
