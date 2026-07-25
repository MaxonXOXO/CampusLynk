<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }}) - Practicum Virtual Classroom | Revision 2026</title>
    
    <!-- Google Fonts & Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #030712;
            color: #f3f4f6;
            background-image: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.15) 0%, transparent 60%);
        }
        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.4);
        }
        .glass-card {
            background: rgba(17, 24, 39, 0.55);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover {
            border-color: rgba(99, 102, 241, 0.2);
            box-shadow: 0 12px 40px 0 rgba(99, 102, 241, 0.08);
        }
        .mode-btn {
            background: rgba(31, 41, 55, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            color: #9ca3af;
            transition: all 0.25s ease;
        }
        .mode-btn:hover {
            color: #ffffff;
            background: rgba(31, 41, 55, 0.6);
            border-color: rgba(255, 255, 255, 0.1);
        }
        .mode-btn.active {
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
            color: #ffffff !important;
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        }
        .subtab-btn {
            background: transparent;
            color: #9ca3af;
            border-bottom: 2px solid transparent;
            transition: all 0.25s ease;
        }
        .subtab-btn:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.03);
        }
        .subtab-btn.active {
            color: #818cf8 !important;
            border-bottom-color: #6366f1;
            font-weight: 700;
        }
        /* Strict Minimum Font Size Policy Compliance */
        input, select, textarea, button, table, td, th, label, p, span, div {
            font-size: 0.9375rem !important; /* 15px minimum for high readability */
        }
        /* Compact Font Size Specifically for 90-Hour Dense Lesson Planner */
        .lp-table input, .lp-table select, .lp-table td, .lp-table th, .lp-table span, .lp-table button {
            font-size: 0.8125rem !important; /* 13px compact font for high density */
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
        }
        /* Custom Scrollbars */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0b0f19;
        }
        ::-webkit-scrollbar-thumb {
            background: #1f2937;
            border-radius: 6px;
            border: 2px solid #0b0f19;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #374151;
        }
    </style>
</head>
<body class="min-h-screen pb-12">

    <!-- 1. TOP HEADER CONTAINER -->
    <header class="glass-panel sticky top-0 z-40 border-b border-slate-800 px-4 md:px-8 py-3">
        <div class="max-w-[98%] mx-auto flex flex-col xl:flex-row items-start xl:items-center justify-between gap-3">
            
            <!-- Left: Noticeable Back Button & Subject Details -->
            <div class="flex items-center space-x-3.5 w-full xl:w-auto">
                <a href="javascript:void(0)" onclick="if (document.referrer && !document.referrer.includes(window.location.pathname)) { window.location.href = document.referrer; } else { window.history.back(); }" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 via-pink-600 to-amber-600 hover:from-rose-500 hover:to-amber-500 text-white font-extrabold shadow-lg shadow-rose-500/25 transition-all flex items-center space-x-2 border border-rose-400/40 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Back</span>
                </a>
                
                <div class="space-y-0.5">
                    <div class="flex items-center space-x-2.5 flex-wrap gap-y-1">
                        <h1 class="text-lg font-bold text-white tracking-tight">{{ $batchSubject->subject_name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full bg-purple-500/20 text-purple-300 border border-purple-500/30 text-xs font-semibold whitespace-nowrap">
                            Practicum Course (Rev 2026)
                        </span>
                    </div>
                    
                    <p class="text-slate-400 text-sm">
                        Subject Code: <span class="text-white font-semibold">{{ $batchSubject->subject_code }}</span> | 
                        Batch Code: <span class="text-amber-400 font-bold font-mono">{{ $batchSubject->classroom_id }}</span> | 
                        Branch: <span class="text-blue-300 font-semibold">{{ function_exists('getFullBranchName') ? getFullBranchName($classroom->department ?? $classroom->branch ?? '') : ($classroom->department ?? $classroom->branch) }}</span> | 
                        Semester: <span class="text-white font-semibold">{{ $practicumCourseFile->semester }}</span>
                    </p>
                </div>
            </div>

            <!-- Right: Logged-In & Assigned Faculty Info -->
            <div class="px-4 py-2 rounded-xl bg-slate-900/90 border border-slate-700/80 text-slate-300 flex items-center space-x-3 flex-shrink-0">
                <div class="p-1.5 rounded-lg bg-purple-500/20 text-purple-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="leading-tight">
                    <div class="text-white font-semibold text-sm">
                        Faculty: <span class="text-purple-300 font-bold">
                            {{ Session::get('userName') ?? 'Faculty In-Charge' }}
                            @if(isset($assignedStaff) && count($assignedStaff) > 0)
                                @foreach($assignedStaff as $stf)
                                    @if($stf->name !== Session::get('userName'))
                                        , {{ $stf->name }}
                                    @endif
                                @endforeach
                            @endif
                        </span>
                    </div>
                    <div class="text-slate-400 text-xs mt-0.5">
                        Scheme: <span class="text-white font-bold">{{ $practicumCourseFile->teaching_scheme }}</span> | 
                        Credits: <span class="text-emerald-400 font-bold">{{ $practicumCourseFile->credits }}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- 2. SUB-HEADER CONTROL CONSOLE BAR -->
    <div class="max-w-[98%] mx-auto px-4 md:px-8 mt-3">
        <div class="glass-card p-3.5 rounded-xl border border-slate-800 flex items-center justify-between flex-wrap gap-3">
            
            <!-- Hours & Assessment Badges -->
            <div class="flex items-center space-x-3 flex-wrap gap-y-2">
                <div class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-slate-300">
                    Theory: <span class="font-bold text-blue-400">45 Hrs</span> (L)
                </div>
                <div class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-slate-300">
                    Practical: <span class="font-bold text-emerald-400">45 Hrs</span> (P)
                </div>
                <div class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-slate-300">
                    Total Schedule: <span class="font-bold text-purple-400">90 Hrs</span>
                </div>
                <div class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-slate-300">
                    CIA: <span class="font-bold text-amber-400">40M</span> | ESE: <span class="font-bold text-indigo-400">{{ $practicumCourseFile->ese_marks }}M</span>
                </div>
            </div>

            <!-- Action Controls -->
            <div class="flex items-center space-x-2.5 flex-wrap gap-y-2">
                
                <!-- Upload Syllabus -->
                <button onclick="openSyllabusModal()" class="px-3.5 py-2 rounded-lg bg-blue-600/20 hover:bg-blue-600/35 border border-blue-500/40 text-blue-300 font-bold transition-all flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Upload Syllabus</span>
                </button>

                <!-- View Syllabus PDF -->
                @if($practicumCourseFile->syllabus_pdf_path)
                <a href="/storage/{{ $practicumCourseFile->syllabus_pdf_path }}" target="_blank" class="px-3.5 py-2 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/35 border border-emerald-500/40 text-emerald-300 font-bold transition-all flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>View Syllabus PDF</span>
                </a>
                @endif

                <!-- Course File Console -->
                <a href="/r26/classroom/practicum/course-file/{{ $batchSubject->id }}" class="px-3.5 py-2 rounded-lg bg-purple-600/20 hover:bg-purple-600/35 border border-purple-500/40 text-purple-300 font-bold transition-all flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    <span>Course File Console</span>
                </a>

                <!-- Fullscreen Button -->
                <button onclick="toggleFullscreen()" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 transition-all flex items-center space-x-1" title="Toggle Fullscreen">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- 3. TOP-LEVEL DUAL MODE SWITCHER -->
    <main class="max-w-[98%] mx-auto px-4 md:px-8 mt-4">
        
        <div class="glass-panel p-2 rounded-xl mb-5 flex items-center justify-center space-x-3">
            <button onclick="switchMode('theory')" id="mode-btn-theory" class="mode-btn active w-1/2 py-3 rounded-xl font-bold transition-all flex items-center justify-center space-x-2 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>📖 Virtual Theory Classroom (Practicum - 45 L Hours)</span>
            </button>
            <button onclick="switchMode('lab')" id="mode-btn-lab" class="mode-btn w-1/2 py-3 rounded-xl font-bold text-slate-300 hover:text-white hover:bg-slate-800/60 transition-all flex items-center justify-center space-x-2 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                <span>🔬 Virtual Lab (Practicum - 45 P Hours)</span>
            </button>
        </div>

        <!-- ========================================================================= -->
        <!-- MODE A: VIRTUAL THEORY CLASSROOM (PRACTICUM)                              -->
        <!-- ========================================================================= -->
        <div id="mode-theory-container" class="space-y-5">
            
            <!-- Theory Sub-Tabs Navigation -->
            <div class="glass-card p-1.5 rounded-xl flex items-center space-x-2 overflow-x-auto">
                <button onclick="switchTheorySubtab('overview')" id="theory-tab-overview" class="subtab-btn active px-4 py-2 rounded-lg font-semibold whitespace-nowrap">📘 Modules & COs</button>
                <button onclick="switchTheorySubtab('planner')" id="theory-tab-planner" class="subtab-btn px-4 py-2 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📅 Combined 90-Hr Lesson Plan</button>
                <button onclick="switchTheorySubtab('sl')" id="theory-tab-sl" class="subtab-btn px-4 py-2 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📝 Self-Learning (CA - 5M)</button>
                <button onclick="switchTheorySubtab('series')" id="theory-tab-series" class="subtab-btn px-4 py-2 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">✍️ Theory Series Exams (4 COs - 10M)</button>
                <button onclick="switchTheorySubtab('ese')" id="theory-tab-ese" class="subtab-btn px-4 py-2 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">🏆 Theory ESE & Results</button>
            </div>

            <!-- Subtab 1: Theory Modules, COs & CO-PO Mapping Table -->
            <div id="theory-subcontent-overview" class="space-y-5">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <div class="lg:col-span-2 space-y-4">
                        @foreach($practicumCourseFile->parsed_modules as $mod)
                        <div class="glass-card p-4 rounded-xl border border-slate-800">
                            <div class="flex items-center justify-between mb-1.5">
                                <h3 class="font-bold text-blue-400">Module {{ $mod['module_id'] }}: {{ $mod['title'] }}</h3>
                                <span class="px-2.5 py-0.5 rounded bg-blue-500/10 text-blue-300 font-semibold text-xs">{{ $mod['hours'] ?? 15 }} Lecture Hours</span>
                            </div>
                            <p class="text-slate-300 leading-relaxed">{{ $mod['content'] }}</p>
                        </div>
                        @endforeach
                    </div>

                    <div class="space-y-4">
                        <div class="glass-card p-4 rounded-xl border border-slate-800 space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="font-bold text-emerald-400 text-base">🔬 Practical Lab Experiments Summary</h3>
                                <span class="text-xs px-2.5 py-0.5 rounded bg-emerald-500/10 text-emerald-300 font-semibold border border-emerald-500/20">45 P Hours</span>
                            </div>
                            <div class="space-y-2 max-h-[520px] overflow-y-auto pr-1">
                                @foreach($practicumCourseFile->parsed_experiments as $exp)
                                <div class="p-3 rounded-xl bg-slate-900/70 border border-slate-800/80">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-emerald-400 text-xs">{{ $exp['experiment_no'] }}</span>
                                        <span class="px-2 py-0.5 rounded bg-purple-500/10 text-purple-300 text-xs font-semibold border border-purple-500/20">{{ $exp['co_id'] }}</span>
                                    </div>
                                    <p class="text-slate-200 text-sm font-medium leading-snug">{{ $exp['title'] }}</p>
                                    <div class="text-slate-400 text-xs mt-1 font-semibold">{{ $exp['hours'] ?? 3 }} Hours Session</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CO-PO Articulation Matrix Table -->
                <div class="glass-card p-5 rounded-xl border border-slate-800 space-y-3">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-2">
                        <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                            <span>🎯 Course Articulation Matrix (CO-PO Mapping)</span>
                        </h3>
                        <span class="text-slate-400 text-xs font-medium">Correlation Levels: 3 = High, 2 = Medium, 1 = Low, - = None</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr class="bg-slate-900/80 border-b border-slate-800 text-slate-300 font-bold">
                                    <th class="p-3 text-left w-24">CO</th>
                                    <th class="p-3 text-left">Course Outcome Description</th>
                                    @for($p = 1; $p <= 11; $p++)
                                    <th class="p-2.5 w-12 font-bold text-indigo-400">PO{{ $p }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @foreach($practicumCourseFile->parsed_cos as $co)
                                <tr class="hover:bg-slate-800/30">
                                    <td class="p-3 text-left font-bold text-amber-400">{{ $co['id'] }}</td>
                                    <td class="p-3 text-left text-slate-300 text-sm">{{ $co['description'] }}</td>
                                    @for($p = 1; $p <= 11; $p++)
                                        @php
                                            $val = $mappings[$co['id']]['PO' . $p] ?? '-';
                                        @endphp
                                        <td class="p-2.5 font-bold {{ $val !== '-' ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-500' }}">
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

            <!-- Subtab 2: Combined 90-Hour Practicum Lesson Planner (Interactive Table & Print) -->
            <div id="theory-subcontent-planner" class="glass-card p-5 rounded-xl border border-slate-800 hidden space-y-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-3 border-b border-slate-800 pb-3">
                    <div>
                        <h3 class="text-lg font-bold text-white">Practicum Combined Lesson Planner (90 Hours Schedule)</h3>
                        <p class="text-slate-400 text-xs mt-0.5">Includes 45 Theory Lecture Hours (L), 45 Practical Lab Hours (P), 4 Theory Series (ST), & 2 Lab Series (SP).</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button onclick="saveAllLessonPlans()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-lg flex items-center space-x-2 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Save All Changes</span>
                        </button>

                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-lesson-plan" target="_blank" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Print Lesson Plan</span>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto max-h-[650px] overflow-y-auto">
                    <table class="w-full text-left border-collapse lp-table">
                        <thead class="sticky top-0 z-10 bg-slate-900 shadow">
                            <tr class="border-b border-slate-800 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="p-3 w-16 text-center">Day/Hr</th>
                                <th class="p-3 w-36">Pedagogy</th>
                                <th class="p-3 w-32">Proposed Date</th>
                                <th class="p-3 w-32">Actual Date</th>
                                <th class="p-3 w-[40%]">Topic & Content Description</th>
                                <th class="p-3 w-24 text-center">CO</th>
                                <th class="p-3 w-32">Sub-Batch</th>
                                <th class="p-3 w-24 text-center">Hours Needed</th>
                                <th class="p-3 w-32">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm">
                            @foreach($lessonPlans as $plan)
                            <tr id="lp-row-{{ $plan->id }}" data-plan-id="{{ $plan->id }}" class="hover:bg-slate-800/30 transition-all">
                                <td class="p-2.5 font-bold text-center text-white">{{ $plan->day_no }}</td>
                                <td class="p-2.5">
                                    <select id="lp-pedagogy-{{ $plan->id }}" onchange="onPedagogyChange({{ $plan->id }}, this.value)" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 font-bold text-xs w-full {{ $plan->mode === 'L' ? 'text-blue-400' : ($plan->mode === 'P' ? 'text-emerald-400' : 'text-purple-400') }}">
                                        <option value="Lecture (L)" {{ ($plan->pedagogy ?? 'Lecture (L)') === 'Lecture (L)' || ($plan->mode === 'L' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Lecture (L)</option>
                                        <option value="Practical Lab (P)" {{ ($plan->pedagogy ?? '') === 'Practical Lab (P)' || ($plan->mode === 'P' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Practical Lab (P)</option>
                                        <option value="Theory Series Exam (ST)" {{ ($plan->pedagogy ?? '') === 'Theory Series Exam (ST)' || ($plan->mode === 'ST' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Theory Series Exam (ST)</option>
                                        <option value="Practical Series Exam (SP)" {{ ($plan->pedagogy ?? '') === 'Practical Series Exam (SP)' || ($plan->mode === 'SP' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Practical Series Exam (SP)</option>
                                        <option value="PPT Presentation" {{ ($plan->pedagogy ?? '') === 'PPT Presentation' ? 'selected' : '' }}>PPT Presentation</option>
                                        <option value="Demonstration" {{ ($plan->pedagogy ?? '') === 'Demonstration' ? 'selected' : '' }}>Demonstration</option>
                                        <option value="Group Activity" {{ ($plan->pedagogy ?? '') === 'Group Activity' ? 'selected' : '' }}>Group Activity</option>
                                    </select>
                                </td>
                                <td class="p-2.5">
                                    <input type="date" id="lp-prop-{{ $plan->id }}" value="{{ $plan->proposed_date }}" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-200 text-xs w-full">
                                </td>
                                <td class="p-2.5">
                                    <input type="date" id="lp-act-{{ $plan->id }}" value="{{ $plan->actual_date }}" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-200 text-xs w-full">
                                </td>
                                <td class="p-2.5">
                                    <textarea id="lp-topic-{{ $plan->id }}" rows="2" class="bg-slate-900 border border-slate-700 rounded p-2 text-slate-100 text-sm font-medium w-full focus:border-blue-500 outline-none resize-y leading-snug" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ $plan->topic_content }}</textarea>
                                </td>
                                <td class="p-2.5 text-center">
                                    <span class="px-2.5 py-1 rounded-lg bg-amber-500/15 text-amber-400 border border-amber-500/30 text-xs font-mono font-bold inline-block">
                                        {{ $plan->co_id }}
                                    </span>
                                    <input type="hidden" id="lp-co-{{ $plan->id }}" value="{{ $plan->co_id }}">
                                </td>
                                <td id="lp-batch-td-{{ $plan->id }}" class="p-2.5">
                                    @if(in_array($plan->mode, ['P', 'SP']) || (isset($plan->pedagogy) && (stripos($plan->pedagogy, 'Practical') !== false || stripos($plan->pedagogy, 'Lab') !== false)))
                                        <select id="lp-batch-{{ $plan->id }}" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 font-bold text-xs text-emerald-400 w-full">
                                            <option value="Batch A & B" {{ ($plan->sub_batch ?? 'Batch A & B') === 'Batch A & B' ? 'selected' : '' }}>Batch A & B (Combined)</option>
                                            <option value="Batch A" {{ ($plan->sub_batch ?? '') === 'Batch A' ? 'selected' : '' }}>Batch A</option>
                                            <option value="Batch B" {{ ($plan->sub_batch ?? '') === 'Batch B' ? 'selected' : '' }}>Batch B</option>
                                        </select>
                                    @else
                                        <span class="px-2.5 py-1 rounded bg-slate-900/80 text-slate-400 font-semibold text-xs border border-slate-800 inline-block">
                                            All Students
                                        </span>
                                        <input type="hidden" id="lp-batch-{{ $plan->id }}" value="All Students">
                                    @endif
                                </td>
                                <td id="lp-hours-td-{{ $plan->id }}" class="p-2.5 text-center font-bold">
                                    @if(in_array($plan->mode, ['P', 'SP']) || (isset($plan->pedagogy) && (stripos($plan->pedagogy, 'Practical') !== false || stripos($plan->pedagogy, 'Lab') !== false)))
                                        <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold">3 Hours</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30 text-xs font-bold">1 Hour</span>
                                    @endif
                                </td>
                                <td class="p-2.5">
                                    <input type="text" id="lp-remarks-{{ $plan->id }}" value="{{ $plan->remarks }}" placeholder="Status/Remarks" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-400 text-xs w-full">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Subtab 3: Self-Learning Activities (CA - 5 CIA Marks) -->
            <div id="theory-subcontent-sl" class="glass-card p-5 rounded-xl border border-slate-800 hidden space-y-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 border-b border-slate-800 pb-3">
                    <div class="space-y-1">
                        <h3 class="text-lg font-bold text-white">Self-Learning Evaluation & Customization (CA - 5 CIA Marks)</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            Mandatory Core: <span class="font-bold text-amber-400">Assignment</span> & <span class="font-bold text-emerald-400">MCQ</span> (Out of 15 Marks).<br>
                            Custom Catalog: Case Study, Quiz, Activity, Microproject, Mini Project, Report, Exercises, Presentation.
                        </p>
                    </div>
                    <div class="flex items-center space-x-2 flex-wrap gap-y-2 flex-shrink-0">
                        <button onclick="openSlConfigModal()" class="px-3 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-xs transition-all flex items-center space-x-1.5 shadow whitespace-nowrap">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Customize Activities</span>
                        </button>

                        <button onclick="openSlMarksModal()" class="px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md flex items-center space-x-1.5 transition-all whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Enter CA Marks</span>
                        </button>

                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-self-learning-splitup" target="_blank" class="px-3 py-2 rounded-lg bg-teal-600/20 hover:bg-teal-600/35 border border-teal-500/40 text-teal-300 font-bold text-xs transition-all flex items-center space-x-1.5 whitespace-nowrap no-underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Print Splitup Report</span>
                        </a>

                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-self-learning-summary" target="_blank" class="px-3 py-2 rounded-lg bg-blue-600/20 hover:bg-blue-600/35 border border-blue-500/40 text-blue-300 font-bold text-xs transition-all flex items-center space-x-1.5 whitespace-nowrap no-underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Print Summary Report</span>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider bg-slate-900/80">
                                <th class="p-2 text-center w-12">Roll</th>
                                <th class="p-2">Reg No</th>
                                <th class="p-2">SBTE Reg No</th>
                                <th class="p-2">Student Name</th>
                                <th class="p-2">Active Activities</th>
                                <th class="p-2 text-center">Avg Raw Score (/15M)</th>
                                <th class="p-2 text-center">Converted CIA (/5M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 font-medium">
                            @foreach($studentResults as $res)
                            <tr class="hover:bg-slate-800/40 transition-all">
                                <td class="p-2 text-center font-bold text-slate-300">{{ $res['roll_no'] }}</td>
                                <td class="p-2 font-mono text-slate-400 text-xs">{{ $res['reg_no'] }}</td>
                                <td class="p-2 font-mono text-emerald-400 font-bold text-xs">{{ $res['sbte_reg_no'] ?: '-' }}</td>
                                <td class="p-2 font-bold text-white text-xs">{{ $res['name'] }}</td>
                                <td class="p-2">
                                    <div class="flex items-center space-x-1 text-[11px]">
                                        <span class="px-1.5 py-0.5 rounded bg-amber-500/10 text-amber-300 font-bold border border-amber-500/20">Assignment</span>
                                        <span class="px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-300 font-bold border border-emerald-500/20">MCQ</span>
                                    </div>
                                </td>
                                <td class="p-2 text-center font-semibold text-slate-200">{{ number_format(($res['sl_marks'] / 5.0) * 15.0, 2) }} / 15.00</td>
                                <td class="p-2 text-center font-bold text-emerald-400">{{ number_format($res['sl_marks'], 2) }} / 5.00</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Subtab 4: Theory Series Examinations -->
            <div id="theory-subcontent-series" class="space-y-4 hidden">

                <!-- Subject Type Classification Badge -->
                <div class="glass-card p-3 rounded-xl border border-slate-700 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1.5 rounded-lg bg-blue-600/20 text-blue-300 font-bold text-sm border border-blue-500/30">
                            {{ $subjectType['label'] ?? '💻 Program Core (Table 6.4 - ESE 100M)' }}
                        </span>
                        <span class="text-slate-400 text-xs">SBTE Revision 2026 Annexure IV — Internal Test Pattern Classification</span>
                    </div>
                </div>

                <!-- QP Generator Panel — 4 Cards -->
                <div class="glass-card p-5 rounded-xl border border-amber-700/40 bg-amber-900/10">
                    <div class="mb-4">
                        <h3 class="text-base font-bold text-amber-300">📄 Series Exam QP Generator — SBTE 2026</h3>
                        <p class="text-slate-400 text-xs mt-1">
                            @if(($subjectType['pattern'] ?? '') === 'table_4_2_design')
                                Table 4.2 Design Paper: Part A (6×5=30M) + Part B (2×10=20M) = 50 Marks | 2 Hours
                            @else
                                Single CO Test: Part A (2×1=2M) + Part B (3×3=9M) + Part C (answer any 2 of 3 × 7=14M) = 25 Marks | 1½ Hours
                            @endif
                            | Scaled to 10 CIA Marks
                        </p>
                    </div>

                    <!-- 4 Series Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(['Series 1' => 'CO1', 'Series 2' => 'CO2', 'Series 3' => 'CO3', 'Series 4' => 'CO4'] as $series => $co)
                        @php $savedQp = $seriesQps[$series] ?? null; @endphp
                        <div class="rounded-xl border {{ $savedQp ? 'border-emerald-600/50 bg-emerald-900/15' : 'border-slate-700 bg-slate-800/50' }} p-3 flex flex-col gap-2">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-white text-sm">{{ $series }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $savedQp ? 'bg-emerald-600/30 text-emerald-300' : 'bg-slate-700 text-slate-400' }}">{{ $co }}</span>
                            </div>

                            <!-- Status -->
                            @if($savedQp)
                            <div class="text-xs text-emerald-400 font-semibold">✅ QP Saved</div>
                            @else
                            <div class="text-xs text-slate-500">⬜ Not generated</div>
                            @endif

                            <!-- Generate buttons -->
                            <div class="flex flex-col gap-1.5 mt-1">
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $co }}', 'ai')"
                                    class="w-full py-1.5 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white transition-all text-center">
                                    ⚡ AI Generate
                                </button>
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $co }}', 'manual')"
                                    class="w-full py-1.5 rounded-lg text-xs font-bold bg-slate-700 hover:bg-slate-800 text-white transition-all text-center">
                                    ✏ Manual Entry
                                </button>
                            </div>

                            <!-- Print buttons (only if saved) -->
                            @if($savedQp)
                            <div class="border-t border-slate-700/50 pt-2 flex flex-col gap-1.5">
                                <a href="{{ url('r26/classroom/practicum/'.request()->segment(4).'/series-qp/print-qp/'.urlencode($series)) }}" target="_blank"
                                    class="w-full py-1.5 rounded-lg text-xs font-bold bg-indigo-950/40 hover:bg-indigo-900/60 border border-indigo-500/30 text-indigo-300 text-center block">
                                    🖨️ Print QP
                                </a>
                                <div class="grid grid-cols-2 gap-1.5">
                                    <a href="{{ url('r26/classroom/practicum/'.request()->segment(4).'/series-qp/print-scheme/'.urlencode($series)) }}" target="_blank"
                                        class="py-1.5 rounded-lg text-xs font-bold bg-slate-800 hover:bg-slate-700 border border-slate-750 text-slate-300 text-center block">
                                        📋 Scheme
                                    </a>
                                    <a href="{{ url('r26/classroom/practicum/'.request()->segment(4).'/series-qp/print-key/'.urlencode($series)) }}" target="_blank"
                                        class="py-1.5 rounded-lg text-xs font-bold bg-slate-800 hover:bg-slate-700 border border-slate-750 text-slate-300 text-center block">
                                        🔑 Key
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div><!-- /grid -->

                    <div id="qp-gen-status" class="mt-3 text-xs text-slate-400 hidden"></div>
                </div><!-- /QP Generator Panel -->


                <!-- Theory Series Marks -->
                <div class="glass-card p-5 rounded-xl border border-slate-800">
                    <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-white">Theory Series Examinations (Four 1-Hour CO Tests - 10 CIA Marks)</h3>
                            <p class="text-slate-400 text-xs mt-0.5">4 Series Tests (CO1, CO2, CO3, CO4 - 2 Hours each out of 50 marks), averaged and scaled to 10 CIA marks</p>
                        </div>
                        <button onclick="openSeriesTheoryModal()" class="px-3.5 py-2 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-bold shadow-md">Enter Theory Series Marks</button>
                    </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-semibold bg-slate-900/60">
                                <th class="p-3">Roll</th>
                                <th class="p-3">Reg No</th>
                                <th class="p-3">Student Name</th>
                                <th class="p-3 text-center">Test 1 (CO1)</th>
                                <th class="p-3 text-center">Test 2 (CO2)</th>
                                <th class="p-3 text-center">Test 3 (CO3)</th>
                                <th class="p-3 text-center">Test 4 (CO4)</th>
                                <th class="p-3 text-center">Avg (/50)</th>
                                <th class="p-3 text-center">Converted CIA (/10M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @foreach($studentResults as $res)
                            @php
                                $stEvals = $seriesTheoryEvals->get($res['reg_no'], collect());
                                $s1 = $stEvals->whereIn('series_no', ['Series 1', 'CO1'])->first();
                                $s2 = $stEvals->whereIn('series_no', ['Series 2', 'CO2'])->first();
                                $s3 = $stEvals->whereIn('series_no', ['Series 3', 'CO3'])->first();
                                $s4 = $stEvals->whereIn('series_no', ['Series 4', 'CO4'])->first();
                            @endphp
                            <tr>
                                <td class="p-3 text-slate-300">{{ $res['roll_no'] }}</td>
                                <td class="p-3 font-mono text-slate-300">{{ $res['reg_no'] }}</td>
                                <td class="p-3 font-bold text-white">{{ $res['name'] }}</td>
                                <td class="p-3 text-center text-slate-200">{{ $s1 ? number_format($s1->total_score_50, 2) : '-' }}</td>
                                <td class="p-3 text-center text-slate-200">{{ $s2 ? number_format($s2->total_score_50, 2) : '-' }}</td>
                                <td class="p-3 text-center text-slate-200">{{ $s3 ? number_format($s3->total_score_50, 2) : '-' }}</td>
                                <td class="p-3 text-center text-slate-200">{{ $s4 ? number_format($s4->total_score_50, 2) : '-' }}</td>
                                <td class="p-3 text-center font-semibold text-slate-200">{{ number_format($res['series_theory_marks'] * 5, 2) }}</td>
                                <td class="p-3 text-center font-bold text-purple-400">{{ number_format($res['series_theory_marks'], 2) }} / 10.00</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /inner glass-card (marks table) -->
            </div>
            <!-- /outer space-y-4 (theory-subcontent-series) -->

            <!-- Subtab 5: Theory ESE & Consolidated Results -->
            <div id="theory-subcontent-ese" class="space-y-5 hidden">
                <div class="glass-card p-5 rounded-xl border border-slate-800">
                    <h3 class="text-lg font-bold text-white mb-3">Written Theory End Semester Exam (60 Marks)</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 font-semibold bg-slate-900/60">
                                    <th class="p-3">Roll</th>
                                    <th class="p-3">Reg No</th>
                                    <th class="p-3">Student Name</th>
                                    <th class="p-3">Theory ESE Score (/60)</th>
                                    <th class="p-3">Pass Status (Min 24/60)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @foreach($studentResults as $res)
                                <tr>
                                    <td class="p-3 text-slate-300">{{ $res['roll_no'] }}</td>
                                    <td class="p-3 font-mono text-slate-300">{{ $res['reg_no'] }}</td>
                                    <td class="p-3 font-bold text-white">{{ $res['name'] }}</td>
                                    <td class="p-3 font-bold text-indigo-400">{{ number_format($res['ese_theory'], 2) }} / 60.00</td>
                                    <td class="p-3 font-bold {{ $res['ese_theory'] >= 24 ? 'text-emerald-400' : 'text-rose-400' }}">
                                        {{ $res['ese_theory'] >= 24 ? 'PASSED (Theory)' : 'REAPPEAR' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Consolidated Results -->
                <div class="glass-card p-5 rounded-xl border border-slate-800 space-y-4">
                    <h3 class="text-lg font-bold text-white">🏆 NBA Attainment Summary (Direct 80% + Indirect 20%)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                        <div class="p-4 rounded-xl bg-slate-900/70 border border-slate-800 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-amber-400 text-base">{{ $coTag }}</span>
                                <span class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-xs font-bold">Level {{ $combinedStats[$coTag] ?? 0.0 }} / 3.0</span>
                            </div>
                            <div class="text-slate-300 text-xs space-y-1">
                                <div>Direct Attainment: <span class="font-bold text-emerald-400">{{ $directStats[$coTag]['level'] ?? 0 }}</span> ({{ $directStats[$coTag]['percentage'] ?? 0 }}% Students)</div>
                                <div>Indirect Attainment: <span class="font-bold text-blue-400">{{ $indirectStats[$coTag]['level'] ?? 0 }}</span></div>
                                <div>Overall (80:20): <span class="font-bold text-amber-300">{{ $combinedStats[$coTag] ?? 0 }}</span></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- PO Attainment Row -->
                    <div class="mt-4 pt-4 border-t border-slate-800">
                        <h4 class="font-bold text-slate-200 text-sm mb-3">Calculated Program Outcome (PO) Attainment Scores</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-11 gap-2 text-center">
                            @for($p = 1; $p <= 11; $p++)
                            @php $po = "PO" . $p; @endphp
                            <div class="p-2.5 rounded-lg bg-slate-900 border border-slate-800">
                                <div class="text-xs text-slate-400 font-bold">{{ $po }}</div>
                                <div class="font-extrabold text-indigo-400 text-base mt-0.5">{{ $poAttainments[$po]['value'] ?? 0.0 }}</div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- CIA Summary Card -->
                <div class="glass-card p-5 rounded-xl border border-slate-800">
                    <h3 class="text-lg font-bold text-white mb-1">Consolidated Continuous Internal Assessment (CIA - 40 Marks Table 1.4)</h3>
                    <p class="text-slate-400 text-xs mb-3">Attendance (5M) + CA1 Self Learning (5M) + CE Continuous Lab (10M) + CA2/CA3 Practical Tests (10M) + CA4/CA5 Theory Tests (10M) = 40 CIA Marks</p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 font-semibold bg-slate-900/60">
                                    <th class="p-2.5">Roll</th>
                                    <th class="p-2.5">Reg No</th>
                                    <th class="p-2.5">SBTE Reg No</th>
                                    <th class="p-2.5">Student Name</th>
                                    <th class="p-2.5 text-center">Att (5M)</th>
                                    <th class="p-2.5 text-center">CA1 SL (5M)</th>
                                    <th class="p-2.5 text-center">CE Lab (10M)</th>
                                    <th class="p-2.5 text-center">CA4/5 Th Tests (10M)</th>
                                    <th class="p-2.5 text-center">CA2/3 Pr Tests (10M)</th>
                                    <th class="p-2.5 text-center">Total CIA (40M)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @foreach($studentResults as $res)
                                <tr class="hover:bg-slate-800/30 transition-all">
                                    <td class="p-2.5 text-slate-300">{{ $res['roll_no'] }}</td>
                                    <td class="p-2.5 font-mono text-slate-400 text-xs">{{ $res['reg_no'] }}</td>
                                    <td class="p-2.5 font-mono text-emerald-400 font-bold text-xs">{{ $res['sbte_reg_no'] ?: '-' }}</td>
                                    <td class="p-2.5 font-bold text-white">{{ $res['name'] }}</td>
                                    <td class="p-2.5 text-center font-semibold text-blue-400">{{ $res['att_marks'] }}</td>
                                    <td class="p-2.5 text-center font-semibold text-emerald-400">{{ number_format($res['sl_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center font-semibold text-amber-400">{{ number_format($res['continuous_eval_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center font-semibold text-purple-400">{{ number_format($res['series_theory_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center font-semibold text-indigo-400">{{ number_format($res['series_practical_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center font-extrabold text-amber-400 bg-amber-500/10 rounded-lg border border-amber-500/20">
                                        {{ number_format($res['total_cia_marks'], 2) }} / 40.00
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- MODE B: VIRTUAL LAB (PRACTICUM)                                           -->
        <!-- ========================================================================= -->
        <div id="mode-lab-container" class="space-y-5 hidden">
            
            <!-- Lab Sub-Tabs Navigation -->
            <div class="glass-card p-1.5 rounded-xl flex items-center space-x-2 overflow-x-auto">
                <button onclick="switchLabSubtab('roster')" id="lab-tab-roster" class="subtab-btn active px-4 py-2 rounded-lg font-semibold whitespace-nowrap">🧪 3-Hour Lab Sessions</button>
                <button onclick="switchLabSubtab('planner')" id="lab-tab-planner" class="subtab-btn px-4 py-2 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📅 Lab Planner (45 P Hrs)</button>
                <button onclick="switchLabSubtab('eval')" id="lab-tab-eval" class="subtab-btn px-4 py-2 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">🔬 Continuous Lab Eval (CE - 10M)</button>
                <button onclick="switchLabSubtab('series')" id="lab-tab-series" class="subtab-btn px-4 py-2 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📝 Practical Series Tests (2 Tests - 10M)</button>
                <button onclick="switchLabSubtab('ese')" id="lab-tab-ese" class="subtab-btn px-4 py-2 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">🏆 Practical ESE (40M)</button>
            </div>

            <!-- Subtab 1: 3-Hour Session Experiments Roster -->
            <div id="lab-subcontent-roster" class="glass-card p-5 rounded-xl border border-slate-800">
                <h3 class="text-lg font-bold text-white mb-1">Practical Experiments Roster (3-Hour Session Blocks)</h3>
                <p class="text-slate-400 text-xs mb-3">All practical topics automatically divided into 3-hour lab sessions as per Revision 2026 rules.</p>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-semibold bg-slate-900/60">
                                <th class="p-3">Session Code</th>
                                <th class="p-3">Experiment Title</th>
                                <th class="p-3">Mapped CO</th>
                                <th class="p-3">Duration</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @foreach($practicumCourseFile->parsed_experiments as $exp)
                            <tr class="hover:bg-slate-800/30 transition-all">
                                <td class="p-3 font-bold text-emerald-400">{{ $exp['experiment_no'] }}</td>
                                <td class="p-3 text-slate-200 font-medium">{{ $exp['title'] }}</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded bg-purple-500/10 text-purple-300 font-semibold border border-purple-500/20 text-xs">{{ $exp['co_id'] }}</span></td>
                                <td class="p-3 text-slate-300 font-semibold">{{ $exp['hours'] ?? 3 }} Hours (1 Session)</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Subtab 2: Lab Planner View -->
            <div id="lab-subcontent-planner" class="glass-card p-5 rounded-xl border border-slate-800 hidden">
                <h3 class="text-lg font-bold text-white mb-3">Practical Sessions Planner (45 P Hours)</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-semibold bg-slate-900/60">
                                <th class="p-3">Day / Hr</th>
                                <th class="p-3">Lab Session Topic</th>
                                <th class="p-3">Mapped CO</th>
                                <th class="p-3">Sub-Batch</th>
                                <th class="p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($lessonPlans->whereIn('mode', ['P', 'SP']) as $plan)
                            <tr>
                                <td class="p-3 font-bold text-white">Hr {{ $plan->day_no }}</td>
                                <td class="p-3 text-slate-200">{{ $plan->topic_content }}</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded bg-purple-500/10 text-purple-300 font-bold text-xs">{{ $plan->co_id }}</span></td>
                                <td class="p-3 text-slate-300">{{ $plan->sub_batch ?? 'Batch A & B' }}</td>
                                <td class="p-3"><span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 font-semibold text-xs">{{ $plan->status ?? 'Completed' }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="p-5 text-center text-slate-400">Practical sessions auto-scheduled upon syllabus upload.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Subtab 3: Continuous Practical Evaluation -->
            <div id="lab-subcontent-eval" class="glass-card p-5 rounded-xl border border-slate-800 hidden">
                <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-white">Continuous Practical Evaluation (CE - 10 CIA Marks)</h3>
                        <p class="text-slate-400 text-xs mt-0.5">Table 2.2 Rubrics (Criteria 1 to 6 out of 50 Marks) converted to 10 CIA marks</p>
                    </div>
                    <button onclick="openExperimentEvalModal()" class="px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold shadow-md">Evaluate Experiment</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-semibold bg-slate-900/60">
                                <th class="p-3">Roll</th>
                                <th class="p-3">Reg No</th>
                                <th class="p-3">Student Name</th>
                                <th class="p-3">Evaluated Sessions</th>
                                <th class="p-3">Avg (/50)</th>
                                <th class="p-3">Converted CIA (/10M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @foreach($studentResults as $res)
                            <tr>
                                <td class="p-3 text-slate-300">{{ $res['roll_no'] }}</td>
                                <td class="p-3 font-mono text-slate-300">{{ $res['reg_no'] }}</td>
                                <td class="p-3 font-bold text-white">{{ $res['name'] }}</td>
                                <td class="p-3 text-slate-300">{{ isset($experimentEvals[$res['reg_no']]) ? count($experimentEvals[$res['reg_no']]) : 0 }} Sessions</td>
                                <td class="p-3 text-slate-200">{{ number_format($res['continuous_eval_marks'] * 5, 2) }}</td>
                                <td class="p-3 font-bold text-amber-400">{{ number_format($res['continuous_eval_marks'], 2) }} / 10.00</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Subtab 4: Practical Series Examinations -->
            <div id="lab-subcontent-series" class="glass-card p-5 rounded-xl border border-slate-800 hidden">
                <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-white">Practical Series Examinations (Two 3-Hour Combined Tests - 10 CIA Marks)</h3>
                        <p class="text-slate-400 text-xs mt-0.5">Practical Test 1 (CO1+CO2 by 11th week) & Practical Test 2 (CO3+CO4 by 14th week, Table 3.1 Rubrics out of 40 -> converted to 10 CIA marks)</p>
                    </div>
                    <button onclick="openSeriesPracticalModal()" class="px-3.5 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-bold shadow-md">Enter Lab Series Test Marks</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-semibold bg-slate-900/60">
                                <th class="p-3">Roll</th>
                                <th class="p-3">Reg No</th>
                                <th class="p-3">Student Name</th>
                                <th class="p-3 text-center">Practical Test 1 (CO1+CO2 /40)</th>
                                <th class="p-3 text-center">Practical Test 2 (CO3+CO4 /40)</th>
                                <th class="p-3 text-center">Avg (/40)</th>
                                <th class="p-3 text-center">Converted CIA (/10M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @foreach($studentResults as $res)
                            @php
                                $spEvals = $seriesPracticalEvals->get($res['reg_no'], collect());
                                $sp1 = $spEvals->whereIn('series_no', ['Series 1', 'Test 1 (CO1+CO2)'])->first();
                                $sp2 = $spEvals->whereIn('series_no', ['Series 2', 'Test 2 (CO3+CO4)'])->first();
                            @endphp
                            <tr>
                                <td class="p-3 text-slate-300">{{ $res['roll_no'] }}</td>
                                <td class="p-3 font-mono text-slate-300">{{ $res['reg_no'] }}</td>
                                <td class="p-3 font-bold text-white">{{ $res['name'] }}</td>
                                <td class="p-3 text-center text-slate-200">{{ $sp1 ? number_format($sp1->total_score_40, 2) : '-' }}</td>
                                <td class="p-3 text-center text-slate-200">{{ $sp2 ? number_format($sp2->total_score_40, 2) : '-' }}</td>
                                <td class="p-3 text-center font-semibold text-slate-200">{{ number_format($res['series_practical_marks'] * 4, 2) }}</td>
                                <td class="p-3 text-center font-bold text-blue-400">{{ number_format($res['series_practical_marks'], 2) }} / 10.00</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Subtab 5: Practical ESE -->
            <div id="lab-subcontent-ese" class="glass-card p-5 rounded-xl border border-slate-800 hidden">
                <h3 class="text-lg font-bold text-white mb-3">Institutional Practical End Semester Exam (40 Marks)</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-semibold bg-slate-900/60">
                                <th class="p-3">Roll</th>
                                <th class="p-3">Reg No</th>
                                <th class="p-3">Student Name</th>
                                <th class="p-3">Practical ESE Score (/40)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @foreach($studentResults as $res)
                            <tr>
                                <td class="p-3 text-slate-300">{{ $res['roll_no'] }}</td>
                                <td class="p-3 font-mono text-slate-300">{{ $res['reg_no'] }}</td>
                                <td class="p-3 font-bold text-white">{{ $res['name'] }}</td>
                                <td class="p-3 font-bold text-purple-400">{{ number_format($res['ese_practical'], 2) }} / 40.00</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </main>

    <!-- Customize Self-Learning Activities Modal -->
    <div id="sl-config-modal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center hidden p-4">
        <div class="glass-card max-w-2xl w-full p-6 rounded-2xl border border-slate-700 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-xl font-bold text-white">Customize Self-Learning Activities (CA1)</h3>
                <button onclick="closeSlConfigModal()" class="text-slate-400 hover:text-white text-xl">&times;</button>
            </div>
            
            <p class="text-slate-400 text-xs">Mandatory core activities (<span class="text-amber-400 font-bold">Assignment</span> & <span class="text-emerald-400 font-bold">MCQ</span>) are always evaluated out of 15 Marks. Select optional assessment activities per CO:</p>

            <form id="sl-config-form" onsubmit="saveSlConfig(event)" class="space-y-4 max-h-[450px] overflow-y-auto pr-1">
                @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 space-y-2">
                    <h4 class="font-bold text-amber-400 text-sm">{{ $coTag }} Assessment Activities</h4>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                        <label class="flex items-center space-x-2 text-slate-300 opacity-80 cursor-not-allowed">
                            <input type="checkbox" checked disabled class="rounded bg-slate-800 border-slate-700 text-amber-500">
                            <span class="font-bold">Assignment (Mandatory)</span>
                        </label>
                        <label class="flex items-center space-x-2 text-slate-300 opacity-80 cursor-not-allowed">
                            <input type="checkbox" checked disabled class="rounded bg-slate-800 border-slate-700 text-emerald-500">
                            <span class="font-bold">MCQ (Mandatory)</span>
                        </label>
                        @foreach(['case_study' => 'Case Study', 'quiz' => 'Quiz', 'activity' => 'Activity', 'microproject' => 'Microproject', 'mini_project' => 'Mini Project', 'report' => 'Report', 'exercises' => 'Exercises', 'presentation' => 'Presentation'] as $actKey => $actLabel)
                        <label class="flex items-center space-x-2 text-slate-200 cursor-pointer">
                            <input type="checkbox" name="configs[{{ $coTag }}][{{ $actKey }}]" value="1" class="rounded bg-slate-800 border-slate-700 text-blue-500 focus:ring-0">
                            <span>{{ $actLabel }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <div class="flex items-center justify-end space-x-3 pt-2">
                    <button type="button" onclick="closeSlConfigModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold shadow-lg shadow-purple-500/25">Save Activities Config</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Enter Self-Learning Marks Modal (CA1 Activity-Wise Sliders) -->
    <div id="sl-marks-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center hidden p-3 sm:p-5">
        <div class="glass-card max-w-3xl w-full p-5 rounded-2xl border border-slate-700 shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-800 pb-3 flex-shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-white">Continuous Assessment Activity Evaluator</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Adjust sliders or tap +/- steppers to evaluate activity-wise splitup for each student.</p>
                </div>
                <button onclick="closeSlMarksModal()" class="text-slate-400 hover:text-white text-2xl font-bold">&times;</button>
            </div>

            <!-- Student Selection & Stepper Bar -->
            <div class="bg-slate-900/90 p-3 rounded-xl border border-slate-800 flex items-center justify-between gap-2 flex-shrink-0">
                <button type="button" onclick="prevSlStudent()" class="px-3 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs flex items-center space-x-1">
                    <span>◀ Prev</span>
                </button>

                <div class="flex-1 max-w-md">
                    <select id="sl-student-select" onchange="loadSlStudent(this.value)" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-1.5 font-bold text-sm text-white outline-none focus:border-emerald-500">
                        @foreach($studentResults as $idx => $res)
                        <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }} (SBTE: {{ $res['sbte_reg_no'] ?: $res['reg_no'] }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" onclick="nextSlStudent()" class="px-3 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs flex items-center space-x-1">
                    <span>Next ▶</span>
                </button>
            </div>

            <!-- Live Score Summary Card -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950/40 to-slate-900 p-3 rounded-xl border border-indigo-500/30 flex items-center justify-between text-xs flex-shrink-0">
                <div>
                    <span class="text-slate-400 font-medium">Selected Student Average:</span>
                    <span id="sl-student-total-raw" class="font-extrabold text-amber-400 text-sm ml-1.5">0.00 / 15.00 M</span>
                </div>
                <div class="flex items-center space-x-1">
                    <span class="text-slate-400 font-medium">Converted CA1 CIA:</span>
                    <span id="sl-student-converted-cia" class="font-black text-emerald-400 text-base ml-1.5 px-2.5 py-0.5 rounded bg-emerald-500/20 border border-emerald-500/30">0.00 / 5.00 M</span>
                </div>
            </div>

            <!-- Scrollable Activity Sliders Container -->
            <div id="sl-sliders-container" class="overflow-y-auto space-y-4 flex-1 pr-1">
                <!-- Dynamically populated by JS loadSlStudent() -->
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-800 flex-shrink-0">
                <button type="button" onclick="closeSlMarksModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs hover:bg-slate-700">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextSlStudent()" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md">Next Student ▶</button>
                    <button type="button" onclick="saveAllSlMarks()" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-500/25">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Switching & Handlers -->
    <script>
        function switchMode(mode) {
            document.getElementById('mode-theory-container').classList.add('hidden');
            document.getElementById('mode-lab-container').classList.add('hidden');
            
            document.getElementById('mode-btn-theory').classList.remove('active', 'text-white');
            document.getElementById('mode-btn-lab').classList.remove('active', 'text-white');

            if (mode === 'theory') {
                document.getElementById('mode-theory-container').classList.remove('hidden');
                document.getElementById('mode-btn-theory').classList.add('active', 'text-white');
            } else {
                document.getElementById('mode-lab-container').classList.remove('hidden');
                document.getElementById('mode-btn-lab').classList.add('active', 'text-white');
            }
        }

        function switchTheorySubtab(tab) {
            ['overview', 'planner', 'sl', 'series', 'ese'].forEach(t => {
                document.getElementById('theory-subcontent-' + t)?.classList.add('hidden');
                document.getElementById('theory-tab-' + t)?.classList.remove('active', 'text-white');
            });
            document.getElementById('theory-subcontent-' + tab)?.classList.remove('hidden');
            document.getElementById('theory-tab-' + tab)?.classList.add('active', 'text-white');
        }

        function switchLabSubtab(tab) {
            ['roster', 'planner', 'eval', 'series', 'ese'].forEach(t => {
                document.getElementById('lab-subcontent-' + t)?.classList.add('hidden');
                document.getElementById('lab-tab-' + t)?.classList.remove('active', 'text-white');
            });
            document.getElementById('lab-subcontent-' + tab)?.classList.remove('hidden');
            document.getElementById('lab-tab-' + tab)?.classList.add('active', 'text-white');
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    alert(`Error attempting to enable fullscreen mode: ${err.message}`);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        function openSyllabusModal() { document.getElementById('syllabus-modal').classList.remove('hidden'); }
        function closeSyllabusModal() { document.getElementById('syllabus-modal').classList.add('hidden'); }

        function openSlConfigModal() { document.getElementById('sl-config-modal').classList.remove('hidden'); }
        function closeSlConfigModal() { document.getElementById('sl-config-modal').classList.add('hidden'); }

        function saveSlConfig(e) {
            e.preventDefault();
            const form = document.getElementById('sl-config-form');
            const formData = new FormData(form);
            const configs = {};

            formData.forEach((val, key) => {
                const matches = key.match(/configs\[(.*?)\]\[(.*?)\]/);
                if (matches) {
                    const co = matches[1];
                    const act = matches[2];
                    if (!configs[co]) configs[co] = { assignment: true, mcq: true };
                    configs[co][act] = true;
                }
            });

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/self-learning/configs', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ configs: configs })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    closeSlConfigModal();
                    Swal.fire('Configured!', data.message, 'success');
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }

        const slConfigs = @json($slConfigs);
        const slSplitupState = @json($slStudentSplitup);
        const studentsList = @json($studentResults->values()->all());

        const activityLabels = {
            'assignment': 'Assignment',
            'mcq': 'MCQ',
            'case_study': 'Case Study',
            'quiz': 'Quiz',
            'activity': 'Activity',
            'microproject': 'Microproject',
            'mini_project': 'Mini Project',
            'report': 'Report',
            'exercises': 'Exercises',
            'presentation': 'Presentation'
        };

        function openSlMarksModal() {
            document.getElementById('sl-marks-modal').classList.remove('hidden');
            const sel = document.getElementById('sl-student-select');
            if (sel && sel.value) {
                loadSlStudent(sel.value);
            }
        }

        function closeSlMarksModal() {
            document.getElementById('sl-marks-modal').classList.add('hidden');
        }

        function loadSlStudent(regNo) {
            const container = document.getElementById('sl-sliders-container');
            if (!container) return;

            if (!slSplitupState[regNo]) {
                slSplitupState[regNo] = {
                    'CO1': { assignment: 0, mcq: 0 },
                    'CO2': { assignment: 0, mcq: 0 },
                    'CO3': { assignment: 0, mcq: 0 },
                    'CO4': { assignment: 0, mcq: 0 }
                };
            }

            let html = '';
            const cos = ['CO1', 'CO2', 'CO3', 'CO4'];

            cos.forEach(co => {
                const activeActs = slConfigs[co] || { assignment: true, mcq: true };
                const actKeys = Object.keys(activeActs).filter(k => activeActs[k]);

                html += `
                    <div class="p-3.5 rounded-xl bg-slate-900/90 border border-slate-800 space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-amber-400 text-sm flex items-center space-x-2">
                                <span>${co} Assessment Activities</span>
                                <span class="text-xs text-slate-400 font-normal">(${actKeys.length} Active)</span>
                            </h4>
                            <span id="co-sum-${co}" class="text-xs font-bold text-slate-300 bg-slate-800 px-2 py-0.5 rounded border border-slate-700">
                                Avg: 0.0 / 15.0
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                `;

                actKeys.forEach(actKey => {
                    const label = activityLabels[actKey] || actKey.toUpperCase();
                    const currentVal = slSplitupState[regNo][co] ? (slSplitupState[regNo][co][actKey] || 0) : 0;

                    html += `
                        <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-800/80 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-200 text-xs uppercase">${label}</span>
                                <span id="badge-${co}-${actKey}" class="px-2.5 py-0.5 rounded bg-amber-500/15 text-amber-300 font-mono text-xs font-bold border border-amber-500/20">
                                    ${parseFloat(currentVal).toFixed(1)} / 15.0
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="stepSlSlider('${regNo}', '${co}', '${actKey}', -0.5)" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 font-extrabold text-white text-base shadow flex items-center justify-center">-</button>
                                <input type="range" id="slider-${co}-${actKey}" min="0" max="15" step="0.5" value="${currentVal}" oninput="syncSlSlider('${regNo}', '${co}', '${actKey}', this.value)" class="flex-1 accent-emerald-400 h-2 bg-slate-800 rounded-lg cursor-pointer">
                                <button type="button" onclick="stepSlSlider('${regNo}', '${co}', '${actKey}', 0.5)" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 font-extrabold text-white text-base shadow flex items-center justify-center">+</button>
                            </div>
                        </div>
                    `;
                });

                html += `
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            calculateSlLiveTotal(regNo);
        }

        function syncSlSlider(regNo, co, actKey, val) {
            const num = parseFloat(val) || 0;
            if (!slSplitupState[regNo]) slSplitupState[regNo] = {};
            if (!slSplitupState[regNo][co]) slSplitupState[regNo][co] = {};
            slSplitupState[regNo][co][actKey] = num;

            const badge = document.getElementById(`badge-${co}-${actKey}`);
            if (badge) badge.innerText = `${num.toFixed(1)} / 15.0`;

            calculateSlLiveTotal(regNo);
        }

        function stepSlSlider(regNo, co, actKey, delta) {
            const slider = document.getElementById(`slider-${co}-${actKey}`);
            if (!slider) return;

            let current = parseFloat(slider.value) || 0;
            let next = Math.max(0, Math.min(15, current + delta));
            slider.value = next;
            syncSlSlider(regNo, co, actKey, next);
        }

        function calculateSlLiveTotal(regNo) {
            const data = slSplitupState[regNo] || {};
            let totalScore = 0;
            let totalCount = 0;

            ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
                const coData = data[co] || {};
                let coSum = 0;
                let coCnt = 0;
                Object.values(coData).forEach(val => {
                    coSum += parseFloat(val) || 0;
                    coCnt++;
                });

                const coSumSpan = document.getElementById(`co-sum-${co}`);
                if (coSumSpan) {
                    const coAvg = coCnt > 0 ? (coSum / coCnt) : 0;
                    coSumSpan.innerText = `Avg: ${coAvg.toFixed(2)} / 15.0`;
                }

                totalScore += coSum;
                totalCount += coCnt;
            });

            const overallAvg = totalCount > 0 ? (totalScore / totalCount) : 0;
            const ciaConverted = Math.min(5.0, (overallAvg / 15.0) * 5.0);

            const rawElem = document.getElementById('sl-student-total-raw');
            const ciaElem = document.getElementById('sl-student-converted-cia');

            if (rawElem) rawElem.innerText = `${overallAvg.toFixed(2)} / 15.00 M`;
            if (ciaElem) ciaElem.innerText = `${ciaConverted.toFixed(2)} / 5.00 M`;
        }

        function prevSlStudent() {
            const sel = document.getElementById('sl-student-select');
            if (!sel || sel.selectedIndex <= 0) return;
            sel.selectedIndex--;
            loadSlStudent(sel.value);
        }

        function nextSlStudent() {
            const sel = document.getElementById('sl-student-select');
            if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
            sel.selectedIndex++;
            loadSlStudent(sel.value);
        }

        function saveAndNextSlStudent() {
            nextSlStudent();
        }

        function saveAllSlMarks() {
            const marksData = [];
            Object.keys(slSplitupState).forEach(regNo => {
                marksData.push({
                    reg_no: regNo,
                    co_details: slSplitupState[regNo]
                });
            });

            Swal.fire({
                title: 'Saving All Student Marks...',
                text: 'Updating activity-wise splitup for CA1',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/self-learning/marks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ marks_data: marksData })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    closeSlMarksModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved Successfully!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', err.message, 'error');
            });
        }

        function saveAllLessonPlans() {
            const rows = document.querySelectorAll('tr[id^="lp-row-"]');
            const plans = [];

            rows.forEach(tr => {
                const planId = tr.getAttribute('data-plan-id');
                if (!planId) return;

                const pedagogy = document.getElementById('lp-pedagogy-' + planId)?.value || 'Lecture (L)';
                const propDate = document.getElementById('lp-prop-' + planId)?.value || '';
                const actDate = document.getElementById('lp-act-' + planId)?.value || '';
                const topic = document.getElementById('lp-topic-' + planId)?.value || '';
                const coId = document.getElementById('lp-co-' + planId)?.value || 'CO1';
                const batch = document.getElementById('lp-batch-' + planId)?.value || '';
                const remarks = document.getElementById('lp-remarks-' + planId)?.value || '';

                plans.push({
                    id: planId,
                    pedagogy: pedagogy,
                    proposed_date: propDate,
                    actual_date: actDate,
                    topic_content: topic,
                    co_id: coId,
                    sub_batch: batch,
                    remarks: remarks
                });
            });

            Swal.fire({
                title: 'Saving All 90 Hours...',
                text: 'Updating complete Practicum lesson plan',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/lesson-plan/save-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ plans: plans })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    Swal.fire('Saved Successfully!', data.message, 'success');
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', err.message, 'error');
            });
        }

        function onPedagogyChange(planId, val) {
            const batchTd = document.getElementById('lp-batch-td-' + planId);
            const hoursTd = document.getElementById('lp-hours-td-' + planId);
            const select = document.getElementById('lp-pedagogy-' + planId);
            if (!batchTd) return;

            const isLab = val.includes('Practical') || val.includes('Lab') || val.includes('(P)') || val.includes('(SP)');

            if (select) {
                select.className = "bg-slate-900 border border-slate-700 rounded px-2 py-1 font-bold text-xs w-full " + 
                    (isLab ? "text-emerald-400" : (val.includes('Series') ? "text-purple-400" : "text-blue-400"));
            }

            if (hoursTd) {
                if (isLab) {
                    hoursTd.innerHTML = `<span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold">3 Hours</span>`;
                } else {
                    hoursTd.innerHTML = `<span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30 text-xs font-bold">1 Hour</span>`;
                }
            }

            if (isLab) {
                batchTd.innerHTML = `
                    <select id="lp-batch-${planId}" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 font-bold text-xs text-emerald-400 w-full">
                        <option value="Batch A & B" selected>Batch A & B (Combined)</option>
                        <option value="Batch A">Batch A</option>
                        <option value="Batch B">Batch B</option>
                    </select>
                `;
            } else {
                batchTd.innerHTML = `
                    <span class="px-2.5 py-1 rounded bg-slate-900/80 text-slate-400 font-semibold text-xs border border-slate-800 inline-block">
                        All Students
                    </span>
                    <input type="hidden" id="lp-batch-${planId}" value="All Students">
                `;
            }
        }

    // =====================================================================
    // Series QP Generator — Preview / Edit Modal System
    // =====================================================================

    const SUBJECT_ID = {{ $batchSubject->id }};
    const QP_PATTERN = '{{ ($subjectType['pattern'] ?? 'table_4_1_standard') }}';
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    let _currentSeries = '', _currentCo = '', _draftQp = {};

    async function openQpPreviewModal(seriesNo, coTag, mode) {
        _currentSeries = seriesNo;
        _currentCo     = coTag;
        const statusEl = document.getElementById('qp-gen-status');
        statusEl.classList.remove('hidden');
        statusEl.style.color = '#94a3b8';

        const modal = document.getElementById('qp-preview-modal');
        modal.classList.remove('hidden');
        document.getElementById('qp-modal-title').textContent = `Series Exam QP — ${seriesNo} (${coTag}) | ${QP_PATTERN === 'table_4_2_design' ? 'Table 4.2 Design' : 'Table 4.1 Standard'}`;

        document.getElementById('qp-editor-body').innerHTML = '<div class="text-slate-400 text-sm p-8 text-center animate-pulse">⚡ Loading questions…</div>';

        if (mode === 'ai') {
            statusEl.innerHTML = `⚡ Fetching AI/Bank questions for <strong>${seriesNo}</strong>...`;
            try {
                const res = await fetch(`/api/r26/classroom/practicum/${SUBJECT_ID}/series-qp/generate/${encodeURIComponent(seriesNo)}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF }
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    _draftQp = data.qp_data;
                    statusEl.innerHTML = `<span style="color:#4ade80">${data.message}</span>`;
                    renderQpEditor(_draftQp, data.pattern_type);
                } else {
                    document.getElementById('qp-editor-body').innerHTML = `<div class="text-red-400 p-6">${data.message}</div>`;
                }
            } catch(e) {
                document.getElementById('qp-editor-body').innerHTML = `<div class="text-red-400 p-6">Network Error: ${e.message}</div>`;
            }
        } else {
            // Manual entry — blank template
            statusEl.innerHTML = `✏ Manual mode — fill in questions for <strong>${seriesNo}</strong>`;
            _draftQp = buildEmptyQpTemplate(QP_PATTERN, coTag);
            renderQpEditor(_draftQp, QP_PATTERN);
        }
    }

    function buildEmptyQpTemplate(pattern, coTag) {
        if (pattern === 'table_4_2_design') {
            return {
                part_a: Array.from({length:6}, (_,i) => ({q_no:String(i+1), text:'', marks:5, co:coTag, bloom:'Understand', scheme_key:'', answer_key:''})),
                part_b: [
                    {q_no:'7(a)', text:'', marks:10, co:coTag, bloom:'Analyze', choice_group:'Set 1', scheme_key:'', answer_key:''},
                    {q_no:'7(b)', text:'OR: ', marks:10, co:coTag, bloom:'Analyze', choice_group:'Set 1', scheme_key:'', answer_key:''},
                    {q_no:'8(a)', text:'', marks:10, co:coTag, bloom:'Analyze', choice_group:'Set 2', scheme_key:'', answer_key:''},
                    {q_no:'8(b)', text:'OR: ', marks:10, co:coTag, bloom:'Analyze', choice_group:'Set 2', scheme_key:'', answer_key:''},
                ]
            };
        } else {
            // Single CO Test: 2×1M + 3×3M + 3×7M (answer any 2) = 25M
            return {
                part_a: [
                    {q_no:'1', text:'', marks:1, co:coTag, bloom:'Remember', scheme_key:'', answer_key:''},
                    {q_no:'2', text:'', marks:1, co:coTag, bloom:'Remember', scheme_key:'', answer_key:''},
                ],
                part_b: [
                    {q_no:'3', text:'', marks:3, co:coTag, bloom:'Understand', scheme_key:'', answer_key:''},
                    {q_no:'4', text:'', marks:3, co:coTag, bloom:'Understand', scheme_key:'', answer_key:''},
                    {q_no:'5', text:'', marks:3, co:coTag, bloom:'Apply', scheme_key:'', answer_key:''},
                ],
                part_c: [
                    {q_no:'6', text:'', marks:7, co:coTag, bloom:'Analyze', choice_group:'Answer any 2 of 3', scheme_key:'', answer_key:''},
                    {q_no:'7', text:'', marks:7, co:coTag, bloom:'Analyze', choice_group:'Answer any 2 of 3', scheme_key:'', answer_key:''},
                    {q_no:'8', text:'', marks:7, co:coTag, bloom:'Analyze', choice_group:'Answer any 2 of 3', scheme_key:'', answer_key:''},
                ]
            };
        }
    }

    function renderQpEditor(qpData, pattern) {
        const container = document.getElementById('qp-editor-body');
        const parts = pattern === 'table_4_2_design'
            ? [['part_a','PART A — Answer ALL (6 × 5M = 30M)','5'],['part_b','PART B — Answer ONE per Set (10M each)','10']]
            : [['part_a','PART A — Answer ALL (2 × 1M = 2M)','1'],['part_b','PART B — Answer ALL (3 × 3M = 9M)','3'],['part_c','PART C — Answer ANY 2 of 3 (7M each = 14M)','7']];

        let html = '';
        for (const [partKey, partLabel, defaultMark] of parts) {
            const rows = qpData[partKey] || [];
            html += `<div class="mb-4">
                <div class="flex items-center justify-between bg-slate-800 px-4 py-2.5 rounded-t-xl border-t border-x border-slate-700">
                    <span class="font-bold text-indigo-300 text-sm">${partLabel}</span>
                    <button onclick="addQpRow('${partKey}','${defaultMark}')" class="text-xs px-2.5 py-1 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition-all">+ Add Question</button>
                </div>
                <div class="border border-slate-700 rounded-b-xl overflow-hidden bg-slate-900/60">
                    <table class="w-full text-sm" id="tbl-${partKey}">
                        <thead class="bg-slate-850 text-slate-400 text-xs">
                            <tr>
                                <th class="p-3 w-14 text-center">Q.No</th>
                                <th class="p-3">Question Text</th>
                                <th class="p-3 w-1/4">Evaluation Scheme (Key Points)</th>
                                <th class="p-3 w-1/4">Answer Key / Model Answer</th>
                                <th class="p-3 w-28">Bloom</th>
                                <th class="p-3 w-14 text-center">Marks</th>
                                <th class="p-3 w-28">Choice Group</th>
                                <th class="p-3 w-10"></th>
                            </tr>
                        </thead>
                        <tbody>`;
            rows.forEach((q, idx) => {
                html += `<tr class="border-b border-slate-800 hover:bg-slate-800/40" data-part="${partKey}" data-idx="${idx}">
                    <td class="p-2"><input type="text" value="${q.q_no||''}" onchange="updateQpField('${partKey}',${idx},'q_no',this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-white font-mono text-center"></td>
                    <td class="p-2"><textarea rows="3" onchange="updateQpField('${partKey}',${idx},'text',this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-white resize-y" placeholder="Type question here…">${q.text||''}</textarea></td>
                    <td class="p-2"><textarea rows="3" onchange="updateQpField('${partKey}',${idx},'scheme_key',this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-emerald-300 resize-y" placeholder="Marking breakdown / keys…">${q.scheme_key||''}</textarea></td>
                    <td class="p-2"><textarea rows="3" onchange="updateQpField('${partKey}',${idx},'answer_key',this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-blue-300 resize-y" placeholder="Model answer text…">${q.answer_key||''}</textarea></td>
                    <td class="p-2"><select onchange="updateQpField('${partKey}',${idx},'bloom',this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-white">
                        ${['Remember','Understand','Apply','Analyze','Evaluate','Create'].map(l=>`<option ${q.bloom===l?'selected':''}>${l}</option>`).join('')}
                    </select></td>
                    <td class="p-2"><input type="number" min="1" max="30" value="${q.marks||defaultMark}" onchange="updateQpField('${partKey}',${idx},'marks',parseInt(this.value))" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-amber-300 font-bold text-center"></td>
                    <td class="p-2"><input type="text" value="${q.choice_group||''}" placeholder="e.g. Answer any 2" onchange="updateQpField('${partKey}',${idx},'choice_group',this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-purple-300"></td>
                    <td class="p-2 text-center"><button onclick="removeQpRow('${partKey}',${idx})" class="text-red-400 hover:text-red-300 text-xs font-bold">✕</button></td>
                </tr>`;
            });
            html += `</tbody></table></div></div>`;
        }
        container.innerHTML = html;
    }

    function updateQpField(part, idx, field, value) {
        if (!_draftQp[part]) return;
        _draftQp[part][idx][field] = value;
    }

    function addQpRow(partKey, defaultMark) {
        if (!_draftQp[partKey]) _draftQp[partKey] = [];
        const idx = _draftQp[partKey].length + 1;
        _draftQp[partKey].push({q_no: String(idx), text: '', marks: parseInt(defaultMark), co: _currentCo, bloom: 'Understand', scheme_key: '', answer_key: ''});
        renderQpEditor(_draftQp, QP_PATTERN);
    }

    function removeQpRow(partKey, idx) {
        if (!_draftQp[partKey]) return;
        _draftQp[partKey].splice(idx, 1);
        renderQpEditor(_draftQp, QP_PATTERN);
    }

    function closeQpModal() {
        document.getElementById('qp-preview-modal').classList.add('hidden');
    }

    async function saveQpFromModal() {
        const statusEl = document.getElementById('qp-gen-status');
        const saveBtn  = document.getElementById('qp-save-btn');
        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving…';

        try {
            const res = await fetch(`/api/r26/classroom/practicum/${SUBJECT_ID}/series-qp/save/${encodeURIComponent(_currentSeries)}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({
                    co_tag: _currentCo,
                    pattern_type: QP_PATTERN,
                    qp_data: _draftQp,
                    scheme_data: _draftQp,
                    answer_key: _draftQp,
                })
            });
            const data = await res.json();
            if (data.status === 'SUCCESS') {
                statusEl.innerHTML = `✅ <strong>${_currentSeries}</strong> QP saved to Question Bank!`;
                statusEl.style.color = '#4ade80';
                statusEl.classList.remove('hidden');
                closeQpModal();
                setTimeout(() => location.reload(), 1200);
            } else {
                saveBtn.disabled = false;
                saveBtn.textContent = '💾 Save & Add to Question Bank';
                alert('Error: ' + data.message);
            }
        } catch(e) {
            saveBtn.disabled = false;
            saveBtn.textContent = '💾 Save & Add to Question Bank';
            alert('Network error: ' + e.message);
        }
    }
    </script>

    <!-- ================================================================
         QP Preview / Edit Modal (Unified Columns Layout)
    ================================================================= -->
    <div id="qp-preview-modal" class="hidden fixed inset-0 z-50 bg-black/80 flex items-start justify-center p-4 overflow-auto">
        <div class="w-full max-w-[98%] bg-slate-900 rounded-2xl shadow-2xl border border-slate-700 flex flex-col" style="max-height:95vh">

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-700 bg-slate-800 rounded-t-2xl">
                <div>
                    <h2 class="text-lg font-bold text-white" id="qp-modal-title">Series QP Preview</h2>
                    <p class="text-slate-400 text-xs mt-0.5">Edit questions, marking schemes, and model answers side-by-side — then Save to Question Bank</p>
                </div>
                <button onclick="closeQpModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none">&times;</button>
            </div>

            <!-- Editor Body -->
            <div id="qp-editor-body" class="flex-1 overflow-y-auto p-6 space-y-2">
                <div class="text-slate-500 text-sm text-center py-12">Loading…</div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-700 bg-slate-800 rounded-b-2xl">
                <button onclick="closeQpModal()" class="px-5 py-2.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-semibold text-sm">Cancel</button>
                <div class="flex items-center gap-3">
                    <span class="text-slate-500 text-xs">Questions, schemes, and model answers are saved together in one step</span>
                    <button id="qp-save-btn" onclick="saveQpFromModal()" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-lg transition-all">
                        💾 Save &amp; Add to Question Bank
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

