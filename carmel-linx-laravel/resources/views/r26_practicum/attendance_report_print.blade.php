<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Attendance Report — {{ $batchSubject->subject_name }}</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; background: #fff; color: #1e293b; padding: 24px; }
  .institution-header { text-align: center; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 12px; margin-bottom: 16px; }
  .institution-header h1 { font-size: 18px; font-weight: 700; color: #1e3a5f; }
  .institution-header p { font-size: 12px; color: #475569; margin-top: 3px; }
  .meta-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 6px 20px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px 14px; margin-bottom: 18px; }
  .meta-label { font-weight: 600; color: #475569; font-size: 11px; text-transform: uppercase; }
  .meta-val { font-weight: 700; color: #1e293b; font-size: 13px; }
  .section-header { display: flex; align-items: center; gap: 8px; padding: 7px 12px; border-radius: 5px; margin-bottom: 8px; margin-top: 22px; font-size: 13px; font-weight: 700; }
  .theory-hdr { background: #dbeafe; color: #1d4ed8; border-left: 4px solid #2563eb; }
  .lab-hdr { background: #dcfce7; color: #15803d; border-left: 4px solid #16a34a; }
  .badge-t { font-size: 10px; padding: 2px 7px; border-radius: 10px; font-weight: 700; background: #2563eb; color: #fff; }
  .badge-l { font-size: 10px; padding: 2px 7px; border-radius: 10px; font-weight: 700; background: #16a34a; color: #fff; }
  .att-table-wrap { overflow-x: auto; margin-bottom: 18px; }
  table { width: 100%; border-collapse: collapse; font-size: 11px; }
  thead th { background: #1e3a5f; color: #fff; padding: 5px 4px; text-align: center; font-weight: 600; font-size: 10px; border: 1px solid #2d5a8a; white-space: nowrap; }
  thead th.tleft { text-align: left; padding-left: 8px; }
  tbody tr:nth-child(even) { background: #f8fafc; }
  tbody td { border: 1px solid #e2e8f0; padding: 4px; text-align: center; font-size: 11px; white-space: nowrap; }
  tbody td.tdleft { text-align: left; padding-left: 8px; }
  .sP { color: #15803d; font-weight: 700; }
  .sL { color: #d97706; font-weight: 700; }
  .sA { color: #dc2626; font-weight: 700; }
  .sN { color: #94a3b8; }
  .tot-td { font-weight: 700; color: #1e3a5f; background: #e0f2fe; }
  .pct-h { color: #15803d; font-weight: 700; }
  .pct-m { color: #d97706; font-weight: 700; }
  .pct-l { color: #dc2626; font-weight: 700; }
  tr.short-row { background: #fef2f2 !important; }
  .sum-tbl { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 6px; margin-bottom: 20px; }
  .sum-tbl th { background: #334155; color: #fff; padding: 5px 8px; text-align: center; font-size: 11px; border: 1px solid #475569; }
  .sum-tbl td { border: 1px solid #cbd5e1; padding: 4px 8px; text-align: center; }
  .legend { display: flex; align-items: center; gap: 16px; font-size: 11px; margin-bottom: 12px; padding: 6px 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; flex-wrap: wrap; }
  .no-data { padding: 20px; text-align: center; color: #94a3b8; font-style: italic; border: 1px dashed #cbd5e1; border-radius: 6px; margin-bottom: 16px; }
  .sig-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 40px; border-top: 1px solid #cbd5e1; padding-top: 16px; }
  .sig-box { text-align: center; font-size: 12px; }
  .sig-line { border-bottom: 1px solid #475569; height: 40px; margin-bottom: 5px; }
  .sig-lbl { font-weight: 600; color: #475569; font-size: 11px; }
  .no-print { text-align: center; padding: 14px; background: #1e3a5f; border-radius: 8px; margin-bottom: 20px; }
  .no-print button { background: #2563eb; color: #fff; border: none; padding: 10px 30px; font-size: 14px; font-weight: 700; border-radius: 6px; cursor: pointer; margin: 0 6px; }
  .no-print button.back-btn { background: #475569; }
  @media print { .no-print { display: none !important; } body { padding: 10px; } tr { page-break-inside: avoid; } }
</style>
</head>
<body>

<div class="no-print">
  <button class="back-btn" onclick="history.back()">&#8592; Back to Classroom</button>
  <button onclick="window.print()">&#128424; Print / Save as PDF</button>
</div>

<div class="institution-header">
  <h1>CARMEL POLYTECHNIC COLLEGE</h1>
  <p>Affiliated to SBTE Kerala &nbsp;|&nbsp; Approved by AICTE, New Delhi</p>
  <p style="font-size:13px;font-weight:700;margin-top:6px;color:#1e3a5f;">STUDENT ATTENDANCE REGISTER &mdash; REVISION 2026 PRACTICUM</p>
</div>

<div class="meta-grid">
  <div><div class="meta-label">Subject</div><div class="meta-val">{{ $batchSubject->subject_name }}</div></div>
  <div><div class="meta-label">Subject Code</div><div class="meta-val">{{ $batchSubject->subject_code }}</div></div>
  <div><div class="meta-label">Semester</div><div class="meta-val">{{ $classroom->current_semester ?? $batchSubject->semester ?? 'N/A' }}</div></div>
  <div><div class="meta-label">Department</div><div class="meta-val">{{ $classroom->department ?? $classroom->branch ?? 'N/A' }}</div></div>
  <div><div class="meta-label">Batch / Division</div><div class="meta-val">{{ $classroom->batch ?? $classroom->division ?? $classroom->classroom_id }}</div></div>
  <div><div class="meta-label">Faculty In-Charge</div><div class="meta-val">{{ $assignedStaff->count() ? $assignedStaff->pluck('name')->implode(', ') : '—' }}</div></div>
  <div><div class="meta-label">Academic Year</div><div class="meta-val">{{ date('Y') . '–' . (date('Y') + 1) }}</div></div>
  <div><div class="meta-label">Total Students</div><div class="meta-val">{{ $students->count() }}</div></div>
  <div><div class="meta-label">Report Generated</div><div class="meta-val">{{ date('d M Y, h:i A') }}</div></div>
</div>

<div class="legend">
  <strong>Legend:</strong>
  <span style="color:#15803d;font-weight:700;">P = Present</span>
  <span style="color:#d97706;font-weight:700;">L = Late (counted as present)</span>
  <span style="color:#dc2626;font-weight:700;">A = Absent</span>
  <span style="color:#94a3b8;">&mdash; = Not recorded</span>
  <span style="color:#dc2626;font-weight:600;margin-left:auto;">&#9888; = Shortage below 75%</span>
</div>

{{-- ===== THEORY SECTION ===== --}}
<div class="section-header theory-hdr">
  &#128216; Theory Attendance Register
  <span class="badge-t">{{ $theoryPlans->count() }} Session(s) &nbsp;|&nbsp; 45 Theory Hours</span>
</div>

@if($theoryPlans->isEmpty())
  <div class="no-data">No theory sessions have been recorded yet for this subject.</div>
@else
<div class="att-table-wrap">
<table>
  <thead>
    <tr>
      <th class="tleft" rowspan="2">Roll</th>
      <th class="tleft" rowspan="2">Reg. No.</th>
      <th class="tleft" rowspan="2" style="min-width:140px;">Student Name</th>
      @foreach($theoryPlans as $plan)
        <th style="min-width:24px;">H{{ $plan->day_no ?? '?' }}<br><span style="font-size:8px;font-weight:400;">{{ $plan->co_id ?? '' }}</span></th>
      @endforeach
      <th rowspan="2" style="background:#143d60;">Pres.</th>
      <th rowspan="2" style="background:#143d60;">Total</th>
      <th rowspan="2" style="background:#143d60;">%</th>
      <th rowspan="2" style="background:#143d60;">Marks</th>
    </tr>
    <tr>
      @foreach($theoryPlans as $plan)
        <th style="font-size:8.5px;color:#a7c8f0;background:#264d7a;font-weight:400;">
          @if($plan->actual_date) {{ \Carbon\Carbon::parse($plan->actual_date)->format('d/m') }}
          @elseif($plan->proposed_date) {{ \Carbon\Carbon::parse($plan->proposed_date)->format('d/m') }}
          @else --
          @endif
        </th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach($students as $st)
      @php
        $tot  = $theoryTotals[$st->reg_no]['total']   ?? 0;
        $pres = $theoryTotals[$st->reg_no]['present']  ?? 0;
        $pct  = $tot > 0 ? round(($pres / $tot) * 100, 1) : null;
        $am   = 0;
        if ($pct !== null) { if($pct>=90) $am=5; elseif($pct>=80) $am=4; elseif($pct>=75) $am=3; elseif($pct>=70) $am=2; elseif($pct>=65) $am=1; }
        $short = ($pct !== null && $pct < 75);
        $pc   = $pct === null ? 'sN' : ($pct >= 75 ? 'pct-h' : ($pct >= 65 ? 'pct-m' : 'pct-l'));
      @endphp
      <tr class="{{ $short ? 'short-row' : '' }}">
        <td class="tdleft">{{ $st->roll_no }}</td>
        <td class="tdleft" style="font-family:monospace;font-size:10px;">{{ $st->sbte_reg_no ?: $st->reg_no }}</td>
        <td class="tdleft">{{ $st->name }}@if($short) <span style="color:#dc2626;font-size:9px;">&#9888;</span>@endif</td>
        @foreach($theoryPlans as $plan)
          @php $s = $theoryMatrix[$st->reg_no][$plan->id] ?? null; @endphp
          <td class="s{{ $s ? $s[0] : 'N' }}">{{ $s ? $s[0] : '&mdash;' }}</td>
        @endforeach
        <td class="tot-td">{{ $tot > 0 ? $pres : '&mdash;' }}</td>
        <td class="tot-td">{{ $tot > 0 ? $tot  : '&mdash;' }}</td>
        <td class="{{ $pc }}">{{ $pct !== null ? $pct.'%' : '&mdash;' }}</td>
        <td style="font-weight:700;color:#1d4ed8;">{{ $tot > 0 ? $am.'/5' : '&mdash;' }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
</div>
@php
  $thS=0; $thA=0; $thV=0;
  foreach($students as $st){ $t=$theoryTotals[$st->reg_no]['total']??0; $p=$theoryTotals[$st->reg_no]['present']??0;
    if($t>0){ $pc=round(($p/$t)*100,1); $thA+=$pc; $thV++; if($pc<75)$thS++; } }
  $thA=$thV>0?round($thA/$thV,1):0;
@endphp
<table class="sum-tbl">
  <thead><tr><th>Theory Sessions</th><th>Class Avg Attendance</th><th>Students with Shortage (&lt; 75%)</th><th>Students in Good Standing (&ge; 75%)</th></tr></thead>
  <tbody><tr>
    <td style="font-weight:700;">{{ $theoryPlans->count() }}</td>
    <td style="font-weight:700;color:{{ $thA>=75?'#15803d':'#dc2626' }};">{{ $thA }}%</td>
    <td style="font-weight:700;color:#dc2626;">{{ $thS }}</td>
    <td style="font-weight:700;color:#15803d;">{{ $students->count()-$thS }}</td>
  </tr></tbody>
</table>
@endif

{{-- ===== LAB SECTION ===== --}}
<div class="section-header lab-hdr" style="margin-top:28px;">
  &#128300; Lab (Practical) Attendance Register
  <span class="badge-l">{{ $labPlans->count() }} Session(s) &nbsp;|&nbsp; 45 Practical Hours</span>
</div>

@if($labPlans->isEmpty())
  <div class="no-data">No lab sessions have been recorded yet for this subject.</div>
@else
<div class="att-table-wrap">
<table>
  <thead>
    <tr>
      <th class="tleft" rowspan="2" style="background:#1a4a2e;">Roll</th>
      <th class="tleft" rowspan="2" style="background:#1a4a2e;">Reg. No.</th>
      <th class="tleft" rowspan="2" style="background:#1a4a2e;min-width:140px;">Student Name</th>
      @foreach($labPlans as $plan)
        <th style="min-width:24px;background:#1a4a2e;border-color:#255c3a;">P{{ $plan->day_no ?? '?' }}<br><span style="font-size:8px;font-weight:400;">{{ $plan->co_id ?? '' }}</span></th>
      @endforeach
      <th rowspan="2" style="background:#124027;">Pres.</th>
      <th rowspan="2" style="background:#124027;">Total</th>
      <th rowspan="2" style="background:#124027;">%</th>
      <th rowspan="2" style="background:#124027;">Status</th>
    </tr>
    <tr>
      @foreach($labPlans as $plan)
        <th style="font-size:8.5px;color:#6ee7b7;background:#1e4f33;font-weight:400;border-color:#255c3a;">
          @if($plan->actual_date) {{ \Carbon\Carbon::parse($plan->actual_date)->format('d/m') }}
          @elseif($plan->proposed_date) {{ \Carbon\Carbon::parse($plan->proposed_date)->format('d/m') }}
          @else --
          @endif
        </th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach($students as $st)
      @php
        $tot  = $labTotals[$st->reg_no]['total']   ?? 0;
        $pres = $labTotals[$st->reg_no]['present']  ?? 0;
        $pct  = $tot > 0 ? round(($pres / $tot) * 100, 1) : null;
        $short= ($pct !== null && $pct < 75);
        $pc   = $pct === null ? 'sN' : ($pct >= 75 ? 'pct-h' : ($pct >= 65 ? 'pct-m' : 'pct-l'));
        $lst  = $pct === null ? '&mdash;' : ($pct >= 75 ? 'OK' : 'SHORT');
        $lstC = $short ? '#dc2626' : '#15803d';
      @endphp
      <tr class="{{ $short ? 'short-row' : '' }}">
        <td class="tdleft">{{ $st->roll_no }}</td>
        <td class="tdleft" style="font-family:monospace;font-size:10px;">{{ $st->sbte_reg_no ?: $st->reg_no }}</td>
        <td class="tdleft">{{ $st->name }}@if($short) <span style="color:#dc2626;font-size:9px;">&#9888;</span>@endif</td>
        @foreach($labPlans as $plan)
          @php $s = $labMatrix[$st->reg_no][$plan->id] ?? null; @endphp
          <td class="s{{ $s ? $s[0] : 'N' }}">{{ $s ? $s[0] : '&mdash;' }}</td>
        @endforeach
        <td class="tot-td" style="background:#d1fae5;">{{ $tot > 0 ? $pres : '&mdash;' }}</td>
        <td class="tot-td" style="background:#d1fae5;">{{ $tot > 0 ? $tot  : '&mdash;' }}</td>
        <td class="{{ $pc }}">{{ $pct !== null ? $pct.'%' : '&mdash;' }}</td>
        <td style="font-weight:700;color:{{ $lstC }};font-size:11px;">{!! $lst !!}</td>
      </tr>
    @endforeach
  </tbody>
</table>
</div>
@php
  $lbS=0; $lbA=0; $lbV=0;
  foreach($students as $st){ $t=$labTotals[$st->reg_no]['total']??0; $p=$labTotals[$st->reg_no]['present']??0;
    if($t>0){ $pc=round(($p/$t)*100,1); $lbA+=$pc; $lbV++; if($pc<75)$lbS++; } }
  $lbA=$lbV>0?round($lbA/$lbV,1):0;
@endphp
<table class="sum-tbl">
  <thead><tr><th>Lab Sessions</th><th>Class Avg Attendance</th><th>Students with Shortage (&lt; 75%)</th><th>Students in Good Standing (&ge; 75%)</th></tr></thead>
  <tbody><tr>
    <td style="font-weight:700;">{{ $labPlans->count() }}</td>
    <td style="font-weight:700;color:{{ $lbA>=75?'#15803d':'#dc2626' }};">{{ $lbA }}%</td>
    <td style="font-weight:700;color:#dc2626;">{{ $lbS }}</td>
    <td style="font-weight:700;color:#15803d;">{{ $students->count()-$lbS }}</td>
  </tr></tbody>
</table>
@endif

<div class="sig-row">
  <div class="sig-box"><div class="sig-line"></div><div class="sig-lbl">Staff In-Charge</div><div style="font-weight:700;font-size:12px;">{{ $assignedStaff->count() ? $assignedStaff->first()->name : '_________________' }}</div></div>
  <div class="sig-box"><div class="sig-line"></div><div class="sig-lbl">HOD Signature</div><div style="font-weight:700;font-size:12px;">Head of Department</div></div>
  <div class="sig-box"><div class="sig-line"></div><div class="sig-lbl">Principal Signature</div><div style="font-weight:700;font-size:12px;">Principal</div></div>
</div>

</body>
</html>

