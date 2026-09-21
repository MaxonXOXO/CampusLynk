                </div>
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900">Extra-Curricular Claims</h4>
                <p class="text-xs text-slate-500 leading-relaxed mt-1">
                  Audit student extracurricular activity point claims.
                </p>
              </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
              <span class="text-xs font-medium text-slate-400">Activity Points Audit</span>
              <button type="button" onclick="openActivityPointsModal()" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-xs rounded-xl shadow-xs transition-all duration-200 flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                <span>View Claims</span>
              </button>
            </div>
          </div>

          <!-- Card 5: Department Course Files -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between space-y-4">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                  <i data-lucide="folder-check" class="w-5 h-5 text-emerald-600"></i>
                </div>
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900">Department Course Files</h4>
                <p class="text-xs text-slate-500 leading-relaxed mt-1">
                  Review course-file preparation and compliance.
                </p>
              </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
              <span class="text-xs font-medium text-slate-400">Curriculum Compliance</span>
              <button type="button" onclick="openCourseFilesModal()" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-xs rounded-xl shadow-xs transition-all duration-200 flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                <span>Check Status</span>
              </button>
            </div>
          </div>

          <!-- Card 6: Student Mentoring Diaries -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between space-y-4">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                  <i data-lucide="notebook" class="w-5 h-5 text-indigo-600"></i>
                </div>
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900">Student Mentoring Diaries</h4>
                <p class="text-xs text-slate-500 leading-relaxed mt-1">
                  Student cumulative mentoring dossiers, tutor consultation notes, family profiles, and academic progression.
                </p>
              </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
              <span class="text-xs font-medium text-slate-400">Dossier &amp; Notes</span>
              <button type="button" onclick="handleHodSidebarNav('batches')" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 font-medium text-xs border border-slate-200 rounded-xl shadow-2xs transition-all duration-200 flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="users" class="w-3.5 h-3.5"></i>
                <span>Access Logs</span>
              </button>
            </div>
          </div>

          <!-- Card 7: SBTE Annual Compliance Audit -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between space-y-4">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center border border-cyan-100">
                  <i data-lucide="shield-check" class="w-5 h-5 text-cyan-600"></i>
                </div>
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900">SBTE Annual Audit (Part C)</h4>
                <p class="text-xs text-slate-500 leading-relaxed mt-1">
                  Open the departmental SBTE annual compliance workspace.
                </p>
              </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
              <span class="text-xs font-medium text-slate-400">SBTE Accreditation</span>
              <a href="/hod/sbte-audit" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-xs rounded-xl shadow-xs transition-all duration-200 flex items-center gap-1.5 no-underline">
                <i data-lucide="file-check" class="w-3.5 h-3.5"></i>
                <span>View Console</span>
              </a>
            </div>
          </div>

          <!-- Card 8: NBA Criteria Accreditation -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between space-y-4">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100">
                  <i data-lucide="award" class="w-5 h-5 text-rose-600"></i>
                </div>
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900">NBA Criteria Accreditation</h4>
                <p class="text-xs text-slate-500 leading-relaxed mt-1">
                  Manage accreditation documents across NBA criteria.
                </p>
              </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
              <span class="text-xs font-medium text-slate-400">Accreditation Files</span>
              <a href="/hod/nba-audit" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-xs rounded-xl shadow-xs transition-all duration-200 flex items-center gap-1.5 no-underline">
                <i data-lucide="folder" class="w-3.5 h-3.5"></i>
                <span>View Console</span>
              </a>
            </div>
          </div>

          <!-- Card 9: Academic Calendar Planner -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between space-y-4">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                  <i data-lucide="calendar-range" class="w-5 h-5 text-amber-600"></i>
                </div>
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900">Academic Calendar Planner</h4>
                <p class="text-xs text-slate-500 leading-relaxed mt-1">
                  Plan and manage the departmental academic calendar.
                </p>
              </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
              <span class="text-xs font-medium text-slate-400">Academic Planning</span>
              <a href="/hod/academic-calendar" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-xs rounded-xl shadow-xs transition-all duration-200 flex items-center gap-1.5 no-underline">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                <span>Open Planner</span>
              </a>
            </div>
          </div>

          <!-- Card 10: Security & Operations Audit Trail -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between space-y-4">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center border border-violet-100">
                  <i data-lucide="shield" class="w-5 h-5 text-violet-600"></i>
                </div>
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900">Security &amp; Operations Audit</h4>
                <p class="text-xs text-slate-500 leading-relaxed mt-1">
                  Review departmental security and administrative audit events.
                </p>
              </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
              <span class="text-xs font-medium text-slate-400">Audit History</span>
              <button type="button" onclick="handleHodSidebarNav('audit')" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-xs rounded-xl shadow-xs transition-all duration-200 flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                <span>Extract Logs</span>
              </button>
            </div>
          </div>

          <!-- Card 11: Staff Leave Master Ledger -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between space-y-4">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                  <i data-lucide="calendar-days" class="w-5 h-5 text-emerald-600"></i>
                </div>
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900">Staff Leave Master Ledger</h4>
                <p class="text-xs text-slate-500 leading-relaxed mt-1">
                  Review the staff leave ledger and approval records.
                </p>
              </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
              <span class="text-xs font-medium text-slate-400">Printable Ledger</span>
              <button type="button" onclick="handleHodSidebarNav('leave_ledger')" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-xs rounded-xl shadow-xs transition-all duration-200 flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="calendar-check-2" class="w-3.5 h-3.5"></i>
                <span>Open Ledger</span>
              </button>
            </div>
          </div>

        </div>

      </div>

    </div>

  <!-- CREATE BATCH MODAL -->
  <div id="createBatchModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-5">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <i data-lucide="school" class="w-5 h-5 text-blue-600"></i>
          <span>Create New Batch</span>
        </h3>
        <button type="button" onclick="closeCreateBatchModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <div class="space-y-4">
        <!-- Admission Year -->
        <div id="batchAdmYearContainer">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Admission Year</label>
          <input type="number" id="batchAdmYear" min="2000" max="2100" value="2026"
            oninput="updateBatchPreview()"
            class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
        </div>

        <!-- Batch Type -->
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Batch Type</label>
          <select id="batchTypeSelect" onchange="toggleBatchCreationLetView(); updateBatchPreview();" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none cursor-pointer">
            <option value="Regular" selected>Regular (Default 3-Year Batch)</option>
            <option value="LET">Lateral Entry (LET Batch - Copy Tutor/Mentor, Starts S3)</option>
          </select>
        </div>

        <!-- Preview -->
        <div class="bg-blue-50/60 border border-blue-100 rounded-xl p-3.5 flex items-center gap-3">
          <i data-lucide="info" class="w-4 h-4 text-blue-600 shrink-0"></i>
          <div>
            <p class="text-xs text-slate-600 font-medium">Classroom ID that will be created:</p>
            <p id="batchIdPreview" class="font-mono font-bold text-blue-700 text-sm">{{ session('userBranch') }}_2025_2028</p>
          </div>
        </div>

        <!-- Starting Semester -->
        <div id="batchStartSemesterContainer">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Starting Semester</label>
          <select id="batchStartSemesterSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
            <option value="1" selected>Semester 1 (S1)</option>
            <option value="2">Semester 2 (S2)</option>
            <option value="3">Semester 3 (S3)</option>
            <option value="4">Semester 4 (S4)</option>
            <option value="5">Semester 5 (S5)</option>
            <option value="6">Semester 6 (S6)</option>
          </select>
        </div>

        <!-- Optional Tutor -->
        <div id="batchTutorContainer">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Assign Tutor (Optional)</label>
          <select id="batchTutorSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
            <option value=""> Select Tutor (optional) </option>
          </select>
        </div>

        <!-- Optional Mentor -->
        <div id="batchMentorContainer">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Assign Mentor (Optional)</label>
          <select id="batchMentorSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
            <option value="">Select Mentor (optional) </option>
          </select>
        </div>
      </div>

      <div id="createBatchAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

      <div class="flex gap-3 pt-2">
        <button type="button" onclick="closeCreateBatchModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-sm text-slate-700 transition-colors cursor-pointer">Cancel</button>
        <button type="button" onclick="submitCreateBatch()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-colors cursor-pointer flex items-center justify-center gap-2 shadow-xs">
          <span>Create Batch</span>
          <div id="createBatchSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
        </button>
      </div>
    </div>
  </div>
      </div>
    </div>
  </div>

  <!-- BATCH DETAIL MODAL -->
  <div id="batchDetailModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-7xl shadow-2xl flex flex-col max-h-[95vh]">
      <!-- Modal Header -->
      <div class="flex justify-between items-center border-b border-slate-100 p-5 flex-shrink-0">
        <div>
          <h3 id="batchDetailTitle" class="font-bold text-slate-900 text-base">Batch Detail</h3>
          <p id="batchDetailSubtitle" class="text-xs text-slate-500 mt-0.5">Manage tutor, mentor, subjects, and enrolled students</p>
        </div>
        <div class="flex items-center gap-2">
          <!-- Graduate / Archive Batch button (NEW - purely additive) -->
          <button id="btnGraduateBatch" onclick="confirmGraduateBatch()" class="hidden px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-xl text-xs font-semibold transition-colors cursor-pointer flex items-center gap-1.5">
            <i data-lucide="graduation-cap" class="w-4 h-4"></i>
            <span>Graduate / Archive</span>
          </button>
          <!-- Delete Batch button -->
          <button id="btnDeleteBatch" onclick="confirmDeleteBatch()" class="hidden px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold transition-colors cursor-pointer flex items-center gap-1.5">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
            <span>Delete Batch</span>
          </button>
          <button type="button" onclick="closeBatchDetailModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>
      </div>

      <!-- Tabs Navigation -->
      <div class="flex border-b border-slate-100 px-5 pt-3 gap-6 overflow-x-auto">
         <button onclick="switchBatchTab('tutorMentor')" id="tabBtn_tutorMentor" class="pb-3 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 transition-colors cursor-pointer whitespace-nowrap">Tutor &amp; Mentor</button>
         <button onclick="switchBatchTab('timetable')" id="tabBtn_timetable" class="pb-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-colors cursor-pointer whitespace-nowrap">Time Table</button>
         <button onclick="switchBatchTab('semesterHistory')" id="tabBtn_semesterHistory" class="pb-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-colors cursor-pointer whitespace-nowrap">Semester History</button>
      </div>

      <div class="flex-grow overflow-y-auto p-5 relative">
        <!-- Tab: Tutor & Mentor -->
        <div id="batchTab_tutorMentor" class="block space-y-4">

        <!-- Assignment Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

          <!-- Tutor Card -->
          <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-3">
            <div class="flex items-center gap-2">
              <i data-lucide="user-check" class="w-4 h-4 text-blue-600"></i>
              <h4 class="font-bold text-slate-900 text-sm">Class Tutor</h4>
            </div>
            <div id="tutorCurrentDisplay" class="text-sm text-slate-600 font-medium">Not assigned</div>
            <div class="space-y-2">
              <select id="detailTutorSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
                <option value="">- None (Remove) -</option>
              </select>
              <button onclick="submitAssignTutor()" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-colors cursor-pointer flex items-center justify-center gap-1.5 shadow-xs">
                <span>Update Tutor</span>
                <div id="assignTutorSpinner" class="hidden w-3 h-3 border-2 border-blue-200 border-t-white rounded-full animate-spin"></div>
              </button>
            </div>
            <div id="assignTutorAlert" class="hidden p-2 rounded-lg text-sm font-bold border"></div>
          </div>

          <!-- Mentor Card -->
          <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-3">
            <div class="flex items-center gap-2">
              <i data-lucide="users" class="w-4 h-4 text-emerald-600"></i>
              <h4 class="font-bold text-slate-900 text-sm">Class Mentor</h4>
            </div>
            <div id="mentorCurrentDisplay" class="text-sm text-slate-600 font-medium">Not assigned</div>
            <div class="space-y-2">
              <select id="detailMentorSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
                <option value="">- None (Remove) -</option>
              </select>
              <button onclick="submitAssignMentor()" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-sm transition-colors cursor-pointer flex items-center justify-center gap-1.5 shadow-xs">
                <span>Update Mentor</span>
                <div id="assignMentorSpinner" class="hidden w-3 h-3 border-2 border-emerald-200 border-t-white rounded-full animate-spin"></div>
              </button>
            </div>
            <div id="assignMentorAlert" class="hidden p-2 rounded-lg text-sm font-bold border"></div>
          </div>
        </div>
        </div>

      <!-- Tab: Time Table -->
      <div id="batchTab_timetable" class="hidden space-y-4">
        <div class="flex justify-between items-center bg-slate-50 border border-slate-200 p-4 rounded-xl">
          <div>
            <h4 class="text-sm font-bold text-slate-900">Batch Weekly Timetable</h4>
            <p class="text-xs text-slate-500">Configure weekly lecture and lab hours. 3 periods forenoon, 3 periods afternoon.</p>
          </div>
          <div class="flex gap-2">
            <button onclick="printTimetable()" class="px-3.5 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl font-semibold text-xs transition-colors cursor-pointer flex items-center gap-1.5">
              <i data-lucide="printer" class="w-4 h-4"></i>
              <span>Print</span>
            </button>
            <button id="btnEditTimetable" onclick="toggleTimetableEdit(true)" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-xs transition-colors cursor-pointer flex items-center gap-1.5 shadow-xs">
              <i data-lucide="edit-3" class="w-4 h-4"></i>
              <span>Edit Timetable</span>
            </button>
            <button id="btnCancelTimetable" onclick="toggleTimetableEdit(false)" class="hidden px-3.5 py-2 border border-slate-200 hover:bg-slate-100 text-slate-700 rounded-xl font-semibold text-xs transition-colors cursor-pointer">
              Cancel
            </button>
            <button id="btnSaveTimetable" onclick="submitTimetable()" class="hidden px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-xs transition-colors cursor-pointer flex items-center gap-1.5 shadow-xs">
              <i data-lucide="save" class="w-4 h-4"></i>
              <span>Save Changes</span>
            </button>
          </div>
        </div>

        <!-- View Mode -->
        <div id="timetableDisplayArea" class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                <th class="p-3 text-center w-24">Day</th>
                <th class="p-3 text-center">Period 1<br><span class="text-[11px] text-slate-500 font-normal">09:00 - 10:00</span></th>
                <th class="p-3 text-center">Period 2<br><span class="text-[11px] text-slate-500 font-normal">10:00 - 11:00</span></th>
                <th class="p-3 text-center">Period 3<br><span class="text-[11px] text-slate-500 font-normal">11:10 - 12:10</span></th>
                <th class="p-3 text-center bg-slate-100/60 w-16 text-slate-500">Lunch</th>
                <th class="p-3 text-center">Period 4<br><span class="text-[11px] text-slate-500 font-normal">01:00 - 02:00</span></th>
                <th class="p-3 text-center">Period 5<br><span class="text-[11px] text-slate-500 font-normal">02:00 - 03:00</span></th>
                <th class="p-3 text-center">Period 6<br><span class="text-[11px] text-slate-500 font-normal">03:00 - 04:00</span></th>
              </tr>
            </thead>
            <tbody id="timetableDisplayBody">
              <!-- Rendered by JS -->
            </tbody>
          </table>
        </div>
      </div>
      </div> <!-- Close flex-grow container -->
    </div>
  </div>

        <!-- Edit Mode (Form Grid) -->
        <div id="timetableEditArea" class="hidden bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-x-auto">
          <table class="w-full text-left text-sm border-collapse min-w-[800px]">
            <thead>
              <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                <th class="p-3 text-center w-24">Day</th>
                <th class="p-3 text-center">Period 1</th>
                <th class="p-3 text-center">Period 2</th>
                <th class="p-3 text-center">Period 3</th>
                <th class="p-3 text-center bg-slate-900/20 w-16">Lunch</th>
                <th class="p-3 text-center">Period 4</th>
                <th class="p-3 text-center">Period 5</th>
                <th class="p-3 text-center">Period 6</th>
              </tr>
            </thead>
            <tbody id="timetableEditBody">
              <!-- Rendered by JS -->
            </tbody>
          </table>
        </div>
      </div>
      </div> <!-- Close flex-grow container -->
    </div>
  </div>

  <!-- ============================================================ -->
  <!-- NEW: SEMESTER HISTORY TAB PANEL (purely additive, no existing code changed) -->
  <!-- This panel is part of the batchDetailModal flex-grow area but rendered as a hidden sibling -->
  <!-- Note: Panel is injected via JS into the flex-grow container on tab switch -->
  <!-- ============================================================ -->

  <!-- PASSWORD RESET MODAL -->
  <div id="passwordModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <i data-lucide="key-round" class="w-5 h-5 text-blue-600"></i>
          <span>Password Reset</span>
        </h3>
        <button type="button" onclick="closePasswordModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
