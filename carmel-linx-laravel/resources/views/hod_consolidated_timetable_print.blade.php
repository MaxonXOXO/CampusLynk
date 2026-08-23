<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consolidated Timetable - {{ $department }}</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    /* Screen (Dark Mode) Styles */
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
      background-color: #0b0f19;
      color: #f1f5f9;
    }
    .header-border {
      border-color: #1e293b;
    }
    .meta-val {
      color: #ffffff;
    }
    .meta-lbl {
      color: #94a3b8;
    }
    table {
      border-collapse: collapse;
      width: 100%;
      border: 2px solid #1e293b;
      background-color: #0f172a;
    }
    th {
      background-color: #1e293b;
      color: #f1f5f9;
      border: 1px solid #334155;
      padding: 12px;
      text-align: center;
      font-weight: 800;
    }
    td {
      border: 1px solid #334155;
      padding: 12px;
      text-align: center;
      vertical-align: middle;
      font-weight: 500;
    }
    .day-cell {
      background-color: #1e293b;
      font-weight: bold;
      color: #ffffff;
    }
    .batch-cell {
      background-color: #0f172a;
      font-weight: bold;
      color: #e2e8f0;
    }
    .lunch-cell {
      background-color: #090d16;
      color: #64748b;
      font-weight: 900;
    }
    .legend-box {
      background-color: #0f172a;
      border: 1px solid #1e293b;
    }
    .legend-title {
      color: #ffffff;
    }
    .legend-item {
      border-color: #1e293b;
      color: #cbd5e1;
    }
    .legend-code {
      color: #ffffff;
    }
    .legend-staff {
      color: #94a3b8;
    }
    .free-period {
      color: #475569;
      font-style: italic;
    }

    /* Print (Light Mode) Styles */
    @media print {
      .no-print {
        display: none;
      }
      @page {
        size: A4 landscape;
        margin: 0.5cm;
      }
      body {
        background-color: #ffffff;
        color: #000000;
        padding: 0;
        margin: 0;
      }
      table {
        background-color: #ffffff;
        border: 2px solid #000000 !important;
      }
      th, td {
        border: 2px solid #000000 !important;
        color: #000000 !important;
        background-color: #ffffff !important;
        padding: 5px !important;
        font-size: 11px !important;
      }
      .day-cell {
        background-color: #f3f4f6 !important;
      }
      .batch-cell {
        background-color: #fafafa !important;
      }
      .lunch-cell {
        background-color: #e5e7eb !important;
      }
      .free-period {
        color: #9ca3af !important;
      }
    }
  </style>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
  <div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Centered Header Section -->
    <div class="border-b pb-4 text-center relative header-border">
      <h1 class="text-lg font-bold meta-lbl uppercase tracking-widest text-slate-400">Carmel Polytechnic College</h1>
      <h2 class="text-2xl font-black text-white mt-1">Consolidated Department Timetable</h2>
      
      <div class="flex justify-center gap-12 mt-4 text-sm meta-lbl">
        <div>Department: <strong class="meta-val">
          @php
            $deptNames = [
              "EL" => "Electronics Engineering",
              "CS" => "Computer Engineering",
              "ME" => "Mechanical Engineering",
              "EE" => "Electrical & Electronics Engineering",
              "CE" => "Civil Engineering",
              "CH" => "Chemical Engineering"
            ];
            echo $deptNames[strtoupper($department)] ?? $department;
          @endphp
        </strong></div>
        <div>Batches: <strong class="meta-val">{{ implode(', ', array_keys($timetables)) }}</strong></div>
        <div>Academic Year: <strong class="meta-val">{{ $currentYear }}</strong></div>
      </div>

      <div class="no-print absolute top-0 right-0 flex gap-2">
        <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-sm shadow transition duration-200">
          Print Consolidated Sheet
        </button>
        <button onclick="window.close()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-bold text-sm shadow transition duration-200">
          Close Preview
        </button>
      </div>
    </div>
    
    <!-- Consolidated Timetable Grid -->
    <table class="w-full text-left border">
      <thead>
        <tr class="text-slate-400 font-bold border-b header-border">
          <th class="p-3 text-center w-24">Day</th>
          <th class="p-3 text-center w-32">Classroom</th>
          <th class="p-3 text-center">Period 1<br><span class="text-xs font-normal meta-lbl">09:00 - 10:00</span></th>
          <th class="p-3 text-center">Period 2<br><span class="text-xs font-normal meta-lbl">10:00 - 11:00</span></th>
          <th class="p-3 text-center">Period 3<br><span class="text-xs font-normal meta-lbl">11:10 - 12:10</span></th>
          <th class="p-3 text-center w-16">Lunch</th>
          <th class="p-3 text-center">Period 4<br><span class="text-xs font-normal meta-lbl">01:00 - 02:00</span></th>
          <th class="p-3 text-center">Period 5<br><span class="text-xs font-normal meta-lbl">02:00 - 03:00</span></th>
          <th class="p-3 text-center">Period 6<br><span class="text-xs font-normal meta-lbl">03:00 - 04:00</span></th>
        </tr>
      </thead>
      <tbody>
        @php
          $days = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];
          $batchCount = count($timetables);
          $scheduledSubjects = new \StdClass();
          $scheduledSubjects->list = [];
        @endphp

        @foreach ($days as $dayIndex => $day)
          @php
            $firstBatchRow = true;
          @endphp
          @foreach ($timetables as $classroomId => $info)
            @php
              $dayData = $info['data'][$day] ?? [];
              
              $s1 = $dayData[1] ?? ['subject' => '', 'staff' => ''];
              $s2 = $dayData[2] ?? ['subject' => '', 'staff' => ''];
              $s3 = $dayData[3] ?? ['subject' => '', 'staff' => ''];
              $s4 = $dayData[4] ?? ['subject' => '', 'staff' => ''];
              $s5 = $dayData[5] ?? ['subject' => '', 'staff' => ''];
              $s6 = $dayData[6] ?? ['subject' => '', 'staff' => ''];

              foreach([$s1, $s2, $s3, $s4, $s5, $s6] as $slot) {
                if (!empty($slot['subject'])) {
                  $scheduledSubjects->list[$slot['subject']] = $classroomId;
                }
              }
            @endphp
            <tr class="border-b header-border">
              @if ($firstBatchRow)
                <td rowspan="{{ $batchCount }}" class="p-4 text-center font-bold bg-slate-900/40 day-cell">{{ $day }}</td>
              @endif
              
              <td class="p-3 font-bold batch-cell border-r border-slate-800/40">{{ $classroomId }}</td>

              {{-- Forenoon Slots --}}
              @if ($s1['subject'] && $s1['subject'] === $s2['subject'] && $s2['subject'] === $s3['subject'])
                {!! renderPrintCellHtml($s1, 3, $info['subjects']) !!}
              @elseif ($s1['subject'] && $s1['subject'] === $s2['subject'])
                {!! renderPrintCellHtml($s1, 2, $info['subjects']) !!}
                {!! renderPrintCellHtml($s3, 1, $info['subjects']) !!}
              @elseif ($s2['subject'] && $s2['subject'] === $s3['subject'])
                {!! renderPrintCellHtml($s1, 1, $info['subjects']) !!}
                {!! renderPrintCellHtml($s2, 2, $info['subjects']) !!}
              @else
                {!! renderPrintCellHtml($s1, 1, $info['subjects']) !!}
                {!! renderPrintCellHtml($s2, 1, $info['subjects']) !!}
                {!! renderPrintCellHtml($s3, 1, $info['subjects']) !!}
              @endif

              {{-- Lunch Column (rowspan across all days & batches) --}}
              @if ($dayIndex === 0 && $firstBatchRow)
                <td rowspan="{{ 5 * $batchCount }}" class="p-4 text-center font-black lunch-cell text-base" style="writing-mode: vertical-rl; text-orientation: mixed; transform: rotate(180deg); letter-spacing: 5px; vertical-align: middle; min-width: 50px;">LUNCH BREAK</td>
              @endif

              {{-- Afternoon Slots --}}
              @if ($s4['subject'] && $s4['subject'] === $s5['subject'] && $s5['subject'] === $s6['subject'])
                {!! renderPrintCellHtml($s4, 3, $info['subjects']) !!}
              @elseif ($s4['subject'] && $s4['subject'] === $s5['subject'])
                {!! renderPrintCellHtml($s4, 2, $info['subjects']) !!}
                {!! renderPrintCellHtml($s6, 1, $info['subjects']) !!}
              @elseif ($s5['subject'] && $s5['subject'] === $s6['subject'])
                {!! renderPrintCellHtml($s4, 1, $info['subjects']) !!}
                {!! renderPrintCellHtml($s5, 2, $info['subjects']) !!}
              @else
                {!! renderPrintCellHtml($s4, 1, $info['subjects']) !!}
                {!! renderPrintCellHtml($s5, 1, $info['subjects']) !!}
                {!! renderPrintCellHtml($s6, 1, $info['subjects']) !!}
              @endif
            </tr>
            @php
              $firstBatchRow = false;
            @endphp
          @endforeach
        @endforeach
      </tbody>
    </table>

    
  </div>
</body>
</html>

@php
  function renderPrintCellHtml($slot, $colspan = 1, $subjectsList = []) {
    $colspanAttr = $colspan > 1 ? "colspan=\"{$colspan}\"" : "";
    if (empty($slot['subject'])) {
      return "<td {$colspanAttr} class=\"p-4 text-center free-period\">-- Free --</td>";
    }
    
    $matchedSub = $subjectsList->firstWhere('subject_code', $slot['subject']);
    $subjectName = $matchedSub ? $matchedSub->subject_name : '';
    
    // Find assigned staff names
    $assignedStaff = [];
    if ($matchedSub) {
      $assignedStaff = DB::table('subject_staff_assignments')
          ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
          ->where('subject_staff_assignments.batch_subject_id', $matchedSub->id)
          ->pluck('staff_profiles.name')
          ->toArray();
    }
    $staffDisplay = count($assignedStaff) > 0 ? implode(', ', $assignedStaff) : ($slot['staff'] ?? 'N/A');

    return "
      <td {$colspanAttr} class=\"p-4 text-center\">
        <div style=\"font-weight: 850; font-size: 15px;\">{$slot['subject']}</div>
        <div style=\"font-weight: 600; font-size: 12px; margin-top: 2px;\">{$subjectName}</div>
        <div style=\"font-size: 11px; margin-top: 2px;\">{$staffDisplay}</div>
      </td>
    ";
  }
@endphp
