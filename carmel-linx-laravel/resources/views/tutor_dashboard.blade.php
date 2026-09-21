@php
  $initialPanel = request('panel', request('tab', 'roster'));
  $activeTab = in_array($initialPanel, ['mentoring', 'rollNumbers', 'leaveApproval', 'activity', 'audit', 'profile', 'security']) ? $initialPanel : 'roster';
@endphp

<x-layouts.faculty-shell
  title="Tutor Console"
  subtitle="Student mentoring, batch roster, attendance verification, and activity points."
  :activeNav="$activeTab"
>
  <!-- Global Alert -->
  <div id="globalAlert" class="hidden p-4 rounded-xl font-semibold border text-sm transition-all shadow-2xs mb-6"></div>

  <!-- Navigation Tab Bar -->
  <div class="flex items-center gap-2 overflow-x-auto border-b border-slate-200 pb-3 mb-6 custom-scrollbar">
    <button type="button" onclick="switchPanel('roster')" class="px-4 py-2 rounded-xl text-xs font-semibold {{ $activeTab === 'roster' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 border border-slate-200' }}">Roster</button>
    <button type="button" onclick="switchPanel('rollNumbers')" class="px-4 py-2 rounded-xl text-xs font-semibold {{ $activeTab === 'rollNumbers' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 border border-slate-200' }}">Roll Numbers</button>
    <button type="button" onclick="switchPanel('mentoring')" class="px-4 py-2 rounded-xl text-xs font-semibold {{ $activeTab === 'mentoring' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 border border-slate-200' }}">Mentoring</button>
    <button type="button" onclick="switchPanel('leaveApproval')" class="px-4 py-2 rounded-xl text-xs font-semibold {{ $activeTab === 'leaveApproval' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 border border-slate-200' }}">Leave Approval</button>
    <button type="button" onclick="switchPanel('activity')" class="px-4 py-2 rounded-xl text-xs font-semibold {{ $activeTab === 'activity' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 border border-slate-200' }}">Activity Points</button>
    <button type="button" onclick="switchPanel('audit')" class="px-4 py-2 rounded-xl text-xs font-semibold {{ $activeTab === 'audit' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 border border-slate-200' }}">Audit Trail</button>
    <button type="button" onclick="switchPanel('profile')" class="px-4 py-2 rounded-xl text-xs font-semibold {{ $activeTab === 'profile' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 border border-slate-200' }}">My Profile</button>
  </div>

  <!-- Panels Container -->
  <div class="space-y-6">
    @include('tutor.panel-roster')
    @include('tutor.panel-roll-numbers')
    @include('tutor.panel-mentoring')
    @include('tutor.panel-leave-approval')
    @include('tutor.panel-activity')
    @include('tutor.panel-audit')
    @include('tutor.panel-profile')
  </div>

  @include('tutor.modals')

  @push('scripts')
    @include('tutor.scripts')
  @endpush
</x-layouts.faculty-shell>