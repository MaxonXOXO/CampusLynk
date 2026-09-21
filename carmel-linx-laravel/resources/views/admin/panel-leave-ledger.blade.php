<div id="panelLeave_ledger" class="hidden space-y-6">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-slate-200 p-5 rounded-2xl gap-3 shadow-sm">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-rounded text-2xl">event_note</span>
              </div>
              <div>
                <h3 class="text-lg font-bold text-slate-900">All-Department Staff Leave Master Ledger</h3>
                <p class="text-xs text-slate-500 mt-0.5">Multi-stage leave approval trail, departmental balances, and official leave orders.</p>
              </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
              <select id="leaveLedgerDept" onchange="loadLeaveLedger()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
                <option value="">All Departments</option>
                <option value="EL">Electronics (EL)</option>
                <option value="ME">Mechanical (ME)</option>
                <option value="CE">Civil (CE)</option>
                <option value="EEE">Electrical (EEE)</option>
                <option value="CT">Computer (CT)</option>
                <option value="AU">Automobile (AU)</option>
                <option value="GEN_AIDED">Gen Aided</option>
                <option value="GEN_SF">Gen SF</option>
              </select>

              <select id="leaveLedgerStatus" onchange="loadLeaveLedger()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
                <option value="">All Statuses</option>
                <option value="Pending_Principal">Pending Principal Approval</option>
                <option value="Approved">Approved</option>
                <option value="Pending_HOD">Pending HOD</option>
                <option value="Pending_Coordinator">Pending Coordinator</option>
                <option value="Rejected">Rejected</option>
              </select>

              <button onclick="loadLeaveLedger()" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition cursor-pointer border border-slate-200" title="Refresh">
                <span class="material-symbols-rounded text-sm">sync</span>
              </button>
            </div>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl text-center shadow-sm">
              <span class="text-xs text-slate-500 font-semibold uppercase block">Total Days</span>
              <span id="leaveKpiTotal" class="text-lg font-bold text-slate-900 block mt-0.5">0.0</span>
            </div>
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl text-center shadow-sm">
              <span class="text-xs text-slate-500 font-semibold uppercase block">Casual (CL)</span>
              <span id="leaveKpiCL" class="text-lg font-bold text-blue-600 block mt-0.5">0.0</span>
            </div>
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl text-center shadow-sm">
              <span class="text-xs text-slate-500 font-semibold uppercase block">Comp (CCL)</span>
              <span id="leaveKpiCCL" class="text-lg font-bold text-amber-600 block mt-0.5">0.0</span>
            </div>
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl text-center shadow-sm">
              <span class="text-xs text-slate-500 font-semibold uppercase block">Duty (DL)</span>
              <span id="leaveKpiDL" class="text-lg font-bold text-indigo-600 block mt-0.5">0.0</span>
            </div>
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl text-center shadow-sm">
              <span class="text-xs text-slate-500 font-semibold uppercase block">Medical (ML)</span>
              <span id="leaveKpiML" class="text-lg font-bold text-emerald-600 block mt-0.5">0.0</span>
            </div>
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl text-center shadow-sm">
              <span class="text-xs text-slate-500 font-semibold uppercase block">Loss of Pay</span>
              <span id="leaveKpiLOP" class="text-lg font-bold text-rose-600 block mt-0.5">0.0</span>
            </div>
          </div>

          <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider text-xs">
                    <th class="py-3.5 px-4">Application &amp; Staff</th>
                    <th class="py-3.5 px-4">Leave Type</th>
                    <th class="py-3.5 px-4">Duration &amp; Dates</th>
                    <th class="py-3.5 px-4">Reason &amp; Duty Arrangement</th>
                    <th class="py-3.5 px-4 text-center">Multi-Stage Status</th>
                    <th class="py-3.5 px-4 text-right">Executive Actions</th>
                  </tr>
                </thead>
                <tbody id="leaveLedgerTableBody" class="divide-y divide-slate-100 text-slate-800">
                  <tr><td colspan="6" class="p-8 text-center text-slate-400">Loading leave ledger applications...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
