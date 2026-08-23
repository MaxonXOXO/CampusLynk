<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }}) - Virtual Drawing Hall (R2026)</title>
    
    <!-- Canonical Vite Asset Pipeline -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <style>
        :root {
            --bg-primary: #FAFAFB;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --accent-blue: #2563eb;
        }

        body {
            background-color: #FAFAFB;
            color: #0f172a;
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            font-size: 0.875rem;
        }

        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Outfit', 'Poppins', sans-serif;
            text-shadow: none !important;
            filter: none !important;
        }
        span, p, label, button, a, th, td, div {
            text-shadow: none !important;
            filter: none !important;
        }

        .glass-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05); }
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05);
            transition: all 0.2s ease;
        }

        .stat-card {
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .stat-card .stat-val {
            font-size: 1.15rem;
            font-weight: 800;
        }

        .nav-tabs-custom {
            border-bottom: none;
            gap: 0.35rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 0.35rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05);
        }

        .nav-tabs-custom .nav-link {
            color: #64748b;
            border: 1px solid transparent;
            border-radius: 0.75rem !important;
            padding: 0.5rem 0.875rem;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .nav-tabs-custom .nav-link:hover {
            color: #0f172a;
            background: #f8fafc;
        }

        .nav-tabs-custom .nav-link.active {
            color: #1d4ed8 !important;
            background: #eff6ff !important;
            border-color: #bfdbfe !important;
            font-weight: 700;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .table-custom {
            color: #0f172a;
            border-color: #e2e8f0;
            font-size: 0.875rem;
        }

        .table-custom th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.04em;
            padding: 0.625rem 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .table-custom td {
            background-color: #ffffff;
            border-color: #e2e8f0;
            vertical-align: middle;
            padding: 0.625rem 0.75rem;
        }

        .form-control-custom {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            border-radius: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control-custom:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            color: #0f172a;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
        }

        .growable-textarea {
            resize: none;
            overflow-y: hidden;
            min-height: 38px;
            line-height: 1.4;
            font-size: 0.875rem;
            white-space: pre-wrap;
            word-wrap: break-word;
            border-radius: 0.5rem;
            background-color: #ffffff;
            color: #0f172a;
            border: 1px solid #cbd5e1;
            width: 100%;
        }

        .growable-textarea:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
            color: #0f172a;
            outline: none;
        }

        .badge-cyan { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-size: 0.75rem; padding: 0.3em 0.6em; border-radius: 0.375rem; }
        .badge-emerald { background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 0.75rem; padding: 0.3em 0.6em; border-radius: 0.375rem; }
        .badge-amber { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-size: 0.75rem; padding: 0.3em 0.6em; border-radius: 0.375rem; }
        .badge-rose { background-color: #fff1f2; color: #be123c; border: 1px solid #fecdd3; font-size: 0.75rem; padding: 0.3em 0.6em; border-radius: 0.375rem; }
        .badge-purple { background-color: #faf5ff; color: #7e22ce; border: 1px solid #e9d5ff; font-size: 0.75rem; padding: 0.3em 0.6em; border-radius: 0.375rem; }

        .rubric-input {
            width: 56px;
            text-align: center;
            font-weight: 700;
            font-size: 0.875rem;
            padding: 0.25rem;
            height: 32px;
            border-radius: 0.375rem;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            color: #0f172a;
        }

        .mark-slider {
            width: 100%;
            accent-color: #2563eb;
            height: 5px;
            background: #cbd5e1;
            border-radius: 4px;
            cursor: pointer;
            margin: 2px 0 0 0;
        }

        .btn-cyan {
            background-color: #2563eb;
            color: #ffffff;
            font-weight: 600;
            border: none;
            font-size: 0.875rem;
            padding: 0.45rem 0.85rem;
            border-radius: 0.5rem;
        }
        .btn-cyan:hover {
            background-color: #1d4ed8;
            color: #ffffff;
        }
    </style>
</head>
<body class="bg-[#FAFAFB] text-slate-900 min-h-screen antialiased">
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
                    <span>Drawing Virtual Hall</span>
                    <span class="text-xs font-bold font-mono px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200/80 rounded-md">R2026</span>
                </span>
            </nav>

            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ $dashboardUrl }}" class="px-3.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition flex items-center gap-1.5 border border-rose-200 shadow-2xs text-decoration-none">
                    <span class="material-symbols-rounded text-sm">arrow_back</span>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="max-w-[1600px] mx-auto px-4 md:px-8 py-4">

        <!-- Top Banner / Hero Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm mb-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <span class="badge badge-cyan px-2.5 py-1"><i class="fa-solid fa-compass-drafting me-1"></i> R2026 Drawing Course</span>
                        
                        <!-- Batch Badge -->
                        <span class="badge badge-emerald px-2.5 py-1">
                            <i class="fa-solid fa-users me-1"></i> Batch: {{ $classroom->batch_year ?? 'R26' }} ({{ $batchSubject->classroom_id }})
                        </span>

                        <!-- Assigned Faculty Badge -->
                        <span class="badge badge-purple px-2.5 py-1">
                            <i class="fa-solid fa-user-tie me-1"></i> Faculty: 
                            @if(isset($assignedStaff) && count($assignedStaff) > 0)
                                {{ $assignedStaff->pluck('name')->implode(', ') }}
                            @else
                                {{ Session::get('userName') ?? 'Faculty In-Charge' }}
                            @endif
                        </span>

                        <!-- AI Status Badge -->
                        @php
                            $isAiActive = \App\Http\Controllers\SystemSettingController::isAiEnabled();
                        @endphp
                        @if($isAiActive)
                            <span class="badge badge-emerald px-2.5 py-1 d-inline-flex align-items-center gap-1" title="AI Support API Active">
                                <span class="rounded-circle bg-success d-inline-block" style="width:6px; height:6px;"></span>
                                <span>AI Active</span>
                            </span>
                        @else
                            <span class="badge bg-light text-secondary border px-2.5 py-1 d-inline-flex align-items-center gap-1" title="AI Support API Deactivated">
                                <span class="rounded-circle bg-secondary d-inline-block" style="width:6px; height:6px;"></span>
                                <span>AI Off</span>
                            </span>
                        @endif

                        <span class="badge badge-amber px-2.5 py-1"><i class="fa-solid fa-clock me-1"></i> 45 Hours</span>
                    </div>
                    <h2 class="fw-bold mb-1 fs-4 text-slate-900">{{ $drawingCourseFile->course_title ?? $batchSubject->subject_name }}</h2>
                    <p class="text-muted mb-0" style="font-size: 0.8125rem;">Course Code: <strong class="text-slate-800">{{ $drawingCourseFile->course_code ?? $batchSubject->subject_code }}</strong> | Scheme L:T:P:R: <strong class="text-slate-800">{{ $drawingCourseFile->teaching_scheme }}</strong> | Credits: <strong class="text-slate-800">{{ $drawingCourseFile->credits }}</strong></p>
                </div>
                <div class="col-lg-5">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="stat-card text-center py-2 px-3 rounded-xl" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                                <div class="fw-bold text-uppercase" style="font-size: 0.68rem; color: #1d4ed8; letter-spacing: 0.2px;">Continuous Assessment</div>
                                <span class="stat-val d-block fw-bold text-blue-700 mt-0.5" style="font-size: 1.1rem;">60 Marks</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card text-center py-2 px-3 rounded-xl" style="background: #fffbeb; border: 1px solid #fde68a;">
                                <div class="fw-bold text-uppercase" style="font-size: 0.68rem; color: #b45309; letter-spacing: 0.2px;">End Semester Exam</div>
                                <span class="stat-val d-block fw-bold text-amber-700 mt-0.5" style="font-size: 1.1rem;">40 Marks</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-custom mb-4" id="drawingHallTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="tab-syllabus-link" data-bs-toggle="tab" data-bs-target="#tab-syllabus" type="button"><i class="fa-solid fa-file-pdf me-2 text-primary"></i>Syllabus & Parser</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-lessonplan-link" data-bs-toggle="tab" data-bs-target="#tab-lessonplan" type="button"><i class="fa-solid fa-calendar-days me-2 text-warning"></i>Lesson Plan</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-ce-link" data-bs-toggle="tab" data-bs-target="#tab-ce" type="button"><i class="fa-solid fa-pen-ruler me-2 text-success"></i>Continuous Eval (CE 30M)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-ca-link" data-bs-toggle="tab" data-bs-target="#tab-ca" type="button"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Practical Tests (15M)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-oee-link" data-bs-toggle="tab" data-bs-target="#tab-oee" type="button"><i class="fa-solid fa-lightbulb me-2 text-warning"></i>Open-Ended (10M)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-ese-link" data-bs-toggle="tab" data-bs-target="#tab-ese" type="button"><i class="fa-solid fa-desktop me-2 text-danger"></i>End Sem CAD Exam (40M)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-cie-link" data-bs-toggle="tab" data-bs-target="#tab-cie" type="button"><i class="fa-solid fa-chart-pie me-2 text-purple"></i>Consolidated CIE & Reports</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-materials-link" data-bs-toggle="tab" data-bs-target="#tab-materials" type="button"><i class="fa-solid fa-folder-open me-2 text-warning"></i>Study Materials & Pre-Class Hub</button>
            </li>
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content" id="drawingHallTabContent">

            <!-- TAB 1: SYLLABUS & PARSER -->
            <div class="tab-pane fade show active" id="tab-syllabus" role="tabpanel">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    
                    <!-- Left Col: Syllabus Upload & Auto-Extraction Workspace -->
                    <div class="space-y-4">
                        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
                                        <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h6"/><path d="M9 11h6"/></svg>
                                    </span>
                                    <h3 class="font-bold text-slate-900 text-sm">Drawing Syllabus File</h3>
                                </div>
                                @if(isset($drawingCourseFile) && $drawingCourseFile && $drawingCourseFile->syllabus_pdf_path)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">Active</span>
                                @endif
                            </div>

                            @if(isset($drawingCourseFile) && $drawingCourseFile && $drawingCourseFile->syllabus_pdf_path)
                                <div class="p-3 bg-emerald-50/60 border border-emerald-200/80 rounded-xl flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        <span class="text-xs font-bold text-emerald-950 truncate max-w-[130px] sm:max-w-none">Parsed Syllabus Active</span>
                                    </div>
                                    <a href="/storage/{{ $drawingCourseFile->syllabus_pdf_path }}" target="_blank" class="px-2.5 py-1 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-lg border border-emerald-200 flex items-center gap-1 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/></svg>
                                        <span>View PDF</span>
                                    </a>
                                </div>
                            @endif

                            <form id="uploadSyllabusForm" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div id="drawingSyllabusDropzone" ondragover="handleDrawingDragOver(event)" ondragleave="handleDrawingDragLeave(event)" ondrop="handleDrawingFileDrop(event)" onclick="document.getElementById('drawingSyllabusInput').click()" class="border-2 border-dashed border-slate-300 hover:border-blue-500 bg-slate-50/70 hover:bg-blue-50/40 rounded-2xl p-5 text-center space-y-2 transition cursor-pointer">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 mx-auto flex items-center justify-center border border-blue-200">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900">Select Drawing Syllabus PDF</h4>
                                        <p class="text-xs text-slate-500 mt-0.5">Drag &amp; drop PDF or <span class="text-blue-600 font-semibold underline">browse</span></p>
                                    </div>
                                    <input type="file" id="drawingSyllabusInput" name="syllabus_file" accept=".pdf" class="hidden" onchange="handleDrawingFileInput(this)">
                                </div>

                                <!-- Selected File Preview Box -->
                                <div id="drawingFilePreview" class="hidden p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-rose-600 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/></svg>
                                        <span id="drawingFileName" class="text-xs font-bold text-slate-900 truncate max-w-[140px]">syllabus.pdf</span>
                                    </div>
                                    <button type="button" onclick="cancelDrawingSelectedFile(event)" class="text-slate-400 hover:text-slate-600">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </div>

                                <!-- Processing State -->
                                <div id="drawingProcessingState" class="hidden p-3.5 bg-blue-50/60 border border-blue-200 rounded-xl text-center space-y-1">
                                    <div class="flex items-center justify-center gap-2 text-blue-700 text-xs font-bold">
                                        <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                                        <span>Extracting Drawing Specifications...</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500">Detecting manual sheets, CAD modules, and CO-PO matrix.</p>
                                </div>

                                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-all flex items-center justify-center gap-1.5 shadow-xs cursor-pointer" id="uploadBtn">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    <span>Parse &amp; Extract Syllabus</span>
                                </button>
                            </form>

                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
                                <span class="text-xs font-bold text-slate-700 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                    Auto Extraction Support
                                </span>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Parses Course Title, Code, L:T:P:R, Credits, CO1–CO4 descriptions, Bloom's cognitive levels, CO-PO Matrix, and 45h Drawing Exercises.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right 2 Cols: Course Outcomes & CO-PO Matrix -->
                    <div class="lg:col-span-2 space-y-5">
                        
                        <!-- Course Outcomes (COs) Card -->
                        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
                                        <span class="material-symbols-rounded text-base">stars</span>
                                    </span>
                                    <h3 class="font-bold text-slate-900 text-base">Drawing Course Outcomes (COs)</h3>
                                </div>
                                <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg border border-slate-200">
                                    {{ count($drawingCourseFile->parsed_cos ?? []) }} Outcomes
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @forelse($drawingCourseFile->parsed_cos ?? [] as $co)
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
                                        <span class="px-2 py-0.5 rounded-md font-semibold text-xs border {{ $badgeClasses }}">{{ $co['cognitive_level'] ?? 'Apply' }}</span>
                                    </div>
                                    <p class="text-sm font-medium text-slate-800 leading-relaxed">{{ $co['description'] }}</p>
                                </div>
                                @empty
                                <div class="col-span-2 text-center py-6 text-slate-500 text-sm italic bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                    Drawing outcomes not uploaded yet.
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- CO-PO Matrix Card -->
                        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
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
                                            <th class="p-3 text-left pl-4 w-24">CO Tag</th>
                                            @for($p=1; $p<=11; $p++) <th class="p-3 font-mono">PO{{ $p }}</th> @endfor
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
                                                <td class="p-2.5 font-mono text-sm {{ $cellClass }}">{{ $val }}</td>
                                            @endfor
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- TAB 2: 45-HOUR DRAWING LAB LESSON PLANNER -->
            <div class="tab-pane fade" id="tab-lessonplan" role="tabpanel">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom border-secondary">
                        <div>
                            <h5 class="fw-bold mb-1 text-warning"><i class="fa-solid fa-list-check me-2"></i>45-Hour Drawing Lab Lesson Plan</h5>
                            <small class="text-muted">Single Batch (Whole Class) practical sessions covering Manual Drawing & CAD Drafting, Series Exams & OEE Project</small>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <input type="hidden" id="lesson_planner_mode" value="single">
                            <span class="badge badge-cyan px-3 py-2 fw-semibold fs-6">
                                <i class="fa-solid fa-users me-1"></i> Single Batch Lab
                            </span>
                            <button onclick="generateLessonTimeline()" class="btn btn-sm btn-primary fw-bold">
                                <i class="fa-solid fa-arrows-rotate me-1"></i> Generate Planner
                            </button>
                            <a href="/r26/classroom/drawing/lesson-plan/print/{{ $batchSubject->id }}" target="_blank" class="btn btn-sm btn-outline-light fw-bold">
                                <i class="fa-solid fa-print me-1"></i> Print Plan
                            </a>
                            <a href="/r26/classroom/drawing/{{ $batchSubject->id }}/attendance-report" target="_blank" class="btn btn-sm btn-outline-info fw-bold">
                                <i class="fa-solid fa-clipboard-user me-1"></i> Register Matrix
                            </a>
                            <a href="/r26/classroom/drawing/{{ $batchSubject->id }}/attendance-consolidated" target="_blank" class="btn btn-sm btn-info fw-bold text-dark">
                                <i class="fa-solid fa-file-contract me-1"></i> Consolidated A4 Sheet
                            </a>
                            <button onclick="saveLessonPlannerBulk()" class="btn btn-sm btn-success fw-bold">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Planner
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0" id="lesson-plan-table">
                            <thead>
                                <tr>
                                    <th style="width: 65px;">Hour</th>
                                    <th style="width: 130px;">Proposed Date</th>
                                    <th style="width: 130px;">Actual Date</th>
                                    <th>Topic & Exercise Content (Growable Field)</th>
                                    <th style="width: 90px;">Mapped CO</th>
                                    <th style="width: 65px;">Hrs</th>
                                    <th style="width: 160px;">Pedagogy / Activity</th>
                                    <th style="width: 110px;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="lesson-plan-rows-container">
                                @forelse($lessonPlans as $lp)
                                <tr class="lesson-plan-row" data-id="{{ $lp->id }}">
                                    <td class="fw-bold text-center text-info">#{{ $lp->day_no }}</td>
                                    <td>
                                        <input type="date" value="{{ $lp->proposed_date ?: $lp->planned_date }}" class="form-control form-control-custom form-control-sm lp-proposed">
                                    </td>
                                    <td>
                                        <input type="date" value="{{ $lp->actual_date }}" class="form-control form-control-custom form-control-sm lp-actual">
                                    </td>
                                    <td>
                                        <textarea class="growable-textarea lp-topic" rows="1" oninput="autoGrow(this)">{{ $lp->topic_content }}</textarea>
                                    </td>
                                    <td>
                                        <select class="form-select form-control-custom form-select-sm lp-co">
                                            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coId)
                                            <option value="{{ $coId }}" {{ ($lp->co_tag ?: $lp->co_id) == $coId ? 'selected' : '' }}>{{ $coId }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" value="{{ $lp->allocated_hours ?: 1 }}" class="form-control form-control-custom form-control-sm text-center lp-hours" min="1" max="6">
                                    </td>
                                    <td>
                                        <select class="form-select form-control-custom form-select-sm lp-pedagogy">
                                            <option value="Drawing Lab Practical (P)" {{ $lp->pedagogy == 'Drawing Lab Practical (P)' ? 'selected' : '' }}>Drawing Lab Practical (P)</option>
                                            <option value="Series Test Examination (CA1)" {{ $lp->pedagogy == 'Series Test Examination (CA1)' ? 'selected' : '' }}>Series Test Examination (CA1)</option>
                                            <option value="Series Test Examination (CA2)" {{ $lp->pedagogy == 'Series Test Examination (CA2)' ? 'selected' : '' }}>Series Test Examination (CA2)</option>
                                            <option value="Open-Ended Project (OEE)" {{ $lp->pedagogy == 'Open-Ended Project (OEE)' ? 'selected' : '' }}>Open-Ended Project (OEE)</option>
                                            <option value="Drawing Lab Revision (P)" {{ $lp->pedagogy == 'Drawing Lab Revision (P)' ? 'selected' : '' }}>Drawing Lab Revision (P)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select form-control-custom form-select-sm fw-bold lp-status">
                                            <option value="Pending" {{ $lp->status == 'Pending' ? 'selected' : '' }} class="text-warning">Pending</option>
                                            <option value="Completed" {{ $lp->status == 'Completed' ? 'selected' : '' }} class="text-success">Completed</option>
                                        </select>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4 italic">No planner generated yet. Click "Generate Planner" to populate 45-hour single batch schedule.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: CONTINUOUS EVALUATION (CE - 30 MARKS) WITH SLIDER INPUTS -->
            <div class="tab-pane fade" id="tab-ce" role="tabpanel">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                    <div class="row align-items-center g-3 mb-4">
                        <div class="col-md-5">
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-pen-ruler me-2 text-success"></i>Continuous Practical Evaluation (CE)</h5>
                            <small class="text-muted">Split rubric scoring via slider controls (Max 50 -> Converted to 30 CIE Marks)</small>
                        </div>
                        <div class="col-md-5">
                            <select class="form-select form-control-custom" id="ceExerciseSelect">
                                @foreach($drawingCourseFile->parsed_exercises ?? [] as $ex)
                                <option value="{{ $ex['exercise_no'] }}">{{ $ex['exercise_no'] }}: {{ $ex['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-cyan w-100" id="saveCeBtn"><i class="fa-solid fa-floppy-disk me-1"></i> Save CE</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0" id="ceTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Roll</th>
                                    <th style="width: 90px;">Reg No</th>
                                    <th>Student Name</th>
                                    <th title="Attendance & Pre-lab (10)">Prep (10)</th>
                                    <th title="Setup & Procedure (10)">Setup (10)</th>
                                    <th title="Observation & Recording (5)">Obs (5)</th>
                                    <th title="Analysis & Dimensioning (10)">Anal (10)</th>
                                    <th title="Viva Voce (10)">Viva (10)</th>
                                    <th title="Workmanship & Line Quality (5)">Work (5)</th>
                                    <th style="width: 80px;">Total (50)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $st)
                                @php
                                    $stEval = isset($slotEvals[$st->reg_no]) ? $slotEvals[$st->reg_no]->first() : null;
                                    $v1 = $stEval->prep_punctuality ?? 0;
                                    $v2 = $stEval->setup_procedure ?? 0;
                                    $v3 = $stEval->observation_recording ?? 0;
                                    $v4 = $stEval->analysis_interpretation ?? 0;
                                    $v5 = $stEval->viva_voce ?? 0;
                                    $v6 = $stEval->workmanship_discipline ?? 0;
                                @endphp
                                <tr data-reg-no="{{ $st->reg_no }}">
                                    <td class="fw-bold text-center">{{ $st->roll_no }}</td>
                                    <td><small class="text-muted">{{ $st->reg_no }}</small></td>
                                    <td class="fw-semibold">{{ $st->name }}</td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input p1" value="{{ $v1 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $v1 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input p2" value="{{ $v2 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $v2 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input p3" value="{{ $v3 }}" max="5" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $v3 }}" max="5" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input p4" value="{{ $v4 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $v4 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input p5" value="{{ $v5 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $v5 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input p6" value="{{ $v6 }}" max="5" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $v6 }}" max="5" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td class="fw-bold text-info total-50 fs-6 text-center">{{ number_format($v1+$v2+$v3+$v4+$v5+$v6, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 4: PRACTICAL SERIES TESTS (CA1 & CA2 - 15 MARKS) WITH SLIDER INPUTS -->
            <div class="tab-pane fade" id="tab-ca" role="tabpanel">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                    <div class="row align-items-center g-3 mb-4">
                        <div class="col-md-4">
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Practical Series Tests (CA1 & CA2)</h5>
                            <small class="text-muted">Interactive sliders & automated QP, Scheme & Answer Key generation</small>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-control-custom" id="caTestSelect" onchange="loadCaTestData()">
                                <option value="CA1">CA1: Manual Drawing (Modules I & II - Max 40)</option>
                                <option value="CA2">CA2: CAD Exam (Modules III & IV - Max 40)</option>
                            </select>
                        </div>
                        <div class="col-md-5 text-end d-flex align-items-center justify-content-end gap-1 flex-wrap">
                            <button class="btn btn-outline-warning btn-sm" onclick="openQuestionBankModal()">
                                <i class="fa-solid fa-edit me-1"></i> Question Bank & Edit QP
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-print me-1"></i> Print Papers
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <li><h6 class="dropdown-header">Series Test 1 (Modules I & II)</h6></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/1?doc_type=qp" target="_blank">📄 QP Only (Strict 1 A4 Page)</a></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/1?doc_type=scheme" target="_blank">📊 Valuation Scheme</a></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/1?doc_type=key" target="_blank">🔑 Model Answer Key</a></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/1?doc_type=all" target="_blank">📚 Complete Package</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Series Test 2 (Modules III & IV)</h6></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/2?doc_type=qp" target="_blank">📄 QP Only (Strict 1 A4 Page)</a></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/2?doc_type=scheme" target="_blank">📊 Valuation Scheme</a></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/2?doc_type=key" target="_blank">🔑 Model Answer Key</a></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/2?doc_type=all" target="_blank">📚 Complete Package</a></li>
                                </ul>
                            </div>
                            <button class="btn btn-cyan btn-sm" id="saveCaBtn"><i class="fa-solid fa-floppy-disk me-1"></i> Save Marks</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0" id="caTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Roll</th>
                                    <th style="width: 90px;">Reg No</th>
                                    <th>Student Name</th>
                                    <th>Procedure / Writeup (10)</th>
                                    <th>Execution / Setup (10)</th>
                                    <th>Output / Drawing (8)</th>
                                    <th>Viva Voce (8)</th>
                                    <th>Record Completion (4)</th>
                                    <th style="width: 80px;">Total (40)</th>
                                    <th style="width: 60px;">Absent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $st)
                                @php
                                    $stTests = isset($practicalTests[$st->reg_no]) ? $practicalTests[$st->reg_no] : collect();
                                    $ca1 = $stTests->where('test_no', 'CA1')->first();
                                    $cw = $ca1->writeup_procedure ?? 0;
                                    $cs = $ca1->setup_execution ?? 0;
                                    $co = $ca1->observation_result ?? 0;
                                    $cv = $ca1->viva_voce ?? 0;
                                    $cr = $ca1->record_completion ?? 0;
                                    $isAbs = $ca1->is_absent ?? 0;
                                @endphp
                                <tr data-reg-no="{{ $st->reg_no }}">
                                    <td class="fw-bold text-center">{{ $st->roll_no }}</td>
                                    <td><small class="text-muted">{{ $st->reg_no }}</small></td>
                                    <td class="fw-semibold">{{ $st->name }}</td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input ca-w" value="{{ $cw }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $cw }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input ca-s" value="{{ $cs }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $cs }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input ca-o" value="{{ $co }}" max="8" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $co }}" max="8" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input ca-v" value="{{ $cv }}" max="8" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $cv }}" max="8" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input ca-r" value="{{ $cr }}" max="4" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $cr }}" max="4" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td class="fw-bold text-warning ca-total-40 fs-6 text-center">{{ number_format($cw+$cs+$co+$cv+$cr, 2) }}</td>
                                    <td class="text-center"><input type="checkbox" class="form-check-input ca-absent" {{ $isAbs ? 'checked' : '' }}></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 5: OPEN-ENDED EXPERIMENT (OEE - 10 MARKS) WITH SLIDER INPUTS -->
            <div class="tab-pane fade" id="tab-oee" role="tabpanel">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-lightbulb me-2 text-amber"></i>Open-Ended Experiment (OEE)</h5>
                            <small class="text-muted">Slider entry for CAD mini-project criteria (Max 50 -> Converted to 10 CIE Marks)</small>
                        </div>
                        <button class="btn btn-cyan" id="saveOeeBtn"><i class="fa-solid fa-floppy-disk me-1"></i> Save OEE Marks</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0" id="oeeTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Roll</th>
                                    <th style="width: 90px;">Reg No</th>
                                    <th>Student Name</th>
                                    <th>Originality & Innovation (10)</th>
                                    <th>Objectives & Planning (10)</th>
                                    <th>Execution & CAD Drafting (10)</th>
                                    <th>Analysis & Dimensioning (10)</th>
                                    <th>Teamwork & Viva (10)</th>
                                    <th style="width: 80px;">Total (50)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $st)
                                @php
                                    $stOee = $oeeEvals[$st->reg_no] ?? null;
                                    $m1 = $stOee->originality_relevance ?? 0;
                                    $m2 = $stOee->objectives_plan ?? 0;
                                    $m3 = $stOee->execution_recording ?? 0;
                                    $m4 = $stOee->analysis_presentation ?? 0;
                                    $m5 = $stOee->teamwork_innovation ?? 0;
                                @endphp
                                <tr data-reg-no="{{ $st->reg_no }}">
                                    <td class="fw-bold text-center">{{ $st->roll_no }}</td>
                                    <td><small class="text-muted">{{ $st->reg_no }}</small></td>
                                    <td class="fw-semibold">{{ $st->name }}</td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input oee-m1" value="{{ $m1 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $m1 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input oee-m2" value="{{ $m2 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $m2 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input oee-m3" value="{{ $m3 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $m3 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input oee-m4" value="{{ $m4 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $m4 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input oee-m5" value="{{ $m5 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $m5 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td class="fw-bold text-amber oee-total-50 fs-6 text-center">{{ number_format($m1+$m2+$m3+$m4+$m5, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 6: END SEMESTER EXAM (ESE - 40 MARKS) WITH SLIDER INPUTS -->
            <div class="tab-pane fade" id="tab-ese" role="tabpanel">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-desktop me-2 text-danger"></i>End Semester CAD Practical Exam (ESE)</h5>
                            <small class="text-muted">Board CAD Practical Exam split marks via sliders: Part A MCQ (10) + Part B CAD (18) + Part C Viva (8) + Part D Record (4) = 40 Marks</small>
                        </div>
                        <button class="btn btn-cyan" id="saveEseBtn"><i class="fa-solid fa-floppy-disk me-1"></i> Save ESE Marks</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0" id="eseTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Roll</th>
                                    <th style="width: 90px;">Reg No</th>
                                    <th>Student Name</th>
                                    <th>Part A: MCQ (10)</th>
                                    <th>Part B: CAD Drafting (18)</th>
                                    <th>Part C: Viva Voce (8)</th>
                                    <th>Part D: Record (4)</th>
                                    <th style="width: 80px;">Total ESE (40)</th>
                                    <th style="width: 60px;">Absent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $st)
                                @php
                                    $stEse = $eseMarks[$st->reg_no] ?? null;
                                    $pa = $stEse->part_a_mcq ?? 0;
                                    $pb = $stEse->part_b_cad ?? 0;
                                    $pc = $stEse->part_c_viva ?? 0;
                                    $pd = $stEse->part_d_record ?? 0;
                                    $isAbsEse = $stEse->is_absent ?? 0;
                                @endphp
                                <tr data-reg-no="{{ $st->reg_no }}">
                                    <td class="fw-bold text-center">{{ $st->roll_no }}</td>
                                    <td><small class="text-muted">{{ $st->reg_no }}</small></td>
                                    <td class="fw-semibold">{{ $st->name }}</td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input ese-pa" value="{{ $pa }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $pa }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input ese-pb" value="{{ $pb }}" max="18" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $pb }}" max="18" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input ese-pc" value="{{ $pc }}" max="8" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $pc }}" max="8" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="number" class="rubric-input ese-pd" value="{{ $pd }}" max="4" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $pd }}" max="4" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td class="fw-bold text-danger ese-total-40 fs-6 text-center">{{ number_format($pa+$pb+$pc+$pd, 2) }}</td>
                                    <td class="text-center"><input type="checkbox" class="form-check-input ese-absent" {{ $isAbsEse ? 'checked' : '' }}></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 7: CONSOLIDATED CIE, SURVEYS & REPORTS -->
            <div class="tab-pane fade" id="tab-cie" role="tabpanel">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm mb-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <h5 class="fw-bold mb-0"><i class="fa-solid fa-chart-pie me-2 text-purple"></i>Consolidated Course Score Sheet</h5>
                        <div class="btn-group">
                            <a href="/r26/classroom/drawing/{{ $batchSubject->id }}/attendance-report" target="_blank" class="btn btn-outline-info btn-sm fw-bold">
                                <i class="fa-solid fa-clipboard-user me-1"></i> Register Matrix
                            </a>
                            <a href="/r26/classroom/drawing/{{ $batchSubject->id }}/attendance-consolidated" target="_blank" class="btn btn-info btn-sm fw-bold text-dark">
                                <i class="fa-solid fa-file-contract me-1"></i> Consolidated A4 Sheet
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Roll</th>
                                    <th>Reg No</th>
                                    <th>Student Name</th>
                                    <th>Att (5)</th>
                                    <th>CE (30)</th>
                                    <th>Tests (15)</th>
                                    <th>OEE (10)</th>
                                    <th>Total CIE (60)</th>
                                    <th>ESE CAD (40)</th>
                                    <th>Total Marks (100)</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentResults as $r)
                                <tr>
                                    <td class="fw-bold text-center">{{ $r['roll_no'] }}</td>
                                    <td><small class="text-muted">{{ $r['reg_no'] }}</small></td>
                                    <td class="fw-semibold">{{ $r['name'] }}</td>
                                    <td>{{ $r['att_marks'] }}</td>
                                    <td>{{ $r['ce_marks'] }}</td>
                                    <td>{{ $r['practical_test_marks'] }}</td>
                                    <td>{{ $r['oee_marks'] }}</td>
                                    <td class="fw-bold text-info">{{ $r['total_cie_marks'] }}</td>
                                    <td class="fw-bold text-warning">{{ $r['total_ese'] }}</td>
                                    <td class="fw-bold fs-6 text-light">{{ $r['total_course_marks'] }}</td>
                                    <td>
                                        <span class="badge {{ $r['is_passed'] ? 'badge-emerald' : 'badge-rose' }}">
                                            {{ $r['is_passed'] ? 'PASSED' : 'FAILED' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Surveys & CO-PO Attainment -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                            <h5 class="fw-bold mb-3 text-info"><i class="fa-solid fa-poll me-2"></i>Indirect Attainment via Surveys</h5>
                            
                            <!-- Course Exit Survey Box -->
                            <div class="p-3 rounded mb-3" style="background: #111827; border: 1px solid var(--border-color);">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
                                    <div>
                                        <span class="fw-bold text-white fs-6">Course Exit Survey</span>
                                        <span class="badge {{ $exitSurvey ? ($exitSurvey->status == 'Active' ? 'badge-cyan' : 'badge-emerald') : 'badge-amber' }} ms-2">
                                            {{ $exitSurvey ? ($exitSurvey->status == 'Active' ? 'Active / Open' : 'Completed') : 'Not Initiated' }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                        <button class="btn btn-sm btn-outline-info" onclick="openExitInitModalDrawing()">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> Edit & Preview Questionnaire
                                        </button>
                                        @if(!$exitSurvey || $exitSurvey->status != 'Active')
                                        <button class="btn btn-sm btn-cyan" onclick="openExitInitModalDrawing()">
                                            <i class="fa-solid fa-paper-plane me-1"></i> Initiate & Notify
                                        </button>
                                        @else
                                        <button class="btn btn-sm btn-outline-danger" onclick="closeSurveyAction('exit')">
                                            <i class="fa-solid fa-lock me-1"></i> Close
                                        </button>
                                        @endif
                                        <a href="/classroom/{{ $batchSubject->id }}/course-exit/report" target="_blank" class="btn btn-sm btn-outline-light">
                                            <i class="fa-solid fa-file-pdf me-1"></i> Report
                                        </a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-secondary">
                                    <span class="fw-bold text-info" style="font-size: 0.88rem;">
                                        <i class="fa-solid fa-users me-1 text-cyan"></i> Responses Collected: 
                                        <span class="badge badge-cyan px-2 py-1 fs-6 ms-1">{{ $exitSurveyResponses->count() }}</span> / {{ $students->count() }} Enrolled
                                    </span>
                                    <small class="text-light" style="font-size: 0.75rem;"><i class="fa-solid fa-bell me-1 text-warning"></i> Auto-notifies student panel</small>
                                </div>
                            </div>

                            <!-- Mid-Semester Survey Box -->
                            <div class="p-3 rounded" style="background: #111827; border: 1px solid var(--border-color);">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
                                    <div>
                                        <span class="fw-bold text-white fs-6">Mid-Semester Survey</span>
                                        <span class="badge {{ $midSemSurvey ? ($midSemSurvey->status == 'Active' ? 'badge-cyan' : 'badge-emerald') : 'badge-amber' }} ms-2">
                                            {{ $midSemSurvey ? ($midSemSurvey->status == 'Active' ? 'Active / Open' : 'Completed') : 'Not Initiated' }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                        <button class="btn btn-sm btn-outline-info" onclick="previewSurveyModal('midsem')">
                                            <i class="fa-solid fa-eye me-1"></i> Preview
                                        </button>
                                        @if(!$midSemSurvey || $midSemSurvey->status != 'Active')
                                        <button class="btn btn-sm btn-cyan" onclick="initiateSurveyAction('midsem')">
                                            <i class="fa-solid fa-paper-plane me-1"></i> Initiate & Notify
                                        </button>
                                        @else
                                        <button class="btn btn-sm btn-outline-danger" onclick="closeSurveyAction('midsem')">
                                            <i class="fa-solid fa-lock me-1"></i> Close
                                        </button>
                                        @endif
                                        <a href="/classroom/{{ $batchSubject->id }}/survey/report" target="_blank" class="btn btn-sm btn-outline-light">
                                            <i class="fa-solid fa-file-pdf me-1"></i> Report
                                        </a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-secondary">
                                    <span class="fw-bold text-info" style="font-size: 0.88rem;">
                                        <i class="fa-solid fa-users me-1 text-cyan"></i> Responses Collected: 
                                        <span class="badge badge-cyan px-2 py-1 fs-6 ms-1">{{ $midSemResponses->count() }}</span> / {{ $students->count() }} Enrolled
                                    </span>
                                    <small class="text-light" style="font-size: 0.75rem;"><i class="fa-solid fa-bell me-1 text-warning"></i> Auto-notifies student panel</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-award me-2 text-warning"></i>CO Attainment Levels (80% Direct + 20% Indirect)</h5>
                            <div class="table-responsive">
                                <table class="table table-custom table-bordered text-center align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>CO Tag</th>
                                            <th>Direct (80%)</th>
                                            <th>Indirect (20%)</th>
                                            <th>Final Attainment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                                        <tr>
                                            <th class="text-info">{{ $coTag }}</th>
                                            <td>L{{ number_format($directStats[$coTag]['level'] ?? 0.0, 1) }}</td>
                                            <td>
                                                <span class="badge {{ ($indirectStats[$coTag]['level'] ?? 0) >= 3 ? 'badge-emerald' : (($indirectStats[$coTag]['level'] ?? 0) >= 2 ? 'badge-amber' : 'badge-rose') }}">
                                                    {{ $indirectStats[$coTag]['rating'] ?? 'High (L3)' }} ({{ number_format($indirectStats[$coTag]['avg_score'] ?? 2.5, 2) }}/3.0)
                                                </span>
                                            </td>
                                            <td class="fw-bold text-success">{{ number_format($combinedStats[$coTag] ?? 0.0, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 p-2 rounded bg-dark border border-secondary text-light text-center" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-info-circle me-1 text-cyan"></i>
                                <strong>Indirect Attainment Scaling (3-Point Likert Scale):</strong>
                                <span class="text-success ms-2"><strong>3 = High</strong> (&ge;70%)</span> |
                                <span class="text-warning ms-1"><strong>2 = Medium</strong> (60-69%)</span> |
                                <span class="text-danger ms-1"><strong>1 = Low</strong> (50-59%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 8: STUDY MATERIALS & PRE-CLASS HUB -->
            <div class="tab-pane fade" id="tab-materials" role="tabpanel">
                @include('partials.virtual_learning_hub_tab', ['roomType' => 'Drawing'])
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const subjectId = {{ $batchSubject->id }};
        const practicalTestsAll = @json($practicalTests);

        // Auto Grow Textarea Helper
        function autoGrow(element) {
            if (!element) return;
            element.style.height = 'auto';
            element.style.height = (element.scrollHeight) + 'px';
        }

        // Initialize Slider & Number Input Bidirectional Sync
        function initSliderSync() {
            document.querySelectorAll('.mark-cell').forEach(cell => {
                const numInput = cell.querySelector('input[type="number"]');
                const sliderInput = cell.querySelector('input[type="range"]');
                if (!numInput || !sliderInput) return;

                sliderInput.addEventListener('input', () => {
                    numInput.value = sliderInput.value;
                    numInput.dispatchEvent(new Event('input', { bubbles: true }));
                });

                numInput.addEventListener('input', () => {
                    sliderInput.value = numInput.value;
                });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Auto grow all growable textareas
            document.querySelectorAll('.growable-textarea').forEach(el => autoGrow(el));

            // Initialize slider sync
            initSliderSync();

            // Auto sum listeners for CE Table
            document.querySelectorAll('#ceTable tbody tr').forEach(tr => {
                tr.querySelectorAll('.rubric-input').forEach(input => {
                    input.addEventListener('input', () => {
                        let sum = 0;
                        tr.querySelectorAll('.rubric-input').forEach(inp => sum += parseFloat(inp.value || 0));
                        tr.querySelector('.total-50').textContent = sum.toFixed(2);
                    });
                });
            });

            // Auto sum listeners for CA Table
            document.querySelectorAll('#caTable tbody tr').forEach(tr => {
                tr.querySelectorAll('.rubric-input').forEach(input => {
                    input.addEventListener('input', () => {
                        let sum = 0;
                        tr.querySelectorAll('.rubric-input').forEach(inp => sum += parseFloat(inp.value || 0));
                        tr.querySelector('.ca-total-40').textContent = sum.toFixed(2);
                    });
                });
            });

            // Auto sum listeners for OEE Table
            document.querySelectorAll('#oeeTable tbody tr').forEach(tr => {
                tr.querySelectorAll('.rubric-input').forEach(input => {
                    input.addEventListener('input', () => {
                        let sum = 0;
                        tr.querySelectorAll('.rubric-input').forEach(inp => sum += parseFloat(inp.value || 0));
                        tr.querySelector('.oee-total-50').textContent = sum.toFixed(2);
                    });
                });
            });

            // Auto sum listeners for ESE Table
            document.querySelectorAll('#eseTable tbody tr').forEach(tr => {
                tr.querySelectorAll('.rubric-input').forEach(input => {
                    input.addEventListener('input', () => {
                        let sum = 0;
                        tr.querySelectorAll('.rubric-input').forEach(inp => sum += parseFloat(inp.value || 0));
                        tr.querySelector('.ese-total-40').textContent = sum.toFixed(2);
                    });
                });
            });
        });

        // Dynamic Loading for CA1 & CA2 Series Tests
        function loadCaTestData() {
            const selectedTest = document.getElementById('caTestSelect').value;
            document.querySelectorAll('#caTable tbody tr').forEach(tr => {
                const regNo = tr.dataset.regNo;
                const studentTests = practicalTestsAll[regNo] || [];
                const testObj = studentTests.find(t => t.test_no === selectedTest) || {};

                const w = testObj.writeup_procedure || 0;
                const s = testObj.setup_execution || 0;
                const o = testObj.observation_result || 0;
                const v = testObj.viva_voce || 0;
                const r = testObj.record_completion || 0;
                const abs = testObj.is_absent ? true : false;

                const inputW = tr.querySelector('.ca-w');
                const inputS = tr.querySelector('.ca-s');
                const inputO = tr.querySelector('.ca-o');
                const inputV = tr.querySelector('.ca-v');
                const inputR = tr.querySelector('.ca-r');
                const chkAbs = tr.querySelector('.ca-absent');

                if (inputW) { inputW.value = w; inputW.dispatchEvent(new Event('input')); }
                if (inputS) { inputS.value = s; inputS.dispatchEvent(new Event('input')); }
                if (inputO) { inputO.value = o; inputO.dispatchEvent(new Event('input')); }
                if (inputV) { inputV.value = v; inputV.dispatchEvent(new Event('input')); }
                if (inputR) { inputR.value = r; inputR.dispatchEvent(new Event('input')); }
                if (chkAbs) { chkAbs.checked = abs; }
            });
        }

        function handleDrawingDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropzone = document.getElementById('drawingSyllabusDropzone');
            if (dropzone) dropzone.classList.add('border-blue-500', 'bg-blue-50/60');
        }

        function handleDrawingDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropzone = document.getElementById('drawingSyllabusDropzone');
            if (dropzone) dropzone.classList.remove('border-blue-500', 'bg-blue-50/60');
        }

        function handleDrawingFileDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropzone = document.getElementById('drawingSyllabusDropzone');
            if (dropzone) dropzone.classList.remove('border-blue-500', 'bg-blue-50/60');

            const files = e.dataTransfer.files;
            if (!files || files.length === 0) return;
            const file = files[0];
            if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
                alert('Please drop a valid PDF file.');
                return;
            }
            const input = document.getElementById('drawingSyllabusInput');
            if (input) {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                showDrawingFilePreview(file);
            }
        }

        function handleDrawingFileInput(input) {
            if (!input.files || input.files.length === 0) return;
            showDrawingFilePreview(input.files[0]);
        }

        function showDrawingFilePreview(file) {
            const dropzone = document.getElementById('drawingSyllabusDropzone');
            const preview = document.getElementById('drawingFilePreview');
            const nameEl = document.getElementById('drawingFileName');
            if (nameEl) nameEl.innerText = file.name;
            if (dropzone) dropzone.classList.add('hidden');
            if (preview) preview.classList.remove('hidden');
        }

        function cancelDrawingSelectedFile(e) {
            if (e) { e.preventDefault(); e.stopPropagation(); }
            const input = document.getElementById('drawingSyllabusInput');
            if (input) input.value = '';
            const dropzone = document.getElementById('drawingSyllabusDropzone');
            const preview = document.getElementById('drawingFilePreview');
            const processing = document.getElementById('drawingProcessingState');

            if (preview) preview.classList.add('hidden');
            if (processing) processing.classList.add('hidden');
            if (dropzone) dropzone.classList.remove('hidden');
        }

        // Upload Syllabus PDF
        document.getElementById('uploadSyllabusForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const input = document.getElementById('drawingSyllabusInput');
            if (!input || !input.files || input.files.length === 0) {
                alert('Please select a syllabus PDF file first.');
                return;
            }
            const formData = new FormData(this);
            const btn = document.getElementById('uploadBtn');
            const preview = document.getElementById('drawingFilePreview');
            const processing = document.getElementById('drawingProcessingState');

            btn.disabled = true;
            btn.innerHTML = '<svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> <span>Extracting...</span>';
            if (preview) preview.classList.add('hidden');
            if (processing) processing.classList.remove('hidden');

            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/syllabus`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await res.json();
                if(data.status === 'SUCCESS') {
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                    if (processing) processing.classList.add('hidden');
                    const dropzone = document.getElementById('drawingSyllabusDropzone');
                    if (dropzone) dropzone.classList.remove('hidden');
                }
            } catch(err) {
                alert('Parsing failed: ' + err.message);
                if (processing) processing.classList.add('hidden');
                const dropzone = document.getElementById('drawingSyllabusDropzone');
                if (dropzone) dropzone.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> <span>Parse &amp; Extract Syllabus</span>';
            }
        });

        // Save CE
        document.getElementById('saveCeBtn').addEventListener('click', async () => {
            const exNo = document.getElementById('ceExerciseSelect').value;
            const marksData = [];
            document.querySelectorAll('#ceTable tbody tr').forEach(tr => {
                marksData.push({
                    reg_no: tr.dataset.regNo,
                    prep_punctuality: tr.querySelector('.p1').value,
                    setup_procedure: tr.querySelector('.p2').value,
                    observation_recording: tr.querySelector('.p3').value,
                    analysis_interpretation: tr.querySelector('.p4').value,
                    viva_voce: tr.querySelector('.p5').value,
                    workmanship_discipline: tr.querySelector('.p6').value
                });
            });

            const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/evaluate/slot`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ exercise_no: exNo, marks_data: marksData })
            });
            const data = await res.json();
            alert(data.message);
        });

        // Save Practical Test (CA1 & CA2)
        document.getElementById('saveCaBtn').addEventListener('click', async () => {
            const testNo = document.getElementById('caTestSelect').value;
            const marksData = [];
            document.querySelectorAll('#caTable tbody tr').forEach(tr => {
                marksData.push({
                    reg_no: tr.dataset.regNo,
                    writeup_procedure: tr.querySelector('.ca-w').value,
                    setup_execution: tr.querySelector('.ca-s').value,
                    observation_result: tr.querySelector('.ca-o').value,
                    viva_voce: tr.querySelector('.ca-v').value,
                    record_completion: tr.querySelector('.ca-r').value,
                    is_absent: tr.querySelector('.ca-absent').checked ? 1 : 0
                });
            });

            const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/evaluate/practical-test`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ test_no: testNo, marks_data: marksData })
            });
            const data = await res.json();
            alert(data.message);
        });

        // Save OEE
        document.getElementById('saveOeeBtn').addEventListener('click', async () => {
            const marksData = [];
            document.querySelectorAll('#oeeTable tbody tr').forEach(tr => {
                marksData.push({
                    reg_no: tr.dataset.regNo,
                    originality_relevance: tr.querySelector('.oee-m1').value,
                    objectives_plan: tr.querySelector('.oee-m2').value,
                    execution_recording: tr.querySelector('.oee-m3').value,
                    analysis_presentation: tr.querySelector('.oee-m4').value,
                    teamwork_innovation: tr.querySelector('.oee-m5').value
                });
            });

            const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/evaluate/oee`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ marks_data: marksData })
            });
            const data = await res.json();
            alert(data.message);
        });

        // Save ESE
        document.getElementById('saveEseBtn').addEventListener('click', async () => {
            const marksData = [];
            document.querySelectorAll('#eseTable tbody tr').forEach(tr => {
                marksData.push({
                    reg_no: tr.dataset.regNo,
                    part_a_mcq: tr.querySelector('.ese-pa').value,
                    part_b_cad: tr.querySelector('.ese-pb').value,
                    part_c_viva: tr.querySelector('.ese-pc').value,
                    part_d_record: tr.querySelector('.ese-pd').value,
                    is_absent: tr.querySelector('.ese-absent').checked ? 1 : 0
                });
            });

            const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/evaluate/ese`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ marks_data: marksData })
            });
            const data = await res.json();
            alert(data.message);
        });

        // Generate Lesson Plan Timeline (Single Batch)
        async function generateLessonTimeline() {
            if (!confirm(`Regenerate full 45-hour Drawing Lab lesson plan for single batch? Existing customized dates will be reset.`)) return;

            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/lesson-plan/generate`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ mode: 'single' })
                });
                const data = await res.json();
                alert(data.message);
                if (data.status === 'SUCCESS') {
                    window.location.reload();
                }
            } catch (e) {
                alert('Generation error: ' + e.message);
            }
        }

        // Save Bulk Lesson Planner Entries
        async function saveLessonPlannerBulk() {
            const plans = {};
            document.querySelectorAll('.lesson-plan-row').forEach(row => {
                const id = row.dataset.id;
                plans[id] = {
                    proposed_date: row.querySelector('.lp-proposed').value,
                    actual_date: row.querySelector('.lp-actual').value,
                    topic_content: row.querySelector('.lp-topic').value,
                    co_tag: row.querySelector('.lp-co').value,
                    allocated_hours: row.querySelector('.lp-hours').value,
                    pedagogy: row.querySelector('.lp-pedagogy').value,
                    status: row.querySelector('.lp-status').value
                };
            });

            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/lesson-plan/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ plans: plans })
                });
                const data = await res.json();
                alert(data.message);
            } catch (e) {
                alert('Save error: ' + e.message);
            }
        }

        // Preview Survey Questionnaire Modal Handler
        function previewSurveyModal(type) {
            const title = type === 'exit' ? 'Course Exit Survey Questionnaire (CO Mapped - R26 Drawing Lab)' : 'Mid-Semester Feedback Questionnaire (R26 Drawing Lab)';
            document.getElementById('surveyPreviewTitle').innerHTML = `<i class="fa-solid fa-clipboard-question me-2"></i>${title}`;
            
            let html = `
                <div class="mb-3 p-3 rounded" style="background: rgba(6,182,212,0.12); border: 1px solid var(--accent-cyan);">
                    <div class="fw-bold text-info mb-1"><i class="fa-solid fa-circle-info me-1"></i> CO-Mapped Questionnaire Standard</div>
                    <small class="text-light">Students score each outcome on a 3-Point Likert Scale: <strong>3 = High / Excellent</strong>, <strong>2 = Moderate / Good</strong>, <strong>1 = Low / Basic</strong>.</small>
                </div>
                <div class="list-group">
                    <div class="list-group-item bg-dark text-light border-secondary mb-2 rounded">
                        <div class="fw-bold text-cyan mb-1"><i class="fa-solid fa-compass me-1"></i> CO1: Manual Geometrical Drawing & Constructions</div>
                        <div class="ps-3 border-start border-cyan small">
                            <div class="mb-1">1. Rate your ability to manually construct regular polygons, conic sections, and developments.</div>
                            <div>2. Rate instructor step-by-step guidance and demonstration during manual sheet exercises.</div>
                        </div>
                    </div>
                    <div class="list-group-item bg-dark text-light border-secondary mb-2 rounded">
                        <div class="fw-bold text-warning mb-1"><i class="fa-solid fa-cube me-1"></i> CO2: Orthographic Projections & Sectional Views</div>
                        <div class="ps-3 border-start border-warning small">
                            <div class="mb-1">3. Rate your clarity on 1st & 3rd angle projection principles and sectional views.</div>
                            <div>4. Rate the timeliness of feedback during continuous slot evaluation of drawing sheets.</div>
                        </div>
                    </div>
                    <div class="list-group-item bg-dark text-light border-secondary mb-2 rounded">
                        <div class="fw-bold text-success mb-1"><i class="fa-solid fa-laptop-code me-1"></i> CO3: CAD Software Interface & Commands</div>
                        <div class="ps-3 border-start border-success small">
                            <div class="mb-1">5. Rate your proficiency in using CAD draw/modify tools, layer management, and dimensioning.</div>
                            <div>6. Rate the availability and performance of CAD workstation hardware/software facilities.</div>
                        </div>
                    </div>
                    <div class="list-group-item bg-dark text-light border-secondary rounded">
                        <div class="fw-bold text-danger mb-1"><i class="fa-solid fa-draw-polygon me-1"></i> CO4: 2D Component Drafting & Sectional Plotting</div>
                        <div class="ps-3 border-start border-danger small">
                            <div class="mb-1">7. Rate your confidence in generating 2D orthographic component drawings & sectional views in CAD.</div>
                            <div>8. Rate overall satisfaction with the 45-hour Drawing Lab curriculum delivery and outcomes.</div>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('surveyPreviewBody').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('surveyPreviewModal'));
            modal.show();
        }

        // Initiate Survey Action & Send Notification to Student Panel
        async function initiateSurveyAction(type) {
            const url = type === 'exit' ? `/api/r26/classroom/${subjectId}/exit-survey/initiate` : `/api/r26/classroom/${subjectId}/midsem-survey/initiate`;
            const label = type === 'exit' ? 'Course Exit Survey' : 'Mid-Semester Survey';
            
            if (!confirm(`Initiate ${label}? The survey notification will immediately appear on all student dashboard panels.`)) return;

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken 
                    }
                });
                const data = await res.json();
                alert(data.message);
                if (data.status === 'SUCCESS') window.location.reload();
            } catch (e) {
                alert('Error initiating survey: ' + e.message);
            }
        }

        // Close Active Survey Action
        async function closeSurveyAction(type) {
            const url = type === 'exit' ? `/api/r26/classroom/${subjectId}/exit-survey/close` : `/api/r26/classroom/${subjectId}/midsem-survey/close`;
            const label = type === 'exit' ? 'Course Exit Survey' : 'Mid-Semester Survey';
            
            if (!confirm(`Close ${label}? Student panel notifications will be closed and response collection finalized.`)) return;

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken 
                    }
                });
                const data = await res.json();
                alert(data.message);
                if (data.status === 'SUCCESS') window.location.reload();
            } catch (e) {
                alert('Error closing survey: ' + e.message);
            }
        }

        // Open Editable Drawing Course Exit Survey Modal
        function openExitInitModalDrawing() {
            const modalElement = document.getElementById('drawingExitSurveyInitModal');
            if (modalElement) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                modal.show();
            }
        }

        // Submit Edited Drawing Exit Survey Questions & Initiate
        async function submitDrawingExitInit(event) {
            event.preventDefault();
            const questions = {
                q1: document.getElementById('drg-ex-q1').value.trim(),
                q2: document.getElementById('drg-ex-q2').value.trim(),
                q3: document.getElementById('drg-ex-q3').value.trim(),
                q4: document.getElementById('drg-ex-q4').value.trim(),
                q5: document.getElementById('drg-ex-q5').value.trim(),
                q6: document.getElementById('drg-ex-q6').value.trim(),
                q7: document.getElementById('drg-ex-q7').value.trim(),
                q8: document.getElementById('drg-ex-q8').value.trim()
            };

            if (!confirm('Initiate Course Exit Survey with these edited questions? Student notifications will be sent immediately.')) return;

            try {
                const res = await fetch(`/api/r26/classroom/${subjectId}/exit-survey/initiate`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken 
                    },
                    body: JSON.stringify({ questions })
                });
                const data = await res.json();
                alert(data.message);
                if (data.status === 'SUCCESS') window.location.reload();
            } catch (e) {
                alert('Error initiating survey: ' + e.message);
            }
        }
    </script>

    <!-- Survey Questionnaire Preview Modal -->
    <div class="modal fade" id="surveyPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-white border border-slate-200 rounded-2xl shadow-2xl text-slate-800">
                <div class="modal-header border-b border-slate-100 p-4">
                    <h5 class="modal-title font-bold text-slate-900 text-sm flex items-center" id="surveyPreviewTitle"><i class="fa-solid fa-clipboard-question text-blue-600 me-2"></i>Survey Questionnaire Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5" id="surveyPreviewBody">
                    <!-- Dynamic Preview Content -->
                </div>
                <div class="modal-footer border-t border-slate-100 p-3">
                    <button type="button" class="btn btn-light btn-sm text-slate-700 font-bold border border-slate-200 rounded-xl px-4 py-2" data-bs-dismiss="modal">Close Preview</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Editable Drawing Course Exit Survey Questionnaire Modal -->
    <div class="modal fade" id="drawingExitSurveyInitModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content bg-white border border-slate-200 rounded-2xl shadow-2xl text-slate-800">
                <div class="modal-header border-b border-slate-100 p-4">
                    <div>
                        <h5 class="modal-title font-bold text-slate-900 text-sm flex items-center"><i class="fa-solid fa-pen-to-square text-cyan-600 me-2"></i>Edit Course Exit Survey Questionnaire (CO-Mapped)</h5>
                        <small class="text-slate-500">Faculty can edit and customize all 8 CO questions before publishing to students.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5">
                    <form id="drawingExitSurveyForm" onsubmit="submitDrawingExitInit(event)">
                        <div class="mb-3.5 p-3.5 rounded-xl bg-cyan-50 border border-cyan-200">
                            <div class="font-bold text-cyan-800 text-xs mb-1"><i class="fa-solid fa-circle-info me-1"></i> CO-Mapped Questionnaire Standard</div>
                            <small class="text-cyan-700 text-xs">Customize or edit question wording below before initiating. Students evaluate each CO question on a 3-Point Likert Scale (3 = High, 2 = Medium, 1 = Low).</small>
                        </div>

                        <div class="space-y-3">
                            <!-- CO1 Questions -->
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                                <div class="font-bold text-cyan-700 text-xs mb-2.5 flex items-center"><i class="fa-solid fa-compass me-1.5"></i> CO1: Manual Geometrical Drawing &amp; Constructions</div>
                                <div class="mb-2.5">
                                    <label class="form-label text-slate-600 text-xs font-bold mb-1">Question 1 (CO1 - Manual Constructions)</label>
                                    <input type="text" id="drg-ex-q1" class="form-control bg-white text-slate-800 border-slate-200 rounded-lg text-xs py-1.5 focus:border-cyan-500 shadow-2xs" value="1. Rate your ability to manually construct regular polygons, conic sections, and developments." required>
                                </div>
                                <div>
                                    <label class="form-label text-slate-600 text-xs font-bold mb-1">Question 2 (CO1 - Step-by-Step Guidance)</label>
                                    <input type="text" id="drg-ex-q2" class="form-control bg-white text-slate-800 border-slate-200 rounded-lg text-xs py-1.5 focus:border-cyan-500 shadow-2xs" value="2. Rate instructor step-by-step guidance and demonstration during manual sheet exercises." required>
                                </div>
                            </div>

                            <!-- CO2 Questions -->
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                                <div class="font-bold text-amber-700 text-xs mb-2.5 flex items-center"><i class="fa-solid fa-cube me-1.5"></i> CO2: Orthographic Projections &amp; Sectional Views</div>
                                <div class="mb-2.5">
                                    <label class="form-label text-slate-600 text-xs font-bold mb-1">Question 3 (CO2 - Projection Principles)</label>
                                    <input type="text" id="drg-ex-q3" class="form-control bg-white text-slate-800 border-slate-200 rounded-lg text-xs py-1.5 focus:border-amber-500 shadow-2xs" value="3. Rate your clarity on 1st &amp; 3rd angle projection principles and sectional views." required>
                                </div>
                                <div>
                                    <label class="form-label text-slate-600 text-xs font-bold mb-1">Question 4 (CO2 - Slot Feedback)</label>
                                    <input type="text" id="drg-ex-q4" class="form-control bg-white text-slate-800 border-slate-200 rounded-lg text-xs py-1.5 focus:border-amber-500 shadow-2xs" value="4. Rate the timeliness of feedback during continuous slot evaluation of drawing sheets." required>
                                </div>
                            </div>

                            <!-- CO3 Questions -->
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                                <div class="font-bold text-emerald-700 text-xs mb-2.5 flex items-center"><i class="fa-solid fa-laptop-code me-1.5"></i> CO3: CAD Software Interface &amp; Commands</div>
                                <div class="mb-2.5">
                                    <label class="form-label text-slate-600 text-xs font-bold mb-1">Question 5 (CO3 - CAD Tools)</label>
                                    <input type="text" id="drg-ex-q5" class="form-control bg-white text-slate-800 border-slate-200 rounded-lg text-xs py-1.5 focus:border-emerald-500 shadow-2xs" value="5. Rate your proficiency in using CAD draw/modify tools, layer management, and dimensioning." required>
                                </div>
                                <div>
                                    <label class="form-label text-slate-600 text-xs font-bold mb-1">Question 6 (CO3 - Workstation Facilities)</label>
                                    <input type="text" id="drg-ex-q6" class="form-control bg-white text-slate-800 border-slate-200 rounded-lg text-xs py-1.5 focus:border-emerald-500 shadow-2xs" value="6. Rate the availability and performance of CAD workstation hardware/software facilities." required>
                                </div>
                            </div>

                            <!-- CO4 Questions -->
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                                <div class="font-bold text-rose-700 text-xs mb-2.5 flex items-center"><i class="fa-solid fa-draw-polygon me-1.5"></i> CO4: 2D Component Drafting &amp; Sectional Plotting</div>
                                <div class="mb-2.5">
                                    <label class="form-label text-slate-600 text-xs font-bold mb-1">Question 7 (CO4 - 2D Component Drafting)</label>
                                    <input type="text" id="drg-ex-q7" class="form-control bg-white text-slate-800 border-slate-200 rounded-lg text-xs py-1.5 focus:border-rose-500 shadow-2xs" value="7. Rate your confidence in generating 2D orthographic component drawings &amp; sectional views in CAD." required>
                                </div>
                                <div>
                                    <label class="form-label text-slate-600 text-xs font-bold mb-1">Question 8 (CO4 - Overall Satisfaction)</label>
                                    <input type="text" id="drg-ex-q8" class="form-control bg-white text-slate-800 border-slate-200 rounded-lg text-xs py-1.5 focus:border-rose-500 shadow-2xs" value="8. Rate overall satisfaction with the 45-hour Drawing Lab curriculum delivery and outcomes." required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2 pt-3 border-t border-slate-100">
                            <button type="button" class="btn btn-light btn-sm text-slate-700 font-bold border border-slate-200 rounded-xl px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm font-bold px-4 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white shadow-xs">
                                <i class="fa-solid fa-paper-plane me-1"></i> Initiate &amp; Send to Student Portal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Bank & Question Paper Editor Modal -->
    <div class="modal fade" id="questionBankModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content bg-white border border-slate-200 rounded-2xl shadow-2xl text-slate-800">
                <div class="modal-header border-b border-slate-100 p-4">
                    <div>
                        <h5 class="modal-title font-bold text-slate-900 text-sm flex items-center"><i class="fa-solid fa-pen-to-square text-amber-600 me-2"></i>Question Bank &amp; Series Test Paper Manager</h5>
                        <small class="text-slate-500">Edit questions, choices, valuation rubrics, and answer keys. Saved changes persist in Question Bank database.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="row g-3 mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label text-slate-600 text-xs font-bold mb-1">Select Series Test Exam</label>
                            <select class="form-select bg-white text-slate-800 border-slate-200 rounded-lg text-xs py-1.5 focus:border-amber-500 shadow-2xs" id="qbModalTestNoSelect" onchange="loadQuestionBankData(this.value)">
                                <option value="1">Series Test 1 (Manual Drawing - Modules I &amp; II)</option>
                                <option value="2">Series Test 2 (CAD Exam - Modules III &amp; IV)</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label text-slate-600 text-xs font-bold mb-1">Paper Title</label>
                            <input type="text" class="form-control bg-white text-slate-800 border-slate-200 rounded-lg text-xs py-1.5 focus:border-amber-500 shadow-2xs" id="qbTestTitleInput">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-slate-600 text-xs font-bold mb-1">Instructions to Candidates</label>
                            <input type="text" class="form-control bg-white text-slate-800 border-slate-200 rounded-lg text-xs py-1.5 focus:border-amber-500 shadow-2xs" id="qbInstructionsInput">
                        </div>
                    </div>

                    <hr class="border-slate-100 my-3">

                    <div id="qbQuestionsEditorContainer">
                        <!-- Dynamic Question Cards -->
                    </div>
                </div>
                <div class="modal-footer border-t border-slate-100 p-3 justify-content-between">
                    <button type="button" class="btn btn-light btn-sm text-slate-700 font-bold border border-slate-200 rounded-xl px-4 py-2" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm font-bold rounded-xl px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white shadow-xs" onclick="saveQuestionBankData()">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save to Question Bank &amp; Update QP
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentQpData = null;
        let currentTestNo = 1;

        async function openQuestionBankModal() {
            const testNo = document.getElementById('caTestSelect').value === 'CA2' ? 2 : 1;
            currentTestNo = testNo;
            document.getElementById('qbModalTestNoSelect').value = testNo;
            await loadQuestionBankData(testNo);
            const modal = new bootstrap.Modal(document.getElementById('questionBankModal'));
            modal.show();
        }

        async function loadQuestionBankData(testNo) {
            currentTestNo = testNo;
            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/series-qp/${testNo}`);
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    currentQpData = data.data;
                    renderQuestionBankEditor();
                }
            } catch (e) {
                alert('Failed to load Question Bank data: ' + e.message);
            }
        }

        function renderQuestionBankEditor() {
            if (!currentQpData) return;
            document.getElementById('qbTestTitleInput').value = currentQpData.test_title || '';
            document.getElementById('qbInstructionsInput').value = currentQpData.instructions || '';
            
            let html = '';
            currentQpData.questions.forEach((q, qIndex) => {
                html += `
                    <div class="card bg-dark border-secondary mb-4 p-3 shadow">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary pb-2">
                            <h6 class="fw-bold text-info mb-0"><i class="fa-solid fa-list-check me-2"></i>${q.q_no} [${q.module} — ${q.co}] (Max ${q.total_marks} Marks)</h6>
                        </div>
                        
                        <!-- Option A -->
                        <div class="border border-info rounded p-3 mb-3" style="background-color: rgba(14, 165, 233, 0.05);">
                            <div class="fw-bold text-info mb-2"><i class="fa-solid fa-code-branch me-1"></i> Option A (Choice Title)</div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Option A Title / Heading</label>
                                <input type="text" class="form-control form-control-sm bg-dark text-light border-secondary" 
                                    value="${escapeHtml(q.option_a.title)}" onchange="updateQpData(${qIndex}, 'option_a', 'title', null, this.value)">
                            </div>
                            ${q.option_a.sub_questions.map((sub, sIndex) => `
                                <div class="p-3 mb-2 bg-dark rounded border border-secondary">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="badge bg-primary fs-6">${sub.sub_no}</span>
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="text-muted small">Marks:</span>
                                            <input type="number" class="form-control form-control-sm bg-dark text-light border-secondary text-end" style="width: 70px;"
                                                value="${sub.marks}" onchange="updateSubQpData(${qIndex}, 'option_a', ${sIndex}, 'marks', this.value)">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-muted small">Question Description</label>
                                        <textarea class="form-control form-control-sm bg-dark text-light border-secondary" rows="2"
                                            onchange="updateSubQpData(${qIndex}, 'option_a', ${sIndex}, 'text', this.value)">${escapeHtml(sub.text)}</textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-muted small">Valuation Scheme / Rubric Breakdown</label>
                                        <input type="text" class="form-control form-control-sm bg-dark text-light border-secondary"
                                            value="${escapeHtml(sub.scheme)}" onchange="updateSubQpData(${qIndex}, 'option_a', ${sIndex}, 'scheme', this.value)">
                                    </div>
                                    <div>
                                        <label class="form-label text-muted small">Model Answer Key / Solution Steps</label>
                                        <textarea class="form-control form-control-sm bg-dark text-light border-secondary" rows="2"
                                            onchange="updateSubQpData(${qIndex}, 'option_a', ${sIndex}, 'answer_key', this.value)">${escapeHtml(sub.answer_key)}</textarea>
                                    </div>
                                </div>
                            `).join('')}
                        </div>

                        <!-- Choice Divider -->
                        <div class="text-center font-monospace text-danger fw-bold my-2 fs-6">--- EITHER OPTION A OR OPTION B ---</div>

                        <!-- Option B -->
                        <div class="border border-warning rounded p-3 mb-2" style="background-color: rgba(245, 158, 11, 0.05);">
                            <div class="fw-bold text-warning mb-2"><i class="fa-solid fa-code-branch me-1"></i> Option B (Choice Title)</div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Option B Title / Heading</label>
                                <input type="text" class="form-control form-control-sm bg-dark text-light border-secondary" 
                                    value="${escapeHtml(q.option_b.title)}" onchange="updateQpData(${qIndex}, 'option_b', 'title', null, this.value)">
                            </div>
                            ${q.option_b.sub_questions.map((sub, sIndex) => `
                                <div class="p-3 mb-2 bg-dark rounded border border-secondary">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="badge bg-warning text-dark fs-6">${sub.sub_no}</span>
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="text-muted small">Marks:</span>
                                            <input type="number" class="form-control form-control-sm bg-dark text-light border-secondary text-end" style="width: 70px;"
                                                value="${sub.marks}" onchange="updateSubQpData(${qIndex}, 'option_b', ${sIndex}, 'marks', this.value)">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-muted small">Question Description</label>
                                        <textarea class="form-control form-control-sm bg-dark text-light border-secondary" rows="2"
                                            onchange="updateSubQpData(${qIndex}, 'option_b', ${sIndex}, 'text', this.value)">${escapeHtml(sub.text)}</textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-muted small">Valuation Scheme / Rubric Breakdown</label>
                                        <input type="text" class="form-control form-control-sm bg-dark text-light border-secondary"
                                            value="${escapeHtml(sub.scheme)}" onchange="updateSubQpData(${qIndex}, 'option_b', ${sIndex}, 'scheme', this.value)">
                                    </div>
                                    <div>
                                        <label class="form-label text-muted small">Model Answer Key / Solution Steps</label>
                                        <textarea class="form-control form-control-sm bg-dark text-light border-secondary" rows="2"
                                            onchange="updateSubQpData(${qIndex}, 'option_b', ${sIndex}, 'answer_key', this.value)">${escapeHtml(sub.answer_key)}</textarea>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            });
            document.getElementById('qbQuestionsEditorContainer').innerHTML = html;
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        function updateQpData(qIndex, optKey, field, subIndex, val) {
            if (currentQpData && currentQpData.questions[qIndex]) {
                currentQpData.questions[qIndex][optKey][field] = val;
            }
        }

        function updateSubQpData(qIndex, optKey, subIndex, field, val) {
            if (currentQpData && currentQpData.questions[qIndex]) {
                if (field === 'marks') val = parseFloat(val) || 0;
                currentQpData.questions[qIndex][optKey].sub_questions[subIndex][field] = val;
            }
        }

        async function saveQuestionBankData() {
            if (!currentQpData) return;
            currentQpData.test_title = document.getElementById('qbTestTitleInput').value;
            currentQpData.instructions = document.getElementById('qbInstructionsInput').value;

            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/series-qp/save`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        test_no: currentTestNo,
                        payload: currentQpData
                    })
                });
                const data = await res.json();
                alert(data.message);
            } catch (e) {
                alert('Error saving Question Bank: ' + e.message);
            }
        }
    </script>
</body>
</html>
