<!-- Modal: Bulk Enter End Semester Exam (ESE) Marks & NBA Attainment Criteria -->
  <div id="modalEseMarks" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-4xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
      <!-- Modal Header -->
      <div class="p-5 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
        <div>
          <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
            <span class="material-symbols-rounded text-emerald-700 text-base">tune</span>
            NBA Attainment Threshold Config & ESE Evaluation
          </h3>
          <p class="text-xs text-slate-500 mt-1">Configure threshold marks/grades for CIE and ESE exams, target student percentage, and batch attainment criteria.</p>
        </div>
        <button onclick="closeEseMarksModal()" class="text-slate-400 hover:text-slate-700 text-lg font-bold p-1 cursor-pointer">✕</button>
      </div>

      <!-- Modal Body -->
      <div class="p-5 overflow-y-auto space-y-5 flex-grow" id="eseModalBody">
        
        <!-- Streamlined Threshold Config Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <!-- Card 1: Exam Threshold Settings -->
          <div class="bg-slate-50/80 border border-slate-200 p-3 rounded-xl space-y-2">
            <div class="flex items-center justify-between border-b border-slate-200/80 pb-1.5">
              <span class="text-xs font-black text-slate-800 uppercase tracking-wider">1. Assessment Threshold Settings</span>
              <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">CIE & ESE Targets</span>
            </div>

            <!-- Hidden Inputs for API Backward Compatibility -->
            <input type="hidden" id="eseEntryMode" value="grades">
            <input type="hidden" id="eseMaxMarks" value="60">
            <input type="hidden" id="eseThresholdPercent" value="50">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
              <!-- ESE Threshold Grade (SBTE Kerala Board) -->
              <div>
                <label class="block text-[10px] font-bold text-slate-500 mb-1">ESE Threshold Grade (SBTE)</label>
                <select id="eseThresholdGrade" onchange="recalculateEseStats()" class="w-full bg-white border border-slate-200 text-teal-700 font-bold text-xs px-2 py-1.5 rounded-lg outline-none focus:border-teal-500 overflow-ellipsis">
                  <option value="E">E Grade & Above (Pass - 40%+ | 5 GP)</option>
                  <option value="D" selected>D Grade & Above (Average - 50%+ | 6 GP)</option>
                  <option value="C">C Grade & Above (Good - 60%+ | 7 GP)</option>
                  <option value="B">B Grade & Above (Very Good - 70%+ | 8 GP)</option>
                  <option value="A">A Grade & Above (Excellent - 80%+ | 9 GP)</option>
                  <option value="S">S Grade (Outstanding - 90%+ | 10 GP)</option>
                </select>
              </div>

              <!-- Internal (CIE) Threshold -->
              <div>
                <label class="block text-[10px] font-bold text-slate-500 mb-1">Internal (CIE) Threshold (%)</label>
                <input type="number" id="cieThresholdPercent" value="50" min="30" max="90" step="1" oninput="recalculateEseStats()" class="w-full bg-white border border-slate-200 text-indigo-700 font-mono font-bold text-xs px-2.5 py-1.5 rounded-lg outline-none focus:border-indigo-500">
              </div>
            </div>
          </div>

          <!-- Card 2: Target Student % & Attainment Levels -->
          <div class="bg-slate-50/80 border border-slate-200 p-3 rounded-xl space-y-2">
            <div class="flex items-center justify-between border-b border-slate-200/80 pb-1.5">
              <span class="text-xs font-black text-slate-800 uppercase tracking-wider">2. Batch Target & Attainment Levels</span>
              <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">NBA Criteria</span>
            </div>

            <div class="grid grid-cols-4 gap-2">
              <div class="bg-emerald-50 border border-emerald-300 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                <span class="block text-[9px] font-bold text-emerald-700 uppercase tracking-tight">Target (T)</span>
                <div class="flex items-center justify-center gap-0.5 mt-0.5">
                  <input type="number" id="targetStudentPercent" value="70" min="30" max="100" step="1" oninput="recalculateEseStats(true)" class="w-12 bg-transparent text-emerald-700 font-mono font-black text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                  <span class="text-[10px] text-slate-500 font-bold">%</span>
                </div>
              </div>

              <div class="bg-emerald-50 border border-emerald-300 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                <span class="block text-[9px] font-bold text-emerald-700 uppercase tracking-tight">Level 3 (High)</span>
                <div class="flex items-center justify-center gap-0.5 mt-0.5">
                  <span class="text-[10px] text-emerald-700 font-bold">&ge;</span>
                  <input type="number" id="inputLevel3Percent" value="70" min="0" max="100" step="1" oninput="recalculateEseStats(false)" class="w-10 bg-transparent text-emerald-700 font-mono font-bold text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                  <span class="text-[10px] text-slate-500 font-bold">%</span>
                </div>
              </div>

              <div class="bg-amber-50 border border-amber-300 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                <span class="block text-[9px] font-bold text-amber-700 uppercase tracking-tight">Level 2 (Mod)</span>
                <div class="flex items-center justify-center gap-0.5 mt-0.5">
                  <span class="text-[10px] text-amber-700 font-bold">&ge;</span>
                  <input type="number" id="inputLevel2Percent" value="60" min="0" max="100" step="1" oninput="recalculateEseStats(false)" class="w-10 bg-transparent text-amber-700 font-mono font-bold text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                  <span class="text-[10px] text-slate-500 font-bold">%</span>
                </div>
              </div>

              <div class="bg-blue-50 border border-blue-300 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                <span class="block text-[9px] font-bold text-blue-700 uppercase tracking-tight">Level 1 (Low)</span>
                <div class="flex items-center justify-center gap-0.5 mt-0.5">
                  <span class="text-[10px] text-blue-700 font-bold">&ge;</span>
                  <input type="number" id="inputLevel1Percent" value="50" min="0" max="100" step="1" oninput="recalculateEseStats(false)" class="w-10 bg-transparent text-blue-700 font-mono font-bold text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                  <span class="text-[10px] text-slate-500 font-bold">%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Summary & Batch Metrics -->
        <div id="eseSummaryBar" class="grid grid-cols-2 md:grid-cols-4 gap-3 bg-slate-50/60 p-3.5 rounded-xl border border-slate-200/80">
          <div>
            <span class="block text-[10px] font-bold text-slate-500 uppercase">Max Batch Students</span>
            <span id="statTotalStudents" class="text-sm font-black text-slate-800">0</span>
          </div>
          <div>
            <span class="block text-[10px] font-bold text-slate-500 uppercase">Students Appeared</span>
            <span id="statAppearedStudents" class="text-sm font-black text-blue-700">0</span>
          </div>
          <div>
            <span class="block text-[10px] font-bold text-slate-500 uppercase">Met Target Threshold</span>
            <span id="statMetTargetStudents" class="text-sm font-black text-emerald-700">0 (0%)</span>
          </div>
          <div>
            <span class="block text-[10px] font-bold text-slate-500 uppercase">ESE Attainment Level</span>
            <span id="statAttainmentLevel" class="text-sm font-black text-amber-700">Level 0</span>
          </div>
        </div>

        <!-- Toolbar & Table -->
        <div class="space-y-3">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 bg-slate-50/40 p-3 rounded-xl border border-slate-200">
            <span class="text-xs text-slate-700 font-bold">Student ESE Grade Ledger (SBTE Kerala)</span>
            <span class="text-[10px] font-bold text-slate-500 bg-white px-2 py-1 rounded border border-slate-200">S, A, B, C, D, E, F Evaluation</span>
          </div>

          <div class="overflow-x-auto border border-slate-200 rounded-xl">
            <table class="w-full text-left text-xs border-collapse">
              <thead>
                <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                  <th class="p-3 w-16">Roll</th>
                  <th class="p-3 w-36">Register No</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3 text-center w-64">ESE Score & SBTE Grade</th>
                  <th class="p-3 text-center w-28">Status</th>
                </tr>
              </thead>
              <tbody id="eseMarksTableBody" class="divide-y divide-slate-100">
                <tr>
                  <td colspan="5" class="p-6 text-center text-slate-500 font-bold">Loading student records...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
        <button onclick="closeEseMarksModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-700 rounded-xl text-xs font-bold transition-premium cursor-pointer">
          Cancel
        </button>
        <button onclick="saveEseMarks()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer shadow-lg shadow-xs flex items-center gap-1.5">
          <span class="material-symbols-rounded text-sm">save</span> Save ESE Evaluation & Calculate Attainment
        </button>
      </div>
    </div>
  </div>

  <!-- Dedicated Labwork Grade Modal (37.5 Marks) -->
  <div id="labworkGradeModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-lg lg:max-w-6xl xl:max-w-7xl overflow-hidden shadow-2xl space-y-0 transition-all">
      <!-- Header -->
      <div class="bg-slate-50 px-4 py-3 border-b border-slate-200 flex justify-between items-center">
        <div class="flex items-center gap-2.5">
          <div class="p-1.5 bg-slate-800/80 border border-slate-700/60 rounded-lg text-slate-700 shrink-0">
            <span class="material-symbols-rounded text-lg">science</span>
          </div>
          <div>
            <h3 id="lwModalStudentInfo" class="text-base sm:text-lg font-black text-slate-900 tracking-wide uppercase">Student Name (PRN)</h3>
            <p class="text-[11px] font-bold text-slate-500 mt-0.5 flex items-center gap-1">
              Labwork Continuous Evaluation (Max 37.5 Marks)
            </p>
          </div>
        </div>
        
        <!-- Prev / Next Navigation -->
        <div class="flex items-center gap-1.5">
          <button onclick="navigateLwStudent(-1)" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-700 text-[11px] font-semibold rounded-md transition flex items-center gap-0.5 cursor-pointer">
            <span class="material-symbols-rounded text-xs">chevron_left</span> Prev
          </button>
          <span id="lwModalStudentCounter" class="text-[11px] font-mono text-slate-500">1 / 40</span>
          <button onclick="navigateLwStudent(1)" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-700 text-[11px] font-semibold rounded-md transition flex items-center gap-0.5 cursor-pointer">
            Next <span class="material-symbols-rounded text-xs">chevron_right</span>
          </button>
          <button onclick="closeLwModal()" class="text-slate-400 hover:text-slate-700 p-1 ml-1 cursor-pointer">
            <span class="material-symbols-rounded text-base">close</span>
          </button>
        </div>
      </div>

      <!-- Body -->
      <div class="p-4 space-y-3 max-h-[75vh] overflow-y-auto">
        <!-- Select Exp No and Title & Date -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-2.5 bg-slate-50/60 p-3 rounded-xl border border-slate-200">
          <div class="md:col-span-3">
            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Select Experiment No &amp; Title</label>
            <select id="lwModalExpSelect" onchange="loadLwExpValues()" class="w-full bg-white border border-slate-200 text-slate-800 focus:border-slate-500 rounded-lg px-2.5 py-1.5 text-xs text-slate-900 font-medium outline-none">
            </select>
          </div>
          <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Date of Experiment</label>
            <input type="date" id="lwModalExpDate" onchange="updateLwExpField('date', this.value)" class="w-full bg-white border border-slate-200 text-slate-800 focus:border-slate-500 rounded-lg px-2 py-1.5 text-xs text-slate-900 font-mono outline-none">
          </div>
        </div>

        <!-- Sliders for 5 Components (Single Row on Desktop) -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-2.5">
          <!-- Rough Record (5) -->
          <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 flex flex-col justify-between gap-2 shadow-sm">
            <div class="flex justify-between items-center text-xs font-bold">
              <span class="text-slate-700 text-[11px] font-bold truncate">Rough Record</span>
              <div class="flex items-center gap-1 shrink-0">
                <input type="number" id="lw_rough" step="0.5" min="0" max="5" onfocus="this.select()" onkeydown="navigateLwModalInput(event, 'rough')" oninput="syncLwComponent('rough', this.value, 5)" class="w-12 bg-white border border-slate-200 text-slate-800 rounded text-center text-xs font-mono font-bold text-slate-900 py-0.5 outline-none no-spinner focus:border-cyan-500">
                <span class="text-slate-500 text-[10px] font-mono">/ 5</span>
              </div>
            </div>
            <input type="range" id="lw_rough_slider" tabindex="-1" min="0" max="5" step="0.5" oninput="syncLwComponent('rough', this.value, 5)" class="w-full h-1.5 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-cyan-400">
          </div>

          <!-- Fair Record (7.5) -->
          <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 flex flex-col justify-between gap-2 shadow-sm">
            <div class="flex justify-between items-center text-xs font-bold">
              <span class="text-slate-700 text-[11px] font-bold truncate">Fair Record</span>
              <div class="flex items-center gap-1 shrink-0">
                <input type="number" id="lw_fair" step="0.5" min="0" max="7.5" onfocus="this.select()" onkeydown="navigateLwModalInput(event, 'fair')" oninput="syncLwComponent('fair', this.value, 7.5)" class="w-12 bg-white border border-slate-200 text-slate-800 rounded text-center text-xs font-mono font-bold text-slate-900 py-0.5 outline-none no-spinner focus:border-cyan-500">
                <span class="text-slate-500 text-[10px] font-mono">/ 7.5</span>
              </div>
            </div>
            <input type="range" id="lw_fair_slider" tabindex="-1" min="0" max="7.5" step="0.5" oninput="syncLwComponent('fair', this.value, 7.5)" class="w-full h-1.5 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-cyan-400">
          </div>

          <!-- Observation & Recording (7.5) -->
          <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 flex flex-col justify-between gap-2 shadow-sm">
            <div class="flex justify-between items-center text-xs font-bold">
              <span class="text-slate-700 text-[11px] font-bold truncate">Obs &amp; Record</span>
              <div class="flex items-center gap-1 shrink-0">
                <input type="number" id="lw_obs" step="0.5" min="0" max="7.5" onfocus="this.select()" onkeydown="navigateLwModalInput(event, 'obs')" oninput="syncLwComponent('obs', this.value, 7.5)" class="w-12 bg-white border border-slate-200 text-slate-800 rounded text-center text-xs font-mono font-bold text-slate-900 py-0.5 outline-none no-spinner focus:border-cyan-500">
                <span class="text-slate-500 text-[10px] font-mono">/ 7.5</span>
              </div>
            </div>
            <input type="range" id="lw_obs_slider" tabindex="-1" min="0" max="7.5" step="0.5" oninput="syncLwComponent('obs', this.value, 7.5)" class="w-full h-1.5 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-cyan-400">
          </div>

          <!-- Procedure & Punctuality (7.5) -->
          <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 flex flex-col justify-between gap-2 shadow-sm">
            <div class="flex justify-between items-center text-xs font-bold">
              <span class="text-slate-700 text-[11px] font-bold truncate">Proc &amp; Punct</span>
              <div class="flex items-center gap-1 shrink-0">
                <input type="number" id="lw_proc" step="0.5" min="0" max="7.5" onfocus="this.select()" onkeydown="navigateLwModalInput(event, 'proc')" oninput="syncLwComponent('proc', this.value, 7.5)" class="w-12 bg-white border border-slate-200 text-slate-800 rounded text-center text-xs font-mono font-bold text-slate-900 py-0.5 outline-none no-spinner focus:border-cyan-500">
                <span class="text-slate-500 text-[10px] font-mono">/ 7.5</span>
              </div>
            </div>
            <input type="range" id="lw_proc_slider" tabindex="-1" min="0" max="7.5" step="0.5" oninput="syncLwComponent('proc', this.value, 7.5)" class="w-full h-1.5 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-cyan-400">
          </div>

          <!-- Viva Voce (10) -->
          <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 flex flex-col justify-between gap-2 shadow-sm">
            <div class="flex justify-between items-center text-xs font-bold">
              <span class="text-slate-700 text-[11px] font-bold truncate">Viva Voce</span>
              <div class="flex items-center gap-1 shrink-0">
                <input type="number" id="lw_viva" step="0.5" min="0" max="10" onfocus="this.select()" onkeydown="navigateLwModalInput(event, 'viva')" oninput="syncLwComponent('viva', this.value, 10)" class="w-12 bg-white border border-slate-200 text-slate-800 rounded text-center text-xs font-mono font-bold text-slate-900 py-0.5 outline-none no-spinner focus:border-cyan-500">
                <span class="text-slate-500 text-[10px] font-mono">/ 10</span>
              </div>
            </div>
            <input type="range" id="lw_viva_slider" tabindex="-1" min="0" max="10" step="0.5" oninput="syncLwComponent('viva', this.value, 10)" class="w-full h-1.5 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-cyan-400">
          </div>
        </div>

        <!-- Live Exp Total (Enlarged) -->
        <div class="bg-slate-100/90 p-3.5 rounded-xl border border-slate-200/80 shadow-md flex flex-wrap justify-between items-center gap-3">
          <div class="flex items-center gap-2.5">
            <div class="p-2 bg-cyan-500/10 border border-cyan-500/30 rounded-lg text-cyan-700">
              <span class="material-symbols-rounded text-xl">calculate</span>
            </div>
            <div>
              <span class="text-xs font-black text-slate-800 uppercase tracking-wider block">Experiment Total Score</span>
              <span class="text-[11px] text-slate-500">Continuous Lab Work Rubric Total (Max 37.5 Marks)</span>
            </div>
          </div>
          <div class="flex items-center bg-white px-4 py-1.5 rounded-xl border border-cyan-400 shadow-inner">
            <span id="lwModalExpTotal" class="text-2xl sm:text-3xl font-mono font-black text-cyan-700 tracking-tight">0.0 / 37.5</span>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="bg-slate-50 px-4 py-3 border-t border-slate-200 flex flex-wrap justify-between items-center gap-2">
        <button onclick="closeLwModal()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-700 rounded-lg text-xs font-semibold transition cursor-pointer">Cancel</button>
        <div class="flex items-center gap-1.5">
          <button onclick="saveLwModal(false, -1)" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-800 rounded-lg text-xs font-bold transition flex items-center gap-0.5 cursor-pointer">
            <span class="material-symbols-rounded text-xs">arrow_back</span> Save &amp; Prev
          </button>
          <button onclick="saveLwModal(true)" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white rounded-lg text-xs font-bold transition cursor-pointer border border-slate-600">Save Mark</button>
          <button id="lwModalSaveNextBtn" onclick="saveLwModal(false, 1)" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-800 rounded-lg text-xs font-bold transition flex items-center gap-0.5 cursor-pointer">
            Save &amp; Next <span class="material-symbols-rounded text-xs">arrow_forward</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Dedicated Open-Ended Project Grade Modal (7.5 Marks) -->
  <div id="openEndedGradeModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl space-y-0">
      <!-- Header -->
      <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-slate-800 border border-slate-700 rounded-xl text-slate-700 shrink-0">
            <span class="material-symbols-rounded text-xl">assignment</span>
          </div>
          <div>
            <h3 id="oeModalStudentInfo" class="text-lg sm:text-xl font-black text-slate-900 tracking-wide uppercase">Student Name (PRN)</h3>
            <p class="text-xs font-bold text-slate-500 mt-0.5 flex items-center gap-1">
              Open-Ended Project Evaluation (Max 7.5 Marks)
            </p>
          </div>
        </div>
        
        <!-- Prev / Next Navigation -->
        <div class="flex items-center gap-2">
          <button onclick="navigateOeStudent(-1)" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-700 text-xs font-semibold rounded-lg transition flex items-center gap-1 cursor-pointer">
            <span class="material-symbols-rounded text-xs">chevron_left</span> Prev
          </button>
          <span id="oeModalStudentCounter" class="text-xs font-mono text-slate-500">1 / 40</span>
          <button onclick="navigateOeStudent(1)" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-700 text-xs font-semibold rounded-lg transition flex items-center gap-1 cursor-pointer">
            Next <span class="material-symbols-rounded text-xs">chevron_right</span>
          </button>
          <button onclick="closeOeModal()" class="text-slate-400 hover:text-slate-700 p-1 ml-2 cursor-pointer">
            <span class="material-symbols-rounded text-lg">close</span>
          </button>
        </div>
      </div>

      <!-- Body -->
      <div class="p-6 space-y-5">
        <!-- Project Topic/Title -->
        <div>
          <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Project Topic / Title</label>
          <input type="text" id="oeModalProjectTopic" placeholder="e.g., Automatic Water Level Controller Circuit Design" class="w-full bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-medium outline-none">
        </div>

        <!-- Mark (out of 7.5) -->
        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2.5">
          <div class="flex justify-between items-center text-xs font-bold">
            <span class="text-slate-700">Open-Ended Evaluation Mark</span>
            <div class="flex items-center gap-1">
              <input type="number" id="oe_mark" step="0.5" min="0" max="7.5" onfocus="this.select()" oninput="syncOeSlider(this.value)" class="w-14 bg-white border border-slate-200 text-slate-800 rounded text-center text-xs font-mono font-bold text-slate-900 py-1 outline-none no-spinner focus:border-slate-500">
              <span class="text-slate-500 text-[11px]">/ 7.5</span>
            </div>
          </div>
          <input type="range" id="oe_mark_slider" tabindex="-1" min="0" max="7.5" step="0.5" oninput="syncOeSlider(this.value)" class="w-full accent-slate-400 cursor-pointer">
        </div>
      </div>

      <!-- Footer -->
      <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex flex-wrap justify-between items-center gap-3">
        <button onclick="closeOeModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-700 rounded-xl text-xs font-semibold transition cursor-pointer">Cancel</button>
        <div class="flex items-center gap-2">
          <button onclick="saveOeModal(false, -1)" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-800 rounded-xl text-xs font-bold transition flex items-center gap-1 cursor-pointer">
            <span class="material-symbols-rounded text-xs">arrow_back</span> Save &amp; Prev
          </button>
          <button onclick="saveOeModal(true)" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-xl text-xs font-bold transition cursor-pointer border border-slate-600">Save Mark</button>
          <button onclick="saveOeModal(false, 1)" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-800 rounded-xl text-xs font-bold transition flex items-center gap-1 cursor-pointer">
            Save &amp; Next <span class="material-symbols-rounded text-xs">arrow_forward</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Virtual Lab Modals (Revision 2021) -->
  <!-- Student Lab Modal -->
  <div id="studentLabModal" onclick="if(event.target === this) closeStudentLabModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-6xl max-h-[90vh] flex flex-col overflow-hidden shadow-2xl">
      <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
        <div>
          <h3 id="labModalStudentName" class="text-lg sm:text-xl font-black text-slate-900 uppercase tracking-wide">Student Evaluation</h3>
          <p id="labModalStudentReg" class="text-sm font-bold text-cyan-700 font-mono mt-0.5"></p>
        </div>
        <div class="flex items-center gap-3">
          <a id="btnLabModalPrintStudent" href="#" target="_blank" class="px-3.5 py-1.5 bg-blue-600/20 hover:bg-blue-600/30 border border-blue-500/40 text-blue-700 hover:text-blue-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm cursor-pointer" title="Print Individual Student Practical Evaluation & Attendance Report">
            <span class="material-symbols-rounded text-base">print</span>
            <span>Print Student Report</span>
          </a>
          <button type="button" onclick="closeStudentLabModal()" class="text-slate-400 hover:text-slate-700 transition-premium cursor-pointer p-1.5 rounded-lg hover:bg-slate-800 flex items-center justify-center">
            <span class="material-symbols-rounded text-2xl">close</span>
          </button>
        </div>
      </div>
      <div class="px-6 py-2 bg-white border-b border-slate-200/50 flex gap-4 text-xs font-bold">
        <button onclick="switchLabModalTab('exp')" id="labTabBtn_exp" class="py-2 border-b-2 border-blue-500 text-blue-700 px-1 transition-premium">Experiments (37.5)</button>
        <button onclick="switchLabModalTab('test')" id="labTabBtn_test" class="py-2 border-b-2 border-transparent text-slate-500 px-1 transition-premium">Model Tests (15)</button>
        <button onclick="switchLabModalTab('project')" id="labTabBtn_project" class="py-2 border-b-2 border-transparent text-slate-500 px-1 transition-premium">Micro-Project &amp; Attendance (22.5)</button>
        <button onclick="switchLabModalTab('board')" id="labTabBtn_board" class="py-2 border-b-2 border-transparent text-slate-500 px-1 transition-premium font-black text-blue-700">Board Exam (50)</button>
      </div>
      
      <div class="flex-grow overflow-y-auto p-6 space-y-6">
        <!-- TAB: EXPERIMENTS (CONTINUOUS EVALUATION CARDS LIST) -->
        <div id="labModalTab_exp" class="space-y-5">
          <!-- Summary Header Banner -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/60 border border-slate-200 p-4 rounded-xl shadow-md">
            <div>
              <h4 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-rounded text-teal-400 text-base">science</span>
                Continuous Evaluation (Day-to-Day Lab Work)
              </h4>
              <p class="text-xs text-slate-500 mt-0.5">Grade experiments individually (out of 37.5) or enter the direct total mark. If direct mark is set, it overrides the split-up average.</p>
            </div>
            <div class="flex flex-wrap items-center gap-4 shrink-0">
              <div class="flex items-center gap-2 bg-white border border-slate-200 text-slate-800/60 px-3 py-1.5 rounded-xl shadow-sm">
                <label for="labScore_directLabWork" class="text-[11px] font-bold text-cyan-700 uppercase tracking-wider">Direct Mark (37.5):</label>
                <input type="number" step="0.25" min="0" max="37.5" id="labScore_directLabWork" onfocus="this.select()" oninput="calcLabModalScores()" placeholder="Auto" class="no-spinner w-16 bg-slate-50 border border-slate-700 focus:border-cyan-400 rounded-lg px-2 py-1 text-xs text-cyan-300 font-mono font-bold text-center outline-none" style="-moz-appearance: textfield; -webkit-appearance: none; appearance: none; margin: 0;">
              </div>
              <div class="text-right">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Lab Work Average:</span>
                <span id="labModalLabelExpSummary" class="text-base font-mono font-black text-emerald-700">0.00 / 37.5</span>
              </div>
              <div class="w-48">
                <select id="expJumpSelect" onchange="jumpToExpCard(this.value)" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-700 font-bold focus:border-blue-500 outline-none">
                  <option value="">Jump to Exp...</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Experiments Dynamic List Container -->
          <div id="labModalExpsListContainer" class="space-y-4">
            <!-- Dynamically populated cards -->
          </div>
        </div>

        <!-- TAB: MODEL TESTS -->
        <div id="labModalTab_test" class="space-y-4 hidden">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Test 1 Card -->
            <div class="bg-slate-50/30 border border-slate-200/40 p-4 rounded-xl space-y-4">
              <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                <h4 class="text-xs font-black text-blue-700 uppercase tracking-widest">Model Test 1</h4>
                <span class="text-xs font-mono font-black text-slate-500" id="labModalT1Sum">0.0 / 15</span>
              </div>
              <!-- CO1 -->
              <div class="bg-white/40 p-3 rounded-lg border border-slate-850/50 space-y-2">
                <div class="flex justify-between items-center text-xs font-bold">
                  <span class="text-slate-700">CO1 (Max 7.5)</span>
                  <input type="number" step="0.5" min="0" max="7.5" id="labScore_t1_co1" onfocus="this.select()" oninput="syncSlider('labScore_t1_co1','labScore_t1_co1_slider',7.5); calcLabModalScores()" class="w-14 bg-slate-50 border border-slate-200 rounded px-1.5 py-0.5 text-xs text-slate-800 text-center focus:border-blue-500 outline-none" placeholder="0.0">
                </div>
                <input type="range" id="labScore_t1_co1_slider" tabindex="-1" min="0" max="7.5" step="0.5" value="0" oninput="document.getElementById('labScore_t1_co1').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-500 bg-slate-800 cursor-pointer">
              </div>
              <!-- CO2 -->
              <div class="bg-white/40 p-3 rounded-lg border border-slate-850/50 space-y-2">
                <div class="flex justify-between items-center text-xs font-bold">
                  <span class="text-slate-700">CO2 (Max 7.5)</span>
                  <input type="number" step="0.5" min="0" max="7.5" id="labScore_t1_co2" onfocus="this.select()" oninput="syncSlider('labScore_t1_co2','labScore_t1_co2_slider',7.5); calcLabModalScores()" class="w-14 bg-slate-50 border border-slate-200 rounded px-1.5 py-0.5 text-xs text-slate-800 text-center focus:border-blue-500 outline-none" placeholder="0.0">
                </div>
                <input type="range" id="labScore_t1_co2_slider" tabindex="-1" min="0" max="7.5" step="0.5" value="0" oninput="document.getElementById('labScore_t1_co2').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-500 bg-slate-800 cursor-pointer">
              </div>
            </div>

            <!-- Test 2 Card -->
            <div class="bg-slate-50/30 border border-slate-200/40 p-4 rounded-xl space-y-4">
              <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                <h4 class="text-xs font-black text-purple-400 uppercase tracking-widest">Model Test 2</h4>
                <span class="text-xs font-mono font-black text-slate-500" id="labModalT2Sum">0.0 / 15</span>
              </div>
              <!-- CO3 -->
              <div class="bg-white/40 p-3 rounded-lg border border-slate-850/50 space-y-2">
                <div class="flex justify-between items-center text-xs font-bold">
                  <span class="text-slate-700">CO3 (Max 7.5)</span>
                  <input type="number" step="0.5" min="0" max="7.5" id="labScore_t2_co3" onfocus="this.select()" oninput="syncSlider('labScore_t2_co3','labScore_t2_co3_slider',7.5); calcLabModalScores()" class="w-14 bg-slate-50 border border-slate-200 rounded px-1.5 py-0.5 text-xs text-slate-800 text-center focus:border-purple-500 outline-none" placeholder="0.0">
                </div>
                <input type="range" id="labScore_t2_co3_slider" tabindex="-1" min="0" max="7.5" step="0.5" value="0" oninput="document.getElementById('labScore_t2_co3').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-purple-500 bg-slate-800 cursor-pointer">
              </div>
              <!-- CO4 -->
              <div class="bg-white/40 p-3 rounded-lg border border-slate-850/50 space-y-2">
                <div class="flex justify-between items-center text-xs font-bold">
                  <span class="text-slate-700">CO4 (Max 7.5)</span>
                  <input type="number" step="0.5" min="0" max="7.5" id="labScore_t2_co4" onfocus="this.select()" oninput="syncSlider('labScore_t2_co4','labScore_t2_co4_slider',7.5); calcLabModalScores()" class="w-14 bg-slate-50 border border-slate-200 rounded px-1.5 py-0.5 text-xs text-slate-800 text-center focus:border-purple-500 outline-none" placeholder="0.0">
                </div>
                <input type="range" id="labScore_t2_co4_slider" tabindex="-1" min="0" max="7.5" step="0.5" value="0" oninput="document.getElementById('labScore_t2_co4').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-purple-500 bg-slate-800 cursor-pointer">
              </div>
            </div>
          </div>
        </div>

        <!-- TAB: PROJECT & ATTENDANCE -->
        <div id="labModalTab_project" class="space-y-4 hidden">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Project Card -->
            <div class="bg-slate-50/30 border border-slate-200/40 p-4 rounded-xl space-y-4">
              <h4 class="text-xs font-black text-amber-700 border-b border-slate-200 pb-2 uppercase tracking-widest">Micro-Project / Open-Ended (CO5)</h4>
              <div>
                <label class="text-xs text-slate-500 block mb-1 font-bold">Project Topic</label>
                <input type="text" id="labScore_projectTopic" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs text-slate-800 focus:border-amber-500 outline-none" placeholder="Enter assigned project title...">
              </div>
              <div class="bg-white/40 p-3 rounded-lg border border-slate-850/50 space-y-2">
                <div class="flex justify-between items-center text-xs font-bold">
                  <span class="text-slate-700">Project Mark (Max 7.5)</span>
                  <input type="number" step="0.1" min="0" max="7.5" id="labScore_projectMark" onfocus="this.select()" oninput="syncSlider('labScore_projectMark','labScore_projectMark_slider',7.5); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded px-2 py-1 text-base font-normal text-slate-800 text-center focus:border-slate-500 outline-none">
                </div>
                <input type="range" id="labScore_projectMark_slider" tabindex="-1" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById('labScore_projectMark').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-slate-400 bg-slate-800 cursor-pointer">
              </div>
            </div>
            <div class="bg-slate-50/30 border border-slate-200/40 p-4 rounded-xl space-y-4 flex flex-col justify-between">
              <div>
                <h4 class="text-xs font-black text-slate-350 border-b border-slate-200 pb-2 uppercase tracking-widest mb-3">Attendance Scoring</h4>
                <div class="flex justify-between items-center mb-3">
                  <span class="text-xs text-slate-500 font-bold">Class Attendance Percentage:</span>
                  <span class="text-xs font-black text-slate-900 font-mono" id="labModalStudentAttPct">0%</span>
                </div>
              </div>
              <div class="bg-white/40 p-3 rounded-lg border border-slate-850/50 space-y-2">
                <div class="flex justify-between items-center text-xs font-bold">
                  <span class="text-slate-700">Attendance Mark (Max 15)</span>
                  <input type="number" step="0.1" min="0" max="15" id="labScore_attendanceMark" onfocus="this.select()" oninput="syncSlider('labScore_attendanceMark','labScore_attendanceMark_slider',15); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded px-2 py-1 text-base font-normal text-slate-800 text-center focus:border-slate-500 outline-none">
                </div>
                <input type="range" id="labScore_attendanceMark_slider" tabindex="-1" min="0" max="15" step="0.1" value="0" oninput="document.getElementById('labScore_attendanceMark').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-slate-400 bg-slate-800 cursor-pointer">
              </div>
            </div>
          </div>
        </div>

        <!-- TAB: BOARD EXAM -->
        <div id="labModalTab_board" class="space-y-4 hidden">
          <div class="bg-slate-50/30 border border-slate-200/40 p-4 rounded-xl space-y-4 max-w-md mx-auto">
            <h4 class="text-xs font-black text-slate-350 border-b border-slate-200 pb-2 uppercase tracking-widest">External Board Examination</h4>
            <div class="bg-white/40 p-3 rounded-lg border border-slate-850/50 space-y-2">
              <div class="flex justify-between items-center text-xs font-bold">
                <span class="text-slate-700">Board Exam Mark (Max 50)</span>
                <input type="number" step="0.5" min="0" max="50" id="labScore_boardExam" onfocus="this.select()" oninput="syncSlider('labScore_boardExam','labScore_boardExam_slider',50); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded px-2 py-1 text-base font-normal text-slate-800 text-center focus:border-slate-500 outline-none" placeholder="0.0">
              </div>
              <input type="range" id="labScore_boardExam_slider" tabindex="-1" min="0" max="50" step="0.5" value="0" oninput="document.getElementById('labScore_boardExam').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-slate-400 bg-slate-800 cursor-pointer">
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom bar -->
      <div class="px-6 py-4 bg-slate-50/60 border-t border-slate-200 flex justify-between items-center">
        <div class="text-xs text-slate-500 font-bold flex gap-4">
          <div>Lab Work Avg: <span class="text-slate-800 font-mono" id="labModalLabelExp">0.0</span></div>
          <div>Model Test: <span class="text-slate-800 font-mono" id="labModalLabelTest">0.0</span></div>
          <div>Internal CA: <span class="text-emerald-700 font-bold font-mono text-base sm:text-lg" id="labModalLabelInternals">0 / 75</span></div>
        </div>
        <div class="flex items-center gap-2">
          <button type="button" onclick="closeStudentLabModal()" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-700 rounded-xl text-xs font-bold transition-premium cursor-pointer">
            Close
          </button>
          <button type="button" id="btnSaveStudentLabEval" onclick="saveStudentLabEvaluation()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-lg shadow-blue-500/10">
            <span class="material-symbols-rounded text-sm">save</span> Save Evaluation
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Manage Experiments Modal -->
  <div id="manageExperimentsModal" onclick="if(event.target === this) closeManageExperimentsModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-6xl max-h-[90vh] flex flex-col overflow-hidden shadow-2xl">
      <!-- Modal Header (Stable/Fixed) -->
      <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center shrink-0">
        <div>
          <h3 class="text-base font-black text-slate-900">Experiments List</h3>
          <p class="text-xs text-slate-500 mt-0.5">Setup the experiments syllabus for day-to-day continuous evaluation.</p>
        </div>
        <button onclick="closeManageExperimentsModal()" class="text-slate-400 hover:text-slate-700 transition-premium cursor-pointer">
          <span class="material-symbols-rounded">close</span>
        </button>
      </div>

      <!-- Add Experiment Form (Stable/Fixed at top, Single-row on desktop) -->
      <div class="p-5 bg-white/40 border-b border-slate-200/80 shrink-0">
        <form onsubmit="savePracticalExperiment(event)" class="bg-slate-50/80 border border-slate-200 p-3.5 rounded-xl">
          <input type="hidden" id="expEditId">
          <div class="flex flex-col md:flex-row items-end gap-3">
            <div class="w-full md:w-20 shrink-0">
              <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Exp No.</label>
              <input type="text" id="expFormNo" required maxlength="2" pattern="[0-9]{1,2}" inputmode="numeric" placeholder="01" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2)" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-2 text-sm font-bold text-slate-800 focus:border-blue-500 outline-none text-center font-mono">
            </div>
            <div class="w-full flex-1 min-w-0">
              <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Experiment Title / Objective</label>
              <input type="text" id="expFormTitle" required placeholder="Enter experiment title or detailed objective..." class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-normal text-slate-800 focus:border-blue-500 outline-none">
            </div>
            <div class="w-full md:w-28 shrink-0">
              <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Map CO</label>
              <select id="expFormCo" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-normal text-slate-800 focus:border-blue-500 outline-none cursor-pointer">
                <option value="CO1">CO1</option>
                <option value="CO2">CO2</option>
                <option value="CO3">CO3</option>
                <option value="CO4">CO4</option>
              </select>
            </div>
            <div class="w-full md:w-auto shrink-0 flex items-center gap-2">
              <button type="submit" id="btnSaveExp" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold transition-premium flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap shadow shadow-blue-600/20">
                <span class="material-symbols-rounded text-sm" id="btnSaveExpIcon">add</span>
                <span id="btnSaveExpLabel">Add Experiment</span>
              </button>
              <button type="button" id="btnCancelExpEdit" onclick="cancelExperimentEdit()" class="hidden px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-700 hover:text-white rounded-lg text-xs font-bold transition-premium cursor-pointer whitespace-nowrap">
                Cancel
              </button>
              <button type="button" id="btnImportDatabank" onclick="importFromDatabank()" class="hidden px-3.5 py-2 bg-amber-600/10 hover:bg-amber-600 border border-amber-500/20 hover:border-amber-500 text-amber-700 hover:text-white rounded-lg text-xs font-bold transition-premium flex items-center gap-1 cursor-pointer whitespace-nowrap">
                <span class="material-symbols-rounded text-sm">database</span> Import
              </button>
            </div>
          </div>
          <div id="expSaveFeedbackDashboard" class="hidden mt-2.5 px-3 py-1.5 rounded-lg text-xs font-bold border"></div>
        </form>
      </div>

      <!-- Experiments List Table (Dedicated Scroll Container) -->
      <div id="manageExpsTableScrollContainer" class="p-6 flex-1 overflow-y-auto min-h-0">
        <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50/20 shadow-inner">
          <table class="w-full text-left border-collapse text-xs">
            <thead class="sticky top-0 bg-white border-b border-slate-200 text-slate-500 font-bold uppercase z-10 shadow-sm">
              <tr>
                <th class="p-3 w-16 text-center">No.</th>
                <th class="p-3">Title / Objective</th>
                <th class="p-3 w-20 text-center">CO</th>
                <th class="p-3 w-28 text-center">Actions</th>
              </tr>
            </thead>
            <tbody id="manageExpsTableBody" class="divide-y divide-slate-850">
              <tr>
                <td colspan="4" class="p-6 text-center text-slate-500">No experiments set up yet.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Completed Experiments Details Modal -->
  <div id="completedExperimentsModal" onclick="if(event.target === this) closeCompletedExperimentsModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-3 sm:p-5">
    <div id="completedExperimentsModalDialog" class="bg-white border border-slate-200 rounded-2xl w-full max-w-[98vw] xl:max-w-[1700px] h-[95vh] max-h-[95vh] flex flex-col overflow-hidden shadow-2xl transition-all">
      <!-- Modal Header -->
      <div class="px-5 py-3.5 bg-slate-50/90 border-b border-slate-200 flex justify-between items-center shrink-0">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-teal-500/20 border border-teal-500/30 flex items-center justify-center text-teal-400 shrink-0">
            <span class="material-symbols-rounded text-xl">biotech</span>
          </div>
          <div>
            <div class="flex items-center gap-2">
              <h3 class="text-sm sm:text-base font-black text-slate-900 leading-tight">Completed Practical Experiments &amp; Sessions</h3>
              <span class="px-2 py-0.5 rounded bg-teal-500/20 border border-teal-500/30 text-teal-300 text-[10px] font-bold uppercase tracking-wider hidden sm:inline">Workspace</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-0.5 leading-tight" id="completedExpsModalSubtitle">Classroom &bull; Normalized Timetable Continuous Sessions</p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <button type="button" onclick="printCompletedExperimentsLog()" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-lg transition flex items-center gap-1.5 cursor-pointer shadow shadow-blue-500/20">
            <span class="material-symbols-rounded text-sm">print</span>
            <span>Print Report</span>
          </button>
          <button type="button" onclick="toggleCompletedExperimentsFullscreen()" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-700 hover:text-white text-xs font-semibold rounded-lg border border-slate-700/80 transition flex items-center gap-1.5 cursor-pointer" title="Toggle Fullscreen">
            <span class="material-symbols-rounded text-base" id="completedExpsFullscreenIcon">fullscreen</span>
            <span class="hidden sm:inline">Fullscreen</span>
          </button>
          <button type="button" onclick="closeCompletedExperimentsModal()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 active:bg-slate-600 text-slate-800 hover:text-white text-xs font-bold rounded-lg border border-slate-700 transition flex items-center gap-1.5 cursor-pointer shadow-sm" title="Close">
            <span class="material-symbols-rounded text-sm">close</span>
            <span>Close</span>
          </button>
        </div>
      </div>

      <!-- KPI Overview Cards & Table -->
      <div class="p-4 sm:p-5 overflow-y-auto space-y-4 flex-grow custom-scrollbar">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
          <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded-xl flex items-center justify-between shadow-sm">
            <div>
              <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Syllabus Experiments</span>
              <span class="text-2xl font-mono font-bold text-white mt-1 block" id="kpiTotalSyllabusExps">0</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-800/70 border border-slate-700/60 flex items-center justify-center text-slate-700">
              <span class="material-symbols-rounded text-xl">menu_book</span>
            </div>
          </div>
          <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded-xl flex items-center justify-between shadow-sm">
            <div>
              <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Sessions Conducted</span>
              <span class="text-2xl font-mono font-bold text-indigo-300 mt-1 block" id="kpiCompletedExpsCount">0</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-700">
              <span class="material-symbols-rounded text-xl">task_alt</span>
            </div>
          </div>
          <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded-xl flex items-center justify-between shadow-sm">
            <div>
              <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Actual Lab Hours</span>
              <span class="text-2xl font-mono font-bold text-white mt-1 block" id="kpiActualLabHours">0 hrs</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-800/70 border border-slate-700/60 flex items-center justify-center text-slate-700">
              <span class="material-symbols-rounded text-xl">schedule</span>
            </div>
          </div>
          <div class="p-3.5 bg-slate-50/70 border border-slate-200/80 rounded-xl flex items-center justify-between shadow-sm">
            <div>
              <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Syllabus Coverage</span>
              <span class="text-2xl font-mono font-bold text-emerald-700 mt-1 block" id="kpiCoveragePercent">0%</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-700">
              <span class="material-symbols-rounded text-xl">verified</span>
            </div>
          </div>
        </div>

        <!-- Completed Experiments Table -->
        <div class="bg-slate-50/80 border border-slate-200 rounded-xl overflow-hidden shadow-inner">
          <div class="px-4 py-3 bg-white/90 border-b border-slate-200/80 flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center gap-2.5">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                <span class="material-symbols-rounded text-sm text-teal-400">task_alt</span> Completed Experiments Log Details
              </span>
              <span class="text-[11px] font-mono text-slate-500" id="completedExpsTableCounter">Showing 0 completed session records</span>
            </div>
            <!-- Cohort Filter Tabs -->
            <div class="inline-flex rounded-lg p-0.5 bg-slate-50 border border-slate-200 text-[11px]">
              <button type="button" onclick="filterDashboardCompletedExpsCohort('all')" id="dashCohortTab_all" class="px-2.5 py-1 rounded-md font-bold transition bg-indigo-600 text-white cursor-pointer">All</button>
              <button type="button" onclick="filterDashboardCompletedExpsCohort('1')" id="dashCohortTab_1" class="px-2.5 py-1 rounded-md font-semibold transition text-slate-500 hover:text-slate-800 cursor-pointer">Batch 1</button>
              <button type="button" onclick="filterDashboardCompletedExpsCohort('2')" id="dashCohortTab_2" class="px-2.5 py-1 rounded-md font-semibold transition text-slate-500 hover:text-slate-800 cursor-pointer">Batch 2</button>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="border-b border-slate-200 text-slate-500 font-semibold uppercase text-[10px] bg-white/60 whitespace-nowrap">
                  <th class="p-2.5 w-10 text-center">#</th>
                  <th class="p-2.5 w-20 text-center">Exp No</th>
                  <th class="p-2.5">Title / Topics Covered</th>
                  <th class="p-2.5 text-center w-28">Date</th>
                  <th class="p-2.5 text-center w-28">Hours (Periods)</th>
                  <th class="p-2.5 text-center w-24">Batch</th>
                  <th class="p-2.5 text-center w-28">Attendance (%)</th>
                  <th class="p-2.5 text-center w-20">Absent</th>
                  <th class="p-2.5 text-center w-40">Absent Roll Nos</th>
                </tr>
              </thead>
              <tbody id="completedExperimentsTableBody" class="divide-y divide-slate-100 text-xs">
                <tr>
                  <td colspan="9" class="p-6 text-center text-slate-500">No completed experiments recorded yet.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="px-5 py-2.5 bg-slate-50/90 border-t border-slate-200 flex justify-between items-center shrink-0">
        <span class="text-xs text-slate-500 font-mono hidden sm:inline">Press ESC or click outside to dismiss</span>
        <button type="button" onclick="closeCompletedExperimentsModal()" class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 active:bg-slate-600 text-slate-800 hover:text-white text-xs font-bold rounded-lg border border-slate-700 transition flex items-center gap-1.5 cursor-pointer shadow-sm ml-auto">
          <span class="material-symbols-rounded text-sm">close</span>
          <span>Close</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Edit Experiment Date Modal -->
  <div id="editExpDateModal" onclick="if(event.target === this) closeEditExpDateModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-md overflow-hidden shadow-2xl flex flex-col">
      <!-- Modal Header -->
      <div class="px-5 py-4 bg-slate-50/70 border-b border-slate-200 flex justify-between items-center">
        <div class="flex items-center gap-2">
          <span class="material-symbols-rounded text-teal-400 text-xl">edit_calendar</span>
          <h3 class="text-sm font-bold text-white">Edit Experiment Date</h3>
        </div>
        <button onclick="closeEditExpDateModal()" class="text-slate-400 hover:text-slate-700 transition-premium cursor-pointer p-1">
          <span class="material-symbols-rounded text-lg">close</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-5 space-y-4">
        <!-- Exp Info -->
        <div class="p-3 bg-slate-50/80 border border-slate-200 rounded-xl space-y-2.5">
          <div class="flex items-center gap-2">
            <div class="flex items-center gap-1 bg-white border border-slate-200 text-slate-800/80 rounded-lg px-2 py-1 shadow-inner">
              <span class="text-[11px] font-bold text-slate-500 uppercase">Exp No:</span>
              <input type="text" id="editModalExpNoInput" class="w-12 bg-transparent text-xs font-mono font-bold text-teal-300 outline-none text-center" title="Edit Experiment Number (e.g. 1, 2, 2A)">
            </div>
            <span id="editModalExpTitle" class="text-xs font-semibold text-slate-800 line-clamp-1 flex-1">Experiment Title</span>
          </div>
          <div class="text-[11px] text-slate-500 flex items-center justify-between pt-1 border-t border-slate-200">
            <span>Currently Recorded Date:</span>
            <span id="editModalCurrentDate" class="font-mono text-amber-700 font-bold">—</span>
          </div>
        </div>

        <!-- New Date Input -->
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center gap-1">
            <span class="material-symbols-rounded text-sm text-teal-400">calendar_month</span> New Conducted Date
          </label>
          <input type="date" id="editModalNewDateInput" class="w-full px-3 py-2 bg-slate-50 border border-slate-700/80 rounded-xl text-white font-mono text-sm [color-scheme:dark] focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
        </div>

        <!-- Update Scope -->
        <div class="space-y-2">
          <label class="block text-xs font-semibold text-slate-700">Application Scope</label>
          <div class="grid grid-cols-1 gap-2">
            <label class="flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-slate-50/40 hover:bg-slate-50/80 cursor-pointer transition">
              <input type="radio" name="editExpDateScope" value="universal" checked class="mt-0.5 text-teal-500 focus:ring-teal-500">
              <div>
                <span class="text-xs font-bold text-white block">Universal (All Batches &amp; Master Syllabus)</span>
                <span class="text-[10px] text-slate-500 block">Fix this experiment's date across all batches, syllabus master, and evaluations.</span>
              </div>
            </label>
            <label class="flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-slate-50/40 hover:bg-slate-50/80 cursor-pointer transition">
              <input type="radio" name="editExpDateScope" value="batch" class="mt-0.5 text-teal-500 focus:ring-teal-500">
              <div>
                <span class="text-xs font-bold text-white block" id="editModalBatchLabel">This Batch Only</span>
                <span class="text-[10px] text-slate-500 block">Update evaluation logs and student attendance specifically for this lab batch.</span>
              </div>
            </label>
          </div>
        </div>

        <!-- Attendance Sync Checkbox -->
        <div class="pt-1">
          <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" id="editModalSyncAttendance" checked class="rounded border-slate-700 text-teal-500 focus:ring-teal-500">
            <span class="text-xs text-slate-700">Synchronize student lab attendance records to this new date</span>
          </label>
          <p class="text-[10px] text-slate-500 ml-5 mt-0.5">Moves class log continuous sessions (3 hrs) &amp; student attendance from old date to new date.</p>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="px-5 py-3.5 bg-slate-50/70 border-t border-slate-200 flex justify-end items-center gap-2.5">
        <button type="button" onclick="closeEditExpDateModal()" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-700 text-xs font-semibold rounded-lg transition cursor-pointer">
          Cancel
        </button>
        <button type="button" id="btnConfirmEditExpDate" onclick="submitEditExpDate()" class="px-4 py-1.5 bg-teal-600 hover:bg-teal-500 text-white text-xs font-bold rounded-lg transition flex items-center gap-1.5 cursor-pointer shadow-sm shadow-teal-500/20">
          <span class="material-symbols-rounded text-sm">check</span>
          <span>Save &amp; Update</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Manage Tests Modal -->
  <div id="manageTestsModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-[95vw] lg:max-w-7xl max-h-[92vh] flex flex-col overflow-hidden shadow-2xl">
      <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-200 flex justify-between items-center">
        <div>
          <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
            <span class="material-symbols-rounded text-blue-700">quiz</span> Configure Practical Series Exam Questions &amp; Scheme
          </h3>
          <p class="text-xs text-slate-500 mt-0.5">Edit questions, CO selection, cognitive levels (BT), and rubrics for 15-Mark Practical Series Examinations.</p>
        </div>
        <button onclick="closeManageTestsModal()" class="text-slate-400 hover:text-slate-700 transition-premium cursor-pointer">
          <span class="material-symbols-rounded">close</span>
        </button>
      </div>

      <form onsubmit="savePracticalTestQuestions(event)" class="flex-grow flex flex-col overflow-hidden">
        <div class="p-6 overflow-y-auto space-y-6 flex-grow scrollbar-thin">
          
          <!-- Series Exam Selector & Print Controls -->
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-slate-50/60 p-4 rounded-xl border border-slate-200">
            <div class="w-full sm:w-auto flex-grow max-w-md">
              <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block mb-1">Select Practical Model Test</label>
              <select id="designTestName" onchange="renderTestQuestionsFields()" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-bold text-white focus:border-blue-500 outline-none cursor-pointer">
                <option value="Test 1">Series Exam 1 (CO1 &amp; CO2 — 15 Marks Total)</option>
                <option value="Test 2">Series Exam 2 (CO3 &amp; CO4 — 15 Marks Total)</option>
              </select>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end pt-2 sm:pt-0">
              <button type="button" onclick="printPracticalTestPaper()" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-sky-300 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow">
                <span class="material-symbols-rounded text-sm text-sky-400">print</span> Print Question Paper
              </button>
              <button type="button" onclick="printPracticalTestScheme()" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-purple-300 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow">
                <span class="material-symbols-rounded text-sm text-purple-400">fact_check</span> Print Scheme &amp; Rubrics
              </button>
            </div>
          </div>

          <!-- Question Form Fields (2 COs x 2 Questions = 4 Questions per test) -->
          <div id="testQuestionsFieldsContainer" class="space-y-6">
            <!-- Inputs generated dynamically -->
          </div>
        </div>

        <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-200 flex justify-between items-center">
          <span class="text-xs text-slate-500 font-medium">Total Exam Duration: <strong class="text-white">1 Hour</strong> | Total Marks: <strong class="text-white">15 Marks (2 COs × 7.5 Marks)</strong></span>
          <div class="flex items-center gap-2">
            <button type="button" onclick="closeManageTestsModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer">Cancel</button>
            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-md">
              <span class="material-symbols-rounded text-sm">save</span> Save Test Scheme
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Generate Lesson Planner Modal -->
  <div id="generatePlannerModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
      <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
        <h3 class="text-base font-black text-slate-900">Generate Practical Lesson Plan</h3>
        <button onclick="closeGeneratePlannerModal()" class="text-slate-400 hover:text-slate-700 transition-premium cursor-pointer">
          <span class="material-symbols-rounded">close</span>
        </button>
      </div>
      <form onsubmit="generatePlannerFromExperiments(event)" class="p-6 space-y-4">
        <div>
          <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Batch Mode</label>
          <select id="genPlannerBatchMode" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-bold text-white focus:border-blue-500 outline-none cursor-pointer">
            <option value="Full">Full Batch (15 sessions of 3 hrs = 45 hrs)</option>
            <option value="Split">Split Batch (Batch 1 &amp; 2 - 30 sessions of 3 hrs)</option>
          </select>
        </div>
        <div>
          <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Allocated Hours per Session</label>
          <input type="number" id="genPlannerHours" value="3" min="1" max="10" required class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-bold text-white focus:border-blue-500 outline-none">
        </div>
        <div class="pt-2 flex justify-end gap-2">
          <button type="button" onclick="closeGeneratePlannerModal()" class="px-4 py-2 bg-slate-850 hover:bg-slate-800 text-slate-500 hover:text-slate-350 rounded-xl text-xs font-bold transition-premium cursor-pointer">Cancel</button>
          <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer">Generate</button>
        </div>
      </form>
    </div>
  </div>

  @include('partials.support_desk_overlay')
  @include('partials.staff_birthday_modal')
  @include('partials.lab_batch_setup_modal')