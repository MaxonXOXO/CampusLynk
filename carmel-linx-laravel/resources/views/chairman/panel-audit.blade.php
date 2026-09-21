<div id="panelAudit" class="hidden space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-sm">System Audit Trail Log</h3>
            <p class="text-xs text-slate-400 mt-1">Lifecycle events, password resets, status changes, and registration records.</p>
          </div>
          <button onclick="loadAuditTrail()" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-slate-950 rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-2">
            <span class="material-symbols-rounded text-sm">sync</span> Refresh Log
          </button>
        </div>

        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="overflow-x-auto scrollbar-hidden">
            <table class="w-full text-left text-xs border-collapse">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                  <th class="p-4">Timestamp</th>
                  <th class="p-4">Actor</th>
                  <th class="p-4">Target User (ID)</th>
                  <th class="p-4">Action</th>
                  <th class="p-4">IP Address</th>
                  <th class="p-4">Details</th>
                </tr>
              </thead>
              <tbody id="auditTableBody">
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
