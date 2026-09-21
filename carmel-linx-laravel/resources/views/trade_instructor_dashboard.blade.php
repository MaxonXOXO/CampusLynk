@php
  $currentPanel = request('panel', request('tab', 'dashboard'));
  $isSecurityPanel = in_array($currentPanel, ['security', 'profile']);
  $activeNav = $isSecurityPanel ? 'profile' : 'my_batches';
  $userRole = session('userRole', 'Trade_Instructor');
  $userDesg = str_replace('_', ' ', $userRole);
@endphp

<x-layouts.faculty-shell
  :title="$isSecurityPanel ? 'My Profile & Security' : 'Trade & Workshop Tasks'"
  :subtitle="$isSecurityPanel ? 'Manage personal authentication, biometrics, and credentials.' : 'Assigned practical sessions, trade portfolios, and workshop equipment operations.'"
  :activeNav="$activeNav"
>
  <!-- PANEL 1: WORKSHOP TASKS & ASSIGNED TRADE BATCHES -->
  <div id="panelDashboard" class="{{ $isSecurityPanel ? 'hidden' : '' }} space-y-6">

    <!-- Header Summary Banner -->
    <div class="bg-white border border-slate-200/80 p-5 sm:p-6 rounded-2xl shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div class="flex items-center gap-3.5">
        <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center shrink-0 shadow-2xs">
          <x-ui.icon name="wrench" class="w-6 h-6" />
        </div>
        <div>
          <div class="flex items-center gap-2">
            <h3 class="text-base sm:text-lg font-bold text-slate-900">Trade &amp; Workshop Workspaces</h3>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
              {{ $userDesg }}
            </span>
          </div>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Select an assigned trade batch or workshop laboratory to enter the practical classroom and evaluate student performance.
          </p>
        </div>
      </div>
      <div class="flex items-center gap-2 self-stretch md:self-auto">
        <a href="/staff/attendance-log" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 transition-colors shadow-2xs no-underline">
          <x-ui.icon name="clipboard-check" class="w-4 h-4 text-slate-500" />
          <span>Attendance Log</span>
        </a>
        <a href="/staff/my-leave" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 transition-colors shadow-2xs no-underline">
          <x-ui.icon name="calendar-check-2" class="w-4 h-4 text-slate-500" />
          <span>My Leave</span>
        </a>
      </div>
    </div>

    <!-- Workshop Batches Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
      @forelse($assignments ?? [] as $s)
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
          <div>
            <div class="flex items-start justify-between gap-3 mb-3">
              <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 font-mono">
                {{ $s->subject_code }}
              </span>
              <span class="text-xs text-slate-400 font-medium">Sem {{ $s->semester }} &bull; {{ $s->branch }}</span>
            </div>

            <h3 class="text-base font-bold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2">
              {{ $s->subject_name }}
            </h3>

            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
              <span>Batch {{ $s->batch_year }}</span>
              <span class="text-slate-400 uppercase font-mono">{{ $s->syllabus_revision_code ?? 'R2021' }}</span>
            </div>
          </div>

          <div class="mt-5 pt-3">
            <a 
              href="/virtual-classroom-practical/{{ $s->subject_id }}" 
              class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold flex items-center justify-center gap-2 transition-all shadow-xs group-hover:shadow-sm"
            >
              <span>Enter Workshop Workspace</span>
              <x-ui.icon name="arrow-right" class="w-4 h-4" />
            </a>
          </div>
        </div>
      @empty
        <div class="col-span-full bg-white border border-slate-200/80 rounded-2xl p-12 text-center text-slate-400">
          <x-ui.icon name="wrench" class="w-12 h-12 mx-auto text-slate-300 mb-3" />
          <p class="text-sm font-semibold text-slate-600">No trade or workshop subjects currently assigned.</p>
          <p class="text-xs text-slate-400 mt-1">Contact your Head of Department or Workshop Superintendent for subject assignments.</p>
        </div>
      @endforelse
    </div>
  </div>

  <!-- PANEL 2: MY PROFILE & CREDENTIALS -->
  <div id="panelSecurity" class="{{ $isSecurityPanel ? '' : 'hidden' }} space-y-6">
    @include('partials.staff_profile_panel', ['hideAuditLog' => true])
  </div>
</x-layouts.faculty-shell>