<div id="panelLeave_ledger" class="{{ $initialPanel === 'leave_ledger' ? '' : 'hidden' }} space-y-6">
        
        <!-- Header & Filters Card -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 shrink-0">
              <i data-lucide="calendar-range" class="w-6 h-6 text-rose-600"></i>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                  <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                  {{ $activeBranch }} Department · Leave Ledger
                </span>
              </div>
              <h3 class="text-base font-bold text-slate-900 mt-1">Staff Leave Master Ledger &amp; Report Center</h3>
              <p class="text-xs text-slate-500 mt-0.5">Multi-stage approval audit trail, departmental leave balances, and official leave orders.</p>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-2.5">
            <select id="leaveLedgerYear" onchange="loadLeaveLedger()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
              @foreach([date('Y'), date('Y')-1, date('Y')-2] as $yr)
                <option value="{{ $yr }}">{{ $yr }} - {{ $yr+1 }}</option>
              @endforeach
            </select>

            <select id="leaveLedgerDept" onchange="loadLeaveLedger()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
              <option value="{{ $activeBranch }}">{{ $activeBranch }} Department</option>
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

            <select id="leaveLedgerStatus" onchange="loadLeaveLedger()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
              <option value="">All Statuses</option>
              <option value="Pending_HOD">Pending HOD Recommendation</option>
              <option value="Pending_Coordinator">Pending Coordinator</option>
              <option value="Pending_Principal">Pending Principal Approval</option>
              <option value="Approved">Final Approved</option>
              <option value="Rejected">Rejected</option>
            </select>

            <button type="button" onclick="loadLeaveLedger()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition cursor-pointer border border-slate-200 flex items-center gap-2 text-sm font-semibold" title="Refresh Leave Ledger">
              <i data-lucide="refresh-cw" class="w-4 h-4 text-slate-600"></i>
              <span>Refresh</span>
            </button>
          </div>
        </div>

        <!-- 6 KPI Metric Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
          <div class="bg-white border border-slate-200/80 p-4 rounded-2xl text-center shadow-xs">
            <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block">Total Days</span>
            <span id="leaveKpiTotal" class="text-xl font-bold text-slate-900 block mt-1">0.0</span>
            <span class="text-[11px] text-slate-400 font-medium">Days in Selection</span>
          </div>
          <div class="bg-white border border-slate-200/80 p-4 rounded-2xl text-center shadow-xs">
            <span class="text-xs text-blue-600 font-bold uppercase tracking-wider block">Casual (CL)</span>
            <span id="leaveKpiCL" class="text-xl font-bold text-blue-600 block mt-1">0.0</span>
            <span class="text-[11px] text-slate-400 font-medium">Casual Leave</span>
          </div>
          <div class="bg-white border border-slate-200/80 p-4 rounded-2xl text-center shadow-xs">
            <span class="text-xs text-amber-600 font-bold uppercase tracking-wider block">Comp (CCL)</span>
            <span id="leaveKpiCCL" class="text-xl font-bold text-amber-600 block mt-1">0.0</span>
            <span class="text-[11px] text-slate-400 font-medium">Compensatory</span>
          </div>
          <div class="bg-white border border-slate-200/80 p-4 rounded-2xl text-center shadow-xs">
            <span class="text-xs text-indigo-600 font-bold uppercase tracking-wider block">Duty (DL)</span>
            <span id="leaveKpiDL" class="text-xl font-bold text-indigo-600 block mt-1">0.0</span>
            <span class="text-[11px] text-slate-400 font-medium">Duty Leave</span>
          </div>
          <div class="bg-white border border-slate-200/80 p-4 rounded-2xl text-center shadow-xs">
            <span class="text-xs text-emerald-600 font-bold uppercase tracking-wider block">Medical (ML)</span>
            <span id="leaveKpiML" class="text-xl font-bold text-emerald-600 block mt-1">0.0</span>
            <span class="text-[11px] text-slate-400 font-medium">Medical Leave</span>
          </div>
          <div class="bg-white border border-slate-200/80 p-4 rounded-2xl text-center shadow-xs">
            <span class="text-xs text-rose-600 font-bold uppercase tracking-wider block">Loss of Pay</span>
            <span id="leaveKpiLOP" class="text-xl font-bold text-rose-600 block mt-1">0.0</span>
            <span class="text-[11px] text-slate-400 font-medium">LOP Leave</span>
          </div>
        </div>

        <!-- Master Leave Table Container -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
          <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse text-sm">
              <thead>
                <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider text-xs">
                  <th class="py-3.5 px-4">Application &amp; Staff</th>
                  <th class="py-3.5 px-4">Leave Type</th>
                  <th class="py-3.5 px-4">Duration &amp; Dates</th>
                  <th class="py-3.5 px-4">Reason &amp; Duty Arrangement</th>
                  <th class="py-3.5 px-4 text-center">Multi-Stage Status</th>
                  <th class="py-3.5 px-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="leaveLedgerTableBody" class="divide-y divide-slate-100 text-slate-800">
                <tr><td colspan="6" class="p-12 text-center text-slate-500 font-medium text-sm">Loading leave ledger applications...</td></tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
