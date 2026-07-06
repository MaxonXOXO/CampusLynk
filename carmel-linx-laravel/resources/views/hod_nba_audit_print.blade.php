<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NBA Criteria Compliance Sheet - {{ $academicYear }}</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;850&display=swap" rel="stylesheet">
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
      .page-break {
        page-break-before: always;
      }
    }
  </style>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8">

  <!-- Print Actions Bar -->
  <div class="max-w-3xl mx-auto mb-6 p-4 bg-white border border-slate-200 rounded-2xl shadow-sm flex items-center justify-between no-print">
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
        <span class="material-symbols-rounded text-sm">print</span> Print Sheet
      </button>
      <button onclick="window.close()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition flex items-center gap-2 cursor-pointer text-sm border border-slate-200">
        <span class="material-symbols-rounded text-sm">close</span> Close Preview
      </button>
    </div>
  </div>

  <!-- A4 Printable Sheet Container -->
  <div class="max-w-3xl mx-auto bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm print:border-0 print:p-0 print:shadow-none">
    
    <!-- Heading Banner -->
    <div class="border-b border-slate-200 pb-4 mb-5 flex justify-between items-start">
      <div>
        <h1 class="text-slate-900 font-extrabold tracking-tight text-lg">
          Carmel Polytechnic College
        </h1>
        <p class="text-slate-600 font-bold text-sm uppercase tracking-wider mt-0.5">
          NBA Criteria Audit Compliance Checklist
        </p>
        <p class="text-slate-500 text-xs mt-0.5 font-bold">
          Academic Year: <span class="text-slate-800">{{ $academicYear }}</span> | Branch: <span class="text-slate-800">{{ $department }}</span>
        </p>
      </div>
      <div class="text-right">
        <span class="inline-block px-3 py-1 bg-slate-100 border border-slate-200 rounded-lg text-slate-700 font-mono text-xs">
          Date Compiled: {{ $currentDate }}
        </span>
      </div>
    </div>

    <!-- Section: Checklist Table -->
    <div class="overflow-hidden border border-slate-200 rounded-xl">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200">
            <th class="p-3 font-bold text-slate-700 text-xs w-28">NBA Criteria</th>
            <th class="p-3 font-bold text-slate-700 text-xs">Accreditation Document Name</th>
            <th class="p-3 font-bold text-slate-700 text-xs text-center w-36">Status</th>
            <th class="p-3 font-bold text-slate-700 text-xs text-center w-28">Sign / Seal</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-150">
          @for($i = 1; $i <= 9; $i++)
            @if(isset($documents[$i]))
              @foreach($documents[$i] as $doc)
                <tr>
                  <td class="p-3 font-bold text-slate-800">Criteria {{ $i }}</td>
                  <td class="p-3 text-slate-600 font-medium">{{ $doc->document_name }}</td>
                  <td class="p-3 text-center">
                    @if($doc->status === 'Verified')
                      <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold bg-green-150 text-green-700 border border-green-300 rounded-md">
                        VERIFIED
                      </span>
                    @elseif($doc->status === 'Uploaded')
                      <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold bg-rose-150 text-rose-700 border border-rose-300 rounded-md">
                        UPLOADED (PENDING)
                      </span>
                    @else
                      <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-400 border border-slate-250 rounded-md">
                        MISSING
                      </span>
                    @endif
                  </td>
                  <td class="p-3 text-slate-400 italic text-center text-xs">___________</td>
                </tr>
              @endforeach
            @endif
          @endfor
        </tbody>
      </table>
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
        <p class="text-[10px] text-slate-500 mt-0.5">{{ $department }} Department</p>
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
