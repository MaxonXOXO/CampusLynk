@php
  $currentPanel = request('panel', request('tab', 'dashboard'));
  $isSecurityPanel = in_array($currentPanel, ['security', 'profile']);
  $activeNav = $isSecurityPanel ? 'profile' : 'my_batches';
  $userRole = session('userRole', 'Trade_Instructor');
  $userDesg = str_replace('_', ' ', $userRole);
@endphp
<!DOCTYPE html>
<html lang="en" class="h-full bg-[#FAFAFB]">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Instructor Console — Carmel Linx</title>

  <!-- Google Fonts: Poppins -->
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

  <!-- Compiled Application Assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body { font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
    .transition-premium { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
</head>
<body class="bg-[#FAFAFB] text-slate-900 min-h-screen font-sans antialiased sidebar-preload">

  <!-- Master Application Shell Container -->
  <div class="flex min-h-screen">

    <!-- Global Dedicated Trade Instructor Sidebar Navigation -->
    <x-layout.sidebar role="trade_instructor" :active="$activeNav" />

    <!-- Main Viewport Container -->
    <div class="flex-1 flex flex-col min-w-0">

      <!-- Global Topbar Header Component -->
      <x-layout.topbar 
        :title="$isSecurityPanel ? 'My Profile & Security' : 'Trade & Workshop Tasks'" 
        :subtitle="$isSecurityPanel ? 'Manage personal authentication, biometrics, and credentials.' : 'Assigned practical sessions, trade portfolios, and workshop equipment operations.'" 
      />

      <!-- Scrollable Main Workspace Canvas -->
      <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">

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
                    <div class="flex items-center gap-2 flex-wrap">
                      <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $s->branch ?? session('userBranch', 'N/A') }}
                      </span>
                      @if(isset($s->batch_year))
                        <span class="px-2 py-0.5 rounded-md text-xs font-medium bg-slate-50 text-slate-600 border border-slate-200">
                          Batch {{ $s->batch_year }}
                        </span>
                      @endif
                    </div>
                    <span class="px-2 py-0.5 rounded-md text-xs font-semibold {{ ($s->syllabus_revision_code ?? '') === 'REV2026' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">
                      {{ $s->syllabus_revision_code ?? 'REV2021' }}
                    </span>
                  </div>

                  <h4 class="font-bold text-slate-900 text-base leading-snug group-hover:text-blue-600 transition-colors mb-1">
                    {{ $s->subject_name }}
                  </h4>
                  <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                    <span class="font-mono font-semibold text-slate-700">{{ $s->subject_code }}</span>
                    <span>•</span>
                    <span>Semester {{ $s->semester }}</span>
                    <span>•</span>
                    <span class="capitalize">{{ str_replace('_', ' ', $s->subject_type ?? 'Practical') }}</span>
                  </div>
                </div>

                @php
                  $isR26 = ($s->syllabus_revision_code ?? '') === 'REV2026';
                  $sTypeLower = strtolower($s->subject_type ?? '');
                  $sNameLower = strtolower($s->subject_name ?? '');

                  if ($isR26) {
                    if (str_contains($sTypeLower, 'health') || str_contains($sNameLower, 'physical') || str_contains($sNameLower, 'health')) {
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

                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                  <span class="text-xs text-slate-400 font-medium">Classroom ID: {{ $s->classroom_id }}</span>
                  <a href="{{ $targetUrl }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white transition-all shadow-2xs no-underline">
                    <span>Enter Workshop</span>
                    <x-ui.icon name="arrow-right" class="w-3.5 h-3.5" />
                  </a>
                </div>
              </div>
            @empty
              <div class="col-span-full bg-white border border-slate-200/80 p-12 rounded-2xl text-center shadow-xs">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 border border-blue-200 text-blue-500 flex items-center justify-center mx-auto mb-4">
                  <x-ui.icon name="wrench" class="w-8 h-8" />
                </div>
                <h4 class="font-bold text-slate-900 text-base">Trade &amp; Workshop Console Connected</h4>
                <p class="text-sm text-slate-500 max-w-lg mx-auto mt-1.5">
                  You are ready to map workshop tasks, evaluate trade-level practical portfolios, and manage student attendance registries. When your Head of Department assigns active practical sections, they will appear here automatically.
                </p>
                <div class="flex items-center justify-center gap-3 mt-6">
                  <a href="/staff/attendance-log" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 transition-colors shadow-2xs no-underline">
                    <x-ui.icon name="clipboard-check" class="w-4 h-4 text-blue-400" />
                    <span>Open Attendance Log</span>
                  </a>
                  <a href="/staff/professional-activities" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs no-underline">
                    <x-ui.icon name="award" class="w-4 h-4 text-amber-500" />
                    <span>Professional Activities</span>
                  </a>
                </div>
              </div>
            @endforelse
          </div>

        </div>

        <!-- PANEL 2: MY PROFILE & SECURITY -->
        <div id="panelSecurity" class="{{ $isSecurityPanel ? '' : 'hidden' }} space-y-6">
          @include('partials.staff_profile_panel', ['hideAuditLog' => true])
        </div>

      </main>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
    let currentPanel = '{{ $currentPanel }}';

    function handleInstructorSidebarNav(panel) {
      currentPanel = panel;
      
      const dashPane = document.getElementById('panelDashboard');
      const secPane = document.getElementById('panelSecurity');
      const navBatches = document.getElementById('nav-link-my_batches') || document.getElementById('nav-btn-my_batches');
      const navProfile = document.getElementById('nav-link-profile') || document.getElementById('nav-btn-profile');

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

        window.history.pushState(null, '', '/dashboard/tradeinstructor?panel=security');
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

        window.history.pushState(null, '', '/dashboard/tradeinstructor');
      }
    }

    // Attach click listeners for instant tab switching when already on the dashboard
    document.addEventListener('DOMContentLoaded', () => {
      const navBatches = document.getElementById('nav-link-my_batches') || document.getElementById('nav-btn-my_batches');
      if (navBatches) {
        navBatches.addEventListener('click', (e) => {
          e.preventDefault();
          handleInstructorSidebarNav('dashboard');
        });
      }

      const navProfile = document.getElementById('nav-link-profile') || document.getElementById('nav-btn-profile');
      if (navProfile) {
        navProfile.addEventListener('click', (e) => {
          e.preventDefault();
          handleInstructorSidebarNav('security');
        });
      }
    });

    // Support popstate (browser back / forward button)
    window.addEventListener('popstate', () => {
      const urlParams = new URLSearchParams(window.location.search);
      const panel = urlParams.get('panel') || 'dashboard';
      handleInstructorSidebarNav(panel);
    });
  </script>

  @include('partials.support_desk_overlay')
</body>
</html>
