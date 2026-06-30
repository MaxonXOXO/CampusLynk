<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Lecturer Dashboard</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <!-- Flatpickr for premium Date/Time selection -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <!-- SheetJS for client-side Excel parse & generation -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  
  <style>
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .scrollbar-hidden::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hidden {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }

    /* Clean overrides to enlarge fonts in the virtual classroom and related containers to normal readable sizes */
    #panelClassroom, 
    #panelClassroom button,
    #panelClassroom select,
    #panelClassroom input,
    #panelClassroom table,
    #panelClassroom th,
    #panelClassroom td,
    #panelClassroom div,
    #panelClassroom p,
    #panelClassroom h3,
    #panelClassroom h4,
    #panelClassroom h5,
    #panelClassroom span {
      font-size: 13px !important;
    }
    
    #panelClassroom h3#vcTitle,
    #panelClassroom h3#vcTitle span {
      font-size: 18px !important;
      font-weight: 900 !important;
    }
    
    #panelClassroom #vcSubtitle,
    #panelClassroom #vcSubtitle span {
      font-size: 14px !important;
    }
    
    #panelClassroom #vcViewStudentsBtn,
    #panelClassroom #vcViewStudentsBtn span {
      font-size: 13px !important;
      font-weight: bold !important;
    }

    #panelClassroom #tabStructure,
    #panelClassroom #tabPlanner,
    #panelClassroom #tabAssessment,
    #panelClassroom #tabSummative {
      font-size: 14px !important;
      font-weight: 900 !important;
    }

    #panelClassroom #tabStructure span,
    #panelClassroom #tabPlanner span,
    #panelClassroom #tabAssessment span,
    #panelClassroom #tabSummative span {
      font-size: 16px !important;
    }

    /* Course structure, lesson planner, formative, summative & online test setup container headings */
    #panelClassroom h4, 
    #panelClassroom h5 {
      font-size: 15px !important;
      font-weight: bold !important;
    }

    /* Manual mark entry table title, names, and internal grid data font sizes */
    #manualMarksWrapper table th,
    #manualMarksWrapper table td,
    #manualMarksWrapper input,
    #manualMarksWrapper span {
      font-size: 13px !important;
    }
    
    #manualMarksWrapper table td {
      padding: 12px 10px !important;
    }

    /* Flatpickr date picker calendar visibility in dark background */
    .flatpickr-calendar {
      background: #0f172a !important;
      border: 1px solid #334155 !important;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
      color: #f1f5f9 !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-month,
    .flatpickr-calendar .flatpickr-weekdays,
    .flatpickr-calendar .flatpickr-weekday,
    .flatpickr-calendar .flatpickr-days .flatpickr-day {
      color: #f1f5f9 !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.prevMonthDay:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.nextMonthDay:hover {
      background: #1e293b !important;
      color: #38bdf8 !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day.selected {
      background: #2563eb !important;
      color: white !important;
    }
    .flatpickr-calendar .flatpickr-current-month span.cur-month,
    .flatpickr-calendar .numInputWrapper span,
    .flatpickr-calendar input.numInput {
      color: #f1f5f9 !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-prev-month, 
    .flatpickr-calendar .flatpickr-months .flatpickr-next-month {
      color: #38bdf8 !important;
      fill: #38bdf8 !important;
    }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col md:flex-row">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Sidebar Navigation -->
  <aside class="w-full md:w-64 bg-slate-950 text-white flex-shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl">
    <div class="p-6 border-b border-slate-800/60 flex items-center gap-3">
      <img src="{{ asset('logo.jpg') }}" class="w-10 h-10 rounded-xl object-cover shadow-lg">
      <div>
        <h2 class="font-extrabold text-[10px] tracking-wide text-sm">Carmel Linx</h2>
        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Lecturer Console</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3">
      <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-bold text-[10px] block truncate text-slate-200 text-[10px] text-xs">{{ session('userName') }}</span>
        <span class="text-[10px] font-bold text-teal-400 block uppercase tracking-wider">{{ session('userBranch') }} Lecturer</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 ">
        <span class="material-symbols-rounded text-lg">grid_view</span> My Batches
      </button>

      @php
        $mobileNo = session('userId');
        $isTutor = \App\Models\ClassManagement::where('tutor_mobile_no', $mobileNo)->exists();
        $isMentor = \App\Models\ClassManagement::where('mentor_mobile_no', $mobileNo)->exists();
      @endphp

      @if($isTutor)
      <a href="/dashboard/tutor" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-sky-400 hover:bg-sky-900/30 cursor-pointer no-underline block ">
        <span class="material-symbols-rounded text-lg">admin_panel_settings</span> Tutor Console
      </a>
      @endif

      @if($isTutor || $isMentor)
      <a href="/dashboard/tutor" onclick="sessionStorage.setItem('openMentoring', 'true')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-emerald-400 hover:bg-emerald-900/30 cursor-pointer no-underline block ">
        <span class="material-symbols-rounded text-lg">diversity_3</span> My Mentoring
      </a>
      @endif
      
      <a href="/course-files" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-amber-400 hover:bg-amber-900/30 hover:text-amber-300 cursor-pointer no-underline ">
         <span class="material-symbols-rounded text-lg">folder_open</span> Course Files
      </a>

      <a href="/remedial-sessions" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-purple-400 hover:bg-purple-900/30 hover:text-purple-300 cursor-pointer no-underline">
         <span class="material-symbols-rounded text-xs">health_and_safety</span> Remedial Sessions
      </a>

      <a href="/staff/attendance-log" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-rose-400 hover:bg-rose-900/30 hover:text-rose-300 cursor-pointer no-underline">
         <span class="material-symbols-rounded text-lg">co_present</span> Log & Attendance
      </a>

      <button id="navSecurity" onclick="switchPanel('security')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer mt-4">
        <span class="material-symbols-rounded text-lg">settings</span> My Profile
      </button>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80">
      <a href="{{ url('/logout') }}" class="w-full py-3 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold text-sm flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium">
        <span class="material-symbols-rounded text-sm">logout</span> Sign Out
      </a>
    </div>
  </aside>

  <!-- Main Workspace -->
  <main class="flex-grow flex flex-col relative">
    
    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-2xl">My Batches</h1>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- PANEL 1: DASHBOARD (BATCH CARDS) -->
      <div id="panelDashboard" class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl gap-4">
          <div>
            <h3 class="text-lg font-black text-slate-200">My Assigned Batches & Classrooms</h3>
            <p class="text-sm text-slate-400 mt-0.5">Select a subject to enter the virtual classroom for assignments and assessments.</p>
          </div>
          <div class="flex bg-slate-900 border border-slate-800 p-1 rounded-xl shadow-inner">
            <button onclick="setDashboardBatchFilter('active')" id="btnFilterActive" class="px-4 py-2 rounded-lg text-sm font-bold bg-blue-600 text-white shadow transition-premium cursor-pointer">
              Active Batches
            </button>
            <button onclick="setDashboardBatchFilter('historical')" id="btnFilterHistorical" class="px-4 py-2 rounded-lg text-sm font-bold text-slate-400 hover:text-slate-200 transition-premium cursor-pointer">
              Archived Batches
            </button>
          </div>
        </div>
        
        <div id="lecturerBatchGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div class="col-span-full py-12 text-center text-slate-500 font-bold text-[10px] animate-pulse">Loading batches...</div>
        </div>
      </div>

      <!-- PANEL: VIRTUAL CLASSROOM -->
      <div id="panelClassroom" class="hidden space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center justify-between">
          <div>
            <button onclick="switchPanel('dashboard')" class="text-[10px] font-bold text-slate-400 hover:text-white uppercase tracking-wider flex items-center gap-1 transition-premium mb-1 cursor-pointer">
              <span class="material-symbols-rounded text-[10px]">arrow_back</span> Back to Dashboard
            </button>
            <h3 id="vcTitle" class="text-[10px] font-black text-slate-200 flex items-center gap-2 mt-1">
              <span class="material-symbols-rounded text-blue-400 text-xs">meeting_room</span> Virtual Classroom
            </h3>
            <p id="vcSubtitle" class="text-[10px] text-slate-400 mt-0.5 font-mono">Loading...</p>
          </div>
          <button id="vcViewStudentsBtn" onclick="showVcStudentsList()" class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700 text-slate-300 rounded-xl text-[10px] font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-md border border-slate-700/60">
            <span class="material-symbols-rounded text-[10px]">groups</span> View Students
          </button>
        </div>

        <!-- Top Banner: Course File Actions -->
        <div class="flex flex-col md:flex-row gap-6 mb-6">
             <!-- Syllabus Setup Card -->
             <div class="flex-grow bg-slate-950/40 border border-slate-800/60 p-4 rounded-2xl relative overflow-hidden group flex items-center justify-between">
                <div class="flex items-center gap-4">
                  <div id="syllabusUploadBox" class="border border-dashed border-slate-700/60 rounded-xl px-4 py-2 text-center hover:border-blue-500/50 hover:bg-slate-900/40 transition-premium cursor-pointer relative z-10 flex items-center gap-3" onclick="document.getElementById('syllabusFileInput').click()">
                    <span class="material-symbols-rounded text-base text-slate-500">picture_as_pdf</span>
                    <div class="text-left">
                      <p class="text-[10px] font-bold text-slate-300">Upload Syllabus PDF</p>
                      <p class="text-[10px] text-slate-500">Max 10MB</p>
                    </div>
                    <input type="file" id="syllabusFileInput" class="hidden" accept="application/pdf" onchange="handleSyllabusUpload(this)">
                  </div>
                  
                  <div id="syllabusUploadProgress" class="hidden relative z-10 flex-col justify-center min-w-[200px]">
                    <div class="flex justify-between text-[10px] font-bold text-blue-400 mb-1">
                      <span>Extracting...</span>
                      <span id="syllabusProgressText" class="animate-pulse">Processing</span>
                    </div>
                    <div class="w-full bg-slate-900 rounded-full h-1.5 border border-slate-800 overflow-hidden">
                      <div class="bg-gradient-to-r from-blue-600 to-sky-400 h-1.5 rounded-full w-full animate-[progress_2s_ease-in-out_infinite]"></div>
                    </div>
                  </div>
                </div>
                <span id="parseStatusBadge" class="text-[10px] font-bold px-3 py-1.5 rounded-md bg-slate-800/80 text-slate-400 border border-slate-700/50 whitespace-nowrap">Waiting for upload</span>
             </div>

             <!-- Download Active Syllabus Card -->
             <div id="activeSyllabusCard" class="hidden bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl flex items-center gap-4 transition-premium border-l-2 border-l-emerald-500 min-w-[250px]">
                <div class="bg-emerald-500/10 p-2 rounded-lg flex-shrink-0">
                  <span class="material-symbols-rounded text-emerald-400 text-xs block">check_circle</span>
                </div>
                <div class="flex-grow">
                  <h4 class="text-[10px] font-black text-slate-200">Active Syllabus</h4>
                  <p class="text-[10px] text-slate-400">Parsed & ready</p>
                </div>
                <a id="downloadSyllabusBtn" href="#" target="_blank" class="text-slate-400 hover:text-blue-400 transition-premium bg-slate-900/50 p-1.5 rounded-lg border border-slate-800 hover:border-blue-500/50">
                  <span class="material-symbols-rounded text-[10px] block">download</span>
                </a>
             </div>
        </div>
        
        <!-- Toggle Buttons -->
        <div class="flex flex-wrap items-center gap-4 border-b border-slate-800/60 pb-3 mb-4">
            <button onclick="toggleClassroomTab('structure')" id="tabStructure" class="text-sm font-black text-blue-400 flex items-center gap-1.5 transition-premium border-b-2 border-blue-500 pb-1">
              <span class="material-symbols-rounded text-base">account_tree</span> Course Structure
            </button>
            <button onclick="toggleClassroomTab('planner')" id="tabPlanner" class="text-sm font-bold text-slate-500 hover:text-slate-300 flex items-center gap-1.5 transition-premium pb-1 border-b-2 border-transparent hover:border-slate-600">
              <span class="material-symbols-rounded text-base">calendar_month</span> Lesson Planner
            </button>
            <button onclick="toggleClassroomTab('assessment')" id="tabAssessment" class="text-sm font-bold text-slate-500 hover:text-slate-300 flex items-center gap-1.5 transition-premium pb-1 border-b-2 border-transparent hover:border-slate-600">
              <span class="material-symbols-rounded text-base">assignment_turned_in</span> Formative Assessment
            </button>
            <button onclick="toggleClassroomTab('summative')" id="tabSummative" class="text-sm font-bold text-slate-500 hover:text-slate-300 flex items-center gap-1.5 transition-premium pb-1 border-b-2 border-transparent hover:border-slate-600">
              <span class="material-symbols-rounded text-base">school</span> Summative Assessment
            </button>
            <button onclick="toggleClassroomTab('reports')" id="tabReports" class="text-sm font-bold text-slate-500 hover:text-slate-300 flex items-center gap-1.5 transition-premium pb-1 border-b-2 border-transparent hover:border-slate-600">
              <span class="material-symbols-rounded text-base">assessment</span> Reports
            </button>
            <button onclick="toggleClassroomTab('qbank')" id="tabQBank" class="text-sm font-bold text-slate-500 hover:text-slate-300 flex items-center gap-1.5 transition-premium pb-1 border-b-2 border-transparent hover:border-slate-600">
              <span class="material-symbols-rounded text-base">database</span> Question Bank
            </button>
        </div>

        <!-- Parsed Data View (Full Width) -->
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl min-h-[400px] flex flex-col w-full">
            <div id="courseStructureContent" class="space-y-6 flex-grow overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-xl text-slate-600">inventory_2</span>
                </div>
                <p class="text-[10px] font-bold text-slate-400">No syllabus loaded.</p>
                <p class="text-[10px] mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus PDF to automatically populate Course Outcomes, Modules, and Textbooks.</p>
              </div>
            </div>
            
            <div id="coursePlannerContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-xl text-slate-600">event_note</span>
                </div>
                <p class="text-[10px] font-bold text-slate-400">Planner not generated.</p>
                <p class="text-[10px] mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to automatically generate the lesson plan.</p>
              </div>
            </div>

            <div id="formativeAssessmentContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-xl text-slate-600">quiz</span>
                </div>
                <p class="text-[10px] font-bold text-slate-400">No students or COs available.</p>
                <p class="text-[10px] mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to activate formative assessment tasks.</p>
              </div>
            </div>

            <div id="summativeAssessmentContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-xl text-slate-600">school</span>
                </div>
                <p class="text-[10px] font-bold text-slate-400">Loading summative assessments...</p>
              </div>
            </div>

            <div id="classReportsContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex flex-wrap gap-3">
                <button onclick="loadClassReport('attendance_log')" id="btnReportLog" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm cursor-pointer transition-premium">
                  Class Attendance Log
                </button>
                <button onclick="loadClassReport('subject_log')" id="btnReportSubject" class="px-4 py-2 bg-slate-900 text-slate-300 border border-slate-800 rounded-xl font-bold text-sm cursor-pointer hover:bg-slate-800 transition-premium">
                  Class Subject Log
                </button>
                <button onclick="loadClassReport('summary_matrix')" id="btnReportMatrix" class="px-4 py-2 bg-slate-900 text-slate-300 border border-slate-800 rounded-xl font-bold text-sm cursor-pointer hover:bg-slate-800 transition-premium">
                  Attendance Matrix
                </button>
              </div>

              <div id="classroomReportWorkspace" class="pt-4 overflow-x-auto">
                <div class="text-sm font-bold text-slate-400 py-10 text-center">No reports loaded. Please select a report type above.</div>
              </div>
            </div>

            <!-- Question Bank Panel -->
            <div id="questionBankContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-800/60 pb-4">
                <div>
                  <h4 class="text-sm font-black text-slate-200">Shared Question Bank Pool</h4>
                  <p class="text-sm text-slate-400 mt-1">Manage and import MCQ or Descriptive questions for this subject code. These questions are pooled across all batches.</p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                  <button onclick="downloadExcelTemplate()" class="px-4 py-2 bg-slate-900 hover:bg-slate-850 border border-slate-800 text-slate-200 rounded-xl text-sm font-bold transition-premium flex items-center gap-1.5 shadow-md cursor-pointer">
                    <span class="material-symbols-rounded text-base">download</span> Download Excel Template
                  </button>
                  <button onclick="document.getElementById('qbankFileInput').click()" class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700 text-slate-200 rounded-xl text-sm font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-md border border-slate-700/60">
                    <span class="material-symbols-rounded text-base">upload_file</span> Upload Filled Excel
                  </button>
                  <input type="file" id="qbankFileInput" class="hidden" accept=".xlsx,.xls,.csv" onchange="handleQBankUpload(this)">
                </div>
              </div>

              <!-- Question Bank View -->
              <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl p-6 shadow-inner">
                <div class="space-y-6" id="qbankCoGroups">
                  <div class="text-sm font-bold text-slate-400 py-10 text-center">Loading Question Bank...</div>
                </div>
              </div>
            </div>
        </div>
      </div>

      <!-- PANEL 2: SECURITY LOG / MY PROFILE -->
      <div id="panelSecurity" class="hidden space-y-6 animate-fade-in">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Profile Card -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl space-y-4">
            <div class="flex flex-col items-center text-center space-y-3">
              <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100' }}" class="w-24 h-24 rounded-full border border-slate-700 object-cover shadow-lg">
              <div>
                <h3 class="font-black text-white text-base">{{ session('userName') }}</h3>
                <span class="font-bold text-teal-400 uppercase tracking-wider text-xs">{{ session('userBranch') }} Lecturer</span>
              </div>
            </div>
            <div class="border-t border-slate-800/60 pt-4 space-y-2.5 text-xs">
              <div class="flex justify-between">
                <span class="text-slate-400">Mobile ID:</span>
                <span class="font-bold text-slate-200">{{ session('userId') }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-400">Branch:</span>
                <span class="font-bold text-slate-200">{{ session('userBranch') }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-400">Role Designation:</span>
                <span class="font-bold text-slate-200">Lecturer / Academic Staff</span>
              </div>
            </div>
          </div>

          <!-- Self Security Logs -->
          <div class="lg:col-span-2 bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl flex flex-col">
            <h3 class="font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2 text-sm">
              <span class="material-symbols-rounded text-blue-400 text-lg">security</span> My Profile Security Audit Trail
            </h3>
            <div class="flex-grow max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-800 rounded-xl">
              <table class="w-full text-left border-collapse text-xs">
                <thead>
                  <tr class="bg-slate-900/40 border-b border-slate-800 text-slate-400 font-bold">
                    <th class="p-3">Time</th>
                    <th class="p-3">Action</th>
                    <th class="p-3">Details</th>
                  </tr>
                </thead>
                <tbody id="securityLogsTable">
                  <!-- Loaded dynamically -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>

  <script>
    let activePanel = 'dashboard';

    document.addEventListener("DOMContentLoaded", () => {
      loadLecturerBatches();
      if (activePanel === 'security') loadSecurityLogs();
    });

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'security', 'classroom'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'dashboard': 'My Batches',
        'security': 'My Profile Security Log',
        'classroom': 'Virtual Classroom'
      };
      document.getElementById('panelTitle').innerText = titles[panelId] || 'Lecturer Console';

      if (panelId === 'security') loadSecurityLogs();
      if (panelId === 'dashboard') loadLecturerBatches();
    }

    let currentDashboardFilter = 'active';

    function setDashboardBatchFilter(status) {
      currentDashboardFilter = status;
      
      const activeBtn = document.getElementById('btnFilterActive');
      const historicalBtn = document.getElementById('btnFilterHistorical');

      if (status === 'active') {
        if (activeBtn) activeBtn.className = 'px-4 py-2 rounded-lg text-sm font-bold bg-blue-600 text-white shadow transition-premium cursor-pointer';
        if (historicalBtn) historicalBtn.className = 'px-4 py-2 rounded-lg text-sm font-bold text-slate-400 hover:text-slate-200 transition-premium cursor-pointer';
      } else {
        if (activeBtn) activeBtn.className = 'px-4 py-2 rounded-lg text-sm font-bold text-slate-400 hover:text-slate-200 transition-premium cursor-pointer';
        if (historicalBtn) historicalBtn.className = 'px-4 py-2 rounded-lg text-sm font-bold bg-blue-600 text-white shadow transition-premium cursor-pointer';
      }

      loadLecturerBatches();
    }

    function loadLecturerBatches() {
      const grid = document.getElementById('lecturerBatchGrid');
      grid.innerHTML = '<div class="col-span-full py-12 text-center text-slate-500 font-bold text-[10px] animate-pulse">Loading batches...</div>';

      fetch(`/api/lecturer/my-batches?status=${currentDashboardFilter}`, {
        headers: { 'Content-Type': 'application/json' }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          renderBatchCards(data.batches);
        } else {
          grid.innerHTML = `<div class="col-span-full p-4 bg-red-950/40 text-red-400 border border-red-900 rounded-xl text-[10px]">${data.message}</div>`;
        }
      })
      .catch(() => {
        grid.innerHTML = `<div class="col-span-full p-4 bg-red-950/40 text-red-400 border border-red-900 rounded-xl text-[10px]">Error loading batches.</div>`;
      });
    }

    function renderBatchCards(batches) {
      const grid = document.getElementById('lecturerBatchGrid');
      grid.innerHTML = '';

      if (batches.length === 0) {
        grid.innerHTML = `
          <div class="col-span-full bg-slate-950/40 border border-slate-800/60 p-8 rounded-2xl text-center shadow-sm max-w-2xl mx-auto">
            <span class="material-symbols-rounded text-5xl text-slate-700 mb-3">sentiment_dissatisfied</span>
            <p class="font-bold text-slate-300 text-sm">No batches assigned</p>
            <p class="text-xs text-slate-500 mt-1">You have not been assigned as a Tutor, Mentor, or Subject Staff for any batches yet.</p>
          </div>
        `;
        return;
      }

      batches.forEach(b => {
        let rolesHtml = '';
        b.roles.forEach(r => {
          let color = 'slate';
          if (r === 'Tutor') color = 'sky';
          if (r === 'Mentor') color = 'emerald';
          if (r === 'Subject Staff') color = 'violet';
          rolesHtml += `<span class="px-2 py-0.5 rounded text-[11px] font-bold bg-${color}-500/10 text-${color}-400 border border-${color}-500/20">${r}</span>`;
        });

        let subjectsHtml = '';
        if (b.subjects && b.subjects.length > 0) {
          b.subjects.forEach(s => {
            subjectsHtml += `
              <button onclick="openClassroom('${b.classroom_id}', '${s.id}', '${s.name} (${s.code})')" class="w-full text-left px-3.5 py-2.5 bg-slate-900/60 hover:bg-slate-800 border border-slate-800/60 hover:border-blue-500/50 rounded-xl transition-premium cursor-pointer group flex justify-between items-center">
                <div>
                  <div class="text-sm font-bold text-slate-200 group-hover:text-blue-400 transition-premium">${s.name}</div>
                  <div class="text-xs text-slate-400 font-mono mt-0.5">Sem ${s.semester} • ${s.type} • ${s.code}</div>
                </div>
                <span class="material-symbols-rounded text-slate-600 group-hover:text-blue-500 text-sm transition-premium">open_in_new</span>
              </button>
            `;
          });
        } else {
          subjectsHtml = `<div class="text-xs text-slate-500 italic px-2 py-2">No subjects assigned in this batch.</div>`;
        }

        const card = document.createElement('div');
        card.className = "bg-slate-950/40 border border-slate-800/60 rounded-2xl overflow-hidden flex flex-col transition-premium hover:shadow-xl hover:shadow-black/50 hover:border-slate-700/60";
        card.innerHTML = `
          <div class="p-4 border-b border-slate-800/60 bg-slate-900/40">
            <div class="flex justify-between items-start mb-2">
              <div>
                <h4 class="font-black text-slate-200 text-xl tracking-tight">${b.classroom_id}</h4>
                <div class="text-sm text-slate-400 font-mono mt-0.5">${b.branch} • Year ${b.batch_year}</div>
              </div>
              <div class="flex flex-wrap gap-1 justify-end max-w-[50%]">
                ${rolesHtml}
              </div>
            </div>
          </div>
          
          <div class="p-4 flex-grow space-y-3 bg-slate-950/20">
            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5"><span class="material-symbols-rounded text-xs">book</span> Assigned Subjects</h5>
            <div class="space-y-2">
              ${subjectsHtml}
            </div>
          </div>
        `;
        grid.appendChild(card);
      });
    }

    let currentSubjectId = null;

    function openClassroom(batchId, subjectId, subjectName) {
      currentSubjectId = subjectId;
      document.getElementById('vcTitle').innerHTML = `<span class="material-symbols-rounded text-blue-400 text-xs">meeting_room</span> ${subjectName}`;
      document.getElementById('vcSubtitle').innerText = `Batch: ${batchId}`;
      switchPanel('classroom');
      loadCourseDetails(subjectId);
    }

    function handleSyllabusUpload(input) {
      if (!input.files || input.files.length === 0) return;
      if (!currentSubjectId) return;

      const file = input.files[0];
      const formData = new FormData();
      formData.append('syllabus_file', file);
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

      document.getElementById('syllabusUploadBox').classList.add('hidden');
      document.getElementById('syllabusUploadProgress').classList.remove('hidden');
      document.getElementById('parseStatusBadge').innerText = 'Extracting...';
      document.getElementById('parseStatusBadge').className = 'text-[10px] font-bold px-2.5 py-1 rounded-md bg-blue-900/30 text-blue-400 border border-blue-500/30';

      fetch(`/api/classroom/${currentSubjectId}/syllabus`, {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        document.getElementById('syllabusUploadBox').classList.remove('hidden');
        document.getElementById('syllabusUploadProgress').classList.add('hidden');
        if (data.status === 'SUCCESS') {
          renderCourseStructure(data.data.cos, data.data.modules, data.data.textbooks, data.data.copo);
          renderCoursePlanner(data.data.lesson_plans);
          document.getElementById('activeSyllabusCard').classList.remove('hidden');
          alert('Syllabus successfully parsed!');
        } else {
          alert(data.message);
          document.getElementById('parseStatusBadge').innerText = 'Upload Failed';
          document.getElementById('parseStatusBadge').className = 'text-[10px] font-bold px-2.5 py-1 rounded-md bg-red-900/30 text-red-400 border border-red-500/30';
        }
      })
      .catch(err => {
        document.getElementById('syllabusUploadBox').classList.remove('hidden');
        document.getElementById('syllabusUploadProgress').classList.add('hidden');
        alert('Failed to upload syllabus.');
      });
    }

    function toggleClassroomTab(tabName) {
      const tabs = [
        { id: 'structure', btn: 'tabStructure', content: 'courseStructureContent' },
        { id: 'planner', btn: 'tabPlanner', content: 'coursePlannerContent' },
        { id: 'assessment', btn: 'tabAssessment', content: 'formativeAssessmentContent' },
        { id: 'summative', btn: 'tabSummative', content: 'summativeAssessmentContent' },
        { id: 'reports', btn: 'tabReports', content: 'classReportsContent' },
        { id: 'qbank', btn: 'tabQBank', content: 'questionBankContent' }
      ];

      tabs.forEach(t => {
        const btn = document.getElementById(t.btn);
        const content = document.getElementById(t.content);
        
        if (t.id === tabName) {
          if (btn) {
            btn.classList.add('border-blue-500', 'text-blue-400');
            btn.classList.remove('border-transparent', 'text-slate-500', 'hover:border-slate-600', 'hover:text-slate-300');
          }
          if (content) {
            content.classList.remove('hidden');
            if (t.id !== 'structure') content.classList.add('flex');
          }
        } else {
          if (btn) {
            btn.classList.remove('border-blue-500', 'text-blue-400');
            btn.classList.add('border-transparent', 'text-slate-500', 'hover:border-slate-600', 'hover:text-slate-300');
          }
          if (content) {
            content.classList.add('hidden');
            if (t.id !== 'structure') content.classList.remove('flex');
          }
        }
      });

      if (tabName === 'reports') {
        fetchClassReports();
      } else if (tabName === 'qbank') {
        fetchQuestionBank(currentSubjectId);
      }
    }

    let classReportsData = null;
    let activeReportType = 'attendance_log';
    let currentDeadlines = {};
    let currentQuestions = {};
    let currentSummativeTests = {};
    let currentSubjectName = '';
    let currentSubjectCode = '';

    function loadCourseDetails(subjectId) {
      currentSubjectId = subjectId;
      document.getElementById('courseStructureContent').innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
          <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-[10px] font-bold text-slate-400">Loading course data...</p>
        </div>
      `;
      document.getElementById('coursePlannerContent').innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
          <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-[10px] font-bold text-slate-400">Loading planner...</p>
        </div>
      `;
      document.getElementById('activeSyllabusCard').classList.add('hidden');
      document.getElementById('parseStatusBadge').innerText = 'Syncing...';
      document.getElementById('parseStatusBadge').className = 'text-[10px] font-bold px-2.5 py-1 rounded-md bg-blue-900/30 text-blue-400 border border-blue-500/30';

      fetch(`/api/classroom/${subjectId}/details`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS' && data.data) {
          currentDeadlines = data.data.assignment_deadlines || {};
          currentQuestions = data.data.assignment_questions || {};
          currentSummativeTests = data.data.summative_manual_tests || {};
          currentSubjectName = data.data.subject_name || '';
          currentSubjectCode = data.data.subject_code || '';
          window.currentVirtualStudents = data.data.students || [];
          
          renderCourseStructure(data.data.cos, data.data.modules, data.data.textbooks, data.data.copo);
          renderCoursePlanner(data.data.lesson_plans);
          renderFormativeAssessment(data.data.students || []);
          renderSummativeAssessment(data.data.cos, data.data.students || []);
          loadActiveOnlineTests(subjectId);
          
          if (Object.keys(currentQuestions).length > 0) {
            renderAIQuestionsList(currentQuestions, subjectId);
          }

          if (data.data.syllabus_pdf_path) {
            document.getElementById('activeSyllabusCard').classList.remove('hidden');
            document.getElementById('downloadSyllabusBtn').href = data.data.syllabus_pdf_path;
          }
        } else {
          document.getElementById('parseStatusBadge').innerText = 'Waiting for upload';
          document.getElementById('parseStatusBadge').className = 'text-[10px] font-bold px-2.5 py-1 rounded-md bg-slate-800/80 text-slate-400 border border-slate-700/50';
          document.getElementById('courseStructureContent').innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
              <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                <span class="material-symbols-rounded text-xl text-slate-600">inventory_2</span>
              </div>
              <p class="text-sm font-bold text-slate-400">No syllabus loaded.</p>
              <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus PDF to automatically populate Course Outcomes, Modules, and Textbooks.</p>
            </div>
          `;
          document.getElementById('coursePlannerContent').innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
              <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                <span class="material-symbols-rounded text-xl text-slate-600">event_note</span>
              </div>
              <p class="text-sm font-bold text-slate-400">Planner not generated.</p>
              <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to automatically generate the lesson plan.</p>
            </div>
          `;
          document.getElementById('formativeAssessmentContent').innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
              <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                <span class="material-symbols-rounded text-xl text-slate-600">assignment_turned_in</span>
              </div>
              <p class="text-sm font-bold text-slate-400">Formative Assessment Inactive.</p>
              <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to activate formative assessment tasks and mark entry.</p>
            </div>
          `;
          document.getElementById('summativeAssessmentContent').innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
              <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                <span class="material-symbols-rounded text-xl text-slate-600">quiz</span>
              </div>
              <p class="text-sm font-bold text-slate-400">Summative Assessment Inactive.</p>
              <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to activate written test configuration and mark entry.</p>
            </div>
          `;
          const qbContent = document.getElementById('questionBankContent');
          if (qbContent) {
              qbContent.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                  <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                    <span class="material-symbols-rounded text-xl text-slate-600">database</span>
                  </div>
                  <p class="text-sm font-bold text-slate-400">Question Bank Inactive.</p>
                  <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to activate the question bank pooling.</p>
                </div>
              `;
          }
        }
      });
    }

    function renderCoursePlanner(lessonPlans) {
      if (!lessonPlans || lessonPlans.length === 0) return;
      
      let totalHours = lessonPlans.reduce((sum, lp) => sum + (lp.allocated_hours || 0), 0);
      let html = `
        <div class="flex justify-between items-end mb-4">
          <div>
            <h4 class="text-[10px] font-black text-slate-200">Interactive Lesson Planner</h4>
            <p class="text-[10px] text-slate-500 mt-1">Set proposed dates and pedagogy. Remarks can be added after class completion.</p>
          </div>
          <div class="text-[10px] font-bold text-slate-400 bg-slate-900/50 px-3 py-1.5 rounded-lg border border-slate-800/50">
            Total Est. Hours: <span class="text-emerald-400 ml-1 text-[10px]">${totalHours}</span>
          </div>
        </div>
        
        <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner">
          <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                  <tr class="bg-slate-900/80 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800/60">
                    <th class="p-3 w-12 text-center">Day No</th>
                    <th class="p-3 w-32">Proposed Date</th>
                    <th class="p-3">Topic / Content</th>
                    <th class="p-3 text-center w-20">Hours</th>
                    <th class="p-3 w-32">Actual Date</th>
                    <th class="p-3 w-32">Pedagogy</th>
                    <th class="p-3 w-40">Remarks</th>
                  </tr>
                </thead>
                <tbody>
      `;

      lessonPlans.forEach((lp, index) => {
        let coBadge = lp.co_id ? `<span class="px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] ml-2">${lp.co_id}</span>` : '';
        let proposed = lp.proposed_date ? lp.proposed_date : '';
        let actual = lp.actual_date ? `<span class="text-emerald-400 font-mono">${lp.actual_date}</span>` : `<span class="text-slate-600 font-mono italic">Pending</span>`;
        let pedagogy = lp.pedagogy || 'Lecture';
        let remarks = lp.remarks || '';
        let dayNo = lp.day_no || (index + 1);
        
        html += `
          <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium text-[10px]">
            <td class="p-3 text-center font-bold text-slate-500">${dayNo}</td>
            <td class="p-3">
              <input type="date" value="${proposed}" class="w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 font-mono" onchange="updateProposedDate(${lp.id}, this.value)">
            </td>
            <td class="p-3 text-slate-300 leading-relaxed">${lp.topic_content} ${coBadge}</td>
            <td class="p-3 text-center font-mono text-slate-400 bg-slate-900/20">${lp.allocated_hours}</td>
            <td class="p-3">${actual}</td>
            <td class="p-3">
              <input type="text" value="${pedagogy}" class="w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50" placeholder="Lecture, Demo...">
            </td>
            <td class="p-3">
              <input type="text" value="${remarks}" class="w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50" placeholder="Add remarks...">
            </td>
          </tr>
        `;
      });

      html += `
                </tbody>
              </table>
          </div>
        </div>
      `;

      document.getElementById('coursePlannerContent').innerHTML = html;
    }

    function renderFormativeAssessment(students) {
      let html = `
        <div class="flex items-center justify-between mb-4">
          <div>
            <h4 class="text-sm font-black text-slate-200">Formative Assessment (Assignments)</h4>
            <p class="text-sm text-slate-500 mt-1">Generate AI questions for each CO and record 10-mark evaluations.</p>
          </div>
          <div class="flex items-center gap-2">
            <button onclick="printAssignmentReport('${currentSubjectId}')" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-2 shadow-lg shadow-blue-500/10 cursor-pointer">
              <span class="material-symbols-rounded text-sm">print</span> Print Assignment Report
            </button>
            <button onclick="generateAIQuestions('${currentSubjectId}')" class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-2 shadow-lg shadow-blue-900/20 cursor-pointer">
              <span class="material-symbols-rounded text-sm">smart_toy</span> AI Generate Questions
            </button>
          </div>
        </div>

        <div id="aiQuestionsContainer" class="hidden grid-cols-1 md:grid-cols-2 gap-4 mb-6"></div>

        <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner">
          <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 flex items-center justify-between">
            <div class="font-bold text-base text-slate-300 flex items-center gap-2 tracking-wide uppercase">
              <span class="material-symbols-rounded text-base text-emerald-400">edit_note</span> Enter Assignment Marks
            </div>
            <button onclick="saveAssignmentMarks('${currentSubjectId}')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-bold transition-premium cursor-pointer">
              Save Marks
            </button>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
              <thead>
                <tr class="bg-slate-900/40 text-sm font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800/60">
                  <th class="p-3 w-12">S.No.</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3 w-28">Admission No</th>
                  <th class="p-3 w-32">SBTE Reg No</th>
                  <th class="p-3 text-center w-20">CO1 (10)</th>
                  <th class="p-3 text-center w-20">CO2 (10)</th>
                  <th class="p-3 text-center w-20">CO3 (10)</th>
                  <th class="p-3 text-center w-20">CO4 (10)</th>
                </tr>
              </thead>
              <tbody id="markEntryTbody">
      `;

      if (students && students.length > 0) {
        students.forEach((student, index) => {
          let m = student.assignment_marks || {};
          html += `
            <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/40 transition-premium" data-reg="${student.reg_no}">
              <td class="px-4 py-4 text-slate-400 font-bold text-base">${index + 1}</td>
              <td class="px-4 py-4 font-bold text-slate-50 text-lg tracking-wide">${student.name}</td>
              <td class="px-4 py-4 font-mono text-slate-200 text-base">${student.reg_no}</td>
              <td class="px-4 py-4 font-mono text-slate-200 text-base">${student.sbte_reg_no || '-'}</td>
              <td class="px-3 py-3"><input type="number" step="1" max="10" min="0" value="${m.CO1 !== null ? Math.round(m.CO1) : ''}" class="co-mark w-full bg-slate-900 border border-slate-600/70 rounded-lg px-2 py-2 text-slate-100 text-base font-bold focus:outline-none focus:border-blue-400 text-center" data-co="CO1"></td>
              <td class="px-3 py-3"><input type="number" step="1" max="10" min="0" value="${m.CO2 !== null ? Math.round(m.CO2) : ''}" class="co-mark w-full bg-slate-900 border border-slate-600/70 rounded-lg px-2 py-2 text-slate-100 text-base font-bold focus:outline-none focus:border-blue-400 text-center" data-co="CO2"></td>
              <td class="px-3 py-3"><input type="number" step="1" max="10" min="0" value="${m.CO3 !== null ? Math.round(m.CO3) : ''}" class="co-mark w-full bg-slate-900 border border-slate-600/70 rounded-lg px-2 py-2 text-slate-100 text-base font-bold focus:outline-none focus:border-blue-400 text-center" data-co="CO3"></td>
              <td class="px-3 py-3"><input type="number" step="1" max="10" min="0" value="${m.CO4 !== null ? Math.round(m.CO4) : ''}" class="co-mark w-full bg-slate-900 border border-slate-600/70 rounded-lg px-2 py-2 text-slate-100 text-base font-bold focus:outline-none focus:border-blue-400 text-center" data-co="CO4"></td>
            </tr>
          `;
        });
      } else {
        html += `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-sm font-bold">No students found in this classroom.</td></tr>`;
      }

      html += `</tbody></table></div></div>`;
      document.getElementById('formativeAssessmentContent').innerHTML = html;
    }

    function renderAIQuestionsList(questionsData, subjectId) {
      document.getElementById('aiQuestionsContainer').classList.remove('hidden');
      let html = '';
      for (const [co, qs] of Object.entries(questionsData)) {
        let qList = qs.map(q => {
          let cog = '';
          let lower = q.toLowerCase();
          if (!lower.includes('[remember]') && !lower.includes('[u]') && !lower.includes('[a]') && !lower.includes('[r]') && !lower.includes('cognitive')) {
            if (lower.includes('define') || lower.includes('list') || lower.includes('what is') || lower.includes('state') || lower.includes('name')) {
              cog = ' <span class="text-blue-400 font-bold">[Remember - R]</span>';
            } else if (lower.includes('design') || lower.includes('solve') || lower.includes('calculate') || lower.includes('write') || lower.includes('implement') || lower.includes('apply') || lower.includes('draw')) {
              cog = ' <span class="text-emerald-400 font-bold">[Apply - A]</span>';
            } else {
              cog = ' <span class="text-indigo-400 font-bold">[Understand - U]</span>';
            }
          }
          return `<li class="text-sm text-slate-300 mb-2 leading-relaxed font-medium">${q}${cog}</li>`;
        }).join('');
        let schedule = currentDeadlines[co] || { start: '', due: '', locked: false };
        if (typeof schedule === 'string') schedule = { start: '', due: schedule, locked: false }; // Legacy fallback
        
        let isLocked = schedule.locked;
        let lockStr = isLocked ? `<span class="material-symbols-rounded text-[10px] text-amber-500 ml-1" title="Locked">lock</span>` : '';
        let disabledAttr = isLocked ? 'disabled' : '';
        let regenBtn = isLocked ? '' : `
                <button onclick="generateAIQuestions('${subjectId}', '${co}', 'ai')" class="p-1 rounded-lg bg-slate-800 hover:bg-blue-600 text-slate-400 hover:text-white transition-premium cursor-pointer" title="Generate via AI (Gemini)">
                  <span class="material-symbols-rounded text-[14px] block">auto_awesome</span>
                </button>
                <button onclick="generateAIQuestions('${subjectId}', '${co}', 'bank')" class="p-1 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-400 hover:text-white transition-premium cursor-pointer" title="Pull from Question Bank Pool">
                  <span class="material-symbols-rounded text-[14px] block">database</span>
                </button>
        `;
        let lockBtn = isLocked ? '' : `
                <button onclick="toggleAssignmentLock('${subjectId}', '${co}')" class="p-1 rounded-lg bg-slate-800 hover:bg-amber-600 text-slate-400 hover:text-white transition-premium cursor-pointer" title="Lock & Finalize">
                  <span class="material-symbols-rounded text-[14px] block">lock</span>
                </button>
        `;

        html += `
          <div class="bg-slate-900/50 border border-slate-800/60 p-4 rounded-xl relative overflow-hidden group ${isLocked ? 'ring-1 ring-amber-500/30' : ''}">
            <div class="absolute inset-0 bg-blue-500/5 opacity-0 group-hover:opacity-100 transition-premium pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-3 border-b border-slate-800/60 pb-2 relative z-10">
              <h5 class="text-[10px] font-black text-blue-400 flex items-center gap-1">
                <span class="px-1.5 py-0.5 rounded bg-blue-500/10 border border-blue-500/20 text-[10px] mr-1">${co}</span> Assignment ${lockStr}
              </h5>
              <div class="flex items-center gap-2">
                <div class="flex items-center gap-1 bg-slate-950/80 px-2 py-1 rounded border border-slate-700/50">
                  <span class="text-[10px] text-slate-500 font-bold uppercase">Start</span>
                  <input type="date" value="${schedule.start || ''}" ${disabledAttr} class="bg-transparent text-[10px] text-slate-300 font-mono outline-none w-20" onchange="updateAssignmentSchedule('${subjectId}', '${co}', 'start', this.value)">
                </div>
                <div class="flex items-center gap-1 bg-slate-950/80 px-2 py-1 rounded border border-slate-700/50">
                  <span class="text-[10px] text-slate-500 font-bold uppercase">Due</span>
                  <input type="date" value="${schedule.due || ''}" ${disabledAttr} class="bg-transparent text-[10px] text-slate-300 font-mono outline-none w-20" onchange="updateAssignmentSchedule('${subjectId}', '${co}', 'due', this.value)">
                </div>
                ${regenBtn}
                ${lockBtn}
              </div>
            </div>
            
            <ul id="questions-list-${co}" class="list-none m-0 p-0 relative z-10 min-h-[60px]">${qList}</ul>
          </div>
        `;
      }
      document.getElementById('aiQuestionsContainer').innerHTML = html;
    }

    function generateAIQuestions(subjectId, coTag = null, mode = 'ai') {
      if (!coTag) {
        document.getElementById('aiQuestionsContainer').classList.remove('hidden');
        document.getElementById('aiQuestionsContainer').innerHTML = `<div class="col-span-full text-center py-4 text-sm font-bold text-blue-400 animate-pulse">Generating questions...</div>`;
      } else {
        const ul = document.getElementById(`questions-list-${coTag}`);
        if(ul) ul.innerHTML = `<li class="text-sm text-blue-400 animate-pulse">Retrieving questions...</li>`;
      }
      
      let url = `/api/classroom/${subjectId}/generate-questions?_t=${Date.now()}&generation_mode=${mode}`;
      if (coTag) url += `&co_tag=${coTag}`;

      fetch(url)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          if (!coTag) {
             currentQuestions = data.data;
             renderAIQuestionsList(currentQuestions, subjectId);
          } else {
             currentQuestions[coTag] = data.data[coTag];
             const ul = document.getElementById(`questions-list-${coTag}`);
             if (ul && data.data[coTag]) {
               ul.innerHTML = data.data[coTag].map(q => `<li class="text-sm text-slate-400 mb-1 leading-relaxed">${q}</li>`).join('');
             }
          }
        } else {
           alert(data.message || 'Failed to retrieve questions.');
           loadCourseDetails(subjectId);
        }
      });
    }

    function updateAssignmentSchedule(subjectId, coTag, type, dateValue) {
      let payload = { co_tag: coTag };
      if (type === 'start') payload.start_date = dateValue;
      if (type === 'due') payload.due_date = dateValue;
      
      fetch(`/api/classroom/${subjectId}/save-assignment-deadline`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
           if(!currentDeadlines[coTag] || typeof currentDeadlines[coTag] === 'string') currentDeadlines[coTag] = {start:'', due:'', locked:false};
           if (type === 'start') currentDeadlines[coTag].start = dateValue;
           if (type === 'due') currentDeadlines[coTag].due = dateValue;
           console.log(`Schedule for ${coTag} updated.`);
        } else {
           alert(data.message);
        }
      });
    }

    function toggleAssignmentLock(subjectId, coTag) {
      if(!confirm(`Are you sure you want to lock ${coTag} questions? This cannot be easily undone.`)) return;
      
      fetch(`/api/classroom/${subjectId}/save-assignment-deadline`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, is_locked: true })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
           if(!currentDeadlines[coTag] || typeof currentDeadlines[coTag] === 'string') currentDeadlines[coTag] = {start:'', due:'', locked:false};
           currentDeadlines[coTag].locked = true;
           renderAIQuestionsList(currentQuestions, subjectId);
        } else {
           alert(data.message);
        }
      });
    }

    function saveAssignmentMarks(subjectId) {
      let marksPayload = [];
      const rows = document.querySelectorAll('#markEntryTbody tr[data-reg]');
      rows.forEach(row => {
        const regNo = row.getAttribute('data-reg');
        const inputs = row.querySelectorAll('.co-mark');
        inputs.forEach(input => {
          if (input.value !== '') {
            marksPayload.push({
              reg_no: regNo,
              co_tag: input.getAttribute('data-co'),
              marks_obtained: input.value
            });
          }
        });
      });

      if (marksPayload.length === 0) {
        alert("No marks entered.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/save-assignment-marks`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ marks: marksPayload })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') alert("Marks successfully saved!");
        else alert(data.message || "Failed to save marks.");
      });
    }

    function updateProposedDate(lessonPlanId, dateValue) {
        console.log("Updating lesson plan", lessonPlanId, "with date", dateValue);
    }

    function renderCourseStructure(cos, modules, textbooks, copo) {
      document.getElementById('parseStatusBadge').innerText = 'Parsed Successfully';
      document.getElementById('parseStatusBadge').className = 'text-[10px] font-bold px-2.5 py-1 rounded-md bg-emerald-900/30 text-emerald-400 border border-emerald-500/30';
      
      let html = '';

      if (cos && cos.length > 0) {
        let cosList = cos.map(co => `
          <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium text-[10px]">
            <td class="p-3 font-bold text-blue-400 whitespace-nowrap">${co.id}</td>
            <td class="p-3 text-slate-300 leading-relaxed">${co.description}</td>
            <td class="p-3 text-center text-slate-400">${co.duration ? co.duration + ' hrs' : '-'}</td>
            <td class="p-3 text-emerald-400 font-mono">${co.cognitive_level || '-'}</td>
          </tr>
        `).join('');
        html += `
          <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner mb-6">
            <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 font-bold text-[10px] text-slate-400 flex items-center gap-2 tracking-wider uppercase">
              <span class="material-symbols-rounded text-[10px] text-sky-400">target</span> Course Outcomes
            </div>
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-900/40 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-800/60">
                  <th class="p-3 w-16">CO</th>
                  <th class="p-3">Description</th>
                  <th class="p-3 text-center w-20">Duration</th>
                  <th class="p-3 w-32">Cognitive Level</th>
                </tr>
              </thead>
              <tbody>
                ${cosList}
              </tbody>
            </table>
          </div>
        `;
      }

      if (copo && Object.keys(copo).length > 0) {
        let copoList = Object.keys(copo).map(coKey => {
            let mapping = copo[coKey];
            let poCells = '';
            for(let i = 1; i <= 12; i++) {
                let val = mapping['PO' + i] || '-';
                poCells += `<td class="p-2 text-center text-slate-400 ${val !== '-' ? 'font-bold text-emerald-400' : ''}">${val}</td>`;
            }
            return `
              <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium text-[10px]">
                <td class="p-2 font-bold text-blue-400 whitespace-nowrap border-r border-slate-800/60">${coKey}</td>
                ${poCells}
              </tr>
            `;
        }).join('');
        
        let poHeaders = '';
        for(let i=1; i<=12; i++) {
            poHeaders += `<th class="p-2 text-center">PO${i}</th>`;
        }

        html += `
          <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner mb-6">
            <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 font-bold text-[10px] text-slate-400 flex items-center gap-2 tracking-wider uppercase">
              <span class="material-symbols-rounded text-[10px] text-amber-400">grid_on</span> CO-PO Mapping Matrix
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                  <thead>
                    <tr class="bg-slate-900/40 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-800/60">
                      <th class="p-2 w-16 border-r border-slate-800/60">CO</th>
                      ${poHeaders}
                    </tr>
                  </thead>
                  <tbody>
                    ${copoList}
                  </tbody>
                </table>
            </div>
          </div>
        `;
      }

      if (html === '') {
        html = `<div class="p-6 text-center text-[10px] text-slate-500 border border-dashed border-slate-700/50 rounded-xl">Could not extract structured data. The syllabus might have an unparseable format.</div>`;
      }

      document.getElementById('courseStructureContent').innerHTML = html;
    }

    function renderSummativeAssessment(cos, students) {
      let html = `
        <div class="flex items-center justify-between mb-4 no-print">
          <div>
            <h4 class="text-[10px] font-black text-slate-200">Summative Assessment (Manual Tests)</h4>
            <p class="text-[10px] text-slate-500 mt-1">Configure and generate precise Cognitive Level based question papers for each CO.</p>
          </div>
        </div>
      `;

      // Build the marks entry table FIRST so it's at the top
      let marksEntryHtml = `
        <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner no-print mb-6">
          <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 flex items-center justify-between cursor-pointer hover:bg-slate-800/80 transition-premium" onclick="document.getElementById('manualMarksWrapper').classList.toggle('hidden'); document.getElementById('marksToggleIcon').innerText = document.getElementById('manualMarksWrapper').classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <div class="font-bold text-sm text-slate-400 flex items-center gap-2 tracking-wider uppercase">
              <span class="material-symbols-rounded text-sm text-emerald-400">edit_document</span> Enter Manual Marks
              <span id="marksToggleIcon" class="material-symbols-rounded text-sm text-slate-500">expand_more</span>
            </div>
            <div class="flex items-center gap-2">
              <button onclick="event.stopPropagation(); printSummativeReport('${currentSubjectId}')" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer">
                Print Written Report
              </button>
              <button onclick="event.stopPropagation(); saveSummativeMarks('${currentSubjectId}')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer">
                Save Written Marks
              </button>
            </div>
          </div>
          <div id="manualMarksWrapper" class="hidden overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
              <thead>
                <tr class="bg-slate-900/40 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800/60">
                  <th class="p-3 w-12">S.No.</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3 w-28">Admission No</th>
                  <th class="p-3 w-32">SBTE Reg No</th>
                  <th class="p-3 text-center w-20">CO1</th>
                  <th class="p-3 text-center w-20">CO2</th>
                  <th class="p-3 text-center w-20">CO3</th>
                  <th class="p-3 text-center w-20">CO4</th>
                </tr>
              </thead>
              <tbody id="summativeMarkEntryTbody">
      `;

      if (students && students.length > 0) {
        students.forEach((student, index) => {
          let sm = student.summative_marks || {};
          marksEntryHtml += `
            <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium text-sm" data-reg="${student.reg_no}">
              <td class="p-3 text-slate-400 font-bold">${index + 1}</td>
              <td class="p-3 font-bold text-slate-200">${student.name}</td>
              <td class="p-3 font-mono text-slate-400">${student.reg_no}</td>
              <td class="p-3 font-mono text-slate-400">${student.sbte_reg_no || '-'}</td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO1 !== null ? Math.round(sm.CO1) : ''}" placeholder="-" class="summ-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-sm focus:outline-none focus:border-blue-500/50 text-center" data-co="CO1"></td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO2 !== null ? Math.round(sm.CO2) : ''}" placeholder="-" class="summ-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-sm focus:outline-none focus:border-blue-500/50 text-center" data-co="CO2"></td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO3 !== null ? Math.round(sm.CO3) : ''}" placeholder="-" class="summ-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-sm focus:outline-none focus:border-blue-500/50 text-center" data-co="CO3"></td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO4 !== null ? Math.round(sm.CO4) : ''}" placeholder="-" class="summ-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-sm focus:outline-none focus:border-blue-500/50 text-center" data-co="CO4"></td>
            </tr>
          `;
        });
      } else {
        marksEntryHtml += `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-sm font-bold">No students found.</td></tr>`;
      }
      marksEntryHtml += `</tbody></table></div></div>`;

      html += marksEntryHtml;

      html += `
        <div id="summativePapersContainer" class="flex flex-col gap-6 mb-6 no-print">
      `;

      if (cos && cos.length > 0) {
        cos.forEach(co => {
          let testData = currentSummativeTests[co.id] || null;
          let generatedContent = '';
          
          if (testData) {
            let partAStr = testData.part_a ? testData.part_a.questions.map(q => `<li class="mb-1.5"><span class="font-mono text-sm text-emerald-400 mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-sm text-slate-500">(${q.marks})</span></li>`).join('') : '';
            let partBStr = testData.part_b ? testData.part_b.questions.map(q => `<li class="mb-1.5"><span class="font-mono text-sm text-emerald-400 mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-sm text-slate-500">(${q.marks})</span></li>`).join('') : '';
            let partCStr = testData.part_c ? testData.part_c.questions.map(q => `<li class="mb-1.5"><span class="font-mono text-sm text-emerald-400 mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-sm text-slate-500">(${q.marks})</span></li>`).join('') : '';

            generatedContent = `
              <div class="mt-4 pt-4 border-t border-slate-800/60" id="paper-${co.id}">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-sm font-bold text-emerald-400 uppercase tracking-widest">Generated Question Paper</span>
                  <div class="flex items-center gap-2">
                    <button onclick="printSummativePaper('${co.id}', ${testData.total_marks})" class="flex items-center gap-1.5 text-sm bg-blue-700/30 hover:bg-blue-600 border border-blue-600/40 px-3 py-1.5 rounded-lg text-blue-300 hover:text-white transition-premium cursor-pointer">
                      <span class="material-symbols-rounded text-base">print</span> Print Q Paper
                    </button>
                    <button onclick="printAnswerKey('${co.id}', ${testData.total_marks})" class="flex items-center gap-1.5 text-sm bg-amber-700/30 hover:bg-amber-600 border border-amber-600/40 px-3 py-1.5 rounded-lg text-amber-300 hover:text-white transition-premium cursor-pointer">
                      <span class="material-symbols-rounded text-base">assignment</span> Print Answer Key
                    </button>
                  </div>
                </div>
                <div class="text-sm text-slate-300 bg-slate-950/50 p-4 rounded-lg border border-slate-800/40">
                  ${partAStr ? `<div class="font-bold mb-1.5 text-slate-400">PART A (Short Answers)</div><ul class="list-decimal pl-5 mb-4">${partAStr}</ul>` : ''}
                  ${partBStr ? `<div class="font-bold mb-1.5 text-slate-400">PART B (Medium Answers)</div><ul class="list-decimal pl-5 mb-4">${partBStr}</ul>` : ''}
                  ${partCStr ? `<div class="font-bold mb-1.5 text-slate-400">PART C (Long Answers)</div><ul class="list-decimal pl-5 mb-2">${partCStr}</ul>` : ''}
                </div>
              </div>
            `;
          }

          let isLocked = testData && testData.is_locked ? true : false;
          let disabledAttr = isLocked ? 'disabled' : '';
          let lockStr = isLocked ? `<span class="material-symbols-rounded text-sm text-amber-500 ml-1" title="Locked">lock</span>` : '';
          let dateStr = testData && testData.date_of_exam ? testData.date_of_exam : '';

          let qA = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qA : (testData?.part_a?.q_count || '');
          let mA = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mA : (testData?.part_a?.marks_per_q || '');
          let qB = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qB : (testData?.part_b?.q_count || '');
          let mB = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mB : (testData?.part_b?.marks_per_q || '');
          let qC = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qC : (testData?.part_c?.q_count || '');
          let mC = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mC : (testData?.part_c?.marks_per_q || '');

          let lockBtn = isLocked || !testData ? '' : `
            <button onclick="lockSummativeTest('${currentSubjectId}', '${co.id}')" class="p-1.5 rounded-lg bg-slate-800 hover:bg-amber-600 text-slate-400 hover:text-white transition-premium cursor-pointer" title="Lock & Finalize">
              <span class="material-symbols-rounded text-base block">lock</span>
            </button>
          `;

          let genBtn = isLocked ? '' : `
              <button id="gen_btn_${co.id}" onclick="generateSummativePaper('${currentSubjectId}', '${co.id}')" class="w-full py-2.5 bg-blue-600/20 hover:bg-blue-600 border border-blue-500/30 text-blue-400 hover:text-white rounded-xl text-sm font-bold transition-premium mt-3 cursor-pointer">
                ${testData ? 'Regenerate Question Paper' : 'Generate AI Question Paper'}
              </button>
          `;
          
          let dateInputStr = `
            <div class="flex items-center gap-1.5 bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-700/80 shadow-inner">
              <span class="text-sm text-slate-400 font-bold uppercase flex items-center gap-1"><span class="material-symbols-rounded text-sm">calendar_today</span>Date</span>
              <input type="date" id="summ_date_${co.id}" value="${dateStr}" ${disabledAttr} onchange="saveSummativeConfig('${currentSubjectId}', '${co.id}')" class="bg-slate-900 text-sm text-slate-200 font-mono outline-none w-[110px] px-2 py-0.5 rounded border border-slate-700 focus:border-blue-500">
            </div>
          `;

          html += `
            <div id="summ_card_${co.id}" class="bg-slate-900/50 border border-slate-800/60 p-5 rounded-xl relative ${isLocked ? 'ring-1 ring-amber-500/30' : ''}">
              <div class="flex items-center justify-between mb-4 border-b border-slate-800/60 pb-3 cursor-pointer hover:opacity-80 transition-premium" onclick="document.getElementById('co_body_${co.id}').classList.toggle('hidden'); document.getElementById('co_icon_${co.id}').innerText = document.getElementById('co_body_${co.id}').classList.contains('hidden') ? 'expand_more' : 'expand_less';">
                <h5 class="text-sm font-black text-blue-400 flex items-center gap-1">
                  <span id="co_icon_${co.id}" class="material-symbols-rounded text-sm text-slate-500">expand_more</span>
                  ${co.id} Written Test ${lockStr}
                </h5>
                <div class="flex items-center gap-2" onclick="event.stopPropagation()">
                  ${dateInputStr}
                  ${lockBtn}
                </div>
              </div>
 
              <div id="co_body_${co.id}" class="hidden pt-2">
 
              <div class="flex items-center gap-4 mb-4 mt-1 text-sm font-bold text-slate-400 bg-slate-950/50 p-2 rounded-lg border border-slate-800/40 w-max">
                 <label class="flex items-center gap-1.5 cursor-pointer hover:text-blue-400 transition-premium">
                   <input type="radio" name="summ_mode_${co.id}" value="ai" ${(!testData || !testData.manual_mode) ? 'checked' : ''} onchange="toggleSummativeMode('${co.id}')" class="text-blue-500 focus:ring-blue-500 bg-slate-900 border-slate-700" ${disabledAttr}>
                   AI Generation
                 </label>
                 <label class="flex items-center gap-1.5 cursor-pointer hover:text-emerald-400 transition-premium">
                   <input type="radio" name="summ_mode_${co.id}" value="manual" ${(testData && testData.manual_mode) ? 'checked' : ''} onchange="toggleSummativeMode('${co.id}')" class="text-emerald-500 focus:ring-emerald-500 bg-slate-900 border-slate-700" ${disabledAttr}>
                   Manual Entry
                 </label>
              </div>
              
              <div class="space-y-3 mb-4">
                <div class="flex items-center gap-3 text-sm text-slate-400 font-bold mb-1"><span class="w-24 shrink-0 whitespace-nowrap">Part</span><span class="flex-1 text-center">Q. Count</span><span class="w-4"></span><span class="flex-1 text-center">Marks/Q</span></div>
                <div class="flex items-center justify-between gap-3">
                  <span class="text-sm text-slate-400 font-bold w-24 shrink-0 whitespace-nowrap">PART A</span>
                  <input type="number" id="summ_q_A_${co.id}" value="${qA}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded-lg px-3 py-1.5 text-sm text-slate-200 outline-none focus:border-blue-500">
                  <span class="text-slate-600 text-sm font-bold">x</span>
                  <input type="number" id="summ_m_A_${co.id}" value="${mA}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded-lg px-3 py-1.5 text-sm text-slate-200 outline-none focus:border-blue-500">
                </div>
                <div class="flex items-center justify-between gap-3">
                  <span class="text-sm text-slate-400 font-bold w-24 shrink-0 whitespace-nowrap">PART B</span>
                  <input type="number" id="summ_q_B_${co.id}" value="${qB}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded-lg px-3 py-1.5 text-sm text-slate-200 outline-none focus:border-blue-500">
                  <span class="text-slate-600 text-sm font-bold">x</span>
                  <input type="number" id="summ_m_B_${co.id}" value="${mB}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded-lg px-3 py-1.5 text-sm text-slate-200 outline-none focus:border-blue-500">
                </div>
                <div class="flex items-center justify-between gap-3">
                  <span class="text-sm text-slate-400 font-bold w-24 shrink-0 whitespace-nowrap">PART C</span>
                  <input type="number" id="summ_q_C_${co.id}" value="${qC}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded-lg px-3 py-1.5 text-sm text-slate-200 outline-none focus:border-blue-500">
                  <span class="text-slate-600 text-sm font-bold">x</span>
                  <input type="number" id="summ_m_C_${co.id}" value="${mC}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded-lg px-3 py-1.5 text-sm text-slate-200 outline-none focus:border-blue-500">
                </div>
              </div>

              <div class="flex items-center justify-between mb-4 border-t border-slate-800/40 pt-3">
                <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-400 hover:text-slate-200 transition-premium">
                  <input type="checkbox" id="sync_pattern_${co.id}" ${disabledAttr} onchange="if(this.checked) applySummativePatternToAll('${co.id}')" class="rounded border-slate-700 bg-slate-900 text-blue-500 focus:ring-blue-500/30">
                  <span>Apply pattern to all COs</span>
                </label>
                <div class="text-sm font-bold text-slate-300 bg-slate-800/50 px-3 py-1 rounded-lg border border-slate-700/50">
                  Total Marks: <span id="summ_total_${co.id}" class="${testData ? 'text-emerald-400' : 'text-blue-400'}">${testData ? testData.total_marks : '0'}</span>
                </div>
              </div>
              
              ${genBtn}

              <div id="manual_form_wrapper_${co.id}"></div>

              ${generatedContent}
              </div> <!-- close co_body -->
            </div>
          `;
        });
      }

      html += `</div>`;

      // Online MCQ Test Setup (Collapsible)
      let onlineTestHtml = `
        <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner no-print mb-6">
          <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 flex items-center justify-between cursor-pointer hover:bg-slate-800/80 transition-premium" onclick="document.getElementById('onlineTestWrapper').classList.toggle('hidden'); document.getElementById('onlineTestIcon').innerText = document.getElementById('onlineTestWrapper').classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <div class="font-bold text-[10px] text-slate-400 flex items-center gap-2 tracking-wider uppercase">
              <span class="material-symbols-rounded text-[10px] text-purple-400">devices</span> Online MCQ Tests Setup
              <span id="onlineTestIcon" class="material-symbols-rounded text-[10px] text-slate-500">expand_more</span>
            </div>
          </div>
          <div id="onlineTestWrapper" class="hidden p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <!-- Configuration Form -->
              <div class="col-span-2 bg-slate-900/50 p-4 rounded-lg border border-slate-800/50">
                <h5 class="text-[10px] font-bold text-slate-300 mb-3 border-b border-slate-800/60 pb-2">Publish New Online Test</h5>
                <div class="grid grid-cols-2 gap-3 mb-3">
                  <div>
                    <label class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Target COs (Multiple)</label>
                    <select id="online_test_cos" multiple class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500 h-[96px]">
                      ${cos ? cos.map(co => `<option value="${co.id}">${co.id}</option>`).join('') : ''}
                    </select>
                  </div>
                  <div>
                    <label class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Max Attempts</label>
                    <input type="number" id="online_test_attempts" value="1" min="1" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">
                    <label class="block text-[10px] text-slate-500 font-bold mt-2 mb-1 uppercase">Duration (Minutes)</label>
                    <input type="number" id="online_test_duration" value="30" min="5" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">
                  </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                  <div>
                    <label class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Number of Questions</label>
                    <input type="number" id="online_test_q_count" value="10" min="1" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">
                  </div>
                  <div>
                    <label class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Generation Mode</label>
                    <select id="online_test_gen_mode" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">
                      <option value="bank">Mode B: Question Bank Pool</option>
                      <option value="ai">Mode A: AI Generator (Gemini)</option>
                    </select>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-4">
                  <div>
                    <label class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Start Time</label>
                    <input type="text" id="online_test_start" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500" placeholder="Select Date & Time">
                  </div>
                  <div>
                    <label class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">End Time (Deadline)</label>
                    <input type="text" id="online_test_end" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500" placeholder="Select Date & Time">
                  </div>
                </div>
                
                <div class="mb-4">
                  <label class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Custom Test ID/Name (Optional)</label>
                  <input type="text" id="online_test_name" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500" placeholder="e.g. Midterm Test 1">
                </div>
                <button onclick="publishOnlineTest('${currentSubjectId}')" class="w-full py-2 bg-purple-600/80 hover:bg-purple-500 text-white rounded-lg text-[10px] font-bold transition-premium flex items-center justify-center gap-2">
                  <span class="material-symbols-rounded text-[10px]">rocket_launch</span> Generate & Publish to Students
                </button>
              </div>
              
              <!-- Active Tests Dashboard -->
              <div class="bg-slate-900/50 p-4 rounded-lg border border-slate-800/50">
                <h5 class="text-[10px] font-bold text-slate-300 mb-3 border-b border-slate-800/60 pb-2">Active Online Tests</h5>
                <div id="activeOnlineTestsList" class="space-y-2 text-[10px] text-slate-400">
                   <div class="p-3 bg-slate-950 border border-slate-800 rounded text-center border-dashed">No active online tests found.</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;

      html += onlineTestHtml;

      html += `
        <div id="printableExamArea" class="hidden no-print"></div>
      `;

      document.getElementById('summativeAssessmentContent').innerHTML = html;

      // Automatically spawn manual fields for any saved manual papers on load
      if (cos && cos.length > 0) {
         cos.forEach(co => {
             let testData = currentSummativeTests[co.id] || null;
             if (testData && testData.manual_mode) {
                 spawnManualFields(co.id);
                 // Adjust button styling to show it's manual save
                 const btn = document.getElementById(`gen_btn_${co.id}`);
                 if (btn) {
                     btn.innerText = 'Save Custom Questions';
                     btn.classList.replace('bg-blue-600/20', 'bg-emerald-600/20');
                     btn.classList.replace('hover:bg-blue-600', 'hover:bg-emerald-600');
                     btn.classList.replace('border-blue-500/30', 'border-emerald-500/30');
                     btn.classList.replace('text-blue-400', 'text-emerald-400');
                 }
             }
         });
      }

      // Initialize Flatpickr
      if (typeof flatpickr !== 'undefined') {
        flatpickr("#online_test_start", { 
          enableTime: true, 
          dateFormat: "Y-m-d H:i", 
          time_24hr: false, 
          minDate: "today" 
        });
        flatpickr("#online_test_end", { 
          enableTime: true, 
          dateFormat: "Y-m-d H:i", 
          time_24hr: false, 
          minDate: "today" 
        });
      }
    }

    function syncSummativeInputs(sourceCoId) {
      calcSummativeTotal(sourceCoId);
      if(document.getElementById(`sync_pattern_${sourceCoId}`)?.checked) {
         applySummativePatternToAll(sourceCoId);
         
         // Trigger spawn for all COs in manual mode
         document.querySelectorAll('[id^="summ_card_"]').forEach(card => {
             const coId = card.id.replace('summ_card_', '');
             const isManual = document.querySelector(`input[name="summ_mode_${coId}"]:checked`)?.value === 'manual';
             if (isManual) {
                 spawnManualFields(coId);
             }
         });
      } else {
         const isManual = document.querySelector(`input[name="summ_mode_${sourceCoId}"]:checked`)?.value === 'manual';
         if (isManual) {
             spawnManualFields(sourceCoId);
         }
      }
    }

    function calcSummativeTotal(coId) {
      let total = 0;
      ['A', 'B', 'C'].forEach(p => {
        let q = parseInt(document.getElementById(`summ_q_${p}_${coId}`).value) || 0;
        let m = parseInt(document.getElementById(`summ_m_${p}_${coId}`).value) || 0;
        total += (q * m);
      });
      const tEl = document.getElementById(`summ_total_${coId}`);
      if (tEl) {
        tEl.innerText = total;
        tEl.classList.remove('text-emerald-400');
        tEl.classList.add('text-blue-400');
      }
    }

    function applySummativePatternToAll(sourceCoId) {
      const qA = document.getElementById(`summ_q_A_${sourceCoId}`).value;
      const mA = document.getElementById(`summ_m_A_${sourceCoId}`).value;
      const qB = document.getElementById(`summ_q_B_${sourceCoId}`).value;
      const mB = document.getElementById(`summ_m_B_${sourceCoId}`).value;
      const qC = document.getElementById(`summ_q_C_${sourceCoId}`).value;
      const mC = document.getElementById(`summ_m_C_${sourceCoId}`).value;

      document.querySelectorAll('[id^="summ_q_A_"]').forEach(el => { if(el.id !== `summ_q_A_${sourceCoId}`) el.value = qA; });
      document.querySelectorAll('[id^="summ_m_A_"]').forEach(el => { if(el.id !== `summ_m_A_${sourceCoId}`) el.value = mA; });
      document.querySelectorAll('[id^="summ_q_B_"]').forEach(el => { if(el.id !== `summ_q_B_${sourceCoId}`) el.value = qB; });
      document.querySelectorAll('[id^="summ_m_B_"]').forEach(el => { if(el.id !== `summ_m_B_${sourceCoId}`) el.value = mB; });
      document.querySelectorAll('[id^="summ_q_C_"]').forEach(el => { if(el.id !== `summ_q_C_${sourceCoId}`) el.value = qC; });
      document.querySelectorAll('[id^="summ_m_C_"]').forEach(el => { if(el.id !== `summ_m_C_${sourceCoId}`) el.value = mC; });

      // Uncheck all other checkboxes to avoid conflict
      document.querySelectorAll('[id^="sync_pattern_"]').forEach(el => {
         if(el.id !== `sync_pattern_${sourceCoId}`) el.checked = false;
         
         // Trigger recalculation on all modified cards
         let c_id = el.id.replace('sync_pattern_', '');
         calcSummativeTotal(c_id);
      });
    }

    function saveSummativeConfig(subjectId, coTag) {
      let dateValue = document.getElementById(`summ_date_${coTag}`).value;
      fetch(`/api/classroom/${subjectId}/save-summative-config`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, date_of_exam: dateValue })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') console.log('Saved date');
      });
    }

    function lockSummativeTest(subjectId, coTag) {
      if(!confirm(`Are you sure you want to lock ${coTag} test? This cannot be easily undone.`)) return;
      fetch(`/api/classroom/${subjectId}/save-summative-config`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, is_locked: true })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') loadCourseDetails(subjectId);
        else alert(data.message);
      });
    }

    let tempSummativePatterns = {};

    function saveSummativePatterns() {
       document.querySelectorAll('[id^="summ_q_A_"]').forEach(el => {
          let coTag = el.id.replace('summ_q_A_', '');
          tempSummativePatterns[coTag] = {
             qA: document.getElementById(`summ_q_A_${coTag}`)?.value || '',
             mA: document.getElementById(`summ_m_A_${coTag}`)?.value || '',
             qB: document.getElementById(`summ_q_B_${coTag}`)?.value || '',
             mB: document.getElementById(`summ_m_B_${coTag}`)?.value || '',
             qC: document.getElementById(`summ_q_C_${coTag}`)?.value || '',
             mC: document.getElementById(`summ_m_C_${coTag}`)?.value || '',
          };
       });
    }

    function toggleSummativeMode(coId) {
       const isManual = document.querySelector(`input[name="summ_mode_${coId}"]:checked`).value === 'manual';
       const btn = document.getElementById(`gen_btn_${coId}`);
       if(btn) {
          if(isManual) {
             btn.innerText = 'Save Custom Questions';
             btn.classList.replace('bg-blue-600/20', 'bg-emerald-600/20');
             btn.classList.replace('hover:bg-blue-600', 'hover:bg-emerald-600');
             btn.classList.replace('border-blue-500/30', 'border-emerald-500/30');
             btn.classList.replace('text-blue-400', 'text-emerald-400');
             
             // Instantly spawn manual question fields
             spawnManualFields(coId);
          } else {
             btn.innerText = 'Generate AI Question Paper';
             btn.classList.replace('bg-emerald-600/20', 'bg-blue-600/20');
             btn.classList.replace('hover:bg-emerald-600', 'hover:bg-blue-600');
             btn.classList.replace('border-emerald-500/30', 'border-blue-500/30');
             btn.classList.replace('text-emerald-400', 'text-blue-400');

             // Remove manual entry fields if switching back to AI
             const wrapper = document.getElementById(`manual_form_wrapper_${coId}`);
             if (wrapper) {
                 wrapper.innerHTML = '';
             }
          }
       }
    }

    function spawnManualFields(coTag) {
      let qA = parseInt(document.getElementById(`summ_q_A_${coTag}`).value) || 0;
      let qB = parseInt(document.getElementById(`summ_q_B_${coTag}`).value) || 0;
      let qC = parseInt(document.getElementById(`summ_q_C_${coTag}`).value) || 0;

      let testData = currentSummativeTests[coTag] || null;
      let savedA = (testData && testData.manual_mode) ? (testData.part_a?.questions || []) : [];
      let savedB = (testData && testData.manual_mode) ? (testData.part_b?.questions || []) : [];
      let savedC = (testData && testData.manual_mode) ? (testData.part_c?.questions || []) : [];

      let html = `<div id="manual_form_${coTag}" class="mt-4 pt-4 border-t border-slate-800/60">`;
      html += `<div class="text-sm text-slate-300 bg-slate-950/50 p-4 rounded-xl border border-slate-800/40 space-y-4">`;
      
      const buildFields = (count, partName, prefix, savedQuestions) => {
         let fHtml = '';
         if(count > 0) fHtml += `<div class="font-bold text-slate-400 border-b border-slate-800 pb-1.5">${partName}</div><div class="space-y-3 mt-2">`;
         for(let i=0; i<count; i++) {
            let qText = savedQuestions && savedQuestions[i] ? savedQuestions[i].q : '';
            let qLvl = savedQuestions && savedQuestions[i] ? savedQuestions[i].level : 'U';
            fHtml += `
              <div class="flex gap-3 items-start">
                 <span class="text-slate-500 mt-2 font-mono">${i+1}.</span>
                 <textarea id="man_q_${prefix}_${coTag}_${i}" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-slate-200 outline-none focus:border-emerald-500 text-sm" rows="2" placeholder="Enter question ${i+1}...">${qText}</textarea>
                 <select id="man_lvl_${prefix}_${coTag}_${i}" class="bg-slate-900 border border-slate-700 rounded-lg p-2 text-slate-200 text-sm w-24 outline-none focus:border-emerald-500 mt-0.5">
                    <option value="U" ${qLvl === 'U' ? 'selected' : ''}>U (Understand)</option>
                    <option value="R" ${qLvl === 'R' ? 'selected' : ''}>R (Remember)</option>
                    <option value="A" ${qLvl === 'A' ? 'selected' : ''}>A (Apply)</option>
                 </select>
              </div>
            `;
         }
         if(count > 0) fHtml += `</div>`;
         return fHtml;
      };

      html += buildFields(qA, 'PART A', 'A', savedA);
      html += buildFields(qB, 'PART B', 'B', savedB);
      html += buildFields(qC, 'PART C', 'C', savedC);
      html += `</div></div>`;

      let wrapper = document.getElementById(`manual_form_wrapper_${coTag}`);
      if (wrapper) {
          wrapper.innerHTML = html;
      }
      
      const btn = document.getElementById(`gen_btn_${coTag}`);
      if (btn) btn.innerText = 'Save Custom Questions';
    }

    function saveManualSummativePaper(subjectId, coTag) {
      let qA = parseInt(document.getElementById(`summ_q_A_${coTag}`).value) || 0;
      let mA = parseInt(document.getElementById(`summ_m_A_${coTag}`).value) || 0;
      let qB = parseInt(document.getElementById(`summ_q_B_${coTag}`).value) || 0;
      let mB = parseInt(document.getElementById(`summ_m_B_${coTag}`).value) || 0;
      let qC = parseInt(document.getElementById(`summ_q_C_${coTag}`).value) || 0;
      let mC = parseInt(document.getElementById(`summ_m_C_${coTag}`).value) || 0;

      let gather = (count, marks, prefix) => {
         let questions = [];
         for(let i=0; i<count; i++) {
            let elQ = document.getElementById(`man_q_${prefix}_${coTag}_${i}`);
            let elL = document.getElementById(`man_lvl_${prefix}_${coTag}_${i}`);
            if(elQ) questions.push({ q: elQ.value, level: elL.value, marks: marks });
         }
         return { q_count: count, marks_per_q: marks, total_marks: count * marks, questions: questions };
      };

      saveSummativePatterns();

      fetch(`/api/classroom/${subjectId}/generate-summative-paper`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ 
           co_tag: coTag, 
           manual_mode: true,
           manual_part_a: gather(qA, mA, 'A'),
           manual_part_b: gather(qB, mB, 'B'),
           manual_part_c: gather(qC, mC, 'C')
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentSummativeTests[coTag] = data.data;
          loadCourseDetails(subjectId);
        } else alert(data.message);
      });
    }

    function generateSummativePaper(subjectId, coTag) {
      const isManual = document.querySelector(`input[name="summ_mode_${coTag}"]:checked`).value === 'manual';
      
      if(isManual) {
         if (document.getElementById(`manual_form_${coTag}`)) {
             saveManualSummativePaper(subjectId, coTag);
         } else {
             spawnManualFields(coTag);
         }
         return;
      }

      saveSummativePatterns();

      let partA = { q_count: document.getElementById(`summ_q_A_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_A_${coTag}`).value };
      let partB = { q_count: document.getElementById(`summ_q_B_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_B_${coTag}`).value };
      let partC = { q_count: document.getElementById(`summ_q_C_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_C_${coTag}`).value };

      fetch(`/api/classroom/${subjectId}/generate-summative-paper`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, part_a: partA, part_b: partB, part_c: partC })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentSummativeTests[coTag] = data.data;
          // Soft re-render the whole summative tab using existing data
          // We don't have cos/students cached globally in a variable cleanly, so let's reload.
          loadCourseDetails(subjectId);
        } else alert(data.message);
      });
    }

    function loadActiveOnlineTests(subjectId) {
      fetch(`/api/classroom/${subjectId}/active-online-tests`)
        .then(res => res.json())
        .then(data => {
          let listDiv = document.getElementById('activeOnlineTestsList');
          if (!listDiv) return;
          if (data.status === 'SUCCESS' && data.data && data.data.length > 0) {
            let html = '';
            data.data.forEach(t => {
              html += `
                <div class="bg-slate-950 p-3 rounded-lg border border-slate-800/80 mb-2">
                  <div class="flex justify-between items-start mb-1">
                    <h6 class="font-bold text-purple-400 text-[10px]">${t.test_name}</h6>
                    <span class="bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded text-[10px] font-bold">${t.duration} Mins</span>
                  </div>
                  <div class="text-[10px] text-slate-500 mb-2">
                    Start: ${t.start_time ? new Date(t.start_time).toLocaleString() : 'Now'}<br>
                    Live Students: <span class="text-emerald-400 font-bold">${t.student_count || 0}</span> | Completed: <span class="text-blue-400 font-bold">${t.completed_count || 0}</span>
                  </div>
                  <div class="grid grid-cols-2 gap-2 mt-2">
                      <button onclick="generateOnlineTestReport('${t.test_id}')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 text-[10px] transition-premium" title="Download Results">
                        <span class="material-symbols-rounded text-[10px]">download</span> Report
                      </button>
                      <button onclick="printOnlineTest('${t.test_id}')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 text-[10px] transition-premium" title="Print Question Paper with Answers">
                        <span class="material-symbols-rounded text-[10px]">print</span> Print Q&A
                      </button>
                      <button onclick="deleteOnlineTest('${t.test_id}', '${subjectId}')" class="col-span-2 w-full py-1 bg-red-900/50 hover:bg-red-800/80 text-red-300 rounded border border-red-800/50 flex items-center justify-center gap-1 text-[10px] transition-premium" title="Delete Test">
                        <span class="material-symbols-rounded text-[10px]">delete</span> Delete
                      </button>
                    </div>
                </div>
              `;
            });
            listDiv.innerHTML = html;
          } else {
            listDiv.innerHTML = `<div class="p-3 bg-slate-950 border border-slate-800 rounded text-center border-dashed">No active online tests found.</div>`;
          }
        });
    }

    function publishOnlineTest(subjectId) {
      const selectElement = document.getElementById('online_test_cos');
      const selectedCos = Array.from(selectElement.selectedOptions).map(opt => opt.value);
      const attempts = document.getElementById('online_test_attempts').value;
      const duration = document.getElementById('online_test_duration').value;
      const start = document.getElementById('online_test_start').value;
      const end = document.getElementById('online_test_end').value;
      const q_count = document.getElementById('online_test_q_count').value;
      const gen_mode = document.getElementById('online_test_gen_mode').value;

      if (selectedCos.length === 0) {
        alert("Please select at least one CO.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/publish-online-test`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ cos: selectedCos, attempts, duration, start, end, q_count, generation_mode: gen_mode })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert("Online Test successfully published!");
          loadActiveOnlineTests(subjectId);
          
          // Clear inputs
          selectElement.selectedIndex = -1;
          if (document.getElementById('online_test_start')._flatpickr) document.getElementById('online_test_start')._flatpickr.clear();
          if (document.getElementById('online_test_end')._flatpickr) document.getElementById('online_test_end')._flatpickr.clear();
        } else {
          alert(data.message || "Failed to publish test.");
        }
      });
    }

    function generateOnlineTestReport(testId) {
      fetch(`/api/test-engine/report/${testId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const test = data.test_info;
            const attempts = data.report;
            
            let tableRows = '';
            if(attempts && attempts.length > 0) {
              attempts.forEach(a => {
                 let start = new Date(a.start_time);
                 let end = new Date(a.end_time);
                 let timeTakenStr = '-';
                 if(a.start_time && a.end_time) {
                    let diffMs = end - start;
                    let diffMins = Math.floor(diffMs / 60000);
                    let diffSecs = Math.floor((diffMs % 60000) / 1000);
                    timeTakenStr = `${diffMins}m ${diffSecs}s`;
                 }
                 
                 tableRows += `
                   <tr>
                     <td style="padding: 8px; border: 1px solid #ddd; font-family: monospace;">${a.reg_no}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">${a.name}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${a.attempt_number}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${timeTakenStr}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; text-align: center; font-weight: bold; font-size: 14px;">${a.total_score}</td>
                   </tr>
                 `;
              });
            } else {
              tableRows = `<tr><td colspan="5" style="padding: 16px; text-align: center; border: 1px solid #ddd;">No completed attempts yet.</td></tr>`;
            }

            const html = `<!DOCTYPE html>
            <html>
            <head>
              <title>${test.test_name} - Report</title>
              <style>
                body { font-family: system-ui, -apple-system, sans-serif; padding: 40px; color: #111; }
                h2 { text-align: center; margin-bottom: 5px; text-transform: uppercase; border-bottom: 2px solid #000; padding-bottom: 10px; display: inline-block; }
                .meta { text-align: center; font-size: 14px; color: #555; margin-bottom: 30px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 13px; }
                th { background: #f0f0f0; padding: 10px 8px; border: 1px solid #ddd; text-align: left; }
                .center { text-align: center; }
              </style>
            </head>
            <body>
              <div style="text-align: center;">
                <h2>Online Test Evaluation Report</h2>
                <div class="meta">
                  <strong>Test Name:</strong> ${test.test_name} <br>
                  <strong>Subject Code:</strong> ${test.subject_code} <br>
                  <strong>Total MCQs:</strong> ${test.mcq_count} | <strong>Duration:</strong> ${test.duration} Mins<br>
                  <strong>Generated On:</strong> ${new Date().toLocaleString()}
                </div>
              </div>
              
              <table>
                <thead>
                  <tr>
                    <th>Reg No</th>
                    <th>Student Name</th>
                    <th class="center">Attempts Used</th>
                    <th class="center">Time Taken</th>
                    <th class="center">Marks Obtained</th>
                  </tr>
                </thead>
                <tbody>
                  ${tableRows}
                </tbody>
              </table>
              <script>
                window.onload = () => { window.print(); window.close(); }
              <\/script>
            </body>
            </html>`;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(html);
            printWindow.document.close();
          } else {
            alert(data.message || "Failed to generate report.");
          }
        });
    }

    function saveSummativeMarks(subjectId) {
      let marksPayload = [];
      const rows = document.querySelectorAll('#summativeMarkEntryTbody tr[data-reg]');
      rows.forEach(row => {
        const regNo = row.getAttribute('data-reg');
        const inputs = row.querySelectorAll('.summ-mark');
        inputs.forEach(input => {
          if (input.value !== '') {
            marksPayload.push({
              reg_no: regNo,
              co_tag: input.getAttribute('data-co'),
              marks_obtained: input.value
            });
          }
        });
      });

      if (marksPayload.length === 0) {
        alert("No marks entered.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/save-written-test-marks`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ marks: marksPayload })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') alert("Written Marks successfully saved!");
        else alert(data.message || "Failed to save marks.");
      });
    }

    function printAssignmentReport(subjectId) {
      window.open(`/classroom/${subjectId}/assignment-report`, '_blank');
    }

    function printSummativeReport(subjectId) {
      window.open(`/classroom/${subjectId}/summative-report`, '_blank');
    }

    function printSummativePaper(coTag, totalMarks) {
      const data = currentSummativeTests[coTag];
      if(!data) return;

      const deptMap = {
        'EL': 'ELECTRONICS ENGINEERING',
        'CS': 'COMPUTER SCIENCE AND ENGINEERING',
        'CE': 'CIVIL ENGINEERING',
        'ME': 'MECHANICAL ENGINEERING',
        'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
        'IT': 'INFORMATION TECHNOLOGY',
        'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
      };
      const sessionBranch = "{{ session('userBranch', 'ENGINEERING') }}";
      const subjectName = currentSubjectName;
      const subjectCode = currentSubjectCode;
      const deptName = deptMap[sessionBranch.toUpperCase()] || sessionBranch;
      const examDate = data.date_of_exam
        ? new Date(data.date_of_exam).toLocaleDateString('en-IN', {day:'2-digit', month:'long', year:'numeric'})
        : 'TBA';

      const buildRows = (part) => {
        if (!part || !part.q_count || !part.questions) return '';
        return part.questions.map((q, i) =>
          `<tr>
            <td style="width:28px;vertical-align:top;padding:4px 2px;">${i+1}.</td>
            <td style="vertical-align:top;padding:4px 6px;">${q.q}</td>
            <td style="width:95px;text-align:right;vertical-align:top;padding:4px 2px;white-space:nowrap;">${q.marks} Marks &nbsp;<strong>[${q.level}]</strong></td>
          </tr>`
        ).join('');
      };

      let bodyHtml = '';

      if (data.part_a && data.part_a.q_count > 0) {
        bodyHtml += `
          <h4 style="text-align:center;font-weight:bold;margin:18px 0 6px;">PART A &nbsp;<small style="font-weight:normal;font-size:12px;">(${data.part_a.q_count} Ã ${data.part_a.marks_per_q} = ${data.part_a.total_marks} Marks)</small></h4>
          <p style="text-align:center;font-style:italic;font-size:12px;margin:0 0 10px;">Answer all questions.</p>
          <table style="width:100%;border-collapse:collapse;font-size:13px;">${buildRows(data.part_a)}</table>`;
      }
      if (data.part_b && data.part_b.q_count > 0) {
        bodyHtml += `
          <h4 style="text-align:center;font-weight:bold;margin:20px 0 6px;">PART B &nbsp;<small style="font-weight:normal;font-size:12px;">(${data.part_b.q_count} Ã ${data.part_b.marks_per_q} = ${data.part_b.total_marks} Marks)</small></h4>
          <p style="text-align:center;font-style:italic;font-size:12px;margin:0 0 10px;">Answer all questions.</p>
          <table style="width:100%;border-collapse:collapse;font-size:13px;">${buildRows(data.part_b)}</table>`;
      }
      if (data.part_c && data.part_c.q_count > 0) {
        bodyHtml += `
          <h4 style="text-align:center;font-weight:bold;margin:20px 0 6px;">PART C &nbsp;<small style="font-weight:normal;font-size:12px;">(${data.part_c.q_count} Ã ${data.part_c.marks_per_q} = ${data.part_c.total_marks} Marks)</small></h4>
          <p style="text-align:center;font-style:italic;font-size:12px;margin:0 0 10px;">Answer all questions.</p>
          <table style="width:100%;border-collapse:collapse;font-size:13px;">${buildRows(data.part_c)}</table>`;
      }

      const fullHtml = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Question Paper - ${coTag}</title>
  <style>
    @page { size: A4 portrait; margin: 1.5cm 2cm; }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      padding: 0;
      font-family: 'Times New Roman', Times, serif;
      font-size: 13px;
      color: #000;
      background: #fff;
    }
    h2, h3, h4, p { margin: 0; padding: 0; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 16px; }
    .college-name { font-size: 21px; font-weight: bold; letter-spacing: 1px; }
    .dept-name { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-top: 3px; }
    .subject-info { font-size: 12px; margin-top: 4px; color: #222; }
    .exam-title { font-size: 14px; margin-top: 6px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border-top: 1px solid #888; border-bottom: 1px solid #888; padding: 4px 0; display: inline-block; }
    .meta-row { display: flex; justify-content: space-between; margin-top: 10px; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 5px 3px; vertical-align: top; line-height: 1.5; }
  </style>
</head>
<body>
  <div class="header">
    <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
    <div class="dept-name">Department of ${deptName}</div>
    <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
    <div style="margin-top:6px;"><span class="exam-title">&nbsp;${coTag} &ndash; Written Test&nbsp;</span></div>
    <div class="meta-row">
      <span><strong>Time:</strong> 1.5 Hours</span>
      <span><strong>Date:</strong> ${examDate}</span>
      <span><strong>Max Marks:</strong> ${totalMarks}</span>
    </div>
  </div>
  ${bodyHtml}
</body>
</html>`;

      const pw = window.open('', '_blank', 'width=900,height=700');
      pw.document.write(fullHtml);
      pw.document.close();
      pw.focus();
      setTimeout(() => { pw.print(); }, 400);
    }

    function printAnswerKey(coTag, totalMarks) {
      const data = currentSummativeTests[coTag];
      if(!data) return;

      const deptMap = {
        'EL': 'ELECTRONICS ENGINEERING',
        'CS': 'COMPUTER SCIENCE AND ENGINEERING',
        'CE': 'CIVIL ENGINEERING',
        'ME': 'MECHANICAL ENGINEERING',
        'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
        'IT': 'INFORMATION TECHNOLOGY',
        'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
      };
      const sessionBranch = "{{ session('userBranch', 'ENGINEERING') }}";
      const subjectName = currentSubjectName;
      const subjectCode = currentSubjectCode;
      const deptName = deptMap[sessionBranch.toUpperCase()] || sessionBranch;
      const examDate = data.date_of_exam
        ? new Date(data.date_of_exam).toLocaleDateString('en-IN', {day:'2-digit', month:'long', year:'numeric'})
        : 'TBA';

      const buildRubricHtml = (rubric, marks) => {
        // Fallback for older generated papers that don't have a rubric saved
        if (!rubric || rubric.length === 0) {
            if (marks <= 2) rubric = [{desc: 'Correct definition / answer', mark: marks}];
            else if (marks <= 4) rubric = [{desc: 'Key definition / concept', mark: 1}, {desc: 'Explanation / relevant points', mark: marks - 1}];
            else rubric = [{desc: 'Definition / Concept statement', mark: 1}, {desc: 'Explanation with supporting points', mark: Math.floor(marks/2)}, {desc: 'Diagram / Application', mark: marks - Math.floor(marks/2) - 1}];
        }

        return `<table style="width: 100%; border-collapse: collapse; font-size: 11px; margin-top: 4px; background: #fafafa;">
          ${rubric.map(r => `<tr>
            <td style="padding: 3px 6px; border: 1px solid #ddd;">${r.desc}</td>
            <td style="padding: 3px 6px; text-align: center; width: 50px; border: 1px solid #ddd; font-weight: bold; color: #444;">${r.mark}</td>
          </tr>`).join('')}
        </table>`;
      };

      const buildRows = (part) => {
        if (!part || !part.q_count || !part.questions) return '';
        return part.questions.map((q, i) => {
          let ansHtml = '';
          if (q.ans && q.ans.length > 0) {
            ansHtml = `<div style="margin-bottom: 8px; font-size: 12px; color: #333;">
              <ul style="margin: 0; padding-left: 16px;">
                ${q.ans.map(a => `<li style="margin-bottom: 3px;">${a}</li>`).join('')}
              </ul>
            </div>`;
          }
          
          return `<tr>
            <td style="width: 40px; text-align: center; vertical-align: top; padding: 10px 5px; border: 1px solid #000; font-weight: bold;">${i+1}</td>
            <td style="vertical-align: top; padding: 10px; border: 1px solid #000;">
              <div style="font-weight: 500; margin-bottom: 6px; font-size: 13px;">${q.q}</div>
              ${ansHtml}
              <div style="font-size: 11px; font-weight: bold; color: #555; margin-bottom: 2px; margin-top: 6px;">Marking Scheme / Answer Pointers:</div>
              ${buildRubricHtml(q.rubric, q.marks)}
            </td>
            <td style="width: 80px; text-align: center; vertical-align: middle; padding: 10px 5px; border: 1px solid #000; font-size: 14px; font-weight: bold;">${q.marks}</td>
            <td style="width: 60px; text-align: center; vertical-align: middle; padding: 10px 5px; border: 1px solid #000; font-size: 11px;">[${q.level}]</td>
          </tr>`;
        }).join('');
      };

      let bodyHtml = '';

      const tableHeader = `
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
          <thead>
            <tr>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 40px;">Q.No</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee;">Question & Expected Answer Key</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 80px;">Marks</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 60px;">Level</th>
            </tr>
          </thead>
          <tbody>
      `;

      if (data.part_a && data.part_a.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART A <small style="font-weight:normal; font-size:12px;">(${data.part_a.q_count} Ã ${data.part_a.marks_per_q} = ${data.part_a.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_a)}</tbody></table>`;
      }
      if (data.part_b && data.part_b.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART B <small style="font-weight:normal; font-size:12px;">(${data.part_b.q_count} Ã ${data.part_b.marks_per_q} = ${data.part_b.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_b)}</tbody></table>`;
      }
      if (data.part_c && data.part_c.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART C <small style="font-weight:normal; font-size:12px;">(${data.part_c.q_count} Ã ${data.part_c.marks_per_q} = ${data.part_c.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_c)}</tbody></table>`;
      }

      const fullHtml = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Answer Key - ${coTag}</title>
  <style>
    @page { size: A4 portrait; margin: 1.5cm 2cm; }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      padding: 0;
      font-family: 'Times New Roman', Times, serif;
      font-size: 13px;
      color: #000;
      background: #fff;
    }
    h2, h3, h4, p { margin: 0; padding: 0; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 16px; }
    .college-name { font-size: 21px; font-weight: bold; letter-spacing: 1px; }
    .dept-name { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-top: 3px; }
    .subject-info { font-size: 12px; margin-top: 4px; color: #222; }
    .exam-title { font-size: 14px; margin-top: 6px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border-top: 1px solid #888; border-bottom: 1px solid #888; padding: 4px 0; display: inline-block; background-color: #f0f0f0; }
    .meta-row { display: flex; justify-content: space-between; margin-top: 10px; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 5px 3px; vertical-align: top; line-height: 1.5; }
  </style>
</head>
<body>
  <div class="header">
    <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
    <div class="dept-name">Department of ${deptName}</div>
    <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
    <div style="margin-top:6px;"><span class="exam-title">&nbsp;${coTag} &ndash; ANSWER KEY & RUBRIC&nbsp;</span></div>
    <div class="meta-row">
      <span><strong>Time:</strong> 1.5 Hours</span>
      <span><strong>Date:</strong> ${examDate}</span>
      <span><strong>Max Marks:</strong> ${totalMarks}</span>
    </div>
  </div>
  ${bodyHtml}
</body>
</html>`;

      const pw = window.open('', '_blank', 'width=900,height=700');
      pw.document.write(fullHtml);
      pw.document.close();
      pw.focus();
      setTimeout(() => { pw.print(); }, 400);
    }

    function loadSecurityLogs() {
      const tbody = document.getElementById('securityLogsTable');
      tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500">Querying security logs...</td></tr>`;

      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500">No profile action logs recorded.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 text-[10px] hover:bg-slate-900/20";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-4 text-slate-400 font-mono">${date}</td>
                <td class="p-4"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-4 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-red-400 font-bold">Failed to load logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-red-400 font-bold">Error querying logs.</td></tr>`;
        });
    }
  
      function deleteOnlineTest(testId, subjectId) {
        if (!confirm("Are you sure you want to delete this online test? This will permanently remove all student attempts and records associated with it.")) return;
        fetch(`/api/classroom/online-tests/${testId}`, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            loadActiveOnlineTests(subjectId);
          } else {
            alert(data.message || "Failed to delete test.");
          }
        });
      }

      function printOnlineTest(testId) {
        fetch(`/api/classroom/online-tests/${testId}/key`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const deptMap = {
              'EL': 'ELECTRONICS ENGINEERING',
              'CS': 'COMPUTER SCIENCE AND ENGINEERING',
              'CE': 'CIVIL ENGINEERING',
              'ME': 'MECHANICAL ENGINEERING',
              'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
              'IT': 'INFORMATION TECHNOLOGY',
              'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
            };
            const sessionBranch = "{{ session('userBranch', 'ENGINEERING') }}";
            const subjectName = currentSubjectName;
            const subjectCode = currentSubjectCode;
            const deptName = deptMap[sessionBranch.toUpperCase()] || sessionBranch;
            const testName = data.test_name || 'Online Test';
            const totalQ = data.total || 0;

            let html = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Online MCQ Test - ${testName}</title>
  <style>
    @page { size: A4 portrait; margin: 1.5cm 2cm; }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      padding: 0;
      font-family: 'Times New Roman', Times, serif;
      font-size: 13px;
      color: #000;
      background: #fff;
    }
    h2, h3, h4, p { margin: 0; padding: 0; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 16px; }
    .college-name { font-size: 21px; font-weight: bold; letter-spacing: 1px; }
    .dept-name { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-top: 3px; }
    .subject-info { font-size: 12px; margin-top: 4px; color: #222; }
    .exam-title { font-size: 14px; margin-top: 6px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border-top: 1px solid #888; border-bottom: 1px solid #888; padding: 4px 0; display: inline-block; background-color: #f0f0f0; }
    .meta-row { display: flex; justify-content: space-between; margin-top: 10px; font-size: 12px; }
    .q-block { margin-bottom: 15px; page-break-inside: avoid; }
    .q-text { font-weight: bold; margin-bottom: 5px; }
    .options { list-style-type: lower-alpha; margin: 0; padding-left: 20px; }
    .options li { margin-bottom: 3px; }
  </style>
</head>
<body>
  <div class="header">
    <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
    <div class="dept-name">Department of ${deptName}</div>
    <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
    <div style="margin-top:6px;"><span class="exam-title">&nbsp;${testName} &ndash; Answer Key&nbsp;</span></div>
    <div class="meta-row">
      <span><strong>Total Questions:</strong> ${totalQ}</span>
    </div>
  </div>`;

            data.details.forEach((q, i) => {
              html += `<div class="q-block">
                <div class="q-text">${i+1}. ${q.q} &nbsp; <em>[${q.co}]</em></div>
                <ul class="options">`;
              q.options.forEach(opt => {
                let isCorrect = (opt === q.correct_ans);
                if (isCorrect) {
                  html += `<li><strong>${opt} &nbsp; &#10004;</strong></li>`;
                } else {
                  html += `<li>${opt}</li>`;
                }
              });
              html += `</ul></div>`;
            });

            html += `</body></html>`;
            let pw = window.open('', '_blank', 'width=800,height=600');
            pw.document.write(html);
            pw.document.close();
            pw.focus();
            setTimeout(() => { pw.print(); }, 500);
          } else {
            alert(data.message);
          }
        });
      }

      function showVcStudentsList() {
        let html = '';
        if (window.currentVirtualStudents && window.currentVirtualStudents.length > 0) {
          html = `
            <table class="w-full text-left text-[10px] border-collapse">
              <thead>
                <tr class="bg-slate-900 border-b border-slate-800 text-slate-400">
                  <th class="p-3 font-bold w-12 text-center">No.</th>
                  <th class="p-3 font-bold w-32">Reg No</th>
                  <th class="p-3 font-bold">Student Name</th>
                </tr>
              </thead>
              <tbody>
          `;
          window.currentVirtualStudents.forEach((s, idx) => {
            html += `
              <tr class="border-b border-slate-800/50 hover:bg-slate-900/50 transition-colors">
                <td class="p-3 text-center text-slate-500 font-bold">${idx + 1}</td>
                <td class="p-3 font-mono text-slate-400">${s.reg_no}</td>
                <td class="p-3 font-bold text-slate-200">${s.name}</td>
              </tr>
            `;
          });
          html += `</tbody></table>`;
        } else {
          html = '<p class="text-[10px] text-slate-500 text-center py-4">No students enrolled in this classroom.</p>';
        }
        document.getElementById('vcStudentsListContent').innerHTML = html;
        document.getElementById('vcStudentsModal').classList.remove('hidden');
      }

      function closeVcStudentsList() {
        document.getElementById('vcStudentsModal').classList.add('hidden');
      }

      function printVcStudentsList() {
        if (!window.currentVirtualStudents || window.currentVirtualStudents.length === 0) {
            alert("No students to print.");
            return;
        }

        let printHtml = `
          <html>
            <head>
              <title>Classroom Students List</title>
              <style>
                body { font-family: sans-serif; font-size: 12px; padding: 20px; }
                h2 { text-align: center; margin-bottom: 5px; font-size: 18px; text-transform: uppercase; }
                p.subtitle { text-align: center; margin-top: 0; font-size: 12px; font-weight: bold; margin-bottom: 20px; color: #555; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                th { background-color: #f0f0f0; }
                .text-center { text-align: center; }
              </style>
            </head>
            <body>
              <h2>Classroom Students List</h2>
              <p class="subtitle">Subject: ${currentSubjectCode} - ${currentSubjectName}</p>
              <table>
                <thead>
                  <tr>
                    <th class="text-center" style="width: 40px;">No.</th>
                    <th style="width: 100px;">Roll No</th>
                    <th style="width: 120px;">Reg No</th>
                    <th>Student Name</th>
                    <th style="width: 150px;">Remarks</th>
                  </tr>
                </thead>
                <tbody>
        `;

        window.currentVirtualStudents.forEach((s, idx) => {
          printHtml += `
            <tr>
              <td class="text-center">${idx + 1}</td>
              <td></td>
              <td style="font-family: monospace;">${s.reg_no}</td>
              <td><strong>${s.name}</strong></td>
              <td></td>
            </tr>
          `;
        });

        printHtml += `
                </tbody>
              </table>
              <script>
                setTimeout(() => { window.print(); window.close(); }, 500);
              <\/script>
            </body>
          </html>
        `;

        let printWin = window.open('', '_blank');
        printWin.document.write(printHtml);
        printWin.document.close();
      }

    function fetchClassReports() {
      if (!currentSubjectId) return;
      const workspace = document.getElementById('classroomReportWorkspace');
      workspace.innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500">
          <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">Loading reports...</p>
        </div>
      `;

      fetch(`/api/staff/attendance/subjects/${currentSubjectId}/reports`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            classReportsData = data;
            renderActiveReport();
          } else {
            workspace.innerHTML = `<div class="text-sm font-bold text-red-400 py-10 text-center">${data.message || 'Failed to load reports.'}</div>`;
          }
        })
        .catch(err => {
          console.error(err);
          workspace.innerHTML = '<div class="text-sm font-bold text-red-400 py-10 text-center">Error loading reports.</div>';
        });
    }

    function loadClassReport(type) {
      activeReportType = type;
      
      const buttons = [
        { id: 'attendance_log', btn: 'btnReportLog' },
        { id: 'subject_log', btn: 'btnReportSubject' },
        { id: 'summary_matrix', btn: 'btnReportMatrix' }
      ];

      buttons.forEach(b => {
        const el = document.getElementById(b.btn);
        if (!el) return;
        if (b.id === type) {
          el.className = "px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm cursor-pointer transition-premium";
        } else {
          el.className = "px-4 py-2 bg-slate-900 text-slate-300 border border-slate-800 rounded-xl font-bold text-sm cursor-pointer hover:bg-slate-800 transition-premium";
        }
      });

      renderActiveReport();
    }

    function renderActiveReport() {
      if (!classReportsData) return;
      const workspace = document.getElementById('classroomReportWorkspace');
      workspace.innerHTML = '';

      if (activeReportType === 'attendance_log') {
        renderAttendanceLogReport(workspace);
      } else if (activeReportType === 'subject_log') {
        renderSubjectLogReport(workspace);
      } else if (activeReportType === 'summary_matrix') {
        renderSummaryMatrixReport(workspace);
      }
    }

    function renderAttendanceLogReport(container) {
      const logs = classReportsData.logs || [];
      if (logs.length === 0) {
        container.innerHTML = '<div class="text-sm font-bold text-slate-400 py-10 text-center">No attendance logs recorded yet for this subject.</div>';
        return;
      }

      let html = `
        <div class="overflow-x-auto border border-slate-800/60 rounded-xl bg-slate-900/20">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-950/40 text-slate-400 border-b border-slate-800 uppercase tracking-wider text-xs font-black">
                <th class="p-4">Date</th>
                <th class="p-4 text-center">Period</th>
                <th class="p-4">Topics Covered</th>
                <th class="p-4 text-center">Present</th>
                <th class="p-4 text-center">Absent</th>
              </tr>
            </thead>
            <tbody>
      `;

      logs.forEach(log => {
        html += `
          <tr class="border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium">
            <td class="p-4 font-mono font-bold text-slate-300">${log.date}</td>
            <td class="p-4 text-center font-bold text-slate-400">P${log.period}</td>
            <td class="p-4 text-slate-200">${log.topics_covered || '-'}</td>
            <td class="p-4 text-center font-bold text-emerald-400">${log.present_count}</td>
            <td class="p-4 text-center font-bold text-rose-400">${log.absent_count}</td>
          </tr>
        `;
      });

      html += `
            </tbody>
          </table>
        </div>
      `;
      container.innerHTML = html;
    }

    function renderSubjectLogReport(container) {
      const logs = classReportsData.logs || [];
      if (logs.length === 0) {
        container.innerHTML = '<div class="text-sm font-bold text-slate-400 py-10 text-center">No class logs recorded yet for this subject.</div>';
        return;
      }

      let html = `
        <div class="overflow-x-auto border border-slate-800/60 rounded-xl bg-slate-900/20">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-950/40 text-slate-400 border-b border-slate-800 uppercase tracking-wider text-xs font-black">
                <th class="p-4">Completed Date</th>
                <th class="p-4 text-center">Period</th>
                <th class="p-4">Lesson Plan Reference</th>
                <th class="p-4">Topics Covered</th>
              </tr>
            </thead>
            <tbody>
      `;

      logs.forEach(log => {
        html += `
          <tr class="border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium">
            <td class="p-4 font-mono font-bold text-slate-300">${log.date}</td>
            <td class="p-4 text-center font-bold text-slate-400">P${log.period}</td>
            <td class="p-4 text-slate-400 font-bold">${log.lesson_plan_id ? 'LP ID: ' + log.lesson_plan_id : 'Manual Entry'}</td>
            <td class="p-4 text-slate-200">${log.topics_covered || '-'}</td>
          </tr>
        `;
      });

      html += `
            </tbody>
          </table>
        </div>
      `;
      container.innerHTML = html;
    }

    function renderSummaryMatrixReport(container) {
      const dates = classReportsData.dates || [];
      const matrix = classReportsData.matrix || [];

      if (dates.length === 0 || matrix.length === 0) {
        container.innerHTML = '<div class="text-sm font-bold text-slate-400 py-10 text-center">No attendance summary available.</div>';
        return;
      }

      let html = `
        <div class="overflow-x-auto border border-slate-800/60 rounded-xl bg-slate-900/20 max-h-[500px]">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-950/40 text-slate-400 border-b border-slate-800 uppercase tracking-wider text-xs font-black sticky top-0 z-10">
                <th class="p-4 bg-slate-950/90 w-16 text-center sticky left-0 z-20">Roll</th>
                <th class="p-4 bg-slate-950/90 w-44 sticky left-16 z-20">Name</th>
      `;

      dates.forEach(d => {
        const shortDate = d.date.substring(5);
        html += `<th class="p-4 text-center min-w-[70px]">${shortDate}<br><span class="text-[10px] text-slate-500">P${d.period}</span></th>`;
      });

      html += `
              </tr>
            </thead>
            <tbody>
      `;

      matrix.forEach(row => {
        html += `
          <tr class="border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium">
            <td class="p-4 text-center font-bold text-slate-500 bg-slate-900/90 sticky left-0 z-10">${row.roll_no || '-'}</td>
            <td class="p-4 font-bold text-white bg-slate-900/90 sticky left-16 z-10 truncate max-w-[176px]">${row.name}</td>
        `;

        dates.forEach(d => {
          const key = d.date + ' | P' + d.period;
          const status = row.attendance[key] || '-';
          let cellClass = 'text-slate-500';
          if (status === 'P') cellClass = 'text-emerald-400 font-bold';
          if (status === 'A') cellClass = 'text-rose-400 font-bold';

          html += `<td class="p-4 text-center ${cellClass}">${status}</td>`;
        });

        html += `</tr>`;
      });

      html += `
            </tbody>
          </table>
        </div>
      `;
      html += `
            </tbody>
          </table>
        </div>
      `;
      container.innerHTML = html;
    }

    function fetchQuestionBank(subjectId) {
      const container = document.getElementById('qbankCoGroups');
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-10">
          <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">Loading Question Bank...</p>
        </div>
      `;

      fetch(`/api/classroom/${subjectId}/question-bank`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          renderQuestionBank(data.questions);
        } else {
          container.innerHTML = `<div class="text-sm text-rose-400 py-6 text-center font-bold">Failed to load questions.</div>`;
        }
      })
      .catch(err => {
        container.innerHTML = `<div class="text-sm text-rose-400 py-6 text-center font-bold">Error loading questions.</div>`;
      });
    }

    function renderQuestionBank(questions) {
      const container = document.getElementById('qbankCoGroups');
      if (!questions || questions.length === 0) {
        container.innerHTML = `
          <div class="text-center py-12 text-slate-400 space-y-4 max-w-md mx-auto">
            <div class="bg-slate-900/50 p-4 rounded-full border border-slate-800/60 inline-block">
              <span class="material-symbols-rounded text-3xl text-slate-600 block">database</span>
            </div>
            <p class="text-sm font-bold text-slate-300">No questions in this subject's pool.</p>
            <p class="text-sm text-slate-500">You can download the template CSV, fill it with questions, and upload it. Alternatively, seed the pool instantly with high-quality questions using AI.</p>
            <div class="pt-2">
              <button onclick="seedQuestionBankWithAi(currentSubjectId)" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold transition-premium cursor-pointer shadow-md flex items-center gap-1.5 mx-auto">
                <span class="material-symbols-rounded text-base">auto_awesome</span> Seed Pool via AI
              </button>
            </div>
          </div>
        `;
        return;
      }

      const markGroups = {
        '1 Mark Questions': [],
        '3 Mark Questions': [],
        '7 Mark Questions': [],
        'Other Marks': []
      };

      questions.forEach(q => {
        const marks = parseInt(q.marks || 0);
        if (marks === 1) {
          markGroups['1 Mark Questions'].push(q);
        } else if (marks === 3) {
          markGroups['3 Mark Questions'].push(q);
        } else if (marks === 7) {
          markGroups['7 Mark Questions'].push(q);
        } else {
          markGroups['Other Marks'].push(q);
        }
      });

      let html = '';
      Object.keys(markGroups).forEach(groupName => {
        const qList = markGroups[groupName];
        if (qList.length === 0) return;

        html += `
          <div class="border border-slate-800/80 rounded-xl overflow-hidden bg-slate-900/10 mb-6">
            <div class="bg-slate-900/60 p-4 flex justify-between items-center border-b border-slate-800/60">
              <div class="flex items-center gap-2">
                <span class="material-symbols-rounded text-blue-400 text-base">grade</span>
                <span class="text-sm font-black text-slate-200">${groupName}</span>
              </div>
              <span class="text-sm text-slate-400 font-bold bg-slate-950/40 px-2.5 py-1 rounded-md">${qList.length} Questions</span>
            </div>
            <div class="p-0 overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="border-b border-slate-800/60 text-slate-400 text-sm bg-slate-950/20">
                    <th class="py-2.5 px-4 font-bold w-12 text-center">#</th>
                    <th class="py-2.5 px-4 font-bold">Question Text</th>
                    <th class="py-2.5 px-4 font-bold w-20 text-center">CO Tag</th>
                    <th class="py-2.5 px-4 font-bold w-28 text-center">Cognitive</th>
                    <th class="py-2.5 px-4 font-bold w-28 text-center">Type</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/30 text-slate-300">
        `;

        qList.forEach((q, index) => {
          const typeStr = q.type === 'MCQ' ? `MCQ (Ans: ${q.correct_answer || 'N/A'})` : 'Descriptive';
          html += `
            <tr class="hover:bg-slate-900/20 transition-premium text-sm">
              <td class="py-3 px-4 text-center text-slate-500 font-mono">${index + 1}</td>
              <td class="py-3 px-4 font-bold text-slate-200">
                <div>${q.question_text}</div>
                ${q.type === 'MCQ' && q.options ? renderCompactOptions(q.options, q.correct_answer) : ''}
              </td>
              <td class="py-3 px-4 text-center">
                <span class="px-2 py-0.5 rounded bg-blue-950/40 text-blue-400 border border-blue-900/30 font-mono text-[11px] font-bold">${q.co_tag || 'CO1'}</span>
              </td>
              <td class="py-3 px-4 text-center">
                <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 border border-slate-700/40 text-[11px] font-bold">${q.cognitive_level || 'Understand'}</span>
              </td>
              <td class="py-3 px-4 text-center">
                <span class="px-2 py-0.5 rounded ${q.type === 'MCQ' ? 'bg-purple-950/40 text-purple-400 border border-purple-900/30' : 'bg-amber-950/40 text-amber-400 border border-amber-900/30'} text-[11px] font-bold">${typeStr}</span>
              </td>
            </tr>
          `;
        });

        html += `
                </tbody>
              </table>
            </div>
          </div>
        `;
      });

      if (!html) {
        html = `<div class="text-sm text-slate-500 py-8 text-center">No grouped questions found.</div>`;
      }

      container.innerHTML = html;
    }

    function renderCompactOptions(optionsStr, correctAns) {
      const options = typeof optionsStr === 'string' ? JSON_decode_safe(optionsStr) : optionsStr;
      if (!options || options.length === 0) return '';
      const labels = ['A', 'B', 'C', 'D'];
      let optHtml = '<div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-[11px] text-slate-400 font-normal pl-2 border-l border-slate-800">';
      options.forEach((opt, idx) => {
        if (!opt) return;
        const isCorrect = correctAns === labels[idx] || correctAns === opt;
        const colorClass = isCorrect ? 'text-emerald-400 font-bold' : 'text-slate-500';
        optHtml += `<span class="${colorClass}"><b class="opacity-60">${labels[idx]}:</b> ${opt}</span>`;
      });
      optHtml += '</div>';
      return optHtml;
    }

    function JSON_decode_safe(str) {
      try {
        return JSON.parse(str);
      } catch (e) {
        return [];
      }
    }

    function downloadExcelTemplate() {
      const headers = [
        ['Type', 'Marks', 'Cognitive Level', 'CO Tag', 'Question Text', 'Option A', 'Option B', 'Option C', 'Option D', 'Correct Answer'],
        ['MCQ', '1', 'Remember', 'CO1', 'What is the correct definition of an embedded system?', 'A general purpose computer system', 'A specialized computer system designed for specific control functions', 'A computer system with no hardware', 'A system only used in gaming consoles', 'B'],
        ['Descriptive', '5', 'Understand', 'CO2', 'Explain the differences between RISC and CISC architectures in embedded processors.', '', '', '', '', '']
      ];
      const ws = XLSX.utils.aoa_to_sheet(headers);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, "Questions Template");
      XLSX.writeFile(wb, "Question_Bank_Template.xlsx");
    }

    function handleQBankUpload(input) {
      if (!input.files || input.files.length === 0) return;
      const file = input.files[0];

      const container = document.getElementById('qbankCoGroups');
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-10">
          <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">Parsing Excel file...</p>
        </div>
      `;

      const reader = new FileReader();
      reader.onload = function(e) {
        try {
          const data = new Uint8Array(e.target.result);
          const workbook = XLSX.read(data, { type: 'array' });
          const firstSheetName = workbook.SheetNames[0];
          const worksheet = workbook.Sheets[firstSheetName];
          const rows = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
          
          if (rows.length < 2) {
            alert('Excel file is empty or missing data rows.');
            fetchQuestionBank(currentSubjectId);
            input.value = '';
            return;
          }

          container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-10">
              <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
              <p class="text-sm font-bold text-slate-400">Uploading and saving questions to pool...</p>
            </div>
          `;

          fetch(`/api/classroom/${currentSubjectId}/question-bank/upload-json`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ rows: rows })
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'SUCCESS') {
              alert(data.message || 'Questions imported successfully!');
            } else {
              alert('Upload failed: ' + (data.message || 'Unknown error'));
            }
            fetchQuestionBank(currentSubjectId);
            input.value = '';
          })
          .catch(err => {
            alert('Upload failed: server error');
            fetchQuestionBank(currentSubjectId);
            input.value = '';
          });

        } catch (err) {
          alert('Error reading Excel file: ' + err.message);
          fetchQuestionBank(currentSubjectId);
          input.value = '';
        }
      };
      
      reader.readAsArrayBuffer(file);
    }

    function seedQuestionBankWithAi(subjectId) {
      const container = document.getElementById('qbankCoGroups');
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-12">
          <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">AI is generating structured exam questions for all COs...</p>
        </div>
      `;

      fetch(`/api/classroom/${subjectId}/question-bank/seed-ai`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
        } else {
          alert('Failed to seed: ' + data.message);
        }
        fetchQuestionBank(subjectId);
      })
      .catch(err => {
        alert('Server error seeding question bank.');
        fetchQuestionBank(subjectId);
      });
    }
</script>

<!-- Virtual Classroom Students Modal -->
<div id="vcStudentsModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
  <div class="bg-slate-950 border border-slate-800/80 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[80vh]">
    <div class="p-4 border-b border-slate-800/80 bg-slate-900/50 flex justify-between items-center">
      <h3 class="text-[10px] font-black text-slate-200 flex items-center gap-2">
        <span class="material-symbols-rounded text-blue-400">groups</span> Enrolled Students
      </h3>
      <div class="flex items-center gap-3">
        <button onclick="printVcStudentsList()" class="text-[10px] font-bold text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 px-3 py-1.5 rounded flex items-center gap-1 transition-premium">
          <span class="material-symbols-rounded text-[14px]">print</span> Print List
        </button>
        <button onclick="closeVcStudentsList()" class="text-slate-500 hover:text-white transition-premium ml-2">
          <span class="material-symbols-rounded">close</span>
        </button>
      </div>
    </div>
    <div class="p-0 overflow-y-auto custom-scrollbar flex-1">
      <div id="vcStudentsListContent"></div>
    </div>
  </div>
</div>

</body>
</html>
