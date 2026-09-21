        <!-- PANEL 4: SECURITY LOG -->
        <div id="panelSecurity" class="hidden space-y-6">
          <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl">
            <h3 class="font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2 text-sm">
              <span class="material-symbols-rounded text-blue-400 text-lg">security</span> My Profile Security Audit Trail
            </h3>
            <div class="overflow-x-auto scrollbar-hidden border border-slate-800 rounded-xl">
              <table class="w-full text-left border-collapse text-xs">
                <thead>
                  <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-400 font-bold">
                    <th class="p-4">Time</th>
                    <th class="p-4">Action</th>
                    <th class="p-4">Details</th>
                  </tr>
                </thead>
                <tbody id="selfSecurityLogsTable">
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </main>
  </div>

  <!-- REJECTION REMARKS MODAL -->
  <div id="rejectModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-rose-400 text-lg">cancel</span> Reject Leave Application
        </h3>
        <button onclick="closeRejectModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

