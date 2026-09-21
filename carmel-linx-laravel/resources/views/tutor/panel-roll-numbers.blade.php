<div id="panelRollNumbers" class="{{ $activeTab === 'rollNumbers' ? '' : 'hidden' }} space-y-6">
          <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-5">
              <div>
                <h3 class="font-bold text-slate-900 text-base sm:text-lg">Assign Class Roll Numbers</h3>
                <p class="text-xs text-slate-500 mt-0.5">Set the serial roll numbers for students in your supervised classroom.</p>
              </div>
              <div class="flex items-center gap-2.5 flex-wrap">
                <button type="button" onclick="autoFillRollNumbers()" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold text-sm flex items-center gap-2 transition-all cursor-pointer shadow-2xs">
                  <x-ui.icon name="auto_awesome" class="w-4 h-4 text-amber-500" />
                  <span>Auto-Fill (A-Z)</span>
                </button>
                <button type="button" onclick="saveRollNumbers()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm flex items-center gap-2 cursor-pointer transition-all shadow-xs">
                  <x-ui.icon name="save" class="w-4 h-4" />
                  <span>Save Roll Numbers</span>
                </button>
              </div>
            </div>
            
            <div class="overflow-x-auto border border-slate-200/80 rounded-xl bg-white">
              <table class="w-full text-left text-sm border-collapse">
                <thead>
                  <tr class="bg-slate-50 text-slate-600 border-b border-slate-200 uppercase tracking-wider text-xs font-semibold">
                    <th class="p-3.5 pl-5 w-16 text-center">No.</th>
                    <th class="p-3.5 w-40">Reg No</th>
                    <th class="p-3.5 w-48">SBTE Exam No</th>
                    <th class="p-3.5">Student Name</th>
                    <th class="p-3.5 pr-5 w-36 text-center">Roll Number</th>
                  </tr>
                </thead>
                <tbody id="tutorRollNumberList" class="divide-y divide-slate-100">
                  <tr><td colspan="5" class="p-8 text-center text-slate-500 font-medium">Loading roll numbers...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
