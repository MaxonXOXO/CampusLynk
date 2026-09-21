<x-layouts.faculty-shell
  title="Workshop Superintendent Desk"
  subtitle="Central workshop oversight, trade instructor management, equipment logs, and student rosters."
  activeNav="overview"
>
  <!-- Global Toast Alert Banner -->
  <div id="globalAlert" class="hidden p-4 rounded-xl font-semibold border text-sm transition-all shadow-2xs mb-6"></div>

  <!-- Panels Container -->
  <div class="space-y-6">
    @include('workshop.panel-overview')
    @include('workshop.panel-staff')
    @include('workshop.panel-students')
    @include('workshop.panel-audit')
    @include('workshop.panel-security')
  </div>

  @include('workshop.modals')

  @push('scripts')
    @include('workshop.scripts')
  @endpush
</x-layouts.faculty-shell>