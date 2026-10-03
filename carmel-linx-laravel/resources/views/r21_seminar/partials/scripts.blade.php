<script>
    // Client-side Student Data Cache
    const studentDataset = @json($studentResults);
    const studentData = studentDataset;
    const subjectId = {{ $batchSubject->id }};
    const currentLoggedInMobile = "{{ $activeStaff->mobile_no ?? Session::get('userId') }}";

    // Criteria Configuration for R-2021 Seminar (Clause 11.2.6 - 75 Marks Total)
    const criteriaConfig = {
        relevance: { max: 7.5, weight: 0.10, name: 'Relevance of Topic' },
        literature: { max: 7.5, weight: 0.10, name: 'Literature Survey' },
        presentation: { max: 37.5, weight: 0.50, name: 'Presentation Delivery' },
        interaction: { max: 7.5, weight: 0.10, name: 'Defense & Discussion' },
        report: { max: 7.5, weight: 0.10, name: 'Seminar Report' },
        attendance: { max: 7.5, weight: 0.10, name: 'Attendance & Regularity' }
    };

    let isRubricsExpanded = true;
    let userManuallyToggledRubrics = false;

    // Return to Parent / Dashboard
    function returnToParent() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = '/lecturer/dashboard';
        }
    }

    // Toggle Print Dropdown
    function togglePrintDropdown(event) {
        if (event) event.stopPropagation();
        const dd = document.getElementById('printDropdownMenu');
        if (dd) dd.classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        const dd = document.getElementById('printDropdownMenu');
        if (dd && !dd.classList.contains('hidden') && !e.target.closest('#btnPrintDropdown') && !e.target.closest('#printDropdownMenu')) {
            dd.classList.add('hidden');
        }
    });

    // Tab Switching Logic
    function switchTab(tabId) {
        // Support both 'tab-eval' and 'tab-evaluation' naming
        if (tabId === 'tab-eval') tabId = 'tab-evaluation';

        document.querySelectorAll('.tab-pane').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        const targetTab = document.getElementById(tabId);
        if (targetTab) {
            targetTab.classList.remove('hidden');
            targetTab.classList.add('block');
        }

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'border-blue-500', 'text-blue-600', 'text-blue-400');
            btn.classList.add('border-transparent', 'text-slate-500');
        });
        const activeBtn = document.getElementById('btn-' + tabId) || document.getElementById('btn-' + tabId.replace('tab-', ''));
        if (activeBtn) {
            activeBtn.classList.add('active', 'border-blue-500', 'text-blue-600');
            activeBtn.classList.remove('border-transparent', 'text-slate-500');
        }

        if (tabId === 'tab-attainment') {
            loadAttainmentData();
        }
    }

    // Batch Filtering
    function filterBatch(batch) {
        document.querySelectorAll('.batch-filter-btn').forEach(btn => {
            if (btn.dataset.batch === batch) {
                btn.classList.add('active', 'bg-blue-600', 'text-white');
                btn.classList.remove('text-slate-300', 'text-slate-600');
            } else {
                btn.classList.remove('active', 'bg-blue-600', 'text-white');
                btn.classList.add('text-slate-600');
            }
        });

        document.querySelectorAll('.student-row').forEach(row => {
            const rowBatch = row.dataset.batch;
            if (batch === 'all' || rowBatch === batch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function filterLabBatch(batch) {
        filterBatch(batch);
    }

    // Search Filtering
    function filterStudents() {
        const query = (document.getElementById('searchStudentInput')?.value || '').toLowerCase().trim();
        document.querySelectorAll('.student-row').forEach(row => {
            const reg = (row.dataset.reg || '').toLowerCase();
            const name = (row.dataset.name || '').toLowerCase();
            const roll = (row.dataset.roll || '').toLowerCase();
            if (reg.includes(query) || name.includes(query) || roll.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function onStudentSearch(query) {
        filterStudents();
    }

    function applyFilters() {
        filterStudents();
    }

    // ==========================================================
    // EVALUATION MODAL & LIVE RUBRICS
    // ==========================================================
    function openEvaluationModal(regNo) {
        try {
            const st = studentDataset.find(s => s.reg_no === regNo);
            if (!st) {
                console.error("Student record not found for regNo:", regNo);
                return;
            }

            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.value = val !== undefined && val !== null ? val : '';
            };
            const setText = (id, txt) => {
                const el = document.getElementById(id);
                if (el) el.textContent = txt;
            };

            setVal('evalRegNo', st.reg_no);
            setText('evalModalStudentName', st.name);
            setText('evalModalStudentMeta', `Reg: ${st.sbte_reg_no || st.reg_no} | Roll: ${st.roll_no || '—'}`);
            
            // Topic, Guide, Date inputs inside evaluation modal
            setVal('evalTopicInput', st.topic || '');
            setVal('evalTopic', st.topic || '');
            setVal('evalGuideSelect', st.guide_mobile_no || '');
            setVal('evalGuideMobile', st.guide_mobile_no || '');
            setVal('evalPresentationDateInput', st.presentation_date || '');

            // Attendance help
            const suggestedAtt = (st.suggested_att_mark !== undefined && st.suggested_att_mark !== null) ? st.suggested_att_mark : 7.5;
            setText('modalSuggestedAttVal', suggestedAtt);
            setText('modalAttHelpText', `Class attendance: ${st.att_percentage || 100}% -> Auto Suggested: ${suggestedAtt} / 7.5 M`);

            // Reset Assessor Selector to current logged-in user or first assessor
            const assessorSel = document.getElementById('evalAssessorMobile');
            if (assessorSel && currentLoggedInMobile) {
                assessorSel.value = currentLoggedInMobile;
            }

            // Render committee breakdown box if other faculty evaluated
            renderModalCommitteeBreakdown(st);

            // Populate rubric inputs for selected assessor
            populateRubricsForAssessor(st, (assessorSel ? assessorSel.value : currentLoggedInMobile));

            calculateLiveTotal();

            const modal = document.getElementById('evaluationModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        } catch (err) {
            console.error("Error opening evaluation modal:", err);
        }
    }

    function onAssessorChange(selectedAssessorMobile) {
        const regNo = document.getElementById('evalRegNo')?.value;
        const st = studentDataset.find(s => s.reg_no === regNo);
        if (!st) return;
        populateRubricsForAssessor(st, selectedAssessorMobile);
        calculateLiveTotal();
    }

    function populateRubricsForAssessor(st, assessorMobile) {
        const evalObj = (st.assessors_list || []).find(e => e.assessor_mobile === assessorMobile);
        for (let c in criteriaConfig) {
            let val = evalObj ? evalObj[c] : (c === 'attendance' ? (st.suggested_att_mark !== undefined ? st.suggested_att_mark : 7.5) : 0);
            const inp = document.getElementById(`input_${c}`);
            const rng = document.getElementById(`range_${c}`);
            if (inp) inp.value = val;
            if (rng) rng.value = val;
        }
    }

    function renderModalCommitteeBreakdown(st) {
        const box = document.getElementById('evalCommitteeBreakdownBox');
        const list = document.getElementById('evalBreakdownList');
        const avgText = document.getElementById('evalBreakdownAvgText');
        if (!box || !list || !avgText) return;

        if (!st.assessors_list || st.assessors_list.length === 0) {
            box.classList.add('hidden');
            return;
        }

        box.classList.remove('hidden');
        avgText.textContent = `Committee Average: ${parseFloat(st.final_score || 0).toFixed(1)} / 75 (Grade ${st.letter_grade || '—'})`;

        let html = '';
        st.assessors_list.forEach((ev) => {
            html += `
                <div class="p-2.5 rounded-lg bg-white border border-slate-200 flex items-center justify-between shadow-xs">
                    <div>
                        <span class="font-bold text-slate-800 text-xs">${ev.assessor_name || ev.assessor_mobile}</span>
                        <span class="text-slate-500 text-[10px] ml-1">(${ev.designation || 'Faculty'})</span>
                        <div class="text-[10px] text-slate-500 mt-0.5">
                            Rel: ${ev.relevance} | Lit: ${ev.literature} | Pres: ${ev.presentation} | Disc: ${ev.interaction} | Rep: ${ev.report} | Att: ${ev.attendance}
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-blue-700 font-mono">${parseFloat(ev.total_score).toFixed(1)} M</span>
                    </div>
                </div>
            `;
        });
        list.innerHTML = html;
    }

    function closeEvaluationModal() {
        const modal = document.getElementById('evaluationModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function syncEvalSlider(c) {
        const inp = document.getElementById(`input_${c}`);
        const rng = document.getElementById(`range_${c}`);
        let val = parseFloat(inp?.value || 0) || 0;
        if (val > criteriaConfig[c].max) val = criteriaConfig[c].max;
        if (val < 0) val = 0;
        if (inp) inp.value = val;
        if (rng) rng.value = val;
        calculateLiveTotal();
    }

    function syncEvalInput(c) {
        const inp = document.getElementById(`input_${c}`);
        const rng = document.getElementById(`range_${c}`);
        if (inp && rng) inp.value = rng.value;
        calculateLiveTotal();
    }

    function applySuggestedAttendance() {
        const regNo = document.getElementById('evalRegNo')?.value;
        const st = studentDataset.find(s => s.reg_no === regNo);
        if (!st) return;
        const val = st.suggested_att_mark !== undefined ? st.suggested_att_mark : 7.5;
        const inp = document.getElementById('input_attendance');
        const rng = document.getElementById('range_attendance');
        if (inp) inp.value = val;
        if (rng) rng.value = val;
        calculateLiveTotal();
    }

    function calculateLiveTotal() {
        let total = 0;
        for (let c in criteriaConfig) {
            total += parseFloat(document.getElementById(`input_${c}`)?.value || 0) || 0;
        }
        if (total > 75.0) total = 75.0;

        const liveTotalEl = document.getElementById('evalLiveTotal');
        if (liveTotalEl) {
            liveTotalEl.innerHTML = `${total.toFixed(1)} <span class="text-xs text-slate-400 font-normal">/ 75.0</span>`;
        }

        // Calculate SBTE Grade
        const pct = (total / 75.0) * 100.0;
        let grade = 'F';
        let color = 'text-rose-600';
        if (pct >= 90) { grade = 'S (Outstanding)'; color = 'text-amber-600'; }
        else if (pct >= 80) { grade = 'A (Excellent)'; color = 'text-blue-600'; }
        else if (pct >= 70) { grade = 'B (Very Good)'; color = 'text-sky-600'; }
        else if (pct >= 60) { grade = 'C (Good)'; color = 'text-teal-600'; }
        else if (pct >= 50) { grade = 'D (Satisfactory)'; color = 'text-emerald-600'; }
        else if (pct >= 40) { grade = 'E (Pass)'; color = 'text-slate-600'; }
        else { grade = 'F (Failed)'; color = 'text-rose-600'; }

        const gradeEl = document.getElementById('evalLiveGrade');
        if (gradeEl) {
            gradeEl.textContent = grade;
            gradeEl.className = `text-xs sm:text-sm font-extrabold mt-0.5 ${color}`;
        }

        const headerScore = document.getElementById('evalHeaderScoreVal');
        if (headerScore) headerScore.textContent = total.toFixed(1);
    }

    function calculateLiveScore() {
        calculateLiveTotal();
    }

    // Submit Evaluation AJAX
    async function submitEvaluationForm(e) {
        if (e) e.preventDefault();
        const btn = document.getElementById('btnSaveEval') || document.getElementById('btnSaveEvaluation');
        if (btn) btn.disabled = true;

        const regNo = document.getElementById('evalRegNo')?.value;
        const payload = {
            reg_no: regNo,
            relevance: parseFloat(document.getElementById('input_relevance')?.value || 0),
            literature: parseFloat(document.getElementById('input_literature')?.value || 0),
            presentation: parseFloat(document.getElementById('input_presentation')?.value || 0),
            interaction: parseFloat(document.getElementById('input_interaction')?.value || 0),
            report: parseFloat(document.getElementById('input_report')?.value || 0),
            attendance: parseFloat(document.getElementById('input_attendance')?.value || 0),
            topic: document.getElementById('evalTopicInput')?.value || '',
            assessor_mobile_no: document.getElementById('evalAssessorMobile')?.value || '',
            guide_mobile_no: document.getElementById('evalGuideSelect')?.value || '',
            presentation_date: document.getElementById('evalPresentationDateInput')?.value || ''
        };

        try {
            const resp = await fetch(`/r21/classroom/seminar/${subjectId}/evaluate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const result = await resp.json();
            if (resp.ok && result.status === 'SUCCESS') {
                const data = result.data;
                const rNo = data.reg_no;

                // Update cached student in dataset
                const st = studentDataset.find(s => s.reg_no === rNo);
                if (st) {
                    st.is_completed = true;
                    st.final_score = data.average_score;
                    st.letter_grade = data.letter_grade;
                    st.grade_point = data.grade_point;
                    st.result = data.result;
                    st.my_evaluation = { total_score: data.my_total };
                    st.eval_count = data.eval_count;
                    st.assessors_list = data.assessors_list;
                    if (data.topic) st.topic = data.topic;
                    if (data.guide_name) st.guide_name = data.guide_name;
                    if (data.presentation_date) st.presentation_date = data.presentation_date;
                    if (data.presentation_date_formatted) st.presentation_date_formatted = data.presentation_date_formatted;
                }

                // Update DOM table cells in Evaluation tab
                const myScoreCell = document.querySelector(`.my-score-${rNo}`);
                if (myScoreCell) myScoreCell.innerText = parseFloat(data.my_total).toFixed(1);

                const commAvgCell = document.querySelector(`.comm-avg-${rNo}`);
                if (commAvgCell) {
                    commAvgCell.innerText = parseFloat(data.average_score).toFixed(1);
                    commAvgCell.classList.add('font-bold', 'text-blue-600', 'underline', 'cursor-pointer');
                    commAvgCell.onclick = () => showFacultyBreakdown(rNo);
                }

                const gradeCell = document.querySelector(`.grade-cell-${rNo}`);
                if (gradeCell) {
                    const badgeClass = data.letter_grade === 'S' || data.letter_grade === 'A' 
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200' 
                        : (['B', 'C', 'D'].includes(data.letter_grade) ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-rose-50 text-rose-700 border-rose-200');
                    gradeCell.innerHTML = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold ${badgeClass} border">${data.letter_grade}</span>`;
                }

                const evalRow = document.getElementById(`row-eval-${rNo}`);
                if (evalRow) {
                    if (data.topic && evalRow.querySelector('.col-row-topic')) {
                        evalRow.querySelector('.col-row-topic').textContent = data.topic;
                    }
                    if (data.guide_name && evalRow.querySelector('.col-row-guide')) {
                        evalRow.querySelector('.col-row-guide').textContent = 'Guide: ' + data.guide_name;
                        evalRow.querySelector('.col-row-guide').classList.remove('hidden');
                    }
                }

                // Update row in Schedule tab if exists
                const schedRow = document.getElementById(`row-sched-${rNo}`);
                if (schedRow) {
                    if (data.topic && schedRow.querySelector('.col-sched-topic')) {
                        schedRow.querySelector('.col-sched-topic').textContent = data.topic;
                    }
                    if (data.guide_name && schedRow.querySelector('.col-sched-guide')) {
                        schedRow.querySelector('.col-sched-guide').textContent = data.guide_name;
                    }
                    if (data.presentation_date_formatted && schedRow.querySelector('.col-sched-date')) {
                        schedRow.querySelector('.col-sched-date').innerHTML = `<div class="flex items-center gap-1.5 text-sky-600 font-mono"><span>${data.presentation_date_formatted}</span></div>`;
                    }
                }

                // Update Stats Strip
                if (data.completed_count) {
                    const statCompleted = document.getElementById('statCompletedCount');
                    if (statCompleted) statCompleted.innerText = data.completed_count;
                    const statTotal = document.getElementById('statTotalCount');
                    const statPending = document.getElementById('statPendingCount');
                    if (statTotal && statPending) {
                        const total = parseInt(statTotal.innerText) || 0;
                        statPending.innerText = Math.max(0, total - data.completed_count);
                    }
                }

                closeEvaluationModal();
            } else {
                alert('Evaluation save failed: ' + (result.message || 'Validation error'));
            }
        } catch (err) {
            console.error(err);
            alert('An unexpected error occurred while saving evaluation.');
        } finally {
            if (btn) btn.disabled = false;
        }
    }

    function submitEvaluation(e) {
        submitEvaluationForm(e);
    }

    // ==========================================================
    // FACULTY BREAKDOWN MODAL
    // ==========================================================
    function showFacultyBreakdown(regNo) {
        const st = studentDataset.find(s => s.reg_no === regNo);
        if (!st) return;

        const nameEl = document.getElementById('breakdownModalStudentName') || document.getElementById('breakdownStudentName');
        if (nameEl) nameEl.textContent = st.name;

        const metaEl = document.getElementById('breakdownModalStudentMeta') || document.getElementById('breakdownStudentInfo');
        if (metaEl) metaEl.textContent = `Reg: ${st.sbte_reg_no || st.reg_no} | Roll: ${st.roll_no || '—'}`;

        const avgEl = document.getElementById('breakdownFinalAvg') || document.getElementById('breakdownFinalScore');
        if (avgEl) avgEl.innerHTML = `${parseFloat(st.final_score || 0).toFixed(1)} <span class="text-xs text-slate-400 font-normal">/ 75.0</span>`;

        const gradeEl = document.getElementById('breakdownFinalGrade');
        if (gradeEl) gradeEl.textContent = `Grade ${st.letter_grade || '—'} (${st.result || 'P'})`;

        const container = document.getElementById('breakdownCardsContainer') || document.getElementById('breakdownTableContainer');
        if (!container) return;

        if (!st.assessors_list || st.assessors_list.length === 0) {
            container.innerHTML = `<div class="p-4 text-center text-slate-400 bg-slate-50 border border-slate-200 rounded-xl">No assessor marks recorded yet.</div>`;
        } else {
            let html = '';
            st.assessors_list.forEach((ev) => {
                html += `
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-bold text-slate-900 text-xs">${ev.assessor_name || ev.assessor_mobile}</span>
                                <span class="text-slate-500 text-[10px] ml-1">(${ev.designation || 'Faculty Assessor'})</span>
                            </div>
                            <span class="font-bold text-sm text-blue-700 font-mono">${parseFloat(ev.total_score).toFixed(1)} / 75</span>
                        </div>
                        <div class="grid grid-cols-3 sm:grid-cols-6 gap-1.5 text-[11px] text-center pt-2 border-t border-slate-200">
                            <div class="bg-white p-1.5 rounded-lg border border-slate-200"><span class="text-slate-500 block text-[9px] uppercase">Relevance</span><span class="font-bold text-slate-800">${ev.relevance}</span></div>
                            <div class="bg-white p-1.5 rounded-lg border border-slate-200"><span class="text-slate-500 block text-[9px] uppercase">Literature</span><span class="font-bold text-slate-800">${ev.literature}</span></div>
                            <div class="bg-white p-1.5 rounded-lg border border-slate-200"><span class="text-slate-500 block text-[9px] uppercase">Presentation</span><span class="font-bold text-slate-800">${ev.presentation}</span></div>
                            <div class="bg-white p-1.5 rounded-lg border border-slate-200"><span class="text-slate-500 block text-[9px] uppercase">Discussion</span><span class="font-bold text-slate-800">${ev.interaction}</span></div>
                            <div class="bg-white p-1.5 rounded-lg border border-slate-200"><span class="text-slate-500 block text-[9px] uppercase">Report</span><span class="font-bold text-slate-800">${ev.report}</span></div>
                            <div class="bg-white p-1.5 rounded-lg border border-slate-200"><span class="text-slate-500 block text-[9px] uppercase">Attendance</span><span class="font-bold text-slate-800">${ev.attendance}</span></div>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        const modal = document.getElementById('facultyBreakdownModal') || document.getElementById('breakdownModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function openBreakdownModal(regNo) {
        showFacultyBreakdown(regNo);
    }

    function closeFacultyBreakdownModal() {
        const modal = document.getElementById('facultyBreakdownModal') || document.getElementById('breakdownModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // ==========================================================
    // SCHEDULE MODAL
    // ==========================================================
    function openScheduleModal(regNo) {
        const student = studentDataset.find(s => s.reg_no === regNo);
        if (!student) return;

        const regInput = document.getElementById('schedRegNo');
        if (regInput) regInput.value = student.reg_no;

        const nameEl = document.getElementById('schedModalStudentName') || document.getElementById('schedStudentName');
        if (nameEl) nameEl.innerText = student.name;

        const metaEl = document.getElementById('schedModalStudentMeta');
        if (metaEl) metaEl.innerText = `Reg: ${student.sbte_reg_no || student.reg_no} | Roll: ${student.roll_no || '—'}`;

        const topicInput = document.getElementById('schedTopic');
        if (topicInput) topicInput.value = student.topic || '';

        const dateInput = document.getElementById('schedDate');
        if (dateInput) dateInput.value = student.presentation_date || '';

        const guideSelect = document.getElementById('schedGuideMobile');
        if (guideSelect) guideSelect.value = student.guide_mobile_no || '';

        const modal = document.getElementById('scheduleModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeScheduleModal() {
        const modal = document.getElementById('scheduleModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    async function submitScheduleForm(e) {
        if (e) e.preventDefault();
        const btn = document.getElementById('btnSaveSchedule');
        if (btn) btn.disabled = true;

        const regNo = document.getElementById('schedRegNo')?.value;
        const payload = {
            reg_no: regNo,
            topic: document.getElementById('schedTopic')?.value || '',
            presentation_date: document.getElementById('schedDate')?.value || '',
            guide_mobile_no: document.getElementById('schedGuideMobile')?.value || ''
        };

        try {
            const resp = await fetch(`/r21/classroom/seminar/${subjectId}/schedule`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const result = await resp.json();
            if (resp.ok && result.status === 'SUCCESS') {
                const data = result.data;
                const rNo = data.reg_no;

                const student = studentDataset.find(s => s.reg_no === rNo);
                if (student) {
                    student.topic = data.topic;
                    student.presentation_date = data.presentation_date;
                    student.presentation_date_formatted = data.presentation_date_formatted;
                    student.guide_name = data.guide_name;
                    student.guide_mobile_no = data.guide_mobile_no;
                }

                // Update Schedule Tab DOM cells
                const dateCell = document.querySelector(`.sched-date-${rNo}`);
                if (dateCell && data.presentation_date_formatted) {
                    dateCell.innerHTML = `<div class="flex items-center gap-1.5 text-sky-600 font-mono"><span>${data.presentation_date_formatted}</span></div>`;
                }

                const topicCell = document.querySelector(`.sched-topic-${rNo}`);
                if (topicCell) topicCell.innerText = data.topic;

                const guideCell = document.querySelector(`.sched-guide-${rNo}`);
                if (guideCell) guideCell.innerText = data.guide_name;

                // Also update Evaluation Tab DOM row
                const evalRow = document.getElementById(`row-eval-${rNo}`);
                if (evalRow) {
                    if (data.topic && evalRow.querySelector('.col-row-topic')) {
                        evalRow.querySelector('.col-row-topic').textContent = data.topic;
                    }
                    if (data.guide_name && evalRow.querySelector('.col-row-guide')) {
                        evalRow.querySelector('.col-row-guide').textContent = 'Guide: ' + data.guide_name;
                        evalRow.querySelector('.col-row-guide').classList.remove('hidden');
                    }
                }

                closeScheduleModal();
            } else {
                alert('Schedule update failed: ' + (result.message || 'Validation error'));
            }
        } catch (err) {
            console.error(err);
            alert('An unexpected error occurred while updating schedule.');
        } finally {
            if (btn) btn.disabled = false;
        }
    }

    function submitSchedule(e) {
        submitScheduleForm(e);
    }

    // ==========================================================
    // SYLLABUS MODAL
    // ==========================================================
    function openSyllabusModal() {
        const modal = document.getElementById('syllabusModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeSyllabusModal() {
        const modal = document.getElementById('syllabusModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    async function submitSyllabusForm(e) {
        if (e) e.preventDefault();
        const btn = document.getElementById('btnUploadSyllabus');
        if (btn) btn.disabled = true;

        const form = document.getElementById('syllabusForm');
        const formData = new FormData(form);

        try {
            const resp = await fetch(`/r21/classroom/seminar/${subjectId}/syllabus`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const result = await resp.json();
            if (resp.ok && result.status === 'SUCCESS') {
                alert('Syllabus PDF uploaded successfully.');
                closeSyllabusModal();
                window.location.reload();
            } else {
                alert('Syllabus upload failed: ' + (result.message || 'Validation error'));
            }
        } catch (err) {
            console.error(err);
            alert('An unexpected error occurred while uploading syllabus.');
        } finally {
            if (btn) btn.disabled = false;
        }
    }

    function submitSyllabus(e) {
        submitSyllabusForm(e);
    }

    // ==========================================================
    // ATTAINMENT & SURVEY
    // ==========================================================
    async function loadAttainmentData() {
        try {
            const resp = await fetch(`/r21/classroom/seminar/${subjectId}/attainment-summary`, {
                headers: { 'Accept': 'application/json' }
            });
            const result = await resp.json();
            if (resp.ok && result.status === 'SUCCESS') {
                renderSeminarAttainment(result.data);
            }
        } catch (err) {
            console.error('Error loading attainment data:', err);
        }
    }

    function renderSeminarAttainment(data) {
        if (!data) return;
        const summaryText = document.getElementById('seminarAttainmentSummaryText');
        if (summaryText) {
            summaryText.innerHTML = `Direct CIE: <strong class="text-emerald-600">${data.average_direct || '0.00'}</strong> | Indirect Exit: <strong class="text-purple-600">${data.average_indirect || '0.00'}</strong> | Overall: <strong class="text-slate-900">${data.average_overall || '0.00'}</strong> / 3.0`;
        }

        const tbody = document.getElementById('seminarAttainmentTableBody');
        if (!tbody) return;
        tbody.innerHTML = '';

        const matrix = data.matrix || [];
        if (matrix.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-6 text-slate-400">No attainment data available. Complete evaluations first.</td></tr>';
            return;
        }

        matrix.forEach(row => {
            const tr = document.createElement('tr');
            tr.className = 'border-b border-slate-100 hover:bg-slate-50 transition';
            tr.innerHTML = `
                <td class="font-bold text-sky-700 font-mono py-3 px-4">${row.co_tag}</td>
                <td class="text-slate-700 text-xs py-3 px-4">${row.description}</td>
                <td class="text-center font-mono font-bold text-emerald-700 py-3 px-4">${row.cie_level}</td>
                <td class="text-center font-mono font-bold text-purple-700 py-3 px-4">${row.indirect_attainment}</td>
                <td class="text-center font-mono font-bold bg-purple-50 text-purple-900 text-sm py-3 px-4">${row.overall_attainment}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Course Exit Survey Lifecycle for Seminar
    let activeSeminarSurveyId = null;
    let activeSeminarSurveyUrl = null;

    async function loadSeminarSurveyData() {
        try {
            const resp = await fetch(`/api/classroom/${subjectId}/course-exit/status`, {
                headers: { 'Accept': 'application/json' }
            });
            const res = await resp.json();
            if (res && res.survey) {
                updateSeminarSurveyUi(res.survey);
            }
        } catch (err) {
            console.log('Course exit survey check:', err);
        }
    }

    function updateSeminarSurveyUi(survey) {
        if (!survey) return;
        activeSeminarSurveyId = survey.id;
        activeSeminarSurveyUrl = survey.student_url;

        const badge = document.getElementById('seminarSurveyStatusBadge');
        const btnOpen = document.getElementById('btnOpenSeminarExitSurvey');
        const btnClose = document.getElementById('btnCloseSeminarExitSurvey');
        const btnCopy = document.getElementById('btnCopySeminarSurveyLink');
        const btnTest = document.getElementById('btnTestSeminarSurveyLink');
        const urlInput = document.getElementById('seminarSurveyUrlInput');
        const statText = document.getElementById('seminarSurveyResponseStat');
        const pctText = document.getElementById('seminarSurveyResponsePct');
        const progBar = document.getElementById('seminarSurveyProgressBar');

        const responded = survey.responded_count || 0;
        const total = survey.total_students || {{ $totalStudents > 0 ? $totalStudents : 1 }};
        const pct = Math.round((responded / total) * 100);

        if (statText) statText.innerText = `${responded} / ${total} Submitted`;
        if (pctText) pctText.innerText = `${pct}%`;
        if (progBar) progBar.style.width = `${pct}%`;

        if (survey.status === 'Active' || survey.is_active) {
            if (badge) {
                badge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200';
                badge.innerText = 'Survey Active';
            }
            if (btnOpen) btnOpen.classList.add('hidden');
            if (btnClose) btnClose.classList.remove('hidden');
            if (btnCopy) btnCopy.classList.remove('hidden');
            if (btnTest) {
                btnTest.classList.remove('hidden');
                btnTest.href = survey.student_url || `/student/course-exit/${subjectId}`;
            }
            if (urlInput) urlInput.value = survey.student_url || `${window.location.origin}/student/course-exit/${subjectId}`;
        } else {
            if (badge) {
                badge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200';
                badge.innerText = 'Survey Inactive / Closed';
            }
            if (btnOpen) btnOpen.classList.remove('hidden');
            if (btnClose) btnClose.classList.add('hidden');
            if (btnCopy) btnCopy.classList.add('hidden');
            if (btnTest) btnTest.classList.add('hidden');
            if (urlInput) urlInput.value = 'Initiate survey to generate student link';
        }
    }

    async function initiateSeminarExitSurvey() {
        if (!confirm('Open End Semester Course Exit Survey for all enrolled Seminar students?')) return;

        try {
            const resp = await fetch(`/api/classroom/${subjectId}/course-exit/initiate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const res = await resp.json();
            if (res.status === 'SUCCESS') {
                alert('Course Exit Survey initiated successfully! Students can now submit their feedback.');
                loadSeminarSurveyData();
            } else {
                alert('Notice: ' + (res.message || 'Could not initiate survey'));
                loadSeminarSurveyData();
            }
        } catch (err) {
            alert('Network error while initiating survey.');
        }
    }

    async function closeSeminarExitSurvey() {
        if (!confirm('Are you sure you want to close and lock this survey? Student submissions will be locked.')) return;

        try {
            const resp = await fetch(`/api/classroom/${subjectId}/course-exit/close`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const res = await resp.json();
            if (res.status === 'SUCCESS') {
                alert('Course Exit Survey locked and finalized successfully.');
                loadSeminarSurveyData();
            } else {
                alert('Error: ' + (res.message || 'Could not close survey'));
            }
        } catch (err) {
            alert('Network error while closing survey.');
        }
    }

    function copySeminarSurveyLink() {
        const urlInput = document.getElementById('seminarSurveyUrlInput');
        if (!urlInput || !urlInput.value || urlInput.value.indexOf('http') === -1) {
            alert('No active survey URL to copy.');
            return;
        }
        navigator.clipboard.writeText(urlInput.value).then(() => {
            alert('Survey Link copied to clipboard!\n\n' + urlInput.value);
        }).catch(() => {
            urlInput.select();
            document.execCommand('copy');
            alert('Survey Link copied to clipboard!');
        });
    }

    // ==========================================================
    // RUBRICS EXPAND / COLLAPSE
    // ==========================================================
    function toggleRubricColumns() {
        isRubricsExpanded = !isRubricsExpanded;
        userManuallyToggledRubrics = true;
        applyRubricVisibility();
    }

    function applyRubricVisibility() {
        const cols = document.querySelectorAll('.rubric-col');
        const txt = document.getElementById('rubricToggleText');
        const btn = document.getElementById('btnToggleRubrics');

        cols.forEach(el => {
            if (isRubricsExpanded) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        });

        if (txt) {
            txt.textContent = isRubricsExpanded ? 'Compact View' : 'Full Rubrics';
        }
        if (btn) {
            if (isRubricsExpanded) {
                btn.classList.add('border-blue-500', 'text-blue-600');
                btn.classList.remove('border-slate-300', 'text-slate-600');
            } else {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-slate-300', 'text-slate-600');
            }
        }
    }

    // Escape Key Modal Dismissal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
            const evalModal = document.getElementById('evaluationModal');
            if (evalModal && !evalModal.classList.contains('hidden')) {
                closeEvaluationModal();
                return;
            }
            const schedModal = document.getElementById('scheduleModal');
            if (schedModal && !schedModal.classList.contains('hidden')) {
                closeScheduleModal();
                return;
            }
            const bkModal = document.getElementById('facultyBreakdownModal') || document.getElementById('breakdownModal');
            if (bkModal && !bkModal.classList.contains('hidden')) {
                closeFacultyBreakdownModal();
                return;
            }
            const sylModal = document.getElementById('syllabusModal');
            if (sylModal && !sylModal.classList.contains('hidden')) {
                closeSyllabusModal();
                return;
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        loadSeminarSurveyData();
    });
</script>
