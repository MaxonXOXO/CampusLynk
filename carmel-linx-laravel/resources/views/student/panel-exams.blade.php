<div id="panelExams" class="space-y-6">
          
          <!-- KPI Summary Cards (Neutral 70% Base, High-Contrast Metrics) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Assignments Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <i data-lucide="file-text" class="w-5 h-5"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Assignments</p>
                <div class="flex justify-between items-baseline mt-1">
                  <span class="text-xs text-slate-500">Active: <strong class="text-slate-900 font-bold text-base" id="statActiveAssign">0</strong></span>
                  <span class="text-xs text-slate-500">Done: <strong class="text-emerald-700 font-bold text-base" id="statAssignDone">0</strong></span>
                </div>
              </div>
            </div>

            <!-- Written Tests Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <i data-lucide="file-edit" class="w-5 h-5"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Written Tests</p>
                <div class="flex justify-between items-baseline mt-1">
                  <span class="text-xs text-slate-500">Active: <strong class="text-slate-900 font-bold text-base" id="statWrittenTests">0</strong></span>
                  <span class="text-xs text-slate-500">Done: <strong class="text-blue-700 font-bold text-base" id="statWrittenTestsDone">0</strong></span>
                </div>
              </div>
            </div>

            <!-- MCQ Tests Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <i data-lucide="help-circle" class="w-5 h-5"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Online MCQ</p>
                <div class="flex justify-between items-baseline mt-1">
                  <span class="text-xs text-slate-500">Active: <strong class="text-slate-900 font-bold text-base" id="statActiveTests">0</strong></span>
                  <span class="text-xs text-slate-500">Done: <strong class="text-emerald-700 font-bold text-base" id="statTestsDone">0</strong></span>
                </div>
              </div>
            </div>

            <!-- Overall Tasks Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <i data-lucide="list-checks" class="w-5 h-5"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Overall Tasks</p>
                <div class="flex justify-between items-baseline mt-1">
                  <span class="text-xs text-slate-500">Pending: <strong class="text-rose-600 font-bold text-base" id="statPendingTotal">0</strong></span>
                  <span class="text-xs text-slate-500">Total Done: <strong class="text-slate-900 font-bold text-base" id="statOverallDone">0</strong></span>
                </div>
              </div>
            </div>

          </div>

          <!-- Pending Tasks Section -->
          <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
              <div>
                <h2 class="text-base font-bold text-slate-900">Pending Academic Work</h2>
                <p class="text-xs text-slate-500 mt-0.5">Active continuous evaluations, assignments, and series test submissions.</p>
              </div>
            </div>

            <div id="pendingGridContainer" class="grid grid-cols-1 gap-6">
              <!-- Active Surveys Container -->
              <div id="studentSurveysContainer" class="col-span-full hidden"></div>

              <!-- Column 1: Online MCQ Tests -->
              <div id="mcqTestsSection" class="hidden space-y-3">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                  <i data-lucide="help-circle" class="w-4 h-4 text-blue-600"></i>
                  <span>Online MCQ Assessments</span>
                </h3>
                <div id="studentActiveTestsList" class="flex flex-col gap-3">
                  <div class="py-8 text-center text-slate-400 font-medium text-xs">Loading active tests...</div>
                </div>
              </div>

              <!-- Column 2: Assignments & Written Tests -->
              <div id="assignmentsSection" class="hidden space-y-3">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                  <i data-lucide="file-text" class="w-4 h-4 text-blue-600"></i>
                  <span>Assignments & Written Tasks</span>
                </h3>
                <div id="studentActiveTasksContainer" class="flex flex-col gap-3">
                  <div class="py-8 text-center text-slate-400 font-medium text-xs">Loading active tasks...</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Subject Syllabus Progress Cards -->
          <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3">
              <h2 class="text-base font-bold text-slate-900">Semester Course Progress</h2>
              <p id="subjectProgressSubtitle" class="text-xs text-slate-500 mt-0.5">Completed class hours and syllabus coverage logged by course faculty.</p>
            </div>
            
            <div id="subjectProgressGrid" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 pt-2">
              <div class="col-span-full py-8 text-center text-slate-400 font-medium text-xs">Loading course syllabus coverage...</div>
            </div>
          </div>

        </div>
