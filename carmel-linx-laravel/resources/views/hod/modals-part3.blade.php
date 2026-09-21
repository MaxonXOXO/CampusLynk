            <i data-lucide="trophy" class="w-4 h-4 text-rose-600"></i>
          </div>
          <span>Activity Points Audit Report</span>
        </h3>
        <button type="button" onclick="closeActivityPointsModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <div class="space-y-4">
        <p class="text-sm text-slate-600 leading-relaxed">
          Generate semester-wise or batch-wise student activity points audits showing target thresholds for course completion (75 points standard).
        </p>
        
        <div class="space-y-3">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Select Semester Batch</label>
            <select id="selectActivityBatch" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none cursor-pointer">
              @foreach($batches as $batch)
                <option value="{{ $batch->classroom_id }}">{{ $batch->classroom_id }} (Sem {{ $batch->current_semester }})</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Select Semester Scope</label>
            <select id="selectActivitySemester" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none cursor-pointer">
              <option value="all">All Semesters (Cumulative)</option>
              <option value="1">Semester 1</option>
              <option value="2">Semester 2</option>
              <option value="3">Semester 3</option>
              <option value="4">Semester 4</option>
              <option value="5">Semester 5</option>
              <option value="6">Semester 6</option>
            </select>
          </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
          <button type="button" onclick="closeActivityPointsModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-50 rounded-xl font-medium transition-all text-slate-700 text-sm cursor-pointer">
            Cancel
          </button>
          <button type="button" onclick="printActivityPointsReport()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-xl font-medium shadow-sm transition-all flex items-center justify-center gap-2 text-sm cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Print Report</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
