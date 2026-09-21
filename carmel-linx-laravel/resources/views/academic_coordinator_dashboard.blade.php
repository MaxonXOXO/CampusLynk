<x-layouts.faculty-shell
  title="Academic Coordinator Portal"
  subtitle="Self-financing staff oversight, leave governance, academic metrics, and audit ledger."
  activeNav="dashboard"
>
  <!-- Global Alert -->
  <div id="globalAlert" class="hidden p-4 rounded-xl font-semibold border text-sm transition-all shadow-2xs mb-6"></div>

  <!-- Navigation Tab Bar -->
  <div class="flex items-center gap-2 overflow-x-auto border-b border-slate-200 pb-3 mb-6 custom-scrollbar">
    <button type="button" onclick="switchPanel('dashboard')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 text-white">Overview</button>
    <button type="button" onclick="switchPanel('directory')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200">Staff Directory</button>
    <button type="button" onclick="switchPanel('reports')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200">Leave Master Ledger</button>
    <button type="button" onclick="switchPanel('security')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200">Security Log</button>
  </div>

  <!-- Panels Container -->
  <div class="space-y-6">
    @include('academic_coordinator.panel-dashboard')
    @include('academic_coordinator.panel-directory')
    @include('academic_coordinator.panel-reports')
    @include('academic_coordinator.panel-security')
  </div>

  @push('scripts')
    @include('academic_coordinator.scripts')
  @endpush
</x-layouts.faculty-shell>