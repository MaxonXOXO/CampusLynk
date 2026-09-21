@php
  $activeBranch = $branchOverride ?? session('userBranch');
  $isPrincipalMode = isset($isPrincipalView) && $isPrincipalView;
  $isPrincipal = $isPrincipalMode;
  $departmentOverride = $branchOverride ?? null;
  $initialPanel = request()->query('panel', request()->query('tab', $activePanel ?? 'batches'));
  if (!in_array($initialPanel, ['batches', 'directory', 'subjects', 'audit', 'leave_ledger', 'prof_activities', 'report_centre', 'profile'])) {
    $initialPanel = 'batches';
  }
  $batches = \Illuminate\Support\Facades\Schema::hasTable('class_management')
      ? \Illuminate\Support\Facades\DB::table('class_management')
          ->where('branch', $activeBranch)
          ->orderBy('current_semester', 'asc')
          ->get()
      : collect();
@endphp

<x-layouts.faculty-shell
  :title="$isPrincipal ? 'Department Overview (' . ($departmentOverride ?? 'All') . ')' : 'Head of Department Desk'"
  :subtitle="$isPrincipal ? 'Executive inspection and departmental metrics.' : 'Departmental oversight, academic batches, faculty allocations, and leave governance.'"
  :activeNav="$initialPanel"
>
  <!-- Global Toast / Alert -->
  <div id="globalAlert" class="hidden p-4 rounded-xl font-semibold border text-sm transition-all shadow-2xs mb-6"></div>

  <!-- Panels Container -->
  <div class="space-y-6">
    @include('hod.panel-batches')
    @include('hod.panel-directory')
    @include('hod.panel-subjects')
    @include('hod.panel-audit')
    @include('hod.panel-leave-ledger')
    @include('hod.panel-prof-activities')
    @include('hod.panel-profile')
    @include('hod.panel-report-centre')
  </div>

  @include('hod.modals')

  @push('scripts')
    @include('hod.scripts')
  @endpush
</x-layouts.faculty-shell>