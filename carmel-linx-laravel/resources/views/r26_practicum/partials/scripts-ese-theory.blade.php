    function openEseTheoryModal() {
        document.getElementById('ese-theory-modal').classList.remove('hidden');
        const sel = document.getElementById('ese-student-select');
        if (sel && sel.value) {
            loadEseStudent(sel.value);
        }
    }

    function closeEseTheoryModal() {
        document.getElementById('ese-theory-modal').classList.add('hidden');
    }

    function loadEseStudent(regNo) {
        const student = studentsList.find(s => s.reg_no === regNo);
        if (!student) return;

        document.getElementById('ese-student-reg').innerText = student.reg_no;
        document.getElementById('ese-student-name').innerText = student.name;

        const state = eseGradesState[regNo] || { ese_theory_grade: '', theory_absent: false };
        const gradeSelect = document.getElementById('ese-grade-select');
        const absentCheck = document.getElementById('ese-absent-check');

        gradeSelect.value = state.ese_theory_grade;
        absentCheck.checked = state.theory_absent;
        gradeSelect.disabled = state.theory_absent;

        updateEseLiveDisplay(state.ese_theory_grade);
    }

    function onEseGradeChange(grade) {
        const sel = document.getElementById('ese-student-select');
        const regNo = sel.value;
        if (!regNo) return;

        if (!eseGradesState[regNo]) eseGradesState[regNo] = {};
        eseGradesState[regNo].ese_theory_grade = grade;
        eseGradesState[regNo].theory_absent = (grade === 'FE');

        document.getElementById('ese-absent-check').checked = (grade === 'FE');
        updateEseLiveDisplay(grade);
    }

    function toggleEseAbsent(isAbsent) {
        const sel = document.getElementById('ese-student-select');
        const regNo = sel.value;
        if (!regNo) return;

        if (!eseGradesState[regNo]) eseGradesState[regNo] = {};
        eseGradesState[regNo].theory_absent = isAbsent;

        const gradeSelect = document.getElementById('ese-grade-select');
        if (isAbsent) {
            eseGradesState[regNo].ese_theory_grade = 'FE';
            gradeSelect.value = 'FE';
            gradeSelect.disabled = true;
            updateEseLiveDisplay('FE');
        } else {
            eseGradesState[regNo].ese_theory_grade = '';
            gradeSelect.value = '';
            gradeSelect.disabled = false;
            updateEseLiveDisplay('');
        }
    }

    function updateEseLiveDisplay(grade) {
        const score = convertGradeToScore(grade);
        document.getElementById('ese-mapped-score').innerText = `${score.toFixed(2)} / 60.00`;

        const isPass = (score >= 24.0 || ['S','A','B','C','D','P'].includes(String(grade).toUpperCase().trim()));
        const statusEl = document.getElementById('ese-pass-status');
        if (isPass) {
            statusEl.innerText = 'PASSED';
            statusEl.className = 'font-bold text-emerald-600';
        } else {
            statusEl.innerText = grade ? 'REAPPEAR' : '-';
            statusEl.className = 'font-bold text-rose-600';
        }
    }

    function convertGradeToScore(grade) {
        switch (String(grade).toUpperCase().trim()) {
            case 'S': return 57.0;
            case 'A': return 51.0;
            case 'B': return 45.0;
            case 'C': return 39.0;
            case 'D': return 33.0;
            case 'P': return 27.0;
            default: return 0.0;
        }
    }

    function prevEseStudent() {
        const sel = document.getElementById('ese-student-select');
        if (!sel || sel.selectedIndex <= 0) return;
        sel.selectedIndex--;
        loadEseStudent(sel.value);
    }

    function nextEseStudent() {
        const sel = document.getElementById('ese-student-select');
        if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
        sel.selectedIndex++;
        loadEseStudent(sel.value);
    }

    function saveAndNextEseStudent() {
        nextEseStudent();
    }

    function saveAllEseGrades() {
        const marksData = [];
        Object.keys(eseGradesState).forEach(regNo => {
            const student = studentsList.find(s => s.reg_no === regNo);
            marksData.push({
                reg_no: regNo,
                ese_theory_grade: eseGradesState[regNo].ese_theory_grade,
                theory_absent: eseGradesState[regNo].theory_absent,
                practical_absent: false,
                ese_practical_marks: parseFloat(student ? (student.ese_practical || 0) : 0)
            });
        });

        Swal.fire({
            title: 'Saving ESE Grades...',
            text: 'Updating board theory grades for all students',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/ese', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ marks_data: marksData })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                closeEseTheoryModal();
                Swal.fire({
                    icon: 'success',
                    title: 'Saved Successfully!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(err => {
            Swal.fire('Error', err.message, 'error');
        });
    }

    // =====================================================================
    // Theory Series Exam Marks Modal System
    // =====================================================================
    const seriesTheoryEvalsDb = @json($seriesTheoryEvals);
    const seriesTheoryEvalsState = {};

    studentsList.forEach(s => {
        const regNo = s.reg_no;
        seriesTheoryEvalsState[regNo] = {
            'Series 1': { total_score_50: 0, is_absent: false },
            'Series 2': { total_score_50: 0, is_absent: false },
            'Series 3': { total_score_50: 0, is_absent: false },
            'Series 4': { total_score_50: 0, is_absent: false }
        };

        const dbList = seriesTheoryEvalsDb[regNo] || [];
        dbList.forEach(evalRecord => {
            const sNo = evalRecord.series_no;
            let mappedSeries = sNo;
            if (sNo === 'CO1') mappedSeries = 'Series 1';
            if (sNo === 'CO2') mappedSeries = 'Series 2';
            if (sNo === 'CO3') mappedSeries = 'Series 3';
            if (sNo === 'CO4') mappedSeries = 'Series 4';

            if (seriesTheoryEvalsState[regNo][mappedSeries]) {
                seriesTheoryEvalsState[regNo][mappedSeries] = {
                    total_score_50: parseFloat(evalRecord.total_score_50) || 0,
                    is_absent: !!evalRecord.is_absent
                };
            }
        });
    });

