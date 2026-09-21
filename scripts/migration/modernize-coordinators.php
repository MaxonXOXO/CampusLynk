<?php

declare(strict_types=1);

$baseViews = 'd:/CampusLynk/CampusLynk/carmel-linx-laravel/resources/views/';

function sliceLines(array $lines, int $startLine, int $endLine): string {
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

// 1. academic_coordinator_dashboard.blade.php
$acadFile = $baseViews . 'academic_coordinator_dashboard.blade.php';
$acadLines = file($acadFile);

writePartial($baseViews . 'academic_coordinator/panel-dashboard.blade.php', sliceLines($acadLines, 458, 564));
writePartial($baseViews . 'academic_coordinator/panel-directory.blade.php', sliceLines($acadLines, 565, 610));
writePartial($baseViews . 'academic_coordinator/panel-reports.blade.php', sliceLines($acadLines, 611, 719));
writePartial($baseViews . 'academic_coordinator/panel-security.blade.php', sliceLines($acadLines, 720, 755));
writePartial($baseViews . 'academic_coordinator/scripts.blade.php', sliceLines($acadLines, 756, count($acadLines)));

$acadRoot = <<<'BLADE'
<x-layouts.faculty-shell
  title="Academic Coordinator Portal"
  subtitle="Self-financing staff oversight, leave governance, academic metrics, and audit ledger."
  activeNav="dashboard"
>
  <!-- Global Alert -->
  <div id="globalAlert" class="hidden p-4 rounded-xl font-semibold border text-sm transition-all shadow-2xs mb-6"></div>

  <!-- Navigation Tab Bar -->
  <div class="flex items-center gap-2 overflow-x-auto border-b border-slate-200 pb-3 mb-6 custom-scrollbar">
    <button type="button" onclick="switchPanel('dashboard')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 text-white">Overview</button>
    <button type="button" onclick="switchPanel('directory')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200">Staff Directory</button>
    <button type="button" onclick="switchPanel('reports')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200">Leave Master Ledger</button>
    <button type="button" onclick="switchPanel('security')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200">Security Log</button>
  </div>

  <!-- Panels Container -->
  <div class="space-y-6">
    @include('academic_coordinator.panel-dashboard')
    @include('academic_coordinator.panel-directory')
    @include('academic_coordinator.panel-reports')
    @include('academic_coordinator.panel-security')
  </div>

  @push('scripts')
    @include('academic_coordinator.scripts')
  @endpush
</x-layouts.faculty-shell>
BLADE;

file_put_contents($acadFile, $acadRoot);
echo "  [MODERNIZED] academic_coordinator_dashboard.blade.php (" . count(file($acadFile)) . " lines)\n";


// 2. general_coordinator_aided_dashboard.blade.php
$aidedFile = $baseViews . 'general_coordinator_aided_dashboard.blade.php';
$aidedLines = file($aidedFile);

writePartial($baseViews . 'general_coordinator/panel-dashboard-aided.blade.php', sliceLines($aidedLines, 294, 304));
writePartial($baseViews . 'general_coordinator/panel-directory-aided.blade.php', sliceLines($aidedLines, 305, 345));
writePartial($baseViews . 'general_coordinator/scripts-aided.blade.php', sliceLines($aidedLines, 346, count($aidedLines)));

$aidedRoot = <<<'BLADE'
<x-layouts.faculty-shell
  title="General Coordinator (Aided) Desk"
  subtitle="First-year and foundational academics, aided staff directory, and institutional monitoring."
  activeNav="dashboard"
>
  <!-- Navigation Tab Bar -->
  <div class="flex items-center gap-2 overflow-x-auto border-b border-slate-200 pb-3 mb-6 custom-scrollbar">
    <button type="button" onclick="switchPanel('dashboard')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 text-white">Overview</button>
    <button type="button" onclick="switchPanel('directory')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200">User Directory</button>
  </div>

  <!-- Panels Container -->
  <div class="space-y-6">
    @include('general_coordinator.panel-dashboard-aided')
    @include('general_coordinator.panel-directory-aided')
  </div>

  @push('scripts')
    @include('general_coordinator.scripts-aided')
  @endpush
</x-layouts.faculty-shell>
BLADE;

file_put_contents($aidedFile, $aidedRoot);
echo "  [MODERNIZED] general_coordinator_aided_dashboard.blade.php (" . count(file($aidedFile)) . " lines)\n";


// 3. general_coordinator_sf_dashboard.blade.php
$sfFile = $baseViews . 'general_coordinator_sf_dashboard.blade.php';
$sfLines = file($sfFile);

writePartial($baseViews . 'general_coordinator/panel-dashboard-sf.blade.php', sliceLines($sfLines, 300, 310));
writePartial($baseViews . 'general_coordinator/panel-directory-sf.blade.php', sliceLines($sfLines, 311, 351));
writePartial($baseViews . 'general_coordinator/scripts-sf.blade.php', sliceLines($sfLines, 352, count($sfLines)));

$sfRoot = <<<'BLADE'
<x-layouts.faculty-shell
  title="General Coordinator (Self-Finance) Desk"
  subtitle="Self-financing department coordination, faculty allocations, and foundational academics."
  activeNav="dashboard"
>
  <!-- Navigation Tab Bar -->
  <div class="flex items-center gap-2 overflow-x-auto border-b border-slate-200 pb-3 mb-6 custom-scrollbar">
    <button type="button" onclick="switchPanel('dashboard')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 text-white">Overview</button>
    <button type="button" onclick="switchPanel('directory')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200">User Directory</button>
  </div>

  <!-- Panels Container -->
  <div class="space-y-6">
    @include('general_coordinator.panel-dashboard-sf')
    @include('general_coordinator.panel-directory-sf')
  </div>

  @push('scripts')
    @include('general_coordinator.scripts-sf')
  @endpush
</x-layouts.faculty-shell>
BLADE;

file_put_contents($sfFile, $sfRoot);
echo "  [MODERNIZED] general_coordinator_sf_dashboard.blade.php (" . count(file($sfFile)) . " lines)\n";
