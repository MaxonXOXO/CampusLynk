<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activity Points Report - {{ $classroom->classroom_id }}</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #ffffff;
      color: #0f172a;
    }
    /* Enforce 12px print font standard for tabular A4 data */
    body, table, td, th {
      font-size: 12px !important;
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
        margin: 0.5cm;
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
      .page-break {
        page-break-before: always;
      }
    }
  </style>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8">

  <!-- Print Actions Bar -->
  <div class="max-w-4xl mx-auto mb-6 p-4 bg-white border border-slate-200 rounded-2xl shadow-sm flex items-center justify-between no-print">
    <div class="flex items-center gap-3">
      <div class="bg-rose-500/10 text-rose-600 p-2.5 rounded-xl">
        <span class="material-symbols-rounded">print</span>
      </div>
      <div>
        <h3 class="font-bold text-slate-800 text-sm">Print Configuration</h3>
        <p class="text-xs text-slate-500">Set layout to Portrait, Margins: Narrow/Default, Paper: A4</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <button onclick="window.print()" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl transition flex items-center gap-2 cursor-pointer text-sm shadow-sm">
        <span class="material-symbols-rounded text-sm">print</span> Print Report
      </button>
      <button onclick="window.close()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition flex items-center gap-2 cursor-pointer text-sm border border-slate-200">
        <span class="material-symbols-rounded text-sm">close</span> Close Preview
      </button>
    </div>
  </div>

  <!-- A4 Printable Sheet Container -->
  <div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm print:border-0 print:p-0 print:shadow-none">
    
    <!-- Heading Banner -->
    <div class="border-b border-slate-200 pb-4 mb-5 flex justify-between items-start">
      <div>
        <h1 class="text-slate-900 font-extrabold tracking-tight text-lg">
          Carmel Polytechnic College
        </h1>
        <p class="text-slate-600 font-bold text-sm uppercase tracking-wider mt-0.5">
          Student Extra-Curricular Activity Points Audit
        </p>
        <p class="text-slate-500 text-xs mt-0.5 font-bold">
          Branch: <span class="text-slate-800">{{ $classroom->branch }}</span> | Batch: <span class="text-slate-800">{{ $classroom->classroom_id }}</span> | Semester Scope: <span class="text-slate-800 uppercase">{{ $semester === 'all' ? 'All Semesters' : 'Semester ' . $semester }}</span>
        </p>
      </div>
      <div class="text-right">
        <span class="inline-block px-3 py-1 bg-slate-100 border border-slate-200 rounded-lg text-slate-700 font-mono text-xs">
          Date Compiled: {{ $currentDate }}
        </span>
      </div>
    </div>

    <!-- Section 1: Consolidated Roster Table -->
    <div class="mb-8">
      <h2 class="text-slate-800 font-extrabold text-sm mb-3 flex items-center gap-1.5">
        <span class="material-symbols-rounded text-rose-500 text-base">military_tech</span> Cumulative Activity Points & Course Completion Status (Min 75 Points)
      </h2>
      <div class="overflow-x-auto border border-slate-200 rounded-xl">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
              <th class="p-2.5 font-bold text-slate-700 text-xs text-center w-16">Roll No</th>
              <th class="p-2.5 font-bold text-slate-700 text-xs">Student Name</th>
              <th class="p-2.5 font-bold text-slate-700 text-xs">SBTE Registration Number</th>
              <th class="p-2.5 font-bold text-slate-700 text-xs text-center w-28">Points Claimed</th>
              <th class="p-2.5 font-bold text-slate-700 text-xs text-center w-28">Points Awarded</th>
              <th class="p-2.5 font-bold text-slate-700 text-xs text-center w-36">Audit Outcome</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-150">
            @forelse($students as $std)
              <tr class="{{ $std['status'] === 'Deficient' ? 'bg-red-50/30' : '' }}">
                <td class="p-2.5 text-center text-slate-800 font-bold">{{ $std['roll_no'] ?: '-' }}</td>
                <td class="p-2.5 text-slate-900 font-medium">{{ $std['name'] }}</td>
                <td class="p-2.5 font-mono text-slate-600 font-bold">{{ $std['sbte_reg_no'] ?: 'NOT UPDATED' }}</td>
                <td class="p-2.5 text-center text-slate-600">{{ $std['claimed'] }}</td>
                <td class="p-2.5 text-center text-slate-850 font-bold">{{ $std['awarded'] }}</td>
                <td class="p-2.5 text-center">
                  @if($std['status'] === 'Met')
                    <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold bg-green-100 text-green-700 border border-green-200 rounded-md">
                      TARGET MET (>= 75)
                    </span>
                  @else
                    <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold bg-red-100 text-red-700 border border-red-200 rounded-md">
                      DEFICIENT (< 75)
                    </span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="p-4 text-center text-slate-500 italic">No approved student profiles found in this batch.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Section 2: Student-wise Detailed Claim Breakdowns -->
    <div class="page-break">
      <h2 class="text-slate-800 font-extrabold text-sm mb-4 flex items-center gap-1.5 border-b border-slate-200 pb-2">
        <span class="material-symbols-rounded text-rose-500 text-base">article</span> Student Certificate & Claims Breakdown Logs
      </h2>

      @forelse($students as $std)
        <div class="mb-6 p-4 border border-slate-150 rounded-xl bg-slate-50/40 page-break-inside-avoid">
          <div class="flex justify-between items-start mb-3">
            <div>
              <h3 class="font-extrabold text-slate-900 text-sm">
                {{ $std['name'] }}
              </h3>
              <p class="text-xs text-slate-500 mt-0.5 font-bold">SBTE Registration Number: <span class="text-slate-700 font-mono">{{ $std['sbte_reg_no'] ?: 'NOT UPDATED' }}</span></p>
            </div>
            <div class="text-right">
              <span class="inline-block px-2 py-0.5 text-xs font-bold bg-slate-100 border border-slate-200 text-slate-700 rounded-md">
                Awarded Total: {{ $std['awarded'] }} / 75 pts
              </span>
            </div>
          </div>

          <div class="overflow-hidden border border-slate-200 rounded-lg bg-white">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 font-bold text-[10px] text-slate-700">
                  <th class="p-2 w-12 text-center">Sem</th>
                  <th class="p-2">Segment</th>
                  <th class="p-2">Activity Name</th>
                  <th class="p-2">Level</th>
                  <th class="p-2 text-center w-16">Claimed</th>
                  <th class="p-2 text-center w-16">Awarded</th>
                  <th class="p-2 text-center w-24">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-xs">
                @forelse($std['claims_list'] as $claim)
                  <tr>
                    <td class="p-2 text-center">S{{ $claim->semester }}</td>
                    <td class="p-2 text-slate-600">{{ $claim->activity_segment }}</td>
                    <td class="p-2 text-slate-800 font-medium">{{ $claim->activity_name }}</td>
                    <td class="p-2 text-slate-500">{{ $claim->level }}</td>
                    <td class="p-2 text-center text-slate-500">{{ $claim->points_claimed }}</td>
                    <td class="p-2 text-center font-bold">{{ $claim->points_awarded }}</td>
                    <td class="p-2 text-center">
                      @if($claim->status === 'Verified')
                        <span class="text-emerald-600 font-bold">Verified</span>
                      @elseif($claim->status === 'Rejected')
                        <span class="text-red-500 font-bold">Rejected</span>
                      @else
                        <span class="text-slate-400">Pending</span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="p-3 text-center text-slate-400 italic">No extra-curricular activity point claims registered.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      @empty
        <p class="text-slate-500 italic text-center p-4">No student records to display.</p>
      @endforelse
    </div>

    <!-- Signatures Panel -->
    <div class="mt-16 grid grid-cols-3 gap-8 border-t border-slate-200 pt-8 print:mt-12">
      <div class="text-center">
        <div class="h-10"></div>
        <p class="font-bold text-slate-800 text-xs">Class Tutor Signature</p>
        <p class="text-[10px] text-slate-500 mt-0.5">Carmel Polytechnic College</p>
      </div>
      <div class="text-center">
        <div class="h-10"></div>
        <p class="font-bold text-slate-800 text-xs">Head of the Department (HOD)</p>
        <p class="text-[10px] text-slate-500 mt-0.5">{{ $classroom->branch }} Department</p>
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
