<script>
    window.subjectId = {{ $batchSubject->id }};
    window.studentResults = @json($studentResults);
    window.projectGroups = @json($projectGroups);
    window.csrfToken = '{{ csrf_token() }}';

    // 1. Tab Switching Navigation
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
            btn.classList.remove('active', 'border-blue-500', 'text-blue-400');
            btn.classList.add('border-transparent', 'text-slate-400');
        });
        var activeBtn = document.getElementById('btn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.add('active', 'border-blue-500', 'text-blue-400');
            activeBtn.classList.remove('border-transparent', 'text-slate-400');
        }
    }

    // 2. Real-Time Table Filtering
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

    // 3. Individual CIA Evaluation Modal
    function openCiaEvalModal(regNo) {
        var student = getStudentData(regNo);
        if (!student) return;

        document.getElementById('ciaModalRegNo').value = regNo;
        document.getElementById('ciaModalStudentName').textContent = student.name;
        document.getElementById('ciaModalStudentReg').textContent = student.reg_no;
        document.getElementById('ciaModalAttPct').textContent = Number(student.att_percentage).toFixed(1) + '%';
        document.getElementById('ciaModalSuggestedAtt').textContent = 'Suggested: ' + Number(student.suggested_att_mark).toFixed(1);

        document.getElementById('ciaModalDiary').value = student.formative_diary_marks || 0;
        document.getElementById('ciaModalDept').value = student.summative_dept_marks || 0;
        document.getElementById('ciaModalAttd').value = student.attendance_marks !== undefined ? student.attendance_marks : student.suggested_att_mark;

        computeLiveCiaTotals();
        document.getElementById('ciaEvalModal').classList.remove('hidden');
    }

    function closeCiaEvalModal() {
        document.getElementById('ciaEvalModal').classList.add('hidden');
    }

    function computeLiveCiaTotals() {
        var diary = parseFloat(document.getElementById('ciaModalDiary').value) || 0;
        var dept = parseFloat(document.getElementById('ciaModalDept').value) || 0;
        var attd = parseFloat(document.getElementById('ciaModalAttd').value) || 0;

        diary = Math.max(0, Math.min(30, diary));
        dept = Math.max(0, Math.min(30, dept));
        attd = Math.max(0, Math.min(15, attd));

        var total = diary + dept + attd;
        document.getElementById('ciaModalLiveTotal').textContent = total.toFixed(1) + ' / 75';

        var grade = calcGradeFromMarks(total, 75.0);
        document.getElementById('ciaModalGradeBadge').textContent = grade;

        var passBadge = document.getElementById('ciaModalPassBadge');
        if (total >= 30.0) {
            passBadge.textContent = 'Eligible (≥ 30M)';
            passBadge.className = 'px-2 py-0.5 rounded text-xs font-bold bg-emerald-500/20 text-emerald-300';
        } else {
            passBadge.textContent = 'Below Min (30M)';
            passBadge.className = 'px-2 py-0.5 rounded text-xs font-bold bg-rose-500/20 text-rose-300';
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

    // 4. Group Common CIA Modal
    function openGroupCiaModal() {
        document.getElementById('groupCiaModal').classList.remove('hidden');
    }

    function closeGroupCiaModal() {
        document.getElementById('groupCiaModal').classList.add('hidden');
    }

    function saveGroupCiaForm() {
        var groupId = document.getElementById('grpCiaModalGroupId').value;
        var diary = parseFloat(document.getElementById('grpCiaDiary').value) || 0;
        var dept = parseFloat(document.getElementById('grpCiaDept').value) || 0;

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

    // 5. Comprehensive Student Evaluation Modal (CIA + ESE)
    function openEvalModal(regNo) {
        var student = getStudentData(regNo);
        if (!student) return;

        document.getElementById('modalRegNo').value = regNo;
        document.getElementById('modalStudentName').textContent = student.name;
        document.getElementById('modalStudentReg').textContent = student.reg_no;
        document.getElementById('modalGroupBadge').textContent = student.group_name || 'Unassigned';
        document.getElementById('modalProjectTitle').value = student.project_title || '';

        document.getElementById('modalDiary').value = student.formative_diary_marks || 0;
        document.getElementById('modalDept').value = student.summative_dept_marks || 0;
        document.getElementById('modalAttd').value = student.attendance_marks !== undefined ? student.attendance_marks : student.suggested_att_mark;

        document.getElementById('modalEseTotal').value = student.total_ese_50 || 0;
        document.getElementById('modalEseGrade').value = student.ese_grade && student.ese_grade !== '—' ? student.ese_grade : '';

        computeLiveTotals();
        document.getElementById('evalModal').classList.remove('hidden');
    }

    function closeEvalModal() {
        document.getElementById('evalModal').classList.add('hidden');
    }

    function onModalEseDirectInput() {
        var ese = parseFloat(document.getElementById('modalEseTotal').value) || 0;
        ese = Math.max(0, Math.min(50, ese));
        document.getElementById('modalEseTotal').value = ese;
        var grade = calcGradeFromMarks(ese, 50.0);
        document.getElementById('modalEseGrade').value = (grade !== '—' && grade !== 'F') ? grade : '';
        computeLiveTotals();
    }

    function onModalGradeSelect() {
        var grade = document.getElementById('modalEseGrade').value;
        if (grade) {
            var marks = calcMarksFromGrade(grade);
            document.getElementById('modalEseTotal').value = marks.toFixed(1);
        }
        computeLiveTotals();
    }

    function computeLiveTotals() {
        var diary = parseFloat(document.getElementById('modalDiary').value) || 0;
        var dept = parseFloat(document.getElementById('modalDept').value) || 0;
        var attd = parseFloat(document.getElementById('modalAttd').value) || 0;
        var ese = parseFloat(document.getElementById('modalEseTotal').value) || 0;

        var ciaTotal = diary + dept + attd;
        var grandTotal = ciaTotal + ese;

        document.getElementById('modalCiaSubTotal').textContent = ciaTotal.toFixed(1) + ' / 75';
        document.getElementById('modalEseSubTotal').textContent = ese.toFixed(1) + ' / 50';
        document.getElementById('modalGrandTotal').textContent = grandTotal.toFixed(1) + ' / 125';

        var passBadge = document.getElementById('modalPassBadge');
        var passed = (ciaTotal >= 30.0 && ese >= 20.0 && grandTotal >= 50.0);
        if (passed) {
            passBadge.textContent = 'PASS';
            passBadge.className = 'px-2.5 py-1 rounded text-xs font-bold bg-emerald-500/20 text-emerald-300';
        } else if (grandTotal > 0) {
            passBadge.textContent = 'FAIL';
            passBadge.className = 'px-2.5 py-1 rounded text-xs font-bold bg-rose-500/20 text-rose-300';
        } else {
            passBadge.textContent = 'PENDING';
            passBadge.className = 'px-2.5 py-1 rounded text-xs font-bold bg-slate-800 text-slate-400';
        }
    }

    function saveStudentEval() {
        var regNo = document.getElementById('modalRegNo').value;
        var diary = parseFloat(document.getElementById('modalDiary').value) || 0;
        var dept = parseFloat(document.getElementById('modalDept').value) || 0;
        var attd = parseFloat(document.getElementById('modalAttd').value) || 0;
        var ese = parseFloat(document.getElementById('modalEseTotal').value) || 0;
        var eseGrade = document.getElementById('modalEseGrade').value;
        var projectTitle = document.getElementById('modalProjectTitle').value;

        var payload = {
            reg_no: regNo,
            formative_diary_marks: diary,
            summative_dept_marks: dept,
            attendance_marks: attd,
            total_ese_50: ese,
            ese_grade: eseGrade,
            project_title: projectTitle
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
                closeEvalModal();
                window.location.reload();
            } else {
                alert(data.message || 'Error saving evaluation.');
            }
        })
        .catch(function(err) { alert('Request failed: ' + err.message); });
    }

    // 6. Group ESE Assessment Modal
    function openGroupEseModal() {
        document.getElementById('groupEseModal').classList.remove('hidden');
    }

    function closeGroupEseModal() {
        document.getElementById('groupEseModal').classList.add('hidden');
    }

    function saveGroupEseForm() {
        var groupId = document.getElementById('grpEseModalGroupId').value;
        var payload = {
            group_id: groupId,
            ese_prototype: parseFloat(document.getElementById('grpEseProto').value) || 0,
            ese_modern_tools: parseFloat(document.getElementById('grpEseTools').value) || 0,
            ese_presentation: parseFloat(document.getElementById('grpEsePres').value) || 0,
            ese_innovativeness: parseFloat(document.getElementById('grpEseInno').value) || 0,
            ese_viva: parseFloat(document.getElementById('grpEseViva').value) || 0,
            ese_individual_contrib: parseFloat(document.getElementById('grpEseIndiv').value) || 0,
            ese_group_activity: parseFloat(document.getElementById('grpEseGrp').value) || 0,
            ese_project_report: parseFloat(document.getElementById('grpEseRep').value) || 0
        };

        fetch('/r21/classroom/project/' + window.subjectId + '/group-ese', {
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
                closeGroupEseModal();
                window.location.reload();
            } else {
                alert(data.message || 'Error updating group ESE rubrics.');
            }
        })
        .catch(function(err) { alert('Request failed: ' + err.message); });
    }

    // 7. Examiners Panel Modal
    function openExaminersModal() {
        document.getElementById('examinersModal').classList.remove('hidden');
    }

    function closeExaminersModal() {
        document.getElementById('examinersModal').classList.add('hidden');
    }

    function saveExaminersForm() {
        var payload = {
            internal_name: document.getElementById('internalExaminerName').value,
            internal_designation: document.getElementById('internalExaminerDesig').value,
            external_name: document.getElementById('externalExaminerName').value,
            external_designation: document.getElementById('externalExaminerDesig').value,
            external_college: document.getElementById('externalExaminerCollege').value,
            exam_date: document.getElementById('examinerExamDate').value
        };

        fetch('/r21/classroom/project/' + window.subjectId + '/examiners', {
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
                closeExaminersModal();
                alert('Examiners panel updated successfully.');
            } else {
                alert(data.message || 'Error saving examiners.');
            }
        })
        .catch(function(err) { alert('Request failed: ' + err.message); });
    }

    // 8. Project Groups Setup & Dynamic Management
    function addGroupRow() {
        switchTab('tab-groups');
        alert('To add a new project group, configure title, guide, and members in the group form and click Save Groups.');
    }

    function promptDeleteGroup(idx) {
        document.getElementById('deleteGroupIndex').value = idx;
        document.getElementById('modalDeleteGroupConfirm').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('modalDeleteGroupConfirm').classList.add('hidden');
    }

    function confirmDeleteGroup() {
        var idx = parseInt(document.getElementById('deleteGroupIndex').value, 10);
        closeDeleteModal();
        alert('Group removed. Click Save Groups to commit changes.');
    }

    function saveProjectGroups() {
        var cards = document.querySelectorAll('[data-group-card]');
        var groups = [];

        cards.forEach(function(card, i) {
            var groupId = card.getAttribute('data-group-id') || (i + 1);
            var titleInput = card.querySelector('input[name*="[title]"]');
            var guideSelect = card.querySelector('select[name*="[guide_mobile]"]');
            var checkedBoxes = card.querySelectorAll('input[type="checkbox"]:checked');

            var members = [];
            checkedBoxes.forEach(function(cb) { members.push(cb.value); });

            groups.push({
                id: groupId,
                name: 'Group ' + groupId,
                title: titleInput ? titleInput.value : '',
                guide_mobile: guideSelect ? guideSelect.value : '',
                members: members
            });
        });

        fetch('/r21/classroom/project/' + window.subjectId + '/save-groups', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ groups: groups })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'SUCCESS') {
                alert('Project groups saved successfully.');
                window.location.reload();
            } else {
                alert(data.message || 'Error saving project groups.');
            }
        })
        .catch(function(err) { alert('Request failed: ' + err.message); });
    }

    // 9. CO Attainment & Course Exit Survey
    function loadAttainmentData() {
        fetch('/r21/classroom/project/' + window.subjectId + '/attainment-summary', {
            headers: { 'Accept': 'application/json' }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'SUCCESS') {
                alert('Attainment metrics refreshed.');
            }
        })
        .catch(function(err) { console.error(err); });
    }

    function initiateExitSurvey() {
        fetch('/api/classroom/' + window.subjectId + '/course-exit/initiate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'SUCCESS') {
                alert('Course Exit Survey initiated successfully. Share the student link.');
                document.getElementById('btnInitiateSurvey').classList.add('hidden');
                document.getElementById('btnCloseSurvey').classList.remove('hidden');
            } else {
                alert(data.message || 'Error initiating survey.');
            }
        })
        .catch(function(err) { alert('Request failed: ' + err.message); });
    }

    function closeExitSurvey() {
        fetch('/api/classroom/' + window.subjectId + '/course-exit/close', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'SUCCESS') {
                alert('Course Exit Survey closed.');
                document.getElementById('btnCloseSurvey').classList.add('hidden');
                document.getElementById('btnInitiateSurvey').classList.remove('hidden');
            } else {
                alert(data.message || 'Error closing survey.');
            }
        })
        .catch(function(err) { alert('Request failed: ' + err.message); });
    }

    function copySurveyLink() {
        var url = window.location.origin + '/student/course-exit/' + window.subjectId;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(function() {
                alert('Survey link copied to clipboard: ' + url);
            });
        } else {
            prompt('Copy survey link:', url);
        }
    }
</script>
