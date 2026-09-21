<div id="panelBatches" class="{{ $initialPanel === 'batches' ? '' : 'hidden' }} space-y-6">

        <!-- Seminar Presentations Today dynamic notifications section -->
        <div id="seminarNotificationsContainer" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-2">
          <!-- Populated dynamically -->
        </div>

        <!-- Panel Header -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs border border-blue-100 uppercase tracking-wider">{{ $activeBranch }} Department</span>
              <span class="text-xs text-slate-400">·</span>
              <span class="text-xs text-slate-500 font-medium">Academic Console</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Batch & Classroom Management</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage admission-year batches, class tutors, batch mentors, and semester progression.</p>
          </div>
          <div class="flex items-center gap-3 shrink-0 flex-wrap sm:flex-nowrap">
            <!-- Active / Historical Filter Pills -->
            <div class="inline-flex p-1 bg-slate-100/80 border border-slate-200/60 rounded-xl">
              <button 
                id="btnHodFilterActive" 
                type="button" 
                onclick="loadBatches('active')" 
                class="px-3.5 py-1.5 rounded-lg text-sm font-semibold transition-all bg-white text-slate-900 shadow-xs border border-slate-200/60 cursor-pointer"
              >
                Current Batches
              </button>
              <button 
                id="btnHodFilterHistorical" 
                type="button" 
                onclick="loadBatches('historical')" 
                class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all text-slate-600 hover:text-slate-900 cursor-pointer"
              >
                Previous Batches
              </button>
            </div>

            <!-- Primary Action CTA -->
            <button 
              type="button" 
              onclick="openCreateBatchModal()" 
              class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-all shadow-xs shrink-0 cursor-pointer"
            >
              <i data-lucide="plus-circle" class="w-4 h-4 text-white"></i>
              <span>Create Batch</span>
            </button>
          </div>
        </div>

        <!-- Batch Alert -->
        <div id="batchGlobalAlert" class="hidden p-4 rounded-xl text-sm font-semibold border mb-4"></div>

        <!-- Batch Cards Grid -->
        <div id="batchCardsGrid" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- rendered by JS -->
        </div>

        <!-- Empty state -->
        <div id="batchEmptyState" class="hidden bg-white border border-slate-200/80 rounded-2xl p-12 text-center shadow-xs">
          <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
            <i data-lucide="folder-open" class="w-6 h-6 text-slate-400"></i>
          </div>
          <h4 class="text-base font-bold text-slate-800">No batches found</h4>
          <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">No admission year batches exist for this filter. Click "Create Batch" to initialize your first department batch.</p>
        </div>

      </div>
