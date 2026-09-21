<div id="panelActivity" class="{{ $activeTab === 'activity' ? '' : 'hidden' }} space-y-6">
          <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-5">
              <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shrink-0">
                  <x-ui.icon name="verified" class="w-5 h-5 text-blue-600" />
                </div>
                <div>
                  <h3 class="font-bold text-slate-900 text-base sm:text-lg">Activity Points Verification</h3>
                  <p class="text-xs text-slate-500 mt-0.5">Review and verify extracurricular claims submitted by students in your batch.</p>
                </div>
              </div>
              <button type="button" onclick="loadActivityClaims()" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-sm font-semibold transition-all flex items-center gap-1.5 shadow-2xs cursor-pointer">
                <x-ui.icon name="refresh" class="w-4 h-4 text-slate-500" />
                <span>Refresh Claims</span>
              </button>
            </div>

            <div id="activityContent" class="overflow-x-auto rounded-xl border border-slate-200/80">
              <table class="w-full text-left text-sm border-collapse whitespace-nowrap">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider text-xs">
                    <th class="p-3.5 pl-4">Submitted On</th>
                    <th class="p-3.5">Student</th>
                    <th class="p-3.5">Segment</th>
                    <th class="p-3.5">Activity &amp; Level</th>
                    <th class="p-3.5">Evidence</th>
                    <th class="p-3.5 text-center">Claimed</th>
                    <th class="p-3.5 pr-4 text-center">Action</th>
                  </tr>
                </thead>
                <tbody id="tutorActivityTableBody" class="divide-y divide-slate-100">
                  <tr><td colspan="7" class="p-6 text-center text-slate-500 font-medium">Loading claims...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
