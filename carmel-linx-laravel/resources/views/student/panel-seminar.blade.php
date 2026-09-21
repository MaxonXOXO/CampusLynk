<div id="panelSeminar" class="hidden space-y-6">
          <div class="max-w-3xl mx-auto space-y-5">
            
            <div id="seminarToast" class="hidden p-3.5 rounded-xl text-xs font-semibold border"></div>

            <!-- Status Banner when Registered -->
            <div id="seminarStatusBanner" class="hidden bg-emerald-50 border border-emerald-200/80 rounded-2xl p-5 shadow-sm">
              <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                  <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5"></i>
                  <div>
                    <p id="semStatusBadgeTitle" class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Seminar Registered</p>
                    <p id="semStatusTopic" class="text-slate-900 font-bold text-sm mt-0.5">-</p>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-xs text-slate-600">
                      <span>Guide: <strong id="semStatusGuide" class="text-slate-900">-</strong></span>
                      <span>Date: <strong id="semStatusDate" class="text-slate-900">-</strong></span>
                      <span>Avg Score: <strong id="semStatusScore" class="text-blue-700 font-bold">- / 75</strong></span>
                    </div>
                  </div>
                </div>
                <button type="button" onclick="showSeminarEditForm()" class="px-3.5 py-1.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
                  Edit Details
                </button>
              </div>
            </div>

            <!-- Seminar Form Card -->
            <div id="seminarFormCard" class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm space-y-4">
              <div class="border-b border-slate-100 pb-3">
                <h3 id="semFormTitle" class="font-bold text-slate-900 text-base flex items-center gap-2">
                  <i data-lucide="presentation" class="w-4 h-4 text-blue-600"></i>
                  <span>Register Seminar Details</span>
                </h3>
                <p class="text-xs text-slate-500 mt-1">Specify your technical presentation topic, proposed date, and assign a faculty advisor.</p>
              </div>

              <form id="seminarRegistrationForm" onsubmit="submitSeminarRegistration(event)" class="space-y-4 pt-1">
                <input type="hidden" id="semRegSubject">
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">Seminar Topic</label>
                  <input type="text" id="semRegTopic" required placeholder="e.g. Transformer Neural Networks in Autonomous Vehicles"
                    class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Proposed Presentation Date</label>
                    <input type="date" id="semRegDate" required
                      class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none">
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Seminar Guide / Faculty</label>
                    <select id="semRegGuide" required
                      class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none">
                      <option value="">Loading guides...</option>
                    </select>
                  </div>
                </div>
                <div class="pt-3 flex items-center justify-between">
                  <button type="button" id="semCancelEditBtn" onclick="cancelSeminarEdit()" class="hidden px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
                    Cancel
                  </button>
                  <button type="submit" id="semSubmitBtn" class="ml-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition-all shadow-sm">
                    Save Registration
                  </button>
                </div>
              </form>
            </div>

          </div>
        </div>
