<x-layouts.faculty-shell
  title="General Coordinator (Aided) Desk"
  subtitle="First-year and foundational academics, aided staff directory, and institutional monitoring."
  activeNav="dashboard"
>
  <!-- Navigation Tab Bar -->
  <div class="flex items-center gap-2 overflow-x-auto border-b border-slate-200 pb-3 mb-6 custom-scrollbar">
    <button type="button" onclick="switchPanel('dashboard')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 text-white">Overview</button>
    <button type="button" onclick="switchPanel('directory')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200">User Directory</button>
  </div>

  <!-- Panels Container -->
  <div class="space-y-6">
    @include('general_coordinator.panel-dashboard-aided')
    @include('general_coordinator.panel-directory-aided')
  </div>

  @push('scripts')
    @include('general_coordinator.scripts-aided')
  @endpush
</x-layouts.faculty-shell>