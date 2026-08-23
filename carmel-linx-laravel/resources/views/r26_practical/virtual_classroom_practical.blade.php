<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[{{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}] Practical Virtual Lab - {{ $batchSubject->subject_name }}</title>

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
            --bg-base:       #FAFAFB;
            --bg-card:       #ffffff;
            --bg-card-hover: #f8fafc;
            --bg-stripe:     #f8fafc;
            --border:        #e2e8f0;
            --border-light:  #cbd5e1;
            --text-primary:  #0f172a;
            --text-secondary:#334155;
            --text-muted:    #64748b;
            --accent:        #2563eb;
            --accent-dim:    rgba(37, 99, 235, 0.1);
            --accent-hover:  #1d4ed8;
            --success:       #059669;
            --warning:       #d97706;
            --danger:        #dc2626;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            line-height: 1.55;
            background-color: #FAFAFB;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', 'Poppins', sans-serif;
            text-shadow: none !important;
            filter: none !important;
        }
        span, p, label, button, a, th, td, div {
            text-shadow: none !important;
            filter: none !important;
        }

        /* ── Cards & Panels ───────────────────────────────── */
        .vl-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05);
        }

        .vl-card-inner {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
        }

        .glass-panel { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05); }
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05);
        }

        /* ── Buttons ──────────────────────────────────────── */
        .vl-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 0.75rem;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s ease;
            border: 1px solid #e2e8f0;
            background: #f1f5f9;
            color: #475569;
        }
        .vl-btn:hover { background: #e2e8f0; color: #0f172a; }
        .vl-btn-primary {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #fff !important;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }
        .vl-btn-primary:hover { background: #1d4ed8 !important; border-color: #1d4ed8 !important; }

        /* ── Sidebar Navigation ───────────────────────────── */
        #workspaceSidebar {
            width: 280px;
            flex-shrink: 0;
            transition: width .25s ease, opacity .25s ease;
        }
        #workspaceSidebar.sidebar-collapsed {
            width: 0;
            opacity: 0;
            overflow: hidden;
            padding: 0;
        }

        .sidebar-nav-group {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05);
        }
        .sidebar-nav-header {
            padding: 10px 14px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .tab-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 10px 14px;
            font-size: 13.5px;
            font-weight: 600;
            color: #475569;
            background: transparent;
            border: none;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: all .15s ease;
            text-align: left;
        }
        .tab-btn:last-child { border-bottom: none; }
        .tab-btn:hover { background: #f8fafc; color: #0f172a; }
        .tab-btn.active {
            background: #eff6ff !important;
            color: #1d4ed8 !important;
            font-weight: 700;
            border-left: 4px solid #2563eb !important;
        }

        /* ── Range Sliders ────────────────────────────────── */
        input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            height: 6px;
            border-radius: 3px;
            background: #cbd5e1;
            outline: none;
            cursor: pointer;
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #2563eb;
            cursor: pointer;
        }

        /* ── Inputs / Selects ─────────────────────────────── */
        .vl-input {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            border-radius: 0.5rem;
            padding: 7px 10px;
            font-size: 14px;
            font-family: inherit;
            width: 100%;
            transition: border-color .12s;
        }
        .vl-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.15); }

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
<body class="bg-[#FAFAFB] text-slate-900 min-h-screen flex flex-col overflow-x-hidden antialiased">
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

    <!-- ═══ TOP BREADCRUMB & HEADER ═══════════════════════════════════════ -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-50 px-4 md:px-8 py-3 shadow-xs">
        <div class="max-w-[1600px] mx-auto flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center gap-2 text-xs sm:text-sm font-medium text-slate-500 flex-wrap">
                <a href="{{ $dashboardUrl }}" class="hover:text-blue-600 transition flex items-center gap-1.5 font-semibold text-slate-700">
                    <span class="material-symbols-rounded text-base text-blue-600">domain</span>
                    <span>{{ $dashboardLabel }}</span>
                </a>
                <span class="text-slate-300">/</span>
                <a href="{{ $dashboardUrl }}" class="hover:text-blue-600 transition font-medium text-slate-600">My Batches</a>
                <span class="text-slate-300">/</span>
                <span class="font-bold text-slate-900 flex items-center gap-1.5">
                    <span>Virtual Lab</span>
                    <span class="text-xs font-bold font-mono px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200/80 rounded-md">Practical</span>
                </span>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-2.5 flex-wrap">
                <button onclick="toggleFullscreen()" id="fullscreen-btn" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1.5 border border-slate-200 shadow-2xs cursor-pointer" title="Toggle Fullscreen">
                    <span class="material-symbols-rounded text-sm">fullscreen</span>
                    <span>Fullscreen</span>
                </button>
                <button onclick="toggleSidebar()" id="sidebar-toggle-btn" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1.5 border border-slate-200 shadow-2xs cursor-pointer" title="Toggle Sidebar">
                    <span class="material-symbols-rounded text-sm">view_sidebar</span>
                    <span>Sidebar</span>
                </button>
                <a href="/r26/classroom/practical/course-file/{{ $batchSubject->id }}" class="px-3.5 py-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold transition flex items-center gap-1.5 border border-purple-200 shadow-2xs" title="Course File">
                    <span class="material-symbols-rounded text-sm">folder_open</span>
                    <span>Course File</span>
                </a>
                <a href="{{ $dashboardUrl }}" onclick="handleVirtualLabBack(event)" class="px-3.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition flex items-center gap-1.5 border border-rose-200 shadow-2xs" title="Back">
                    <span class="material-symbols-rounded text-sm">arrow_back</span>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </header>

    <!-- HERO HEADER CARD -->
    <div class="max-w-[1600px] mx-auto px-4 md:px-8 mt-4 w-full">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-5">
            <div class="space-y-2">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-blue-50 text-blue-700 border border-blue-200/80">
                        {{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R2021 · PRACTICAL' : 'R2026 · PRACTICAL (LAB)' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-purple-50 text-purple-700 border border-purple-200/80">
                        {{ $batchSubject->classroom_id }} · S{{ $batchSubject->semester }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-md font-mono font-bold text-xs bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $batchSubject->subject_code }}
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight" id="header-subject-name">
                    {{ $practicalCourseFile?->course_title ?: $batchSubject->subject_name }}
                </h1>

                <div class="flex items-center gap-3 text-xs sm:text-sm font-medium text-slate-500 flex-wrap">
                    <span>Branch: <strong class="text-slate-800">{{ $classroom?->branch ?? '—' }}</strong></span>
                    <span class="text-slate-300">•</span>
                    <span>Batch: <strong class="text-slate-800">{{ $classroom?->batch_year ?? '—' }}</strong></span>
                    <span class="text-slate-300">•</span>
                    <span>Staff: <strong class="text-slate-800">{{ $staff?->name ?? Session::get('userName') ?? 'Faculty' }}</strong></span>
                </div>
            </div>

            <!-- Quick Action: Print CIA Report -->
            <div class="flex items-center gap-2.5">
                <a href="/classroom/practical/{{ $batchSubject->id }}/report/print" target="_blank" class="px-4 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs transition border border-emerald-200 flex items-center gap-1.5 shadow-2xs">
                    <span class="material-symbols-rounded text-sm">print</span>
                    <span>Print CIA Statement</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ═══ MAIN WORKSPACE ════════════════════════════════════════════════ -->

    <div class="max-w-[1600px] mx-auto px-4 md:px-8 mt-4 w-full flex flex-col lg:flex-row gap-5 flex-1 pb-10">

        <!-- ── Sidebar ──────────────────────────────────────── -->
        <aside id="workspaceSidebar" style="display:flex;flex-direction:column;gap:12px;padding:14px;overflow-y:auto;">

            <!-- Navigation -->
            <div class="sidebar-nav-group" style="border: 1.5px solid rgba(99, 102, 241, 0.45); border-left: 4px solid #6366f1; box-shadow: 0 4px 12px rgba(0,0,0,0.25);">
                <div class="sidebar-nav-header" style="display:flex;align-items:center;gap:6px;color:#a5b4fc;font-weight:700;">
                    <i class="fa-solid fa-cubes"></i> Workspace Modules
                </div>

                <button type="button" onclick="switchTab('outline')" id="btn-outline" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-file-pdf tab-icon"></i> Course Outline / Syllabus
                    </span>
                </button>
                <button type="button" onclick="switchTab('experiments')" id="btn-experiments" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-vials tab-icon"></i> List of Experiments
                    </span>
                </button>
                <button type="button" onclick="switchTab('lesson_plan')" id="btn-lesson_plan" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-calendar-day tab-icon"></i> Lesson Planner
                    </span>
                </button>
                <button type="button" onclick="switchTab('table22')" id="btn-table22" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-flask tab-icon"></i> Daywork Log
                    </span>
                </button>
                <button type="button" onclick="switchTab('table23')" id="btn-table23" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-lightbulb tab-icon"></i> Open-Ended (OEE)
                    </span>
                </button>
                <button type="button" onclick="switchTab('series_qp')" id="btn-series_qp" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-clipboard-question tab-icon"></i> Series QP &amp; Scheme
                    </span>
                </button>
                <button type="button" onclick="switchTab('table31')" id="btn-table31" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-signature tab-icon"></i> Series Marks (CA)
                    </span>
                </button>
                <button type="button" onclick="switchTab('surveys')" id="btn-surveys" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-comment-medical tab-icon"></i> Surveys &amp; Feedback
                    </span>
                </button>
                <button type="button" onclick="switchTab('ese')" id="btn-ese" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-clipboard-check tab-icon"></i> ESE Mark Entry
                    </span>
                    <span class="tab-badge">40M</span>
                </button>
                <button type="button" onclick="switchTab('summary')" id="btn-summary" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-award tab-icon"></i> CIA Consolidated
                    </span>
                    <span class="tab-badge tab-badge-green">100M</span>
                </button>
                <button type="button" onclick="switchTab('materials')" id="btn-materials" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-folder-special tab-icon"></i> Learning Materials & Pre-Lab
                    </span>
                </button>
            </div>

            <!-- Lab Batch Filter -->
            <div class="sidebar-nav-group" style="border: 1.5px solid rgba(79, 110, 247, 0.45); border-left: 4px solid var(--accent); box-shadow: 0 4px 12px rgba(0,0,0,0.25);">
                <div class="sidebar-nav-header" style="display:flex;align-items:center;gap:6px;color:#818cf8;font-weight:700;">
                    <i class="fa-solid fa-filter"></i> Lab Batch Filter
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;padding:10px;">
                    <button onclick="filterLabBatch('All')" id="batch-filter-All" class="batch-filter-btn vl-btn vl-btn-primary" style="font-size:12px;justify-content:center;">All</button>
                    <button onclick="filterLabBatch('Unassigned')" id="batch-filter-Unassigned" class="batch-filter-btn vl-btn" style="font-size:12px;justify-content:center;">Unassigned</button>
                    <button onclick="filterLabBatch('Batch A')" id="batch-filter-A" class="batch-filter-btn vl-btn" style="font-size:12px;justify-content:center;">Batch A</button>
                    <button onclick="filterLabBatch('Batch B')" id="batch-filter-B" class="batch-filter-btn vl-btn" style="font-size:12px;justify-content:center;">Batch B</button>
                </div>
            </div>

            <!-- Assessment Specs -->
            <div class="vl-card" style="border-left:3px solid var(--accent);">
                <div style="padding:12px 14px;">
                    <div style="font-size:12px;font-weight:600;color:var(--text-secondary);display:flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-graduation-cap"></i> Assessment Specs (R2026)
                    </div>
                    <div style="margin-top:10px;display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                        <div class="vl-stat-card" style="padding:7px 10px;">
                            <div class="vl-stat-label">Day Work</div>
                            <div style="font-size:14px;font-weight:600;color:var(--text-primary);">30 M</div>
                        </div>
                        <div class="vl-stat-card" style="padding:7px 10px;">
                            <div class="vl-stat-label">Series Exams</div>
                            <div style="font-size:14px;font-weight:600;color:var(--text-primary);">15 M</div>
                        </div>
                        <div class="vl-stat-card" style="padding:7px 10px;">
                            <div class="vl-stat-label">Open-ended</div>
                            <div style="font-size:14px;font-weight:600;color:var(--text-primary);">10 M</div>
                        </div>
                        <div class="vl-stat-card" style="padding:7px 10px;">
                            <div class="vl-stat-label">Attendance</div>
                            <div style="font-size:14px;font-weight:600;color:var(--text-primary);">5 M</div>
                        </div>
                    </div>
                    <div style="margin-top:8px;padding-top:8px;border-top:1px solid var(--border);font-size:12px;color:var(--text-muted);">
                        ESE: <strong style="color:#30d158;">40 Marks</strong>
                    </div>
                </div>
            </div>

        </aside>

        <!-- ── Main Content ─────────────────────────────────── -->
        <section id="workspaceContent" style="flex:1;overflow-y:auto;padding:14px;min-width:0;">

            <!-- TAB: Course Outline & CO-PO -->
            <div id="tab-outline" class="tab-content space-y-5">
                
                <!-- Course Identity & Practical Syllabus Header Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-purple-50 text-purple-700 border border-purple-200/80">
                                    R2026 · PRACTICAL VIRTUAL LAB
                                </span>
                                <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-blue-50 text-blue-700 border border-blue-200/80">
                                    Semester {{ $batchSubject->semester }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-md font-mono font-bold text-xs bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $batchSubject->subject_code }}
                                </span>
                            </div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                                {{ $practicalCourseFile && $practicalCourseFile->course_title ? $practicalCourseFile->course_title : $batchSubject->subject_name }}
                            </h2>
                            <p class="text-xs text-slate-500">Continuous Day-Work (30M), Series Tests (15M), Open-Ended Project (10M), and Attendance (5M).</p>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0 flex-wrap">
                            @if($practicalCourseFile && $practicalCourseFile->syllabus_pdf_path)
                                <a href="/storage/{{ $practicalCourseFile->syllabus_pdf_path }}" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 rounded-xl text-xs font-bold transition-all border border-slate-200 flex items-center gap-1.5 shadow-2xs">
                                    <svg class="w-4 h-4 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h6"/><path d="M9 11h6"/></svg>
                                    <span>View Syllabus PDF</span>
                                </a>
                                <button onclick="togglePracticalSyllabusWorkspace()" class="px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 border border-blue-200/80 cursor-pointer shadow-2xs">
                                    <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg>
                                    <span>Replace Syllabus</span>
                                </button>
                            @else
                                <button id="syllabus-upload-btn" onclick="togglePracticalSyllabusWorkspace()" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs cursor-pointer">
                                    <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    <span>Upload Syllabus PDF</span>
                                </button>
                            @endif
                            <input type="file" id="syllabus_pdf_input" accept=".pdf" class="hidden" onchange="uploadSyllabusPdf()">
                        </div>
                    </div>

                    <!-- Collapsible Syllabus Upload & Dropzone Workspace -->
                    <div id="practicalSyllabusWorkspace" class="{{ ($practicalCourseFile && $practicalCourseFile->syllabus_pdf_path) ? 'hidden' : '' }} pt-2">
                        <div id="practicalSyllabusDropzone" ondragover="handlePracticalDragOver(event)" ondragleave="handlePracticalDragLeave(event)" ondrop="handlePracticalFileDrop(event)" onclick="document.getElementById('syllabus_pdf_input').click()" class="border-2 border-dashed border-slate-300 hover:border-blue-500 bg-slate-50/70 hover:bg-blue-50/40 rounded-2xl p-6 text-center space-y-2.5 transition cursor-pointer">
                            <div class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-600 mx-auto flex items-center justify-center border border-blue-200">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Upload Practical Lab Syllabus PDF</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Drag &amp; drop syllabus PDF here, or <span class="text-blue-600 font-semibold underline">browse files</span></p>
                            </div>
                            <div class="flex items-center justify-center gap-2 text-xs font-semibold text-slate-400">
                                <span class="px-2 py-0.5 rounded bg-white border border-slate-200">PDF only</span>
                                <span>•</span>
                                <span class="px-2 py-0.5 rounded bg-white border border-slate-200">Max 10MB</span>
                            </div>
                        </div>

                        {{-- Upload Status Bar --}}
                        <div id="syllabus-upload-status" class="hidden mt-3 p-3.5 rounded-xl text-xs font-semibold flex items-center justify-between"></div>
                    </div>

                    {{-- Scheme Parameters --}}
                    @php
                        $copoDecoded = $practicalCourseFile ? json_decode($practicalCourseFile->parsed_copo, true) : [];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3">
                            <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Credits</span>
                            <span class="text-base font-bold text-slate-900 mt-0.5 block" id="meta-credits">{{ $copoDecoded['credit'] ?? '1' }} Credits</span>
                        </div>
                        <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3">
                            <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Proposed Hours</span>
                            <span class="text-base font-bold text-slate-900 mt-0.5 block" id="meta-hours">{{ $copoDecoded['total_hours'] ?? '30' }} hrs</span>
                        </div>
                        <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3">
                            <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">L:T:P:R</span>
                            <span class="text-base font-bold font-mono text-slate-900 mt-0.5 block" id="meta-ltpr">{{ $copoDecoded['l_t_p_r'] ?? '0:0:2:0' }}</span>
                        </div>
                        <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3">
                            <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">CIE Marks</span>
                            <span class="text-base font-bold text-slate-900 mt-0.5 block" id="meta-cie">{{ $copoDecoded['cie_marks'] ?? '60' }} M</span>
                        </div>
                        <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3">
                            <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">ESE Marks</span>
                            <span class="text-base font-bold text-slate-900 mt-0.5 block" id="meta-ese">{{ $copoDecoded['ese_marks'] ?? '40' }} M</span>
                        </div>
                    </div>
                </div>

                <!-- Practical Course Outcomes (COs) Section -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
                                <span class="material-symbols-rounded text-base">stars</span>
                            </span>
                            <h3 class="font-bold text-slate-900 text-base">Practical Course Outcomes (COs)</h3>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg border border-slate-200">
                            {{ count($cosList ?? []) }} Outcomes
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="co-outcomes-container">
                        @php
                            $cosList = $practicalCourseFile ? json_decode($practicalCourseFile->parsed_cos, true) : [];
                        @endphp
                        @forelse($cosList as $co)
                        @php
                            $cog = strtolower($co['cognitive_level'] ?? 'apply');
                            $badgeClasses = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                            if (str_contains($cog, 'understand')) {
                                $badgeClasses = 'bg-blue-50 text-blue-700 border-blue-200';
                            } elseif (str_contains($cog, 'analy') || str_contains($cog, 'eval')) {
                                $badgeClasses = 'bg-purple-50 text-purple-700 border-purple-200';
                            }
                        @endphp
                        <div class="p-4 rounded-xl bg-slate-50/60 border border-slate-200/80 hover:border-blue-300 transition-all space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-bold font-mono text-xs border border-blue-200">{{ $co['id'] }}</span>
                                @if(!empty($co['cognitive_level']))
                                <span class="px-2 py-0.5 rounded-md font-semibold text-xs border {{ $badgeClasses }}">{{ $co['cognitive_level'] }}</span>
                                @endif
                            </div>
                            <p class="text-sm font-medium text-slate-800 leading-relaxed">{{ $co['description'] }}</p>
                        </div>
                        @empty
                        <div class="col-span-2 text-center py-6 text-slate-500 text-sm italic bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            Practical syllabus outcomes not uploaded yet.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- CO-PO Articulation Matrix Section -->
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
                                    <th class="p-3 text-left pl-4 w-24">CO</th>
                                    @for($p = 1; $p <= 11; $p++)
                                    <th class="p-3 font-mono">PO{{ $p }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @php
                                    $mappings = $copoDecoded['mappings'] ?? [];
                                @endphp
                                @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coId)
                                <tr class="hover:bg-slate-50/80 transition-all">
                                    <td class="p-3 text-left font-bold text-blue-700 pl-4 font-mono">{{ $coId }}</td>
                                    @for($p = 1; $p <= 11; $p++)
                                    @php
                                        $poVal = $mappings[$coId]["PO$p"] ?? '-';
                                        $cellClass = 'text-slate-400 font-normal';
                                        if ($poVal == '3') $cellClass = 'font-bold text-emerald-700 bg-emerald-50/60';
                                        elseif ($poVal == '2') $cellClass = 'font-bold text-blue-700 bg-blue-50/60';
                                        elseif ($poVal == '1') $cellClass = 'font-semibold text-slate-700 bg-slate-50';
                                    @endphp
                                    <td class="p-2.5 font-mono text-sm {{ $cellClass }}">
                                        {{ $poVal }}
                                    </td>
                                    @endfor
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


            <!-- TAB: List of Experiments -->
            <div id="tab-experiments" class="tab-content bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 hidden">
                {{-- Header --}}
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;padding-bottom:16px;border-bottom:1px solid var(--border);">
                    <div>
                        <h2 style="font-size:16px;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:8px;margin:0;">
                            <i class="fa-solid fa-list-check" style="color:#818cf8;"></i> Course Experiments Inventory
                        </h2>
                        <p style="font-size:13px;color:var(--text-secondary);margin:4px 0 0 0;">
                            Edit experiment details inline and click Save List to update.
                            @if($practicalCourseFile && count($practicalCourseFile->getExperimentsArray()) > 0)
                                <span style="margin-left:8px;padding:2px 10px;background:rgba(48,209,88,.12);border:1px solid rgba(48,209,88,.25);color:#30d158;border-radius:6px;font-size:12px;font-weight:600;">
                                    {{ count($practicalCourseFile->getExperimentsArray()) }} experiments
                                </span>
                            @endif
                        </p>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                        <button id="btn-load-pdf" onclick="loadFromPdfExperiments()" class="exp-btn-touch" style="padding:8px 16px;background:rgba(239,68,68,.2);border:1px solid rgba(239,68,68,.5);color:#fca5a5;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
                            <i class="fa-solid fa-file-pdf"></i> Load from PDF
                        </button>
                        <button id="btn-add-row" onclick="addNewExperimentRow()" class="exp-btn-touch" style="padding:8px 16px;background:#3b5bdb;border:1px solid #4c6ef5;color:#fff;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
                            <i class="fa-solid fa-plus"></i> Add Row
                        </button>
                        <button id="save-experiments-btn" onclick="saveExperimentsInventory()" class="exp-btn-touch" style="padding:8px 18px;background:#087f5b;border:1px solid #0ca678;color:#fff;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;">
                            <i class="fa-solid fa-floppy-disk"></i> Save List
                        </button>
                    </div>
                </div>

                {{-- Status banner (shown by JS) --}}
                <div id="exp-pdf-status" style="display:none;margin-top:10px;padding:10px 14px;border-radius:8px;font-size:13px;font-weight:600;align-items:center;gap:8px;"></div>

                {{-- Table --}}
                <div style="margin-top:14px;overflow-x:auto;">
                    <table id="experiments-inventory-table" style="width:100%;border-collapse:collapse;font-size:13px;color:var(--text-primary);">
                        <thead>
                            <tr style="background:var(--bg-stripe);border-bottom:2px solid var(--border-light);">
                                <th style="padding:10px 12px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;width:90px;">Expt No</th>
                                <th style="padding:10px 12px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;">Experiment Title</th>
                                <th style="padding:10px 12px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;">Practical Outcomes / Details</th>
                                <th style="padding:10px 12px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;width:90px;">CO</th>
                                <th style="padding:10px 12px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;width:70px;">Hrs</th>
                                <th style="padding:10px 12px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;width:50px;">Del</th>
                            </tr>
                        </thead>
                        <tbody id="experiments-rows-container">
                            @php
                                $expList  = $practicalCourseFile ? $practicalCourseFile->getExperimentsArray() : [];
                                $coOptions = ['CO1','CO2','CO3','CO4','CO5','CO6'];
                                $inputStyle = 'background:#0f1117;border:1px solid #2a2d3e;color:#e2e5f0;border-radius:6px;padding:6px 8px;font-size:13px;width:100%;outline:none;';
                            @endphp
                            @if(count($expList) > 0)
                                @foreach($expList as $idx => $exp)
                                <tr style="border-bottom:1px solid var(--border);transition:background .1s;" onmouseover="this.style.background='var(--bg-card-hover)'" onmouseout="this.style.background='transparent'">
                                    <td style="padding:6px 8px;">
                                        <input type="text" class="exp-no" style="{{ $inputStyle }}width:80px;font-family:monospace;" value="{{ $exp['expt_no'] ?? '' }}">
                                    </td>
                                    <td style="padding:6px 8px;">
                                        <input type="text" class="exp-title" style="{{ $inputStyle }}" value="{{ $exp['title'] ?? '' }}">
                                    </td>
                                    <td style="padding:6px 8px;">
                                        <input type="text" class="exp-desc" style="{{ $inputStyle }}color:#a0a7be;" value="{{ $exp['description'] ?? '' }}">
                                    </td>
                                    <td style="padding:6px 8px;">
                                        <select class="exp-co" style="{{ $inputStyle }}">
                                            @foreach($coOptions as $co)
                                            <option value="{{ $co }}" {{ ($exp['co'] ?? '') === $co ? 'selected' : '' }}>{{ $co }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="padding:6px 8px;text-align:center;">
                                        <input type="number" class="exp-hours" style="{{ $inputStyle }}width:60px;text-align:center;" value="{{ $exp['hours'] ?? 2 }}" min="1" max="6">
                                    </td>
                                    <td style="padding:6px 8px;text-align:center;">
                                        <button onclick="removeExperimentRow(this)" style="width:32px;height:32px;background:#7f1d1d;border:1px solid #991b1b;color:#fca5a5;border-radius:6px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;" title="Remove">
                                            <i class="fa-solid fa-trash-can" style="font-size:11px;"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr id="exp-empty-row">
                                    <td colspan="6" class="p-8 text-center text-slate-400 italic text-sm">
                                        <i class="fa-solid fa-inbox text-slate-600 text-2xl block mb-2"></i>
                                        No experiments loaded yet.<br>
                                        <span class="text-xs">Click <strong class="text-red-400">Load from PDF</strong> to fetch from the uploaded syllabus.</span>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB: Lesson Planner -->
            <div id="tab-lesson_plan" class="tab-content bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-calendar-day text-slate-500"></i> Lab Lesson Planner splitup
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Split content modularly. Generates timeline including two series exams and open ended project.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <select id="lesson_planner_mode" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-blue-500 font-bold shadow-xs">
                            <option value="single">Single/Whole Class Batch</option>
                            <option value="split">Split Batch (Batch A/B)</option>
                        </select>
                        <button onclick="generateLessonTimeline()" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                            <i class="fa-solid fa-arrows-rotate"></i> Generate Planner
                        </button>
                        <a href="/r26/classroom/lesson-plan/print/{{ $batchSubject->id }}" target="_blank" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs rounded-xl font-bold transition flex items-center gap-1.5 shadow-xs">
                            <i class="fa-solid fa-print"></i> Print
                        </a>
                    </div>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-left text-xs border border-slate-200 border-collapse divide-y divide-slate-100" id="lesson-plan-table">
                        <thead>
                            <tr class="bg-slate-50 font-bold text-slate-700 border-b border-slate-200">
                                <th class="p-3 w-16">Day No</th>
                                <th class="p-3 w-28">Sub-Batch</th>
                                <th class="p-3 w-36">Proposed Date</th>
                                <th class="p-3 w-36">Actual Date</th>
                                <th class="p-3">Topic Content / Target Experiment</th>
                                <th class="p-3 w-24">Mapped CO</th>
                                <th class="p-3 w-20">Hours</th>
                                <th class="p-3 w-28">Pedagogy</th>
                                <th class="p-3 w-28">Status</th>
                            </tr>
                        </thead>
                        <tbody id="lesson-plan-rows-container">
                            @forelse($lessonPlans as $lp)
                            <tr class="lesson-plan-row hover:bg-slate-50/80 border-b border-slate-100 transition-colors" data-id="{{ $lp->id }}">
                                <td class="p-3 font-mono">{{ $lp->day_no }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $lp->sub_batch == 'Batch A' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : ($lp->sub_batch == 'Batch B' ? 'bg-cyan-50 text-cyan-700 border border-cyan-200' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                                        {{ $lp->sub_batch ?? 'Whole' }}
                                    </span>
                                </td>
                                <td class="p-2">
                                    <input type="date" value="{{ $lp->proposed_date }}" class="lp-proposed px-2 py-1 bg-white border border-slate-200 rounded text-slate-800 focus:outline-none focus:border-blue-500 w-full font-sans shadow-2xs">
                                </td>
                                <td class="p-2">
                                    <input type="date" value="{{ $lp->actual_date }}" class="lp-actual px-2 py-1 bg-white border border-slate-200 rounded text-slate-800 focus:outline-none focus:border-blue-500 w-full font-sans shadow-2xs">
                                </td>
                                <td class="p-2">
                                    <input type="text" value="{{ $lp->topic_content }}" class="lp-topic px-2 py-1 bg-white border border-slate-200 rounded text-slate-800 focus:outline-none focus:border-blue-500 w-full shadow-2xs">
                                </td>
                                <td class="p-2">
                                    <select class="lp-co bg-white border border-slate-200 rounded px-1 py-1 text-slate-800 focus:outline-none focus:border-blue-500 w-full shadow-2xs">
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coId)
                                        <option value="{{ $coId }}" {{ $lp->co_id == $coId ? 'selected' : '' }}>{{ $coId }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-2">
                                    <input type="number" value="{{ $lp->allocated_hours }}" class="lp-hours px-2 py-1 bg-white border border-slate-200 rounded text-slate-800 text-center focus:border-blue-500 w-14 shadow-2xs">
                                </td>
                                <td class="p-2">
                                    <select class="lp-pedagogy bg-white border border-slate-200 rounded px-1 py-1 text-slate-800 focus:outline-none focus:border-blue-500 w-full shadow-2xs">
                                        <option value="Practical" {{ $lp->pedagogy == 'Practical' ? 'selected' : '' }}>Practical</option>
                                        <option value="Exam" {{ $lp->pedagogy == 'Exam' ? 'selected' : '' }}>Exam</option>
                                        <option value="Lecture" {{ $lp->pedagogy == 'Lecture' ? 'selected' : '' }}>Lecture</option>
                                    </select>
                                </td>
                                <td class="p-2">
                                    <select class="lp-status bg-white border border-slate-200 rounded px-1 py-1 text-slate-800 focus:outline-none focus:border-blue-500 w-full font-bold shadow-2xs">
                                        <option value="Pending" {{ $lp->status == 'Pending' ? 'selected' : '' }} class="text-amber-500">Pending</option>
                                        <option value="Completed" {{ $lp->status == 'Completed' ? 'selected' : '' }} class="text-emerald-500">Completed</option>
                                    </select>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="p-6 text-center text-slate-500 italic" colspan="10">No planner generated yet. Select batch option and click Generate.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($lessonPlans->isNotEmpty())
                <div class="mt-6 pt-4 border-t border-slate-200 flex justify-end">
                    <button onclick="saveLessonPlannerBulk()" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl shadow-lg transition">
                        Save Planner Entries
                    </button>
                </div>
                @endif
            </div>

            <!-- TAB: Continuous Log (Table 2.2) -->
            <div id="tab-table22" class="tab-content hidden">
                <!-- Header Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-purple-600 bg-purple-50 px-2.5 py-1 rounded-md border border-purple-100 font-mono">TABLE 2.2</span>
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100 font-mono">Continuous Daywork Evaluation</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mt-1">Daywork Log &mdash; Continuous Practical Evaluation</h3>
                            <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">Continuous laboratory evaluation log out of 50. Total averages scale to 30 marks. Select an experiment to load and enter marks.</p>
                        </div>

                        <!-- Experiment Controls -->
                        <div class="flex flex-wrap items-end gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Select Experiment</label>
                                <select id="exp_select" onchange="switchActiveExperiment(this.value)" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-purple-500 font-mono font-bold shadow-2xs">
                                    <!-- Dynamic options -->
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Exp. No</label>
                                <input type="text" id="exp_no" value="Exp 1" oninput="onManualExpNoChange()" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-purple-500 font-mono w-24 shadow-2xs">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Experiment Title</label>
                                <input type="text" id="exp_title" placeholder="e.g. Verification of Ohm's Law" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-purple-500 w-52 sm:w-64 shadow-2xs">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Register Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden mt-4">
                    <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Enrolled Students &mdash; Daywork Evaluation Register</span>
                        </div>
                        <span class="text-xs font-bold text-slate-500 font-mono">{{ $students->count() }} Students</span>
                    </div>

                    <div class="divide-y divide-slate-100 max-h-[60vh] overflow-y-auto">
                        @foreach($students as $index => $student)
                        @php
                            $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                            $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                            $expLog = $experimentLogs->get('Exp 1') ? $experimentLogs->get('Exp 1')->where('reg_no', $student->reg_no)->first() : null;
                        @endphp
                        <div class="student-row flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-4 sm:px-6 py-3.5 hover:bg-slate-50/80 transition-colors"
                             data-reg-no="{{ $student->reg_no }}"
                             data-batch="{{ $batchVal }}">
                            <!-- Student Identity -->
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-700 font-black text-sm flex items-center justify-center border border-indigo-100 flex-shrink-0">
                                    {{ $student->roll_no ?? ($index + 1) }}
                                </span>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900">{{ $student->name }}</h4>
                                    <span class="text-xs font-mono text-slate-500">{{ $student->reg_no }}</span>
                                </div>
                            </div>

                            <!-- Controls -->
                            <div class="flex items-center gap-3 flex-wrap">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Lab Batch:</span>
                                    <select onchange="updateLabBatch('{{ $student->reg_no }}', this.value)" class="student-batch-select-{{ $student->reg_no }} bg-white border border-slate-200 text-sm rounded-lg py-1 px-2 text-slate-700 focus:outline-none focus:border-indigo-400 shadow-2xs cursor-pointer">
                                        <option value="" {{ empty($savedBatch) ? '' : ($batchVal == 'Unassigned' || empty($batchVal) ? 'selected' : '') }}>Unassigned</option>
                                        <option value="Batch A" {{ $batchVal == 'Batch A' ? 'selected' : '' }}>Batch A</option>
                                        <option value="Batch B" {{ $batchVal == 'Batch B' ? 'selected' : '' }}>Batch B</option>
                                    </select>
                                </div>

                                <button onclick="openGradingModal('{{ $student->reg_no }}', 'table22')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 shadow-xs border border-indigo-500 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Evaluate
                                </button>

                                <div class="text-right min-w-[80px]">
                                    <span class="text-xs uppercase font-bold text-slate-400 block">Score</span>
                                    <span id="score-text-exp-{{ $student->reg_no }}" class="font-mono text-sm font-bold text-slate-800">
                                        {{ $expLog ? floatval($expLog->total_score_50) : '0.00' }} / 50
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-4 border-t border-slate-100 flex justify-end bg-slate-50/40">
                        <button onclick="submitExpMarks()" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-xs transition-all cursor-pointer">
                            Save Continuous Log
                        </button>
                    </div>
                </div>
            </div>

            <!-- TAB: Open-Ended (Table 2.3) -->
            <div id="tab-table23" class="tab-content bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 hidden">
                <div class="pb-6 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb text-amber-400"></i> Open-Ended Experiments
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Assess originality and execution of open-ended projects out of 50. Normalized to 10 marks.</p>
                </div>

                <div class="mt-6 space-y-3 max-h-[60vh] overflow-y-auto pr-1">
                    @foreach($students as $index => $student)
                    @php
                        $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                        $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                        $openLog = $openEndedLogs->get($student->reg_no);
                    @endphp
                    <div class="student-row p-4 rounded-2xl bg-white border border-slate-200 hover:border-slate-300 hover:shadow-sm transition-all" 
                         data-reg-no="{{ $student->reg_no }}" 
                         data-batch="{{ $batchVal }}">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 font-black text-xs flex items-center justify-center border border-amber-500/20">
                                    {{ $student->roll_no ?? ($index + 1) }}
                                </span>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">{{ $student->name }}</h4>
                                    <span class="text-xs font-mono text-slate-400">{{ $student->reg_no }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <input type="text" id="open-title-{{ $student->reg_no }}" value="{{ $openLog ? $openLog->project_title : '' }}" placeholder="Project Title..." class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-blue-500 w-44 shadow-2xs">

                                <button onclick="openGradingModal('{{ $student->reg_no }}', 'table23')" class="px-4 py-2 bg-amber-600 hover:bg-amber-550 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-md shadow-amber-900/30 border border-amber-500">
                                    <i class="fa-solid fa-sliders"></i> Evaluate
                                </button>

                                <div class="text-right min-w-[70px]">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Score</span>
                                    <span id="score-text-open-{{ $student->reg_no }}" class="font-mono text-xs font-bold text-slate-800">
                                        {{ $openLog ? floatval($openLog->total_score_50) : '0.00' }} / 50
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-slate-200 flex justify-end">
                    <button onclick="submitOpenEndedMarks()" class="px-6 py-3 bg-amber-600 hover:bg-amber-550 text-white font-bold text-xs rounded-xl shadow transition">
                        Save Open-Ended Marks
                    </button>
                </div>
            </div>

            <!-- TAB: Series QP & Outline -->
            <div id="tab-series_qp" class="tab-content bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 hidden">
                <div class="flex items-center justify-between pb-6 border-b border-slate-200">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-clipboard-question text-violet-600"></i> Series Exam Question Paper Setup
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Configure practical examination blueprints. Evaluate mapped outcomes separately.</p>
                    </div>
                    <select id="series_qp_select" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-violet-500 font-bold shadow-xs" onchange="loadSeriesQpConfig(this.value)">
                        <option value="Series 1">Practical Series Exam 1</option>
                        <option value="Series 2">Practical Series Exam 2</option>
                    </select>
                </div>

                <!-- Form configs -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="space-y-4 md:col-span-1">
                        <div>
                            <label class="block text-[11px] font-black uppercase text-slate-500 tracking-wider mb-1.5">Max Marks</label>
                            <input type="number" id="series_qp_max_marks" value="40" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-blue-500 w-full shadow-2xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black uppercase text-slate-500 tracking-wider mb-1.5">Duration (mins)</label>
                            <input type="number" id="series_qp_duration" value="120" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-blue-500 w-full shadow-2xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black uppercase text-slate-500 tracking-wider mb-1.5">Target COs</label>
                            <div class="flex flex-wrap gap-3 mt-1" id="series_qp_co_checks">
                                @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coId)
                                <label class="flex items-center gap-2 text-xs font-mono cursor-pointer select-none">
                                    <input type="checkbox" value="{{ $coId }}" class="co-checkbox rounded border-slate-300 text-blue-600 focus:ring-0"> {{ $coId }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-black uppercase text-slate-500 tracking-wider mb-1.5">Instructions & Question Outline (JSON / Instructions)</label>
                        <textarea id="series_qp_outline" rows="8" placeholder="Enter practical experiment choices, setup specifications or instructions..." class="p-3 bg-white border border-slate-200 rounded-2xl text-xs text-slate-800 focus:outline-none focus:border-blue-500 w-full font-mono shadow-2xs"></textarea>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-200 flex justify-end gap-3">
                    <button onclick="saveSeriesQpOutline()" class="px-6 py-3 bg-purple-650 hover:bg-purple-600 text-white font-bold text-xs rounded-xl shadow-lg transition">
                        Save Question Blueprint
                    </button>
                </div>
            </div>

            <!-- TAB: Series Marks Entry (Table 3.1) -->
            <div id="tab-table31" class="tab-content bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 hidden">
                <div class="flex items-center justify-between pb-6 border-b border-slate-200">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-signature text-violet-600"></i> Series Marks (CA)
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Practical examinations out of 40. Consolidated average represents 15 CIA marks.</p>
                    </div>
                    <select id="series_no" onchange="switchSeriesExam(this.value)" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-violet-500 font-bold shadow-xs">
                        <option value="Series 1">Series Exam 1</option>
                        <option value="Series 2">Series Exam 2</option>
                    </select>
                </div>

                <div class="mt-6 space-y-3 max-h-[60vh] overflow-y-auto pr-1">
                    @foreach($students as $index => $student)
                    @php
                        $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                        $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                        $series1Log = $seriesExamLogs->get('Series 1') ? $seriesExamLogs->get('Series 1')->where('reg_no', $student->reg_no)->first() : null;
                        $series2Log = $seriesExamLogs->get('Series 2') ? $seriesExamLogs->get('Series 2')->where('reg_no', $student->reg_no)->first() : null;
                    @endphp
                    <div class="student-row p-4 rounded-2xl bg-white border border-slate-200 hover:border-slate-300 hover:shadow-sm transition-all" 
                         data-reg-no="{{ $student->reg_no }}" 
                         data-batch="{{ $batchVal }}">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-purple-500/10 text-violet-600 font-black text-xs flex items-center justify-center border border-purple-500/20">
                                    {{ $student->roll_no ?? ($index + 1) }}
                                </span>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">{{ $student->name }}</h4>
                                    <span class="text-xs font-mono text-slate-400">{{ $student->reg_no }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <button onclick="openGradingModal('{{ $student->reg_no }}', 'table31')" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-md shadow-purple-900/30 border border-purple-550">
                                    <i class="fa-solid fa-sliders"></i> Evaluate
                                </button>

                                <div class="text-right min-w-[70px]">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Score</span>
                                    <span id="score-text-series-{{ $student->reg_no }}" class="font-mono text-xs font-bold text-slate-800"
                                          data-s1="{{ $series1Log ? floatval($series1Log->total_score_40) : '0.00' }}"
                                          data-s2="{{ $series2Log ? floatval($series2Log->total_score_40) : '0.00' }}">
                                        {{ $series1Log ? floatval($series1Log->total_score_40) : '0.00' }} / 40
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-slate-200 flex justify-end">
                    <button onclick="submitSeriesMarks()" class="px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs rounded-xl shadow transition">
                        Save Series Marks
                    </button>
                </div>
            </div>

            <!-- TAB: Surveys & Feedback -->
            <div id="tab-surveys" class="tab-content bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 hidden">
                <div class="pb-6 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-comment-medical text-slate-500"></i> Feedback & Surveys Module
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Initiate and analyze mid-semester feedback and course exit surveys dynamically mapped to COs.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Mid-sem panel -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2"><i class="fa-solid fa-hourglass-half text-amber-500"></i> Mid-Semester Survey</h3>
                        <p class="text-xs text-slate-400">Collects quick feedback on lab delivery, pace, and evaluation clarity.</p>
                        <div class="flex items-center gap-2 pt-2">
                            <button onclick="manageSurvey('mid', 'initiate')" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl transition">Initiate</button>
                            <button onclick="manageSurvey('mid', 'close')" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-bold rounded-xl transition shadow-xs">Close</button>
                        </div>
                    </div>

                    <!-- Exit Survey panel -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Course Exit Survey</h3>
                        <p class="text-xs text-slate-400">Assesses direct attainment level of the 4 course outcomes (COs) upon course completion.</p>
                        <div class="flex items-center gap-2 pt-2">
                            <button onclick="manageSurvey('exit', 'initiate')" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl transition">Initiate</button>
                            <button onclick="manageSurvey('exit', 'close')" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-bold rounded-xl transition shadow-xs">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB: ESE Entry -->
            <div id="tab-ese" class="tab-content bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 hidden">
                <div class="pb-6 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-check text-emerald-600"></i> End Semester Evaluation (ESE) Marks
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Record the final external practical exam marks out of 40. Displays alongside internal totals.</p>
                </div>

                <div class="mt-6 space-y-3 max-h-[60vh] overflow-y-auto pr-1">
                    @foreach($students as $index => $student)
                    @php
                        $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                        $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                        $eseLog = $eseMarks->get($student->reg_no);
                    @endphp
                    <div class="student-row p-4 rounded-2xl bg-white border border-slate-200 hover:border-slate-300 hover:shadow-sm transition-all" 
                         data-reg-no="{{ $student->reg_no }}" 
                         data-batch="{{ $batchVal }}">

                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 font-black text-xs flex items-center justify-center border border-emerald-200">
                                    {{ $student->roll_no ?? ($index + 1) }}
                                </span>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">{{ $student->name }}</h4>
                                    <span class="text-xs font-mono text-slate-500">{{ $student->reg_no }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-[10px] uppercase font-bold text-slate-500 block">Marks (Max 40)</span>
                                <input type="number" id="ese-mark-{{ $student->reg_no }}" value="{{ $eseLog ? floatval($eseLog->ese_score) : '0' }}" min="0" max="40" step="0.5" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold font-mono text-slate-800 text-center w-20 focus:outline-none focus:border-emerald-500 shadow-2xs" oninput="recalculateCIA('{{ $student->reg_no }}')">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-slate-200 flex justify-end">
                    <button onclick="submitEseMarks()" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow transition">
                        Save ESE Marks
                    </button>
                </div>
            </div>

            <!-- TAB: Consolidated CIA Summary (100 Marks) -->
            <div id="tab-summary" class="tab-content bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 hidden">
                <div class="pb-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-award text-emerald-600"></i> Lab CIA Consolidated Register
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Continuous assessment summaries combined with external practical totals out of 100.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="/r26/classroom/practical/{{ $batchSubject->id }}/print/cia" target="_blank" class="px-4 py-2.5 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 text-emerald-700 text-xs rounded-xl font-bold transition flex items-center gap-1.5 shadow-xs">
                            <i class="fa-solid fa-print"></i> Print CIA Summary
                        </a>
                        <a href="/r26/classroom/practical/{{ $batchSubject->id }}/print/attainment" target="_blank" class="px-4 py-2.5 bg-blue-600/20 border border-blue-500/30 hover:bg-blue-600/35 text-slate-500 text-xs rounded-xl font-bold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-chart-line"></i> Attainment report
                        </a>
                    </div>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700 border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                                <th class="p-3">Roll / Reg No</th>
                                <th class="p-3">Student Name</th>
                                <th class="p-3 text-center">Batch</th>
                                <th class="p-3 text-center">Lab Work (30M)</th>
                                <th class="p-3 text-center">Series (15M)</th>
                                <th class="p-3 text-center">Open Ended (10M)</th>
                                <th class="p-3 text-center">Attendance (5M)</th>
                                <th class="p-3 text-center font-bold text-white">CIA Total (60M)</th>
                                <th class="p-3 text-center text-emerald-600 font-bold">ESE Marks (40M)</th>
                                <th class="p-3 text-center text-white font-black">Grand Total (100M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/40">
                            @foreach($students as $index => $student)
                            @php
                                $score = $consolidatedScores[$student->reg_no] ?? [];
                                $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                                $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                            @endphp
                            <tr class="student-row hover:bg-slate-50/80 border-b border-slate-100 transition-colors" 
                                data-reg-no="{{ $student->reg_no }}" 
                                data-batch="{{ $batchVal }}">
                                <td class="p-3 font-mono text-slate-500">{{ $student->reg_no }}</td>
                                <td class="p-3 font-bold text-white">{{ $student->name }}</td>
                                <td class="p-3 text-center">
                                    <span class="student-batch-badge-{{ $student->reg_no }} px-2 py-0.5 rounded text-[10px] font-bold {{ $batchVal == 'Batch A' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : ($batchVal == 'Batch B' ? 'bg-cyan-50 text-cyan-700 border border-cyan-200' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                                        {{ $batchVal }}
                                    </span>
                                </td>

                                <td class="p-3 text-center font-mono text-slate-500" id="cia-lab-work-{{ $student->reg_no }}">{{ $score['scaled_lab_work_30'] ?? '0.00' }}</td>
                                <td class="p-3 text-center font-mono text-violet-600" id="cia-series-{{ $student->reg_no }}">{{ $score['scaled_series_15'] ?? '0.00' }}</td>
                                <td class="p-3 text-center font-mono text-amber-400" id="cia-open-{{ $student->reg_no }}">{{ $score['scaled_open_ended_10'] ?? '0.00' }}</td>
                                <td class="p-3 text-center font-mono text-emerald-600">{{ $attendanceMarks[$student->reg_no]['mark'] ?? 5 }}</td>
                                <td class="p-3 text-center font-mono font-bold text-white" id="cia-total-{{ $student->reg_no }}">{{ $score['total_cia_60'] ?? '0.00' }}</td>
                                <td class="p-3 text-center font-mono text-emerald-600 font-bold" id="cia-ese-{{ $student->reg_no }}">{{ $score['ese_score_40'] ?? '0.00' }}</td>
                                <td class="p-3 text-center font-mono font-black text-sm text-emerald-600" id="cia-grand-{{ $student->reg_no }}">{{ $score['grand_total_100'] ?? '0.00' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @include('partials.virtual_learning_hub_tab', ['roomType' => 'Practical'])

        </section>

    </div>

    <!-- EVALUATION GRADING MODAL — CampusLynk Light Theme -->
    <div id="gradingModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex-col justify-end sm:justify-center p-4">
        <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-lg shadow-2xl flex flex-col">
            <!-- Header -->
            <div class="flex justify-between items-center border-b border-slate-100 px-5 py-4 flex-shrink-0">
                <div>
                    <h3 id="modalStudentName" class="font-bold text-slate-900 text-base">Student Name</h3>
                    <span id="modalStudentReg" class="text-xs font-mono text-slate-500">Reg No</span>
                </div>
                <button onclick="closeGradingModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all">
                    &times;
                </button>
            </div>

            <!-- Sliders -->
            <div id="modalSlidersContainer" class="space-y-3 px-5 py-4 max-h-[55vh] overflow-y-auto">
                <!-- Sliders populated dynamically -->
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between px-5 py-4 border-t border-slate-100 flex-shrink-0">
                <button onclick="navigateStudent(-1)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Prev
                </button>

                <div class="text-center">
                    <span class="text-xs uppercase font-bold text-slate-400 block">Total Score</span>
                    <span id="modalTotalScore" class="font-mono text-lg font-black text-slate-900">0.00</span>
                </div>

                <button onclick="navigateStudent(1)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-all flex items-center gap-1.5 cursor-pointer">
                    Next
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Scripting controls -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const subjectId = "{{ $batchSubject->id }}";
        
        const studentList = @json($students);
        const labBatches = @json($labBatches);
        const experimentLogs = @json($experimentLogs);
        const openEndedLogs = @json($openEndedLogs);
        const seriesExamLogs = @json($seriesExamLogs);
        const eseMarks = @json($eseMarks);
        const attendanceMarks = @json($attendanceMarks);
        const consolidatedScores = @json($consolidatedScores);

        let activeTab = 'outline';
        let activeBatchFilter = 'All';
        let currentStudentIndex = 0;


        const scoresState = {
            table22: {},
            table23: {},
            table31: {}
        };

        // Populate local JS scores cache
        studentList.forEach(s => {
            const reg = s.reg_no;

            const expLog = experimentLogs['Exp 1'] ? experimentLogs['Exp 1'].find(x => x.reg_no === reg) : null;
            scoresState.table22[reg] = expLog ? {
                c1: parseFloat(expLog.prep_punctuality),
                c2: parseFloat(expLog.setup_procedure),
                c3: parseFloat(expLog.observation_recording),
                c4: parseFloat(expLog.analysis_interpretation),
                c5: parseFloat(expLog.viva_voce),
                c6: parseFloat(expLog.teamwork_discipline)
            } : { c1:0, c2:0, c3:0, c4:0, c5:0, c6:0 };

            const openLog = openEndedLogs[reg];
            scoresState.table23[reg] = openLog ? {
                c1: parseFloat(openLog.originality_relevance),
                c2: parseFloat(openLog.objectives_plan),
                c3: parseFloat(openLog.execution_recording),
                c4: parseFloat(openLog.analysis_presentation),
                c5: parseFloat(openLog.teamwork_innovation)
            } : { c1:0, c2:0, c3:0, c4:0, c5:0 };

            const s1Log = seriesExamLogs['Series 1'] ? seriesExamLogs['Series 1'].find(x => x.reg_no === reg) : null;
            const s2Log = seriesExamLogs['Series 2'] ? seriesExamLogs['Series 2'].find(x => x.reg_no === reg) : null;
            scoresState.table31[reg] = {
                'Series 1': s1Log ? {
                    c1: parseFloat(s1Log.writeup_procedure),
                    c2: parseFloat(s1Log.setup_execution),
                    c3: parseFloat(s1Log.observation_result),
                    c4: parseFloat(s1Log.viva_voce),
                    c5: parseFloat(s1Log.record_completion)
                } : { c1:0, c2:0, c3:0, c4:0, c5:0 },
                'Series 2': s2Log ? {
                    c1: parseFloat(s2Log.writeup_procedure),
                    c2: parseFloat(s2Log.setup_execution),
                    c3: parseFloat(s2Log.observation_result),
                    c4: parseFloat(s2Log.viva_voce),
                    c5: parseFloat(s2Log.record_completion)
                } : { c1:0, c2:0, c3:0, c4:0, c5:0 }
            };
        });

        function handleVirtualLabBack(e) {
            if (e) e.preventDefault();
            if (window.opener && !window.opener.closed) {
                window.close();
                return false;
            }
            if (window.history.length <= 1) {
                window.close();
                return false;
            }
            if (document.referrer && document.referrer.indexOf(window.location.host) !== -1) {
                window.history.back();
                return false;
            }
            window.close();
            return false;
        }

        // Initialize experiment dropdown and handlers
        function initExperimentSelect() {
            const select = document.getElementById('exp_select');
            if (!select) return;
            select.innerHTML = '';
            
            // Collect unique experiment numbers from localExperiments and experimentLogs
            const uniqueExps = new Set();
            if (typeof localExperiments !== 'undefined' && localExperiments && localExperiments.length > 0) {
                localExperiments.forEach(e => {
                    if (e.expt_no) uniqueExps.add(e.expt_no);
                });
            }
            // Add any other experiments found in saved logs
            if (typeof experimentLogs !== 'undefined' && experimentLogs) {
                Object.keys(experimentLogs).forEach(k => {
                    uniqueExps.add(k);
                });
            }

            // Ensure at least "Exp 1" is present
            if (uniqueExps.size === 0) {
                uniqueExps.add("Exp 1");
            }

            // Convert to array and sort naturally
            const sorted = Array.from(uniqueExps).sort((a, b) => {
                return a.localeCompare(b, undefined, { numeric: true, sensitivity: 'base' });
            });

            sorted.forEach(expNo => {
                const opt = document.createElement('option');
                opt.value = expNo;
                opt.textContent = expNo;
                select.appendChild(opt);
            });

            // Set default value matching text box
            const activeExp = document.getElementById('exp_no')?.value || 'Exp 1';
            select.value = activeExp;
            
            updateExperimentTitleFromList(activeExp);
        }

        function updateExperimentTitleFromList(expNo) {
            // Find title from localExperiments
            const matched = (typeof localExperiments !== 'undefined' ? localExperiments : []).find(e => e.expt_no === expNo);
            if (matched && matched.title) {
                document.getElementById('exp_title').value = matched.title;
            } else {
                // Fallback: look in experimentLogs
                const logs = experimentLogs[expNo];
                if (logs && logs.length > 0 && logs[0].title) {
                    document.getElementById('exp_title').value = logs[0].title;
                } else {
                    document.getElementById('exp_title').value = '';
                }
            }
        }

        function switchActiveExperiment(expNo) {
            const expNoInput = document.getElementById('exp_no');
            if (expNoInput) expNoInput.value = expNo;
            updateExperimentTitleFromList(expNo);

            // Populate scoresState.table22 with the selected experiment's marks
            studentList.forEach(s => {
                const reg = s.reg_no;
                const expLog = experimentLogs[expNo] ? experimentLogs[expNo].find(x => x.reg_no === reg) : null;
                
                scoresState.table22[reg] = expLog ? {
                    c1: parseFloat(expLog.prep_punctuality) || 0,
                    c2: parseFloat(expLog.setup_procedure) || 0,
                    c3: parseFloat(expLog.observation_recording) || 0,
                    c4: parseFloat(expLog.analysis_interpretation) || 0,
                    c5: parseFloat(expLog.viva_voce) || 0,
                    c6: parseFloat(expLog.teamwork_discipline) || 0
                } : { c1:0, c2:0, c3:0, c4:0, c5:0, c6:0 };

                // Update the student row score display on screen
                const sum = (scoresState.table22[reg].c1||0) + (scoresState.table22[reg].c2||0) + (scoresState.table22[reg].c3||0) + (scoresState.table22[reg].c4||0) + (scoresState.table22[reg].c5||0) + (scoresState.table22[reg].c6||0);
                const scoreTextSpan = document.getElementById(`score-text-exp-${reg}`);
                if (scoreTextSpan) {
                    scoreTextSpan.innerText = sum.toFixed(2) + ' / 50';
                }

                // Recalculate consolidated CIA values for this student on the client side
                recalculateCIA(reg);
            });
        }

        function onManualExpNoChange() {
            const val = document.getElementById('exp_no').value;
            const select = document.getElementById('exp_select');
            if (select) {
                let exists = false;
                for (let i = 0; i < select.options.length; i++) {
                    if (select.options[i].value === val) {
                        select.value = val;
                        exists = true;
                        break;
                    }
                }
                if (!exists && val.trim() !== '') {
                    const opt = document.createElement('option');
                    opt.value = val;
                    opt.textContent = val;
                    select.appendChild(opt);
                    select.value = val;
                }
            }
            switchActiveExperiment(val);
        }

        // Initialize on load
        initExperimentSelect();

        // Initialise first tab active state and filter on page load
        document.getElementById('btn-outline')?.classList.add('active');
        
        // Tab triggers
        function switchTab(tabId) {
            activeTab = tabId;
            // Hide all tab content panels
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Remove active state from all buttons
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

            // Show target tab
            const panel = document.getElementById('tab-' + tabId);
            if (panel) panel.classList.remove('hidden');

            // Activate button
            const btn = document.getElementById('btn-' + tabId);
            if (btn) btn.classList.add('active');

            if (tabId === 'series_qp') {
                loadSeriesQpConfig('Series 1');
            }
            
            // Re-apply the current batch filter to the newly shown tab
            filterLabBatch(activeBatchFilter);
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('workspaceSidebar');
            sidebar.classList.toggle('sidebar-collapsed');
            const btn = document.getElementById('sidebar-toggle-btn');
            if (sidebar.classList.contains('sidebar-collapsed')) {
                btn.style.color = 'var(--accent)';
            } else {
                btn.style.color = '';
            }
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }

        function filterLabBatch(batch) {
            activeBatchFilter = batch;
            document.querySelectorAll('.batch-filter-btn').forEach(b => b.classList.remove('vl-btn-primary'));

            const btnMap = { 'All': 'All', 'Unassigned': 'Unassigned', 'Batch A': 'A', 'Batch B': 'B' };
            const id = btnMap[batch];
            const activeBtn = document.getElementById(`batch-filter-${id}`);
            if (activeBtn) activeBtn.classList.add('vl-btn-primary');

            document.querySelectorAll('.student-row').forEach(row => {
                const b = row.getAttribute('data-batch') || 'Unassigned';
                if (batch === 'All') row.classList.remove('hidden');
                else if (batch === 'Unassigned' && (b === 'Unassigned' || b === '')) row.classList.remove('hidden');
                else if (b === batch) row.classList.remove('hidden');
                else row.classList.add('hidden');
            });
        }

        async function updateLabBatch(regNo, value) {
            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/lab-batch`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ reg_no: regNo, lab_batch: value })
                });
                const data = await res.json();
                if (data.success) {
                    const displayVal = value || 'Unassigned';
                    
                    // 1. Update data-batch attributes for visibility matching
                    document.querySelectorAll(`.student-row[data-reg-no="${regNo}"]`).forEach(row => {
                        row.setAttribute('data-batch', displayVal);
                    });
                    
                    // 2. Sync all other select boxes for this student in other views
                    document.querySelectorAll(`.student-batch-select-${regNo}`).forEach(sel => {
                        sel.value = value;
                    });
                    
                    // 3. Sync badge in consolidated summary table
                    document.querySelectorAll(`.student-batch-badge-${regNo}`).forEach(badge => {
                        badge.innerText = displayVal;
                        badge.className = `student-batch-badge-${regNo} px-2 py-0.5 rounded text-[10px] font-bold ` + 
                            (displayVal === 'Batch A' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 
                            (displayVal === 'Batch B' ? 'bg-cyan-50 text-cyan-700 border border-cyan-200' : 'bg-slate-100 text-slate-600 border border-slate-200'));
                    });
                    
                    // 4. Re-filter view
                    filterLabBatch(activeBatchFilter);
                }
            } catch(e) {
                console.error(e);
            }
        }

        // Apply initial filter state on page load
        filterLabBatch('All');


        function togglePracticalSyllabusWorkspace() {
            const ws = document.getElementById('practicalSyllabusWorkspace');
            if (ws) ws.classList.toggle('hidden');
        }

        function handlePracticalDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropzone = document.getElementById('practicalSyllabusDropzone');
            if (dropzone) dropzone.classList.add('border-blue-500', 'bg-blue-50/60');
        }

        function handlePracticalDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropzone = document.getElementById('practicalSyllabusDropzone');
            if (dropzone) dropzone.classList.remove('border-blue-500', 'bg-blue-50/60');
        }

        function handlePracticalFileDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropzone = document.getElementById('practicalSyllabusDropzone');
            if (dropzone) dropzone.classList.remove('border-blue-500', 'bg-blue-50/60');

            const files = e.dataTransfer.files;
            if (!files || files.length === 0) return;
            const file = files[0];
            if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
                alert('Please drop a valid PDF file.');
                return;
            }
            const input = document.getElementById('syllabus_pdf_input');
            if (input) {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                uploadSyllabusPdf();
            }
        }

        // Syllabus Upload
        async function uploadSyllabusPdf() {
            const input = document.getElementById('syllabus_pdf_input');
            if (input.files.length === 0) return;

            const btn    = document.getElementById('syllabus-upload-btn');
            const status = document.getElementById('syllabus-upload-status');

            // Disable button, show spinner
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Extracting Structure...';
                btn.classList.add('opacity-70', 'cursor-not-allowed');
            }
            status.className = 'mt-3 p-3.5 rounded-xl text-xs font-semibold flex items-center gap-2.5 bg-blue-50 border border-blue-200 text-blue-800';
            status.innerHTML = '<svg class="w-4 h-4 animate-spin text-blue-600 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> <span>Uploading PDF and extracting Course Outcomes, CO-PO Matrix, and Lab Experiments list...</span>';
            status.classList.remove('hidden');

            const formData = new FormData();
            formData.append('syllabus_file', input.files[0]);
            formData.append('_token', csrfToken);

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/syllabus`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    status.className = 'mt-3 p-3.5 rounded-xl text-xs font-semibold flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800';
                    status.innerHTML = `<svg class="w-4 h-4 text-emerald-600 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> <span>${data.message || 'Syllabus parsed successfully.'} Reloading...</span>`;
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    status.className = 'mt-3 p-3.5 rounded-xl text-xs font-semibold flex items-center justify-between bg-rose-50 border border-rose-200 text-rose-800';
                    status.innerHTML = `<span>${data.message || 'Upload failed. Please try again.'}</span> <button type="button" onclick="document.getElementById('syllabus_pdf_input').click()" class="px-2.5 py-1 bg-rose-100 text-rose-900 rounded font-bold">Retry</button>`;
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> <span>Upload Syllabus PDF</span>';
                        btn.classList.remove('opacity-70', 'cursor-not-allowed');
                    }
                }
            } catch(e) {
                status.className = 'mt-3 p-3.5 rounded-xl text-xs font-semibold flex items-center justify-between bg-rose-50 border border-rose-200 text-rose-800';
                status.innerHTML = `<span>Network error: ${e.message}.</span> <button type="button" onclick="document.getElementById('syllabus_pdf_input').click()" class="px-2.5 py-1 bg-rose-100 text-rose-900 rounded font-bold">Retry</button>`;
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> <span>Upload Syllabus PDF</span>';
                    btn.classList.remove('opacity-70', 'cursor-not-allowed');
                }
            }
        }


        // CO-PO Matrix Mappings
        async function saveCoPoMatrix() {
            const mappings = {};
            document.querySelectorAll('.copo-select').forEach(el => {
                const co = el.getAttribute('data-co');
                const po = el.getAttribute('data-po');
                if (!mappings[co]) mappings[co] = {};
                mappings[co][po] = el.value;
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/copo`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ mappings: mappings })
                });
                const data = await res.json();
                alert(data.message);
            } catch(e) {
                alert("Failed to save mappings.");
            }
        }

        // Manual Experiments Inventory List (for JS-driven operations: Load from PDF, Add Row)
        let localExperiments = @json($practicalCourseFile ? ($practicalCourseFile->getExperimentsArray()) : []);
        const pdfParsedExperiments = @json($practicalCourseFile ? (json_decode($practicalCourseFile->parsed_experiments, true) ?? []) : []);
        // NOTE: initial rows are server-rendered by Blade; JS only re-renders when user clicks Load from PDF or Add Row


        function loadFromPdfExperiments() {
            const statusEl = document.getElementById('exp-pdf-status');
            if (!pdfParsedExperiments || pdfParsedExperiments.length === 0) {
                if (statusEl) {
                    statusEl.className = 'mt-3 p-3 rounded-xl text-sm font-semibold flex items-center gap-2 bg-amber-950/40 border border-amber-700/40 text-amber-300';
                    statusEl.style.display = 'flex';
                    statusEl.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> No experiments found in uploaded PDF. Please upload a syllabus PDF first from the Course Outline tab.';
                }
                alert('No experiments found in uploaded PDF. Please upload a syllabus PDF first from the Course Outline tab.');
                return;
            }
            localExperiments = JSON.parse(JSON.stringify(pdfParsedExperiments));
            loadExperimentsInventoryTable();
            if (statusEl) {
                statusEl.className = 'mt-3 p-3 rounded-xl text-sm font-semibold flex items-center gap-2 bg-emerald-950/40 border border-emerald-700/40 text-emerald-300';
                statusEl.style.display = 'flex';
                statusEl.innerHTML = `<i class="fa-solid fa-circle-check"></i> Loaded ${pdfParsedExperiments.length} experiments from the syllabus PDF. Edit below and click Save List.`;
            }
        }

        function loadExperimentsInventoryTable() {
            const container = document.getElementById('experiments-rows-container');
            if (!container) return;

            const IS = 'background:#0f1117;border:1px solid #2a2d3e;color:#e2e5f0;border-radius:6px;padding:6px 8px;font-size:13px;width:100%;outline:none;';

            if (!localExperiments || localExperiments.length === 0) {
                container.innerHTML = `<tr><td colspan="6" style="padding:40px;text-align:center;color:#5a6180;font-style:italic;"><i class="fa-solid fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;color:#323552;"></i>No experiments loaded.<br><span style="font-size:12px;">Click <strong style="color:#fca5a5;">Load from PDF</strong> to fetch experiments.</span></td></tr>`;
                return;
            }

            const esc = str => String(str || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/[\r\n]+/g, ' ');

            let html = '';
            localExperiments.forEach((exp) => {
                const coOptions = ['CO1','CO2','CO3','CO4','CO5','CO6'].map(c =>
                    `<option value="${c}" ${(exp.co||'') === c ? 'selected' : ''}>${c}</option>`
                ).join('');
                html += `
                    <tr style="border-bottom:1px solid #2a2d3e;" onmouseover="this.style.background='#1f2233'" onmouseout="this.style.background='transparent'">
                        <td style="padding:6px 8px;"><input type="text" class="exp-no" style="${IS}width:80px;font-family:monospace;" value="${esc(exp.expt_no)}"></td>
                        <td style="padding:6px 8px;"><input type="text" class="exp-title" style="${IS}" value="${esc(exp.title)}"></td>
                        <td style="padding:6px 8px;"><input type="text" class="exp-desc" style="${IS}color:#a0a7be;" value="${esc(exp.description)}"></td>
                        <td style="padding:6px 8px;"><select class="exp-co" style="${IS}">${coOptions}</select></td>
                        <td style="padding:6px 8px;text-align:center;"><input type="number" class="exp-hours" style="${IS}width:60px;text-align:center;" value="${exp.hours || 2}" min="1" max="6"></td>
                        <td style="padding:6px 8px;text-align:center;">
                            <button onclick="removeExperimentRow(this)" style="width:32px;height:32px;background:#7f1d1d;border:1px solid #991b1b;color:#fca5a5;border-radius:6px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;" title="Remove">
                                <i class="fa-solid fa-trash-can" style="font-size:11px;"></i>
                            </button>
                        </td>
                    </tr>`;
            });
            container.innerHTML = html;
        }

        function addNewExperimentRow() {
            const container = document.getElementById('experiments-rows-container');
            if (!container) return;

            const emptyRow = document.getElementById('exp-empty-row');
            if (emptyRow) emptyRow.remove();

            const count = container.querySelectorAll('tr').length + 1;
            const IS = 'background:#0f1117;border:1px solid #2a2d3e;color:#e2e5f0;border-radius:6px;padding:6px 8px;font-size:13px;width:100%;outline:none;';

            const newTr = document.createElement('tr');
            newTr.style.borderBottom = '1px solid #2a2d3e';
            newTr.style.transition = 'background .1s';
            newTr.onmouseover = function() { this.style.background = '#1f2233'; };
            newTr.onmouseout = function() { this.style.background = 'transparent'; };

            newTr.innerHTML = `
                <td style="padding:6px 8px;"><input type="text" class="exp-no" style="${IS}width:80px;font-family:monospace;" value="Expt ${count}"></td>
                <td style="padding:6px 8px;"><input type="text" class="exp-title" style="${IS}" value="" placeholder="Experiment title..."></td>
                <td style="padding:6px 8px;"><input type="text" class="exp-desc" style="${IS}color:#a0a7be;" value="" placeholder="Practical details / outcomes..."></td>
                <td style="padding:6px 8px;">
                    <select class="exp-co" style="${IS}">
                        <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                        <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                        <option value="CO5">CO5</option>
                        <option value="CO6">CO6</option>
                    </select>
                </td>
                <td style="padding:6px 8px;text-align:center;"><input type="number" class="exp-hours" style="${IS}width:60px;text-align:center;" value="2" min="1" max="6"></td>
                <td style="padding:6px 8px;text-align:center;">
                    <button onclick="removeExperimentRow(this)" style="width:32px;height:32px;background:#7f1d1d;border:1px solid #991b1b;color:#fca5a5;border-radius:6px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;" title="Remove">
                        <i class="fa-solid fa-trash-can" style="font-size:11px;"></i>
                    </button>
                </td>
            `;
            container.appendChild(newTr);
        }

        function removeExperimentRow(target) {
            if (target && target.closest) {
                const tr = target.closest('tr');
                if (tr) tr.remove();
            } else if (typeof target === 'number') {
                const trs = document.querySelectorAll('#experiments-rows-container tr');
                if (trs[target]) trs[target].remove();
            }
        }

        function syncLocalExperimentsFromDom() {
            const rows = [];
            document.querySelectorAll('#experiments-rows-container tr').forEach(tr => {
                const noInput = tr.querySelector('.exp-no');
                if (!noInput) return;
                rows.push({
                    expt_no: noInput.value,
                    title: tr.querySelector('.exp-title').value,
                    description: tr.querySelector('.exp-desc').value,
                    co: tr.querySelector('.exp-co').value,
                    hours: parseInt(tr.querySelector('.exp-hours').value || 2)
                });
            });
            if (rows.length > 0) localExperiments = rows;
        }

        // keep importDefaults as alias for backward compat
        function importDefaults() { loadFromPdfExperiments(); }

        async function saveExperimentsInventory() {
            const btn = document.getElementById('save-experiments-btn');
            const originalHtml = btn ? btn.innerHTML : '<i class="fa-solid fa-floppy-disk"></i> Save List';
            
            const rows = [];
            const container = document.getElementById('experiments-rows-container');
            if (!container) return;
            const trs = container.querySelectorAll('tr');
            
            trs.forEach(tr => {
                const noInput = tr.querySelector('.exp-no');
                if (!noInput) return;
                rows.push({
                    expt_no: noInput.value,
                    title: tr.querySelector('.exp-title').value,
                    description: tr.querySelector('.exp-desc').value,
                    co: tr.querySelector('.exp-co').value,
                    hours: parseInt(tr.querySelector('.exp-hours').value || 2)
                });
            });

            if (rows.length === 0) {
                alert('No experiment rows to save.');
                return;
            }

            localExperiments = rows;

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
            }

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/experiments`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ experiments: localExperiments })
                });
                const data = await res.json();
                
                if (btn) {
                    btn.style.background = '#059669';
                    btn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Saved!';
                    setTimeout(() => {
                        btn.style.background = '#087f5b';
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    }, 2500);
                }

                const statusEl = document.getElementById('exp-pdf-status');
                if (statusEl) {
                    statusEl.className = 'mt-3 p-3 rounded-xl text-sm font-semibold flex items-center gap-2 bg-emerald-950/60 border border-emerald-700/60 text-emerald-300';
                    statusEl.style.display = 'flex';
                    statusEl.innerHTML = `<i class="fa-solid fa-circle-check"></i> ${data.message || 'Experiments saved successfully and preserved in Databank!'}`;
                }
            } catch(e) {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
                alert("Failed to save experiments.");
            }
        }

        // Generate Lesson Planner Timeline
        async function generateLessonTimeline() {
            const mode = document.getElementById('lesson_planner_mode').value;
            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/lesson-plan/generate`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ mode: mode })
                });
                const data = await res.json();
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                }
            } catch(e) {
                alert("Failed to generate plan.");
            }
        }

        async function saveLessonPlannerBulk() {
            const plans = {};
            document.querySelectorAll('.lesson-plan-row').forEach(row => {
                const id = row.getAttribute('data-id');
                plans[id] = {
                    topic_content: row.querySelector('.lp-topic').value,
                    co_id: row.querySelector('.lp-co').value,
                    allocated_hours: parseInt(row.querySelector('.lp-hours').value || 1),
                    pedagogy: row.querySelector('.lp-pedagogy').value,
                    status: row.querySelector('.lp-status').value
                };
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/lesson-plans/bulk-update`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ plans: plans })
                });
                const data = await res.json();
                alert(data.message);
            } catch(e) {
                alert("Save failed.");
            }
        }

        // Save Practical Marks Entry
        function stepSlider(key, val) {
            const slider = document.getElementById(`slider-${key}`);
            if (!slider) return;
            let target = parseFloat(slider.value) + val;
            target = Math.max(parseFloat(slider.min), Math.min(parseFloat(slider.max), target));
            slider.value = target;
            slider.dispatchEvent(new Event('input'));
        }

        function openGradingModal(regNo, type) {
            const student = studentList.find(s => s.reg_no === regNo);
            if (!student) return;

            currentStudentIndex = studentList.findIndex(s => s.reg_no === regNo);

            document.getElementById('modalStudentName').innerText = student.name;
            document.getElementById('modalStudentReg').innerText = student.reg_no;

            const container = document.getElementById('modalSlidersContainer');
            container.innerHTML = '';

            let rubrics = [];
            if (type === 'table22') {
                rubrics = [
                    { label: '1. Prep & Punctuality (Max 10)', key: 'c1', max: 10, step: 0.5 },
                    { label: '2. Setup & Procedure (Max 10)', key: 'c2', max: 10, step: 0.5 },
                    { label: '3. Observation & Recording (Max 5)', key: 'c3', max: 5, step: 0.5 },
                    { label: '4. Analysis & Outcomes (Max 10)', key: 'c4', max: 10, step: 0.5 },
                    { label: '5. Viva Voce (Max 10)', key: 'c5', max: 10, step: 0.5 },
                    { label: '6. Workmanship & Safety (Max 5)', key: 'c6', max: 5, step: 0.5 }
                ];
            } else if (type === 'table23') {
                rubrics = [
                    { label: '1. Idea & Relevance (Max 10)', key: 'c1', max: 10, step: 0.5 },
                    { label: '2. Plan & Objectives (Max 10)', key: 'c2', max: 10, step: 0.5 },
                    { label: '3. Execution & Safety (Max 10)', key: 'c3', max: 10, step: 0.5 },
                    { label: '4. Analysis & Results (Max 10)', key: 'c4', max: 10, step: 0.5 },
                    { label: '5. Teamwork & Creativity (Max 10)', key: 'c5', max: 10, step: 0.5 }
                ];
            } else if (type === 'table31') {
                rubrics = [
                    { label: '1. Procedure Write-up (Max 10)', key: 'c1', max: 10, step: 0.5 },
                    { label: '2. Setup & Execution (Max 10)', key: 'c2', max: 10, step: 0.5 },
                    { label: '3. Output Results (Max 8)', key: 'c3', max: 8, step: 0.5 },
                    { label: '4. Viva Voce (Max 8)', key: 'c4', max: 8, step: 0.5 },
                    { label: '5. Record Book (Max 4)', key: 'c5', max: 4, step: 0.5 }
                ];
            }

            const stateScores = type === 'table31'
                ? (scoresState[type][regNo][document.getElementById('series_no').value] || {})
                : (scoresState[type][regNo] || {});

            rubrics.forEach(r => {
                const val = stateScores[r.key] || 0;
                const html = `
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <div class="flex justify-between font-bold text-xs mb-2">
                            <span class="text-slate-700">${r.label}</span>
                            <span class="text-emerald-700 font-mono font-black text-sm px-2 py-0.5 bg-emerald-50 rounded-md border border-emerald-200" id="modal-val-${r.key}">${val}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="stepSlider('${r.key}', -${r.step})" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 border border-slate-300 text-slate-700 font-bold transition-colors">-</button>
                            <input type="range" id="slider-${r.key}" min="0" max="${r.max}" step="${r.step}" value="${val}" oninput="syncModalSlider('${regNo}', '${r.key}', '${type}')" class="flex-grow accent-indigo-600">
                            <button onclick="stepSlider('${r.key}', ${r.step})" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 border border-slate-300 text-slate-700 font-bold transition-colors">+</button>
                        </div>
                    </div>
                `;
                container.innerHTML += html;
            });

            updateModalTotal(regNo, type);

            document.getElementById('gradingModal').classList.remove('hidden');
            document.getElementById('gradingModal').classList.add('flex');
        }

        function syncModalSlider(regNo, key, type) {
            const val = parseFloat(document.getElementById(`slider-${key}`).value) || 0;
            document.getElementById(`modal-val-${key}`).innerText = val;

            if (type === 'table31') {
                const sNo = document.getElementById('series_no').value;
                scoresState[type][regNo][sNo][key] = val;
            } else {
                scoresState[type][regNo][key] = val;
            }

            updateModalTotal(regNo, type);
        }

        function updateModalTotal(regNo, type) {
            let total = 0;
            if (type === 'table31') {
                const sNo = document.getElementById('series_no').value;
                const s = scoresState[type][regNo][sNo];
                total = (s.c1||0) + (s.c2||0) + (s.c3||0) + (s.c4||0) + (s.c5||0);
                document.getElementById(`score-text-series-${regNo}`).innerText = `${total.toFixed(2)} / 40`;
            } else if (type === 'table23') {
                const s = scoresState[type][regNo];
                total = (s.c1||0) + (s.c2||0) + (s.c3||0) + (s.c4||0) + (s.c5||0);
                document.getElementById(`score-text-open-${regNo}`).innerText = `${total.toFixed(2)} / 50`;
            } else if (type === 'table22') {
                const s = scoresState[type][regNo];
                total = (s.c1||0) + (s.c2||0) + (s.c3||0) + (s.c4||0) + (s.c5||0) + (s.c6||0);
                document.getElementById(`score-text-exp-${regNo}`).innerText = `${total.toFixed(2)} / 50`;
            }

            document.getElementById('modalTotalScore').innerText = total.toFixed(2);
            recalculateCIA(regNo);
        }

        function navigateStudent(direction) {
            let nextIdx = currentStudentIndex + direction;
            if (nextIdx >= studentList.length) nextIdx = 0;
            if (nextIdx < 0) nextIdx = studentList.length - 1;

            const nextStud = studentList[nextIdx];
            const row = document.querySelector(`.student-row[data-reg-no="${nextStud.reg_no}"]`);
            if (row.classList.contains('hidden')) {
                currentStudentIndex = nextIdx;
                navigateStudent(direction);
                return;
            }

            closeGradingModal();
            openGradingModal(nextStud.reg_no, activeTab);
        }

        function closeGradingModal() {
            document.getElementById('gradingModal').classList.add('hidden');
            document.getElementById('gradingModal').classList.remove('flex');
        }

        function recalculateCIA(regNo) {
            // Day Work 30M
            const s22 = scoresState.table22[regNo] || {};
            const sum22 = (s22.c1||0) + (s22.c2||0) + (s22.c3||0) + (s22.c4||0) + (s22.c5||0) + (s22.c6||0);
            const scaled22 = (sum22 / 50) * 30;
            document.getElementById(`cia-lab-work-${regNo}`).innerText = scaled22.toFixed(2);

            // Open-ended 10M
            const s23 = scoresState.table23[regNo] || {};
            const sum23 = (s23.c1||0) + (s23.c2||0) + (s23.c3||0) + (s23.c4||0) + (s23.c5||0);
            const scaled23 = (sum23 / 50) * 10;
            document.getElementById(`cia-open-${regNo}`).innerText = scaled23.toFixed(2);

            // Series 15M
            const s31_1 = scoresState.table31[regNo]['Series 1'] || {};
            const s31_2 = scoresState.table31[regNo]['Series 2'] || {};
            const sum31_1 = (s31_1.c1||0) + (s31_1.c2||0) + (s31_1.c3||0) + (s31_1.c4||0) + (s31_1.c5||0);
            const sum31_2 = (s31_2.c1||0) + (s31_2.c2||0) + (s31_2.c3||0) + (s31_2.c4||0) + (s31_2.c5||0);
            const avg31 = (sum31_1 + sum31_2) / 2;
            const scaled31 = (avg31 / 40) * 15;
            document.getElementById(`cia-series-${regNo}`).innerText = scaled31.toFixed(2);

            // Attendance 5M
            const att = attendanceMarks[regNo] ? attendanceMarks[regNo].mark : 5;

            // Total CIA (60)
            const cia = scaled22 + scaled23 + scaled31 + att;
            document.getElementById(`cia-total-${regNo}`).innerText = cia.toFixed(2);

            // ESE 40M
            const eseInput = document.getElementById(`ese-mark-${regNo}`);
            const ese = eseInput ? (parseFloat(eseInput.value) || 0) : 0;
            document.getElementById(`cia-ese-${regNo}`).innerText = ese.toFixed(2);

            // Grand Total (100)
            const grand = cia + ese;
            document.getElementById(`cia-grand-${regNo}`).innerText = grand.toFixed(2);
        }

        // Submission triggers
        async function submitExpMarks() {
            const expNoVal = document.getElementById('exp_no').value;
            const titleVal = document.getElementById('exp_title').value;
            const marks = scoresState.table22;

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/evaluate/experiment`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ experiment_no: expNoVal, title: titleVal, marks: marks })
                });
                const data = await res.json();
                alert(data.message);
                if (data.success) {
                    // Update client-side experimentLogs cache
                    experimentLogs[expNoVal] = studentList.map(s => {
                        const m = marks[s.reg_no] || {};
                        return {
                            reg_no: s.reg_no,
                            title: titleVal,
                            prep_punctuality: m.c1 || 0,
                            setup_procedure: m.c2 || 0,
                            observation_recording: m.c3 || 0,
                            analysis_interpretation: m.c4 || 0,
                            viva_voce: m.c5 || 0,
                            teamwork_discipline: m.c6 || 0,
                            total_score_50: (m.c1||0) + (m.c2||0) + (m.c3||0) + (m.c4||0) + (m.c5||0) + (m.c6||0)
                        };
                    });
                    initExperimentSelect();
                }
            } catch(e) {
                alert("Failed to save continuous log.");
            }
        }

        async function submitOpenEndedMarks() {
            const marks = {};
            studentList.forEach(s => {
                const reg = s.reg_no;
                marks[reg] = {
                    title: document.getElementById(`open-title-${reg}`).value || 'Open-ended Project',
                    ...scoresState.table23[reg]
                };
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/evaluate/open-ended`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ marks: marks })
                });
                const data = await res.json();
                alert(data.message);
            } catch(e) {
                alert("Failed to save open-ended marks.");
            }
        }

        async function submitSeriesMarks() {
            const sNoVal = document.getElementById('series_no').value;
            const marks = {};
            studentList.forEach(s => {
                const reg = s.reg_no;
                marks[reg] = scoresState.table31[reg][sNoVal] || { c1:0, c2:0, c3:0, c4:0, c5:0 };
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/evaluate/series`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ series_no: sNoVal, marks: marks })
                });
                const data = await res.json();
                alert(data.message);
            } catch(e) {
                alert("Failed to save series marks.");
            }
        }

        async function submitEseMarks() {
            const marks = {};
            studentList.forEach(s => {
                const reg = s.reg_no;
                marks[reg] = parseFloat(document.getElementById(`ese-mark-${reg}`).value) || 0;
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/ese-marks`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ marks: marks })
                });
                const data = await res.json();
                alert(data.message);
            } catch(e) {
                alert("Failed to save ESE marks.");
            }
        }

        // Series QP config
        const seriesQpConfigs = @json($seriesExams);

        function loadSeriesQpConfig(examName) {
            const conf = seriesQpConfigs[examName] || {};
            document.getElementById('series_qp_max_marks').value = conf.max_marks || 40;
            document.getElementById('series_qp_duration').value = conf.duration_minutes || 120;
            document.getElementById('series_qp_outline').value = typeof conf.question_outline === 'string' 
                ? conf.question_outline 
                : (conf.question_outline ? JSON.stringify(conf.question_outline, null, 2) : '');

            const coChecks = document.querySelectorAll('#series_qp_co_checks .co-checkbox');
            const activeCos = conf.co_tags || [];
            coChecks.forEach(chk => {
                chk.checked = activeCos.includes(chk.value);
            });
        }

        async function saveSeriesQpOutline() {
            const examName = document.getElementById('series_qp_select').value;
            const maxMarks = parseInt(document.getElementById('series_qp_max_marks').value || 40);
            const duration = parseInt(document.getElementById('series_qp_duration').value || 120);
            const outline = document.getElementById('series_qp_outline').value;

            const coTags = [];
            document.querySelectorAll('#series_qp_co_checks .co-checkbox').forEach(chk => {
                if (chk.checked) coTags.push(chk.value);
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/series-exams/configure`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({
                        exam_name: examName,
                        max_marks: maxMarks,
                        duration_minutes: duration,
                        question_outline: outline,
                        co_tags: coTags
                    })
                });
                const data = await res.json();
                if (data.success) {
                    alert(data.message);
                    seriesQpConfigs[examName] = {
                        max_marks: maxMarks,
                        duration_minutes: duration,
                        question_outline: outline,
                        co_tags: coTags
                    };
                }
            } catch(e) {
                alert("Failed to save blueprint.");
            }
        }

        // Initiate Mid/Exit surveys
        async function manageSurvey(type, action) {
            const endpoint = `/api/r26/classroom/${subjectId}/${type === 'mid' ? 'midsem' : 'exit'}-survey/${action}`;
            try {
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                alert(data.message || 'Survey state updated successfully!');
            } catch(e) {
                alert("Failed to update survey.");
            }
        }
    </script>
</body>
</html>
