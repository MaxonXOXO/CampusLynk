
      <!-- PANEL: MOBILE SEMINAR EVALUATION WORKSPACE -->
      <!-- PANEL: MOBILE SEMINAR EVALUATION -->
      <div id="panelMobileSeminar" class="hidden fade-up">

        <!-- Header — NO Sign Out here, sidebar already has it -->
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-700/60">
          <button onclick="switchPanel('dashboard')" class="p-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 transition-premium cursor-pointer shrink-0">
            <x-ui.icon name="science" class="w-5 h-5 text-slate-200" />
          </button>
          <div>
            <h3 class="text-lg font-black text-white flex items-center gap-2 leading-tight">
              <x-ui.icon name="co_present" class="w-5 h-5 text-blue-400" /> Virtual Seminar Room
            </h3>
            <p class="text-sm text-slate-400 mt-0.5">Evaluate student seminar presentations for today.</p>
          </div>
        </div>

        <!-- Seminar Presentations Today dynamic notifications section (Mobile Panel) -->
        <div id="mobileSeminarNotificationsContainer" class="hidden grid grid-cols-1 gap-3 mb-5">
          <!-- Populated dynamically -->
        </div>

        <!-- Mobile toast -->
        <div id="mobileSemToast" class="hidden mb-4 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2"></div>

        <!-- Step 1: Pending Invitations -->
        <div id="mobileSemStep1" class="space-y-4">

          <!-- Pending Invitations -->
          <div class="bg-white border border-amber-600/30 rounded-2xl overflow-hidden shadow-lg">
            <div class="px-5 py-4 border-b border-amber-600/20 flex items-center gap-3 bg-amber-950/20">
              <x-ui.icon name="science" class="w-5 h-5 text-amber-400" />
              <h4 class="text-base font-black text-amber-200">Pending Invitations</h4>
            </div>
            <div id="mobilePendingInvitationsList" class="p-4 space-y-3">
              <div class="text-sm text-slate-400 text-center py-4">Loading...</div>
            </div>
          </div>

          <!-- Accepted / Start Evaluation -->
          <div class="bg-white border border-emerald-700/30 rounded-2xl overflow-hidden shadow-lg">
            <div class="px-5 py-4 border-b border-emerald-700/20 flex items-center gap-3 bg-emerald-950/20">
              <x-ui.icon name="how_to_reg" class="w-5 h-5 text-emerald-400" />
              <h4 class="text-base font-black text-emerald-200">Attending Seminars</h4>
            </div>
            <div class="p-4 space-y-3">
              <div id="mobileSemAttendingList" class="space-y-2">
                <div class="text-sm text-slate-400 text-center py-4">No accepted seminars yet.</div>
              </div>
            </div>
          </div>

        </div>
