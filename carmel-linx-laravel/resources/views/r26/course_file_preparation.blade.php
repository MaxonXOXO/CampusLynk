<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CampusLynk - R2026 Course File Preparation &middot; {{ $batchSubject->subject_code }}</title>
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
        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold border border-blue-200/60 shadow-2xs">
          <span class="material-symbols-rounded text-xl">folder_special</span>
        </div>
        <div>
          <div class="text-base font-bold text-slate-900 flex items-center gap-2">
            <span>CampusLynk</span>
            <span class="text-xs px-2.5 py-0.5 bg-blue-50 text-blue-700 border border-blue-200/80 rounded-md font-bold font-mono">R2026 THEORY COURSE FILE</span>
          </div>
          <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">NBA Audit Preparation &amp; Checklist Console</p>
        </div>
      </div>

      <div class="flex items-center gap-2.5">
        <a href="/r26/classroom/theory/{{ $batchSubject->id }}" onclick="window.close(); return false;" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-xl font-bold text-xs transition-all cursor-pointer flex items-center gap-1.5 shadow-2xs no-underline">
          <span class="material-symbols-rounded text-base">arrow_back</span>
          <span>Close &amp; Back to Classroom</span>
        </a>
      </div>
    </div>

    <!-- 2. COURSE HERO & OVERVIEW CARD -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div class="space-y-1">
        <div class="flex items-center gap-2">
          <span class="px-2.5 py-0.5 rounded-md font-mono font-bold text-xs bg-slate-100 text-slate-700 border border-slate-200">{{ $batchSubject->subject_code }}</span>
          <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-blue-50 text-blue-700 border border-blue-200">Semester {{ $batchSubject->semester }}</span>
          <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-indigo-50 text-indigo-700 border border-indigo-200">Theory Course</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight font-heading mt-1">
          {{ $batchSubject->subject_name }}
        </h1>
        <p class="text-xs text-slate-500 font-medium">Standard 25-Document National Board of Accreditation (NBA) Portfolio Catalog</p>
      </div>
      
      <div class="flex flex-wrap items-center gap-3">
        <div class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 flex items-center gap-1.5 shadow-2xs">
          <span>Status:</span>
          <span id="file-status-badge" class="font-black uppercase {{ $courseFile->status === 'Complete' ? 'text-emerald-700' : 'text-amber-700' }}">{{ $courseFile->status }}</span>
        </div>
        <a href="/r26/classroom/course-file/{{ $batchSubject->id }}/print-pdf" target="_blank" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 no-underline">
          <span class="material-symbols-rounded text-base">picture_as_pdf</span>
          <span>Generate &amp; Download Course File PDF</span>
        </a>
      </div>
    </div>

    <!-- 3. DOCUMENT CHECKLIST CATALOG CARD -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
      <div class="border-b border-slate-100 pb-4">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-600 text-lg">playlist_add_check</span>
          <span>Course File Document Catalog Index</span>
        </h3>
        <p class="text-xs text-slate-500 mt-1">Verify catalog completion status, upload physical attachments, and inspect auto-generated sub-documents.</p>
      </div>

      <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
              <th class="p-3.5 w-[8%] text-center">Doc No.</th>
              <th class="p-3.5 w-[42%]">Document Description</th>
              <th class="p-3.5 w-[15%] text-center">Audit Status</th>
              <th class="p-3.5 w-[23%]">Remarks / Notes</th>
              <th class="p-3.5 w-[12%] text-center">Action / Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @foreach($documents as $doc)
              <tr id="doc-row-{{ $doc->id }}" class="hover:bg-slate-50/80 transition-colors">
                <td class="p-3.5 font-mono font-bold text-center text-slate-600">{{ sprintf('%02d', $doc->document_number) }}</td>
                <td class="p-3.5 font-semibold text-slate-900">{{ $doc->document_name }}</td>
                <td class="p-3.5 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <input type="checkbox" id="check-{{ $doc->id }}" {{ $doc->is_checked ? 'checked' : '' }} class="w-4.5 h-4.5 text-blue-600 bg-white border-slate-300 rounded focus:ring-0 cursor-pointer">
                    <label for="check-{{ $doc->id }}" class="font-bold uppercase cursor-pointer {{ $doc->is_checked ? 'text-emerald-700' : 'text-slate-400' }}" id="lbl-status-{{ $doc->id }}">
                      {{ $doc->is_checked ? 'Verified' : 'Pending' }}
                    </label>
                  </div>
                </td>
                <td class="p-3.5">
                  <input type="text" id="remarks-{{ $doc->id }}" value="{{ $doc->remarks }}" placeholder="Add audit remarks..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 outline-none focus:bg-white focus:border-blue-500 transition-colors shadow-2xs">
                </td>
                <td class="p-3.5 text-center">
                    @php
                      $previewUrl = null;
                      $num = $doc->document_number;
                      $manualUpload = in_array($num, [2, 13, 17, 18, 25]);
                      
                      $filePath = null;
                      if ($doc->data_payload) {
                          $payload = json_decode($doc->data_payload, true);
                          $filePath = $payload['file_path'] ?? null;
                      }

                      if ($num == 3 || $num == 4 || $num == 10 || $num == 14) {
                          $previewUrl = "/r26/classroom/theory/" . $batchSubject->id;
                      } elseif ($num == 7 && isset($calendarId) && $calendarId) {
                          $previewUrl = "/hod/academic-calendar/" . $calendarId . "/print";
                      } elseif ($num == 8) {
                          $previewUrl = "/r26/classroom/lesson-plan/print/" . $batchSubject->id;
                      } elseif ($num == 12) {
                          $previewUrl = "/hod/remedial-report/print?classroom_id=" . ($classroom->classroom_id ?? '');
                      } elseif ($num == 15) {
                          $previewUrl = "/r26/classroom/" . $batchSubject->id . "/internals/print-cie";
                      } elseif ($num == 16) {
                          $previewUrl = "/r26/classroom/" . $batchSubject->id . "/final-results/print";
                      } elseif ($num == 19 || $num == 20) {
                          $previewUrl = "/r26/classroom/" . $batchSubject->id . "/nba/attainment-report";
                      }
                    @endphp
                  <div class="grid grid-cols-2 gap-1.5 w-44 mx-auto">
                    @if($manualUpload)
                      @if($filePath)
                        <a href="/{{ $filePath }}" target="_blank" class="px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1 no-underline w-full shadow-2xs">
                          <span class="material-symbols-rounded text-sm">download</span>
                          <span>File</span>
                        </a>
                      @else
                        <div>
                          <input type="file" id="file-input-{{ $doc->id }}" class="hidden" onchange="uploadAttachment({{ $doc->id }})">
                          <button type="button" onclick="document.getElementById('file-input-{{ $doc->id }}').click()" class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center justify-center gap-1 w-full shadow-2xs">
                            <span class="material-symbols-rounded text-sm">upload</span>
                            <span>Upload</span>
                          </button>
                        </div>
                      @endif
                    @elseif($previewUrl)
                      <a href="{{ $previewUrl }}" target="_blank" class="px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1 no-underline w-full shadow-2xs">
                        <span class="material-symbols-rounded text-sm">visibility</span>
                        <span>Preview</span>
                      </a>
                    @else
                      <div></div>
                    @endif
                    <button type="button" onclick="saveDocumentStatus({{ $doc->id }})" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center justify-center gap-1 w-full shadow-2xs">
                      <span class="material-symbols-rounded text-sm">save</span>
                      <span>Save</span>
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script>
    function saveDocumentStatus(docId) {
      const isChecked = document.getElementById('check-' + docId).checked;
      const remarks = document.getElementById('remarks-' + docId).value;

      fetch(`/api/r26/classroom/course-file/{{ $batchSubject->id }}/save-doc`, {
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
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          // Update status label
          const statusLbl = document.getElementById('lbl-status-' + docId);
          if (isChecked) {
            statusLbl.innerText = 'Verified';
            statusLbl.className = 'font-bold uppercase cursor-pointer text-emerald-700';
          } else {
            statusLbl.innerText = 'Pending';
            statusLbl.className = 'font-bold uppercase cursor-pointer text-slate-400';
          }

          // Update general file status badge
          const statusBadge = document.getElementById('file-status-badge');
          statusBadge.innerText = data.file_status;
          if (data.file_status === 'Complete') {
            statusBadge.className = 'font-black uppercase text-emerald-700';
          } else {
            statusBadge.className = 'font-black uppercase text-amber-700';
          }

          // Show minor auto-saved toast
          const toast = document.createElement('div');
          toast.className = 'fixed bottom-4 right-4 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-xl transition-opacity duration-300 z-50 flex items-center gap-1.5';
          toast.innerHTML = '<span class="text-emerald-400 font-bold">✓</span> Document status saved!';
          document.body.appendChild(toast);
          setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
          }, 1500);
        } else {
          alert('Failed to save document status: ' + data.message);
        }
      })
      .catch(err => {
        console.error(err);
        alert('An error occurred while saving.');
      });
    }

    function uploadAttachment(docId) {
      const fileInput = document.getElementById('file-input-' + docId);
      if (!fileInput.files.length) return;
      
      const file = fileInput.files[0];
      const formData = new FormData();
      formData.append('file', file);
      formData.append('doc_id', docId);

      const uploadBtn = fileInput.nextElementSibling;
      const originalText = uploadBtn.innerHTML;
      uploadBtn.disabled = true;
      uploadBtn.innerHTML = `<span class="material-symbols-rounded text-sm animate-spin">sync</span>`;

      fetch(`/api/r26/classroom/course-file/{{ $batchSubject->id }}/upload-doc`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          // Check the verification checkbox
          document.getElementById('check-' + docId).checked = true;
          const statusLbl = document.getElementById('lbl-status-' + docId);
          statusLbl.innerText = 'Verified';
          statusLbl.className = 'font-bold uppercase cursor-pointer text-emerald-700';

          // Replace upload button with download button
          const container = fileInput.parentElement.parentElement;
          container.firstElementChild.outerHTML = `
            <a href="/${data.file_path}" target="_blank" class="px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1 no-underline w-full shadow-2xs">
              <span class="material-symbols-rounded text-sm">download</span>
              <span>File</span>
            </a>
          `;

          // Show saved toast
          const toast = document.createElement('div');
          toast.className = 'fixed bottom-4 right-4 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-xl transition-opacity duration-300 z-50 flex items-center gap-1.5';
          toast.innerHTML = '<span class="text-emerald-400 font-bold">✓</span> File uploaded and verified!';
          document.body.appendChild(toast);
          setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
          }, 1500);
        } else {
          alert('Upload failed: ' + data.message);
          uploadBtn.disabled = false;
          uploadBtn.innerHTML = originalText;
        }
      })
      .catch(err => {
        console.error(err);
        alert('An error occurred during upload.');
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = originalText;
      });
    }
  </script>
</body>
</html>
