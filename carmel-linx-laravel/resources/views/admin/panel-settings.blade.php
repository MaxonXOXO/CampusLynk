<div id="panelSettings" class="hidden space-y-6">
          <div class="flex items-center gap-3 bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
              <span class="material-symbols-rounded text-2xl">settings_suggest</span>
            </div>
            <div>
              <h3 class="font-bold text-slate-900 text-lg">System Settings &amp; AI Controls</h3>
              <p class="text-xs text-slate-500 mt-0.5">Configure global AI integrations, syllabus parsing engine, and local fallbacks.</p>
            </div>
          </div>

          <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-6 shadow-sm">
            <div class="flex items-center justify-between p-5 bg-slate-50 border border-slate-200 rounded-xl gap-4">
              <div class="space-y-1">
                <h4 class="font-bold text-slate-900 text-base flex items-center gap-2">
                  <span class="material-symbols-rounded text-indigo-600 text-xl">auto_awesome</span>
                  <span>Gemini AI Integration Engine</span>
                </h4>
                <p class="text-xs text-slate-500 leading-relaxed max-w-3xl">
                  Toggle Gemini AI integration across the portal. When deactivated (Offline Mode), all syllabus planners, MCQs, and question generation operations will read strictly from local databases and question banks to save API credit costs.
                </p>
              </div>
              <div class="shrink-0 flex items-center">
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" id="settingAiEnabled" class="sr-only peer" onchange="saveSystemSettings()">
                  <div class="w-12 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
              </div>
            </div>
            <div id="settingsSaveAlert" class="hidden p-4 rounded-xl font-semibold border text-sm"></div>
          </div>
        </div>
