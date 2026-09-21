<script>
    // Client-side Student Data Cache
    const studentData = @json($studentResults);
    const subjectId = {{ $batchSubject->id }};

    // Tab Switching Logic
    function switchTab(tabId) {
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
            btn.classList.remove('active', 'border-blue-500', 'text-blue-400');
            btn.classList.add('border-transparent', 'text-slate-400');
        });
        const activeBtn = document.getElementById('btn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.add('active', 'border-blue-500', 'text-blue-400');
            activeBtn.classList.remove('border-transparent', 'text-slate-400');
        }
    }

    // Batch Filtering
    function filterBatch(batch) {
        document.querySelectorAll('.batch-filter-btn').forEach(btn => {
            if (btn.dataset.batch === batch) {
                btn.classList.add('active', 'bg-blue-600', 'text-white');
                btn.classList.remove('text-slate-300');
            } else {
                btn.classList.remove('active', 'bg-blue-600', 'text-white');
                btn.classList.add('text-slate-300');
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

    // Open Evaluation Modal
    function openEvaluationModal(regNo) {
        const student = studentData.find(s => s.reg_no === regNo);
        if (!student) return;

        document.getElementById('evalRegNo').value = student.reg_no;
        document.getElementById('evalStudentName').innerText = student.name;
        document.getElementById('evalStudentReg').innerText = 'Reg No: ' + (student.sbte_reg_no || student.reg_no);
        document.getElementById('evalTopic').value = student.topic || '';
        document.getElementById('evalGuideMobile').value = student.guide_mobile_no || '';

        const myEval = student.my_evaluation;
        document.getElementById('evalRelevance').value = myEval ? myEval.relevance : 0;
        document.getElementById('evalLiterature').value = myEval ? myEval.literature : 0;
        document.getElementById('evalPresentation').value = myEval ? myEval.presentation : 0;
        document.getElementById('evalInteraction').value = myEval ? myEval.interaction : 0;
        document.getElementById('evalReport').value = myEval ? myEval.report : 0;
        document.getElementById('evalAttendance').value = myEval ? myEval.attendance : (student.suggested_att_mark || 7.5);

        const suggestedLabel = document.getElementById('evalSuggestedAtt');
        if (suggestedLabel) {
            suggestedLabel.innerText = `Suggested: ${student.suggested_att_mark || 7.5}M (${student.att_percentage || 100}%)`;
        }

        calculateLiveScore();
        document.getElementById('evaluationModal').classList.remove('hidden');
    }

    // Calculate Live Evaluation Total Score
    function calculateLiveScore() {
        const rel = parseFloat(document.getElementById('evalRelevance')?.value || 0) || 0;
        const lit = parseFloat(document.getElementById('evalLiterature')?.value || 0) || 0;
        const pres = parseFloat(document.getElementById('evalPresentation')?.value || 0) || 0;
        const disc = parseFloat(document.getElementById('evalInteraction')?.value || 0) || 0;
        const rep = parseFloat(document.getElementById('evalReport')?.value || 0) || 0;
        const att = parseFloat(document.getElementById('evalAttendance')?.value || 0) || 0;

        let total = rel + lit + pres + disc + rep + att;
        if (total > 75) total = 75;

        const liveTotalEl = document.getElementById('evalLiveTotal');
        if (liveTotalEl) {
            liveTotalEl.innerText = total.toFixed(1) + ' / 75';
        }
    }

    // Submit Evaluation AJAX
    async function submitEvaluation(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSaveEvaluation');
        if (btn) btn.disabled = true;

        const formData = new FormData(document.getElementById('evaluationForm'));
        const payload = Object.fromEntries(formData.entries());

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
                const regNo = data.reg_no;

                // Update cached student
                const student = studentData.find(s => s.reg_no === regNo);
                if (student) {
                    student.is_completed = true;
                    student.final_score = data.average_score;
                    student.letter_grade = data.letter_grade;
                    student.grade_point = data.grade_point;
                    student.result = data.result;
                    student.my_evaluation = {
                        total_score: data.my_total
                    };
                    student.eval_count = data.eval_count;
                    student.assessors_list = data.assessors_list;
                    student.topic = data.topic;
                    student.guide_name = data.guide_name;
                }

                // Update DOM table cells
                const myScoreCell = document.querySelector(`.my-score-${regNo}`);
                if (myScoreCell) myScoreCell.innerText = parseFloat(data.my_total).toFixed(1);

                const commAvgCell = document.querySelector(`.comm-avg-${regNo}`);
                if (commAvgCell) {
                    commAvgCell.innerText = parseFloat(data.average_score).toFixed(1);
                    commAvgCell.classList.add('font-bold', 'text-blue-400', 'underline', 'cursor-pointer');
                    commAvgCell.onclick = () => openBreakdownModal(regNo);
                }

                const gradeCell = document.querySelector(`.grade-cell-${regNo}`);
                if (gradeCell) {
                    gradeCell.innerHTML = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">${data.letter_grade}</span>`;
                }

                // Update Stats Strip
                const statCompleted = document.getElementById('statCompletedCount');
                if (statCompleted && data.completed_count) {
                    statCompleted.innerText = data.completed_count;
                }

                document.getElementById('evaluationModal').classList.add('hidden');
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

    // Open Schedule Modal
    function openScheduleModal(regNo) {
        const student = studentData.find(s => s.reg_no === regNo);
        if (!student) return;

        document.getElementById('schedRegNo').value = student.reg_no;
        document.getElementById('schedStudentName').innerText = student.name + ' (' + (student.sbte_reg_no || student.reg_no) + ')';
        document.getElementById('schedTopic').value = student.topic || '';
        document.getElementById('schedDate').value = student.presentation_date || '';
        document.getElementById('schedGuideMobile').value = student.guide_mobile_no || '';

        document.getElementById('scheduleModal').classList.remove('hidden');
    }

    // Submit Schedule AJAX
    async function submitSchedule(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSaveSchedule');
        if (btn) btn.disabled = true;

        const formData = new FormData(document.getElementById('scheduleForm'));
        const payload = Object.fromEntries(formData.entries());

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
                const regNo = data.reg_no;

                const student = studentData.find(s => s.reg_no === regNo);
                if (student) {
                    student.topic = data.topic;
                    student.presentation_date = data.presentation_date;
                    student.presentation_date_formatted = data.presentation_date_formatted;
                    student.guide_name = data.guide_name;
                    student.guide_mobile_no = data.guide_mobile_no;
                }

                // Update DOM cells in schedule tab
                const dateCell = document.querySelector(`.sched-date-${regNo}`);
                if (dateCell && data.presentation_date_formatted) {
                    dateCell.innerHTML = `<div class="flex items-center gap-1.5 text-sky-400"><span>📅</span><span>${data.presentation_date_formatted}</span></div>`;
                }

                const topicCell = document.querySelector(`.sched-topic-${regNo}`);
                if (topicCell) topicCell.innerText = data.topic;

                const guideCell = document.querySelector(`.sched-guide-${regNo}`);
                if (guideCell) guideCell.innerText = data.guide_name;

                document.getElementById('scheduleModal').classList.add('hidden');
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

    // Open Breakdown Modal
    function openBreakdownModal(regNo) {
        const student = studentData.find(s => s.reg_no === regNo);
        if (!student) return;

        document.getElementById('breakdownStudentName').innerText = student.name + ' (' + (student.sbte_reg_no || student.reg_no) + ')';
        document.getElementById('breakdownFinalScore').innerText = parseFloat(student.final_score).toFixed(1) + ' / 75';

        const container = document.getElementById('breakdownTableContainer');
        const assessors = student.assessors_list || [];

        if (assessors.length === 0) {
            container.innerHTML = '<div class="text-xs text-slate-400 py-4 text-center">No individual committee evaluations recorded.</div>';
        } else {
            let html = `
                <div class="border border-slate-200 rounded-xl overflow-hidden text-xs">
                    <table class="w-full text-left">
                        <thead class="bg-slate-100 border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-3 font-semibold text-slate-700">Assessor</th>
                                <th class="py-2.5 px-2 font-semibold text-slate-700 text-center">Rel (7.5)</th>
                                <th class="py-2.5 px-2 font-semibold text-slate-700 text-center">Lit (7.5)</th>
                                <th class="py-2.5 px-2 font-semibold text-slate-700 text-center">Pres (37.5)</th>
                                <th class="py-2.5 px-2 font-semibold text-slate-700 text-center">Disc (7.5)</th>
                                <th class="py-2.5 px-2 font-semibold text-slate-700 text-center">Rep (7.5)</th>
                                <th class="py-2.5 px-2 font-semibold text-slate-700 text-center">Att (7.5)</th>
                                <th class="py-2.5 px-3 font-semibold text-slate-700 text-center">Total (75)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
            `;
            assessors.forEach(a => {
                html += `
                    <tr>
                        <td class="py-2 px-3 font-medium text-slate-800">${a.assessor_name || a.assessor_mobile}</td>
                        <td class="py-2 px-2 text-center font-mono">${parseFloat(a.relevance).toFixed(1)}</td>
                        <td class="py-2 px-2 text-center font-mono">${parseFloat(a.literature).toFixed(1)}</td>
                        <td class="py-2 px-2 text-center font-mono">${parseFloat(a.presentation).toFixed(1)}</td>
                        <td class="py-2 px-2 text-center font-mono">${parseFloat(a.interaction).toFixed(1)}</td>
                        <td class="py-2 px-2 text-center font-mono">${parseFloat(a.report).toFixed(1)}</td>
                        <td class="py-2 px-2 text-center font-mono">${parseFloat(a.attendance).toFixed(1)}</td>
                        <td class="py-2 px-3 text-center font-mono font-bold text-blue-600">${parseFloat(a.total_score).toFixed(1)}</td>
                    </tr>
                `;
            });
            html += `</tbody></table></div>`;
            container.innerHTML = html;
        }

        document.getElementById('breakdownModal').classList.remove('hidden');
    }

    // Open Syllabus Modal
    function openSyllabusModal() {
        document.getElementById('syllabusModal').classList.remove('hidden');
    }

    // Submit Syllabus AJAX
    async function submitSyllabus(e) {
        e.preventDefault();
        const btn = document.getElementById('btnUploadSyllabus');
        if (btn) btn.disabled = true;

        const formData = new FormData(document.getElementById('syllabusForm'));

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
                document.getElementById('syllabusModal').classList.add('hidden');
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

    // Load Attainment Data
    async function loadAttainmentData() {
        try {
            const resp = await fetch(`/r21/classroom/seminar/${subjectId}/attainment-summary`, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            const result = await resp.json();
            if (resp.ok && result.status === 'SUCCESS') {
                alert('Attainment matrix refreshed from live evaluations.');
            }
        } catch (err) {
            console.error(err);
        }
    }
</script>
