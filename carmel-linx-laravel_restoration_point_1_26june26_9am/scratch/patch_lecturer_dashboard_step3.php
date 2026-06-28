<?php

$filePath = "c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\lecturer_dashboard.blade.php";
$content = file_get_contents($filePath);

// Check if panelCourseFiles already exists
if (strpos($content, 'id="panelCourseFiles"') === false) {
    // Add the panel before the closing </div> of the panel container
    $panelHTML = '
      <!-- PANEL: COURSE FILES -->
      <div id="panelCourseFiles" class="hidden space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center justify-between">
          <div>
            <h3 class="text-sm font-black text-slate-200 flex items-center gap-2 mt-1">
              <span class="material-symbols-rounded text-amber-400 text-lg">folder_special</span> Course Files (NBA/SBTE)
            </h3>
            <p class="text-[10px] text-slate-400 font-medium">Select an assigned course to compile and generate its physical course file.</p>
          </div>
        </div>

        <!-- Selection Grid -->
        <div id="cfSelectionGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in">
          <div class="col-span-full py-12 text-center text-slate-500 font-bold text-xs animate-pulse">Loading assigned courses...</div>
        </div>

        <!-- Course File Builder (Hidden initially) -->
        <div id="cfBuilder" class="hidden space-y-6 animate-fade-in">
          <div class="flex items-center justify-between">
            <button onclick="closeCourseFileBuilder()" class="text-[10px] font-bold text-slate-400 hover:text-white uppercase tracking-wider flex items-center gap-1 transition-premium cursor-pointer">
              <span class="material-symbols-rounded text-sm">arrow_back</span> Back to Selection
            </button>
            <div class="flex gap-2">
              <button onclick="saveCourseFileProgress()" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-1.5 rounded-lg text-xs font-bold transition-premium">Save Progress</button>
              <button onclick="generateCourseFilePDF()" class="bg-amber-600 hover:bg-amber-500 text-white px-4 py-1.5 rounded-lg text-xs font-bold transition-premium flex items-center gap-1"><span class="material-symbols-rounded text-sm">picture_as_pdf</span> Generate PDF</button>
            </div>
          </div>

          <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 flex gap-4">
            <div class="flex-1">
              <h2 id="cfSubjectTitle" class="text-lg font-black text-amber-400">Subject Name</h2>
              <p id="cfBatchInfo" class="text-xs text-slate-400">Batch Info</p>
            </div>
            <div class="text-right">
              <span id="cfStatusBadge" class="px-2 py-0.5 rounded border text-[9px] font-bold uppercase tracking-wider text-amber-400 bg-amber-500/10 border-amber-500/20">DRAFT</span>
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
                  <textarea name="gaps_identified" rows="2" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none"></textarea>
                </div>
                <div>
                  <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Bridge Topics</label>
                  <textarea name="bridge_topics" rows="2" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none"></textarea>
                </div>
              </div>
            </div>

            <!-- Section B -->
            <div id="cfTabB" class="hidden space-y-4">
              <div class="grid md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">NPTEL / Swayam Links</label>
                  <textarea name="nptel_swayam_links" rows="3" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none" placeholder="Paste external course URLs..."></textarea>
                </div>
                <div>
                  <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Other Reference Materials</label>
                  <textarea name="other_resources" rows="3" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none" placeholder="Any physical books or hand-outs..."></textarea>
                </div>
              </div>
            </div>

            <!-- Section C -->
            <div id="cfTabC" class="hidden space-y-4">
              <div class="bg-slate-950/40 border border-slate-800/60 p-4 rounded-xl">
                <h4 class="text-xs font-bold text-slate-200 mb-2">Auto-Pulled Assessments</h4>
                <p class="text-[10px] text-slate-400">Test Questions, Assignment Questions, and Mark Sheets will be automatically extracted.</p>
                <div class="mt-2 text-[10px] font-bold text-amber-400 border border-amber-500/30 bg-amber-500/10 p-2 rounded-lg">
                  Placeholders will be generated in the PDF for you to physically attach: End-Semester Question Paper, and Sample Student Answer Scripts (Best/Avg/Low).
                </div>
              </div>
              <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Evaluation Scheme</label>
                <textarea name="evaluation_scheme" rows="2" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none" placeholder="e.g., Internals 40%, End Sem 60%..."></textarea>
              </div>
            </div>

            <!-- Section D -->
            <div id="cfTabD" class="hidden space-y-4">
              <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Action Taken Report (if COs not fully attained)</label>
                <textarea name="action_taken_report" rows="3" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none"></textarea>
              </div>
              <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Course Committee Minutes (Summary)</label>
                <textarea name="committee_minutes" rows="3" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
';
    
    // Find the end of the panel container
    $pos = strrpos($content, '    </div> <!-- End Panel Container -->');
    if ($pos !== false) {
        $content = substr_replace($content, $panelHTML . "\n", $pos, 0);
    } else {
        // Fallback: Just insert before the last </div></main>
        $content = str_replace('</main>', "    </div>\n" . $panelHTML . "\n</main>", $content);
    }

    // Add JavaScript to handle this
    $jsHTML = '
    // COURSE FILES LOGIC
    let currentCfId = null;

    function loadCourseFiles() {
      const grid = document.getElementById("cfSelectionGrid");
      grid.innerHTML = \'<div class="col-span-full py-12 text-center text-slate-500 font-bold text-xs animate-pulse">Loading assigned courses...</div>\';
      
      fetch("/api/course-files/subjects")
        .then(res => res.json())
        .then(data => {
          if (data.status === "SUCCESS") {
            if (data.courses.length === 0) {
              grid.innerHTML = \'<div class="col-span-full py-12 text-center text-slate-500 font-bold text-xs">No assigned courses found for this academic year.</div>\';
              return;
            }
            grid.innerHTML = data.courses.map(c => `
              <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-amber-500/50 transition-colors cursor-pointer" onclick="openCourseFileBuilder(\'${c.course_file_id}\', \'${c.subject_name}\', \'${c.batch_year} - ${c.branch} - Sem ${c.semester}\')">
                <div class="flex justify-between items-start mb-3">
                  <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center border border-amber-500/20">
                    <span class="material-symbols-rounded text-amber-400 text-lg">school</span>
                  </div>
                  <span class="px-2 py-0.5 rounded border text-[9px] font-bold uppercase tracking-wider ${c.status === \'Complete\' ? \'text-emerald-400 bg-emerald-500/10 border-emerald-500/20\' : \'text-amber-400 bg-amber-500/10 border-amber-500/20\'}">${c.status}</span>
                </div>
                <h4 class="text-sm font-bold text-slate-200 leading-tight mb-1">${c.subject_name}</h4>
                <p class="text-[10px] text-slate-400 font-medium">${c.batch_year} • ${c.branch} • Sem ${c.semester}</p>
                <div class="mt-4 flex items-center gap-2">
                  <div class="text-[10px] text-amber-400 font-bold flex items-center gap-1 group-hover:text-amber-300">
                    Open Builder <span class="material-symbols-rounded text-[14px]">arrow_forward</span>
                  </div>
                </div>
              </div>
            `).join("");
          }
        });
    }

    function openCourseFileBuilder(id, subjectName, batchInfo) {
      currentCfId = id;
      document.getElementById("cfSelectionGrid").classList.add("hidden");
      document.getElementById("cfBuilder").classList.remove("hidden");
      document.getElementById("cfSubjectTitle").innerText = subjectName;
      document.getElementById("cfBatchInfo").innerText = batchInfo;
      
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

    function closeCourseFileBuilder() {
      currentCfId = null;
      document.getElementById("cfBuilder").classList.add("hidden");
      document.getElementById("cfSelectionGrid").classList.remove("hidden");
      loadCourseFiles();
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

    $content = str_replace('</script>', $jsHTML . "\n</script>", $content);

    // Modify switchPanel to load course files
    $oldSwitch = "      if (panelId === 'dashboard') {";
    $newSwitch = "      if (panelId === 'courseFiles') {
        loadCourseFiles();
      } else if (panelId === 'dashboard') {";
    $content = str_replace($oldSwitch, $newSwitch, $content);

    file_put_contents($filePath, $content);
    echo "Injected Panel Course Files to lecturer_dashboard.blade.php\n";
} else {
    echo "Panel Course Files already exists.\n";
}
