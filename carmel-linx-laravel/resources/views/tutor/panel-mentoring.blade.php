<div id="panelMentoring" class="{{ $activeTab === 'mentoring' ? '' : 'hidden' }} space-y-6">
          <!-- Header Controls -->
          <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs flex flex-wrap items-center justify-between gap-4">
            <div>
              <h3 class="font-bold text-slate-900 text-base sm:text-lg">Mentoring Batches &amp; Splitter</h3>
              <p class="text-xs text-slate-500 mt-0.5">Split students between yourself and the second mentor.</p>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
              <select id="mentorClassroomSelect" onchange="loadMentoringData()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-slate-900 text-sm font-medium focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all cursor-pointer">
                <option value="">Loading classrooms...</option>
              </select>
              <button type="button" onclick="loadMentoringData()" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold text-sm transition-all cursor-pointer flex items-center gap-1.5 shadow-2xs">
                <x-ui.icon name="sync" class="w-4 h-4 text-slate-500" />
                <span>Refresh</span>
              </button>
              <button type="button" onclick="generateBacklogReport()" class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200/80 rounded-xl text-sm font-semibold transition-all cursor-pointer flex items-center gap-2 shadow-2xs">
                <x-ui.icon name="summarize" class="w-4 h-4 text-blue-600" />
                <span>Backlog Report</span>
              </button>
            </div>
          </div>

          <!-- Collapsible Batch Assignment Panel -->
          <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
            <div onclick="toggleBatchAssignment()" class="p-4 bg-slate-50/60 border-b border-slate-200/80 flex justify-between items-center cursor-pointer hover:bg-slate-100/70 transition-colors">
              <div class="flex items-center gap-2.5">
                <span id="batchAssignIcon" class="transition-transform duration-300">
                  <x-ui.icon name="chevron-down" class="w-4 h-4 text-blue-600" />
                </span>
                <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">Batch Assignment &amp; Mentorship Splitter Settings</h4>
              </div>
              <span class="text-xs text-slate-500 font-medium hidden sm:inline">Click to configure Batch A &amp; B assignments / unassigned students</span>
            </div>
            
            <div id="batchAssignmentContent" class="hidden p-6 border-t border-slate-100">
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Unassigned Students -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-2xs flex flex-col overflow-hidden">
                  <div class="p-4 border-b border-slate-100 bg-amber-50/40 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                      <x-ui.icon name="person_off" class="w-4 h-4 text-amber-600" />
                      <div>
                        <h4 class="font-bold text-xs text-slate-900 uppercase">Unassigned Students</h4>
                        <p class="text-xs text-slate-500">Students without a mentor.</p>
                      </div>
                    </div>
                    <span id="unassignedCountBadge" class="bg-amber-100 text-amber-800 border border-amber-200 px-2 py-0.5 rounded-full font-bold text-xs">0</span>
                  </div>
                  <div class="flex-grow max-h-[300px] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-left text-xs">
                      <tbody id="unassignedList" class="divide-y divide-slate-100">
                        <tr><td class="p-4 text-center text-slate-500">Select a classroom to view.</td></tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- Mentors Split View -->
                <div class="space-y-6">
                  <!-- Mentor A (Tutor) -->
                  <div class="bg-white border border-slate-200/80 rounded-2xl shadow-2xs flex flex-col overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-sky-50/40 flex justify-between items-center">
                      <div class="flex items-center gap-2">
                        <x-ui.icon name="person_pin" class="w-4 h-4 text-sky-600" />
                        <div>
                          <h4 class="font-bold text-xs text-sky-900 uppercase">Batch A (Tutor)</h4>
                          <p id="mentorAInfo" class="text-xs text-slate-500">Loading...</p>
                        </div>
                      </div>
                      <span id="batchACountBadge" class="bg-sky-100 text-sky-800 border border-sky-200 px-2 py-0.5 rounded-full font-bold text-xs">0</span>
                    </div>
                    <div class="flex-grow max-h-[180px] overflow-y-auto custom-scrollbar">
                      <table class="w-full text-left text-xs">
                        <tbody id="batchAList" class="divide-y divide-slate-100"></tbody>
                      </table>
                    </div>
                  </div>

                  <!-- Mentor B -->
                  <div class="bg-white border border-slate-200/80 rounded-2xl shadow-2xs flex flex-col overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-emerald-50/40 flex justify-between items-center">
                      <div class="flex items-center gap-2">
                        <x-ui.icon name="supervisor_account" class="w-4 h-4 text-emerald-600" />
                        <div>
                          <h4 class="font-bold text-xs text-emerald-900 uppercase">Batch B (Mentor)</h4>
                          <p id="mentorBInfo" class="text-xs text-slate-500">Loading...</p>
                        </div>
                      </div>
                      <span id="batchBCountBadge" class="bg-emerald-100 text-emerald-800 border border-emerald-200 px-2 py-0.5 rounded-full font-bold text-xs">0</span>
                    </div>
                    <div class="flex-grow max-h-[180px] overflow-y-auto custom-scrollbar">
                      <table class="w-full text-left text-xs">
                        <tbody id="batchBList" class="divide-y divide-slate-100"></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Mentoring Caseload Data View -->
          <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                  <x-ui.icon name="school" class="w-5 h-5 text-blue-600" />
                </div>
                <div>
                  <h3 class="text-base font-bold text-slate-900">Mentoring Caseload (Data View)</h3>
                  <p class="text-xs text-slate-500">Tutors see the full class; Mentors see only their assigned batch.</p>
                </div>
              </div>
              <div>
                <span class="bg-blue-50 text-blue-700 text-xs px-3 py-1 rounded-full font-semibold border border-blue-200">📱 Mobile Parent Portal SMS Enabled</span>
              </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
              <table class="w-full text-left text-sm border-collapse">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                    <th class="p-3.5 pl-4">Student</th>
                    <th class="p-3.5">Reg No</th>
                    <th class="p-3.5">Batch Assigned</th>
                    <th class="p-3.5">Diary Logs</th>
                    <th class="p-3.5 pr-4 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody id="myMentoringStudentsList" class="divide-y divide-slate-100">
                  <tr><td colspan="5" class="p-6 text-center text-slate-500 font-medium">Select a classroom to view caseload.</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
