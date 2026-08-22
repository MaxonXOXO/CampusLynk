@php
  $auditData = $auditData ?? [];
  $academicYear = $academicYear ?? (date('Y') . '-' . (date('Y') + 1));
  $department = $department ?? 'Department';
@endphp

<x-layouts.app-shell 
    title="CampusLynk - SBTE Academic Audit" 
    topbarTitle="SBTE Academic Audit" 
    topbarSubtitle="State Board of Technical Education (SBTE) Part C Annual Self-Assessment & Compliance Console"
    activeNav="report_centre">

  <style>
    .tab-content {
      display: none;
    }
    .tab-content.active {
      display: block;
    }
    .tab-btn.active {
      background-color: #2563eb;
      color: #ffffff;
      border-color: #2563eb;
    }
    .tab-btn:not(.active) {
      background-color: #ffffff;
      color: #475569;
      border-color: #e2e8f0;
    }
    .tab-btn:not(.active):hover {
      background-color: #f8fafc;
      color: #0f172a;
    }
  </style>

  <div class="space-y-6 max-w-7xl mx-auto pb-12">
    
    <!-- Breadcrumb Navigation -->
    <div class="flex items-center gap-2 text-sm text-slate-500">
      <a href="/dashboard/hod?panel=report_centre" class="hover:text-blue-600 font-medium transition-colors flex items-center gap-1.5 no-underline">
        <i data-lucide="bar-chart-3" class="w-4 h-4 text-slate-400"></i>
        <span>Report Centre</span>
      </a>
      <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300"></i>
      <span class="text-slate-900 font-semibold">SBTE Academic Audit (Part C)</span>
    </div>

    @if(session('success'))
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 p-4 rounded-2xl text-sm font-semibold flex items-center gap-3 shadow-xs">
        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    <!-- Header & Action Panel -->
    <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shrink-0">
          <i data-lucide="award" class="w-6 h-6 text-blue-600"></i>
        </div>
        <div>
          <h3 class="text-base font-bold text-slate-900">SBTE Academic Audit Console</h3>
          <p class="text-xs text-slate-500 mt-0.5">SITTTR Kerala 15-Criteria Departmental Self-Assessment with live Institutional DB aggregation.</p>
        </div>
      </div>

      <div class="flex flex-wrap sm:flex-nowrap items-center gap-2.5 shrink-0">
        <!-- Academic Year Switcher Form -->
        <form method="GET" action="/hod/sbte-audit" class="flex items-center gap-2">
          <div class="relative">
            <select name="academic_year" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-bold text-slate-800 outline-none cursor-pointer hover:bg-slate-100 transition-colors focus:border-blue-600">
              <option value="2025-2026" {{ $academicYear === '2025-2026' ? 'selected' : '' }}>AY 2025–2026</option>
              <option value="2026-2027" {{ $academicYear === '2026-2027' ? 'selected' : '' }}>AY 2026–2027</option>
              <option value="2024-2025" {{ $academicYear === '2024-2025' ? 'selected' : '' }}>AY 2024–2025</option>
            </select>
          </div>
        </form>

        <button type="submit" form="auditForm" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-xs flex items-center gap-2 cursor-pointer">
          <i data-lucide="save" class="w-4 h-4"></i>
          <span>Save Progress</span>
        </button>

        <a href="/hod/sbte-audit/print?academic_year={{ urlencode($academicYear) }}" target="_blank" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm border border-slate-200 rounded-xl transition-all shadow-2xs flex items-center gap-2 no-underline cursor-pointer">
          <i data-lucide="printer" class="w-4 h-4 text-slate-500"></i>
          <span>Print Part C</span>
        </a>

        <a href="/dashboard/hod?panel=report_centre" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 font-medium text-xs border border-slate-200 rounded-xl shadow-2xs transition-all duration-200 flex items-center gap-1.5 no-underline cursor-pointer">
          <i data-lucide="arrow-left" class="w-3.5 h-3.5 text-slate-500"></i>
          <span>Back to Report Centre</span>
        </a>
      </div>
    </div>

    <!-- Contextual Guidance Banner -->
    <div class="bg-blue-50/60 border border-blue-200/80 rounded-2xl p-5 shadow-xs flex items-start gap-3.5">
      <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
        <i data-lucide="info" class="w-5 h-5 text-blue-700"></i>
      </div>
      <div class="space-y-1 text-sm">
        <h4 class="font-bold text-blue-950">SBTE Kerala Academic Audit Guidelines</h4>
        <p class="text-blue-800 leading-relaxed text-xs">
          Complete the 15 statutory criteria across the 3 sections below. Use the <span class="font-semibold text-indigo-700">"Generate from DB"</span> and <span class="font-semibold text-indigo-700">"Fetch from Staff Logs"</span> actions to automatically populate student pass metrics, course files, and verified faculty activities. All entries are preserved across tabs and committed when clicking <strong>"Save Progress"</strong>.
        </p>
      </div>
    </div>

    <!-- Grouped Segmented Criteria Navigation -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white p-2 border border-slate-200/80 rounded-2xl shadow-xs">
      <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
        <button type="button" onclick="switchTab('group1')" id="tab_group1" class="tab-btn active px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2 cursor-pointer shadow-2xs">
          <span class="w-5 h-5 rounded-full bg-white/20 text-xs flex items-center justify-center">1</span>
          <span>Program & Performance (Crit 1–5)</span>
        </button>
        <button type="button" onclick="switchTab('group2')" id="tab_group2" class="tab-btn px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2 cursor-pointer shadow-2xs">
          <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 text-xs flex items-center justify-center">2</span>
          <span>Faculty & Teaching-Learning (Crit 6–10)</span>
        </button>
        <button type="button" onclick="switchTab('group3')" id="tab_group3" class="tab-btn px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2 cursor-pointer shadow-2xs">
          <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 text-xs flex items-center justify-center">3</span>
          <span>Compliance & Achievements (Crit 11–15)</span>
        </button>
      </div>

      <div class="text-xs font-bold text-slate-400 px-3">
        15 Total Criteria
      </div>
    </div>

    <!-- Master Form Containing All 15 Criteria -->
    <form id="auditForm" method="POST" action="/hod/sbte-audit/save" onsubmit="return confirm('Are you sure you want to save all SBTE audit progress?');" class="space-y-6">
      @csrf
      <input type="hidden" name="academic_year" value="{{ $academicYear }}">

      <!-- ========================================================================= -->
      <!-- GROUP 1: PROGRAM & ACADEMIC PERFORMANCE (CRITERIA 1 - 5)                  -->
      <!-- ========================================================================= -->
      <div id="content_group1" class="tab-content active space-y-6">
        
        <!-- Criterion 1: Program & HOD Details -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 01</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Program & HOD Details</h4>
            </div>
            <span class="px-3 py-1 bg-slate-50 text-slate-600 font-semibold text-xs border border-slate-200 rounded-lg">General</span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">NBA Accredited? (Criterion 1)</label>
              <select name="nba_accredited" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none cursor-pointer">
                <option value="1" {{ ($audit->nba_accredited ?? false) ? 'selected' : '' }}>Yes (Accredited)</option>
                <option value="0" {{ !($audit->nba_accredited ?? false) ? 'selected' : '' }}>No (Not Accredited)</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Name of HOD</label>
              <input type="text" name="professional_activities[hod_name]" value="{{ $auditData['professional_activities']['hod_name'] ?? '' }}" placeholder="Name of HOD" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Number of Faculty / Staff</label>
              <input type="number" name="professional_activities[faculty_count]" value="{{ $auditData['professional_activities']['faculty_count'] ?? '' }}" placeholder="e.g. 15" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
            </div>
          </div>
        </div>

        <!-- Criterion 2: Enrollment -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 02</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Student Enrollment Metrics</h4>
            </div>
            <span class="text-xs text-slate-500 font-medium">CAY, CAY-1, CAY-2</span>
          </div>

          <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="p-3">Academic Year</th>
                    <th class="p-3 text-center">Approved Intake</th>
                    <th class="p-3 text-center">Students Enrolled</th>
                    <th class="p-3 text-center">Present Strength</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @php $enData = $auditData['enrollment'] ?? []; @endphp
                  @foreach(['CAY', 'CAY-1', 'CAY-2'] as $y)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="p-3 font-bold text-slate-800">{{ $y }}</td>
                      <td class="p-2.5">
                        <input type="number" name="enrollment[{{ $y }}][intake]" value="{{ $enData[$y]['intake'] ?? '' }}" class="w-28 mx-auto block bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-center text-slate-900 text-sm font-medium focus:border-blue-600 outline-none">
                      </td>
                      <td class="p-2.5">
                        <input type="number" name="enrollment[{{ $y }}][enrolled]" value="{{ $enData[$y]['enrolled'] ?? '' }}" class="w-28 mx-auto block bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-center text-slate-900 text-sm font-medium focus:border-blue-600 outline-none">
                      </td>
                      <td class="p-2.5">
                        <input type="number" name="enrollment[{{ $y }}][present]" value="{{ $enData[$y]['present'] ?? '' }}" class="w-28 mx-auto block bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-center text-slate-900 text-sm font-medium focus:border-blue-600 outline-none">
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Criterion 3: Academic Performance Without Backlog -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 03</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Academic Performance (Without Backlog)</h4>
            </div>
            <button type="button" onclick="generateAcademicPerformance()" class="px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs border border-indigo-200 rounded-xl transition flex items-center gap-1.5 shadow-2xs cursor-pointer">
              <i data-lucide="sparkles" class="w-3.5 h-3.5 text-indigo-600"></i>
              <span>Generate from DB</span>
            </button>
          </div>

          <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="p-3 w-28">Semester</th>
                    <th class="p-3 text-center">CAY (Registered / Passed)</th>
                    <th class="p-3 text-center">CAY-1 (Registered / Passed)</th>
                    <th class="p-3 text-center">CAY-2 (Registered / Passed)</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @php $perfNoBack = $auditData['perf_no_backlog'] ?? []; @endphp
                  @for($s = 1; $s <= 6; $s++)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="p-3 font-bold text-slate-800">Semester {{ $s }}</td>
                      @foreach(['CAY', 'CAY-1', 'CAY-2'] as $y)
                        <td class="p-2.5">
                          <div class="flex items-center justify-center gap-1.5">
                            <input type="number" name="perf_no_backlog[{{ $s }}][{{ $y }}][reg]" value="{{ $perfNoBack[$s][$y]['reg'] ?? '' }}" placeholder="Reg" class="w-20 bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-slate-900 text-sm font-medium focus:border-blue-600 outline-none">
                            <span class="text-slate-400 font-bold">/</span>
                            <input type="number" name="perf_no_backlog[{{ $s }}][{{ $y }}][pass]" value="{{ $perfNoBack[$s][$y]['pass'] ?? '' }}" placeholder="Pass" class="w-20 bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-emerald-700 text-sm font-bold focus:border-blue-600 outline-none">
                          </div>
                        </td>
                      @endforeach
                    </tr>
                  @endfor
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Criterion 4: Academic Performance With Backlog -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 04</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Academic Performance (With Backlog)</h4>
            </div>
            <button type="button" onclick="generateAcademicPerformance()" class="px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs border border-indigo-200 rounded-xl transition flex items-center gap-1.5 shadow-2xs cursor-pointer">
              <i data-lucide="sparkles" class="w-3.5 h-3.5 text-indigo-600"></i>
              <span>Generate from DB</span>
            </button>
          </div>

          <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="p-3 w-28">Semester</th>
                    <th class="p-3 text-center">CAY (Registered / Passed)</th>
                    <th class="p-3 text-center">CAY-1 (Registered / Passed)</th>
                    <th class="p-3 text-center">CAY-2 (Registered / Passed)</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @php $perfBack = $auditData['perf_with_backlog'] ?? []; @endphp
                  @for($s = 1; $s <= 6; $s++)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="p-3 font-bold text-slate-800">Semester {{ $s }}</td>
                      @foreach(['CAY', 'CAY-1', 'CAY-2'] as $y)
                        <td class="p-2.5">
                          <div class="flex items-center justify-center gap-1.5">
                            <input type="number" name="perf_with_backlog[{{ $s }}][{{ $y }}][reg]" value="{{ $perfBack[$s][$y]['reg'] ?? '' }}" placeholder="Reg" class="w-20 bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-slate-900 text-sm font-medium focus:border-blue-600 outline-none">
                            <span class="text-slate-400 font-bold">/</span>
                            <input type="number" name="perf_with_backlog[{{ $s }}][{{ $y }}][pass]" value="{{ $perfBack[$s][$y]['pass'] ?? '' }}" placeholder="Pass" class="w-20 bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-amber-700 text-sm font-bold focus:border-blue-600 outline-none">
                          </div>
                        </td>
                      @endforeach
                    </tr>
                  @endfor
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Criterion 5: Placement -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 05</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Placement & Higher Studies Outcomes</h4>
            </div>
            <span class="text-xs text-slate-500 font-medium">CAY-1, CAY-2, CAY-3</span>
          </div>

          <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="p-3">Batch</th>
                    <th class="p-3 text-center">Admitted</th>
                    <th class="p-3 text-center">Placed</th>
                    <th class="p-3 text-center">Higher Education</th>
                    <th class="p-3 text-center">Entrepreneurs</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @php $placement = $auditData['placement'] ?? []; @endphp
                  @foreach(['CAY-1', 'CAY-2', 'CAY-3'] as $b)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="p-3 font-bold text-slate-800">{{ $b }}</td>
                      <td class="p-2.5"><input type="number" name="placement[{{ $b }}][admitted]" value="{{ $placement[$b]['admitted'] ?? '' }}" class="w-24 mx-auto block bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-center text-slate-900 text-sm font-medium focus:border-blue-600 outline-none"></td>
                      <td class="p-2.5"><input type="number" name="placement[{{ $b }}][placed]" value="{{ $placement[$b]['placed'] ?? '' }}" class="w-24 mx-auto block bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-center text-slate-900 text-sm font-medium focus:border-blue-600 outline-none"></td>
                      <td class="p-2.5"><input type="number" name="placement[{{ $b }}][higher_ed]" value="{{ $placement[$b]['higher_ed'] ?? '' }}" class="w-24 mx-auto block bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-center text-slate-900 text-sm font-medium focus:border-blue-600 outline-none"></td>
                      <td class="p-2.5"><input type="number" name="placement[{{ $b }}][entrepreneurs]" value="{{ $placement[$b]['entrepreneurs'] ?? '' }}" class="w-24 mx-auto block bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-center text-slate-900 text-sm font-medium focus:border-blue-600 outline-none"></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="flex justify-end pt-2">
          <button type="button" onclick="switchTab('group2')" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-xl transition flex items-center gap-2 cursor-pointer shadow-xs">
            <span>Next: Faculty & Teaching-Learning</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
          </button>
        </div>

      </div>

      <!-- ========================================================================= -->
      <!-- GROUP 2: FACULTY & TEACHING-LEARNING (CRITERIA 6 - 10)                    -->
      <!-- ========================================================================= -->
      <div id="content_group2" class="tab-content space-y-6">
        
        <!-- Criterion 6: Professional Activities -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 06</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Professional Societies, Chapters & Publications</h4>
            </div>
            <button type="button" onclick="fetchStaffActivities()" class="px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs border border-indigo-200 rounded-xl transition flex items-center gap-1.5 shadow-2xs cursor-pointer">
              <i data-lucide="sparkles" class="w-3.5 h-3.5 text-indigo-600"></i>
              <span>Fetch from Staff Logs</span>
            </button>
          </div>

          <!-- Societies list -->
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">i. Professional Societies / Student Chapters</label>
              <button type="button" onclick="addSocietyRow()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center gap-1 cursor-pointer">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Add Society</span>
              </button>
            </div>
            <div id="societiesContainer" class="space-y-2">
              @php $societies = $auditData['professional_activities']['societies'] ?? ['']; @endphp
              @foreach($societies as $soc)
                <div class="flex items-center gap-2">
                  <input type="text" name="professional_activities[societies][]" value="{{ $soc }}" placeholder="e.g. IEEE Student Branch" class="flex-grow bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
                  <button type="button" onclick="this.parentElement.remove()" class="w-9 h-9 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition-colors flex items-center justify-center shrink-0 cursor-pointer" title="Remove">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </div>
              @endforeach
            </div>
          </div>

          <!-- Publications list -->
          <div class="space-y-3 pt-3 border-t border-slate-100">
            <div class="flex items-center justify-between">
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">ii. Publication of Newsletters, Magazines & Papers</label>
              <button type="button" onclick="addPublicationRow()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center gap-1 cursor-pointer">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Add Publication</span>
              </button>
            </div>
            <div id="publicationsContainer" class="space-y-2">
              @php $publications = $auditData['professional_activities']['publications'] ?? ['']; @endphp
              @foreach($publications as $pub)
                <div class="flex items-center gap-2">
                  <input type="text" name="professional_activities[publications][]" value="{{ $pub }}" placeholder="e.g. Department Newsletter Edition 2" class="flex-grow bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
                  <button type="button" onclick="this.parentElement.remove()" class="w-9 h-9 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition-colors flex items-center justify-center shrink-0 cursor-pointer" title="Remove">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        <!-- Criterion 7: SFR -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 07</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Student Faculty Ratio (SFR)</h4>
            </div>
            <span class="text-xs text-slate-500 font-medium">CAY, CAY-1, CAY-2</span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @php $sfr = $auditData['sfr'] ?? []; @endphp
            @foreach(['CAY', 'CAY-1', 'CAY-2'] as $y)
              <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">{{ $y }} SFR Ratio</label>
                <input type="text" name="sfr[{{ $y }}]" value="{{ $sfr[$y] ?? '' }}" placeholder="e.g. 1:20" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 font-semibold focus:border-blue-600 outline-none">
              </div>
            @endforeach
          </div>
        </div>

        <!-- Criterion 8: Infrastructure -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 08</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Infrastructure of Program</h4>
            </div>
            <span class="text-xs text-slate-500 font-medium">Classrooms, Labs & Facilities</span>
          </div>

          <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="p-3">Infrastructure Facility</th>
                    <th class="p-3 text-center">Number</th>
                    <th class="p-3 text-center">Area (sqm)</th>
                    <th class="p-3 text-center">Adequacy (Yes/No)</th>
                    <th class="p-3 text-center">Ambience Remarks</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @php
                    $infra = $auditData['infrastructure'] ?? [];
                    $infraItems = ['Classrooms', 'Smart classrooms', 'Laboratories', 'Computer Lab', 'Cabin for HoD', 'Faculty room', 'Others'];
                  @endphp
                  @foreach($infraItems as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="p-3 font-bold text-slate-800">{{ $item }}</td>
                      <td class="p-2.5"><input type="text" name="infrastructure[{{ $item }}][number]" value="{{ $infra[$item]['number'] ?? '' }}" placeholder="e.g. 3" class="w-20 mx-auto block bg-white border border-slate-200 rounded-xl px-2.5 py-1.5 text-center text-slate-900 text-sm outline-none focus:border-blue-600"></td>
                      <td class="p-2.5"><input type="text" name="infrastructure[{{ $item }}][area]" value="{{ $infra[$item]['area'] ?? '' }}" placeholder="e.g. 66 sqm" class="w-28 mx-auto block bg-white border border-slate-200 rounded-xl px-2.5 py-1.5 text-center text-slate-900 text-sm outline-none focus:border-blue-600"></td>
                      <td class="p-2.5"><input type="text" name="infrastructure[{{ $item }}][adequacy]" value="{{ $infra[$item]['adequacy'] ?? '' }}" placeholder="Yes/No" class="w-24 mx-auto block bg-white border border-slate-200 rounded-xl px-2.5 py-1.5 text-center text-slate-900 text-sm outline-none focus:border-blue-600"></td>
                      <td class="p-2.5"><input type="text" name="infrastructure[{{ $item }}][ambience]" value="{{ $infra[$item]['ambience'] ?? '' }}" placeholder="e.g. Good" class="w-32 mx-auto block bg-white border border-slate-200 rounded-xl px-2.5 py-1.5 text-center text-slate-900 text-sm outline-none focus:border-blue-600"></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Criterion 9: Vision & Mission -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 09</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Vision, Mission, PEOs & PSOs</h4>
            </div>
          </div>

          @php $vm = $auditData['vision_mission'] ?? []; @endphp
          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Vision Statement</label>
              <textarea name="vision_mission[vision]" rows="3" placeholder="Department Vision Statement..." class="w-full bg-white border border-slate-200 rounded-xl p-3 text-sm text-slate-900 focus:border-blue-600 outline-none">{{ $vm['vision'] ?? '' }}</textarea>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Mission Statement</label>
              <textarea name="vision_mission[mission]" rows="3" placeholder="Department Mission Statement..." class="w-full bg-white border border-slate-200 rounded-xl p-3 text-sm text-slate-900 focus:border-blue-600 outline-none">{{ $vm['mission'] ?? '' }}</textarea>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Program Educational Objectives (PEOs)</label>
              <textarea name="vision_mission[peos]" rows="3" placeholder="PEO statements..." class="w-full bg-white border border-slate-200 rounded-xl p-3 text-sm text-slate-900 focus:border-blue-600 outline-none">{{ $vm['peos'] ?? '' }}</textarea>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Program Specific Outcomes (PSOs)</label>
              <textarea name="vision_mission[psos]" rows="3" placeholder="PSO statements..." class="w-full bg-white border border-slate-200 rounded-xl p-3 text-sm text-slate-900 focus:border-blue-600 outline-none">{{ $vm['psos'] ?? '' }}</textarea>
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Availability & Dissemination Remarks</label>
            <input type="text" name="vision_mission[remarks]" value="{{ $vm['remarks'] ?? '' }}" placeholder="Availability and Dissemination status" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-600 outline-none">
          </div>
        </div>

        <!-- Criterion 10: Teaching-Learning Process -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 10</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Teaching - Learning Process Compliance</h4>
            </div>
            <button type="button" onclick="fetchStaffActivities()" class="px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs border border-indigo-200 rounded-xl transition flex items-center gap-1.5 shadow-2xs cursor-pointer">
              <i data-lucide="sparkles" class="w-3.5 h-3.5 text-indigo-600"></i>
              <span>Fetch from Staff Logs</span>
            </button>
          </div>

          <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="p-3">Teaching-Learning Item</th>
                    <th class="p-3 text-center w-36">Status (Yes/No)</th>
                    <th class="p-3">Compliance Details / Remarks</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
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
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="p-3 font-medium text-slate-800">{{ $label }}</td>
                      <td class="p-2.5">
                        <select name="teaching_learning[{{ $key }}][status]" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm font-bold text-slate-900 outline-none focus:border-blue-600 cursor-pointer">
                          <option value="Yes" {{ ($tl[$key]['status'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                          <option value="No" {{ ($tl[$key]['status'] ?? '') === 'No' ? 'selected' : '' }}>No</option>
                        </select>
                      </td>
                      <td class="p-2.5">
                        <input type="text" name="teaching_learning[{{ $key }}][remarks]" value="{{ $tl[$key]['remarks'] ?? '' }}" placeholder="Remarks..." class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600">
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="flex justify-between items-center pt-2">
          <button type="button" onclick="switchTab('group1')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition flex items-center gap-2 cursor-pointer">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Previous: Program & Performance</span>
          </button>
          <button type="button" onclick="switchTab('group3')" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-xl transition flex items-center gap-2 cursor-pointer shadow-xs">
            <span>Next: Compliance & Achievements</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
          </button>
        </div>

      </div>

      <!-- ========================================================================= -->
      <!-- GROUP 3: COMPLIANCE & ACHIEVEMENTS (CRITERIA 11 - 15)                     -->
      <!-- ========================================================================= -->
      <div id="content_group3" class="tab-content space-y-6">
        
        <!-- Criterion 11: Course Files -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 11</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Course Files (Coverage & Attainment)</h4>
            </div>
            <button type="button" onclick="generateCourseFiles()" class="px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs border border-indigo-200 rounded-xl transition flex items-center gap-1.5 shadow-2xs cursor-pointer">
              <i data-lucide="sparkles" class="w-3.5 h-3.5 text-indigo-600"></i>
              <span>Generate from DB</span>
            </button>
          </div>

          <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="p-3">Batch</th>
                    <th class="p-3 text-center">Revision Year</th>
                    <th class="p-3 text-center">No. Courses</th>
                    <th class="p-3 text-center">Completed Files</th>
                    <th class="p-3 text-center">PO Attained?</th>
                    <th class="p-3 text-center">PSO Attained?</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @php
                    $cf = $auditData['course_files'] ?? [];
                    $batches = ['CAY-3' => 'CAY-3', 'CAY-2' => 'CAY-2', 'CAY-1' => 'CAY-1'];
                  @endphp
                  @foreach($batches as $key => $label)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="p-3 font-bold text-slate-800">{{ $label }}</td>
                      <td class="p-2.5"><input type="text" name="course_files[{{ $key }}][rev_year]" value="{{ $cf[$key]['rev_year'] ?? ($key === 'CAY-1' ? '21' : '15') }}" placeholder="e.g. 15" class="w-20 mx-auto block bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-slate-900 text-sm outline-none focus:border-blue-600"></td>
                      <td class="p-2.5"><input type="number" name="course_files[{{ $key }}][courses]" value="{{ $cf[$key]['courses'] ?? '' }}" placeholder="e.g. 18" class="w-20 mx-auto block bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-slate-900 text-sm outline-none focus:border-blue-600"></td>
                      <td class="p-2.5"><input type="number" name="course_files[{{ $key }}][completed]" value="{{ $cf[$key]['completed'] ?? '' }}" placeholder="e.g. 17" class="w-24 mx-auto block bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-slate-900 text-sm outline-none focus:border-blue-600"></td>
                      <td class="p-2.5">
                        <select name="course_files[{{ $key }}][po_attained]" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-slate-900 text-sm font-bold outline-none focus:border-blue-600 cursor-pointer">
                          <option value="Yes" {{ ($cf[$key]['po_attained'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                          <option value="No" {{ ($cf[$key]['po_attained'] ?? '') === 'No' ? 'selected' : '' }}>No</option>
                        </select>
                      </td>
                      <td class="p-2.5">
                        <select name="course_files[{{ $key }}][pso_attained]" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-slate-900 text-sm font-bold outline-none focus:border-blue-600 cursor-pointer">
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
        </div>

        <!-- Criterion 12: Faculty Training -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 12</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Faculty Training Participation</h4>
            </div>
            <div class="flex items-center gap-2">
              <button type="button" onclick="fetchStaffActivities()" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs border border-indigo-200 rounded-xl transition flex items-center gap-1 shadow-2xs cursor-pointer">
                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-indigo-600"></i>
                <span>Fetch Logs</span>
              </button>
              <button type="button" onclick="addFacultyTrainingRow()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center gap-1 shadow-2xs cursor-pointer">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Add Training</span>
              </button>
            </div>
          </div>

          <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="p-3">Faculty Name</th>
                    <th class="p-3">Designation</th>
                    <th class="p-3">FDP Title</th>
                    <th class="p-3 text-center w-24">Days</th>
                    <th class="p-3">Venue</th>
                    <th class="p-3 text-center w-16"></th>
                  </tr>
                </thead>
                <tbody id="facultyTrainingContainer" class="divide-y divide-slate-100">
                  @php $ftRows = $auditData['faculty_training'] ?? []; @endphp
                  @foreach($ftRows as $index => $row)
                    @if(is_array($row) && isset($row['name']))
                      <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-2.5"><input type="text" name="faculty_training[{{ $index }}][name]" value="{{ $row['name'] }}" placeholder="Faculty Name" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5"><input type="text" name="faculty_training[{{ $index }}][designation]" value="{{ $row['designation'] }}" placeholder="Designation" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5"><input type="text" name="faculty_training[{{ $index }}][title]" value="{{ $row['title'] }}" placeholder="FDP Title" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5"><input type="number" name="faculty_training[{{ $index }}][duration]" value="{{ $row['duration'] }}" placeholder="Days" class="w-full bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5"><input type="text" name="faculty_training[{{ $index }}][venue]" value="{{ $row['venue'] }}" placeholder="Venue" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5 text-center">
                          <button type="button" onclick="this.parentElement.parentElement.remove()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center mx-auto cursor-pointer" title="Remove">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                          </button>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Criterion 13: FDPs Conducted -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 13</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Details of FDPs Conducted in Past 3 Years</h4>
            </div>
            <div class="flex items-center gap-2">
              <button type="button" onclick="fetchStaffActivities()" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs border border-indigo-200 rounded-xl transition flex items-center gap-1 shadow-2xs cursor-pointer">
                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-indigo-600"></i>
                <span>Fetch Logs</span>
              </button>
              <button type="button" onclick="addFdpConductedRow()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center gap-1 shadow-2xs cursor-pointer">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Add FDP</span>
              </button>
            </div>
          </div>

          <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="p-3">Name of FDP</th>
                    <th class="p-3 text-center w-28">No. Attended</th>
                    <th class="p-3 text-center w-36">Date From</th>
                    <th class="p-3">Funding Agency</th>
                    <th class="p-3 text-center w-16"></th>
                  </tr>
                </thead>
                <tbody id="fdpConductedContainer" class="divide-y divide-slate-100">
                  @php $fdpRows = $auditData['fdp_conducted'] ?? []; @endphp
                  @foreach($fdpRows as $index => $row)
                    @if(is_array($row) && isset($row['title']))
                      <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-2.5"><input type="text" name="fdp_conducted[{{ $index }}][title]" value="{{ $row['title'] }}" placeholder="FDP Title" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5"><input type="number" name="fdp_conducted[{{ $index }}][attended]" value="{{ $row['attended'] }}" placeholder="No." class="w-full bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5"><input type="text" name="fdp_conducted[{{ $index }}][date_from]" value="{{ $row['date_from'] }}" placeholder="YYYY-MM-DD" class="w-full bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5"><input type="text" name="fdp_conducted[{{ $index }}][funding]" value="{{ $row['funding'] }}" placeholder="e.g. SITTTR" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5 text-center">
                          <button type="button" onclick="this.parentElement.parentElement.remove()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center mx-auto cursor-pointer" title="Remove">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                          </button>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Criterion 14: Consultancy -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 14</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Consultancy & Testing Funds</h4>
            </div>
            <button type="button" onclick="addConsultancyRow()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center gap-1 shadow-2xs cursor-pointer">
              <i data-lucide="plus" class="w-3.5 h-3.5"></i>
              <span>Add Consultancy</span>
            </button>
          </div>

          <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="p-3">Project / Work Name</th>
                    <th class="p-3 text-center w-36">Date</th>
                    <th class="p-3 text-center w-36">Fund (₹)</th>
                    <th class="p-3">Faculty Involved</th>
                    <th class="p-3">Remarks</th>
                    <th class="p-3 text-center w-16"></th>
                  </tr>
                </thead>
                <tbody id="consultancyContainer" class="divide-y divide-slate-100">
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
                      <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-2.5"><input type="text" name="consultancy[{{ $index }}][name]" value="{{ $row['name'] }}" placeholder="Project Name" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5"><input type="text" name="consultancy[{{ $index }}][date]" value="{{ $row['date'] }}" placeholder="YYYY-MM-DD" class="w-full bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5"><input type="text" name="consultancy[{{ $index }}][fund]" value="{{ $row['fund'] }}" placeholder="Amount" class="w-full bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5"><input type="text" name="consultancy[{{ $index }}][faculty]" value="{{ $row['faculty'] }}" placeholder="Faculty Name(s)" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5"><input type="text" name="consultancy[{{ $index }}][remarks]" value="{{ $row['remarks'] }}" placeholder="Remarks" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5 text-center">
                          <button type="button" onclick="this.parentElement.parentElement.remove()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center mx-auto cursor-pointer" title="Remove">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                          </button>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Criterion 15: Achievements -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-3">
            <div>
              <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Criterion 15</span>
              <h4 class="text-base font-bold text-slate-900 mt-0.5">Remarkable Achievements (Faculty & Students)</h4>
            </div>
            <button type="button" onclick="addAchievementRow()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center gap-1 shadow-2xs cursor-pointer">
              <i data-lucide="plus" class="w-3.5 h-3.5"></i>
              <span>Add Achievement</span>
            </button>
          </div>

          <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="p-3 text-center w-12">No.</th>
                    <th class="p-3 w-40">Category</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Achievement Details</th>
                    <th class="p-3">Remarks</th>
                    <th class="p-3 text-center w-16"></th>
                  </tr>
                </thead>
                <tbody id="achievementsContainer" class="divide-y divide-slate-100">
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
                      <tr class="achievement-row hover:bg-slate-50/50 transition-colors">
                        <td class="p-3 text-center font-bold text-slate-400 row-num">{{ $index + 1 }}</td>
                        <td class="p-2.5">
                          <select name="achievements[{{ $index }}][category]" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm font-semibold text-slate-900 outline-none focus:border-blue-600 cursor-pointer">
                            <option value="Faculty" {{ ($row['category'] ?? '') === 'Faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="Student" {{ ($row['category'] ?? '') === 'Student' ? 'selected' : '' }}>Student</option>
                          </select>
                        </td>
                        <td class="p-2.5"><input type="text" name="achievements[{{ $index }}][name]" value="{{ $row['name'] }}" placeholder="Name" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5"><input type="text" name="achievements[{{ $index }}][achievement]" value="{{ $row['achievement'] }}" placeholder="Achievement details" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5"><input type="text" name="achievements[{{ $index }}][remarks]" value="{{ $row['remarks'] }}" placeholder="Remarks" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
                        <td class="p-2.5 text-center">
                          <button type="button" onclick="removeAchievementRow(this)" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center mx-auto cursor-pointer" title="Remove">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                          </button>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="flex justify-between items-center pt-2">
          <button type="button" onclick="switchTab('group2')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition flex items-center gap-2 cursor-pointer">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Previous: Faculty & Teaching-Learning</span>
          </button>
          <button type="submit" form="auditForm" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition flex items-center gap-2 cursor-pointer shadow-xs">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Save All SBTE Progress</span>
          </button>
        </div>

      </div>

    </form>

  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      if (window.lucide) {
        lucide.createIcons();
      }
    });

    function switchTab(groupId) {
      document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
      document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
      
      const content = document.getElementById('content_' + groupId);
      const tab = document.getElementById('tab_' + groupId);
      
      if (content) content.classList.add('active');
      if (tab) tab.classList.add('active');
      
      if (window.lucide) {
        lucide.createIcons();
      }
      window.scrollTo({ top: 120, behavior: 'smooth' });
    }
    window.switchTab = switchTab;

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
    window.generateAcademicPerformance = generateAcademicPerformance;

    function addSocietyRow() {
      const container = document.getElementById('societiesContainer');
      if (!container) return;
      const div = document.createElement('div');
      div.className = 'flex items-center gap-2';
      div.innerHTML = `
        <input type="text" name="professional_activities[societies][]" placeholder="e.g. IEEE Student Branch" class="flex-grow bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
        <button type="button" onclick="this.parentElement.remove()" class="w-9 h-9 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition-colors flex items-center justify-center shrink-0 cursor-pointer" title="Remove">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
      `;
      container.appendChild(div);
      if (window.lucide) lucide.createIcons();
    }
    window.addSocietyRow = addSocietyRow;

    function addPublicationRow() {
      const container = document.getElementById('publicationsContainer');
      if (!container) return;
      const div = document.createElement('div');
      div.className = 'flex items-center gap-2';
      div.innerHTML = `
        <input type="text" name="professional_activities[publications][]" placeholder="e.g. Department Newsletter Edition 2" class="flex-grow bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
        <button type="button" onclick="this.parentElement.remove()" class="w-9 h-9 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition-colors flex items-center justify-center shrink-0 cursor-pointer" title="Remove">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
      `;
      container.appendChild(div);
      if (window.lucide) lucide.createIcons();
    }
    window.addPublicationRow = addPublicationRow;

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
    window.generateCourseFiles = generateCourseFiles;

    function addFacultyTrainingRow() {
      const container = document.getElementById('facultyTrainingContainer');
      if (!container) return;
      const index = container.children.length;
      const tr = document.createElement('tr');
      tr.className = 'hover:bg-slate-50/50 transition-colors';
      tr.innerHTML = `
        <td class="p-2.5"><input type="text" name="faculty_training[${index}][name]" placeholder="Faculty Name" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5"><input type="text" name="faculty_training[${index}][designation]" placeholder="Designation" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5"><input type="text" name="faculty_training[${index}][title]" placeholder="FDP Title" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5"><input type="number" name="faculty_training[${index}][duration]" placeholder="Days" class="w-full bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5"><input type="text" name="faculty_training[${index}][venue]" placeholder="Venue" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5 text-center">
          <button type="button" onclick="this.parentElement.parentElement.remove()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center mx-auto cursor-pointer" title="Remove">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
          </button>
        </td>
      `;
      container.appendChild(tr);
      if (window.lucide) lucide.createIcons();
    }
    window.addFacultyTrainingRow = addFacultyTrainingRow;

    function addFdpConductedRow() {
      const container = document.getElementById('fdpConductedContainer');
      if (!container) return;
      const index = container.children.length;
      const tr = document.createElement('tr');
      tr.className = 'hover:bg-slate-50/50 transition-colors';
      tr.innerHTML = `
        <td class="p-2.5"><input type="text" name="fdp_conducted[${index}][title]" placeholder="FDP Title" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5"><input type="number" name="fdp_conducted[${index}][attended]" placeholder="No." class="w-full bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5"><input type="text" name="fdp_conducted[${index}][date_from]" placeholder="YYYY-MM-DD" class="w-full bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5"><input type="text" name="fdp_conducted[${index}][funding]" placeholder="e.g. SITTTR" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5 text-center">
          <button type="button" onclick="this.parentElement.parentElement.remove()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center mx-auto cursor-pointer" title="Remove">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
          </button>
        </td>
      `;
      container.appendChild(tr);
      if (window.lucide) lucide.createIcons();
    }
    window.addFdpConductedRow = addFdpConductedRow;

    function addConsultancyRow() {
      const container = document.getElementById('consultancyContainer');
      if (!container) return;
      const index = container.children.length;
      const tr = document.createElement('tr');
      tr.className = 'hover:bg-slate-50/50 transition-colors';
      tr.innerHTML = `
        <td class="p-2.5"><input type="text" name="consultancy[${index}][name]" placeholder="Project Name" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5"><input type="text" name="consultancy[${index}][date]" placeholder="YYYY-MM-DD" class="w-full bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5"><input type="text" name="consultancy[${index}][fund]" placeholder="Amount" class="w-full bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5"><input type="text" name="consultancy[${index}][faculty]" placeholder="Faculty Name(s)" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5"><input type="text" name="consultancy[${index}][remarks]" placeholder="Remarks" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5 text-center">
          <button type="button" onclick="this.parentElement.parentElement.remove()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center mx-auto cursor-pointer" title="Remove">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
          </button>
        </td>
      `;
      container.appendChild(tr);
      if (window.lucide) lucide.createIcons();
    }
    window.addConsultancyRow = addConsultancyRow;

    function reindexAchievements() {
      const container = document.getElementById('achievementsContainer');
      if (!container) return;
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
    window.reindexAchievements = reindexAchievements;

    function addAchievementRow() {
      const container = document.getElementById('achievementsContainer');
      if (!container) return;
      const index = container.children.length;
      const tr = document.createElement('tr');
      tr.className = 'achievement-row hover:bg-slate-50/50 transition-colors';
      tr.innerHTML = `
        <td class="p-3 text-center font-bold text-slate-400 row-num">${index + 1}</td>
        <td class="p-2.5">
          <select name="achievements[${index}][category]" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm font-semibold text-slate-900 outline-none focus:border-blue-600 cursor-pointer">
            <option value="Faculty">Faculty</option>
            <option value="Student">Student</option>
          </select>
        </td>
        <td class="p-2.5"><input type="text" name="achievements[${index}][name]" placeholder="Name" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5"><input type="text" name="achievements[${index}][achievement]" placeholder="Achievement details" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5"><input type="text" name="achievements[${index}][remarks]" placeholder="Remarks" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
        <td class="p-2.5 text-center">
          <button type="button" onclick="removeAchievementRow(this)" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center mx-auto cursor-pointer" title="Remove">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
          </button>
        </td>
      `;
      container.appendChild(tr);
      reindexAchievements();
      if (window.lucide) lucide.createIcons();
    }
    window.addAchievementRow = addAchievementRow;

    function removeAchievementRow(btn) {
      btn.parentElement.parentElement.remove();
      reindexAchievements();
    }
    window.removeAchievementRow = removeAchievementRow;

    async function fetchStaffActivities() {
      try {
        const yearInput = document.querySelector('input[name="academic_year"]');
        const year = yearInput ? yearInput.value : '';
        const response = await fetch('/api/hod/sbte-audit/fetch-staff-activities?academic_year=' + encodeURIComponent(year));
        if (!response.ok) {
          alert('Failed to fetch staff activities');
          return;
        }
        const data = await response.json();
        
        // 1. Publications & Newsletters
        const publicationsContainer = document.getElementById('publicationsContainer');
        const pubList = (data.activities && data.activities.publication) ? data.activities.publication : [];
        const bookList = (data.activities && data.activities.book_published) ? data.activities.book_published : [];
        
        if (publicationsContainer) {
          pubList.forEach(p => {
            const text = `Paper: "${p.details.title}" in ${p.details.journal} (${p.details.year}) - Author: ${p.staff_name}`;
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2';
            div.innerHTML = `
              <input type="text" name="professional_activities[publications][]" value="${text}" class="flex-grow bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
              <button type="button" onclick="this.parentElement.remove()" class="w-9 h-9 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition-colors flex items-center justify-center shrink-0 cursor-pointer" title="Remove">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
              </button>
            `;
            publicationsContainer.appendChild(div);
          });

          bookList.forEach(b => {
            const text = `Book: "${b.details.title}", ISBN: ${b.details.isbn}, Publisher: ${b.details.publisher} (${b.details.year}) - Author: ${b.staff_name}`;
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2';
            div.innerHTML = `
              <input type="text" name="professional_activities[publications][]" value="${text}" class="flex-grow bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
              <button type="button" onclick="this.parentElement.remove()" class="w-9 h-9 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition-colors flex items-center justify-center shrink-0 cursor-pointer" title="Remove">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
              </button>
            `;
            publicationsContainer.appendChild(div);
          });
        }

        // 2. Syllabus Gaps (Criterion 10)
        const gapsList = (data.activities && data.activities.gap_in_syllabus) ? data.activities.gap_in_syllabus : [];
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
        const fdpAttended = (data.activities && data.activities.fdp_attended) ? data.activities.fdp_attended : [];
        const workshopAttended = (data.activities && data.activities.workshop_attended) ? data.activities.workshop_attended : [];
        const courseAttended = (data.activities && data.activities.course_attended) ? data.activities.course_attended : [];
        
        const allTraining = [...fdpAttended, ...workshopAttended, ...courseAttended];
        if (ftContainer) {
          allTraining.forEach((t) => {
            const index = ftContainer.children.length;
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-50/50 transition-colors';
            const typeLabel = t.activity_type ? t.activity_type.replace('_', ' ').toUpperCase() : 'TRAINING';
            tr.innerHTML = `
              <td class="p-2.5"><input type="text" name="faculty_training[${index}][name]" value="${t.staff_name}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
              <td class="p-2.5"><input type="text" name="faculty_training[${index}][designation]" value="${t.designation}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
              <td class="p-2.5"><input type="text" name="faculty_training[${index}][title]" value="${typeLabel}: ${t.details ? t.details.title : ''}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
              <td class="p-2.5"><input type="number" name="faculty_training[${index}][duration]" value="${(t.details && parseInt(t.details.duration)) || 3}" class="w-full bg-white border border-slate-200 rounded-xl px-2 py-1.5 text-center text-sm text-slate-900 outline-none focus:border-blue-600"></td>
              <td class="p-2.5"><input type="text" name="faculty_training[${index}][venue]" value="${(t.details && t.details.venue) || 'N/A'}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 outline-none focus:border-blue-600"></td>
              <td class="p-2.5 text-center">
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center mx-auto cursor-pointer" title="Remove">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
              </td>
            `;
            ftContainer.appendChild(tr);
          });
        }

        alert('Staff logs compiled and imported successfully!');
        if (window.lucide) lucide.createIcons();
      } catch (err) {
        console.error(err);
        alert('Error fetching staff logs.');
      }
    }
    window.fetchStaffActivities = fetchStaffActivities;
  </script>

</x-layouts.app-shell>
