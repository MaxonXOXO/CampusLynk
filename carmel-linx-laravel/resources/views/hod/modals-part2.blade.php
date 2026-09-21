      </div>

      <div class="space-y-3">
        <p class="text-sm text-slate-600">
          Set a new password for <span id="pwdResetName" class="font-bold text-slate-900"></span> (<span id="pwdResetId" class="text-blue-600 font-mono"></span>).
        </p>
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">New Password</label>
          <input type="text" id="newPasswordInput" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600" placeholder="Minimum 4 characters">
        </div>
      </div>

      <div id="pwdAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

      <div class="flex gap-3 pt-2">
        <button type="button" onclick="closePasswordModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-sm text-slate-700 transition-colors cursor-pointer">Cancel</button>
        <button type="button" onclick="submitPasswordReset()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-colors cursor-pointer">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- AUDIT LOG MODAL FOR SINGLE PROFILE -->
  <div id="auditModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-2xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <i data-lucide="file-text" class="w-5 h-5 text-blue-600"></i>
          <span>Profile Audit Trail</span>
        </h3>
        <button type="button" onclick="closeAuditModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <div class="space-y-3">
        <p class="text-sm text-slate-600">
          History log for <span id="auditProfileName" class="font-bold text-slate-900"></span> (<span id="auditProfileId" class="text-blue-600 font-mono"></span>).
        </p>

        <div class="max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-200 rounded-xl">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                <th class="p-3">Time</th>
                <th class="p-3">Actor</th>
                <th class="p-3">Action</th>
                <th class="p-3">Details</th>
              </tr>
            </thead>
            <tbody id="modalAuditTableBody">
              <!-- Rendered via JS -->
            </tbody>
          </table>
        </div>
      </div>

      <div class="flex pt-2">
        <button type="button" onclick="closeAuditModal()" class="w-full py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-sm text-slate-700 transition-colors cursor-pointer">Close Window</button>
      </div>
    </div>
  </div>

  <!-- DIRECT REGISTRATION MODAL -->
  <div id="registerModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <i data-lucide="user-plus" class="w-5 h-5 text-blue-600"></i>
          <span>Register New Profile</span>
        </h3>
        <button type="button" onclick="closeRegisterModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form id="directRegisterForm" onsubmit="handleDirectRegister(event)" class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-hidden">
        <!-- Type Selection -->
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">User Type</label>
          <select id="regType" onchange="toggleDirectRegisterFields(this.value)" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
            <option value="student">Student Profile</option>
            <option value="staff">Staff Profile</option>
          </select>
        </div>

        <!-- Common Fields -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Full Name</label>
            <input type="text" id="directRegName" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
            <input type="email" id="directRegEmail" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none" placeholder="name@carmelpoly.edu.in">
          </div>
        </div>

        <!-- Student-Specific Fields -->
        <div id="directStudentFields" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Admission Type</label>
              <select id="directRegAdmType" onchange="handleAdmTypeChange()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
                <option value="Regular">Regular</option>
                <option value="LET">Lateral Entry (LET)</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Adm Year</label>
              <input type="number" id="directRegStudentYear" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none" value="2026">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Register No</label>
              <input type="text" id="directRegStudentId" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none" placeholder="e.g. 25EL1001">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Admission No</label>
              <input type="text" id="directRegStudentAdm" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none" placeholder="e.g. ADM25EL01">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Branch</label>
              <input type="text" id="directRegStudentBranch" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-500 focus:outline-none cursor-not-allowed" value="{{ $activeBranch }}">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Semester</label>
              <select id="directRegStudentSem" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
                <option value="S1">S1</option>
                <option value="S2">S2</option>
                <option value="S3" selected>S3</option>
                <option value="S4">S4</option>
                <option value="S5">S5</option>
                <option value="S6">S6</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Staff-Specific Fields -->
        <div id="directStaffFields" class="space-y-4 hidden">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Mobile No (Login ID)</label>
              <input type="text" id="directRegStaffMobile" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none" placeholder="10-digit number">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Designation</label>
              <select id="directRegStaffDesig" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
                <option value="Lecturer" selected>Lecturer</option>
                <option value="Demonstrator">Demonstrator</option>
                <option value="Physical_Instructor">Physical Instructor</option>
                <option value="Trade_Instructor">Trade Instructor</option>
                <option value="Tradesman">Tradesman</option>
                <option value="Laboratory_Assistant">Laboratory Assistant</option>
                <option value="Workshop_Instructor">Workshop Instructor</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Branch</label>
            <input type="text" id="directRegStaffBranch" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-500 focus:outline-none cursor-not-allowed" value="{{ $activeBranch }}">
          </div>
        </div>

        <!-- Password -->
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Password</label>
          <input type="text" id="directRegPassword" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none" placeholder="e.g. 12345">
        </div>

        <div id="directRegAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRegisterModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-sm text-slate-700 transition-colors cursor-pointer">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-colors cursor-pointer flex items-center justify-center gap-2">
            <span>Register Profile</span>
            <div id="directRegSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>
  <!-- SUBJECT MODAL (Add + Edit mode) -->
  <div id="subjectModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 id="subjectModalTitle" class="font-bold text-slate-900 text-base flex items-center gap-2">
          <i data-lucide="book-open" class="w-5 h-5 text-emerald-600"></i>
          <span id="subjectModalTitleText">Add Curriculum Subject</span>
        </h3>
        <button type="button" onclick="closeSubjectModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form id="subjectForm" onsubmit="saveSubject(event)" class="space-y-4">
        <!-- Hidden: tracks which mode we are in. Empty = Add, filled = Edit (holds subject ID) -->
        <input type="hidden" id="modalEditSubjectId" value="">
        <input type="hidden" id="modalFormSubjectBatch">
        <input type="hidden" id="modalFormSubjectSemester">

        <div id="subjectBatchSemRow" class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl mb-2 flex justify-between items-center text-sm">
          <span class="text-slate-600 font-medium">Target Batch: <span id="displaySubjectBatch" class="font-bold text-slate-900"></span></span>
          <span class="text-slate-600 font-medium">Semester: <span id="displaySubjectSemester" class="font-bold text-slate-900"></span></span>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Subject Code</label>
            <div class="flex items-stretch rounded-xl overflow-hidden border border-slate-200 focus-within:border-blue-600 bg-white">
              <span id="subjectCodePrefix" class="hidden items-center px-3 bg-slate-100 text-blue-700 font-bold font-mono text-xs border-r border-slate-200 select-none whitespace-nowrap"></span>
              <input type="text" id="subjectCodeRaw" class="flex-1 bg-transparent px-3 py-2 text-sm text-slate-900 outline-none" placeholder="e.g. ENG101">
            </div>
            <!-- Keep hidden field to maintain integration with save handlers -->
            <input type="hidden" id="subjectCode">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Subject Type</label>
            <select id="subjectType" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
              <option value="Theory">Theory</option>
              <option value="Practical / Lab">Practical / Lab</option>
              <option value="Practicum">Practicum</option>
              <option value="Project Based Theory">Project Based Theory</option>
              <option value="Seminar">Seminar</option>
              <option value="Project">Project</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Subject Name</label>
          <input type="text" id="subjectName" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none" placeholder="e.g. Engineering Mathematics">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Syllabus Revision</label>
          <select id="subjectRevisionYear" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
            <option value="REV2026">REV2026 (Current)</option>
            <option value="REV2021">REV2021</option>
            <option value="REV2015">REV2015</option>
            <option value="REV2010">REV2010</option>
          </select>
        </div>

        <div id="subjectAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeSubjectModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-sm text-slate-700 transition-colors cursor-pointer">Cancel</button>
          <button type="submit" id="subjectSubmitBtn" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-sm transition-colors cursor-pointer flex items-center justify-center gap-2 shadow-xs">
            <span id="subjectSubmitLabel">Add Subject</span>
            <div id="subjectSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ASSIGN STAFF MODAL -->
  <div id="assignStaffModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <i data-lucide="user-plus" class="w-5 h-5 text-blue-600"></i>
          <span>Assign Teaching Staff</span>
        </h3>
        <button type="button" onclick="closeAssignStaffModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form id="assignStaffForm" onsubmit="assignStaff(event)" class="space-y-4">
        <input type="hidden" id="assignSubjectId">
        
        <p class="text-sm text-slate-600">Select one or more staff members to assign to <strong id="assignSubjectName" class="text-slate-900 font-bold"></strong>.</p>
        
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Branch Filter (For Inter-Department)</label>
          <select id="staffBranchFilter" onchange="renderAssignStaffList()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
            <option value="">All Branches</option>
            <option value="EL">Electronics (EL)</option>
            <option value="ME">Mechanical (ME)</option>
            <option value="CE">Civil (CE)</option>
            <option value="EEE">Electrical (EEE)</option>
            <option value="CT">Computer (CT)</option>
            <option value="AU">Automobile (AU)</option>
            <option value="GEN_AIDED">General (Aided)</option>
            <option value="GEN_SF">General (SF)</option>
          </select>
        </div>

        <div class="max-h-[300px] overflow-y-auto custom-scrollbar border border-slate-200 rounded-xl p-2 space-y-1" id="staffCheckboxList">
          <!-- Populated by JS -->
        </div>

        <div id="assignStaffAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeAssignStaffModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-sm text-slate-700 transition-colors cursor-pointer">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-colors cursor-pointer flex items-center justify-center gap-2 shadow-xs">
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ATTENDANCE MODAL -->
  <div id="attendanceModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-5">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-100">
            <i data-lucide="calendar-check" class="w-4 h-4 text-sky-600"></i>
          </div>
          <span>Attendance Summary &amp; Condonation</span>
        </h3>
        <button type="button" onclick="closeAttendanceModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <div class="space-y-4">
        <p class="text-sm text-slate-600 leading-relaxed">
          Select a semester batch to generate the consolidated class attendance summary, lesson plan coverage rates, and condonation candidate roster.
        </p>
        <div class="space-y-3">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Select Semester Batch</label>
            <select id="selectAttendanceBatch" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none cursor-pointer">
              @foreach($batches as $batch)
                <option value="{{ $batch->classroom_id }}">{{ $batch->classroom_id }} (Sem {{ $batch->current_semester }})</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Select Report Type</label>
            <select id="selectAttendanceReportType" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none cursor-pointer">
              <option value="coverage">Course Coverage Rates &amp; Hours Conducted</option>
              <option value="roster">Student Attendance Roster &amp; Deficiencies</option>
              <option value="condonation">Condonation Students List (SBTE No)</option>
            </select>
          </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
          <button type="button" onclick="closeAttendanceModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-50 rounded-xl font-medium transition-all text-slate-700 text-sm cursor-pointer">
            Cancel
          </button>
          <button type="button" onclick="printAttendanceSummary()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-xl font-medium shadow-sm transition-all flex items-center justify-center gap-2 text-sm cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Print Summary</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- REMEDIAL MODAL -->
  <div id="remedialModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-5">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100">
            <i data-lucide="heart-pulse" class="w-4 h-4 text-purple-600"></i>
          </div>
          <span>Remedial Coaching Analytics</span>
        </h3>
        <button type="button" onclick="closeRemedialModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <div class="space-y-4">
        <p class="text-sm text-slate-600 leading-relaxed">
          Select a semester batch to generate the consolidated Remedial Session Analytics, conducted hours, and registered slower learners list.
        </p>
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Select Semester Batch</label>
          <select id="selectRemedialBatch" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none cursor-pointer">
            @foreach($batches as $batch)
              <option value="{{ $batch->classroom_id }}">{{ $batch->classroom_id }} (Sem {{ $batch->current_semester }})</option>
            @endforeach
          </select>
        </div>

        <div class="flex items-center gap-3 pt-2">
          <button type="button" onclick="closeRemedialModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-50 rounded-xl font-medium transition-all text-slate-700 text-sm cursor-pointer">
            Cancel
          </button>
          <button type="button" onclick="printRemedialReport()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-xl font-medium shadow-sm transition-all flex items-center justify-center gap-2 text-sm cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Print Report</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- COURSE FILES MODAL -->
  <div id="courseFilesModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-5">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
            <i data-lucide="folder-check" class="w-4 h-4 text-emerald-600"></i>
          </div>
          <span>Course Files Compliance Status</span>
        </h3>
        <button type="button" onclick="closeCourseFilesModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <div class="space-y-4">
        <p class="text-sm text-slate-600 leading-relaxed">
          Select a semester batch to generate the consolidated syllabus registry, CO-PO mapping, and NBA Course File compliance status report.
        </p>
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Select Semester Batch</label>
          <select id="selectCourseFilesBatch" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none cursor-pointer">
            @foreach($batches as $batch)
              <option value="{{ $batch->classroom_id }}">{{ $batch->classroom_id }} (Sem {{ $batch->current_semester }})</option>
            @endforeach
          </select>
        </div>

        <div class="flex items-center gap-3 pt-2">
          <button type="button" onclick="closeCourseFilesModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-50 rounded-xl font-medium transition-all text-slate-700 text-sm cursor-pointer">
            Cancel
          </button>
          <button type="button" onclick="printCourseFilesReport()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-xl font-medium shadow-sm transition-all flex items-center justify-center gap-2 text-sm cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Print Report</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ACTIVITY POINTS MODAL -->
  <div id="activityPointsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-5">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100">
