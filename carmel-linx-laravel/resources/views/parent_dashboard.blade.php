<x-layouts.app-shell
  :title="'Parent Portal — ' . ($student->name ?? 'Ward Overview')"
  :topbarTitle="'Parent Portal'"
  :topbarSubtitle="'Ward attendance, hourly schedule, academic performance, and mentor contact.'"
  activeNav="parent"
>
  <div class="max-w-5xl mx-auto space-y-6">
    @include('parent.profile-card')

    <!-- Tab Bar -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3">
      <button type="button" onclick="switchParentTab('attendance')" id="btnTabAttendance" class="px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 text-white">Attendance & Schedule</button>
      <button type="button" onclick="switchParentTab('academic')" id="btnTabAcademic" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200">Academic Status & CGPA</button>
    </div>

    <!-- Tab Panes -->
    <div class="space-y-6">
      <div id="tab-attendance" class="space-y-6">
        @include('parent.tab-attendance')
      </div>
      <div id="tab-academic" class="hidden space-y-6">
        @include('parent.tab-academic')
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      function switchParentTab(tabName) {
        const attPane = document.getElementById('tab-attendance');
        const acadPane = document.getElementById('tab-academic');
        const btnAtt = document.getElementById('btnTabAttendance');
        const btnAcad = document.getElementById('btnTabAcademic');

        if (tabName === 'attendance') {
          attPane.classList.remove('hidden');
          acadPane.classList.add('hidden');
          btnAtt.className = 'px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 text-white';
          btnAcad.className = 'px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200';
        } else {
          acadPane.classList.remove('hidden');
          attPane.classList.add('hidden');
          btnAcad.className = 'px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 text-white';
          btnAtt.className = 'px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200';
        }
      }
    </script>
    @include('parent.scripts')
  @endpush
</x-layouts.app-shell>