{{-- SBTE Subject Log PDF & Text Import Modal --}}
<div id="sbte-subject-log-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-4xl rounded-2xl border border-white/10 bg-slate-900/95 p-6 shadow-2xl backdrop-blur-xl">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/20 text-indigo-400">
                        <span class="material-symbols-rounded">picture_as_pdf</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Import Official SBTE Subject Log</h3>
                        <p class="text-xs text-slate-400">Parse official SBTE PDF logs or text to bulk populate lesson plans & attendance records</p>
                    </div>
                </div>
                <button type="button" onclick="closeSbteModal()" class="rounded-lg p-1 text-slate-400 hover:bg-white/10 hover:text-white">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="mt-4 space-y-4">
                {{-- Input Selector Tabs --}}
                <div class="flex gap-2 border-b border-white/10 pb-2">
                    <button type="button" id="sbte-tab-pdf" onclick="switchSbteTab('pdf')" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 transition">
                        PDF Upload
                    </button>
                    <button type="button" id="sbte-tab-text" onclick="switchSbteTab('text')" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-400 hover:text-white transition">
                        Raw Text Paste
                    </button>
                </div>

                {{-- File Input Section --}}
                <div id="sbte-section-pdf" class="space-y-2">
                    <label class="block text-xs font-medium text-slate-300">Select Official SBTE Log PDF</label>
                    <input type="file" id="sbte-pdf-input" accept=".pdf,.txt" class="block w-full text-xs text-slate-400 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-600/30 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-indigo-300 hover:file:bg-indigo-600/50">
                    <p class="text-[11px] text-slate-500">Supports Kerala SBTE Polytechnic subject log sheets with date, hour, and topic columns.</p>
                </div>

                {{-- Raw Text Section --}}
                <div id="sbte-section-text" class="hidden space-y-2">
                    <label class="block text-xs font-medium text-slate-300">Paste Log Text Content</label>
                    <textarea id="sbte-text-input" rows="5" placeholder="Programme: Computer Engineering&#10;Course: Operating Systems (4041) Semester: 4&#10;Faculty: John Doe&#10;1. 15-01-2026 1, 2 Introduction to OS..." class="w-full rounded-xl border border-white/10 bg-slate-950/60 p-3 text-xs text-slate-200 placeholder-slate-600 focus:border-indigo-500 focus:outline-none"></textarea>
                </div>

                {{-- Parse Trigger & Options --}}
                <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                    <label class="flex items-center gap-2 text-xs text-slate-300 cursor-pointer">
                        <input type="checkbox" id="sbte-overwrite" class="rounded border-white/10 bg-slate-800 text-indigo-600 focus:ring-0">
                        <span>Overwrite existing attendance sessions for matching dates/periods</span>
                    </label>
                    <button type="button" id="sbte-btn-parse" onclick="parseSbteLog()" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 transition">
                        <span class="material-symbols-rounded text-sm">manage_search</span>
                        <span>Parse & Preview</span>
                    </button>
                </div>

                {{-- Status / Alerts --}}
                <div id="sbte-alert" class="hidden rounded-xl p-3 text-xs"></div>

                {{-- Preview Section --}}
                <div id="sbte-preview-container" class="hidden space-y-3 pt-2 border-t border-white/10">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 rounded-xl bg-slate-950/40 p-3 text-xs">
                        <div>
                            <span class="text-slate-500 block text-[10px] uppercase tracking-wider">Course</span>
                            <span id="sbte-meta-course" class="font-semibold text-white truncate block">-</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[10px] uppercase tracking-wider">Semester</span>
                            <span id="sbte-meta-semester" class="font-semibold text-white block">-</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[10px] uppercase tracking-wider">Faculty</span>
                            <span id="sbte-meta-faculty" class="font-semibold text-white truncate block">-</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[10px] uppercase tracking-wider">Total Sessions</span>
                            <span id="sbte-meta-sessions" class="font-semibold text-indigo-400 block">0</span>
                        </div>
                    </div>

                    <div class="max-h-60 overflow-y-auto rounded-xl border border-white/10 bg-slate-950/60">
                        <table class="w-full text-left text-xs">
                            <thead class="sticky top-0 bg-slate-800/90 text-[11px] text-slate-400 backdrop-blur">
                                <tr>
                                    <th class="p-2.5">#</th>
                                    <th class="p-2.5">Date</th>
                                    <th class="p-2.5">Hours</th>
                                    <th class="p-2.5">Topics Covered</th>
                                </tr>
                            </thead>
                            <tbody id="sbte-preview-tbody" class="divide-y divide-white/5 text-slate-300">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="mt-6 flex items-center justify-end gap-3 border-t border-white/10 pt-4">
                <button type="button" onclick="closeSbteModal()" class="rounded-xl px-4 py-2 text-xs font-semibold text-slate-400 hover:bg-white/10 hover:text-white transition">
                    Cancel
                </button>
                <button type="button" id="sbte-btn-import" onclick="executeSbteImport()" disabled class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2 text-xs font-semibold text-white opacity-50 cursor-not-allowed transition hover:bg-emerald-500">
                    <span class="material-symbols-rounded text-sm">cloud_upload</span>
                    <span>Confirm & Import to Classroom</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let parsedSbteSessions = [];

function switchSbteTab(tab) {
    const pdfSec = document.getElementById('sbte-section-pdf');
    const txtSec = document.getElementById('sbte-section-text');
    const pdfTab = document.getElementById('sbte-tab-pdf');
    const txtTab = document.getElementById('sbte-tab-text');

    if (tab === 'pdf') {
        pdfSec.classList.remove('hidden');
        txtSec.classList.add('hidden');
        pdfTab.className = 'rounded-lg px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 transition';
        txtTab.className = 'rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-400 hover:text-white transition';
    } else {
        pdfSec.classList.add('hidden');
        txtSec.classList.remove('hidden');
        txtTab.className = 'rounded-lg px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 transition';
        pdfTab.className = 'rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-400 hover:text-white transition';
    }
}

function openSbteModal() {
    document.getElementById('sbte-subject-log-modal').classList.remove('hidden');
}

function closeSbteModal() {
    document.getElementById('sbte-subject-log-modal').classList.add('hidden');
}

function parseSbteLog() {
    const fileInput = document.getElementById('sbte-pdf-input');
    const textInput = document.getElementById('sbte-text-input');
    const alertBox = document.getElementById('sbte-alert');
    const parseBtn = document.getElementById('sbte-btn-parse');

    const formData = new FormData();
    if (fileInput.files.length > 0) {
        formData.append('pdf_file', fileInput.files[0]);
    } else if (textInput.value.trim().length > 0) {
        formData.append('raw_text', textInput.value);
    } else {
        alertBox.className = 'rounded-xl p-3 text-xs bg-amber-500/20 text-amber-300 border border-amber-500/30';
        alertBox.innerText = 'Please select a PDF file or enter raw text.';
        alertBox.classList.remove('hidden');
        return;
    }

    parseBtn.disabled = true;
    parseBtn.innerHTML = '<span class="material-symbols-rounded animate-spin text-sm">progress_activity</span> Parsing...';

    fetch('/api/sbte-log/parse', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        parseBtn.disabled = false;
        parseBtn.innerHTML = '<span class="material-symbols-rounded text-sm">manage_search</span> Parse & Preview';

        if (!data.success) {
            alertBox.className = 'rounded-xl p-3 text-xs bg-rose-500/20 text-rose-300 border border-rose-500/30';
            alertBox.innerText = data.message || 'Failed to parse log.';
            alertBox.classList.remove('hidden');
            return;
        }

        const res = data.data;
        parsedSbteSessions = res.sessions || [];

        document.getElementById('sbte-meta-course').innerText = res.course_title ? `${res.course_title} (${res.course_code})` : 'N/A';
        document.getElementById('sbte-meta-semester').innerText = res.semester || 'N/A';
        document.getElementById('sbte-meta-faculty').innerText = res.faculty || 'N/A';
        document.getElementById('sbte-meta-sessions').innerText = `${res.total_sessions} (${res.total_hours} hrs)`;

        const tbody = document.getElementById('sbte-preview-tbody');
        tbody.innerHTML = '';

        parsedSbteSessions.forEach(s => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="p-2.5 text-slate-500">${s.sl_no}</td>
                <td class="p-2.5 font-medium text-white">${s.display_date || s.date}</td>
                <td class="p-2.5"><span class="rounded bg-indigo-500/20 px-1.5 py-0.5 text-[10px] text-indigo-300 font-mono">${s.hours.join(', ')}</span></td>
                <td class="p-2.5 text-slate-300">${s.contents}</td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('sbte-preview-container').classList.remove('hidden');
        alertBox.className = 'rounded-xl p-3 text-xs bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
        alertBox.innerText = `Successfully parsed ${res.total_sessions} session(s) totaling ${res.total_hours} hour(s).`;
        alertBox.classList.remove('hidden');

        const importBtn = document.getElementById('sbte-btn-import');
        importBtn.disabled = false;
        importBtn.className = 'inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2 text-xs font-semibold text-white transition hover:bg-emerald-500 shadow-lg shadow-emerald-600/30 cursor-pointer';
    })
    .catch(err => {
        parseBtn.disabled = false;
        parseBtn.innerHTML = '<span class="material-symbols-rounded text-sm">manage_search</span> Parse & Preview';
        alertBox.className = 'rounded-xl p-3 text-xs bg-rose-500/20 text-rose-300 border border-rose-500/30';
        alertBox.innerText = 'Network error while parsing SBTE log: ' + err.message;
        alertBox.classList.remove('hidden');
    });
}

function executeSbteImport() {
    if (!parsedSbteSessions.length) return;

    const subjectId = window.activeSubjectId || (typeof activeSubjectId !== 'undefined' ? activeSubjectId : null);
    if (!subjectId) {
        alert('Subject ID is missing from active context.');
        return;
    }

    const overwrite = document.getElementById('sbte-overwrite').checked;
    const importBtn = document.getElementById('sbte-btn-import');
    const alertBox = document.getElementById('sbte-alert');

    importBtn.disabled = true;
    importBtn.innerHTML = '<span class="material-symbols-rounded animate-spin text-sm">progress_activity</span> Importing...';

    fetch(`/classroom/${subjectId}/sbte-log/import`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            sessions: parsedSbteSessions,
            overwrite: overwrite
        })
    })
    .then(res => res.json())
    .then(data => {
        importBtn.disabled = false;
        importBtn.innerHTML = '<span class="material-symbols-rounded text-sm">cloud_upload</span> Confirm & Import to Classroom';

        if (data.success) {
            alertBox.className = 'rounded-xl p-3 text-xs bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
            alertBox.innerText = data.message;
            alertBox.classList.remove('hidden');
            setTimeout(() => {
                location.reload();
            }, 1200);
        } else {
            alertBox.className = 'rounded-xl p-3 text-xs bg-rose-500/20 text-rose-300 border border-rose-500/30';
            alertBox.innerText = data.message || 'Import failed.';
            alertBox.classList.remove('hidden');
        }
    })
    .catch(err => {
        importBtn.disabled = false;
        importBtn.innerHTML = '<span class="material-symbols-rounded text-sm">cloud_upload</span> Confirm & Import to Classroom';
        alertBox.className = 'rounded-xl p-3 text-xs bg-rose-500/20 text-rose-300 border border-rose-500/30';
        alertBox.innerText = 'Network error during import: ' + err.message;
        alertBox.classList.remove('hidden');
    });
}
</script>
