        <!-- PANEL: LEAVE APPROVAL & REPORTS -->
        <div id="panelLeaveApproval" class="{{ $activeTab === 'leaveApproval' ? '' : 'hidden' }} space-y-6">
          <!-- Header -->
          <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs flex flex-wrap items-center justify-between gap-4">
            <div>
              <h3 class="font-bold text-slate-900 text-base sm:text-lg">Leave Approval &amp; Student Reports</h3>
              <p class="text-xs text-slate-500 mt-0.5">Review leave applications from students and view classroom reports.</p>
            </div>
            <div class="flex items-center gap-2.5">
              <select id="leaveClassroomSelect" onchange="loadClassroomLeaves()" class="bg-white border border-slate-200 rounded-xl px-4 py-2 text-slate-900 text-sm font-semibold focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all cursor-pointer">
                <option value="">Loading classrooms...</option>
              </select>
              <button type="button" onclick="loadClassroomLeaves()" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold text-sm transition-all cursor-pointer flex items-center gap-2 shadow-2xs">
                <x-ui.icon name="sync" class="w-4 h-4 text-slate-500" />
                <span>Refresh</span>
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Leave Table (col-span-2) -->
            <div class="xl:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
              <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <x-ui.icon name="approval" class="w-4 h-4 text-blue-600" />
                <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">Pending &amp; Recent Leaves</h4>
              </div>
              <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs border-collapse whitespace-nowrap">
                  <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                      <th class="p-3 pl-4">Student</th>
                      <th class="p-3">Semester</th>
                      <th class="p-3">Date</th>
                      <th class="p-3">Days</th>
                      <th class="p-3">Reason</th>
                      <th class="p-3">Status</th>
                      <th class="p-3 pr-4 text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="classroomLeavesTableBody" class="divide-y divide-slate-100 text-slate-700">
                    <tr><td colspan="7" class="p-6 text-center text-slate-500 font-medium">Select a classroom to load leaves.</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
