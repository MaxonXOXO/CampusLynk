<div id="panelProf_activities" class="{{ $initialPanel === 'prof_activities' ? '' : 'hidden' }} space-y-6">
        
        <!-- Header & Filters Toolbar Card -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
              <i data-lucide="award" class="w-6 h-6 text-indigo-600"></i>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/80">
                  <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                  {{ $activeBranch }} Department · Faculty Professional Activities
                </span>
              </div>
              <h3 class="text-base font-bold text-slate-900 mt-1">Professional Activities</h3>
              <p class="text-xs text-slate-500 mt-0.5">Faculty development, publications, workshops, projects, and academic contributions.</p>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-2.5">
            <select id="profActAyFilter" onchange="loadProfActivities()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
              @php
                $sYear = 2020;
                $eYear = date('Y') + 3;
              @endphp
              @for($y = $eYear; $y >= $sYear; $y--)
                @php $yr = $y . '-' . ($y + 1); @endphp
                <option value="{{ $yr }}" {{ $yr === (date('Y') . '-' . (date('Y') + 1)) ? 'selected' : '' }}>AY {{ $yr }}</option>
              @endfor
            </select>

            <select id="profActDeptFilter" onchange="loadProfActivities()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
              <option value="{{ $activeBranch }}">{{ $activeBranch }} Department</option>
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

            <button type="button" onclick="loadProfActivities()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition cursor-pointer border border-slate-200 flex items-center gap-2 text-sm font-semibold" title="Refresh Activities">
              <i data-lucide="refresh-cw" class="w-4 h-4 text-slate-600"></i>
              <span>Refresh</span>
            </button>
          </div>
        </div>

        <!-- 5/7 Split Workspace -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
          
          <!-- Left 5 Columns: Activity Entry Form -->
          <div class="lg:col-span-5 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>Record Professional Activity</span>
              </h4>
              <span class="text-xs text-slate-400 font-mono" id="profActAyLabel">AY {{ date('Y') }}-{{ date('Y') + 1 }}</span>
            </div>

            <form id="profActivityForm" onsubmit="submitProfActivity(event)" class="space-y-4">
              <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Activity Category <span class="text-rose-500">*</span></label>
                <select id="profActType" onchange="toggleProfActFields(this.value)" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all font-medium">
                  <option value="fdp_attended">Faculty Development Program (FDP) / Training</option>
                  <option value="workshop_attended">Technical Workshop / Hands-on BootCamp</option>
                  <option value="course_attended">Online Certification / MOOC / NPTEL Course</option>
                  <option value="gap_in_syllabus">Curricular Gap Identified in Syllabus</option>
                  <option value="project_guided">Student Major / Minor Project Guided</option>
                  <option value="seminar_guided">Student Technical Seminar Guided</option>
                  <option value="publication">Research Paper / Journal Publication</option>
                  <option value="book_published">Book Published (with ISBN)</option>
                </select>
              </div>

              <!-- Dynamic Schema Fields Container -->
              <div id="profActDynamicFields" class="space-y-3 pt-1">
                <!-- Rendered dynamically by JS toggleProfActFields -->
              </div>

              <div id="profActAlert" class="hidden p-3 rounded-xl font-semibold border text-sm"></div>

              <button type="submit" id="btnSaveProfAct" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                <i data-lucide="save" class="w-4 h-4 text-white"></i>
                <span>Save Activity Record</span>
              </button>
            </form>
          </div>

          <!-- Right 7 Columns: KPI Metrics & Activity Feed -->
          <div class="lg:col-span-7 space-y-4">
            
            <!-- 3 Metric KPI Summary Cards -->
            <div class="grid grid-cols-3 gap-3">
              <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-xs text-center">
                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block">Total Recorded</span>
                <span id="profActTotalCount" class="text-xl font-bold text-slate-900 block mt-1">0</span>
                <span class="text-[11px] text-slate-400 font-medium">Department Items</span>
              </div>
              <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-xs text-center">
                <span class="text-xs text-indigo-600 font-bold uppercase tracking-wider block">FDPs &amp; Workshops</span>
                <span id="profActFdpCount" class="text-xl font-bold text-indigo-600 block mt-1">0</span>
                <span class="text-[11px] text-slate-400 font-medium">Training Programs</span>
              </div>
              <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-xs text-center">
                <span class="text-xs text-emerald-600 font-bold uppercase tracking-wider block">Publications &amp; Books</span>
                <span id="profActPubCount" class="text-xl font-bold text-emerald-600 block mt-1">0</span>
                <span class="text-[11px] text-slate-400 font-medium">Research &amp; Text</span>
              </div>
            </div>

            <!-- Activity Registry Feed Card -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-3.5">
              <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                  <i data-lucide="award" class="w-4 h-4 text-blue-600"></i>
                  <span>Professional Activity Registry</span>
                </h4>
                <span id="profActRegistryCount" class="text-xs text-slate-500 font-medium">0 records in AY</span>
              </div>

              <!-- List Container -->
              <div id="profActListContainer" class="space-y-3 max-h-[560px] overflow-y-auto custom-scrollbar">
                <div class="p-8 text-center text-slate-400 text-sm">Loading activity records...</div>
              </div>
            </div>

          </div>

        </div>

      </div>
