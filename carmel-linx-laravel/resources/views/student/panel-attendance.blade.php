<div id="panelAttendance" class="hidden space-y-6">
          
          <!-- Attendance Loader -->
          <div id="attendanceLoader" class="flex flex-col items-center justify-center py-12 text-center text-slate-400">
            <div class="w-8 h-8 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin mb-3"></div>
            <p class="text-xs font-semibold text-slate-500">Loading attendance records and real-time period logs...</p>
          </div>

          <!-- Attendance Content Container -->
          <div id="attendanceContent" class="hidden space-y-6">
            
            <!-- Top KPI Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              
              <!-- Cumulative Attendance Card -->
              <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between items-center text-center">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Overall Semester Attendance</span>
                
                <div class="relative w-32 h-32 flex items-center justify-center my-3">
                  <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" stroke="#F1F5F9" stroke-width="8" fill="transparent" />
                    <circle id="attGaugeCircle" cx="50" cy="50" r="40" stroke="#10B981" stroke-width="8" fill="transparent"
                            stroke-dasharray="251.2" stroke-dashoffset="0" stroke-linecap="round" class="transition-all duration-1000 ease-out" />
                  </svg>
                  <div class="absolute flex flex-col items-center leading-none">
                    <span class="text-2xl font-bold text-slate-900" id="attOverallPctText">100%</span>
                    <span class="text-[10px] text-slate-400 font-medium mt-1 uppercase">Target 75%</span>
                  </div>
                </div>

                <div class="text-xs text-slate-500 font-medium">
                  Status: 
                  <span class="font-bold text-emerald-700" id="attEligibilityBadge">
                    Satisfactory & Eligible
                  </span>
                </div>
              </div>

              <!-- Hours Statistics Card -->
              <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between space-y-4">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 pb-2">Academic Hours Summary</h3>
                
                <div class="space-y-3">
                  <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                    <span class="text-xs text-slate-600 font-medium">Conducted Sessions</span>
                    <span class="text-sm font-bold font-mono text-slate-900" id="attTotalConducted">0 Hours</span>
                  </div>
                  <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50/60 border border-emerald-200/60">
                    <span class="text-xs text-emerald-800 font-medium">Attended Sessions</span>
                    <span class="text-sm font-bold font-mono text-emerald-950" id="attTotalAttended">0 Hours</span>
                  </div>
                  <div class="flex items-center justify-between p-3 rounded-xl bg-rose-50/60 border border-rose-200/60">
                    <span class="text-xs text-rose-800 font-medium">Absent Sessions</span>
                    <span class="text-sm font-bold font-mono text-rose-950" id="attTotalAbsent">0 Hours</span>
                  </div>
                </div>

                <div class="text-[11px] text-slate-400">Calculated across standard periods 1 to 6.</div>
              </div>

              <!-- Class & Faculty Tutor Card -->
              <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between space-y-4">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 pb-2">Institutional Advisor</h3>
                
                <div class="space-y-3">
                  <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                    <p class="text-xs font-medium text-slate-500">Classroom Identifier</p>
                    <p class="text-sm font-bold text-slate-900 mt-0.5" id="attClassroomId">-</p>
                  </div>
                  <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                    <p class="text-xs font-medium text-slate-500">Class Tutor</p>
                    <p class="text-sm font-bold text-slate-900 mt-0.5" id="attTutorName">-</p>
                    <p class="text-[11px] text-slate-500 font-mono mt-0.5" id="attTutorContact"></p>
                  </div>
                </div>

                <div class="text-[11px] text-slate-400">Regular contact tutor for condonation and leaves.</div>
              </div>

            </div>

            <!-- Today's Hour-Wise Attendance Grid -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
                <div>
                  <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 text-blue-600"></i>
                    <span>Today's Period Attendance Timeline</span>
                  </h2>
                  <p class="text-xs text-slate-500 mt-0.5" id="attTodayDateLabel">Today • Hourly live attendance logs</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">
                  <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                  Live Log Active
                </span>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3 pt-2" id="attHourlyGrid"></div>
            </div>

            <!-- Subject-Wise Attendance Breakdown Table -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
              <div class="border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                  <i data-lucide="book-open" class="w-4 h-4 text-blue-600"></i>
                  <span>Subject-Wise Attendance Distribution</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Continuous attendance records and minimum 75% examination eligibility thresholds.</p>
              </div>

              <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-xs border-collapse">
                  <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                      <th class="py-3 px-4">Subject Code & Name</th>
                      <th class="py-3 px-4 text-center">Conducted</th>
                      <th class="py-3 px-4 text-center">Attended</th>
                      <th class="py-3 px-4 text-center">Percentage</th>
                      <th class="py-3 px-4 text-right">Eligibility Status</th>
                    </tr>
                  </thead>
                  <tbody id="attSubjectStatsList" class="divide-y divide-slate-100 text-slate-800">
                    <tr><td colspan="5" class="p-6 text-center text-slate-400">Loading subject statistics...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Leave & Absence Records Table -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
              <div class="border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                  <i data-lucide="file-text" class="w-4 h-4 text-blue-600"></i>
                  <span>Student Leave & Absence Log</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Registered medical leaves, duty leaves, and tutor condonation records.</p>
              </div>

              <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-xs border-collapse">
                  <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                      <th class="py-3 px-4">Leave Date</th>
                      <th class="py-3 px-4">Type / Reason</th>
                      <th class="py-3 px-4">Period Range</th>
                      <th class="py-3 px-4 text-right">Verification Status</th>
                    </tr>
                  </thead>
                  <tbody id="attLeaveRecordsList" class="divide-y divide-slate-100 text-slate-800">
                    <tr><td colspan="4" class="p-6 text-center text-slate-400">No formal leave requests recorded.</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
