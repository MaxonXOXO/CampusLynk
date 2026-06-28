<?php

$path = 'resources/views/course_files_dashboard.blade.php';
$content = file_get_contents($path);

// Add Sidebar Tab Button
$oldTabBtn = "<button type=\"button\" onclick=\"switchCfTab('D')\" id=\"tabBtnD\" class=\"w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition-premium text-slate-400 hover:bg-slate-800 hover:text-white border-l-4 border-transparent\">Section D: Attainment</button>";
$newTabBtn = $oldTabBtn . "\n                        <button type=\"button\" onclick=\"switchCfTab('Checklist')\" id=\"tabBtnChecklist\" class=\"w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition-premium text-slate-400 hover:bg-slate-800 hover:text-white border-l-4 border-transparent mt-4 bg-slate-800/50\">Master Checklist</button>";

if (strpos($content, "switchCfTab('Checklist')") === false) {
    $content = str_replace($oldTabBtn, $newTabBtn, $content);
}

// Add Checklist Panel
$oldPanel = "                            </div>\n                        </form>";
$newPanel = "                            </div>
                            
                            <!-- Master Checklist -->
                            <div id=\"cfTabChecklist\" class=\"hidden space-y-6 animate-fade-in\">
                                <div class=\"bg-indigo-500/10 border border-indigo-500/20 p-4 rounded-xl flex items-start gap-4 mb-4\">
                                    <div class=\"bg-indigo-500/20 text-indigo-400 p-2 rounded-lg shrink-0\"><span class=\"material-symbols-rounded\">checklist</span></div>
                                    <div>
                                        <h4 class=\"text-sm font-bold text-slate-200 mb-1\">File Integrity Checklist</h4>
                                        <p class=\"text-xs text-slate-400 leading-relaxed\">Verify each document before final PDF generation. You can check off items as you complete them.</p>
                                    </div>
                                </div>
                                <div class=\"overflow-x-auto rounded-xl border border-slate-800\">
                                    <table class=\"w-full text-left text-sm text-slate-300\">
                                        <thead class=\"text-xs text-slate-400 bg-slate-950 uppercase border-b border-slate-800\">
                                            <tr>
                                                <th class=\"px-4 py-3 w-16 text-center\">Doc No.</th>
                                                <th class=\"px-4 py-3\">Document Name</th>
                                                <th class=\"px-4 py-3 w-24 text-center\">Status</th>
                                                <th class=\"px-4 py-3 w-48\">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id=\"cfChecklistBody\" class=\"divide-y divide-slate-800/60\">
                                            <!-- Dynamically populated -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>";

if (strpos($content, 'id="cfTabChecklist"') === false) {
    $content = str_replace($oldPanel, $newPanel, $content);
}

// Add Checklist list to switchCfTab function
$oldTabsArray = "[\"A\", \"B\", \"C\", \"D\"].forEach(t => {";
$newTabsArray = "[\"A\", \"B\", \"C\", \"D\", \"Checklist\"].forEach(t => {";
if (strpos($content, "[\"A\", \"B\", \"C\", \"D\", \"Checklist\"]") === false) {
    $content = str_replace($oldTabsArray, $newTabsArray, $content);
}

// Ensure the checklist data is sent on save
$oldPayload = "section_d: { action_taken_report: form.action_taken_report.value, committee_minutes: form.committee_minutes.value }\n            };";
$newPayload = "section_d: { action_taken_report: form.action_taken_report.value, committee_minutes: form.committee_minutes.value },
                checklist: Array.from(document.querySelectorAll('.cf-check-item')).map(cb => ({
                    document_number: cb.dataset.docNo,
                    document_name: cb.dataset.docName,
                    is_checked: cb.checked,
                    remarks: document.getElementById('remark_' + cb.dataset.docNo).value
                }))
            };";
if (strpos($content, "checklist: Array.from") === false) {
    $content = str_replace($oldPayload, $newPayload, $content);
}

// Render Checklist data
$oldLoadData = "form.action_taken_report.value = cf.section_d?.action_taken_report || \"\";\n                        form.committee_minutes.value = cf.section_d?.committee_minutes || \"\";";
$newLoadData = "form.action_taken_report.value = cf.section_d?.action_taken_report || \"\";\n                        form.committee_minutes.value = cf.section_d?.committee_minutes || \"\";
                        renderChecklist(cf.documents || []);";
if (strpos($content, "renderChecklist(") === false) {
    $content = str_replace($oldLoadData, $newLoadData, $content);
}

// Add the JS rendering function
$jsFunction = "
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

        function renderChecklist(savedDocs) {
            const tbody = document.getElementById('cfChecklistBody');
            let html = '';
            defaultChecklist.forEach((name, idx) => {
                const docNo = idx + 1;
                const saved = savedDocs.find(d => d.document_number == docNo);
                const isChecked = saved && saved.is_checked ? 'checked' : '';
                const remarks = saved && saved.remarks ? saved.remarks : '';
                
                html += `
                    <tr class=\"hover:bg-slate-800/30 transition-colors\">
                        <td class=\"px-4 py-3 text-center font-black text-slate-500\">` + docNo + `</td>
                        <td class=\"px-4 py-3 font-medium text-slate-300\">` + name + `</td>
                        <td class=\"px-4 py-3 text-center\">
                            <input type=\"checkbox\" class=\"cf-check-item w-4 h-4 rounded border-slate-700 bg-slate-900 text-amber-500 focus:ring-amber-500/20\" data-doc-no=\"` + docNo + `\" data-doc-name=\"` + name + `\" ` + isChecked + `>
                        </td>
                        <td class=\"px-4 py-3\">
                            <input type=\"text\" id=\"remark_` + docNo + `\" value=\"` + remarks + `\" class=\"w-full bg-slate-950 border border-slate-800 rounded px-2 py-1 text-xs text-white focus:border-amber-500 outline-none placeholder-slate-600\" placeholder=\"...\">
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }
";

if (strpos($content, "defaultChecklist = [") === false) {
    $content = str_replace("</script>", $jsFunction . "\n    </script>", $content);
}

file_put_contents($path, $content);
echo "Checklist UI added.\n";
