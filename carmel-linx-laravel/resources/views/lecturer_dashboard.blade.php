@php
  $initialPanel = request('panel', request('tab', 'dashboard'));
  $isSecurityPanel = in_array($initialPanel, ['security', 'profile']);
@endphp

<x-layouts.faculty-shell
  :title="$isSecurityPanel ? 'My Profile' : 'Faculty Batches & Classroom'"
  :subtitle="$isSecurityPanel ? 'Profile details and account security.' : 'Assigned classes, syllabus progress, and student attendance.'"
  :activeNav="$isSecurityPanel ? 'profile' : 'my_batches'"
>
  <!-- Global Alert -->
  <div id="globalAlert" class="hidden p-4 rounded-xl font-semibold border text-sm transition-all shadow-2xs mb-6"></div>

  <!-- Panels Container -->
  <div class="space-y-6">
    @include('lecturer.panel-dashboard')
    @include('lecturer.panel-classroom')
    @include('lecturer.panel-security')
    @include('lecturer.panel-mobile-seminar')
  </div>

  @include('lecturer.modals')

  @push('scripts')
    @include('lecturer.scripts')
  @endpush
</x-layouts.faculty-shell>