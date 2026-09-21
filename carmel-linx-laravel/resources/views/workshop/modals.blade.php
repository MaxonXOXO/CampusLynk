  </main>

  <!-- PASSWORD RESET MODAL -->
  <div id="passwordModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-[10px] flex items-center gap-2 text-sm">
          <span class="material-symbols-rounded text-blue-400 text-lg">lock_reset</span> Password Reset
        </h3>
        <button onclick="closePasswordModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>
      <div class="space-y-3">
        <p class="text-[10px] text-slate-400 text-[10px] text-xs">
          Set a new password for <span id="pwdResetName" class="font-bold text-slate-200"></span> (<span id="pwdResetId" class="text-blue-400 font-mono"></span>).
        </p>
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">New Password</label>
          <input type="text" id="newPasswordInput" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-[10px] text-xs" placeholder="Minimum 4 characters">
        </div>
      </div>
      <div id="pwdAlert" class="hidden p-3 rounded-xl text-[10px] font-bold border text-[10px] text-xs"></div>
      <div class="flex gap-3 pt-2">
        <button onclick="closePasswordModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-[10px] text-slate-300 transition-premium cursor-pointer text-[10px] text-xs">Cancel</button>
        <button onclick="submitPasswordReset()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-[10px] transition-premium cursor-pointer text-[10px] text-xs">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- AUDIT LOG MODAL FOR SINGLE PROFILE -->
  <div id="auditModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-2xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-[10px] flex items-center gap-2 text-sm">
          <span class="material-symbols-rounded text-blue-400 text-lg">receipt_long</span> Profile Audit Trail
        </h3>
        <button onclick="closeAuditModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>
      <div class="space-y-3">
        <p class="text-[10px] text-slate-400 text-[10px] text-xs">History log for <span id="auditProfileName" class="font-bold text-slate-200"></span> (<span id="auditProfileId" class="text-blue-400 font-mono"></span>).</p>
        <div class="max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-800/60 rounded-xl">
          <table class="w-full text-left text-[10px] border-collapse text-[10px] text-xs">
            <thead>
              <tr class="bg-slate-950/80 border-b border-slate-800 text-slate-400 font-bold">
                <th class="p-3">Time</th>
                <th class="p-3">Actor</th>
                <th class="p-3">Action</th>
                <th class="p-3">Details</th>
              </tr>
            </thead>
            <tbody id="modalAuditTableBody"></tbody>
          </table>
        </div>
      </div>
      <div class="flex pt-2">
        <button onclick="closeAuditModal()" class="w-full py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-[10px] text-slate-300 transition-premium cursor-pointer text-[10px] text-xs">Close Window</button>
      </div>
    </div>
  </div>

  <!-- REGISTER INSTRUCTOR MODAL -->
  <div id="registerStaffModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-[10px] flex items-center gap-2 text-sm">
          <span class="material-symbols-rounded text-blue-400 text-lg">person_add</span> Register Trade Instructor
        </h3>
        <button onclick="closeRegisterStaffModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <form id="registerStaffForm" onsubmit="handleRegisterStaff(event)" class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-hidden">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Full Name</label>
            <input type="text" id="regStaffName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Email Address</label>
            <input type="email" id="regStaffEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="name@carmelpoly.edu.in">
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Mobile No (Login ID)</label>
            <input type="text" id="regStaffMobile" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="10-digit number">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
            <select id="regStaffBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
              <option value="EL">Electronics (EL)</option>
              <option value="ME">Mechanical (ME)</option>
              <option value="CE">Civil (CE)</option>
              <option value="EEE">Electrical (EEE)</option>
              <option value="CT">Computer (CT)</option>
              <option value="AU">Automobile (AU)</option>
            </select>
          </div>
        </div>
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Password</label>
          <input type="text" id="regStaffPassword" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="e.g. trade123">
        </div>
        <div id="regStaffAlert" class="hidden p-3 rounded-xl text-[10px] font-bold border text-[10px] text-xs"></div>
        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRegisterStaffModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-[10px] text-slate-300 transition-premium cursor-pointer text-[10px] text-xs">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-[10px] transition-premium cursor-pointer flex items-center justify-center gap-1.5 text-[10px] text-xs">
            <span>Register Instructor</span>
            <div id="regStaffSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
