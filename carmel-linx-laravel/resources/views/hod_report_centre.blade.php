<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Report Centre - Carmel Linx</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,600,1,0" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .transition-premium {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    /* Enforce minimal 14px font policy across all elements */
    body, button, select, input, textarea, table, th, td, div, p, span, a {
      font-size: 14px !important;
    }
    h1, h2, h3, h4, h5, h6 {
      font-size: 16px !important;
      font-weight: 800 !important;
    }
    .card-gradient {
      background: linear-gradient(135deg, rgba(30, 41, 59, 0.4) 0%, rgba(15, 23, 42, 0.6) 100%);
    }
  </style>
</head>
<body class="bg-slate-950 text-slate-300 min-h-screen flex flex-col relative overflow-x-hidden selection:bg-amber-500/30">

  <!-- Sticky Header -->
  <header class="bg-slate-900/80 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-40 shadow-2xl">
    <div class="px-6 h-16 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <a href="/dashboard/hod" class="flex items-center gap-2 px-4 py-1.5 bg-amber-500/15 hover:bg-amber-500/30 border border-amber-500/40 hover:border-amber-400 text-amber-400 hover:text-amber-300 rounded-xl font-bold transition-premium no-underline">
          <span class="material-symbols-rounded text-base">arrow_back</span>
          <span class="text-sm">Back to HOD Console</span>
        </a>
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 text-white font-black rounded-lg w-8 h-8 flex items-center justify-center text-sm shadow-lg shadow-amber-500/20">RC</div>
        <div>
          <h1 class="font-extrabold text-slate-100 tracking-wide text-sm">Report Centre</h1>
          <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ session('userBranch') }} Department</p>
        </div>
      </div>
      
      <div class="flex items-center gap-3">
        <span class="px-3 py-1 bg-slate-800 text-slate-300 border border-slate-700 rounded-lg font-mono text-sm">
          Branch: {{ session('userBranch') }}
        </span>
      </div>
    </div>
  </header>

  <!-- Main Content Space -->
  <main class="flex-grow p-6 lg:p-10 max-w-7xl mx-auto w-full space-y-8">
    
    <!-- Hero Banner Section -->
    <div class="bg-gradient-to-r from-amber-500/10 via-orange-600/5 to-slate-950 border border-amber-500/20 rounded-3xl p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-xl">
      <div class="space-y-2">
        <h2 class="text-white text-lg font-black tracking-tight">Centralized Analytical Report Engine</h2>
        <p class="text-slate-400 max-w-2xl leading-relaxed text-sm">
          Pull historical records, download mentoring logs, track academic performance analytics, and view audit reports. All departmental intelligence is gathered here in real-time.
        </p>
      </div>
      <div class="flex-shrink-0">
        <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
          <span class="material-symbols-rounded text-3xl">insights</span>
        </div>
      </div>
    </div>

    <!-- Reports Directory Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      
      <!-- Card 1: Attendance & Log Analysis -->
      <div class="card-gradient border border-slate-800/80 rounded-2xl p-5 space-y-4 hover:border-sky-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-sky-400 text-2xl">co_present</span>
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-sky-500/15 border border-sky-500/30 text-sky-400 font-black text-sm">1</span>
            </div>
          </div>
          <h3 class="text-white text-sm font-black">Attendance, Log, Condonation</h3>
          <p class="text-slate-400 text-sm leading-relaxed">
            Consolidated reports of daily class logs, lesson plan coverage rates, and student attendance percentages by batch.
          </p>
        </div>
        <div class="pt-4 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-xs text-slate-500">Live Coverage Logs</span>
          <button onclick="openAttendanceModal()" class="px-3 py-1.5 bg-sky-500/15 hover:bg-sky-500/30 text-sky-400 hover:text-sky-300 border border-sky-500/30 hover:border-sky-400 rounded-xl font-bold transition-premium cursor-pointer text-sm">
            Compile Logs
          </button>
        </div>
      </div>

      <!-- Card 2: Remedial Session Analysis -->
      <div class="card-gradient border border-slate-800/80 rounded-2xl p-5 space-y-4 hover:border-purple-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-purple-400 text-2xl">psychology</span>
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-purple-500/15 border border-purple-500/30 text-purple-400 font-black text-sm">2</span>
            </div>
          </div>
          <h3 class="text-white text-sm font-black">Remedial coaching Analytics</h3>
          <p class="text-slate-400 text-sm leading-relaxed">
            Track diagnostics, active coaching rooms, weakness analysis, and student improvement outcomes for slower learners.
          </p>
        </div>
        <div class="pt-4 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-xs text-slate-500">Diagnostic Reports</span>
          <button onclick="openRemedialModal()" class="px-3 py-1.5 bg-purple-500/15 hover:bg-purple-500/30 text-purple-400 hover:text-purple-300 border border-purple-500/30 hover:border-purple-400 rounded-xl font-bold transition-premium cursor-pointer text-sm">
            Analyze Data
          </button>
        </div>
      </div>

      <!-- Card 3: Faculty Workload Report -->
      <div class="card-gradient border border-slate-800/80 rounded-2xl p-5 space-y-4 hover:border-amber-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-amber-500 text-2xl">pending_actions</span>
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-amber-500/15 border border-amber-500/30 text-amber-400 font-black text-sm">3</span>
            </div>
          </div>
          <h3 class="text-white text-sm font-black">Faculty Workload and Timetables</h3>
          <p class="text-slate-400 text-sm leading-relaxed">
            Consolidated workload hours for classroom lectures and laboratories per week across all department timetables.
          </p>
        </div>
        <div class="pt-4 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-xs text-slate-500">Commencement Week Submission</span>
          <a href="/hod/report-centre/workload-panel" class="px-3 py-1.5 bg-amber-500/15 hover:bg-amber-500/30 text-amber-400 hover:text-amber-300 border border-amber-500/30 hover:border-amber-400 rounded-xl font-bold transition-premium cursor-pointer text-sm no-underline inline-block">
            View Panel
          </a>
        </div>
      </div>

      <!-- Card 4: Extra-Curricular Claims -->
      <div class="card-gradient border border-slate-800/80 rounded-2xl p-5 space-y-4 hover:border-rose-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-rose-400 text-2xl">emoji_events</span>
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>
              <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-rose-500/15 border border-rose-500/30 text-rose-400 font-black text-sm">4</span>
            </div>
          </div>
          <h3 class="text-white text-sm font-black">Extra-Curricular Claims</h3>
          <p class="text-slate-400 text-sm leading-relaxed">
            Aggregate student activity point verifications, pending claims logs, and approved co-curricular achievement statuses.
          </p>
        </div>
        <div class="pt-4 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-xs text-slate-500">Activity Analytics</span>
          <button onclick="openActivityPointsModal()" class="px-3 py-1.5 bg-rose-500/15 hover:bg-rose-500/30 text-rose-400 hover:text-rose-300 border border-rose-500/30 hover:border-rose-400 rounded-xl font-bold transition-premium cursor-pointer text-sm">
            View Claims
          </button>
        </div>
      </div>

      <!-- Card 5: Department Course Files -->
      <div class="card-gradient border border-slate-800/80 rounded-2xl p-5 space-y-4 hover:border-emerald-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-emerald-400 text-2xl">folder_zip</span>
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>
              <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-450 font-black text-sm">5</span>
            </div>
          </div>
          <h3 class="text-white text-sm font-black">Department Course Files</h3>
          <p class="text-slate-400 text-sm leading-relaxed">
            Consolidated audits of subject syllabus progress, CO-PO mappings, assignment plans, and lesson plan submission compliance.
          </p>
        </div>
        <div class="pt-4 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-xs text-slate-500">Curriculum Compliance</span>
          <button onclick="openCourseFilesModal()" class="px-3 py-1.5 bg-emerald-500/15 hover:bg-emerald-500/30 text-emerald-400 hover:text-emerald-300 border border-emerald-500/30 hover:border-emerald-400 rounded-xl font-bold transition-premium cursor-pointer text-sm">
            Check Status
          </button>
        </div>
      </div>

      <!-- Card 6: Mentoring Diaries -->
      <div class="card-gradient border border-slate-800/80 rounded-2xl p-5 space-y-4 hover:border-amber-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-amber-400 text-2xl">book</span>
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-amber-500/15 border border-amber-500/30 text-amber-400 font-black text-sm">6</span>
            </div>
          </div>
          <h3 class="text-white text-sm font-black">Student Mentoring Diaries</h3>
          <p class="text-slate-400 text-sm leading-relaxed">
            Generate and export complete cumulative mentoring diaries, counselor notes, and personal records for students in your branch.
          </p>
        </div>
        <div class="pt-4 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-xs text-slate-500">PDF / Print Format</span>
          <button onclick="alert('Feature coming soon: Dynamic Mentoring Export will pull active records.')" class="px-3 py-1.5 bg-amber-500/15 hover:bg-amber-500/30 text-amber-400 hover:text-amber-300 border border-amber-500/30 hover:border-amber-400 rounded-xl font-bold transition-premium cursor-pointer text-sm">
            Access Logs
          </button>
        </div>
      </div>

      <!-- Card 7: SBTE Audit Console -->
      <div class="card-gradient border border-slate-800/80 rounded-2xl p-5 space-y-4 hover:border-sky-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-sky-400 text-2xl">verified_user</span>
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-sky-500/15 border border-sky-500/30 text-sky-400 font-black text-sm">7</span>
            </div>
          </div>
          <h3 class="text-white text-sm font-black">SBTE Annual Compliance Audit</h3>
          <p class="text-slate-400 text-sm leading-relaxed">
            Manage mandatory annual audit documentation, AICTE approval letters, affiliation orders, and board result registries.
          </p>
        </div>
        <div class="pt-4 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-xs text-slate-500">SBTE Accreditation</span>
          <a href="/hod/sbte-audit" class="px-3 py-1.5 bg-sky-500/15 hover:bg-sky-500/30 text-sky-400 hover:text-sky-300 border border-sky-500/30 hover:border-sky-400 rounded-xl font-bold transition-premium cursor-pointer text-sm no-underline inline-block">
            View Console
          </a>
        </div>
      </div>

      <!-- Card 8: NBA Criteria Audit Console -->
      <div class="card-gradient border border-slate-800/80 rounded-2xl p-5 space-y-4 hover:border-rose-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-rose-400 text-2xl">menu_book</span>
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-rose-500/15 border border-rose-500/30 text-rose-450 font-black text-sm">8</span>
            </div>
          </div>
          <h3 class="text-white text-sm font-black">NBA Criteria Accreditation Folders</h3>
          <p class="text-slate-400 text-sm leading-relaxed">
            Organize and review academic audit files and related documentation across NBA Criteria 1 to 10.
          </p>
        </div>
        <div class="pt-4 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-xs text-slate-500">NBA Criteria Audit</span>
          <a href="/hod/nba-audit" class="px-3 py-1.5 bg-rose-500/15 hover:bg-rose-500/30 text-rose-400 hover:text-rose-300 border border-rose-500/30 hover:border-rose-400 rounded-xl font-bold transition-premium cursor-pointer text-sm no-underline inline-block">
            View Console
          </a>
        </div>
      </div>

      <!-- Card 9: Academic Calendar Preparation -->
      <div class="card-gradient border border-slate-800/80 rounded-2xl p-5 space-y-4 hover:border-amber-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-amber-400 text-2xl">calendar_month</span>
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-amber-500/15 border border-amber-500/30 text-amber-450 font-black text-sm">9</span>
            </div>
          </div>
          <h3 class="text-white text-sm font-black">Academic Calendar Preparation</h3>
          <p class="text-slate-400 text-sm leading-relaxed">
            All semester department academic calendar details will be managed, scheduled, and configured here.
          </p>
        </div>
        <div class="pt-4 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-xs text-slate-500">Academic Planning</span>
          <a href="/hod/academic-calendar" class="px-3 py-1.5 bg-amber-500/15 hover:bg-amber-500/30 text-amber-400 hover:text-amber-300 border border-amber-500/30 hover:border-amber-400 rounded-xl font-bold transition-premium cursor-pointer text-sm no-underline inline-block">
            Open Planner
          </a>
        </div>
      </div>

      <!-- Card 10: Security & Operations Audit -->
      <div class="card-gradient border border-slate-800/80 rounded-2xl p-5 space-y-4 hover:border-violet-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-violet-400 text-2xl">receipt_long</span>
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-violet-500/15 border border-violet-500/30 text-violet-450 font-black text-sm">10</span>
            </div>
          </div>
          <h3 class="text-white text-sm font-black">Security & Operations Audit</h3>
          <p class="text-slate-400 text-sm leading-relaxed">
            Detailed department timeline of actions, password resets, registration changes, and critical security audits.
          </p>
        </div>
        <div class="pt-4 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-xs text-slate-500">Historical Audit Records</span>
          <button onclick="alert('Feature coming soon: Security Log Exports.')" class="px-3 py-1.5 bg-violet-500/15 hover:bg-violet-500/30 text-violet-400 hover:text-violet-300 border border-violet-500/30 hover:border-violet-400 rounded-xl font-bold transition-premium cursor-pointer text-sm">
            Extract Logs
          </button>
        </div>
      </div>

    </div>

  </main>

  <!-- ATTENDANCE MODAL -->
  <div id="attendanceModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800/80 pb-3">
        <h3 class="font-bold text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-sky-400 text-base">co_present</span> Attendance Summary
        </h3>
        <button onclick="closeAttendanceModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <div class="space-y-4">
        <p class="text-xs text-slate-400 leading-relaxed">
          Select a semester batch to generate the consolidated class attendance summary, lesson plan coverage rates, and condonation list.
        </p>
        <div class="space-y-3">
          <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5 font-bold">Select Semester Batch</label>
            <select id="selectAttendanceBatch" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-2 text-white outline-none text-sm">
              @foreach($batches as $batch)
                <option value="{{ $batch->classroom_id }}">{{ $batch->classroom_id }} (Sem {{ $batch->current_semester }})</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5 font-bold">Select Report Type</label>
            <select id="selectAttendanceReportType" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-2 text-white outline-none text-sm">
              <option value="coverage">Course Coverage Rates & Hours Conducted</option>
              <option value="roster">Student Attendance Roster & Deficiencies</option>
              <option value="condonation">Condonation Students List (SBTE No)</option>
            </select>
          </div>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeAttendanceModal()" class="flex-1 py-2 border border-slate-850 hover:bg-slate-800/60 rounded-xl font-bold transition-premium text-slate-300 text-sm cursor-pointer">
            Cancel
          </button>
          <button type="button" onclick="printAttendanceSummary()" class="flex-1 py-2 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white rounded-xl font-bold shadow-lg transition-premium flex items-center justify-center gap-2 text-sm cursor-pointer">
            <span class="material-symbols-rounded text-sm">print</span> Print Summary
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- REMEDIAL MODAL -->
  <div id="remedialModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800/80 pb-3">
        <h3 class="font-bold text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-purple-400 text-base">psychology</span> Remedial Analysis
        </h3>
        <button onclick="closeRemedialModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <div class="space-y-4">
        <p class="text-xs text-slate-400 leading-relaxed">
          Select a semester batch to generate the consolidated Remedial Session Analytics, conducted hours, and registered students list.
        </p>
        <div>
          <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5 font-bold">Select Semester Batch</label>
          <select id="selectRemedialBatch" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-2 text-white outline-none text-sm">
            @foreach($batches as $batch)
              <option value="{{ $batch->classroom_id }}">{{ $batch->classroom_id }} (Sem {{ $batch->current_semester }})</option>
            @endforeach
          </select>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRemedialModal()" class="flex-1 py-2 border border-slate-850 hover:bg-slate-800/60 rounded-xl font-bold transition-premium text-slate-300 text-sm cursor-pointer">
            Cancel
          </button>
          <button type="button" onclick="printRemedialReport()" class="flex-1 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white rounded-xl font-bold shadow-lg transition-premium flex items-center justify-center gap-2 text-sm cursor-pointer">
            <span class="material-symbols-rounded text-sm">print</span> Print Report
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- COURSE FILES MODAL -->
  <div id="courseFilesModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800/80 pb-3">
        <h3 class="font-bold text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-emerald-400 text-base">folder_zip</span> Course Files Status
        </h3>
        <button onclick="closeCourseFilesModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <div class="space-y-4">
        <p class="text-xs text-slate-400 leading-relaxed">
          Select a semester batch to generate the consolidated syllabus registry, CO-PO mapping, and NBA Course File compliance status report.
        </p>
        <div>
          <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5 font-bold">Select Semester Batch</label>
          <select id="selectCourseFilesBatch" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-2 text-white outline-none text-sm">
            @foreach($batches as $batch)
              <option value="{{ $batch->classroom_id }}">{{ $batch->classroom_id }} (Sem {{ $batch->current_semester }})</option>
            @endforeach
          </select>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeCourseFilesModal()" class="flex-1 py-2 border border-slate-850 hover:bg-slate-800/60 rounded-xl font-bold transition-premium text-slate-300 text-sm cursor-pointer">
            Cancel
          </button>
          <button type="button" onclick="printCourseFilesReport()" class="flex-1 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl font-bold shadow-lg transition-premium flex items-center justify-center gap-2 text-sm cursor-pointer">
            <span class="material-symbols-rounded text-sm">print</span> Print Report
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ACTIVITY POINTS MODAL -->
  <div id="activityPointsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800/80 pb-3">
        <h3 class="font-bold text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-rose-400 text-base">emoji_events</span> Activity Points Report
        </h3>
        <button onclick="closeActivityPointsModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <div class="space-y-4">
        <p class="text-xs text-slate-400 leading-relaxed">
          Generate semester-wise or batch-wise student activity points audits showing target thresholds for course completion.
        </p>
        
        <div class="space-y-3">
          <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5 font-bold">Select Semester Batch</label>
            <select id="selectActivityBatch" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-2 text-white outline-none text-sm">
              @foreach($batches as $batch)
                <option value="{{ $batch->classroom_id }}">{{ $batch->classroom_id }} (Sem {{ $batch->current_semester }})</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5 font-bold">Select Semester Scope</label>
            <select id="selectActivitySemester" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-2 text-white outline-none text-sm">
              <option value="all">All Semesters (Cumulative)</option>
              <option value="1">Semester 1</option>
              <option value="2">Semester 2</option>
              <option value="3">Semester 3</option>
              <option value="4">Semester 4</option>
              <option value="5">Semester 5</option>
              <option value="6">Semester 6</option>
            </select>
          </div>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeActivityPointsModal()" class="flex-1 py-2 border border-slate-850 hover:bg-slate-800/60 rounded-xl font-bold transition-premium text-slate-300 text-sm cursor-pointer">
            Cancel
          </button>
          <button type="button" onclick="printActivityPointsReport()" class="flex-1 py-2 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white rounded-xl font-bold shadow-lg transition-premium flex items-center justify-center gap-2 text-sm cursor-pointer">
            <span class="material-symbols-rounded text-sm">print</span> Print Report
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Sticky Footer -->
  <footer class="bg-slate-900 border-t border-slate-800/80 py-4 text-center text-slate-500 text-xs mt-auto">
    <p>&copy; 2026 Carmel Linx - Report Centre Engine. All rights reserved.</p>
  </footer>

  <script>
    function openAttendanceModal() {
      const modal = document.getElementById('attendanceModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeAttendanceModal() {
      const modal = document.getElementById('attendanceModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function printAttendanceSummary() {
      const batchId = document.getElementById('selectAttendanceBatch').value;
      const reportType = document.getElementById('selectAttendanceReportType').value;
      if (!batchId) {
        alert('Please select a batch.');
        return;
      }
      closeAttendanceModal();
      window.open('/hod/attendance-summary/print?classroom_id=' + encodeURIComponent(batchId) + '&report_type=' + encodeURIComponent(reportType), '_blank');
    }

    function openRemedialModal() {
      const modal = document.getElementById('remedialModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeRemedialModal() {
      const modal = document.getElementById('remedialModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function printRemedialReport() {
      const batchId = document.getElementById('selectRemedialBatch').value;
      if (!batchId) {
        alert('Please select a batch.');
        return;
      }
      closeRemedialModal();
      window.open('/hod/remedial-report/print?classroom_id=' + encodeURIComponent(batchId), '_blank');
    }

    function openCourseFilesModal() {
      const modal = document.getElementById('courseFilesModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeCourseFilesModal() {
      const modal = document.getElementById('courseFilesModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function printCourseFilesReport() {
      const batchId = document.getElementById('selectCourseFilesBatch').value;
      if (!batchId) {
        alert('Please select a batch.');
        return;
      }
      closeCourseFilesModal();
      window.open('/hod/course-files-report/print?classroom_id=' + encodeURIComponent(batchId), '_blank');
    }

    function openActivityPointsModal() {
      const modal = document.getElementById('activityPointsModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeActivityPointsModal() {
      const modal = document.getElementById('activityPointsModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function printActivityPointsReport() {
      const batchId = document.getElementById('selectActivityBatch').value;
      const sem = document.getElementById('selectActivitySemester').value;
      if (!batchId) {
        alert('Please select a batch.');
        return;
      }
      closeActivityPointsModal();
      window.open('/hod/activity-points-report/print?classroom_id=' + encodeURIComponent(batchId) + '&semester=' + encodeURIComponent(sem), '_blank');
    }
  </script>

</body>
</html>
