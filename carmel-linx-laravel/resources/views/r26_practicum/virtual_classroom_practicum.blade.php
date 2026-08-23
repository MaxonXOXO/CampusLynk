<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[{{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}] Practicum Virtual Classroom - {{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</title>
    
    <!-- Canonical Vite Asset Pipeline -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            letter-spacing: -0.01em;
            text-shadow: none !important;
            filter: none !important;
        }

        span, p, label, button, a, th, td, div {
            text-shadow: none !important;
            filter: none !important;
        }

        .bg-white border border-slate-200/80 rounded-2xl shadow-sm { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05); }
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05);
        }

        .bg-white border border-slate-200/80 rounded-2xl shadow-sm { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05); }
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05);
            transition: all 0.2s ease;
        }

        .bg-white border border-slate-200/80 rounded-2xl shadow-sm:hover { border-color: #cbd5e1; }
            border-color: #cbd5e1;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.07);
        }

        .mode-btn {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #64748b;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            font-weight: 700;
        }

        .mode-btn:hover {
            color: #0f172a;
            background: #e2e8f0;
        }

        .mode-btn.active {
            background: #eff6ff !important;
            color: #1d4ed8 !important;
            border: 1px solid #bfdbfe !important;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .subtab-btn {
            background: transparent;
            color: #64748b;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            font-size: 0.875rem !important; /* 14px */
            padding: 0.5rem 0.875rem;
            border-radius: 0.75rem;
            font-weight: 600;
        }

        .subtab-btn:hover {
            color: #0f172a;
            background: #f8fafc;
        }

        .subtab-btn.active {
            color: #1d4ed8 !important;
            background: #eff6ff !important;
            border-color: #bfdbfe !important;
            font-weight: 700;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        /* Form Inputs & Select Controls */
        input[type="text"], input[type="date"], input[type="number"], select, textarea {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            border-radius: 0.5rem !important;
            font-size: 0.875rem !important; /* 14px minimum */
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input[type="text"]:focus, input[type="date"]:focus, input[type="number"]:focus, select:focus, textarea:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
            outline: none !important;
        }

        /* Table Styling */
        table {
            border-collapse: separate !important;
            border-spacing: 0 !important;
            width: 100%;
        }

        table th {
            background-color: #f8fafc !important;
            color: #475569 !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.04em !important;
            font-size: 0.8125rem !important; /* 13px */
            border-bottom: 1px solid #e2e8f0 !important;
            border-right: 1px solid #f1f5f9 !important;
            padding: 0.625rem 0.75rem !important;
        }

        table td {
            border-bottom: 1px solid #e2e8f0 !important;
            border-right: 1px solid #f1f5f9 !important;
            font-size: 0.875rem !important; /* 14px */
            color: #0f172a !important;
            padding: 0.625rem 0.75rem !important;
        }

        table tr:hover td {
            background-color: #f8fafc !important;
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
<body class="bg-[#FAFAFB] text-slate-900 min-h-screen pb-12 antialiased">
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
        } elseif ($role === 'Gen_Dept_Coordinator_Aided') {
            $dashboardUrl = '/dashboard/general-coordinator-aided';
            $dashboardLabel = 'General Dept Coordinator';
        } elseif ($role === 'Gen_Dept_Coordinator_Self_Finance') {
            $dashboardUrl = '/dashboard/general-coordinator-sf';
            $dashboardLabel = 'General Dept Coordinator';
        } elseif ($role === 'Trade_Instructor') {
            $dashboardUrl = '/dashboard/tradeinstructor';
            $dashboardLabel = 'Trade Instructor Desk';
        } elseif ($role === 'Workshop_Superintendent') {
            $dashboardUrl = '/dashboard/workshop';
            $dashboardLabel = 'Workshop Console';
        }
    @endphp

    <!-- 1. TOP BREADCRUMB & TOOLBAR -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-40 px-4 md:px-8 py-3 shadow-xs">
        <div class="max-w-[1600px] mx-auto flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
            
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-xs sm:text-sm font-medium text-slate-500 flex-wrap">
                <a href="{{ $dashboardUrl }}" class="hover:text-blue-600 transition flex items-center gap-1.5 font-semibold text-slate-700">
                    <span class="material-symbols-rounded text-base text-blue-600">domain</span>
                    <span>{{ $dashboardLabel }}</span>
                </a>
                <span class="text-slate-700">/</span>
                <a href="{{ $dashboardUrl }}" class="hover:text-blue-600 transition font-medium text-slate-600">My Batches</a>
                <span class="text-slate-700">/</span>
                <span class="font-bold text-slate-900 flex items-center gap-1.5">
                    <span>Practicum Virtual Classroom</span>
                    <span class="text-xs font-bold font-mono px-2 py-0.5 bg-purple-50 text-purple-700 border border-purple-200/80 rounded-md">Joint Theory + Lab</span>
                </span>
            </nav>

            <!-- Actions & Staff Context -->
            <div class="flex items-center gap-3 flex-wrap">
                <!-- AI Active Pill -->
                @php $isAiActive = \App\Http\Controllers\SystemSettingController::isAiEnabled(); @endphp
                @if($isAiActive)
                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg font-bold text-xs select-none flex items-center gap-1.5 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>AI Active</span>
                    </span>
                @else
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 border border-slate-200 rounded-lg font-bold text-xs select-none flex items-center gap-1.5 shadow-2xs">
                        <span class="material-symbols-rounded text-xs text-slate-500">database</span>
                        <span>Local Engine</span>
                    </span>
                @endif

                <!-- Faculty Info -->
                <div class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 flex items-center gap-2 text-xs font-semibold">
                    <span class="material-symbols-rounded text-sm text-purple-600">person</span>
                    <span>Faculty: <strong class="text-slate-900">{{ Session::get('userName') ?? 'Faculty In-Charge' }}</strong></span>
                </div>

                <!-- Back Action -->
                <a href="{{ $dashboardUrl }}" onclick="window.close(); setTimeout(function() { window.location.href = '{{ $dashboardUrl }}'; }, 100); return false;" class="px-3.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition border border-rose-200 flex items-center gap-1.5 shadow-2xs">
                    <span class="material-symbols-rounded text-sm">arrow_back</span>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </header>

    <!-- 2. HERO HEADER & METRIC CARD -->
    <div class="max-w-[1600px] mx-auto px-4 md:px-8 mt-5">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-5">
            <div class="space-y-2">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-purple-50 text-purple-700 border border-purple-200/80">
                        {{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R2021 · PRACTICUM' : 'R2026 · PRACTICUM' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-blue-50 text-blue-700 border border-blue-200/80">
                        {{ $batchSubject->classroom_id }} · S{{ $practicumCourseFile->semester }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-md font-mono font-bold text-xs bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $batchSubject->subject_code }}
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    {{ $batchSubject->subject_name }}
                </h1>

                <div class="flex items-center gap-3 text-xs sm:text-sm font-medium text-slate-500 flex-wrap">
                    <span>Theory: <strong class="text-blue-600 font-bold">45 Hrs</strong> (L)</span>
                    <span class="text-slate-700">•</span>
                    <span>Practical: <strong class="text-emerald-600 font-bold">45 Hrs</strong> (P)</span>
                    <span class="text-slate-700">•</span>
                    <span>Total: <strong class="text-purple-600 font-bold">90 Hrs</strong></span>
                    <span class="text-slate-700">•</span>
                    <span>CIA: <strong class="text-amber-600 font-bold">40M</strong> | ESE: <strong class="text-indigo-600 font-bold">{{ $practicumCourseFile->ese_marks }}M</strong></span>
                </div>
            </div>

            <!-- Action Controls -->
            <div class="flex items-center gap-2.5 flex-wrap self-stretch sm:self-auto">
                <button onclick="openSyllabusModal()" class="flex-1 sm:flex-none px-3.5 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs transition border border-blue-200 flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                    <span class="material-symbols-rounded text-sm">upload_file</span>
                    <span>Upload Syllabus</span>
                </button>

                @if($practicumCourseFile->syllabus_pdf_path)
                <a href="/storage/{{ $practicumCourseFile->syllabus_pdf_path }}" target="_blank" class="flex-1 sm:flex-none px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs transition border border-emerald-200 flex items-center justify-center gap-1.5 shadow-2xs">
                    <span class="material-symbols-rounded text-sm">picture_as_pdf</span>
                    <span>View Syllabus PDF</span>
                </a>
                @endif

                <a href="/r26/classroom/practicum/course-file/{{ $batchSubject->id }}" class="flex-1 sm:flex-none px-3.5 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold text-xs transition border border-purple-200 flex items-center justify-center gap-1.5 shadow-2xs">
                    <span class="material-symbols-rounded text-sm">folder_open</span>
                    <span>Course File Console</span>
                </a>

                <button onclick="toggleFullscreen()" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition border border-slate-200 flex items-center gap-1 shadow-2xs cursor-pointer" title="Toggle Fullscreen">
                    <span class="material-symbols-rounded text-sm">fullscreen</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 3. TOP-LEVEL DUAL MODE SWITCHER -->
    <main class="max-w-[1600px] mx-auto px-4 md:px-8 mt-5">
        
        <div class="bg-white border border-slate-200/80 p-2 rounded-2xl mb-5 flex items-center justify-center gap-3 shadow-xs">
            <button onclick="switchMode('theory')" id="mode-btn-theory" class="mode-btn active w-1/2 py-3 rounded-xl font-bold transition-all flex items-center justify-center gap-2 text-sm sm:text-base cursor-pointer">
                <span class="material-symbols-rounded text-lg">menu_book</span>
                <span>Virtual Theory Classroom</span>
            </button>
            <button onclick="switchMode('lab')" id="mode-btn-lab" class="mode-btn w-1/2 py-3 rounded-xl font-bold transition-all flex items-center justify-center gap-2 text-sm sm:text-base cursor-pointer">
                <span class="material-symbols-rounded text-lg">science</span>
                <span>Virtual Lab Workspace</span>
            </button>
        </div>

        <!-- ========================================================================= -->
        <!-- MODE A: VIRTUAL THEORY CLASSROOM (PRACTICUM)                              -->
        <!-- ========================================================================= -->
        <div id="mode-theory-container" class="space-y-5">
            
            <!-- Theory Sub-Tabs Navigation -->
            <div class="bg-white border border-slate-200/80 p-2 rounded-2xl flex items-center gap-2 overflow-x-auto shadow-xs">
                <button onclick="switchTheorySubtab('overview')" id="theory-tab-overview" class="subtab-btn active px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Modules & COs</button>
                <button onclick="switchTheorySubtab('planner')" id="theory-tab-planner" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Lesson Plan</button>
                <button onclick="switchTheorySubtab('sl')" id="theory-tab-sl" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Self-Learning</button>
                <button onclick="switchTheorySubtab('series')" id="theory-tab-series" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Theory Series</button>
                <button onclick="switchTheorySubtab('ese')" id="theory-tab-ese" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Theory ESE</button>
                <button onclick="switchTheorySubtab('surveys')" id="theory-tab-surveys" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Surveys</button>
                <button onclick="switchTheorySubtab('attendance')" id="theory-tab-attendance" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Attendance</button>
                <button onclick="switchTheorySubtab('materials')" id="theory-tab-materials" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Study Materials & Learning Hub</button>
            </div>

            <!-- Subtab 1: Theory Modules, COs & CO-PO Mapping Table -->
            <div id="theory-subcontent-overview" class="space-y-5">
                
                <!-- Practicum Dual Split Identity Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-indigo-50 text-indigo-700 border border-indigo-200/80">
                                    R2026 · PRACTICUM (JOINT THEORY + LAB)
                                </span>
                                <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-purple-50 text-purple-700 border border-purple-200/80">
                                    Semester {{ $batchSubject->semester }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-md font-mono font-bold text-xs bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $batchSubject->subject_code }}
                                </span>
                            </div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                                {{ $batchSubject->subject_name }}
                            </h2>
                        </div>

                        <div class="flex items-center gap-2 flex-wrap">
                            @if($practicumCourseFile && $practicumCourseFile->syllabus_pdf_path)
                                <a href="/storage/{{ $practicumCourseFile->syllabus_pdf_path }}" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 rounded-xl text-xs font-bold transition-all border border-slate-200 flex items-center gap-1.5 shadow-2xs">
                                    <span class="material-symbols-rounded text-base text-rose-600">picture_as_pdf</span>
                                    <span>View Syllabus PDF</span>
                                </a>
                            @endif
                            <button onclick="openSyllabusModal()" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs cursor-pointer">
                                <span class="material-symbols-rounded text-base">upload_file</span>
                                <span>Upload Syllabus</span>
                            </button>
                        </div>
                    </div>

                    <!-- Dual 90-Hour Allocation Metrics -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="bg-blue-50/50 border border-blue-200/70 rounded-xl p-3">
                            <span class="text-xs font-semibold text-blue-700 block uppercase tracking-wider">Theory Component</span>
                            <span class="text-base font-bold text-blue-950 mt-0.5 block">45 Lecture Hours</span>
                        </div>
                        <div class="bg-emerald-50/50 border border-emerald-200/70 rounded-xl p-3">
                            <span class="text-xs font-semibold text-emerald-700 block uppercase tracking-wider">Lab Component</span>
                            <span class="text-base font-bold text-emerald-950 mt-0.5 block">45 Practical Hours</span>
                        </div>
                        <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3">
                            <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Total Workload</span>
                            <span class="text-base font-bold text-slate-900 mt-0.5 block">90 Total Hours</span>
                        </div>
                        <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3">
                            <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Evaluation</span>
                            <span class="text-base font-bold text-slate-900 mt-0.5 block">CIE 60M | ESE 40M</span>
                        </div>
                    </div>
                </div>

                <!-- Theory Modules & Lab Experiments Split Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    
                    <!-- Left 2 Cols: Theory Modules -->
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
                                        <span class="material-symbols-rounded text-base">collections_bookmark</span>
                                    </span>
                                    <h3 class="font-bold text-slate-900 text-base">Theory Modules (45 Hours)</h3>
                                </div>
                                <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg border border-slate-200">
                                    {{ count($practicumCourseFile->parsed_modules ?? []) }} Modules
                                </span>
                            </div>

                            <div class="space-y-3">
                                @forelse(($practicumCourseFile->parsed_modules ?? []) as $mod)
                                <div class="bg-slate-50/60 border border-slate-200/80 rounded-xl p-4 space-y-2 hover:border-blue-300 transition-all">
                                    <div class="flex items-center justify-between gap-2 border-b border-slate-200/60 pb-2">
                                        <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold">Module {{ $mod['module_id'] }}</span>
                                            <span>{{ $mod['title'] ?? 'Unit ' . $mod['module_id'] }}</span>
                                        </h4>
                                        <span class="px-2.5 py-0.5 rounded-md bg-white border border-slate-200 text-slate-700 font-bold text-xs font-mono shadow-2xs">
                                            {{ $mod['hours'] ?? 15 }} Lecture Hours
                                        </span>
                                    </div>
                                    <p class="text-sm font-normal text-slate-700 leading-relaxed whitespace-pre-line pt-1">{{ $mod['content'] }}</p>
                                </div>
                                @empty
                                <div class="text-center py-6 text-slate-500 text-sm italic bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                    No theory modules extracted yet.
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Right Col: Lab Experiments Summary -->
                    <div class="space-y-4">
                        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold border border-emerald-200/80">
                                        <span class="material-symbols-rounded text-base">science</span>
                                    </span>
                                    <h3 class="font-bold text-slate-900 text-base">Lab Experiments (45h)</h3>
                                </div>
                                <span class="text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200">
                                    45 P Hours
                                </span>
                            </div>

                            <div class="space-y-2.5 max-h-[560px] overflow-y-auto pr-1">
                                @forelse(($practicumCourseFile->parsed_experiments ?? []) as $exp)
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 hover:border-emerald-300 transition-all">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <span class="font-bold font-mono text-emerald-700 text-xs px-2 py-0.5 rounded bg-emerald-50 border border-emerald-200">{{ $exp['experiment_no'] }}</span>
                                        <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 text-xs font-semibold border border-purple-200">{{ $exp['co_id'] }}</span>
                                    </div>
                                    <p class="text-slate-800 text-sm font-medium leading-snug">{{ $exp['title'] }}</p>
                                    <div class="text-slate-500 text-xs mt-1.5 font-semibold flex items-center gap-1">
                                        <span class="material-symbols-rounded text-xs text-slate-400">schedule</span>
                                        <span>{{ $exp['hours'] ?? 3 }} Hours Session</span>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-6 text-slate-500 text-sm italic bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                    No experiments configured yet.
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CO-PO Articulation Matrix Table -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center text-sm font-bold border border-indigo-200/80">
                                <span class="material-symbols-rounded text-base">grid_on</span>
                            </span>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Course Articulation Matrix (CO-PO Mapping)</h3>
                                <p class="text-xs text-slate-500">Mapping strengths: 3 = High, 2 = Medium, 1 = Low</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="printSubtabReport('Theory Modules & CO-PO Matrix Report', 'theory-subcontent-overview')" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition-all flex items-center gap-1.5 shadow-2xs no-print cursor-pointer">
                                <span class="material-symbols-rounded text-sm">print</span>
                                <span>Print Matrix</span>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-center border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold text-xs uppercase">
                                    <th class="p-3 text-left w-24 pl-4">CO</th>
                                    <th class="p-3 text-left">Course Outcome Description</th>
                                    @for($p = 1; $p <= 11; $p++)
                                    <th class="p-2.5 w-12 font-mono">PO{{ $p }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse(($practicumCourseFile->parsed_cos ?? []) as $co)
                                <tr class="hover:bg-slate-50/80 transition-all">
                                    <td class="p-3 text-left font-bold text-blue-700 pl-4 font-mono">{{ $co['id'] }}</td>
                                    <td class="p-3 text-left text-slate-800 font-medium leading-relaxed">{{ $co['description'] }}</td>
                                    @for($p = 1; $p <= 11; $p++)
                                        @php
                                            $val = $mappings[$co['id']]['PO' . $p] ?? '-';
                                            $cellClass = 'text-slate-400 font-normal';
                                            if ($val == '3') $cellClass = 'font-bold text-emerald-700 bg-emerald-50/60';
                                            elseif ($val == '2') $cellClass = 'font-bold text-blue-700 bg-blue-50/60';
                                            elseif ($val == '1') $cellClass = 'font-semibold text-slate-700 bg-slate-50';
                                        @endphp
                                        <td class="p-2.5 font-mono {{ $cellClass }}">
                                            {{ $val }}
                                        </td>
                                    @endfor
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13" class="text-center py-6 text-slate-500 text-sm italic">No outcomes extracted yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Subtab 2: Combined 90-Hour Practicum Lesson Planner (Interactive Table & Print) -->
            <div id="theory-subcontent-planner" class="space-y-5 hidden">
                @php
                    $theoryPlans = $lessonPlans->whereIn('mode', ['L', 'ST'])->take(45);
                    $theoryPlannedHours = 45;
                    $theoryCompletedCount = $theoryPlans->filter(function($p) { return !empty($p->actual_date); })->count();
                    $theoryRemainingHours = max(0, 45 - $theoryCompletedCount);
                    $theoryCoveragePct = round(($theoryCompletedCount / 45) * 100);
                    $theoryCoList = $theoryPlans->pluck('co_id')->filter()->unique()->values();
                @endphp

                <!-- 1. THEORY METRIC SUMMARY BAR (4-CARD GRID) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Planned Hours -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-indigo-300">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            <span class="text-xs font-bold text-slate-400 font-mono uppercase tracking-wider">Theory Target</span>
                        </div>
                        <div class="mt-3">
                            <div class="text-2xl font-black text-slate-900 font-heading tracking-tight" id="theoryMetricPlanned">45 <span class="text-xs font-bold text-slate-500">Hrs</span></div>
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Planned Hours</div>
                            <div class="text-xs text-slate-500 font-medium mt-1">45 scheduled lecture periods</div>
                        </div>
                    </div>

                    <!-- Completed Hours -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-emerald-300">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60 font-mono">Conducted</span>
                        </div>
                        <div class="mt-3">
                            <div class="text-2xl font-black text-emerald-700 font-heading tracking-tight" id="theoryMetricCompleted">{{ $theoryCompletedCount }} <span class="text-xs font-bold text-emerald-600/70">Hrs</span></div>
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Completed Hours</div>
                            <div class="text-xs text-slate-500 font-medium mt-1" id="theoryMetricCompletedSub">{{ $theoryCompletedCount }} sessions conducted</div>
                        </div>
                    </div>

                    <!-- Remaining Hours -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-amber-300">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200/60 font-mono">Pending</span>
                        </div>
                        <div class="mt-3">
                            <div class="text-2xl font-black text-slate-900 font-heading tracking-tight" id="theoryMetricRemaining">{{ $theoryRemainingHours }} <span class="text-xs font-bold text-slate-500">Hrs</span></div>
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Remaining Hours</div>
                            <div class="text-xs text-slate-500 font-medium mt-1" id="theoryMetricRemainingSub">{{ $theoryRemainingHours }} sessions pending</div>
                        </div>
                    </div>

                    <!-- Syllabus Coverage -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-blue-300">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </span>
                            <span class="text-xs font-bold text-blue-700 font-mono" id="theoryMetricCoverageBadge">{{ $theoryCoveragePct }}%</span>
                        </div>
                        <div class="mt-3">
                            <div class="text-2xl font-black text-blue-700 font-heading tracking-tight" id="theoryMetricCoverage">{{ $theoryCoveragePct }}%</div>
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Theory Coverage</div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden mt-2">
                                <div id="theoryMetricProgressBar" class="bg-blue-600 h-full rounded-full transition-all duration-500" style="width: {{ $theoryCoveragePct }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. THEORY PLANNER MAIN WORKSPACE CARD -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                    <!-- Action Toolbar Header -->
                    <div class="p-5 sm:px-6 border-b border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 font-mono">THEORY DELIVERY PLAN</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mt-1">Theory Lesson Planner</h3>
                            <p class="text-slate-500 text-xs mt-0.5">Plan, schedule and track the 45-hour theory component (41 Lecture Hours + 4 Series Tests).</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-lesson-plan" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition-all flex items-center gap-1.5 shadow-2xs no-underline cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                <span>Print Lesson Plan</span>
                            </a>

                            <button type="button" id="btnSaveTheoryPlanner" onclick="saveAllLessonPlans()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 shadow-xs cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Save All 90 Hours</span>
                            </button>
                        </div>
                    </div>

                    <!-- Interactive Filter / Search Bar -->
                    <div class="border-b border-slate-100 bg-slate-50/60 p-3 sm:px-6 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <!-- Search Box -->
                            <div class="relative min-w-[200px] sm:w-64">
                                <input type="text" id="theoryPlannerSearch" oninput="filterTheoryPlannerRows()" placeholder="Search topics, COs or sessions..." class="w-full pl-8 pr-3 py-1.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-indigo-500 shadow-2xs">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </span>
                            </div>

                            <!-- CO Filter -->
                            <select id="theoryPlannerCOFilter" onchange="filterTheoryPlannerRows()" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-indigo-500 shadow-2xs cursor-pointer">
                                <option value="ALL">All Outcomes (CO)</option>
                                @foreach($theoryCoList as $co)
                                    <option value="{{ $co }}">{{ $co }}</option>
                                @endforeach
                            </select>

                            <!-- Status Filter -->
                            <select id="theoryPlannerStatusFilter" onchange="filterTheoryPlannerRows()" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-indigo-500 shadow-2xs cursor-pointer">
                                <option value="ALL">All Statuses</option>
                                <option value="Pending">Pending Only</option>
                                <option value="Completed">Completed Only</option>
                            </select>
                        </div>

                        <!-- Counter -->
                        <span id="theoryPlannerCount" class="text-xs font-bold text-slate-500 ml-auto">Showing {{ $theoryPlans->count() }} of {{ $theoryPlans->count() }} sessions</span>
                    </div>

                    <!-- Table Container -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[960px] lp-table">
                            <thead>
                                <tr class="bg-slate-50/90 text-xs font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200 sticky top-0 z-10">
                                    <th class="p-3.5 w-16 text-center"># / Session</th>
                                    <th class="p-3.5 w-36">Pedagogy</th>
                                    <th class="p-3.5 w-36">Proposed Date</th>
                                    <th class="p-3.5 w-36">Actual Date</th>
                                    <th class="p-3.5 min-w-[280px]">Topic & Content Description</th>
                                    <th class="p-3.5 w-20 text-center">CO</th>
                                    <th class="p-3.5 w-32">Students</th>
                                    <th class="p-3.5 w-24 text-center">Hours</th>
                                    <th class="p-3.5 w-24 text-center">Status</th>
                                    <th class="p-3.5 w-36">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm font-normal">
                                @forelse($theoryPlans as $plan)
                                @php
                                    $isCompleted = !empty($plan->actual_date);
                                    $co = $plan->co_id ?: 'CO1';
                                    $coBadgeClass = match($co) {
                                        'CO1' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'CO2' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'CO3' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'CO4' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'CO5' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'CO6' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <tr id="lp-row-{{ $plan->id }}" data-plan-id="{{ $plan->id }}" data-co="{{ $plan->co_id }}" data-status="{{ $isCompleted ? 'Completed' : 'Pending' }}" class="theory-planner-row hover:bg-slate-50/70 transition-colors {{ $isCompleted ? 'bg-emerald-50/15' : '' }}">
                                    <!-- Session # -->
                                    <td class="p-3 font-mono font-bold text-center text-slate-900 text-sm">
                                        <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 inline-flex items-center justify-center font-bold text-xs border border-slate-200/80">#{{ str_pad($plan->day_no, 2, '0', STR_PAD_LEFT) }}</span>
                                    </td>

                                    <!-- Pedagogy -->
                                    <td class="p-2.5">
                                        <select id="lp-pedagogy-{{ $plan->id }}" onchange="onPedagogyChange({{ $plan->id }}, this.value); updateTheoryMetrics(); filterTheoryPlannerRows();" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-sm font-medium transition-all outline-none cursor-pointer {{ $plan->mode === 'L' ? 'text-blue-700' : ($plan->mode === 'P' ? 'text-emerald-700' : 'text-purple-700') }}">
                                            <option value="Lecture (L)" {{ ($plan->pedagogy ?? 'Lecture (L)') === 'Lecture (L)' || ($plan->mode === 'L' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Lecture (L)</option>
                                            <option value="Practical Lab (P)" {{ ($plan->pedagogy ?? '') === 'Practical Lab (P)' || ($plan->mode === 'P' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Practical Lab (P)</option>
                                            <option value="Theory Series Exam (ST)" {{ ($plan->pedagogy ?? '') === 'Theory Series Exam (ST)' || ($plan->mode === 'ST' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Theory Series Exam (ST)</option>
                                            <option value="Practical Series Exam (SP)" {{ ($plan->pedagogy ?? '') === 'Practical Series Exam (SP)' || ($plan->mode === 'SP' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Practical Series Exam (SP)</option>
                                            <option value="PPT Presentation" {{ ($plan->pedagogy ?? '') === 'PPT Presentation' ? 'selected' : '' }}>PPT Presentation</option>
                                            <option value="Demonstration" {{ ($plan->pedagogy ?? '') === 'Demonstration' ? 'selected' : '' }}>Demonstration</option>
                                            <option value="Group Activity" {{ ($plan->pedagogy ?? '') === 'Group Activity' ? 'selected' : '' }}>Group Activity</option>
                                        </select>
                                    </td>

                                    <!-- Proposed Date -->
                                    <td class="p-2.5">
                                        <input type="date" id="lp-prop-{{ $plan->id }}" value="{{ $plan->proposed_date }}" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-slate-800 text-sm font-mono transition-all outline-none">
                                    </td>

                                    <!-- Actual Date -->
                                    <td class="p-2.5">
                                        <input type="date" id="lp-act-{{ $plan->id }}" value="{{ $plan->actual_date }}" onchange="onTheoryActualDateChange({{ $plan->id }}, this.value)" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-slate-800 text-sm font-mono transition-all outline-none">
                                    </td>

                                    <!-- Topic & Content -->
                                    <td class="p-2.5">
                                        <textarea id="lp-topic-{{ $plan->id }}" rows="2" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-3 py-2 text-slate-900 text-sm font-normal transition-all outline-none resize-y leading-snug">{{ $plan->topic_content }}</textarea>
                                    </td>

                                    <!-- CO -->
                                    <td class="p-2.5 text-center">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold border shadow-2xs {{ $coBadgeClass }}">
                                            {{ $plan->co_id ?: 'CO1' }}
                                        </span>
                                        <input type="hidden" id="lp-co-{{ $plan->id }}" value="{{ $plan->co_id ?: 'CO1' }}">
                                    </td>

                                    <!-- Students / Batch -->
                                    <td id="lp-batch-td-{{ $plan->id }}" class="p-2.5">
                                        @if(in_array($plan->mode, ['P', 'SP']) || (isset($plan->pedagogy) && (stripos($plan->pedagogy, 'Practical') !== false || stripos($plan->pedagogy, 'Lab') !== false)))
                                            <select id="lp-batch-{{ $plan->id }}" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 rounded-xl px-2.5 py-2 font-bold text-xs text-emerald-700 outline-none cursor-pointer">
                                                <option value="Batch A & B" {{ ($plan->sub_batch ?? 'Batch A & B') === 'Batch A & B' ? 'selected' : '' }}>Batch A & B (Combined)</option>
                                                <option value="Batch A" {{ ($plan->sub_batch ?? '') === 'Batch A' ? 'selected' : '' }}>Batch A</option>
                                                <option value="Batch B" {{ ($plan->sub_batch ?? '') === 'Batch B' ? 'selected' : '' }}>Batch B</option>
                                            </select>
                                        @else
                                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-bold text-xs border border-slate-200/80 inline-block">
                                                All Students
                                            </span>
                                            <input type="hidden" id="lp-batch-{{ $plan->id }}" value="All Students">
                                        @endif
                                    </td>

                                    <!-- Hours -->
                                    <td id="lp-hours-td-{{ $plan->id }}" class="p-2.5 text-center font-normal">
                                        @if(in_array($plan->mode, ['P', 'SP']) || (isset($plan->pedagogy) && (stripos($plan->pedagogy, 'Practical') !== false || stripos($plan->pedagogy, 'Lab') !== false)))
                                            <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-xs font-bold">3 Hours</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200/80 text-xs font-bold">1 Hour</span>
                                        @endif
                                    </td>

                                    <!-- Status Indicator -->
                                    <td id="lp-status-td-{{ $plan->id }}" class="p-2.5 text-center">
                                        <span id="lp-status-pill-{{ $plan->id }}" class="px-2.5 py-1 rounded-full text-xs font-bold border shadow-2xs {{ $isCompleted ? 'bg-emerald-50 text-emerald-700 border-emerald-200/80' : 'bg-amber-50 text-amber-700 border-amber-200/80' }}">
                                            {{ $isCompleted ? 'Completed' : 'Pending' }}
                                        </span>
                                    </td>

                                    <!-- Remarks -->
                                    <td class="p-2.5">
                                        <input type="text" id="lp-remarks-{{ $plan->id }}" value="{{ $plan->remarks }}" placeholder="Status/Remarks..." class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-3 py-2 text-slate-800 text-sm font-normal transition-all outline-none">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="p-8 text-center text-slate-400 font-normal">No theory lecture hours scheduled yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Subtab 3: Self-Learning Activities (CA - 5 CIA Marks) -->
            <div id="theory-subcontent-sl" class="space-y-5 hidden">
                <!-- 1. Header & Quick Actions Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 font-mono">CONTINUOUS ASSESSMENT (CA1)</span>
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100 font-mono">5 CIA Marks</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mt-1">Self-Learning Evaluation & Customization</h3>
                            <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">
                                Mandatory Core Activities: <span class="font-bold text-amber-700">Assignment</span> & <span class="font-bold text-emerald-700">MCQ</span> (Evaluated out of 15 Marks). Optional custom catalog per CO: Case Study, Quiz, Microproject, Mini Project, Report, Exercises, Presentation.
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <button type="button" onclick="openSlConfigModal()" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition-all flex items-center gap-1.5 shadow-2xs cursor-pointer">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Customize Activities</span>
                            </button>

                            <button type="button" onclick="openSlMarksModal()" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs flex items-center gap-1.5 transition-all cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Enter CA Marks</span>
                            </button>

                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-self-learning-splitup" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition-all flex items-center gap-1.5 shadow-2xs no-underline cursor-pointer">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                <span>Splitup Report</span>
                            </a>

                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-self-learning-summary" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition-all flex items-center gap-1.5 shadow-2xs no-underline cursor-pointer">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Summary Report</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. Student Evaluation Table Workspace Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                    <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Enrolled Students Continuous Assessment Register</span>
                        </div>
                        <span class="text-xs font-bold text-slate-500 font-mono">{{ count($studentResults) }} Students</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[760px]">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-700 font-bold text-xs uppercase tracking-wider">
                                    <th class="p-3.5 pl-4 text-center w-16">Roll</th>
                                    <th class="p-3.5 w-40">SBTE Reg No</th>
                                    <th class="p-3.5">Student Name</th>
                                    <th class="p-3.5 w-48">Core Activities</th>
                                    <th class="p-3.5 text-center w-36">Raw Score (/15M)</th>
                                    <th class="p-3.5 text-center w-36">Converted CIA (5M)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm font-normal text-slate-700">
                                @forelse($studentResults as $res)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-3 pl-4 text-center font-mono font-bold text-slate-900">{{ $res['roll_no'] ?: '—' }}</td>
                                    <td class="p-3 font-mono font-bold text-indigo-700 text-xs">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-3 text-slate-900 font-medium">{{ $res['name'] }}</td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-1.5">
                                            <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200/80 font-bold text-xs font-mono">Assignment</span>
                                            <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200/80 font-bold text-xs font-mono">MCQ</span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-center font-mono font-bold text-slate-800">{{ number_format(($res['sl_marks'] / 5.0) * 15.0, 2) }} / 15.00</td>
                                    <td class="p-3 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono border shadow-2xs bg-emerald-50 text-emerald-700 border-emerald-200/80">
                                            {{ number_format($res['sl_marks'], 2) }} / 5.00
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-400 font-normal">No student assessment records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Subtab 4: Theory Series Examinations -->
            <div id="theory-subcontent-series" class="space-y-4 hidden">

                <!-- QP Generator Panel — 4 Cards -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm no-print">
                    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h3 class="text-base font-bold text-white flex items-center gap-2 flex-wrap">
                                <span>📄 Series Exam QP Generator</span>
                                <span class="px-2.5 py-0.5 rounded-lg bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-semibold">
                                    {{ $subjectType['label'] ?? '💻 Program Core - ESE 100M' }}
                                </span>
                            </h3>
                            <p class="text-slate-400 text-xs mt-1">
                                @if(($subjectType['pattern'] ?? '') === 'table_4_2_design')
                                    Table 4.2 Design Paper: Part A (6×5=30M) + Part B (2×10=20M) = 50 Marks | 2 Hours
                                @else
                                    Single CO Test: Part A (2×1=2M) + Part B (3×3=9M) + Part C (answer any 2 of 3 × 7=14M) = 25 Marks | 1½ Hours
                                @endif
                                | Scaled to 10 CIA Marks
                            </p>
                        </div>
                    </div>

                    <!-- 4 Series Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(['Series 1' => 'CO1', 'Series 2' => 'CO2', 'Series 3' => 'CO3', 'Series 4' => 'CO4'] as $series => $co)
                        @php $savedQp = $seriesQps[$series] ?? null; @endphp
                        <div class="rounded-xl border {{ $savedQp ? 'border-emerald-600/50 bg-emerald-50/60' : 'border-slate-200 bg-slate-50' }} p-3 flex flex-col gap-2">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 text-sm">{{ $series }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $savedQp ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">{{ $co }}</span>
                            </div>

                            <!-- Status -->
                            @if($savedQp)
                            <div class="text-xs text-emerald-600 font-semibold">✅ QP Saved</div>
                            @else
                            <div class="text-xs text-slate-500">⬜ Not generated</div>
                            @endif

                            <!-- Generate buttons -->
                            <div class="flex flex-col gap-1.5 mt-1">
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $co }}', 'ai')"
                                    class="w-full py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-800 transition-all text-center">
                                    ⚡ AI Generate
                                </button>
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $co }}', 'manual')"
                                    class="w-full py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 transition-all text-center">
                                    ✏ Manual Entry
                                </button>
                            </div>

                            <!-- Print buttons (only if saved) -->
                            @if($savedQp)
                            <div class="border-t border-slate-200/50 pt-2 flex flex-col gap-1.5">
                                <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-qp/{{ rawurlencode($series) }}" target="_blank"
                                    class="w-full py-1.5 rounded-lg text-xs font-semibold bg-indigo-950/40 hover:bg-indigo-900/60 border border-indigo-500/30 text-indigo-300 text-center block">
                                    🖨️ Print QP
                                </a>
                                <div class="grid grid-cols-2 gap-1.5">
                                    <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-scheme/{{ rawurlencode($series) }}" target="_blank"
                                        class="py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-center block">
                                        📋 Scheme
                                    </a>
                                    <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-key/{{ rawurlencode($series) }}" target="_blank"
                                        class="py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-center block">
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
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                    <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                        <div>
                            <h3 class="text-base font-semibold text-slate-800">Theory Series Examinations</h3>
                            <p class="text-slate-400 text-xs mt-0.5">4 Series Tests (CO1, CO2, CO3, CO4 - 2 Hours each out of 50 marks), averaged and scaled to 10 CIA marks</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="printSubtabReport('Theory Series Examinations Report', 'theory-subcontent-series')" class="header-btn px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200 font-semibold text-xs transition-all no-print">🖨️ Print Report</button>
                            <button onclick="openSeriesTheoryModal()" class="header-btn px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs shadow-sm">Enter Theory Series Marks</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-700 font-bold bg-slate-50 text-sm border-b border-slate-200">
                                    <th class="p-3">Roll</th>
                                    <th class="p-3">SBTE Reg No</th>
                                    <th class="p-3">Student Name</th>
                                    <th class="p-3 text-center">Test 1 (CO1)</th>
                                    <th class="p-3 text-center">Test 2 (CO2)</th>
                                    <th class="p-3 text-center">Test 3 (CO3)</th>
                                    <th class="p-3 text-center">Test 4 (CO4)</th>
                                    <th class="p-3 text-center">Avg (/50)</th>
                                    <th class="p-3 text-center">Converted CIA (/10M)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-sm">
                                @foreach($studentResults as $res)
                                @php
                                    $stEvals = $seriesTheoryEvals->get($res['reg_no'], collect());
                                    $s1 = $stEvals->whereIn('series_no', ['Series 1', 'CO1'])->first();
                                    $s2 = $stEvals->whereIn('series_no', ['Series 2', 'CO2'])->first();
                                    $s3 = $stEvals->whereIn('series_no', ['Series 3', 'CO3'])->first();
                                    $s4 = $stEvals->whereIn('series_no', ['Series 4', 'CO4'])->first();
                                @endphp
                                <tr>
                                    <td class="p-3 text-slate-700 font-normal">{{ $res['roll_no'] }}</td>
                                    <td class="p-3 font-mono text-slate-700 font-bold">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-3 text-slate-700 font-normal">{{ $res['name'] }}</td>
                                    <td class="p-3 text-center text-slate-700 font-normal">{{ $s1 ? number_format($s1->total_score_50, 2) : '-' }}</td>
                                    <td class="p-3 text-center text-slate-700 font-normal">{{ $s2 ? number_format($s2->total_score_50, 2) : '-' }}</td>
                                    <td class="p-3 text-center text-slate-700 font-normal">{{ $s3 ? number_format($s3->total_score_50, 2) : '-' }}</td>
                                    <td class="p-3 text-center text-slate-700 font-normal">{{ $s4 ? number_format($s4->total_score_50, 2) : '-' }}</td>
                                    <td class="p-3 text-center text-slate-700 font-normal">{{ number_format($res['series_theory_marks'] * 5, 2) }}</td>
                                    <td class="p-3 text-center text-slate-400 font-normal">{{ number_format($res['series_theory_marks'], 2) }} / 10.00</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- /inner bg-white border border-slate-200/80 rounded-2xl shadow-sm (marks table) -->
            </div>
            <!-- /outer space-y-4 (theory-subcontent-series) -->

            <!-- Subtab 5: Theory ESE & Consolidated Results -->
            <div id="theory-subcontent-ese" class="space-y-5 hidden">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                    <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-white">Written Theory End Semester Exam (60 Marks)</h3>
                            <p class="text-slate-400 text-xs mt-0.5">Board Theory ESE Grades evaluated per Official Revision 2026 Grading System Standard</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="printSubtabReport('Theory ESE & Overall Results Report', 'theory-subcontent-ese')" class="header-btn px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200 font-semibold text-xs transition-all no-print">🖨️ Print Report</button>
                            <button onclick="openEseTheoryModal()" class="header-btn px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs shadow-sm">Enter Theory ESE Grades</button>
                        </div>
                    </div>

                    <!-- Official R2026 Grade Scale Legend Box -->
                    <div class="p-3 mb-4 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                        <div class="font-bold text-slate-700 mb-2 uppercase tracking-wide">Revision 2026 Official Grading System Standard (Theory ESE)</div>
                        <div class="grid grid-cols-7 gap-1 text-center font-mono">
                            <div class="p-1.5 rounded bg-emerald-500/10 border border-emerald-500/30 text-emerald-600">
                                <div class="font-bold text-sm">S</div>
                                <div class="text-[10px] opacity-80">≥90%</div>
                                <div class="text-[10px] text-slate-400">GP: 10</div>
                            </div>
                            <div class="p-1.5 rounded bg-blue-50 text-blue-700 border border-blue-200">
                                <div class="font-bold text-sm">A</div>
                                <div class="text-[10px] opacity-80">80–89%</div>
                                <div class="text-[10px] text-slate-400">GP: 9</div>
                            </div>
                            <div class="p-1.5 rounded bg-indigo-500/10 border border-indigo-500/30 text-indigo-600">
                                <div class="font-bold text-sm">B</div>
                                <div class="text-[10px] opacity-80">70–79%</div>
                                <div class="text-[10px] text-slate-400">GP: 8</div>
                            </div>
                            <div class="p-1.5 rounded bg-purple-500/10 border border-purple-500/30 text-violet-600">
                                <div class="font-bold text-sm">C</div>
                                <div class="text-[10px] opacity-80">60–69%</div>
                                <div class="text-[10px] text-slate-400">GP: 7</div>
                            </div>
                            <div class="p-1.5 rounded bg-amber-500/10 border border-amber-500/30 text-amber-600">
                                <div class="font-bold text-sm">D</div>
                                <div class="text-[10px] opacity-80">50–59%</div>
                                <div class="text-[10px] text-slate-400">GP: 6</div>
                            </div>
                            <div class="p-1.5 rounded bg-orange-500/10 border border-orange-500/30 text-orange-400">
                                <div class="font-bold text-sm">E</div>
                                <div class="text-[10px] opacity-80">40–49%</div>
                                <div class="text-[10px] text-slate-400">GP: 5</div>
                            </div>
                            <div class="p-1.5 rounded bg-rose-500/10 border border-rose-500/30 text-rose-600">
                                <div class="font-bold text-sm">F</div>
                                <div class="text-[10px] opacity-80">&lt;40%</div>
                                <div class="text-[10px] text-slate-400">GP: 0</div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs table-compact-header">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-700 font-bold bg-slate-50 border-b border-slate-200">
                                    <th class="p-2.5 w-12 text-center">Roll</th>
                                    <th class="p-2.5">SBTE Reg No</th>
                                    <th class="p-2.5">Student Name</th>
                                    <th class="p-2.5 text-center">Board Theory ESE Grade</th>
                                    <th class="p-2.5 text-center">Pass / Fail Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-xs">
                                @foreach($studentResults as $res)
                                @php
                                    $grade = strtoupper($res['ese_theory_grade'] ?? '-');
                                    if ($grade === 'S') { $gc = 'text-emerald-600 bg-emerald-500/10 border-emerald-500/30'; }
                                    elseif ($grade === 'A') { $gc = 'text-blue-700 bg-blue-50 border-blue-200'; }
                                    elseif ($grade === 'B') { $gc = 'text-indigo-600 bg-indigo-500/10 border-indigo-500/30'; }
                                    elseif ($grade === 'C') { $gc = 'text-violet-600 bg-purple-500/10 border-purple-500/30'; }
                                    elseif ($grade === 'D') { $gc = 'text-amber-600 bg-amber-500/10 border-amber-500/30'; }
                                    elseif ($grade === 'E') { $gc = 'text-orange-400 bg-orange-500/10 border-orange-500/30'; }
                                    elseif (in_array($grade, ['F', 'FE', 'ABSENT', 'ABS'])) { $gc = 'text-rose-600 bg-rose-500/10 border-rose-500/30'; }
                                    else { $gc = 'text-slate-400 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 border-slate-200'; }

                                    $isFail = in_array($grade, ['F', 'FE', 'ABSENT', 'ABS']);
                                @endphp
                                <tr class="hover:bg-slate-50 transition-all">
                                    <td class="p-2.5 text-center text-slate-700">{{ $res['roll_no'] }}</td>
                                    <td class="p-2.5 font-mono text-slate-700 font-bold">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-2.5 font-bold text-white">{{ $res['name'] }}</td>
                                    <td class="p-2.5 text-center font-bold">
                                        <span class="px-3 py-0.5 rounded-full border text-xs font-bold {{ $gc }}">{{ $grade !== '-' ? $grade : 'Not Entered' }}</span>
                                    </td>
                                    <td class="p-2.5 text-center font-semibold {{ !$isFail && $grade !== '-' ? 'text-emerald-600' : ($isFail ? 'text-rose-600' : 'text-slate-400') }}">
                                        {{ $grade === '-' ? '-' : (!$isFail ? 'PASSED' : 'REAPPEAR / FAIL') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Consolidated Results -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                    <h3 class="text-lg font-bold text-white">🏆 NBA Attainment Summary (Direct 80% + Indirect 20%)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-normal text-slate-800 text-sm">{{ $coTag }}</span>
                                <span class="px-2 py-0.5 rounded bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-600 text-xs font-normal border border-slate-200">Level {{ $combinedStats[$coTag] ?? 0.0 }} / 3.0</span>
                            </div>
                            <div class="text-slate-600 text-xs space-y-1">
                                <div>Direct Attainment: <span class="font-normal text-slate-700">{{ $directStats[$coTag]['level'] ?? 0 }}</span> ({{ $directStats[$coTag]['percentage'] ?? 0 }}% Students)</div>
                                <div>Indirect Attainment: <span class="font-normal text-slate-700">{{ $indirectStats[$coTag]['level'] ?? 0 }}</span></div>
                                <div>Overall (80:20): <span class="font-normal text-slate-800">{{ $combinedStats[$coTag] ?? 0 }}</span></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- PO Attainment Row -->
                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <h4 class="font-normal text-slate-700 text-sm mb-3">Calculated Program Outcome (PO) Attainment Scores</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-11 gap-2 text-center">
                            @for($p = 1; $p <= 11; $p++)
                            @php $po = "PO" . $p; @endphp
                            <div class="p-2.5 rounded-lg bg-white border border-slate-200">
                                <div class="text-xs text-slate-400 font-normal">{{ $po }}</div>
                                <div class="font-normal text-slate-800 text-sm mt-0.5">{{ $poAttainments[$po]['value'] ?? 0.0 }}</div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- CIA Summary Card -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                    <h3 class="text-lg font-bold text-white mb-1">Consolidated Continuous Internal Assessment (CIA - 40 Marks Table 1.4)</h3>
                    <p class="text-slate-400 text-xs mb-3">Attendance (5M) + CA1 Self Learning (5M) + CE Continuous Lab (10M) + CA2/CA3 Practical Tests (10M) + CA4/CA5 Theory Tests (10M) = 40 CIA Marks</p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-700 font-bold bg-slate-50 text-sm border-b border-slate-200">
                                    <th class="p-2.5">Roll</th>
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
                            <tbody class="divide-y divide-slate-800/60 text-sm">
                                @foreach($studentResults as $res)
                                <tr class="hover:bg-slate-50 transition-all">
                                    <td class="p-2.5 text-slate-700 font-normal">{{ $res['roll_no'] }}</td>
                                    <td class="p-2.5 font-mono text-slate-700 font-bold text-xs">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-2.5 text-slate-700 font-normal">{{ $res['name'] }}</td>
                                    <td class="p-2.5 text-center text-slate-700 font-normal">{{ $res['att_marks'] }}</td>
                                    <td class="p-2.5 text-center text-slate-700 font-normal">{{ number_format($res['sl_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center text-slate-700 font-normal">{{ number_format($res['continuous_eval_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center text-slate-700 font-normal">{{ number_format($res['series_theory_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center text-slate-700 font-normal">{{ number_format($res['series_practical_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center text-slate-800 font-normal">
                                        {{ number_format($res['total_cia_marks'], 2) }} / 40.00
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Subtab 6: Online Surveys & Indirect Attainment -->
            <div id="theory-subcontent-surveys" class="space-y-5 hidden">
                
                <!-- Top Header Card -->
                <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-bold text-white tracking-tight">
                            Online Feedback Surveys & Indirect CO-PO Attainment
                        </h3>
                        <p class="text-slate-400 text-[11px] leading-snug mt-1">
                            Manage Mid-Semester Online Surveys (SAR Criterion 2)<br>
                            End-Semester Course Exit Surveys for Indirect CO Attainment (20% Weightage).
                        </p>
                    </div>
                    <div class="flex items-center space-x-2 whitespace-nowrap flex-shrink-0">
                        <a href="/classroom/{{ $batchSubject->id }}/course-exit/report" target="_blank" class="px-3 py-1.5 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/35 text-indigo-300 border border-indigo-500/40 font-semibold text-xs transition-all flex items-center space-x-1.5 no-print whitespace-nowrap">
                            <span>🖨️ Course Exit Report</span>
                        </a>
                        <a href="/classroom/{{ $batchSubject->id }}/survey/report" target="_blank" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200 font-semibold text-xs transition-all flex items-center space-x-1.5 no-print whitespace-nowrap">
                            <span>🖨️ MidSem Report</span>
                        </a>
                    </div>
                </div>

                <!-- Dual Surveys Control Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <!-- Mid-Semester Survey Card -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                            <div class="flex items-center space-x-3">
                                <div class="p-2.5 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-600">
                                    <span class="material-symbols-rounded text-2xl">rate_review</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white text-base">Mid-Semester Online Survey</h4>
                                    <p class="text-xs text-slate-400">SAR Criterion 2 Evaluation</p>
                                </div>
                            </div>
                            <span id="midsem-practicum-status-badge" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-700 border border-slate-200">
                                Checking...
                            </span>
                        </div>

                        <p class="text-slate-700 text-xs leading-relaxed">
                            Captures early student feedback on syllabus delivery pace, concept clarity, ICT tools, classroom interaction, and evaluation fairness. Sends active task notification to student portal.
                        </p>

                        <div class="flex items-center space-x-2 pt-2 border-t border-slate-200/60 whitespace-nowrap">
                            <button id="btn-open-midsem-practicum" onclick="openMidsemInitModal()" class="px-3 py-1.5 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/35 text-emerald-300 border border-emerald-500/40 font-semibold text-xs transition-all shadow-sm flex items-center space-x-1.5 whitespace-nowrap">
                                <span>Initiate / Open Survey</span>
                            </button>
                            <button id="btn-close-midsem-practicum" onclick="controlPracticumSurvey('midsem', 'close')" class="px-3 py-1.5 rounded-lg bg-rose-600/20 hover:bg-rose-600/35 text-rose-300 border border-rose-500/40 font-semibold text-xs transition-all shadow-sm hidden whitespace-nowrap">
                                <span>Close & Lock Survey</span>
                            </button>
                            <a href="/classroom/{{ $batchSubject->id }}/survey/report" target="_blank" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200 font-semibold text-xs transition-all flex items-center space-x-1 whitespace-nowrap">
                                <span>Print Report</span>
                            </a>
                        </div>
                    </div>

                    <!-- Course Exit Survey Card -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                            <div class="flex items-center space-x-3">
                                <div class="p-2.5 rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400">
                                    <span class="material-symbols-rounded text-2xl">assignment_turned_in</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white text-base">Course Exit Survey</h4>
                                    <p class="text-xs text-slate-400">Indirect CO Attainment Assessment (20% Weightage)</p>
                                </div>
                            </div>
                            <span id="exit-practicum-status-badge" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-700 border border-slate-200">
                                Checking...
                            </span>
                        </div>

                        <p class="text-slate-700 text-xs leading-relaxed">
                            Evaluates student perception of Course Outcomes (CO1–CO4) at semester completion. Results automatically feed into Indirect CO Attainment (20% weightage).
                        </p>

                        <div class="flex items-center space-x-2 pt-2 border-t border-slate-200/60 whitespace-nowrap">
                            <button id="btn-open-exit-practicum" onclick="openExitInitModal()" class="px-3 py-1.5 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/35 text-emerald-300 border border-emerald-500/40 font-semibold text-xs transition-all shadow-sm flex items-center space-x-1.5 whitespace-nowrap">
                                <span>Initiate / Open Survey</span>
                            </button>
                            <button id="btn-close-exit-practicum" onclick="controlPracticumSurvey('exit', 'close')" class="px-3 py-1.5 rounded-lg bg-rose-600/20 hover:bg-rose-600/35 text-rose-300 border border-rose-500/40 font-semibold text-xs transition-all shadow-sm hidden whitespace-nowrap">
                                <span>Close & Lock Survey</span>
                            </button>
                            <a href="/classroom/{{ $batchSubject->id }}/course-exit/report" target="_blank" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200 font-semibold text-xs transition-all flex items-center space-x-1 whitespace-nowrap">
                                <span>Print Report</span>
                            </a>
                        </div>
                    </div>

                </div>

                <!-- Indirect CO Attainment Summary Grid -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                        <h4 class="font-bold text-white text-base flex items-center space-x-2">
                            <span class="material-symbols-rounded text-emerald-600">analytics</span>
                            <span>Calculated Indirect CO Attainment Scores (Scale 1–3 & High/Med/Low Rating)</span>
                        </h4>
                        <span class="text-xs text-slate-400">Computed from Course Exit Survey Responses</span>
                    </div>

                    <!-- NBA Scaling Standard Box -->
                    <div class="p-3 rounded-xl bg-white/90 border border-slate-200 text-xs text-slate-700 flex flex-wrap items-center gap-4">
                        <span class="font-bold text-indigo-600 uppercase tracking-wide">Attainment Scaling Standard:</span>
                        <span><strong class="text-emerald-600">Level 3 (High):</strong> &ge; 70%</span>
                        <span><strong class="text-amber-600">Level 2 (Medium):</strong> 60% – 69%</span>
                        <span><strong class="text-orange-400">Level 1 (Low):</strong> 50% – 59%</span>
                        <span><strong class="text-rose-600">Level 0 (Nil):</strong> &lt; 50%</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                        @php
                            $lvl = (int)($indirectStats[$coTag]['level'] ?? 3);
                            $rtg = $indirectStats[$coTag]['rating'] ?? ($lvl == 3 ? 'High' : ($lvl == 2 ? 'Medium' : ($lvl == 1 ? 'Low' : 'Nil')));
                        @endphp
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 text-sm">{{ $coTag }}</span>
                                <span class="px-2.5 py-0.5 rounded text-xs font-bold border {{ $lvl == 3 ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : ($lvl == 2 ? 'bg-amber-500/10 text-amber-600 border-amber-500/20' : ($lvl == 1 ? 'bg-orange-500/10 text-orange-400 border-orange-500/20' : 'bg-rose-500/10 text-rose-600 border-rose-500/20')) }}">
                                    Level {{ $lvl }} ({{ $rtg }})
                                </span>
                            </div>
                            <div class="text-slate-400 text-xs space-y-1">
                                <div>Survey Avg Rating: <span class="font-bold text-slate-800">{{ number_format($indirectStats[$coTag]['avg_score'] ?? 2.50, 2) }} / 3.0</span></div>
                                <div>Attainment Pct: <span class="font-bold text-emerald-600">{{ number_format($indirectStats[$coTag]['percentage'] ?? 83.3, 1) }}%</span></div>
                                <div class="text-[10px] text-slate-500 mt-1">Weightage in PO Calculation: 20%</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- PO Attainment Matrix Box (Direct 80% + Indirect 20%) -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                    <h4 class="font-bold text-white text-base flex items-center space-x-2">
                        <span class="material-symbols-rounded text-indigo-600">grid_on</span>
                        <span>Final Program Outcome (PO1–PO11) Attainment Scores</span>
                    </h4>
                    <p class="text-slate-400 text-xs">Overall PO Attainment = 80% Direct Attainment (Series/Lab/ESE) + 20% Indirect Attainment (Exit Survey)</p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-11 gap-2 text-center">
                        @for($p = 1; $p <= 11; $p++)
                        @php $po = "PO" . $p; @endphp
                        <div class="p-2.5 rounded-lg bg-white border border-slate-200">
                            <div class="text-xs text-slate-400 font-semibold">{{ $po }}</div>
                            <div class="font-bold text-emerald-600 text-sm mt-0.5">{{ $poAttainments[$po]['value'] ?? 0.0 }}</div>
                        </div>
                        @endfor
                    </div>
                </div>

            </div>

            <!-- Subtab 7: Attendance Reports -->
            <div id="theory-subcontent-attendance" class="space-y-5 hidden">
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 rounded-xl border border-slate-200 space-y-4">
                    <div>
                        <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                            <span>📅 Course Attendance Reports</span>
                        </h3>
                        <p class="text-slate-400 text-xs mt-1">Select and print the detailed session attendance register or the consolidated final attendance report.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Card 1: Detailed Register -->
                        <div class="p-5 rounded-2xl bg-slate-50 border border-cyan-500/20 hover:border-cyan-500/50 shadow-lg transition-all duration-300 group flex flex-col justify-between space-y-4">
                            <div class="flex justify-between items-start">
                                <div class="space-y-1">
                                    <h4 class="text-base font-bold text-slate-100 group-hover:text-cyan-600 transition-all">Detailed Attendance Register</h4>
                                    <p class="text-slate-400 text-xs leading-relaxed">View and print the complete, session-by-session student attendance grid with specific dates, hourly remarks, percentage logs, and marks calculation.</p>
                                </div>
                                <span class="material-symbols-rounded text-cyan-600 bg-cyan-500/10 p-3 rounded-xl text-2xl flex-shrink-0">view_list</span>
                            </div>
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-report" target="_blank" class="w-full text-center px-4 py-2.5 rounded-xl font-bold text-xs bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-cyan-300 hover:text-white border border-cyan-500/40 hover:border-cyan-400 transition-all shadow-md no-underline block">
                                Open Detailed Register
                            </a>
                        </div>

                        <!-- Card 2: Consolidated Report -->
                        <div class="p-5 rounded-2xl bg-slate-50 border border-emerald-500/20 hover:border-emerald-500/50 shadow-lg transition-all duration-300 group flex flex-col justify-between space-y-4">
                            <div class="flex justify-between items-start">
                                <div class="space-y-1">
                                    <h4 class="text-base font-bold text-slate-100 group-hover:text-emerald-600 transition-all">Consolidated Attendance Report</h4>
                                    <p class="text-slate-400 text-xs leading-relaxed">View and print the consolidated A4 report showing the total theory conducted/present, practical conducted/present, and the final average attendance percentage for CIA preparation.</p>
                                </div>
                                <span class="material-symbols-rounded text-emerald-600 bg-emerald-500/10 p-3 rounded-xl text-2xl flex-shrink-0">analytics</span>
                            </div>
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-consolidated" target="_blank" class="w-full text-center px-4 py-2.5 rounded-xl font-bold text-xs bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-emerald-300 hover:text-white border border-emerald-500/40 hover:border-emerald-400 transition-all shadow-md no-underline block">
                                Open Consolidated Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- MODE B: VIRTUAL LAB (PRACTICUM)                                           -->
        <!-- ========================================================================= -->
        <div id="mode-lab-container" class="space-y-5 hidden">
            
            <!-- Lab Sub-Tabs Navigation -->
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm p-1.5 rounded-xl flex items-center space-x-1.5 overflow-x-auto">
                <button onclick="switchLabSubtab('roster')" id="lab-tab-roster" class="subtab-btn active px-2.5 py-1.5 rounded-lg font-semibold whitespace-nowrap">🧪 Lab Sessions</button>
                <button onclick="switchLabSubtab('planner')" id="lab-tab-planner" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-700 hover:text-white whitespace-nowrap">📅 Lab Planner</button>
                <button onclick="switchLabSubtab('eval')" id="lab-tab-eval" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-700 hover:text-white whitespace-nowrap">🔬 Lab Eval</button>
                <button onclick="switchLabSubtab('series')" id="lab-tab-series" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-700 hover:text-white whitespace-nowrap">📝 Lab Series</button>
                <button onclick="switchLabSubtab('ese')" id="lab-tab-ese" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-700 hover:text-white whitespace-nowrap">🏆 Lab ESE</button>
            </div>

            <div id="theory-subcontent-materials" class="hidden">
                @include('partials.virtual_learning_hub_tab', ['roomType' => 'Practicum'])
            </div>

            <!-- Subtab 1: 3-Hour Session Experiments Roster -->
            <div id="lab-subcontent-roster" class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold border border-emerald-200/80">
                            <span class="material-symbols-rounded text-base">science</span>
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Practical Experiments Roster (3-Hour Session Blocks)</h3>
                            <p class="text-slate-500 text-xs mt-0.5">All practical topics structured into 3-hour lab sessions as per Revision 2026 guidelines.</p>
                        </div>
                    </div>
                    <button onclick="printSubtabReport('Practical Experiments Roster Report', 'lab-subcontent-roster')" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition-all no-print flex items-center gap-1.5 shadow-2xs cursor-pointer">
                        <span class="material-symbols-rounded text-sm">print</span>
                        <span>Print Report</span>
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-slate-700 font-bold text-xs uppercase">
                                <th class="p-3 pl-4 w-32">Session Code</th>
                                <th class="p-3">Experiment Title</th>
                                <th class="p-3 w-32">Mapped CO</th>
                                <th class="p-3 w-44">Duration</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse(($practicumCourseFile->parsed_experiments ?? []) as $exp)
                            <tr class="hover:bg-slate-50/80 transition-all">
                                <td class="p-3 pl-4 font-bold font-mono text-emerald-700">
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 border border-emerald-200 text-xs">{{ $exp['experiment_no'] }}</span>
                                </td>
                                <td class="p-3 text-slate-900 font-medium leading-snug">{{ $exp['title'] }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 font-semibold border border-purple-200 text-xs font-mono">{{ $exp['co_id'] }}</span>
                                </td>
                                <td class="p-3 text-slate-600 font-medium text-xs">
                                    <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-700 rounded-md font-semibold">{{ $exp['hours'] ?? 3 }} Hours (1 Session)</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-slate-500 text-sm italic">No lab experiments extracted yet.</td>
                            </tr>
                            @endforelse
                <!-- Subtab 2: Lab Planner View -->
            <div id="lab-subcontent-planner" class="space-y-5 hidden">
                @php
                    $labPlans = $lessonPlans->whereIn('mode', ['P', 'SP'])->values()->take(45);
                    $labSessions = $labPlans->chunk(3);
                    $labTotalSessions = 15;
                    $labTotalHours = 45;
                    
                    $labCompletedBlocksCount = $labSessions->filter(function($block) {
                        $first = $block->first();
                        return !empty($first->actual_date);
                    })->count();
                    $labCompletedHours = $labCompletedBlocksCount * 3;
                    $labRemainingHours = max(0, $labTotalHours - $labCompletedHours);
                    $labRemainingBlocks = max(0, $labTotalSessions - $labCompletedBlocksCount);
                    $labCoveragePct = round(($labCompletedHours / $labTotalHours) * 100);
                    $labCoList = $labPlans->pluck('co_id')->filter()->unique()->values();
                @endphp

                <!-- 1. LAB METRIC SUMMARY BAR (4-CARD GRID) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Planned Hours -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-purple-300">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            </span>
                            <span class="text-xs font-bold text-slate-400 font-mono uppercase tracking-wider">Lab Target</span>
                        </div>
                        <div class="mt-3">
                            <div class="text-2xl font-black text-slate-900 font-heading tracking-tight" id="labMetricPlanned">45 <span class="text-xs font-bold text-slate-500">Hrs</span></div>
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Planned Lab Workload</div>
                            <div class="text-xs text-slate-500 font-medium mt-1">15 scheduled 3-hour sessions</div>
                        </div>
                    </div>

                    <!-- Conducted Hours -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-emerald-300">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60 font-mono">Conducted</span>
                        </div>
                        <div class="mt-3">
                            <div class="text-2xl font-black text-emerald-700 font-heading tracking-tight" id="labMetricCompleted">{{ $labCompletedHours }} <span class="text-xs font-bold text-emerald-600/70">Hrs</span></div>
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Conducted Hours</div>
                            <div class="text-xs text-slate-500 font-medium mt-1" id="labMetricCompletedSub">{{ $labCompletedBlocksCount }} of 15 sessions completed</div>
                        </div>
                    </div>

                    <!-- Remaining Hours -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-amber-300">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200/60 font-mono">Pending</span>
                        </div>
                        <div class="mt-3">
                            <div class="text-2xl font-black text-slate-900 font-heading tracking-tight" id="labMetricRemaining">{{ $labRemainingHours }} <span class="text-xs font-bold text-slate-500">Hrs</span></div>
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Remaining Hours</div>
                            <div class="text-xs text-slate-500 font-medium mt-1" id="labMetricRemainingSub">{{ $labRemainingBlocks }} sessions pending</div>
                        </div>
                    </div>

                    <!-- Practical Coverage -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-purple-300">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </span>
                            <span class="text-xs font-bold text-purple-700 font-mono" id="labMetricCoverageBadge">{{ $labCoveragePct }}%</span>
                        </div>
                        <div class="mt-3">
                            <div class="text-2xl font-black text-purple-700 font-heading tracking-tight" id="labMetricCoverage">{{ $labCoveragePct }}%</div>
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Practical Coverage</div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden mt-2">
                                <div id="labMetricProgressBar" class="bg-purple-600 h-full rounded-full transition-all duration-500" style="width: {{ $labCoveragePct }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. LAB PLANNER MAIN WORKSPACE CARD -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                    <!-- Action Toolbar Header -->
                    <div class="p-5 sm:px-6 border-b border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-purple-600 bg-purple-50 px-2.5 py-1 rounded-md border border-purple-100 font-mono">LAB DELIVERY PLAN</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mt-1">Practical Lab Lesson Planner</h3>
                            <p class="text-slate-500 text-xs mt-0.5">Plan experiments, practical activities and 15 scheduled three-hour laboratory sessions (45 Hours Total).</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-lesson-plan" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition-all flex items-center gap-1.5 shadow-2xs no-underline cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                <span>Print Lesson Plan</span>
                            </a>

                            <button type="button" id="btnSaveLabPlanner" onclick="saveAllLessonPlans()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 shadow-xs cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Save All 90 Hours</span>
                            </button>
                        </div>
                    </div>

                    <!-- Interactive Filter / Search Bar -->
                    <div class="border-b border-slate-100 bg-slate-50/60 p-3 sm:px-6 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <!-- Search Box -->
                            <div class="relative min-w-[200px] sm:w-64">
                                <input type="text" id="labPlannerSearch" oninput="filterLabPlannerRows()" placeholder="Search experiments, topics or sessions..." class="w-full pl-8 pr-3 py-1.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-indigo-500 shadow-2xs">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </span>
                            </div>

                            <!-- CO Filter -->
                            <select id="labPlannerCOFilter" onchange="filterLabPlannerRows()" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-indigo-500 shadow-2xs cursor-pointer">
                                <option value="ALL">All Outcomes (CO)</option>
                                @foreach($labCoList as $co)
                                    <option value="{{ $co }}">{{ $co }}</option>
                                @endforeach
                            </select>

                            <!-- Sub-Batch Filter -->
                            <select id="labPlannerBatchFilter" onchange="filterLabPlannerRows()" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-indigo-500 shadow-2xs cursor-pointer">
                                <option value="ALL">All Sub-Batches</option>
                                <option value="Batch A & B">Batch A & B (Combined)</option>
                                <option value="Batch A">Batch A</option>
                                <option value="Batch B">Batch B</option>
                            </select>

                            <!-- Status Filter -->
                            <select id="labPlannerStatusFilter" onchange="filterLabPlannerRows()" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-indigo-500 shadow-2xs cursor-pointer">
                                <option value="ALL">All Statuses</option>
                                <option value="Pending">Pending Only</option>
                                <option value="Completed">Completed Only</option>
                            </select>
                        </div>

                        <!-- Counter -->
                        <span id="labPlannerCount" class="text-xs font-bold text-slate-500 ml-auto">Showing {{ $labSessions->count() }} of {{ $labSessions->count() }} lab sessions</span>
                    </div>

                    <!-- Table Container -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[960px] lp-table">
                            <thead>
                                <tr class="bg-slate-50/90 text-xs font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200 sticky top-0 z-10">
                                    <th class="p-3.5 w-20 text-center">Session</th>
                                    <th class="p-3.5 w-36">Pedagogy</th>
                                    <th class="p-3.5 w-36">Proposed Date</th>
                                    <th class="p-3.5 w-36">Actual Date</th>
                                    <th class="p-3.5 min-w-[280px]">Experiment / Practical Topic</th>
                                    <th class="p-3.5 w-20 text-center">CO</th>
                                    <th class="p-3.5 w-36">Sub-Batch</th>
                                    <th class="p-3.5 w-24 text-center">Hours</th>
                                    <th class="p-3.5 w-24 text-center">Status</th>
                                    <th class="p-3.5 w-36">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm font-normal">
                                @forelse($labSessions as $sIdx => $block)
                                @php
                                    $firstPlan = $block->first();
                                    $blockIds = $block->pluck('id')->implode(',');
                                    $cleanTopic = preg_replace('/\s*\(Hour \d+\/\d+\)/i', '', $firstPlan->topic_content);
                                    $isCompleted = !empty($firstPlan->actual_date);
                                    $co = $firstPlan->co_id ?: 'CO1';
                                    $coBadgeClass = match($co) {
                                        'CO1' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'CO2' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'CO3' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'CO4' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'CO5' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'CO6' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <tr id="lp-row-{{ $firstPlan->id }}" data-plan-id="{{ $firstPlan->id }}" data-block-ids="{{ $blockIds }}" data-co="{{ $firstPlan->co_id }}" data-batch="{{ $firstPlan->sub_batch ?? 'Batch A & B' }}" data-status="{{ $isCompleted ? 'Completed' : 'Pending' }}" class="lab-planner-row hover:bg-slate-50/70 transition-colors {{ $isCompleted ? 'bg-emerald-50/15' : '' }}">
                                    <!-- Session # -->
                                    <td class="p-3 font-mono font-bold text-center text-slate-900 text-sm">
                                        <span class="px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 inline-flex items-center justify-center font-bold text-xs border border-purple-200/80 font-mono">Session {{ str_pad($sIdx + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    </td>

                                    <!-- Pedagogy -->
                                    <td class="p-2.5">
                                        <select id="lp-pedagogy-{{ $firstPlan->id }}" onchange="onPedagogyChange({{ $firstPlan->id }}, this.value); updateLabMetrics(); filterLabPlannerRows();" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-sm font-medium transition-all outline-none cursor-pointer text-emerald-700">
                                            <option value="Practical Lab (P)" {{ ($firstPlan->pedagogy ?? '') === 'Practical Lab (P)' || ($firstPlan->mode === 'P' && !isset($firstPlan->pedagogy)) ? 'selected' : '' }}>Practical Lab (P)</option>
                                            <option value="Practical Series Exam (SP)" {{ ($firstPlan->pedagogy ?? '') === 'Practical Series Exam (SP)' || ($firstPlan->mode === 'SP' && !isset($firstPlan->pedagogy)) ? 'selected' : '' }}>Practical Series Exam (SP)</option>
                                            <option value="Lecture (L)" {{ ($firstPlan->pedagogy ?? 'Lecture (L)') === 'Lecture (L)' || ($firstPlan->mode === 'L' && !isset($firstPlan->pedagogy)) ? 'selected' : '' }}>Lecture (L)</option>
                                            <option value="Theory Series Exam (ST)" {{ ($firstPlan->pedagogy ?? '') === 'Theory Series Exam (ST)' || ($firstPlan->mode === 'ST' && !isset($firstPlan->pedagogy)) ? 'selected' : '' }}>Theory Series Exam (ST)</option>
                                            <option value="PPT Presentation" {{ ($firstPlan->pedagogy ?? '') === 'PPT Presentation' ? 'selected' : '' }}>PPT Presentation</option>
                                            <option value="Demonstration" {{ ($firstPlan->pedagogy ?? '') === 'Demonstration' ? 'selected' : '' }}>Demonstration</option>
                                            <option value="Group Activity" {{ ($firstPlan->pedagogy ?? '') === 'Group Activity' ? 'selected' : '' }}>Group Activity</option>
                                        </select>
                                    </td>

                                    <!-- Proposed Date -->
                                    <td class="p-2.5">
                                        <input type="date" id="lp-prop-{{ $firstPlan->id }}" value="{{ $firstPlan->proposed_date }}" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-slate-800 text-sm font-mono transition-all outline-none">
                                    </td>

                                    <!-- Actual Date -->
                                    <td class="p-2.5">
                                        <input type="date" id="lp-act-{{ $firstPlan->id }}" value="{{ $firstPlan->actual_date }}" onchange="onLabActualDateChange({{ $firstPlan->id }}, this.value)" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-slate-800 text-sm font-mono transition-all outline-none">
                                    </td>

                                    <!-- Topic & Content -->
                                    <td class="p-2.5">
                                        <textarea id="lp-topic-{{ $firstPlan->id }}" rows="2" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-3 py-2 text-slate-900 text-sm font-normal transition-all outline-none resize-y leading-snug">{{ $cleanTopic }}</textarea>
                                    </td>

                                    <!-- CO -->
                                    <td class="p-2.5 text-center">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold border shadow-2xs {{ $coBadgeClass }}">
                                            {{ $firstPlan->co_id ?: 'CO1' }}
                                        </span>
                                        <input type="hidden" id="lp-co-{{ $firstPlan->id }}" value="{{ $firstPlan->co_id ?: 'CO1' }}">
                                    </td>

                                    <!-- Sub-Batch -->
                                    <td id="lp-batch-td-{{ $firstPlan->id }}" class="p-2.5">
                                        <select id="lp-batch-{{ $firstPlan->id }}" onchange="onLabBatchChange({{ $firstPlan->id }}, this.value)" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 rounded-xl px-2.5 py-2 font-bold text-xs text-emerald-700 outline-none cursor-pointer">
                                            <option value="Batch A & B" {{ ($firstPlan->sub_batch ?? 'Batch A & B') === 'Batch A & B' ? 'selected' : '' }}>Batch A & B (Combined)</option>
                                            <option value="Batch A" {{ ($firstPlan->sub_batch ?? '') === 'Batch A' ? 'selected' : '' }}>Batch A</option>
                                            <option value="Batch B" {{ ($firstPlan->sub_batch ?? '') === 'Batch B' ? 'selected' : '' }}>Batch B</option>
                                        </select>
                                    </td>

                                    <!-- Hours -->
                                    <td id="lp-hours-td-{{ $firstPlan->id }}" class="p-2.5 text-center font-normal">
                                        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-xs font-bold">3 Hours</span>
                                    </td>

                                    <!-- Status Indicator -->
                                    <td id="lp-status-td-{{ $firstPlan->id }}" class="p-2.5 text-center">
                                        <span id="lp-status-pill-{{ $firstPlan->id }}" class="px-2.5 py-1 rounded-full text-xs font-bold border shadow-2xs {{ $isCompleted ? 'bg-emerald-50 text-emerald-700 border-emerald-200/80' : 'bg-amber-50 text-amber-700 border-amber-200/80' }}">
                                            {{ $isCompleted ? 'Completed' : 'Pending' }}
                                        </span>
                                    </td>

                                    <!-- Remarks -->
                                    <td class="p-2.5">
                                        <input type="text" id="lp-remarks-{{ $firstPlan->id }}" value="{{ $firstPlan->remarks }}" placeholder="Status/Remarks..." class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-3 py-2 text-slate-800 text-sm font-normal transition-all outline-none">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="p-8 text-center text-slate-400 font-normal">No practical hours scheduled yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Subtab 3: Continuous Practical Evaluation -->
            <div id="lab-subcontent-eval" class="space-y-5 hidden">
                <!-- 1. Header & Actions Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-purple-600 bg-purple-50 px-2.5 py-1 rounded-md border border-purple-100 font-mono">PRACTICAL ASSESSMENT</span>
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100 font-mono">10 CIA Marks</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mt-1">Continuous Practical Evaluation (Table 2.2 Rubrics)</h3>
                            <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">
                                Grade student performance across 6 defined criteria: Prep (10M), Setup (10M), Observation (5M), Analysis (10M), Viva (10M), and Workmanship (5M). Total raw score of 50 is automatically scaled to 10 CIA Marks.
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <button type="button" onclick="printSubtabReport('Continuous Lab Evaluation (CE - 10M) Report', 'lab-subcontent-eval')" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition-all no-print flex items-center gap-1.5 shadow-2xs cursor-pointer">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                <span>Print Report</span>
                            </button>

                            <button type="button" onclick="openExperimentEvalModal()" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs flex items-center gap-1.5 transition-all cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                <span>Evaluate Experiment</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. Table Workspace Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                    <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Continuous Lab Work Rubric Evaluation Register</span>
                        </div>
                        <span class="text-xs font-bold text-slate-500 font-mono">{{ count($studentResults) }} Enrolled</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[980px]">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-700 font-bold text-xs uppercase tracking-wider">
                                    <th class="p-3.5 pl-4 text-center w-16">Roll</th>
                                    <th class="p-3.5 w-36">SBTE Reg No</th>
                                    <th class="p-3.5">Student Name</th>
                                    <th class="p-3 text-center w-24">Prep (10M)</th>
                                    <th class="p-3 text-center w-24">Setup (10M)</th>
                                    <th class="p-3 text-center w-20">Obs (5M)</th>
                                    <th class="p-3 text-center w-24">Analysis (10M)</th>
                                    <th class="p-3 text-center w-24">Viva (10M)</th>
                                    <th class="p-3 text-center w-20">Work (5M)</th>
                                    <th class="p-3 text-center w-28">Total Avg (/50)</th>
                                    <th class="p-3.5 text-center w-36">Converted CIA (10M)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm font-normal text-slate-700">
                                @forelse($studentResults as $res)
                                @php
                                    $stExps = $experimentEvals->get($res['reg_no'], collect());
                                    $count = $stExps->count();
                                    $avgPrep = $count > 0 ? $stExps->avg('prep_punctuality') : 0;
                                    $avgSetup = $count > 0 ? $stExps->avg('setup_procedure') : 0;
                                    $avgObs = $count > 0 ? $stExps->avg('observation_recording') : 0;
                                    $avgAnalysis = $count > 0 ? $stExps->avg('analysis_interpretation') : 0;
                                    $avgViva = $count > 0 ? $stExps->avg('viva_voce') : 0;
                                    $avgWorkmanship = $count > 0 ? $stExps->avg('workmanship_discipline') : 0;
                                    $totalAvg50 = $avgPrep + $avgSetup + $avgObs + $avgAnalysis + $avgViva + $avgWorkmanship;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-3 pl-4 text-center font-mono font-bold text-slate-900">{{ $res['roll_no'] ?: '—' }}</td>
                                    <td class="p-3 font-mono font-bold text-indigo-700 text-xs">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-3 text-slate-900 font-medium">{{ $res['name'] }}</td>
                                    <td class="p-3 text-center font-mono text-slate-700">{{ number_format($avgPrep, 1) }}</td>
                                    <td class="p-3 text-center font-mono text-slate-700">{{ number_format($avgSetup, 1) }}</td>
                                    <td class="p-3 text-center font-mono text-slate-700">{{ number_format($avgObs, 1) }}</td>
                                    <td class="p-3 text-center font-mono text-slate-700">{{ number_format($avgAnalysis, 1) }}</td>
                                    <td class="p-3 text-center font-mono text-slate-700">{{ number_format($avgViva, 1) }}</td>
                                    <td class="p-3 text-center font-mono text-slate-700">{{ number_format($avgWorkmanship, 1) }}</td>
                                    <td class="p-3 text-center font-mono font-bold text-slate-900">{{ number_format($totalAvg50, 2) }}</td>
                                    <td class="p-3 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono border shadow-2xs bg-purple-50 text-purple-700 border-purple-200/80">
                                            {{ number_format($res['continuous_eval_marks'], 1) }} / 10.0
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="p-8 text-center text-slate-400 font-normal">No student experiment evaluation records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Subtab 4: Practical Series Examinations -->
            <div id="lab-subcontent-series" class="space-y-4 hidden">
                <!-- Section Header -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 font-mono">TABLE 3.1</span>
                                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-100 font-mono">Practical Series Examinations</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mt-1">Practical Series Test — CIA Marks Register</h3>
                            <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">Rubric-based series test evaluation. Total out of 40 Marks, scaled to 10 CIA Marks. Avg of best tests.</p>
                        </div>
                        <div class="flex items-center gap-2 no-print">
                            <button onclick="printSubtabReport('Practical Series Examinations Report', 'lab-subcontent-series')" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition-all shadow-2xs cursor-pointer flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                Print Report
                            </button>
                            <button onclick="openSeriesPracticalModal()" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition-all cursor-pointer flex items-center gap-1.5 border border-indigo-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Enter Lab Series Test Marks
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Practical QP Generator Panel -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs no-print">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Practical Series QP Generator</h3>
                            <p class="text-slate-500 text-xs mt-0.5">Rubrics: Procedure (10M) + Setup (10M) + Result (10M) + Viva (5M) + Record (5M) = 40 Marks | 3 Hours | Scaled to 10 CIA Marks</p>
                        </div>
                    </div>

                    <!-- 2 Practical Series Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(['Practical Series 1' => 'CO1+CO2', 'Practical Series 2' => 'CO3+CO4'] as $series => $co)
                        @php $savedQp = $seriesQps[$series] ?? null; @endphp
                        <div class="rounded-xl border {{ $savedQp ? 'border-emerald-200 bg-emerald-50/60' : 'border-slate-200 bg-slate-50' }} p-4 flex flex-col gap-3">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-sm">{{ $series }}</span>
                                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold {{ $savedQp ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">{{ $co }}</span>
                            </div>

                            <!-- Status -->
                            @if($savedQp)
                            <div class="text-xs text-emerald-700 font-semibold flex items-center gap-1"><span class="text-emerald-500">✓</span> Practical QP Saved</div>
                            @else
                            <div class="text-xs text-slate-400">Not generated yet</div>
                            @endif

                            <!-- Generate buttons -->
                            <div class="flex flex-col sm:flex-row gap-2 mt-1">
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $co }}', 'ai')"
                                    class="w-full sm:w-1/2 py-2 rounded-lg text-xs font-bold bg-white hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 text-slate-700 hover:text-indigo-700 transition-all text-center shadow-2xs cursor-pointer">
                                    ⚡ AI Generate
                                </button>
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $co }}', 'manual')"
                                    class="w-full sm:w-1/2 py-2 rounded-lg text-xs font-bold bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 transition-all text-center shadow-2xs cursor-pointer">
                                    ✏ Manual Entry
                                </button>
                            </div>

                            <!-- Print buttons (only if saved) -->
                            @if($savedQp)
                            <div class="border-t border-slate-200/70 pt-3 flex flex-col gap-2">
                                <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-qp/{{ rawurlencode($series) }}" target="_blank"
                                    class="w-full py-2 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white text-center block no-underline transition-all cursor-pointer">
                                    🖨️ Print Practical QP
                                </a>
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-scheme/{{ rawurlencode($series) }}" target="_blank"
                                        class="py-2 rounded-lg text-xs font-bold bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-center block no-underline transition-all cursor-pointer">
                                        📋 Scheme
                                    </a>
                                    <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-key/{{ rawurlencode($series) }}" target="_blank"
                                        class="py-2 rounded-lg text-xs font-bold bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-center block no-underline transition-all cursor-pointer">
                                        🔑 Key
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div><!-- /grid -->
                </div><!-- /Practical QP Generator Panel -->

                <!-- Data Table Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                    <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/60">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Series Practical Marks Register</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs table-compact-header">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase bg-slate-50 text-xs tracking-wide">
                                    <th class="p-3 w-12 text-center">Roll</th>
                                    <th class="p-3">SBTE Reg No</th>
                                    <th class="p-3">Student Name</th>
                                    <th class="p-3 text-center">Writeup (10M)</th>
                                    <th class="p-3 text-center">Setup (10M)</th>
                                    <th class="p-3 text-center">Obs/Result (8M)</th>
                                    <th class="p-3 text-center">Viva (8M)</th>
                                    <th class="p-3 text-center">Record (4M)</th>
                                    <th class="p-3 text-center">Test 1 (/40)</th>
                                    <th class="p-3 text-center">Test 2 (/40)</th>
                                    <th class="p-3 text-center">Avg (/40)</th>
                                    <th class="p-3 text-center">CIA (/10M)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-normal">
                                @foreach($studentResults as $res)
                                @php
                                    $spEvals = $seriesPracticalEvals->get($res['reg_no'], collect());
                                    $sp1 = $spEvals->whereIn('series_no', ['Series 1', 'Test 1 (CO1+CO2)'])->first();
                                    $sp2 = $spEvals->whereIn('series_no', ['Series 2', 'Test 2 (CO3+CO4)'])->first();
                                    $spCount = $spEvals->count();
                                    $spWriteup = $spCount > 0 ? $spEvals->avg('writeup_procedure') : 0;
                                    $spSetup = $spCount > 0 ? $spEvals->avg('setup_execution') : 0;
                                    $spObs = $spCount > 0 ? $spEvals->avg('observation_result') : 0;
                                    $spViva = $spCount > 0 ? $spEvals->avg('viva_voce') : 0;
                                    $spRecord = $spCount > 0 ? $spEvals->avg('record_completion') : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-3 text-center font-mono font-bold text-slate-500 text-xs">{{ $res['roll_no'] }}</td>
                                    <td class="p-3 font-mono text-slate-700 font-bold text-xs">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-3 font-semibold text-slate-900">{{ $res['name'] }}</td>
                                    <td class="p-3 text-center text-slate-600">{{ number_format($spWriteup, 1) }}</td>
                                    <td class="p-3 text-center text-slate-600">{{ number_format($spSetup, 1) }}</td>
                                    <td class="p-3 text-center text-slate-600">{{ number_format($spObs, 1) }}</td>
                                    <td class="p-3 text-center text-slate-600">{{ number_format($spViva, 1) }}</td>
                                    <td class="p-3 text-center text-slate-600">{{ number_format($spRecord, 1) }}</td>
                                    <td class="p-3 text-center font-mono font-bold text-slate-800">{{ $sp1 ? number_format($sp1->total_score_40, 2) : '-' }}</td>
                                    <td class="p-3 text-center font-mono font-bold text-slate-800">{{ $sp2 ? number_format($sp2->total_score_40, 2) : '-' }}</td>
                                    <td class="p-3 text-center font-mono font-bold text-slate-800">{{ number_format($res['series_practical_marks'] * 4, 2) }}</td>
                                    <td class="p-3 text-center font-mono font-black text-indigo-700">{{ number_format($res['series_practical_marks'], 1) }} / 10.0</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Subtab 5: Practical ESE -->
            <div id="lab-subcontent-ese" class="bg-white border border-slate-200/80 rounded-2xl shadow-sm p-5 rounded-xl border border-blue-600/40 bg-gradient-to-br from-slate-900 via-slate-900/95 to-blue-950/20 hidden">
                <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                    <div>
                        <h3 class="text-base font-semibold text-blue-300 flex items-center space-x-2">
                            <span>🏆 Institutional Practical End Semester Exam (40 Marks)</span>
                        </h3>
                        <p class="text-slate-400 text-xs mt-0.5">
                            Rubrics splitup: Procedure (10M) + Setup (10M) + Result (8M) + Viva (8M) + Record (4M) = 40 Marks
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="printSubtabReport('Practical End Semester Exam (ESE) Report', 'lab-subcontent-ese')" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200 font-semibold text-xs transition-all no-print">🖨️ Print Report</button>
                        <button onclick="openEsePracticalModal()" class="px-3 py-1.5 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/35 text-indigo-300 border border-indigo-500/40 font-semibold text-xs shadow-sm transition-all flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Enter Practical ESE Marks</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs table-compact-header">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-700 font-bold bg-slate-50 border-b border-slate-200">
                                <th class="p-2.5 w-12 text-center">Roll</th>
                                <th class="p-2.5">SBTE Reg No</th>
                                <th class="p-2.5">Student Name</th>
                                <th class="p-2.5 text-center">Writeup (10M)</th>
                                <th class="p-2.5 text-center">Setup (10M)</th>
                                <th class="p-2.5 text-center">Obs/Result (8M)</th>
                                <th class="p-2.5 text-center">Viva (8M)</th>
                                <th class="p-2.5 text-center">Record (4M)</th>
                                <th class="p-2.5 text-center">Practical ESE Total</th>
                                <th class="p-2.5 text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60" id="ese-practical-table-body">
                            @foreach($studentResults as $res)
                            @php
                                $score = $res['ese_practical'] ?? 0;
                                $pct = ($score / 40) * 100;
                                if ($pct >= 90) { $g = 'S'; $gc = 'text-emerald-600 bg-emerald-500/10 border-emerald-500/30'; }
                                elseif ($pct >= 80) { $g = 'A'; $gc = 'text-blue-700 bg-blue-50 border border-blue-200'; }
                                elseif ($pct >= 70) { $g = 'B'; $gc = 'text-indigo-600 bg-indigo-500/10 border-indigo-500/30'; }
                                elseif ($pct >= 60) { $g = 'C'; $gc = 'text-violet-600 bg-purple-500/10 border-purple-500/30'; }
                                elseif ($pct >= 50) { $g = 'D'; $gc = 'text-amber-600 bg-amber-500/10 border-amber-500/30'; }
                                elseif ($pct >= 40) { $g = 'E'; $gc = 'text-orange-400 bg-orange-500/10 border-orange-500/30'; }
                                else { $g = 'F'; $gc = 'text-rose-600 bg-rose-500/10 border-rose-500/30'; }
                                
                                $wInit = number_format(($score / 40.0) * 10.0, 1);
                                $sInit = number_format(($score / 40.0) * 10.0, 1);
                                $rInit = number_format(($score / 40.0) * 8.0, 1);
                                $vInit = number_format(($score / 40.0) * 8.0, 1);
                                $recInit = number_format(($score / 40.0) * 4.0, 1);
                            @endphp
                            <tr class="hover:bg-slate-50 transition-all" id="ese-row-{{ $res['reg_no'] }}">
                                <td class="p-2.5 text-center text-slate-700">{{ $res['roll_no'] }}</td>
                                <td class="p-2.5 font-mono text-slate-700 font-bold">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                <td class="p-2.5 font-bold text-white">{{ $res['name'] }}</td>
                                <td class="p-2.5 text-center text-slate-700 ese-val-writeup">{{ $score > 0 ? $wInit : '-' }}</td>
                                <td class="p-2.5 text-center text-slate-700 ese-val-setup">{{ $score > 0 ? $sInit : '-' }}</td>
                                <td class="p-2.5 text-center text-slate-700 ese-val-result">{{ $score > 0 ? $rInit : '-' }}</td>
                                <td class="p-2.5 text-center text-slate-700 ese-val-viva">{{ $score > 0 ? $vInit : '-' }}</td>
                                <td class="p-2.5 text-center text-slate-700 ese-val-record">{{ $score > 0 ? $recInit : '-' }}</td>
                                <td class="p-2.5 text-center font-bold text-blue-700 ese-val-total">{{ round($score) }}</td>
                                <td class="p-2.5 text-center ese-val-grade">
                                    <span class="px-2.5 py-0.5 rounded-full border text-xs font-bold {{ $gc }}">{{ $g }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </main>

    <!-- Customize Self-Learning Activities Modal -->
    <div id="sl-config-modal" class="fixed inset-0 z-50 bg-slate-50 backdrop-blur-xs flex items-center justify-center hidden p-4">
        <div class="bg-white max-w-2xl w-full p-6 rounded-2xl border border-slate-200 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </span>
                    <h3 class="text-base font-bold text-slate-900">Customize Self-Learning Activities (CA1)</h3>
                </div>
                <button type="button" onclick="closeSlConfigModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all">&times;</button>
            </div>
            
            <p class="text-slate-500 text-xs">Mandatory core activities (<span class="text-amber-700 font-bold">Assignment</span> & <span class="text-emerald-700 font-bold">MCQ</span>) are always evaluated out of 15 Marks. Select optional assessment activities per Course Outcome:</p>

            <form id="sl-config-form" onsubmit="saveSlConfig(event)" class="space-y-4 max-h-[450px] overflow-y-auto pr-1">
                @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2.5">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-200 font-mono font-bold text-xs">{{ $coTag }}</span>
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Assessment Activities</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5 text-xs">
                        <label class="flex items-center space-x-2 text-slate-600 bg-white p-2 rounded-lg border border-slate-200/80 opacity-90 cursor-not-allowed">
                            <input type="checkbox" checked disabled class="rounded text-amber-600 border-slate-300">
                            <span class="font-bold text-amber-800">Assignment (Mandatory)</span>
                        </label>
                        <label class="flex items-center space-x-2 text-slate-600 bg-white p-2 rounded-lg border border-slate-200/80 opacity-90 cursor-not-allowed">
                            <input type="checkbox" checked disabled class="rounded text-emerald-600 border-slate-300">
                            <span class="font-bold text-emerald-800">MCQ (Mandatory)</span>
                        </label>
                        @foreach(['case_study' => 'Case Study', 'quiz' => 'Quiz', 'activity' => 'Activity', 'microproject' => 'Microproject', 'mini_project' => 'Mini Project', 'report' => 'Report', 'exercises' => 'Exercises', 'presentation' => 'Presentation'] as $actKey => $actLabel)
                        <label class="flex items-center space-x-2 text-slate-700 bg-white p-2 rounded-lg border border-slate-200 hover:border-indigo-300 cursor-pointer transition-colors">
                            <input type="checkbox" name="configs[{{ $coTag }}][{{ $actKey }}]" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-0">
                            <span class="font-medium text-slate-800">{{ $actLabel }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeSlConfigModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer">Save Activities Config</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Enter Self-Learning Marks Modal (CA1 Activity-Wise Sliders) -->
    <div id="sl-marks-modal" class="fixed inset-0 z-50 bg-slate-50 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-5">
        <div class="bg-white max-w-3xl w-full p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Continuous Assessment Activity Evaluator</h3>
                        <p class="text-slate-500 text-xs mt-0.5">Adjust sliders or tap +/- steppers to evaluate activity-wise splitup for each student.</p>
                    </div>
                </div>
                <button type="button" onclick="closeSlMarksModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all">&times;</button>
            </div>

            <!-- Student Selection & Stepper Bar -->
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 flex items-center justify-between gap-2.5 flex-shrink-0">
                <button type="button" onclick="prevSlStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                    <span>◀ Prev</span>
                </button>

                <div class="flex-1 max-w-md">
                    <select id="sl-student-select" onchange="loadSlStudent(this.value)" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-bold text-sm text-slate-900 outline-none focus:border-indigo-500 shadow-2xs cursor-pointer">
                        @foreach($studentResults as $idx => $res)
                        <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }} (SBTE: {{ $res['sbte_reg_no'] ?: $res['reg_no'] }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" onclick="nextSlStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                    <span>Next ▶</span>
                </button>
            </div>

            <!-- Live Score Summary Card -->
            <div class="bg-indigo-50/70 p-3.5 rounded-xl border border-indigo-100 flex items-center justify-between text-xs flex-shrink-0">
                <div>
                    <span class="text-slate-600 font-bold">Selected Student Average:</span>
                    <span id="sl-student-total-raw" class="font-extrabold text-slate-900 text-sm ml-1.5 font-mono">0.00 / 15.00 M</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-600 font-bold">Converted CA1 CIA:</span>
                    <span id="sl-student-converted-cia" class="font-black text-emerald-700 text-sm ml-1 px-2.5 py-1 rounded-md bg-emerald-50 border border-emerald-200 font-mono">0.00 / 5.00 M</span>
                </div>
            </div>

            <!-- Scrollable Activity Sliders Container -->
            <div id="sl-sliders-container" class="overflow-y-auto space-y-4 flex-1 pr-1">
                <!-- Dynamically populated by JS loadSlStudent() -->
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 flex-shrink-0">
                <button type="button" onclick="closeSlMarksModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-colors cursor-pointer">Close</button>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="saveAndNextSlStudent()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200 font-bold text-xs transition-colors shadow-2xs cursor-pointer">Next Student ▶</button>
                    <button type="button" onclick="saveAllSlMarks()" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Practical ESE Evaluator Modal — CampusLynk Light Theme -->
    <div id="ese-practical-modal" class="fixed inset-0 z-50 bg-slate-50 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-5">
        <div class="bg-white max-w-3xl w-full rounded-2xl border border-slate-200 shadow-2xl max-h-[92vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Institutional Practical ESE Evaluator</h3>
                        <p class="text-slate-500 text-xs mt-0.5">Adjust rubric sliders for procedure, setup, result, viva, and record.</p>
                    </div>
                </div>
                <button onclick="closeEsePracticalModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all">&times;</button>
            </div>

            <!-- Student Selection & Stepper Bar -->
            <div class="bg-slate-50 px-5 py-3 border-b border-slate-100 flex items-center justify-between gap-2.5 flex-shrink-0">
                <button type="button" onclick="prevEseStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                    <span>◀ Prev</span>
                </button>
                <div class="flex-1 max-w-md">
                    <select id="ese-student-select" onchange="loadEseStudent(this.value)" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-bold text-sm text-slate-900 outline-none focus:border-blue-500 shadow-2xs cursor-pointer">
                        @foreach($studentResults as $idx => $res)
                        <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }} (SBTE: {{ $res['sbte_reg_no'] ?: $res['reg_no'] }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" onclick="nextEseStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                    <span>Next ▶</span>
                </button>
            </div>

            <!-- Live Score Summary Card -->
            <div class="bg-blue-50/70 px-5 py-3 border-b border-blue-100 flex items-center justify-between text-xs flex-shrink-0">
                <div>
                    <span class="text-slate-600 font-bold">Practical ESE Score:</span>
                    <span id="ese-student-total-raw" class="font-extrabold text-blue-700 text-sm ml-1.5 font-mono">0.00 / 40.00 Marks</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-600 font-bold">Evaluated Grade:</span>
                    <span id="ese-student-grade-badge" class="font-black text-blue-700 text-sm px-3 py-1 rounded-full bg-blue-100 border border-blue-200 font-mono">S</span>
                </div>
            </div>

            <!-- Scrollable Rubric Sliders Container -->
            <div id="ese-sliders-container" class="overflow-y-auto space-y-3 flex-1 px-5 py-4 pr-4">
                <!-- Dynamically populated by JS loadEseStudent() -->
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between px-5 py-4 border-t border-slate-100 flex-shrink-0">
                <button type="button" onclick="closeEsePracticalModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-colors cursor-pointer">Close</button>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="saveAndNextEseStudent()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200 font-bold text-xs transition-colors shadow-2xs cursor-pointer">Next Student ▶</button>
                    <button type="button" onclick="saveAllEseMarks()" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer">Save All ESE Marks</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Board Theory ESE Grade Entry Modal -->
    <div id="ese-theory-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-5">
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm max-w-3xl w-full p-5 rounded-2xl border border-indigo-500/40 shadow-2xl space-y-4 max-h-[92vh] flex flex-col bg-white">
            <div class="flex items-center justify-between border-b border-slate-200 pb-3 flex-shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-indigo-300">Board Theory ESE Grade Entry</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Select official letter grades issued by SBTE board norms.</p>
                </div>
                <button onclick="closeEseTheoryModal()" class="text-slate-400 hover:text-white text-2xl font-bold">&times;</button>
            </div>

            <div class="overflow-x-auto max-h-[60vh] overflow-y-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="sticky top-0 bg-white shadow">
                        <tr class="border-b border-slate-200 text-slate-400 font-semibold uppercase">
                            <th class="p-2.5 w-12 text-center">Roll</th>
                            <th class="p-2.5">Reg No</th>
                            <th class="p-2.5">Student Name</th>
                            <th class="p-2.5 text-center">Board Letter Grade</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 font-normal">
                        @foreach($studentResults as $res)
                        @php $curGrade = strtoupper($res['ese_theory_grade'] ?? ''); @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="p-2.5 text-center text-slate-400">{{ $res['roll_no'] }}</td>
                            <td class="p-2.5 font-mono text-slate-700">{{ $res['reg_no'] }}</td>
                            <td class="p-2.5 font-bold text-white">{{ $res['name'] }}</td>
                            <td class="p-2.5 text-center">
                                <select id="ese-theory-grade-{{ $res['reg_no'] }}" class="bg-white border border-slate-200 rounded px-3 py-1 text-xs font-bold text-indigo-300 outline-none focus:border-indigo-400">
                                    <option value="" {{ $curGrade === '' ? 'selected' : '' }}>-- Select Grade --</option>
                                    <option value="S" {{ $curGrade === 'S' ? 'selected' : '' }}>S (90% & above - Outstanding)</option>
                                    <option value="A" {{ $curGrade === 'A' ? 'selected' : '' }}>A ([80-90) - Excellent)</option>
                                    <option value="B" {{ $curGrade === 'B' ? 'selected' : '' }}>B ([70-80) - Very Good)</option>
                                    <option value="C" {{ $curGrade === 'C' ? 'selected' : '' }}>C ([60-70) - Good)</option>
                                    <option value="D" {{ $curGrade === 'D' ? 'selected' : '' }}>D ([50-60) - Average)</option>
                                    <option value="E" {{ $curGrade === 'E' ? 'selected' : '' }}>E ([40-50) - Satisfactory)</option>
                                    <option value="F" {{ $curGrade === 'F' ? 'selected' : '' }}>F (Below 40 - Reappearance Required)</option>
                                    <option value="FE" {{ $curGrade === 'FE' ? 'selected' : '' }}>FE (Shortage of Attendance)</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-200 flex-shrink-0">
                <button type="button" onclick="closeEseTheoryModal()" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-700 font-semibold text-xs hover:bg-slate-100">Cancel</button>
                <button type="button" onclick="saveAllEseTheoryGrades()" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm">Save Theory Grades</button>
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
            localStorage.setItem('active_mode', mode);
        }

        function switchTheorySubtab(tab) {
            ['overview', 'planner', 'sl', 'series', 'ese', 'surveys', 'attendance', 'materials'].forEach(t => {
                document.getElementById('theory-subcontent-' + t)?.classList.add('hidden');
                document.getElementById('theory-tab-' + t)?.classList.remove('active', 'text-white');
            });
            document.getElementById('theory-subcontent-' + tab)?.classList.remove('hidden');
            document.getElementById('theory-tab-' + tab)?.classList.add('active', 'text-white');
            localStorage.setItem('active_theory_subtab', tab);
        }

        function switchLabSubtab(tab) {
            ['roster', 'planner', 'eval', 'series', 'ese'].forEach(t => {
                document.getElementById('lab-subcontent-' + t)?.classList.add('hidden');
                document.getElementById('lab-tab-' + t)?.classList.remove('active', 'text-white');
            });
            document.getElementById('lab-subcontent-' + tab)?.classList.remove('hidden');
            document.getElementById('lab-tab-' + tab)?.classList.add('active', 'text-white');
            localStorage.setItem('active_lab_subtab', tab);
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

        function openSyllabusModal() { 
          const modal = document.getElementById('syllabus-modal');
          if (modal) modal.classList.remove('hidden'); 
        }
        function closeSyllabusModal() { 
          const modal = document.getElementById('syllabus-modal');
          if (modal) modal.classList.add('hidden'); 
        }

        function handlePracticumDragOver(e) {
          e.preventDefault();
          e.stopPropagation();
          const dropzone = document.getElementById('practicumSyllabusDropzone');
          if (dropzone) dropzone.classList.add('border-blue-500', 'bg-blue-50/60');
        }

        function handlePracticumDragLeave(e) {
          e.preventDefault();
          e.stopPropagation();
          const dropzone = document.getElementById('practicumSyllabusDropzone');
          if (dropzone) dropzone.classList.remove('border-blue-500', 'bg-blue-50/60');
        }

        function handlePracticumFileDrop(e) {
          e.preventDefault();
          e.stopPropagation();
          const dropzone = document.getElementById('practicumSyllabusDropzone');
          if (dropzone) dropzone.classList.remove('border-blue-500', 'bg-blue-50/60');

          const files = e.dataTransfer.files;
          if (!files || files.length === 0) return;
          const file = files[0];
          if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
            alert('Please drop a valid PDF file.');
            return;
          }
          const input = document.getElementById('practicumSyllabusFileInput');
          if (input) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
            showPracticumFilePreview(file);
          }
        }

        function handlePracticumFileInput(input) {
          if (!input.files || input.files.length === 0) return;
          showPracticumFilePreview(input.files[0]);
        }

        function showPracticumFilePreview(file) {
          const dropzone = document.getElementById('practicumSyllabusDropzone');
          const preview = document.getElementById('practicumFilePreview');
          const nameEl = document.getElementById('practicumFileName');
          const sizeEl = document.getElementById('practicumFileSize');
          const errBox = document.getElementById('practicumErrorAlert');

          if (errBox) errBox.classList.add('hidden');
          if (nameEl) nameEl.innerText = file.name;
          if (sizeEl) {
            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
            sizeEl.innerText = `${sizeMB} MB · Ready`;
          }
          if (dropzone) dropzone.classList.add('hidden');
          if (preview) preview.classList.remove('hidden');
        }

        function cancelPracticumSelectedFile(e) {
          if (e) { e.preventDefault(); e.stopPropagation(); }
          const input = document.getElementById('practicumSyllabusFileInput');
          if (input) input.value = '';
          const dropzone = document.getElementById('practicumSyllabusDropzone');
          const preview = document.getElementById('practicumFilePreview');
          const processing = document.getElementById('practicumProcessingState');
          const errBox = document.getElementById('practicumErrorAlert');

          if (preview) preview.classList.add('hidden');
          if (processing) processing.classList.add('hidden');
          if (errBox) errBox.classList.add('hidden');
          if (dropzone) dropzone.classList.remove('hidden');
        }

        function submitPracticumSyllabus() {
          const input = document.getElementById('practicumSyllabusFileInput');
          if (!input || !input.files || input.files.length === 0) {
            alert('Please select a syllabus PDF first.');
            return;
          }
          const file = input.files[0];
          const formData = new FormData();
          formData.append('syllabus_file', file);
          formData.append('_token', "{{ csrf_token() }}");

          const preview = document.getElementById('practicumFilePreview');
          const processing = document.getElementById('practicumProcessingState');
          const errBox = document.getElementById('practicumErrorAlert');
          const errMsg = document.getElementById('practicumErrorMessage');
          const btnSubmit = document.getElementById('btnSubmitPracticumSyllabus');

          if (preview) preview.classList.add('hidden');
          if (errBox) errBox.classList.add('hidden');
          if (processing) processing.classList.remove('hidden');
          if (btnSubmit) btnSubmit.disabled = true;

          fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/syllabus', {
            method: 'POST',
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if (processing) processing.classList.add('hidden');
            if (btnSubmit) btnSubmit.disabled = false;
            if (data.status === 'SUCCESS') {
              window.location.reload();
            } else {
              if (errMsg) errMsg.innerText = data.message || 'Extraction failed. Please check PDF.';
              if (errBox) errBox.classList.remove('hidden');
              const dropzone = document.getElementById('practicumSyllabusDropzone');
              if (dropzone) dropzone.classList.remove('hidden');
            }
          })
          .catch(err => {
            if (processing) processing.classList.add('hidden');
            if (btnSubmit) btnSubmit.disabled = false;
            if (errMsg) errMsg.innerText = 'Upload Error: ' + err.message;
            if (errBox) errBox.classList.remove('hidden');
            const dropzone = document.getElementById('practicumSyllabusDropzone');
            if (dropzone) dropzone.classList.remove('hidden');
          });
        }

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
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-200 font-mono text-xs">${co}</span>
                                <span>Assessment Activities</span>
                                <span class="text-xs text-slate-500 font-medium">(${actKeys.length} Active)</span>
                            </h4>
                            <span id="co-sum-${co}" class="text-xs font-bold text-slate-700 bg-white px-2.5 py-1 rounded-md border border-slate-200 shadow-2xs font-mono">
                                Avg: 0.0 / 15.0
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                `;

                actKeys.forEach(actKey => {
                    const label = activityLabels[actKey] || actKey.toUpperCase();
                    const currentVal = slSplitupState[regNo][co] ? (slSplitupState[regNo][co][actKey] || 0) : 0;

                    html += `
                        <div class="p-3.5 rounded-xl bg-white border border-slate-200 shadow-2xs space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 text-xs uppercase tracking-wide">${label}</span>
                                <span id="badge-${co}-${actKey}" class="px-2.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-mono text-xs font-bold border border-indigo-200">
                                    ${parseFloat(currentVal).toFixed(1)} / 15.0
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="stepSlSlider('${regNo}', '${co}', '${actKey}', -0.5)" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 border border-slate-200 font-bold text-slate-800 text-base flex items-center justify-center transition-colors shadow-2xs cursor-pointer">-</button>
                                <input type="range" id="slider-${co}-${actKey}" min="0" max="15" step="0.5" value="${currentVal}" oninput="syncSlSlider('${regNo}', '${co}', '${actKey}', this.value)" class="flex-1 accent-indigo-600 h-2 bg-slate-200 rounded-lg cursor-pointer">
                                <button type="button" onclick="stepSlSlider('${regNo}', '${co}', '${actKey}', 0.5)" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 border border-slate-200 font-bold text-slate-800 text-base flex items-center justify-center transition-colors shadow-2xs cursor-pointer">+</button>
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

        function filterTheoryPlannerRows() {
            const search = (document.getElementById('theoryPlannerSearch')?.value || '').toLowerCase().trim();
            const co = document.getElementById('theoryPlannerCOFilter')?.value || 'ALL';
            const status = document.getElementById('theoryPlannerStatusFilter')?.value || 'ALL';

            const rows = document.querySelectorAll('#theory-subcontent-planner tr.theory-planner-row');
            let visibleCount = 0;

            rows.forEach(tr => {
                const rowCo = tr.getAttribute('data-co') || '';
                const rowStatus = tr.getAttribute('data-status') || '';
                const topic = (tr.querySelector('textarea[id^="lp-topic-"]')?.value || '').toLowerCase();
                const sessionText = (tr.querySelector('td:first-child')?.innerText || '').toLowerCase();
                const remarks = (tr.querySelector('input[id^="lp-remarks-"]')?.value || '').toLowerCase();
                const pedagogy = (tr.querySelector('select[id^="lp-pedagogy-"]')?.value || '').toLowerCase();

                let matchSearch = !search || topic.includes(search) || sessionText.includes(search) || remarks.includes(search) || pedagogy.includes(search) || rowCo.toLowerCase().includes(search);
                let matchCo = (co === 'ALL') || (rowCo === co);
                let matchStatus = (status === 'ALL') || (rowStatus === status);

                if (matchSearch && matchCo && matchStatus) {
                    tr.classList.remove('hidden');
                    visibleCount++;
                } else {
                    tr.classList.add('hidden');
                }
            });

            const countEl = document.getElementById('theoryPlannerCount');
            if (countEl) {
                countEl.innerText = `Showing ${visibleCount} of ${rows.length} sessions`;
            }
        }

        function onTheoryActualDateChange(planId, value) {
            const tr = document.getElementById('lp-row-' + planId);
            const pill = document.getElementById('lp-status-pill-' + planId);
            const isCompleted = Boolean(value && value.trim() !== '');

            if (tr) {
                tr.setAttribute('data-status', isCompleted ? 'Completed' : 'Pending');
                if (isCompleted) {
                    tr.classList.add('bg-emerald-50/15');
                } else {
                    tr.classList.remove('bg-emerald-50/15');
                }
            }

            if (pill) {
                if (isCompleted) {
                    pill.className = "px-2.5 py-1 rounded-full text-xs font-bold border shadow-2xs bg-emerald-50 text-emerald-700 border-emerald-200/80";
                    pill.innerText = 'Completed';
                } else {
                    pill.className = "px-2.5 py-1 rounded-full text-xs font-bold border shadow-2xs bg-amber-50 text-amber-700 border-amber-200/80";
                    pill.innerText = 'Pending';
                }
            }

            updateTheoryMetrics();
        }

        function updateTheoryMetrics() {
            const rows = document.querySelectorAll('#theory-subcontent-planner tr.theory-planner-row');
            let completed = 0;

            rows.forEach(tr => {
                const actInput = tr.querySelector('input[id^="lp-act-"]');
                if (actInput && actInput.value && actInput.value.trim() !== '') {
                    completed++;
                }
            });

            const total = 45;
            const remaining = Math.max(0, total - completed);
            const coverage = Math.round((completed / total) * 100);

            const completedEl = document.getElementById('theoryMetricCompleted');
            const completedSubEl = document.getElementById('theoryMetricCompletedSub');
            const remainingEl = document.getElementById('theoryMetricRemaining');
            const remainingSubEl = document.getElementById('theoryMetricRemainingSub');
            const coverageEl = document.getElementById('theoryMetricCoverage');
            const coverageBadge = document.getElementById('theoryMetricCoverageBadge');
            const progressBar = document.getElementById('theoryMetricProgressBar');

            if (completedEl) completedEl.innerHTML = `${completed} <span class="text-xs font-bold text-emerald-600/70">Hrs</span>`;
            if (completedSubEl) completedSubEl.innerText = `${completed} sessions conducted`;
            if (remainingEl) remainingEl.innerHTML = `${remaining} <span class="text-xs font-bold text-slate-500">Hrs</span>`;
            if (remainingSubEl) remainingSubEl.innerText = `${remaining} sessions pending`;
            if (coverageEl) coverageEl.innerText = `${coverage}%`;
            if (coverageBadge) coverageBadge.innerText = `${coverage}%`;
            if (progressBar) progressBar.style.width = `${coverage}%`;
        }

        function filterLabPlannerRows() {
            const search = (document.getElementById('labPlannerSearch')?.value || '').toLowerCase().trim();
            const co = document.getElementById('labPlannerCOFilter')?.value || 'ALL';
            const batch = document.getElementById('labPlannerBatchFilter')?.value || 'ALL';
            const status = document.getElementById('labPlannerStatusFilter')?.value || 'ALL';

            const rows = document.querySelectorAll('#lab-subcontent-planner tr.lab-planner-row');
            let visibleCount = 0;

            rows.forEach(tr => {
                const rowCo = tr.getAttribute('data-co') || '';
                const rowBatch = tr.getAttribute('data-batch') || '';
                const rowStatus = tr.getAttribute('data-status') || '';
                const topic = (tr.querySelector('textarea[id^="lp-topic-"]')?.value || '').toLowerCase();
                const sessionText = (tr.querySelector('td:first-child')?.innerText || '').toLowerCase();
                const remarks = (tr.querySelector('input[id^="lp-remarks-"]')?.value || '').toLowerCase();
                const pedagogy = (tr.querySelector('select[id^="lp-pedagogy-"]')?.value || '').toLowerCase();

                let matchSearch = !search || topic.includes(search) || sessionText.includes(search) || remarks.includes(search) || pedagogy.includes(search) || rowCo.toLowerCase().includes(search) || rowBatch.toLowerCase().includes(search);
                let matchCo = (co === 'ALL') || (rowCo === co);
                let matchBatch = (batch === 'ALL') || (rowBatch === batch);
                let matchStatus = (status === 'ALL') || (rowStatus === status);

                if (matchSearch && matchCo && matchBatch && matchStatus) {
                    tr.classList.remove('hidden');
                    visibleCount++;
                } else {
                    tr.classList.add('hidden');
                }
            });

            const countEl = document.getElementById('labPlannerCount');
            if (countEl) {
                countEl.innerText = `Showing ${visibleCount} of ${rows.length} lab sessions`;
            }
        }

        function onLabActualDateChange(planId, value) {
            const tr = document.getElementById('lp-row-' + planId);
            const pill = document.getElementById('lp-status-pill-' + planId);
            const isCompleted = Boolean(value && value.trim() !== '');

            if (tr) {
                tr.setAttribute('data-status', isCompleted ? 'Completed' : 'Pending');
                if (isCompleted) {
                    tr.classList.add('bg-emerald-50/15');
                } else {
                    tr.classList.remove('bg-emerald-50/15');
                }
            }

            if (pill) {
                if (isCompleted) {
                    pill.className = "px-2.5 py-1 rounded-full text-xs font-bold border shadow-2xs bg-emerald-50 text-emerald-700 border-emerald-200/80";
                    pill.innerText = 'Completed';
                } else {
                    pill.className = "px-2.5 py-1 rounded-full text-xs font-bold border shadow-2xs bg-amber-50 text-amber-700 border-amber-200/80";
                    pill.innerText = 'Pending';
                }
            }

            updateLabMetrics();
        }

        function onLabBatchChange(planId, value) {
            const tr = document.getElementById('lp-row-' + planId);
            if (tr) {
                tr.setAttribute('data-batch', value);
            }
            filterLabPlannerRows();
        }

        function updateLabMetrics() {
            const rows = document.querySelectorAll('#lab-subcontent-planner tr.lab-planner-row');
            let completedBlocks = 0;

            rows.forEach(tr => {
                const actInput = tr.querySelector('input[id^="lp-act-"]');
                if (actInput && actInput.value && actInput.value.trim() !== '') {
                    completedBlocks++;
                }
            });

            const totalSessions = 15;
            const totalHours = 45;
            const completedHours = completedBlocks * 3;
            const remainingHours = Math.max(0, totalHours - completedHours);
            const remainingBlocks = Math.max(0, totalSessions - completedBlocks);
            const coverage = Math.round((completedHours / totalHours) * 100);

            const completedEl = document.getElementById('labMetricCompleted');
            const completedSubEl = document.getElementById('labMetricCompletedSub');
            const remainingEl = document.getElementById('labMetricRemaining');
            const remainingSubEl = document.getElementById('labMetricRemainingSub');
            const coverageEl = document.getElementById('labMetricCoverage');
            const coverageBadge = document.getElementById('labMetricCoverageBadge');
            const progressBar = document.getElementById('labMetricProgressBar');

            if (completedEl) completedEl.innerHTML = `${completedHours} <span class="text-xs font-bold text-emerald-600/70">Hrs</span>`;
            if (completedSubEl) completedSubEl.innerText = `${completedBlocks} of ${totalSessions} sessions completed`;
            if (remainingEl) remainingEl.innerHTML = `${remainingHours} <span class="text-xs font-bold text-slate-500">Hrs</span>`;
            if (remainingSubEl) remainingSubEl.innerText = `${remainingBlocks} sessions pending`;
            if (coverageEl) coverageEl.innerText = `${coverage}%`;
            if (coverageBadge) coverageBadge.innerText = `${coverage}%`;
            if (progressBar) progressBar.style.width = `${coverage}%`;
        }

        function saveAllLessonPlans() {
            const rows = document.querySelectorAll('tr[id^="lp-row-"]');
            const plans = [];

            rows.forEach(tr => {
                const planId = tr.getAttribute('data-plan-id');
                if (!planId) return;

                const blockIdsAttr = tr.getAttribute('data-block-ids');
                const targetIds = blockIdsAttr ? blockIdsAttr.split(',') : [planId];

                const pedagogy = document.getElementById('lp-pedagogy-' + planId)?.value || 'Lecture (L)';
                const propDate = document.getElementById('lp-prop-' + planId)?.value || '';
                const actDate = document.getElementById('lp-act-' + planId)?.value || '';
                const topic = document.getElementById('lp-topic-' + planId)?.value || '';
                const coId = document.getElementById('lp-co-' + planId)?.value || 'CO1';
                const batch = document.getElementById('lp-batch-' + planId)?.value || '';
                const remarks = document.getElementById('lp-remarks-' + planId)?.value || '';

                targetIds.forEach(id => {
                    plans.push({
                        id: id,
                        pedagogy: pedagogy,
                        proposed_date: propDate,
                        actual_date: actDate,
                        topic_content: topic,
                        co_id: coId,
                        sub_batch: batch,
                        remarks: remarks
                    });
                });
            });

            const buttons = [
                document.getElementById('btnSaveTheoryPlanner'),
                document.getElementById('btnSaveLabPlanner')
            ].filter(Boolean);

            const originalBtnHtmls = buttons.map(b => b.innerHTML);
            buttons.forEach(b => {
                b.disabled = true;
                b.innerHTML = `<svg class="w-3.5 h-3.5 animate-spin text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> <span>Saving...</span>`;
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
                buttons.forEach((b, idx) => {
                    b.disabled = false;
                    b.innerHTML = originalBtnHtmls[idx];
                });
                if (data.status === 'SUCCESS') {
                    Swal.fire('Saved Successfully!', data.message, 'success');
                    updateTheoryMetrics();
                    updateLabMetrics();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => {
                buttons.forEach((b, idx) => {
                    b.disabled = false;
                    b.innerHTML = originalBtnHtmls[idx];
                });
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
                select.className = "w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-sm font-medium transition-all outline-none cursor-pointer " + 
                    (isLab ? "text-emerald-700" : (val.includes('Series') ? "text-purple-700" : "text-blue-700"));
            }

            if (hoursTd) {
                if (isLab) {
                    hoursTd.innerHTML = `<span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-xs font-bold">3 Hours</span>`;
                } else {
                    hoursTd.innerHTML = `<span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200/80 text-xs font-bold">1 Hour</span>`;
                }
            }

            if (isLab) {
                batchTd.innerHTML = `
                    <select id="lp-batch-${planId}" onchange="onLabBatchChange(${planId}, this.value)" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 rounded-xl px-2.5 py-2 font-bold text-xs text-emerald-700 outline-none cursor-pointer">
                        <option value="Batch A & B" selected>Batch A & B (Combined)</option>
                        <option value="Batch A">Batch A</option>
                        <option value="Batch B">Batch B</option>
                    </select>
                `;
            } else {
                batchTd.innerHTML = `
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-bold text-xs border border-slate-200/80 inline-block">
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

    let _currentSeries = '', _currentCo = '', _currentPattern = QP_PATTERN, _draftQp = {};
 
    async function openQpPreviewModal(seriesNo, coTag, mode) {
        _currentSeries = seriesNo;
        _currentCo     = coTag;
        _activeQpTab   = 'qp';
        switchQpEditorTab('qp');
        const statusEl = document.getElementById('qp-gen-status');
        statusEl.classList.remove('hidden');
        statusEl.style.color = '#94a3b8';
 
        const isPractical = seriesNo.indexOf('Practical') !== -1;
        _currentPattern = isPractical ? 'practical_series' : QP_PATTERN;
 
        const modal = document.getElementById('qp-preview-modal');
        modal.classList.remove('hidden');
        document.getElementById('qp-modal-title').textContent = `Series Exam QP — ${seriesNo} (${coTag}) | ${_currentPattern === 'practical_series' ? 'Practical Rubrics (Table 3.1)' : (_currentPattern === 'table_4_2_design' ? 'Table 4.2 Design' : 'Table 4.1 Standard')}`;
 
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
                    _currentPattern = data.pattern_type;
                    statusEl.innerHTML = `<span style="color:#4ade80">${data.message}</span>`;
                    renderQpEditor(_draftQp, _currentPattern);
                } else {
                    document.getElementById('qp-editor-body').innerHTML = `<div class="text-red-400 p-6">${data.message}</div>`;
                }
            } catch(e) {
                document.getElementById('qp-editor-body').innerHTML = `<div class="text-red-400 p-6">Network Error: ${e.message}</div>`;
            }
        } else {
            // Manual entry — blank template
            statusEl.innerHTML = `✏ Manual mode — fill in questions for <strong>${seriesNo}</strong>`;
            _draftQp = buildEmptyQpTemplate(_currentPattern, coTag);
            renderQpEditor(_draftQp, _currentPattern);
        }
    }
 
    function buildEmptyQpTemplate(pattern, coTag) {
        if (pattern === 'practical_series') {
            return {
                part_a: [
                    {q_no:'1', text:'Perform identification, testing, and troubleshooting of electronic components.', marks:40, co:coTag, bloom:'Apply', choice_group:'Answer any ONE', scheme_key:'1. Writeup & Procedure: 10 Marks\n2. Setup & Execution: 10 Marks\n3. Observation & Result: 10 Marks\n4. Viva Voce: 5 Marks\n5. Record Completion: 5 Marks', answer_key:'Expected components list, test procedure and values.'},
                    {q_no:'2', text:'Construct and test the given resistor/diode circuit on breadboard and verify output.', marks:40, co:coTag, bloom:'Apply', choice_group:'Answer any ONE', scheme_key:'1. Writeup & Procedure: 10 Marks\n2. Setup & Execution: 10 Marks\n3. Observation & Result: 10 Marks\n4. Viva Voce: 5 Marks\n5. Record Completion: 5 Marks', answer_key:'Expected schematic connections and measured readings.'}
                ]
            };
        } else if (pattern === 'table_4_2_design') {
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
 
    let _activeQpTab = 'qp';
    function switchQpEditorTab(tab) {
        _activeQpTab = tab;
        ['qp', 'scheme', 'key'].forEach(t => {
            const el = document.getElementById('qp-editor-tab-' + t);
            if (el) {
                if (t === tab) el.classList.remove('hidden');
                else el.classList.add('hidden');
            }
            const btn = document.getElementById('qp-edit-btn-' + t);
            if (btn) {
                btn.className = t === tab
                    ? "px-4 py-2 text-xs font-bold rounded-lg bg-indigo-600 text-white transition-all"
                    : "px-4 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-750 text-slate-700 transition-all";
            }
        });
    }
 
    function syncQpQuestionTexts(partKey, idx, val) {
        const schemeText = document.getElementById(`scheme-qtxt-${partKey}-${idx}`);
        const keyText = document.getElementById(`key-qtxt-${partKey}-${idx}`);
        if (schemeText) schemeText.innerText = val;
        if (keyText) keyText.innerText = val;
    }
 
    function syncQpQuestionMarks(partKey, idx, val) {
        const schemeMarks = document.getElementById(`scheme-qmarks-${partKey}-${idx}`);
        const keyMarks = document.getElementById(`key-qmarks-${partKey}-${idx}`);
        if (schemeMarks) schemeMarks.innerText = val + 'M';
        if (keyMarks) keyMarks.innerText = val + 'M';
    }
 
    function renderQpEditor(qpData, pattern) {
        const container = document.getElementById('qp-editor-body');
        const parts = pattern === 'practical_series'
            ? [['part_a', 'PART A — Practical Tasks (Answer any ONE task - 40 Marks)', '40']]
            : (pattern === 'table_4_2_design'
                ? [['part_a','PART A — Answer ALL (6 × 5M = 30M)','5'],['part_b','PART B — Answer ONE per Set (10M each)','10']]
                : [['part_a','PART A — Answer ALL (2 × 1M = 2M)','1'],['part_b','PART B — Answer ALL (3 × 3M = 9M)','3'],['part_c','PART C — Answer ANY 2 of 3 (7M each = 14M)','7']]);
 
        let htmlQp = '';
        let htmlScheme = '';
        let htmlKey = '';

        for (const [partKey, partLabel, defaultMark] of parts) {
            const rows = qpData[partKey] || [];
            
            // 1. Question Paper Tab
            htmlQp += `<div class="mb-4">
                <div class="flex items-center justify-between bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2 rounded-t-xl border-t border-x border-slate-200">
                    <span class="font-bold text-indigo-300 text-sm">${partLabel}</span>
                    <button onclick="addQpRow('${partKey}','${defaultMark}')" class="text-xs px-2.5 py-1 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition-all">+ Add Question</button>
                </div>
                <div class="border border-slate-200 rounded-b-xl overflow-hidden bg-slate-50">
                    <table class="w-full text-sm" id="tbl-qp-${partKey}">
                        <thead class="bg-slate-850 text-slate-400 text-xs">
                            <tr>
                                <th class="p-2 w-14 text-center">Q.No</th>
                                <th class="p-2">Question Text</th>
                                <th class="p-2 w-28 text-center">Bloom (BT)</th>
                                <th class="p-2 w-14 text-center">Marks</th>
                                <th class="p-2 w-28 text-center">Choice Group</th>
                                <th class="p-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody>`;
            rows.forEach((q, idx) => {
                htmlQp += `<tr class="border-b border-slate-200 hover:bg-white border border-slate-200 text-slate-700 hover:bg-slate-50/40" data-part="${partKey}" data-idx="${idx}">
                    <td class="p-2"><input type="text" value="${q.q_no||''}" onchange="updateQpField('${partKey}',${idx},'q_no',this.value)" class="w-full bg-white border border-slate-200 rounded px-1.5 py-1 text-xs text-white font-mono text-center"></td>
                    <td class="p-2"><textarea rows="3" onchange="updateQpField('${partKey}',${idx},'text',this.value); syncQpQuestionTexts('${partKey}',${idx},this.value)" class="w-full bg-white border border-slate-200 rounded px-1.5 py-1 text-xs text-white resize-y" placeholder="Type question here…">${q.text||''}</textarea></td>
                    <td class="p-2"><select onchange="updateQpField('${partKey}',${idx},'bloom',this.value)" class="w-full bg-white border border-slate-200 rounded px-1.5 py-1 text-xs text-white">
                        ${['Remember','Understand','Apply','Analyze','Evaluate','Create'].map(l=>`<option ${q.bloom===l?'selected':''}>${l}</option>`).join('')}
                    </select></td>
                    <td class="p-2"><input type="number" min="1" max="30" value="${q.marks||defaultMark}" onchange="updateQpField('${partKey}',${idx},'marks',parseInt(this.value)); syncQpQuestionMarks('${partKey}',${idx},parseInt(this.value))" class="w-full bg-white border border-slate-200 rounded px-1.5 py-1 text-xs text-amber-300 font-bold text-center"></td>
                    <td class="p-2"><input type="text" value="${q.choice_group||''}" placeholder="e.g. Set A" onchange="updateQpField('${partKey}',${idx},'choice_group',this.value)" class="w-full bg-white border border-slate-200 rounded px-1.5 py-1 text-xs text-purple-300"></td>
                    <td class="p-2 text-center"><button onclick="removeQpRow('${partKey}',${idx})" class="text-red-400 hover:text-red-300 text-xs font-bold">✕</button></td>
                </tr>`;
            });
            htmlQp += `</tbody></table></div></div>`;

            // 2. Evaluation Scheme Tab
            htmlScheme += `<div class="mb-4">
                <div class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2 rounded-t-xl border-t border-x border-slate-200">
                    <span class="font-bold text-emerald-600 text-sm">${partLabel} — Scheme</span>
                </div>
                <div class="border border-slate-200 rounded-b-xl overflow-hidden bg-slate-50">
                    <table class="w-full text-sm" id="tbl-scheme-${partKey}">
                        <thead class="bg-slate-850 text-slate-400 text-xs">
                            <tr>
                                <th class="p-2 w-14 text-center">Q.No</th>
                                <th class="p-2 w-1/2">Question Text</th>
                                <th class="p-2 w-1/2">Evaluation Scheme (Key Points / Mark Split)</th>
                                <th class="p-2 w-14 text-center">Marks</th>
                            </tr>
                        </thead>
                        <tbody>`;
            rows.forEach((q, idx) => {
                htmlScheme += `<tr class="border-b border-slate-200 hover:bg-white border border-slate-200 text-slate-700 hover:bg-slate-50/40">
                    <td class="p-2 text-center text-slate-400 font-mono text-xs">${q.q_no||''}</td>
                    <td class="p-2 text-xs text-slate-700 bg-white/20 max-w-xs truncate" id="scheme-qtxt-${partKey}-${idx}">${q.text||''}</td>
                    <td class="p-2"><textarea rows="3" onchange="updateQpField('${partKey}',${idx},'scheme_key',this.value)" class="w-full bg-white border border-slate-200 rounded px-1.5 py-1 text-xs text-emerald-300 resize-y" placeholder="Marking scheme guidelines…">${q.scheme_key||''}</textarea></td>
                    <td class="p-2 text-center text-amber-300 font-bold text-xs" id="scheme-qmarks-${partKey}-${idx}">${q.marks||defaultMark}M</td>
                </tr>`;
            });
            htmlScheme += `</tbody></table></div></div>`;

            // 3. Answer Key Tab
            htmlKey += `<div class="mb-4">
                <div class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2 rounded-t-xl border-t border-x border-slate-200">
                    <span class="font-bold text-blue-700 text-sm">${partLabel} — Answer Key</span>
                </div>
                <div class="border border-slate-200 rounded-b-xl overflow-hidden bg-slate-50">
                    <table class="w-full text-sm" id="tbl-key-${partKey}">
                        <thead class="bg-slate-850 text-slate-400 text-xs">
                            <tr>
                                <th class="p-2 w-14 text-center">Q.No</th>
                                <th class="p-2 w-1/2">Question Text</th>
                                <th class="p-2 w-1/2">Model Answer / Key Details</th>
                                <th class="p-2 w-14 text-center">Marks</th>
                            </tr>
                        </thead>
                        <tbody>`;
            rows.forEach((q, idx) => {
                htmlKey += `<tr class="border-b border-slate-200 hover:bg-white border border-slate-200 text-slate-700 hover:bg-slate-50/40">
                    <td class="p-2 text-center text-slate-400 font-mono text-xs">${q.q_no||''}</td>
                    <td class="p-2 text-xs text-slate-700 bg-white/20 max-w-xs truncate" id="key-qtxt-${partKey}-${idx}">${q.text||''}</td>
                    <td class="p-2"><textarea rows="3" onchange="updateQpField('${partKey}',${idx},'answer_key',this.value)" class="w-full bg-white border border-slate-200 rounded px-1.5 py-1 text-xs text-blue-300 resize-y" placeholder="Model answer text…">${q.answer_key||''}</textarea></td>
                    <td class="p-2 text-center text-amber-300 font-bold text-xs" id="key-qmarks-${partKey}-${idx}">${q.marks||defaultMark}M</td>
                </tr>`;
            });
            htmlKey += `</tbody></table></div></div>`;
        }

        container.innerHTML = `
            <div id="qp-editor-tab-qp" class="${_activeQpTab === 'qp' ? '' : 'hidden'}">${htmlQp}</div>
            <div id="qp-editor-tab-scheme" class="${_activeQpTab === 'scheme' ? '' : 'hidden'}">${htmlScheme}</div>
            <div id="qp-editor-tab-key" class="${_activeQpTab === 'key' ? '' : 'hidden'}">${htmlKey}</div>
        `;
    }

    function updateQpField(part, idx, field, value) {
        if (!_draftQp[part]) return;
        _draftQp[part][idx][field] = value;
    }

    function addQpRow(partKey, defaultMark) {
        if (!_draftQp[partKey]) _draftQp[partKey] = [];
        const idx = _draftQp[partKey].length + 1;
        _draftQp[partKey].push({q_no: String(idx), text: '', marks: parseInt(defaultMark), co: _currentCo, bloom: 'Understand', scheme_key: '', answer_key: ''});
        renderQpEditor(_draftQp, _currentPattern);
    }
 
    function removeQpRow(partKey, idx) {
        if (!_draftQp[partKey]) return;
        _draftQp[partKey].splice(idx, 1);
        renderQpEditor(_draftQp, _currentPattern);
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
                    pattern_type: _currentPattern,
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

    // =====================================================================
    // ESE Theory Grade Modal System
    // =====================================================================
    const eseGradesState = {};
    studentsList.forEach(s => {
        eseGradesState[s.reg_no] = {
            ese_theory_grade: s.ese_theory_grade === '-' ? '' : s.ese_theory_grade,
            theory_absent: (s.ese_theory_grade === 'FE')
        };
    });

    function openEseTheoryModal() {
        document.getElementById('ese-theory-modal').classList.remove('hidden');
        const sel = document.getElementById('ese-student-select');
        if (sel && sel.value) {
            loadEseStudent(sel.value);
        }
    }

    function closeEseTheoryModal() {
        document.getElementById('ese-theory-modal').classList.add('hidden');
    }

    function loadEseStudent(regNo) {
        const student = studentsList.find(s => s.reg_no === regNo);
        if (!student) return;

        document.getElementById('ese-student-reg').innerText = student.reg_no;
        document.getElementById('ese-student-name').innerText = student.name;

        const state = eseGradesState[regNo] || { ese_theory_grade: '', theory_absent: false };
        const gradeSelect = document.getElementById('ese-grade-select');
        const absentCheck = document.getElementById('ese-absent-check');

        gradeSelect.value = state.ese_theory_grade;
        absentCheck.checked = state.theory_absent;
        gradeSelect.disabled = state.theory_absent;

        updateEseLiveDisplay(state.ese_theory_grade);
    }

    function onEseGradeChange(grade) {
        const sel = document.getElementById('ese-student-select');
        const regNo = sel.value;
        if (!regNo) return;

        if (!eseGradesState[regNo]) eseGradesState[regNo] = {};
        eseGradesState[regNo].ese_theory_grade = grade;
        eseGradesState[regNo].theory_absent = (grade === 'FE');

        document.getElementById('ese-absent-check').checked = (grade === 'FE');
        updateEseLiveDisplay(grade);
    }

    function toggleEseAbsent(isAbsent) {
        const sel = document.getElementById('ese-student-select');
        const regNo = sel.value;
        if (!regNo) return;

        if (!eseGradesState[regNo]) eseGradesState[regNo] = {};
        eseGradesState[regNo].theory_absent = isAbsent;

        const gradeSelect = document.getElementById('ese-grade-select');
        if (isAbsent) {
            eseGradesState[regNo].ese_theory_grade = 'FE';
            gradeSelect.value = 'FE';
            gradeSelect.disabled = true;
            updateEseLiveDisplay('FE');
        } else {
            eseGradesState[regNo].ese_theory_grade = '';
            gradeSelect.value = '';
            gradeSelect.disabled = false;
            updateEseLiveDisplay('');
        }
    }

    function updateEseLiveDisplay(grade) {
        const score = convertGradeToScore(grade);
        document.getElementById('ese-mapped-score').innerText = `${score.toFixed(2)} / 60.00`;

        const isPass = (score >= 24.0 || ['S','A','B','C','D','P'].includes(String(grade).toUpperCase().trim()));
        const statusEl = document.getElementById('ese-pass-status');
        if (isPass) {
            statusEl.innerText = 'PASSED';
            statusEl.className = 'font-bold text-emerald-600';
        } else {
            statusEl.innerText = grade ? 'REAPPEAR' : '-';
            statusEl.className = 'font-bold text-rose-600';
        }
    }

    function convertGradeToScore(grade) {
        switch (String(grade).toUpperCase().trim()) {
            case 'S': return 57.0;
            case 'A': return 51.0;
            case 'B': return 45.0;
            case 'C': return 39.0;
            case 'D': return 33.0;
            case 'P': return 27.0;
            default: return 0.0;
        }
    }

    function prevEseStudent() {
        const sel = document.getElementById('ese-student-select');
        if (!sel || sel.selectedIndex <= 0) return;
        sel.selectedIndex--;
        loadEseStudent(sel.value);
    }

    function nextEseStudent() {
        const sel = document.getElementById('ese-student-select');
        if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
        sel.selectedIndex++;
        loadEseStudent(sel.value);
    }

    function saveAndNextEseStudent() {
        nextEseStudent();
    }

    function saveAllEseGrades() {
        const marksData = [];
        Object.keys(eseGradesState).forEach(regNo => {
            const student = studentsList.find(s => s.reg_no === regNo);
            marksData.push({
                reg_no: regNo,
                ese_theory_grade: eseGradesState[regNo].ese_theory_grade,
                theory_absent: eseGradesState[regNo].theory_absent,
                practical_absent: false,
                ese_practical_marks: parseFloat(student ? (student.ese_practical || 0) : 0)
            });
        });

        Swal.fire({
            title: 'Saving ESE Grades...',
            text: 'Updating board theory grades for all students',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/ese', {
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
                closeEseTheoryModal();
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

    // =====================================================================
    // Theory Series Exam Marks Modal System
    // =====================================================================
    const seriesTheoryEvalsDb = @json($seriesTheoryEvals);
    const seriesTheoryEvalsState = {};

    studentsList.forEach(s => {
        const regNo = s.reg_no;
        seriesTheoryEvalsState[regNo] = {
            'Series 1': { total_score_50: 0, is_absent: false },
            'Series 2': { total_score_50: 0, is_absent: false },
            'Series 3': { total_score_50: 0, is_absent: false },
            'Series 4': { total_score_50: 0, is_absent: false }
        };

        const dbList = seriesTheoryEvalsDb[regNo] || [];
        dbList.forEach(evalRecord => {
            const sNo = evalRecord.series_no;
            let mappedSeries = sNo;
            if (sNo === 'CO1') mappedSeries = 'Series 1';
            if (sNo === 'CO2') mappedSeries = 'Series 2';
            if (sNo === 'CO3') mappedSeries = 'Series 3';
            if (sNo === 'CO4') mappedSeries = 'Series 4';

            if (seriesTheoryEvalsState[regNo][mappedSeries]) {
                seriesTheoryEvalsState[regNo][mappedSeries] = {
                    total_score_50: parseFloat(evalRecord.total_score_50) || 0,
                    is_absent: !!evalRecord.is_absent
                };
            }
        });
    });

    function openSeriesTheoryModal() {
        document.getElementById('series-theory-modal').classList.remove('hidden');
        const sel = document.getElementById('series-theory-student-select');
        if (sel && sel.value) {
            loadSeriesTheoryStudent(sel.value);
        }
    }

    function closeSeriesTheoryModal() {
        document.getElementById('series-theory-modal').classList.add('hidden');
    }

    function onSeriesTheoryTestChange(test) {
        const sel = document.getElementById('series-theory-student-select');
        if (sel && sel.value) {
            loadSeriesTheoryStudent(sel.value);
        }
    }

    function loadSeriesTheoryStudent(regNo) {
        const student = studentsList.find(s => s.reg_no === regNo);
        if (!student) return;

        document.getElementById('series-theory-student-display').innerText = `${student.name} (${student.reg_no})`;

        const test = document.getElementById('series-theory-test-select').value;
        const state = seriesTheoryEvalsState[regNo][test] || { total_score_50: 0, is_absent: false };

        const totalInput = document.getElementById('series-theory-total');
        const absentCheck = document.getElementById('series-theory-absent');

        totalInput.value = state.total_score_50;
        absentCheck.checked = state.is_absent;

        totalInput.disabled = state.is_absent;

        updateSeriesTheoryLiveTotal();
    }

    function onSeriesTheoryMarksInput() {
        const sel = document.getElementById('series-theory-student-select');
        const regNo = sel.value;
        if (!regNo) return;

        const test = document.getElementById('series-theory-test-select').value;
        const total = parseFloat(document.getElementById('series-theory-total').value) || 0;

        seriesTheoryEvalsState[regNo][test].total_score_50 = total;

        updateSeriesTheoryLiveTotal();
    }

    function toggleSeriesTheoryAbsent(isAbsent) {
        const sel = document.getElementById('series-theory-student-select');
        const regNo = sel.value;
        if (!regNo) return;

        const test = document.getElementById('series-theory-test-select').value;
        seriesTheoryEvalsState[regNo][test].is_absent = isAbsent;

        const totalInput = document.getElementById('series-theory-total');

        if (isAbsent) {
            totalInput.value = 0;
            totalInput.disabled = true;
            seriesTheoryEvalsState[regNo][test].total_score_50 = 0;
        } else {
            totalInput.disabled = false;
        }
        updateSeriesTheoryLiveTotal();
    }

    function updateSeriesTheoryLiveTotal() {
        const total = parseFloat(document.getElementById('series-theory-total').value) || 0;
        const isAbsent = document.getElementById('series-theory-absent').checked;

        const displayTotal = isAbsent ? 0 : total;
        document.getElementById('series-theory-live-total').innerText = `${displayTotal.toFixed(2)} / 50.00`;
    }

    function prevSeriesTheoryStudent() {
        const sel = document.getElementById('series-theory-student-select');
        if (!sel || sel.selectedIndex <= 0) return;
        sel.selectedIndex--;
        loadSeriesTheoryStudent(sel.value);
    }

    function nextSeriesTheoryStudent() {
        const sel = document.getElementById('series-theory-student-select');
        if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
        sel.selectedIndex++;
        loadSeriesTheoryStudent(sel.value);
    }

    function saveAndNextSeriesTheoryStudent() {
        nextSeriesTheoryStudent();
    }

    function saveAllSeriesTheoryMarks() {
        const test = document.getElementById('series-theory-test-select').value;
        const marksData = [];

        Object.keys(seriesTheoryEvalsState).forEach(regNo => {
            const state = seriesTheoryEvalsState[regNo][test];
            marksData.push({
                reg_no: regNo,
                total_score_50: state.total_score_50,
                is_absent: state.is_absent
            });
        });

        Swal.fire({
            title: 'Saving Series Marks...',
            text: `Updating scores for ${test}`,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/series-theory', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ series_no: test, marks_data: marksData })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                closeSeriesTheoryModal();
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

    document.addEventListener('DOMContentLoaded', () => {
        const savedMode = localStorage.getItem('active_mode');
        const savedTheoryTab = localStorage.getItem('active_theory_subtab');
        const savedLabTab = localStorage.getItem('active_lab_subtab');

        if (savedMode) {
            switchMode(savedMode);
        }
        if (savedTheoryTab) {
            switchTheorySubtab(savedTheoryTab);
        }
        if (savedLabTab) {
            switchLabSubtab(savedLabTab);
        }
    });
    </script>

    <!-- ================================================================
         QP Preview / Edit Modal (Unified Columns Layout)
    ================================================================= -->
    <div id="qp-preview-modal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-start justify-center p-4 overflow-auto">
        <div class="w-full max-w-[98%] bg-white rounded-2xl shadow-2xl border border-slate-200 flex flex-col" style="max-height:95vh">

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-t-2xl">
                <div>
                    <h2 class="text-lg font-bold text-white" id="qp-modal-title">Series QP Preview</h2>
                    <p class="text-slate-400 text-xs mt-0.5">Edit questions, marking schemes, and model answers side-by-side — then Save to Question Bank</p>
                </div>
                <button onclick="closeQpModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none">&times;</button>
            </div>

            <!-- Tab Switcher Bar -->
            <div class="flex border-b border-slate-200 bg-white/90 px-6 py-2 gap-2 flex-shrink-0">
                <button type="button" onclick="switchQpEditorTab('qp')" id="qp-edit-btn-qp" class="px-4 py-2 text-xs font-bold rounded-lg bg-indigo-600 text-white transition-all">📝 1. Edit Questions (QP)</button>
                <button type="button" onclick="switchQpEditorTab('scheme')" id="qp-edit-btn-scheme" class="px-4 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-750 text-slate-700 transition-all">📋 2. Edit Evaluation Scheme</button>
                <button type="button" onclick="switchQpEditorTab('key')" id="qp-edit-btn-key" class="px-4 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-750 text-slate-700 transition-all">🔑 3. Edit Model Answer Key</button>
            </div>

            <!-- Editor Body -->
            <div id="qp-editor-body" class="flex-1 overflow-y-auto p-6 space-y-2">
                <div class="text-slate-500 text-sm text-center py-12">Loading…</div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-200 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-b-2xl">
                <button onclick="closeQpModal()" class="px-5 py-2.5 rounded-lg bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-semibold text-sm shadow-xs">Cancel</button>
                <div class="flex items-center gap-3">
                    <span class="text-slate-500 text-xs">Questions, schemes, and model answers are saved together in one step</span>
                    <button id="qp-save-btn" onclick="saveQpFromModal()" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-lg transition-all">
                        💾 Save &amp; Add to Question Bank
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================
         Enter Theory ESE Grades Modal
    ================================================================= -->
    <div id="ese-theory-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-5">
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm max-w-2xl w-full p-5 rounded-2xl border border-slate-200 shadow-2xl space-y-4 max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-200 pb-3 flex-shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-white">End Semester Exam (ESE) Theory Grade Evaluator</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Select ESE Grade for each student. Mapped score and status will update automatically.</p>
                </div>
                <button onclick="closeEseTheoryModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none">&times;</button>
            </div>

            <!-- Student Selection & Stepper Bar -->
            <div class="bg-white/90 p-3 rounded-xl border border-slate-200 flex items-center justify-between gap-2 flex-shrink-0">
                <button type="button" onclick="prevEseStudent()" class="header-btn px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 font-bold text-xs flex items-center space-x-1">
                    <span>◀ Prev</span>
                </button>

                <div class="flex-1 max-w-md">
                    <select id="ese-student-select" onchange="loadEseStudent(this.value)" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 font-bold text-xs text-white outline-none focus:border-emerald-500">
                        @foreach($studentResults as $idx => $res)
                        <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }} (SBTE: {{ $res['sbte_reg_no'] ?: $res['reg_no'] }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" onclick="nextEseStudent()" class="header-btn px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 font-bold text-xs flex items-center space-x-1">
                    <span>Next ▶</span>
                </button>
            </div>

            <!-- Grading Content Card -->
            <div class="p-4 rounded-xl bg-white/90 border border-slate-200 space-y-4 flex-1 overflow-y-auto">
                <div class="flex items-center justify-between">
                    <span class="text-slate-400 text-xs font-semibold">Reg No:</span>
                    <span id="ese-student-reg" class="font-mono text-xs font-bold text-slate-800"></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400 text-xs font-semibold">Student Name:</span>
                    <span id="ese-student-name" class="text-xs font-bold text-white"></span>
                </div>

                <div class="border-t border-slate-200/80 pt-3 space-y-3">
                    <div>
                        <label class="block text-slate-400 text-xs font-semibold mb-1">Theory ESE Grade:</label>
                        <select id="ese-grade-select" onchange="onEseGradeChange(this.value)" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 font-bold text-sm text-amber-600 outline-none">
                            <option value="">- Select Grade -</option>
                            <option value="S">S (90% - 100%)</option>
                            <option value="A">A (80% - 89%)</option>
                            <option value="B">B (70% - 79%)</option>
                            <option value="C">C (60% - 69%)</option>
                            <option value="D">D (50% - 59%)</option>
                            <option value="P">P (40% - 49% - Pass)</option>
                            <option value="F">F (Fail)</option>
                            <option value="FE">FE (Absent / Shortage)</option>
                            <option value="I">I (Incomplete)</option>
                        </select>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="ese-absent-check" onchange="toggleEseAbsent(this.checked)" class="rounded bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 border-slate-200 text-rose-500 focus:ring-0">
                        <label for="ese-absent-check" class="text-xs text-slate-700 font-semibold cursor-pointer">Mark Student as Absent (Grade FE)</label>
                    </div>
                </div>

                <!-- Live Conversion Display -->
                <div class="bg-white p-3 rounded-xl border border-slate-200 space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Equivalent Marks:</span>
                        <span id="ese-mapped-score" class="font-bold text-indigo-600">0.00 / 60.00</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Theory Pass Status:</span>
                        <span id="ese-pass-status" class="font-bold text-rose-600">REAPPEAR</span>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-200 flex-shrink-0">
                <button type="button" onclick="closeEseTheoryModal()" class="header-btn px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-700 font-semibold text-xs hover:bg-slate-100">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextEseStudent()" class="header-btn px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm">Next Student ▶</button>
                    <button type="button" onclick="saveAllEseGrades()" class="header-btn px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm">Save ESE Grades</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================
         Enter Theory Series Marks Modal
    ================================================================= -->
    <div id="series-theory-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-5">
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm max-w-2xl w-full p-5 rounded-2xl border border-slate-200 shadow-2xl space-y-4 max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-200 pb-3 flex-shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-white">Theory Series Exam Marks Evaluator</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Select a series test and enter Part A, B, and C scores for each student.</p>
                </div>
                <button onclick="closeSeriesTheoryModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none">&times;</button>
            </div>

            <!-- Series Selection & Student Selection Bar -->
            <div class="bg-white/90 p-3 rounded-xl border border-slate-200 space-y-3 flex-shrink-0">
                <div class="flex items-center justify-between gap-3">
                    <label class="text-slate-700 text-xs font-semibold">Select Series Test:</label>
                    <select id="series-theory-test-select" onchange="onSeriesTheoryTestChange(this.value)" class="bg-white border border-slate-200 rounded px-2.5 py-1 text-xs text-amber-600 font-bold outline-none focus:border-amber-500">
                        <option value="Series 1">Test 1 (CO1)</option>
                        <option value="Series 2">Test 2 (CO2)</option>
                        <option value="Series 3">Test 3 (CO3)</option>
                        <option value="Series 4">Test 4 (CO4)</option>
                    </select>
                </div>

                <div class="flex items-center justify-between gap-2">
                    <button type="button" onclick="prevSeriesTheoryStudent()" class="header-btn px-3 py-1.5 rounded bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 font-bold text-xs">
                        <span>◀ Prev</span>
                    </button>

                    <div class="flex-1">
                        <select id="series-theory-student-select" onchange="loadSeriesTheoryStudent(this.value)" class="w-full bg-white border border-slate-200 rounded px-3 py-1.5 font-bold text-xs text-white outline-none focus:border-emerald-500">
                            @foreach($studentResults as $idx => $res)
                            <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="button" onclick="nextSeriesTheoryStudent()" class="header-btn px-3 py-1.5 rounded bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 font-bold text-xs">
                        <span>Next ▶</span>
                    </button>
                </div>
            </div>

            <!-- Marks Form Card -->
            <div class="p-4 rounded-xl bg-white/90 border border-slate-200 space-y-4 flex-1 overflow-y-auto">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-400">Student:</span>
                    <span id="series-theory-student-display" class="font-bold text-white"></span>
                </div>

                <div class="border-t border-slate-200/80 pt-3 space-y-3">
                    <div>
                        <label class="block text-slate-400 text-xs font-semibold mb-1">Total Series Test Mark (Max 50):</label>
                        <input type="number" id="series-theory-total" min="0" max="50" step="0.5" oninput="onSeriesTheoryMarksInput()" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 font-bold text-sm text-white text-center focus:border-emerald-500 outline-none">
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="series-theory-absent" onchange="toggleSeriesTheoryAbsent(this.checked)" class="rounded bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 border-slate-200 text-rose-500 focus:ring-0">
                        <label for="series-theory-absent" class="text-xs text-slate-600 font-semibold cursor-pointer">Mark Student as Absent</label>
                    </div>
                </div>

                <!-- Live Total Display -->
                <div class="bg-white p-3 rounded-xl border border-slate-200 flex justify-between items-center text-xs">
                    <span class="text-slate-400 font-semibold">Total Series Test Score:</span>
                    <span id="series-theory-live-total" class="font-bold text-emerald-600 text-sm">0.00 / 50.00</span>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-200 flex-shrink-0">
                <button type="button" onclick="closeSeriesTheoryModal()" class="header-btn px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-700 font-semibold text-xs hover:bg-slate-100">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextSeriesTheoryStudent()" class="header-btn px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm">Next Student ▶</button>
                    <button type="button" onclick="saveAllSeriesTheoryMarks()" class="header-btn px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================
         Continuous Lab Experiment Evaluation Modal — CampusLynk Light Theme
    ================================================================= -->
    <div id="experiment-eval-modal" class="fixed inset-0 z-50 bg-slate-50 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-5">
        <div class="bg-white max-w-2xl w-full rounded-2xl border border-slate-200 shadow-2xl max-h-[95vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Continuous Lab Work Evaluator (Table 2.2)</h3>
                        <p class="text-slate-500 text-xs mt-0.5">Grade the student on 6 criteria. Total out of 50, scaled to 10 CIA marks.</p>
                    </div>
                </div>
                <button onclick="closeExperimentEvalModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all leading-none">&times;</button>
            </div>

            <!-- Experiment & Student Selection -->
            <div class="bg-slate-50 px-5 py-3 border-b border-slate-100 space-y-2.5 flex-shrink-0">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                    <label class="text-slate-600 text-xs font-bold whitespace-nowrap">Select Experiment:</label>
                    <select id="eval-exp-select" onchange="onEvalExpChange(this.value)" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 font-bold text-xs text-slate-900 outline-none w-full sm:max-w-sm focus:border-purple-500 shadow-2xs cursor-pointer">
                        @foreach(($practicumCourseFile->parsed_experiments ?? []) as $exp)
                        <option value="{{ $exp['experiment_no'] }}">{{ $exp['experiment_no'] }} - {{ $exp['title'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <button type="button" onclick="prevExpStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                        <span>◀ Prev</span>
                    </button>
                    <div class="flex-1">
                        <select id="eval-student-select" onchange="loadExpStudent(this.value)" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-bold text-sm text-slate-900 outline-none focus:border-purple-500 shadow-2xs cursor-pointer">
                            @foreach($studentResults as $idx => $res)
                            <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" onclick="nextExpStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                        <span>Next ▶</span>
                    </button>
                </div>
            </div>

            <!-- Rubrics Form Card -->
            <div class="overflow-y-auto flex-1 px-5 py-4" id="exp-rubrics-container">
                <!-- Javascript will populate criterion rows here -->
            </div>

            <!-- Live Converted Result Display -->
            <div class="bg-slate-50 px-5 py-3 border-t border-slate-100 flex justify-between items-center text-xs flex-shrink-0">
                <div>
                    <span class="text-slate-500 font-semibold block text-xs uppercase tracking-wider">Total Evaluation Score</span>
                    <span id="exp-live-total" class="font-bold text-slate-900 text-base font-mono">0.00 / 50.00 M</span>
                </div>
                <div class="text-right">
                    <span class="text-slate-500 font-semibold block text-xs uppercase tracking-wider">Scaled CIA Marks</span>
                    <span id="exp-live-cia" class="font-black text-emerald-700 text-base px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-200 font-mono">0.00 / 10.00 M</span>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between px-5 py-4 border-t border-slate-100 flex-shrink-0">
                <button type="button" onclick="closeExperimentEvalModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-colors cursor-pointer">Close</button>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="saveAndNextExpStudent()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200 font-bold text-xs transition-colors shadow-2xs cursor-pointer">Next Student ▶</button>
                    <button type="button" onclick="saveAllExpMarks()" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>
 
    <!-- ================================================================
         Practical Series Exam Evaluation Modal — CampusLynk Light Theme
    ================================================================= -->
    <div id="series-practical-modal" class="fixed inset-0 z-50 bg-slate-50 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-5">
        <div class="bg-white max-w-2xl w-full rounded-2xl border border-slate-200 shadow-2xl max-h-[95vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Practical Series Test Marks Evaluator (Table 3.1)</h3>
                        <p class="text-slate-500 text-xs mt-0.5">Grade on 5 practical criteria. Total out of 40, scaled to 10 CIA marks.</p>
                    </div>
                </div>
                <button onclick="closeSeriesPracticalModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all leading-none">&times;</button>
            </div>

            <!-- Series and Student Selection -->
            <div class="bg-slate-50 px-5 py-3 border-b border-slate-100 space-y-2.5 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <label class="text-slate-600 text-xs font-bold whitespace-nowrap">Select Series Test:</label>
                    <select id="series-pr-test-select" onchange="onSeriesPrTestChange(this.value)" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-900 font-bold outline-none focus:border-amber-500 shadow-2xs cursor-pointer">
                        <option value="Series 1">Practical Test 1 (CO1+CO2)</option>
                        <option value="Series 2">Practical Test 2 (CO3+CO4)</option>
                    </select>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <button type="button" onclick="prevSeriesPrStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                        <span>◀ Prev</span>
                    </button>
                    <div class="flex-1">
                        <select id="series-pr-student-select" onchange="loadSeriesPrStudent(this.value)" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-bold text-sm text-slate-900 outline-none focus:border-indigo-500 shadow-2xs cursor-pointer">
                            @foreach($studentResults as $idx => $res)
                            <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" onclick="nextSeriesPrStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                        <span>Next ▶</span>
                    </button>
                </div>
            </div>

            <!-- Rubrics Form Card -->
            <div class="overflow-y-auto flex-1 px-5 py-4" id="series-pr-rubrics-container">
                <!-- Javascript will populate criterion rows here -->
            </div>

            <!-- Live Converted Result Display -->
            <div class="bg-slate-50 px-5 py-3 border-t border-slate-100 flex justify-between items-center text-xs flex-shrink-0">
                <div>
                    <span class="text-slate-500 font-semibold block text-xs uppercase tracking-wider">Total Exam Score</span>
                    <span id="series-pr-live-total" class="font-bold text-slate-900 text-base font-mono">0.00 / 40.00 M</span>
                </div>
                <div class="text-right">
                    <span class="text-slate-500 font-semibold block text-xs uppercase tracking-wider">Scaled CIA Marks</span>
                    <span id="series-pr-live-cia" class="font-black text-indigo-700 text-base px-3 py-1 rounded-lg bg-indigo-50 border border-indigo-200 font-mono">0.00 / 10.00 M</span>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between px-5 py-4 border-t border-slate-100 flex-shrink-0">
                <button type="button" onclick="closeSeriesPracticalModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-colors cursor-pointer">Close</button>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="saveAndNextSeriesPrStudent()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200 font-bold text-xs transition-colors shadow-2xs cursor-pointer">Next Student ▶</button>
                    <button type="button" onclick="saveAllSeriesPrMarks()" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>
 
    <script>
    // =====================================================================
    // Continuous Lab Experiment Evaluation System
    // =====================================================================
    const experimentEvalsDb = @json($experimentEvals);
    const experimentEvalsState = {};
 
    // Initialize state
    studentsList.forEach(s => {
        const regNo = s.reg_no;
        experimentEvalsState[regNo] = {};
        
        // Populate from DB if exists
        const dbList = experimentEvalsDb[regNo] || [];
        dbList.forEach(rec => {
            experimentEvalsState[regNo][rec.experiment_no] = {
                prep_punctuality: parseFloat(rec.prep_punctuality) || 0,
                setup_procedure: parseFloat(rec.setup_procedure) || 0,
                observation_recording: parseFloat(rec.observation_recording) || 0,
                analysis_interpretation: parseFloat(rec.analysis_interpretation) || 0,
                viva_voce: parseFloat(rec.viva_voce) || 0,
                workmanship_discipline: parseFloat(rec.workmanship_discipline) || 0,
                total_score_50: parseFloat(rec.total_score_50) || 0
            };
        });
    });
 
    function openExperimentEvalModal() {
        document.getElementById('experiment-eval-modal').classList.remove('hidden');
        const selectStudent = document.getElementById('eval-student-select');
        if (selectStudent && selectStudent.value) {
            loadExpStudent(selectStudent.value);
        }
    }
 
    function closeExperimentEvalModal() {
        document.getElementById('experiment-eval-modal').classList.add('hidden');
    }
 
    function onEvalExpChange(expNo) {
        const selectStudent = document.getElementById('eval-student-select');
        if (selectStudent && selectStudent.value) {
            loadExpStudent(selectStudent.value);
        }
    }
 
    function loadExpStudent(regNo) {
        const student = studentsList.find(s => s.reg_no === regNo);
        if (!student) return;
 
        const expNo = document.getElementById('eval-exp-select').value;
        if (!expNo) return;
 
        // Ensure state exists for this student/experiment
        if (!experimentEvalsState[regNo][expNo]) {
            experimentEvalsState[regNo][expNo] = {
                prep_punctuality: 0,
                setup_procedure: 0,
                observation_recording: 0,
                analysis_interpretation: 0,
                viva_voce: 0,
                workmanship_discipline: 0,
                total_score_50: 0
            };
        }
 
        const state = experimentEvalsState[regNo][expNo];
        const container = document.getElementById('exp-rubrics-container');
 
        const criteria = [
            { label: '1. Prep & Punctuality', key: 'prep_punctuality', max: 10, step: 0.5 },
            { label: '2. Setup & Procedure', key: 'setup_procedure', max: 10, step: 0.5 },
            { label: '3. Observation & Recording', key: 'observation_recording', max: 5, step: 0.5 },
            { label: '4. Analysis & Interpretation', key: 'analysis_interpretation', max: 10, step: 0.5 },
            { label: '5. Viva Voce', key: 'viva_voce', max: 10, step: 0.5 },
            { label: '6. Workmanship & Discipline', key: 'workmanship_discipline', max: 5, step: 0.5 }
        ];
 
        let html = `
            <div class="mb-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Grading Criteria &mdash; ${student.name}</div>
            <div class="space-y-3">
        `;
 
        criteria.forEach(c => {
            const val = state[c.key] || 0;
            html += `
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex justify-between items-center text-xs font-semibold">
                        <span class="text-slate-700">${c.label} <span class="text-slate-400 font-normal">(Max ${c.max})</span></span>
                        <span class="text-emerald-700 font-mono font-bold text-sm bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200" id="exp-val-badge-${c.key}">${val.toFixed(1)}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="adjustExpVal('${c.key}', -${c.step}, ${c.max})" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 font-black text-slate-700 flex items-center justify-center transition-colors border border-slate-300">-</button>
                        <input type="range" min="0" max="${c.max}" step="${c.step}" value="${val}" id="exp-slider-${c.key}" oninput="syncExpSlider('${c.key}', this.value, ${c.max})" class="flex-1 accent-purple-600 h-2 rounded-lg cursor-pointer">
                        <button type="button" onclick="adjustExpVal('${c.key}', ${c.step}, ${c.max})" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 font-black text-slate-700 flex items-center justify-center transition-colors border border-slate-300">+</button>
                    </div>
                </div>
            `;
        });
 
        html += '</div>';
 
        container.innerHTML = html;
        updateExpLiveDisplay(regNo, expNo);
    }
 
    function syncExpSlider(key, val, max) {
        const num = parseFloat(val) || 0;
        const regNo = document.getElementById('eval-student-select').value;
        const expNo = document.getElementById('eval-exp-select').value;
        if (!regNo || !expNo) return;
 
        experimentEvalsState[regNo][expNo][key] = num;
 
        const badge = document.getElementById(`exp-val-badge-${key}`);
        if (badge) badge.innerText = num.toFixed(1);
 
        updateExpLiveDisplay(regNo, expNo);
    }
 
    function adjustExpVal(key, delta, max) {
        const slider = document.getElementById(`exp-slider-${key}`);
        if (!slider) return;
 
        let current = parseFloat(slider.value) || 0;
        let next = Math.max(0, Math.min(max, current + delta));
        slider.value = next;
        syncExpSlider(key, next, max);
    }
 
    function updateExpLiveDisplay(regNo, expNo) {
        const state = experimentEvalsState[regNo][expNo];
        if (!state) return;
 
        const total = (state.prep_punctuality || 0) +
                      (state.setup_procedure || 0) +
                      (state.observation_recording || 0) +
                      (state.analysis_interpretation || 0) +
                      (state.viva_voce || 0) +
                      (state.workmanship_discipline || 0);
 
        state.total_score_50 = total;
 
        const cia = Math.round(((total / 50.0) * 10.0) * 2) / 2;
 
        document.getElementById('exp-live-total').innerText = `${total.toFixed(1)} / 50.0 M`;
        document.getElementById('exp-live-cia').innerText = `${cia.toFixed(1)} / 10.0 M`;
    }
 
    function prevExpStudent() {
        const sel = document.getElementById('eval-student-select');
        if (!sel || sel.selectedIndex <= 0) return;
        sel.selectedIndex--;
        loadExpStudent(sel.value);
    }
 
    function nextExpStudent() {
        const sel = document.getElementById('eval-student-select');
        if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
        sel.selectedIndex++;
        loadExpStudent(sel.value);
    }
 
    function saveAndNextExpStudent() {
        const sel = document.getElementById('eval-student-select');
        const regNo = sel.value;
        const expNo = document.getElementById('eval-exp-select').value;
        if (!regNo || !expNo) return;
 
        const state = experimentEvalsState[regNo][expNo];
        const bsId = {{ $batchSubject->id }};
 
        fetch(`/api/r26/classroom/practicum/${bsId}/evaluate/experiment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({
                experiment_no: expNo,
                marks_data: [{
                    reg_no: regNo,
                    prep_punctuality: state.prep_punctuality,
                    setup_procedure: state.setup_procedure,
                    observation_recording: state.observation_recording,
                    analysis_interpretation: state.analysis_interpretation,
                    viva_voce: state.viva_voce,
                    workmanship_discipline: state.workmanship_discipline,
                    total_score_50: state.total_score_50
                }]
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                nextExpStudent();
            } else {
                alert('Auto-save error: ' + data.message);
            }
        });
    }
 
    function saveAllExpMarks() {
        const marksData = [];
        const expNo = document.getElementById('eval-exp-select').value;
        if (!expNo) return;
 
        Object.keys(experimentEvalsState).forEach(regNo => {
            const state = experimentEvalsState[regNo][expNo];
            if (state) {
                marksData.push({
                    reg_no: regNo,
                    prep_punctuality: state.prep_punctuality,
                    setup_procedure: state.setup_procedure,
                    observation_recording: state.observation_recording,
                    analysis_interpretation: state.analysis_interpretation,
                    viva_voce: state.viva_voce,
                    workmanship_discipline: state.workmanship_discipline,
                    total_score_50: state.total_score_50
                });
            }
        });
 
        Swal.fire({
            title: 'Saving Lab Work Marks...',
            text: `Saving scores for ${expNo}`,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
 
        fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/experiment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({ experiment_no: expNo, marks_data: marksData })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                closeExperimentEvalModal();
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
 
    // =====================================================================
    // Practical Series Exams Evaluation System
    // =====================================================================
    const seriesPracticalEvalsDb = @json($seriesPracticalEvals);
    const seriesPracticalEvalsState = {};
 
    studentsList.forEach(s => {
        const regNo = s.reg_no;
        seriesPracticalEvalsState[regNo] = {
            'Series 1': { writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false },
            'Series 2': { writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false }
        };
 
        const dbList = seriesPracticalEvalsDb[regNo] || [];
        dbList.forEach(rec => {
            const sNo = rec.series_no;
            let mapped = sNo;
            if (sNo === 'Test 1 (CO1+CO2)') mapped = 'Series 1';
            if (sNo === 'Test 2 (CO3+CO4)') mapped = 'Series 2';
 
            if (seriesPracticalEvalsState[regNo][mapped]) {
                seriesPracticalEvalsState[regNo][mapped] = {
                    writeup_procedure: parseFloat(rec.writeup_procedure) || 0,
                    setup_execution: parseFloat(rec.setup_execution) || 0,
                    observation_result: parseFloat(rec.observation_result) || 0,
                    viva_voce: parseFloat(rec.viva_voce) || 0,
                    record_completion: parseFloat(rec.record_completion) || 0,
                    total_score_40: parseFloat(rec.total_score_40) || 0,
                    is_absent: !!rec.is_absent
                };
            }
        });
    });
 
    function openSeriesPracticalModal() {
        document.getElementById('series-practical-modal').classList.remove('hidden');
        const selectStudent = document.getElementById('series-pr-student-select');
        if (selectStudent && selectStudent.value) {
            loadSeriesPrStudent(selectStudent.value);
        }
    }
 
    function closeSeriesPracticalModal() {
        document.getElementById('series-practical-modal').classList.add('hidden');
    }
 
    function onSeriesPrTestChange(test) {
        const selectStudent = document.getElementById('series-pr-student-select');
        if (selectStudent && selectStudent.value) {
            loadSeriesPrStudent(selectStudent.value);
        }
    }
 
    function loadSeriesPrStudent(regNo) {
        const student = studentsList.find(s => s.reg_no === regNo);
        if (!student) return;
 
        const test = document.getElementById('series-pr-test-select').value;
        if (!test) return;
 
        const state = seriesPracticalEvalsState[regNo][test];
        const container = document.getElementById('series-pr-rubrics-container');
 
        const criteria = [
            { label: '1. Write-up / Procedure (Aim, Circuit/Flowchart, Stepwise procedure)', key: 'writeup_procedure', max: 10, step: 0.5 },
            { label: '2. Experiment Setup & Execution (Connections, Handling, Accuracy)', key: 'setup_execution', max: 10, step: 0.5 },
            { label: '3. Observation & Result / Output (Tabulation, Calculations, Outcome)', key: 'observation_result', max: 8, step: 0.5 },
            { label: '4. Viva Voce (Conceptual understanding, Theory knowledge)', key: 'viva_voce', max: 8, step: 0.5 },
            { label: '5. Record (Completion & neatness, Faculty certification)', key: 'record_completion', max: 4, step: 0.5 }
        ];
 
        let html = `
            <div class="mb-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Grading Criteria &mdash; ${student.name}</div>
            <div class="space-y-3">
        `;
 
        criteria.forEach(c => {
            const val = state[c.key] || 0;
            html += `
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex justify-between items-center text-xs font-semibold">
                        <span class="text-slate-700">${c.label} <span class="text-slate-400 font-normal">(Max ${c.max})</span></span>
                        <span class="text-indigo-700 font-mono font-bold text-sm bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-200" id="series-pr-val-badge-${c.key}">${val.toFixed(1)}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="adjustSeriesPrVal('${c.key}', -${c.step}, ${c.max})" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 font-black text-slate-700 flex items-center justify-center transition-colors border border-slate-300">-</button>
                        <input type="range" min="0" max="${c.max}" step="${c.step}" value="${val}" id="series-pr-slider-${c.key}" oninput="syncSeriesPrSlider('${c.key}', this.value, ${c.max})" class="flex-1 accent-indigo-600 h-2 rounded-lg cursor-pointer">
                        <button type="button" onclick="adjustSeriesPrVal('${c.key}', ${c.step}, ${c.max})" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 font-black text-slate-700 flex items-center justify-center transition-colors border border-slate-300">+</button>
                    </div>
                </div>
            `;
        });
 
        html += '</div>';
 
        container.innerHTML = html;
        updateSeriesPrLiveDisplay(regNo, test);
    }
 
    function syncSeriesPrSlider(key, val, max) {
        const num = parseFloat(val) || 0;
        const regNo = document.getElementById('series-pr-student-select').value;
        const test = document.getElementById('series-pr-test-select').value;
        if (!regNo || !test) return;
 
        seriesPracticalEvalsState[regNo][test][key] = num;
 
        const badge = document.getElementById(`series-pr-val-badge-${key}`);
        if (badge) badge.innerText = num.toFixed(1);
 
        updateSeriesPrLiveDisplay(regNo, test);
    }
 
    function adjustSeriesPrVal(key, delta, max) {
        const slider = document.getElementById(`series-pr-slider-${key}`);
        if (!slider) return;
 
        let current = parseFloat(slider.value) || 0;
        let next = Math.max(0, Math.min(max, current + delta));
        slider.value = next;
        syncSeriesPrSlider(key, next, max);
    }
 
    function updateSeriesPrLiveDisplay(regNo, test) {
        const state = seriesPracticalEvalsState[regNo][test];
        if (!state) return;
 
        const total = (state.writeup_procedure || 0) +
                      (state.setup_execution || 0) +
                      (state.observation_result || 0) +
                      (state.viva_voce || 0) +
                      (state.record_completion || 0);
 
        state.total_score_40 = total;
 
        const cia = Math.round(((total / 40.0) * 10.0) * 2) / 2;
 
        document.getElementById('series-pr-live-total').innerText = `${total.toFixed(1)} / 40.0 M`;
        document.getElementById('series-pr-live-cia').innerText = `${cia.toFixed(1)} / 10.0 M`;
    }
 
    function prevSeriesPrStudent() {
        const sel = document.getElementById('series-pr-student-select');
        if (!sel || sel.selectedIndex <= 0) return;
        sel.selectedIndex--;
        loadSeriesPrStudent(sel.value);
    }
 
    function nextSeriesPrStudent() {
        const sel = document.getElementById('series-pr-student-select');
        if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
        sel.selectedIndex++;
        loadSeriesPrStudent(sel.value);
    }
 
    function saveAndNextSeriesPrStudent() {
        const sel = document.getElementById('series-pr-student-select');
        const regNo = sel.value;
        const test = document.getElementById('series-pr-test-select').value;
        if (!regNo || !test) return;
 
        const state = seriesPracticalEvalsState[regNo][test];
        const dbSeriesName = (test === 'Series 1') ? 'Test 1 (CO1+CO2)' : 'Test 2 (CO3+CO4)';
        const bsId = {{ $batchSubject->id }};
 
        fetch(`/api/r26/classroom/practicum/${bsId}/evaluate/series-practical`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({
                series_no: dbSeriesName,
                marks_data: [{
                    reg_no: regNo,
                    writeup_procedure: state.writeup_procedure,
                    setup_execution: state.setup_execution,
                    observation_result: state.observation_result,
                    viva_voce: state.viva_voce,
                    record_completion: state.record_completion,
                    is_absent: state.is_absent
                }]
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                nextSeriesPrStudent();
            } else {
                alert('Auto-save error: ' + data.message);
            }
        });
    }
 
    function saveAllSeriesPrMarks() {
        const marksData = [];
        const test = document.getElementById('series-pr-test-select').value;
        if (!test) return;
 
        const dbSeriesName = (test === 'Series 1') ? 'Test 1 (CO1+CO2)' : 'Test 2 (CO3+CO4)';
 
        Object.keys(seriesPracticalEvalsState).forEach(regNo => {
            const state = seriesPracticalEvalsState[regNo][test];
            if (state) {
                marksData.push({
                    reg_no: regNo,
                    writeup_procedure: state.writeup_procedure,
                    setup_execution: state.setup_execution,
                    observation_result: state.observation_result,
                    viva_voce: state.viva_voce,
                    record_completion: state.record_completion,
                    is_absent: state.is_absent
                });
            }
        });
 
        Swal.fire({
            title: 'Saving Series Test Marks...',
            text: `Saving scores for ${dbSeriesName}`,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
 
        fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/series-practical', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({ series_no: dbSeriesName, marks_data: marksData })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                closeSeriesPracticalModal();
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
        const eseSplitupState = {};
        
        studentsList.forEach(st => {
            const currentTotal = parseFloat(st.ese_practical || 0);
            if (currentTotal > 0) {
                const factor = currentTotal / 40.0;
                eseSplitupState[st.reg_no] = {
                    writeup: Math.round(10 * factor * 2) / 2,
                    setup: Math.round(10 * factor * 2) / 2,
                    result: Math.round(8 * factor * 2) / 2,
                    viva: Math.round(8 * factor * 2) / 2,
                    record: Math.round(4 * factor * 2) / 2
                };
            } else {
                eseSplitupState[st.reg_no] = { writeup: 0, setup: 0, result: 0, viva: 0, record: 0 };
            }
        });

        function openEsePracticalModal() {
            document.getElementById('ese-practical-modal').classList.remove('hidden');
            const sel = document.getElementById('ese-student-select');
            if (sel && sel.value) {
                loadEseStudent(sel.value);
            }
        }

        function closeEsePracticalModal() {
            document.getElementById('ese-practical-modal').classList.add('hidden');
        }

        const eseRubrics = [
            { key: 'writeup', label: 'Procedure & Writeup', max: 10 },
            { key: 'setup', label: 'Setup & Circuit Execution', max: 10 },
            { key: 'result', label: 'Observation & Result', max: 8 },
            { key: 'viva', label: 'Viva-Voce Examination', max: 8 },
            { key: 'record', label: 'Record & Logbook', max: 4 }
        ];

        function loadEseStudent(regNo) {
            const container = document.getElementById('ese-sliders-container');
            if (!container) return;

            if (!eseSplitupState[regNo]) {
                eseSplitupState[regNo] = { writeup: 0, setup: 0, result: 0, viva: 0, record: 0 };
            }

            let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-3">';

            eseRubrics.forEach(rub => {
                const currentVal = eseSplitupState[regNo][rub.key] || 0;
                html += `
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-700 text-xs">${rub.label}</span>
                            <span id="ese-badge-${rub.key}" class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-mono text-xs font-bold border border-blue-200">
                                ${parseFloat(currentVal).toFixed(1)} / ${rub.max}.0
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="stepEseSlider('${regNo}', '${rub.key}', -0.5, ${rub.max})" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 font-extrabold text-slate-700 text-base flex items-center justify-center transition-colors border border-slate-300">-</button>
                            <input type="range" id="ese-slider-${rub.key}" min="0" max="${rub.max}" step="0.5" value="${currentVal}" oninput="syncEseSlider('${regNo}', '${rub.key}', this.value, ${rub.max})" class="flex-1 accent-blue-600 h-2 rounded-lg cursor-pointer">
                            <button type="button" onclick="stepEseSlider('${regNo}', '${rub.key}', 0.5, ${rub.max})" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 font-extrabold text-slate-700 text-base flex items-center justify-center transition-colors border border-slate-300">+</button>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            container.innerHTML = html;
            calculateEseLiveTotal(regNo);
        }

        function syncEseSlider(regNo, key, val, maxVal) {
            const num = Math.min(maxVal, Math.max(0, parseFloat(val) || 0));
            if (!eseSplitupState[regNo]) eseSplitupState[regNo] = {};
            eseSplitupState[regNo][key] = num;

            const badge = document.getElementById(`ese-badge-${key}`);
            if (badge) badge.innerText = `${num.toFixed(1)} / ${maxVal}.0`;

            calculateEseLiveTotal(regNo);
        }

        function stepEseSlider(regNo, key, delta, maxVal) {
            const slider = document.getElementById(`ese-slider-${key}`);
            if (!slider) return;

            let current = parseFloat(slider.value) || 0;
            let next = Math.max(0, Math.min(maxVal, current + delta));
            slider.value = next;
            syncEseSlider(regNo, key, next, maxVal);
        }

        function calculateEseLiveTotal(regNo) {
            const data = eseSplitupState[regNo] || {};
            const total = (data.writeup || 0) + (data.setup || 0) + (data.result || 0) + (data.viva || 0) + (data.record || 0);

            const rawElem = document.getElementById('ese-student-total-raw');
            const gradeBadge = document.getElementById('ese-student-grade-badge');

            if (rawElem) rawElem.innerText = `${Math.round(total)} / 40 Marks`;

            const pct = (total / 40) * 100;
            let grade = 'F';
            let gClass = 'text-rose-600 bg-rose-500/20 border-rose-500/30';
            if (pct >= 90) { grade = 'S'; gClass = 'text-emerald-600 bg-emerald-500/20 border-emerald-500/30'; }
            else if (pct >= 80) { grade = 'A'; gClass = 'text-blue-700 bg-blue-50 border border-blue-200'; }
            else if (pct >= 70) { grade = 'B'; gClass = 'text-indigo-600 bg-indigo-500/20 border-indigo-500/30'; }
            else if (pct >= 60) { grade = 'C'; gClass = 'text-violet-600 bg-purple-500/20 border-purple-500/30'; }
            else if (pct >= 50) { grade = 'D'; gClass = 'text-amber-600 bg-amber-500/20 border-amber-500/30'; }
            else if (pct >= 40) { grade = 'E'; gClass = 'text-orange-400 bg-orange-500/20 border-orange-500/30'; }

            if (gradeBadge) {
                gradeBadge.innerText = grade;
                gradeBadge.className = `font-black text-base px-3 py-0.5 rounded-full border ${gClass}`;
            }

            const row = document.getElementById(`ese-row-${regNo}`);
            if (row) {
                const w = row.querySelector('.ese-val-writeup'); if (w) w.innerText = (data.writeup || 0).toFixed(1);
                const s = row.querySelector('.ese-val-setup'); if (s) s.innerText = (data.setup || 0).toFixed(1);
                const r = row.querySelector('.ese-val-result'); if (r) r.innerText = (data.result || 0).toFixed(1);
                const v = row.querySelector('.ese-val-viva'); if (v) v.innerText = (data.viva || 0).toFixed(1);
                const rec = row.querySelector('.ese-val-record'); if (rec) rec.innerText = (data.record || 0).toFixed(1);
                const tot = row.querySelector('.ese-val-total'); if (tot) tot.innerText = Math.round(total);
                const gr = row.querySelector('.ese-val-grade');
                if (gr) gr.innerHTML = `<span class="px-2.5 py-0.5 rounded-full border text-xs font-bold ${gClass}">${grade}</span>`;
            }
        }

        function prevEseStudent() {
            const sel = document.getElementById('ese-student-select');
            if (!sel || sel.selectedIndex <= 0) return;
            sel.selectedIndex--;
            loadEseStudent(sel.value);
        }

        function nextEseStudent() {
            const sel = document.getElementById('ese-student-select');
            if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
            sel.selectedIndex++;
            loadEseStudent(sel.value);
        }

        function saveAndNextEseStudent() {
            const sel = document.getElementById('ese-student-select');
            if (!sel) return;
            if (sel.selectedIndex < sel.options.length - 1) {
                sel.selectedIndex++;
                loadEseStudent(sel.value);
            } else {
                Swal.fire('End of List', 'Reached last student in list.', 'info');
            }
        }

        function saveAllEseMarks() {
            const marksData = studentsList.map(st => {
                const splitup = eseSplitupState[st.reg_no] || { writeup: 0, setup: 0, result: 0, viva: 0, record: 0 };
                const totalScore = (splitup.writeup || 0) + (splitup.setup || 0) + (splitup.result || 0) + (splitup.viva || 0) + (splitup.record || 0);
                return {
                    reg_no: st.reg_no,
                    ese_practical_marks: totalScore
                };
            });

            Swal.fire({
                title: 'Saving Practical ESE Marks...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/ese', {
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
                    closeEsePracticalModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved Successfully!',
                        text: 'Practical ESE marks and grades saved!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to save ESE marks', 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function openEseTheoryModal() {
            const modal = document.getElementById('ese-theory-modal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeEseTheoryModal() {
            const modal = document.getElementById('ese-theory-modal');
            if (modal) modal.classList.add('hidden');
        }

        function saveAllEseTheoryGrades() {
            const marksData = studentsList.map(st => {
                const elem = document.getElementById('ese-theory-grade-' + st.reg_no);
                return {
                    reg_no: st.reg_no,
                    ese_theory_grade: elem ? elem.value : ''
                };
            });

            Swal.fire({
                title: 'Saving Theory ESE Grades...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/ese', {
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
                    closeEseTheoryModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved Successfully!',
                        text: 'Board Theory ESE grades saved!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed to save ESE grades', 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function printSubtabReport(reportTitle, containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;

            const clone = container.cloneNode(true);
            
            // Remove non-printable elements, buttons, inputs, dropdowns, QP generator cards
            clone.querySelectorAll('button, select, input, .no-print, #qp-gen-status').forEach(el => el.remove());

            const collegeName = "CARMEL COLLEGE OF ENGINEERING & TECHNOLOGY, ALAPPUZHA";
            const branchName = @json(function_exists('getFullBranchName') ? getFullBranchName($classroom->department ?? $classroom->branch ?? '') : ($classroom->department ?? $classroom->branch));
            const subjectName = @json($batchSubject->subject_name);
            const subjectCode = @json($batchSubject->subject_code);
            const batchCode = @json($batchSubject->classroom_id);
            const semester = @json($practicumCourseFile->semester);
            const facultyName = @json(Session::get('userName') ?? 'Faculty In-Charge');
            const eseMarks = @json($practicumCourseFile->ese_marks);
            const todayStr = new Date().toLocaleDateString('en-GB');

            const printWin = window.open('', '_blank', 'width=1150,height=850');
            printWin.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${reportTitle} - ${subjectName}</title>
                    <style>
                        @page {
                            size: A4 landscape;
                            margin: 12mm 10mm 12mm 10mm;
                        }
                        body {
                            font-family: 'Times New Roman', Times, serif;
                            color: #000;
                            background: #fff;
                            margin: 0;
                            padding: 10px;
                            font-size: 11px;
                            line-height: 1.35;
                        }
                        .header-container {
                            text-align: center;
                            border-bottom: 2px double #000;
                            padding-bottom: 8px;
                            margin-bottom: 12px;
                        }
                        .college-title {
                            font-size: 17px;
                            font-weight: bold;
                            text-transform: uppercase;
                            margin-bottom: 3px;
                            color: #000;
                            letter-spacing: 0.5px;
                        }
                        .dept-title {
                            font-size: 12px;
                            font-weight: bold;
                            text-transform: uppercase;
                            margin-bottom: 4px;
                            color: #111;
                        }
                        .report-badge {
                            font-size: 13px;
                            font-weight: bold;
                            text-transform: uppercase;
                            margin: 6px 0;
                            color: #000;
                            text-decoration: underline;
                        }
                        .meta-table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 8px;
                            margin-bottom: 12px;
                            font-size: 11px;
                        }
                        .meta-table td {
                            padding: 5px 8px;
                            border: 1px solid #000;
                            width: 50%;
                            color: #000;
                            background: #fafafa;
                        }
                        .meta-table td strong {
                            color: #000;
                        }
                        /* FORCE CLEAN BLACK AND WHITE FOR PRINT CONTENT */
                        .print-content * {
                            box-shadow: none !important;
                            text-shadow: none !important;
                        }
                        .print-content div {
                            border-radius: 0 !important;
                            background: transparent !important;
                            border: none !important;
                        }
                        .print-content p, .print-content span, .print-content h3, .print-content h4 {
                            color: #000 !important;
                        }
                        table {
                            width: 100% !important;
                            border-collapse: collapse !important;
                            margin-top: 8px !important;
                            margin-bottom: 12px !important;
                            page-break-inside: auto;
                        }
                        tr {
                            page-break-inside: avoid;
                            page-break-after: auto;
                        }
                        th {
                            border: 1px solid #000 !important;
                            padding: 6px 6px !important;
                            background-color: #f1f5f9 !important;
                            color: #000 !important;
                            font-size: 11px !important;
                            font-weight: bold !important;
                            text-transform: uppercase !important;
                            text-align: center !important;
                        }
                        td {
                            border: 1px solid #000 !important;
                            padding: 5px 6px !important;
                            color: #000 !important;
                            font-size: 11px !important;
                            background: #fff !important;
                        }
                        td.text-center, th.text-center {
                            text-align: center !important;
                        }
                        .signatures-table {
                            width: 100%;
                            margin-top: 45px;
                            page-break-inside: avoid;
                        }
                        .signatures-table td {
                            width: 33.33%;
                            text-align: center;
                            padding-top: 5px;
                            font-weight: bold;
                            font-size: 12px;
                            border: none !important;
                            border-top: 1px solid #000 !important;
                            background: transparent !important;
                            color: #000 !important;
                        }
                        .footer-note {
                            margin-top: 20px;
                            font-size: 9px;
                            text-align: right;
                            color: #555;
                            border-top: 1px dashed #ccc;
                            padding-top: 4px;
                        }
                        @media print {
                            body { padding: 0; margin: 0; }
                            button, select, input, .no-print { display: none !important; }
                            .meta-table td, th {
                                background-color: #f1f5f9 !important;
                                -webkit-print-color-adjust: exact !important;
                                print-color-adjust: exact !important;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="header-container">
                        <div class="college-title">${collegeName}</div>
                        <div class="dept-title">DEPARTMENT OF ${branchName.toUpperCase()}</div>
                        <div class="report-badge">${reportTitle}</div>
                        
                        <table class="meta-table">
                            <tr>
                                <td><strong>Course Name & Code:</strong> ${subjectName} (${subjectCode})</td>
                                <td><strong>Batch Code:</strong> ${batchCode}</td>
                            </tr>
                            <tr>
                                <td><strong>Branch:</strong> ${branchName}</td>
                                <td><strong>Semester / Scheme:</strong> Semester ${semester} (Rev 2026)</td>
                            </tr>
                            <tr>
                                <td><strong>Assessment Year:</strong> 2026 – 2027</td>
                                <td><strong>Date of Report:</strong> ${todayStr}</td>
                            </tr>
                            <tr>
                                <td><strong>Faculty In-Charge:</strong> ${facultyName}</td>
                                <td><strong>Evaluation Scheme:</strong> CIA: 40 Marks | Theory ESE: ${eseMarks} Marks</td>
                            </tr>
                        </table>
                    </div>

                    <div class="print-content">
                        ${clone.innerHTML}
                    </div>

                    <table class="signatures-table">
                        <tr>
                            <td>Signature of Faculty In-Charge</td>
                            <td>Signature of Head of Department (HOD)</td>
                            <td>Signature of Principal</td>
                        </tr>
                    </table>

                    <div class="footer-note">
                        Generated via Practicum Virtual Classroom System • Carmel College of Engineering & Technology, Alappuzha
                    </div>

                    <script>
                        window.onload = function() {
                            setTimeout(function() { window.print(); }, 400);
                        }
                    <\/script>
                </body>
                </html>
            `);
            printWin.document.close();
        }

        function openMidsemInitModal() {
            document.getElementById('modal-midsem-survey-init-practicum').classList.remove('hidden');
        }
        function closeMidsemInitModal() {
            document.getElementById('modal-midsem-survey-init-practicum').classList.add('hidden');
        }
        function openExitInitModal() {
            document.getElementById('modal-exit-survey-init-practicum').classList.remove('hidden');
        }
        function closeExitInitModal() {
            document.getElementById('modal-exit-survey-init-practicum').classList.add('hidden');
        }

        function submitPracticumMidsemInit(event) {
            event.preventDefault();
            const questions = {
                q5: document.getElementById('p-ms-q5').value.trim(),
                q6: document.getElementById('p-ms-q6').value.trim(),
                q7: document.getElementById('p-ms-q7').value.trim(),
                q8: document.getElementById('p-ms-q8').value.trim(),
                q9: document.getElementById('p-ms-q9').value.trim(),
                q10: document.getElementById('p-ms-q10').value.trim(),
                q11: document.getElementById('p-ms-q11').value.trim(),
                q12: document.getElementById('p-ms-q12').value.trim()
            };

            fetch('/api/classroom/{{ $batchSubject->id }}/survey/initiate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ questions })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    Swal.fire('Published!', 'Mid-Semester survey initiated successfully and sent to student portal!', 'success');
                    closeMidsemInitModal();
                    checkPracticumSurveyStatuses();
                } else {
                    Swal.fire('Error', data.message || 'Failed to initiate survey', 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function submitPracticumExitInit(event) {
            event.preventDefault();
            const questions = {
                q1: document.getElementById('p-ex-q1').value.trim(),
                q2: document.getElementById('p-ex-q2').value.trim(),
                q3: document.getElementById('p-ex-q3').value.trim(),
                q4: document.getElementById('p-ex-q4').value.trim(),
                q5: document.getElementById('p-ex-q5').value.trim(),
                q6: document.getElementById('p-ex-q6').value.trim(),
                q7: document.getElementById('p-ex-q7').value.trim(),
                q8: document.getElementById('p-ex-q8').value.trim()
            };

            fetch('/api/classroom/{{ $batchSubject->id }}/course-exit/initiate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ questions })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    Swal.fire('Published!', 'Course Exit survey initiated successfully! Students notified in their Works To Do.', 'success');
                    closeExitInitModal();
                    checkPracticumSurveyStatuses();
                } else {
                    Swal.fire('Error', data.message || 'Failed to initiate survey', 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function controlPracticumSurvey(type, action) {
            const endpoint = type === 'midsem' ? '/api/classroom/{{ $batchSubject->id }}/survey/' + action : '/api/classroom/{{ $batchSubject->id }}/course-exit/' + action;
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    Swal.fire('Updated!', data.message, 'success');
                    checkPracticumSurveyStatuses();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function checkPracticumSurveyStatuses() {
            fetch('/api/classroom/{{ $batchSubject->id }}/survey/results')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('midsem-practicum-status-badge');
                    const openBtn = document.getElementById('btn-open-midsem-practicum');
                    const closeBtn = document.getElementById('btn-close-midsem-practicum');
                    if (data.status === 'INACTIVE') {
                        if (badge) { badge.innerText = 'Not Initiated'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-400 border border-slate-200'; }
                        if (openBtn) openBtn.classList.remove('hidden');
                        if (closeBtn) closeBtn.classList.add('hidden');
                    } else if (data.data && data.data.survey) {
                        const st = data.data.survey.status;
                        if (st === 'Active') {
                            if (badge) { badge.innerText = 'Active (' + data.data.responded_count + ' Submitted)'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40'; }
                            if (openBtn) openBtn.classList.add('hidden');
                            if (closeBtn) closeBtn.classList.remove('hidden');
                        } else {
                            if (badge) { badge.innerText = 'Closed / Locked (' + data.data.responded_count + ' Submitted)'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-700 border border-slate-200'; }
                            if (openBtn) openBtn.classList.remove('hidden');
                            if (closeBtn) closeBtn.classList.add('hidden');
                        }
                    }
                }).catch(() => {});

            fetch('/api/classroom/{{ $batchSubject->id }}/course-exit/results')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('exit-practicum-status-badge');
                    const openBtn = document.getElementById('btn-open-exit-practicum');
                    const closeBtn = document.getElementById('btn-close-exit-practicum');
                    if (data.status === 'INACTIVE') {
                        if (badge) { badge.innerText = 'Not Initiated'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-400 border border-slate-200'; }
                        if (openBtn) openBtn.classList.remove('hidden');
                        if (closeBtn) closeBtn.classList.add('hidden');
                    } else if (data.data && data.data.survey) {
                        const st = data.data.survey.status;
                        if (st === 'Active') {
                            if (badge) { badge.innerText = 'Active (' + data.data.responded_count + ' Submitted)'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-teal-500/20 text-teal-300 border border-teal-500/40'; }
                            if (openBtn) openBtn.classList.add('hidden');
                            if (closeBtn) closeBtn.classList.remove('hidden');
                        } else {
                            if (badge) { badge.innerText = 'Closed / Locked (' + data.data.responded_count + ' Submitted)'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-700 border border-slate-200'; }
                            if (openBtn) openBtn.classList.remove('hidden');
                            if (closeBtn) closeBtn.classList.add('hidden');
                        }
                    }
                }).catch(() => {});
        }

        document.addEventListener('DOMContentLoaded', function() {
            checkPracticumSurveyStatuses();
        });
    </script>

    <!-- MODAL: MID-SEM SURVEY INITIATION PREVIEW & EDIT -->
    <div id="modal-midsem-survey-init-practicum" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
      <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-4xl p-6 space-y-4 shadow-2xl max-h-[88vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
          <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <span class="material-symbols-rounded text-indigo-600">rate_review</span>
            <span>Preview &amp; Edit Mid-Semester Survey Questions</span>
          </h3>
          <button type="button" onclick="closeMidsemInitModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all cursor-pointer">
            &times;
          </button>
        </div>
        
        <p class="text-xs text-slate-500 leading-relaxed">
          Review or edit the survey questions below before activating. Once published, active survey notifications will automatically appear on the student dashboard ("Works to do").
        </p>

        <form id="form-midsem-init-practicum" onsubmit="submitPracticumMidsemInit(event)" class="space-y-4">
          <div class="space-y-3.5">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q1. CO1 - Course Outcomes Communication</label>
              <input type="text" id="p-ms-q5" value="The teacher clearly communicates the Course Outcomes (COs) and learning goals at the start of new topics." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q2. CO1 - Syllabus Delivery Pace</label>
              <input type="text" id="p-ms-q6" value="The pace, speed, and coverage of the syllabus completed so far is appropriate." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q3. CO2 - Concept Clarity &amp; Application</label>
              <input type="text" id="p-ms-q7" value="The teacher explains complex concepts clearly and links classroom theory to real-world industrial or field applications." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q4. CO2 - Effectiveness of Teaching &amp; Lab Demonstrations</label>
              <input type="text" id="p-ms-q8" value="The use of teaching tools, animations, lab demonstrations, or ICT tools is effective." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q5. CO3 - Doubt Clearing &amp; Classroom Interaction</label>
              <input type="text" id="p-ms-q9" value="The teacher encourages student questions, manages classroom discussions well, and clears doubts patiently." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q6. CO3 - Test &amp; Practical Assignment Relevance</label>
              <input type="text" id="p-ms-q10" value="Internal assessment test questions and practical assignments match the topics taught in class." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q7. CO4 - Fairness in Evaluation</label>
              <input type="text" id="p-ms-q11" value="Evaluation of mid-semester tests or practical submissions is fair, timely, and transparent." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q8. CO4 - Guidance &amp; Support for Students</label>
              <input type="text" id="p-ms-q12" value="The teacher provides extra guidance, remedial tips, or support to students needing assistance." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
          </div>

          <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
            <button type="button" onclick="closeMidsemInitModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors cursor-pointer">Cancel</button>
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-xs transition-colors cursor-pointer">Activate &amp; Publish Survey</button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL: COURSE EXIT SURVEY INITIATION PREVIEW & EDIT -->
    <div id="modal-exit-survey-init-practicum" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
      <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-4xl p-6 space-y-4 shadow-2xl max-h-[88vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
          <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <span class="material-symbols-rounded text-teal-600">assignment_turned_in</span>
            <span>Preview &amp; Edit Course Exit Survey Questions</span>
          </h3>
          <button type="button" onclick="closeExitInitModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all cursor-pointer">
            &times;
          </button>
        </div>
        
        <p class="text-xs text-slate-500 leading-relaxed">
          Review or edit the Course Exit questions below before activating. Students will submit responses to calculate Indirect CO Attainment.
        </p>

        <form id="form-exit-init-practicum" onsubmit="submitPracticumExitInit(event)" class="space-y-4">
          <div class="space-y-3.5">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q1. CO1 - Theoretical Principles &amp; Fundamentals</label>
              <input type="text" id="p-ex-q1" value="How well did the course help you understand and remember core academic principles, models, and structural fundamentals?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q2. CO1 - Outcome &amp; Syllabus Alignment</label>
              <input type="text" id="p-ex-q2" value="How clearly were the course objectives, scope, and basic terms aligned with class lectures and lab demonstrations?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q3. CO2 - Analytical Ability &amp; Logic</label>
              <input type="text" id="p-ex-q3" value="How effectively did the course build your reasoning skills, mathematical derivations, or logical analysis capabilities?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q4. CO2 - Design &amp; Troubleshooting Skills</label>
              <input type="text" id="p-ex-q4" value="To what extent can you design models, troubleshoot bugs, or conduct lab experiments based on class lessons?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q5. CO3 - Modern Tools &amp; Practical Execution</label>
              <input type="text" id="p-ex-q5" value="How confident are you in using modern software, lab apparatus, or engineering software for tasks?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q6. CO3 - Problem Solving in Field &amp; Lab</label>
              <input type="text" id="p-ex-q6" value="How effectively can you apply core theoretical principles to solve practical or field problems?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q7. CO4 - Ethics, Teamwork &amp; Professional Conduct</label>
              <input type="text" id="p-ex-q7" value="Did the course foster professional ethics, group collaboration, and responsible work habits?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q8. CO4 - Communication &amp; Report Writing</label>
              <input type="text" id="p-ex-q8" value="How well did the course improve your technical documentation, presentation skills, and report writing?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
          </div>

          <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
            <button type="button" onclick="closeExitInitModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors cursor-pointer">Cancel</button>
            <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-xs shadow-xs transition-colors cursor-pointer">Activate &amp; Publish Survey</button>
          </div>
        </form>
      </div>
    </div>
    <!-- Modern CampusLynk Syllabus Upload & Document Processing Modal -->
    <div id="syllabus-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-50 backdrop-blur-xs hidden p-4">
      <div class="bg-white border border-slate-200/90 rounded-2xl max-w-xl w-full p-6 shadow-2xl space-y-4">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <div class="flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
              <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h6"/><path d="M9 11h6"/></svg>
            </span>
            <div>
              <h3 class="text-base font-bold text-slate-900">Upload Practicum Syllabus PDF</h3>
              <p class="text-xs text-slate-500">Automatically extract Theory modules, COs &amp; Lab experiments</p>
            </div>
          </div>
          <button type="button" onclick="closeSyllabusModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        @if($practicumCourseFile && $practicumCourseFile->syllabus_pdf_path)
        <!-- Current Active Syllabus Banner -->
        <div class="p-3.5 bg-emerald-50/70 border border-emerald-200/80 rounded-xl flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <div>
              <h4 class="text-xs font-bold text-emerald-950">Active Syllabus Document Loaded</h4>
              <p class="text-xs text-emerald-700">Ready to replace or re-parse anytime</p>
            </div>
          </div>
          <a href="/storage/{{ $practicumCourseFile->syllabus_pdf_path }}" target="_blank" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-lg border border-emerald-200 flex items-center gap-1 shadow-2xs">
            <svg class="w-3.5 h-3.5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/></svg>
            <span>View PDF</span>
          </a>
        </div>
        @endif

        <!-- Drag & Drop Zone -->
        <div id="practicumSyllabusDropzone" ondragover="handlePracticumDragOver(event)" ondragleave="handlePracticumDragLeave(event)" ondrop="handlePracticumFileDrop(event)" onclick="document.getElementById('practicumSyllabusFileInput').click()" class="border-2 border-dashed border-slate-300 hover:border-blue-500 bg-slate-50/70 hover:bg-blue-50/40 rounded-2xl p-6 text-center space-y-2.5 transition cursor-pointer">
          <div class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-600 mx-auto flex items-center justify-center border border-blue-200">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          </div>
          <div>
            <h4 class="text-sm font-bold text-slate-900">Select Practicum Syllabus PDF</h4>
            <p class="text-xs text-slate-500 mt-0.5">Drag &amp; drop PDF file here, or <span class="text-blue-600 font-semibold underline">browse local files</span></p>
          </div>
          <div class="flex items-center justify-center gap-2 text-xs font-semibold text-slate-400 pt-1">
            <span class="px-2 py-0.5 rounded bg-white border border-slate-200">PDF only</span>
            <span>•</span>
            <span class="px-2 py-0.5 rounded bg-white border border-slate-200">Max 10MB</span>
          </div>
          <input type="file" id="practicumSyllabusFileInput" accept="application/pdf" class="hidden" onchange="handlePracticumFileInput(this)">
        </div>

        <!-- Selected File Preview -->
        <div id="practicumFilePreview" class="hidden p-3.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-lg bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/></svg>
            </div>
            <div>
              <h5 id="practicumFileName" class="text-xs font-bold text-slate-900">syllabus.pdf</h5>
              <p id="practicumFileSize" class="text-xs text-slate-500">2.1 MB · Ready</p>
            </div>
          </div>
          <button type="button" onclick="cancelPracticumSelectedFile(event)" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-200/60 transition">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <!-- Indeterminate Processing State -->
        <div id="practicumProcessingState" class="hidden p-4 bg-blue-50/60 border border-blue-200 rounded-xl text-center space-y-2">
          <div class="flex items-center justify-center gap-2 text-blue-700">
            <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
            <span class="text-xs font-bold">Extracting 90-Hour Practicum Specifications...</span>
          </div>
          <p class="text-xs text-slate-600">Parsing 45h Theory Modules, 45h Lab Experiments, and CO-PO matrix.</p>
        </div>

        <!-- Error State -->
        <div id="practicumErrorAlert" class="hidden p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-center justify-between text-rose-800 text-xs font-semibold">
          <span id="practicumErrorMessage">Upload failed. Please verify PDF format.</span>
          <button type="button" onclick="document.getElementById('practicumSyllabusFileInput').click()" class="px-2.5 py-1 bg-rose-100 text-rose-900 rounded font-bold">Retry</button>
        </div>

        <!-- Modal Footer Actions -->
        <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
          <button type="button" onclick="closeSyllabusModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer">Cancel</button>
          <button type="button" id="btnSubmitPracticumSyllabus" onclick="submitPracticumSyllabus()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <span>Upload &amp; Extract</span>
          </button>
        </div>

      </div>
    </div>

 </body>
 </html>

