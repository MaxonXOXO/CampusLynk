  <!-- MODALS & DRAWERS -->
  <!-- ========================================================================= -->

  @if(in_array(strtolower(session('userRole', '')), ['super_admin', 'superadmin', 'admin', 'principal']))
  <!-- 1. EDIT STAFF MODAL -->
  <div id="editStaffModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 sm:p-8 shadow-2xl space-y-5">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-600">edit</span>
          <span>Edit Staff Details</span>
        </h3>
        <button onclick="closeEditStaffModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><span class="material-symbols-rounded">close</span></button>
      </div>

      <form id="editStaffForm" onsubmit="submitStaffEdit(event)" class="space-y-4">
        <input type="hidden" id="editStaffMobile">
        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Full Name</label>
          <input type="text" id="editStaffName" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
          <input type="email" id="editStaffEmail" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Department Branch</label>
          <select id="editStaffBranch" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            <option value="EL">Electronics Engineering (EL)</option>
            <option value="ME">Mechanical Engineering (ME)</option>
            <option value="CE">Civil Engineering (CE)</option>
            <option value="EEE">Electrical &amp; Electronics Engineering (EEE)</option>
            <option value="CT">Computer Engineering (CT)</option>
            <option value="AU">Automobile Engineering (AU)</option>
            <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
            <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
            <option value="Admin">Administration</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Designation Role</label>
          <select id="editStaffDesig" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            <option value="Principal">Principal</option>
            <option value="HOD">Head of Department (HOD)</option>
            <option value="Academic_Coordinator">Academic Coordinator (Self-Financing)</option>
            <option value="Gen_Dept_Coordinator_Aided">Gen Dept Coordinator Aided</option>
            <option value="Gen_Dept_Coordinator_Self_Finance">Gen Dept Coordinator Self Finance</option>
            <option value="Lecturer">Lecturer</option>
            <option value="Demonstrator">Demonstrator</option>
            <option value="Physical_Instructor">Physical Instructor</option>
            <option value="Trade_Instructor">Trade Instructor</option>
            <option value="Tradesman">Tradesman</option>
            <option value="Laboratory_Assistant">Laboratory Assistant</option>
            <option value="Workshop_Instructor">Workshop Instructor</option>
            <option value="Workshop_Superintendent">Workshop Superintendent</option>
            @if(in_array(strtolower(session('userRole', '')), ['super_admin', 'superadmin']))
            <option value="Super_Admin">Super Admin</option>
            @endif
            <option value="Chairman">Chairman</option>
            <option value="Admin">Admin</option>
          </select>
        </div>

        <div id="editStaffAlert" class="hidden p-3 rounded-xl font-semibold border text-sm"></div>

        <div class="flex gap-3 pt-3 border-t border-slate-100">
          <button type="button" onclick="closeEditStaffModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-slate-700 transition-all text-sm">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all text-sm flex items-center justify-center gap-1.5 shadow-sm">
            <span>Save Details</span>
            <div id="editStaffSpinner" class="hidden w-4 h-4 border-2 border-slate-200 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- 2. PASSWORD RESET MODAL -->
  <div id="passwordModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-sm p-6 sm:p-8 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-600">lock_reset</span>
          <span>Password Reset</span>
        </h3>
        <button onclick="closePasswordModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><span class="material-symbols-rounded">close</span></button>
      </div>

      <p class="text-xs text-slate-500">Set a new password for <strong id="pwTargetId" class="text-slate-800"></strong>.</p>

      <form id="passwordResetForm" onsubmit="submitPasswordReset(event)" class="space-y-4">
        <input type="hidden" id="pwResetMobile">
        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">New Password</label>
          <input type="password" id="pwResetNew" required minlength="4" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
        </div>

        <div id="passwordResetAlert" class="hidden p-3 rounded-xl font-semibold border text-sm"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closePasswordModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-slate-700 transition-all text-sm">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all text-sm">Reset Password</button>
        </div>
      </form>
    </div>
  </div>
  @endif

  <!-- 3. PROFILE AUDIT MODAL -->
  <div id="auditModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-2xl p-6 sm:p-8 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-600">receipt_long</span>
          <span>Profile Audit Trail</span>
        </h3>
        <button onclick="closeAuditModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><span class="material-symbols-rounded">close</span></button>
      </div>

      <p class="text-xs text-slate-500">History log for <strong id="auditTargetUser" class="text-slate-800"></strong>.</p>

      <div class="max-h-80 overflow-y-auto custom-scrollbar border border-slate-100 rounded-xl">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-700 font-semibold">
            <tr>
              <th class="p-3">Time</th>
              <th class="p-3">Actor</th>
              <th class="p-3">Action</th>
              <th class="p-3">Details</th>
            </tr>
          </thead>
          <tbody id="userAuditBody" class="divide-y divide-slate-100 text-slate-800">
            <tr><td colspan="4" class="p-4 text-center text-slate-400">Loading audit history...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if(in_array(strtolower(session('userRole', '')), ['super_admin', 'superadmin', 'admin', 'principal']))
  <!-- 4. REGISTER NEW PROFILE MODAL (RESTORED FULL FIELDS) -->
  <div id="registerModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 sm:p-8 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-600">person_add</span>
          <span>Register New Profile</span>
        </h3>
        <button onclick="closeRegisterModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><span class="material-symbols-rounded">close</span></button>
      </div>

      <form id="registerUserForm" onsubmit="submitNewUser(event)" class="space-y-3.5 text-xs">
        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">User Type <span class="text-rose-500">*</span></label>
          <select id="regType" onchange="toggleRegFields()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 font-semibold">
            <option value="Student" selected>Student Profile</option>
            <option value="Staff">Faculty / Staff Profile</option>
          </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Full Name <span class="text-rose-500">*</span></label>
            <input type="text" id="regName" required placeholder="e.g. Rahul K" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address <span class="text-rose-500">*</span></label>
            <input type="email" id="regEmail" required placeholder="name@carmelpoly.edu.in" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
          </div>
        </div>

        <!-- Student Specific Fields -->
        <div id="regStudentSpecific" class="space-y-3">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Register No <span class="text-slate-400 font-normal">(Optional)</span></label>
              <input type="text" id="regRegisterNo" placeholder="e.g. 25EL1001" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 uppercase">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Admission No <span class="text-rose-500">*</span></label>
              <input type="text" id="regAdmNo" placeholder="e.g. ADM25EL01" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 uppercase">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Branch <span class="text-rose-500">*</span></label>
              <select id="regStudentBranch" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                <option value="EL">EL</option>
                <option value="ME">ME</option>
                <option value="CE">CE</option>
                <option value="EEE">EEE</option>
                <option value="CT">CT</option>
                <option value="AU">AU</option>
                <option value="GEN_AIDED">GEN_AIDED</option>
                <option value="GEN_SF">GEN_SF</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Adm Year <span class="text-rose-500">*</span></label>
              <input type="number" id="regAdmYear" value="{{ date('Y') }}" min="2020" max="2035" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 font-bold">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Semester <span class="text-rose-500">*</span></label>
              <select id="regSemester" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
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

        <!-- Staff Specific Fields -->
        <div id="regStaffSpecific" class="space-y-3 hidden">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Mobile / Staff ID <span class="text-rose-500">*</span></label>
              <input type="text" id="regStaffMobile" placeholder="e.g. 9876543210" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Department Branch <span class="text-rose-500">*</span></label>
              <select id="regStaffBranch" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                <option value="EL">Electronics Engineering (EL)</option>
                <option value="ME">Mechanical Engineering (ME)</option>
                <option value="CE">Civil Engineering (CE)</option>
                <option value="EEE">Electrical &amp; Electronics Engineering (EEE)</option>
                <option value="CT">Computer Engineering (CT)</option>
                <option value="AU">Automobile Engineering (AU)</option>
                <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
                <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Designation <span class="text-rose-500">*</span></label>
            <select id="regDesignation" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
              <option value="Lecturer">Lecturer</option>
              <option value="HOD">Head of Department (HOD)</option>
              <option value="Academic_Coordinator">Academic Coordinator</option>
              <option value="Demonstrator">Demonstrator</option>
              <option value="Physical_Instructor">Physical Instructor</option>
              <option value="Trade_Instructor">Trade Instructor</option>
              <option value="Workshop_Superintendent">Workshop Superintendent</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password <span class="text-rose-500">*</span></label>
          <input type="password" id="regPassword" required minlength="4" placeholder="e.g. 12345" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
        </div>

        <div id="registerAlert" class="hidden p-3 rounded-xl font-semibold border text-sm"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRegisterModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-slate-700 transition-all text-sm">Cancel</button>
          <button type="submit" id="regSubmitBtn" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all text-sm flex items-center justify-center gap-1.5 shadow-sm">
            <span class="material-symbols-rounded text-base">person_add</span>
            <span>Register Profile</span>
          </button>
        </div>
      </form>
    </div>
  </div>
  @endif

  <!-- 5. FLASH NOTICE BROADCAST MODAL -->
  <div id="flashNoticeModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-2xl p-6 sm:p-8 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <div class="flex items-center gap-2.5">
          <div class="p-2 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
            <span class="material-symbols-rounded text-xl">campaign</span>
          </div>
          <div>
            <h3 class="font-bold text-slate-900 text-base">Broadcast Executive Flash Notice</h3>
            <p class="text-xs text-slate-500">Immediate directive broadcast across student, staff &amp; department feeds</p>
          </div>
        </div>
        <button onclick="closeFlashNoticeModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><span class="material-symbols-rounded">close</span></button>
      </div>

      <form id="flashNoticeForm" onsubmit="submitFlashNotice(event)" class="space-y-4 text-xs" enctype="multipart/form-data">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Notice Title / Subject <span class="text-rose-500">*</span></label>
            <input type="text" id="fnTitle" name="title" required placeholder="e.g. Special Working Day & Exam Valuation Notice" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Priority / Type <span class="text-rose-500">*</span></label>
            <select id="fnPriority" name="priority" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
              <option value="Normal">Normal Announcement</option>
              <option value="Urgent">Urgent Flash Warning</option>
              <option value="Circular">Official Circular</option>
            </select>
          </div>
        </div>

        <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
          <span class="block text-slate-700 font-bold text-xs uppercase tracking-wider flex items-center gap-1.5">
            <span class="material-symbols-rounded text-sky-600 text-sm">groups</span> Target Audience &amp; Scope
          </span>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-slate-600 mb-1 font-semibold">Recipient Group</label>
              <select id="fnTargetAudience" name="target_audience" onchange="toggleNoticeTargetFields()" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-2 text-sm text-slate-800 outline-none focus:border-blue-500">
                <option value="ALL_CAMPUS">🌐 ALL Campus (Staff &amp; Students)</option>
                <option value="STAFF_ALL">👨‍🏫 Staff - All Departments</option>
                <option value="STAFF_DEPT">🏫 Staff - Specific Department</option>
                <option value="STUDENTS_ALL">🎓 Students - All Batches</option>
                <option value="STUDENTS_DEPT_SEM">📚 Students - Dept &amp; Semester</option>
              </select>
            </div>
            <div id="fnDeptWrapper">
              <label class="block text-slate-600 mb-1 font-semibold">Department Branch</label>
              <select id="fnTargetDepartment" name="target_department" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-2 text-sm text-slate-800 outline-none focus:border-blue-500">
                <option value="ALL">All Departments</option>
                <option value="EL">Electronics Engg (EL)</option>
                <option value="ME">Mechanical Engg (ME)</option>
                <option value="CE">Civil Engg (CE)</option>
                <option value="EEE">Electrical Engg (EEE)</option>
                <option value="CT">Computer Engg (CT)</option>
                <option value="AU">Automobile Engg (AU)</option>
                <option value="GEN_AIDED">General Aided</option>
                <option value="GEN_SF">General SF</option>
              </select>
            </div>
            <div id="fnSemWrapper">
              <label class="block text-slate-600 mb-1 font-semibold">Semester Level</label>
              <select id="fnTargetSemester" name="target_semester" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-2 text-sm text-slate-800 outline-none focus:border-blue-500">
                <option value="ALL">All Semesters (S1 to S6)</option>
                <option value="1">Semester 1 (S1)</option>
                <option value="2">Semester 2 (S2)</option>
                <option value="3">Semester 3 (S3)</option>
                <option value="4">Semester 4 (S4)</option>
                <option value="5">Semester 5 (S5)</option>
                <option value="6">Semester 6 (S6)</option>
              </select>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Notice Description / Content <span class="text-rose-500">*</span></label>
          <textarea id="fnContent" name="content" required rows="3" placeholder="Enter detailed notice message, instructions, or official directive text..." class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 leading-relaxed"></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
          <!-- Left: Attach Image or PDF -->
          <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
              <span class="material-symbols-rounded text-amber-600 text-sm">attach_file</span>
              <span>Attach Image or PDF <span class="text-slate-400 font-normal lowercase">(Optional)</span></span>
            </label>
            <input type="file" id="fnAttachment" name="attachment" accept=".jpg,.jpeg,.png,.webp,.pdf" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
            <p class="text-[10px] text-slate-400">Supports JPG, PNG, WEBP images or PDF files (Max 10MB).</p>
          </div>

          <!-- Right: Dispatch Timing -->
          <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
              <span class="material-symbols-rounded text-emerald-600 text-sm">schedule</span>
              <span>Dispatch Timing</span>
            </label>
            <div class="flex items-center gap-4 pt-1">
              <label class="inline-flex items-center gap-1.5 cursor-pointer select-none text-xs font-bold text-slate-700">
                <input type="radio" name="dispatch_type" value="immediate" checked onchange="toggleNoticeDispatchTiming()" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                <span>⚡ Immediate Now</span>
              </label>
              <label class="inline-flex items-center gap-1.5 cursor-pointer select-none text-xs font-bold text-amber-600">
                <input type="radio" name="dispatch_type" value="scheduled" onchange="toggleNoticeDispatchTiming()" class="w-4 h-4 text-amber-600 focus:ring-amber-500">
                <span>⏰ Scheduled</span>
              </label>
            </div>
            <div id="fnScheduledWrapper" style="display:none;" class="pt-1">
              <input type="datetime-local" id="fnScheduledAt" name="scheduled_at" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-900 outline-none focus:border-blue-500">
            </div>
          </div>
        </div>

        <div id="flashNoticeAlert" class="hidden p-3 rounded-xl font-semibold border text-sm"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeFlashNoticeModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-slate-700 transition-all text-sm">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all text-sm flex items-center justify-center gap-1.5 shadow-sm">
            <span class="material-symbols-rounded text-base">send</span>
            <span>Broadcast Notice</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- 6. FLASH NOTICE HISTORY MODAL -->
  <div id="flashNoticeHistoryModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-3xl p-6 sm:p-8 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <span class="material-symbols-rounded text-amber-600">history</span>
          <span>Flash Notice Broadcast History</span>
        </h3>
        <button onclick="closeFlashNoticeHistoryModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><span class="material-symbols-rounded">close</span></button>
      </div>

      <div class="overflow-x-auto custom-scrollbar border border-slate-100 rounded-xl">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-700 font-semibold">
            <tr>
              <th class="p-3">Date</th>
              <th class="p-3">Title &amp; Priority</th>
              <th class="p-3">Target Scope</th>
              <th class="p-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody id="flashNoticeHistoryBody" class="divide-y divide-slate-100 text-slate-800">
            <tr><td colspan="4" class="p-4 text-center text-slate-400">Loading broadcast history...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- 7. PRINCIPAL SCHEDULE EVENT MODAL -->
  <div id="principalScheduleEventModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-2xl p-6 sm:p-8 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <div class="flex items-center gap-2.5">
          <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
            <x-ui.icon name="event_available" class="w-5 h-5" />
          </div>
          <div>
            <h3 class="font-bold text-slate-900 text-base">Schedule College Institutional Event</h3>
            <p class="text-xs text-slate-500">Target College, Department, Staff, Students, or Special Groups</p>
          </div>
        </div>
        <button onclick="closePrincipalScheduleEventModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><x-ui.icon name="x" class="w-4 h-4" /></button>
      </div>

      <form id="principalScheduleEventForm" onsubmit="submitPrincipalScheduleEvent(event)" class="space-y-3.5 text-xs" enctype="multipart/form-data">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Event Title <span class="text-rose-500">*</span></label>
            <input type="text" id="peTitle" name="title" required placeholder="e.g., Annual Sports Meet 2026 / Placement Drive" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Event Category <span class="text-rose-500">*</span></label>
