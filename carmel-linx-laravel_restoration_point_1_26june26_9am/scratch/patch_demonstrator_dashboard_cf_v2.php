<?php

$filePath = "c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\demonstrator_dashboard.blade.php";
$content = file_get_contents($filePath);

$newHtml = '
      <!-- PANEL: COURSE FILES -->
      <div id="panelCourseFiles" class="hidden h-full flex flex-col">
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center justify-between shrink-0 mb-6">
          <div>
            <h3 class="text-sm font-black text-slate-200 flex items-center gap-2 mt-1">
              <span class="material-symbols-rounded text-amber-400 text-lg">folder_special</span> Course Files (NBA/SBTE)
            </h3>
            <p class="text-[10px] text-slate-400 font-medium">Navigate through your assigned batches to compile course files.</p>
          </div>
        </div>

        <div class="flex-1 overflow-y-auto pr-2 pb-6 space-y-6">
            <!-- BREADCRUMBS -->
            <div id="cfBreadcrumbs" class="hidden flex items-center gap-2 text-xs font-bold text-slate-400 mb-4 bg-slate-900/50 p-2 rounded-lg border border-slate-800">
                <button onclick="cfShowLevel(1)" class="hover:text-amber-400 transition-colors flex items-center gap-1"><span class="material-symbols-rounded text-[14px]">home</span> Batches</button>
                <span id="cfCrumbBatch" class="hidden items-center gap-2"><span class="material-symbols-rounded text-[14px]">chevron_right</span> <button onclick="cfShowLevel(2)" class="hover:text-amber-400 transition-colors" id="cfCrumbBatchText">2024</button></span>
                <span id="cfCrumbSem" class="hidden items-center gap-2"><span class="material-symbols-rounded text-[14px]">chevron_right</span> <button onclick="cfShowLevel(3)" class="hover:text-amber-400 transition-colors" id="cfCrumbSemText">Sem 1</button></span>
                <span id="cfCrumbCourse" class="hidden items-center gap-2"><span class="material-symbols-rounded text-[14px]">chevron_right</span> <span class="text-slate-200" id="cfCrumbCourseText">Subject</span></span>
            </div>

            <!-- LEVEL 1: BATCH SELECTION -->
            <div id="cfLevel1" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 animate-fade-in">
                <div class="col-span-full py-12 text-center text-slate-500 font-bold text-xs animate-pulse">Loading data...</div>
            </div>

            <!-- LEVEL 2: SEMESTER SELECTION -->
            <div id="cfLevel2" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 animate-fade-in"></div>

            <!-- LEVEL 3: COURSE SELECTION -->
            <div id="cfLevel3" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 animate-fade-in"></div>

            <!-- LEVEL 4: COURSE FILE BUILDER -->
            <div id="cfLevel4" class="hidden space-y-6 animate-fade-in">
                <div class="flex items-center justify-between">
                    <h2 id="cfSubjectTitle" class="text-lg font-black text-amber-400">Subject Name</h2>
                    <div class="flex gap-2">
                        <button onclick="saveCourseFileProgress()" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-1.5 rounded-lg text-xs font-bold transition-premium">Save Progress</button>
                        <button onclick="generateCourseFilePDF()" class="bg-amber-600 hover:bg-amber-500 text-white px-4 py-1.5 rounded-lg text-xs font-bold transition-premium flex items-center gap-1"><span class="material-symbols-rounded text-sm">picture_as_pdf</span> Generate PDF</button>
                    </div>
                </div>

                <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 flex gap-4 items-center">
                    <div class="flex-1">
                        <p id="cfBatchInfo" class="text-xs text-slate-400 font-medium">Batch Info</p>
                    </div>
                    <div class="text-right">
                        <span id="cfStatusBadge" class="px-3 py-1 rounded-lg border text-[10px] font-bold uppercase tracking-wider text-amber-400 bg-amber-500/10 border-amber-500/20">DRAFT</span>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="flex bg-slate-950/60 p-1.5 rounded-xl border border-slate-800">
                    <button onclick="switchCfTab(\'A\')" id="tabBtnA" class="flex-1 py-2 rounded-lg text-xs font-bold transition-premium bg-slate-800 text-white">Section A: Planning</button>
                    <button onclick="switchCfTab(\'B\')" id="tabBtnB" class="flex-1 py-2 rounded-lg text-xs font-bold transition-premium text-slate-500 hover:text-slate-300">Section B: Materials</button>
                    <button onclick="switchCfTab(\'C\')" id="tabBtnC" class="flex-1 py-2 rounded-lg text-xs font-bold transition-premium text-slate-500 hover:text-slate-300">Section C: Assessments</button>
                    <button onclick="switchCfTab(\'D\')" id="tabBtnD" class="flex-1 py-2 rounded-lg text-xs font-bold transition-premium text-slate-500 hover:text-slate-300">Section D: Attainment</button>
                </div>

                <form id="cfForm">
                    <!-- Section A -->
                    <div id="cfTabA" class="space-y-4">
                        <div class="bg-slate-950/40 border border-slate-800/60 p-4 rounded-xl">
                            <h4 class="text-xs font-bold text-slate-200 mb-2">Auto-Pulled Data</h4>
                            <p class="text-[10px] text-slate-400">Syllabus, Course Outcomes (COs), and Lesson Plans will be automatically injected into the final PDF from your existing dashboard records.</p>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Gaps Identified (if any)</label>
                                <textarea name="gaps_identified" rows="3" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Bridge Topics</label>
                                <textarea name="bridge_topics" rows="3" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section B -->
                    <div id="cfTabB" class="hidden space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">NPTEL / Swayam Links</label>
                                <textarea name="nptel_swayam_links" rows="4" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none" placeholder="Paste external course URLs..."></textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Other Reference Materials (Lab Manuals etc)</label>
                                <textarea name="other_resources" rows="4" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none" placeholder="Any physical books or hand-outs..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section C -->
                    <div id="cfTabC" class="hidden space-y-4">
                        <div class="bg-slate-950/40 border border-slate-800/60 p-4 rounded-xl">
                            <h4 class="text-xs font-bold text-slate-200 mb-2">Auto-Pulled Assessments</h4>
                            <p class="text-[10px] text-slate-400">Test Questions, Assignment Questions, and Mark Sheets will be automatically extracted.</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Evaluation Scheme</label>
                            <textarea name="evaluation_scheme" rows="3" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none" placeholder="e.g., Internals 40%, End Sem 60%..."></textarea>
                        </div>
                    </div>

                    <!-- Section D -->
                    <div id="cfTabD" class="hidden space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Action Taken Report (if COs not fully attained)</label>
                            <textarea name="action_taken_report" rows="4" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Course Committee Minutes (Summary)</label>
                            <textarea name="committee_minutes" rows="4" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none"></textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      </div>
';

$newJs = '
    // COURSE FILES LOGIC (Hierarchical)
    let cfDataTree = [];
    let currentCfId = null;
    let selectedBatch = null;
    let selectedSemester = null;

    function loadCourseFiles() {
      cfShowLevel(1);
      const grid = document.getElementById("cfLevel1");
      grid.innerHTML = \'<div class="col-span-full py-12 text-center text-slate-500 font-bold text-xs animate-pulse">Loading assigned courses...</div>\';
      
      fetch("/api/course-files/subjects")
        .then(res => res.json())
        .then(data => {
          if (data.status === "SUCCESS") {
            cfDataTree = data.batches;
            renderCfLevel1();
          }
        });
    }

    function renderCfLevel1() {
        const grid = document.getElementById("cfLevel1");
        if (cfDataTree.length === 0) {
            grid.innerHTML = \'<div class="col-span-full py-12 text-center text-slate-500 font-bold text-xs">No assigned courses found.</div>\';
            return;
        }
        grid.innerHTML = cfDataTree.map((b, idx) => `
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-amber-500/50 transition-colors cursor-pointer" onclick="cfSelectBatch(${idx})">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center border border-amber-500/20">
                        <span class="material-symbols-rounded text-amber-400 text-lg">folder</span>
                    </div>
                </div>
                <h4 class="text-sm font-bold text-slate-200 leading-tight mb-1">${b.batch_year} Admission</h4>
                <p class="text-[10px] text-slate-400 font-medium">${b.branch}</p>
                <div class="mt-4 flex items-center gap-2">
                    <div class="text-[10px] text-amber-400 font-bold flex items-center gap-1 group-hover:text-amber-300">
                        View Semesters <span class="material-symbols-rounded text-[14px]">arrow_forward</span>
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
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-amber-500/50 transition-colors cursor-pointer" onclick="cfSelectSemester(${sIdx})">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center border border-indigo-500/20">
                        <span class="material-symbols-rounded text-indigo-400 text-lg">calendar_month</span>
                    </div>
                    <span class="px-2 py-0.5 rounded border text-[9px] font-bold text-slate-400 bg-slate-800 border-slate-700">${s.courses.length} Courses</span>
                </div>
                <h4 class="text-sm font-bold text-slate-200 leading-tight mb-1">Semester ${s.semester}</h4>
                <div class="mt-4 flex items-center gap-2">
                    <div class="text-[10px] text-indigo-400 font-bold flex items-center gap-1 group-hover:text-indigo-300">
                        View Courses <span class="material-symbols-rounded text-[14px]">arrow_forward</span>
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
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-amber-500/50 transition-colors cursor-pointer" onclick="cfOpenBuilder(\'${c.course_file_id}\', \'${c.subject_name}\')">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center border border-amber-500/20">
                        <span class="material-symbols-rounded text-amber-400 text-lg">school</span>
                    </div>
                    <span class="px-2 py-0.5 rounded border text-[9px] font-bold uppercase tracking-wider ${c.status === \'Complete\' ? \'text-emerald-400 bg-emerald-500/10 border-emerald-500/20\' : \'text-amber-400 bg-amber-500/10 border-amber-500/20\'}">${c.status}</span>
                </div>
                <h4 class="text-sm font-bold text-slate-200 leading-tight mb-1">${c.subject_name}</h4>
                <p class="text-[10px] text-slate-400 font-medium">${c.subject_code}</p>
                <div class="mt-4 flex items-center gap-2">
                    <div class="text-[10px] text-amber-400 font-bold flex items-center gap-1 group-hover:text-amber-300">
                        Open File <span class="material-symbols-rounded text-[14px]">arrow_forward</span>
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
                }
            });
    }

    function cfShowLevel(lvl) {
        document.getElementById("cfBreadcrumbs").classList.remove("hidden");
        document.getElementById("cfLevel1").classList.add("hidden");
        document.getElementById("cfLevel2").classList.add("hidden");
        document.getElementById("cfLevel3").classList.add("hidden");
        document.getElementById("cfLevel4").classList.add("hidden");

        document.getElementById("cfCrumbBatch").classList.add("hidden");
        document.getElementById("cfCrumbSem").classList.add("hidden");
        document.getElementById("cfCrumbCourse").classList.add("hidden");
        document.getElementById("cfCrumbBatch").classList.remove("flex");
        document.getElementById("cfCrumbSem").classList.remove("flex");
        document.getElementById("cfCrumbCourse").classList.remove("flex");

        if (lvl === 1) {
            document.getElementById("cfBreadcrumbs").classList.add("hidden");
            document.getElementById("cfLevel1").classList.remove("hidden");
        } else if (lvl === 2) {
            document.getElementById("cfLevel2").classList.remove("hidden");
            document.getElementById("cfCrumbBatch").classList.remove("hidden");
            document.getElementById("cfCrumbBatch").classList.add("flex");
        } else if (lvl === 3) {
            document.getElementById("cfLevel3").classList.remove("hidden");
            document.getElementById("cfCrumbBatch").classList.remove("hidden");
            document.getElementById("cfCrumbBatch").classList.add("flex");
            document.getElementById("cfCrumbSem").classList.remove("hidden");
            document.getElementById("cfCrumbSem").classList.add("flex");
        } else if (lvl === 4) {
            document.getElementById("cfLevel4").classList.remove("hidden");
            document.getElementById("cfCrumbBatch").classList.remove("hidden");
            document.getElementById("cfCrumbBatch").classList.add("flex");
            document.getElementById("cfCrumbSem").classList.remove("hidden");
            document.getElementById("cfCrumbSem").classList.add("flex");
            document.getElementById("cfCrumbCourse").classList.remove("hidden");
            document.getElementById("cfCrumbCourse").classList.add("flex");
        }
    }

    function switchCfTab(tab) {
      ["A", "B", "C", "D"].forEach(t => {
        const btn = document.getElementById("tabBtn" + t);
        const panel = document.getElementById("cfTab" + t);
        if (t === tab) {
          btn.className = "flex-1 py-2 rounded-lg text-xs font-bold transition-premium bg-slate-800 text-white";
          panel.classList.remove("hidden");
        } else {
          btn.className = "flex-1 py-2 rounded-lg text-xs font-bold transition-premium text-slate-500 hover:text-slate-300";
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
        section_d: { action_taken_report: form.action_taken_report.value, committee_minutes: form.committee_minutes.value }
      };

      fetch(`/api/course-files/${currentCfId}`, {
        method: "POST",
        headers: getHeaders(),
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "SUCCESS") {
          showGlobalMessage("Progress saved successfully.");
        } else {
          showGlobalMessage(data.message, true);
        }
      });
    }

    function generateCourseFilePDF() {
      showGlobalMessage("PDF Generation triggered. It will open in a new tab.", false);
      window.open(`/api/course-files/${currentCfId}/pdf`, "_blank");
    }

    // End Course Files Logic
';

    // Regex replace the old HTML block
    $content = preg_replace('/<!-- PANEL: COURSE FILES -->.*<!-- End Panel Container -->/s', $newHtml . "\n    </div> <!-- End Panel Container -->", $content);
    if (strpos($content, $newHtml) === false) {
       $htmlPos1 = strpos($content, '<!-- PANEL: COURSE FILES -->');
       $htmlPos2 = strpos($content, '<!-- PANEL: SECURITY LOG -->');
       if ($htmlPos1 !== false && $htmlPos2 !== false) {
           $content = substr_replace($content, $newHtml . "\n      ", $htmlPos1, $htmlPos2 - $htmlPos1);
       }
    }
    
    // Regex replace JS block
    $content = preg_replace('/\/\/ COURSE FILES LOGIC.*\/\/ End Course Files Logic/s', $newJs, $content);
    
    file_put_contents($filePath, $content);
    echo "Replaced Course Files panel in " . basename($filePath) . "\n";
