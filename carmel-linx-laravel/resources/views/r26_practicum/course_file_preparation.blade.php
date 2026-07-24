<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - R2026 Practicum Course File Preparation</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons & Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    body {
      font-family: 'Outfit', sans-serif;
      background-color: #0b0f19;
      color: #f1f5f9;
    }
    .bg-panel {
      background-color: rgba(15, 23, 42, 0.4);
      border-color: rgba(30, 41, 59, 0.8);
    }
    /* Strict Minimum Font Size Policy Compliance */
    input, select, textarea, button, table, td, th, label, p, span, div {
      font-size: 0.9375rem !important; /* 15px minimum for high readability */
    }
  </style>
</head>
<body class="min-h-screen p-6">

  <div class="w-full max-w-7xl mx-auto space-y-6">
    
    <!-- HEADER -->
    <div class="flex flex-wrap justify-between items-center bg-panel border border-slate-800/80 rounded-2xl px-6 py-4 gap-4 shadow-md">
      <div class="flex items-center gap-3">
        <div class="p-3 rounded-xl bg-purple-500/20 text-purple-400 font-bold border border-purple-500/30">
            PRACTICUM
        </div>
        <div>
          <div class="text-lg font-bold text-slate-100 flex items-center gap-2">
            <span>Carmel Linx</span>
            <span class="text-sm px-2.5 py-1 bg-purple-500/10 text-purple-400 border border-purple-500/20 rounded-md font-bold">R2026 PRACTICUM COURSE FILE</span>
          </div>
          <p class="text-sm text-slate-400 font-semibold uppercase tracking-wider">NBA Audit Preparation & Checklist Console</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl font-bold transition-all cursor-pointer flex items-center gap-2 shadow-sm">
          <span class="material-symbols-rounded">arrow_back</span>
          <span>Back to Practicum Classroom</span>
        </a>
      </div>
    </div>

    <!-- META INFORMATION -->
    <div class="bg-panel border border-slate-800/80 rounded-2xl p-6 shadow-md flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div>
        <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent">
          {{ $batchSubject->subject_name }}
        </h1>
        <p class="text-slate-400 font-medium flex items-center gap-3 mt-1">
          <span class="font-bold text-slate-200">{{ $batchSubject->subject_code }}</span>
          <span>•</span>
          <span>Semester {{ $batchSubject->semester }}</span>
          <span>•</span>
          <span class="text-purple-400 font-semibold">Practicum Course</span>
        </p>
      </div>
      
      <div class="flex flex-wrap items-center gap-3">
        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-course-file" target="_blank" class="px-5 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-xl font-bold transition-all shadow-lg shadow-indigo-500/25 flex items-center gap-2">
          <span class="material-symbols-rounded">picture_as_pdf</span>
          <span>Generate & Download Full Course File PDF</span>
        </a>
      </div>
    </div>

    <!-- DOCUMENT CHECKLIST TABLE -->
    <div class="bg-panel border border-slate-800/80 rounded-2xl p-6 shadow-md space-y-4">
      <div class="border-b border-slate-800 pb-4">
        <h3 class="text-lg font-bold uppercase tracking-wider text-slate-200 flex items-center gap-2">
          <span class="material-symbols-rounded text-purple-400">playlist_add_check</span>
          Practicum NBA Catalog Document Index (25 Standard Documents)
        </h3>
        <p class="text-slate-400 mt-1">Update checklist status, upload external artifacts, and inspect generated sub-documents.</p>
      </div>

      <div class="border border-slate-800 rounded-xl overflow-hidden bg-slate-950/20">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-900/60 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-800">
              <th class="p-4 w-[8%] text-center">Doc No</th>
              <th class="p-4 w-[45%]">Document Catalog Description</th>
              <th class="p-4 w-[15%] text-center">Audit Status</th>
              <th class="p-4 w-[22%]">Faculty Remarks</th>
              <th class="p-4 w-[10%] text-center">Action / Preview</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800">
            @foreach($documents as $doc)
              <tr id="doc-row-{{ $doc->id }}" class="hover:bg-slate-900/30 transition-all">
                <td class="p-4 font-mono font-bold text-center text-slate-400">{{ sprintf('%02d', $doc->document_number) }}</td>
                <td class="p-4 font-semibold text-slate-200">{{ $doc->document_name }}</td>
                <td class="p-4 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <input type="checkbox" id="check-{{ $doc->id }}" {{ $doc->is_checked ? 'checked' : '' }} onchange="saveDocStatus({{ $doc->id }})" class="w-5 h-5 text-purple-600 bg-slate-900 border-slate-700 rounded">
                    <label for="check-{{ $doc->id }}" class="font-bold uppercase {{ $doc->is_checked ? 'text-emerald-400' : 'text-slate-400' }}" id="lbl-status-{{ $doc->id }}">
                      {{ $doc->is_checked ? 'Verified' : 'Pending' }}
                    </label>
                  </div>
                </td>
                <td class="p-4">
                  <input type="text" id="remarks-{{ $doc->id }}" value="{{ $doc->remarks }}" onblur="saveDocStatus({{ $doc->id }})" placeholder="No remarks added" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-slate-200 outline-none focus:border-purple-500">
                </td>
                <td class="p-4 text-center">
                  <a href="/r26/classroom/practicum/{{ $batchSubject->id }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-semibold inline-flex items-center gap-1">
                    <span class="material-symbols-rounded text-sm">visibility</span>
                    <span>View</span>
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script>
    function saveDocStatus(docId) {
        const isChecked = document.getElementById('check-' + docId).checked;
        const remarks = document.getElementById('remarks-' + docId).value;
        const lbl = document.getElementById('lbl-status-' + docId);

        lbl.innerText = isChecked ? 'Verified' : 'Pending';
        lbl.className = 'font-bold uppercase ' + (isChecked ? 'text-emerald-400' : 'text-slate-400');

        fetch('/api/r26/classroom/practicum/course-file/{{ $batchSubject->id }}/save-doc', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                doc_id: docId,
                is_checked: isChecked,
                remarks: remarks
            })
        });
    }
  </script>
</body>
</html>
