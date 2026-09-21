        <!-- PANEL 1: OVERVIEW & PENDING LEAVE APPROVALS -->
        <div id="panelDashboard" class="space-y-6">
          
          <!-- Metrics Row -->
          <div class="grid grid-cols-4 gap-5">
            <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
              <div class="bg-amber-500/10 text-amber-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">approval</span></div>
              <div>
                <span class="text-xs text-slate-400 uppercase font-bold tracking-wider block">Pending SF Approvals</span>
                <span id="statPendingLeave" class="text-xl font-black text-white mt-0.5">0</span>
              </div>
            </div>

            <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
              <div class="bg-indigo-500/10 text-indigo-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">account_tree</span></div>
              <div>
                <span class="text-xs text-slate-400 uppercase font-bold tracking-wider block">Supervised Stream</span>
                <span class="text-sm font-black text-indigo-300 mt-0.5 block">EL • AU • CT • GEN SF</span>
              </div>
            </div>

            <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
              <div class="bg-emerald-500/10 text-emerald-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">event_note</span></div>
              <div>
                <span class="text-xs text-slate-400 uppercase font-bold tracking-wider block">Master Ledger</span>
                <button onclick="switchPanel('reports')" class="text-xs font-bold text-emerald-400 hover:underline flex items-center gap-1 mt-0.5 bg-transparent border-0 p-0 cursor-pointer">
                  View Ledger & Reports <span class="material-symbols-rounded text-xs">arrow_forward</span>
                </button>
              </div>
            </div>

            <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
              <div class="bg-purple-500/10 text-purple-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">smartphone</span></div>
              <div>
                <span class="text-xs text-slate-400 uppercase font-bold tracking-wider block">Mobile Portal</span>
                <a href="/staff/mobile?mode=mobile" class="text-xs font-bold text-purple-400 hover:underline flex items-center gap-1 mt-0.5">
                  My Leave Log & Portal <span class="material-symbols-rounded text-xs">arrow_forward</span>
                </a>
              </div>
            </div>
          </div>

          <!-- Pending Leave Applications Section -->
          <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl p-6 space-y-4">
            <div class="flex justify-between items-center border-b border-slate-800/60 pb-3">
              <div>
                <h3 class="font-black text-slate-100 text-base flex items-center gap-2">
                  <span class="material-symbols-rounded text-amber-400 text-lg">pending_actions</span>
                  Staff Leave Applications Pending Academic Coordinator Approval
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Stage 2 of 3-tier hierarchy (HOD Approved → <strong>Academic Coordinator</strong> → Principal) for Self-Financing departments (EL, AU, CT, GEN SF).</p>
              </div>
              <button onclick="loadPendingApprovals()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5">
                <span class="material-symbols-rounded text-sm">sync</span> Refresh Queue
              </button>
            </div>

            <div class="overflow-x-auto scrollbar-hidden">
              <table class="w-full text-left text-xs border-collapse whitespace-nowrap">
                <thead>
                  <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <th class="p-3">Staff Member</th>
                    <th class="p-3">Dept</th>
                    <th class="p-3">Leave Category</th>
                    <th class="p-3">Date(s) Needed</th>
                    <th class="p-3">Session</th>
                    <th class="p-3">Reason & Work Arrangement</th>
                    <th class="p-3">HOD Stage</th>
                    <th class="p-3 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody id="pendingLeaveTableBody" class="divide-y divide-slate-800/40 text-slate-300">
                  <tr><td colspan="8" class="p-6 text-center text-slate-500 font-bold">Loading pending leave applications...</td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Supervised Department Quick Info -->
          <div class="grid grid-cols-3 gap-6">
            <div class="bg-slate-950/30 border border-slate-800/40 p-5 rounded-2xl space-y-2">
              <div class="flex justify-between items-center">
                <span class="font-bold text-slate-200 text-sm">Electronics (EL)</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">Self-Financing</span>
              </div>
              <p class="text-xs text-slate-400">3-Tier Approval Path Active (HOD → Coordinator → Principal)</p>
            </div>

            <div class="bg-slate-950/30 border border-slate-800/40 p-5 rounded-2xl space-y-2">
              <div class="flex justify-between items-center">
                <span class="font-bold text-slate-200 text-sm">Automobile (AU)</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">Self-Financing</span>
              </div>
              <p class="text-xs text-slate-400">3-Tier Approval Path Active (HOD → Coordinator → Principal)</p>
            </div>

            <div class="bg-slate-950/30 border border-slate-800/40 p-5 rounded-2xl space-y-2">
              <div class="flex justify-between items-center">
                <span class="font-bold text-slate-200 text-sm">Computer (CT) & GEN SF</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">Self-Financing</span>
              </div>
              <p class="text-xs text-slate-400">3-Tier Approval Path Active (HOD → Coordinator → Principal)</p>
            </div>
          </div>

        </div>

