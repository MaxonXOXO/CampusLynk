<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Remedial Attendance Report - {{ $room->subject_code }}</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #ffffff;
      color: #0f172a;
    }
    body, table, td, th {
      font-size: 11px !important;
    }
    @media print {
      body {
        background-color: #ffffff;
        color: #000000;
        margin: 0;
        padding: 0;
      }
      .no-print {
        display: none !important;
      }
      @page {
        size: A4 portrait;
        margin: 0.6cm;
      }
      table {
        page-break-inside: auto;
      }
      tr {
        page-break-inside: avoid;
        page-break-after: auto;
      }
      thead {
        display: table-header-group;
      }
    }
  </style>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8">

  <!-- Print Actions Bar -->
  <div class="max-w-4xl mx-auto mb-6 p-4 bg-white border border-slate-200 rounded-xl shadow-sm flex items-center justify-between no-print">
    <div class="flex items-center gap-3">
      <div class="bg-blue-500/10 text-blue-600 p-2.5 rounded-lg">
        <span class="material-symbols-rounded">print</span>
      </div>
      <div>
        <h3 class="font-bold text-slate-800 text-sm">Remedial Attendance Report</h3>
        <p class="text-[11px] text-slate-500">Set layout to Portrait, Margins: Narrow/Default, Paper: A4</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition flex items-center gap-2 cursor-pointer text-sm shadow-sm">
        <span class="material-symbols-rounded text-sm">print</span> Print Report
      </button>
      <button onclick="window.opener ? window.close() : window.location.href = '/remedial-sessions'" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition flex items-center gap-2 cursor-pointer text-sm border border-slate-200">
        <span class="material-symbols-rounded text-sm">close</span> Close Preview
      </button>
    </div>
  </div>

  <!-- A4 Printable Sheet Container -->
  <div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm print:border-0 print:p-0 print:shadow-none">
    
    <!-- Heading Banner -->
    <div class="border-b-2 border-slate-900 pb-4 mb-5 flex justify-between items-start">
      <div>
        <h1 class="text-slate-900 font-extrabold tracking-tight text-lg">
          Carmel Polytechnic College
        </h1>
        <p class="text-slate-700 font-bold text-sm uppercase tracking-wider mt-0.5">
          Remedial Coaching Activity Log & Attendance Record
        </p>
        <p class="text-slate-500 text-xs mt-0.5 font-semibold">
          Department: <span class="text-slate-800">{{ $fullDepartment }}</span> | Classroom/Batch: <span class="text-slate-800 font-mono">{{ $cleanedBatch }}</span>
        </p>
      </div>
      <div class="text-right">
        <span class="inline-block px-3 py-1 bg-slate-100 border border-slate-200 rounded text-slate-700 font-mono text-xs font-semibold">
          Date Compiled: {{ $currentDate }}
        </span>
      </div>
    </div>

    <!-- Subject & Lecturer Info Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 mb-6">
      <div>
        <span class="text-slate-500 uppercase tracking-wide font-bold block" style="font-size: 9px;">Subject Code</span>
        <span class="text-slate-800 font-mono font-bold text-sm">{{ $room->subject_code }}</span>
      </div>
      <div>
        <span class="text-slate-500 uppercase tracking-wide font-bold block" style="font-size: 9px;">Subject Name</span>
        <span class="text-slate-800 font-bold text-sm">{{ $subject ? $subject->subject_name : $room->subject_code }}</span>
      </div>
      <div>
        <span class="text-slate-500 uppercase tracking-wide font-bold block" style="font-size: 9px;">Course Lecturer</span>
        <span class="text-slate-800 font-bold text-sm">{{ $lecturerName }}</span>
      </div>
      <div>
        <span class="text-slate-500 uppercase tracking-wide font-bold block" style="font-size: 9px;">Total Sessions</span>
        <span class="text-slate-800 font-bold text-sm">{{ $logs->count() }} Sessions ({{ $logs->sum('duration_minutes') }} mins)</span>
      </div>
    </div>

    <!-- Section 1: Session Activity Log -->
    <div class="mb-8">
      <h2 class="text-slate-900 font-bold text-sm mb-3 flex items-center gap-1.5 border-b border-slate-200 pb-1.5 uppercase tracking-wide">
        <span class="material-symbols-rounded text-blue-600 text-lg">event_note</span> Session Activity Log (Coaching Conducted)
      </h2>
      <div class="overflow-x-auto border border-slate-200 rounded-lg">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
              <th class="p-2 w-12 text-center font-bold text-slate-700">No.</th>
              <th class="p-2 w-28 font-bold text-slate-700">Session Date</th>
              <th class="p-2 w-20 text-center font-bold text-slate-700">Duration</th>
              <th class="p-2 font-bold text-slate-700">Topic Covered & Revision Details</th>
              <th class="p-2 w-24 text-right font-bold text-slate-700">Attendance</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-150">
            @forelse($logs as $idx => $log)
              <tr>
                <td class="p-2 text-center text-slate-500 font-mono">{{ $idx + 1 }}</td>
                <td class="p-2 font-semibold text-slate-800">{{ date('d-M-Y', strtotime($log->session_date)) }}</td>
                <td class="p-2 text-center text-slate-600 font-mono">{{ $log->duration_minutes }}m</td>
                <td class="p-2 text-slate-700 leading-relaxed font-medium">{{ $log->topic_covered }}</td>
                <td class="p-2 text-right font-semibold text-emerald-600 font-mono">{{ is_array($log->attendance_data) ? count($log->attendance_data) : 0 }} Present</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="p-4 text-center text-slate-400 italic">No coaching sessions have been logged for this room yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Section 2: Consolidated Attendance Matrix -->
    <div class="mb-8">
      <h2 class="text-slate-900 font-bold text-sm mb-3 flex items-center gap-1.5 border-b border-slate-200 pb-1.5 uppercase tracking-wide">
        <span class="material-symbols-rounded text-blue-600 text-lg">grid_on</span> Attendance Matrix
      </h2>
      <div class="overflow-x-auto border border-slate-200 rounded-lg">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
              <th class="p-2 w-10 text-center font-bold text-slate-700">No.</th>
              <th class="p-2 font-bold text-slate-700">Student Name</th>
              <th class="p-2 w-28 font-bold text-slate-700">Admission / Reg No</th>
              <th class="p-2 w-28 font-bold text-slate-700">SBTE Reg No</th>
              @foreach($logs as $idx => $log)
                <th class="p-2 w-12 text-center font-bold text-slate-700" title="{{ $log->session_date }}">
                  S{{ $idx + 1 }}
                  <span class="block text-[8px] text-slate-400 font-normal font-mono">{{ date('d/m', strtotime($log->session_date)) }}</span>
                </th>
              @endforeach
              <th class="p-2 w-16 text-center font-bold text-slate-700">Present</th>
              <th class="p-2 w-16 text-right font-bold text-slate-700">Rate (%)</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-150">
            @php $sNo = 1; @endphp
            @forelse($attendanceMatrix as $regNo => $data)
              <tr>
                <td class="p-2 text-center text-slate-500 font-mono">{{ $sNo++ }}</td>
                <td class="p-2 font-semibold text-slate-900">{{ $data['name'] }}</td>
                <td class="p-2 text-slate-700 font-mono">{{ $data['reg_no'] }}</td>
                <td class="p-2 text-slate-700 font-mono text-xs">{{ $data['sbte_reg_no'] ?: '-' }}</td>
                @foreach($logs as $log)
                  <td class="p-2 text-center font-bold text-sm">
                    @if(isset($data['sessions'][$log->log_id]) && $data['sessions'][$log->log_id])
                      <span class="text-emerald-600">P</span>
                    @else
                      <span class="text-rose-500">A</span>
                    @endif
                  </td>
                @endforeach
                <td class="p-2 text-center font-bold text-slate-800 font-mono">{{ $data['present_count'] }}/{{ $logs->count() }}</td>
                <td class="p-2 text-right font-bold font-mono {{ $data['percentage'] >= 75 ? 'text-emerald-600' : 'text-rose-500' }}">
                  {{ $data['percentage'] }}%
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="{{ 6 + $logs->count() }}" class="p-4 text-center text-slate-400 italic">No registered students found in this room.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($logs->count() > 0)
        <div class="mt-2 text-slate-500 text-[10px] italic">
          * Legend: S1, S2, S3... represent conducted remedial sessions chronologically. P = Present, A = Absent.
        </div>
      @endif
    </div>

    <!-- Signatures Panel -->
    <div class="mt-12 grid grid-cols-3 gap-8 border-t border-slate-200 pt-8 print:mt-10">
      <div class="text-center">
        <div class="h-10"></div>
        <p class="font-bold text-slate-800 text-xs">Course Lecturer Signature</p>
        <p class="text-[10px] text-slate-500 mt-0.5">Carmel Polytechnic College</p>
      </div>
      <div class="text-center">
        <div class="h-10"></div>
        <p class="font-bold text-slate-800 text-xs">Head of the Department (HOD)</p>
        <p class="text-[10px] text-slate-500 mt-0.5">{{ $fullDepartment }} Department</p>
      </div>
      <div class="text-center">
        <div class="h-10"></div>
        <p class="font-bold text-slate-800 text-xs">Principal Seal / Sign</p>
        <p class="text-[10px] text-slate-500 mt-0.5">Carmel Polytechnic College</p>
      </div>
    </div>

  </div>

</body>
</html>
