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
  <div class="max-w-7xl mx-auto space-y-4">
    
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

        <!-- Back Button -->
        <a href="/dashboard/lecturer" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white rounded-lg font-bold text-xs transition-all border border-slate-700 cursor-pointer flex items-center gap-1.5">
          <span class="material-symbols-rounded text-xs">arrow_back</span>
          Go Back
        </a>
      </div>
    </div>

    <!-- SUBJECT META CARD / TITLE PANEL (COMPACT) -->
    <div class="bg-panel border rounded-xl p-5 shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-xl font-bold text-title flex items-center gap-2">
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
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
      
      <!-- NAVIGATION PANEL (COMPACT) -->
      <div class="lg:col-span-1 space-y-3">
        <div class="bg-panel border rounded-xl p-3 shadow-md space-y-1">
          <button onclick="switchTab('outline')" id="btn-outline" class="w-full text-left px-3 py-2.5 rounded-lg font-bold text-xs flex items-center gap-2 transition-all bg-emerald-500/10 text-emerald-400 border-l-2 border-emerald-500">
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
      <div class="lg:col-span-3">
        
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
                        <td class="p-3 pl-4 font-bold text-emerald-500">{{ $co['id'] }}</td>
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
                        <td class="p-4.5 pl-4 font-bold text-title text-base">Module {{ $mod['module_id'] }}</td>
                        <td class="p-4.5 text-title font-mono font-bold text-base">{{ $mod['hours'] ?? floor($totalHours / 4) }} Hours</td>
                        <td class="p-4.5 pr-4 text-muted leading-relaxed font-semibold text-base">{{ $mod['content'] }}</td>
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
            <div class="space-x-1">
              <button class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold border border-slate-700 transition-all cursor-pointer">
                Load Template
              </button>
              <button class="px-2.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-md">
                Add Session Topic
              </button>
            </div>
          </div>

          <!-- PLANNER TABLE -->
          <div class="border border-card rounded-xl overflow-hidden bg-slate-950/10">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                  <th class="p-3">Period</th>
                  <th class="p-3">CO Tag</th>
                  <th class="p-3">Topic</th>
                  <th class="p-3">Pedagogy</th>
                  <th class="p-3 text-right">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-card text-xs">
                @forelse($lessonPlans as $lp)
                  <tr class="bg-card-hover transition-all">
                    <td class="p-3 font-mono font-bold text-title">{{ $lp->day_no }}</td>
                    <td class="p-3"><span class="px-1.5 py-0.5 bg-emerald-500/10 text-emerald-500 rounded text-xs font-bold border border-emerald-500/20">{{ $lp->co_id }}</span></td>
                    <td class="p-3 font-bold text-title">{{ $lp->topic_content }}</td>
                    <td class="p-3 text-muted">{{ $lp->pedagogy }}</td>
                    <td class="p-3 text-right">
                      <span class="px-1.5 py-0.5 bg-slate-800 text-slate-400 rounded-lg text-xs font-bold select-none">{{ $lp->status }}</span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="p-6 text-center text-muted italic">No lesson plan topics registered yet.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- TAB: CONTINUOUS INTERNAL ASSESSMENT -->
        <div id="tab-cia" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 hidden">
          <div class="border-b border-slate-800/30 pb-3">
            <h3 class="text-base font-bold text-title flex items-center gap-2">
              <span class="material-symbols-rounded text-violet-400">fact_check</span>
              Continuous Internal Assessment (CIA)
            </h3>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Attendance Card -->
            <div class="bg-panel border border-card rounded-xl p-4 space-y-2">
              <div class="flex justify-between items-center border-b border-card pb-1.5">
                <span class="font-bold text-title text-xs">Attendance</span>
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
                <span class="font-bold text-title text-xs">Self-Learning</span>
                <span class="text-xs bg-emerald-500/10 text-emerald-450 border border-emerald-500/20 px-1.5 py-0.5 rounded font-bold">15M Max</span>
              </div>
              <p class="text-xs text-muted leading-relaxed">Average of self-learning modules, quizzes, and micro-tasks across modules.</p>
              <button class="w-full py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-sm">
                Assignments
              </button>
            </div>

            <!-- Series Exams Card -->
            <div class="bg-panel border border-card rounded-xl p-4 space-y-2">
              <div class="flex justify-between items-center border-b border-card pb-1.5">
                <span class="font-bold text-title text-xs">Series Exams</span>
                <span class="text-xs bg-purple-500/10 text-purple-400 border border-purple-500/20 px-1.5 py-0.5 rounded font-bold">20M Max</span>
              </div>
              <p class="text-xs text-muted leading-relaxed">Two written examinations covering all defined course outcomes (COs).</p>
              <button class="w-full py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-sm">
                Manage Exams
              </button>
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

      </div>

    </div>

  </div>

  <script>
    function switchTab(tabId) {
      document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.add('hidden');
      });
      document.getElementById('tab-' + tabId).classList.remove('hidden');

      const tabs = ['outline', 'planner', 'cia', 'roster'];
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
  </script>
</body>
</html>
