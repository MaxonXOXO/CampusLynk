<x-layouts.app-shell activeNav="my_leave">
  @php
    $sessionRole = session('userRole');
    $isHod = ($sessionRole === 'HOD');
    $isPrincipal = in_array($sessionRole, ['Principal', 'Executive']);
    $isAdmin = in_array($sessionRole, ['Admin', 'Super_Admin', 'SuperAdmin']);
    
    $backUrl = '/dashboard/lecturer';
    $backLabel = 'Faculty Platform';
    
    if ($isHod) {
        $backUrl = '/dashboard/hod';
        $backLabel = 'Department Console (HOD)';
    } elseif ($isPrincipal) {
        $backUrl = '/dashboard/principal';
        $backLabel = 'Principal Desk';
    } elseif ($isAdmin) {
        $backUrl = '/dashboard/admin';
        $backLabel = 'Admin Desk';
    }
  @endphp

  <div class="space-y-6 w-full pb-12">


    <!-- Workspace Header & Hero Card -->
    <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-5">
      <div class="flex items-start sm:items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shrink-0">
          <x-ui.icon name="event_available" class="w-6 h-6 text-blue-600" />
        </div>
        <div>
          <div class="flex items-center gap-2.5 flex-wrap">
            <h3 class="text-lg font-bold text-slate-900">My Leave &amp; Attendance</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/80">
              {{ $staff->department ?? session('userBranch', 'General') }} Department
            </span>
          </div>
          <p class="text-sm text-slate-500 mt-1">Manage your leave requests, balances, attendance, and approval status.</p>
        </div>
      </div>

      <!-- Action Area -->
      <div class="flex items-center gap-3 shrink-0">
        <button type="button" onclick="openApplyLeaveModal()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm rounded-xl shadow-xs transition-all flex items-center gap-2 cursor-pointer shrink-0">
          <x-ui.icon name="add_circle" class="w-4 h-4 shrink-0" />
          <span>Apply Leave</span>
        </button>
      </div>
    </div>

    <!-- Workspace Grid: 4 Core Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <!-- ========================================================================= -->
      <!-- SECTION 1: LEAVE BALANCE SUMMARY (PHASE 2D.2)                             -->
      <!-- ========================================================================= -->
      <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between space-y-5">
        <div>
          <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shrink-0">
                <x-ui.icon name="pie_chart" class="w-5 h-5 text-blue-600" />
              </div>
              <div>
                <h4 class="text-base font-bold text-slate-900">1. Leave Balance Summary</h4>
                <p class="text-xs text-slate-500 mt-0.5">Annual entitlements and category balances (AY {{ date('Y') }}–{{ date('Y') + 1 }})</p>
              </div>
            </div>
            <button type="button" onclick="loadLeaveBalances()" id="btnRefreshBalances" class="px-3.5 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs group shrink-0" title="Refresh Leave Balances">
              <x-ui.icon name="sync" class="w-3.5 h-3.5 text-slate-500 group-hover:text-blue-600 transition-transform group-hover:rotate-180 duration-500 shrink-0" />
              <span>Refresh</span>
            </button>
          </div>

          <!-- Leave Balances Cards Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mt-4">
            
            <!-- Casual Leave (CL) Main Card -->
            <div class="p-4 rounded-xl bg-gradient-to-br from-blue-50/50 to-white border border-blue-100/80 shadow-2xs flex flex-col justify-between space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-900 flex items-center gap-1.5">
                  <i data-lucide="calendar" class="w-3.5 h-3.5 text-blue-600"></i> Casual Leave (CL)
                </span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800">15 Days Quota</span>
              </div>
              <div>
                <div class="flex items-baseline justify-between">
                  <span id="clRemaining" class="text-2xl font-bold text-slate-900 font-mono">15.0</span>
                  <span class="text-xs text-slate-500 font-medium">Days Available</span>
                </div>
                <!-- Progress Bar -->
                <div class="w-full bg-slate-100 rounded-full h-2 mt-2 overflow-hidden border border-slate-200/60">
                  <div id="clProgressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: 0%;"></div>
                </div>
              </div>
              <div class="flex items-center justify-between text-xs text-slate-500 font-medium pt-1 border-t border-blue-100/60">
                <span>Taken: <strong id="clTaken" class="text-slate-800 font-mono font-bold">0.0</strong> Days</span>
                <span id="clPercentText" class="text-[11px] text-blue-700 font-semibold">0% Used</span>
              </div>
            </div>

            <!-- Compensatory Casual Leave (CCL) Card -->
            <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 shadow-2xs flex flex-col justify-between space-y-3">
              <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-1.5 truncate">
                  <i data-lucide="clock-check" class="w-3.5 h-3.5 text-amber-600 shrink-0"></i> Compensatory (CCL)
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/60 whitespace-nowrap shrink-0">Earned Duty</span>
              </div>
              <div>
                <div class="flex items-baseline justify-between">
                  <span id="cclTaken" class="text-2xl font-bold text-slate-900 font-mono">0.0</span>
                  <span class="text-xs text-slate-500 font-medium">Days Taken</span>
                </div>
                <p class="text-xs text-slate-500 mt-1 leading-snug">
                  Earned against verified institutional holiday duty.
                </p>
              </div>
              <div class="text-[11px] text-slate-400 font-medium pt-1 border-t border-slate-200/60">
                Requires approved CCL duty date
              </div>
            </div>

            <!-- Duty Leave (DL) Card -->
            <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 shadow-2xs flex flex-col justify-between space-y-3">
              <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-1.5 truncate">
                  <i data-lucide="briefcase" class="w-3.5 h-3.5 text-indigo-600 shrink-0"></i> Duty Leave (DL)
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-800 border border-indigo-200/60 whitespace-nowrap shrink-0">Official</span>
              </div>
              <div>
                <div class="flex items-baseline justify-between">
                  <span id="dlTaken" class="text-2xl font-bold text-slate-900 font-mono">0.0</span>
                  <span class="text-xs text-slate-500 font-medium">Days Taken</span>
                </div>
                <p class="text-xs text-slate-500 mt-1 leading-snug">
                  External exam, university, or deputation duties.
                </p>
              </div>
              <div class="text-[11px] text-slate-400 font-medium pt-1 border-t border-slate-200/60">
                Requires attendance certificate
              </div>
            </div>

            <!-- Medical Leave (ML) Card -->
            <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 shadow-2xs flex flex-col justify-between space-y-3">
              <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-1.5 truncate">
                  <i data-lucide="heart-pulse" class="w-3.5 h-3.5 text-rose-600 shrink-0"></i> Medical Leave (ML)
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-800 border border-rose-200/60 whitespace-nowrap shrink-0">Health</span>
              </div>
              <div>
                <div class="flex items-baseline justify-between">
                  <span id="mlTaken" class="text-2xl font-bold text-slate-900 font-mono">0.0</span>
                  <span class="text-xs text-slate-500 font-medium">Days Taken</span>
                </div>
                <p class="text-xs text-slate-500 mt-1 leading-snug">
                  Health &amp; medical certificated leave applications.
                </p>
              </div>
              <div class="text-[11px] text-slate-400 font-medium pt-1 border-t border-slate-200/60">
                Requires medical certificate
              </div>
            </div>

          </div>

          <!-- Secondary Leave Types Row -->
          <div class="flex flex-wrap items-center justify-between gap-3 mt-3 p-3 rounded-xl bg-slate-50/60 border border-slate-200/60 text-xs">
            <div class="flex items-center gap-4 flex-wrap">
              <span class="text-slate-600">Loss of Pay (LOP): <strong id="lopTaken" class="text-slate-900 font-mono font-bold">0.0</strong> d</span>
              <span class="text-slate-300">•</span>
              <span class="text-slate-600">Special Leave (SL): <strong id="slTaken" class="text-slate-900 font-mono font-bold">0.0</strong> d</span>
            </div>
            <div class="font-semibold text-slate-700">
              Total Absent Days: <span id="totalLeaveTaken" class="text-blue-700 font-mono font-bold">0.0</span>
            </div>
          </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 font-medium">
          <span>Official R2026 Leave Standard</span>
          <span id="balanceSyncStatus" class="flex items-center gap-1 text-slate-500">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Live Ledger Synced
          </span>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- SECTION 2: TODAY'S ATTENDANCE RECORD (PHASE 2D.2)                         -->
      <!-- ========================================================================= -->
      @php
        $inTimeFormatted = ($todayPunch && $todayPunch->in_time) ? date('h:i A', strtotime($todayPunch->in_time)) : null;
        $outTimeFormatted = ($todayPunch && $todayPunch->out_time) ? date('h:i A', strtotime($todayPunch->out_time)) : null;
        $isPunchedIn = !empty($inTimeFormatted);
        $isPunchedOut = !empty($outTimeFormatted);
        $isCompleted = $isPunchedIn && $isPunchedOut;

        $overallStateLabel = 'NOT PUNCHED';
        $stateBadgeClass = 'bg-amber-50 text-amber-700 border-amber-200/80';
        if ($isCompleted) {
            $overallStateLabel = 'COMPLETED';
            $stateBadgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/80';
        } elseif ($isPunchedIn) {
            $overallStateLabel = 'CHECKED IN';
            $stateBadgeClass = 'bg-blue-50 text-blue-700 border-blue-200/80';
        }

        $inStatusLabel = 'PRESENT';
        if ($isPunchedIn && $todayPunch->in_time) {
            $inHi = date('H:i', strtotime($todayPunch->in_time));
            if ($inHi < '08:45') {
                $inStatusLabel = 'EARLY IN';
            } elseif ($inHi > '09:15') {
                $inStatusLabel = 'LATE IN';
            }
        }

        $outStatusLabel = 'OUT RECORDED';
        if ($isPunchedOut && $todayPunch->out_time) {
            $outHi = date('H:i', strtotime($todayPunch->out_time));
            if ($outHi < '16:00') {
                $outStatusLabel = 'EARLY OUT';
            } elseif ($outHi > '16:30') {
                $outStatusLabel = 'LATE OUT';
            } else {
                $outStatusLabel = 'ON TIME OUT';
            }
        }

        $workingDuration = '--';
        $workingDurationSubtitle = 'Pending Check-in';
        if ($isCompleted) {
            $tIn = strtotime($todayPunch->punch_date . ' ' . $todayPunch->in_time);
            $tOut = strtotime($todayPunch->punch_date . ' ' . $todayPunch->out_time);
            $diffSec = max(0, $tOut - $tIn);
            $hrs = floor($diffSec / 3600);
            $mins = round(($diffSec % 3600) / 60);
            $workingDuration = "{$hrs}h {$mins}m";
            $workingDurationSubtitle = 'Completed Campus Residency';
        } elseif ($isPunchedIn) {
            $tIn = strtotime($todayPunch->punch_date . ' ' . $todayPunch->in_time);
            $diffSec = max(0, time() - $tIn);
            $hrs = floor($diffSec / 3600);
            $mins = round(($diffSec % 3600) / 60);
            $workingDuration = "{$hrs}h {$mins}m";
            $workingDurationSubtitle = 'Current Duration (In Progress)';
        }
      @endphp

      <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between space-y-5">
        <div>
          <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                <i data-lucide="clock" class="w-5 h-5 text-emerald-600"></i>
              </div>
              <div>
                <h4 class="text-base font-bold text-slate-900">2. Today's Attendance Record</h4>
                <p class="text-xs text-slate-500 mt-0.5">{{ date('l, d F Y') }}</p>
              </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $stateBadgeClass }}">
              <span class="w-1.5 h-1.5 rounded-full {{ $isCompleted ? 'bg-emerald-500' : ($isPunchedIn ? 'bg-blue-500' : 'bg-amber-500') }}"></span>
              {{ $overallStateLabel }}
            </span>
          </div>

          <!-- 3-Column Attendance Metrics Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mt-4">
            
            <!-- Morning IN Box -->
            <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 shadow-2xs flex flex-col justify-between space-y-2">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                <i data-lucide="sun" class="w-3.5 h-3.5 text-amber-500"></i> Morning IN
              </span>
              <div>
                <span class="text-xl font-bold font-mono {{ $isPunchedIn ? 'text-slate-900' : 'text-slate-400' }}">
                  {{ $inTimeFormatted ?? '--:--' }}
                </span>
                <p class="text-xs font-medium mt-0.5 {{ $isPunchedIn ? 'text-emerald-700' : 'text-slate-400' }}">
                  @if($isPunchedIn)
                    <span class="inline-flex items-center gap-1"><i data-lucide="check" class="w-3 h-3"></i> {{ $inStatusLabel }}</span>
                  @else
                    Pending Morning Entry
                  @endif
                </p>
              </div>
            </div>

            <!-- Evening OUT Box -->
            <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 shadow-2xs flex flex-col justify-between space-y-2">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                <i data-lucide="moon" class="w-3.5 h-3.5 text-indigo-500"></i> Evening OUT
              </span>
              <div>
                <span class="text-xl font-bold font-mono {{ $isPunchedOut ? 'text-slate-900' : 'text-slate-400' }}">
                  {{ $outTimeFormatted ?? '--:--' }}
                </span>
                <p class="text-xs font-medium mt-0.5 {{ $isPunchedOut ? 'text-blue-700' : 'text-slate-400' }}">
                  @if($isPunchedOut)
                    <span class="inline-flex items-center gap-1"><i data-lucide="check" class="w-3 h-3"></i> {{ $outStatusLabel }}</span>
                  @else
                    Pending Departure
                  @endif
                </p>
              </div>
            </div>

            <!-- Total Working Hours Box -->
            <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 shadow-2xs flex flex-col justify-between space-y-2">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                <i data-lucide="timer" class="w-3.5 h-3.5 text-emerald-600"></i> Working Hours
              </span>
              <div>
                <span class="text-xl font-bold font-mono {{ ($isPunchedIn || $isCompleted) ? 'text-emerald-700' : 'text-slate-400' }}">
                  {{ $workingDuration }}
                </span>
                <p class="text-xs font-medium text-slate-500 mt-0.5">
                  {{ $workingDurationSubtitle }}
                </p>
              </div>
            </div>

          </div>

          <!-- Geofence & Biometric Verification Status Panel -->
          <div class="mt-4 p-4 rounded-xl bg-slate-50/60 border border-slate-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                <i data-lucide="map-pin" class="w-4 h-4 text-emerald-700"></i>
              </div>
              <div>
                <span class="font-bold text-slate-800 block">Carmel Polytechnic Geofence</span>
                <span class="text-slate-500 text-[11px] block">
                  @if($todayPunch)
                    {{ $todayPunch->in_premises_status ?? 'On Campus' }} • Biometric Confidence: {{ round($todayPunch->biometric_confidence ?? 98) }}%
                  @else
                    Campus Geofence boundary active. Ready for biometric attendance verification.
                  @endif
                </span>
              </div>
            </div>

            @if(!$isCompleted)
              <div class="px-3.5 py-2 rounded-xl bg-blue-50/80 border border-blue-200/80 flex items-center gap-2 text-blue-900 text-xs shrink-0 shadow-2xs">
                <i data-lucide="smartphone" class="w-4 h-4 text-blue-600 shrink-0"></i>
                <span class="font-medium">Login with phone to punch attendance</span>
              </div>
            @else
              <span class="text-xs font-semibold text-emerald-700 flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200/60">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i> All punches recorded
              </span>
            @endif
          </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 font-medium">
          <span>Biometric &amp; Geofence Logging</span>
          <span>Official Institutional Ledger</span>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- SECTION 3: LEAVE APPLICATION HISTORY (PHASE 2D.3)                         -->
      <!-- ========================================================================= -->
      <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between space-y-5 lg:col-span-2">
        <div>
          <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 shrink-0">
                <x-ui.icon name="description" class="w-5 h-5 text-amber-600" />
              </div>
              <div>
                <h4 class="text-base font-bold text-slate-900">3. Leave Application History</h4>
                <p class="text-xs text-slate-500 mt-0.5">Submitted requests, multi-stage approval audit trail, and formal orders</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button type="button" onclick="loadLeaveHistory()" id="btnRefreshHistory" class="px-3.5 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs group shrink-0" title="Refresh History">
                <x-ui.icon name="sync" class="w-3.5 h-3.5 text-slate-500 group-hover:text-blue-600 transition-transform group-hover:rotate-180 duration-500 shrink-0" id="iconRefreshHistory" />
                <span>Refresh History</span>
              </button>
            </div>
          </div>

          <!-- Dynamic History Container -->
          <div id="leaveHistoryContainer" class="mt-4">
            <!-- Loading Skeleton -->
            <div class="py-12 text-center text-slate-400 space-y-3">
              <div class="w-8 h-8 mx-auto border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin"></div>
              <p class="text-xs font-medium">Loading your leave records...</p>
            </div>
          </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 font-medium">
          <span>Digital Signature Ledger &amp; Audit Trail</span>
          <span id="historyRecordsCount">0 Total Records</span>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- SECTION 4: MULTI-STAGE APPROVAL STATUS (PHASE 2D.4 IMPLEMENTED)           -->
      <!-- ========================================================================= -->
      <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between space-y-5 lg:col-span-2">
        <div>
          <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100 shrink-0">
                <x-ui.icon name="folder-open" class="w-5 h-5 text-purple-600" />
              </div>
              <div>
                <h4 class="text-base font-bold text-slate-900">4. Multi-Stage Approval Status &amp; Progression</h4>
                <p class="text-xs text-slate-500 mt-0.5">Hierarchical review tracking across Department HOD, Academic Coordinator, and Principal</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button type="button" onclick="loadApprovalStatus()" id="btnRefreshApproval" class="px-3.5 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs group shrink-0" title="Refresh Approval Progression">
                <x-ui.icon name="sync" class="w-3.5 h-3.5 text-slate-500 group-hover:text-purple-600 transition-transform group-hover:rotate-180 duration-500 shrink-0" id="iconRefreshApproval" />
                <span>Refresh Status</span>
              </button>
            </div>
          </div>

          <!-- Current Request Selector Strip (If multiple leaves exist) -->
          <div id="approvalRequestSelector" class="flex items-center gap-2 overflow-x-auto pb-2 pt-1 border-b border-slate-100 empty:hidden"></div>

          <!-- Main Detailed Timeline Container -->
          <div id="approvalTimelineContainer" class="mt-4">
            <!-- Loading Skeleton -->
            <div class="py-12 text-center text-slate-400 space-y-3">
              <div class="w-8 h-8 mx-auto border-2 border-slate-200 border-t-purple-600 rounded-full animate-spin"></div>
              <p class="text-xs font-medium">Tracking hierarchical approval progression...</p>
            </div>
          </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 font-medium">
          <span>Institutional Workflow &amp; Approval Governance</span>
          <span id="approvalActiveStreamNotice">Stream-Aware Routing</span>
        </div>
      </div>

    </div>

  </div>

  <!-- ========================================================================= -->
  <!-- MODAL: APPLY FOR LEAVE (PHASE 2D.3 IMPLEMENTED)                            -->
  <!-- ========================================================================= -->
  <div id="applyLeaveModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 hidden items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xl max-w-2xl w-full my-8 overflow-hidden transform transition-all animate-in fade-in zoom-in-95 duration-150">
      
      <!-- Modal Header -->
      <div class="p-6 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shrink-0">
            <i data-lucide="file-signature" class="w-5 h-5 text-blue-600"></i>
          </div>
          <div>
            <h3 class="text-base font-bold text-slate-900">Apply for Staff Leave</h3>
            <p class="text-xs text-slate-500 mt-0.5">Submit a formal leave request for departmental and institutional review.</p>
          </div>
        </div>
        <button type="button" onclick="closeApplyLeaveModal()" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition cursor-pointer" aria-label="Close modal">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <!-- Modal Body Form -->
      <form id="leaveApplicationForm" onsubmit="submitLeaveApplication(event)" class="p-6 space-y-5">
        <div id="modalLeaveAlert" class="hidden p-3.5 rounded-xl text-xs font-semibold"></div>

        <!-- Row 1: Leave Type & Session Type -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="modalLeaveType" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Leave Type <span class="text-rose-500">*</span></label>
            <select id="modalLeaveType" onchange="handleLeaveTypeChange()" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 font-semibold focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-colors cursor-pointer">
              <option value="Casual Leave">Casual Leave (CL)</option>
              <option value="Compensatory Casual Leave">Compensatory Casual Leave (CCL)</option>
              <option value="Duty Leave">Duty Leave (DL)</option>
              <option value="Medical Leave">Medical Leave (ML)</option>
              <option value="Loss of Pay">Loss of Pay (LOP)</option>
              <option value="Special Leave">Special Leave (SL)</option>
            </select>
            <span id="modalClQuotaHint" class="block text-[11px] text-blue-600 font-medium mt-1">Available Quota: 15 Days</span>
          </div>

          <div>
            <label for="modalSessionType" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Session Type <span class="text-rose-500">*</span></label>
            <select id="modalSessionType" onchange="calculateTotalDays()" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 font-semibold focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-colors cursor-pointer">
              <option value="Full Day">Full Day (1.0 d/day)</option>
              <option value="FN">FN - Forenoon (0.5 d)</option>
              <option value="AN">AN - Afternoon (0.5 d)</option>
            </select>
          </div>
        </div>

        <!-- Conditional Row: CCL Duty Date (Only when CCL selected) -->
        <div id="modalCclBox" class="hidden p-4 rounded-xl bg-amber-50/60 border border-amber-200/80 space-y-1.5">
          <label for="modalCclDate" class="block text-xs font-bold text-amber-900 uppercase tracking-wider">CCL Duty Date (Date Worked) <span class="text-rose-500">*</span></label>
          <input type="date" id="modalCclDate" class="w-full bg-white border border-amber-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600 outline-none transition-colors">
          <p class="text-[11px] text-amber-700 leading-tight">Specify the institutional holiday or non-working day on which compensatory duty was performed.</p>
        </div>

        <!-- Row 2: From Date, To Date & Total Days -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label for="modalFromDate" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">From Date <span class="text-rose-500">*</span></label>
            <input type="date" id="modalFromDate" required onchange="calculateTotalDays()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-colors">
          </div>

          <div>
            <label for="modalToDate" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">To Date <span class="text-rose-500">*</span></label>
            <input type="date" id="modalToDate" required onchange="calculateTotalDays()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-colors">
          </div>

          <div>
            <label for="modalTotalDays" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Total Days <span class="text-rose-500">*</span></label>
            <input type="number" id="modalTotalDays" step="0.5" min="0.5" required value="1" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-bold font-mono text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-colors">
          </div>
        </div>

        <!-- Row 3: Reason for Leave -->
        <div>
          <label for="modalReason" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reason for Absence <span class="text-rose-500">*</span></label>
          <textarea id="modalReason" rows="2" required placeholder="State official reason for leave request..." class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-colors"></textarea>
        </div>

        <!-- Row 4: Work Arrangements / Substitutes -->
        <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200/80 space-y-3">
          <div class="flex items-center justify-between">
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Work Arrangements &amp; Class Substitutes</label>
            <span class="text-[11px] font-medium text-slate-500">Optional</span>
          </div>

          <!-- Add Substitute Inputs -->
          <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
            <div class="sm:col-span-5">
              <input type="text" id="subClassroom" onkeydown="if(event.key==='Enter'){event.preventDefault();addSubstituteRow();}" placeholder="Class / Period (e.g. CT-S5 P2)" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
            </div>
            <div class="sm:col-span-5">
              <input type="text" id="subStaffName" onkeydown="if(event.key==='Enter'){event.preventDefault();addSubstituteRow();}" placeholder="Substitute Faculty Name" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
            </div>
            <div class="sm:col-span-2">
              <button type="button" id="btnAddSubstitute" onclick="addSubstituteRow()" class="w-full h-full min-h-[36px] px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-1 cursor-pointer">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Add</span>
              </button>
            </div>
          </div>

          <!-- Rendered Substitute Chips / List -->
          <div id="substitutesList" class="space-y-1.5">
            <p class="text-[11px] text-slate-400 italic">No class substitute arrangements added.</p>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
          <button type="button" onclick="closeApplyLeaveModal()" class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm border border-slate-200 rounded-xl transition cursor-pointer">
            Cancel
          </button>
          <button type="submit" id="btnSubmitLeave" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm rounded-xl shadow-xs transition-all flex items-center gap-2 cursor-pointer">
            <i data-lucide="send" class="w-4 h-4"></i>
            <span>Submit to HOD</span>
          </button>
        </div>
      </form>

    </div>
  </div>

  <!-- Client-side Data Hydration & Interaction Script -->
  <script>
    let workArrangementsArray = [];
    let cachedLeaveHistory = [];
    let selectedApprovalLeaveId = null;

    // =========================================================================
    // MODAL HANDLERS
    // =========================================================================
    function openApplyLeaveModal() {
      const modal = document.getElementById('applyLeaveModal');
      const form = document.getElementById('leaveApplicationForm');
      const alertBox = document.getElementById('modalLeaveAlert');
      
      if (modal) {
        if (form) form.reset();
        if (alertBox) {
          alertBox.className = 'hidden';
          alertBox.textContent = '';
        }
        
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('modalFromDate').value = today;
        document.getElementById('modalToDate').value = today;
        document.getElementById('modalTotalDays').value = '1';
        
        workArrangementsArray = [];
        renderSubstitutes();
        handleLeaveTypeChange();

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        if (window.lucide) window.lucide.createIcons();
      }
    }

    function closeApplyLeaveModal() {
      const modal = document.getElementById('applyLeaveModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    function handleLeaveTypeChange() {
      const type = document.getElementById('modalLeaveType').value;
      const cclBox = document.getElementById('modalCclBox');
      const cclInput = document.getElementById('modalCclDate');
      const clHint = document.getElementById('modalClQuotaHint');

      if (type === 'Compensatory Casual Leave' || type === 'CCL') {
        cclBox.classList.remove('hidden');
        if (cclInput) cclInput.required = true;
      } else {
        cclBox.classList.add('hidden');
        if (cclInput) {
          cclInput.required = false;
          cclInput.value = '';
        }
      }

      if (type === 'Casual Leave' || type === 'CL') {
        if (clHint) clHint.classList.remove('hidden');
      } else {
        if (clHint) clHint.classList.add('hidden');
      }
    }

    function calculateTotalDays() {
      const fromVal = document.getElementById('modalFromDate').value;
      const toVal = document.getElementById('modalToDate').value;
      const sessionType = document.getElementById('modalSessionType').value;
      const daysInput = document.getElementById('modalTotalDays');

      if (!fromVal || !toVal || !daysInput) return;

      const from = new Date(fromVal);
      const to = new Date(toVal);

      if (to < from) {
        document.getElementById('modalToDate').value = fromVal;
        daysInput.value = sessionType === 'Full Day' ? '1' : '0.5';
        return;
      }

      const diffTime = Math.abs(to - from);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

      if (sessionType === 'Full Day') {
        daysInput.value = diffDays.toString();
      } else {
        daysInput.value = (diffDays * 0.5).toString();
      }
    }

    function addSubstituteRow() {
      const clsInput = document.getElementById('subClassroom');
      const nameInput = document.getElementById('subStaffName');
      const fromDateInput = document.getElementById('modalFromDate');

      let classroom = clsInput ? clsInput.value.trim() : '';
      let substitute_name = nameInput ? nameInput.value.trim() : '';
      const fromDate = fromDateInput ? fromDateInput.value : '';

      if (!substitute_name && !classroom) {
        if (clsInput) clsInput.focus();
        return;
      }

      if (!classroom && substitute_name) {
        classroom = 'General Department Duty';
      } else if (!substitute_name && classroom) {
        substitute_name = 'Staff Handover';
      }

      workArrangementsArray.push({
        classroom: classroom,
        substitute_name: substitute_name,
        date: fromDate || new Date().toISOString().split('T')[0]
      });

      if (clsInput) clsInput.value = '';
      if (nameInput) nameInput.value = '';
      renderSubstitutes();
      if (window.lucide) window.lucide.createIcons();
    }

    function removeSubstituteRow(index) {
      workArrangementsArray.splice(index, 1);
      renderSubstitutes();
    }

    function renderSubstitutes() {
      const container = document.getElementById('substitutesList');
      if (!container) return;

      if (workArrangementsArray.length === 0) {
        container.innerHTML = '<p class="text-[11px] text-slate-400 italic">No class substitute arrangements added.</p>';
        return;
      }

      let html = '';
      workArrangementsArray.forEach((item, idx) => {
        html += `
          <div class="flex items-center justify-between p-2 rounded-lg bg-white border border-slate-200 text-xs">
            <div class="flex items-center gap-2">
              <span class="font-bold text-slate-800">${escapeHtml(item.classroom)}</span>
              <span class="text-slate-400">&rarr;</span>
              <span class="text-blue-700 font-medium">${escapeHtml(item.substitute_name)}</span>
            </div>
            <button type="button" onclick="removeSubstituteRow(${idx})" class="p-1 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded cursor-pointer" title="Remove">
              &times;
            </button>
          </div>
        `;
      });
      container.innerHTML = html;
    }

    function submitLeaveApplication(e) {
      e.preventDefault();
      const btn = document.getElementById('btnSubmitLeave');
      const alertBox = document.getElementById('modalLeaveAlert');

      const leaveType = document.getElementById('modalLeaveType').value;
      const sessionType = document.getElementById('modalSessionType').value;
      const fromDate = document.getElementById('modalFromDate').value;
      const toDate = document.getElementById('modalToDate').value;
      const cclDate = document.getElementById('modalCclDate').value;
      const totalDays = parseFloat(document.getElementById('modalTotalDays').value);
      const reason = document.getElementById('modalReason').value.trim();

      if (!fromDate || !toDate || !reason || isNaN(totalDays)) {
        if (alertBox) {
          alertBox.className = 'p-3.5 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800';
          alertBox.textContent = 'Please fill in all mandatory fields.';
          alertBox.classList.remove('hidden');
        }
        return;
      }

      if ((leaveType === 'Compensatory Casual Leave' || leaveType === 'CCL') && !cclDate) {
        if (alertBox) {
          alertBox.className = 'p-3.5 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800';
          alertBox.textContent = 'Please specify the CCL duty date on which holiday work was performed.';
          alertBox.classList.remove('hidden');
        }
        return;
      }

      const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
      const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i><span>Submitting...</span>';
        if (window.lucide) window.lucide.createIcons();
      }

      fetch('/api/staff/leave/apply', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          leave_type: leaveType,
          session_type: sessionType,
          from_date: fromDate,
          to_date: toDate,
          ccl_date: (leaveType === 'Compensatory Casual Leave' || leaveType === 'CCL') ? cclDate : null,
          total_days: totalDays,
          reason: reason,
          work_arrangement: workArrangementsArray
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          if (alertBox) {
            alertBox.className = 'p-3.5 rounded-xl text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800';
            alertBox.textContent = data.message || 'Leave application submitted successfully!';
            alertBox.classList.remove('hidden');
          }
          setTimeout(() => {
            closeApplyLeaveModal();
            loadLeaveBalances();
            loadLeaveHistory();
            loadApprovalStatus();
          }, 1200);
        } else {
          if (alertBox) {
            alertBox.className = 'p-3.5 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800';
            alertBox.textContent = data.message || 'Failed to submit leave application.';
            alertBox.classList.remove('hidden');
          }
        }
      })
      .catch(err => {
        console.error('Error submitting leave:', err);
        if (alertBox) {
          alertBox.className = 'p-3.5 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800';
          alertBox.textContent = 'A network error occurred while submitting. Please try again.';
          alertBox.classList.remove('hidden');
        }
      })
      .finally(() => {
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = '<i data-lucide="send" class="w-4 h-4"></i><span>Submit to HOD</span>';
          if (window.lucide) window.lucide.createIcons();
        }
      });
    }

    // =========================================================================
    // DATA HYDRATION: LEAVE BALANCES (SECTION 1)
    // =========================================================================
    function loadLeaveBalances() {
      const refreshBtn = document.getElementById('btnRefreshBalances');
      const syncStatus = document.getElementById('balanceSyncStatus');
      
      if (refreshBtn) {
        refreshBtn.disabled = true;
        refreshBtn.innerHTML = '<span class="material-symbols-rounded text-base text-blue-600 animate-spin shrink-0">sync</span><span>Syncing...</span>';
      }

      fetch('/api/staff/leave/my-history')
        .then(res => {
          if (!res.ok) throw new Error('Network response was not ok');
          return res.json();
        })
        .then(data => {
          if (data && data.status === 'SUCCESS') {
            const clTotal = (typeof data.cl_total === 'number') ? data.cl_total : 15.0;
            const clTaken = (typeof data.cl_taken === 'number') ? data.cl_taken : 0.0;
            const clRemaining = Math.max(0, clTotal - clTaken);
            const clPercent = clTotal > 0 ? Math.min(100, Math.round((clTaken / clTotal) * 100)) : 0;

            const elClRemaining = document.getElementById('clRemaining');
            const elClTaken = document.getElementById('clTaken');
            const elClProgress = document.getElementById('clProgressBar');
            const elClPercentText = document.getElementById('clPercentText');

            if (elClRemaining) elClRemaining.textContent = clRemaining.toFixed(1);
            if (elClTaken) elClTaken.textContent = clTaken.toFixed(1);
            if (elClProgress) elClProgress.style.width = clPercent + '%';
            if (elClPercentText) elClPercentText.textContent = clPercent + '% Used';

            let ccl = 0.0, dl = 0.0, ml = 0.0, lop = 0.0, sl = 0.0, total = 0.0;
            if (Array.isArray(data.leaves)) {
              data.leaves.forEach(l => {
                if (l.overall_status === 'Rejected') return;
                const days = parseFloat(l.total_days || 0);
                const type = (l.leave_type || '').toLowerCase();
                total += days;

                if (type.includes('compensatory') || type.includes('ccl')) {
                  ccl += days;
                } else if (type.includes('duty') || type.includes('dl')) {
                  dl += days;
                } else if (type.includes('medical') || type.includes('ml')) {
                  ml += days;
                } else if (type.includes('loss') || type.includes('lop')) {
                  lop += days;
                } else if (type.includes('special') || type.includes('sl')) {
                  sl += days;
                }
              });
            }

            const elCcl = document.getElementById('cclTaken');
            const elDl = document.getElementById('dlTaken');
            const elMl = document.getElementById('mlTaken');
            const elLop = document.getElementById('lopTaken');
            const elSl = document.getElementById('slTaken');
            const elTotal = document.getElementById('totalLeaveTaken');

            if (elCcl) elCcl.textContent = ccl.toFixed(1);
            if (elDl) elDl.textContent = dl.toFixed(1);
            if (elMl) elMl.textContent = ml.toFixed(1);
            if (elLop) elLop.textContent = lop.toFixed(1);
            if (elSl) elSl.textContent = sl.toFixed(1);
            if (elTotal) elTotal.textContent = total.toFixed(1);

            if (syncStatus) {
              syncStatus.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Live Ledger Synced';
            }
          }
        })
        .catch(err => {
          console.error('Error fetching leave balances:', err);
          if (syncStatus) {
            syncStatus.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Sync Failed (Offline)';
          }
        })
        .finally(() => {
          if (refreshBtn) {
            refreshBtn.disabled = false;
            refreshBtn.innerHTML = '<span class="material-symbols-rounded text-base text-slate-500 shrink-0">sync</span><span>Refresh</span>';
          }
        });
    }

    // =========================================================================
    // DATA HYDRATION: LEAVE APPLICATION HISTORY (SECTION 3)
    // =========================================================================
    function loadLeaveHistory() {
      const container = document.getElementById('leaveHistoryContainer');
      const countBadge = document.getElementById('historyRecordsCount');
      const refreshBtn = document.getElementById('btnRefreshHistory');

      if (refreshBtn) {
        refreshBtn.disabled = true;
        refreshBtn.innerHTML = '<span class="material-symbols-rounded text-base text-blue-600 animate-spin shrink-0">sync</span><span>Loading...</span>';
      }

      fetch('/api/staff/leave/my-history')
        .then(res => {
          if (!res.ok) throw new Error('Network response was not ok');
          return res.json();
        })
        .then(data => {
          if (!container) return;

          if (data && data.status === 'SUCCESS' && Array.isArray(data.leaves) && data.leaves.length > 0) {
            cachedLeaveHistory = data.leaves;
            if (countBadge) countBadge.textContent = `${data.leaves.length} Total Records`;
            
            let tableHtml = `
              <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left border-collapse">
                  <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-600">
                      <th class="py-3.5 px-4">Leave Code &amp; Type</th>
                      <th class="py-3.5 px-4">Dates &amp; Duration</th>
                      <th class="py-3.5 px-4">Reason &amp; Arrangements</th>
                      <th class="py-3.5 px-4 text-center">Status</th>
                      <th class="py-3.5 px-4 text-right">Order PDF</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 text-sm">
            `;

            data.leaves.forEach(item => {
              const statusBadge = getStatusBadgeHtml(item.overall_status);
              const dateRange = (item.from_date === item.to_date) ? item.from_date : `${item.from_date} &rarr; ${item.to_date}`;
              const cclDuty = item.ccl_date ? `<span class="block text-[11px] text-amber-700 font-mono mt-0.5">CCL Worked: ${item.ccl_date}</span>` : '';
              
              let subsHtml = '';
              if (Array.isArray(item.work_arrangement) && item.work_arrangement.length > 0) {
                subsHtml = `<div class="flex items-center gap-1 flex-wrap mt-1">` + 
                  item.work_arrangement.map(s => `<span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px]">${escapeHtml(s.classroom)} &rarr; ${escapeHtml(s.substitute_name)}</span>`).join('') +
                  `</div>`;
              }

              tableHtml += `
                <tr class="hover:bg-slate-50/70 transition-colors">
                  <td class="py-3.5 px-4 align-top">
                    <span class="font-bold text-slate-900 block">${escapeHtml(item.leave_type)}</span>
                    <div class="flex items-center gap-2 mt-0.5">
                      <button type="button" onclick="selectApprovalRequest(${item.id})" class="font-mono text-xs text-blue-700 hover:text-blue-800 font-semibold hover:underline cursor-pointer" title="Inspect approval progression">
                        ${escapeHtml(item.leave_code)}
                      </button>
                      <span class="text-[11px] text-slate-400">• ${escapeHtml(item.session_type)}</span>
                    </div>
                  </td>
                  <td class="py-3.5 px-4 align-top">
                    <span class="font-semibold text-slate-800 block">${dateRange}</span>
                    <span class="text-xs text-slate-500 font-mono font-bold">${item.total_days} Day(s)</span>
                    ${cclDuty}
                  </td>
                  <td class="py-3.5 px-4 align-top max-w-xs">
                    <p class="text-xs text-slate-700 leading-snug line-clamp-2">${escapeHtml(item.reason)}</p>
                    ${subsHtml}
                  </td>
                  <td class="py-3.5 px-4 align-top text-center">
                    ${statusBadge}
                  </td>
                  <td class="py-3.5 px-4 align-top text-right">
                    <a href="/staff/leave/${item.id}/pdf" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-semibold shadow-2xs transition-all no-underline" title="View Formal A4 PDF Order">
                      <span class="material-symbols-rounded text-base text-slate-500">print</span>
                      <span>PDF</span>
                    </a>
                  </td>
                </tr>
              `;
            });

            tableHtml += `
                  </tbody>
                </table>
              </div>
            `;

            container.innerHTML = tableHtml;
          } else {
            cachedLeaveHistory = [];
            if (countBadge) countBadge.textContent = '0 Records';
            container.innerHTML = `
              <div class="py-10 text-center rounded-xl bg-slate-50/70 border border-slate-200/60 p-6 space-y-2">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                  <span class="material-symbols-rounded text-2xl">description</span>
                </div>
                <h5 class="text-sm font-bold text-slate-800">No Leave Applications Found</h5>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">You have not submitted any formal leave requests yet this academic year.</p>
                <div class="pt-2">
                  <button type="button" onclick="openApplyLeaveModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs rounded-xl shadow-xs transition cursor-pointer">
                    <span class="material-symbols-rounded text-base shrink-0">add_circle</span>
                    <span>Apply for Leave</span>
                  </button>
                </div>
              </div>
            `;
          }
        })
        .catch(err => {
          console.error('Error fetching leave history:', err);
          if (container) {
            container.innerHTML = `
              <div class="py-8 text-center rounded-xl bg-rose-50/60 border border-rose-200/60 p-4 space-y-2">
                <p class="text-xs font-bold text-rose-800">Unable to load leave history.</p>
                <button type="button" onclick="loadLeaveHistory()" class="px-3 py-1.5 bg-rose-600 text-white font-semibold text-xs rounded-lg shadow-xs cursor-pointer">
                  Retry
                </button>
              </div>
            `;
          }
        })
        .finally(() => {
          if (refreshBtn) {
            refreshBtn.disabled = false;
            refreshBtn.innerHTML = '<span class="material-symbols-rounded text-base text-slate-500 shrink-0">sync</span><span>Refresh History</span>';
          }
        });
    }

    // =========================================================================
    // DATA HYDRATION: MULTI-STAGE APPROVAL STATUS & TIMELINE (SECTION 4)
    // =========================================================================
    function loadApprovalStatus(targetLeaveId = null) {
      const container = document.getElementById('approvalTimelineContainer');
      const selectorContainer = document.getElementById('approvalRequestSelector');
      const refreshBtn = document.getElementById('btnRefreshApproval');
      const streamNotice = document.getElementById('approvalActiveStreamNotice');

      if (refreshBtn) {
        refreshBtn.disabled = true;
        refreshBtn.innerHTML = '<span class="material-symbols-rounded text-base text-purple-600 animate-spin shrink-0">sync</span><span>Loading...</span>';
      }

      fetch('/api/staff/leave/my-history')
        .then(res => {
          if (!res.ok) throw new Error('Network response was not ok');
          return res.json();
        })
        .then(data => {
          if (!container) return;

          if (data && data.status === 'SUCCESS' && Array.isArray(data.leaves) && data.leaves.length > 0) {
            cachedLeaveHistory = data.leaves;
            
            // Choose active leave item
            let activeItem = null;
            if (targetLeaveId) {
              activeItem = data.leaves.find(l => String(l.id) === String(targetLeaveId));
            }
            if (!activeItem) {
              if (selectedApprovalLeaveId) {
                activeItem = data.leaves.find(l => String(l.id) === String(selectedApprovalLeaveId));
              }
            }
            if (!activeItem) {
              activeItem = data.leaves[0];
            }
            selectedApprovalLeaveId = activeItem.id;

            // Render Request Selector Buttons
            if (selectorContainer) {
              let selectorHtml = '';
              data.leaves.forEach(l => {
                const isSelected = String(l.id) === String(activeItem.id);
                const badgeColor = (l.overall_status === 'Approved') ? 'bg-emerald-100 text-emerald-800' :
                  ((l.overall_status === 'Rejected') ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800');
                
                selectorHtml += `
                  <button type="button" onclick="selectApprovalRequest(${l.id})" class="px-3.5 py-2 rounded-xl text-xs font-semibold shrink-0 transition-all flex items-center gap-2 cursor-pointer border ${isSelected ? 'bg-purple-50 text-purple-900 border-purple-300 ring-2 ring-purple-500/20 shadow-xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'}">
                    <span class="font-mono font-bold">${escapeHtml(l.leave_code)}</span>
                    <span class="text-slate-400">•</span>
                    <span>${escapeHtml(l.leave_type)}</span>
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold ${badgeColor}">${escapeHtml(l.overall_status.replace('_', ' '))}</span>
                  </button>
                `;
              });
              selectorContainer.innerHTML = selectorHtml;
            }

            // Detect Department Stream (SF 3-Tier vs Aided 2-Tier)
            const isSF = isSelfFinancing(activeItem.department);
            if (streamNotice) {
              streamNotice.textContent = isSF ? 'Self-Financing (3-Stage: HOD → Coordinator → Principal)' : 'Aided Stream (2-Stage: HOD → Principal)';
            }

            // Render Approval Timeline Card for Active Item
            renderDetailedTimeline(activeItem, isSF);
          } else {
            if (selectorContainer) selectorContainer.innerHTML = '';
            if (streamNotice) streamNotice.textContent = 'Stream-Aware Routing';
            container.innerHTML = `
              <div class="py-10 text-center rounded-xl bg-slate-50/70 border border-slate-200/60 p-6 space-y-2">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                  <span class="material-symbols-rounded text-2xl">account_tree</span>
                </div>
                <h5 class="text-sm font-bold text-slate-800">No Leave Requests to Track</h5>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">When you submit a leave application, its real-time multi-stage approval workflow and audit notes will appear here.</p>
                <div class="pt-2">
                  <button type="button" onclick="openApplyLeaveModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs rounded-xl shadow-xs transition cursor-pointer">
                    <span class="material-symbols-rounded text-base shrink-0">add_circle</span>
                    <span>Apply for Leave</span>
                  </button>
                </div>
              </div>
            `;
          }
        })
        .catch(err => {
          console.error('Error fetching approval status:', err);
          if (container) {
            container.innerHTML = `
              <div class="py-8 text-center rounded-xl bg-rose-50/60 border border-rose-200/60 p-4 space-y-2">
                <p class="text-xs font-bold text-rose-800">Unable to load approval progression.</p>
                <button type="button" onclick="loadApprovalStatus()" class="px-3 py-1.5 bg-rose-600 text-white font-semibold text-xs rounded-lg shadow-xs cursor-pointer">
                  Retry
                </button>
              </div>
            `;
          }
        })
        .finally(() => {
          if (refreshBtn) {
            refreshBtn.disabled = false;
            refreshBtn.innerHTML = '<span class="material-symbols-rounded text-base text-slate-500 shrink-0">sync</span><span>Refresh Status</span>';
          }
        });
    }

    function selectApprovalRequest(leaveId) {
      selectedApprovalLeaveId = leaveId;
      loadApprovalStatus(leaveId);
    }

    function isSelfFinancing(dept) {
      if (!dept) return false;
      const d = String(dept).toUpperCase().trim();
      const sfKeys = ['EL', 'CT', 'AU', 'SF', 'GEN SF', 'GENERAL SF', 'ELECTRONICS', 'COMPUTER', 'AUTOMOBILE', 'COMPUTER TECH'];
      return sfKeys.includes(d) || d.includes('SF') || d.includes('SELF');
    }

    function renderDetailedTimeline(item, isSF) {
      const container = document.getElementById('approvalTimelineContainer');
      if (!container) return;

      const dateRange = (item.from_date === item.to_date) ? item.from_date : `${item.from_date} to ${item.to_date}`;
      const statusBadge = getStatusBadgeHtml(item.overall_status);

      // Stage Evaluations
      // Stage 1: Submission
      const stage1State = 'completed';
      const submittedDateStr = item.submitted_at ? formatDateString(item.submitted_at) : 'Logged in system';

      // Stage 2: HOD Review
      let stage2State = 'pending';
      let stage2Title = 'Awaiting Department HOD Verification';
      let stage2Sub = 'Pending initial review';
      if (item.hod_status === 'Approved') {
        stage2State = 'completed';
        stage2Title = `Approved by ${escapeHtml(item.hod_name || 'HOD')}`;
        stage2Sub = (item.hod_action_at ? formatDateString(item.hod_action_at) : 'Approved') + (item.hod_remarks ? ` • "${escapeHtml(item.hod_remarks)}"` : '');
      } else if (item.hod_status === 'Rejected') {
        stage2State = 'rejected';
        stage2Title = `Rejected by ${escapeHtml(item.hod_name || 'HOD')}`;
        stage2Sub = (item.hod_action_at ? formatDateString(item.hod_action_at) : 'Rejected') + (item.hod_remarks ? ` • "${escapeHtml(item.hod_remarks)}"` : '');
      } else if (item.overall_status !== 'Pending_HOD' && item.overall_status !== 'Pending') {
        stage2State = 'not_reached';
      }

      // Stage 3: Academic Coordinator Review
      let stage3Applicable = isSF;
      let stage3State = 'not_reached';
      let stage3Title = 'Awaiting Academic Coordinator Review';
      let stage3Sub = 'Pending HOD approval';

      if (!stage3Applicable || (item.coordinator_status && String(item.coordinator_status).includes('N/A'))) {
        stage3Applicable = false;
      } else {
        if (item.coordinator_status === 'Approved') {
          stage3State = 'completed';
          stage3Title = `Approved by ${escapeHtml(item.coordinator_name || 'Academic Coordinator')}`;
          stage3Sub = (item.coordinator_action_at ? formatDateString(item.coordinator_action_at) : 'Approved') + (item.coordinator_remarks ? ` • "${escapeHtml(item.coordinator_remarks)}"` : '');
        } else if (item.coordinator_status === 'Rejected') {
          stage3State = 'rejected';
          stage3Title = `Rejected by ${escapeHtml(item.coordinator_name || 'Academic Coordinator')}`;
          stage3Sub = (item.coordinator_action_at ? formatDateString(item.coordinator_action_at) : 'Rejected') + (item.coordinator_remarks ? ` • "${escapeHtml(item.coordinator_remarks)}"` : '');
        } else if (item.overall_status === 'Pending_Coordinator') {
          stage3State = 'in_progress';
          stage3Title = 'Awaiting Academic Coordinator Review';
          stage3Sub = 'In active queue';
        }
      }

      // Stage 4: Principal Sanction
      let stage4State = 'not_reached';
      let stage4Title = 'Awaiting Principal Sanction';
      let stage4Sub = 'Pending prior stage approvals';

      if (item.principal_status === 'Approved') {
        stage4State = 'completed';
        stage4Title = `Sanctioned by ${escapeHtml(item.principal_name || 'Principal')}`;
        stage4Sub = (item.principal_action_at ? formatDateString(item.principal_action_at) : 'Final Sanctioned') + (item.principal_remarks ? ` • "${escapeHtml(item.principal_remarks)}"` : '');
      } else if (item.principal_status === 'Rejected') {
        stage4State = 'rejected';
        stage4Title = `Rejected by ${escapeHtml(item.principal_name || 'Principal')}`;
        stage4Sub = (item.principal_action_at ? formatDateString(item.principal_action_at) : 'Rejected') + (item.principal_remarks ? ` • "${escapeHtml(item.principal_remarks)}"` : '');
      } else if (item.overall_status === 'Pending_Principal') {
        stage4State = 'in_progress';
        stage4Title = 'Awaiting Principal Final Sanction';
        stage4Sub = 'In Principal active review desk';
      }

      // Stage 5: Final Order Sanction
      const isApproved = item.overall_status === 'Approved';
      const isRejected = item.overall_status === 'Rejected';

      container.innerHTML = `
        <div class="space-y-6">
          
          <!-- Selected Leave Summary Card Header -->
          <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
              <div class="flex items-center gap-2.5 flex-wrap">
                <span class="font-bold text-slate-900 text-sm">${escapeHtml(item.leave_type)}</span>
                <span class="px-2 py-0.5 rounded-full font-mono text-xs font-bold bg-blue-100 text-blue-800">${escapeHtml(item.leave_code)}</span>
                <span class="text-xs text-slate-500 font-medium">${escapeHtml(item.session_type)} • ${item.total_days} Day(s)</span>
              </div>
              <p class="text-xs text-slate-600">
                <strong>Dates:</strong> ${dateRange} • <strong>Reason:</strong> ${escapeHtml(item.reason)}
              </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
              ${statusBadge}
              <a href="/staff/leave/${item.id}/pdf" target="_blank" class="px-3 py-1.5 bg-white hover:bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200 rounded-lg shadow-2xs flex items-center gap-1.5 no-underline transition" title="Print/Download Official Leave Order">
                <i data-lucide="printer" class="w-3.5 h-3.5 text-slate-500"></i>
                <span>PDF Order</span>
              </a>
            </div>
          </div>

          <!-- Step Progression Workflow -->
          <div class="relative pl-6 sm:pl-8 space-y-6 before:absolute before:left-3 sm:before:left-4 before:top-3 before:bottom-3 before:w-0.5 before:bg-slate-200">
            
            <!-- Step 1: Submission -->
            <div class="relative flex items-start gap-4">
              <div class="absolute -left-6 sm:-left-8 w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center ring-4 ring-white shrink-0">
                <i data-lucide="check" class="w-4 h-4"></i>
              </div>
              <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs w-full">
                <div class="flex items-center justify-between flex-wrap gap-1">
                  <h5 class="text-xs font-bold text-slate-900 uppercase tracking-wider">1. Application Submitted</h5>
                  <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/60">Verified</span>
                </div>
                <p class="text-xs text-slate-600 mt-1 font-medium">Submitted by applicant • ${submittedDateStr}</p>
                <p class="text-[11px] text-slate-400 font-mono mt-0.5 truncate">Digital Signature SHA-256 Verified</p>
              </div>
            </div>

            <!-- Step 2: HOD Review -->
            <div class="relative flex items-start gap-4">
              <div class="absolute -left-6 sm:-left-8 w-7 h-7 rounded-full ${getStepBadgeClass(stage2State)} text-white flex items-center justify-center ring-4 ring-white shrink-0">
                ${getStepIconHtml(stage2State, '2')}
              </div>
              <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs w-full">
                <div class="flex items-center justify-between flex-wrap gap-1">
                  <h5 class="text-xs font-bold text-slate-900 uppercase tracking-wider">2. Department HOD Review</h5>
                  <span class="px-2 py-0.5 rounded text-[11px] font-bold ${getStepPillClass(stage2State)}">${getStepPillText(stage2State)}</span>
                </div>
                <p class="text-xs text-slate-700 mt-1 font-medium">${stage2Title}</p>
                <p class="text-[11px] text-slate-500 mt-0.5">${stage2Sub}</p>
              </div>
            </div>

            <!-- Step 3: Academic Coordinator Review (Only for Self-Financing Stream) -->
            ${stage3Applicable ? `
              <div class="relative flex items-start gap-4">
                <div class="absolute -left-6 sm:-left-8 w-7 h-7 rounded-full ${getStepBadgeClass(stage3State)} text-white flex items-center justify-center ring-4 ring-white shrink-0">
                  ${getStepIconHtml(stage3State, '3')}
                </div>
                <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs w-full">
                  <div class="flex items-center justify-between flex-wrap gap-1">
                    <h5 class="text-xs font-bold text-slate-900 uppercase tracking-wider">3. Academic Coordinator Review</h5>
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold ${getStepPillClass(stage3State)}">${getStepPillText(stage3State)}</span>
                  </div>
                  <p class="text-xs text-slate-700 mt-1 font-medium">${stage3Title}</p>
                  <p class="text-[11px] text-slate-500 mt-0.5">${stage3Sub}</p>
                </div>
              </div>
            ` : `
              <div class="relative flex items-start gap-4 opacity-75">
                <div class="absolute -left-6 sm:-left-8 w-7 h-7 rounded-full bg-slate-300 text-slate-600 flex items-center justify-center ring-4 ring-white shrink-0">
                  <i data-lucide="skip-forward" class="w-3.5 h-3.5"></i>
                </div>
                <div class="bg-slate-50/60 p-3 rounded-xl border border-slate-200/60 shadow-2xs w-full text-xs">
                  <div class="flex items-center justify-between">
                    <h5 class="font-bold text-slate-600 uppercase tracking-wider">3. Academic Coordinator Review</h5>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-700">N/A (Aided Stream)</span>
                  </div>
                  <p class="text-[11px] text-slate-500 mt-0.5">Aided Department requests route directly from HOD to Principal sanction.</p>
                </div>
              </div>
            `}

            <!-- Step 4: Principal Sanction -->
            <div class="relative flex items-start gap-4">
              <div class="absolute -left-6 sm:-left-8 w-7 h-7 rounded-full ${getStepBadgeClass(stage4State)} text-white flex items-center justify-center ring-4 ring-white shrink-0">
                ${getStepIconHtml(stage4State, stage3Applicable ? '4' : '3')}
              </div>
              <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs w-full">
                <div class="flex items-center justify-between flex-wrap gap-1">
                  <h5 class="text-xs font-bold text-slate-900 uppercase tracking-wider">${stage3Applicable ? '4' : '3'}. Principal Sanction</h5>
                  <span class="px-2 py-0.5 rounded text-[11px] font-bold ${getStepPillClass(stage4State)}">${getStepPillText(stage4State)}</span>
                </div>
                <p class="text-xs text-slate-700 mt-1 font-medium">${stage4Title}</p>
                <p class="text-[11px] text-slate-500 mt-0.5">${stage4Sub}</p>
              </div>
            </div>

            <!-- Step 5: Final Sanction Order -->
            <div class="relative flex items-start gap-4">
              <div class="absolute -left-6 sm:-left-8 w-7 h-7 rounded-full ${isApproved ? 'bg-emerald-600 text-white' : (isRejected ? 'bg-rose-600 text-white' : 'bg-slate-300 text-slate-600')} flex items-center justify-center ring-4 ring-white shrink-0">
                <i data-lucide="${isApproved ? 'check-check' : (isRejected ? 'x-circle' : 'file-badge')}" class="w-4 h-4"></i>
              </div>
              <div class="p-4 rounded-xl border shadow-2xs w-full ${isApproved ? 'bg-emerald-50/70 border-emerald-200' : (isRejected ? 'bg-rose-50/70 border-rose-200' : 'bg-slate-50/70 border-slate-200/80')}">
                <div class="flex items-center justify-between flex-wrap gap-2">
                  <div>
                    <h5 class="text-xs font-bold uppercase tracking-wider ${isApproved ? 'text-emerald-900' : (isRejected ? 'text-rose-900' : 'text-slate-700')}">
                      ${isApproved ? 'Official Sanction Order Ready' : (isRejected ? 'Application Rejected & Closed' : 'Official Order Pending')}
                    </h5>
                    <p class="text-xs mt-0.5 ${isApproved ? 'text-emerald-800' : (isRejected ? 'text-rose-800' : 'text-slate-500')}">
                      ${isApproved ? 'The institutional leave order has received final authorization. You may print the official order.' : (isRejected ? 'This leave application was not approved. Please consult your department HOD for clarification.' : 'Once authorized by the Principal, your formal order will be generated.')}
                    </p>
                  </div>
                  ${isApproved ? `
                    <a href="/staff/leave/${item.id}/pdf" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-xs transition flex items-center gap-1.5 no-underline shrink-0">
                      <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                      <span>Print Official Order (PDF)</span>
                    </a>
                  ` : ''}
                </div>
              </div>
            </div>

          </div>

        </div>
      `;

      if (window.lucide) window.lucide.createIcons();
    }

    function getStepBadgeClass(state) {
      switch (state) {
        case 'completed': return 'bg-emerald-600';
        case 'rejected': return 'bg-rose-600';
        case 'in_progress':
        case 'pending': return 'bg-blue-600 animate-pulse';
        case 'not_reached':
        default: return 'bg-slate-300 text-slate-600';
      }
    }

    function getStepIconHtml(state, stepNum) {
      if (state === 'completed') return '<i data-lucide="check" class="w-4 h-4"></i>';
      if (state === 'rejected') return '<i data-lucide="x" class="w-4 h-4"></i>';
      return `<span class="font-bold text-xs">${stepNum}</span>`;
    }

    function getStepPillClass(state) {
      switch (state) {
        case 'completed': return 'bg-emerald-50 text-emerald-800 border border-emerald-200/60';
        case 'rejected': return 'bg-rose-50 text-rose-800 border border-rose-200/60';
        case 'in_progress':
        case 'pending': return 'bg-blue-50 text-blue-800 border border-blue-200/60';
        case 'not_reached':
        default: return 'bg-slate-100 text-slate-500';
      }
    }

    function getStepPillText(state) {
      switch (state) {
        case 'completed': return 'Approved';
        case 'rejected': return 'Rejected';
        case 'in_progress':
        case 'pending': return 'In Progress';
        case 'not_reached':
        default: return 'Pending';
      }
    }

    function formatDateString(str) {
      if (!str) return '';
      const d = new Date(str);
      if (isNaN(d.getTime())) return String(str);
      return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function getStatusBadgeHtml(status) {
      switch (status) {
        case 'Approved':
          return `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/80"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Final Approved</span>`;
        case 'Rejected':
          return `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-800 border border-rose-200/80"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Rejected</span>`;
        case 'Pending_Coordinator':
          return `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/80"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending Coord</span>`;
        case 'Pending_Principal':
          return `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-800 border border-purple-200/80"><span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Pending Principal</span>`;
        case 'Pending_HOD':
        default:
          return `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200/80"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Pending HOD</span>`;
      }
    }

    function escapeHtml(str) {
      if (!str) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    document.addEventListener('DOMContentLoaded', function() {
      loadLeaveBalances();
      loadLeaveHistory();
      loadApprovalStatus();
    });
  </script>
</x-layouts.app-shell>
