        <!-- PANEL 3: STAFF LEAVE MASTER LEDGER & REPORTS -->
        <div id="panelReports" class="hidden space-y-6">
          <div class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-800/60 pb-3">
              <div>
                <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2">
                  <span class="material-symbols-rounded text-emerald-400 text-base">event_note</span>
                  Staff Leave Master Ledger & Report Center
                </h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Filter leave applications, audit approvals, and generate printable reports.</p>
              </div>
              <a href="/staff/leave/reports" target="_blank" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-premium no-underline flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-rounded text-sm">print</span> Print Official Ledger PDF
              </a>
            </div>

            <!-- Filters -->
            <div class="grid grid-cols-3 gap-3">
              <div>
                <label class="block text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1">Department Filter</label>
                <select id="desktopReportBranch" onchange="loadLeaveReports()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-2.5 py-1.5 text-white focus:border-blue-500 outline-none text-xs">
                  <option value="">All SF Departments (EL, AU, CT, GEN SF)</option>
                  <option value="EL">Electronics (EL)</option>
                  <option value="AU">Automobile (AU)</option>
                  <option value="CT">Computer Engineering (CT)</option>
                  <option value="GEN_SF">General SF (GEN_SF)</option>
                </select>
              </div>

              <div>
                <label class="block text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1">Leave Category</label>
                <select id="desktopReportCategory" onchange="loadLeaveReports()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-2.5 py-1.5 text-white focus:border-blue-500 outline-none text-xs">
                  <option value="">All Leave Categories</option>
                  <option value="Casual Leave">Casual Leave (CL)</option>
                  <option value="Compensatory Casual Leave">Compensatory (CCL)</option>
                  <option value="Duty Leave">Duty Leave (DL)</option>
                  <option value="Medical Leave">Medical Leave (ML)</option>
                  <option value="Loss of Pay">Loss of Pay (LOP)</option>
                  <option value="Special Leave">Special Leave (SL)</option>
                </select>
              </div>

              <div>
                <label class="block text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1">Academic Year</label>
                <select id="desktopReportYear" onchange="loadLeaveReports()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-2.5 py-1.5 text-white focus:border-blue-500 outline-none text-xs">
                  <option value="2026">Academic Year 2026</option>
                  <option value="2025">Academic Year 2025</option>
                  <option value="2024">Academic Year 2024</option>
                </select>
              </div>
            </div>

            <!-- Category Summary Cards -->
            <div class="grid grid-cols-7 gap-2.5 pt-1">
              <div class="bg-slate-900 border border-slate-800 p-2 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">CL</span>
                <span id="summaryCL" class="text-xs font-bold text-blue-400">0d</span>
              </div>
              <div class="bg-slate-900 border border-slate-800 p-2 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">CCL</span>
                <span id="summaryCCL" class="text-xs font-bold text-amber-400">0d</span>
              </div>
              <div class="bg-slate-900 border border-slate-800 p-2 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">DL</span>
                <span id="summaryDL" class="text-xs font-bold text-sky-400">0d</span>
              </div>
              <div class="bg-slate-900 border border-slate-800 p-2 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">ML</span>
                <span id="summaryML" class="text-xs font-bold text-purple-400">0d</span>
              </div>
              <div class="bg-slate-900 border border-slate-800 p-2 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">LOP</span>
                <span id="summaryLOP" class="text-xs font-bold text-rose-400">0d</span>
              </div>
              <div class="bg-slate-900 border border-slate-800 p-2 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">SL</span>
                <span id="summarySL" class="text-xs font-bold text-teal-400">0d</span>
              </div>
              <div class="bg-slate-900 border border-slate-800 p-2 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">Total</span>
                <span id="summaryTOTAL" class="text-xs font-bold text-emerald-400">0d</span>
              </div>
            </div>

            <!-- Ledger Table -->
            <div class="overflow-x-auto scrollbar-hidden border border-slate-800 rounded-xl">
              <table class="w-full text-left border-collapse text-xs whitespace-nowrap">
                <thead>
                  <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <th class="p-3">Staff Name</th>
                    <th class="p-3">Dept</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Dates</th>
                    <th class="p-3">Days</th>
                    <th class="p-3">HOD Stage</th>
                    <th class="p-3">Coordinator Stage</th>
                    <th class="p-3">Principal Stage</th>
                    <th class="p-3 text-right">Status & PDF</th>
                  </tr>
                </thead>
                <tbody id="reportsTableBody" class="divide-y divide-slate-800/40 text-slate-300">
                  <tr><td colspan="9" class="p-6 text-center text-slate-500 font-bold">Loading leave ledger records...</td></tr>
                </tbody>
              </table>
            </div>

          </div>
        </div>

