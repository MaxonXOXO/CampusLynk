
            <!-- Mentorship Reports Card -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5 flex flex-col justify-between">
              <div>
                <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                  <x-ui.icon name="summarize" class="w-4 h-4 text-emerald-600" />
                  <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">Classroom Reports</h4>
                </div>
                <p class="text-slate-500 text-xs mt-2">Generate summary reports of student records for parents or administration.</p>
                <div class="space-y-4 mt-5">
                  <div>
                    <label class="block text-slate-600 font-semibold mb-1.5 text-xs">Select Student</label>
                    <select id="reportStudentSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 text-sm outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 cursor-pointer">
                      <option value="">Select student...</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="space-y-2.5 pt-2">
                <button type="button" onclick="printStudentFullDiary()" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all cursor-pointer text-xs flex items-center justify-center gap-2 shadow-xs">
                  <x-ui.icon name="print" class="w-4 h-4" />
                  <span>Print Student Diary Report</span>
                </button>
                <button type="button" onclick="printStudentLeaveReport()" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold transition-all cursor-pointer text-xs flex items-center justify-center gap-2 shadow-xs">
                  <x-ui.icon name="summarize" class="w-4 h-4" />
                  <span>Print Student Leave Report</span>
                </button>
                <button type="button" onclick="printCondonationReport()" class="w-full py-2.5 bg-white hover:bg-rose-50 text-rose-700 border border-rose-200 rounded-xl font-semibold transition-all cursor-pointer text-xs flex items-center justify-center gap-2 shadow-2xs">
                  <x-ui.icon name="gavel" class="w-4 h-4 text-rose-600" />
                  <span>Print Condonation &amp; Shortage Report</span>
                </button>
              </div>
            </div>
          </div>
        </div>

      </main>
    </div>
  </div>

  @include('mentoring_diary_modal')

  <!-- BACKLOG REPORT MODAL -->
  <div id="backlogReportModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 hidden items-center justify-center p-4 transition-all overflow-y-auto">
    <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-4xl p-6 shadow-2xl space-y-5 my-8 relative">
      <div class="flex justify-between items-center border-b border-slate-100 pb-4 sticky top-0 bg-white z-10">
        <div>
          <h2 class="text-lg font-bold text-slate-900">Backlog Report</h2>
          <p class="text-xs text-slate-500 mt-0.5">Students with and without backlogs over the 3-year diploma.</p>
        </div>
        <button type="button" onclick="document.getElementById('backlogReportModal').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer rounded-lg hover:bg-slate-100">
          <x-ui.icon name="close" class="w-5 h-5" />
        </button>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Without Backlog -->
        <div class="bg-white rounded-xl border border-emerald-200 overflow-hidden flex flex-col h-[480px]">
          <div class="bg-emerald-50 p-3.5 border-b border-emerald-100 flex justify-between items-center sticky top-0">
            <h3 class="text-xs font-bold text-emerald-800 flex items-center gap-2">
              <x-ui.icon name="check_circle" class="w-4 h-4 text-emerald-600" />
              <span>Completed Without Backlog</span>
            </h3>
            <span id="noBacklogCount" class="bg-emerald-600 text-white px-2 py-0.5 rounded-full text-xs font-bold">0</span>
          </div>
          <div class="overflow-y-auto flex-grow p-2 custom-scrollbar">
            <table class="w-full text-left text-xs">
              <tbody id="noBacklogList" class="divide-y divide-slate-100">
                <tr><td class="p-4 text-center text-slate-500 font-medium">Generating report...</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- With Backlog -->
        <div class="bg-white rounded-xl border border-rose-200 overflow-hidden flex flex-col h-[480px]">
          <div class="bg-rose-50 p-3.5 border-b border-rose-100 flex justify-between items-center sticky top-0">
            <h3 class="text-xs font-bold text-rose-800 flex items-center gap-2">
              <x-ui.icon name="warning" class="w-4 h-4 text-rose-600" />
              <span>With Backlog</span>
            </h3>
            <span id="withBacklogCount" class="bg-rose-600 text-white px-2 py-0.5 rounded-full text-xs font-bold">0</span>
          </div>
          <div class="overflow-y-auto flex-grow p-2 custom-scrollbar">
            <table class="w-full text-left text-xs">
              <tbody id="withBacklogList" class="divide-y divide-slate-100">
                <tr><td class="p-4 text-center text-slate-500 font-medium">Generating report...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
      <div class="flex justify-end pt-3 border-t border-slate-100">
        <button type="button" onclick="document.getElementById('backlogReportModal').classList.add('hidden')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-all cursor-pointer">
          Close Report
        </button>
      </div>
    </div>
  </div>

  <!-- PASSWORD RESET MODAL -->
  <div id="passwordModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
          <x-ui.icon name="lock_reset" class="w-4 h-4 text-blue-600" />
          <span>Password Reset</span>
        </h3>
        <button type="button" onclick="closePasswordModal()" class="p-1 text-slate-400 hover:text-slate-600 cursor-pointer rounded-lg hover:bg-slate-100">
          <x-ui.icon name="close" class="w-4 h-4" />
        </button>
      </div>

      <div class="space-y-3">
        <p class="text-xs text-slate-500">
          Set a new password for <span id="pwdResetName" class="font-bold text-slate-800"></span> (<span id="pwdResetId" class="text-blue-600 font-mono font-semibold"></span>).
        </p>
        <div>
          <label class="block text-xs text-slate-600 font-semibold mb-1.5 uppercase tracking-wider">New Password</label>
          <input type="text" id="newPasswordInput" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all placeholder:text-slate-400" placeholder="Minimum 4 characters">
        </div>
      </div>

      <div id="pwdAlert" class="hidden p-3 rounded-xl text-xs font-semibold border"></div>

      <div class="flex gap-3 pt-2">
        <button type="button" onclick="closePasswordModal()" class="flex-1 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold text-xs transition-all cursor-pointer">Cancel</button>
        <button type="button" onclick="submitPasswordReset()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-xs transition-all cursor-pointer shadow-xs">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- AUDIT LOG MODAL FOR SINGLE STUDENT -->
  <div id="auditModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-2xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
          <x-ui.icon name="receipt_long" class="w-4 h-4 text-blue-600" />
          <span>Profile Audit Trail</span>
        </h3>
        <button type="button" onclick="closeAuditModal()" class="p-1 text-slate-400 hover:text-slate-600 cursor-pointer rounded-lg hover:bg-slate-100">
          <x-ui.icon name="close" class="w-4 h-4" />
        </button>
      </div>

      <div class="space-y-3">
        <p class="text-xs text-slate-500">
          History log for <span id="auditProfileName" class="font-bold text-slate-800"></span> (<span id="auditProfileId" class="text-blue-600 font-mono font-semibold"></span>).
        </p>

        <div class="max-h-[300px] overflow-y-auto custom-scrollbar border border-slate-200 rounded-xl">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                <th class="p-3">Time</th>
                <th class="p-3">Actor</th>
                <th class="p-3">Action</th>
                <th class="p-3">Details</th>
              </tr>
            </thead>
            <tbody id="modalAuditTableBody" class="divide-y divide-slate-100">
              <tr><td colspan="4" class="p-4 text-center text-slate-500">Loading audit history...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="flex justify-end pt-2 border-t border-slate-100">
        <button type="button" onclick="closeAuditModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all cursor-pointer">
          Close
        </button>
      </div>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
