<div id="panelDashboard" class="{{ $isSecurityPanel ? 'hidden' : '' }} space-y-6">
        
        <!-- Seminar Presentations Today dynamic notifications section -->
        <div id="seminarNotificationsContainer" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <!-- Populated dynamically -->
        </div>

        <div id="assignedClassroomHeader" class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-slate-200 p-5 rounded-2xl gap-4 shadow-sm">
          <div>
            <h3 class="text-base sm:text-lg font-bold text-slate-900">My Assigned Batches &amp; Classrooms</h3>
            <p class="text-xs text-slate-500 mt-0.5">Select a subject to enter the virtual classroom for syllabus coverage, assignments and assessments.</p>
          </div>
          <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 shadow-2xs self-stretch sm:self-auto">
            <button onclick="setDashboardBatchFilter('active')" id="btnFilterActive" class="flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-semibold bg-white text-blue-600 shadow-sm transition-all cursor-pointer">
              Active Batches
            </button>
            <button onclick="setDashboardBatchFilter('historical')" id="btnFilterHistorical" class="flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 transition-all cursor-pointer">
              Archived Batches
            </button>
          </div>
        </div>
        
        <div id="lecturerBatchGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div class="col-span-full py-16 text-center text-slate-400 font-semibold text-sm">
            <div class="w-8 h-8 border-2 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
            <span>Loading assigned batches &amp; classrooms...</span>
          </div>
        </div>
      </div>
