      <!-- PANEL: REPORT CENTRE (SINGLE-WORKSPACE CATALOG) -->
      <div id="panelReport_centre" class="{{ $initialPanel === 'report_centre' ? '' : 'hidden' }} space-y-6">
        
        <!-- Header & Operational Overview Card -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shrink-0">
              <i data-lucide="bar-chart-3" class="w-6 h-6 text-blue-600"></i>
            </div>
            <div>
              <h3 class="text-base font-bold text-slate-900">Report Centre</h3>
              <p class="text-xs text-slate-500 mt-0.5">Centralized academic, faculty, compliance, and accreditation reporting workspace.</p>
            </div>
          </div>

          <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 bg-blue-50 border border-blue-100 text-blue-700 rounded-xl text-xs font-bold font-mono">
              11 reporting modules
            </span>
            <span class="px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-xs font-bold font-mono">
              {{ $activeBranch }} Dept Scope
            </span>
          </div>
        </div>

        <!-- 11 Report Categories Grid (Desktop: 4 columns, Tablet: 2 columns, Mobile: 1 column) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
          
          <!-- Card 1: Attendance, Log & Condonation -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between space-y-4">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-100">
                  <i data-lucide="calendar-check" class="w-5 h-5 text-sky-600"></i>
                </div>
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900">Attendance &amp; Condonation</h4>
                <p class="text-xs text-slate-500 leading-relaxed mt-1">
                  Compile course coverage, attendance rosters, and condonation reports for a selected classroom.
                </p>
              </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
              <span class="text-xs font-medium text-slate-400">Coverage &amp; Shortage</span>
              <button type="button" onclick="openAttendanceModal()" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-xs rounded-xl shadow-xs transition-all duration-200 flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                <span>Compile Logs</span>
              </button>
            </div>
          </div>

          <!-- Card 2: Remedial Coaching Analytics -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between space-y-4">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100">
                  <i data-lucide="heart-pulse" class="w-5 h-5 text-purple-600"></i>
                </div>
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900">Remedial Coaching Analytics</h4>
                <p class="text-xs text-slate-500 leading-relaxed mt-1">
                  Review remedial coaching activity, diagnostics, and outcomes.
                </p>
              </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
              <span class="text-xs font-medium text-slate-400">Diagnostic Reports</span>
              <button type="button" onclick="openRemedialModal()" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-xs rounded-xl shadow-xs transition-all duration-200 flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                <span>Analyze Data</span>
              </button>
            </div>
          </div>

          <!-- Card 3: Faculty Workload & Timetables -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between space-y-4">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                  <i data-lucide="briefcase" class="w-5 h-5 text-amber-600"></i>
                </div>
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900">Faculty Workload &amp; Timetables</h4>
                <p class="text-xs text-slate-500 leading-relaxed mt-1">
                  Review departmental faculty workload and batch timetables.
                </p>
              </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
              <span class="text-xs font-medium text-slate-400">Commencement Week</span>
              <a href="/hod/report-centre/workload-panel" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-xs rounded-xl shadow-xs transition-all duration-200 flex items-center gap-1.5 no-underline">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
                <span>View Panel</span>
              </a>
            </div>
          </div>

          <!-- Card 4: Extra-Curricular Activity Points -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between space-y-4">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100">
                  <i data-lucide="trophy" class="w-5 h-5 text-rose-600"></i>
