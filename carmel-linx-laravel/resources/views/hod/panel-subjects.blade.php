<div id="panelSubjects" class="{{ $initialPanel === 'subjects' ? '' : 'hidden' }} space-y-6">
        
        <!-- Header Card -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs border border-blue-100 uppercase tracking-wider">{{ $activeBranch }} Department</span>
              <span class="text-xs text-slate-400">·</span>
              <span class="text-xs text-slate-500 font-medium">Curriculum Management</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Subject & Staff Allocation</h2>
            <p class="text-sm text-slate-500 mt-0.5">Map curriculum subjects to batches per semester and assign staff across departments.</p>
          </div>
          <button 
            type="button" 
            onclick="openSubjectModal()" 
            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition-all shadow-xs shrink-0 cursor-pointer"
          >
            <i data-lucide="plus-circle" class="w-4 h-4 text-white"></i>
            <span>Add Subject</span>
          </button>
        </div>

        <!-- Filters & Action Bar -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Select Target Batch</label>
            <select 
              id="subjectBatchSelect" 
              onchange="loadSubjects()" 
              class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none transition-all cursor-pointer font-medium"
            >
              <option value="">-- Choose a Classroom --</option>
              <!-- Loaded via JS -->
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Select Semester</label>
            <select 
              id="subjectSemesterSelect" 
              onchange="loadSubjects()" 
              class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none transition-all cursor-pointer font-medium"
            >
              <option value="1" selected>Semester 1</option>
              <option value="2">Semester 2</option>
              <option value="3">Semester 3</option>
              <option value="4">Semester 4</option>
              <option value="5">Semester 5</option>
              <option value="6">Semester 6</option>
            </select>
          </div>
        </div>

        <!-- Data Table Container -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
          <div class="max-h-[calc(100vh-320px)] overflow-auto custom-scrollbar">
            <table class="min-w-[900px] w-full text-left border-collapse text-sm">
              <thead>
                <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                  <th class="p-4">Subject Code</th>
                  <th class="p-4">Subject Name</th>
                  <th class="p-4">Type</th>
                  <th class="p-4">Assigned Staff</th>
                  <th class="p-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="subjectsTableBody">
                <tr><td colspan="5" class="p-12 text-center text-slate-500 font-medium text-sm">Select a batch above to view its allocated subjects.</td></tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
