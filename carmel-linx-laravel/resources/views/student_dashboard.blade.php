@php
  $activeTab = request()->query('tab', 'exams');
@endphp

<x-layouts.app-shell
  title="CampusLynk - Student Desk"
  topbarTitle="Student Learning Portal"
  topbarSubtitle="Academic courses, internal marks, continuous attendance, and mentoring diary."
  :activeNav="$activeTab"
>
  <!-- Panels Container -->
  <div class="space-y-6">
    @include('student.panel-exams')
    @include('student.panel-marks')
    @include('student.panel-profile')
    @include('student.panel-mentoring')
    @include('student.panel-activity')
    @include('student.panel-seminar')
    @include('student.panel-attendance')
    @include('student.panel-mock-test')
  </div>

  @include('student.modals')

  @push('scripts')
    @include('student.scripts')
  @endpush
</x-layouts.app-shell>