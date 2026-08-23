<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CampusLynk - R2026 Practicum Course File Preparation &middot; {{ $batchSubject->subject_code }}</title>
  <!-- Canonical Vite Asset Pipeline -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <!-- Google Icons & Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background-color: #FAFAFB;
      color: #0f172a;
    }
    h1, h2, h3, h4, .font-heading {
      font-family: 'Outfit', 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="min-h-screen p-4 sm:p-6 bg-[#FAFAFB]">

  <div class="w-full max-w-7xl mx-auto space-y-5">
    
    <!-- 1. HEADER & NAVIGATION -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-slate-200/80 rounded-2xl px-6 py-4 gap-4 shadow-xs">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold border border-purple-200/60 shadow-2xs">
          <span class="material-symbols-rounded text-xl">folder_special</span>
        </div>
        <div>
          <div class="text-base font-bold text-slate-900 flex items-center gap-2">
            <span>CampusLynk</span>
            <span class="text-xs px-2.5 py-0.5 bg-purple-50 text-purple-700 border border-purple-200/80 rounded-md font-bold font-mono">R2026 PRACTICUM COURSE FILE</span>
          </div>
          <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">NBA Audit Preparation &amp; Checklist Console</p>
        </div>
      </div>

      <div class="flex items-center gap-2.5">
        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-xl font-bold text-xs transition-all cursor-pointer flex items-center gap-1.5 shadow-2xs no-underline">
          <span class="material-symbols-rounded text-base">arrow_back</span>
          <span>Back to Practicum Classroom</span>
        </a>
      </div>
    </div>

    <!-- 2. COURSE HERO & OVERVIEW CARD -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div class="space-y-1">
        <div class="flex items-center gap-2">
          <span class="px-2.5 py-0.5 rounded-md font-mono font-bold text-xs bg-slate-100 text-slate-700 border border-slate-200">{{ $batchSubject->subject_code }}</span>
          <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-purple-50 text-purple-700 border border-purple-200">Semester {{ $batchSubject->semester }}</span>
          <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-indigo-50 text-indigo-700 border border-indigo-200">Integrated Practicum</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight font-heading mt-1">
          {{ $batchSubject->subject_name }}
        </h1>
        <p class="text-xs text-slate-500 font-medium">Standard 25-Document National Board of Accreditation (NBA) Portfolio Catalog</p>
      </div>
      
      <div class="flex flex-wrap items-center gap-3">
        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-course-file" target="_blank" class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 no-underline">
          <span class="material-symbols-rounded text-base">picture_as_pdf</span>
          <span>Generate &amp; Download Full Course File PDF</span>
        </a>
      </div>
    </div>

    <!-- 3. DOCUMENT CHECKLIST TABLE -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
      <div class="border-b border-slate-100 pb-4">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
          <span class="material-symbols-rounded text-purple-600 text-lg">playlist_add_check</span>
          <span>Practicum NBA Catalog Document Index (25 Standard Documents)</span>
        </h3>
        <p class="text-xs text-slate-500 mt-1">Update checklist status, add audit remarks, and inspect generated sub-documents.</p>
      </div>

      <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
              <th class="p-3.5 w-[8%] text-center">Doc No</th>
              <th class="p-3.5 w-[45%]">Document Catalog Description</th>
              <th class="p-3.5 w-[15%] text-center">Audit Status</th>
              <th class="p-3.5 w-[22%]">Faculty Remarks</th>
              <th class="p-3.5 w-[10%] text-center">Action / Preview</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @foreach($documents as $doc)
              <tr id="doc-row-{{ $doc->id }}" class="hover:bg-slate-50/80 transition-colors">
                <td class="p-3.5 font-mono font-bold text-center text-slate-600">{{ sprintf('%02d', $doc->document_number) }}</td>
                <td class="p-3.5 font-semibold text-slate-900">{{ $doc->document_name }}</td>
                <td class="p-3.5 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <input type="checkbox" id="check-{{ $doc->id }}" {{ $doc->is_checked ? 'checked' : '' }} onchange="saveDocStatus({{ $doc->id }})" class="w-4.5 h-4.5 text-purple-600 bg-white border-slate-300 rounded focus:ring-0 cursor-pointer">
                    <label for="check-{{ $doc->id }}" class="font-bold uppercase cursor-pointer {{ $doc->is_checked ? 'text-emerald-700' : 'text-slate-400' }}" id="lbl-status-{{ $doc->id }}">
                      {{ $doc->is_checked ? 'Verified' : 'Pending' }}
                    </label>
                  </div>
                </td>
                <td class="p-3.5">
                  <input type="text" id="remarks-{{ $doc->id }}" value="{{ $doc->remarks }}" onblur="saveDocStatus({{ $doc->id }})" placeholder="Add audit remarks..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 outline-none focus:bg-white focus:border-purple-500 transition-colors shadow-2xs">
                </td>
                <td class="p-3.5 text-center">
                  <a href="/r26/classroom/practicum/{{ $batchSubject->id }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-lg font-bold inline-flex items-center gap-1 no-underline text-xs transition-all shadow-2xs">
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
        lbl.className = 'font-bold uppercase cursor-pointer ' + (isChecked ? 'text-emerald-700' : 'text-slate-400');

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
        }).then(res => res.json()).then(data => {
            // Show toast feedback
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-xl transition-opacity duration-300 z-50 flex items-center gap-1.5';
            toast.innerHTML = '<span class="text-emerald-400 font-bold">✓</span> Status saved!';
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 1200);
        });
    }
  </script>
</body>
</html>
