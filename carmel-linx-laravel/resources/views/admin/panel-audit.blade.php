<div id="panelAudit" class="hidden space-y-6">
          <div class="bg-white border border-slate-200 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4 shadow-sm">
            <div>
              <h3 class="font-bold text-slate-900 text-lg">System Audit Trail</h3>
              <p class="text-xs text-slate-500 mt-0.5">Lifecycle events, password resets, status changes, and registration logs recorded across the platform.</p>
            </div>
            <button onclick="loadAuditTrail()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-xs transition-all flex items-center gap-2 cursor-pointer border border-slate-200">
              <span class="material-symbols-rounded text-sm">sync</span>
              <span>Refresh Audit Log</span>
            </button>
          </div>

          <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider text-xs">
                    <th class="py-3 px-4">Timestamp</th>
                    <th class="py-3 px-4">Actor</th>
                    <th class="py-3 px-4">Target User (ID)</th>
                    <th class="py-3 px-4">Action</th>
                    <th class="py-3 px-4">IP Address</th>
                    <th class="py-3 px-4">Details</th>
                  </tr>
                </thead>
                <tbody id="auditTableBody" class="divide-y divide-slate-100 text-slate-800">
                  <tr><td colspan="6" class="p-8 text-center text-slate-400">Loading audit trail...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
