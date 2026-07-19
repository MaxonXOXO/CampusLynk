<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Virtual Classroom (Theory) REV-2026</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons & Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Outfit', sans-serif;
      transition: background-color 0.3s, color 0.3s;
    }
    
    /* Dark Mode (Default) */
    body.dark {
      background-color: #0b0f19;
      color: #f1f5f9;
    }
    body.dark .bg-panel {
      background-color: rgba(15, 23, 42, 0.4);
      border-color: rgba(30, 41, 59, 0.8);
    }
    body.dark .text-title {
      color: #f1f5f9;
    }
    body.dark .text-muted {
      color: #94a3b8;
    }
    body.dark .border-card {
      border-color: rgba(30, 41, 59, 0.6);
    }
    body.dark .bg-card-hover:hover {
      background-color: rgba(15, 23, 42, 0.6);
    }

    /* Light Mode */
    body.light {
      background-color: #f8fafc;
      color: #0f172a;
    }
    body.light .bg-panel {
      background-color: #ffffff;
      border-color: #e2e8f0;
      box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05);
    }
    body.light .text-title {
      color: #0f172a;
    }
    body.light .text-muted {
      color: #475569;
    }
    body.light .border-card {
      border-color: #e2e8f0;
    }
    body.light .bg-card-hover:hover {
      background-color: #f1f5f9;
    }

    input, select, textarea {
      font-size: 0.875rem !important; /* 14px minimum */
    }
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: rgba(15, 23, 42, 0.1);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: rgba(148, 163, 184, 0.3);
      border-radius: 9999px;
    }
  </style>
</head>
<body class="dark min-h-screen p-4 custom-scrollbar">

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
  @endphp

  <!-- TOP COMPACT BANNER -->
  <div class="w-full max-w-none px-6 space-y-4">
    
    <!-- TOP LOGO & CONTROLS HEADER (COMPACT) -->
    <div class="flex flex-wrap justify-between items-center bg-panel border rounded-xl px-4 py-2.5 gap-3 shadow-md">
      <!-- Left: Logo & App Title -->
      <div class="flex items-center gap-3">
        <img src="/logo.jpg" class="w-10 h-10 rounded-xl object-cover shadow-md">
        <div>
          <div class="text-base font-bold tracking-tight text-title flex items-center gap-2">
            <span>Carmel Linx</span>
            <span class="text-sm font-bold px-2 py-0.5 bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 rounded">REV-2026</span>
          </div>
          <p class="text-xs text-muted font-bold uppercase tracking-wider">Lecturer Console</p>
        </div>
      </div>

      <!-- Right: AI Badge, Mode Toggle, Lecturer Profile & Back Button -->
      <div class="flex items-center gap-3">
        <!-- AI / DB Badge -->
        <span class="px-2.5 py-1 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-md font-bold text-xs select-none flex items-center gap-1">
          <span class="material-symbols-rounded text-sm">database</span>
          Local Extract Active
        </span>

        <!-- Dark/Light Mode Toggle -->
        <button onclick="toggleTheme()" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-all cursor-pointer border border-slate-700/50" title="Toggle Dark/Light Mode">
          <span id="theme-icon" class="material-symbols-rounded text-sm block">light_mode</span>
        </button>

        <!-- Lecturer Profile -->
        <div class="flex items-center gap-1.5 border-l border-slate-700/60 pl-3">
          <span class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 text-sm font-bold border border-slate-700">
            {{ substr(Session::get('userName', 'L'), 0, 1) }}
          </span>
          <div class="hidden sm:block text-left">
            <p class="text-xs font-bold text-title leading-tight">{{ Session::get('userName', 'Lecturer') }}</p>
            <p class="text-xs text-muted leading-none">Subject Staff</p>
          </div>
        </div>

        <button onclick="toggleSidebarWideMode()" id="btn-fullscreen-toggle" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-lg font-bold text-xs transition-all border border-sky-500/20 cursor-pointer flex items-center gap-1.5 shadow-sm">
          <span class="material-symbols-rounded text-xs">fullscreen</span>
          Fullscreen Mode
        </button>
        @php
          $role = Session::get('userRole');
          $backUrl = '/dashboard/lecturer';
          if ($role === 'HOD') $backUrl = '/dashboard/hod';
          elseif ($role === 'Admin') $backUrl = '/dashboard/admin';
          elseif ($role === 'Principal') $backUrl = '/dashboard/principal';
          elseif ($role === 'Super_Admin') $backUrl = '/dashboard/superadmin';
          elseif ($role === 'Gen_Dept_Coordinator_Aided') $backUrl = '/dashboard/general-coordinator-aided';
          elseif ($role === 'Gen_Dept_Coordinator_Self_Finance') $backUrl = '/dashboard/general-coordinator-sf';
        @endphp
        <a href="{{ $backUrl }}" onclick="localStorage.removeItem('classroomFullscreen'); window.close(); setTimeout(function(){ window.location.href = '{{ $backUrl }}'; }, 100); return false;" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-bold text-xs transition-all border border-rose-500/20 cursor-pointer flex items-center gap-1.5 shadow-sm">
          <span class="material-symbols-rounded text-xs">arrow_back</span>
          Go Back
        </a>
      </div>
    </div>

    <!-- SUBJECT META CARD / TITLE PANEL (COMPACT) -->
    <div class="bg-panel border rounded-xl p-5 shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-xl font-bold bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent flex items-center gap-2">
          Virtual Classroom (Theory)
        </h1>
        <p class="text-sm text-muted font-medium flex items-center gap-2 mt-1">
          <span class="material-symbols-rounded text-sm">auto_stories</span>
          <span class="font-bold text-title">{{ $batchSubject->subject_name }}</span>
          <span class="px-2 py-0.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded font-mono text-xs">{{ $batchSubject->subject_code }}</span>
        </p>
      </div>
      <div class="flex flex-wrap items-center gap-3 text-sm font-bold text-muted">
        <span class="flex items-center gap-1"><span class="material-symbols-rounded text-sm">groups</span>{{ $batchSubject->classroom_id }}</span>
        <span>•</span>
        <span class="flex items-center gap-1"><span class="material-symbols-rounded text-sm">calendar_month</span>Semester {{ $batchSubject->semester }}</span>
      </div>
    </div>

    <!-- MAIN GRID LAYOUT -->
    <div id="main-classroom-grid" class="grid grid-cols-1 lg:grid-cols-4 gap-4">
      
      <!-- NAVIGATION PANEL (COMPACT) -->
      <div id="sidebar-panel-column" class="lg:col-span-1 space-y-3 transition-all duration-300">
        <div class="bg-panel border rounded-xl p-3 shadow-md space-y-1">
          <button onclick="switchTab('outline')" id="btn-outline" class="w-full text-left px-3 py-2.5 rounded-lg font-bold text-xs flex items-center gap-2 transition-all bg-emerald-500/10 text-emerald-450 border-l-2 border-emerald-500">
            <span class="material-symbols-rounded text-sm">import_contacts</span>
            Course Outline
          </button>
          
          <button onclick="switchTab('planner')" id="btn-planner" class="w-full text-left px-3 py-2.5 rounded-lg font-bold text-xs flex items-center gap-2 transition-all text-muted hover:bg-slate-900/40">
            <span class="material-symbols-rounded text-sm">calendar_month</span>
            Lesson Planner
          </button>
          
          <button onclick="switchTab('cia')" id="btn-cia" class="w-full text-left px-3 py-2.5 rounded-lg font-bold text-xs flex items-center gap-2 transition-all text-muted hover:bg-slate-900/40">
            <span class="material-symbols-rounded text-sm">fact_check</span>
            Continuous Assessment
          </button>
          
          <button onclick="switchTab('roster')" id="btn-roster" class="w-full text-left px-3 py-2.5 rounded-lg font-bold text-xs flex items-center gap-2 transition-all text-muted hover:bg-slate-900/40">
            <span class="material-symbols-rounded text-sm">group</span>
            Student Roster ({{ $students->count() }})
          </button>

          <button onclick="switchTab('series')" id="btn-series" class="w-full text-left px-3 py-2.5 rounded-lg font-bold text-xs flex items-center gap-2 transition-all text-muted hover:bg-slate-900/40">
            <span class="material-symbols-rounded text-sm">quiz</span>
            Series Exams
          </button>
        </div>

        <!-- QUICK SNAPSHOT WIDGET -->
        <div class="bg-panel border rounded-xl p-4 space-y-3 shadow-md">
          <h4 class="font-bold text-title text-xs uppercase tracking-wider">Evaluation Policy</h4>
          <div class="space-y-2 text-xs">
            <div class="flex justify-between border-b border-slate-800/40 pb-1.5">
              <span class="text-muted">CIA Max Marks:</span>
              <span class="font-bold text-title">{{ $cieMarks }} Marks</span>
            </div>
            <div class="flex justify-between border-b border-slate-800/40 pb-1.5">
              <span class="text-muted">ESE Max Marks:</span>
              <span class="font-bold text-title">{{ $eseMarks }} Marks</span>
            </div>
            <div class="flex justify-between border-b border-slate-800/40 pb-1.5">
              <span class="text-muted">Syllabus Credits:</span>
              <span class="font-bold text-title">{{ $credit }} Credits</span>
            </div>
            <div class="flex justify-between border-b border-slate-800/40 pb-1.5">
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
        <div id="tab-outline" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4">
          <div class="flex justify-between items-center border-b border-slate-800/30 pb-3">
            <div>
              <h3 class="text-base font-bold text-title flex items-center gap-2">
                <span class="material-symbols-rounded text-emerald-400">import_contacts</span>
                Syllabus & Course Outline
              </h3>
            </div>
            <div class="flex items-center gap-2">
              @if($courseFile->syllabus_pdf_path)
                <a href="/storage/{{ $courseFile->syllabus_pdf_path }}" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white rounded-lg text-xs font-bold transition-all cursor-pointer border border-slate-700/80 flex items-center gap-1.5">
                  <span class="material-symbols-rounded text-sm">picture_as_pdf</span>
                  Preview PDF
                </a>
              @endif
              <button onclick="document.getElementById('syllabusFileInput').click()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1">
                <span class="material-symbols-rounded text-xs">upload_file</span>
                Upload PDF
              </button>
            </div>
            <input type="file" id="syllabusFileInput" accept="application/pdf" class="hidden" onchange="performSyllabusUpload(this)">
          </div>

          <!-- PARSED CONTENTS TABLES -->
          <div class="space-y-6">
            <!-- CO Details Table -->
            <div class="bg-panel border border-card rounded-xl p-4 space-y-3">
              <h4 class="font-bold text-title text-sm flex items-center gap-1.5 border-b border-slate-850 pb-1.5">
                <span class="material-symbols-rounded text-emerald-450 text-sm">stars</span>
                Course Outcomes (COs)
              </h4>
              <div class="border border-card rounded-lg overflow-hidden bg-slate-950/10 text-sm">
                <table class="w-full text-left border-collapse">
                  <thead>
                    <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                      <th class="p-3 pl-4">Outcome ID</th>
                      <th class="p-3">Cognitive Level</th>
                      <th class="p-3">Duration</th>
                      <th class="p-3 pr-4">Description</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-card text-sm">
                    @foreach($cosList as $co)
                      <tr class="hover:bg-slate-900/10">
                        <td class="p-3 pl-4 font-medium text-emerald-500">{{ $co['id'] }}</td>
                        <td class="p-3 text-title font-medium">{{ $co['cognitive_level'] ?? 'Understanding' }}</td>
                        <td class="p-3 text-title font-mono">{{ $co['duration'] ?? '12' }} Periods</td>
                        <td class="p-3 pr-4 text-muted leading-relaxed">{{ $co['description'] }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Modules Table (Major Topics) -->
            <div class="bg-panel border border-card rounded-xl p-4 space-y-3">
              <h4 class="font-bold text-title text-sm flex items-center gap-1.5 border-b border-slate-850 pb-1.5">
                <span class="material-symbols-rounded text-emerald-450 text-sm">collections_bookmark</span>
                Course Modules & Major Topics
              </h4>
              <div class="border border-card rounded-lg overflow-hidden bg-slate-950/10 text-sm">
                <table class="w-full text-left border-collapse">
                  <thead>
                    <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                      <th class="p-3 pl-4">Module No</th>
                      <th class="p-3">Instructional Hours</th>
                      <th class="p-3 pr-4">Major Topics Description</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-card text-sm">
                    @foreach($modulesList as $mod)
                      <tr class="hover:bg-slate-900/10">
                        <td class="p-4.5 pl-4 font-medium text-title text-base">Module {{ $mod['module_id'] }}</td>
                        <td class="p-4.5 text-title font-mono font-medium text-base">{{ $mod['hours'] ?? floor($totalHours / 4) }} Hours</td>
                        <td class="p-4.5 pr-4 text-muted leading-relaxed font-normal text-base">{{ $mod['content'] ?? '' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- CO-PO Mapping Matrix -->
          <div class="bg-panel border border-card rounded-xl p-4 space-y-3">
            <h4 class="font-bold text-title text-sm flex items-center gap-1.5 border-b border-slate-850 pb-1.5">
              <span class="material-symbols-rounded text-indigo-400 text-xs">grid_on</span>
              CO-PO Correlation Matrix
            </h4>
            <div class="border border-card rounded-lg overflow-hidden bg-slate-950/10 text-xs">
              <table class="w-full text-center border-collapse">
                <thead>
                  <tr class="bg-slate-900/30 text-[10px] font-bold text-muted uppercase tracking-wider border-b border-card">
                    <th class="p-2.5 text-left pl-4">Course Outcome</th>
                    @for($p = 1; $p <= 11; $p++)
                      <th class="p-2.5">PO{{ $p }}</th>
                    @endfor
                  </tr>
                </thead>
                <tbody class="divide-y divide-card">
                  @foreach($cosList as $co)
                    @php
                      $coId = $co['id'];
                      $m = $mappings[$coId] ?? [];
                    @endphp
                    <tr class="hover:bg-slate-900/10">
                      <td class="p-2.5 text-left font-bold text-title pl-4 text-sm">{{ $coId }}</td>
                      @for($p = 1; $p <= 11; $p++)
                        <td class="p-2.5 font-bold text-indigo-500 text-sm">{{ $m["PO$p"] ?? '-' }}</td>
                      @endfor
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- TAB: LESSON PLANNER -->
        <div id="tab-planner" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 hidden">
          <div class="flex justify-between items-center border-b border-slate-800/30 pb-3">
            <div>
              <h3 class="text-base font-bold text-title flex items-center gap-2">
                <span class="material-symbols-rounded text-indigo-400">calendar_month</span>
                Academic Lesson Planner
              </h3>
            </div>
            <div class="flex items-center gap-2">
              <a href="/r26/classroom/lesson-plan/print/{{ $batchSubject->id }}" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white rounded-lg text-xs font-medium border border-slate-700 transition-all cursor-pointer flex items-center gap-1.5">
                <span class="material-symbols-rounded text-sm">print</span>
                Print Lesson Plan
              </a>
              <button id="btnSaveTemplate" onclick="saveAsTemplate()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-medium transition-all cursor-pointer shadow-sm">
                Save as Template
              </button>
              <button id="btnSavePlanner" onclick="saveLessonPlanEdits()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-medium transition-all cursor-pointer shadow-sm">
                Save Changes
              </button>
            </div>
          </div>

          <!-- PLANNER TABLE -->
          <div class="border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[900px]">
              <thead>
                <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                  <th class="p-3 w-[6%] text-center">Period</th>
                  <th class="p-3 w-[8%] text-center">CO Tag</th>
                  <th class="p-3 w-[30%]">Topic / Content Scheduled (Full Preview)</th>
                  <th class="p-3 w-[10%]">Pedagogy</th>
                  <th class="p-3 w-[10%]">Taxonomy</th>
                  <th class="p-3 w-[12%]">Proposed Date</th>
                  <th class="p-3 w-[12%]">Actual Date</th>
                  <th class="p-3 w-[6%]">Hours</th>
                  <th class="p-3 w-[6%]">Status</th>
                </tr>
              </thead>
              <tbody id="plannerTableBody" class="divide-y divide-card text-sm font-normal">
                @forelse($lessonPlans as $lp)
                  <tr data-lp-id="{{ $lp->id }}" class="bg-card-hover transition-all font-normal">
                    <td class="p-2.5 font-mono text-center text-title">{{ $lp->day_no }}</td>
                    <td class="p-2.5 text-center">
                      <span class="px-1.5 py-0.5 bg-emerald-500/10 text-emerald-500 rounded text-xs border border-emerald-500/20 font-medium">{{ $lp->co_id }}</span>
                    </td>
                    <td class="p-2.5">
                      <textarea data-field="topic_content" rows="2" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal text-xs resize-y">{{ $lp->topic_content }}</textarea>
                    </td>
                    <td class="p-2.5">
                      <select data-field="pedagogy" class="w-full bg-slate-950 border border-slate-800 rounded px-1.5 py-1 text-slate-300 focus:border-indigo-500 outline-none font-normal">
                        <option value="Lecture" {{ $lp->pedagogy == 'Lecture' ? 'selected' : '' }}>Lecture</option>
                        <option value="Tutorial" {{ $lp->pedagogy == 'Tutorial' ? 'selected' : '' }}>Tutorial</option>
                        <option value="Practical" {{ $lp->pedagogy == 'Practical' ? 'selected' : '' }}>Practical</option>
                        <option value="Exam" {{ $lp->pedagogy == 'Exam' ? 'selected' : '' }}>Exam</option>
                      </select>
                    </td>
                    <td class="p-2.5">
                      <input type="text" data-field="taxonomy" value="{{ $lp->taxonomy }}" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal text-xs" placeholder="Taxonomy Level...">
                    </td>
                    <td class="p-2.5">
                      <input type="date" data-field="proposed_date" value="{{ $lp->proposed_date }}" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal">
                    </td>
                    <td class="p-2.5">
                      <input type="date" data-field="actual_date" value="{{ $lp->actual_date }}" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal">
                    </td>
                    <td class="p-2.5">
                      <input type="number" data-field="allocated_hours" value="{{ $lp->allocated_hours ?: 1 }}" min="1" max="10" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal">
                    </td>
                    <td class="p-2.5">
                      <select data-field="status" class="w-full bg-slate-950 border border-slate-800 rounded px-1.5 py-1 text-slate-300 focus:border-indigo-500 outline-none font-normal">
                        <option value="Pending" {{ $lp->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ $lp->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                      </select>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9" class="p-6 text-center text-muted italic font-normal">No lesson plan topics registered yet.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- TAB: CONTINUOUS INTERNAL ASSESSMENT -->
        <div id="tab-cia" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 hidden">
          
          <!-- SUB-VIEW 1: THREE CARDS VIEW (DEFAULT) -->
          <div id="cia-cards-view" class="space-y-4">
            <div class="flex justify-between items-center border-b border-slate-800/30 pb-3">
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
                  <span class="text-xs bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 px-1.5 py-0.5 rounded font-bold">5M Max</span>
                </div>
                <p class="text-xs text-muted leading-relaxed">Automatically evaluated based on student class logs attendance metrics.</p>
                <button class="w-full py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold border border-slate-750 transition-all cursor-pointer">
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
                  <span class="text-xs bg-purple-500/10 text-purple-400 border border-purple-500/20 px-1.5 py-0.5 rounded font-bold">20M Max</span>
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
            <div class="flex justify-between items-center border-b border-slate-800/30 pb-3">
              <div>
                <h3 class="text-base font-bold text-title flex items-center gap-2">
                  <span class="material-symbols-rounded text-emerald-450">local_library</span>
                  Self-Learning Activities Marksheet (CO-wise)
                </h3>
                <p class="text-xs text-muted mt-1">
                  Assign self-learning marks (Max 15 per CO) for each Course Outcome. The average of all 4 CO marks will automatically determine the final Self-Learning Marks (out of 15 max) in the consolidated marksheet.
                </p>
              </div>
              <div class="flex items-center gap-2">
                <button onclick="toggleCiaView('cards')" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-medium transition-all border border-slate-750 cursor-pointer flex items-center gap-1">
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
              <button type="button" onclick="switchSelfLearningTab('CO2')" id="tabbtn-sl-CO2" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-muted hover:bg-slate-900/40">CO2 Self-Study</button>
              <button type="button" onclick="switchSelfLearningTab('CO3')" id="tabbtn-sl-CO3" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-muted hover:bg-slate-900/40">CO3 Self-Study</button>
              <button type="button" onclick="switchSelfLearningTab('CO4')" id="tabbtn-sl-CO4" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-muted hover:bg-slate-900/40">CO4 Self-Study</button>
              <button type="button" onclick="switchSelfLearningTab('Summary')" id="tabbtn-sl-Summary" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-muted hover:bg-slate-900/40">Summary Sheet</button>
            </div>

            <!-- Max Marks Configuration Panels -->
            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
              <div id="sl-config-{{ $coTag }}" class="sl-config-panel bg-slate-900/20 border border-card rounded-xl p-3 flex flex-wrap gap-4 items-center text-xs">
                <span class="font-bold text-title uppercase">{{ $coTag }} Max Marks Configuration (Sum must equal 15):</span>
                <div class="flex items-center gap-1.5">
                  <span class="text-muted">Assignment:</span>
                  <input type="number" step="0.5" id="cfg-{{ $coTag }}-assignment" value="{{ $selfLearningConfigs[$coTag]['assignment'] ?? 5.0 }}" class="w-12 bg-slate-950 border border-slate-800 rounded px-1 text-center font-bold text-title focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                </div>
                <div class="flex items-center gap-1.5">
                  <span class="text-muted">MCQ Test:</span>
                  <input type="number" step="0.5" id="cfg-{{ $coTag }}-mcq" value="{{ $selfLearningConfigs[$coTag]['mcq'] ?? 5.0 }}" class="w-12 bg-slate-950 border border-slate-800 rounded px-1 text-center font-bold text-title focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                </div>
                <div class="flex items-center gap-1.5">
                  <select id="cfg-{{ $coTag }}-act3_mode" class="bg-slate-950 border border-slate-800 rounded px-1 text-title text-xs focus:border-indigo-500 outline-none">
                    <option value="Case Study" {{ ($selfLearningConfigs[$coTag]['act3_mode'] ?? '') == 'Case Study' ? 'selected' : '' }}>Case Study</option>
                    <option value="Activity" {{ ($selfLearningConfigs[$coTag]['act3_mode'] ?? '') == 'Activity' ? 'selected' : '' }}>Activity/Seminar</option>
                    <option value="Minor Project" {{ ($selfLearningConfigs[$coTag]['act3_mode'] ?? '') == 'Minor Project' ? 'selected' : '' }}>Minor Project</option>
                    <option value="Exercises" {{ ($selfLearningConfigs[$coTag]['act3_mode'] ?? '') == 'Exercises' ? 'selected' : '' }}>Exercises</option>
                  </select>
                  <input type="number" step="0.5" id="cfg-{{ $coTag }}-act3" value="{{ $selfLearningConfigs[$coTag]['act3'] ?? 5.0 }}" class="w-12 bg-slate-950 border border-slate-800 rounded px-1 text-center font-bold text-title focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                </div>
                <div class="flex items-center gap-1.5">
                  <select id="cfg-{{ $coTag }}-act4_mode" class="bg-slate-950 border border-slate-800 rounded px-1 text-title text-xs focus:border-indigo-500 outline-none">
                    <option value="Case Study" {{ ($selfLearningConfigs[$coTag]['act4_mode'] ?? '') == 'Case Study' ? 'selected' : '' }}>Case Study</option>
                    <option value="Activity" {{ ($selfLearningConfigs[$coTag]['act4_mode'] ?? '') == 'Activity' ? 'selected' : '' }}>Activity/Seminar</option>
                    <option value="Minor Project" {{ ($selfLearningConfigs[$coTag]['act4_mode'] ?? '') == 'Minor Project' ? 'selected' : '' }}>Minor Project</option>
                    <option value="Exercises" {{ ($selfLearningConfigs[$coTag]['act4_mode'] ?? '') == 'Exercises' ? 'selected' : '' }}>Exercises</option>
                  </select>
                  <input type="number" step="0.5" id="cfg-{{ $coTag }}-act4" value="{{ $selfLearningConfigs[$coTag]['act4'] ?? 0.0 }}" class="w-12 bg-slate-950 border border-slate-800 rounded px-1 text-center font-bold text-title focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                </div>
                <div class="flex items-center gap-1.5">
                  <select id="cfg-{{ $coTag }}-act5_mode" class="bg-slate-950 border border-slate-800 rounded px-1 text-title text-xs focus:border-indigo-500 outline-none">
                    <option value="Case Study" {{ ($selfLearningConfigs[$coTag]['act5_mode'] ?? '') == 'Case Study' ? 'selected' : '' }}>Case Study</option>
                    <option value="Activity" {{ ($selfLearningConfigs[$coTag]['act5_mode'] ?? '') == 'Activity' ? 'selected' : '' }}>Activity/Seminar</option>
                    <option value="Minor Project" {{ ($selfLearningConfigs[$coTag]['act5_mode'] ?? '') == 'Minor Project' ? 'selected' : '' }}>Minor Project</option>
                    <option value="Exercises" {{ ($selfLearningConfigs[$coTag]['act5_mode'] ?? '') == 'Exercises' ? 'selected' : '' }}>Exercises</option>
                  </select>
                  <input type="number" step="0.5" id="cfg-{{ $coTag }}-act5" value="{{ $selfLearningConfigs[$coTag]['act5'] ?? 0.0 }}" class="w-12 bg-slate-950 border border-slate-800 rounded px-1 text-center font-bold text-title focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                </div>
                <span id="cfg-{{ $coTag }}-status" class="font-bold text-emerald-500"></span>
                <button type="button" onclick="openAssignmentModal('{{ $coTag }}')" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-xs font-bold transition-all ml-auto cursor-pointer shadow-sm flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">assignment</span>
                  Manage Assignments
                </button>
              </div>
            @endforeach

            <!-- CO-wise Entry Sheets -->
            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
              <div id="sl-table-container-{{ $coTag }}" class="sl-table-container border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar hidden">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                  <thead>
                    <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                      <th class="p-3 w-[6%] text-center">Roll No</th>
                      <th class="p-3 w-[12%]">Register No</th>
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
                        <td class="p-2.5 font-mono text-title">{{ $sc['reg_no'] }}</td>
                        <td class="p-2.5 text-title font-medium">{{ $sc['name'] }}</td>
                        
                        <td class="p-2.5 text-center relative">
                          @if(($sc['co_details'][$coTag]['submission_status'] ?? '') === 'Submitted')
                            <div class="absolute top-1 right-2 flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500" title="Assignment Submitted - Grade Now"></span>
                            </div>
                          @endif
                          <input type="number" step="0.5" min="0" data-field="assignment" value="{{ $sc['co_details'][$coTag]['assignment'] ?? 0.0 }}" class="w-20 bg-slate-950/50 border {{ ($sc['co_details'][$coTag]['submission_status'] ?? '') === 'Submitted' ? 'border-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.4)]' : 'border-slate-800' }} rounded px-2 py-0.5 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center">
                          <input type="number" step="0.5" min="0" data-field="mcq" value="{{ $sc['co_details'][$coTag]['mcq'] ?? 0.0 }}" class="w-20 bg-slate-950/50 border border-slate-800 rounded px-2 py-0.5 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center">
                          <input type="number" step="0.5" min="0" data-field="act3" value="{{ $sc['co_details'][$coTag]['act3'] ?? 0.0 }}" class="w-20 bg-slate-950/50 border border-slate-800 rounded px-2 py-0.5 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center">
                          <input type="number" step="0.5" min="0" data-field="act4" value="{{ $sc['co_details'][$coTag]['act4'] ?? 0.0 }}" class="w-20 bg-slate-950/50 border border-slate-800 rounded px-2 py-0.5 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center">
                          <input type="number" step="0.5" min="0" data-field="act5" value="{{ $sc['co_details'][$coTag]['act5'] ?? 0.0 }}" class="w-20 bg-slate-950/50 border border-slate-800 rounded px-2 py-0.5 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center font-mono text-emerald-400 font-bold text-base" data-field="co_total">
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
            <div id="sl-table-container-Summary" class="sl-table-container border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar hidden">
              <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                  <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                    <th class="p-3 w-[6%] text-center">Roll No</th>
                    <th class="p-3 w-[12%]">Register No</th>
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
                      <td class="p-2.5 font-mono text-title">{{ $sc['reg_no'] }}</td>
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
            <div class="flex justify-between items-center border-b border-slate-800/30 pb-3">
              <div>
                <h3 class="text-base font-bold text-title flex items-center gap-2">
                  <span class="material-symbols-rounded text-violet-400">table_chart</span>
                  Consolidated CIA Marks Sheet
                </h3>
                <p class="text-xs text-muted mt-1">
                  Attendance is fetched from class logs. Marks are mapped out of 5 based on Table 2.1 (90%+ = 5M, 85%+ = 4M, 80%+ = 3M, 75%+ = 2M, &lt;75% = 0M).
                </p>
              </div>
              <div class="flex items-center gap-2">
                <button onclick="toggleCiaView('cards')" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-medium transition-all border border-slate-750 cursor-pointer flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">arrow_back</span>
                  Back to Categories
                </button>
                <button id="btnSaveCia" onclick="saveCiaMarks()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-medium transition-all cursor-pointer shadow-sm">
                  Save CIA Marks
                </button>
              </div>
            </div>

            <div class="border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar">
              <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                  <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                    <th class="p-3 w-[6%] text-center">Roll No</th>
                    <th class="p-3 w-[12%]">Register No</th>
                    <th class="p-3 w-[25%]">Student Name</th>
                    <th class="p-3 w-[12%] text-center">Attendance %</th>
                    <th class="p-3 w-[12%] text-center">Attendance Marks (5M)</th>
                    <th class="p-3 w-[12%] text-center">Self Learning (15M)</th>
                    <th class="p-3 w-[12%] text-center">Series Exams (20M)</th>
                    <th class="p-3 w-[10%] text-center">Total CIA (40M)</th>
                  </tr>
                </thead>
                <tbody id="ciaTableBody" class="divide-y divide-card text-sm font-normal">
                  @forelse($studentCiaData as $sc)
                    <tr data-reg-no="{{ $sc['reg_no'] }}" class="bg-card-hover transition-all font-normal">
                      <td class="p-2.5 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                      <td class="p-2.5 font-mono text-title">{{ $sc['reg_no'] }}</td>
                      <td class="p-2.5 text-title font-medium">{{ $sc['name'] }}</td>
                      <td class="p-2.5 text-center font-mono text-title">{{ $sc['attendance_percent'] }}%</td>
                      <td class="p-2.5 text-center font-mono text-emerald-500 font-bold" data-val-attendance="{{ $sc['attendance_marks'] }}">
                        {{ $sc['attendance_marks'] }}
                      </td>
                      <td class="p-2.5 text-center">
                        <input type="number" step="0.5" min="0" max="15" data-field="self_learning" value="{{ $sc['self_learning_marks'] }}" class="w-20 bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal" oninput="calculateRowCia(this)">
                      </td>
                      <td class="p-2.5 text-center">
                        <input type="number" step="0.5" min="0" max="20" data-field="series_exam" value="{{ $sc['series_exam_marks'] }}" class="w-20 bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal" oninput="calculateRowCia(this)">
                      </td>
                      <td class="p-2.5 text-center font-mono text-indigo-400 font-bold text-base" data-field="total_cia">
                        {{ $sc['total_cia'] }}
                      </td>
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
        </div>

        <!-- TAB: STUDENT ROSTER -->
        <div id="tab-roster" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 hidden">
          <div class="border-b border-slate-800/30 pb-3">
            <h3 class="text-base font-bold text-title flex items-center gap-2">
              <span class="material-symbols-rounded text-sky-400">group</span>
              Student Enrollment Directory
            </h3>
          </div>

          <div class="border border-card rounded-xl overflow-hidden bg-slate-950/10">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                  <th class="p-3">Roll No</th>
                  <th class="p-3">Register No</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3">SBTE ID</th>
                  <th class="p-3 text-right">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-card text-xs">
                @forelse($students as $student)
                  <tr class="bg-card-hover transition-all">
                    <td class="p-3 font-mono font-bold text-muted">{{ $student->roll_no ?? '-' }}</td>
                    <td class="p-3 font-mono text-title">{{ $student->reg_no }}</td>
                    <td class="p-3 font-bold text-title">{{ $student->name }}</td>
                    <td class="p-3 font-mono text-muted">{{ $student->sbte_reg_no ?? 'Unassigned' }}</td>
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
          <div class="border-b border-slate-800/30 pb-3 flex justify-between items-center">
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
            <div class="bg-slate-900/10 border border-card rounded-xl p-6 text-center space-y-4 max-w-2xl mx-auto my-8">
              <span class="material-symbols-rounded text-4xl text-sky-450">tune</span>
              <h4 class="font-bold text-title text-sm">Configure Series Examination Pattern</h4>
              <p class="text-xs text-muted leading-relaxed">
                Please select the examination pattern according to the syllabus requirements. You can conduct 4 independent single-CO tests (25 marks each) or 2 combined-CO tests (50 marks each).
              </p>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <label class="border border-card hover:border-sky-500/30 rounded-xl p-4 cursor-pointer block text-left bg-slate-950/20 space-y-2">
                  <input type="radio" name="series-mode-select" value="single_co" checked class="text-sky-500 focus:ring-sky-500">
                  <span class="font-bold text-title text-xs block">4 Single-CO Tests (25M each)</span>
                  <span class="text-[11px] text-muted block leading-snug">
                    Conduct one separate exam for each CO (CO1 to CO4). Exam duration is 1 hour. Total marks scaled to 20.
                  </span>
                </label>
                
                <label class="border border-card hover:border-sky-500/30 rounded-xl p-4 cursor-pointer block text-left bg-slate-950/20 space-y-2">
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  @foreach($seriesExams as $exam)
                    <div class="bg-slate-900/10 border border-card rounded-xl p-4 flex flex-col justify-between space-y-3 relative overflow-hidden">
                      
                      <!-- Header -->
                      <div class="flex justify-between items-start">
                        <div>
                          <h5 class="font-bold text-title text-xs">{{ $exam->exam_name }}</h5>
                          <p class="text-[11px] text-muted mt-0.5">
                            CO Tags: {{ implode(', ', $exam->co_tags) }} | Marks: {{ $exam->max_marks }}M | Duration: {{ $exam->duration_minutes }} min
                          </p>
                        </div>
                        @if($exam->locked)
                          <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-450 border border-emerald-500/20 rounded text-[10px] font-bold flex items-center gap-0.5">
                            <span class="material-symbols-rounded text-[11px]">lock</span> Locked & Published
                          </span>
                        @else
                          <span class="px-2 py-0.5 bg-sky-500/10 text-sky-405 border border-sky-500/20 rounded text-[10px] font-bold">
                            Drafting Mode
                          </span>
                        @endif
                      </div>

                      <!-- Actions -->
                      <div class="flex gap-2 flex-wrap pt-2 border-t border-slate-800/30">
                        <button onclick='openSeriesBuilderModal({{ $exam->id }}, "{{ addslashes($exam->exam_name) }}", "{{ $exam->mode }}", {{ json_encode($exam->co_tags) }}, {{ $exam->max_marks }})' class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-[11px] font-bold transition-all cursor-pointer flex items-center gap-1">
                          <span class="material-symbols-rounded text-xs">edit_document</span> Build QP
                        </button>
                        <a href="/r26/classroom/series-exams/{{ $exam->id }}/print-qp" target="_blank" class="px-2.5 py-1 bg-slate-850 hover:bg-slate-800 text-slate-200 border border-slate-700 rounded text-[11px] font-bold transition-all flex items-center gap-1">
                          <span class="material-symbols-rounded text-xs">print</span> Print QP
                        </a>
                        <a href="/r26/classroom/series-exams/{{ $exam->id }}/print-scheme" target="_blank" class="px-2.5 py-1 bg-slate-850 hover:bg-slate-800 text-slate-200 border border-slate-700 rounded text-[11px] font-bold transition-all flex items-center gap-1">
                          <span class="material-symbols-rounded text-xs">description</span> Print Scheme
                        </a>
                        @if(!$exam->locked)
                          <button onclick="lockAndPublishSeries({{ $exam->id }})" class="px-2.5 py-1 bg-violet-650 hover:bg-violet-750 text-white rounded text-[11px] font-bold transition-all cursor-pointer flex items-center gap-1">
                            <span class="material-symbols-rounded text-xs">publish</span> Lock & Notify
                          </button>
                        @endif
                      </div>

                    </div>
                  @endforeach
                </div>
              </div>

              <!-- Marks Entry Panel -->
              <div class="space-y-3">
                <div class="flex justify-between items-center">
                  <h4 class="font-bold text-title text-xs uppercase tracking-wider">Series Exam detailed marksheet</h4>
                  <button id="btnSaveSeriesMarks" onclick="saveSeriesExamMarks()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-md flex items-center gap-1">
                    <span class="material-symbols-rounded text-xs font-bold">save</span> Save Series Marks
                  </button>
                </div>

                <div class="border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar">
                  <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                      <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
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
                                     class="w-20 bg-slate-950/50 border border-slate-800 rounded px-2 py-0.5 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal text-xs series-mark-input"
                                     oninput="recalculateSeriesRow(this)">
                            </td>
                          @endforeach
                          <td class="p-3 text-center font-mono text-emerald-400 font-bold text-base" data-field="series-scaled-total">
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

      </div>

    </div>

  </div>

  <script>
    function switchTab(tabId) {
      localStorage.setItem('activeClassroomTab', tabId);
      document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.add('hidden');
      });
      document.getElementById('tab-' + tabId).classList.remove('hidden');

      const tabs = ['outline', 'planner', 'cia', 'roster', 'series'];
      tabs.forEach(id => {
        const btn = document.getElementById('btn-' + id);
        if (id === tabId) {
          btn.className = "w-full text-left px-3 py-2.5 rounded-lg font-bold text-xs flex items-center gap-2 transition-all bg-emerald-500/10 text-emerald-450 border-l-2 border-emerald-500";
        } else {
          btn.className = "w-full text-left px-3 py-2.5 rounded-lg font-bold text-xs flex items-center gap-2 transition-all text-muted hover:bg-slate-900/40";
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

    function performSyllabusUpload(input) {
      if (!input.files || input.files.length === 0) return;
      const file = input.files[0];
      const formData = new FormData();
      formData.append('syllabus_file', file);
      
      // CSRF token
      const token = "{{ csrf_token() }}";
      formData.append('_token', token);

      const btnText = document.querySelector('button[onclick*="syllabusFileInput"]');
      const originalText = btnText.innerHTML;
      btnText.disabled = true;
      btnText.innerHTML = '<span class="material-symbols-rounded text-xs animate-spin">sync</span> Uploading...';

      fetch('/api/r26/classroom/{{ $batchSubject->id }}/syllabus', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        btnText.disabled = false;
        btnText.innerHTML = originalText;
        if (data.status === 'SUCCESS') {
          alert('Syllabus uploaded and parsed successfully!');
          window.location.reload();
        } else {
          alert('Upload failed: ' + data.message);
        }
      })
      .catch(err => {
        btnText.disabled = false;
        btnText.innerHTML = originalText;
        alert('Upload Error: ' + err.message);
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
          btn.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-muted hover:bg-slate-900/40 cursor-pointer";
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
        statusEl.innerText = "✓ Valid (Total: 15M)";
        statusEl.className = "font-bold text-emerald-500 ml-auto";
        updateActivityHeaders(co);
        return true;
      } else {
        statusEl.innerText = "⚠ Warning: Sum is " + total + "M (Must be 15M)";
        statusEl.className = "font-bold text-rose-500 ml-auto animate-pulse";
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
        btn.className = "px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-bold text-xs transition-all border border-amber-500/20 cursor-pointer flex items-center gap-1.5 shadow-sm";
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
        btn.className = "px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-lg font-bold text-xs transition-all border border-sky-500/20 cursor-pointer flex items-center gap-1.5 shadow-sm";
        localStorage.setItem('classroomFullscreen', 'false');
      } else {
        sidebar.classList.add('hidden');
        details.className = "lg:col-span-4 transition-all duration-300";
        btn.innerHTML = `<span class="material-symbols-rounded text-xs">fullscreen_exit</span> Exit Fullscreen`;
        btn.className = "px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-bold text-xs transition-all border border-amber-500/20 cursor-pointer flex items-center gap-1.5 shadow-sm";
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
      const editor = document.querySelector('#assignment-modal .bg-slate-50');
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
        btnLock.className = "px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-xs font-medium transition-all flex items-center gap-1 cursor-pointer border-0";
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
        tr.className = "bg-white hover:bg-slate-50 border-b border-slate-100 transition-all font-normal text-slate-800";
        tr.innerHTML = `
          <td class="p-2.5 font-mono text-center text-slate-900">${idx + 1}</td>
          <td class="p-2.5 text-slate-900 font-normal leading-relaxed text-left">${q.question}</td>
          <td class="p-2.5 text-center text-slate-900 font-medium">${q.bt_level}</td>
          <td class="p-2.5 text-center font-mono text-emerald-600 font-bold">${q.marks}M</td>
          <td class="p-2.5 text-slate-550 font-normal leading-relaxed text-left">${q.scheme || '—'}</td>
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
  <div id="assignment-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 hidden">
    <div class="bg-white border border-slate-200 rounded-xl w-full max-w-6xl p-6 shadow-2xl space-y-6 max-h-[95vh] overflow-y-auto custom-scrollbar text-slate-800">
      <div class="flex justify-between items-center border-b border-slate-200 pb-3">
        <div class="flex items-center gap-2">
          <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
            <span class="material-symbols-rounded text-indigo-600">assignment</span>
            Manage Assignment - <span id="assignment-modal-co-title">CO1</span>
          </h3>
          <span id="modal-lock-badge" class="ml-2 px-2 py-0.5 bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 text-xs font-bold rounded flex items-center gap-0.5 hidden">
            <span class="material-symbols-rounded text-xs">lock</span> Published & Locked
          </span>
        </div>
        <div class="flex items-center gap-3">
          <button type="button" id="btn-notify-assignment" onclick="notifyStudentsAssignment()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-xs font-medium transition-all flex items-center gap-1 cursor-pointer border-0 shadow-sm">
            <span class="material-symbols-rounded text-xs">lock</span> Lock & Notify
          </button>
          <button type="button" onclick="closeAssignmentModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer border-0 bg-transparent flex items-center">
            <span class="material-symbols-rounded">close</span>
          </button>
        </div>
      </div>

      <!-- Stacked Editor Section (Full Width) -->
      <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 space-y-4">
        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Add/Edit Question</h4>
        <div class="space-y-3">
          <div>
            <label class="block text-xs text-slate-600 mb-1 font-bold">Question Description:</label>
            <textarea id="modal-q-text" rows="5" class="w-full bg-white border border-slate-350 rounded-lg px-3 py-2 text-slate-900 text-sm focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none font-normal" placeholder="Type assignment question description here..."></textarea>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-xs text-slate-600 mb-1 font-bold">Max Marks:</label>
              <input type="number" id="modal-q-marks" value="5" class="w-full bg-white border border-slate-350 rounded-lg px-3 py-1.5 text-slate-900 text-sm text-center focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none font-normal">
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1 font-bold">Taxonomy Level:</label>
              <select id="modal-q-bt" class="w-full bg-white border border-slate-350 rounded-lg px-3 py-1.5 text-slate-900 text-sm focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none font-normal">
                <option value="Remember">Remember</option>
                <option value="Understand">Understand</option>
                <option value="Apply">Apply</option>
                <option value="Analyze">Analyze</option>
                <option value="Evaluate">Evaluate</option>
                <option value="Create">Create</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1 font-bold">Scheme of Evaluation / Hints:</label>
              <textarea id="modal-q-scheme" rows="1" class="w-full bg-white border border-slate-350 rounded-lg px-3 py-1 text-slate-900 text-sm focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none font-normal" placeholder="E.g., Formula 1M, steps 3M..."></textarea>
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1 font-bold">Due Date:</label>
              <input type="date" id="modal-assignment-due-date" class="w-full bg-white border border-slate-350 rounded-lg px-3 py-1 text-slate-900 text-sm focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none font-normal">
            </div>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <button type="button" onclick="autoGenerateFromBank()" class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-lg text-xs font-bold border border-slate-300 transition-all cursor-pointer flex items-center gap-1">
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
          <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Active Questions Table</h4>
          <!-- Print & Notify Action Panel -->
          <div class="flex gap-2">
            <a href="#" id="btn-print-qp" target="_blank" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-xs font-medium border border-slate-300 transition-all flex items-center gap-1">
              <span class="material-symbols-rounded text-xs font-normal">print</span> Print Assignment Questions
            </a>
            <a href="#" id="btn-print-scheme" target="_blank" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-xs font-medium border border-slate-300 transition-all flex items-center gap-1">
              <span class="material-symbols-rounded text-xs font-normal">description</span> Print Scheme
            </a>
          </div>
        </div>

        <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50">
          <table class="w-full text-left border-collapse bg-white">
            <thead>
              <tr class="bg-slate-100 text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">
                <th class="p-3 w-[6%] text-center border-r border-slate-200">No.</th>
                <th class="p-3 border-r border-slate-200">Question Description</th>
                <th class="p-3 w-[15%] text-center border-r border-slate-200">Cognitive Level (BT)</th>
                <th class="p-3 w-[12%] text-center border-r border-slate-200">Marks</th>
                <th class="p-3 w-[25%] border-r border-slate-200">Evaluation Scheme</th>
                <th class="p-3 w-[8%] text-center">Action</th>
              </tr>
            </thead>
            <tbody id="modal-questions-table-body" class="divide-y divide-slate-100 text-sm font-normal text-slate-800">
              <!-- Rendered dynamically -->
            </tbody>
          </table>
        </div>
      </div>

      <div class="flex justify-end gap-2 border-t border-slate-200 pt-3">
        <button type="button" onclick="closeAssignmentModal()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-medium transition-all cursor-pointer border-0">Cancel</button>
        <button type="button" onclick="saveAssignmentQuestions()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-medium transition-all cursor-pointer border-0">Save Questions</button>
      </div>
    </div>
  </div>

  <!-- SERIES EXAMS BUILDER MODAL POPUP -->
  <div id="series-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 hidden">
    <div class="bg-white border border-slate-200 rounded-xl w-full max-w-6xl p-6 shadow-2xl space-y-6 max-h-[95vh] overflow-y-auto custom-scrollbar text-slate-800">
      
      <!-- Modal Header -->
      <div class="flex justify-between items-center border-b border-slate-200 pb-3">
        <div class="flex items-center gap-2">
          <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
            <span class="material-symbols-rounded text-sky-600">quiz</span>
            Build Series Exam - <span id="series-modal-title">Series Exam 1</span>
          </h3>
          <span id="series-lock-badge" class="ml-2 px-2 py-0.5 bg-emerald-500/10 text-emerald-550 border border-emerald-500/20 text-xs font-bold rounded flex items-center gap-0.5 hidden">
            <span class="material-symbols-rounded text-xs">lock</span> Published & Locked
          </span>
        </div>
        <div class="flex items-center gap-3">
          <button type="button" id="btn-lock-series" onclick="lockActiveSeries()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-xs font-medium transition-all flex items-center gap-1 cursor-pointer border-0 shadow-sm">
            <span class="material-symbols-rounded text-xs">lock</span> Lock & Notify
          </button>
          <button type="button" onclick="closeSeriesModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer border-0 bg-transparent flex items-center">
            <span class="material-symbols-rounded">close</span>
          </button>
        </div>
      </div>

      <!-- Parts Selection Tabs -->
      <div class="flex gap-2 border-b border-slate-200 pb-1">
        <button onclick="switchSeriesPart('Part A')" id="tabbtn-partA" class="px-3 py-1.5 rounded-t-lg text-xs font-bold transition-all border-b-2 border-transparent text-slate-600 hover:bg-slate-100 cursor-pointer">Part A (1M)</button>
        <button onclick="switchSeriesPart('Part B')" id="tabbtn-partB" class="px-3 py-1.5 rounded-t-lg text-xs font-bold transition-all border-b-2 border-transparent text-slate-600 hover:bg-slate-100 cursor-pointer">Part B (3M)</button>
        <button onclick="switchSeriesPart('Part C')" id="tabbtn-partC" class="px-3 py-1.5 rounded-t-lg text-xs font-bold transition-all border-b-2 border-transparent text-slate-600 hover:bg-slate-100 cursor-pointer">Part C (7M)</button>
      </div>

      <!-- Stacked Editor Section -->
      <div id="series-editor-panel" class="bg-slate-50 border border-slate-200 rounded-xl p-5 space-y-4">
        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Add Question to <span id="series-editor-part-title">Part A</span></h4>
        <div class="space-y-3">
          <div>
            <label class="block text-xs text-slate-600 mb-1 font-bold">Question Description:</label>
            <textarea id="series-q-text" rows="4" class="w-full bg-white border border-slate-350 rounded-lg px-3 py-2 text-slate-900 text-sm focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none font-normal" placeholder="Type question description here..."></textarea>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-xs text-slate-600 mb-1 font-bold">Max Marks:</label>
              <input type="number" id="series-q-marks" readonly value="1" class="w-full bg-slate-100 border border-slate-300 rounded-lg px-3 py-1.5 text-slate-500 text-sm text-center outline-none font-normal">
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1 font-bold">Target CO Tag:</label>
              <select id="series-q-co" class="w-full bg-white border border-slate-350 rounded-lg px-3 py-1.5 text-slate-900 text-sm focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none font-normal">
                <!-- Populated dynamically based on exam COs -->
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1 font-bold">Taxonomy Level:</label>
              <select id="series-q-bt" class="w-full bg-white border border-slate-350 rounded-lg px-3 py-1.5 text-slate-900 text-sm focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none font-normal">
                <option value="Remember">Remember</option>
                <option value="Understand" selected>Understand</option>
                <option value="Apply">Apply</option>
                <option value="Analyze">Analyze</option>
                <option value="Evaluate">Evaluate</option>
                <option value="Create">Create</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1 font-bold">Scheme of Evaluation / Hints:</label>
              <textarea id="series-q-scheme" rows="1" class="w-full bg-white border border-slate-350 rounded-lg px-3 py-1 text-slate-900 text-sm focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none font-normal" placeholder="E.g., Correct definition 1M..."></textarea>
            </div>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <button type="button" onclick="autoGenerateSeriesQuestion()" class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-lg text-xs font-bold border border-slate-300 transition-all cursor-pointer flex items-center gap-1">
              <span class="material-symbols-rounded text-xs">psychology</span> Suggest from Q-Bank
            </button>
            <button type="button" onclick="addQuestionToSeriesList()" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer border-0 flex items-center gap-1">
              <span class="material-symbols-rounded text-xs">add</span> Add to List
            </button>
          </div>
        </div>
      </div>

      <!-- Table Grid View for Questions -->
      <div class="space-y-3">
        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Active Questions Table for <span id="series-table-part-title">Part A</span></h4>
        <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50">
          <table class="w-full text-left border-collapse bg-white">
            <thead>
              <tr class="bg-slate-100 text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">
                <th class="p-3 w-[6%] text-center border-r border-slate-200">No.</th>
                <th class="p-3 border-r border-slate-200">Question Description</th>
                <th class="p-3 w-[10%] text-center border-r border-slate-200">CO Tag</th>
                <th class="p-3 w-[15%] text-center border-r border-slate-200">Cognitive Level (BT)</th>
                <th class="p-3 w-[12%] text-center border-r border-slate-200">Marks</th>
                <th class="p-3 w-[25%] border-r border-slate-200">Evaluation Scheme</th>
                <th class="p-3 w-[8%] text-center">Action</th>
              </tr>
            </thead>
            <tbody id="series-questions-table-body" class="divide-y divide-slate-100 text-sm font-normal text-slate-800">
              <!-- Rendered dynamically -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Footer Actions -->
      <div class="flex justify-end gap-2 border-t border-slate-200 pt-3">
        <button type="button" onclick="closeSeriesModal()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-medium transition-all cursor-pointer border-0">Cancel</button>
        <button type="button" id="btn-save-series-qp" onclick="saveSeriesExamQuestions()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-medium transition-all cursor-pointer border-0">Save Questions</button>
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

      // Populate allowed CO selector
      const coSelect = document.getElementById('series-q-co');
      coSelect.innerHTML = '';
      coTags.forEach(co => {
        const opt = document.createElement('option');
        opt.value = co;
        opt.innerText = co;
        coSelect.appendChild(opt);
      });

      // Apply locked states
      const isLocked = !!(examRecord && examRecord.locked);
      applySeriesLockState(isLocked);

      switchSeriesPart('Part A');

      document.getElementById('series-modal').classList.remove('hidden');
    }

    function closeSeriesModal() {
      document.getElementById('series-modal').classList.add('hidden');
    }

    function applySeriesLockState(isLocked) {
      const editor = document.getElementById('series-editor-panel');
      const btnLock = document.getElementById('btn-lock-series');
      const btnSave = document.getElementById('btn-save-series-qp');
      const lockBadge = document.getElementById('series-lock-badge');

      if (isLocked) {
        editor.classList.add('opacity-60', 'pointer-events-none');
        btnLock.disabled = true;
        btnLock.innerHTML = `<span class="material-symbols-rounded text-xs">lock</span> Locked`;
        btnLock.className = "px-3 py-1.5 bg-emerald-600/10 text-emerald-550 border border-emerald-500/20 rounded text-xs font-medium cursor-not-allowed border-0";
        if (btnSave) btnSave.classList.add('hidden');
        if (lockBadge) {
          lockBadge.classList.remove('hidden');
          lockBadge.style.display = 'inline-flex';
        }
      } else {
        editor.classList.remove('opacity-60', 'pointer-events-none');
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

    function switchSeriesPart(partName) {
      activeSeriesPart = partName;
      
      // Update Part sub-tabs styles
      ['Part A', 'Part B', 'Part C'].forEach(part => {
        const btn = document.getElementById('tabbtn-part' + part.replace(' ', ''));
        if (part === partName) {
          btn.className = "px-3 py-1.5 rounded-t-lg text-xs font-bold transition-all border-b-2 border-indigo-600 bg-slate-50 text-indigo-600 cursor-pointer";
        } else {
          btn.className = "px-3 py-1.5 rounded-t-lg text-xs font-bold transition-all border-b-2 border-transparent text-slate-600 hover:bg-slate-100 cursor-pointer";
        }
      });

      // Update titles
      document.getElementById('series-editor-part-title').innerText = partName;
      document.getElementById('series-table-part-title').innerText = partName;

      // Update max marks field rule
      const marksField = document.getElementById('series-q-marks');
      if (partName === 'Part A') marksField.value = 1;
      else if (partName === 'Part B') marksField.value = 3;
      else if (partName === 'Part C') marksField.value = 7;

      renderSeriesQuestionsList();
    }

    function renderSeriesQuestionsList() {
      const container = document.getElementById('series-questions-table-body');
      container.innerHTML = '';

      const list = seriesQuestionsList[activeSeriesPart] || [];
      if (list.length === 0) {
        container.innerHTML = '<tr><td colspan="7" class="p-4 text-center text-slate-500 italic font-normal">No questions added to this part yet.</td></tr>';
        return;
      }

      // Check if locked
      const examRecord = dbSeriesExams.find(ex => ex.id === activeSeriesExamId);
      const isLocked = !!(examRecord && examRecord.locked);

      list.forEach((q, idx) => {
        const tr = document.createElement('tr');
        tr.className = "bg-white hover:bg-slate-50 border-b border-slate-100 transition-all font-normal text-slate-800";
        tr.innerHTML = `
          <td class="p-2.5 font-mono text-center text-slate-900">${idx + 1}</td>
          <td class="p-2.5 text-slate-900 font-normal leading-relaxed text-left">${q.question}</td>
          <td class="p-2.5 text-center text-slate-900 font-medium">${q.co_tag}</td>
          <td class="p-2.5 text-center text-slate-900 font-medium">${q.bt_level}</td>
          <td class="p-2.5 text-center font-mono text-emerald-600 font-bold">${q.marks}M</td>
          <td class="p-2.5 text-slate-550 font-normal leading-relaxed text-left">${q.scheme || '—'}</td>
          <td class="p-2.5 text-center">
            ${isLocked ? `<span class="text-slate-400 font-bold text-xs">Locked</span>` : `
            <button type="button" onclick="deleteSeriesQuestion(${idx})" class="text-rose-500 hover:text-rose-600 cursor-pointer border-0 bg-transparent">
              <span class="material-symbols-rounded text-sm">delete</span>
            </button>
            `}
          </td>
        `;
        container.appendChild(tr);
      });
    }

    function addQuestionToSeriesList() {
      const text = document.getElementById('series-q-text').value.trim();
      const marks = parseInt(document.getElementById('series-q-marks').value) || 1;
      const co = document.getElementById('series-q-co').value;
      const bt = document.getElementById('series-q-bt').value;
      const scheme = document.getElementById('series-q-scheme').value.trim();

      if (!text) {
        alert("Please type a question description.");
        return;
      }

      seriesQuestionsList[activeSeriesPart].push({
        question: text,
        marks: marks,
        co_tag: co,
        bt_level: bt,
        scheme: scheme
      });

      renderSeriesQuestionsList();

      // Clear inputs
      document.getElementById('series-q-text').value = '';
      document.getElementById('series-q-scheme').value = '';
    }

    function deleteSeriesQuestion(idx) {
      seriesQuestionsList[activeSeriesPart].splice(idx, 1);
      renderSeriesQuestionsList();
    }

    function autoGenerateSeriesQuestion() {
      const mockQuestions = [
        { question: "Define the basic term and list characteristics in " + activeSeriesPart + ".", bt_level: "Remember", scheme: "Correct definition (1M)" },
        { question: "Explain the working principle and block diagram for " + activeSeriesPart + " criteria.", bt_level: "Understand", scheme: "Block diagram 2M, explanation 3M" },
        { question: "Apply the mathematical formulation to evaluate standard " + activeSeriesPart + " outcome.", bt_level: "Apply", scheme: "Formula 1M, steps 4M, calculation 2M" },
        { question: "Analyze the difference between standard layout outcomes in " + activeSeriesPart + ".", bt_level: "Analyze", scheme: "Comparison points listed clearly" }
      ];

      const randomQ = mockQuestions[Math.floor(Math.random() * mockQuestions.length)];
      
      document.getElementById('series-q-text').value = randomQ.question;
      document.getElementById('series-q-bt').value = randomQ.bt_level;
      document.getElementById('series-q-scheme').value = randomQ.scheme;

      alert("Suggested question generated from general question bank pool!");
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
  </script>
</body>
</html>
