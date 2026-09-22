    function openSeriesTheoryModal() {
        document.getElementById('series-theory-modal').classList.remove('hidden');
        const sel = document.getElementById('series-theory-student-select');
        if (sel && sel.value) {
            loadSeriesTheoryStudent(sel.value);
        }
    }

    function closeSeriesTheoryModal() {
        document.getElementById('series-theory-modal').classList.add('hidden');
    }

    function onSeriesTheoryTestChange(test) {
        const sel = document.getElementById('series-theory-student-select');
        if (sel && sel.value) {
            loadSeriesTheoryStudent(sel.value);
        }
    }

    function loadSeriesTheoryStudent(regNo) {
        const student = studentsList.find(s => s.reg_no === regNo);
        if (!student) return;

        document.getElementById('series-theory-student-display').innerText = `${student.name} (${student.reg_no})`;

        const test = document.getElementById('series-theory-test-select').value;
        const state = seriesTheoryEvalsState[regNo][test] || { total_score_50: 0, is_absent: false };

        const totalInput = document.getElementById('series-theory-total');
        const absentCheck = document.getElementById('series-theory-absent');

        totalInput.value = state.total_score_50;
        absentCheck.checked = state.is_absent;

        totalInput.disabled = state.is_absent;

        updateSeriesTheoryLiveTotal();
    }

    function onSeriesTheoryMarksInput() {
        const sel = document.getElementById('series-theory-student-select');
        const regNo = sel.value;
        if (!regNo) return;

        const test = document.getElementById('series-theory-test-select').value;
        const total = parseFloat(document.getElementById('series-theory-total').value) || 0;

        seriesTheoryEvalsState[regNo][test].total_score_50 = total;

        updateSeriesTheoryLiveTotal();
    }

    function toggleSeriesTheoryAbsent(isAbsent) {
        const sel = document.getElementById('series-theory-student-select');
        const regNo = sel.value;
        if (!regNo) return;

        const test = document.getElementById('series-theory-test-select').value;
        seriesTheoryEvalsState[regNo][test].is_absent = isAbsent;

        const totalInput = document.getElementById('series-theory-total');

        if (isAbsent) {
            totalInput.value = 0;
            totalInput.disabled = true;
            seriesTheoryEvalsState[regNo][test].total_score_50 = 0;
        } else {
            totalInput.disabled = false;
        }
        updateSeriesTheoryLiveTotal();
    }

    function updateSeriesTheoryLiveTotal() {
        const total = parseFloat(document.getElementById('series-theory-total').value) || 0;
        const isAbsent = document.getElementById('series-theory-absent').checked;

        const displayTotal = isAbsent ? 0 : total;
        document.getElementById('series-theory-live-total').innerText = `${displayTotal.toFixed(2)} / 50.00`;
    }

    function prevSeriesTheoryStudent() {
        const sel = document.getElementById('series-theory-student-select');
        if (!sel || sel.selectedIndex <= 0) return;
        sel.selectedIndex--;
        loadSeriesTheoryStudent(sel.value);
    }

    function nextSeriesTheoryStudent() {
        const sel = document.getElementById('series-theory-student-select');
        if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
        sel.selectedIndex++;
        loadSeriesTheoryStudent(sel.value);
    }

    function saveAndNextSeriesTheoryStudent() {
        nextSeriesTheoryStudent();
    }

    function saveAllSeriesTheoryMarks() {
        const test = document.getElementById('series-theory-test-select').value;
        const marksData = [];

        Object.keys(seriesTheoryEvalsState).forEach(regNo => {
            const state = seriesTheoryEvalsState[regNo][test];
            marksData.push({
                reg_no: regNo,
                total_score_50: state.total_score_50,
                is_absent: state.is_absent
            });
        });

        Swal.fire({
            title: 'Saving Series Marks...',
            text: `Updating scores for ${test}`,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/series-theory', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ series_no: test, marks_data: marksData })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                closeSeriesTheoryModal();
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

    document.addEventListener('DOMContentLoaded', () => {
        const savedMode = localStorage.getItem('active_mode');
        const savedTheoryTab = localStorage.getItem('active_theory_subtab');
        const savedLabTab = localStorage.getItem('active_lab_subtab');

        if (savedMode) {
            switchMode(savedMode);
        }
        if (savedTheoryTab) {
            switchTheorySubtab(savedTheoryTab);
        }
        if (savedLabTab) {
            switchLabSubtab(savedLabTab);
        }
    });
