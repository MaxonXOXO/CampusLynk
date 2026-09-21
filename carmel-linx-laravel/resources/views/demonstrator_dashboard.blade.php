@php
  $currentPanel = request()->query('panel', 'dashboard');
  $activeNav = $currentPanel === 'security' ? 'profile' : 'my_batches';
  $grouped = $assignments->groupBy('classroom_id');
  $totalSubjects = $assignments->count();
  $totalClassrooms = $grouped->count();
@endphp

<x-layouts.faculty-shell
  title="Demonstrator Console"
  subtitle="Assigned practical sessions, laboratory workspaces, and continuous evaluation."
  :activeNav="$activeNav"
>
  <!-- Top Status Alert Banner -->
  <div id="globalAlert" class="hidden p-4 rounded-xl font-semibold border text-sm transition-all shadow-2xs mb-6"></div>

  <!-- PANEL 1: LAB WORKSPACES / DASHBOARD -->
  <div id="panelDashboard" class="{{ $currentPanel === 'security' ? 'hidden' : '' }} space-y-6">
    
    <!-- Banner / Metric Overview Card -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center border border-blue-200/80 shrink-0 shadow-2xs">
          <x-ui.icon name="flask-conical" class="w-6 h-6 text-blue-600" />
        </div>
        <div>
          <h2 class="text-base font-bold text-slate-900">Assigned Practical &amp; Laboratory Workspaces</h2>
          <p class="text-sm text-slate-500 mt-0.5">Select a practical subject below to enter the shared virtual laboratory workspace to manage experiment logs, continuous assessment, and attendance.</p>
        </div>
      </div>

      <div class="flex items-center gap-3 shrink-0">
        <div class="px-3.5 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 flex items-center gap-1.5">
          <span class="w-2 h-2 rounded-full bg-blue-500"></span>
          <span><strong>{{ $totalClassrooms }}</strong> Classrooms</span>
        </div>
        <div class="px-3.5 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 flex items-center gap-1.5">
          <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
          <span><strong>{{ $totalSubjects }}</strong> Assigned Labs</span>
        </div>
      </div>
    </div>

    <!-- Practical Batches Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
      @forelse($assignments as $s)
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
          <div>
            <div class="flex items-start justify-between gap-3 mb-3">
              <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/60 font-mono">
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
              <span>Enter Lab Workspace</span>
              <x-ui.icon name="arrow-right" class="w-4 h-4" />
            </a>
          </div>
        </div>
      @empty
        <div class="col-span-full bg-white border border-slate-200/80 rounded-2xl p-12 text-center text-slate-400">
          <x-ui.icon name="flask-conical" class="w-12 h-12 mx-auto text-slate-300 mb-3" />
          <p class="text-sm font-semibold text-slate-600">No laboratory practical subjects assigned.</p>
          <p class="text-xs text-slate-400 mt-1">Please contact your Head of Department to allocate your practical lab batches.</p>
        </div>
      @endforelse
    </div>
  </div>

  <!-- PANEL 2: PROFILE & SECURITY -->
  <div id="panelSecurity" class="{{ $currentPanel === 'security' ? '' : 'hidden' }} space-y-6">
    @include('partials.staff_profile_panel', ['hideAuditLog' => true])
  </div>
</x-layouts.faculty-shell>