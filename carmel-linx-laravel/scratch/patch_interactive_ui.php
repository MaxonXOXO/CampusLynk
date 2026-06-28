<?php

$path = 'resources/views/course_files_dashboard.blade.php';
$content = file_get_contents($path);

// 1. Add "Action" to the table header
$oldThead = "                                            <tr>
                                                <th class=\"px-4 py-3 w-16 text-center\">Doc No.</th>
                                                <th class=\"px-4 py-3\">Document Name</th>
                                                <th class=\"px-4 py-3 w-24 text-center\">Status</th>
                                                <th class=\"px-4 py-3 w-48\">Remarks</th>
                                            </tr>";
$newThead = "                                            <tr>
                                                <th class=\"px-4 py-3 w-16 text-center\">Doc No.</th>
                                                <th class=\"px-4 py-3\">Document Name</th>
                                                <th class=\"px-4 py-3 w-48 text-center\">Action</th>
                                                <th class=\"px-4 py-3 w-24 text-center\">Verified</th>
                                                <th class=\"px-4 py-3 w-48\">Remarks</th>
                                            </tr>";

if (strpos($content, '<th class="px-4 py-3 w-48 text-center">Action</th>') === false) {
    $content = str_replace($oldThead, $newThead, $content);
}

// 2. Add the Preview Modal HTML at the bottom, just before globalToast
$oldModalTarget = "    <!-- Global Toast -->";
$newModalHtml = "    <!-- Preview Modal -->
    <div id=\"cfPreviewModal\" class=\"fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-40 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-300\">
        <div class=\"bg-slate-900 border border-slate-700 rounded-3xl w-full max-w-5xl h-[85vh] flex flex-col shadow-2xl shadow-black overflow-hidden transform scale-95 transition-transform duration-300\" id=\"cfPreviewModalContent\">
            <div class=\"px-6 py-4 border-b border-slate-800 flex justify-between items-center bg-slate-800/50\">
                <h3 id=\"cfPreviewTitle\" class=\"text-lg font-black text-slate-100\">Document Preview</h3>
                <button type=\"button\" onclick=\"closePreviewModal()\" class=\"text-slate-400 hover:text-white p-2 rounded-xl hover:bg-slate-700 transition-colors\"><span class=\"material-symbols-rounded\">close</span></button>
            </div>
            <div class=\"flex-1 p-6 overflow-y-auto bg-slate-950/50\" id=\"cfPreviewBody\">
                <div class=\"w-full h-full flex items-center justify-center text-slate-500 font-bold animate-pulse\">Loading preview...</div>
            </div>
            <div class=\"px-6 py-4 border-t border-slate-800 flex justify-end gap-3 bg-slate-900\">
                <button type=\"button\" onclick=\"closePreviewModal()\" class=\"px-5 py-2.5 rounded-xl font-bold text-sm text-slate-300 hover:text-white hover:bg-slate-800 transition-colors\">Close</button>
                <button type=\"button\" id=\"btnVerifyDoc\" onclick=\"verifyCurrentDoc()\" class=\"bg-emerald-500 hover:bg-emerald-400 text-slate-950 px-5 py-2.5 rounded-xl text-sm font-black transition-premium shadow-lg shadow-emerald-500/20 flex items-center gap-2\"><span class=\"material-symbols-rounded text-[18px]\">verified</span> Verify & Approve</button>
            </div>
        </div>
    </div>

    <!-- Global Toast -->";

if (strpos($content, 'id="cfPreviewModal"') === false) {
    $content = str_replace($oldModalTarget, $newModalHtml, $content);
}

// 3. Rewrite renderChecklist and add modal JS functions
$oldJs = "        function renderChecklist(savedDocs) {";
$newJs = "
        let currentPreviewDocNo = null;

        function renderChecklist(savedDocs) {
            const tbody = document.getElementById('cfChecklistBody');
            let html = '';
            defaultChecklist.forEach((name, idx) => {
                const docNo = idx + 1;
                const saved = savedDocs.find(d => d.document_number == docNo);
                const isChecked = saved && saved.is_checked ? 'checked' : '';
                const remarks = saved && saved.remarks ? saved.remarks : '';
                
                let actionBtn = '';
                const autoGen = [3, 4, 8, 11, 21, 22];
                const calcGen = [16, 19, 20];
                const inputGen = [5, 12];
                
                if (autoGen.includes(docNo)) {
                    actionBtn = `<button type=\"button\" onclick=\"openPreviewModal(`+docNo+`, '`+name.replace(/'/g, \"\\\\'\")+`')\" class=\"w-full py-1.5 rounded-lg bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 border border-indigo-500/20 text-xs font-bold transition-colors flex items-center justify-center gap-1\"><span class=\"material-symbols-rounded text-[14px]\">visibility</span> Generate Preview</button>`;
                } else if (calcGen.includes(docNo)) {
                    actionBtn = `<button type=\"button\" onclick=\"openPreviewModal(`+docNo+`, '`+name.replace(/'/g, \"\\\\'\")+`')\" class=\"w-full py-1.5 rounded-lg bg-purple-500/10 text-purple-400 hover:bg-purple-500/20 border border-purple-500/20 text-xs font-bold transition-colors flex items-center justify-center gap-1\"><span class=\"material-symbols-rounded text-[14px]\">calculate</span> Calculate & Preview</button>`;
                } else if (inputGen.includes(docNo)) {
                    actionBtn = `<button type=\"button\" onclick=\"openPreviewModal(`+docNo+`, '`+name.replace(/'/g, \"\\\\'\")+`')\" class=\"w-full py-1.5 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 border border-blue-500/20 text-xs font-bold transition-colors flex items-center justify-center gap-1\"><span class=\"material-symbols-rounded text-[14px]\">edit_document</span> Input Data</button>`;
                } else {
                    actionBtn = `<select class=\"w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1.5 text-xs text-slate-300 focus:border-amber-500 outline-none\"><option>Attach Physical Copy</option><option>Generate Cover Page</option></select>`;
                }

                html += `
                    <tr class=\"hover:bg-slate-800/30 transition-colors\">
                        <td class=\"px-4 py-3 text-center font-black text-slate-500\">` + docNo + `</td>
                        <td class=\"px-4 py-3 font-medium text-slate-300\">` + name + `</td>
                        <td class=\"px-4 py-3 text-center\">` + actionBtn + `</td>
                        <td class=\"px-4 py-3 text-center\">
                            <input type=\"checkbox\" id=\"cf_check_`+docNo+`\" class=\"cf-check-item w-4 h-4 rounded border-slate-700 bg-slate-900 text-emerald-500 focus:ring-emerald-500/20\" data-doc-no=\"` + docNo + `\" data-doc-name=\"` + name + `\" ` + isChecked + `>
                        </td>
                        <td class=\"px-4 py-3\">
                            <input type=\"text\" id=\"remark_` + docNo + `\" value=\"` + remarks + `\" class=\"w-full bg-slate-950 border border-slate-800 rounded px-2 py-1 text-xs text-white focus:border-amber-500 outline-none placeholder-slate-600\" placeholder=\"Optional remarks...\">
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        function openPreviewModal(docNo, docName) {
            currentPreviewDocNo = docNo;
            document.getElementById('cfPreviewTitle').innerText = \"Document \" + docNo + \": \" + docName;
            
            const modal = document.getElementById('cfPreviewModal');
            const content = document.getElementById('cfPreviewModalContent');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);

            // Fetch preview HTML from server
            document.getElementById('cfPreviewBody').innerHTML = '<div class=\"w-full h-full flex flex-col items-center justify-center text-slate-500 font-bold gap-3\"><span class=\"material-symbols-rounded animate-spin text-3xl\">sync</span> Generating Document...</div>';
            
            fetch(`/api/course-files/${currentCfId}/preview/${docNo}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'SUCCESS') {
                        document.getElementById('cfPreviewBody').innerHTML = `<div class=\"bg-white text-black p-8 rounded-xl shadow-inner min-h-[800px] max-w-4xl mx-auto\">` + data.html + `</div>`;
                    } else {
                        document.getElementById('cfPreviewBody').innerHTML = `<div class=\"text-red-400 font-bold text-center mt-10\">\${data.message || 'Preview unavailable.'}</div>`;
                    }
                })
                .catch(() => {
                    document.getElementById('cfPreviewBody').innerHTML = `<div class=\"text-red-400 font-bold text-center mt-10\">Failed to load preview layout.</div>`;
                });
        }

        function closePreviewModal() {
            const modal = document.getElementById('cfPreviewModal');
            const content = document.getElementById('cfPreviewModalContent');
            
            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function verifyCurrentDoc() {
            if (currentPreviewDocNo) {
                const cb = document.getElementById('cf_check_' + currentPreviewDocNo);
                if (cb) cb.checked = true;
                saveCourseFileProgress();
                closePreviewModal();
                showGlobalMessage('Document ' + currentPreviewDocNo + ' verified and saved.');
            }
        }

        // Dummy replacement to overwrite old function";

if (strpos($content, 'function openPreviewModal') === false) {
    // We need to replace the entire old renderChecklist function block.
    // Easiest is to regex replace from `function renderChecklist(savedDocs) {` to the end of the script before `</script>`
    $content = preg_replace('/function renderChecklist\(savedDocs\) \{.*?(?=<\/script>)/s', $newJs . "\n    ", $content);
}

file_put_contents($path, $content);
echo "UI updated successfully.\n";
