<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Academic Calendar – Semester {{ ['I','II','III','IV','V','VI'][$cal->semester-1] }} – {{ $branch }}</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, Helvetica, sans-serif; background: #0f172a; }

    /* ── Preview bar (screen only) ── */
    .preview-bar {
      background: #09111f; border-bottom: 1px solid #1e293b;
      display: flex; align-items: center; justify-content: space-between;
      padding: 10px 26px; position: sticky; top: 0; z-index: 20;
    }
    .btn { display:inline-flex;align-items:center;gap:6px;font-family:Arial,sans-serif;font-size:13px;font-weight:700;border-radius:9px;padding:7px 16px;cursor:pointer;text-decoration:none;border:none; }
    .btn-back  { background:transparent;border:1px solid #334155;color:#94a3b8; }
    .btn-back:hover  { background:#1e293b;color:#f1f5f9; }
    .btn-print { background:#1d4ed8;border:1px solid #2563eb;color:#fff; }
    .btn-print:hover { background:#1e40af; }
    .pi-title { font-size:13px;font-weight:700;color:#f1f5f9; }
    .pi-sub   { font-size:11px;color:#475569;margin-top:2px; }
    .screen-body { padding:24px 16px 60px; }

    /* ── A4 page ── */
    .a4-page {
      width: 210mm;
      background: #ffffff;
      color: #111827;
      margin: 0 auto;
      padding: 9mm 12mm 11mm 12mm;
      box-shadow: 0 10px 60px rgba(0,0,0,0.6);
    }

    /* ── College header ── */
    .page-header {
      display: flex; align-items: center; gap: 12px;
      border-bottom: 3px double #1e3a8a;
      padding-bottom: 8px; margin-bottom: 5px;
    }
    .logo-c {
      width: 46px; height: 46px; border-radius: 50%;
      background: #eff6ff; border: 2px solid #1e3a8a;
      display: flex; align-items: center; justify-content: center;
      font-size: 19px; font-weight: 900; color: #1e3a8a; flex-shrink: 0;
    }
    .college-name { font-size: 13.5pt; font-weight: 900; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px; }
    .college-sub  { font-size: 7pt; color: #374151; margin-top: 2px; }

    .dept-band {
      background: #1e3a8a; color: #fff;
      text-align: center; padding: 5px 10px; border-radius: 2px; margin-bottom: 3px;
    }
    .dept-band .dn { font-size: 10.5pt; font-weight: 900; text-transform: uppercase; }
    .dept-band .ds { font-size: 7pt; opacity: 0.8; margin-top: 1px; }

    .cal-title-band {
      background: #dbeafe; border: 1.5px solid #93c5fd;
      text-align: center; padding: 4px 10px; border-radius: 2px; margin-bottom: 8px;
    }
    .cal-title-band .ct  { font-size: 10.5pt; font-weight: 900; color: #1e3a8a; }
    .cal-title-band .cst { font-size: 7.5pt; color: #374151; margin-top: 1px; }

    .meta-strip {
      display: flex; justify-content: space-between;
      background: #f8fafc; border: 1px solid #e2e8f0;
      border-radius: 2px; padding: 4px 10px;
      font-size: 7.5pt; color: #374151; margin-bottom: 10px;
    }
    .meta-strip strong { color: #1e3a8a; }

    /* ══ Month block ══ */
    .month-block {
      margin-bottom: 14px;
      page-break-inside: avoid;
      break-inside: avoid;
    }

    /* Month title bar */
    .month-bar {
      background: #1e3a8a; color: #fff;
      display: flex; align-items: center; justify-content: space-between;
      padding: 4px 10px;
    }
    .month-bar .mb-name { font-size: 9pt; font-weight: 900; text-transform: uppercase; letter-spacing: 0.4px; }
    .month-bar .mb-year { font-size: 7.5pt; opacity: 0.8; }

    /* Two-column body */
    .month-body {
      display: flex;
      gap: 0;
      border: 1px solid #cbd5e1;
      border-top: none;
    }

    /* ── LEFT: event list table ── */
    .left-col {
      width: 58%;
      border-right: 1.5px solid #cbd5e1;
      flex-shrink: 0;
    }
    .ev-table { width: 100%; border-collapse: collapse; font-size: 7.5pt; }
    .ev-table thead th {
      background: #e0f2fe; color: #0c4a6e;
      padding: 4px 6px; font-size: 7pt; font-weight: 700;
      border-bottom: 1px solid #bae6fd; text-transform: uppercase;
      letter-spacing: 0.04em;
    }
    .ev-table tbody td {
      padding: 3px 5px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
      font-size: 7.5pt;
      line-height: 1.4;
    }
    .ev-table tbody tr:last-child td { border-bottom: none; }

    /* row shading by type */
    .ev-sunday   { background: #fff0f0; }
    .ev-holiday  { background: #fff7ed; }
    .ev-exam     { background: #fefce8; }
    .ev-event    { background: #f0fdf4; }
    .ev-dept     { background: #faf5ff; }
    .ev-academic { background: #f0f9ff; }
    .ev-work     { background: #fff; }
    .ev-table tbody tr.ev-alt { background: #f8fafc; }

    .dn  { font-weight: 800; color: #1e3a8a; min-width: 16px; display: inline-block; text-align: right; }
    .dns { font-weight: 800; color: #dc2626; }
    .day-lbl { color: #374151; font-size: 7pt; }
    .day-lbl-sun { color: #dc2626; font-weight: 700; font-size: 7pt; }

    /* category dot */
    .cdot {
      width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0;
      margin-right: 3px; vertical-align: middle;
    }
    .dot-Sunday     { background: #dc2626; }
    .dot-Holiday    { background: #f97316; }
    .dot-Exam       { background: #eab308; }
    .dot-Event      { background: #22c55e; }
    .dot-Department { background: #a855f7; }
    .dot-Academic   { background: #3b82f6; }
    .dot-Other      { background: #94a3b8; }
    .dot-work       { background: #e2e8f0; }

    /* ── RIGHT: mini calendar grid ── */
    .right-col {
      flex: 1;
      padding: 6px 5px 4px 5px;
      background: #fafafa;
    }
    .mini-cal-title {
      text-align: center;
      font-size: 8pt; font-weight: 900; color: #1e3a8a;
      margin-bottom: 5px;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }
    .mini-grid { width: 100%; border-collapse: collapse; }
    .mini-grid th {
      font-size: 6.5pt; font-weight: 700; text-align: center;
      padding: 2px 1px; color: #374151;
      border-bottom: 1px solid #cbd5e1;
    }
    .mini-grid th.sun-h { color: #dc2626; }
    .mini-grid td {
      width: 14.28%;
      text-align: center;
      font-size: 7pt;
      font-weight: 700;
      padding: 0;
      border: 1px solid #f1f5f9;
      line-height: 1;
    }
    .mini-grid td.mc-empty { background: transparent; border-color: transparent; }
    /* Date cell with circle for activity */
    .dc { display:block; width:100%; padding:3px 1px; border-radius:3px; font-size:6.5pt; font-weight:700; }

    /* Grid cell colours */
    .gc-sun   { background: #fecaca; color: #991b1b; }
    .gc-hol   { background: #fed7aa; color: #9a3412; }
    .gc-exam  { background: #fef08a; color: #854d0e; }
    .gc-evt   { background: #bbf7d0; color: #14532d; }
    .gc-dept  { background: #e9d5ff; color: #6b21a8; }
    .gc-acad  { background: #bfdbfe; color: #1e40af; }
    .gc-work  { background: #fff; color: #374151; }
    .gc-other { background: #e2e8f0; color: #374151; }
    .gc-empty { background: transparent; color: transparent; }

    /* Month footer summary */
    .month-footer {
      background: #1e3a8a; color: #fff;
      display: flex; justify-content: space-between; align-items: center;
      padding: 4px 10px; font-size: 7pt; font-weight: 700;
      border-top: 1.5px solid #1e40af;
    }
    .month-footer .mf-item { display: flex; align-items: center; gap: 5px; }
    .mf-hi { background: rgba(255,255,255,0.15); border-radius:4px; padding:2px 8px; font-size:8pt; }

    /* ── Legend ── */
    .legend-row {
      display: flex; flex-wrap: wrap; gap: 8px; align-items: center;
      margin: 8px 0; padding: 5px 8px;
      background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 3px;
    }
    .leg-item { display:flex;align-items:center;gap:4px;font-size:7pt;color:#374151; }

    /* ── Grand summary table ── */
    .sum-table { width:100%;border-collapse:collapse;font-size:8pt;margin-top:10px; }
    .sum-table th { background:#1e3a8a;color:#fff;padding:5px 8px;font-size:7.5pt;text-align:center;border:1px solid #1e40af; }
    .sum-table td { padding:4px 8px;border:1px solid #e2e8f0;text-align:center;font-size:7.5pt; }
    .sum-table tr:nth-child(even) td { background:#eff6ff; }
    .sum-table .tot td { background:#1e3a8a!important;color:#fff;font-weight:900; }

    /* ── Sign / footer ── */
    .sign-row  { display:flex;justify-content:space-between;margin-top:20px; }
    .sign-box  { text-align:center;min-width:110px; }
    .sign-line { border-top:1px solid #374151;padding-top:4px;font-size:7.5pt;font-weight:700;color:#1e3a8a; }
    .sign-sub  { font-size:6.5pt;color:#6b7280;margin-top:2px; }
    .page-foot { display:flex;justify-content:space-between;font-size:6.5pt;color:#9ca3af;border-top:1px solid #e5e7eb;padding-top:5px;margin-top:12px; }

    /* ── Print ── */
    @media print {
      * { -webkit-print-color-adjust:exact!important; print-color-adjust:exact!important; }
      body { background:white!important; }
      .preview-bar { display:none!important; }
      .screen-body { padding:0!important; }
      .a4-page { width:100%!important;margin:0!important;padding:7mm 10mm 9mm 10mm!important;box-shadow:none!important; }
      .month-block { page-break-inside:avoid; break-inside:avoid; }
      @page { size:A4 portrait; margin:0; }
    }
  </style>
</head>
<body>
@php
  $semNames   = ['I','II','III','IV','V','VI'];
  $semLabel   = $semNames[$cal->semester - 1];
  $branchMap  = [
    'EL'  => 'Electronics Engineering',
    'ME'  => 'Mechanical Engineering',
    'CE'  => 'Civil Engineering',
    'EEE' => 'Electrical & Electronics Engineering',
    'CT'  => 'Computer Engineering',
    'AU'  => 'Automobile Engineering',
    'GEN_AIDED' => 'General Department (Aided)',
    'GEN_SF'    => 'General Department (Self Finance)',
  ];
  $branchFull = $branchMap[$branch] ?? $branch;

  $yearParts = explode('-', $cal->academic_year);
  $startYear = (int)($yearParts[0] ?? date('Y'));
  $endYear   = (int)($yearParts[1] ?? ($startYear + 1));

  $monthNum = [
    'January'=>1,'February'=>2,'March'=>3,'April'=>4,'May'=>5,'June'=>6,
    'July'=>7,'August'=>8,'September'=>9,'October'=>10,'November'=>11,'December'=>12
  ];
  // PHP date('N'): 1=Mon … 7=Sun
  $dayNames = [1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat',7=>'Sun'];

  // Determine year for a month given semester
  function resolveYear($mNum, $sem, $sy, $ey) {
    return ($sem % 2 === 1) ? ($mNum >= 6 ? $sy : $ey) : ($mNum >= 11 ? $sy : $ey);
  }

  // Map activity type to CSS classes
  function evCls($type, $isSun) {
    if ($isSun) return 'ev-sunday';
    return match($type) {
      'Holiday'    => 'ev-holiday',
      'Exam'       => 'ev-exam',
      'Event'      => 'ev-event',
      'Department' => 'ev-dept',
      'Academic'   => 'ev-academic',
      default      => 'ev-work',
    };
  }
  function gcCls($type, $isSun) {
    if ($isSun) return 'gc-sun';
    return match($type) {
      'Holiday'    => 'gc-hol',
      'Exam'       => 'gc-exam',
      'Event'      => 'gc-evt',
      'Department' => 'gc-dept',
      'Academic'   => 'gc-acad',
      'Other'      => 'gc-other',
      default      => 'gc-work',
    };
  }
  function dotCls($type, $isSun) {
    if ($isSun) return 'dot-Sunday';
    return 'dot-' . ($type ?: 'work');
  }

  // Group entries by month (preserve order of first appearance)
  $byMonth = [];
  foreach ($activities as $act) {
    $m = trim($act['month'] ?? '');
    if (!$m || !isset($monthNum[$m])) continue;
    if (!isset($byMonth[$m])) $byMonth[$m] = [];
    $byMonth[$m][] = $act;
  }
  $orderedMonths = array_keys($byMonth);

  $summaryRows = [];
  $grandWork = $grandTotal = 0;
@endphp

  <!-- Preview bar -->
  <div class="preview-bar">
    <button onclick="window.close()" class="btn btn-back">← Close Preview</button>
    <div style="text-align:center;">
      <div class="pi-title">Sem {{ $semLabel }} — {{ $branch }} — {{ $cal->academic_year }} | Two-Column Preview</div>
      <div class="pi-sub">Left: date list &nbsp;|&nbsp; Right: colour-coded grid calendar</div>
    </div>
    <button class="btn btn-print" onclick="window.print()">🖨 Print A4</button>
  </div>

  <div class="screen-body">
  <div class="a4-page">

    <!-- Header -->
    <div class="page-header">
      <div class="logo-c">C</div>
      <div>
        <div class="college-name">Carmel Polytechnic College</div>
        <div class="college-sub">Punnapra South, Alappuzha – 688003, Kerala &bull; AICTE Approved &bull; Affiliated to SBTE Kerala &bull; ISO Certified</div>
      </div>
    </div>

    <div class="dept-band">
      <div class="dn">Department of {{ $branchFull }}</div>
      <div class="ds">{{ $branch }} Department</div>
    </div>
    <div class="cal-title-band">
      <div class="ct">Academic Calendar – Semester {{ $semLabel }}</div>
      <div class="cst">Based on SITTTR Academic Calendar &bull; Academic Year: {{ $cal->academic_year }}</div>
    </div>
    <div class="meta-strip">
      <span><strong>Department:</strong> {{ $branchFull }}</span>
      <span><strong>Semester:</strong> {{ $semLabel }}</span>
      <span><strong>Academic Year:</strong> {{ $cal->academic_year }}</span>
      <span><strong>Printed:</strong> {{ now()->format('d M Y') }}</span>
    </div>

    <!-- Legend -->
    <div class="legend-row">
      <span style="font-size:7pt;font-weight:700;color:#374151;margin-right:4px;">KEY:</span>
      @foreach([
        ['gc-sun',  'Sunday'],
        ['gc-hol',  'Holiday'],
        ['gc-exam', 'Exam'],
        ['gc-evt',  'Event'],
        ['gc-dept', 'Dept. Activity'],
        ['gc-acad', 'Academic'],
        ['gc-work', 'Working Day'],
      ] as [$cls,$lbl])
      <span class="leg-item">
        <span style="width:11px;height:11px;border-radius:2px;display:inline-block;border:1px solid #e2e8f0;" class="{{ $cls }}"></span>
        {{ $lbl }}
      </span>
      @endforeach
    </div>

    @if(count($orderedMonths) === 0)
      <div style="text-align:center;padding:28px 0;font-size:9pt;color:#6b7280;font-style:italic;">
        No calendar entries added. Go back and enter month-wise activities.
      </div>
    @else

    @foreach($orderedMonths as $mName)
      @php
        $mNum      = $monthNum[$mName];
        $mYear     = resolveYear($mNum, $cal->semester, $startYear, $endYear);
        $daysInM   = cal_days_in_month(CAL_GREGORIAN, $mNum, $mYear);

        // Index activities by date
        $actByDate = [];
        foreach ($byMonth[$mName] as $a) {
          $d = (int)($a['date'] ?? 0);
          if ($d >= 1 && $d <= $daysInM) $actByDate[$d] = $a;
        }

        // Stats
        $workDays = $sunCount = $holCount = 0;
        for ($d = 1; $d <= $daysInM; $d++) {
          $dow = (int)date('N', mktime(0,0,0,$mNum,$d,$mYear));
          if ($dow === 7) { $sunCount++; continue; }
          $t = $actByDate[$d]['type'] ?? '';
          if ($t === 'Holiday') { $holCount++; continue; }
          $workDays++;
        }
        $summaryRows[] = ['month'=>$mName.' '.$mYear,'total'=>$daysInM,'sun'=>$sunCount,'hol'=>$holCount,'work'=>$workDays];
        $grandTotal += $daysInM;
        $grandWork  += $workDays;

        // Grid: day of week for 1st of month (0=Sun,1=Mon..6=Sat)
        // PHP date('w'): 0=Sun,6=Sat  → offset for Sun-first grid
        $startDow = (int)date('w', mktime(0,0,0,$mNum,1,$mYear)); // 0=Sun
      @endphp

      <div class="month-block">

        <!-- Month title bar -->
        <div class="month-bar">
          <span class="mb-name">{{ $mName }}</span>
          <span class="mb-year">{{ $mYear }}</span>
        </div>

        <!-- Two column body -->
        <div class="month-body">

          <!-- LEFT: Activities only (no empty/Sunday rows) -->
          <div class="left-col">
            <table class="ev-table">
              <thead>
                <tr>
                  <th style="width:26px;">Dt</th>
                  <th style="width:30px;">Day</th>
                  <th>Activity / Description</th>
                  <th style="width:54px;">Type</th>
                </tr>
              </thead>
              <tbody>
                @php $hasAny = false; @endphp
                @for($d = 1; $d <= $daysInM; $d++)
                  @php
                    $act = $actByDate[$d] ?? null;
                    if (!$act) continue;   // skip dates with no activity
                    $dow   = (int)date('N', mktime(0,0,0,$mNum,$d,$mYear));
                    $isSun = ($dow === 7);
                    $dName = $dayNames[$dow];
                    $type  = $act['type'] ?? 'Academic';
                    $cls   = evCls($type, $isSun);
                    $hasAny = true;
                  @endphp
                  <tr class="{{ $cls }}">
                    <td style="text-align:center;">
                      <span class="{{ $isSun ? 'dns' : 'dn' }}">{{ $d }}</span>
                    </td>
                    <td>
                      <span class="{{ $isSun ? 'day-lbl-sun' : 'day-lbl' }}">{{ $dName }}</span>
                    </td>
                    <td>
                      <span style="font-size:7.5pt;">{{ $act['activity'] }}</span>
                    </td>
                    <td style="text-align:center;">
                      <span style="display:inline-block;padding:1px 5px;border-radius:99px;font-size:6pt;font-weight:700;
                        @if($type==='Holiday') background:#fed7aa;color:#9a3412;border:1px solid #fdba74;
                        @elseif($type==='Exam') background:#fef08a;color:#854d0e;border:1px solid #fde047;
                        @elseif($type==='Event') background:#bbf7d0;color:#14532d;border:1px solid #86efac;
                        @elseif($type==='Department') background:#e9d5ff;color:#6b21a8;border:1px solid #d8b4fe;
                        @elseif($type==='Academic') background:#bfdbfe;color:#1e40af;border:1px solid #93c5fd;
                        @else background:#e2e8f0;color:#475569;border:1px solid #cbd5e1;
                        @endif
                      ">{{ $type }}</span>
                    </td>
                  </tr>
                @endfor
                @if(!$hasAny)
                  <tr><td colspan="4" style="text-align:center;padding:12px 6px;color:#9ca3af;font-size:7pt;font-style:italic;">No activities added for this month.</td></tr>
                @endif
              </tbody>
            </table>
          </div><!-- /left-col -->

          <!-- RIGHT: Mini grid calendar -->
          <div class="right-col">
            <div class="mini-cal-title">{{ $mName }} {{ $mYear }}</div>
            <table class="mini-grid">
              <thead>
                <tr>
                  <th class="sun-h">SUN</th>
                  <th>MON</th>
                  <th>TUE</th>
                  <th>WED</th>
                  <th>THU</th>
                  <th>FRI</th>
                  <th>SAT</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $cell = 0;       // current cell in 7-col grid
                  $date = 1;
                  $totalCells = $startDow + $daysInM;   // leading empty + days
                  $rows = ceil($totalCells / 7);
                @endphp
                @for($row = 0; $row < $rows; $row++)
                  <tr>
                    @for($col = 0; $col < 7; $col++)
                      @php
                        $cellIdx = $row * 7 + $col;
                        $d2      = $cellIdx - $startDow + 1;
                        $isBefore = ($d2 < 1);
                        $isAfter  = ($d2 > $daysInM);
                        if (!$isBefore && !$isAfter) {
                          $dow2   = (int)date('N', mktime(0,0,0,$mNum,$d2,$mYear));
                          $isSun2 = ($dow2 === 7);
                          $act2   = $actByDate[$d2] ?? null;
                          $gcls   = gcCls($act2['type'] ?? null, $isSun2);
                        }
                      @endphp
                      @if($isBefore || $isAfter)
                        <td class="mc-empty"><span class="dc gc-empty">.</span></td>
                      @else
                        <td>
                          <span class="dc {{ $gcls }}">{{ $d2 }}</span>
                        </td>
                      @endif
                    @endfor
                  </tr>
                @endfor
              </tbody>
            </table>

            <!-- Mini legend inside right col -->
            <div style="margin-top:6px;display:flex;flex-wrap:wrap;gap:3px 6px;">
              @foreach([
                ['gc-sun','SUN'],
                ['gc-hol','HOL'],
                ['gc-exam','EXM'],
                ['gc-evt','EVT'],
                ['gc-dept','DEP'],
                ['gc-acad','ACD'],
              ] as [$c,$l])
              <span style="display:flex;align-items:center;gap:2px;font-size:6pt;color:#374151;">
                <span style="width:8px;height:8px;border-radius:1px;display:inline-block;" class="{{ $c }}"></span>{{ $l }}
              </span>
              @endforeach
            </div>
          </div><!-- /right-col -->

        </div><!-- /month-body -->

        <!-- Month footer summary -->
        <div class="month-footer">
          <div class="mf-item">&#128197; Total: <strong>{{ $daysInM }}</strong> days</div>
          <div class="mf-item">&#9728; Sundays: <strong>{{ $sunCount }}</strong></div>
          <div class="mf-item">&#127958; Holidays: <strong>{{ $holCount }}</strong></div>
          <div class="mf-item"><span class="mf-hi">&#10003; Working Days: {{ $workDays }}</span></div>
        </div>

      </div><!-- /month-block -->
    @endforeach

    <!-- Working Days Summary Table -->
    <div style="margin-top:12px;">
      <div style="font-size:9pt;font-weight:900;color:#1e3a8a;text-transform:uppercase;letter-spacing:0.4px;border-left:4px solid #1e3a8a;padding-left:8px;margin-bottom:6px;">
        Working Days Summary — Semester {{ $semLabel }}
      </div>
      <table class="sum-table">
        <thead>
          <tr>
            <th style="text-align:left;">Month</th>
            <th>Total Days</th>
            <th>Sundays</th>
            <th>Holidays</th>
            <th>Working Days</th>
          </tr>
        </thead>
        <tbody>
          @foreach($summaryRows as $sr)
          <tr>
            <td style="text-align:left;font-weight:700;">{{ $sr['month'] }}</td>
            <td>{{ $sr['total'] }}</td>
            <td>{{ $sr['sun'] }}</td>
            <td>{{ $sr['hol'] }}</td>
            <td style="font-weight:900;color:#1e3a8a;">{{ $sr['work'] }}</td>
          </tr>
          @endforeach
          <tr class="tot">
            <td style="text-align:left;">TOTAL</td>
            <td>{{ $grandTotal }}</td>
            <td>—</td>
            <td>—</td>
            <td>{{ $grandWork }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    @endif

    @if($cal->pdf_path)
    <div style="border:1px solid #e5e7eb;border-radius:3px;padding:5px 10px;font-size:7pt;color:#374151;margin-top:10px;">
      &#128196; SITTTR Reference PDF available — refer to the official calendar for detailed weekly timetable.
    </div>
    @endif

    <!-- Signatures -->
    <div class="sign-row">
      <div class="sign-box">
        <div class="sign-line">Head of Department</div>
        <div class="sign-sub">{{ $branchFull }}</div>
      </div>
      <div class="sign-box">
        <div class="sign-line">Academic Committee</div>
        <div class="sign-sub">Approved By</div>
      </div>
      <div class="sign-box">
        <div class="sign-line">Principal</div>
        <div class="sign-sub">Carmel Polytechnic College</div>
      </div>
    </div>

    <!-- Page footer -->
    <div class="page-foot">
      <span>Carmel Polytechnic College, Punnapra, Alappuzha</span>
      <span>{{ $branch }} Dept · Sem {{ $semLabel }} · {{ $cal->academic_year }}</span>
      <span>Generated by Carmel Linx</span>
    </div>

  </div><!-- /a4-page -->
  </div><!-- /screen-body -->
</body>
</html>
