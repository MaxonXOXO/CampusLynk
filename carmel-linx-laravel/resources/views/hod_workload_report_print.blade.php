<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Faculty Workload Report - {{ $department }}</title>
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
    .text-left-align {
      text-align: left !important;
    }
    .total-row {
      background-color: #1e293b;
      font-weight: 800;
      color: #ffffff;
    }
    .sign-section {
      margin-top: 50px;
    }

    /* Print (Light Mode) Styles */
    @media print {
      .no-print {
        display: none;
      }
      @page {
        size: A4 portrait;
        margin: 1cm;
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
        padding: 6px !important;
      }
      .total-row {
        background-color: #f3f4f6 !important;
        color: #000000 !important;
      }
      .meta-lbl, .meta-val {
        color: #000000 !important;
      }
      .sign-section {
        margin-top: 30px !important;
      }
    }
  </style>
</head>
<body>
  <div class="max-w-4xl mx-auto space-y-8">
    
    <!-- Centered Header Section -->
    <div class="border-b pb-4 text-center relative header-border">
      <h1 class="text-lg font-bold meta-lbl uppercase tracking-widest text-slate-400">Carmel Polytechnic College</h1>
      <h2 class="text-2xl font-black text-white mt-1">Faculty Workload Commencement Report</h2>
      
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
        <div>Academic Year: <strong class="meta-val">{{ $currentYear }} - {{ $currentYear + 1 }}</strong></div>
        <div>Report Type: <strong class="meta-val">{{ $semTerm }}</strong></div>
      </div>

      <div class="no-print absolute top-0 right-0 flex gap-2">
        <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-sm shadow transition duration-200">
          Print Report
        </button>
        <button onclick="window.close()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-bold text-sm shadow transition duration-200">
          Close Preview
        </button>
      </div>
    </div>
    
    <!-- Workload Table -->
    <table class="w-full text-left border">
      <thead>
        <tr class="text-slate-400 font-bold border-b header-border">
          <th class="p-3 text-center w-16">Sl. No.</th>
          <th class="p-3 text-left-align">Faculty Name</th>
          <th class="p-3 text-left-align">Designation</th>
          <th class="p-3 text-center">Theory Hours / Week</th>
          <th class="p-3 text-center">Lab Hours / Week</th>
          <th class="p-3 text-center w-36">Total Load (Hrs)</th>
        </tr>
      </thead>
      <tbody>
        @php
          $slNo = 1;
          $totalTheory = 0;
          $totalLab = 0;
          $totalAll = 0;
        @endphp
        @forelse ($workload as $facultyName => $data)
          @php
            $totalTheory += $data['theory'];
            $totalLab += $data['lab'];
            $totalAll += $data['total'];
          @endphp
          <tr class="border-b header-border">
            <td class="p-3 text-center">{{ $slNo++ }}</td>
            <td class="p-3 text-left-align font-semibold">{{ $facultyName }}</td>
            <td class="p-3 text-left-align">{{ str_replace('_', ' ', $data['designation']) }}</td>
            <td class="p-3 text-center">{{ $data['theory'] }}</td>
            <td class="p-3 text-center">{{ $data['lab'] }}</td>
            <td class="p-3 text-center font-bold">{{ $data['total'] }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="p-8 text-center text-slate-500 italic">No faculty workload records found. Please ensure batch timetables are configured.</td>
          </tr>
        @endforelse
        
        @if (count($workload) > 0)
          <tr class="total-row">
            <td colspan="3" class="p-3 text-right pr-6 font-bold">Total Department Workload</td>
            <td class="p-3 text-center font-bold">{{ $totalTheory }}</td>
            <td class="p-3 text-center font-bold">{{ $totalLab }}</td>
            <td class="p-3 text-center font-black">{{ $totalAll }}</td>
          </tr>
        @endif
      </tbody>
    </table>
    
    <!-- Signatures Section -->
    <div class="grid grid-cols-2 gap-12 sign-section meta-lbl pt-12">
      <div class="text-center">
        <div class="h-16"></div>
        <p class="border-t border-dashed header-border pt-2 font-bold uppercase tracking-wider text-xs">Head of Department</p>
        <p class="text-xs mt-1">
          @php
            echo $deptNames[strtoupper($department)] ?? $department;
          @endphp Dept.
        </p>
      </div>
      <div class="text-center">
        <div class="h-16"></div>
        <p class="border-t border-dashed header-border pt-2 font-bold uppercase tracking-wider text-xs">Principal</p>
        <p class="text-xs mt-1">Carmel Polytechnic College</p>
      </div>
    </div>
    
  </div>
</body>
</html>
