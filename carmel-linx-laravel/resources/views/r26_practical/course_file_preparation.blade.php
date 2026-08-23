<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CampusLynk - Course File Checklog &middot; {{ $batchSubject->subject_code }}</title>
    
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

    <div class="max-w-6xl mx-auto space-y-5">

        <!-- 1. HEADER -->
        <header class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-xs">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 text-xs font-bold font-mono tracking-wider rounded-md bg-violet-50 text-violet-700 border border-violet-200">
                        COURSE PORTFOLIO COMPILATION (R2026)
                    </span>
                    <span class="px-2.5 py-0.5 text-xs font-bold rounded-md bg-slate-100 text-slate-700 border border-slate-200">
                        Semester {{ $batchSubject->semester }}
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight font-heading mt-1">
                    {{ $batchSubject->subject_name }} <span class="text-slate-400 font-normal">({{ $batchSubject->subject_code }})</span>
                </h1>
                <p class="text-xs text-slate-500 font-medium">Compile and index the 25 required files for academic auditing and NBA portfolio alignment.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="window.close()" class="px-4 py-2 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200 transition-all flex items-center gap-1.5 shadow-2xs cursor-pointer">
                    <span class="material-symbols-rounded text-base">close</span>
                    <span>Close Window</span>
                </button>
            </div>
        </header>

        <!-- 2. DOCUMENT CHECKLIST TABLE -->
        <main class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-rounded text-violet-600 text-lg">playlist_add_check</span>
                    <span>Practical Course Portfolio Catalog (25 Standard Documents)</span>
                </h3>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                <table class="w-full text-left text-xs text-slate-700 border-collapse">
                    <thead>
                        <tr class="bg-slate-50 font-bold border-b border-slate-200 text-slate-600 uppercase tracking-wider">
                            <th class="p-3.5 w-16 text-center">Index</th>
                            <th class="p-3.5 w-16 text-center">Status</th>
                            <th class="p-3.5">Audit Document Title</th>
                            <th class="p-3.5 w-48">Attachment Link</th>
                            <th class="p-3.5 w-72">Remarks / Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($documents as $doc)
                        <tr class="hover:bg-slate-50/80 transition-colors" data-doc-id="{{ $doc->id }}">
                            <td class="p-3.5 font-mono text-center font-bold text-slate-500">{{ sprintf('%02d', $doc->document_number) }}</td>
                            <td class="p-3.5 text-center">
                                <input type="checkbox" {{ $doc->is_checked ? 'checked' : '' }} onchange="updateDocCheck('{{ $doc->id }}', this.checked)" class="w-4.5 h-4.5 rounded border-slate-300 bg-white text-violet-600 focus:ring-0 cursor-pointer">
                            </td>
                            <td class="p-3.5 font-bold text-slate-900">{{ $doc->document_name }}</td>
                            <td class="p-3">
                                <div class="flex items-center gap-2">
                                    @if($doc->data_payload)
                                    <a href="/{{ $doc->data_payload }}" target="_blank" id="link-{{ $doc->id }}" class="text-violet-700 hover:text-violet-800 font-bold flex items-center gap-1 bg-violet-50 hover:bg-violet-100 px-2.5 py-1 rounded-md border border-violet-200 no-underline shadow-2xs">
                                        <span class="material-symbols-rounded text-sm">download</span>
                                        <span>View File</span>
                                    </a>
                                    @else
                                    <span class="text-slate-400 italic text-xs" id="span-{{ $doc->id }}">No attachment</span>
                                    @endif
                                    
                                    <input type="file" id="file-input-{{ $doc->id }}" class="hidden" onchange="uploadDocFile('{{ $doc->id }}')">
                                    <button onclick="document.getElementById('file-input-{{ $doc->id }}').click()" class="text-slate-500 hover:text-slate-900 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer" title="Upload attachment">
                                        <span class="material-symbols-rounded text-base">upload_file</span>
                                    </button>
                                </div>
                            </td>
                            <td class="p-3">
                                <input type="text" value="{{ $doc->remarks }}" placeholder="Write compliance note..." onchange="updateDocRemarks('{{ $doc->id }}', this.value)" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:bg-white focus:border-violet-500 focus:outline-none w-full transition-colors shadow-2xs">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>

    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const subjectId = "{{ $batchSubject->id }}";

        async function updateDocCheck(docId, isChecked) {
            try {
                await fetch(`/api/r26/classroom/practical/course-file/${subjectId}/save-doc`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ doc_id: docId, is_checked: isChecked })
                });
            } catch(e) {
                console.error(e);
            }
        }

        async function updateDocRemarks(docId, val) {
            try {
                await fetch(`/api/r26/classroom/practical/course-file/${subjectId}/save-doc`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ doc_id: docId, remarks: val })
                });
            } catch(e) {
                console.error(e);
            }
        }

        async function uploadDocFile(docId) {
            const input = document.getElementById(`file-input-${docId}`);
            if (input.files.length === 0) return;

            const formData = new FormData();
            formData.append('doc_id', docId);
            formData.append('attachment', input.files[0]);

            try {
                const res = await fetch(`/api/r26/classroom/practical/course-file/${subjectId}/upload-doc`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await res.json();
                if (data.attachment_url) {
                    const row = document.querySelector(`tr[data-doc-id="${docId}"]`);
                    row.querySelector('input[type="checkbox"]').checked = true;

                    // update attachment cell
                    const cell = row.cells[3];
                    cell.innerHTML = `
                        <div class="flex items-center gap-2">
                            <a href="${data.attachment_url}" target="_blank" id="link-${docId}" class="text-violet-700 hover:text-violet-800 font-bold flex items-center gap-1 bg-violet-50 hover:bg-violet-100 px-2.5 py-1 rounded-md border border-violet-200 no-underline shadow-2xs">
                                <span class="material-symbols-rounded text-sm">download</span>
                                <span>View File</span>
                            </a>
                            <input type="file" id="file-input-${docId}" class="hidden" onchange="uploadDocFile('${docId}')">
                            <button onclick="document.getElementById('file-input-${docId}').click()" class="text-slate-500 hover:text-slate-900 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer" title="Upload attachment">
                                <span class="material-symbols-rounded text-base">upload_file</span>
                            </button>
                        </div>
                    `;
                }
            } catch(e) {
                alert("Upload failed.");
            }
        }
    </script>
</body>
</html>
