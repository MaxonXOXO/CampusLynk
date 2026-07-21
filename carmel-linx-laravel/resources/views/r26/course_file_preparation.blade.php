<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - R2026 Course File Preparation</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons & Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
  
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
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: rgba(15, 23, 42, 0.1);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: rgba(148, 163, 184, 0.3);
      border-radius: 9999px;
    }
  </style>
</head>
<body class="min-h-screen p-4 custom-scrollbar">

  <div class="w-full max-w-7xl mx-auto space-y-4">
    
    <!-- HEADER -->
    <div class="flex flex-wrap justify-between items-center bg-panel border border-slate-800/80 rounded-xl px-6 py-4 gap-3 shadow-md">
      <div class="flex items-center gap-3">
        <img src="/logo.jpg" class="w-10 h-10 rounded-xl object-cover shadow-md">
        <div>
          <div class="text-base font-bold text-slate-100 flex items-center gap-2">
            <span>Carmel Linx</span>
            <span class="text-sm px-2.5 py-1 bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 rounded font-bold">R2026 COURSE FILE</span>
          </div>
          <p class="text-sm text-slate-400 font-bold uppercase tracking-wider">Preparation & Checklist Console</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <a href="/r26/classroom/theory/{{ $batchSubject->id }}" onclick="window.close(); return false;" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700/60 rounded-lg font-bold text-sm transition-all cursor-pointer flex items-center gap-1.5 shadow-sm">
          <span class="material-symbols-rounded text-sm">arrow_back</span>
          Close & Back to Classroom
        </a>
      </div>
    </div>

    <!-- META INFORMATION -->
    <div class="bg-panel border border-slate-800/80 rounded-xl p-5 shadow-md flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div>
        <h1 class="text-xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
          {{ $batchSubject->subject_name }}
        </h1>
        <p class="text-sm text-slate-400 font-medium flex items-center gap-2 mt-1">
          <span class="material-symbols-rounded text-sm">auto_stories</span>
          <span class="font-bold text-slate-200 text-sm">{{ $batchSubject->subject_code }}</span>
          <span>•</span>
          <span class="text-sm">Semester {{ $batchSubject->semester }}</span>
        </p>
      </div>
      
      <div class="flex flex-wrap items-center gap-3">
        <div class="px-4 py-2 bg-slate-950/40 border border-slate-800/80 rounded-lg text-sm font-bold text-slate-350">
          Status: <span id="file-status-badge" class="font-extrabold uppercase text-sm {{ $courseFile->status === 'Complete' ? 'text-emerald-450' : 'text-amber-500' }}">{{ $courseFile->status }}</span>
        </div>
        <a href="/r26/classroom/course-file/{{ $batchSubject->id }}/print-pdf" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-bold transition-all shadow-md flex items-center gap-1.5">
          <span class="material-symbols-rounded text-sm">picture_as_pdf</span>
          Generate & Download Course File PDF
        </a>
      </div>
    </div>

    <!-- DOCUMENT CHECKLIST -->
    <div class="bg-panel border border-slate-800/80 rounded-xl p-6 shadow-md space-y-4">
      <div class="border-b border-slate-800 pb-3">
        <h3 class="text-base font-bold uppercase tracking-wider text-slate-350 flex items-center gap-2">
          <span class="material-symbols-rounded text-indigo-400">playlist_add_check</span>
          Course File Document Index
        </h3>
        <p class="text-sm text-slate-400 mt-1">Update checklist status and add faculty audit remarks for each standard catalog document.</p>
      </div>

      <div class="border border-slate-800 rounded-xl overflow-hidden bg-slate-950/15">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-900/40 text-sm font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800">
              <th class="p-3.5 w-[8%] text-center">Doc No.</th>
              <th class="p-3.5 w-[45%]">Document Description</th>
              <th class="p-3.5 w-[15%] text-center">Status</th>
              <th class="p-3.5 w-[22%]">Remarks / Notes</th>
              <th class="p-3.5 w-[10%] text-center">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-850 text-sm">
            @foreach($documents as $doc)
              <tr id="doc-row-{{ $doc->id }}" class="hover:bg-slate-900/20 transition-all">
                <td class="p-3.5 font-mono font-bold text-center text-slate-400 text-sm">{{ sprintf('%02d', $doc->document_number) }}</td>
                <td class="p-3.5 font-semibold text-slate-200 text-sm">{{ $doc->document_name }}</td>
                <td class="p-3.5 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <input type="checkbox" id="check-{{ $doc->id }}" {{ $doc->is_checked ? 'checked' : '' }} class="w-5 h-5 text-indigo-650 bg-slate-900 border-slate-800 rounded focus:ring-indigo-500">
                    <label for="check-{{ $doc->id }}" class="text-sm font-bold uppercase {{ $doc->is_checked ? 'text-emerald-450' : 'text-slate-400' }}" id="lbl-status-{{ $doc->id }}">
                      {{ $doc->is_checked ? 'Verified' : 'Pending' }}
                    </label>
                  </div>
                </td>
                <td class="p-3.5">
                  <input type="text" id="remarks-{{ $doc->id }}" value="{{ $doc->remarks }}" placeholder="No remarks added" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1.5 text-sm text-slate-200 outline-none focus:border-indigo-500">
                </td>
                <td class="p-3.5 text-center">
                    @php
                      $previewUrl = null;
                      $num = $doc->document_number;
                      $manualUpload = in_array($num, [1, 2, 13, 17, 18, 23, 24, 25]);
                      
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
                  <div class="grid grid-cols-2 gap-2 w-48 mx-auto">
                    @if($manualUpload)
                      @if($filePath)
                        <a href="/{{ $filePath }}" target="_blank" class="px-2.5 py-1.5 bg-sky-650 hover:bg-sky-700 text-white rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-1 no-underline w-full">
                          <span class="material-symbols-rounded text-sm">download</span>
                          File
                        </a>
                      @else
                        <div>
                          <input type="file" id="file-input-{{ $doc->id }}" class="hidden" onchange="uploadAttachment({{ $doc->id }})">
                          <button type="button" onclick="document.getElementById('file-input-{{ $doc->id }}').click()" class="px-2.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-bold transition-all cursor-pointer flex items-center justify-center gap-1 w-full">
                            <span class="material-symbols-rounded text-sm">upload</span>
                            Upload
                          </button>
                        </div>
                      @endif
                    @elseif($previewUrl)
                      <a href="{{ $previewUrl }}" target="_blank" class="px-2.5 py-1.5 bg-indigo-650 hover:bg-indigo-700 text-white rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-1 no-underline w-full">
                        <span class="material-symbols-rounded text-sm">visibility</span>
                        Preview
                      </a>
                    @else
                      <div></div>
                    @endif
                    <button type="button" onclick="saveDocumentStatus({{ $doc->id }})" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-350 border border-slate-750 hover:text-white rounded-lg text-sm font-bold transition-all cursor-pointer flex items-center justify-center gap-1 w-full">
                      <span class="material-symbols-rounded text-sm">save</span>
                      Save
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
            statusLbl.className = 'text-sm font-bold uppercase text-emerald-450';
          } else {
            statusLbl.innerText = 'Pending';
            statusLbl.className = 'text-sm font-bold uppercase text-slate-400';
          }

          // Update general file status badge
          const statusBadge = document.getElementById('file-status-badge');
          statusBadge.innerText = data.file_status;
          if (data.file_status === 'Complete') {
            statusBadge.className = 'font-extrabold uppercase text-sm text-emerald-450';
          } else {
            statusBadge.className = 'font-extrabold uppercase text-sm text-amber-500';
          }

          // Show minor auto-saved toast or notification
          const toast = document.createElement('div');
          toast.className = 'fixed bottom-4 right-4 bg-emerald-600/90 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-lg transition-opacity duration-300';
          toast.innerText = '✓ Saved successfully!';
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
          statusLbl.className = 'text-sm font-bold uppercase text-emerald-450';

          // Replace upload button with download button
          const container = fileInput.parentElement.parentElement;
          container.firstElementChild.outerHTML = `
            <a href="/${data.file_path}" target="_blank" class="px-2.5 py-1.5 bg-sky-650 hover:bg-sky-700 text-white rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-1 no-underline w-full">
              <span class="material-symbols-rounded text-sm">download</span>
              File
            </a>
          `;

          // Show saved toast
          const toast = document.createElement('div');
          toast.className = 'fixed bottom-4 right-4 bg-emerald-600/90 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-lg transition-opacity duration-300';
          toast.innerText = '✓ File uploaded and verified!';
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
