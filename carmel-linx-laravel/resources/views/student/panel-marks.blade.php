<div id="panelMarks" class="hidden space-y-6">
          
          <!-- Gauges & Trend Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Cumulative GPA Gauge -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col justify-between items-center text-center">
              <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Cumulative GPA</span>
              <div class="relative w-28 h-28 flex items-center justify-center my-3">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                  <circle cx="50" cy="50" r="40" stroke="#F1F5F9" stroke-width="8" fill="transparent" />
                  <circle id="cgpaGaugeProgress" cx="50" cy="50" r="40" stroke="#2563EB" stroke-width="8" fill="transparent"
                          stroke-dasharray="251.2" stroke-dashoffset="251.2" stroke-linecap="round" class="transition-all duration-1000 ease-out" />
                </svg>
                <div class="absolute flex flex-col items-center leading-none">
                  <span id="overallCgpa" class="text-2xl font-bold text-slate-900">0.00</span>
                  <span class="text-[10px] text-slate-400 font-medium mt-1 uppercase">Max 10.0</span>
                </div>
              </div>
              <div class="text-xs text-slate-500 font-medium">
                Classification: <span id="diplomaClassification" class="text-blue-700 font-semibold">--</span>
              </div>
            </div>

            <!-- Activity Points Gauge -->
            <div id="activityPointsCard" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col justify-between items-center text-center">
              <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Activity Points</span>
              <div class="relative w-28 h-28 flex items-center justify-center my-3">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                  <circle cx="50" cy="50" r="40" stroke="#F1F5F9" stroke-width="8" fill="transparent" />
                  <circle id="activityGaugeProgress" cx="50" cy="50" r="40" stroke="#10B981" stroke-width="8" fill="transparent"
                          stroke-dasharray="251.2" stroke-dashoffset="251.2" stroke-linecap="round" class="transition-all duration-1000 ease-out" />
                </svg>
                <div class="absolute flex flex-col items-center leading-none">
                  <span id="overallActivityPoints" class="text-2xl font-bold text-slate-900">0</span>
                  <span class="text-[10px] text-slate-400 font-medium mt-1 uppercase">Max 160</span>
                </div>
              </div>
              <div class="text-xs text-slate-500 font-medium">
                Min Required: <span class="text-emerald-700 font-semibold">60 Points</span>
              </div>
            </div>

            <!-- Overall Attendance Gauge -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col justify-between items-center text-center">
              <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Semester Attendance</span>
              <div class="relative w-28 h-28 flex items-center justify-center my-3">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                  <circle cx="50" cy="50" r="40" stroke="#F1F5F9" stroke-width="8" fill="transparent" />
                  <circle id="attendanceGaugeProgress" cx="50" cy="50" r="40" stroke="#10B981" stroke-width="8" fill="transparent"
                          stroke-dasharray="251.2" stroke-dashoffset="251.2" stroke-linecap="round" class="transition-all duration-1000 ease-out" />
                </svg>
                <div class="absolute flex flex-col items-center leading-none">
                  <span id="overallAttendancePct" class="text-2xl font-bold text-slate-900">0%</span>
                  <span class="text-[10px] text-slate-400 font-medium mt-1 uppercase">Current Sem</span>
                </div>
              </div>
              <div class="text-xs text-slate-500 font-medium">
                Present Hours: <span id="attendanceHoursDetail" class="text-slate-900 font-semibold">0 / 0</span>
              </div>
            </div>

            <!-- SGPA Trend Chart -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col justify-between items-center text-center">
              <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">SGPA Performance</span>
              <div class="w-full h-28 flex items-center justify-center my-2">
                <canvas id="cgpaChart"></canvas>
              </div>
              <div class="text-xs text-slate-400 font-medium">Semester-wise Trend</div>
            </div>

          </div>

          <!-- Semester Selection Tabs -->
          <div class="bg-white border border-slate-200 rounded-2xl p-3 shadow-sm flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-2">Select Semester:</span>
            <div id="semesterTabsContainer" class="flex gap-1.5 overflow-x-auto"></div>
          </div>

          <!-- Academic Marks Report (God Table) -->
          <div id="academicReportContent" class="space-y-4">
            <div class="text-slate-400 text-center p-8 bg-white border border-slate-200 rounded-2xl shadow-sm text-xs font-medium">Loading marksheet & evaluation records...</div>
          </div>

        </div>
