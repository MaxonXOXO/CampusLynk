
        function showGlobalMessage(msg, isError=false) {
            const toast = document.getElementById('globalToast');
            toast.innerHTML = isError 
                ? `<span class="material-symbols-rounded">error</span> ${msg}`
                : `<span class="material-symbols-rounded">check_circle</span> ${msg}`;
            toast.className = `fixed bottom-6 right-6 px-6 py-4 rounded-2xl font-bold text-sm shadow-2xl transition-all duration-300 z-50 flex items-center gap-3 ${isError ? 'bg-red-500 text-white shadow-red-500/30' : 'bg-emerald-500 text-white shadow-emerald-500/30'}`;
            toast.style.transform = 'translateY(0)';
            toast.style.opacity = '1';
            setTimeout(() => {
                toast.style.transform = 'translateY(24px)';
                toast.style.opacity = '0';
            }, 3000);
        }

        // --- COURSE FILES LOGIC ---
        let cfDataTree = [];
        let currentCfId = null;
        let selectedBatch = null;
        let selectedSemester = null;

        document.addEventListener("DOMContentLoaded", () => {
            loadCourseFiles();
        });

        function loadCourseFiles() {
            cfShowLevel(1);
            fetch("/api/course-files/subjects")
                .then(res => res.json())
                .then(data => {
                    if (data.status === "SUCCESS") {
                        cfDataTree = data.batches;
                        renderCfLevel1();
                    } else {
                        document.getElementById("cfLevel1").innerHTML = `<div class="col-span-full py-12 text-center text-red-400 font-bold text-sm">${data.message}</div>`;
                    }
                })
                .catch(err => {
                    document.getElementById("cfLevel1").innerHTML = `<div class="col-span-full py-12 text-center text-red-400 font-bold text-sm">Error loading data.</div>`;
                });
        }

        function renderCfLevel1() {
            const grid = document.getElementById("cfLevel1");
            if (cfDataTree.length === 0) {
                grid.innerHTML = '<div class="col-span-full py-20 text-center text-slate-500 font-bold text-sm bg-slate-900 border border-slate-800 rounded-3xl">No assigned courses found.</div>';
                return;
            }
            grid.innerHTML = cfDataTree.map((b, idx) => `
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 hover:border-amber-500/50 hover:bg-slate-800/80 transition-all cursor-pointer group shadow-sm hover:shadow-amber-500/5" onclick="cfSelectBatch(${idx})">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400/20 to-orange-500/10 flex items-center justify-center border border-amber-500/20">
                            <span class="material-symbols-rounded text-amber-500 text-xl">folder_open</span>
                        </div>
                    </div>
                    <h4 class="text-lg font-black text-slate-100 leading-tight mb-1 group-hover:text-amber-400 transition-colors">${b.batch_year} Admission</h4>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">${b.branch}</p>
                    <div class="mt-6 flex items-center justify-between border-t border-slate-800 pt-4">
                        <span class="text-xs font-bold text-slate-500">${b.semesters.length} Semesters</span>
                        <div class="text-xs text-amber-500 font-bold flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            View <span class="material-symbols-rounded text-[16px]">arrow_forward</span>
                        </div>
                    </div>
                </div>
            `).join("");
        }

        function cfSelectBatch(idx) {
            selectedBatch = cfDataTree[idx];
            document.getElementById("cfCrumbBatchText").innerText = `${selectedBatch.batch_year} (${selectedBatch.branch})`;
            
            const grid = document.getElementById("cfLevel2");
            grid.innerHTML = selectedBatch.semesters.map((s, sIdx) => `
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 hover:border-indigo-500/50 hover:bg-slate-800/80 transition-all cursor-pointer group shadow-sm hover:shadow-indigo-500/5" onclick="cfSelectSemester(${sIdx})">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-blue-500/10 flex items-center justify-center border border-indigo-500/20">
                            <span class="material-symbols-rounded text-indigo-400 text-xl">calendar_month</span>
                        </div>
                    </div>
                    <h4 class="text-lg font-black text-slate-100 leading-tight mb-1 group-hover:text-indigo-400 transition-colors">Semester ${s.semester}</h4>
                    <div class="mt-6 flex items-center justify-between border-t border-slate-800 pt-4">
                        <span class="px-2.5 py-1 rounded-md bg-slate-950 border border-slate-800 text-[10px] font-bold text-slate-400">${s.courses.length} Courses</span>
                        <div class="text-xs text-indigo-400 font-bold flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            View <span class="material-symbols-rounded text-[16px]">arrow_forward</span>
                        </div>
                    </div>
                </div>
            `).join("");
            cfShowLevel(2);
        }

        function cfSelectSemester(sIdx) {
            selectedSemester = selectedBatch.semesters[sIdx];
            document.getElementById("cfCrumbSemText").innerText = `Semester ${selectedSemester.semester}`;
            
            const grid = document.getElementById("cfLevel3");
            grid.innerHTML = selectedSemester.courses.map(c => `
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 hover:border-emerald-500/50 hover:bg-slate-800/80 transition-all cursor-pointer group shadow-sm hover:shadow-emerald-500/5" onclick="cfOpenBuilder('${c.course_file_id}', '${c.subject_name.replace(/'/g, "\\'")}')">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-teal-500/10 flex items-center justify-center border border-emerald-500/20">
                            <span class="material-symbols-rounded text-emerald-400 text-xl">school</span>
                        </div>
                        <span class="px-3 py-1 rounded-lg border text-[10px] font-black uppercase tracking-wider ${c.status === 'Complete' ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20' : 'text-amber-400 bg-amber-500/10 border-amber-500/20'}">${c.status}</span>
                    </div>
                    <h4 class="text-base font-black text-slate-100 leading-snug mb-1 group-hover:text-emerald-400 transition-colors line-clamp-2">${c.subject_name}</h4>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">${c.subject_code}</p>
                    <div class="mt-6 flex items-center justify-end border-t border-slate-800 pt-4">
                        <div class="text-xs text-emerald-400 font-bold flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            Open Builder <span class="material-symbols-rounded text-[16px]">arrow_forward</span>
                        </div>
                    </div>
                </div>
            `).join("");
            cfShowLevel(3);
        }

        function cfOpenBuilder(id, subjectName) {
            currentCfId = id;
            document.getElementById("cfCrumbCourseText").innerText = subjectName;
            document.getElementById("cfSubjectTitle").innerText = subjectName;
            document.getElementById("cfBatchInfo").innerText = `${selectedBatch.batch_year} Admission • ${selectedBatch.branch} • Semester ${selectedSemester.semester}`;
            
            cfShowLevel(4);

            // Load draft data
            fetch(`/api/course-files/${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === "SUCCESS") {
                        const cf = data.course_file;
                        document.getElementById("cfStatusBadge").innerText = cf.status.toUpperCase();
                        
                        const form = document.getElementById("cfForm");
                        form.gaps_identified.value = cf.section_a?.gaps_identified || "";
                        form.bridge_topics.value = cf.section_a?.bridge_topics || "";
                        form.nptel_swayam_links.value = cf.section_b?.nptel_swayam_links || "";
                        form.other_resources.value = cf.section_b?.other_resources || "";
                        form.evaluation_scheme.value = cf.section_c?.evaluation_scheme || "";
                        form.action_taken_report.value = cf.section_d?.action_taken_report || "";
                        form.committee_minutes.value = cf.section_d?.committee_minutes || "";
                        renderChecklist(cf.documents || []);
                    }
                });
        }

        function cfShowLevel(lvl) {
            const crumb = document.getElementById("cfBreadcrumbs");
            const l1 = document.getElementById("cfLevel1");
            const l2 = document.getElementById("cfLevel2");
            const l3 = document.getElementById("cfLevel3");
            const l4 = document.getElementById("cfLevel4");
            
            crumb.classList.remove("hidden");
            l1.classList.add("hidden");
            l2.classList.add("hidden");
            l3.classList.add("hidden");
            l4.classList.add("hidden");

            document.getElementById("cfCrumbBatch").classList.add("hidden");
            document.getElementById("cfCrumbSem").classList.add("hidden");
            document.getElementById("cfCrumbCourse").classList.add("hidden");
            document.getElementById("cfCrumbBatch").classList.remove("flex");
            document.getElementById("cfCrumbSem").classList.remove("flex");
            document.getElementById("cfCrumbCourse").classList.remove("flex");

            if (lvl === 1) {
                crumb.classList.add("hidden");
                l1.classList.remove("hidden");
            } else if (lvl === 2) {
                l2.classList.remove("hidden");
                document.getElementById("cfCrumbBatch").classList.remove("hidden");
                document.getElementById("cfCrumbBatch").classList.add("flex");
            } else if (lvl === 3) {
                l3.classList.remove("hidden");
                document.getElementById("cfCrumbBatch").classList.remove("hidden");
                document.getElementById("cfCrumbBatch").classList.add("flex");
                document.getElementById("cfCrumbSem").classList.remove("hidden");
                document.getElementById("cfCrumbSem").classList.add("flex");
            } else if (lvl === 4) {
                l4.classList.remove("hidden");
                document.getElementById("cfCrumbBatch").classList.remove("hidden");
                document.getElementById("cfCrumbBatch").classList.add("flex");
                document.getElementById("cfCrumbSem").classList.remove("hidden");
                document.getElementById("cfCrumbSem").classList.add("flex");
                document.getElementById("cfCrumbCourse").classList.remove("hidden");
                document.getElementById("cfCrumbCourse").classList.add("flex");
            }
        }

        function switchCfTab(tab) {
            ["A", "B", "C", "D", "Checklist"].forEach(t => {
                const btn = document.getElementById("tabBtn" + t);
                const panel = document.getElementById("cfTab" + t);
                if (t === tab) {
                    btn.className = "w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition-premium bg-amber-500/10 text-amber-400 border-l-4 border-amber-500";
                    panel.classList.remove("hidden");
                } else {
                    btn.className = "w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition-premium text-slate-400 hover:bg-slate-800 hover:text-white border-l-4 border-transparent";
                    panel.classList.add("hidden");
                }
            });
        }

        function saveCourseFileProgress() {
            if (!currentCfId) return;
            const form = document.getElementById("cfForm");
            
            const payload = {
                section_a: { gaps_identified: form.gaps_identified.value, bridge_topics: form.bridge_topics.value },
                section_b: { nptel_swayam_links: form.nptel_swayam_links.value, other_resources: form.other_resources.value },
                section_c: { evaluation_scheme: form.evaluation_scheme.value },
                section_d: { action_taken_report: form.action_taken_report.value, committee_minutes: form.committee_minutes.value },
                checklist: Array.from(document.querySelectorAll('.cf-check-item')).map(cb => ({
                    document_number: cb.dataset.docNo,
                    document_name: cb.dataset.docName,
                    is_checked: cb.checked,
                    remarks: document.getElementById('remark_' + cb.dataset.docNo).value
                }))
            };

            fetch(`/api/course-files/${currentCfId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "SUCCESS") {
                    showGlobalMessage("Draft saved successfully.");
                } else {
                    showGlobalMessage(data.message || "Failed to save", true);
                }
            })
            .catch(() => showGlobalMessage("Network error", true));
        }

        function generateCourseFilePDF() {
            if (!currentCfId) return;
            showGlobalMessage("PDF Generation triggered. Opening in a new tab.", false);
            window.open(`/api/course-files/${currentCfId}/pdf`, "_blank");
        }
    
        const defaultChecklist = [
            'Class Time table (current semester Program timetable)',
            'Faculty Workload',
            'Student List with register numbers',
            'Course Syllabus with Recommended Books (SITTTR)',
            'Course information sheet',
            'Course outcomes',
            'Academic calender',
            'Course Plan',
            'Course log and Attendance from TEAMS',
            'Internal Exam Question Papers CO 1,2,3,4 with mark splitup / Scheme',
            'Internal Examination Result Analysis NBA',
            'Weaker student coaching schedule and proof (if)',
            'Teaching and Learning Methods Proof - handouts, capsule notes etc.',
            'Assignment questions with rubrics',
            'CE Report (SBTE - common)',
            'Grade Sheet - Proof of CO evaluations',
            'External Exam Question Papers / Question bank',
            'SBTE examination result',
            'Attainment of Course Outcome (CO) Co-Po-PsoO map',
            'Attainment of PO/PSO report',
            'Mid semester survey & report',
            'End semester / Course exit survey & report',
            'Internal Examination sample answer scripts',
            'Assignment sample scripts',
            'Others'
        ];

        
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
    