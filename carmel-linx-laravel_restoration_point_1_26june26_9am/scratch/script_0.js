

        
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
                    actionBtn = `<button type="button" onclick="openPreviewModal(`+docNo+`, '`+name.replace(/'/g, "\'")+`')" class="w-full py-1.5 rounded-lg bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 border border-indigo-500/20 text-xs font-bold transition-colors flex items-center justify-center gap-1"><span class="material-symbols-rounded text-[14px]">visibility</span> Generate Preview</button>`;
                } else if (calcGen.includes(docNo)) {
                    actionBtn = `<button type="button" onclick="openPreviewModal(`+docNo+`, '`+name.replace(/'/g, "\'")+`')" class="w-full py-1.5 rounded-lg bg-purple-500/10 text-purple-400 hover:bg-purple-500/20 border border-purple-500/20 text-xs font-bold transition-colors flex items-center justify-center gap-1"><span class="material-symbols-rounded text-[14px]">calculate</span> Calculate & Preview</button>`;
                } else if (inputGen.includes(docNo)) {
                    actionBtn = `<button type="button" onclick="openPreviewModal(`+docNo+`, '`+name.replace(/'/g, "\'")+`')" class="w-full py-1.5 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 border border-blue-500/20 text-xs font-bold transition-colors flex items-center justify-center gap-1"><span class="material-symbols-rounded text-[14px]">edit_document</span> Input Data</button>`;
                } else {
                    actionBtn = `<select class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1.5 text-xs text-slate-300 focus:border-amber-500 outline-none"><option>Attach Physical Copy</option><option>Generate Cover Page</option></select>`;
                }

                html += `
                    <tr class="hover:bg-slate-800/30 transition-colors">
                        <td class="px-4 py-3 text-center font-black text-slate-500">` + docNo + `</td>
                        <td class="px-4 py-3 font-medium text-slate-300">` + name + `</td>
                        <td class="px-4 py-3 text-center">` + actionBtn + `</td>
                        <td class="px-4 py-3 text-center">
                            <input type="checkbox" id="cf_check_`+docNo+`" class="cf-check-item w-4 h-4 rounded border-slate-700 bg-slate-900 text-emerald-500 focus:ring-emerald-500/20" data-doc-no="` + docNo + `" data-doc-name="` + name + `" ` + isChecked + `>
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" id="remark_` + docNo + `" value="` + remarks + `" class="w-full bg-slate-950 border border-slate-800 rounded px-2 py-1 text-xs text-white focus:border-amber-500 outline-none placeholder-slate-600" placeholder="Optional remarks...">
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        function openPreviewModal(docNo, docName) {
            currentPreviewDocNo = docNo;
            document.getElementById('cfPreviewTitle').innerText = "Document " + docNo + ": " + docName;
            
            const modal = document.getElementById('cfPreviewModal');
            const content = document.getElementById('cfPreviewModalContent');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);

            // Fetch preview HTML from server
            document.getElementById('cfPreviewBody').innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-slate-500 font-bold gap-3"><span class="material-symbols-rounded animate-spin text-3xl">sync</span> Generating Document...</div>';
            
            fetch(`/api/course-files/${currentCfId}/preview/${docNo}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'SUCCESS') {
                        document.getElementById('cfPreviewBody').innerHTML = `<div class="bg-white text-black p-8 rounded-xl shadow-inner min-h-[800px] max-w-4xl mx-auto">` + data.html + `</div>`;
                    } else {
                        document.getElementById('cfPreviewBody').innerHTML = `<div class="text-red-400 font-bold text-center mt-10">${data.message || 'Preview unavailable.'}</div>`;
                    }
                })
                .catch(() => {
                    document.getElementById('cfPreviewBody').innerHTML = `<div class="text-red-400 font-bold text-center mt-10">Failed to load preview layout.</div>`;
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

        // Dummy replacement to overwrite old function
    