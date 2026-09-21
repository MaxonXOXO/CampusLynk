@php
  $userRole = session('userRole', 'Admin');
  $canManageUsers = in_array(strtolower($userRole), ['super_admin', 'superadmin', 'admin', 'principal']);
@endphp

<x-layouts.app-shell 
  title="CampusLynk - Executive Control Desk" 
  topbarTitle="Dashboard Overview" 
  topbarSubtitle="Campus-wide institutional metrics, faculty compliance, and administrative controls."
  activeNav="dashboard"
>
  <!-- Global Alert Notification Banner -->
  <div id="globalAlert" class="hidden p-4 rounded-2xl text-sm font-semibold transition-all border shadow-sm mb-6"></div>

  <!-- Panels Container -->
  <div class="space-y-6">
    @include('admin.panel-dashboard')
    @include('admin.panel-timetables')
    @include('admin.panel-directory')
    @include('admin.panel-backups')
    @include('admin.panel-audit')
    @include('admin.panel-settings')
    @include('admin.panel-prof-activities')
    @include('admin.panel-leave-ledger')
    @include('admin.panel-sf-attendance')
    @include('admin.panel-profile')
  </div>

  @include('admin.modals')

  @push('scripts')
    <!-- Leaflet Map CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @include('admin.scripts')
  @endpush
</x-layouts.app-shell>