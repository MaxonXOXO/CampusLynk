<div id="panelAudit" class="{{ $initialPanel === 'audit' ? '' : 'hidden' }} space-y-6">
        <!-- Audit Logs Controls -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs border border-blue-100 uppercase tracking-wider">{{ session('userBranch') }} Branch</span>
              <span class="text-xs text-slate-400">·</span>
              <span class="text-xs text-slate-500 font-medium">Security & Integrity</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Department Audit Trail</h2>
            <p class="text-sm text-slate-500 mt-0.5">Lifecycle events, status updates, registrations, and actions performed within the {{ session('userBranch') }} branch.</p>
          </div>
          <button 
            type="button" 
            onclick="loadAuditTrail()" 
            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-semibold transition-all shadow-xs shrink-0 cursor-pointer"
          >
            <i data-lucide="refresh-cw" class="w-4 h-4 text-white"></i>
            <span>Refresh Log</span>
          </button>
        </div>

        <!-- Audit Table Container -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
          <div class="max-h-[calc(100vh-320px)] overflow-auto custom-scrollbar">
            <table class="min-w-[1000px] w-full text-left border-collapse text-sm">
              <thead>
                <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                  <th class="p-4">Timestamp</th>
                  <th class="p-4">Actor</th>
                  <th class="p-4">Target User (ID)</th>
                  <th class="p-4">Action</th>
                  <th class="p-4">IP Address</th>
                  <th class="p-4">Details</th>
                </tr>
              </thead>
              <tbody id="auditTableBody">
                <tr><td colspan="6" class="p-12 text-center text-slate-500 font-medium text-sm">Click "Refresh Log" to query audit records.</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
