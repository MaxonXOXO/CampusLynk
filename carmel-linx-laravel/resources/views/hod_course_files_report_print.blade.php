<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Course Files Status - {{ $classroom->classroom_id }}</title>
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
        size: A4 landscape;
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
    }
  </style>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8">

  <!-- Print Actions Bar -->
  <div class="max-w-6xl mx-auto mb-6 p-4 bg-white border border-slate-200 rounded-2xl shadow-sm flex items-center justify-between no-print">
    <div class="flex items-center gap-3">
      <div class="bg-emerald-500/10 text-emerald-600 p-2.5 rounded-xl">
        <span class="material-symbols-rounded">print</span>
      </div>
      <div>
        <h3 class="font-bold text-slate-800 text-sm">Print Configuration</h3>
        <p class="text-xs text-slate-500">Set layout to Landscape, Margins: Narrow/Default, Paper: A4</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition flex items-center gap-2 cursor-pointer text-sm shadow-sm">
        <span class="material-symbols-rounded text-sm">print</span> Print Report
      </button>
      <button onclick="window.close()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition flex items-center gap-2 cursor-pointer text-sm border border-slate-200">
        <span class="material-symbols-rounded text-sm">close</span> Close Preview
      </button>
    </div>
  </div>

  <!-- A4 Printable Sheet Container -->
  <div class="max-w-6xl mx-auto bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm print:border-0 print:p-0 print:shadow-none">
    
    <!-- Heading Banner -->
    <div class="border-b border-slate-200 pb-4 mb-5 flex justify-between items-start">
      <div>
        <h1 class="text-slate-900 font-extrabold tracking-tight text-lg">
          Carmel Polytechnic College
        </h1>
        <p class="text-slate-600 font-bold text-sm uppercase tracking-wider mt-0.5">
          Departmental Course File Auditing Compliance Report
        </p>
        <p class="text-slate-500 text-xs mt-0.5 font-bold">
          Branch: <span class="text-slate-800">{{ $classroom->branch }}</span> | Batch: <span class="text-slate-800">{{ $classroom->classroom_id }}</span> | Semester: <span class="text-slate-800">S{{ $classroom->current_semester }}</span>
        </p>
      </div>
      <div class="text-right">
        <span class="inline-block px-3 py-1 bg-slate-100 border border-slate-200 rounded-lg text-slate-700 font-mono text-xs">
          Date Compiled: {{ $currentDate }}
        </span>
      </div>
    </div>

    <!-- Section 1: Subject Course File Auditing Table -->
    <div>
      <h2 class="text-slate-800 font-extrabold text-sm mb-3 flex items-center gap-1.5">
        <span class="material-symbols-rounded text-emerald-500 text-base">folder_open</span> Syllabus Registry, CO Mappings & NBA Compliance Log
      </h2>
      <div class="overflow-x-auto border border-slate-200 rounded-xl">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
              <th class="p-3 font-bold text-slate-700 text-xs w-28">Subject Code</th>
              <th class="p-3 font-bold text-slate-700 text-xs">Subject Name</th>
              <th class="p-3 font-bold text-slate-700 text-xs">Assigned Lecturer</th>
              <th class="p-3 font-bold text-slate-700 text-xs text-center w-36">Syllabus PDF</th>
              <th class="p-3 font-bold text-slate-700 text-xs text-center w-36">Course Outcomes (CO)</th>
              <th class="p-3 font-bold text-slate-700 text-xs text-center w-40">NBA File Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-150">
            @forelse($subjects as $subj)
              <tr>
                <td class="p-3 font-mono text-slate-800 font-bold">{{ $subj['subject_code'] }}</td>
                <td class="p-3 text-slate-600 font-medium">{{ $subj['subject_name'] }}</td>
                <td class="p-3 text-slate-600">{{ $subj['teacher'] }}</td>
                
                <!-- Syllabus Status -->
                <td class="p-3 text-center">
                  @if($subj['has_syllabus'])
                    <span class="inline-flex items-center gap-1 text-emerald-600 font-bold text-xs">
                      <span class="material-symbols-rounded text-sm">check_circle</span> Uploaded
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1 text-red-500 font-bold text-xs">
                      <span class="material-symbols-rounded text-sm">cancel</span> Pending
                    </span>
                  @endif
                </td>

                <!-- CO Mapping Status -->
                <td class="p-3 text-center">
                  @if($subj['has_cos'])
                    <span class="inline-flex items-center gap-1 text-emerald-600 font-bold text-xs">
                      <span class="material-symbols-rounded text-sm">check_circle</span> Defined
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1 text-red-500 font-bold text-xs">
                      <span class="material-symbols-rounded text-sm">cancel</span> Pending
                    </span>
                  @endif
                </td>

                <!-- NBA Course File Status -->
                <td class="p-3 text-center">
                  @if($subj['nba_status'] === 'Complete')
                    <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold bg-green-100 text-green-700 border border-green-200 rounded-md">
                      COMPLETED
                    </span>
                  @elseif($subj['nba_status'] === 'Draft')
                    <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200 rounded-md">
                      DRAFT IN PROGRESS
                    </span>
                  @else
                    <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-400 border border-slate-200 rounded-md">
                      NOT INITIATED
                    </span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="p-4 text-center text-slate-500 italic">No subject listings found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
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
