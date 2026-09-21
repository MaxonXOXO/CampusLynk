<div id="panelProf_activities" class="hidden space-y-6">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-slate-200 p-5 rounded-2xl gap-3 shadow-sm">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-rounded text-2xl">school</span>
              </div>
              <div>
                <h3 class="text-lg font-bold text-slate-900">Faculty Academic &amp; Professional Activities</h3>
                <p class="text-xs text-slate-500 mt-0.5">FDP certifications, publications, guided projects, industrial trainings, and syllabus gap records.</p>
              </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
              <select id="profActAyFilter" onchange="loadProfActivities()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
                @php
                  $sYear = 2020;
                  $eYear = date('Y') + 3;
                @endphp
                @for($y = $eYear; $y >= $sYear; $y--)
                  @php $yr = $y . '-' . ($y + 1); @endphp
                  <option value="{{ $yr }}" {{ $yr === (date('Y') . '-' . (date('Y') + 1)) ? 'selected' : '' }}>AY {{ $yr }}</option>
                @endfor
              </select>

              <select id="profActDeptFilter" onchange="loadProfActivities()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
                <option value="">All Departments</option>
                <option value="EL">Electronics (EL)</option>
                <option value="ME">Mechanical (ME)</option>
                <option value="CE">Civil (CE)</option>
                <option value="EEE">Electrical (EEE)</option>
                <option value="CT">Computer (CT)</option>
                <option value="AU">Automobile (AU)</option>
                <option value="GEN_AIDED">Gen Aided</option>
                <option value="GEN_SF">Gen SF</option>
              </select>

              <button onclick="loadProfActivities()" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition cursor-pointer border border-slate-200" title="Refresh">
                <span class="material-symbols-rounded text-sm">sync</span>
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <div class="lg:col-span-5 bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
              <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                  <span class="material-symbols-rounded text-emerald-600 text-base">add_circle</span>
                  <span>Record New Faculty Activity</span>
                </h4>
                <span class="text-xs text-slate-400 font-mono" id="profActAyLabel">AY {{ date('Y') }}-{{ date('Y') + 1 }}</span>
              </div>

              <form id="profActivityForm" onsubmit="submitProfActivity(event)" class="space-y-3.5">
                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Activity Category</label>
                  <select id="profActType" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="fdp_attended">Faculty Development Program (FDP) / Training</option>
                    <option value="workshop_attended">Technical Workshop / Hands-on BootCamp</option>
                    <option value="course_attended">Online Certification / MOOC / NPTEL Course</option>
                    <option value="project_guided">Student Major / Minor Project Guided</option>
                    <option value="seminar_guided">Student Technical Seminar Guided</option>
                    <option value="publication">Journal / Conference Research Publication</option>
                    <option value="book_published">Authored Book / Book Chapter</option>
                    <option value="gap_in_syllabus">Curriculum Gap / Industrial Bridge Topic</option>
                  </select>
                </div>

                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Activity Title / Topic</label>
                  <input type="text" id="profActTitle" required placeholder="e.g. Advanced Embedded IoT Systems FDP" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                  <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Organizing Body</label>
                    <input type="text" id="profActOrganizer" required placeholder="e.g. DTE / NITTTR" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Duration / Days</label>
                    <input type="text" id="profActDuration" required placeholder="e.g. 5 Days / 40 Hrs" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Start Date</label>
                    <input type="date" id="profActStartDate" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Description / Key Learnings</label>
                  <textarea id="profActDesc" rows="2" placeholder="Brief summary of the program coverage and implementation in curriculum..." class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 resize-none"></textarea>
                </div>

                <div id="profActAlert" class="hidden p-3 rounded-xl font-semibold border text-xs"></div>

                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                  <span class="material-symbols-rounded text-base">check</span>
                  <span>Save Activity Record</span>
                </button>
              </form>
            </div>

            <div class="lg:col-span-7 space-y-4">
              <div class="grid grid-cols-3 gap-3">
                <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-sm text-center">
                  <span class="text-xs text-slate-500 font-semibold block">Total Recorded</span>
                  <span id="profActTotalCount" class="text-xl font-bold text-slate-900 block mt-0.5">0</span>
                </div>
                <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-sm text-center">
                  <span class="text-xs text-slate-500 font-semibold block">FDPs &amp; Workshops</span>
                  <span id="profActFdpCount" class="text-xl font-bold text-indigo-600 block mt-0.5">0</span>
                </div>
                <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-sm text-center">
                  <span class="text-xs text-slate-500 font-semibold block">Publications</span>
                  <span id="profActPubCount" class="text-xl font-bold text-emerald-600 block mt-0.5">0</span>
                </div>
              </div>

              <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                  <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <span class="material-symbols-rounded text-blue-600 text-base">format_list_bulleted</span>
                    <span>Verified Activities Registry</span>
                  </h4>
                  <span id="profActRegistryCount" class="text-xs text-slate-500">0 records</span>
                </div>

                <div id="profActListContainer" class="space-y-3 max-h-[500px] overflow-y-auto custom-scrollbar">
                  <div class="p-8 text-center text-slate-400 text-sm">Loading activity records...</div>
                </div>
              </div>
            </div>
          </div>
        </div>
