<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CampusLynk - Demonstrator Console</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Modern Typography (Poppins) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Pre-Paint Synchronous Sidebar State Hydration (Anti-FOUC) -->
  <script>
    (function() {
      try {
        var isCollapsed = localStorage.getItem('campuslynk_sidebar_collapsed') === 'true' || 
                          document.cookie.indexOf('campuslynk_sidebar_collapsed=true') !== -1;
        if (isCollapsed && window.innerWidth >= 1024) {
          document.documentElement.classList.add('sidebar-is-collapsed');
        }
      } catch(e) {}
    })();
  </script>

  <!-- Vite Compiled Assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
  </style>
</head>
<body class="bg-[#FAFAFB] text-slate-900 min-h-screen font-sans antialiased sidebar-preload">

  @php
    $currentPanel = request()->query('panel', 'dashboard');
    $activeNav = $currentPanel === 'security' ? 'profile' : 'my_batches';
    $grouped = $assignments->groupBy('classroom_id');
    $totalSubjects = $assignments->count();
    $totalClassrooms = $grouped->count();
  @endphp

  <!-- Master Application Shell -->
  <div class="flex min-h-screen bg-[#FAFAFB]">

    <!-- Global Sidebar Navigation Component -->
    <x-layout.sidebar role="demonstrator" :active="$activeNav" />

    <!-- Main Viewport Container -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#FAFAFB]">
      
      <!-- Global Topbar Header Component -->
      <x-layout.topbar 
        title="Demonstrator Console" 
        subtitle="Assigned practical sessions, laboratory workspaces, and continuous evaluation." 
      />

      <!-- Scrollable Main Workspace -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- Top Status Alert Banner -->
        <div id="globalAlert" class="hidden p-4 rounded-xl font-semibold border text-sm transition-all shadow-2xs"></div>

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
              <div class="px-3.5 py-1.5 rounded-xl bg-blue-50 border border-blue-200 text-xs font-semibold text-blue-700 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span><strong>{{ $totalSubjects }}</strong> Assigned Subjects</span>
              </div>
            </div>
          </div>

          <!-- Classroom / Subject Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($grouped as $classroomId => $subjects)
              @php
                $first = $subjects->first();
              @endphp
              <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden flex flex-col shadow-xs hover:shadow-md hover:border-blue-300 transition-all">
                
                <!-- Card Header -->
                <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-start gap-2">
                  <div>
                    <h3 class="font-bold text-slate-900 text-base tracking-tight">{{ $classroomId }}</h3>
                    <div class="text-xs text-slate-500 font-medium mt-0.5">
                      <span class="font-semibold text-slate-700">{{ $first->branch }}</span> • Year {{ $first->batch_year }}
                    </div>
                  </div>
                  <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200/80 text-xs font-semibold shrink-0">
                    Lab Faculty
                  </span>
                </div>
                
                <!-- Card Body: Subjects List -->
                <div class="p-4 space-y-2.5 flex-grow">
                  @foreach($subjects as $s)
                    @php
                      $isR26 = ($s->syllabus_revision_code ?? 'REV2021') === 'REV2026';
                      $sNameLower = strtolower($s->subject_name ?? '');
                      $sTypeLower = strtolower($s->subject_type ?? '');
                      
                      if ($isR26) {
                        if (str_contains($sNameLower, 'health') || str_contains($sNameLower, 'physical') || str_contains($sTypeLower, 'health') || str_contains($sTypeLower, 'physical')) {
                          $targetUrl = "/r26/classroom/health-physical/{$s->subject_id}";
                        } elseif (str_contains($sTypeLower, 'drawing') || str_contains($sNameLower, 'drawing') || str_contains($sNameLower, 'graphics') || str_contains($sNameLower, 'cad')) {
                          $targetUrl = "/r26/classroom/drawing/{$s->subject_id}";
                        } elseif (str_contains($sTypeLower, 'practicum')) {
                          $targetUrl = "/r26/classroom/practicum/{$s->subject_id}";
                        } elseif (str_contains($sTypeLower, 'theory')) {
                          $targetUrl = "/r26/classroom/theory/{$s->subject_id}";
                        } else {
                          $targetUrl = "/r26/classroom/practical/{$s->subject_id}";
                        }
                      } else {
                        $targetUrl = "/dashboard/lecturer?subject_id={$s->subject_id}&subject_name=" . urlencode($s->subject_name) . "&classroom_id=" . urlencode($s->classroom_id);
                      }
                    @endphp

                    <a href="{{ $targetUrl }}" class="group block p-3 bg-slate-50/70 hover:bg-blue-50/50 border border-slate-200/70 hover:border-blue-300 rounded-xl transition-all no-underline">
                      <div class="flex justify-between items-start gap-3">
                        <div class="min-w-0 flex-1">
                          <div class="font-semibold text-slate-900 group-hover:text-blue-700 transition-colors text-sm leading-snug truncate">
                            {{ $s->subject_name }}
                          </div>
                          <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mt-1 flex-wrap">
                            <span class="font-mono font-semibold text-slate-700">{{ $s->subject_code }}</span>
                            <span>•</span>
                            <span>Sem {{ $s->semester }}</span>
                            <span>•</span>
                            <span class="px-1.5 py-0.2 rounded text-[11px] font-semibold {{ $isR26 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                              {{ $s->syllabus_revision_code ?? 'REV2021' }}
                            </span>
                          </div>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-white group-hover:bg-blue-600 border border-slate-200 group-hover:border-blue-600 flex items-center justify-center text-slate-400 group-hover:text-white transition-all shrink-0 shadow-2xs">
                          <x-ui.icon name="arrow-right" class="w-4 h-4" />
                        </div>
                      </div>
                    </a>
                  @endforeach
                </div>

              </div>
            @empty
              <div class="col-span-full bg-white border border-slate-200/80 p-12 rounded-2xl text-center shadow-xs">
                <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-200 text-slate-400 flex items-center justify-center mx-auto mb-4">
                  <x-ui.icon name="flask-conical" class="w-8 h-8" />
                </div>
                <h4 class="font-bold text-slate-900 text-base">No Lab Assignments Found</h4>
                <p class="text-sm text-slate-500 max-w-md mx-auto mt-1.5">
                  You are currently not assigned to any active lab practical subjects. Please contact your Head of Department to allocate practical sessions.
                </p>
              </div>
            @endforelse
          </div>

        </div>

        <!-- PANEL 2: MY PROFILE & SECURITY -->
        <div id="panelSecurity" class="{{ $currentPanel === 'security' ? '' : 'hidden' }} space-y-6">
          @include('partials.staff_profile_panel', ['hideAuditLog' => true])
        </div>

      </main>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
    let currentPanel = '{{ $currentPanel }}';

    function handleDemonstratorSidebarNav(panel) {
      currentPanel = panel;
      
      const dashPane = document.getElementById('panelDashboard');
      const secPane = document.getElementById('panelSecurity');
      const navBatches = document.getElementById('nav-btn-my_batches') || document.getElementById('nav-link-my_batches');
      const navProfile = document.getElementById('nav-btn-profile') || document.getElementById('nav-link-profile');

      if (panel === 'security') {
        if (dashPane) dashPane.classList.add('hidden');
        if (secPane) secPane.classList.remove('hidden');

        if (navBatches) {
          navBatches.className = "w-full text-left group flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all text-slate-300 hover:bg-slate-800/70 hover:text-white";
          const icon = navBatches.querySelector('svg');
          if (icon) icon.className = "w-4 h-4 text-slate-400 group-hover:text-slate-200 transition-colors";
        }
        if (navProfile) {
          navProfile.className = "w-full text-left group flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all bg-slate-800/90 text-white font-semibold border-l-2 border-blue-500 shadow-2xs";
          const icon = navProfile.querySelector('svg');
          if (icon) icon.className = "w-4 h-4 text-blue-400 transition-colors";
        }

        window.history.pushState(null, '', '/dashboard/demonstrator?panel=security');
      } else {
        if (secPane) secPane.classList.add('hidden');
        if (dashPane) dashPane.classList.remove('hidden');

        if (navProfile) {
          navProfile.className = "w-full text-left group flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all text-slate-300 hover:bg-slate-800/70 hover:text-white";
          const icon = navProfile.querySelector('svg');
          if (icon) icon.className = "w-4 h-4 text-slate-400 group-hover:text-slate-200 transition-colors";
        }
        if (navBatches) {
          navBatches.className = "w-full text-left group flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all bg-slate-800/90 text-white font-semibold border-l-2 border-blue-500 shadow-2xs";
          const icon = navBatches.querySelector('svg');
          if (icon) icon.className = "w-4 h-4 text-blue-400 transition-colors";
        }

        window.history.pushState(null, '', '/dashboard/demonstrator');
      }
    }

    // Attach click listeners for instant tab switching when on dashboard
    document.addEventListener('DOMContentLoaded', () => {
      const navBatches = document.getElementById('nav-link-my_batches') || document.getElementById('nav-btn-my_batches');
      if (navBatches) {
        navBatches.addEventListener('click', (e) => {
          e.preventDefault();
          handleDemonstratorSidebarNav('dashboard');
        });
      }

      const navProfile = document.getElementById('nav-link-profile') || document.getElementById('nav-btn-profile');
      if (navProfile) {
        navProfile.addEventListener('click', (e) => {
          e.preventDefault();
          handleDemonstratorSidebarNav('security');
        });
      }
    });

    // Support popstate (browser back / forward button)
    window.addEventListener('popstate', () => {
      const urlParams = new URLSearchParams(window.location.search);
      const panel = urlParams.get('panel') || 'dashboard';
      handleDemonstratorSidebarNav(panel);
    });
  </script>

  @include('partials.support_desk_overlay')
</body>
</html>
