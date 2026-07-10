<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Remedial Analysis Consolidated Report - {{ $room->subject_code }}</title>
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
      font-size: 10.5px !important;
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
        size: A4 landscape;
        margin: 0.6cm;
      }
      table {
        page-break-inside: auto;
        width: 100% !important;
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
  <div class="max-w-6xl mx-auto mb-6 p-4 bg-white border border-slate-200 rounded-xl shadow-sm flex items-center justify-between no-print">
    <div class="flex items-center gap-3">
      <div class="bg-purple-500/10 text-purple-600 p-2.5 rounded-lg">
        <span class="material-symbols-rounded">print</span>
      </div>
      <div>
        <h3 class="font-bold text-slate-800 text-sm">Remedial Analysis Consolidated Report</h3>
        <p class="text-[11px] text-slate-500">Set layout to Landscape, Margins: Narrow/Default, Paper: A4</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <button onclick="window.print()" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition flex items-center gap-2 cursor-pointer text-sm shadow-sm">
        <span class="material-symbols-rounded text-sm">print</span> Print Report
      </button>
      <button onclick="window.opener ? window.close() : window.location.href = '/remedial-sessions'" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition flex items-center gap-2 cursor-pointer text-sm border border-slate-200">
        <span class="material-symbols-rounded text-sm">close</span> Close Preview
      </button>
    </div>
  </div>

  <!-- A4 Printable Sheet Container (Landscape width matches 6xl) -->
  <div class="max-w-6xl mx-auto bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm print:border-0 print:p-0 print:shadow-none">
    
    <!-- Heading Banner -->
    <div class="border-b-2 border-slate-900 pb-4 mb-5 flex justify-between items-start">
      <div>
        <h1 class="text-slate-900 font-extrabold tracking-tight text-lg">
          Carmel Polytechnic College
        </h1>
        <p class="text-slate-700 font-bold text-sm uppercase tracking-wider mt-0.5">
          Remedial Analysis Consolidated Report
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
        <span class="text-slate-500 uppercase tracking-wide font-bold block" style="font-size: 9px;">Remedial Coaching Details</span>
        <span class="text-slate-800 font-bold text-sm">{{ $logs->count() }} Sessions ({{ $totalStudents }} Students Registered)</span>
      </div>
    </div>

    <!-- Consolidated Report Table -->
    <div class="mb-8 overflow-hidden border border-slate-200 rounded-lg">
      <table class="w-full text-left border-collapse print:w-full">
        <thead>
          <tr class="bg-slate-100 border-b border-slate-200 text-slate-700">
            <th class="p-2 w-10 text-center font-bold">Sl No</th>
            <th class="p-2 font-bold min-w-[120px]">Student Name</th>
            <th class="p-2 w-28 font-bold font-mono">SBTE Number</th>
            
            <!-- Dates and times columns -->
            @foreach($logs as $idx => $log)
              <th class="p-2 w-14 text-center font-bold" title="{{ $log->session_date }}">
                <div class="text-[9px] font-semibold text-slate-850">{{ date('d/m', strtotime($log->session_date)) }}</div>
                <div class="text-[8px] text-slate-400 font-normal font-mono">{{ $log->start_time ? date('H:i', strtotime($log->start_time)) : '--:--' }}</div>
              </th>
            @endforeach
            
            <th class="p-2 w-14 text-center font-bold text-emerald-700">Days Pres</th>
            <th class="p-2 w-14 text-center font-bold text-rose-700">Days Abs</th>
            <th class="p-2 w-24 text-center font-bold">Test Attended</th>
            <th class="p-2 w-28 text-center font-bold">Improvement Status</th>
            <th class="p-2 w-32 font-bold">Remarks</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-150">
          @php $sNo = 1; @endphp
          @forelse($analysisMatrix as $regNo => $data)
            <tr class="hover:bg-slate-50/50">
              <td class="p-2 text-center text-slate-500 font-mono">{{ $sNo++ }}</td>
              <td class="p-2 font-semibold text-slate-900">{{ $data['name'] }}</td>
              <td class="p-2 text-slate-700 font-mono text-xs">{{ $data['sbte_reg_no'] ?: '-' }}</td>
              
              <!-- Individual Present/Absent indicators -->
              @foreach($logs as $log)
                <td class="p-2 text-center font-bold">
                  @if(isset($data['sessions'][$log->log_id]) && $data['sessions'][$log->log_id])
                    <span class="text-emerald-600 font-semibold text-sm">P</span>
                  @else
                    <span class="text-rose-500 font-semibold text-sm">A</span>
                  @endif
                </td>
              @endforeach
              
              <td class="p-2 text-center font-bold text-emerald-600 font-mono">{{ $data['present_count'] }}</td>
              <td class="p-2 text-center font-bold text-rose-500 font-mono">{{ $data['absent_count'] }}</td>
              <td class="p-2 text-center font-semibold text-slate-700 font-mono">{{ $data['tests_attended'] }}</td>
              <td class="p-2 text-center">
                <span class="inline-block px-2 py-0.5 rounded font-bold text-[9px] uppercase 
                  {{ $data['improvement_status'] === 'Improved' ? 'bg-emerald-100 text-emerald-800' : ($data['improvement_status'] === 'Marginal' ? 'bg-amber-100 text-amber-800' : 'bg-slate-150 text-slate-600') }}">
                  {{ $data['improvement_status'] }}
                </span>
              </td>
              <td class="p-2 text-slate-600 font-medium">{{ $data['remark'] }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="{{ 8 + $logs->count() }}" class="p-4 text-center text-slate-400 italic">No registered student records available.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
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
