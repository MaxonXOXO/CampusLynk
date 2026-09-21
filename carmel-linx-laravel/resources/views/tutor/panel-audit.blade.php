<div id="panelAudit" class="{{ $activeTab === 'audit' ? '' : 'hidden' }} space-y-6">
          <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-5">
              <div>
                <h3 class="font-bold text-slate-900 text-base sm:text-lg">Classroom Audit Trail</h3>
                <p class="text-xs text-slate-500 mt-0.5">Lifecycle events, password resets, and approval actions involving students in your classroom.</p>
              </div>
              <button type="button" onclick="loadAuditTrail()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-all cursor-pointer flex items-center gap-2 shadow-xs">
                <x-ui.icon name="sync" class="w-4 h-4" />
                <span>Refresh Log</span>
              </button>
            </div>

            <div class="overflow-x-auto custom-scrollbar border border-slate-200/80 rounded-xl">
              <table class="w-full text-left border-collapse text-xs">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                    <th class="p-3.5 pl-4">Timestamp</th>
                    <th class="p-3.5">Actor</th>
                    <th class="p-3.5">Target Student (ID)</th>
                    <th class="p-3.5">Action</th>
                    <th class="p-3.5">IP Address</th>
                    <th class="p-3.5 pr-4">Details</th>
                  </tr>
                </thead>
                <tbody id="auditTableBody" class="divide-y divide-slate-100">
                  <tr><td colspan="6" class="p-6 text-center text-slate-500 font-medium">Querying classroom audit logs...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
