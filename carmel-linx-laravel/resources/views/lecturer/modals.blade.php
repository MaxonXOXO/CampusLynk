
        <!-- Step 2: Evaluation Form (shown when a student is selected) -->
        <div id="mobileSemStep2" class="hidden space-y-4">

          <!-- Student Info Card -->
          <div class="bg-gradient-to-br from-blue-950/80 to-indigo-950/80 border border-blue-600/40 rounded-2xl p-5 shadow-xl shadow-blue-900/20">
            <div class="flex items-start justify-between gap-3">
              <div class="flex-1 min-w-0">
                <div id="mobSemStudentName" class="text-xl font-black text-white leading-tight">-</div>
                <div class="text-sm text-slate-700 mt-1">SBTE Reg: <span id="mobSemSbteRegV2" class="font-mono text-blue-300 font-bold">-</span></div>
                <div class="mt-3 bg-blue-950/60 border border-blue-800/40 rounded-xl px-4 py-3">
                  <div class="text-xs text-blue-400 uppercase tracking-wider font-bold mb-1">Seminar Topic</div>
                  <div id="mobSemTopicV2" class="text-base font-bold text-white leading-snug">-</div>
                </div>
              </div>
              <!-- Live Score Ring -->
              <div class="shrink-0 flex flex-col items-center">
                <div class="relative w-20 h-20">
                  <svg class="w-20 h-20 -rotate-90" viewBox="0 0 64 64">
                    <circle cx="32" cy="32" r="26" fill="none" stroke="#1e293b" stroke-width="6"/>
                    <circle id="mobScoreRingCircle" cx="32" cy="32" r="26" fill="none" stroke="#3b82f6" stroke-width="6"
                      stroke-dasharray="163.36" stroke-dashoffset="163.36" stroke-linecap="round"
                      style="transition: stroke-dashoffset 0.4s ease, stroke 0.3s ease"/>
                  </svg>
                  <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span id="mobSemRingScore" class="text-lg font-black text-white leading-none">0</span>
                    <span class="text-xs text-slate-400 leading-none mt-0.5">/75</span>
                  </div>
                </div>
                <span class="text-xs text-slate-400 mt-1.5 font-bold uppercase tracking-wide">Your Score</span>
              </div>
            </div>
          </div>

          <!-- Evaluation Criteria Form -->
          <form id="mobileSeminarForm" onsubmit="submitMobileSeminarEvaluation(event)" class="space-y-3">

            <!-- Relevance -->
            <div class="bg-slate-900/70 border border-slate-700/70 rounded-2xl p-5 shadow-md">
              <div class="flex justify-between items-center mb-3">
                <div>
                  <div class="text-base font-bold text-slate-100">Relevance</div>
                  <div class="text-xs text-slate-400 mt-0.5">Topic alignment & suitability</div>
                </div>
                <div class="bg-slate-800 border border-slate-600 rounded-xl px-3 py-2 flex items-center gap-1 shadow-inner">
                  <input type="number" step="0.5" min="0" max="7.5" id="mobSemRelevance" required
                    oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                    class="w-14 bg-transparent text-white font-black text-lg text-right outline-none" placeholder="0">
                  <span class="text-slate-400 text-sm font-bold">/7.5</span>
                </div>
              </div>
              <input type="range" min="0" max="7.5" step="0.5" value="0"
                oninput="document.getElementById('mobSemRelevance').value=this.value; calcMobSemTotal()"
                class="w-full h-3 rounded-full accent-blue-500 bg-slate-700 cursor-pointer">
            </div>

            <!-- Literature -->
            <div class="bg-slate-900/70 border border-slate-700/70 rounded-2xl p-5 shadow-md">
              <div class="flex justify-between items-center mb-3">
                <div>
                  <div class="text-base font-bold text-slate-100">Literature Survey</div>
                  <div class="text-xs text-slate-400 mt-0.5">Depth of research & references</div>
                </div>
                <div class="bg-slate-800 border border-slate-600 rounded-xl px-3 py-2 flex items-center gap-1 shadow-inner">
                  <input type="number" step="0.5" min="0" max="7.5" id="mobSemLiterature" required
                    oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                    class="w-14 bg-transparent text-white font-black text-lg text-right outline-none" placeholder="0">
                  <span class="text-slate-400 text-sm font-bold">/7.5</span>
                </div>
              </div>
              <input type="range" min="0" max="7.5" step="0.5" value="0"
                oninput="document.getElementById('mobSemLiterature').value=this.value; calcMobSemTotal()"
                class="w-full h-3 rounded-full accent-indigo-500 bg-slate-700 cursor-pointer">
            </div>

            <!-- Presentation (largest weight) -->
            <div class="bg-slate-900/70 border border-blue-600/40 rounded-2xl p-5 shadow-md">
              <div class="flex justify-between items-center mb-3">
                <div>
                  <div class="text-base font-bold text-blue-300">Presentation Quality</div>
                  <div class="text-xs text-slate-400 mt-0.5">Clarity, structure & delivery — highest weight</div>
                </div>
                <div class="bg-slate-800 border border-blue-700/50 rounded-xl px-3 py-2 flex items-center gap-1 shadow-inner">
                  <input type="number" step="0.5" min="0" max="37.5" id="mobSemPresentation" required
                    oninput="clampMobSem(this,37.5); calcMobSemTotal()"
                    class="w-16 bg-transparent text-blue-300 font-black text-lg text-right outline-none" placeholder="0">
                  <span class="text-slate-400 text-sm font-bold">/37.5</span>
                </div>
              </div>
              <input type="range" min="0" max="37.5" step="0.5" value="0"
                oninput="document.getElementById('mobSemPresentation').value=this.value; calcMobSemTotal()"
                class="w-full h-3 rounded-full accent-blue-400 bg-slate-700 cursor-pointer">
            </div>

            <!-- Last 3 criteria in a row -->
            <div class="grid grid-cols-3 gap-3">
              <!-- Interaction -->
              <div class="bg-slate-900/70 border border-purple-700/30 rounded-2xl p-3.5 flex flex-col items-center gap-2 shadow-md">
                <div class="text-xs font-black text-purple-300 uppercase tracking-wide text-center">Interaction</div>
                <div class="text-xs text-slate-400 text-center">Q&A</div>
                <input type="number" step="0.5" min="0" max="7.5" id="mobSemInteraction" required
                  oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                  class="w-full bg-slate-800 border border-purple-700/40 rounded-xl px-2 py-2.5 text-white font-black text-base text-center outline-none focus:border-purple-400 transition-premium">
                <div class="text-xs text-slate-500 font-bold">max 7.5</div>
              </div>
              <!-- Report -->
              <div class="bg-slate-900/70 border border-teal-700/30 rounded-2xl p-3.5 flex flex-col items-center gap-2 shadow-md">
                <div class="text-xs font-black text-teal-300 uppercase tracking-wide text-center">Report</div>
                <div class="text-xs text-slate-400 text-center">Written</div>
                <input type="number" step="0.5" min="0" max="7.5" id="mobSemReport" required
                  oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                  class="w-full bg-slate-800 border border-teal-700/40 rounded-xl px-2 py-2.5 text-white font-black text-base text-center outline-none focus:border-teal-400 transition-premium">
                <div class="text-xs text-slate-500 font-bold">max 7.5</div>
              </div>
              <!-- Attendance -->
              <div class="bg-slate-900/70 border border-emerald-700/30 rounded-2xl p-3.5 flex flex-col items-center gap-2 shadow-md">
                <div class="text-xs font-black text-emerald-300 uppercase tracking-wide text-center">Attendance</div>
                <div class="text-xs text-slate-400 text-center">Presence</div>
                <input type="number" step="0.5" min="0" max="7.5" id="mobSemAttendance" required
                  oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                  class="w-full bg-slate-800 border border-emerald-700/40 rounded-xl px-2 py-2.5 text-white font-black text-base text-center outline-none focus:border-emerald-400 transition-premium">
                <div class="text-xs text-slate-500 font-bold">max 7.5</div>
              </div>
            </div>

            <!-- Total + Submit -->
            <div class="bg-gradient-to-r from-slate-900 to-slate-950 border border-slate-600/60 rounded-2xl p-5 flex items-center justify-between gap-4 shadow-xl">
              <div>
                <div class="text-sm text-slate-400 font-bold uppercase tracking-wider mb-1">Total Score</div>
                <div class="text-xl font-black text-slate-500" id="mobSemTotalDisplay">
                  <span id="mobSemTotalNum" class="text-blue-400">0.00</span> / 75
                </div>
                <!-- keep old ID for backward compat -->
                <div id="mobSemTotalScoreLabel" class="hidden"></div>
              </div>
              <button type="submit" id="mobSemSubmitBtn"
                class="px-6 py-4 bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white rounded-xl font-black text-base shadow-lg shadow-blue-500/30 transition-premium cursor-pointer flex items-center gap-2">
                <x-ui.icon name="save" class="w-5 h-5" /> Save
              </button>
            </div>

            <button type="button" onclick="backToSeminarList()" class="w-full py-3.5 text-slate-700 text-sm font-bold flex items-center justify-center gap-2 cursor-pointer hover:text-white transition-premium border border-slate-700/50 rounded-xl hover:bg-slate-800/50">
              <x-ui.icon name="science" class="w-4 h-4" /> Back to Seminar List
            </button>

          </form>
        </div>

      </div>
    </main>
  </div>
</div>

