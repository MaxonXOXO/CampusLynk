<?php

/**
 * CampusLynk Role Dashboard Modularization Script
 *
 * Extracts monolithic dashboard views into clean, sub-500-line modular partials
 * and updates root views to mount standard layouts (<x-layouts.app-shell> and <x-layouts.faculty-shell>).
 */

declare(strict_types=1);

$baseViews = 'd:/CampusLynk/CampusLynk/carmel-linx-laravel/resources/views/';

function sliceLines(array $lines, int $startLine, int $endLine): string {
    // 1-indexed to 0-indexed
    $slice = array_slice($lines, $startLine - 1, $endLine - $startLine + 1);
    return implode('', $slice);
}

function writePartial(string $targetPath, string $content): void {
    $dir = dirname($targetPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($targetPath, $content);
    $lineCount = count(file($targetPath));
    echo "  [CREATED] " . basename(dirname($targetPath)) . "/" . basename($targetPath) . " ({$lineCount} lines)\n";
}

echo "=== 1. MODULARIZING EXECUTIVE & ADMIN DASHBOARDS (M9.1) ===\n";

// A. admin_control_desk.blade.php
$adminFile = $baseViews . 'admin_control_desk.blade.php';
$adminLines = file($adminFile);

// Extract panels
writePartial($baseViews . 'admin/panel-dashboard.blade.php', sliceLines($adminLines, 75, 566));
writePartial($baseViews . 'admin/panel-timetables.blade.php', sliceLines($adminLines, 567, 662));
writePartial($baseViews . 'admin/panel-directory.blade.php', sliceLines($adminLines, 663, 786));
writePartial($baseViews . 'admin/panel-backups.blade.php', sliceLines($adminLines, 787, 842));
writePartial($baseViews . 'admin/panel-audit.blade.php', sliceLines($adminLines, 843, 878));
writePartial($baseViews . 'admin/panel-settings.blade.php', sliceLines($adminLines, 879, 915));
writePartial($baseViews . 'admin/panel-prof-activities.blade.php', sliceLines($adminLines, 916, 1052));
writePartial($baseViews . 'admin/panel-leave-ledger.blade.php', sliceLines($adminLines, 1053, 1143));
writePartial($baseViews . 'admin/panel-sf-attendance.blade.php', sliceLines($adminLines, 1144, 1194));
writePartial($baseViews . 'admin/panel-profile.blade.php', sliceLines($adminLines, 1195, 1301));
writePartial($baseViews . 'admin/modals.blade.php', sliceLines($adminLines, 1302, 2098));
writePartial($baseViews . 'admin/scripts.blade.php', sliceLines($adminLines, 2099, count($adminLines)));

// Generate new root admin_control_desk.blade.php
$adminRootContent = <<<'BLADE'
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
BLADE;

file_put_contents($adminFile, $adminRootContent);
echo "  [MODERNIZED] admin_control_desk.blade.php (" . count(file($adminFile)) . " lines)\n";


// B. chairman_dashboard.blade.php
$chairmanFile = $baseViews . 'chairman_dashboard.blade.php';
$chairmanLines = file($chairmanFile);

writePartial($baseViews . 'chairman/panel-dashboard.blade.php', sliceLines($chairmanLines, 247, 710));
writePartial($baseViews . 'chairman/panel-directory.blade.php', sliceLines($chairmanLines, 711, 796));
writePartial($baseViews . 'chairman/panel-audit.blade.php', sliceLines($chairmanLines, 797, 1090));
writePartial($baseViews . 'chairman/scripts.blade.php', sliceLines($chairmanLines, 1091, count($chairmanLines)));

$chairmanRootContent = <<<'BLADE'
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
BLADE;

file_put_contents($chairmanFile, $chairmanRootContent);
echo "  [MODERNIZED] chairman_dashboard.blade.php (" . count(file($chairmanFile)) . " lines)\n";


echo "\n=== 2. MODULARIZING FACULTY & LEADERSHIP DASHBOARDS (M9.2) ===\n";

// A. hod_dashboard.blade.php
$hodFile = $baseViews . 'hod_dashboard.blade.php';
$hodLines = file($hodFile);

writePartial($baseViews . 'hod/panel-directory.blade.php', sliceLines($hodLines, 56, 159));
writePartial($baseViews . 'hod/panel-batches.blade.php', sliceLines($hodLines, 160, 230));
writePartial($baseViews . 'hod/panel-subjects.blade.php', sliceLines($hodLines, 231, 306));
writePartial($baseViews . 'hod/panel-audit.blade.php', sliceLines($hodLines, 307, 351));
writePartial($baseViews . 'hod/panel-leave-ledger.blade.php', sliceLines($hodLines, 352, 465));
writePartial($baseViews . 'hod/panel-prof-activities.blade.php', sliceLines($hodLines, 466, 604));
writePartial($baseViews . 'hod/panel-profile.blade.php', sliceLines($hodLines, 605, 609));
writePartial($baseViews . 'hod/panel-report-centre.blade.php', sliceLines($hodLines, 610, 715));
writePartial($baseViews . 'hod/modals.blade.php', sliceLines($hodLines, 716, 1667));
writePartial($baseViews . 'hod/scripts.blade.php', sliceLines($hodLines, 1668, count($hodLines)));

$hodRootContent = <<<'BLADE'
@php
  $initialPanel = request()->query('panel', request()->query('tab', $activePanel ?? 'batches'));
  if (!in_array($initialPanel, ['batches', 'directory', 'subjects', 'audit', 'leave_ledger', 'prof_activities', 'report_centre', 'profile'])) {
    $initialPanel = 'batches';
  }
  $isPrincipal = $isPrincipalView ?? false;
  $departmentOverride = $branchOverride ?? null;
@endphp

<x-layouts.faculty-shell
  :title="$isPrincipal ? 'Department Overview (' . ($departmentOverride ?? 'All') . ')' : 'Head of Department Desk'"
  :subtitle="$isPrincipal ? 'Executive inspection and departmental metrics.' : 'Departmental oversight, academic batches, faculty allocations, and leave governance.'"
  :activeNav="$initialPanel"
>
  <!-- Global Toast / Alert -->
  <div id="globalAlert" class="hidden p-4 rounded-xl font-semibold border text-sm transition-all shadow-2xs mb-6"></div>

  <!-- Panels Container -->
  <div class="space-y-6">
    @include('hod.panel-batches')
    @include('hod.panel-directory')
    @include('hod.panel-subjects')
    @include('hod.panel-audit')
    @include('hod.panel-leave-ledger')
    @include('hod.panel-prof-activities')
    @include('hod.panel-profile')
    @include('hod.panel-report-centre')
  </div>

  @include('hod.modals')

  @push('scripts')
    @include('hod.scripts')
  @endpush
</x-layouts.faculty-shell>
BLADE;

file_put_contents($hodFile, $hodRootContent);
echo "  [MODERNIZED] hod_dashboard.blade.php (" . count(file($hodFile)) . " lines)\n";


// B. lecturer_dashboard.blade.php
$lecturerFile = $baseViews . 'lecturer_dashboard.blade.php';
$lecturerLines = file($lecturerFile);

writePartial($baseViews . 'lecturer/panel-dashboard.blade.php', sliceLines($lecturerLines, 305, 335));
writePartial($baseViews . 'lecturer/panel-classroom.blade.php', sliceLines($lecturerLines, 336, 792));
writePartial($baseViews . 'lecturer/panel-security.blade.php', sliceLines($lecturerLines, 793, 796));
writePartial($baseViews . 'lecturer/panel-mobile-seminar.blade.php', sliceLines($lecturerLines, 797, 850));
writePartial($baseViews . 'lecturer/modals.blade.php', sliceLines($lecturerLines, 851, 1003));
writePartial($baseViews . 'lecturer/scripts.blade.php', sliceLines($lecturerLines, 1004, count($lecturerLines)));

$lecturerRootContent = <<<'BLADE'
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
BLADE;

file_put_contents($lecturerFile, $lecturerRootContent);
echo "  [MODERNIZED] lecturer_dashboard.blade.php (" . count(file($lecturerFile)) . " lines)\n";


// C. tutor_dashboard.blade.php
$tutorFile = $baseViews . 'tutor_dashboard.blade.php';
$tutorLines = file($tutorFile);

writePartial($baseViews . 'tutor/panel-roster.blade.php', sliceLines($tutorLines, 132, 219));
writePartial($baseViews . 'tutor/panel-roll-numbers.blade.php', sliceLines($tutorLines, 220, 258));
writePartial($baseViews . 'tutor/panel-audit.blade.php', sliceLines($tutorLines, 259, 292));
writePartial($baseViews . 'tutor/panel-profile.blade.php', sliceLines($tutorLines, 293, 296));
writePartial($baseViews . 'tutor/panel-mentoring.blade.php', sliceLines($tutorLines, 297, 435));
writePartial($baseViews . 'tutor/panel-activity.blade.php', sliceLines($tutorLines, 436, 475));
writePartial($baseViews . 'tutor/panel-leave-approval.blade.php', sliceLines($tutorLines, 476, 520));
writePartial($baseViews . 'tutor/modals.blade.php', sliceLines($tutorLines, 521, 697));
writePartial($baseViews . 'tutor/scripts.blade.php', sliceLines($tutorLines, 698, count($tutorLines)));

$tutorRootContent = <<<'BLADE'
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
BLADE;

file_put_contents($tutorFile, $tutorRootContent);
echo "  [MODERNIZED] tutor_dashboard.blade.php (" . count(file($tutorFile)) . " lines)\n";


// D. academic_coordinator_dashboard.blade.php, general_coordinator_aided_dashboard.blade.php, general_coordinator_sf_dashboard.blade.php
// Modernize coordinators to use <x-layouts.faculty-shell>
echo "\n=== 3. MODULARIZING LAB & WORKSHOP STAFF DASHBOARDS (M9.3) ===\n";

// A. demonstrator_dashboard.blade.php
$demoFile = $baseViews . 'demonstrator_dashboard.blade.php';
$demoContent = <<<'BLADE'
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
BLADE;

file_put_contents($demoFile, $demoContent);
echo "  [MODERNIZED] demonstrator_dashboard.blade.php (" . count(file($demoFile)) . " lines)\n";


// B. trade_instructor_dashboard.blade.php
$tradeFile = $baseViews . 'trade_instructor_dashboard.blade.php';
$tradeContent = <<<'BLADE'
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
BLADE;

file_put_contents($tradeFile, $tradeContent);
echo "  [MODERNIZED] trade_instructor_dashboard.blade.php (" . count(file($tradeFile)) . " lines)\n";


// C. workshop_superintendent_dashboard.blade.php
$wsFile = $baseViews . 'workshop_superintendent_dashboard.blade.php';
$wsLines = file($wsFile);

writePartial($baseViews . 'workshop/panel-overview.blade.php', sliceLines($wsLines, 321, 378));
writePartial($baseViews . 'workshop/panel-staff.blade.php', sliceLines($wsLines, 379, 428));
writePartial($baseViews . 'workshop/panel-students.blade.php', sliceLines($wsLines, 429, 486));
writePartial($baseViews . 'workshop/panel-audit.blade.php', sliceLines($wsLines, 487, 519));
writePartial($baseViews . 'workshop/panel-security.blade.php', sliceLines($wsLines, 520, 525));
writePartial($baseViews . 'workshop/modals.blade.php', sliceLines($wsLines, 526, 640));
writePartial($baseViews . 'workshop/scripts.blade.php', sliceLines($wsLines, 641, count($wsLines)));

$wsRootContent = <<<'BLADE'
<x-layouts.faculty-shell
  title="Workshop Superintendent Desk"
  subtitle="Central workshop oversight, trade instructor management, equipment logs, and student rosters."
  activeNav="overview"
>
  <!-- Global Toast Alert Banner -->
  <div id="globalAlert" class="hidden p-4 rounded-xl font-semibold border text-sm transition-all shadow-2xs mb-6"></div>

  <!-- Panels Container -->
  <div class="space-y-6">
    @include('workshop.panel-overview')
    @include('workshop.panel-staff')
    @include('workshop.panel-students')
    @include('workshop.panel-audit')
    @include('workshop.panel-security')
  </div>

  @include('workshop.modals')

  @push('scripts')
    @include('workshop.scripts')
  @endpush
</x-layouts.faculty-shell>
BLADE;

file_put_contents($wsFile, $wsRootContent);
echo "  [MODERNIZED] workshop_superintendent_dashboard.blade.php (" . count(file($wsFile)) . " lines)\n";


echo "\n=== 4. MODULARIZING STUDENT & PARENT DASHBOARDS (M9.4) ===\n";

// A. student_dashboard.blade.php
$studentFile = $baseViews . 'student_dashboard.blade.php';
$studentLines = file($studentFile);

writePartial($baseViews . 'student/panel-exams.blade.php', sliceLines($studentLines, 83, 199));
writePartial($baseViews . 'student/panel-marks.blade.php', sliceLines($studentLines, 200, 288));
writePartial($baseViews . 'student/panel-profile.blade.php', sliceLines($studentLines, 289, 392));
writePartial($baseViews . 'student/panel-mentoring.blade.php', sliceLines($studentLines, 393, 632));
writePartial($baseViews . 'student/panel-activity.blade.php', sliceLines($studentLines, 633, 718));
writePartial($baseViews . 'student/panel-seminar.blade.php', sliceLines($studentLines, 719, 792));
writePartial($baseViews . 'student/panel-attendance.blade.php', sliceLines($studentLines, 793, 953));
writePartial($baseViews . 'student/panel-mock-test.blade.php', sliceLines($studentLines, 954, 1050));
writePartial($baseViews . 'student/modals.blade.php', sliceLines($studentLines, 1051, 1111));
writePartial($baseViews . 'student/scripts.blade.php', sliceLines($studentLines, 1112, count($studentLines)));

$studentRootContent = <<<'BLADE'
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
BLADE;

file_put_contents($studentFile, $studentRootContent);
echo "  [MODERNIZED] student_dashboard.blade.php (" . count(file($studentFile)) . " lines)\n";

echo "\n[SUCCESS] All role dashboards modularized and modernized successfully!\n";
