<script>
    window.subjectId = {{ $batchSubject->id }};
    window.studentResults = @json($studentResults);
    window.projectGroups = @json($projectGroups);
    window.csrfToken = '{{ csrf_token() }}';

    let activeStudent = null;
    let activeSurveyId = null;
    let activeSurveyUrl = null;

    // 1. Navigation & Dropdown
    function returnToParent() {
        if (window.opener && !window.opener.closed) {
            window.opener.focus();
            window.close();
            return;
        }
        if (document.referrer && document.referrer !== window.location.href) {
            window.location.href = document.referrer;
            return;
        }
        if (window.history.length > 1) {
            window.history.back();
            return;
        }
        window.location.href = '/lecturer/dashboard';
    }

    function toggleProjectPrintDropdown(e) {
        if (e) e.stopPropagation();
        const menu = document.getElementById('projectPrintMenu');
        if (menu) menu.classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        const container = document.getElementById('projectPrintDropdownContainer');
        const menu = document.getElementById('projectPrintMenu');
        if (container && menu && !container.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    // 2. Tab Switching Navigation
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(function(el) {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        var activePanel = document.getElementById(tabId);
        if (activePanel) {
            activePanel.classList.remove('hidden');
            activePanel.classList.add('block');
        }

        document.querySelectorAll('.tab-btn').forEach(function(btn) {
            btn.classList.remove('active', 'border-blue-500', 'text-blue-600', 'text-blue-400');
            btn.classList.add('border-transparent', 'text-slate-500');
        });
        var activeBtn = document.getElementById('btn-' + tabId) || document.getElementById('btn-' + tabId.replace('tab-', ''));
        if (activeBtn) {
            activeBtn.classList.add('active', 'border-blue-500', 'text-blue-600');
            activeBtn.classList.remove('border-transparent', 'text-slate-500');
        }

        if (tabId === 'tab-attainment') {
            loadAttainmentData();
        }
    }

    function openAttainmentModal() {
        switchTab('tab-attainment');
    }

    function openGroupModal() {
        switchTab('tab-groups');
    }

    function printSelectedGroupBreakdown() {
        const select = document.getElementById('reportGroupFilterSelect');
        const grp = select ? select.value : 'all';
        window.open(`/r21/classroom/project/${window.subjectId}/report/print?type=group_breakdown&group_id=${encodeURIComponent(grp)}`, '_blank');
    }

    // 3. Real-Time Table Filtering
    function filterTable(tableId, query) {
        var q = (query || '').toLowerCase().trim();
        document.querySelectorAll('[data-student-row]').forEach(function(row) {
            var regNo = (row.getAttribute('data-reg-no') || '').toLowerCase();
            var name = (row.getAttribute('data-student-name') || '').toLowerCase();
            if (!q || regNo.indexOf(q) !== -1 || name.indexOf(q) !== -1) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Helper: Find student data by reg_no
    function getStudentData(regNo) {
        if (!window.studentResults) return null;
        for (var i = 0; i < window.studentResults.length; i++) {
            if (window.studentResults[i].reg_no === regNo) {
                return window.studentResults[i];
            }
        }
        return null;
    }

    // Helper: SBTE 9-Point Grade Calculation
    function calcGradeFromMarks(marks, maxMarks) {
        if (marks === null || marks === undefined || isNaN(marks)) return '—';
        var pct = (marks / maxMarks) * 100.0;
        if (pct >= 90.0) return 'S';
        if (pct >= 85.0) return 'A+';
        if (pct >= 80.0) return 'A';
        if (pct >= 75.0) return 'B+';
        if (pct >= 70.0) return 'B';
        if (pct >= 65.0) return 'C+';
        if (pct >= 60.0) return 'C';
        if (pct >= 50.0) return 'D';
        return 'F';
    }

    function calcMarksFromGrade(grade) {
        switch (grade) {
            case 'S':  return 47.5;
            case 'A+': return 43.5;
            case 'A':  return 41.0;
            case 'B+': return 38.5;
            case 'B':  return 36.0;
            case 'C+': return 33.5;
            case 'C':  return 31.0;
            case 'D':  return 27.5;
            case 'F':  return 20.0;
            default:   return 0.0;
        }
    }

    // ==========================================================
    // 4. Individual CIA Evaluation Modal
    // ==========================================================
    function openCiaEvalModal(studentOrReg) {
        var student = typeof studentOrReg === 'string' ? getStudentData(studentOrReg) : studentOrReg;
        if (!student) return;

        document.getElementById('ciaModalRegNo').value = student.reg_no;
        document.getElementById('ciaModalStudentName').textContent = student.name;
        document.getElementById('ciaModalStudentReg').textContent = student.sbte_reg_no || student.reg_no;
        const grpEl = document.getElementById('ciaModalGroupId');
        if (grpEl) grpEl.value = student.group_name || '';

        document.getElementById('ciaModalAttPct').textContent = Number(student.att_percentage || 100).toFixed(1) + '%';
        document.getElementById('ciaModalSuggestedAtt').textContent = 'Suggested: ' + Number(student.suggested_att_mark !== undefined ? student.suggested_att_mark : 15.0).toFixed(1);

        document.getElementById('ciaModalDiary').value = student.formative_diary_marks || 0;
        document.getElementById('ciaModalDept').value = student.summative_dept_marks || 0;
        document.getElementById('ciaModalAttd').value = student.attendance_marks !== undefined ? student.attendance_marks : (student.suggested_att_mark || 15.0);

        computeLiveCiaTotals();
        const modal = document.getElementById('ciaEvalModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeCiaEvalModal() {
        const modal = document.getElementById('ciaEvalModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function computeLiveCiaTotals() {
        var diary = parseFloat(document.getElementById('ciaModalDiary')?.value) || 0;
        var dept = parseFloat(document.getElementById('ciaModalDept')?.value) || 0;
        var attd = parseFloat(document.getElementById('ciaModalAttd')?.value) || 0;

        diary = Math.max(0, Math.min(30, diary));
        dept = Math.max(0, Math.min(30, dept));
        attd = Math.max(0, Math.min(15, attd));

        var total = diary + dept + attd;
        const liveTotalEl = document.getElementById('ciaModalLiveTotal');
        if (liveTotalEl) liveTotalEl.textContent = total.toFixed(1) + ' / 75';

        var grade = calcGradeFromMarks(total, 75.0);
        const gradeBadge = document.getElementById('ciaModalGradeBadge');
        if (gradeBadge) gradeBadge.textContent = grade;

        var passBadge = document.getElementById('ciaModalPassBadge');
        if (passBadge) {
            if (total >= 30.0) {
                passBadge.textContent = 'Eligible (≥ 30M)';
                passBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200';
            } else {
                passBadge.textContent = 'Below Min (30M)';
                passBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200';
            }
        }
    }

    function saveCiaStudentEval() {
        var regNo = document.getElementById('ciaModalRegNo').value;
        var diary = parseFloat(document.getElementById('ciaModalDiary').value) || 0;
        var dept = parseFloat(document.getElementById('ciaModalDept').value) || 0;
        var attd = parseFloat(document.getElementById('ciaModalAttd').value) || 0;

        var payload = {
            reg_no: regNo,
            formative_diary_marks: diary,
            summative_dept_marks: dept,
            attendance_marks: attd
        };

        fetch('/r21/classroom/project/' + window.subjectId + '/save-evaluation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'SUCCESS') {
                closeCiaEvalModal();
                window.location.reload();
            } else {
                alert(data.message || 'Error saving CIA evaluation.');
            }
        })
        .catch(function(err) {
            alert('Request failed: ' + err.message);
        });
    }

    // ==========================================================
    // 5. Group Common CIA Modal
    // ==========================================================
    function openGroupCiaModal(groupId, groupName) {
        if (groupId) {
            const grpIdInput = document.getElementById('grpCiaModalGroupId');
            if (grpIdInput) grpIdInput.value = groupId;
        }
        if (groupName) {
            const titleEl = document.getElementById('grpCiaModalTitle');
            if (titleEl) titleEl.textContent = groupName;
            const descEl = document.getElementById('grpCiaModalTitleDesc');
            if (descEl) descEl.textContent = groupName;
        }
        const modal = document.getElementById('groupCiaModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeGroupCiaModal() {
        const modal = document.getElementById('groupCiaModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function saveGroupCiaForm(e) {
        if (e) e.preventDefault();
        var groupId = document.getElementById('grpCiaModalGroupId').value;
        var diary = parseFloat(document.getElementById('grp_cia_diary')?.value || document.getElementById('grpCiaDiary')?.value || 0) || 0;
        var dept = parseFloat(document.getElementById('grp_cia_dept')?.value || document.getElementById('grpCiaDept')?.value || 0) || 0;

        fetch('/r21/classroom/project/' + window.subjectId + '/group-cia', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                group_id: groupId,
                formative_diary_marks: diary,
                summative_dept_marks: dept
            })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'SUCCESS') {
                closeGroupCiaModal();
                window.location.reload();
            } else {
                alert(data.message || 'Error updating group CIA.');
            }
        })
        .catch(function(err) { alert('Request failed: ' + err.message); });
    }

    // ==========================================================
    // 6. Comprehensive Student Evaluation Modal (CIA + ESE)
    // ==========================================================
    function openEvalModal(studentOrReg) {
        var student = typeof studentOrReg === 'string' ? getStudentData(studentOrReg) : studentOrReg;
        if (!student) return;

        activeStudent = student;
        document.getElementById('modalRegNo').value = student.reg_no;
        document.getElementById('modalStudentName').textContent = student.name;
        document.getElementById('modalStudentReg').textContent = student.sbte_reg_no || student.reg_no;
        const grpBadge = document.getElementById('modalGroupBadge');
        if (grpBadge) grpBadge.textContent = student.group_name || 'Unassigned';
        const grpIdEl = document.getElementById('modalGroupId');
        if (grpIdEl) grpIdEl.value = student.group_name || '';

        document.getElementById('modalProjectTitle').value = student.project_title || '';

        document.getElementById('modalDiary').value = student.formative_diary_marks || 0;
        document.getElementById('modalDept').value = student.summative_dept_marks || 0;
        document.getElementById('modalAttd').value = student.attendance_marks !== undefined ? student.attendance_marks : (student.suggested_att_mark || 15.0);

        // Populate 8 ESE Rubrics
        const setVal = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.value = val !== undefined && val !== null ? val : 0;
        };

        setVal('mProto', student.ese_prototype || 0);
        setVal('mTools', student.ese_modern_tools || 0);
        setVal('mPres', student.ese_presentation || 0);
        setVal('mInnov', student.ese_innovativeness || 0);
        setVal('mViva', student.ese_viva || 0);
        setVal('mIndiv', student.ese_individual_contrib || 0);
        setVal('mGroup', student.ese_group_activity || 0);
        setVal('mRep', student.ese_project_report || 0);

        document.getElementById('modalEseTotal').value = student.total_ese_50 || 0;
        document.getElementById('modalEseGrade').value = student.ese_grade && student.ese_grade !== '—' ? student.ese_grade : '';

        computeLiveTotals();
        const modal = document.getElementById('evalModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeEvalModal() {
        const modal = document.getElementById('evalModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function computeLiveTotals() {
        const diary = parseFloat(document.getElementById('modalDiary')?.value) || 0;
        const dept = parseFloat(document.getElementById('modalDept')?.value) || 0;
        const attd = parseFloat(document.getElementById('modalAttd')?.value) || 0;
        const ciaTot = Math.min(75.0, diary + dept + attd);

        const liveCia = document.getElementById('liveCiaTotal');
        if (liveCia) liveCia.innerText = ciaTot.toFixed(1) + ' / 75';
        const modalCiaSub = document.getElementById('modalCiaSubTotal');
        if (modalCiaSub) modalCiaSub.innerText = ciaTot.toFixed(1) + ' / 75';

        const proto = parseFloat(document.getElementById('mProto')?.value) || 0;
        const tools = parseFloat(document.getElementById('mTools')?.value) || 0;
        const pres = parseFloat(document.getElementById('mPres')?.value) || 0;
        const innov = parseFloat(document.getElementById('mInnov')?.value) || 0;
        const viva = parseFloat(document.getElementById('mViva')?.value) || 0;
        const indiv = parseFloat(document.getElementById('mIndiv')?.value) || 0;
        const grp = parseFloat(document.getElementById('mGroup')?.value) || 0;
        const rep = parseFloat(document.getElementById('mRep')?.value) || 0;

        const rubricSum = Math.min(50.0, proto + tools + pres + innov + viva + indiv + grp + rep);

        let eseTot = parseFloat(document.getElementById('modalEseTotal')?.value) || 0;
        if (rubricSum > 0) {
            eseTot = rubricSum;
            const eseTotInput = document.getElementById('modalEseTotal');
            if (eseTotInput) eseTotInput.value = eseTot.toFixed(1);
        }

        const liveEse = document.getElementById('liveEseTotal');
        if (liveEse) liveEse.innerText = eseTot.toFixed(1) + ' / 50';
        const modalEseSub = document.getElementById('modalEseSubTotal');
        if (modalEseSub) modalEseSub.innerText = eseTot.toFixed(1) + ' / 50';

        const grandTot = Math.min(125.0, ciaTot + eseTot);
        const grandTotEl = document.getElementById('modalGrandTotal');
        if (grandTotEl) grandTotEl.innerText = grandTot.toFixed(1) + ' / 125';

        // Auto Grade Calculation
        const gradeSelect = document.getElementById('modalEseGrade');
        if (gradeSelect && eseTot > 0 && !gradeSelect.value) {
            gradeSelect.value = calcGradeFromMarks(eseTot, 50.0);
        }

        // Pass Eligibility (Kerala SBTE R21: CIA >= 30, ESE >= 20, Grand Total >= 50)
        const passBadge = document.getElementById('modalPassBadge');
        if (passBadge) {
            if (ciaTot >= 30.0 && (eseTot === 0 || eseTot >= 20.0) && grandTot >= 50.0) {
                passBadge.innerText = eseTot >= 20.0 ? 'PASSED' : 'CIA ELIGIBLE (≥ 30M)';
                passBadge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200';
            } else if (ciaTot < 30.0) {
                passBadge.innerText = 'CIA FAILED (< 30M)';
                passBadge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200';
            } else if (eseTot > 0 && eseTot < 20.0) {
                passBadge.innerText = 'ESE FAILED (< 20M)';
                passBadge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200';
            } else {
                passBadge.innerText = 'PENDING';
                passBadge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200';
            }
        }
    }

    function onModalEseDirectInput() {
        var ese = parseFloat(document.getElementById('modalEseTotal').value) || 0;
        ese = Math.max(0, Math.min(50, ese));
        document.getElementById('modalEseTotal').value = ese;
        var grade = calcGradeFromMarks(ese, 50.0);
        const gradeEl = document.getElementById('modalEseGrade');
        if (gradeEl) gradeEl.value = (grade !== '—' && grade !== 'F') ? grade : '';
        computeLiveTotals();
    }

    function onModalGradeSelect() {
        var grade = document.getElementById('modalEseGrade').value;
        if (grade) {
            var suggested = calcMarksFromGrade(grade);
            document.getElementById('modalEseTotal').value = suggested.toFixed(1);
        }
        computeLiveTotals();
    }

    function saveStudentEval(e) {
        if (e) e.preventDefault();
        const form = document.getElementById('evalForm');
        const data = new FormData(form);

        fetch(`/r21/classroom/project/${window.subjectId}/save-evaluation`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: data
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'SUCCESS') {
                closeEvalModal();
                window.location.reload();
            } else {
                alert('Error: ' + (res.message || 'Failed to save evaluation.'));
            }
        })
        .catch(err => {
            alert('Network error while saving evaluation.');
        });
    }

    // ==========================================================
    // 7. Group ESE 8-Rubrics Modal
    // ==========================================================
    function openGroupEseModal(groupId, groupName) {
        if (groupId) {
            const grpIdInput = document.getElementById('grpEseModalGroupId');
            if (grpIdInput) grpIdInput.value = groupId;
        }
        if (groupName) {
            const titleEl = document.getElementById('grpEseModalTitle');
            if (titleEl) titleEl.textContent = groupName;
            const descEl = document.getElementById('grpEseModalTitleDesc');
            if (descEl) descEl.textContent = groupName;
        }
        const modal = document.getElementById('groupEseModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeGroupEseModal() {
        const modal = document.getElementById('groupEseModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function saveGroupEseForm(e) {
        if (e) e.preventDefault();
        const form = document.getElementById('groupEseForm');
        const data = new FormData(form);

        fetch(`/r21/classroom/project/${window.subjectId}/group-ese`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: data
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                closeGroupEseModal();
                window.location.reload();
            } else {
                alert(data.message || 'Error updating group ESE.');
            }
        })
        .catch(err => { alert('Request failed: ' + err.message); });
    }

    // ==========================================================
    // 8. ESE Examiners Panel Modal
    // ==========================================================
    function openExaminersModal() {
        const modal = document.getElementById('examinersModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeExaminersModal() {
        const modal = document.getElementById('examinersModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function saveExaminersForm(e) {
        if (e) e.preventDefault();
        const form = document.getElementById('examinersForm');
        const data = new FormData(form);

        fetch(`/r21/classroom/project/${window.subjectId}/examiners`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: data
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                alert('Examiners panel updated successfully.');
                closeExaminersModal();
                window.location.reload();
            } else {
                alert(data.message || 'Error updating examiners.');
            }
        })
        .catch(err => { alert('Request failed: ' + err.message); });
    }

    // ==========================================================
    // 9. Project Groups Management & Dynamic Rows
    // ==========================================================
    function addGroupRow() {
        const container = document.getElementById('groupsContainer');
        const noNotice = document.getElementById('noGroupsNotice');
        if (noNotice) noNotice.remove();

        const idx = container ? container.querySelectorAll('.group-card').length : 0;
        const card = document.createElement('div');
        card.className = 'group-card p-4 rounded-xl bg-white border border-slate-200 shadow-sm space-y-3';
        card.setAttribute('data-group-index', idx);

        card.innerHTML = `
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <span class="font-bold text-xs text-blue-700 uppercase">Group ${idx + 1}</span>
                <button type="button" onclick="promptDeleteGroup(${idx})" class="text-rose-500 hover:text-rose-700 p-1 text-xs">
                    Remove
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Group Name / ID</label>
                    <input type="text" name="groups[${idx}][name]" value="Group ${idx + 1}" required class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-800">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Assigned Guide</label>
                    <select name="groups[${idx}][guide_mobile_no]" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-800">
                        <option value="">-- Select Guide --</option>
                        @if(isset($guides))
                            @foreach($guides as $g)
                                <option value="{{ $g->mobile_no }}">{{ $g->name }} ({{ $g->designation }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-slate-700 font-semibold mb-1">Project Title</label>
                    <input type="text" name="groups[${idx}][title]" placeholder="Project title..." class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-800">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-slate-700 font-semibold mb-1">Select Student Members</label>
                    <select name="groups[${idx}][members][]" multiple class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs text-slate-800 h-24">
                        @if(isset($students))
                            @foreach($students as $st)
                                <option value="{{ $st->reg_no }}">{{ $st->name }} ({{ $st->sbte_reg_no ?? $st->reg_no }})</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="text-[10px] text-slate-400">Hold Ctrl (Cmd) to select multiple members</span>
                </div>
            </div>
        `;

        if (container) container.appendChild(card);
    }

    function promptDeleteGroup(index) {
        document.getElementById('deleteGroupIndex').value = index;
        const modal = document.getElementById('modalDeleteGroupConfirm');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeDeleteModal() {
        const modal = document.getElementById('modalDeleteGroupConfirm');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function confirmDeleteGroup() {
        const idx = document.getElementById('deleteGroupIndex').value;
        const card = document.querySelector(`.group-card[data-group-index="${idx}"]`);
        if (card) card.remove();
        closeDeleteModal();
    }

    function saveProjectGroups(e) {
        if (e) e.preventDefault();
        const form = document.getElementById('groupsForm');
        const data = new FormData(form);

        fetch(`/r21/classroom/project/${window.subjectId}/save-groups`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: data
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                alert('Project groups saved successfully.');
                window.location.reload();
            } else {
                alert(data.message || 'Error saving project groups.');
            }
        })
        .catch(err => { alert('Request failed: ' + err.message); });
    }

    // ==========================================================
    // 10. Attainment & Exit Survey Functions
    // ==========================================================
    async function loadAttainmentData() {
        try {
            const resp = await fetch(`/r21/classroom/project/${window.subjectId}/attainment-summary`, {
                headers: { 'Accept': 'application/json' }
            });
            const result = await resp.json();
            if (resp.ok && result.status === 'SUCCESS') {
                // Attainment data received
            }
        } catch (err) {
            console.error('Error loading project attainment:', err);
        }
    }

    async function initiateExitSurvey() {
        if (!confirm('Open End Semester Course Exit Survey for all enrolled Project students?')) return;

        try {
            const resp = await fetch(`/api/classroom/${window.subjectId}/course-exit/initiate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                }
            });
            const res = await resp.json();
            if (res.status === 'SUCCESS') {
                alert('Course Exit Survey initiated successfully!');
                loadProjectSurveyData();
            } else {
                alert('Notice: ' + (res.message || 'Could not initiate survey'));
                loadProjectSurveyData();
            }
        } catch (err) {
            alert('Network error while initiating survey.');
        }
    }

    async function closeExitSurvey() {
        if (!confirm('Are you sure you want to close and lock this survey? Student submissions will be locked.')) return;

        try {
            const resp = await fetch(`/api/classroom/${window.subjectId}/course-exit/close`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                }
            });
            const res = await resp.json();
            if (res.status === 'SUCCESS') {
                alert('Course Exit Survey closed and finalized successfully.');
                loadProjectSurveyData();
            } else {
                alert('Error: ' + (res.message || 'Could not close survey'));
            }
        } catch (err) {
            alert('Network error while closing survey.');
        }
    }

    function copySurveyLink() {
        const urlInput = document.getElementById('surveyUrlInput');
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

    async function loadProjectSurveyData() {
        try {
            const resp = await fetch(`/api/classroom/${window.subjectId}/course-exit/status`, {
                headers: { 'Accept': 'application/json' }
            });
            const res = await resp.json();
            if (res && res.survey) {
                updateSurveyUi(res.survey);
            }
        } catch (err) {
            console.log('Project exit survey check:', err);
        }
    }

    function updateSurveyUi(survey) {
        if (!survey) return;
        activeSurveyId = survey.id;
        activeSurveyUrl = survey.student_url;

        const badge = document.getElementById('surveyStatusBadge');
        const btnOpen = document.getElementById('btnOpenExitSurvey');
        const btnClose = document.getElementById('btnCloseExitSurvey');
        const btnCopy = document.getElementById('btnCopySurveyLink');
        const btnTest = document.getElementById('btnTestSurveyLink');
        const urlInput = document.getElementById('surveyUrlInput');
        const statText = document.getElementById('surveyResponseStat');
        const pctText = document.getElementById('surveyResponsePct');
        const progBar = document.getElementById('surveyProgressBar');

        const responded = survey.responded_count || 0;
        const total = survey.total_students || 1;
        const pct = Math.round((responded / total) * 100);

        if (statText) statText.innerText = `${responded} / ${total} Submitted`;
        if (pctText) pctText.innerText = `${pct}%`;
        if (progBar) progBar.style.width = `${Math.min(100, pct)}%`;

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
                btnTest.href = survey.student_url || '#';
            }
            if (urlInput) urlInput.value = survey.student_url || `${window.location.origin}/student/course-exit/${window.subjectId}`;
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

    // Escape Key Modal Dismissal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
            closeCiaEvalModal();
            closeGroupCiaModal();
            closeEvalModal();
            closeGroupEseModal();
            closeExaminersModal();
            closeDeleteModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        loadProjectSurveyData();
    });
</script>
