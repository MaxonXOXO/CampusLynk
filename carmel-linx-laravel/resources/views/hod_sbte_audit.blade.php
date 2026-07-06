<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SBTE Academic Audit Console - Carmel Linx</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #020617;
    }
    .custom-gradient-bg {
      background: radial-gradient(circle at top left, rgba(14, 116, 144, 0.15), transparent 40%),
                  radial-gradient(circle at bottom right, rgba(99, 102, 241, 0.1), transparent 40%);
    }
  </style>
</head>
<body class="text-slate-100 min-h-screen flex flex-col custom-gradient-bg relative selection:bg-cyan-500/30 selection:text-cyan-200">

  <!-- Header Panel -->
  <header class="border-b border-slate-900 bg-slate-950/80 backdrop-blur-md sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="/hod/report-centre" class="text-slate-400 hover:text-white transition flex items-center">
          <span class="material-symbols-rounded">arrow_back</span>
        </a>
        <h1 class="text-base font-black tracking-tight text-white uppercase flex items-center gap-2">
          <span class="material-symbols-rounded text-cyan-400 text-lg">verified_user</span> SBTE Academic Audit (Part C)
        </h1>
      </div>
      <div class="flex items-center gap-3">
        <form method="GET" action="/hod/sbte-audit" class="flex items-center gap-2">
          <select name="academic_year" onchange="this.form.submit()" class="bg-slate-900 border border-slate-800 rounded-xl px-3 py-1.5 text-sm font-bold text-white outline-none">
            <option value="2025-2026" {{ $academicYear === '2025-2026' ? 'selected' : '' }}>2025-2026</option>
            <option value="2026-2027" {{ $academicYear === '2026-2027' ? 'selected' : '' }}>2026-2027</option>
          </select>
        </form>
        <button type="submit" form="auditForm" class="px-3.5 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-xl font-bold transition flex items-center gap-1.5 text-sm shadow-lg cursor-pointer">
          <span class="material-symbols-rounded text-sm font-bold">save</span> Save Progress
        </button>
        <a href="/hod/sbte-audit/print?academic_year={{ urlencode($academicYear) }}" target="_blank" class="px-3.5 py-1.5 bg-cyan-500/10 hover:bg-cyan-500/25 text-cyan-400 border border-cyan-500/30 hover:border-cyan-400 rounded-xl font-bold transition flex items-center gap-1.5 text-sm no-underline cursor-pointer">
          <span class="material-symbols-rounded text-sm">print</span> Print Part C
        </a>
      </div>
    </div>
  </header>

  <!-- Main Console Layout -->
  <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    @if(session('success'))
      <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl text-sm font-bold">
        {{ session('success') }}
      </div>
    @endif

    <div class="bg-slate-900/60 border border-slate-850 rounded-2xl p-6 space-y-3">
      <h3 class="text-sm font-black text-white uppercase tracking-wider flex items-center gap-2">
        Academic Year: <span class="text-cyan-400">{{ $academicYear }}</span> | Department: <span class="text-slate-200">{{ $department }}</span>
      </h3>
      <p class="text-slate-400 text-sm leading-relaxed font-medium">
        Fill out all 16 SITTTR Kerala departmental self-assessment metrics below. Fields with database records can be overwritten manually as needed.
      </p>
    </div>

    <!-- Complete 16 Sections Form -->
    <form id="auditForm" method="POST" action="/hod/sbte-audit/save" onsubmit="return confirm('Are you sure you want to save all SBTE audit progress?');" class="space-y-8">
      @csrf
      <input type="hidden" name="academic_year" value="{{ $academicYear }}">

      <!-- Criterion 1: General & NBA Status -->
      <div class="bg-slate-950/60 border border-slate-900 rounded-2xl p-6 space-y-4">
        <h3 class="text-sm font-black text-white uppercase tracking-widest border-b border-slate-900 pb-2">
          1. Program & HOD Details
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-bold text-slate-350 uppercase mb-1.5">NBA Accredited? (Criterion 1)</label>
            <select name="nba_accredited" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm font-bold">
              <option value="1" {{ ($audit->nba_accredited ?? false) ? 'selected' : '' }}>Yes (Accredited)</option>
              <option value="0" {{ !($audit->nba_accredited ?? false) ? 'selected' : '' }}>No (Not Accredited)</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-350 uppercase mb-1.5">Name of HOD</label>
            <input type="text" name="professional_activities[hod_name]" value="{{ $auditData['professional_activities']['hod_name'] ?? '' }}" placeholder="Name of HOD" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-350 uppercase mb-1.5">Number of faculty</label>
            <input type="number" name="professional_activities[faculty_count]" value="{{ $auditData['professional_activities']['faculty_count'] ?? '' }}" placeholder="e.g. 15" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm">
          </div>
        </div>
      </div>

      <!-- Criterion 2: Enrollment -->
      <div class="bg-slate-950/60 border border-slate-900 rounded-2xl p-6 space-y-4">
        <h3 class="text-sm font-black text-white uppercase tracking-widest border-b border-slate-900 pb-2">
          2. Enrollment
        </h3>
        <div class="overflow-x-auto border border-slate-900 rounded-xl bg-slate-950">
          <table class="w-full text-left border-collapse text-sm">
            <thead>
              <tr class="bg-slate-900 border-b border-slate-800 text-sm uppercase font-bold text-slate-400">
                <th class="p-3">Year</th>
                <th class="p-3 text-center">Approved Intake</th>
                <th class="p-3 text-center">Enrolled</th>
                <th class="p-3 text-center">Present (Strength)</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-900">
              @php $enData = $auditData['enrollment'] ?? []; @endphp
              @foreach(['CAY', 'CAY-1', 'CAY-2'] as $y)
                <tr>
                  <td class="p-3 font-bold text-slate-300">{{ $y }}</td>
                  <td class="p-3"><input type="number" name="enrollment[{{ $y }}][intake]" value="{{ $enData[$y]['intake'] ?? '' }}" class="w-24 mx-auto block bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1.5 text-center text-white text-sm outline-none"></td>
                  <td class="p-3"><input type="number" name="enrollment[{{ $y }}][enrolled]" value="{{ $enData[$y]['enrolled'] ?? '' }}" class="w-24 mx-auto block bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1.5 text-center text-white text-sm outline-none"></td>
                  <td class="p-3"><input type="number" name="enrollment[{{ $y }}][present]" value="{{ $enData[$y]['present'] ?? '' }}" class="w-24 mx-auto block bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1.5 text-center text-white text-sm outline-none"></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- Criterion 3: Academic performance without backlog -->
      <div class="bg-slate-950/60 border border-slate-900 rounded-2xl p-6 space-y-4">
        <div class="flex justify-between items-center border-b border-slate-900 pb-2">
          <h3 class="text-sm font-black text-white uppercase tracking-widest">
            3. Academic performance without backlog
          </h3>
          <button type="button" onclick="generateAcademicPerformance()" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-lg transition text-sm cursor-pointer border-none shadow-sm">
            Generate from DB
          </button>
        </div>
        <div class="overflow-x-auto border border-slate-900 rounded-xl bg-slate-950">
          <table class="w-full text-left border-collapse text-sm">
            <thead>
              <tr class="bg-slate-900 border-b border-slate-800 text-sm uppercase font-bold text-slate-400">
                <th class="p-3">Semester</th>
                <th class="p-3 text-center">CAY (Reg / Pass)</th>
                <th class="p-3 text-center">CAY-1 (Reg / Pass)</th>
                <th class="p-3 text-center">CAY-2 (Reg / Pass)</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-900">
              @php $perfNoBack = $auditData['perf_no_backlog'] ?? []; @endphp
              @for($s = 1; $s <= 6; $s++)
                <tr>
                  <td class="p-3 font-bold text-slate-300">S{{ $s }}</td>
                  @foreach(['CAY', 'CAY-1', 'CAY-2'] as $y)
                    <td class="p-3">
                      <div class="flex items-center justify-center gap-2">
                        <input type="number" name="perf_no_backlog[{{ $s }}][{{ $y }}][reg]" value="{{ $perfNoBack[$s][$y]['reg'] ?? '' }}" placeholder="Reg" class="w-16 bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none">
                        <span class="text-slate-600">/</span>
                        <input type="number" name="perf_no_backlog[{{ $s }}][{{ $y }}][pass]" value="{{ $perfNoBack[$s][$y]['pass'] ?? '' }}" placeholder="Pass" class="w-16 bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none">
                      </div>
                    </td>
                  @endforeach
                </tr>
              @endfor
            </tbody>
          </table>
        </div>
      </div>

      <!-- Criterion 4: Academic performance with backlog -->
      <div class="bg-slate-950/60 border border-slate-900 rounded-2xl p-6 space-y-4">
        <div class="flex justify-between items-center border-b border-slate-900 pb-2">
          <h3 class="text-sm font-black text-white uppercase tracking-widest">
            4. Academic performance with backlog
          </h3>
          <button type="button" onclick="generateAcademicPerformance()" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-lg transition text-sm cursor-pointer border-none shadow-sm">
            Generate from DB
          </button>
        </div>
        <div class="overflow-x-auto border border-slate-900 rounded-xl bg-slate-950">
          <table class="w-full text-left border-collapse text-sm">
            <thead>
              <tr class="bg-slate-900 border-b border-slate-800 text-sm uppercase font-bold text-slate-400">
                <th class="p-3">Semester</th>
                <th class="p-3 text-center">CAY (Reg / Pass)</th>
                <th class="p-3 text-center">CAY-1 (Reg / Pass)</th>
                <th class="p-3 text-center">CAY-2 (Reg / Pass)</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-900">
              @php $perfBack = $auditData['perf_with_backlog'] ?? []; @endphp
              @for($s = 1; $s <= 6; $s++)
                <tr>
                  <td class="p-3 font-bold text-slate-300">S{{ $s }}</td>
                  @foreach(['CAY', 'CAY-1', 'CAY-2'] as $y)
                    <td class="p-3">
                      <div class="flex items-center justify-center gap-2">
                        <input type="number" name="perf_with_backlog[{{ $s }}][{{ $y }}][reg]" value="{{ $perfBack[$s][$y]['reg'] ?? '' }}" placeholder="Reg" class="w-16 bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none">
                        <span class="text-slate-600">/</span>
                        <input type="number" name="perf_with_backlog[{{ $s }}][{{ $y }}][pass]" value="{{ $perfBack[$s][$y]['pass'] ?? '' }}" placeholder="Pass" class="w-16 bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none">
                      </div>
                    </td>
                  @endforeach
                </tr>
              @endfor
            </tbody>
          </table>
        </div>
      </div>

      <!-- Criterion 5: Placement -->
      <div class="bg-slate-950/60 border border-slate-900 rounded-2xl p-6 space-y-4">
        <h3 class="text-sm font-black text-white uppercase tracking-widest border-b border-slate-900 pb-2">
          5. Placement Metrics
        </h3>
        <div class="overflow-x-auto border border-slate-900 rounded-xl bg-slate-950">
          <table class="w-full text-left border-collapse text-sm">
            <thead>
              <tr class="bg-slate-900 border-b border-slate-800 text-sm uppercase font-bold text-slate-400">
                <th class="p-3">Batch</th>
                <th class="p-3 text-center">Admitted</th>
                <th class="p-3 text-center">Placed</th>
                <th class="p-3 text-center">Higher Ed</th>
                <th class="p-3 text-center">Entrepreneurs</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-900">
              @php $placement = $auditData['placement'] ?? []; @endphp
              @foreach(['CAY-1', 'CAY-2', 'CAY-3'] as $b)
                <tr>
                  <td class="p-3 font-bold text-slate-300">{{ $b }}</td>
                  <td class="p-3"><input type="number" name="placement[{{ $b }}][admitted]" value="{{ $placement[$b]['admitted'] ?? '' }}" class="w-20 mx-auto block bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
                  <td class="p-3"><input type="number" name="placement[{{ $b }}][placed]" value="{{ $placement[$b]['placed'] ?? '' }}" class="w-20 mx-auto block bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
                  <td class="p-3"><input type="number" name="placement[{{ $b }}][higher_ed]" value="{{ $placement[$b]['higher_ed'] ?? '' }}" class="w-20 mx-auto block bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
                  <td class="p-3"><input type="number" name="placement[{{ $b }}][entrepreneurs]" value="{{ $placement[$b]['entrepreneurs'] ?? '' }}" class="w-20 mx-auto block bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- Criterion 6: Professional Activities -->
      <div class="bg-slate-950/60 border border-slate-900 rounded-2xl p-6 space-y-4">
        <div class="flex justify-between items-center border-b border-slate-900 pb-2 mb-2">
          <h3 class="text-sm font-black text-white uppercase tracking-widest">
            6. Professional Activities (Consolidated Mark: 5)
          </h3>
          <button type="button" onclick="fetchStaffActivities()" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-lg transition text-sm cursor-pointer border-none shadow-sm">
            Fetch from Staff Logs
          </button>
        </div>
        
        <!-- Societies and Chapters list -->
        <div class="space-y-2">
          <div class="flex justify-between items-center">
            <label class="block text-sm font-bold text-slate-350 uppercase">i. Professional Societies / Chapters</label>
            <button type="button" onclick="addSocietyRow()" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded text-sm cursor-pointer border-none">
              + Add Society
            </button>
          </div>
          <div id="societiesContainer" class="space-y-2">
            @php
              $societies = $auditData['professional_activities']['societies'] ?? [''];
            @endphp
            @foreach($societies as $index => $soc)
              <div class="flex items-center gap-2">
                <input type="text" name="professional_activities[societies][]" value="{{ $soc }}" placeholder="e.g. IEEE Student Branch" class="flex-grow bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm">
                <button type="button" onclick="this.parentElement.remove()" class="px-2.5 py-2 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-xl transition text-sm font-bold cursor-pointer">
                  Remove
                </button>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Publications and Newsletters list -->
        <div class="space-y-2 pt-2">
          <div class="flex justify-between items-center">
            <label class="block text-sm font-bold text-slate-350 uppercase">ii. Publication of Newsletters, Magazines</label>
            <button type="button" onclick="addPublicationRow()" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded text-sm cursor-pointer border-none">
              + Add Publication
            </button>
          </div>
          <div id="publicationsContainer" class="space-y-2">
            @php
              $publications = $auditData['professional_activities']['publications'] ?? [''];
            @endphp
            @foreach($publications as $index => $pub)
              <div class="flex items-center gap-2">
                <input type="text" name="professional_activities[publications][]" value="{{ $pub }}" placeholder="e.g. Department Newsletter Edition 2" class="flex-grow bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm">
                <button type="button" onclick="this.parentElement.remove()" class="px-2.5 py-2 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-xl transition text-sm font-bold cursor-pointer">
                  Remove
                </button>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Criterion 7: Student Faculty Ratio (SFR) -->
      <div class="bg-slate-950/60 border border-slate-900 rounded-2xl p-6 space-y-4">
        <h3 class="text-sm font-black text-white uppercase tracking-widest border-b border-slate-900 pb-2">
          7. Student Faculty Ratio (SFR)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          @php $sfr = $auditData['sfr'] ?? []; @endphp
          @foreach(['CAY', 'CAY-1', 'CAY-2'] as $y)
            <div>
              <label class="block text-sm font-bold text-slate-350 uppercase mb-1.5">{{ $y }} SFR Ratio</label>
              <input type="text" name="sfr[{{ $y }}]" value="{{ $sfr[$y] ?? '' }}" placeholder="e.g. 1:20" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm">
            </div>
          @endforeach
        </div>
      </div>

      <!-- Criterion 8: Infrastructure of Program -->
      <div class="bg-slate-950/60 border border-slate-900 rounded-2xl p-6 space-y-4">
        <h3 class="text-sm font-black text-white uppercase tracking-widest border-b border-slate-900 pb-2">
          8. Infrastructure of Program
        </h3>
        <div class="overflow-x-auto border border-slate-900 rounded-xl bg-slate-950">
          <table class="w-full text-left border-collapse text-sm">
            <thead>
              <tr class="bg-slate-900 border-b border-slate-800 text-sm uppercase font-bold text-slate-400">
                <th class="p-3">Infrastructure Item</th>
                <th class="p-3 text-center">Number</th>
                <th class="p-3 text-center">Area (sqm)</th>
                <th class="p-3 text-center">Adequacy (Yes/No)</th>
                <th class="p-3 text-center">Ambience (Remarks)</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-900">
              @php
                $infra = $auditData['infrastructure'] ?? [];
                $infraItems = ['Classrooms', 'Smart classrooms', 'Laboratories', 'Computer Lab', 'Cabin for HoD', 'Faculty room', 'Others'];
              @endphp
              @foreach($infraItems as $item)
                <tr>
                  <td class="p-3 font-bold text-slate-300">{{ $item }}</td>
                  <td class="p-3">
                    <input type="text" name="infrastructure[{{ $item }}][number]" value="{{ $infra[$item]['number'] ?? '' }}" placeholder="e.g. 3" class="w-20 mx-auto block bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none">
                  </td>
                  <td class="p-3">
                    <input type="text" name="infrastructure[{{ $item }}][area]" value="{{ $infra[$item]['area'] ?? '' }}" placeholder="e.g. 66 sqm" class="w-24 mx-auto block bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none">
                  </td>
                  <td class="p-3">
                    <input type="text" name="infrastructure[{{ $item }}][adequacy]" value="{{ $infra[$item]['adequacy'] ?? '' }}" placeholder="Yes/No" class="w-20 mx-auto block bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none">
                  </td>
                  <td class="p-3">
                    <input type="text" name="infrastructure[{{ $item }}][ambience]" value="{{ $infra[$item]['ambience'] ?? '' }}" placeholder="e.g. Good" class="w-28 mx-auto block bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none">
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- Criterion 9 to 16 placeholders -->
      <div class="bg-slate-950/60 border border-slate-900 rounded-2xl p-6 space-y-4">
        <h3 class="text-sm font-black text-white uppercase tracking-widest border-b border-slate-900 pb-2">
          9-16. Quality Systems & Achievements
        </h3>
        <div class="space-y-3">
          @php
            $vm = $auditData['vision_mission'] ?? [];
            $tl = $auditData['teaching_learning'] ?? [];
            $cf = $auditData['course_files'] ?? [];
            $ft = $auditData['faculty_training'] ?? [];
            $fdp = $auditData['fdp_conducted'] ?? [];
            $consult = $auditData['consultancy'] ?? [];
            $ach = $auditData['achievements'] ?? [];
          @endphp
          <div class="border-b border-slate-900 pb-4 mb-4 space-y-3">
            <label class="block text-sm font-bold text-slate-350 uppercase mb-1.5 font-black text-cyan-400">9. Vision, Mission, PEOs, & PSOs</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Vision Statement</label>
                <textarea name="vision_mission[vision]" rows="3" placeholder="Department Vision Statement..." class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm">{{ $vm['vision'] ?? '' }}</textarea>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Mission Statement</label>
                <textarea name="vision_mission[mission]" rows="3" placeholder="Department Mission Statement..." class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm">{{ $vm['mission'] ?? '' }}</textarea>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Program Program Educational Objectives (PEOs)</label>
                <textarea name="vision_mission[peos]" rows="3" placeholder="PEO statements..." class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm">{{ $vm['peos'] ?? '' }}</textarea>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Program Program Specific Outcomes (PSOs)</label>
                <textarea name="vision_mission[psos]" rows="3" placeholder="PSO statements..." class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm">{{ $vm['psos'] ?? '' }}</textarea>
              </div>
            </div>
            <div class="mt-2">
              <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Availability & Dissemination Remarks</label>
              <input type="text" name="vision_mission[remarks]" value="{{ $vm['remarks'] ?? '' }}" placeholder="Availability and Dissemination status" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm">
            </div>
          </div>
          <div class="border-b border-slate-900 pb-4 mb-4 space-y-3">
            <div class="flex justify-between items-center pb-2">
              <label class="block text-sm font-bold text-slate-350 uppercase font-black text-cyan-400">10. Teaching - Learning Process (80 Marks)</label>
              <button type="button" onclick="fetchStaffActivities()" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-lg transition text-sm cursor-pointer border-none shadow-sm">
                Fetch from Staff Logs
              </button>
            </div>
            <div class="overflow-x-auto border border-slate-900 rounded-xl bg-slate-950">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-900 border-b border-slate-800 text-[10px] uppercase font-bold text-slate-400">
                    <th class="p-3">Teaching-Learning Item</th>
                    <th class="p-3 text-center w-28">Status (Yes/No)</th>
                    <th class="p-3">Compliance Details / Remarks</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-900">
                  @php
                    $tl = $auditData['teaching_learning'] ?? [];
                    $tlItems = [
                      'gaps' => 'Whether Curricular gaps identified for attaining POs & PSOs',
                      'weak_bright' => 'Methodologies to support weak students and encourage bright students',
                      'calendar' => 'Adherence to Academic calendar',
                      'internal_tests' => 'Quality check for Internal semester test & assignment',
                      'labs' => 'Laboratory experiments conducted as per syllabus',
                      'projects' => 'Student Projects monitoring and evaluation',
                      'industry' => 'Industry Interaction & Community services',
                      'co_curricular' => 'Co-curricular & extracurricular activities connection'
                    ];
                  @endphp
                  @foreach($tlItems as $key => $label)
                    <tr>
                      <td class="p-3 font-medium text-slate-300">{{ $label }}</td>
                      <td class="p-3">
                        <select name="teaching_learning[{{ $key }}][status]" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-2 py-1 text-white outline-none text-sm font-bold">
                          <option value="Yes" {{ ($tl[$key]['status'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                          <option value="No" {{ ($tl[$key]['status'] ?? '') === 'No' ? 'selected' : '' }}>No</option>
                        </select>
                      </td>
                      <td class="p-3">
                        <input type="text" name="teaching_learning[{{ $key }}][remarks]" value="{{ $tl[$key]['remarks'] ?? '' }}" placeholder="Remarks..." class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1 text-white text-sm outline-none">
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="border-b border-slate-900 pb-4 mb-4 space-y-3">
            <div class="flex justify-between items-center pb-2">
              <label class="block text-sm font-bold text-slate-350 uppercase font-black text-cyan-400">11. Course Files (Attainments & Coverage)</label>
              <button type="button" onclick="generateCourseFiles()" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-lg transition text-sm cursor-pointer border-none shadow-sm">
                Generate from DB
              </button>
            </div>
            <div class="overflow-x-auto border border-slate-900 rounded-xl bg-slate-950">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-900 border-b border-slate-800 text-[10px] uppercase font-bold text-slate-400">
                    <th class="p-3">Batch</th>
                    <th class="p-3 text-center">Revision Year</th>
                    <th class="p-3 text-center">No. of courses in program</th>
                    <th class="p-3 text-center">Course files completed (incl. attainment)</th>
                    <th class="p-3 text-center">PO attainment calculated?</th>
                    <th class="p-3 text-center">PSO attainment calculated?</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-900">
                  @php
                    $cf = $auditData['course_files'] ?? [];
                    $batches = [
                      'CAY-3' => 'CAY-3',
                      'CAY-2' => 'CAY-2',
                      'CAY-1' => 'CAY-1'
                    ];
                  @endphp
                  @foreach($batches as $key => $label)
                    <tr>
                      <td class="p-3 font-medium text-slate-300">{{ $label }}</td>
                      <td class="p-3">
                        <input type="text" name="course_files[{{ $key }}][rev_year]" value="{{ $cf[$key]['rev_year'] ?? ($key === 'CAY-1' ? '21' : '15') }}" placeholder="e.g. 15" class="w-20 mx-auto block bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none">
                      </td>
                      <td class="p-3">
                        <input type="number" name="course_files[{{ $key }}][courses]" value="{{ $cf[$key]['courses'] ?? '' }}" placeholder="e.g. 18" class="w-20 mx-auto block bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none">
                      </td>
                      <td class="p-3">
                        <input type="number" name="course_files[{{ $key }}][completed]" value="{{ $cf[$key]['completed'] ?? '' }}" placeholder="e.g. 17" class="w-24 mx-auto block bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none">
                      </td>
                      <td class="p-3">
                        <select name="course_files[{{ $key }}][po_attained]" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-2 py-1 text-white outline-none text-sm font-bold">
                          <option value="Yes" {{ ($cf[$key]['po_attained'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                          <option value="No" {{ ($cf[$key]['po_attained'] ?? '') === 'No' ? 'selected' : '' }}>No</option>
                        </select>
                      </td>
                      <td class="p-3">
                        <select name="course_files[{{ $key }}][pso_attained]" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-2 py-1 text-white outline-none text-sm font-bold">
                          <option value="Yes" {{ ($cf[$key]['pso_attained'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                          <option value="No" {{ ($cf[$key]['pso_attained'] ?? '') === 'No' ? 'selected' : '' }}>No</option>
                        </select>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <!-- Criterion 12: Faculty Training -->
          <div class="border-b border-slate-900 pb-4 mb-4 space-y-3">
            <div class="flex justify-between items-center pb-2">
              <label class="block text-sm font-bold text-slate-350 uppercase font-black text-cyan-400">12. Faculty Training Participation</label>
              <div class="flex items-center gap-2">
                <button type="button" onclick="fetchStaffActivities()" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-lg transition text-sm cursor-pointer border-none shadow-sm">
                  Fetch from Staff Logs
                </button>
                <button type="button" onclick="addFacultyTrainingRow()" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded text-sm cursor-pointer border-none">
                  + Add Faculty Training
                </button>
              </div>
            </div>
            <div class="overflow-x-auto border border-slate-900 rounded-xl bg-slate-950">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-900 border-b border-slate-800 text-[10px] uppercase font-bold text-slate-400">
                    <th class="p-3">Name of Faculty</th>
                    <th class="p-3">Designation</th>
                    <th class="p-3">Title of FDP</th>
                    <th class="p-3 text-center w-24">Duration (Days)</th>
                    <th class="p-3">Venue</th>
                    <th class="p-3 text-center w-20">Action</th>
                  </tr>
                </thead>
                <tbody id="facultyTrainingContainer" class="divide-y divide-slate-900">
                  @php
                    $ftRows = $auditData['faculty_training'] ?? [];
                  @endphp
                  @foreach($ftRows as $index => $row)
                    @if(is_array($row) && isset($row['name']))
                      <tr>
                        <td class="p-3"><input type="text" name="faculty_training[{{ $index }}][name]" value="{{ $row['name'] }}" placeholder="Faculty Name" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
                        <td class="p-3"><input type="text" name="faculty_training[{{ $index }}][designation]" value="{{ $row['designation'] }}" placeholder="Designation" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
                        <td class="p-3"><input type="text" name="faculty_training[{{ $index }}][title]" value="{{ $row['title'] }}" placeholder="FDP Title" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
                        <td class="p-3"><input type="number" name="faculty_training[{{ $index }}][duration]" value="{{ $row['duration'] }}" placeholder="Days" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
                        <td class="p-3"><input type="text" name="faculty_training[{{ $index }}][venue]" value="{{ $row['venue'] }}" placeholder="Venue" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
                        <td class="p-3 text-center">
                          <button type="button" onclick="this.parentElement.parentElement.remove()" class="px-2 py-1 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-lg transition text-xs font-bold cursor-pointer">Remove</button>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <!-- Criterion 13: FDPs Conducted -->
          <div class="border-b border-slate-900 pb-4 mb-4 space-y-3">
            <div class="flex justify-between items-center pb-2">
              <label class="block text-sm font-bold text-slate-350 uppercase font-black text-cyan-400">13. Details of FDPs conducted in past 3 years</label>
              <div class="flex items-center gap-2">
                <button type="button" onclick="fetchStaffActivities()" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-lg transition text-sm cursor-pointer border-none shadow-sm">
                  Fetch from Staff Logs
                </button>
                <button type="button" onclick="addFdpConductedRow()" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded text-sm cursor-pointer border-none">
                  + Add FDP Conducted
                </button>
              </div>
            </div>
            <div class="overflow-x-auto border border-slate-900 rounded-xl bg-slate-950">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-900 border-b border-slate-800 text-[10px] uppercase font-bold text-slate-400">
                    <th class="p-3">Name of FDP</th>
                    <th class="p-3 text-center w-28">No. Attended</th>
                    <th class="p-3 text-center w-36">Date From</th>
                    <th class="p-3">Funding Agency</th>
                    <th class="p-3 text-center w-20">Action</th>
                  </tr>
                </thead>
                <tbody id="fdpConductedContainer" class="divide-y divide-slate-900">
                  @php
                    $fdpRows = $auditData['fdp_conducted'] ?? [];
                  @endphp
                  @foreach($fdpRows as $index => $row)
                    @if(is_array($row) && isset($row['title']))
                      <tr>
                        <td class="p-3"><input type="text" name="fdp_conducted[{{ $index }}][title]" value="{{ $row['title'] }}" placeholder="FDP Title" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
                        <td class="p-3"><input type="number" name="fdp_conducted[{{ $index }}][attended]" value="{{ $row['attended'] }}" placeholder="No. Attended" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
                        <td class="p-3"><input type="text" name="fdp_conducted[{{ $index }}][date_from]" value="{{ $row['date_from'] }}" placeholder="YYYY-MM-DD" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
                        <td class="p-3"><input type="text" name="fdp_conducted[{{ $index }}][funding]" value="{{ $row['funding'] }}" placeholder="e.g. SITTTR" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
                        <td class="p-3 text-center">
                          <button type="button" onclick="this.parentElement.parentElement.remove()" class="px-2 py-1 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-lg transition text-xs font-bold cursor-pointer">Remove</button>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <!-- Criterion 14: Consultancy & Testing -->
          <div class="border-b border-slate-900 pb-4 mb-4 space-y-3">
            <div class="flex justify-between items-center pb-2">
              <label class="block text-sm font-bold text-slate-350 uppercase font-black text-cyan-400">14. Consultancy & Testing Funds</label>
              <div class="flex items-center gap-2">
                <button type="button" onclick="addConsultancyRow()" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded text-sm cursor-pointer border-none">
                  + Add Consultancy
                </button>
              </div>
            </div>
            <div class="overflow-x-auto border border-slate-900 rounded-xl bg-slate-950">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-900 border-b border-slate-800 text-[10px] uppercase font-bold text-slate-400">
                    <th class="p-3">Name of Project/Work</th>
                    <th class="p-3 text-center w-36">Date</th>
                    <th class="p-3 text-center w-36">Fund Generated</th>
                    <th class="p-3">Faculty Involved</th>
                    <th class="p-3">Remarks</th>
                    <th class="p-3 text-center w-20">Action</th>
                  </tr>
                </thead>
                <tbody id="consultancyContainer" class="divide-y divide-slate-900">
                  @php
                    $consultRows = $auditData['consultancy'] ?? [];
                    if (is_array($consultRows) && !empty($consultRows) && !isset($consultRows[0])) {
                        $consultRows = [[
                            'name' => 'Legacy Record',
                            'date' => '-',
                            'fund' => '-',
                            'faculty' => '-',
                            'remarks' => $consultRows['remarks'] ?? ''
                        ]];
                    }
                  @endphp
                  @foreach($consultRows as $index => $row)
                    @if(is_array($row) && isset($row['name']))
                      <tr>
                        <td class="p-3"><input type="text" name="consultancy[{{ $index }}][name]" value="{{ $row['name'] }}" placeholder="Project/Work Name" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
                        <td class="p-3"><input type="text" name="consultancy[{{ $index }}][date]" value="{{ $row['date'] }}" placeholder="YYYY-MM-DD" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
                        <td class="p-3"><input type="text" name="consultancy[{{ $index }}][fund]" value="{{ $row['fund'] }}" placeholder="Amount" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
                        <td class="p-3"><input type="text" name="consultancy[{{ $index }}][faculty]" value="{{ $row['faculty'] }}" placeholder="Faculty Name(s)" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
                        <td class="p-3"><input type="text" name="consultancy[{{ $index }}][remarks]" value="{{ $row['remarks'] }}" placeholder="Remarks" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
                        <td class="p-3 text-center">
                          <button type="button" onclick="this.parentElement.parentElement.remove()" class="px-2 py-1 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-lg transition text-xs font-bold cursor-pointer">Remove</button>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <!-- Criterion 15: Remarkable Achievements -->
          <div class="border-b border-slate-900 pb-4 mb-4 space-y-3">
            <div class="flex justify-between items-center pb-2">
              <label class="block text-sm font-bold text-slate-350 uppercase font-black text-cyan-400">15. Remarkable achievements (Faculty/Students)</label>
              <div class="flex items-center gap-2">
                <button type="button" onclick="addAchievementRow()" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded text-sm cursor-pointer border-none">
                  + Add Achievement
                </button>
              </div>
            </div>
            <div class="overflow-x-auto border border-slate-900 rounded-xl bg-slate-950">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-900 border-b border-slate-800 text-[10px] uppercase font-bold text-slate-400">
                    <th class="p-3 text-center w-12">No.</th>
                    <th class="p-3 w-40">Faculty / Student</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Achievement</th>
                    <th class="p-3">Remarks</th>
                    <th class="p-3 text-center w-20">Action</th>
                  </tr>
                </thead>
                <tbody id="achievementsContainer" class="divide-y divide-slate-900">
                  @php
                    $achRows = $auditData['achievements'] ?? [];
                    if (is_array($achRows) && !empty($achRows) && !isset($achRows[0])) {
                        $achRows = [[
                            'category' => 'Faculty',
                            'name' => 'Legacy Record',
                            'achievement' => $achRows['remarks'] ?? '',
                            'remarks' => '-'
                        ]];
                    }
                  @endphp
                  @foreach($achRows as $index => $row)
                    @if(is_array($row) && isset($row['name']))
                      <tr class="achievement-row">
                        <td class="p-3 text-center font-bold text-slate-400 row-num">{{ $index + 1 }}</td>
                        <td class="p-3">
                          <select name="achievements[{{ $index }}][category]" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none cursor-pointer">
                            <option value="Faculty" {{ ($row['category'] ?? '') === 'Faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="Student" {{ ($row['category'] ?? '') === 'Student' ? 'selected' : '' }}>Student</option>
                          </select>
                        </td>
                        <td class="p-3"><input type="text" name="achievements[{{ $index }}][name]" value="{{ $row['name'] }}" placeholder="Name" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
                        <td class="p-3"><input type="text" name="achievements[{{ $index }}][achievement]" value="{{ $row['achievement'] }}" placeholder="Achievement details" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
                        <td class="p-3"><input type="text" name="achievements[{{ $index }}][remarks]" value="{{ $row['remarks'] }}" placeholder="Remarks" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
                        <td class="p-3 text-center">
                          <button type="button" onclick="removeAchievementRow(this)" class="px-2 py-1 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-lg transition text-xs font-bold cursor-pointer">Remove</button>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </form>

  </main>

  <!-- Sticky Footer -->
  <footer class="bg-slate-950 border-t border-slate-900 py-4 text-center text-slate-500 text-sm mt-auto">
    <p>&copy; 2026 Carmel Linx - SBTE Audit Engine. All rights reserved.</p>
  </footer>

  <script>
    async function generateAcademicPerformance() {
      try {
        const response = await fetch('/api/hod/sbte-audit/generate-perf');
        if (!response.ok) {
          alert('Failed to generate metrics from DB');
          return;
        }
        const data = await response.json();
        
        for (let s = 1; s <= 6; s++) {
          ['CAY', 'CAY-1', 'CAY-2'].forEach(y => {
            const regInput = document.querySelector(`input[name="perf_no_backlog[${s}][${y}][reg]"]`);
            const passInput = document.querySelector(`input[name="perf_no_backlog[${s}][${y}][pass]"]`);
            if (regInput && data.perf_no_backlog[s] && data.perf_no_backlog[s][y]) {
              regInput.value = data.perf_no_backlog[s][y].reg;
            }
            if (passInput && data.perf_no_backlog[s] && data.perf_no_backlog[s][y]) {
              passInput.value = data.perf_no_backlog[s][y].pass;
            }
          });
        }

        for (let s = 1; s <= 6; s++) {
          ['CAY', 'CAY-1', 'CAY-2'].forEach(y => {
            const regInput = document.querySelector(`input[name="perf_with_backlog[${s}][${y}][reg]"]`);
            const passInput = document.querySelector(`input[name="perf_with_backlog[${s}][${y}][pass]"]`);
            if (regInput && data.perf_with_backlog[s] && data.perf_with_backlog[s][y]) {
              regInput.value = data.perf_with_backlog[s][y].reg;
            }
            if (passInput && data.perf_with_backlog[s] && data.perf_with_backlog[s][y]) {
              passInput.value = data.perf_with_backlog[s][y].pass;
            }
          });
        }
        
        alert('Academic Performance metrics generated from DB successfully!');
      } catch (err) {
        console.error(err);
        alert('Error communicating with generation engine.');
      }
    }

    function addSocietyRow() {
      const container = document.getElementById('societiesContainer');
      const div = document.createElement('div');
      div.className = 'flex items-center gap-2';
      div.innerHTML = `
        <input type="text" name="professional_activities[societies][]" placeholder="e.g. IEEE Student Branch" class="flex-grow bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm">
        <button type="button" onclick="this.parentElement.remove()" class="px-2.5 py-2 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-xl transition text-sm font-bold cursor-pointer">
          Remove
        </button>
      `;
      container.appendChild(div);
    }

    function addPublicationRow() {
      const container = document.getElementById('publicationsContainer');
      const div = document.createElement('div');
      div.className = 'flex items-center gap-2';
      div.innerHTML = `
        <input type="text" name="professional_activities[publications][]" placeholder="e.g. Department Newsletter Edition 2" class="flex-grow bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm">
        <button type="button" onclick="this.parentElement.remove()" class="px-2.5 py-2 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-xl transition text-sm font-bold cursor-pointer">
          Remove
        </button>
      `;
      container.appendChild(div);
    }
    }

    async function generateCourseFiles() {
      try {
        const response = await fetch('/api/hod/sbte-audit/generate-course-files');
        if (!response.ok) {
          alert('Failed to generate course file metrics from DB');
          return;
        }
        const data = await response.json();
        
        ['CAY-3', 'CAY-2', 'CAY-1'].forEach(key => {
          const coursesInput = document.querySelector(`input[name="course_files[${key}][courses]"]`);
          const completedInput = document.querySelector(`input[name="course_files[${key}][completed]"]`);
          const poSelect = document.querySelector(`select[name="course_files[${key}][po_attained]"]`);
          const psoSelect = document.querySelector(`select[name="course_files[${key}][pso_attained]"]`);
          
          if (coursesInput && data.course_files[key]) {
            coursesInput.value = data.course_files[key].courses;
          }
          if (completedInput && data.course_files[key]) {
            completedInput.value = data.course_files[key].completed;
          }
          if (poSelect && data.course_files[key]) {
            poSelect.value = data.course_files[key].po_attained;
          }
          if (psoSelect && data.course_files[key]) {
            psoSelect.value = data.course_files[key].pso_attained;
          }
        });
        
        alert('Course Files metrics generated from DB successfully!');
      } catch (err) {
        console.error(err);
        alert('Error communicating with generation engine.');
      }
    }
    function addFacultyTrainingRow() {
      const container = document.getElementById('facultyTrainingContainer');
      const index = container.children.length;
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="p-3"><input type="text" name="faculty_training[${index}][name]" placeholder="Faculty Name" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
        <td class="p-3"><input type="text" name="faculty_training[${index}][designation]" placeholder="Designation" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
        <td class="p-3"><input type="text" name="faculty_training[${index}][title]" placeholder="FDP Title" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
        <td class="p-3"><input type="number" name="faculty_training[${index}][duration]" placeholder="Days" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
        <td class="p-3"><input type="text" name="faculty_training[${index}][venue]" placeholder="Venue" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
        <td class="p-3 text-center">
          <button type="button" onclick="this.parentElement.parentElement.remove()" class="px-2 py-1 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-lg transition text-xs font-bold cursor-pointer">Remove</button>
        </td>
      `;
      container.appendChild(tr);
    }

    function addFdpConductedRow() {
      const container = document.getElementById('fdpConductedContainer');
      const index = container.children.length;
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="p-3"><input type="text" name="fdp_conducted[${index}][title]" placeholder="FDP Title" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
        <td class="p-3"><input type="number" name="fdp_conducted[${index}][attended]" placeholder="No. Attended" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
        <td class="p-3"><input type="text" name="fdp_conducted[${index}][date_from]" placeholder="YYYY-MM-DD" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
        <td class="p-3"><input type="text" name="fdp_conducted[${index}][funding]" placeholder="e.g. SITTTR" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
        <td class="p-3 text-center">
          <button type="button" onclick="this.parentElement.parentElement.remove()" class="px-2 py-1 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-lg transition text-xs font-bold cursor-pointer">Remove</button>
        </td>
      \`;
      container.appendChild(tr);
    }

    function addConsultancyRow() {
      const container = document.getElementById('consultancyContainer');
      const index = container.children.length;
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="p-3"><input type="text" name="consultancy[${index}][name]" placeholder="Project/Work Name" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
        <td class="p-3"><input type="text" name="consultancy[${index}][date]" placeholder="YYYY-MM-DD" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
        <td class="p-3"><input type="text" name="consultancy[${index}][fund]" placeholder="Amount" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
        <td class="p-3"><input type="text" name="consultancy[${index}][faculty]" placeholder="Faculty Name(s)" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
        <td class="p-3"><input type="text" name="consultancy[${index}][remarks]" placeholder="Remarks" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
        <td class="p-3 text-center">
          <button type="button" onclick="this.parentElement.parentElement.remove()" class="px-2 py-1 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-lg transition text-xs font-bold cursor-pointer">Remove</button>
        </td>
      `;
      container.appendChild(tr);
    }

    function reindexAchievements() {
      const container = document.getElementById('achievementsContainer');
      Array.from(container.children).forEach((tr, index) => {
        const numTd = tr.querySelector('.row-num');
        if (numTd) numTd.innerText = index + 1;
        
        const categorySelect = tr.querySelector('select[name*="[category]"]');
        if (categorySelect) categorySelect.name = `achievements[${index}][category]`;
        
        const nameInput = tr.querySelector('input[name*="[name]"]');
        if (nameInput) nameInput.name = `achievements[${index}][name]`;
        
        const achInput = tr.querySelector('input[name*="[achievement]"]');
        if (achInput) achInput.name = `achievements[${index}][achievement]`;
        
        const remarksInput = tr.querySelector('input[name*="[remarks]"]');
        if (remarksInput) remarksInput.name = `achievements[${index}][remarks]`;
      });
    }

    function addAchievementRow() {
      const container = document.getElementById('achievementsContainer');
      const index = container.children.length;
      const tr = document.createElement('tr');
      tr.className = 'achievement-row';
      tr.innerHTML = `
        <td class="p-3 text-center font-bold text-slate-400 row-num">${index + 1}</td>
        <td class="p-3">
          <select name="achievements[${index}][category]" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none cursor-pointer">
            <option value="Faculty">Faculty</option>
            <option value="Student">Student</option>
          </select>
        </td>
        <td class="p-3"><input type="text" name="achievements[${index}][name]" placeholder="Name" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
        <td class="p-3"><input type="text" name="achievements[${index}][achievement]" placeholder="Achievement details" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
        <td class="p-3"><input type="text" name="achievements[${index}][remarks]" placeholder="Remarks" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
        <td class="p-3 text-center">
          <button type="button" onclick="removeAchievementRow(this)" class="px-2 py-1 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-lg transition text-xs font-bold cursor-pointer">Remove</button>
        </td>
      `;
      container.appendChild(tr);
      reindexAchievements();
    }

    function removeAchievementRow(btn) {
      btn.parentElement.parentElement.remove();
      reindexAchievements();
    }

    async function fetchStaffActivities() {
      try {
        const response = await fetch('/api/hod/sbte-audit/fetch-staff-activities?academic_year=' + document.querySelector('input[name="academic_year"]').value);
        if (!response.ok) {
          alert('Failed to fetch staff activities');
          return;
        }
        const data = await response.json();
        
        // 1. Publications & Newsletters
        const publicationsContainer = document.getElementById('publicationsContainer');
        const pubList = data.activities.publication || [];
        const bookList = data.activities.book_published || [];
        
        pubList.forEach(p => {
          const text = `Paper: "${p.details.title}" in ${p.details.journal} (${p.details.year}) - Author: ${p.staff_name}`;
          const div = document.createElement('div');
          div.className = 'flex items-center gap-2';
          div.innerHTML = `
            <input type="text" name="professional_activities[publications][]" value="${text}" class="flex-grow bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm">
            <button type="button" onclick="this.parentElement.remove()" class="px-2.5 py-2 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-xl transition text-sm font-bold cursor-pointer">Remove</button>
          `;
          publicationsContainer.appendChild(div);
        });

        bookList.forEach(b => {
          const text = `Book: "${b.details.title}", ISBN: ${b.details.isbn}, Publisher: ${b.details.publisher} (${b.details.year}) - Author: ${b.staff_name}`;
          const div = document.createElement('div');
          div.className = 'flex items-center gap-2';
          div.innerHTML = `
            <input type="text" name="professional_activities[publications][]" value="${text}" class="flex-grow bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm">
            <button type="button" onclick="this.parentElement.remove()" class="px-2.5 py-2 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-xl transition text-sm font-bold cursor-pointer">Remove</button>
          `;
          publicationsContainer.appendChild(div);
        });

        // 2. Syllabus Gaps (Criterion 10)
        const gapsList = data.activities.gap_in_syllabus || [];
        if (gapsList.length > 0) {
          const statusSelect = document.querySelector('select[name="teaching_learning[gaps][status]"]');
          if (statusSelect) statusSelect.value = 'Yes';
          
          const remarksInput = document.querySelector('input[name="teaching_learning[gaps][remarks]"]');
          if (remarksInput) {
            remarksInput.value = gapsList.map(g => `${g.details.subject}: ${g.details.gap_details} (${g.details.action_taken})`).join('; ');
          }
        }

        // 3. Faculty Training (Criterion 12)
        const ftContainer = document.getElementById('facultyTrainingContainer');
        const fdpAttended = data.activities.fdp_attended || [];
        const workshopAttended = data.activities.workshop_attended || [];
        const courseAttended = data.activities.course_attended || [];
        
        const allTraining = [...fdpAttended, ...workshopAttended, ...courseAttended];
        allTraining.forEach((t, i) => {
          const index = ftContainer.children.length;
          const tr = document.createElement('tr');
          const typeLabel = t.activity_type.replace('_', ' ').toUpperCase();
          tr.innerHTML = `
            <td class="p-3"><input type="text" name="faculty_training[${index}][name]" value="${t.staff_name}" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
            <td class="p-3"><input type="text" name="faculty_training[${index}][designation]" value="${t.designation}" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
            <td class="p-3"><input type="text" name="faculty_training[${index}][title]" value="${typeLabel}: ${t.details.title}" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
            <td class="p-3"><input type="number" name="faculty_training[${index}][duration]" value="${parseInt(t.details.duration) || 3}" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-center text-white text-sm outline-none"></td>
            <td class="p-3"><input type="text" name="faculty_training[${index}][venue]" value="${t.details.venue || 'N/A'}" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-white text-sm outline-none"></td>
            <td class="p-3 text-center">
              <button type="button" onclick="this.parentElement.parentElement.remove()" class="px-2 py-1 bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 hover:border-rose-400 text-rose-400 rounded-lg transition text-xs font-bold cursor-pointer">Remove</button>
            </td>
          `;
          ftContainer.appendChild(tr);
        });

        alert('Staff logs compiled and imported successfully!');
      } catch (err) {
        console.error(err);
        alert('Error fetching staff logs.');
      }
    }
  </script>

</body>
</html>
