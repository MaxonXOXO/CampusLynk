<div id="panelAudit" class="hidden space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-[10px] text-sm">Cross-Branch Workshop Audit Trail</h3>
            <p class="text-[10px] text-slate-400 mt-1 text-[10px] text-xs">All lifecycle events, status changes, and password resets across the system.</p>
          </div>
          <button onclick="loadAuditTrail()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-bold transition-premium cursor-pointer flex items-center gap-2">
            <span class="material-symbols-rounded text-[10px] text-sm">sync</span> Refresh Log
          </button>
        </div>

        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="overflow-x-auto scrollbar-hidden">
            <table class="w-full text-left text-[10px] border-collapse text-[10px] text-xs">
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
                <!-- Audit logs render dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
