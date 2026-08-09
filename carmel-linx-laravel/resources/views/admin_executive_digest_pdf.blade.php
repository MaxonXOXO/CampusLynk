<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Polytechnic College - Executive Board Governance Digest</title>
  <!-- Tailwind CSS CDN for styling -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

  <style>
    @page {
      size: A4 portrait;
      margin: 12mm 15mm;
    }
    body {
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      color: #0f172a;
      background: #ffffff;
      font-size: 12px;
    }
    .print-container {
      width: 100%;
      max-width: 210mm;
      margin: 0 auto;
      background: #ffffff;
    }
    @media print {
      body {
        background: #ffffff !important;
        color: #000000 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
      .no-print {
        display: none !important;
      }
      .page-break {
        page-break-after: always;
      }
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    th, td {
      border: 1px solid #cbd5e1;
      padding: 6px 10px;
      text-align: left;
    }
    th {
      background-color: #f1f5f9;
      font-weight: 700;
      color: #1e293b;
    }
  </style>
</head>
<body class="p-6 bg-slate-100">

  <!-- No-Print Action Bar -->
  <div class="no-print max-w-4xl mx-auto mb-6 flex justify-between items-center bg-slate-900 text-white p-4 rounded-xl shadow-lg">
    <div class="flex items-center gap-3">
      <span class="material-symbols-rounded text-amber-400 text-2xl">description</span>
      <div>
        <h2 class="font-bold text-base">Executive Board Governance Digest (A4 Formal Report)</h2>
        <p class="text-xs text-slate-400">Carmel Polytechnic College - Alappuzha</p>
      </div>
    </div>
    <div class="flex gap-3">
      <button onclick="window.print()" class="px-5 py-2 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white font-bold rounded-lg text-xs cursor-pointer shadow flex items-center gap-1.5">
        <span class="material-symbols-rounded text-sm">print</span> Print / Save PDF
      </button>
      <button onclick="window.close()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-lg text-xs cursor-pointer">
        Close Window
      </button>
    </div>
  </div>

  <!-- Printable A4 Container -->
  <div class="print-container bg-white p-8 rounded-xl shadow-xl border border-slate-200">
    
    <!-- Institutional Header -->
    <div class="border-b-2 border-slate-900 pb-4 mb-6 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <img src="{{ asset('logo.jpg') }}" class="w-16 h-16 rounded-xl object-cover border border-slate-300 shadow-sm" alt="Carmel Logo">
        <div>
          <h1 class="font-black text-xl text-slate-900 uppercase tracking-tight">Carmel Polytechnic College</h1>
          <p class="text-xs text-slate-600 font-semibold">Punnapra, Alappuzha, Kerala - 688004 | Approved by AICTE &amp; Affiliated to SBTE Kerala</p>
          <p class="text-xs font-extrabold text-blue-900 tracking-wider uppercase mt-1">Institutional Governance Digest &amp; Board Performance Audit</p>
        </div>
      </div>
      <div class="text-right border-l border-slate-300 pl-4">
        <span class="block text-[10px] text-slate-500 uppercase font-bold tracking-wider">Report Generated</span>
        <span class="font-bold text-sm text-slate-900 font-mono">{{ $todayStr }}</span>
        <span class="block text-[10px] text-slate-500 uppercase font-bold tracking-wider mt-1">Academic Session</span>
        <span class="font-bold text-xs text-slate-800">{{ $academicYear }}</span>
      </div>
    </div>

    <!-- Executive Summary Stat Cards Grid -->
    <div class="grid grid-cols-4 gap-3 mb-6">
      <div class="bg-slate-50 border border-slate-300 p-3 rounded-lg text-center">
        <span class="block text-[10px] text-slate-500 font-extrabold uppercase tracking-wider">Total Academic Staff</span>
        <span class="font-black text-xl text-slate-900 mt-0.5 block">{{ $totalStaff }}</span>
      </div>
      <div class="bg-slate-50 border border-slate-300 p-3 rounded-lg text-center">
        <span class="block text-[10px] text-slate-500 font-extrabold uppercase tracking-wider">Approved Students</span>
        <span class="font-black text-xl text-slate-900 mt-0.5 block">{{ $approvedStudents }} / {{ $totalStudents }}</span>
      </div>
      <div class="bg-slate-50 border border-slate-300 p-3 rounded-lg text-center">
        <span class="block text-[10px] text-slate-500 font-extrabold uppercase tracking-wider">Active Classrooms</span>
        <span class="font-black text-xl text-slate-900 mt-0.5 block">{{ $totalClassrooms }}</span>
      </div>
      <div class="bg-slate-50 border border-slate-300 p-3 rounded-lg text-center">
        <span class="block text-[10px] text-slate-500 font-extrabold uppercase tracking-wider">Staff On Leave Today</span>
        <span class="font-black text-xl text-amber-700 mt-0.5 block">{{ $activeLeavesToday->count() }}</span>
      </div>
    </div>

    <!-- Department-wise Performance & Enrollment Breakdown Matrix -->
    <div class="mb-6">
      <h3 class="font-bold text-sm text-slate-900 border-b border-slate-300 pb-1 mb-3 uppercase tracking-wider">
        1. Department Branch Oversight &amp; Student Matrix
      </h3>
      <table>
        <thead>
          <tr>
            <th>Branch Code</th>
            <th>Department Name</th>
            <th class="text-center">Active Faculty</th>
            <th class="text-center">Enrollment</th>
            <th class="text-center">Batches</th>
            <th class="text-center">Pending Approvals</th>
          </tr>
        </thead>
        <tbody>
          @foreach($deptMatrix as $dept)
          <tr>
            <td class="font-mono font-bold text-blue-900">{{ $dept['code'] }}</td>
            <td class="font-semibold text-slate-800">{{ $dept['name'] }}</td>
            <td class="text-center font-bold">{{ $dept['staff_count'] }}</td>
            <td class="text-center font-bold">{{ $dept['student_count'] }}</td>
            <td class="text-center font-bold">{{ $dept['batch_count'] }}</td>
            <td class="text-center font-bold text-amber-700">{{ $dept['pending_leaves'] }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Department Previous Semester Academic Pass Rate Statistics (3 Semesters per Dept) -->
    <div class="mb-6">
      <h3 class="font-bold text-sm text-slate-900 border-b border-slate-300 pb-1 mb-3 uppercase tracking-wider">
        2. Department Previous Semester Academic Pass Rate Statistics (3 Semesters per Dept)
      </h3>
      <table>
        <thead>
          <tr>
            <th>Branch Code</th>
            <th>Department Name</th>
            <th class="text-center">Sem 1 / 2 Pass %</th>
            <th class="text-center">Sem 3 / 4 Pass %</th>
            <th class="text-center">Sem 5 / 6 Pass %</th>
            <th class="text-center">Dept Avg Pass %</th>
          </tr>
        </thead>
        <tbody>
          @foreach($deptMatrix as $dept)
          <tr>
            <td class="font-mono font-bold text-blue-900">{{ $dept['code'] }}</td>
            <td class="font-semibold text-slate-800">{{ $dept['name'] }}</td>
            <td class="text-center font-mono font-semibold text-slate-700">{{ number_format($dept['sem_s1'], 1) }}%</td>
            <td class="text-center font-mono font-semibold text-slate-700">{{ number_format($dept['sem_s3'], 1) }}%</td>
            <td class="text-center font-mono font-semibold text-slate-700">{{ number_format($dept['sem_s5'], 1) }}%</td>
            <td class="text-center font-mono font-bold text-blue-900 bg-blue-50/50">{{ number_format($dept['avg_pct'], 1) }}%</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Active Staff Leaves Record Today -->
    <div class="mb-6">
      <h3 class="font-bold text-sm text-slate-900 border-b border-slate-300 pb-1 mb-3 uppercase tracking-wider">
        3. Today's Staff Absence &amp; Leave Ledger Overview
      </h3>
      @if($activeLeavesToday->count() > 0)
      <table>
        <thead>
          <tr>
            <th>Staff Name</th>
            <th>Department</th>
            <th>Leave Type</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>Days</th>
            <th>Overall Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($activeLeavesToday as $leave)
          <tr>
            <td class="font-bold text-slate-900">{{ $leave->staff_name }}</td>
            <td class="font-semibold">{{ $leave->department }}</td>
            <td class="font-bold text-blue-900">{{ $leave->leave_type }}</td>
            <td class="font-mono text-xs">{{ $leave->from_date }}</td>
            <td class="font-mono text-xs">{{ $leave->to_date }}</td>
            <td class="text-center font-bold">{{ $leave->total_days }}</td>
            <td>
              <span class="font-bold text-xs uppercase px-1.5 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-300">
                {{ str_replace('_', ' ', $leave->overall_status) }}
              </span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
      <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg text-center text-slate-500 font-semibold">
        No active staff leave applications recorded for today ({{ $todayStr }}).
      </div>
      @endif
    </div>

    <!-- Formal Signatures & Certification -->
    <div class="mt-12 pt-8 border-t border-slate-300 flex justify-between items-end text-xs">
      <div class="text-center w-48">
        <div class="h-10"></div>
        <div class="border-t border-slate-800 pt-1 font-bold text-slate-900 uppercase">Super Admin / Office</div>
        <span class="text-[10px] text-slate-500">Carmel Polytechnic College</span>
      </div>
      <div class="text-center w-48">
        <div class="h-10"></div>
        <div class="border-t border-slate-800 pt-1 font-bold text-slate-900 uppercase">Principal</div>
        <span class="text-[10px] text-slate-500">Carmel Polytechnic College</span>
      </div>
      <div class="text-center w-48">
        <div class="h-10"></div>
        <div class="border-t border-slate-800 pt-1 font-bold text-slate-900 uppercase">Chairman</div>
        <span class="text-[10px] text-slate-500">Governing Board</span>
      </div>
    </div>

    <div class="mt-6 text-center text-[10px] text-slate-400 border-t border-slate-200 pt-2">
      Carmel Linx Institutional Management Information System &copy; {{ date('Y') }} | Executive Board Governance Report
    </div>
  </div>

  <script>
    if (new URLSearchParams(window.location.search).get('print') === 'true') {
      window.addEventListener('load', () => window.print());
    }
  </script>
</body>
</html>
