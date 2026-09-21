<div id="panelAll_timetables" class="hidden space-y-6">
          
          <!-- Header Bar -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-slate-200 p-5 rounded-2xl gap-4 shadow-sm">
            <div>
              <div class="flex items-center gap-2.5">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                  <span class="material-symbols-rounded text-xl">calendar_month</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900">All-Department Master Timetables</h3>
              </div>
              <p class="text-sm text-slate-500 mt-1">Live period schedules, classroom allocations, and faculty assignments across all branches.</p>
            </div>
            
            <div class="flex items-center gap-2.5 flex-wrap">
              <!-- Active Day Order Indicator -->
              <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Active Day: <strong id="ttActiveDayOrderBadge" class="text-blue-700 font-bold">Day 1</strong></span>
              </div>
            </div>
          </div>

          <!-- Filter & Controls Bar -->
          <div class="bg-white border border-slate-200 p-5 rounded-2xl space-y-4 shadow-sm">
            
            <!-- Department Selection Tabs -->
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Select Department</label>
              <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-hidden" id="ttDeptFilterContainer">
                <button type="button" onclick="filterTtDepartment('ALL')" id="ttDeptBtn_ALL" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-bold transition-all shrink-0 bg-blue-600 text-white shadow-sm">All Departments</button>
                <button type="button" onclick="filterTtDepartment('EL')" id="ttDeptBtn_EL" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Electronics (EL)</button>
                <button type="button" onclick="filterTtDepartment('ME')" id="ttDeptBtn_ME" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Mechanical (ME)</button>
                <button type="button" onclick="filterTtDepartment('CE')" id="ttDeptBtn_CE" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Civil (CE)</button>
                <button type="button" onclick="filterTtDepartment('EEE')" id="ttDeptBtn_EEE" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Electrical (EEE)</button>
                <button type="button" onclick="filterTtDepartment('CT')" id="ttDeptBtn_CT" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Computer (CT)</button>
                <button type="button" onclick="filterTtDepartment('AU')" id="ttDeptBtn_AU" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Automobile (AU)</button>
                <button type="button" onclick="filterTtDepartment('GEN_AIDED')" id="ttDeptBtn_GEN_AIDED" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">General Aided</button>
                <button type="button" onclick="filterTtDepartment('GEN_SF')" id="ttDeptBtn_GEN_SF" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">General SF</button>
              </div>
            </div>

            <!-- Day & Semester Filter Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
              <!-- Day Order Selector Tabs -->
              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Schedule Day View</label>
                <div class="grid grid-cols-5 gap-1.5" id="ttDayFilterContainer">
                  <button type="button" onclick="filterTtDay('Day 1')" id="ttDayBtn_Day1" class="tt-day-btn py-2 px-2 rounded-xl text-xs font-bold transition-all text-center bg-blue-600 text-white shadow-sm">Day 1 (Mon)</button>
                  <button type="button" onclick="filterTtDay('Day 2')" id="ttDayBtn_Day2" class="tt-day-btn py-2 px-2 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Day 2 (Tue)</button>
                  <button type="button" onclick="filterTtDay('Day 3')" id="ttDayBtn_Day3" class="tt-day-btn py-2 px-2 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Day 3 (Wed)</button>
                  <button type="button" onclick="filterTtDay('Day 4')" id="ttDayBtn_Day4" class="tt-day-btn py-2 px-2 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Day 4 (Thu)</button>
                  <button type="button" onclick="filterTtDay('Day 5')" id="ttDayBtn_Day5" class="tt-day-btn py-2 px-2 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Day 5 (Fri)</button>
                </div>
              </div>

              <!-- Semester Filter Tabs -->
              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Filter Semester</label>
                <div class="grid grid-cols-7 gap-1.5" id="ttSemFilterContainer">
                  <button type="button" onclick="filterTtSem('ALL')" id="ttSemBtn_ALL" class="tt-sem-btn py-2 px-1 rounded-xl text-xs font-bold transition-all text-center bg-blue-600 text-white shadow-sm">All</button>
                  <button type="button" onclick="filterTtSem('S1')" id="ttSemBtn_S1" class="tt-sem-btn py-2 px-1 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">S1</button>
                  <button type="button" onclick="filterTtSem('S2')" id="ttSemBtn_S2" class="tt-sem-btn py-2 px-1 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">S2</button>
                  <button type="button" onclick="filterTtSem('S3')" id="ttSemBtn_S3" class="tt-sem-btn py-2 px-1 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">S3</button>
                  <button type="button" onclick="filterTtSem('S4')" id="ttSemBtn_S4" class="tt-sem-btn py-2 px-1 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">S4</button>
                  <button type="button" onclick="filterTtSem('S5')" id="ttSemBtn_S5" class="tt-sem-btn py-2 px-1 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">S5</button>
                  <button type="button" onclick="filterTtSem('S6')" id="ttSemBtn_S6" class="tt-sem-btn py-2 px-1 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">S6</button>
                </div>
              </div>
            </div>

          </div>

          <!-- Timetable Period Grid Header -->
          <div class="bg-slate-100 border border-slate-200 px-5 py-3 rounded-xl flex items-center justify-between text-xs font-bold text-slate-600 uppercase tracking-wider">
            <span class="flex items-center gap-2">
              <span class="material-symbols-rounded text-sm text-blue-600">schedule</span>
              <span>Class Periods &amp; Time Slots (9:00 AM - 4:00 PM)</span>
            </span>
            <span id="ttTotalBatchesFound" class="font-mono text-blue-700 normal-case font-bold">Loading schedules...</span>
          </div>

          <!-- Dynamic Timetable Batches Container -->
          <div id="ttBatchesListContainer" class="space-y-4">
            <!-- Loaded dynamically via loadAllDepartmentTimetables() -->
            <div class="bg-white border border-slate-200 p-8 rounded-2xl text-center text-slate-400">
              <span class="material-symbols-rounded text-4xl block text-slate-300 mb-2">hourglass_empty</span>
              <span class="text-sm font-semibold text-slate-600">Loading department timetables...</span>
            </div>
          </div>

        </div>
