<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    @if($reportType === 'coverage')
      Course Coverage & Hours - {{ $classroom->classroom_id }}
    @elseif($reportType === 'roster')
      Attendance Roster - {{ $classroom->classroom_id }}
    @else
      Condonation List - {{ $classroom->classroom_id }}
    @endif
  </title>
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
  <div class="max-w-7xl mx-auto mb-6 p-4 bg-white border border-slate-200 rounded-2xl shadow-sm flex items-center justify-between no-print">
    <div class="flex items-center gap-3">
      <div class="bg-sky-500/10 text-sky-600 p-2.5 rounded-xl">
        <span class="material-symbols-rounded">print</span>
      </div>
      <div>
        <h3 class="font-bold text-slate-800 text-sm">Print Configuration</h3>
        <p class="text-xs text-slate-500">Set layout to Landscape, Margins: Narrow/Default, Paper: A4</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <button onclick="window.print()" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl transition flex items-center gap-2 cursor-pointer text-sm shadow-sm">
        <span class="material-symbols-rounded text-sm">print</span> Print Sheet
      </button>
      <button onclick="window.close()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition flex items-center gap-2 cursor-pointer text-sm border border-slate-200">
        <span class="material-symbols-rounded text-sm">close</span> Close Preview
      </button>
    </div>
  </div>

  <!-- A4 Printable Sheet Container -->
  <div class="max-w-7xl mx-auto bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm print:border-0 print:p-0 print:shadow-none">
    
    <!-- Heading Banner -->
    <div class="border-b border-slate-200 pb-4 mb-5 flex justify-between items-start">
      <div>
        <h1 class="text-slate-900 font-extrabold tracking-tight text-lg">
          Carmel Polytechnic College
        </h1>
        <p class="text-slate-600 font-bold text-sm uppercase tracking-wider mt-0.5">
          @if($reportType === 'coverage')
            Course Coverage Rates & Hours Conducted
          @elseif($reportType === 'roster')
            Student Attendance Roster & Deficiencies (Condonation Check)
          @else
            Condonation Required Student List (Below 75%)
          @endif
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

    @if($reportType === 'coverage')
      <!-- Section 1: Subject Lesson Plan Coverage Details -->
      <div class="mb-6">
        <h2 class="text-slate-800 font-extrabold text-sm mb-3 flex items-center gap-1.5">
          <span class="material-symbols-rounded text-sky-500 text-base">auto_stories</span> Course Coverage Rates & Hours Conducted
        </h2>
        <div class="overflow-x-auto border border-slate-200 rounded-xl">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200">
                <th class="p-2.5 font-bold text-slate-700 text-xs">Subject Code</th>
                <th class="p-2.5 font-bold text-slate-700 text-xs">Subject Name</th>
                <th class="p-2.5 font-bold text-slate-700 text-xs">Course Instructor</th>
                <th class="p-2.5 font-bold text-slate-700 text-xs text-center">Conducted Hours</th>
                <th class="p-2.5 font-bold text-slate-700 text-xs text-center">Lesson Plan Completed Rate</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-150">
              @forelse($subjects as $id => $subj)
                <tr>
                  <td class="p-2.5 font-mono text-slate-800 font-bold">{{ $subj['code'] }}</td>
                  <td class="p-2.5 text-slate-600">{{ $subj['name'] }}</td>
                  <td class="p-2.5 text-slate-600">{{ $subj['teacher'] }}</td>
                  <td class="p-2.5 text-slate-800 font-bold text-center">{{ $subj['conducted'] }}</td>
                  <td class="p-2.5 text-center">
                    <div class="inline-flex items-center gap-2">
                      <div class="w-20 bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200">
                        <div class="bg-sky-500 h-full" style="width: {{ $subj['coverage'] }}%"></div>
                      </div>
                      <span class="text-slate-700 font-bold text-xs">{{ $subj['coverage'] }}%</span>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="p-4 text-center text-slate-500 italic">No subject courses registered.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    @elseif($reportType === 'roster')
      <!-- Section 2: Student Consolidated Attendance Matrix -->
      <div>
        <h2 class="text-slate-800 font-extrabold text-sm mb-3 flex items-center gap-1.5">
          <span class="material-symbols-rounded text-sky-500 text-base">group</span> Student Attendance Roster & Deficiencies (Condonation Check)
        </h2>
        <div class="overflow-x-auto border border-slate-200 rounded-xl">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200">
                <th class="p-2 font-bold text-slate-700 text-xs text-center w-12">Roll No</th>
                <th class="p-2 font-bold text-slate-700 text-xs">Student Name</th>
                <th class="p-2 font-bold text-slate-700 text-xs">SBTE Reg No</th>
                @foreach($subjects as $id => $subj)
                  <th class="p-2 font-bold text-slate-700 text-xs text-center" title="{{ $subj['name'] }}">
                    {{ $subj['code'] }}
                  </th>
                @endforeach
                <th class="p-2 font-bold text-slate-700 text-xs text-center bg-slate-100 border-l border-slate-200 w-24">Overall (%)</th>
                <th class="p-2 font-bold text-slate-700 text-xs text-center w-36">Condonation Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-150">
              @forelse($students as $regNo => $data)
                <tr class="{{ $data['overall_percentage'] < 75 ? 'bg-red-50/50 print:bg-red-50/10' : '' }}">
                  <td class="p-2 text-center text-slate-700">{{ $data['roll_no'] ?: '-' }}</td>
                  <td class="p-2 text-slate-900 font-medium">{{ $data['name'] }}</td>
                  <td class="p-2 font-mono text-slate-600 text-xs">{{ $data['sbte_reg_no'] ?: 'NOT UPDATED' }}</td>
                  @foreach($subjects as $id => $subj)
                    @php
                      $att = $data['subjects'][$id] ?? ['present' => 0, 'conducted' => 0, 'percentage' => 0];
                    @endphp
                    <td class="p-2 text-center">
                      <span class="text-slate-800 font-medium block">{{ $att['present'] }}/{{ $att['conducted'] }}</span>
                      <span class="text-[10px] text-slate-500 font-bold block">{{ $att['percentage'] }}%</span>
                    </td>
                  @endforeach
                  <td class="p-2 text-center bg-slate-100 font-bold text-xs border-l border-slate-200">
                    <span class="{{ $data['overall_percentage'] < 75 ? 'text-red-600' : 'text-slate-900' }}">
                      {{ $data['overall_percentage'] }}%
                    </span>
                  </td>
                  <td class="p-2 text-center">
                    @if($data['overall_percentage'] < 75)
                      <span class="inline-block px-2 py-0.5 text-[10px] font-bold bg-red-100 text-red-700 border border-red-200 rounded-md">
                        Condonation Required
                      </span>
                    @else
                      <span class="inline-block px-2 py-0.5 text-[10px] font-bold bg-green-100 text-green-700 border border-green-200 rounded-md">
                        Regular
                      </span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="{{ 5 + count($subjects) }}" class="p-4 text-center text-slate-500 italic">No approved student profiles found in this batch.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    @else
      <!-- Section 3: Condonation Required List (Only < 75% Attendance with SBTE No) -->
      <div>
        <h2 class="text-slate-800 font-extrabold text-sm mb-3 flex items-center gap-1.5 text-red-600">
          <span class="material-symbols-rounded text-red-500 text-base">warning</span> Attendance Deficient Students (Condonation List)
        </h2>
        <div class="overflow-x-auto border border-slate-200 rounded-xl">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200">
                <th class="p-2.5 font-bold text-slate-700 text-xs text-center w-16">Roll No</th>
                <th class="p-2.5 font-bold text-slate-700 text-xs">Student Name</th>
                <th class="p-2.5 font-bold text-slate-700 text-xs">SBTE Registration Number</th>
                <th class="p-2.5 font-bold text-slate-700 text-xs text-center w-32">Overall Attendance</th>
                <th class="p-2.5 font-bold text-slate-700 text-xs text-center w-40">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-150">
              @php
                $condonationCount = 0;
              @endphp
              @foreach($students as $regNo => $data)
                @if($data['overall_percentage'] < 75)
                  @php $condonationCount++; @endphp
                  <tr class="bg-red-50/30">
                    <td class="p-2.5 text-center text-slate-800 font-bold">{{ $data['roll_no'] ?: '-' }}</td>
                    <td class="p-2.5 text-slate-900 font-medium">{{ $data['name'] }}</td>
                    <td class="p-2.5 font-mono text-slate-700 font-bold">{{ $data['sbte_reg_no'] ?: 'NOT UPDATED' }}</td>
                    <td class="p-2.5 text-center text-red-600 font-bold">{{ $data['overall_percentage'] }}%</td>
                    <td class="p-2.5 text-center">
                      <span class="inline-block px-2.5 py-0.5 text-xs font-bold bg-red-150 text-red-700 border border-red-300 rounded-md">
                        Condonation Candidate
                      </span>
                    </td>
                  </tr>
                @endif
              @endforeach

              @if($condonationCount === 0)
                <tr>
                  <td colspan="5" class="p-5 text-center text-green-600 font-bold italic">
                    Great! All students in this batch have attendance above 75%. No condonation required.
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    @endif

    <!-- Signatures Panel -->
    <div class="mt-12 grid grid-cols-3 gap-8 border-t border-slate-200 pt-8 print:mt-10">
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
