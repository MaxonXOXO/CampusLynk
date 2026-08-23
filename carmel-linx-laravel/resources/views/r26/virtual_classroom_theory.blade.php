<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>[{{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}] Virtual Classroom (Theory) - {{ $batchSubject->subject_name }}</title>
  
  <!-- Canonical Vite Asset Pipeline -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  
  <!-- Google Fonts & Material Symbols -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background-color: #FAFAFB;
      color: #0f172a;
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
    .bg-panel {
      background-color: #ffffff;
      border-color: #e2e8f0;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05);
    }
    .text-title {
      color: #0f172a;
    }
    .text-muted {
      color: #64748b;
    }
    .border-card {
      border-color: #e2e8f0;
    }
    .bg-card-hover:hover {
      background-color: #f8fafc;
    }

    input, select, textarea {
      font-size: 0.875rem !important; /* 14px minimum per policy */
    }
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: #f1f5f9;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }

    /* Tab strip horizontal scroll without page overflow */
    .tab-strip-scroll {
      overflow-x: auto;
      scrollbar-width: thin;
    }
  </style>
</head>
<body class="bg-[#FAFAFB] text-slate-900 min-h-screen font-sans antialiased p-3 sm:p-5 lg:p-6 custom-scrollbar">

  @php
    $copoData = json_decode($courseFile->parsed_copo, true) ?: [];
    $cieMarks = $copoData['cie_marks'] ?? 40;
    $eseMarks = $copoData['ese_marks'] ?? 60;
    $credit = $copoData['credit'] ?? 3;
    $ltpr = $copoData['l_t_p_r'] ?? '3:0:0:0';
    $totalHours = $copoData['total_hours'] ?? 60;
    $mappings = $copoData['mappings'] ?? [];
    $cosList = json_decode($courseFile->parsed_cos, true) ?: [];
    $modulesList = json_decode($courseFile->parsed_modules, true) ?: [];

    $role = Session::get('userRole');
    $backUrl = '/dashboard/lecturer';
    $backLabel = 'Faculty Platform';
    if ($role === 'HOD') {
        $backUrl = '/dashboard/hod';
        $backLabel = 'Department Console (HOD)';
    } elseif ($role === 'Admin') {
        $backUrl = '/dashboard/admin';
        $backLabel = 'Admin Desk';
    } elseif ($role === 'Principal') {
        $backUrl = '/dashboard/principal';
        $backLabel = 'Principal Desk';
    } elseif ($role === 'Super_Admin' || $role === 'SuperAdmin') {
        $backUrl = '/dashboard/superadmin';
        $backLabel = 'SuperAdmin Desk';
    } elseif ($role === 'Gen_Dept_Coordinator_Aided') {
        $backUrl = '/dashboard/general-coordinator-aided';
        $backLabel = 'General Dept Coordinator';
    } elseif ($role === 'Gen_Dept_Coordinator_Self_Finance') {
        $backUrl = '/dashboard/general-coordinator-sf';
        $backLabel = 'General Dept Coordinator';
    }
  @endphp

  <!-- MAIN CONTAINER -->
  <div class="w-full max-w-[1600px] mx-auto space-y-4">
    
    <!-- TOP BREADCRUMB & CONSOLE ACTIONS -->
    <div class="flex flex-wrap justify-between items-center bg-white border border-slate-200/80 rounded-2xl px-5 py-3 gap-3 shadow-xs">
      <!-- Breadcrumb Navigation -->
      <nav class="flex items-center gap-2 text-xs sm:text-sm font-medium text-slate-500">
        <a href="{{ $backUrl }}" class="hover:text-blue-600 transition flex items-center gap-1.5 font-semibold text-slate-700">
          <span class="material-symbols-rounded text-base text-blue-600">domain</span>
          <span>{{ $backLabel }}</span>
        </a>
        <span class="text-slate-300">/</span>
        <a href="{{ $backUrl }}" class="hover:text-blue-600 transition font-medium text-slate-600">My Batches</a>
        <span class="text-slate-300">/</span>
        <span class="font-bold text-slate-900 flex items-center gap-1">
          <span>Virtual Classroom</span>
          <span class="text-xs font-bold font-mono px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200/80 rounded-md">Theory</span>
        </span>
      </nav>

      <!-- Right Action Tools -->
      <div class="flex items-center gap-2.5">
        <!-- AI Extraction Badge -->
        @php $isAiActive = \App\Http\Controllers\SystemSettingController::isAiEnabled(); @endphp
        @if($isAiActive)
          <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg font-bold text-xs select-none flex items-center gap-1.5 shadow-2xs">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>AI Copilot Active</span>
          </span>
        @else
          <span class="px-2.5 py-1 bg-slate-100 text-slate-600 border border-slate-200 rounded-lg font-bold text-xs select-none flex items-center gap-1.5 shadow-2xs">
            <span class="material-symbols-rounded text-xs text-slate-500">database</span>
            <span>Local Extract Engine</span>
          </span>
        @endif

        <!-- Lecturer Identity -->
        <div class="flex items-center gap-2 border-l border-slate-200 pl-3">
          <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center text-xs font-bold border border-blue-200/80">
            {{ substr(Session::get('userName', 'L'), 0, 1) }}
          </span>
          <div class="hidden sm:block text-left">
            <p class="text-xs font-bold text-slate-900 leading-tight">{{ Session::get('userName', 'Lecturer') }}</p>
            <p class="text-[11px] text-slate-500 leading-none">Course Faculty</p>
          </div>
        </div>

        <!-- Fullscreen Mode Button -->
        <button onclick="toggleSidebarWideMode()" id="btn-fullscreen-toggle" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-all border border-slate-200 cursor-pointer flex items-center gap-1.5 shadow-2xs">
          <span class="material-symbols-rounded text-sm">fullscreen</span>
          <span class="hidden md:inline">Workspace View</span>
        </button>

        <!-- Back to Console -->
        <a href="{{ $backUrl }}" onclick="localStorage.removeItem('classroomFullscreen'); window.close(); setTimeout(function(){ window.location.href = '{{ $backUrl }}'; }, 100); return false;" class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl font-bold text-xs transition-all border border-rose-200 cursor-pointer flex items-center gap-1.5 shadow-2xs">
          <span class="material-symbols-rounded text-sm">arrow_back</span>
          <span>Back to Batches</span>
        </a>
      </div>
    </div>

    <!-- CLASSROOM HERO HEADER CARD -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-5">
      <div class="space-y-2">
        <!-- Badges Row -->
        <div class="flex flex-wrap items-center gap-2">
          <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-blue-50 text-blue-700 border border-blue-200/80">
            {{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R2021 · THEORY' : 'R2026 · THEORY' }}
          </span>
          <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-purple-50 text-purple-700 border border-purple-200/80">
            {{ $batchSubject->classroom_id }} · S{{ $batchSubject->semester }}
          </span>
          <span class="px-2.5 py-0.5 rounded-md font-mono font-bold text-xs bg-slate-100 text-slate-700 border border-slate-200">
            {{ $batchSubject->subject_code }}
          </span>
        </div>

        <!-- Course Title -->
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
          <span>{{ $batchSubject->subject_name }}</span>
        </h1>

        <!-- Metadata Row -->
        <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm font-medium text-slate-500">
          <span class="flex items-center gap-1"><span class="material-symbols-rounded text-sm text-slate-400">groups</span> Classroom: <strong class="text-slate-800">{{ $batchSubject->classroom_id }}</strong></span>
          <span class="text-slate-300">•</span>
          <span class="flex items-center gap-1"><span class="material-symbols-rounded text-sm text-slate-400">calendar_month</span> Semester: <strong class="text-slate-800">{{ $batchSubject->semester }}</strong></span>
          <span class="text-slate-300">•</span>
          <span class="flex items-center gap-1"><span class="material-symbols-rounded text-sm text-slate-400">schedule</span> Target: <strong class="text-slate-800">{{ $totalHours }} Hours</strong> (L:T:P:R {{ $ltpr }})</span>
          <span class="text-slate-300">•</span>
          <span class="flex items-center gap-1"><span class="material-symbols-rounded text-sm text-slate-400">assignment_turned_in</span> CIE: <strong class="text-slate-800">{{ $cieMarks }}M</strong> | ESE: <strong class="text-slate-800">{{ $eseMarks }}M</strong></span>
        </div>
      </div>

      <!-- Quick Action Buttons -->
      <div class="flex items-center gap-2.5 self-stretch sm:self-auto flex-wrap">
        <a href="/r26/classroom/lesson-plan/print/{{ $batchSubject->id }}" target="_blank" class="flex-1 sm:flex-none px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all border border-slate-200 flex items-center justify-center gap-1.5 shadow-2xs">
          <span class="material-symbols-rounded text-sm">print</span>
          <span>Print Planner</span>
        </a>
        <a href="/r26/classroom/internals/print-cie/{{ $batchSubject->id }}" target="_blank" class="flex-1 sm:flex-none px-3.5 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs transition-all border border-blue-200 flex items-center justify-center gap-1.5 shadow-2xs">
          <span class="material-symbols-rounded text-sm">description</span>
          <span>Print CIE Marks</span>
        </a>
        <a href="/r26/classroom/course-file/{{ $batchSubject->id }}" class="flex-1 sm:flex-none px-3.5 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold text-xs transition-all border border-purple-200 flex items-center justify-center gap-1.5 shadow-2xs">
          <span class="material-symbols-rounded text-sm">folder_open</span>
          <span>Course File</span>
        </a>
      </div>
    </div>

    <!-- MAIN GRID LAYOUT -->
    <div id="main-classroom-grid" class="grid grid-cols-1 lg:grid-cols-4 gap-5">
      
      <!-- NAVIGATION PANEL (COMPACT) -->
      <div id="sidebar-panel-column" class="lg:col-span-1 space-y-3 transition-all duration-300">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-3 shadow-xs space-y-1.5 tab-strip-scroll">
          <button onclick="switchTab('outline')" id="btn-outline" class="w-full text-left px-3.5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2.5 transition-all bg-blue-50 text-blue-700 border-l-4 border-blue-600 shadow-2xs cursor-pointer">
            <span class="material-symbols-rounded text-base">import_contacts</span>
            <span>Course Outline</span>
          </button>
          
          <button onclick="switchTab('planner')" id="btn-planner" class="w-full text-left px-3.5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2.5 transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
            <span class="material-symbols-rounded text-base">calendar_month</span>
            <span>Lesson Planner</span>
          </button>
          
          <button onclick="switchTab('cia')" id="btn-cia" class="w-full text-left px-3.5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2.5 transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
            <span class="material-symbols-rounded text-base">fact_check</span>
            <span>Continuous Assessment</span>
          </button>
          
          <button onclick="switchTab('roster')" id="btn-roster" class="w-full text-left px-3.5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2.5 transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
            <span class="material-symbols-rounded text-base">group</span>
            <span>Student Roster ({{ $students->count() }})</span>
          </button>

          <button onclick="switchTab('series')" id="btn-series" class="w-full text-left px-3.5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2.5 transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
            <span class="material-symbols-rounded text-base">quiz</span>
            <span>Series Exams</span>
          </button>

          <button onclick="switchTab('internals')" id="btn-internals" class="w-full text-left px-3.5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2.5 transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
            <span class="material-symbols-rounded text-base">assignment_turned_in</span>
            <span>Internal Marks</span>
          </button>

          <button onclick="switchTab('attainment')" id="btn-attainment" class="w-full text-left px-3.5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2.5 transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
            <span class="material-symbols-rounded text-base">equalizer</span>
            <span>Course Attainment & Surveys</span>
          </button>

          <button onclick="switchTab('materials')" id="btn-materials" class="w-full text-left px-3.5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2.5 transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
            <span class="material-symbols-rounded text-base">folder_special</span>
            <span>Digital Learning Hub</span>
          </button>
        </div>
      </div>
            Student Roster ({{ $students->count() }})
          </button>

          <button onclick="switchTab('series')" id="btn-series" class="w-full text-left px-3 py-2.5 rounded-lg font-bold text-xs flex items-center gap-2 transition-all text-muted hover:bg-slate-100 hover:text-slate-900 cursor-pointer">
            <span class="material-symbols-rounded text-sm">quiz</span>
            Series Exams
          </button>

          <button onclick="switchTab('internals')" id="btn-internals" class="w-full text-left px-3 py-2.5 rounded-lg font-bold text-xs flex items-center gap-2 transition-all text-muted hover:bg-slate-100 hover:text-slate-900 cursor-pointer">
            <span class="material-symbols-rounded text-sm">assignment_turned_in</span>
            Internal Marks
          </button>

          <button onclick="switchTab('attainment')" id="btn-attainment" class="w-full text-left px-3 py-2.5 rounded-lg font-bold text-xs flex items-center gap-2 transition-all text-muted hover:bg-slate-100 hover:text-slate-900 cursor-pointer">
            <span class="material-symbols-rounded text-sm">equalizer</span>
            Course Attainment & Surveys
          </button>

          <button onclick="switchTab('materials')" id="btn-materials" class="w-full text-left px-3 py-2.5 rounded-lg font-bold text-xs flex items-center gap-2 transition-all text-muted hover:bg-slate-100 hover:text-slate-900 cursor-pointer">
            <span class="material-symbols-rounded text-sm">folder_special</span>
            Study Materials & Pre-Class Hub
          </button>

          <a href="/r26/classroom/course-file/{{ $batchSubject->id }}" target="_blank" class="w-full text-left px-3 py-2.5 rounded-lg font-bold text-xs flex items-center gap-2 transition-all text-muted hover:bg-slate-100 hover:text-slate-900 cursor-pointer no-underline">
            <span class="material-symbols-rounded text-sm">folder_open</span>
            Course File Preparation R2026
          </a>
        </div>

        <!-- QUICK SNAPSHOT WIDGET -->
        <div class="bg-panel border rounded-xl p-4 space-y-3 shadow-md">
          <h4 class="font-bold text-title text-xs uppercase tracking-wider">Evaluation Policy</h4>
          <div class="space-y-2 text-xs">
            <div class="flex justify-between border-b border-slate-200 pb-1.5">
              <span class="text-muted">CIA Max Marks:</span>
              <span class="font-bold text-title">{{ $cieMarks }} Marks</span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-1.5">
              <span class="text-muted">ESE Max Marks:</span>
              <span class="font-bold text-title">{{ $eseMarks }} Marks</span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-1.5">
              <span class="text-muted">Syllabus Credits:</span>
              <span class="font-bold text-title">{{ $credit }} Credits</span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-1.5">
              <span class="text-muted">L : T : P : R:</span>
              <span class="font-bold text-title">{{ $ltpr }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-muted">Instructional Hours:</span>
              <span class="font-bold text-emerald-500">{{ $totalHours }} Hours</span>
            </div>
          </div>
        </div>
      </div>

      <!-- DETAILS PANEL COLUMN -->
      <div id="details-panel-column" class="lg:col-span-3 transition-all duration-300">
        
        <!-- TAB: COURSE OUTLINE -->
        <div id="tab-outline" class="tab-panel space-y-5">
          
          <!-- Course Identity & Syllabus Extraction Card -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
              <div class="space-y-1">
                <div class="flex flex-wrap items-center gap-2">
                  <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-blue-50 text-blue-700 border border-blue-200/80">
                    {{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R2021 · THEORY' : 'R2026 · THEORY' }}
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

              <!-- Syllabus Actions & Status -->
              <div class="flex items-center gap-2 flex-wrap">
                @if($courseFile->syllabus_pdf_path)
                  <a href="/storage/{{ $courseFile->syllabus_pdf_path }}" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 rounded-xl text-xs font-bold transition-all border border-slate-200 flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-4 h-4 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h6"/><path d="M9 11h6"/></svg>
                    <span>View Syllabus PDF</span>
                  </a>
                  <button onclick="toggleSyllabusUploadWorkspace()" class="px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 border border-blue-200/80 cursor-pointer shadow-2xs">
                    <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg>
                    <span>Replace Syllabus</span>
                  </button>
                @else
                  <button onclick="toggleSyllabusUploadWorkspace()" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs cursor-pointer">
                    <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    <span>Upload Syllabus</span>
                  </button>
                @endif
                <input type="file" id="syllabusFileInput" accept="application/pdf" class="hidden" onchange="performSyllabusUpload(this)">
              </div>
            </div>

            <!-- Syllabus Upload & Processing Workspace Dropzone (Collapsible / Active) -->
            <div id="syllabusUploadWorkspace" class="{{ $courseFile->syllabus_pdf_path ? 'hidden' : '' }} pt-2">
              <div id="syllabusDropzone" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleFileDrop(event)" class="border-2 border-dashed border-slate-300 hover:border-blue-500 bg-slate-50/70 hover:bg-blue-50/40 rounded-2xl p-6 transition-all text-center space-y-3 cursor-pointer" onclick="document.getElementById('syllabusFileInput').click()">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 mx-auto flex items-center justify-center border border-blue-200">
                  <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </div>
                <div>
                  <h4 class="text-sm font-bold text-slate-900">Upload Course Syllabus PDF</h4>
                  <p class="text-xs text-slate-500 mt-0.5">Drag &amp; drop your official syllabus PDF here, or <span class="text-blue-600 font-semibold underline">browse files</span></p>
                </div>
                <div class="flex items-center justify-center gap-2 text-xs font-semibold text-slate-400">
                  <span class="px-2.5 py-0.5 rounded-md bg-white border border-slate-200">PDF format only</span>
                  <span>•</span>
                  <span class="px-2.5 py-0.5 rounded-md bg-white border border-slate-200">Max 10MB</span>
                </div>
              </div>

              <!-- Selected File Preview Box -->
              <div id="syllabusFilePreview" class="hidden mt-3 p-4 bg-white border border-slate-200/90 rounded-2xl flex items-center justify-between shadow-2xs">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                  </div>
                  <div>
                    <h5 id="previewFileName" class="text-sm font-bold text-slate-900 leading-tight">syllabus.pdf</h5>
                    <p id="previewFileSize" class="text-xs text-slate-500 mt-0.5">2.4 MB · Ready to process</p>
                  </div>
                </div>
                <div class="flex items-center gap-2">
                  <button type="button" onclick="cancelSelectedFile(event)" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition cursor-pointer" title="Cancel selection">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </button>
                  <button type="button" onclick="submitSelectedSyllabus(event)" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    <span>Extract Academic Structure</span>
                  </button>
                </div>
              </div>

              <!-- Processing / Parsing Indeterminate State -->
              <div id="syllabusProcessingState" class="hidden mt-3 p-5 bg-blue-50/60 border border-blue-200 rounded-2xl text-center space-y-3">
                <div class="flex items-center justify-center gap-2 text-blue-700">
                  <svg class="w-5 h-5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                  <span class="text-sm font-bold">Processing Syllabus &amp; Extracting Academic Structure</span>
                </div>
                <div class="max-w-md mx-auto space-y-1 text-xs text-slate-600">
                  <p class="flex items-center justify-center gap-1.5"><svg class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Reading course metadata &amp; credits</p>
                  <p class="flex items-center justify-center gap-1.5"><svg class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Identifying Course Outcomes (COs) &amp; Bloom's Taxonomy</p>
                  <p class="flex items-center justify-center gap-1.5"><svg class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Detecting modules, units, and CO-PO mapping matrix</p>
                </div>
              </div>

              <!-- Error Alert Box -->
              <div id="syllabusErrorAlert" class="hidden mt-3 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center justify-between text-rose-800 text-xs font-semibold">
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-rose-600 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                  <span id="syllabusErrorMessage">Failed to extract syllabus. Please verify PDF format.</span>
                </div>
                <button type="button" onclick="document.getElementById('syllabusFileInput').click()" class="px-3 py-1 bg-rose-100 hover:bg-rose-200 text-rose-900 rounded-lg font-bold transition">Try Again</button>
              </div>
            </div>

            <!-- Metadata Metrics Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
              <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3">
                <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Credits</span>
                <span class="text-base font-bold text-slate-900 mt-0.5 block">{{ $credits ?? 3 }} Credits</span>
              </div>
              <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3">
                <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Teaching Scheme</span>
                <span class="text-base font-bold font-mono text-slate-900 mt-0.5 block">L:T:P:R {{ $ltpr }}</span>
              </div>
              <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3">
                <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Target Hours</span>
                <span class="text-base font-bold text-slate-900 mt-0.5 block">{{ $totalHours }} Hours</span>
              </div>
              <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3">
                <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Evaluation</span>
                <span class="text-base font-bold text-slate-900 mt-0.5 block">CIE {{ $cieMarks }}M | ESE {{ $eseMarks }}M</span>
              </div>
            </div>
          </div>

          <!-- Course Outcomes (COs) Section -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
                  <span class="material-symbols-rounded text-base">stars</span>
                </span>
                <h3 class="font-bold text-slate-900 text-base">Course Outcomes (COs)</h3>
              </div>
              <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg border border-slate-200">
                {{ count($cosList) }} Outcomes Configured
              </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              @forelse($cosList as $co)
                @php
                  $cog = strtolower($co['cognitive_level'] ?? 'understand');
                  $badgeClasses = 'bg-blue-50 text-blue-700 border-blue-200';
                  if (str_contains($cog, 'apply')) {
                    $badgeClasses = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                  } elseif (str_contains($cog, 'analy') || str_contains($cog, 'eval')) {
                    $badgeClasses = 'bg-purple-50 text-purple-700 border-purple-200';
                  } elseif (str_contains($cog, 'creat')) {
                    $badgeClasses = 'bg-amber-50 text-amber-700 border-amber-200';
                  } elseif (str_contains($cog, 'remem')) {
                    $badgeClasses = 'bg-slate-100 text-slate-700 border-slate-200';
                  }
                @endphp
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-xl p-4 space-y-2 hover:border-blue-300 transition-all shadow-2xs">
                  <div class="flex items-center justify-between gap-2">
                    <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-bold font-mono text-xs border border-blue-200">
                      {{ $co['id'] }}
                    </span>
                    <div class="flex items-center gap-2">
                      @if(!empty($co['cognitive_level']))
                        <span class="px-2 py-0.5 rounded-md font-semibold text-xs border {{ $badgeClasses }}">
                          {{ $co['cognitive_level'] }}
                        </span>
                      @endif
                      <span class="text-xs font-medium text-slate-500 font-mono">
                        {{ $co['duration'] ?? '12' }} Periods
                      </span>
                    </div>
                  </div>
                  <p class="text-sm font-medium text-slate-800 leading-relaxed">
                    {{ $co['description'] }}
                  </p>
                </div>
              @empty
                <div class="col-span-2 text-center py-6 text-slate-500 text-sm italic bg-slate-50 rounded-xl border border-dashed border-slate-200">
                  No Course Outcomes extracted yet. Please upload the syllabus PDF to automatically populate outcomes.
                </div>
              @endforelse
            </div>
          </div>

          <!-- Course Modules & Major Topics Section -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center text-sm font-bold border border-purple-200/80">
                  <span class="material-symbols-rounded text-base">collections_bookmark</span>
                </span>
                <h3 class="font-bold text-slate-900 text-base">Course Modules &amp; Major Topics</h3>
              </div>
              <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg border border-slate-200">
                {{ count($modulesList) }} Modules
              </span>
            </div>

            <div class="space-y-3">
              @forelse($modulesList as $mod)
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-xl p-4 space-y-2 hover:border-slate-300 transition-all">
                  <div class="flex items-center justify-between gap-2 border-b border-slate-200/60 pb-2">
                    <div class="flex items-center gap-2">
                      <span class="px-2.5 py-0.5 rounded-lg bg-purple-50 text-purple-700 font-bold text-xs border border-purple-200">
                        Module {{ $mod['module_id'] }}
                      </span>
                      <h4 class="text-sm font-bold text-slate-900">{{ $mod['title'] ?? 'Module ' . $mod['module_id'] }}</h4>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-md bg-white border border-slate-200 text-slate-700 font-bold text-xs font-mono shadow-2xs">
                      {{ $mod['hours'] ?? floor($totalHours / 4) }} Hours
                    </span>
                  </div>
                  <p class="text-sm font-normal text-slate-700 leading-relaxed whitespace-pre-line pt-1">
                    {{ $mod['content'] ?? '' }}
                  </p>
                </div>
              @empty
                <div class="text-center py-6 text-slate-500 text-sm italic bg-slate-50 rounded-xl border border-dashed border-slate-200">
                  No modules extracted yet. Upload the syllabus PDF to automatically extract module topics.
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
                  <h3 class="font-bold text-slate-900 text-base">CO-PO Correlation Matrix</h3>
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
                    <th class="p-3 text-left pl-4 w-28">Course Outcome</th>
                    @for($p = 1; $p <= 11; $p++)
                      <th class="p-3 font-mono">PO{{ $p }}</th>
                    @endfor
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @foreach($cosList as $co)
                    @php
                      $coId = $co['id'];
                      $m = $mappings[$coId] ?? [];
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-all">
                      <td class="p-3 text-left font-bold text-blue-700 pl-4 font-mono">{{ $coId }}</td>
                      @for($p = 1; $p <= 11; $p++)
                        @php
                          $val = $m["PO$p"] ?? '-';
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

        <!-- TAB: LESSON PLANNER -->
        <div id="tab-planner" class="tab-panel space-y-5 hidden">
          @php
            $totalPlannedHours = $lessonPlans->sum(function($lp) { return $lp->allocated_hours ?: 1; });
            $completedPlans = $lessonPlans->where('status', 'Completed');
            $completedHours = $completedPlans->sum(function($lp) { return $lp->allocated_hours ?: 1; });
            $completedCount = $completedPlans->count();
            $pendingCount = max(0, $lessonPlans->count() - $completedCount);
            $remainingHours = max(0, $totalPlannedHours - $completedHours);
            $coveragePct = $totalPlannedHours > 0 ? round(($completedHours / $totalPlannedHours) * 100) : 0;
            $coTagsList = $lessonPlans->pluck('co_id')->filter()->unique()->values();
          @endphp

          <!-- PLANNER METRIC BAR (4-CARD GRID) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Planned Hours -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-indigo-300">
              <div class="flex items-center justify-between">
                <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <span class="text-xs font-bold text-slate-400 font-mono uppercase tracking-wider">Target</span>
              </div>
              <div class="mt-3">
                <div class="text-2xl font-black text-slate-900 font-heading tracking-tight" id="metricPlannedHours">{{ $totalPlannedHours }} <span class="text-xs font-bold text-slate-500">Hrs</span></div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Planned Hours</div>
                <div class="text-xs text-slate-500 font-medium mt-1">{{ count($lessonPlans) }} scheduled periods</div>
              </div>
            </div>

            <!-- 2. Completed Hours -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-emerald-300">
              <div class="flex items-center justify-between">
                <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60 font-mono">Delivered</span>
              </div>
              <div class="mt-3">
                <div class="text-2xl font-black text-emerald-700 font-heading tracking-tight" id="metricCompletedHours">{{ $completedHours }} <span class="text-xs font-bold text-emerald-600/70">Hrs</span></div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Completed Hours</div>
                <div class="text-xs text-slate-500 font-medium mt-1" id="metricCompletedCount">{{ $completedCount }} sessions conducted</div>
              </div>
            </div>

            <!-- 3. Remaining Hours -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-amber-300">
              <div class="flex items-center justify-between">
                <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200/60 font-mono">Pending</span>
              </div>
              <div class="mt-3">
                <div class="text-2xl font-black text-slate-900 font-heading tracking-tight" id="metricRemainingHours">{{ $remainingHours }} <span class="text-xs font-bold text-slate-500">Hrs</span></div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Remaining Hours</div>
                <div class="text-xs text-slate-500 font-medium mt-1" id="metricPendingCount">{{ $pendingCount }} sessions scheduled</div>
              </div>
            </div>

            <!-- 4. Syllabus Coverage -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-blue-300">
              <div class="flex items-center justify-between">
                <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </span>
                <span class="text-xs font-bold text-blue-700 font-mono">{{ $coveragePct }}%</span>
              </div>
              <div class="mt-3">
                <div class="text-2xl font-black text-blue-700 font-heading tracking-tight" id="metricCoveragePct">{{ $coveragePct }}%</div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Syllabus Coverage</div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden mt-2">
                  <div id="metricCoverageBar" class="bg-blue-600 h-full rounded-full transition-all duration-500" style="width: {{ $coveragePct }}%;"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- PLANNER MAIN WORKSPACE CARD -->
          <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
            <!-- Action Toolbar Header -->
            <div class="p-5 sm:px-6 border-b border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div>
                <div class="flex items-center gap-2">
                  <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center text-sm font-bold border border-indigo-200/80">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  </span>
                  <h3 class="text-base font-bold text-slate-900">Academic Lesson Planner</h3>
                </div>
                <p class="text-slate-500 text-xs mt-1">Manage session topics, pedagogy, Bloom's cognitive taxonomy, proposed dates, and real-time execution status.</p>
              </div>

              <!-- Action Buttons -->
              <div class="flex items-center gap-2 flex-wrap">
                <a href="/r26/classroom/lesson-plan/print/{{ $batchSubject->id }}" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition-all flex items-center gap-1.5 shadow-2xs no-underline cursor-pointer">
                  <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                  <span>Print Plan</span>
                </a>

                <button type="button" onclick="regenerateTheoryLessonPlan()" id="btnRegenPlan" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition-all flex items-center gap-1.5 shadow-2xs cursor-pointer" title="Re-generate all lesson plans from parsed syllabus outcomes and modules">
                  <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                  <span>Regenerate</span>
                </button>

                <button type="button" onclick="loadTheoryTemplate()" id="btnLoadTemplate" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition-all flex items-center gap-1.5 shadow-2xs cursor-pointer" title="Load saved template for subject {{ $batchSubject->subject_code }}">
                  <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                  <span>Load Template</span>
                </button>

                <button type="button" id="btnSaveTemplate" onclick="saveAsTemplate()" class="px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200/80 transition-all flex items-center gap-1.5 shadow-2xs cursor-pointer" title="Save as reusable template for other batches with the same subject">
                  <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                  <span>Save as Template</span>
                </button>

                <button type="button" id="btnSavePlanner" onclick="saveLessonPlanEdits()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 shadow-xs cursor-pointer">
                  <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                  <span>Save Changes</span>
                </button>
              </div>
            </div>

            <!-- Interactive Filter / Search Bar -->
            <div class="border-b border-slate-100 bg-slate-50/60 p-3 sm:px-6 flex flex-wrap items-center justify-between gap-3">
              <div class="flex items-center gap-2.5 flex-wrap">
                <!-- Search Box -->
                <div class="relative min-w-[200px] sm:w-64">
                  <input type="text" id="plannerSearchInput" oninput="filterPlannerRows()" placeholder="Search topic or period..." class="w-full pl-8 pr-3 py-1.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-indigo-500 shadow-2xs">
                  <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                  </span>
                </div>

                <!-- CO Filter -->
                <select id="plannerCoFilter" onchange="filterPlannerRows()" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-indigo-500 shadow-2xs cursor-pointer">
                  <option value="ALL">All Outcomes (CO)</option>
                  @foreach($coTagsList as $coTag)
                    <option value="{{ $coTag }}">{{ $coTag }}</option>
                  @endforeach
                </select>

                <!-- Status Filter -->
                <select id="plannerStatusFilter" onchange="filterPlannerRows()" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-indigo-500 shadow-2xs cursor-pointer">
                  <option value="ALL">All Statuses</option>
                  <option value="Pending">Pending Only</option>
                  <option value="Completed">Completed Only</option>
                </select>
              </div>

              <!-- Counter -->
              <span id="plannerVisibleCount" class="text-xs font-bold text-slate-500 ml-auto">Showing {{ count($lessonPlans) }} of {{ count($lessonPlans) }} sessions</span>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse min-w-[960px]">
                <thead>
                  <tr class="bg-slate-50/90 text-xs font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200 sticky top-0 z-10">
                    <th class="p-3.5 w-16 text-center">Period</th>
                    <th class="p-3.5 w-20 text-center">CO Tag</th>
                    <th class="p-3.5 min-w-[280px]">Topic / Content Scheduled</th>
                    <th class="p-3.5 w-36">Pedagogy</th>
                    <th class="p-3.5 w-32">Bloom Taxonomy</th>
                    <th class="p-3.5 w-36">Proposed Date</th>
                    <th class="p-3.5 w-36">Actual Date</th>
                    <th class="p-3.5 w-20 text-center">Hours</th>
                    <th class="p-3.5 w-32">Status</th>
                  </tr>
                </thead>
                <tbody id="plannerTableBody" class="divide-y divide-slate-100 text-sm font-normal">
                  @forelse($lessonPlans as $lp)
                    @php
                      $co = $lp->co_id ?: 'CO1';
                      $coBadgeClass = match($co) {
                        'CO1' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'CO2' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'CO3' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'CO4' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'CO5' => 'bg-rose-50 text-rose-700 border-rose-200',
                        'CO6' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                      };
                      $isCompleted = ($lp->status === 'Completed');
                    @endphp
                    <tr data-lp-id="{{ $lp->id }}" data-co="{{ $lp->co_id }}" data-status="{{ $lp->status }}" class="planner-row hover:bg-slate-50/70 transition-colors {{ $isCompleted ? 'bg-emerald-50/15' : '' }}">
                      <!-- Period -->
                      <td class="p-3 font-mono font-bold text-center text-slate-900 text-sm">
                        <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 inline-flex items-center justify-center font-bold text-xs border border-slate-200/80">#{{ $lp->day_no }}</span>
                      </td>

                      <!-- CO Tag -->
                      <td class="p-3 text-center">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold border shadow-2xs {{ $coBadgeClass }}">{{ $lp->co_id ?: '—' }}</span>
                      </td>

                      <!-- Topic & Content -->
                      <td class="p-2.5">
                        <textarea data-field="topic_content" rows="2" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-3 py-2 text-slate-900 text-sm font-normal transition-all outline-none resize-y leading-snug">{{ $lp->topic_content }}</textarea>
                      </td>

                      <!-- Pedagogy -->
                      <td class="p-2.5">
                        <select data-field="pedagogy" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-slate-800 text-sm font-medium transition-all outline-none cursor-pointer">
                          <option value="Lecture" {{ $lp->pedagogy == 'Lecture' ? 'selected' : '' }}>Lecture</option>
                          <option value="Tutorial" {{ $lp->pedagogy == 'Tutorial' ? 'selected' : '' }}>Tutorial</option>
                          <option value="Practical" {{ $lp->pedagogy == 'Practical' ? 'selected' : '' }}>Practical</option>
                          <option value="Exam" {{ $lp->pedagogy == 'Exam' ? 'selected' : '' }}>Exam</option>
                        </select>
                      </td>

                      <!-- Taxonomy -->
                      <td class="p-2.5">
                        <input type="text" data-field="taxonomy" value="{{ $lp->taxonomy }}" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-3 py-2 text-slate-800 text-sm font-medium transition-all outline-none" placeholder="e.g. Understand">
                      </td>

                      <!-- Proposed Date -->
                      <td class="p-2.5">
                        <input type="date" data-field="proposed_date" value="{{ $lp->proposed_date }}" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-slate-800 text-sm font-mono transition-all outline-none">
                      </td>

                      <!-- Actual Date -->
                      <td class="p-2.5">
                        <input type="date" data-field="actual_date" value="{{ $lp->actual_date }}" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-slate-800 text-sm font-mono transition-all outline-none">
                      </td>

                      <!-- Hours -->
                      <td class="p-2.5 text-center">
                        <input type="number" data-field="allocated_hours" value="{{ $lp->allocated_hours ?: 1 }}" min="1" max="10" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2 py-2 text-slate-900 text-sm font-bold text-center transition-all outline-none">
                      </td>

                      <!-- Status -->
                      <td class="p-2.5">
                        <select data-field="status" onchange="updatePlannerRowStatusColor(this)" class="w-full border focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-sm font-bold transition-all outline-none cursor-pointer {{ $isCompleted ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50/50 text-amber-700 border-amber-200' }}">
                          <option value="Pending" {{ $lp->status == 'Pending' ? 'selected' : '' }} class="text-amber-700 font-bold bg-white">Pending</option>
                          <option value="Completed" {{ $lp->status == 'Completed' ? 'selected' : '' }} class="text-emerald-700 font-bold bg-white">Completed</option>
                        </select>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="9" class="p-0">
                        <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                          <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4 border border-indigo-100 shadow-2xs">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                          </div>
                          <h4 class="text-base font-bold text-slate-900 mb-1">Academic Lesson Planner Ready</h4>
                          <p class="text-xs text-slate-500 max-w-sm mb-6 leading-relaxed">No lesson-plan sessions have been registered for this course yet. You can auto-generate a structured timeline from syllabus Course Outcomes or load a saved template.</p>
                          <div class="flex items-center gap-3 flex-wrap justify-center">
                            <button type="button" onclick="regenerateTheoryLessonPlan()" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center gap-2 cursor-pointer">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                              <span>Generate Lesson Plan</span>
                            </button>
                            <button type="button" onclick="loadTheoryTemplate()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 shadow-2xs transition-all flex items-center gap-2 cursor-pointer">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                              <span>Load Template</span>
                            </button>
                          </div>
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- TAB: CONTINUOUS INTERNAL ASSESSMENT -->
        <div id="tab-cia" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 hidden">
          
          <!-- SUB-VIEW 1: THREE CARDS VIEW (DEFAULT) -->
          <div id="cia-cards-view" class="space-y-4">
            <div class="flex justify-between items-center border-b border-slate-200 pb-3">
              <div>
                <h3 class="text-base font-bold text-title flex items-center gap-2">
                  <span class="material-symbols-rounded text-violet-400">fact_check</span>
                  Continuous Internal Assessment (CIA)
                </h3>
                <p class="text-xs text-muted mt-1">Select an assessment category to manage details individually or view the consolidated marksheet.</p>
              </div>
              <button onclick="toggleCiaView('consolidated')" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-medium transition-all cursor-pointer shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-rounded text-xs">assessment</span>
                View Consolidated Marksheet
              </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <!-- Attendance Card -->
              <div class="bg-panel border border-card rounded-xl p-4 space-y-2">
                <div class="flex justify-between items-center border-b border-card pb-1.5">
                  <span class="font-medium text-title text-xs">Attendance</span>
                  <span class="text-xs bg-indigo-50 text-indigo-700 border border-indigo-200 px-1.5 py-0.5 rounded font-bold">5M Max</span>
                </div>
                <p class="text-xs text-muted leading-relaxed">Automatically evaluated based on student class logs attendance metrics.</p>
                <button class="w-full py-1.5 bg-blue-600 hover:bg-blue-700 text-white shadow-xs rounded-lg text-xs font-bold border border-slate-200 transition-all cursor-pointer">
                  View Logs
                </button>
              </div>

              <!-- Self Learning Card -->
              <div class="bg-panel border border-card rounded-xl p-4 space-y-2">
                <div class="flex justify-between items-center border-b border-card pb-1.5">
                  <span class="font-medium text-title text-xs">Self-Learning</span>
                  <span class="text-xs bg-emerald-500/10 text-emerald-450 border border-emerald-500/20 px-1.5 py-0.5 rounded font-bold">15M Max</span>
                </div>
                <p class="text-xs text-muted leading-relaxed">Average of self-learning modules, quizzes, and micro-tasks across modules.</p>
                <button onclick="toggleCiaView('self-learning')" class="w-full py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-sm">
                  Assignments
                </button>
              </div>

              <!-- Series Exams Card -->
              <div class="bg-panel border border-card rounded-xl p-4 space-y-2">
                <div class="flex justify-between items-center border-b border-card pb-1.5">
                  <span class="font-medium text-title text-xs">Series Exams</span>
                  <span class="text-xs bg-violet-50 text-violet-700 border border-violet-200 px-1.5 py-0.5 rounded font-bold">20M Max</span>
                </div>
                <p class="text-xs text-muted leading-relaxed">Two written examinations covering all defined course outcomes (COs).</p>
                <button onclick="switchTab('series')" class="w-full py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-sm">
                  Manage Exams
                </button>
              </div>
            </div>
          </div>

          <!-- SUB-VIEW 3: CO-WISE SELF-LEARNING ACTIVITIES MARKSHEET (HIDDEN BY DEFAULT) -->
          <div id="cia-self-learning-view" class="space-y-4 hidden">
            <div class="flex justify-between items-center border-b border-slate-200 pb-3">
              <div>
                <h3 class="text-base font-bold text-title flex items-center gap-2">
                  <span class="material-symbols-rounded text-emerald-450">local_library</span>
                  Self-Learning Activities Marksheet (CO-wise)
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed max-w-3xl">
                  Assign self-learning marks (Max 15 per CO) for each Course Outcome.<br>
                  The average of all 4 CO marks will automatically determine the final Self-Learning Marks (out of 15 max) in the consolidated marksheet.
                </p>
              </div>
              <div class="flex items-center gap-2">
                <button onclick="toggleCiaView('cards')" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-xs rounded-lg text-xs font-medium transition-all border border-slate-200 cursor-pointer flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">arrow_back</span>
                  Back to Categories
                </button>
                <a href="/r26/classroom/self-learning/print/{{ $batchSubject->id }}" target="_blank" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-medium transition-all cursor-pointer shadow-sm flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">print</span>
                  Print Report
                </a>
                <button id="btnSaveSelfLearning" onclick="saveSelfLearningMarks()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-medium transition-all cursor-pointer shadow-sm">
                  Save Self-Learning
                </button>
              </div>
            </div>

            <!-- Self-Learning Navigation Sub-tabs -->
            <div class="flex gap-2 border-b border-card pb-2">
              <button type="button" onclick="switchSelfLearningTab('CO1')" id="tabbtn-sl-CO1" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-emerald-500/10 text-emerald-450 border border-emerald-500/20">CO1 Self-Study</button>
              <button type="button" onclick="switchSelfLearningTab('CO2')" id="tabbtn-sl-CO2" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-muted hover:bg-slate-100">CO2 Self-Study</button>
              <button type="button" onclick="switchSelfLearningTab('CO3')" id="tabbtn-sl-CO3" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-muted hover:bg-slate-100">CO3 Self-Study</button>
              <button type="button" onclick="switchSelfLearningTab('CO4')" id="tabbtn-sl-CO4" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-muted hover:bg-slate-100">CO4 Self-Study</button>
              <button type="button" onclick="switchSelfLearningTab('Summary')" id="tabbtn-sl-Summary" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-muted hover:bg-slate-100">Summary Sheet</button>
            </div>

            <!-- Max Marks Configuration Panels -->
            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
              <div id="sl-config-{{ $coTag }}" class="sl-config-panel bg-slate-50/70 border border-slate-200 rounded-xl p-4 space-y-4">
                <!-- Header Row -->
                <div class="flex justify-between items-center border-b border-slate-200 pb-3 flex-wrap gap-2">
                  <div class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-indigo-600 text-base">settings</span>
                    <h5 class="font-bold text-title text-xs uppercase tracking-wider">{{ $coTag }} Marks Allocation Setup (Total: 15 Marks)</h5>
                  </div>
                  <div class="flex items-center gap-3">
                    <span id="cfg-{{ $coTag }}-status" class="font-bold text-emerald-500 text-xs"></span>
                    <button type="button" onclick="openAssignmentModal('{{ $coTag }}')" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-sm flex items-center gap-1">
                      <span class="material-symbols-rounded text-xs">assignment</span>
                      Manage Assignments
                    </button>
                  </div>
                </div>

                <!-- Grid of 5 Activities -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3.5">
                  <!-- Activity 1 -->
                  <div class="bg-white border border-slate-200 rounded-xl p-3 space-y-2">
                    <label class="block text-xs font-bold text-slate-400">Activity 1 (Assignment)</label>
                    <div class="flex items-center gap-2">
                      <input type="number" step="0.5" id="cfg-{{ $coTag }}-assignment" value="{{ $selfLearningConfigs[$coTag]['assignment'] ?? 5.0 }}" class="w-full bg-white border border-slate-200 rounded-lg py-1 px-2.5 font-bold text-slate-800 text-xs text-center focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                      <span class="text-xs text-slate-400">M</span>
                    </div>
                  </div>

                  <!-- Activity 2 -->
                  <div class="bg-white border border-slate-200 rounded-xl p-3 space-y-2">
                    <label class="block text-xs font-bold text-slate-400">Activity 2 (MCQ Test)</label>
                    <div class="flex items-center gap-2">
                      <input type="number" step="0.5" id="cfg-{{ $coTag }}-mcq" value="{{ $selfLearningConfigs[$coTag]['mcq'] ?? 5.0 }}" class="w-full bg-white border border-slate-200 rounded-lg py-1 px-2.5 font-bold text-slate-800 text-xs text-center focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                      <span class="text-xs text-slate-400">M</span>
                    </div>
                  </div>

                  <!-- Activity 3 -->
                  <div class="bg-white border border-slate-200 rounded-xl p-3 space-y-2">
                    <label class="block text-xs font-bold text-slate-400">Activity 3 (Type & Marks)</label>
                    <div class="flex flex-col gap-2">
                      <select id="cfg-{{ $coTag }}-act3_mode" class="w-full bg-white border border-slate-200 rounded-lg py-1 px-2 text-slate-800 text-xs font-semibold focus:border-indigo-500 outline-none">
                        <option value="Case Study" {{ ($selfLearningConfigs[$coTag]['act3_mode'] ?? '') == 'Case Study' ? 'selected' : '' }}>Case Study</option>
                        <option value="Activity" {{ ($selfLearningConfigs[$coTag]['act3_mode'] ?? '') == 'Activity' ? 'selected' : '' }}>Activity/Seminar</option>
                        <option value="Minor Project" {{ ($selfLearningConfigs[$coTag]['act3_mode'] ?? '') == 'Minor Project' ? 'selected' : '' }}>Minor Project</option>
                        <option value="Exercises" {{ ($selfLearningConfigs[$coTag]['act3_mode'] ?? '') == 'Exercises' ? 'selected' : '' }}>Exercises</option>
                      </select>
                      <div class="flex items-center gap-2">
                        <input type="number" step="0.5" id="cfg-{{ $coTag }}-act3" value="{{ $selfLearningConfigs[$coTag]['act3'] ?? 5.0 }}" class="w-full bg-white border border-slate-200 rounded-lg py-1 px-2.5 font-bold text-slate-800 text-xs text-center focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                        <span class="text-xs text-slate-400">M</span>
                      </div>
                    </div>
                  </div>

                  <!-- Activity 4 -->
                  <div class="bg-white border border-slate-200 rounded-xl p-3 space-y-2">
                    <label class="block text-xs font-bold text-slate-400">Activity 4 (Type & Marks)</label>
                    <div class="flex flex-col gap-2">
                      <select id="cfg-{{ $coTag }}-act4_mode" class="w-full bg-white border border-slate-200 rounded-lg py-1 px-2 text-slate-800 text-xs font-semibold focus:border-indigo-500 outline-none">
                        <option value="Case Study" {{ ($selfLearningConfigs[$coTag]['act4_mode'] ?? '') == 'Case Study' ? 'selected' : '' }}>Case Study</option>
                        <option value="Activity" {{ ($selfLearningConfigs[$coTag]['act4_mode'] ?? '') == 'Activity' ? 'selected' : '' }}>Activity/Seminar</option>
                        <option value="Minor Project" {{ ($selfLearningConfigs[$coTag]['act4_mode'] ?? '') == 'Minor Project' ? 'selected' : '' }}>Minor Project</option>
                        <option value="Exercises" {{ ($selfLearningConfigs[$coTag]['act4_mode'] ?? '') == 'Exercises' ? 'selected' : '' }}>Exercises</option>
                      </select>
                      <div class="flex items-center gap-2">
                        <input type="number" step="0.5" id="cfg-{{ $coTag }}-act4" value="{{ $selfLearningConfigs[$coTag]['act4'] ?? 0.0 }}" class="w-full bg-white border border-slate-200 rounded-lg py-1 px-2.5 font-bold text-slate-800 text-xs text-center focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                        <span class="text-xs text-slate-400">M</span>
                      </div>
                    </div>
                  </div>

                  <!-- Activity 5 -->
                  <div class="bg-white border border-slate-200 rounded-xl p-3 space-y-2">
                    <label class="block text-xs font-bold text-slate-400">Activity 5 (Type & Marks)</label>
                    <div class="flex flex-col gap-2">
                      <select id="cfg-{{ $coTag }}-act5_mode" class="w-full bg-white border border-slate-200 rounded-lg py-1 px-2 text-slate-800 text-xs font-semibold focus:border-indigo-500 outline-none">
                        <option value="Case Study" {{ ($selfLearningConfigs[$coTag]['act5_mode'] ?? '') == 'Case Study' ? 'selected' : '' }}>Case Study</option>
                        <option value="Activity" {{ ($selfLearningConfigs[$coTag]['act5_mode'] ?? '') == 'Activity' ? 'selected' : '' }}>Activity/Seminar</option>
                        <option value="Minor Project" {{ ($selfLearningConfigs[$coTag]['act5_mode'] ?? '') == 'Minor Project' ? 'selected' : '' }}>Minor Project</option>
                        <option value="Exercises" {{ ($selfLearningConfigs[$coTag]['act5_mode'] ?? '') == 'Exercises' ? 'selected' : '' }}>Exercises</option>
                      </select>
                      <div class="flex items-center gap-2">
                        <input type="number" step="0.5" id="cfg-{{ $coTag }}-act5" value="{{ $selfLearningConfigs[$coTag]['act5'] ?? 0.0 }}" class="w-full bg-white border border-slate-200 rounded-lg py-1 px-2.5 font-bold text-slate-800 text-xs text-center focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                        <span class="text-xs text-slate-400">M</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach

            <!-- CO-wise Entry Sheets -->
            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
              <div id="sl-table-container-{{ $coTag }}" class="sl-table-container border border-card rounded-xl overflow-x-auto bg-white custom-scrollbar hidden">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                  <thead>
                    <tr class="bg-slate-50/70 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                      <th class="p-3 w-[6%] text-center">Roll No</th>
                      <th class="p-3 w-[12%]">SBTE Reg No</th>
                      <th class="p-3 w-[22%]">Student Name</th>
                      <th class="p-3 w-[12%] text-center">Assignment</th>
                      <th class="p-3 w-[12%] text-center">MCQ Test</th>
                      <th class="p-3 w-[12%] text-center"><span class="cfg-label-act3-{{ $coTag }}">Act 3</span></th>
                      <th class="p-3 w-[12%] text-center"><span class="cfg-label-act4-{{ $coTag }}">Act 4</span></th>
                      <th class="p-3 w-[12%] text-center"><span class="cfg-label-act5-{{ $coTag }}">Act 5</span></th>
                      <th class="p-3 w-[8%] text-center">Total (15M)</th>
                    </tr>
                  </thead>
                  <tbody id="selfLearningTableBody-{{ $coTag }}" class="divide-y divide-card text-sm font-normal">
                    @forelse($studentCiaData as $sc)
                      <tr data-reg-no="{{ $sc['reg_no'] }}" class="bg-card-hover transition-all font-normal">
                        <td class="p-2.5 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                        <td class="p-2.5 font-mono text-title font-bold">{{ $sc['sbte_reg_no'] ?: $sc['reg_no'] }}</td>
                        <td class="p-2.5 text-title font-medium">{{ $sc['name'] }}</td>
                        
                        <td class="p-2.5 text-center relative">
                          @if(($sc['co_details'][$coTag]['submission_status'] ?? '') === 'Submitted')
                            <div class="absolute top-1 right-2 flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500" title="Assignment Submitted - Grade Now"></span>
                            </div>
                          @endif
                          <input type="number" step="0.5" min="0" data-field="assignment" value="{{ $sc['co_details'][$coTag]['assignment'] ?? 0.0 }}" class="w-20 bg-white border {{ ($sc['co_details'][$coTag]['submission_status'] ?? '') === 'Submitted' ? 'border-amber-500 ring-2 ring-amber-400' : 'border-slate-200' }} rounded px-2 py-0.5 text-slate-800 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center">
                          <input type="number" step="0.5" min="0" data-field="mcq" value="{{ $sc['co_details'][$coTag]['mcq'] ?? 0.0 }}" class="w-20 bg-white border border-slate-200 rounded px-2 py-0.5 text-slate-800 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center">
                          <input type="number" step="0.5" min="0" data-field="act3" value="{{ $sc['co_details'][$coTag]['act3'] ?? 0.0 }}" class="w-20 bg-white border border-slate-200 rounded px-2 py-0.5 text-slate-800 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center">
                          <input type="number" step="0.5" min="0" data-field="act4" value="{{ $sc['co_details'][$coTag]['act4'] ?? 0.0 }}" class="w-20 bg-white border border-slate-200 rounded px-2 py-0.5 text-slate-800 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center">
                          <input type="number" step="0.5" min="0" data-field="act5" value="{{ $sc['co_details'][$coTag]['act5'] ?? 0.0 }}" class="w-20 bg-white border border-slate-200 rounded px-2 py-0.5 text-slate-800 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center font-mono text-emerald-600 font-bold text-base" data-field="co_total">
                          {{ $sc['co_details'][$coTag]['total'] ?? 0.0 }}
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="9" class="p-6 text-center text-muted italic font-normal">No student records enrolled.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            @endforeach

            <!-- Summary Sheet View -->
            <div id="sl-table-container-Summary" class="sl-table-container border border-card rounded-xl overflow-x-auto bg-white custom-scrollbar hidden">
              <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                  <tr class="bg-slate-50/70 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                    <th class="p-3 w-[6%] text-center">Roll No</th>
                    <th class="p-3 w-[12%]">SBTE Reg No</th>
                    <th class="p-3 w-[26%]">Student Name</th>
                    <th class="p-3 w-[12%] text-center">CO1 (15M)</th>
                    <th class="p-3 w-[12%] text-center">CO2 (15M)</th>
                    <th class="p-3 w-[12%] text-center">CO3 (15M)</th>
                    <th class="p-3 w-[12%] text-center">CO4 (15M)</th>
                    <th class="p-3 w-[10%] text-center">Average (15M)</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-card text-sm font-normal">
                  @forelse($studentCiaData as $sc)
                    <tr class="bg-card-hover transition-all font-normal">
                      <td class="p-2.5 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                      <td class="p-2.5 font-mono text-title font-bold">{{ $sc['sbte_reg_no'] ?: $sc['reg_no'] }}</td>
                      <td class="p-2.5 text-title font-medium">{{ $sc['name'] }}</td>
                      <td class="p-2.5 text-center font-mono text-title" id="summary-{{ $sc['reg_no'] }}-CO1">{{ $sc['co_details']['CO1']['total'] ?? 0.0 }}</td>
                      <td class="p-2.5 text-center font-mono text-title" id="summary-{{ $sc['reg_no'] }}-CO2">{{ $sc['co_details']['CO2']['total'] ?? 0.0 }}</td>
                      <td class="p-2.5 text-center font-mono text-title" id="summary-{{ $sc['reg_no'] }}-CO3">{{ $sc['co_details']['CO3']['total'] ?? 0.0 }}</td>
                      <td class="p-2.5 text-center font-mono text-title" id="summary-{{ $sc['reg_no'] }}-CO4">{{ $sc['co_details']['CO4']['total'] ?? 0.0 }}</td>
                      <td class="p-2.5 text-center font-mono text-emerald-450 font-bold text-base" id="summary-{{ $sc['reg_no'] }}-avg">{{ $sc['self_learning_marks'] }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="p-6 text-center text-muted italic font-normal">No student records enrolled.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- SUB-VIEW 2: CONSOLIDATED MARKSHEET (HIDDEN BY DEFAULT) -->
          <div id="cia-consolidated-view" class="space-y-4 hidden">
            <div class="flex justify-between items-center border-b border-slate-200 pb-3">
              <div>
                <h3 class="text-base font-bold text-title flex items-center gap-2">
                  <span class="material-symbols-rounded text-violet-400">table_chart</span>
                  Consolidated CIA Marks Sheet
                </h3>
                <p class="text-xs text-muted mt-1">
                  Attendance is fetched from class logs. Marks are mapped out of 5 based on Table 2.1 (90%+ = 5M, 80%-90% = 4M, 75%-80% = 3M, 70%-75% = 2M, 65%-70% = 1M, &lt;65% = 0M).
                </p>
              </div>
              <div class="flex items-center gap-2">
                <button onclick="toggleCiaView('cards')" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-xs rounded-lg text-xs font-medium transition-all border border-slate-200 cursor-pointer flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">arrow_back</span>
                  Back to Categories
                </button>
                <button id="btnSaveCia" onclick="saveCiaMarks()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-medium transition-all cursor-pointer shadow-sm">
                  Save CIA Marks
                </button>
              </div>
            </div>

            <div class="border border-card rounded-xl overflow-x-auto bg-white custom-scrollbar">
              <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                  <tr class="bg-slate-50/70 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                    <th class="p-3 w-[6%] text-center">Roll No</th>
                    <th class="p-3 w-[12%]">SBTE Reg No</th>
                    <th class="p-3 w-[20%]">Student Name</th>
                    <th class="p-3 w-[10%] text-center">Attendance %</th>
                    <th class="p-3 w-[10%] text-center">Attendance Marks (5M)</th>
                    <th class="p-3 w-[15%] text-center">Eligibility / Status</th>
                    <th class="p-3 w-[12%] text-center">Self Learning (15M)</th>
                    <th class="p-3 w-[12%] text-center">Series Exams (20M)</th>
                    <th class="p-3 w-[10%] text-center">Total CIA (40M)</th>
                  </tr>
                </thead>
                <tbody id="ciaTableBody" class="divide-y divide-card text-sm font-normal">
                  @forelse($studentCiaData as $sc)
                    <tr data-reg-no="{{ $sc['reg_no'] }}" class="bg-card-hover transition-all font-normal">
                      <td class="p-2.5 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                      <td class="p-2.5 font-mono text-title font-bold">{{ $sc['sbte_reg_no'] ?: $sc['reg_no'] }}</td>
                      <td class="p-2.5 text-title font-medium">{{ $sc['name'] }}</td>
                      <td class="p-2.5 text-center font-mono text-title">{{ $sc['attendance_percent'] }}%</td>
                      <td class="p-2.5 text-center font-mono text-emerald-500 font-bold" data-val-attendance="{{ $sc['attendance_marks'] }}">
                        {{ $sc['attendance_marks'] }}
                      </td>
                      <td class="p-2.5 text-center">
                        <span class="px-2 py-0.5 rounded text-xs font-bold" style="color: {{ $sc['attendance_color'] === 'emerald-450' ? '#10b981' : ($sc['attendance_color'] === 'amber-500' ? '#f59e0b' : ($sc['attendance_color'] === 'purple-400' ? '#c084fc' : '#f43f5e')) }}; background-color: {{ $sc['attendance_color'] === 'emerald-450' ? 'rgba(16,185,129,0.1)' : ($sc['attendance_color'] === 'amber-500' ? 'rgba(245,158,11,0.1)' : ($sc['attendance_color'] === 'purple-400' ? 'rgba(192,132,252,0.1)' : 'rgba(244,63,94,0.1)')) }}; border: 1px solid currentColor;">
                          {{ $sc['attendance_status'] }}
                        </span>
                      </td>
                      <td class="p-2.5 text-center">
                        <input type="number" step="0.5" min="0" max="15" data-field="self_learning" value="{{ $sc['self_learning_marks'] }}" class="w-20 bg-white border border-slate-200 rounded px-2 py-1 text-slate-800 text-center focus:border-indigo-500 outline-none font-normal" oninput="calculateRowCia(this)">
                      </td>
                      <td class="p-2.5 text-center">
                        <input type="number" step="0.5" min="0" max="20" data-field="series_exam" value="{{ $sc['series_exam_marks'] }}" class="w-20 bg-white border border-slate-200 rounded px-2 py-1 text-slate-800 text-center focus:border-indigo-500 outline-none font-normal" oninput="calculateRowCia(this)">
                      </td>
                      <td class="p-2.5 text-center font-mono text-indigo-600 font-bold text-base" data-field="total_cia">
                        {{ $sc['total_cia'] }}
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="9" class="p-6 text-center text-muted italic font-normal">No student records enrolled.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- TAB: STUDENT ROSTER -->
        <div id="tab-roster" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 hidden">
          <div class="border-b border-slate-200 pb-3">
            <h3 class="text-base font-bold text-title flex items-center gap-2">
              <span class="material-symbols-rounded text-sky-400">group</span>
              Student Enrollment Directory
            </h3>
          </div>

          <div class="border border-card rounded-xl overflow-hidden bg-white">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50/70 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                  <th class="p-3">Roll No</th>
                  <th class="p-3">SBTE Reg No</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3 text-right">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-card text-xs">
                @forelse($students as $student)
                  <tr class="bg-card-hover transition-all">
                    <td class="p-3 font-mono font-bold text-muted">{{ $student->roll_no ?? '-' }}</td>
                    <td class="p-3 font-mono font-bold text-title">{{ $student->sbte_reg_no ?: $student->reg_no }}</td>
                    <td class="p-3 font-bold text-title">{{ $student->name }}</td>
                    <td class="p-3 text-right">
                      <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-550 border border-emerald-500/20 rounded-md text-xs font-bold select-none">{{ $student->academic_status }}</span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="p-6 text-center text-muted italic">No students assigned to this classroom yet.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- SERIES EXAMS TAB PANEL -->
        <div id="tab-series" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 hidden">
          <div class="border-b border-slate-200 pb-3 flex justify-between items-center">
            <h3 class="text-base font-bold text-title flex items-center gap-2">
              <span class="material-symbols-rounded text-sky-400">quiz</span>
              Series Examinations (Theory)
            </h3>
            @if(!$seriesExams->isEmpty())
              <button onclick="resetSeriesExamsConfig()" class="px-2.5 py-1 bg-rose-600/10 hover:bg-rose-600/20 text-rose-450 border border-rose-500/20 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                <span class="material-symbols-rounded text-xs">restart_alt</span> Reconfigure Pattern
              </button>
            @endif
          </div>

          @if($seriesExams->isEmpty())
            <!-- Unconfigured Pattern State -->
            <div class="bg-slate-50/50 border border-card rounded-xl p-6 text-center space-y-4 max-w-2xl mx-auto my-8">
              <span class="material-symbols-rounded text-4xl text-sky-450">tune</span>
              <h4 class="font-bold text-title text-sm">Configure Series Examination Pattern</h4>
              <p class="text-xs text-muted leading-relaxed">
                Please select the examination pattern according to the syllabus requirements. You can conduct 4 independent single-CO tests (25 marks each) or 2 combined-CO tests (50 marks each).
              </p>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <label class="border border-card hover:border-sky-500/30 rounded-xl p-4 cursor-pointer block text-left bg-white space-y-2">
                  <input type="radio" name="series-mode-select" value="single_co" checked class="text-sky-500 focus:ring-sky-500">
                  <span class="font-bold text-title text-xs block">4 Single-CO Tests (25M each)</span>
                  <span class="text-[11px] text-muted block leading-snug">
                    Conduct one separate exam for each CO (CO1 to CO4). Exam duration is 1 hour. Total marks scaled to 20.
                  </span>
                </label>
                
                <label class="border border-card hover:border-sky-500/30 rounded-xl p-4 cursor-pointer block text-left bg-white space-y-2">
                  <input type="radio" name="series-mode-select" value="combined_co" class="text-sky-500 focus:ring-sky-500">
                  <span class="font-bold text-title text-xs block">2 Combined-CO Tests (50M each)</span>
                  <span class="text-[11px] text-muted block leading-snug">
                    Conduct two series exams combining two COs (CO1+CO2 & CO3+CO4). Exam duration is 2 hours. Total marks scaled to 20.
                  </span>
                </label>
              </div>

              <button onclick="initializeSeriesPattern()" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-md inline-flex items-center gap-1.5">
                <span class="material-symbols-rounded text-sm">settings_suggest</span>
                Initialize Pattern Configuration
              </button>
            </div>
          @else
            <!-- Configured Exams State -->
            <div class="space-y-6">
              
              <!-- QP and Schemes Panel -->
              <div class="space-y-3">
                <h4 class="font-bold text-title text-xs uppercase tracking-wider">Scheduled Series Examinations</h4>
                <div class="grid grid-cols-1 gap-3.5">
                  @foreach($seriesExams as $exam)
                    @php
                      $firstCo = $exam->co_tags[0] ?? 'CO1';
                      $borderColor = 'border-l-sky-500';
                      $bgColor = 'bg-sky-500/10';
                      $textColor = 'text-sky-600 dark:text-sky-400';
                      if ($firstCo === 'CO2') {
                        $borderColor = 'border-l-emerald-500';
                        $bgColor = 'bg-emerald-500/10';
                        $textColor = 'text-emerald-600 dark:text-emerald-600';
                      } elseif ($firstCo === 'CO3') {
                        $borderColor = 'border-l-indigo-500';
                        $bgColor = 'bg-indigo-500/10';
                        $textColor = 'text-indigo-600 dark:text-indigo-600';
                      } elseif ($firstCo === 'CO4') {
                        $borderColor = 'border-l-purple-500';
                        $bgColor = 'bg-purple-500/10';
                        $textColor = 'text-purple-600 dark:text-violet-600';
                      }
                    @endphp
                    <div class="bg-white dark:bg-slate-50/70 border border-slate-200 dark:border-slate-200 border-l-4 {{ $borderColor }} rounded-xl p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4 shadow-sm">
                      
                      <!-- Left: Exam Info -->
                      <div class="flex items-center gap-3">
                        <div class="px-3 py-1 rounded-lg {{ $bgColor }} {{ $textColor }} font-bold text-xs tracking-wider uppercase">
                          {{ implode(' + ', $exam->co_tags) }}
                        </div>
                        <div>
                          <h5 class="font-bold text-slate-800 dark:text-slate-800 text-sm">{{ $exam->exam_name }}</h5>
                          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Marks: <strong class="text-slate-700 dark:text-slate-600 font-bold">{{ $exam->max_marks }} Marks</strong> | Duration: <strong class="text-slate-700 dark:text-slate-600 font-bold">{{ $exam->duration_minutes }} min</strong>
                          </p>
                        </div>
                      </div>

                      <!-- Right: Status and Actions -->
                      <div class="flex flex-wrap items-center gap-3">
                        @if($exam->locked)
                          <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-600 border border-emerald-500/20 rounded-lg text-xs font-bold flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-rounded text-xs">lock</span> Locked & Published
                          </span>
                        @else
                          <span class="px-2.5 py-1 bg-amber-500/10 text-amber-600 dark:text-amber-600 border border-amber-500/20 rounded-lg text-xs font-bold flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-rounded text-xs">edit_note</span> Drafting Mode
                          </span>
                        @endif

                        <div class="flex gap-2">
                          <button onclick='openSeriesBuilderModal({{ $exam->id }}, "{{ addslashes($exam->exam_name) }}", "{{ $exam->mode }}", {{ json_encode($exam->co_tags) }}, {{ $exam->max_marks }})' class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-rounded text-xs">edit_document</span> Build QP
                          </button>
                          <a href="/r26/classroom/series-exams/{{ $exam->id }}/print-qp" target="_blank" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-200 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-800 border border-slate-300 dark:border-slate-200 rounded-lg text-xs font-bold transition-all flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-rounded text-xs">print</span> Print QP
                          </a>
                          <a href="/r26/classroom/series-exams/{{ $exam->id }}/print-scheme" target="_blank" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-200 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-800 border border-slate-300 dark:border-slate-200 rounded-lg text-xs font-bold transition-all flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-rounded text-xs">description</span> Print Scheme
                          </a>
                          @if(!$exam->locked)
                            <button onclick="lockAndPublishSeries({{ $exam->id }})" class="px-3 py-1.5 bg-violet-600 hover:bg-violet-750 text-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                              <span class="material-symbols-rounded text-xs">publish</span> Lock & Notify
                            </button>
                          @endif
                        </div>
                      </div>

                    </div>
                  @endforeach
                </div>
              </div>

              <!-- Marks Entry Panel -->
              <div class="space-y-3">
                <div class="flex justify-between items-center">
                  <h4 class="font-bold text-title text-xs uppercase tracking-wider">Series Exam detailed marksheet</h4>
                  <div class="flex items-center gap-2">
                    <a href="/r26/classroom/{{ $batchSubject->id }}/series-exams/print-marks" target="_blank" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-xs border border-slate-200 rounded-lg text-xs font-bold transition-all flex items-center gap-1 shadow-sm">
                      <span class="material-symbols-rounded text-xs">print</span> Print Marks Report
                    </a>
                    <button id="btnSaveSeriesMarks" onclick="saveSeriesExamMarks()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-md flex items-center gap-1">
                      <span class="material-symbols-rounded text-xs font-bold">save</span> Save Series Marks
                    </button>
                  </div>
                </div>

                <div class="border border-card rounded-xl overflow-x-auto bg-white custom-scrollbar">
                  <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                      <tr class="bg-slate-50/70 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                        <th class="p-3 w-[6%] text-center">Roll No</th>
                        <th class="p-3 w-[15%]">Register No</th>
                        <th class="p-3">Student Name</th>
                        @foreach($seriesExams as $exam)
                          <th class="p-3 text-center w-[15%]">{{ $exam->exam_name }} ({{ $exam->max_marks }}M)</th>
                        @endforeach
                        <th class="p-3 text-center w-[12%]">Scaled Score (20M)</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-card text-xs" id="seriesMarksTableBody">
                      @foreach($studentCiaData as $sc)
                        <tr class="bg-card-hover transition-all" data-reg-no="{{ $sc['reg_no'] }}">
                          <td class="p-3 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                          <td class="p-3 font-mono text-title">{{ $sc['reg_no'] }}</td>
                          <td class="p-3 text-title font-bold">{{ $sc['name'] }}</td>
                          @foreach($seriesExams as $exam)
                            <td class="p-3 text-center">
                              <input type="number" step="0.5" min="0" max="{{ $exam->max_marks }}" 
                                     data-exam-id="{{ $exam->id }}" 
                                     value="{{ $sc['exam_marks'][$exam->id] ?? 0.0 }}" 
                                     class="w-20 bg-white border border-slate-200 rounded px-2 py-0.5 text-slate-800 text-center focus:border-indigo-500 outline-none font-normal text-xs series-mark-input"
                                     oninput="recalculateSeriesRow(this)">
                            </td>
                          @endforeach
                          <td class="p-3 text-center font-mono text-emerald-600 font-bold text-base" data-field="series-scaled-total">
                            {{ $sc['series_exam_marks'] }}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

              </div>

            </div>
          @endif
        </div>

        <!-- TAB: CONSOLIDATED INTERNAL MARKS (NEW) -->
        <div id="tab-internals" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 hidden">
          
          <!-- Sub-Tab Navigation Header -->
          <div class="flex border-b border-slate-200 pb-2 mb-4 gap-4">
            <button onclick="switchInternalsSubtab('cie_marks')" id="subbtn-cie_marks" class="text-sm font-bold text-emerald-600 border-b-2 border-emerald-500 pb-1 cursor-pointer transition-all">
              1. CIA Marks (40M)
            </button>
            <button onclick="switchInternalsSubtab('ese_results')" id="subbtn-ese_results" class="text-sm font-bold text-slate-400 hover:text-slate-800 pb-1 cursor-pointer transition-all">
              2. ESE Marks & Final Results (100M)
            </button>
            <button onclick="switchInternalsSubtab('nba_attainment')" id="subbtn-nba_attainment" class="text-sm font-bold text-slate-400 hover:text-slate-800 pb-1 cursor-pointer transition-all">
              3. NBA Attainment (Surveys & CO-PO)
            </button>
          </div>

          <!-- SUBTAB 1: CIE MARKS -->
          <div id="subtab-cie_marks" class="space-y-4">
            <div class="flex justify-between items-center">
              <div>
                <h4 class="font-bold text-title text-xs uppercase tracking-wider">CIA Consolidated Marksheet</h4>
                <p class="text-xs text-muted mt-0.5">Scale: Attendance (5M), Self Learning (15M), Series Exam (20M). Total out of 40M.</p>
              </div>
              <div class="flex items-center gap-2">
                <a href="/r26/classroom/{{ $batchSubject->id }}/series-exams/print-marks" target="_blank" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-xs border border-slate-200 rounded-lg text-xs font-bold transition-all flex items-center gap-1 shadow-sm">
                  <span class="material-symbols-rounded text-xs">print</span> Print Series Report
                </a>
                <a href="/r26/classroom/{{ $batchSubject->id }}/internals/print-cie" target="_blank" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                  <span class="material-symbols-rounded text-xs">print</span> Print CIA Marksheet
                </a>
              </div>
            </div>

            <div class="border border-card rounded-xl overflow-x-auto bg-white custom-scrollbar">
              <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                  <tr class="bg-slate-50/70 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                    <th class="p-3 w-[6%] text-center">Roll No</th>
                    <th class="p-3 w-[15%]">Register No</th>
                    <th class="p-3">Student Name</th>
                    <th class="p-3 w-[12%] text-center">Attendance %</th>
                    <th class="p-3 w-[12%] text-center">Attendance (5M)</th>
                    <th class="p-3 w-[15%] text-center">Self Learning / Assignment (15M)</th>
                    <th class="p-3 w-[15%] text-center">Series Exam (20M)</th>
                    <th class="p-3 w-[12%] text-center">Total CIA (40M)</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-card text-sm font-normal">
                  @forelse($studentCiaData as $sc)
                    <tr class="bg-card-hover transition-all font-normal">
                      <td class="p-2.5 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                      <td class="p-2.5 font-mono text-title">{{ $sc['reg_no'] }}</td>
                      <td class="p-2.5 text-title font-medium">{{ $sc['name'] }}</td>
                      <td class="p-2.5 text-center font-mono text-title">{{ $sc['attendance_percent'] }}%</td>
                      <td class="p-2.5 text-center font-mono text-emerald-500 font-bold">{{ $sc['attendance_marks'] }}</td>
                      <td class="p-2.5 text-center font-mono text-title">{{ $sc['self_learning_marks'] }}</td>
                      <td class="p-2.5 text-center font-mono text-title">{{ $sc['series_exam_marks'] }}</td>
                      <td class="p-2.5 text-center font-mono text-indigo-600 font-bold text-base">{{ $sc['total_cia'] }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="p-6 text-center text-muted italic font-normal">No student records enrolled.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- SUBTAB 2: ESE MARKS & FINAL RESULTS -->
          <div id="subtab-ese_results" class="space-y-4 hidden">
            <div class="flex justify-between items-center">
              <div>
                <h4 class="font-bold text-title text-xs uppercase tracking-wider">End Semester Exam (ESE) Marks entry & Grades</h4>
                <p class="text-xs text-muted mt-0.5">Enter ESE marks (out of 60) below to view consolidated final scores (CIA 40M + ESE 60M = 100M total).</p>
              </div>
              <div class="flex items-center gap-2">
                <a href="/r26/classroom/{{ $batchSubject->id }}/final-results/print" target="_blank" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                  <span class="material-symbols-rounded text-xs">print</span> Print Final Marksheet
                </a>
                <button onclick="saveEseMarks()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                  <span class="material-symbols-rounded text-xs font-bold">save</span> Save ESE Marks
                </button>
              </div>
            </div>

            <div class="border border-card rounded-xl overflow-x-auto bg-white custom-scrollbar">
              <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                  <tr class="bg-slate-50/70 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                    <th class="p-3 w-[6%] text-center">Roll No</th>
                    <th class="p-3 w-[15%]">Register No</th>
                    <th class="p-3">Student Name</th>
                    <th class="p-3 w-[12%] text-center">CIA Marks (40M)</th>
                    <th class="p-3 w-[15%] text-center">ESE Marks (60M)</th>
                    <th class="p-3 w-[12%] text-center">Total (100M)</th>
                    <th class="p-3 w-[12%] text-center">Grade</th>
                    <th class="p-3 w-[12%] text-center">Remark</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-card text-sm font-normal">
                  @forelse($studentCiaData as $sc)
                    <tr class="bg-card-hover transition-all font-normal student-ese-row" data-reg-no="{{ $sc['reg_no'] }}">
                      <td class="p-2.5 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                      <td class="p-2.5 font-mono text-title">{{ $sc['reg_no'] }}</td>
                      <td class="p-2.5 text-title font-medium">{{ $sc['name'] }}</td>
                      <td class="p-2.5 text-center font-mono text-emerald-500 font-bold" data-val-cie="{{ $sc['total_cia'] }}">{{ $sc['total_cia'] }}</td>
                      <td class="p-2.5 text-center">
                        <input type="number" step="0.5" min="0" max="60" value="{{ $sc['ese_marks'] ?? 0.0 }}" class="w-24 bg-white border border-slate-200 rounded px-2 py-0.5 text-slate-800 text-center focus:border-indigo-500 outline-none font-normal text-xs ese-mark-input" oninput="calculateEseRow(this)">
                      </td>
                      <td class="p-2.5 text-center font-mono text-title font-bold" data-field="total_score">{{ $sc['grand_total'] }}</td>
                      <td class="p-2.5 text-center font-bold" data-field="grade_display">-</td>
                      <td class="p-2.5 text-center font-bold" data-field="remark_display">-</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="p-6 text-center text-muted italic font-normal">No student records enrolled.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- SUBTAB 3: NBA ATTAINMENT -->
          <div id="subtab-nba_attainment" class="space-y-4 hidden">
             <!-- Surveys Control Panel -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 space-y-4 shadow-xs">
                <div class="flex justify-between items-start flex-wrap gap-4">
                  <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold border border-indigo-200/60 shadow-2xs">
                      <span class="material-symbols-rounded text-xl">forum</span>
                    </span>
                    <div>
                      <h4 class="font-bold text-slate-900 text-sm">Mid-Semester Online Survey</h4>
                      <p class="text-xs text-slate-500 mt-0.5">SAR Criterion 2 Formative Evaluation</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 flex-wrap">
                    <button id="btn-initiate-midsem" onclick="document.getElementById('modal-midsem-survey-init').classList.remove('hidden')" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all cursor-pointer shadow-xs">Open Survey</button>
                    <button id="btn-close-midsem" onclick="controlSurvey('midsem', 'close')" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-all cursor-pointer hidden shadow-xs">Close &amp; Lock</button>
                  </div>
                </div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                  <span class="text-xs text-slate-500 font-medium">Feedback on course pace &amp; delivery</span>
                  <span id="status-midsem" class="text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200 rounded-lg px-2.5 py-1 flex items-center">Checking status...</span>
                </div>
              </div>

              <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 space-y-4 shadow-xs">
                <div class="flex justify-between items-start flex-wrap gap-4">
                  <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold border border-teal-200/60 shadow-2xs">
                      <span class="material-symbols-rounded text-xl">assignment_turned_in</span>
                    </span>
                    <div>
                      <h4 class="font-bold text-slate-900 text-sm">Course Exit Survey (Indirect CO)</h4>
                      <p class="text-xs text-slate-500 mt-0.5">Indirect Attainment Assessment (20%)</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 flex-wrap">
                    <button id="btn-initiate-exit" onclick="document.getElementById('modal-exit-survey-init').classList.remove('hidden')" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all cursor-pointer shadow-xs">Open Survey</button>
                    <button id="btn-close-exit" onclick="controlSurvey('exit', 'close')" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-all cursor-pointer hidden shadow-xs">Close &amp; Lock</button>
                  </div>
                </div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                  <span class="text-xs text-slate-500 font-medium">Evaluates indirect CO attainment</span>
                  <span id="status-exit" class="text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200 rounded-lg px-2.5 py-1 flex items-center">Checking status...</span>
                </div>
              </div>
            </div>

            <!-- NBA Attainment Reports -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
              <div class="space-y-1">
                <h4 class="font-bold text-slate-900 text-sm flex items-center gap-1.5">
                  <span class="material-symbols-rounded text-blue-600 text-lg">equalizer</span>
                  <span>NBA 2026 Direct/Indirect CO-PO Attainment Matrix (11 POs)</span>
                </h4>
                <p class="text-xs text-slate-500">Calculated using 80% Direct Attainment (CIA &amp; ESE) + 20% Indirect Attainment (Course Exit Survey).</p>
              </div>
              <a href="/r26/classroom/{{ $batchSubject->id }}/nba/attainment-report" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs no-underline">
                <span class="material-symbols-rounded text-sm">print</span>
                <span>Print Final NBA Attainment Report</span>
              </a>
            </div>

          </div>

        </div>

        <!-- TAB: COURSE ATTAINMENT & SURVEYS (NEW) -->
        <div id="tab-attainment" class="tab-panel space-y-5 hidden">
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex justify-between items-center">
            <div>
              <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <span class="material-symbols-rounded text-blue-600">equalizer</span>
                <span>Course Attainment &amp; Surveys</span>
              </h3>
              <p class="text-xs text-slate-500 mt-1">
                Access surveys and generate PO/CO attainment calculations for Revision 2026.
              </p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Mid Sem Survey -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 space-y-4 shadow-xs">
              <div class="flex justify-between items-start flex-wrap gap-4">
                <div class="flex items-center gap-3">
                  <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold border border-indigo-200/60 shadow-2xs">
                    <span class="material-symbols-rounded text-xl">forum</span>
                  </span>
                  <div>
                    <h4 class="font-bold text-slate-900 text-sm">Mid-Semester Online Survey</h4>
                    <p class="text-xs text-slate-500 mt-0.5">SAR Criterion 2 Evaluation</p>
                  </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                  <button id="btn-initiate-midsem-tab" onclick="document.getElementById('modal-midsem-survey-init').classList.remove('hidden')" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all cursor-pointer shadow-xs">Open Survey</button>
                  <button id="btn-close-midsem-tab" onclick="controlSurvey('midsem', 'close')" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-all cursor-pointer hidden shadow-xs">Close &amp; Lock</button>
                </div>
              </div>
              <p class="text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                Captures direct feedback on course delivery, syllabus coverage, and early corrective action points.
              </p>
              <div class="flex items-center justify-between pt-1">
                <span class="text-xs text-slate-400">Current Survey Status</span>
                <span id="status-midsem-tab" class="text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200 rounded-lg px-2.5 py-1 flex items-center">Checking status...</span>
              </div>
            </div>

            <!-- Course Exit Survey -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 space-y-4 shadow-xs">
              <div class="flex justify-between items-start flex-wrap gap-4">
                <div class="flex items-center gap-3">
                  <span class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold border border-teal-200/60 shadow-2xs">
                    <span class="material-symbols-rounded text-xl">assignment_turned_in</span>
                  </span>
                  <div>
                    <h4 class="font-bold text-slate-900 text-sm">Course Exit Survey (Indirect CO)</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Indirect Attainment Assessment</p>
                  </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                  <button id="btn-initiate-exit-tab" onclick="document.getElementById('modal-exit-survey-init').classList.remove('hidden')" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all cursor-pointer shadow-xs">Open Survey</button>
                  <button id="btn-close-exit-tab" onclick="controlSurvey('exit', 'close')" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-all cursor-pointer hidden shadow-xs">Close &amp; Lock</button>
                </div>
              </div>
              <p class="text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                Evaluates indirect Course Outcome (CO) attainment parameters at semester-end for final PO mapping.
              </p>
              <div class="flex items-center justify-between pt-1">
                <span class="text-xs text-slate-400">Current Survey Status</span>
                <span id="status-exit-tab" class="text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200 rounded-lg px-2.5 py-1 flex items-center">Checking status...</span>
              </div>
            </div>
          </div>

          <!-- NBA Attainment Calculations -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="space-y-1">
              <h4 class="font-bold text-slate-900 text-sm flex items-center gap-1.5">
                <span class="material-symbols-rounded text-blue-600 text-lg">equalizer</span>
                <span>NBA 2026 Direct/Indirect CO-PO Attainment Calculation (11 POs)</span>
              </h4>
              <p class="text-xs text-slate-500">Calculated using 80% Direct Attainment (CIA &amp; ESE) + 20% Indirect Attainment (Course Exit Survey).</p>
            </div>
            <a href="/r26/classroom/{{ $batchSubject->id }}/nba/attainment-report" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs no-underline">
              <span class="material-symbols-rounded text-sm">print</span>
              <span>Print Final NBA Attainment Report</span>
            </a>
          </div>
        </div>

        <!-- MODAL: MID-SEM SURVEY INITIATION PREVIEW & EDIT -->
        <div id="modal-midsem-survey-init" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
          <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-4xl p-6 space-y-4 shadow-2xl max-h-[88vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
              <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <span class="material-symbols-rounded text-indigo-600">rate_review</span>
                <span>Preview &amp; Edit Mid-Semester Survey Questions</span>
              </h3>
              <button type="button" onclick="document.getElementById('modal-midsem-survey-init').classList.add('hidden')" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all cursor-pointer">
                &times;
              </button>
            </div>
            
            <p class="text-xs text-slate-500 leading-relaxed">
              Review or customize the survey questions below before activating. Students will submit responses matching these descriptions.
            </p>

            <form id="form-midsem-init" onsubmit="submitMidsemInit(event)" class="space-y-4">
              <div class="space-y-3.5">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q1. CO1 - Course Outcomes Communication</label>
                  <input type="text" id="ms-q5" value="The teacher clearly communicates the Course Outcomes (COs) and learning goals at the start of new topics." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q2. CO1 - Syllabus Delivery Pace</label>
                  <input type="text" id="ms-q6" value="The pace, speed, and coverage of the syllabus completed so far is appropriate." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q3. CO2 - Concept Clarity &amp; Application</label>
                  <input type="text" id="ms-q7" value="The teacher explains complex concepts clearly and links classroom theory to real-world industrial or field applications." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q4. CO2 - Effectiveness of ICT/PPT Tools</label>
                  <input type="text" id="ms-q8" value="The use of teaching tools, animations, PPTs, model demonstrations, or ICT tools is effective." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q5. CO3 - Doubt Clearing &amp; Interaction</label>
                  <input type="text" id="ms-q9" value="The teacher encourages student questions, manages classroom discussions well, and clears doubts patiently." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q6. CO3 - Test &amp; Assignment Relevance</label>
                  <input type="text" id="ms-q10" value="Internal assessment test questions and assignments match the topics taught in class." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q7. CO4 - Fairness in Evaluation</label>
                  <input type="text" id="ms-q11" value="Evaluation of mid-semester tests or submissions is fair, timely, and transparent." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q8. CO4 - Guidance for Slow Learners</label>
                  <input type="text" id="ms-q12" value="The teacher provides extra guidance, remedial tips, or support to slow learners." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
              </div>

              <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modal-midsem-survey-init').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors cursor-pointer">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-xs transition-colors cursor-pointer">Activate &amp; Publish Survey</button>
              </div>
            </form>
          </div>
        </div>

        <!-- MODAL: COURSE EXIT SURVEY INITIATION PREVIEW & EDIT -->
        <div id="modal-exit-survey-init" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
          <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-4xl p-6 space-y-4 shadow-2xl max-h-[88vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
              <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <span class="material-symbols-rounded text-teal-600">assignment_turned_in</span>
                <span>Preview &amp; Edit Course Exit Survey Questions</span>
              </h3>
              <button type="button" onclick="document.getElementById('modal-exit-survey-init').classList.add('hidden')" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all cursor-pointer">
                &times;
              </button>
            </div>
            
            <p class="text-xs text-slate-500 leading-relaxed">
              Review or customize the survey questions below before activating. Students will submit responses matching these descriptions.
            </p>

            <form id="form-exit-init" onsubmit="submitExitInit(event)" class="space-y-4">
              <div class="space-y-3.5">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q1. CO1 - Subject Knowledge</label>
                  <input type="text" id="ex-q1" value="How well did the course help you understand and remember the core academic principles, models, and structural fundamentals?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q2. CO1 - Outcome Mapping</label>
                  <input type="text" id="ex-q2" value="How clearly were the course objectives, scope, and basic terms aligned with the class presentations?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q3. CO2 - Analytical Ability</label>
                  <input type="text" id="ex-q3" value="How effectively did the course build your reasoning skills, mathematical derivations, or logical analysis capabilities?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q4. CO2 - Design &amp; Analysis</label>
                  <input type="text" id="ex-q4" value="To what extent can you design models, troubleshoot bugs, or draft structural layouts based on class lessons?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q5. CO3 - Practical Skills</label>
                  <input type="text" id="ex-q5" value="How confident are you in operating laboratory kits, executing computer programs, or handling workshop machines?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q6. CO3 - Industry Standards</label>
                  <input type="text" id="ex-q6" value="How clearly do you understand safety regulations, instrumentation limits, and standard protocols?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q7. CO4 - Evaluation Standards</label>
                  <input type="text" id="ex-q7" value="To what extent did assignments, written internal exams, and presentations evaluate your skills thoroughly?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q8. CO4 - Professional Ethics</label>
                  <input type="text" id="ex-q8" value="How effectively did the course emphasize engineering ethics, environmental issues, and professional conduct?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q9. CO4 - Lifelong Learning</label>
                  <input type="text" id="ex-q9" value="How strongly has this course inspired you to self-learn, explore external publications, or research modern field advancements?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Q10. Overall Course Rating</label>
                  <input type="text" id="ex-q10" value="Rate your overall satisfaction with the course syllabus delivery, faculty guidance, and academic outcomes." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
                </div>
              </div>

              <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modal-exit-survey-init').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors cursor-pointer">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-xs shadow-xs transition-colors cursor-pointer">Activate &amp; Publish Survey</button>
              </div>
            </form>
          </div>
        </div>

        @include('partials.virtual_learning_hub_tab', ['roomType' => 'Theory'])

      </div>

    </div>

    </div>

  </div>

  <script>
    function switchTab(tabId) {
      localStorage.setItem('activeClassroomTab', tabId);
      document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.add('hidden');
      });
      const targetPanel = document.getElementById('tab-' + tabId);
      if (targetPanel) targetPanel.classList.remove('hidden');

      const tabs = ['outline', 'planner', 'cia', 'roster', 'series', 'internals', 'attainment', 'materials'];
      tabs.forEach(id => {
        const btn = document.getElementById('btn-' + id);
        if (!btn) return;
        if (id === tabId) {
          btn.className = "w-full text-left px-3.5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2.5 transition-all bg-blue-50 text-blue-700 border-l-4 border-blue-600 shadow-2xs cursor-pointer";
        } else {
          btn.className = "w-full text-left px-3.5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2.5 transition-all text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer";
        }
      });
    }

    function toggleTheme() {
      const body = document.body;
      const themeIcon = document.getElementById('theme-icon');
      if (body.classList.contains('dark')) {
        body.classList.remove('dark');
        body.classList.add('light');
        themeIcon.innerText = 'dark_mode';
      } else {
        body.classList.remove('light');
        body.classList.add('dark');
        themeIcon.innerText = 'light_mode';
      }
    }

    function toggleSyllabusUploadWorkspace() {
      const el = document.getElementById('syllabusUploadWorkspace');
      if (el) el.classList.toggle('hidden');
    }

    function handleDragOver(e) {
      e.preventDefault();
      e.stopPropagation();
      const dropzone = document.getElementById('syllabusDropzone');
      if (dropzone) dropzone.classList.add('border-blue-500', 'bg-blue-50/60');
    }

    function handleDragLeave(e) {
      e.preventDefault();
      e.stopPropagation();
      const dropzone = document.getElementById('syllabusDropzone');
      if (dropzone) dropzone.classList.remove('border-blue-500', 'bg-blue-50/60');
    }

    function handleFileDrop(e) {
      e.preventDefault();
      e.stopPropagation();
      const dropzone = document.getElementById('syllabusDropzone');
      if (dropzone) dropzone.classList.remove('border-blue-500', 'bg-blue-50/60');
      
      const files = e.dataTransfer.files;
      if (!files || files.length === 0) return;
      
      const file = files[0];
      if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
        alert('Please drop a valid PDF file.');
        return;
      }
      
      const input = document.getElementById('syllabusFileInput');
      if (input) {
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        input.files = dataTransfer.files;
        showSelectedFilePreview(file);
      }
    }

    function performSyllabusUpload(input) {
      if (!input.files || input.files.length === 0) return;
      const file = input.files[0];
      showSelectedFilePreview(file);
    }

    function showSelectedFilePreview(file) {
      const dropzone = document.getElementById('syllabusDropzone');
      const preview = document.getElementById('syllabusFilePreview');
      const fileNameEl = document.getElementById('previewFileName');
      const fileSizeEl = document.getElementById('previewFileSize');
      const errBox = document.getElementById('syllabusErrorAlert');

      if (errBox) errBox.classList.add('hidden');
      if (fileNameEl) fileNameEl.innerText = file.name;
      if (fileSizeEl) {
        const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        fileSizeEl.innerText = `${sizeMB} MB · Ready to process`;
      }
      if (dropzone) dropzone.classList.add('hidden');
      if (preview) preview.classList.remove('hidden');
    }

    function cancelSelectedFile(e) {
      if (e) { e.preventDefault(); e.stopPropagation(); }
      const input = document.getElementById('syllabusFileInput');
      if (input) input.value = '';
      const dropzone = document.getElementById('syllabusDropzone');
      const preview = document.getElementById('syllabusFilePreview');
      const processing = document.getElementById('syllabusProcessingState');
      const errBox = document.getElementById('syllabusErrorAlert');

      if (preview) preview.classList.add('hidden');
      if (processing) processing.classList.add('hidden');
      if (errBox) errBox.classList.add('hidden');
      if (dropzone) dropzone.classList.remove('hidden');
    }

    function submitSelectedSyllabus(e) {
      if (e) { e.preventDefault(); e.stopPropagation(); }
      const input = document.getElementById('syllabusFileInput');
      if (!input || !input.files || input.files.length === 0) {
        alert('Please select a syllabus PDF first.');
        return;
      }
      const file = input.files[0];
      const formData = new FormData();
      formData.append('syllabus_file', file);
      formData.append('_token', "{{ csrf_token() }}");

      const preview = document.getElementById('syllabusFilePreview');
      const processing = document.getElementById('syllabusProcessingState');
      const errBox = document.getElementById('syllabusErrorAlert');
      const errMsg = document.getElementById('syllabusErrorMessage');

      if (preview) preview.classList.add('hidden');
      if (errBox) errBox.classList.add('hidden');
      if (processing) processing.classList.remove('hidden');

      fetch('/api/r26/classroom/{{ $batchSubject->id }}/syllabus', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (processing) processing.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          window.location.reload();
        } else {
          if (errMsg) errMsg.innerText = data.message || 'Upload and extraction failed. Please try again.';
          if (errBox) errBox.classList.remove('hidden');
          const dropzone = document.getElementById('syllabusDropzone');
          if (dropzone) dropzone.classList.remove('hidden');
        }
      })
      .catch(err => {
        if (processing) processing.classList.add('hidden');
        if (errMsg) errMsg.innerText = 'Upload Error: ' + err.message;
        if (errBox) errBox.classList.remove('hidden');
        const dropzone = document.getElementById('syllabusDropzone');
        if (dropzone) dropzone.classList.remove('hidden');
      });
    }

    function updatePlannerRowStatusColor(select) {
      if (!select) return;
      const isCompleted = (select.value === 'Completed');
      const tr = select.closest('tr');
      if (tr) {
        tr.setAttribute('data-status', select.value);
        if (isCompleted) {
          tr.classList.add('bg-emerald-50/15');
        } else {
          tr.classList.remove('bg-emerald-50/15');
        }
      }
      if (isCompleted) {
        select.className = "w-full border focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-sm font-bold transition-all outline-none cursor-pointer bg-emerald-50 text-emerald-700 border-emerald-200";
      } else {
        select.className = "w-full border focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-sm font-bold transition-all outline-none cursor-pointer bg-amber-50/50 text-amber-700 border-amber-200";
      }
    }

    function filterPlannerRows() {
      const search = (document.getElementById('plannerSearchInput')?.value || '').toLowerCase().trim();
      const co = document.getElementById('plannerCoFilter')?.value || 'ALL';
      const status = document.getElementById('plannerStatusFilter')?.value || 'ALL';

      const rows = document.querySelectorAll('#plannerTableBody tr.planner-row');
      let visibleCount = 0;

      rows.forEach(tr => {
        const rowCo = tr.getAttribute('data-co') || '';
        const rowStatus = tr.querySelector('[data-field="status"]')?.value || tr.getAttribute('data-status') || '';
        const topic = (tr.querySelector('[data-field="topic_content"]')?.value || '').toLowerCase();
        const periodText = (tr.querySelector('td:first-child')?.innerText || '').toLowerCase();

        let matchSearch = !search || topic.includes(search) || periodText.includes(search);
        let matchCo = (co === 'ALL') || (rowCo === co);
        let matchStatus = (status === 'ALL') || (rowStatus === status);

        if (matchSearch && matchCo && matchStatus) {
          tr.classList.remove('hidden');
          visibleCount++;
        } else {
          tr.classList.add('hidden');
        }
      });

      const countEl = document.getElementById('plannerVisibleCount');
      if (countEl) {
        countEl.innerText = `Showing ${visibleCount} of ${rows.length} sessions`;
      }
    }

    function regenerateTheoryLessonPlan() {
      if (!confirm('Re-generate all lesson plans from syllabus outcomes and modules?\n\nThis will recreate the structured timeline and scale to the target curriculum contact hours.')) return;
      const btn = document.getElementById('btnRegenPlan');
      const originalText = btn ? btn.innerHTML : '';
      if (btn) { btn.disabled = true; btn.innerHTML = '<svg class="w-3.5 h-3.5 animate-spin inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Generating...'; }

      fetch('/api/classroom/{{ $batchSubject->id }}/lesson-plans/regenerate', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({})
      })
      .then(res => res.json())
      .then(data => {
        if (btn) { btn.disabled = false; btn.innerHTML = originalText; }
        if (data.status === 'SUCCESS') {
          alert('Lesson plans successfully regenerated!');
          window.location.reload();
        } else {
          alert('Regeneration failed: ' + (data.message || 'Unknown error'));
        }
      })
      .catch(err => {
        if (btn) { btn.disabled = false; btn.innerHTML = originalText; }
        alert('Error regenerating lesson plans: ' + err.message);
      });
    }

    function loadTheoryTemplate() {
      if (!confirm('Load the saved template for subject {{ $batchSubject->subject_code }}?\n\nThis will replace the current lesson plan with standard template records.')) return;
      const btn = document.getElementById('btnLoadTemplate');
      const originalText = btn ? btn.innerHTML : '';
      if (btn) { btn.disabled = true; btn.innerHTML = '<svg class="w-3.5 h-3.5 animate-spin inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Loading...'; }

      fetch('/api/classroom/{{ $batchSubject->id }}/lesson-plans/load-template', {
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (btn) { btn.disabled = false; btn.innerHTML = originalText; }
        if (data.status === 'SUCCESS') {
          alert('Template loaded successfully!');
          window.location.reload();
        } else {
          alert('Failed to load template: ' + (data.message || 'No template found'));
        }
      })
      .catch(err => {
        if (btn) { btn.disabled = false; btn.innerHTML = originalText; }
        alert('Error loading template: ' + err.message);
      });
    }

    function saveLessonPlanEdits() {
      const rows = [];
      const trs = document.querySelectorAll('#plannerTableBody tr');
      trs.forEach(tr => {
        const id = tr.getAttribute('data-lp-id');
        if (!id) return;
        
        const topic = tr.querySelector('[data-field="topic_content"]').value;
        const pedagogy = tr.querySelector('[data-field="pedagogy"]').value;
        const taxonomy = tr.querySelector('[data-field="taxonomy"]').value;
        const proposed = tr.querySelector('[data-field="proposed_date"]').value || null;
        const actual = tr.querySelector('[data-field="actual_date"]').value || null;
        const hours = tr.querySelector('[data-field="allocated_hours"]').value || 1;
        const status = tr.querySelector('[data-field="status"]').value;
        
        rows.push({
          id,
          topic_content: topic,
          pedagogy,
          taxonomy,
          proposed_date: proposed,
          actual_date: actual,
          allocated_hours: hours,
          status
        });
      });

      const btn = document.getElementById('btnSavePlanner');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Saving...';

      fetch('/api/r26/classroom/{{ $batchSubject->id }}/lesson-plans/bulk-update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ rows })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          alert('Lesson planner updated successfully!');
          window.location.reload();
        } else {
          alert('Failed to save changes: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error saving planner: ' + err.message);
      });
    }

    function saveAsTemplate() {
      if (!confirm('Save the current lesson plan as a reusable template for subject {{ $batchSubject->subject_code }}?\n\nThis will overwrite any previously saved template for this subject code.')) return;
      const btn = document.getElementById('btnSaveTemplate');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Saving Template...';

      fetch('/api/classroom/{{ $batchSubject->id }}/lesson-plans/save-as-template', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          alert('Lesson plan saved as a cross-batch template successfully!');
        } else {
          alert('Failed to save template: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error saving template: ' + err.message);
      });
    }

    function calculateRowCia(input) {
      const tr = input.closest('tr');
      const attVal = parseFloat(tr.querySelector('[data-val-attendance]').getAttribute('data-val-attendance')) || 0;
      const selfLearningVal = parseFloat(tr.querySelector('[data-field="self_learning"]').value) || 0;
      const seriesExamVal = parseFloat(tr.querySelector('[data-field="series_exam"]').value) || 0;
      
      const total = attVal + selfLearningVal + seriesExamVal;
      tr.querySelector('[data-field="total_cia"]').innerText = total.toFixed(1);
    }

    function saveCiaMarks() {
      const rows = [];
      const trs = document.querySelectorAll('#ciaTableBody tr');
      trs.forEach(tr => {
        const regNo = tr.getAttribute('data-reg-no');
        if (!regNo) return;
        
        const selfLearning = tr.querySelector('[data-field="self_learning"]').value;
        const seriesExam = tr.querySelector('[data-field="series_exam"]').value;
        
        rows.push({
          reg_no: regNo,
          self_learning_marks: selfLearning,
          series_exam_marks: seriesExam
        });
      });

      const btn = document.getElementById('btnSaveCia');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Saving...';

      fetch('/api/r26/classroom/{{ $batchSubject->id }}/cia-marks/bulk-update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ rows })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          alert('Continuous Internal Assessment (CIA) marks saved successfully!');
          window.location.reload();
        } else {
          alert('Failed to save CIA marks: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error saving CIA marks: ' + err.message);
      });
    }

    function toggleCiaView(view) {
      localStorage.setItem('activeCiaView', view);
      const cardsView = document.getElementById('cia-cards-view');
      const consolidatedView = document.getElementById('cia-consolidated-view');
      const selfLearningView = document.getElementById('cia-self-learning-view');
      
      cardsView.classList.add('hidden');
      consolidatedView.classList.add('hidden');
      selfLearningView.classList.add('hidden');
      
      if (view === 'consolidated') {
        consolidatedView.classList.remove('hidden');
      } else if (view === 'self-learning') {
        selfLearningView.classList.remove('hidden');
      } else {
        cardsView.classList.remove('hidden');
      }
    }

    let currentSelfLearningTab = 'CO1';

    function switchSelfLearningTab(co) {
      localStorage.setItem('activeSelfLearningTab', co);
      currentSelfLearningTab = co;
      
      // Hide all tables & config panels
      document.querySelectorAll('.sl-table-container').forEach(el => el.classList.add('hidden'));
      document.querySelectorAll('.sl-config-panel').forEach(el => el.classList.add('hidden'));
      
      // Show target table
      document.getElementById('sl-table-container-' + co).classList.remove('hidden');
      
      // If not summary, show config panel and update column headers
      if (co !== 'Summary') {
        document.getElementById('sl-config-' + co).classList.remove('hidden');
        updateActivityHeaders(co);
      }
      
      // Update sub-tab styles
      ['CO1', 'CO2', 'CO3', 'CO4', 'Summary'].forEach(item => {
        const btn = document.getElementById('tabbtn-sl-' + item);
        if (item === co) {
          btn.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-emerald-500/10 text-emerald-450 border border-emerald-500/20 cursor-pointer";
        } else {
          btn.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-muted hover:bg-slate-100 cursor-pointer";
        }
      });
    }

    function updateActivityHeaders(co) {
      const act3Mode = document.getElementById('cfg-' + co + '-act3_mode').value;
      const act4Mode = document.getElementById('cfg-' + co + '-act4_mode').value;
      const act5Mode = document.getElementById('cfg-' + co + '-act5_mode').value;
      
      const act3Max = parseFloat(document.getElementById('cfg-' + co + '-act3').value) || 0;
      const act4Max = parseFloat(document.getElementById('cfg-' + co + '-act4').value) || 0;
      const act5Max = parseFloat(document.getElementById('cfg-' + co + '-act5').value) || 0;
      
      document.querySelectorAll('.cfg-label-act3-' + co).forEach(el => el.innerText = act3Mode + ' (' + act3Max + 'M)');
      document.querySelectorAll('.cfg-label-act4-' + co).forEach(el => el.innerText = act4Mode + ' (' + act4Max + 'M)');
      document.querySelectorAll('.cfg-label-act5-' + co).forEach(el => el.innerText = act5Mode + ' (' + act5Max + 'M)');
    }

    function validateConfigSum(co) {
      const assignment = parseFloat(document.getElementById('cfg-' + co + '-assignment').value) || 0;
      const mcq = parseFloat(document.getElementById('cfg-' + co + '-mcq').value) || 0;
      const act3 = parseFloat(document.getElementById('cfg-' + co + '-act3').value) || 0;
      const act4 = parseFloat(document.getElementById('cfg-' + co + '-act4').value) || 0;
      const act5 = parseFloat(document.getElementById('cfg-' + co + '-act5').value) || 0;
      
      const total = assignment + mcq + act3 + act4 + act5;
      const statusEl = document.getElementById('cfg-' + co + '-status');
      
      if (total === 15) {
        statusEl.innerText = "✓ Valid (15 Marks)";
        statusEl.className = "font-bold text-emerald-500 text-xs";
        updateActivityHeaders(co);
        return true;
      } else {
        statusEl.innerText = "⚠ Warning: Sum is " + total + " Marks (Must be 15)";
        statusEl.className = "font-bold text-rose-500 text-xs animate-pulse";
        return false;
      }
    }

    function calculateSelfLearningRow(input, co) {
      const tr = input.closest('tr');
      const regNo = tr.getAttribute('data-reg-no');
      
      // Get configured max values
      const maxAssignment = parseFloat(document.getElementById('cfg-' + co + '-assignment').value) || 0;
      const maxMcq = parseFloat(document.getElementById('cfg-' + co + '-mcq').value) || 0;
      const maxAct3 = parseFloat(document.getElementById('cfg-' + co + '-act3').value) || 0;
      const maxAct4 = parseFloat(document.getElementById('cfg-' + co + '-act4').value) || 0;
      const maxAct5 = parseFloat(document.getElementById('cfg-' + co + '-act5').value) || 0;
      
      // Validate inputs do not exceed max configurations
      const field = input.getAttribute('data-field');
      let val = parseFloat(input.value) || 0;
      let limit = 0;
      
      if (field === 'assignment') limit = maxAssignment;
      else if (field === 'mcq') limit = maxMcq;
      else if (field === 'act3') limit = maxAct3;
      else if (field === 'act4') limit = maxAct4;
      else if (field === 'act5') limit = maxAct5;
      
      if (val > limit) {
        alert("Mark cannot exceed the maximum configured marks of " + limit + "M for this activity.");
        input.value = limit;
        val = limit;
      }
      
      // Compute total for this CO row
      const assignment = parseFloat(tr.querySelector('[data-field="assignment"]').value) || 0;
      const mcq = parseFloat(tr.querySelector('[data-field="mcq"]').value) || 0;
      const act3 = parseFloat(tr.querySelector('[data-field="act3"]').value) || 0;
      const act4 = parseFloat(tr.querySelector('[data-field="act4"]').value) || 0;
      const act5 = parseFloat(tr.querySelector('[data-field="act5"]').value) || 0;
      
      const rowTotal = assignment + mcq + act3 + act4 + act5;
      tr.querySelector('[data-field="co_total"]').innerText = rowTotal.toFixed(2);
      
      // Update Summary Sheet cells
      const summaryCoCell = document.getElementById('summary-' + regNo + '-' + co);
      if (summaryCoCell) {
        summaryCoCell.innerText = rowTotal.toFixed(2);
      }
      
      // Update Summary Sheet Average
      const co1Val = parseFloat(document.getElementById('summary-' + regNo + '-CO1').innerText) || 0;
      const co2Val = parseFloat(document.getElementById('summary-' + regNo + '-CO2').innerText) || 0;
      const co3Val = parseFloat(document.getElementById('summary-' + regNo + '-CO3').innerText) || 0;
      const co4Val = parseFloat(document.getElementById('summary-' + regNo + '-CO4').innerText) || 0;
      
      const avg = (co1Val + co2Val + co3Val + co4Val) / 4;
      const summaryAvgCell = document.getElementById('summary-' + regNo + '-avg');
      if (summaryAvgCell) {
        summaryAvgCell.innerText = avg.toFixed(2);
      }
    }

    function saveSelfLearningMarks() {
      // Validate all CO config sums are exactly 15 first
      let allValid = true;
      ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
        if (!validateConfigSum(co)) {
          allValid = false;
        }
      });
      
      if (!allValid) {
        alert("Please correct the Max Marks configurations. The sum of max marks for each CO must equal exactly 15.");
        return;
      }

      // Compile configurations
      const configs = {};
      ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
        configs[co] = {
          assignment: parseFloat(document.getElementById('cfg-' + co + '-assignment').value) || 0,
          mcq: parseFloat(document.getElementById('cfg-' + co + '-mcq').value) || 0,
          act3: parseFloat(document.getElementById('cfg-' + co + '-act3').value) || 0,
          act3_mode: document.getElementById('cfg-' + co + '-act3_mode').value,
          act4: parseFloat(document.getElementById('cfg-' + co + '-act4').value) || 0,
          act4_mode: document.getElementById('cfg-' + co + '-act4_mode').value,
          act5: parseFloat(document.getElementById('cfg-' + co + '-act5').value) || 0,
          act5_mode: document.getElementById('cfg-' + co + '-act5_mode').value,
        };
      });

      // Compile student rows
      const rows = [];
      const students = @json($studentCiaData);
      
      students.forEach(st => {
        const regNo = st.reg_no;
        const coDetails = {};
        
        ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
          const tableRow = document.querySelector('#selfLearningTableBody-' + co + ' tr[data-reg-no="' + regNo + '"]');
          if (tableRow) {
            coDetails[co] = {
              assignment: tableRow.querySelector('[data-field="assignment"]').value || 0,
              mcq: tableRow.querySelector('[data-field="mcq"]').value || 0,
              act3: tableRow.querySelector('[data-field="act3"]').value || 0,
              act4: tableRow.querySelector('[data-field="act4"]').value || 0,
              act5: tableRow.querySelector('[data-field="act5"]').value || 0,
            };
          }
        });

        rows.push({
          reg_no: regNo,
          co_details: coDetails
        });
      });

      const btn = document.getElementById('btnSaveSelfLearning');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Saving...';

      fetch('/api/r26/classroom/{{ $batchSubject->id }}/self-learning/bulk-update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ configs, rows })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          alert('Self-learning detailed activities evaluation logs saved successfully!');
          window.location.reload();
        } else {
          alert('Failed to save self-learning: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error saving marks: ' + err.message);
      });
    }

    // Initialize default tabs & labels on page load
    document.addEventListener("DOMContentLoaded", function() {
      switchTab('outline');
      toggleCiaView('cards');
      switchSelfLearningTab('CO1');

      // Restore Fullscreen State
      const isFullscreen = localStorage.getItem('classroomFullscreen') === 'true';
      if (isFullscreen) {
        const sidebar = document.getElementById('sidebar-panel-column');
        const details = document.getElementById('details-panel-column');
        const btn = document.getElementById('btn-fullscreen-toggle');
        
        sidebar.classList.add('hidden');
        details.className = "lg:col-span-4 transition-all duration-300";
        btn.innerHTML = `<span class="material-symbols-rounded text-xs">fullscreen_exit</span> Exit Fullscreen`;
        btn.className = "animate-attention px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-bold text-xs transition-all border border-amber-500/20 cursor-pointer flex items-center gap-1.5 shadow-sm";
      }

      ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
        validateConfigSum(co);
      });
    });

    function toggleSidebarWideMode() {
      const sidebar = document.getElementById('sidebar-panel-column');
      const details = document.getElementById('details-panel-column');
      const btn = document.getElementById('btn-fullscreen-toggle');
      
      if (sidebar.classList.contains('hidden')) {
        sidebar.classList.remove('hidden');
        details.className = "lg:col-span-3 transition-all duration-300";
        btn.innerHTML = `<span class="material-symbols-rounded text-xs">fullscreen</span> Fullscreen Mode`;
        btn.className = "animate-attention px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-lg font-bold text-xs transition-all border border-sky-500/20 cursor-pointer flex items-center gap-1.5 shadow-sm";
        localStorage.setItem('classroomFullscreen', 'false');
      } else {
        sidebar.classList.add('hidden');
        details.className = "lg:col-span-4 transition-all duration-300";
        btn.innerHTML = `<span class="material-symbols-rounded text-xs">fullscreen_exit</span> Exit Fullscreen`;
        btn.className = "animate-attention px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-bold text-xs transition-all border border-amber-500/20 cursor-pointer flex items-center gap-1.5 shadow-sm";
        localStorage.setItem('classroomFullscreen', 'true');
      }
    }

    // Modal Control & Questions State
    let activeCoTag = 'CO1';
    let assignmentQuestions = @json($courseFile->assignment_questions ?? []);
    let assignmentDeadlines = @json($courseFile->assignment_deadlines ?? []);
    let modalQuestionsList = [];

    function openAssignmentModal(coTag) {
      activeCoTag = coTag;
      document.getElementById('assignment-modal-co-title').innerText = coTag;
      
      // Load existing questions
      modalQuestionsList = assignmentQuestions[coTag] || [];
      renderModalQuestionsList();

      // Load existing due date
      const deadline = (assignmentDeadlines[coTag] && assignmentDeadlines[coTag]['deadline']) ? assignmentDeadlines[coTag]['deadline'] : '';
      document.getElementById('modal-assignment-due-date').value = deadline;
      
      // Apply Lock State
      const isLocked = !!(assignmentDeadlines[coTag] && assignmentDeadlines[coTag]['locked']);
      applyLockState(isLocked);

      // Update print link URLs
      document.getElementById('btn-print-qp').href = `/r26/classroom/assignment/{{ $batchSubject->id }}/print-qp/${coTag}`;
      document.getElementById('btn-print-scheme').href = `/r26/classroom/assignment/{{ $batchSubject->id }}/print-scheme/${coTag}`;
      
      document.getElementById('assignment-modal').classList.remove('hidden');
    }

    function closeAssignmentModal() {
      document.getElementById('assignment-modal').classList.add('hidden');
    }

    function applyLockState(isLocked) {
      const editor = document.querySelector('#assignment-modal .bg-white\\/40');
      const btnLock = document.getElementById('btn-notify-assignment');
      const btnSave = document.querySelector('button[onclick="saveAssignmentQuestions()"]');
      const lockBadge = document.getElementById('modal-lock-badge');

      if (isLocked) {
        editor.classList.add('opacity-60', 'pointer-events-none');
        btnLock.disabled = true;
        btnLock.innerHTML = `<span class="material-symbols-rounded text-xs">lock</span> Locked`;
        btnLock.className = "px-3 py-1 bg-emerald-600/10 text-emerald-550 border border-emerald-500/20 rounded text-xs font-medium transition-all flex items-center gap-1 cursor-not-allowed border-0";
        if (btnSave) btnSave.classList.add('hidden');
        if (lockBadge) {
          lockBadge.classList.remove('hidden');
          lockBadge.style.display = 'inline-flex';
        }
      } else {
        editor.classList.remove('opacity-60', 'pointer-events-none');
        btnLock.disabled = false;
        btnLock.innerHTML = `<span class="material-symbols-rounded text-xs">lock</span> Lock & Notify`;
        btnLock.className = "px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer border-0 shadow-sm";
        if (btnSave) btnSave.classList.remove('hidden');
        if (lockBadge) {
          lockBadge.classList.add('hidden');
          lockBadge.style.display = 'none';
        }
      }
    }

    function renderModalQuestionsList() {
      const container = document.getElementById('modal-questions-table-body');
      container.innerHTML = '';
      
      if (modalQuestionsList.length === 0) {
        container.innerHTML = '<tr><td colspan="6" class="p-4 text-center text-slate-500 italic font-normal">No questions added yet.</td></tr>';
        return;
      }

      const isLocked = !!(assignmentDeadlines[activeCoTag] && assignmentDeadlines[activeCoTag]['locked']);
      
      modalQuestionsList.forEach((q, idx) => {
        const tr = document.createElement('tr');
        tr.className = "bg-slate-50/50 hover:bg-slate-100 border-b border-slate-200 transition-all font-normal text-slate-800";
        tr.innerHTML = `
          <td class="p-2.5 font-mono text-center text-slate-600">${idx + 1}</td>
          <td class="p-2.5 text-slate-800 font-medium leading-relaxed text-left text-base">${q.question}</td>
          <td class="p-2.5 text-center text-slate-800 font-medium">${q.bt_level}</td>
          <td class="p-2.5 text-center font-mono text-emerald-450 font-bold">${q.marks}M</td>
          <td class="p-2.5 text-slate-600 font-normal leading-relaxed text-left">${q.scheme || '—'}</td>
          <td class="p-2.5 text-center">
            ${isLocked ? `<span class="text-slate-400 font-bold text-xs">Locked</span>` : `
            <button type="button" onclick="deleteModalQuestion(${idx})" class="text-rose-500 hover:text-rose-600 cursor-pointer border-0 bg-transparent">
              <span class="material-symbols-rounded text-sm">delete</span>
            </button>
            `}
          </td>
        `;
        container.appendChild(tr);
      });
    }

    function addQuestionToModalList() {
      const text = document.getElementById('modal-q-text').value.trim();
      const marks = parseFloat(document.getElementById('modal-q-marks').value) || 5;
      const bt = document.getElementById('modal-q-bt').value;
      const scheme = document.getElementById('modal-q-scheme').value.trim();
      
      if (!text) {
        alert("Please enter question text.");
        return;
      }
      
      modalQuestionsList.push({
        question: text,
        marks: marks,
        bt_level: bt,
        scheme: scheme
      });
      
      renderModalQuestionsList();
      
      // Clear inputs
      document.getElementById('modal-q-text').value = '';
      document.getElementById('modal-q-scheme').value = '';
    }

    function deleteModalQuestion(idx) {
      modalQuestionsList.splice(idx, 1);
      renderModalQuestionsList();
    }

    function autoGenerateFromBank() {
      const mockQuestions = [
        { question: "Explain the fundamental principles and mapping of " + activeCoTag + " topics.", bt_level: "Understand", marks: 5, scheme: "Define core definitions (2M), explain with diagrams (3M)" },
        { question: "Solve the sample numeric evaluation problem relating to " + activeCoTag + " outline.", bt_level: "Apply", marks: 5, scheme: "Formula definition (1M), calculation steps (3M), final answer (1M)" },
        { question: "Compare and contrast the primary elements of " + activeCoTag + " syllabus.", bt_level: "Analyze", marks: 5, scheme: "List primary differences (3M), list similarities (2M)" }
      ];
      
      const randomQ = mockQuestions[Math.floor(Math.random() * mockQuestions.length)];
      document.getElementById('modal-q-text').value = randomQ.question;
      document.getElementById('modal-q-marks').value = randomQ.marks;
      document.getElementById('modal-q-bt').value = randomQ.bt_level;
      document.getElementById('modal-q-scheme').value = randomQ.scheme;
      
      alert("Suggested question populated from general question bank!");
    }

    function saveAssignmentQuestions() {
      const btn = document.querySelector('button[onclick="saveAssignmentQuestions()"]');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Saving...';
      
      const dueDate = document.getElementById('modal-assignment-due-date').value;
      
      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/assignment/${activeCoTag}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ questions: modalQuestionsList, due_date: dueDate })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          assignmentQuestions[activeCoTag] = modalQuestionsList;
          assignmentDeadlines[activeCoTag] = { deadline: dueDate, locked: false };
          alert('Assignment details saved successfully!');
        } else {
          alert('Error saving details: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error: ' + err.message);
      });
    }

    function notifyStudentsAssignment() {
      if (!confirm("Are you sure you want to lock and publish this assignment to the student dashboards? Once locked, you cannot add, edit, or delete questions.")) {
        return;
      }
      const btn = document.getElementById('btn-notify-assignment');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Locking...';
      
      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/assignment/${activeCoTag}/notify`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          alert('Assignment locked and notification successfully published to student dashboards!');
          window.location.reload();
        } else {
          alert('Failed to publish notifications: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error: ' + err.message);
      });
    }
  </script>

  <!-- ASSIGNMENT MODAL POPUP -->
  <div id="assignment-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs hidden">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-6xl p-6 shadow-2xl space-y-6 max-h-[95vh] overflow-y-auto custom-scrollbar text-slate-800" >
      <div class="flex justify-between items-center border-b border-slate-200 pb-3">
        <div class="flex items-center gap-2">
          <h3 class="text-sm font-bold text-title flex items-center gap-2">
            <span class="material-symbols-rounded text-indigo-600">assignment</span>
            Manage Assignment - <span id="assignment-modal-co-title">CO1</span>
          </h3>
          <span id="modal-lock-badge" class="ml-2 px-2 py-0.5 bg-emerald-500/10 text-emerald-450 border border-emerald-500/20 text-xs font-bold rounded flex items-center gap-0.5 hidden">
            <span class="material-symbols-rounded text-xs">lock</span> Published & Locked
          </span>
        </div>
        <div class="flex items-center gap-3">
          <button type="button" id="btn-notify-assignment" onclick="notifyStudentsAssignment()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer border-0 shadow-sm">
            <span class="material-symbols-rounded text-xs">lock</span> Lock & Notify
          </button>
          <button type="button" onclick="closeAssignmentModal()" class="text-slate-400 hover:text-slate-800 cursor-pointer border-0 bg-transparent flex items-center">
            <span class="material-symbols-rounded">close</span>
          </button>
        </div>
      </div>

      <!-- Stacked Editor Section (Full Width) -->
      <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-4">
        <h4 class="font-bold text-title text-xs uppercase tracking-wider">Add/Edit Question</h4>
        <div class="space-y-4">
          <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-bold">Question Description:</label>
            <textarea id="modal-q-text" rows="4" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-slate-800 text-sm focus:border-indigo-500 outline-none font-normal" placeholder="Type assignment question description here..."></textarea>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs text-slate-400 mb-1.5 font-bold">Max Marks:</label>
              <input type="number" id="modal-q-marks" value="5" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-slate-800 text-sm text-center focus:border-indigo-500 outline-none font-normal">
            </div>
            <div>
              <label class="block text-xs text-slate-400 mb-1.5 font-bold">Taxonomy Level:</label>
              <select id="modal-q-bt" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-slate-800 text-sm focus:border-indigo-500 outline-none font-normal">
                <option value="Remember">Remember</option>
                <option value="Understand">Understand</option>
                <option value="Apply">Apply</option>
                <option value="Analyze">Analyze</option>
                <option value="Evaluate">Evaluate</option>
                <option value="Create">Create</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-400 mb-1.5 font-bold">Due Date:</label>
              <input type="date" id="modal-assignment-due-date" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-slate-800 text-sm focus:border-indigo-500 outline-none font-normal">
            </div>
          </div>

          <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-bold">Scheme of Evaluation / Rubrics / Hints:</label>
            <textarea id="modal-q-scheme" rows="2" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-slate-800 text-sm focus:border-indigo-500 outline-none font-normal" placeholder="Specify evaluation guidelines here (e.g., Correct formula: 2 Marks, Steps and explanation: 3 Marks)"></textarea>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <button type="button" onclick="autoGenerateFromBank()" class="px-4 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-xs rounded-lg text-xs font-bold border border-slate-200 transition-all cursor-pointer flex items-center gap-1">
              <span class="material-symbols-rounded text-xs">psychology</span> Suggest from Q-Bank
            </button>
            <button type="button" onclick="addQuestionToModalList()" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer border-0 flex items-center gap-1">
              <span class="material-symbols-rounded text-xs">add</span> Add to List
            </button>
          </div>
        </div>
      </div>

      <!-- Table Grid View for Questions (Full Width) -->
      <div class="space-y-3">
        <div class="flex justify-between items-center">
          <h4 class="font-bold text-title text-xs uppercase tracking-wider">Active Questions Table</h4>
          <!-- Print & Notify Action Panel -->
          <div class="flex gap-2">
            <a href="#" id="btn-print-qp" target="_blank" class="px-3 py-1 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-xs rounded-lg text-xs font-bold border border-slate-200 transition-all flex items-center gap-1">
              <span class="material-symbols-rounded text-xs font-normal">print</span> Print Assignment Questions
            </a>
            <a href="#" id="btn-print-scheme" target="_blank" class="px-3 py-1 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-xs rounded-lg text-xs font-bold border border-slate-200 transition-all flex items-center gap-1">
              <span class="material-symbols-rounded text-xs font-normal">description</span> Print Scheme
            </a>
          </div>
        </div>

        <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50/70 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200">
                <th class="p-3 w-[6%] text-center border-r border-slate-200">No.</th>
                <th class="p-3 border-r border-slate-200">Question Description</th>
                <th class="p-3 w-[15%] text-center border-r border-slate-200">Cognitive Level (BT)</th>
                <th class="p-3 w-[12%] text-center border-r border-slate-200">Marks</th>
                <th class="p-3 w-[25%] border-r border-slate-200">Evaluation Scheme</th>
                <th class="p-3 w-[8%] text-center">Action</th>
              </tr>
            </thead>
            <tbody id="modal-questions-table-body" class="divide-y divide-slate-850 text-sm font-normal text-slate-800">
              <!-- Rendered dynamically -->
            </tbody>
          </table>
        </div>
      </div>

      <div class="flex justify-end gap-2 border-t border-slate-200 pt-3">
        <button type="button" onclick="closeAssignmentModal()" class="px-4 py-2 bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-200 text-slate-800 text-slate-600 rounded-lg text-xs font-bold transition-all cursor-pointer border-0">Cancel</button>
        <button type="button" onclick="saveAssignmentQuestions()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer border-0 shadow-sm">Save Questions</button>
      </div>
    </div>
  </div>

  <!-- SERIES EXAMS BUILDER MODAL POPUP -->
  <div id="series-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs hidden">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-6xl p-6 shadow-2xl space-y-6 max-h-[95vh] overflow-y-auto custom-scrollbar text-slate-800" >
      
      <!-- Modal Header -->
      <div class="flex justify-between items-center border-b border-slate-200 pb-3">
        <div class="flex items-center gap-2">
          <h3 class="text-sm font-bold text-title flex items-center gap-2">
            <span class="material-symbols-rounded text-sky-400">quiz</span>
            Build Series Exam - <span id="series-modal-title">Series Exam 1</span>
          </h3>
          <span id="series-lock-badge" class="ml-2 px-2 py-0.5 bg-emerald-500/10 text-emerald-450 border border-emerald-500/20 text-xs font-bold rounded flex items-center gap-0.5 hidden">
            <span class="material-symbols-rounded text-xs">lock</span> Published & Locked
          </span>
        </div>
        <div class="flex items-center gap-3">
          <button type="button" id="btn-lock-series" onclick="lockActiveSeries()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer border-0 shadow-sm">
            <span class="material-symbols-rounded text-xs">lock</span> Lock & Notify
          </button>
          <button type="button" onclick="closeSeriesModal()" class="text-slate-400 hover:text-slate-800 cursor-pointer border-0 bg-transparent flex items-center">
            <span class="material-symbols-rounded">close</span>
          </button>
        </div>
      </div>

      <!-- Unified Stacked Sections -->
      <div class="space-y-6">
        
        <!-- PART A SECTION -->
        <div class="border border-slate-200 rounded-xl p-4 bg-white space-y-3">
          <div class="flex justify-between items-center border-b border-slate-200 pb-1.5">
            <h4 class="font-bold text-title text-xs uppercase tracking-wider inline-flex items-center gap-1.5 align-middle">
              <span class="material-symbols-rounded text-sm text-indigo-600">filter_1</span> Part A (1 Mark Each)
            </h4>
            <span class="text-xs font-medium text-slate-400" id="part-a-count-info">Questions required: 2 nos (2 Marks total) / 4 nos (Combined COs)</span>
          </div>
          <!-- Table -->
          <div class="border border-slate-200 rounded-lg overflow-hidden bg-white">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50/70 text-xs font-bold text-slate-450 border-b border-slate-200">
                  <th class="p-2 w-[5%] text-center">No.</th>
                  <th class="p-2">Question Description</th>
                  <th class="p-2 w-[12%] text-center series-co-header">CO Tag</th>
                  <th class="p-2 w-[15%] text-center">BT Level</th>
                  <th class="p-2 w-[10%] text-center">Marks</th>
                  <th class="p-2 w-[8%] text-center">Action</th>
                </tr>
              </thead>
              <tbody id="series-questions-PartA" class="divide-y divide-slate-850 text-xs font-normal text-slate-800">
                <!-- Rendered dynamically -->
              </tbody>
            </table>
          </div>
          <!-- Inline Form -->
          <div class="grid grid-cols-1 gap-2 pt-2 border-t border-slate-200" id="editor-PartA">
            <div class="flex flex-col md:flex-row gap-2">
              <input type="text" id="series-q-text-PartA" placeholder="Enter Part A Question Description..." class="flex-1 bg-white border border-slate-200 text-slate-800 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-indigo-500">
              <select id="series-q-co-PartA" class="w-24 bg-white border border-slate-200 text-slate-800 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-indigo-500">
                <!-- Populated dynamically -->
              </select>
              <select id="series-q-bt-PartA" class="w-28 bg-white border border-slate-200 text-slate-800 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-indigo-500">
                <option value="Remember" selected>Remember</option>
                <option value="Understand">Understand</option>
                <option value="Apply">Apply</option>
                <option value="Analyze">Analyze</option>
                <option value="Evaluate">Evaluate</option>
              </select>
              <div class="flex gap-1.5">
                <button type="button" onclick="autoGenPartQuestion('Part A')" title="Suggest from Q-Bank" class="px-2.5 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-xs rounded-lg text-xs font-bold transition-all"><span class="material-symbols-rounded text-xs">psychology</span></button>
                <button type="button" onclick="addSeriesQuestionDirect('Part A')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-0.5"><span class="material-symbols-rounded text-xs">add</span> Add</button>
              </div>
            </div>
          </div>
        </div>

        <!-- PART B SECTION -->
        <div class="border border-slate-200 rounded-xl p-4 bg-white space-y-3">
          <div class="flex justify-between items-center border-b border-slate-200 pb-1.5">
            <h4 class="font-bold text-title text-xs uppercase tracking-wider inline-flex items-center gap-1.5 align-middle">
              <span class="material-symbols-rounded text-sm text-indigo-600">filter_2</span> Part B (3 Marks Each)
            </h4>
            <span class="text-xs font-medium text-slate-400" id="part-b-count-info">Questions required: 3 nos (9 Marks total) / 6 nos (18 Marks total)</span>
          </div>
          <!-- Table -->
          <div class="border border-slate-200 rounded-lg overflow-hidden bg-white">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50/70 text-xs font-bold text-slate-455 border-b border-slate-200">
                  <th class="p-2 w-[5%] text-center">No.</th>
                  <th class="p-2">Question Description</th>
                  <th class="p-2 w-[12%] text-center series-co-header">CO Tag</th>
                  <th class="p-2 w-[15%] text-center">BT Level</th>
                  <th class="p-2 w-[10%] text-center">Marks</th>
                  <th class="p-2 w-[8%] text-center">Action</th>
                </tr>
              </thead>
              <tbody id="series-questions-PartB" class="divide-y divide-slate-850 text-xs font-normal text-slate-800">
                <!-- Rendered dynamically -->
              </tbody>
            </table>
          </div>
          <!-- Inline Form -->
          <div class="grid grid-cols-1 gap-2 pt-2 border-t border-slate-200" id="editor-PartB">
            <div class="flex flex-col md:flex-row gap-2">
              <input type="text" id="series-q-text-PartB" placeholder="Enter Part B Question Description..." class="flex-1 bg-white border border-slate-200 text-slate-800 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-indigo-500">
              <select id="series-q-co-PartB" class="w-24 bg-white border border-slate-200 text-slate-800 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-indigo-500">
                <!-- Populated dynamically -->
              </select>
              <select id="series-q-bt-PartB" class="w-28 bg-white border border-slate-200 text-slate-800 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-indigo-500">
                <option value="Remember">Remember</option>
                <option value="Understand" selected>Understand</option>
                <option value="Apply">Apply</option>
                <option value="Analyze">Analyze</option>
                <option value="Evaluate">Evaluate</option>
              </select>
              <div class="flex gap-1.5">
                <button type="button" onclick="autoGenPartQuestion('Part B')" title="Suggest from Q-Bank" class="px-2.5 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-xs rounded-lg text-xs font-bold transition-all"><span class="material-symbols-rounded text-xs">psychology</span></button>
                <button type="button" onclick="addSeriesQuestionDirect('Part B')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-0.5"><span class="material-symbols-rounded text-xs">add</span> Add</button>
              </div>
            </div>
          </div>
        </div>

        <!-- PART C SECTION -->
        <div class="border border-slate-200 rounded-xl p-4 bg-white space-y-3">
          <div class="flex justify-between items-center border-b border-slate-200 pb-1.5">
            <h4 class="font-bold text-title text-xs uppercase tracking-wider inline-flex items-center gap-1.5 align-middle">
              <span class="material-symbols-rounded text-sm text-indigo-600">filter_3</span> Part C (7 Marks Each)
            </h4>
            <span class="text-xs font-medium text-slate-400" id="part-c-count-info">Questions required: 2 nos (14 Marks total) / 4 nos (28 Marks total)</span>
          </div>
          <!-- Table -->
          <div class="border border-slate-200 rounded-lg overflow-hidden bg-white">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50/70 text-xs font-bold text-slate-455 border-b border-slate-200">
                  <th class="p-2 w-[5%] text-center">No.</th>
                  <th class="p-2">Question Description</th>
                  <th class="p-2 w-[12%] text-center series-co-header">CO Tag</th>
                  <th class="p-2 w-[15%] text-center">BT Level</th>
                  <th class="p-2 w-[10%] text-center">Marks</th>
                  <th class="p-2 w-[8%] text-center">Action</th>
                </tr>
              </thead>
              <tbody id="series-questions-PartC" class="divide-y divide-slate-850 text-xs font-normal text-slate-800">
                <!-- Rendered dynamically -->
              </tbody>
            </table>
          </div>
          <!-- Inline Form -->
          <div class="grid grid-cols-1 gap-2 pt-2 border-t border-slate-200" id="editor-PartC">
            <div class="flex flex-col md:flex-row gap-2">
              <input type="text" id="series-q-text-PartC" placeholder="Enter Part C Question Description..." class="flex-1 bg-white border border-slate-200 text-slate-800 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-indigo-500">
              <select id="series-q-co-PartC" class="w-24 bg-white border border-slate-200 text-slate-800 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-indigo-500">
                <!-- Populated dynamically -->
              </select>
              <select id="series-q-bt-PartC" class="w-28 bg-white border border-slate-200 text-slate-800 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-indigo-500">
                <option value="Remember">Remember</option>
                <option value="Understand">Understand</option>
                <option value="Apply" selected>Apply</option>
                <option value="Analyze">Analyze</option>
                <option value="Evaluate">Evaluate</option>
              </select>
              <div class="flex gap-1.5">
                <button type="button" onclick="autoGenPartQuestion('Part C')" title="Suggest from Q-Bank" class="px-2.5 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-xs rounded-lg text-xs font-bold transition-all"><span class="material-symbols-rounded text-xs">psychology</span></button>
                <button type="button" onclick="addSeriesQuestionDirect('Part C')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-0.5"><span class="material-symbols-rounded text-xs">add</span> Add</button>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Footer Actions -->
      <div class="flex justify-end gap-2 border-t border-slate-200 pt-3">
        <button type="button" onclick="closeSeriesModal()" class="px-4 py-2 bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-200 text-slate-800 text-slate-600 rounded-lg text-xs font-bold transition-all cursor-pointer border-0">Cancel</button>
        <button type="button" id="btn-save-series-qp" onclick="saveSeriesExamQuestions()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer border-0 shadow-sm">Save Questions</button>
      </div>

    </div>
  </div>

  <script>
    // Series Exams Script State
    let dbSeriesExams = @json($seriesExams ?? []);
    let activeSeriesExamId = null;
    let activeSeriesPart = 'Part A';
    let seriesQuestionsList = { 'Part A': [], 'Part B': [], 'Part C': [] };
    let activeExamCoTags = [];
    let activeExamMaxMarks = 50;

    function initializeSeriesPattern() {
      const mode = document.querySelector('input[name="series-mode-select"]:checked').value;
      
      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/series-exams/configure`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ mode })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert('Series exam pattern configured successfully!');
          window.location.reload();
        } else {
          alert('Failed to configure pattern: ' + data.message);
        }
      });
    }

    function resetSeriesExamsConfig() {
      if (confirm("Are you sure you want to reset and reconfigure the series exam pattern? This will delete all current series exam papers and marks entered.")) {
        fetch(`/api/r26/classroom/{{ $batchSubject->id }}/series-exams/configure?reset=1`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            window.location.reload();
          } else {
            alert('Failed to reset configuration: ' + data.message);
          }
        });
      }
    }

    function openSeriesBuilderModal(examId, name, mode, coTags, maxMarks) {
      activeSeriesExamId = examId;
      activeExamCoTags = coTags;
      activeExamMaxMarks = maxMarks;

      document.getElementById('series-modal-title').innerText = name;

      // Find the exam record from db list
      const examRecord = dbSeriesExams.find(ex => ex.id === examId);
      seriesQuestionsList = (examRecord && examRecord.questions) ? examRecord.questions : { 'Part A': [], 'Part B': [], 'Part C': [] };

      // Ensure lists are initialized
      if (!seriesQuestionsList['Part A']) seriesQuestionsList['Part A'] = [];
      if (!seriesQuestionsList['Part B']) seriesQuestionsList['Part B'] = [];
      if (!seriesQuestionsList['Part C']) seriesQuestionsList['Part C'] = [];

      // Update count requirements label based on mode
      const isSingle = (mode === 'single_co');
      document.getElementById('part-a-count-info').innerText = isSingle ? 'Questions required: 2 nos (2 Marks total)' : 'Questions required: 4 nos (4 Marks total)';
      document.getElementById('part-b-count-info').innerText = isSingle ? 'Questions required: 3 nos (9 Marks total)' : 'Questions required: 6 nos (18 Marks total)';
      document.getElementById('part-c-count-info').innerText = isSingle ? 'Questions required: 2 nos (14 Marks total)' : 'Questions required: 4 nos (28 Marks total)';

      // Hide or show CO table headers
      document.querySelectorAll('.series-co-header').forEach(el => {
        el.style.display = isSingle ? 'none' : '';
      });

      // Populate allowed CO selector for each Part
      ['Part A', 'Part B', 'Part C'].forEach(partName => {
        const key = partName.replace(' ', '');
        const coSelect = document.getElementById('series-q-co-' + key);
        if (coSelect) {
          if (isSingle) {
            coSelect.style.display = 'none';
          } else {
            coSelect.style.display = '';
            coSelect.innerHTML = '';
            coTags.forEach(co => {
              const opt = document.createElement('option');
              opt.value = co;
              opt.innerText = co;
              coSelect.appendChild(opt);
            });
          }
        }
      });

      // Apply locked states
      const isLocked = !!(examRecord && examRecord.locked);
      applySeriesLockState(isLocked);

      renderSeriesQuestionsList();

      document.getElementById('series-modal').classList.remove('hidden');
    }

    function closeSeriesModal() {
      document.getElementById('series-modal').classList.add('hidden');
    }

    function applySeriesLockState(isLocked) {
      const btnLock = document.getElementById('btn-lock-series');
      const btnSave = document.getElementById('btn-save-series-qp');
      const lockBadge = document.getElementById('series-lock-badge');

      ['PartA', 'PartB', 'PartC'].forEach(key => {
        const editor = document.getElementById('editor-' + key);
        if (editor) {
          if (isLocked) {
            editor.classList.add('opacity-60', 'pointer-events-none');
          } else {
            editor.classList.remove('opacity-60', 'pointer-events-none');
          }
        }
      });

      if (isLocked) {
        btnLock.disabled = true;
        btnLock.innerHTML = `<span class="material-symbols-rounded text-xs">lock</span> Locked`;
        btnLock.className = "px-3 py-1.5 bg-emerald-600/10 text-emerald-550 border border-emerald-500/20 rounded text-xs font-medium cursor-not-allowed border-0";
        if (btnSave) btnSave.classList.add('hidden');
        if (lockBadge) {
          lockBadge.classList.remove('hidden');
          lockBadge.style.display = 'inline-flex';
        }
      } else {
        btnLock.disabled = false;
        btnLock.innerHTML = `<span class="material-symbols-rounded text-xs">lock</span> Lock & Notify`;
        btnLock.className = "px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-xs font-medium transition-all cursor-pointer border-0 shadow-sm";
        if (btnSave) btnSave.classList.remove('hidden');
        if (lockBadge) {
          lockBadge.classList.add('hidden');
          lockBadge.style.display = 'none';
        }
      }
    }

    function renderSeriesQuestionsList() {
      // Find lock status
      const examRecord = dbSeriesExams.find(ex => ex.id === activeSeriesExamId);
      const isLocked = !!(examRecord && examRecord.locked);
      const isSingle = examRecord ? (examRecord.mode === 'single_co') : false;

      ['Part A', 'Part B', 'Part C'].forEach(partName => {
        const key = partName.replace(' ', '');
        const container = document.getElementById('series-questions-' + key);
        if (!container) return;
        container.innerHTML = '';

        const list = seriesQuestionsList[partName] || [];
        const colSpan = isSingle ? 5 : 6;
        if (list.length === 0) {
          container.innerHTML = `<tr><td colspan="${colSpan}" class="p-3 text-center text-slate-500 italic font-normal">No questions added to ${partName} yet.</td></tr>`;
          return;
        }

        list.forEach((q, idx) => {
          const tr = document.createElement('tr');
          tr.className = "bg-slate-50/50 hover:bg-slate-100 border-b border-slate-200 transition-all font-normal text-slate-800";
          tr.innerHTML = `
            <td class="p-2 font-mono text-center text-slate-600">${idx + 1}</td>
            <td class="p-2 text-slate-800 font-medium leading-relaxed text-left text-base">${q.question}</td>
            <td class="p-2 text-center text-slate-800 font-medium series-co-cell" ${isSingle ? 'style="display:none;"' : ''}>${q.co_tag}</td>
            <td class="p-2 text-center text-slate-800 font-medium">${q.bt_level}</td>
            <td class="p-2 text-center font-mono text-emerald-450 font-bold">${q.marks}M</td>
            <td class="p-2 text-center">
              ${isLocked ? `<span class="text-slate-400 font-bold text-xs">Locked</span>` : `
              <button type="button" onclick="deleteSeriesQuestionDirect('${partName}', ${idx})" class="text-rose-500 hover:text-rose-600 cursor-pointer border-0 bg-transparent">
                <span class="material-symbols-rounded text-sm">delete</span>
              </button>
              `}
            </td>
          `;
          container.appendChild(tr);
        });
      });
    }

    function addSeriesQuestionDirect(partName) {
      const key = partName.replace(' ', '');
      const text = document.getElementById('series-q-text-' + key).value.trim();
      const coSelect = document.getElementById('series-q-co-' + key);
      const co = (coSelect && coSelect.style.display !== 'none' && coSelect.value) ? coSelect.value : (activeExamCoTags[0] || 'CO1');
      const bt = document.getElementById('series-q-bt-' + key).value;
      
      const examRecord = dbSeriesExams.find(ex => ex.id === activeSeriesExamId);
      const isSingle = examRecord ? (examRecord.mode === 'single_co') : false;

      let maxQuestions = 0;
      if (isSingle) {
        if (partName === 'Part A') maxQuestions = 2;
        else if (partName === 'Part B') maxQuestions = 3;
        else if (partName === 'Part C') maxQuestions = 2;
      } else {
        if (partName === 'Part A') maxQuestions = 4;
        else if (partName === 'Part B') maxQuestions = 6;
        else if (partName === 'Part C') maxQuestions = 4;
      }

      const currentCount = (seriesQuestionsList[partName] || []).length;
      if (currentCount >= maxQuestions) {
        alert(`Cannot add more questions. ${partName} is restricted to a maximum of ${maxQuestions} questions in ${isSingle ? 'Single CO' : 'Combined COs'} mode.`);
        return;
      }

      let marks = 1;
      if (partName === 'Part B') marks = 3;
      else if (partName === 'Part C') marks = 7;

      if (!text) {
        alert("Please enter a question description.");
        return;
      }

      seriesQuestionsList[partName].push({
        question: text,
        marks: marks,
        co_tag: co,
        bt_level: bt,
        scheme: ''
      });

      renderSeriesQuestionsList();

      // Clear inputs
      document.getElementById('series-q-text-' + key).value = '';
    }

    function deleteSeriesQuestionDirect(partName, idx) {
      seriesQuestionsList[partName].splice(idx, 1);
      renderSeriesQuestionsList();
    }

    function autoGenPartQuestion(partName) {
      const key = partName.replace(' ', '');

      const examRecord = dbSeriesExams.find(ex => ex.id === activeSeriesExamId);
      const isSingle = examRecord ? (examRecord.mode === 'single_co') : false;

      let maxQuestions = 0;
      if (isSingle) {
        if (partName === 'Part A') maxQuestions = 2;
        else if (partName === 'Part B') maxQuestions = 3;
        else if (partName === 'Part C') maxQuestions = 2;
      } else {
        if (partName === 'Part A') maxQuestions = 4;
        else if (partName === 'Part B') maxQuestions = 6;
        else if (partName === 'Part C') maxQuestions = 4;
      }

      const currentCount = (seriesQuestionsList[partName] || []).length;
      if (currentCount >= maxQuestions) {
        alert(`Cannot suggest questions. ${partName} is restricted to a maximum of ${maxQuestions} questions in ${isSingle ? 'Single CO' : 'Combined COs'} mode.`);
        return;
      }

      const mockQuestions = [
        { question: "Define the fundamental concept and basic operations of " + partName + ".", bt_level: "Remember" },
        { question: "Explain the working architecture and execution flow for " + partName + " module.", bt_level: "Understand" },
        { question: "Develop a functional model based on guidelines defined in " + partName + ".", bt_level: "Apply" },
        { question: "Compare and contrast key components relative to " + partName + " outcomes.", bt_level: "Analyze" }
      ];

      const randomQ = mockQuestions[Math.floor(Math.random() * mockQuestions.length)];
      
      document.getElementById('series-q-text-' + key).value = randomQ.question;
      document.getElementById('series-q-bt-' + key).value = randomQ.bt_level;
      
      alert("Suggested question populated from Question Bank pool!");
    }

    function saveSeriesExamQuestions() {
      const btn = document.getElementById('btn-save-series-qp');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Saving...';

      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/series-exams/${activeSeriesExamId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ questions: seriesQuestionsList })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          // Update local state list
          const exIdx = dbSeriesExams.findIndex(ex => ex.id === activeSeriesExamId);
          if (exIdx !== -1) {
            dbSeriesExams[exIdx].questions = seriesQuestionsList;
          }
          alert('Series exam questions saved successfully!');
        } else {
          alert('Error saving questions: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error: ' + err.message);
      });
    }

    function lockActiveSeries() {
      lockAndPublishSeries(activeSeriesExamId);
    }

    function lockAndPublishSeries(examId) {
      if (!confirm("Are you sure you want to lock and publish this series exam paper? Once locked, you cannot add, edit, or delete questions.")) {
        return;
      }

      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/series-exams/${examId}/lock`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert('Series exam locked and notification successfully published to student dashboards!');
          window.location.reload();
        } else {
          alert('Failed to lock exam: ' + data.message);
        }
      })
      .catch(err => {
        alert('Error: ' + err.message);
      });
    }

    function recalculateSeriesRow(input) {
      const tr = input.closest('tr');
      const regNo = tr.getAttribute('data-reg-no');
      
      let totalObtained = 0.0;
      let totalMax = 0;

      tr.querySelectorAll('.series-mark-input').forEach(inp => {
        const val = parseFloat(inp.value) || 0;
        const max = parseFloat(inp.getAttribute('max')) || 50;
        
        // Ensure input doesn't exceed max marks
        if (val > max) {
          alert("Mark cannot exceed the maximum max marks limit of " + max + "M.");
          inp.value = max;
        }

        totalObtained += parseFloat(inp.value) || 0;
        totalMax += max;
      });

      const scaledTotalCell = tr.querySelector('[data-field="series-scaled-total"]');
      if (scaledTotalCell && totalMax > 0) {
        const scaled = (totalObtained / totalMax) * 20;
        scaledTotalCell.innerText = scaled.toFixed(2);
      }
    }

    function saveSeriesExamMarks() {
      const rows = [];
      document.querySelectorAll('#seriesMarksTableBody tr').forEach(tr => {
        const regNo = tr.getAttribute('data-reg-no');
        const examMarks = {};
        
        tr.querySelectorAll('.series-mark-input').forEach(inp => {
          const examId = inp.getAttribute('data-exam-id');
          examMarks[examId] = parseFloat(inp.value) || 0.0;
        });

        rows.push({
          reg_no: regNo,
          exam_marks: examMarks
        });
      });

      const btn = document.getElementById('btnSaveSeriesMarks');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Saving...';

      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/series-exams/marks/bulk-update`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ rows })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          alert('Series examinations scores saved successfully!');
          window.location.reload();
        } else {
          alert('Failed to save marks: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error: ' + err.message);
      });
    }

    // Consolidated Internals Tab Sub-navigation
    function switchInternalsSubtab(subTabId) {
      const subTabs = ['cie_marks', 'ese_results', 'nba_attainment'];
      subTabs.forEach(id => {
        const btn = document.getElementById('subbtn-' + id);
        const pane = document.getElementById('subtab-' + id);
        if (id === subTabId) {
          btn.className = "text-sm font-bold text-emerald-600 border-b-2 border-emerald-500 pb-1 cursor-pointer transition-all";
          pane.classList.remove('hidden');
        } else {
          btn.className = "text-sm font-bold text-slate-400 hover:text-slate-800 pb-1 cursor-pointer transition-all";
          pane.classList.add('hidden');
        }
      });
      if (subTabId === 'ese_results') {
        document.querySelectorAll('.ese-mark-input').forEach(input => calculateEseRow(input));
      }
    }

    function calculateEseRow(input) {
      const row = input.closest('tr');
      const cie = parseFloat(row.querySelector('[data-val-cie]').innerText) || 0;
      const ese = parseFloat(input.value) || 0;
      const total = cie + ese;
      row.querySelector('[data-field="total_score"]').innerText = total.toFixed(1);

      let grade = 'F';
      if (total >= 90) grade = 'S';
      else if (total >= 80) grade = 'A';
      else if (total >= 70) grade = 'B';
      else if (total >= 60) grade = 'C';
      else if (total >= 50) grade = 'D';
      else if (total >= 40) grade = 'E';
      
      let remark = 'FAIL';
      if (total >= 40 && ese >= 24) {
        remark = 'PASS';
      } else {
        grade = 'F';
      }

      const gDisp = row.querySelector('[data-field="grade_display"]');
      gDisp.innerText = grade;
      if (grade === 'F') {
        gDisp.className = "p-2.5 text-center font-bold text-rose-500";
      } else {
        gDisp.className = "p-2.5 text-center font-bold text-emerald-600";
      }

      const rDisp = row.querySelector('[data-field="remark_display"]');
      rDisp.innerText = remark;
      if (remark === 'PASS') {
        rDisp.className = "p-2.5 text-center font-bold text-emerald-600";
      } else {
        rDisp.className = "p-2.5 text-center font-bold text-rose-500";
      }
    }

    function saveEseMarks() {
      const marks = {};
      document.querySelectorAll('.student-ese-row').forEach(row => {
        const regNo = row.getAttribute('data-reg-no');
        const val = parseFloat(row.querySelector('.ese-mark-input').value) || 0;
        marks[regNo] = val;
      });

      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/ese-marks/bulk-update`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ marks: marks })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert("ESE Marks saved successfully!");
        } else {
          alert("Error saving ESE Marks: " + data.message);
        }
      });
    }

    function checkSurveyStatuses() {
      fetch(`/api/classroom/{{ $batchSubject->id }}/survey/results`)
      .then(res => res.json())
      .then(data => {
        const statusSpans = [document.getElementById('status-midsem'), document.getElementById('status-midsem-tab')];
        const initBtns = [document.getElementById('btn-initiate-midsem'), document.getElementById('btn-initiate-midsem-tab')];
        const closeBtns = [document.getElementById('btn-close-midsem'), document.getElementById('btn-close-midsem-tab')];
        if (data.status === 'SUCCESS') {
          const srv = data.data.survey;
          if (srv.status === 'Active') {
            statusSpans.forEach(el => { if(el) { el.innerText = `Active (${data.data.responded_count} responses)`; el.className = "text-xs font-bold text-emerald-450 flex items-center pl-2"; } });
            initBtns.forEach(el => el && el.classList.add('hidden'));
            closeBtns.forEach(el => el && el.classList.remove('hidden'));
          } else {
            statusSpans.forEach(el => { if(el) { el.innerText = `Completed & Locked`; el.className = "text-xs font-bold text-slate-400 flex items-center pl-2"; } });
            initBtns.forEach(el => el && el.classList.add('hidden'));
            closeBtns.forEach(el => el && el.classList.add('hidden'));
          }
        } else {
          statusSpans.forEach(el => { if(el) { el.innerText = "Inactive"; el.className = "text-xs font-bold text-rose-450 flex items-center pl-2"; } });
          initBtns.forEach(el => el && el.classList.remove('hidden'));
          closeBtns.forEach(el => el && el.classList.add('hidden'));
        }
      });

      fetch(`/api/classroom/{{ $batchSubject->id }}/course-exit/results`)
      .then(res => res.json())
      .then(data => {
        const statusSpans = [document.getElementById('status-exit'), document.getElementById('status-exit-tab')];
        const initBtns = [document.getElementById('btn-initiate-exit'), document.getElementById('btn-initiate-exit-tab')];
        const closeBtns = [document.getElementById('btn-close-exit'), document.getElementById('btn-close-exit-tab')];
        if (data.status === 'SUCCESS') {
          const srv = data.data.survey;
          if (srv.status === 'Active') {
            statusSpans.forEach(el => { if(el) { el.innerText = `Active (${data.data.responded_count} responses)`; el.className = "text-xs font-bold text-emerald-450 flex items-center pl-2"; } });
            initBtns.forEach(el => el && el.classList.add('hidden'));
            closeBtns.forEach(el => el && el.classList.remove('hidden'));
          } else {
            statusSpans.forEach(el => { if(el) { el.innerText = `Completed & Locked`; el.className = "text-xs font-bold text-slate-400 flex items-center pl-2"; } });
            initBtns.forEach(el => el && el.classList.add('hidden'));
            closeBtns.forEach(el => el && el.classList.add('hidden'));
          }
        } else {
          statusSpans.forEach(el => { if(el) { el.innerText = "Inactive"; el.className = "text-xs font-bold text-rose-450 flex items-center pl-2"; } });
          initBtns.forEach(el => el && el.classList.remove('hidden'));
          closeBtns.forEach(el => el && el.classList.add('hidden'));
        }
      });
    }

    function controlSurvey(type, action) {
      const endpoint = type === 'midsem' ? 'survey' : 'course-exit';
      const verb = action === 'initiate' ? 'initiate' : 'close';
      
      fetch(`/api/classroom/{{ $batchSubject->id }}/${endpoint}/${verb}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(`${type === 'midsem' ? 'Mid-Semester' : 'Course Exit'} survey updated successfully.`);
          checkSurveyStatuses();
        } else {
          alert(`Error updating survey: ` + data.message);
        }
      });
    }

    function submitMidsemInit(event) {
      event.preventDefault();
      const questions = {
        q5: document.getElementById('ms-q5').value.trim(),
        q6: document.getElementById('ms-q6').value.trim(),
        q7: document.getElementById('ms-q7').value.trim(),
        q8: document.getElementById('ms-q8').value.trim(),
        q9: document.getElementById('ms-q9').value.trim(),
        q10: document.getElementById('ms-q10').value.trim(),
        q11: document.getElementById('ms-q11').value.trim(),
        q12: document.getElementById('ms-q12').value.trim(),
      };

      fetch(`/api/classroom/{{ $batchSubject->id }}/survey/initiate`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ questions })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert('Mid-Semester survey initiated successfully with customized questions!');
          document.getElementById('modal-midsem-survey-init').classList.add('hidden');
          checkSurveyStatuses();
        } else {
          alert('Failed to initiate survey: ' + data.message);
        }
      });
    }

    // Submit customized Course Exit questions and activate
    function submitExitInit(event) {
      event.preventDefault();
      const questions = {
        q1: document.getElementById('ex-q1').value.trim(),
        q2: document.getElementById('ex-q2').value.trim(),
        q3: document.getElementById('ex-q3').value.trim(),
        q4: document.getElementById('ex-q4').value.trim(),
        q5: document.getElementById('ex-q5').value.trim(),
        q6: document.getElementById('ex-q6').value.trim(),
        q7: document.getElementById('ex-q7').value.trim(),
        q8: document.getElementById('ex-q8').value.trim(),
        q9: document.getElementById('ex-q9').value.trim(),
        q10: document.getElementById('ex-q10').value.trim(),
      };

      fetch(`/api/classroom/{{ $batchSubject->id }}/course-exit/initiate`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ questions })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert('Course Exit survey initiated successfully with customized questions!');
          document.getElementById('modal-exit-survey-init').classList.add('hidden');
          checkSurveyStatuses();
        } else {
          alert('Failed to initiate survey: ' + data.message);
        }
      });
    }

    // Run surveys status checks on page load
    checkSurveyStatuses();
  </script>
</body>
</html>
