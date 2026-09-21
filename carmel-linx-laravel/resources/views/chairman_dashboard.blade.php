<x-layouts.app-shell 
  title="CampusLynk - Chairman Desk" 
  topbarTitle="Executive Overview" 
  topbarSubtitle="High-level governance, institutional audit, and campus management."
  activeNav="dashboard"
>
  <!-- Panels Container -->
  <div class="space-y-6">
    @include('chairman.panel-dashboard')
    @include('chairman.panel-directory')
    @include('chairman.panel-audit')
  </div>

  @push('scripts')
    @include('chairman.scripts')
  @endpush
</x-layouts.app-shell>