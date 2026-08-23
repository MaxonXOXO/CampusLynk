<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[{{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}] Health & Physical Virtual Class - {{ $hpCourseFile->course_title }}</title>
    
    <!-- Canonical Vite Asset Pipeline -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-primary: #FAFAFB;
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #FAFAFB;
            color: #0f172a;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .font-heading, .brand-font {
            font-family: 'Outfit', 'Poppins', sans-serif;
            text-shadow: none !important;
            filter: none !important;
        }

        span, p, label, button, a, th, td, div {
            text-shadow: none !important;
            filter: none !important;
        }

        .glass-panel { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05); }
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05);
        }

        .mark-slider {
            -webkit-appearance: none;
            width: 100%;
            height: 6px;
            background: #cbd5e1;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 4px;
        }
        .mark-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            background: #059669;
            border-radius: 50%;
            cursor: pointer;
        }

        /* Custom Scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-[#FAFAFB] text-slate-900 font-sans min-h-screen antialiased">
    @php
        $role = Session::get('userRole');
        $dashboardUrl = '/dashboard/lecturer';
        $dashboardLabel = 'Faculty Platform';
        if ($role === 'HOD') {
            $dashboardUrl = '/dashboard/hod';
            $dashboardLabel = 'Department Console (HOD)';
        } elseif ($role === 'Principal') {
            $dashboardUrl = '/dashboard/principal';
            $dashboardLabel = 'Principal Desk';
        } elseif ($role === 'Demonstrator') {
            $dashboardUrl = '/dashboard/demonstrator';
            $dashboardLabel = 'Demonstrator Desk';
        } elseif ($role === 'Super_Admin' || $role === 'SuperAdmin') {
            $dashboardUrl = '/dashboard/superadmin';
            $dashboardLabel = 'SuperAdmin Desk';
        } elseif ($role === 'Admin') {
            $dashboardUrl = '/dashboard/admin';
            $dashboardLabel = 'Admin Desk';
        }
    @endphp

    <!-- 1. TOP BREADCRUMB & TOOLBAR -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-50 px-4 md:px-8 py-3 shadow-xs">
        <div class="max-w-[1600px] mx-auto flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
            <nav class="flex items-center gap-2 text-xs sm:text-sm font-medium text-slate-500 flex-wrap">
                <a href="{{ $dashboardUrl }}" class="hover:text-blue-600 transition flex items-center gap-1.5 font-semibold text-slate-700 text-decoration-none">
                    <span class="material-symbols-rounded text-base text-blue-600">domain</span>
                    <span>{{ $dashboardLabel }}</span>
                </a>
                <span class="text-slate-300">/</span>
                <a href="{{ $dashboardUrl }}" class="hover:text-blue-600 transition font-medium text-slate-600 text-decoration-none">My Batches</a>
                <span class="text-slate-300">/</span>
                <span class="font-bold text-slate-900 flex items-center gap-1.5">
                    <span>Health &amp; Physical Education</span>
                    <span class="text-xs font-bold font-mono px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-md">R2026</span>
                </span>
            </nav>

            <div class="flex items-center gap-2.5 flex-wrap">
                <button onclick="document.getElementById('uploadSyllabusModal').classList.remove('hidden')" class="px-3.5 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold transition flex items-center gap-1.5 border border-emerald-200 shadow-2xs cursor-pointer">
                    <span class="material-symbols-rounded text-sm">upload_file</span>
                    <span>Upload Syllabus</span>
                </button>
                <a href="{{ $dashboardUrl }}" class="px-3.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition flex items-center gap-1.5 border border-rose-200 shadow-2xs text-decoration-none">
                    <span class="material-symbols-rounded text-sm">arrow_back</span>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </header>

    <!-- HERO HEADER CARD -->
    <div class="max-w-[1600px] mx-auto px-4 md:px-8 mt-5">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-5">
            <div class="space-y-2">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                        {{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R2021 · HEALTH & PHYSICAL' : 'R2026 · HEALTH & PHYSICAL' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-blue-50 text-blue-700 border border-blue-200/80">
                        {{ $batchSubject->classroom_id }} · S{{ $hpCourseFile->semester }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-md font-mono font-bold text-xs bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $hpCourseFile->course_code }}
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    {{ $hpCourseFile->course_title }}
                </h1>

                <div class="flex items-center gap-3 text-xs sm:text-sm font-medium text-slate-500 flex-wrap">
                    <span>Contact Hours: <strong class="text-slate-800">{{ $hpCourseFile->contact_hours }} Hrs</strong></span>
                    <span class="text-slate-300">•</span>
                    <span>Credits: <strong class="text-emerald-700 font-bold">{{ $hpCourseFile->credits }}</strong></span>
                    <span class="text-slate-300">•</span>
                    <span>Scheme: <strong class="text-slate-800">{{ $hpCourseFile->teaching_scheme }}</strong></span>
                    <span class="text-slate-300">•</span>
                    <span>Assessment: <strong class="text-blue-600">60M CIE</strong> + <strong class="text-amber-600">40M ESE</strong></span>
                </div>
            </div>

            <!-- Syllabus Actions -->
            <div class="flex items-center gap-2 flex-wrap">
                @if(isset($hpCourseFile) && $hpCourseFile && $hpCourseFile->syllabus_pdf_path)
                    <a href="/storage/{{ $hpCourseFile->syllabus_pdf_path }}" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 rounded-xl text-xs font-bold transition-all border border-slate-200 flex items-center gap-1.5 shadow-2xs">
                        <svg class="w-4 h-4 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h6"/><path d="M9 11h6"/></svg>
                        <span>View Syllabus PDF</span>
                    </a>
                @endif
                <button type="button" onclick="document.getElementById('uploadSyllabusModal').classList.remove('hidden')" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs cursor-pointer">
                    <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    <span>{{ (isset($hpCourseFile) && $hpCourseFile && $hpCourseFile->syllabus_pdf_path) ? 'Replace Syllabus' : 'Upload Syllabus PDF' }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Workspace Container -->
    <main class="max-w-[1600px] mx-auto px-4 md:px-8 py-5">
        
        <!-- Tab Controls Navigation Strip -->
        <div class="bg-white border border-slate-200/80 p-2 rounded-2xl mb-5 flex items-center gap-2 overflow-x-auto shadow-xs">
            <button type="button" onclick="switchTab('tab-overview')" id="btn-tab-overview" class="tab-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-2xs whitespace-nowrap cursor-pointer">
                <i class="fa-solid fa-heart-pulse mr-2 text-emerald-600"></i>Course Overview & Rubrics
            </button>
            <button type="button" onclick="switchTab('tab-copo')" id="btn-tab-copo" class="tab-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition border border-transparent whitespace-nowrap cursor-pointer">
                <i class="fa-solid fa-table-cells mr-2"></i>CO-PO Matrix
            </button>
            <button type="button" onclick="switchTab('tab-lesson')" id="btn-tab-lesson" class="tab-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition border border-transparent whitespace-nowrap cursor-pointer">
                <i class="fa-solid fa-calendar-days mr-2"></i>30-Hour Plan
            </button>
            <button type="button" onclick="switchTab('tab-activity')" id="btn-tab-activity" class="tab-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition border border-transparent whitespace-nowrap cursor-pointer">
                <i class="fa-solid fa-person-running mr-2"></i>Continuous Fitness Log (30M)
            </button>
            <button type="button" onclick="switchTab('tab-fitness')" id="btn-tab-fitness" class="tab-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition border border-transparent whitespace-nowrap cursor-pointer">
                <i class="fa-solid fa-dumbbell mr-2"></i>Fitness & Skill Tests (15M)
            </button>
            <button type="button" onclick="switchTab('tab-summary')" id="btn-tab-summary" class="tab-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition border border-transparent whitespace-nowrap cursor-pointer">
                <i class="fa-solid fa-trophy mr-2"></i>Consolidated CIE & ESE (100M)
            </button>
            <button type="button" onclick="switchTab('tab-surveys')" id="btn-tab-surveys" class="tab-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition border border-transparent whitespace-nowrap cursor-pointer">
                <i class="fa-solid fa-chart-pie mr-2"></i>Surveys & Attainment
            </button>
        </div>

        <!-- TAB 1: Overview & Dynamic Rubric Titles from PDF -->
        <div id="tab-overview" class="tab-content space-y-5">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                
                <!-- Course Specifications Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
                            <span class="material-symbols-rounded text-base">info</span>
                        </span>
                        <h3 class="font-bold text-slate-900 text-base">Course Specifications</h3>
                    </div>

                    <dl class="space-y-2.5 text-sm">
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                            <dt class="text-slate-500 font-medium">Course Code</dt>
                            <dd class="font-mono text-blue-700 font-bold">{{ $hpCourseFile->course_code }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                            <dt class="text-slate-500 font-medium">Teaching Scheme</dt>
                            <dd class="font-mono text-slate-800 font-semibold">{{ $hpCourseFile->teaching_scheme }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                            <dt class="text-slate-500 font-medium">Instructional Hours</dt>
                            <dd class="font-bold text-slate-900">{{ $hpCourseFile->contact_hours }} Hours</dd>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                            <dt class="text-slate-500 font-medium">Credits</dt>
                            <dd class="font-bold text-blue-700">{{ $hpCourseFile->credits }} Credits</dd>
                        </div>
                        <div class="flex justify-between items-center py-1.5">
                            <dt class="text-slate-500 font-medium">Assessment Breakdown</dt>
                            <dd class="text-slate-800 font-semibold">60% CIE + 40% ESE</dd>
                        </div>
                    </dl>
                </div>

                <!-- Parsed Assessment Criteria (Dynamic PDF Split-Up) -->
                <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold border border-emerald-200/80">
                                <span class="material-symbols-rounded text-base">tune</span>
                            </span>
                            <h3 class="font-bold text-slate-900 text-base">Continuous Evaluation Criteria (From PDF)</h3>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Dynamic Table Headers Active
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($evalScheme['day_work'] as $crit)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-2">
                            <div>
                                <span class="text-xs font-bold text-blue-700 font-mono uppercase">{{ strtoupper($crit['key']) }}</span>
                                <h4 class="text-sm font-medium text-slate-800 mt-0.5">{{ $crit['title'] }}</h4>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-white text-slate-800 border border-slate-200 shadow-2xs">
                                {{ $crit['max_marks'] }} Marks
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Course Outcomes Table / Cards -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
                            <span class="material-symbols-rounded text-base">stars</span>
                        </span>
                        <h3 class="font-bold text-slate-900 text-base">Course Outcomes (COs)</h3>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg border border-slate-200">
                        {{ count($hpCourseFile->parsed_cos ?? []) }} Outcomes
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse(($hpCourseFile->parsed_cos ?? []) as $co)
                    <div class="p-4 rounded-xl bg-slate-50/60 border border-slate-200/80 hover:border-blue-300 transition-all space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-bold font-mono text-xs border border-blue-200">{{ $co['id'] }}</span>
                            @if(!empty($co['cognitive_level']))
                            <span class="px-2 py-0.5 rounded-md font-semibold text-xs border bg-emerald-50 text-emerald-700 border-emerald-200">{{ $co['cognitive_level'] }}</span>
                            @endif
                        </div>
                        <p class="text-sm font-medium text-slate-800 leading-relaxed">{{ $co['description'] }}</p>
                    </div>
                    @empty
                    <div class="col-span-2 text-center py-6 text-slate-500 text-sm italic bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        Physical education outcomes not uploaded yet.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- TAB 2: CO-PO Matrix -->
        <div id="tab-copo" class="tab-content hidden space-y-5">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center text-sm font-bold border border-indigo-200/80">
                            <span class="material-symbols-rounded text-base">grid_on</span>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">CO-PO Articulation Matrix</h3>
                            <p class="text-xs text-slate-500">Mapping strengths: 3 = High, 2 = Medium, 1 = Low</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-medium text-slate-600">
                        <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold flex items-center justify-center text-[10px]">3</span> High</span>
                        <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-blue-100 text-blue-800 border border-blue-300 font-bold flex items-center justify-center text-[10px]">2</span> Med</span>
                        <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-slate-100 text-slate-700 border border-slate-300 font-bold flex items-center justify-center text-[10px]">1</span> Low</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-center border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-700 font-bold text-xs uppercase border-b border-slate-200">
                                <th class="p-3 text-left pl-4 w-24">CO / PO</th>
                                @for($p=1; $p<=11; $p++)
                                <th class="p-3 font-mono">PO{{ $p }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                            <tr class="hover:bg-slate-50/80 transition-all">
                                <td class="p-3 text-left font-bold text-blue-700 pl-4 font-mono">{{ $coTag }}</td>
                                @for($p=1; $p<=11; $p++)
                                @php
                                    $val = $mappings[$coTag]["PO{$p}"] ?? '-';
                                    $cellClass = 'text-slate-400 font-normal';
                                    if ($val == '3') $cellClass = 'font-bold text-emerald-700 bg-emerald-50/60';
                                    elseif ($val == '2') $cellClass = 'font-bold text-blue-700 bg-blue-50/60';
                                    elseif ($val == '1') $cellClass = 'font-semibold text-slate-700 bg-slate-50';
                                @endphp
                                <td class="p-2.5 font-mono text-sm {{ $cellClass }}">
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
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">30-Hour Physical Activity Schedule</h3>
                    <div class="flex items-center space-x-2">
                        <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/lesson-plan" target="_blank" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-medium text-xs border border-slate-200 shadow-xs flex items-center space-x-1.5">
                            <i class="fa-solid fa-print"></i><span>Print Schedule</span>
                        </a>
                        <button type="button" onclick="saveLessonPlan()" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-medium text-xs">
                            <i class="fa-solid fa-floppy-disk mr-1.5"></i>Save Plan Updates
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 uppercase border-b border-slate-200 font-bold tracking-wider">
                                <th class="p-3.5 w-16 text-center">Hour</th>
                                <th class="p-3.5 w-24 text-center">CO Tag</th>
                                <th class="p-3.5">Topic / Activity Description</th>
                                <th class="p-3.5 w-36 text-center">Proposed Date</th>
                                <th class="p-3.5 w-36 text-center">Actual Date</th>
                                <th class="p-3.5 w-28 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($lessonPlans as $lp)
                            @php $isSeriesTest = str_contains(strtolower($lp->topic_content), 'series test'); @endphp
                            <tr class="{{ $isSeriesTest ? 'bg-sky-50/70 border-l-4 border-sky-500' : 'hover:bg-slate-50/80 transition-colors' }}">
                                <td class="p-3.5 text-center font-bold font-mono text-slate-600 text-xs">{{ $lp->day_no }}</td>
                                <td class="p-3.5 text-center font-mono text-sky-700 font-bold text-xs">
                                    {{ $lp->co_id }}
                                </td>
                                <td class="p-3.5">
                                    <div class="flex items-center space-x-2">
                                        @if($isSeriesTest)
                                            <span class="px-2 py-0.5 rounded text-xs font-bold bg-sky-100 text-sky-800 uppercase border border-sky-200 shadow-2xs">Test</span>
                                        @endif
                                        <input type="text" value="{{ $lp->topic_content }}" id="topic_{{ $lp->id }}" class="w-full bg-slate-50 text-slate-800 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:bg-white focus:border-sky-500 outline-none transition-colors shadow-2xs {{ $isSeriesTest ? 'font-bold text-sky-900' : '' }}">
                                    </div>
                                </td>
                                <td class="p-3.5 text-center">
                                    <input type="date" value="{{ $lp->proposed_date }}" id="pdate_{{ $lp->id }}" class="bg-slate-50 text-slate-800 border border-slate-200 rounded-lg px-2 py-1.5 text-xs focus:bg-white focus:border-sky-500 outline-none transition-colors shadow-2xs font-mono">
                                </td>
                                <td class="p-3.5 text-center">
                                    <input type="date" value="{{ $lp->actual_date }}" id="adate_{{ $lp->id }}" class="bg-slate-50 text-slate-800 border border-slate-200 rounded-lg px-2 py-1.5 text-xs focus:bg-white focus:border-sky-500 outline-none transition-colors shadow-2xs font-mono">
                                </td>
                                <td class="p-3.5 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $lp->status === 'Completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($isSeriesTest ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
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
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Continuous Activity &amp; Fitness Log</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Headers &amp; Criteria titles are dynamically rendered from the uploaded syllabus PDF.</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/activity-log" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-200 flex items-center space-x-1.5 no-underline shadow-2xs">
                            <i class="fa-solid fa-print"></i><span>Print Log</span>
                        </a>
                        <button type="button" onclick="saveActivityMarks()" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs shadow-xs cursor-pointer">
                            <i class="fa-solid fa-floppy-disk mr-1.5"></i>Save Evaluation Marks
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-700 font-bold uppercase border-b border-slate-200">
                                <th class="p-3.5 w-12 text-center">#</th>
                                <th class="p-3.5 w-32 font-mono">Reg No</th>
                                <th class="p-3.5 w-48">Student Name</th>
                                @foreach($evalScheme['day_work'] as $crit)
                                <th class="p-3.5 text-center" title="{{ $crit['title'] }}">
                                    {{ $crit['title'] }} <br>
                                    <span class="text-sky-700 font-normal">({{ $crit['max_marks'] }}M)</span>
                                </th>
                                @endforeach
                                <th class="p-3.5 text-center bg-sky-50 text-sky-900 w-24">Total (50M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($students as $idx => $student)
                            @php
                                $stEval = $activityEvals->get($student->reg_no, collect())->first();
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="p-3.5 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                <td class="p-3.5 font-mono font-bold text-slate-900">{{ $student->reg_no }}</td>
                                <td class="p-3.5 text-slate-900 font-medium">{{ $student->name }}</td>
                                @foreach($evalScheme['day_work'] as $crit)
                                @php $k = $crit['key']; $val = $stEval ? ($stEval->$k ?? 0) : 0; @endphp
                                <td class="p-3.5 text-center min-w-[140px]">
                                    <div class="flex flex-col items-center gap-1">
                                        <input type="number" step="0.5" max="{{ $crit['max_marks'] }}" min="0"
                                               id="m_{{ $student->reg_no }}_{{ $k }}"
                                               value="{{ $val }}"
                                               oninput="syncSlider('{{ $student->reg_no }}', '{{ $k }}', this.value)"
                                               onchange="calcTotal('{{ $student->reg_no }}')"
                                               class="w-16 bg-slate-50 text-center rounded-lg border border-slate-200 text-xs py-1 font-mono text-slate-800 focus:bg-white focus:border-sky-500 outline-none shadow-2xs crit-input-{{ $student->reg_no }}"
                                               data-max="{{ $crit['max_marks'] }}">
                                        <input type="range" step="0.5" max="{{ $crit['max_marks'] }}" min="0"
                                               id="s_{{ $student->reg_no }}_{{ $k }}"
                                               value="{{ $val }}"
                                               oninput="syncInput('{{ $student->reg_no }}', '{{ $k }}', this.value)"
                                               class="mark-slider w-28 accent-sky-600">
                                    </div>
                                </td>
                                @endforeach
                                <td class="p-3.5 text-center font-bold text-xs text-sky-800 bg-sky-50/50" id="tot_{{ $student->reg_no }}">
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
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Physical Fitness &amp; Skill Tests (CA1 / CA2)</h3>
                    <div class="flex items-center space-x-2">
                        <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/fitness-tests" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-200 flex items-center space-x-1.5 no-underline shadow-2xs">
                            <i class="fa-solid fa-print"></i><span>Print Tests</span>
                        </a>
                        <button type="button" onclick="saveFitnessTestMarks()" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs shadow-xs cursor-pointer">
                            <i class="fa-solid fa-floppy-disk mr-1.5"></i>Save Test Scores
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-700 font-bold uppercase border-b border-slate-200">
                                <th class="p-3.5 w-12 text-center">#</th>
                                <th class="p-3.5 w-32 font-mono">Reg No</th>
                                <th class="p-3.5">Student Name</th>
                                <th class="p-3.5 text-center w-44">CA1 Fitness Test (40M)</th>
                                <th class="p-3.5 text-center w-44">CA2 Skill Demo (40M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($students as $idx => $student)
                            @php
                                $stTests = $fitnessTests->get($student->reg_no, collect());
                                $ca1 = $stTests->where('test_no', 'CA1')->first();
                                $ca2 = $stTests->where('test_no', 'CA2')->first();
                                $ca1Val = $ca1 ? $ca1->total_score_40 : 0;
                                $ca2Val = $ca2 ? $ca2->total_score_40 : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="p-3.5 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                <td class="p-3.5 font-mono font-bold text-slate-900 text-xs">{{ $student->reg_no }}</td>
                                <td class="p-3.5 text-slate-900 font-medium text-xs">{{ $student->name }}</td>
                                <td class="p-3.5 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <input type="number" step="0.5" max="40" min="0" id="ca1_{{ $student->reg_no }}" value="{{ $ca1Val }}" oninput="syncFitnessSlider('ca1', '{{ $student->reg_no }}', this.value)" class="w-20 bg-slate-50 text-center rounded-lg border border-slate-200 py-1 text-xs text-slate-800 focus:bg-white focus:border-sky-500 outline-none shadow-2xs font-mono">
                                        <input type="range" step="0.5" max="40" min="0" id="s_ca1_{{ $student->reg_no }}" value="{{ $ca1Val }}" oninput="syncFitnessInput('ca1', '{{ $student->reg_no }}', this.value)" class="mark-slider w-28 accent-sky-600">
                                    </div>
                                </td>
                                <td class="p-3.5 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <input type="number" step="0.5" max="40" min="0" id="ca2_{{ $student->reg_no }}" value="{{ $ca2Val }}" oninput="syncFitnessSlider('ca2', '{{ $student->reg_no }}', this.value)" class="w-20 bg-slate-50 text-center rounded-lg border border-slate-200 py-1 text-xs text-slate-800 focus:bg-white focus:border-sky-500 outline-none shadow-2xs font-mono">
                                        <input type="range" step="0.5" max="40" min="0" id="s_ca2_{{ $student->reg_no }}" value="{{ $ca2Val }}" oninput="syncFitnessInput('ca2', '{{ $student->reg_no }}', this.value)" class="mark-slider w-28 accent-sky-600">
                                    </div>
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
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Consolidated CIE (60M) + ESE (40M) Marksheet</h3>
                    <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/consolidated" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-200 flex items-center space-x-1.5 no-underline shadow-2xs">
                        <i class="fa-solid fa-print"></i><span>Print Consolidated Register</span>
                    </a>
                </div>
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-700 font-bold uppercase border-b border-slate-200">
                                <th class="p-3.5 w-12 text-center">#</th>
                                <th class="p-3.5 w-32 font-mono">Reg No</th>
                                <th class="p-3.5">Student Name</th>
                                <th class="p-3.5 text-center">Att (5M)</th>
                                <th class="p-3.5 text-center">Continuous (30M)</th>
                                <th class="p-3.5 text-center">Tests (15M)</th>
                                <th class="p-3.5 text-center font-bold text-sky-800 bg-sky-50/50">Total CIE (60M)</th>
                                <th class="p-3.5 text-center">ESE (40M)</th>
                                <th class="p-3.5 text-center font-bold text-slate-900 bg-slate-100">Grand Total (100M)</th>
                                <th class="p-3.5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($studentResults as $idx => $res)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="p-3.5 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                <td class="p-3.5 font-mono font-bold text-slate-900">{{ $res['reg_no'] }}</td>
                                <td class="p-3.5 text-slate-900 font-medium">{{ $res['name'] }}</td>
                                <td class="p-3.5 text-center text-slate-600 font-mono">{{ $res['att_marks'] }}</td>
                                <td class="p-3.5 text-center text-slate-600 font-mono">{{ $res['activity_marks'] }}</td>
                                <td class="p-3.5 text-center text-slate-600 font-mono">{{ $res['test_marks'] }}</td>
                                <td class="p-3.5 text-center font-bold text-sky-800 font-mono bg-sky-50/50">{{ $res['total_cie_marks'] }}</td>
                                <td class="p-3.5 text-center text-slate-600 font-mono">{{ $res['total_ese'] }}</td>
                                <td class="p-3.5 text-center font-bold text-slate-900 font-mono bg-slate-50">{{ $res['total_course_marks'] }}</td>
                                <td class="p-3.5 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $res['is_passed'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
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

        <!-- TAB 7: Surveys & Indirect Attainment -->
        <div id="tab-surveys" class="tab-content hidden">
            <div class="space-y-6">
                <!-- Dual Survey Activation & Preview Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mid-Semester Survey Card -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center">
                                    <i class="fa-solid fa-comments text-amber-500 mr-2"></i>Mid-Semester Survey
                                </h3>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ isset($midSemSurvey) && $midSemSurvey && $midSemSurvey->status === 'Active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    {{ isset($midSemSurvey) && $midSemSurvey ? $midSemSurvey->status : 'Inactive' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mb-4 leading-relaxed">Mid-Term feedback on teaching pace, practical demonstrations, equipment availability, and safety protocols.</p>
                        </div>
                        <div class="flex items-center space-x-2 pt-3 border-t border-slate-100">
                            <button type="button" onclick="openPreviewModal('previewMidSemModal')" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-200 flex-1 flex items-center justify-center space-x-1 shadow-2xs cursor-pointer">
                                <i class="fa-solid fa-eye"></i><span>Preview Questionnaire</span>
                            </button>
                            @if(isset($midSemSurvey) && $midSemSurvey && $midSemSurvey->status === 'Active')
                                <button type="button" onclick="closeMidSemSurvey()" class="px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs flex-1 shadow-xs cursor-pointer">
                                    <i class="fa-solid fa-lock mr-1"></i>Close Survey
                                </button>
                            @else
                                <button type="button" onclick="initiateMidSemSurvey()" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs flex-1 shadow-xs cursor-pointer">
                                    <i class="fa-solid fa-paper-plane mr-1"></i>Activate Survey
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- End-Semester Course Exit Survey Card -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center">
                                    <i class="fa-solid fa-graduation-cap text-sky-600 mr-2"></i>End-Semester Course Exit Survey
                                </h3>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ isset($exitSurvey) && $exitSurvey && $exitSurvey->status === 'Active' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    {{ isset($exitSurvey) && $exitSurvey ? $exitSurvey->status : 'Inactive' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mb-4 leading-relaxed">Comprehensive end-of-course survey evaluated on High (L3), Medium (L2), Low (L1) scale for Indirect CO Attainment (20%).</p>
                        </div>
                        <div class="flex items-center space-x-2 pt-3 border-t border-slate-100">
                            <button type="button" onclick="openPreviewModal('previewExitSurveyModal')" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-200 flex-1 flex items-center justify-center space-x-1 shadow-2xs cursor-pointer">
                                <i class="fa-solid fa-eye"></i><span>Preview Questionnaire</span>
                            </button>
                            @if(isset($exitSurvey) && $exitSurvey && $exitSurvey->status === 'Active')
                                <button type="button" onclick="closeExitSurvey()" class="px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs flex-1 shadow-xs cursor-pointer">
                                    <i class="fa-solid fa-lock mr-1"></i>Close Survey
                                </button>
                            @else
                                <button type="button" onclick="initiateExitSurvey()" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs flex-1 shadow-xs cursor-pointer">
                                    <i class="fa-solid fa-paper-plane mr-1"></i>Activate Survey
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Anonymous Survey Results & Questionnaire Report -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-5">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center">
                                <i class="fa-solid fa-chart-column text-sky-600 mr-2"></i>Anonymous Survey Response Breakdown &amp; 3-Level Evaluation
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">Student names are excluded for strict anonymity. Response totals &amp; 3-level scale ratings are summarized below.</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/survey-report" target="_blank" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-200 flex items-center space-x-1.5 no-underline shadow-2xs">
                                <i class="fa-solid fa-print"></i><span>Print Survey Report</span>
                            </a>
                            <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/attainment" target="_blank" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs flex items-center space-x-1.5 shadow-xs no-underline">
                                <i class="fa-solid fa-file-invoice"></i><span>Print CO-PO Attainment</span>
                            </a>
                        </div>
                    </div>

                    <!-- Response Stats Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-center">
                            <span class="text-xs font-bold uppercase text-slate-500 block mb-1">Enrolled Students</span>
                            <span class="text-lg font-bold text-slate-900 font-mono">{{ $students->count() }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-center">
                            <span class="text-xs font-bold uppercase text-slate-500 block mb-1">Responses Submitted</span>
                            <span class="text-lg font-bold text-sky-700 font-mono">{{ $exitSurveyResponses->count() }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-center">
                            <span class="text-xs font-bold uppercase text-slate-500 block mb-1">Response Rate</span>
                            <span class="text-lg font-bold text-emerald-700 font-mono">{{ $students->count() > 0 ? round(($exitSurveyResponses->count() / $students->count()) * 100, 1) : 0 }}%</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-center">
                            <span class="text-xs font-bold uppercase text-slate-500 block mb-1">Evaluation Scale</span>
                            <span class="text-xs font-bold text-sky-800 uppercase">3-Level (High/Med/Low)</span>
                        </div>
                    </div>

                    <!-- Direct (80%) vs Indirect (20%) Combined CO Attainment Table -->
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Direct (80%) + Indirect (20%) CO Attainment Level Matrix</h4>
                    <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                        <table class="w-full text-xs text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-700 font-bold uppercase border-b border-slate-200">
                                    <th class="p-3.5">CO Tag</th>
                                    <th class="p-3.5 text-center">Direct Attainment (80%)</th>
                                    <th class="p-3.5 text-center">Indirect Attainment (20%)</th>
                                    <th class="p-3.5 text-center bg-sky-50 text-sky-900">Combined CO Level (1.0 - 3.0)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                                @php
                                    $d = $directStats[$coTag] ?? ['level' => 0, 'percentage' => 0];
                                    $ind = $indirectStats[$coTag] ?? ['level' => 0, 'rating' => '-'];
                                    $comb = $combinedStats[$coTag] ?? 0;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-3.5 font-bold font-mono text-sky-700 text-xs">{{ $coTag }}</td>
                                    <td class="p-3.5 text-center font-mono text-slate-600">Level {{ $d['level'] }} ({{ $d['percentage'] }}%)</td>
                                    <td class="p-3.5 text-center font-mono text-slate-600">Level {{ $ind['level'] }} ({{ $ind['rating'] }})</td>
                                    <td class="p-3.5 text-center font-bold font-mono text-sky-800 text-xs bg-sky-50/50">{{ number_format($comb, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Visual CO Attainment Bar Graph -->
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Indirect CO Attainment Graphical Level Distribution</h4>
                    <div class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        @foreach(['CO1' => 'CO1 - Health & Posture Principles', 'CO2' => 'CO2 - Fitness & Warming-up Drills', 'CO3' => 'CO3 - Major Games & Athletic Skills', 'CO4' => 'CO4 - Yoga, Stress Relief & First Aid'] as $cKey => $cTitle)
                        @php
                            $indObj = $indirectStats[$cKey] ?? ['avg_score' => 2.5, 'percentage' => 83.3, 'level' => 3.0];
                            $cPct = $indObj['percentage'];
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-slate-700 mb-1">
                                <span>{{ $cTitle }}</span>
                                <span class="font-mono text-sky-700">{{ $indObj['avg_score'] }} / 3.0 ({{ $cPct }}%) - Level {{ $indObj['level'] }}</span>
                            </div>
                            <div class="w-full bg-slate-200 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-sky-500 h-full rounded-full transition-all duration-500" style="width: {{ $cPct }}%;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- PO Attainment Level Summary Table -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Program Outcome (PO) Attainment Summary</h3>
                    <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                        <table class="w-full text-xs text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-700 font-bold uppercase border-b border-slate-200">
                                    @for($p=1; $p<=11; $p++)
                                    <th class="p-3 text-center">PO{{ $p }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @for($p=1; $p<=11; $p++)
                                    @php $poVal = $poAttainments["PO{$p}"]['value'] ?? 0.0; @endphp
                                    <td class="p-3 text-center font-bold font-mono text-xs {{ $poVal > 0 ? 'text-sky-800 bg-sky-50/50' : 'text-slate-400' }}">
                                        {{ number_format($poVal, 2) }}
                                    </td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Modern CampusLynk Health & Physical Syllabus Upload Modal -->
    <div id="uploadSyllabusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs hidden p-4">
        <div class="bg-white border border-slate-200/90 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
                        <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h6"/><path d="M9 11h6"/></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Upload Health &amp; Physical Syllabus PDF</h3>
                        <p class="text-xs text-slate-500">Extracts 45h Physical Education modules &amp; Rubric titles</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('uploadSyllabusModal').classList.add('hidden')" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            
            <form id="uploadSyllabusForm" onsubmit="uploadSyllabusPdf(event)" class="space-y-3">
                @csrf
                <div id="hpSyllabusDropzone" ondragover="handleHpDragOver(event)" ondragleave="handleHpDragLeave(event)" ondrop="handleHpFileDrop(event)" onclick="document.getElementById('hpSyllabusInput').click()" class="border-2 border-dashed border-slate-300 hover:border-blue-500 bg-slate-50/70 hover:bg-blue-50/40 rounded-2xl p-6 text-center space-y-2 transition cursor-pointer">
                    <div class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-600 mx-auto flex items-center justify-center border border-blue-200">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Select Health &amp; Physical Syllabus PDF</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Drag &amp; drop PDF file here, or <span class="text-blue-600 font-semibold underline">browse</span></p>
                    </div>
                    <div class="flex items-center justify-center gap-2 text-xs font-semibold text-slate-400 pt-1">
                        <span class="px-2 py-0.5 rounded bg-white border border-slate-200">PDF only</span>
                        <span>•</span>
                        <span class="px-2 py-0.5 rounded bg-white border border-slate-200">Max 10MB</span>
                    </div>
                    <input type="file" id="hpSyllabusInput" name="syllabus_file" accept=".pdf" required class="hidden" onchange="handleHpFileInput(this)">
                </div>

                <!-- Selected File Preview -->
                <div id="hpFilePreview" class="hidden p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/></svg>
                        <span id="hpFileName" class="text-xs font-bold text-slate-900 truncate max-w-[200px]">syllabus.pdf</span>
                    </div>
                    <button type="button" onclick="cancelHpSelectedFile(event)" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                <!-- Processing Indeterminate State -->
                <div id="hpProcessingState" class="hidden p-3.5 bg-blue-50/60 border border-blue-200 rounded-xl text-center space-y-1">
                    <div class="flex items-center justify-center gap-2 text-blue-700 text-xs font-bold">
                        <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                        <span>Extracting Health &amp; Physical Curriculum...</span>
                    </div>
                    <p class="text-[11px] text-slate-500">Parsing Fitness Drills, Games, Yoga, and CO-PO matrix.</p>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('uploadSyllabusModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">Cancel</button>
                    <button type="submit" id="btnSubmitHpSyllabus" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <span>Upload &amp; Extract Syllabus</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Preview Mid-Sem Survey Modal -->
    <div id="previewMidSemModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs hidden p-4">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 max-w-xl w-full mx-4 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-comments text-amber-500 mr-2"></i>Mid-Semester Survey Questionnaire
                </h3>
                <button type="button" onclick="closePreviewModal('previewMidSemModal')" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all cursor-pointer">
                    &times;
                </button>
            </div>
            <div class="space-y-2.5 max-h-96 overflow-y-auto pr-2 text-xs">
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-800">
                    <span class="text-amber-600 font-bold font-mono">Q1:</span> Pace of coverage for physical fitness sessions and posture correction drills.
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-800">
                    <span class="text-amber-600 font-bold font-mono">Q2:</span> Clarity of practical demonstrations &amp; athletic exercise techniques by staff.
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-800">
                    <span class="text-amber-600 font-bold font-mono">Q3:</span> Availability of playground facilities, sports equipment, and safety measures.
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-800">
                    <span class="text-amber-600 font-bold font-mono">Q4:</span> Overall satisfaction with Health &amp; Physical Education practical sessions.
                </div>
            </div>
            <div class="flex justify-end pt-3 border-t border-slate-100">
                <button type="button" onclick="closePreviewModal('previewMidSemModal')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-colors cursor-pointer">Close</button>
            </div>
        </div>
    </div>

    <!-- Preview End-Semester Exit Survey Modal -->
    <div id="previewExitSurveyModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs hidden p-4">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 max-w-2xl w-full mx-4 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-graduation-cap text-sky-600 mr-2"></i>End-Semester Course Exit Survey Questionnaire (High/Med/Low)
                </h3>
                <button type="button" onclick="closePreviewModal('previewExitSurveyModal')" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all cursor-pointer">
                    &times;
                </button>
            </div>
            <div class="space-y-2.5 max-h-96 overflow-y-auto pr-2 text-xs">
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-800">
                    <span class="text-sky-700 font-bold font-mono">CO1 (Q1):</span> How well did you understand personal health, hygiene, and physical fitness principles?
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-800">
                    <span class="text-sky-700 font-bold font-mono">CO1 (Q2):</span> Rate your ability to calculate BMI and analyze posture alignment.
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-800">
                    <span class="text-sky-700 font-bold font-mono">CO2 (Q3):</span> How effectively can you execute warming-up protocols and calisthenics?
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-800">
                    <span class="text-sky-700 font-bold font-mono">CO2 (Q4):</span> Rate your performance in cardiovascular endurance and track drills.
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-800">
                    <span class="text-sky-700 font-bold font-mono">CO3 (Q5):</span> How confident are you in executing skills and rules of major sports (Volleyball/Football)?
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-800">
                    <span class="text-sky-700 font-bold font-mono">CO3 (Q6):</span> Rate your understanding of athletic track events and relay techniques.
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-800">
                    <span class="text-sky-700 font-bold font-mono">CO4 (Q7):</span> How effectively can you perform yogic asanas and relaxation techniques?
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-800">
                    <span class="text-sky-700 font-bold font-mono">CO4 (Q8):</span> Rate your competence in first aid procedures and CPR fundamentals.
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-800">
                    <span class="text-sky-700 font-bold font-mono">CO4 (Q9):</span> Rate your overall improvement in physical fitness and logbook maintenance.
                </div>
            </div>
            <div class="flex justify-end pt-3 border-t border-slate-100">
                <button type="button" onclick="closePreviewModal('previewExitSurveyModal')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-colors cursor-pointer">Close</button>
            </div>
        </div>
    </div>

    <!-- JavaScript Handlers -->
    <script>
        const subjectId = "{{ $batchSubject->id }}";

        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.className = "tab-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition border border-transparent whitespace-nowrap cursor-pointer";
            });

            document.getElementById(tabId).classList.remove('hidden');
            const activeBtn = document.getElementById('btn-' + tabId);
            if (activeBtn) {
                activeBtn.className = "tab-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-2xs whitespace-nowrap cursor-pointer";
            }
        }

        function calcTotal(regNo) {
            let inputs = document.querySelectorAll('.crit-input-' + regNo);
            let sum = 0;
            inputs.forEach(inp => {
                sum += parseFloat(inp.value || 0);
            });
            document.getElementById('tot_' + regNo).innerText = sum.toFixed(1);
        }

        function syncSlider(regNo, key, val) {
            const slider = document.getElementById(`s_${regNo}_${key}`);
            if (slider) slider.value = val;
            calcTotal(regNo);
        }

        function syncInput(regNo, key, val) {
            const input = document.getElementById(`m_${regNo}_${key}`);
            if (input) input.value = val;
            calcTotal(regNo);
        }

        function syncFitnessSlider(testNo, regNo, val) {
            const slider = document.getElementById(`s_${testNo}_${regNo}`);
            if (slider) slider.value = val;
        }

        function syncFitnessInput(testNo, regNo, val) {
            const input = document.getElementById(`${testNo}_${regNo}`);
            if (input) input.value = val;
        }

        function handleHpDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropzone = document.getElementById('hpSyllabusDropzone');
            if (dropzone) dropzone.classList.add('border-blue-500', 'bg-blue-50/60');
        }

        function handleHpDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropzone = document.getElementById('hpSyllabusDropzone');
            if (dropzone) dropzone.classList.remove('border-blue-500', 'bg-blue-50/60');
        }

        function handleHpFileDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropzone = document.getElementById('hpSyllabusDropzone');
            if (dropzone) dropzone.classList.remove('border-blue-500', 'bg-blue-50/60');

            const files = e.dataTransfer.files;
            if (!files || files.length === 0) return;
            const file = files[0];
            if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
                alert('Please drop a valid PDF file.');
                return;
            }
            const input = document.getElementById('hpSyllabusInput');
            if (input) {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                showHpFilePreview(file);
            }
        }

        function handleHpFileInput(input) {
            if (!input.files || input.files.length === 0) return;
            showHpFilePreview(input.files[0]);
        }

        function showHpFilePreview(file) {
            const dropzone = document.getElementById('hpSyllabusDropzone');
            const preview = document.getElementById('hpFilePreview');
            const nameEl = document.getElementById('hpFileName');
            if (nameEl) nameEl.innerText = file.name;
            if (dropzone) dropzone.classList.add('hidden');
            if (preview) preview.classList.remove('hidden');
        }

        function cancelHpSelectedFile(e) {
            if (e) { e.preventDefault(); e.stopPropagation(); }
            const input = document.getElementById('hpSyllabusInput');
            if (input) input.value = '';
            const dropzone = document.getElementById('hpSyllabusDropzone');
            const preview = document.getElementById('hpFilePreview');
            const processing = document.getElementById('hpProcessingState');

            if (preview) preview.classList.add('hidden');
            if (processing) processing.classList.add('hidden');
            if (dropzone) dropzone.classList.remove('hidden');
        }

        async function uploadSyllabusPdf(e) {
            e.preventDefault();
            const form = document.getElementById('uploadSyllabusForm');
            const input = document.getElementById('hpSyllabusInput');
            if (!input || !input.files || input.files.length === 0) {
                alert('Please select a syllabus PDF file.');
                return;
            }
            const formData = new FormData(form);
            const btnSubmit = document.getElementById('btnSubmitHpSyllabus');
            const preview = document.getElementById('hpFilePreview');
            const processing = document.getElementById('hpProcessingState');

            if (btnSubmit) btnSubmit.disabled = true;
            if (preview) preview.classList.add('hidden');
            if (processing) processing.classList.remove('hidden');

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
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Upload failed.'));
                    if (processing) processing.classList.add('hidden');
                    const dropzone = document.getElementById('hpSyllabusDropzone');
                    if (dropzone) dropzone.classList.remove('hidden');
                    if (btnSubmit) btnSubmit.disabled = false;
                }
            } catch (err) {
                alert('Upload failed: ' + err.message);
                if (processing) processing.classList.add('hidden');
                const dropzone = document.getElementById('hpSyllabusDropzone');
                if (dropzone) dropzone.classList.remove('hidden');
                if (btnSubmit) btnSubmit.disabled = false;
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
                proposed_date: document.getElementById('pdate_{{ $lp->id }}').value,
                actual_date: document.getElementById('adate_{{ $lp->id }}').value,
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

        function openPreviewModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closePreviewModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        async function initiateMidSemSurvey() {
            try {
                const res = await fetch(`/api/classroom/${subjectId}/mid-sem/initiate`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                alert(data.message);
                window.location.reload();
            } catch (err) { alert('Action failed: ' + err.message); }
        }

        async function closeMidSemSurvey() {
            try {
                const res = await fetch(`/api/classroom/${subjectId}/mid-sem/close`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                alert(data.message);
                window.location.reload();
            } catch (err) { alert('Action failed: ' + err.message); }
        }

        async function initiateExitSurvey() {
            try {
                const res = await fetch(`/api/classroom/${subjectId}/course-exit/initiate`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                alert(data.message);
                window.location.reload();
            } catch (err) { alert('Action failed: ' + err.message); }
        }

        async function closeExitSurvey() {
            try {
                const res = await fetch(`/api/classroom/${subjectId}/course-exit/close`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                alert(data.message);
                window.location.reload();
            } catch (err) { alert('Action failed: ' + err.message); }
        }
    </script>
</body>
</html>
