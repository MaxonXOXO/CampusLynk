<x-layouts.faculty-shell title="NBA Course Files" subtitle="Accreditation documents and course documentation." activeNav="course_files">

    <div class="space-y-6">
        
        <!-- BREADCRUMBS -->
        <div id="cfBreadcrumbs" class="hidden flex items-center gap-2 font-bold text-slate-500 bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs text-xs">
            <button onclick="cfShowLevel(1)" class="hover:text-blue-600 transition-colors flex items-center gap-1 cursor-pointer">
                <span class="material-symbols-rounded text-base">home</span> All Batches
            </button>
            <span id="cfCrumbBatch" class="hidden items-center gap-2">
                <span class="material-symbols-rounded text-base text-slate-300">chevron_right</span>
                <button onclick="cfShowLevel(2)" class="hover:text-blue-600 transition-colors bg-slate-100 px-3 py-1 rounded-lg cursor-pointer" id="cfCrumbBatchText">2024</button>
            </span>
            <span id="cfCrumbSem" class="hidden items-center gap-2">
                <span class="material-symbols-rounded text-base text-slate-300">chevron_right</span>
                <button onclick="cfShowLevel(3)" class="hover:text-blue-600 transition-colors bg-slate-100 px-3 py-1 rounded-lg cursor-pointer" id="cfCrumbSemText">Sem 1</button>
            </span>
            <span id="cfCrumbCourse" class="hidden items-center gap-2">
                <span class="material-symbols-rounded text-base text-slate-300">chevron_right</span>
                <span class="text-blue-700 bg-blue-50 px-3 py-1 rounded-lg border border-blue-200" id="cfCrumbCourseText">Subject</span>
            </span>
        </div>

        <!-- LEVEL 1: BATCH SELECTION -->
        <div id="cfLevel1" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate-fade-in">
            <div class="col-span-full py-20 text-center text-slate-400 font-semibold text-sm">
                <div class="w-8 h-8 border-2 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                <span>Loading assigned courses...</span>
            </div>
        </div>

        <!-- LEVEL 2: SEMESTER SELECTION -->
        <div id="cfLevel2" class="hidden grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 animate-fade-in"></div>

        <!-- LEVEL 3: COURSE SELECTION -->
        <div id="cfLevel3" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate-fade-in"></div>

        <!-- LEVEL 4: COURSE FILE BUILDER -->
        <div id="cfLevel4" class="hidden space-y-6 animate-fade-in">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <div>
                    <h2 id="cfSubjectTitle" class="font-bold text-slate-900 mb-0.5 text-lg tracking-tight">Subject Name</h2>
                    <p id="cfBatchInfo" class="text-slate-500 font-medium text-xs">Batch Info</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <span id="cfStatusBadge" class="px-3 py-1 rounded-full border font-bold uppercase tracking-wider text-amber-700 bg-amber-50 border-amber-200 text-xs">DRAFT</span>
                    <button type="button" onclick="openAttainmentSettings()" class="bg-white hover:bg-slate-50 text-slate-700 px-3.5 py-2 rounded-xl text-xs font-bold transition-premium flex items-center gap-2 border border-slate-200 cursor-pointer shadow-2xs">
                        <span class="material-symbols-rounded text-sm text-blue-600">tune</span> Attainment Targets
                    </button>
                    <button type="button" onclick="saveCourseFileProgress()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-premium shadow-sm shadow-blue-500/20 cursor-pointer">
                        Save Draft
                    </button>
                    <button type="button" onclick="generateCourseFilePDF()" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 shadow-sm shadow-amber-500/20 cursor-pointer">
                        <span class="material-symbols-rounded text-base">picture_as_pdf</span> Generate PDF
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Sidebar Tabs -->
                <div class="lg:col-span-3 space-y-2">
                    <button type="button" onclick="switchCfTab('A')" id="tabBtnA" class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold transition-premium bg-blue-50 text-blue-700 border-l-4 border-blue-600 shadow-2xs cursor-pointer">Section A: Planning</button>
                    <button type="button" onclick="switchCfTab('B')" id="tabBtnB" class="w-full text-left px-4 py-3 rounded-xl text-xs font-semibold transition-premium text-slate-600 hover:bg-slate-100 hover:text-slate-900 border-l-4 border-transparent cursor-pointer">Section B: Materials</button>
                    <button type="button" onclick="switchCfTab('C')" id="tabBtnC" class="w-full text-left px-4 py-3 rounded-xl text-xs font-semibold transition-premium text-slate-600 hover:bg-slate-100 hover:text-slate-900 border-l-4 border-transparent cursor-pointer">Section C: Assessments</button>
                    <button type="button" onclick="switchCfTab('D')" id="tabBtnD" class="w-full text-left px-4 py-3 rounded-xl text-xs font-semibold transition-premium text-slate-600 hover:bg-slate-100 hover:text-slate-900 border-l-4 border-transparent cursor-pointer">Section D: Attainment</button>
                    <button type="button" onclick="switchCfTab('Checklist')" id="tabBtnChecklist" class="w-full text-left px-4 py-3 rounded-xl text-xs font-semibold transition-premium text-slate-600 hover:bg-slate-100 hover:text-slate-900 border-l-4 border-transparent mt-3 bg-slate-100 cursor-pointer">Master Checklist</button>
                </div>

                <!-- Form Content -->
                <div class="lg:col-span-9 bg-white border border-slate-200 rounded-2xl p-6 shadow-xs min-h-[450px]">
                    <form id="cfForm">
                        <!-- Section A -->
                        <div id="cfTabA" class="space-y-6 animate-fade-in">
                            <div class="bg-blue-50/70 border border-blue-200 p-5 rounded-2xl flex items-start gap-4 shadow-2xs">
                                <div class="bg-blue-100 text-blue-700 p-2 rounded-xl shrink-0"><span class="material-symbols-rounded text-lg">sync</span></div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 mb-1">Auto-Pulled Data Included</h4>
                                    <p class="text-xs text-slate-600 leading-relaxed font-medium">Syllabus, Course Outcomes (COs), and your detailed Lesson Plans will be automatically injected into the final PDF. You do not need to re-enter them here.</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Gaps Identified (if any)</label>
                                    <textarea name="gaps_identified" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-premium placeholder-slate-400 shadow-2xs" placeholder="E.g., Students lacked prerequisite knowledge in..."></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Bridge Topics Delivered</label>
                                    <textarea name="bridge_topics" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-premium placeholder-slate-400 shadow-2xs" placeholder="E.g., Conducted 2 extra hours on basics of..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Section B -->
                        <div id="cfTabB" class="hidden space-y-6 animate-fade-in">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">NPTEL / Swayam Links</label>
                                <textarea name="nptel_swayam_links" rows="4" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-premium placeholder-slate-400 shadow-2xs" placeholder="Paste external course URLs..."></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Other Reference Materials / Handouts</label>
                                <textarea name="other_resources" rows="4" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-premium placeholder-slate-400 shadow-2xs" placeholder="List textbooks, custom lab manuals, etc..."></textarea>
                            </div>
                        </div>

                        <!-- Section C -->
                        <div id="cfTabC" class="hidden space-y-6 animate-fade-in">
                            <div class="bg-emerald-50/70 border border-emerald-200 p-5 rounded-2xl flex items-start gap-4 shadow-2xs">
                                <div class="bg-emerald-100 text-emerald-700 p-2 rounded-xl shrink-0"><span class="material-symbols-rounded text-lg">grading</span></div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 mb-1">Assessments Auto-Linked</h4>
                                    <p class="text-xs text-slate-600 leading-relaxed font-medium">Test Questions, Assignment Questions, and the final Student Mark Sheets will be attached automatically.</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Evaluation Scheme</label>
                                <textarea name="evaluation_scheme" rows="4" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-premium placeholder-slate-400 shadow-2xs" placeholder="e.g., Internals 40%, End Sem 60%..."></textarea>
                            </div>
                        </div>

                        <!-- Section D -->
                        <div id="cfTabD" class="hidden space-y-6 animate-fade-in">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Action Taken Report (if COs not fully attained)</label>
                                <textarea name="action_taken_report" rows="4" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-premium placeholder-slate-400 shadow-2xs"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Course Committee Minutes (Summary)</label>
                                <textarea name="committee_minutes" rows="4" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-premium placeholder-slate-400 shadow-2xs"></textarea>
                            </div>
                        </div>
                        
                        <!-- Master Checklist -->
                        <div id="cfTabChecklist" class="hidden space-y-6 animate-fade-in">
                            <div class="bg-blue-50/70 border border-blue-200 p-5 rounded-2xl flex items-start gap-4 mb-4 shadow-2xs">
                                <div class="bg-blue-100 text-blue-700 p-2 rounded-xl shrink-0"><span class="material-symbols-rounded text-lg">checklist</span></div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 mb-1">File Integrity Checklist</h4>
                                    <p class="text-xs text-slate-600 leading-relaxed font-medium">Verify each document before final PDF generation. You can check off items as you complete them.</p>
                                </div>
                            </div>
                            <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
                                <table class="w-full text-left text-sm">
                                    <thead class="text-xs text-slate-600 bg-slate-50 uppercase border-b border-slate-200 font-bold">
                                        <tr>
                                            <th class="px-4 py-3 w-16 text-center">Doc No.</th>
                                            <th class="px-4 py-3">Document Name</th>
                                            <th class="px-4 py-3 w-48 text-center">Action</th>
                                            <th class="px-4 py-3 w-24 text-center">Verified</th>
                                            <th class="px-4 py-3 w-48">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cfChecklistBody" class="divide-y divide-slate-100">
                                        <!-- Dynamically populated -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Attainment Settings Modal -->
    <div id="attainmentModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-200">
        <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden transform scale-95 transition-transform duration-200" id="attainmentModalContent">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-white">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-rounded text-blue-600 text-lg">tune</span> Attainment Targets
                </h3>
                <button type="button" onclick="closeAttainmentSettings()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer">
                    <span class="material-symbols-rounded text-base">close</span>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Target Mark Threshold (%)</label>
                    <div class="relative">
                        <input type="number" id="targetMarkPercent" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 font-bold focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm shadow-2xs" placeholder="60">
                        <span class="absolute right-4 top-2.5 text-slate-400 font-bold text-sm">%</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Students must score &gt;= this % to attain the CO.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Level 3 Batch Target (%)</label>
                    <div class="relative">
                        <input type="number" id="level3Percent" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 font-bold focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm shadow-2xs" placeholder="70">
                        <span class="absolute right-4 top-2.5 text-slate-400 font-bold text-sm">%</span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Level 2 Batch Target (%)</label>
                    <div class="relative">
                        <input type="number" id="level2Percent" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 font-bold focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm shadow-2xs" placeholder="60">
                        <span class="absolute right-4 top-2.5 text-slate-400 font-bold text-sm">%</span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Level 1 Batch Target (%)</label>
                    <div class="relative">
                        <input type="number" id="level1Percent" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 font-bold focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm shadow-2xs" placeholder="50">
                        <span class="absolute right-4 top-2.5 text-slate-400 font-bold text-sm">%</span>
                    </div>
                </div>
                
                <div class="bg-blue-50/70 border border-blue-200 p-3.5 rounded-xl mt-4">
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">
                        <strong class="text-blue-700">How this works:</strong> These levels evaluate the <em>entire class</em>. 
                        If you set Level 3 to <strong class="text-slate-900">70%</strong>, your batch achieves the maximum Level 3 rating 
                        only if <strong class="text-slate-900">70% or more</strong> of the students score above your Target Mark.
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3 bg-slate-50">
                <button type="button" onclick="closeAttainmentSettings()" class="px-4 py-2 rounded-xl font-bold text-xs text-slate-600 hover:text-slate-900 hover:bg-slate-200 transition-colors cursor-pointer">Cancel</button>
                <button type="button" id="btnSaveAttainment" onclick="saveAttainmentSettings()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition-premium shadow-sm shadow-blue-500/20 flex items-center gap-1.5 cursor-pointer">Save Targets</button>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="cfPreviewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-200">
        <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-5xl h-[88vh] flex flex-col shadow-2xl overflow-hidden transform scale-95 transition-transform duration-200" id="cfPreviewModalContent">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-white">
                <h3 id="cfPreviewTitle" class="text-sm font-bold text-slate-900">Document Preview</h3>
                <button type="button" onclick="closePreviewModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer"><span class="material-symbols-rounded text-base">close</span></button>
            </div>
            <div class="flex-1 p-6 overflow-y-auto bg-slate-50" id="cfPreviewBody">
                <div class="w-full h-full flex items-center justify-center text-slate-400 font-semibold text-sm">Loading preview...</div>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-3 bg-white">
                <button type="button" onclick="printPreviewDoc()" class="text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 border border-slate-200 cursor-pointer">
                    <span class="material-symbols-rounded text-sm">print</span> Print Document
                </button>
                <button type="button" onclick="closePreviewModal()" class="px-4 py-2 rounded-xl font-bold text-xs text-slate-600 hover:text-slate-900 transition-colors cursor-pointer">Close</button>
                <button type="button" id="btnVerifyDoc" onclick="verifyCurrentDoc()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition-premium shadow-sm shadow-emerald-500/20 flex items-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-rounded text-sm">verified</span> Verify &amp; Approve
                </button>
            </div>
        </div>
    </div>

    <!-- Global Toast -->
    <div id="globalToast" class="fixed bottom-6 right-6 px-5 py-3.5 rounded-2xl font-bold text-xs shadow-xl transition-all duration-300 translate-y-24 opacity-0 z-50 flex items-center gap-2.5"></div>

    @push('scripts')
    <script>
        function showGlobalMessage(msg, isError=false) {
            const toast = document.getElementById('globalToast');
            toast.innerHTML = isError 
                ? `<span class="material-symbols-rounded text-base">error</span> ${msg}`
                : `<span class="material-symbols-rounded text-base">check_circle</span> ${msg}`;
            toast.className = `fixed bottom-6 right-6 px-5 py-3.5 rounded-2xl font-bold text-xs shadow-xl transition-all duration-300 z-50 flex items-center gap-2.5 ${isError ? 'bg-rose-600 text-white shadow-rose-600/30' : 'bg-emerald-600 text-white shadow-emerald-600/30'}`;
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
                        document.getElementById("cfLevel1").innerHTML = `<div class="col-span-full py-12 text-center text-rose-600 font-semibold text-xs">${data.message}</div>`;
                    }
                })
                .catch(err => {
                    document.getElementById("cfLevel1").innerHTML = `<div class="col-span-full py-12 text-center text-rose-600 font-semibold text-xs">Error loading data.</div>`;
                });
        }

        function renderCfLevel1() {
            const grid = document.getElementById("cfLevel1");
            if (cfDataTree.length === 0) {
                grid.innerHTML = '<div class="col-span-full py-20 text-center text-slate-400 font-semibold text-sm bg-white border border-slate-200 rounded-2xl">No assigned courses found.</div>';
                return;
            }
            grid.innerHTML = cfDataTree.map((b, idx) => `
                <div class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-blue-400 hover:shadow-md transition-all duration-200 cursor-pointer group shadow-2xs" onclick="cfSelectBatch(${idx})">
                    <div class="flex justify-between items-start mb-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200">
                            <span class="material-symbols-rounded text-lg">folder_open</span>
                        </div>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900 leading-tight mb-1 group-hover:text-blue-600 transition-colors">${b.batch_year} Admission</h4>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">${b.branch_full_name || b.branch}</p>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                        <span class="text-xs font-semibold text-slate-500">${b.semesters.length} Semesters</span>
                        <div class="text-xs text-blue-600 font-bold flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            View <span class="material-symbols-rounded text-sm">arrow_forward</span>
                        </div>
                    </div>
                </div>
            `).join("");
        }

        function cfSelectBatch(idx) {
            selectedBatch = cfDataTree[idx];
            document.getElementById("cfCrumbBatchText").innerText = `${selectedBatch.batch_year} (${selectedBatch.branch_full_name || selectedBatch.branch})`;
            
            const grid = document.getElementById("cfLevel2");
            grid.innerHTML = selectedBatch.semesters.map((s, sIdx) => `
                <div class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-blue-400 hover:shadow-md transition-all duration-200 cursor-pointer group shadow-2xs" onclick="cfSelectSemester(${sIdx})">
                    <div class="flex justify-between items-start mb-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200">
                            <span class="material-symbols-rounded text-lg">calendar_month</span>
                        </div>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900 leading-tight mb-1 group-hover:text-blue-600 transition-colors">Semester ${s.semester}</h4>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-xs font-bold text-slate-600">${s.courses.length} Courses</span>
                        <div class="text-xs text-blue-600 font-bold flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            View <span class="material-symbols-rounded text-sm">arrow_forward</span>
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
                <div class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-blue-400 hover:shadow-md transition-all duration-200 cursor-pointer group shadow-2xs" onclick="cfHandleOpen('${c.course_file_id}', '${c.batch_subject_id}', '${c.subject_name.replace(/'/g, "\\'")}', '${c.revision}')">
                    <div class="flex justify-between items-start mb-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200">
                            <span class="material-symbols-rounded text-lg">school</span>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider ${c.status === 'Complete' ? 'text-emerald-700 bg-emerald-50 border border-emerald-200' : 'text-amber-700 bg-amber-50 border border-amber-200'}">${c.status}</span>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900 leading-snug mb-1 group-hover:text-blue-600 transition-colors line-clamp-2">${c.subject_name}</h4>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">${c.subject_code}</p>
                    <div class="mt-4 flex items-center justify-end border-t border-slate-100 pt-3">
                        <div class="text-xs text-blue-600 font-bold flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            Open Builder <span class="material-symbols-rounded text-sm">arrow_forward</span>
                        </div>
                    </div>
                </div>
            `).join("");
            cfShowLevel(3);
        }

        function cfHandleOpen(courseFileId, subjectId, subjectName, revision) {
            if (revision === 'REV2026') {
                window.open(`/r26/classroom/course-file/${subjectId}`, '_blank');
            } else {
                cfOpenBuilder(courseFileId, subjectName);
            }
        }

        function cfOpenBuilder(id, subjectName) {
            currentCfId = id;
            document.getElementById("cfCrumbCourseText").innerText = subjectName;
            document.getElementById("cfSubjectTitle").innerText = subjectName;
            document.getElementById("cfBatchInfo").innerText = `${selectedBatch.batch_year} Admission • ${selectedBatch.branch_full_name || selectedBatch.branch} • Semester ${selectedSemester.semester}`;
            
            cfShowLevel(4);

            // Load draft data
            fetch(`/api/course-files/${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === "SUCCESS") {
                        const cf = data.course_file;
                        const statusUpper = cf.status.toUpperCase();
                        const badge = document.getElementById("cfStatusBadge");
                        badge.innerText = statusUpper;
                        if (statusUpper === "COMPLETE") {
                            badge.className = "px-3 py-1 rounded-full border font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200 text-xs";
                        } else {
                            badge.className = "px-3 py-1 rounded-full border font-bold uppercase tracking-wider text-amber-700 bg-amber-50 border border-amber-200 text-xs";
                        }
                        
                        if (cf.attainment_settings) {
                            try {
                                currentAttainmentSettings = JSON.parse(cf.attainment_settings);
                            } catch(e) {
                                currentAttainmentSettings = null;
                            }
                        } else {
                            currentAttainmentSettings = null;
                        }
                        
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
                    btn.className = "w-full text-left px-4 py-3 rounded-xl text-xs font-bold transition-premium bg-blue-50 text-blue-700 border-l-4 border-blue-600 shadow-2xs cursor-pointer";
                    panel.classList.remove("hidden");
                } else {
                    btn.className = "w-full text-left px-4 py-3 rounded-xl text-xs font-semibold transition-premium text-slate-600 hover:bg-slate-100 hover:text-slate-900 border-l-4 border-transparent cursor-pointer";
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

            const btnSave = document.querySelector("button[onclick='saveCourseFileProgress()']");
            const originalText = btnSave.innerHTML;
            btnSave.innerHTML = `<span class="material-symbols-rounded animate-spin align-middle text-sm mr-1">sync</span> Saving...`;
            btnSave.disabled = true;

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
                btnSave.innerHTML = originalText;
                btnSave.disabled = false;
                if (data.status === "SUCCESS") {
                    showGlobalMessage("Draft saved successfully.");
                    const updatedCf = data.course_file || {};
                    const newStatus = (updatedCf.status || "Draft").toUpperCase();
                    const badge = document.getElementById("cfStatusBadge");
                    badge.innerText = newStatus;
                    if (newStatus === "COMPLETE") {
                        badge.className = "px-3 py-1 rounded-full border font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200 text-xs";
                    } else {
                        badge.className = "px-3 py-1 rounded-full border font-bold uppercase tracking-wider text-amber-700 bg-amber-50 border border-amber-200 text-xs";
                    }
                } else {
                    showGlobalMessage(data.message || "Failed to save", true);
                }
            })
            .catch(() => {
                btnSave.innerHTML = originalText;
                btnSave.disabled = false;
                showGlobalMessage("Network error", true);
            });
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
            'Internal Marks - SBTE (CIA)',
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
                const autoGen = [3, 4, 6, 8, 11, 14, 15, 21, 22];
                const calcGen = [16, 19, 20];
                const inputGen = [5, 12];
                
                if (autoGen.includes(docNo)) {
                    actionBtn = `<button type="button" onclick="openPreviewModal(`+docNo+`, '`+name.replace(/'/g, "\'")+`')" class="w-full py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 text-xs font-bold transition-colors flex items-center justify-center gap-1 cursor-pointer"><span class="material-symbols-rounded text-sm">visibility</span> Generate Preview</button>`;
                } else if (calcGen.includes(docNo)) {
                    actionBtn = `<button type="button" onclick="openPreviewModal(`+docNo+`, '`+name.replace(/'/g, "\'")+`')" class="w-full py-1.5 rounded-lg bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 text-xs font-bold transition-colors flex items-center justify-center gap-1 cursor-pointer"><span class="material-symbols-rounded text-sm">calculate</span> Calculate &amp; Preview</button>`;
                } else if (inputGen.includes(docNo)) {
                    actionBtn = `<button type="button" onclick="openPreviewModal(`+docNo+`, '`+name.replace(/'/g, "\'")+`')" class="w-full py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold transition-colors flex items-center justify-center gap-1 cursor-pointer"><span class="material-symbols-rounded text-sm">edit_document</span> Input Data</button>`;
                } else {
                    actionBtn = `<select class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-xs text-slate-700 focus:border-blue-500 outline-none cursor-pointer"><option>Attach Physical Copy</option><option>Generate Cover Page</option></select>`;
                }

                html += `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-center font-bold font-mono text-slate-500 text-xs">` + docNo + `</td>
                        <td class="px-4 py-3 font-semibold text-slate-800 text-xs">` + name + `</td>
                        <td class="px-4 py-3 text-center">` + actionBtn + `</td>
                        <td class="px-4 py-3 text-center">
                            <input type="checkbox" id="cf_check_`+docNo+`" class="cf-check-item w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer" data-doc-no="` + docNo + `" data-doc-name="` + name + `" ` + isChecked + `>
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" id="remark_` + docNo + `" value="` + remarks + `" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1 text-xs text-slate-800 focus:border-blue-500 outline-none placeholder-slate-400" placeholder="Optional remarks...">
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

            document.getElementById('btnVerifyDoc').onclick = verifyCurrentDoc;

            document.getElementById('cfPreviewBody').innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-slate-400 font-semibold gap-3 text-sm"><span class="material-symbols-rounded animate-spin text-xl text-blue-600">sync</span> Generating Document...</div>';
            
            fetch(`/api/course-files/${currentCfId}/preview/${docNo}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'SUCCESS') {
                        document.getElementById('cfPreviewBody').innerHTML = `<div class="bg-white text-slate-900 p-8 rounded-2xl shadow-inner min-h-[800px] max-w-4xl mx-auto border border-slate-200">` + data.html + `</div>`;
                        
                        const scripts = document.getElementById('cfPreviewBody').getElementsByTagName('script');
                        for (let i = 0; i < scripts.length; i++) {
                            try {
                                eval(scripts[i].innerText);
                            } catch (e) {
                                console.error('Error executing injected script:', e);
                            }
                        }
                    } else {
                        document.getElementById('cfPreviewBody').innerHTML = `<div class="text-rose-600 font-bold text-center mt-10 text-xs">${data.message || 'Preview unavailable.'}</div>`;
                    }
                })
                .catch(() => {
                    document.getElementById('cfPreviewBody').innerHTML = `<div class="text-rose-600 font-bold text-center mt-10 text-xs">Failed to load preview layout.</div>`;
                });
        }

        function closePreviewModal() {
            const modal = document.getElementById('cfPreviewModal');
            const content = document.getElementById('cfPreviewModalContent');
            
            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        let currentAttainmentSettings = null;

        function openAttainmentSettings() {
            if (currentAttainmentSettings) {
                document.getElementById('targetMarkPercent').value = currentAttainmentSettings.target_mark_percent || 60;
                document.getElementById('level3Percent').value = currentAttainmentSettings.level3_percent || 70;
                document.getElementById('level2Percent').value = currentAttainmentSettings.level2_percent || 60;
                document.getElementById('level1Percent').value = currentAttainmentSettings.level1_percent || 50;
            }

            const modal = document.getElementById('attainmentModal');
            const content = document.getElementById('attainmentModalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
        }

        function closeAttainmentSettings() {
            const modal = document.getElementById('attainmentModal');
            const content = document.getElementById('attainmentModalContent');
            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        function saveAttainmentSettings() {
            const btn = document.getElementById('btnSaveAttainment');
            btn.innerHTML = `<span class="material-symbols-rounded animate-spin text-sm">sync</span> Saving...`;

            const payload = {
                target_mark_percent: document.getElementById('targetMarkPercent').value || 60,
                level3_percent: document.getElementById('level3Percent').value || 70,
                level2_percent: document.getElementById('level2Percent').value || 60,
                level1_percent: document.getElementById('level1Percent').value || 50,
            };

            fetch(`/api/course-files/${currentCfId}/attainment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'SUCCESS') {
                    currentAttainmentSettings = data.data;
                    showGlobalMessage('Attainment Targets saved successfully!');
                    closeAttainmentSettings();
                } else {
                    alert(data.message || 'Error saving targets');
                }
            })
            .catch(err => {
                console.error(err);
                alert("Network error.");
            })
            .finally(() => {
                btn.innerHTML = `Save Targets`;
            });
        }

        function printPreviewDoc() {
            let html = document.getElementById('cfPreviewBody').innerHTML;
            if(!html || html.includes('Loading preview') || html.includes('Failed')) {
                alert("Nothing to print.");
                return;
            }
            
            let styles = '';
            document.querySelectorAll('link[rel="stylesheet"], style').forEach(el => {
                styles += el.outerHTML;
            });
            
            let printWin = window.open('', '_blank');
            printWin.document.write(`
                <html>
                    <head>
                        <title>Print Document</title>
                        ${styles}
                        <script src="https://cdn.tailwindcss.com"><\/script>
                        <style>
                            @page { size: A4; margin: 15mm; }
                            body { font-family: 'Poppins', sans-serif; background: #fff !important; color: #000 !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                            .min-h-\\[800px\\] { min-height: auto !important; }
                            .max-w-4xl { max-width: 100% !important; }
                            .shadow-inner { box-shadow: none !important; }
                            .bg-white { background: transparent !important; }
                            .mx-auto { margin: 0 !important; }
                            .p-8 { padding: 0 !important; }
                        </style>
                    </head>
                    <body>
                        ${html}
                        <script>
                            setTimeout(() => { window.print(); window.close(); }, 1500);
                        <\/script>
                    </body>
                </html>
            `);
            printWin.document.close();
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

        // Dedicated CIS Upload Function for Document 5
        window.uploadCis = function(file) {
            if (!file) return;
            
            const statusDiv = document.getElementById('uploadStatus');
            if (statusDiv) {
                statusDiv.classList.remove('hidden');
                statusDiv.innerHTML = '<span class="material-symbols-rounded animate-spin align-middle text-sm">sync</span> Uploading...';
            }
            
            const formData = new FormData();
            formData.append('cis_pdf', file);

            fetch(`/api/course-files/${currentCfId}/document/5/upload-cis`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    showGlobalMessage('CIS PDF successfully uploaded!');
                    const cb = document.getElementById('cf_check_5');
                    if (cb) cb.checked = true;
                    saveCourseFileProgress();
                    openPreviewModal(5); 
                } else {
                    showGlobalMessage(data.message || 'Error uploading file.', true);
                    if (statusDiv) statusDiv.classList.add('hidden');
                }
            })
            .catch(err => {
                showGlobalMessage('Network error during upload.', true);
                if (statusDiv) statusDiv.classList.add('hidden');
            });
        }
    </script>
    @endpush
</x-layouts.faculty-shell>
