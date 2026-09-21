<script>
    // State initialization from server
    window.r21DrawingConfig = {
        batchSubjectId: {{ $batchSubject->id }},
        csrfToken: '{{ csrf_token() }}',
        currentSheet: 'Sheet 1',
        currentTest: 'Test 1',
        sheetData: @json($sheetEvals),
        testData: @json($seriesTests),
        students: @json($students),
        attMax: {{ $attMax }},
        formativeMax: {{ $formativeMax }},
        summativeMax: {{ $summativeMax }},
        ciaMax: {{ $ciaMax }}
    };

    // 1. Tab Switching Function
    function switchDrawingTab(targetTabId, triggerBtn) {
        // Hide all panes
        document.querySelectorAll('#r21DrawingTabPanes .tab-pane').forEach(pane => {
            pane.classList.add('hidden');
            pane.classList.remove('block');
        });

        // Show target pane
        const targetPane = document.getElementById(targetTabId);
        if (targetPane) {
            targetPane.classList.remove('hidden');
            targetPane.classList.add('block');
        }

        // Reset button styles
        document.querySelectorAll('#r21DrawingTabs .tab-trigger').forEach(btn => {
            btn.classList.remove('active', 'bg-blue-50', 'text-blue-700', 'border-blue-200', 'shadow-2xs');
            btn.classList.add('text-slate-600', 'border-transparent');
        });

        // Set active button style
        if (triggerBtn) {
            triggerBtn.classList.remove('text-slate-600', 'border-transparent');
            triggerBtn.classList.add('active', 'bg-blue-50', 'text-blue-700', 'border-blue-200', 'shadow-2xs');
        }
    }

    // 2. Formative Sheet Switching & Dynamic Population
    function switchSheet(sheetNo, btnElement) {
        window.r21DrawingConfig.currentSheet = sheetNo;

        // Update pills active styling
        document.querySelectorAll('.sheet-pill').forEach(pill => {
            pill.classList.remove('bg-blue-50/80', 'border-blue-300', 'text-blue-700', 'shadow-2xs', 'active');
            pill.classList.add('bg-slate-50', 'border-slate-200', 'text-slate-600');
        });
        if (btnElement) {
            btnElement.classList.remove('bg-slate-50', 'border-slate-200', 'text-slate-600');
            btnElement.classList.add('bg-blue-50/80', 'border-blue-300', 'text-blue-700', 'shadow-2xs', 'active');
            
            const title = btnElement.getAttribute('data-sheet-title');
            const mod = btnElement.getAttribute('data-sheet-module');
            const co = btnElement.getAttribute('data-sheet-co');
            
            document.getElementById('activeSheetPillLabel').textContent = sheetNo;
            document.getElementById('activeSheetTitle').textContent = title || sheetNo;
            document.getElementById('activeSheetMeta').innerHTML = `${mod || 'Module'} &bull; Mapped to Outcome: <strong class="text-blue-600">${co || 'CO1'}</strong> &bull; Max Score: 100 (Timely 50 + Appearance 50)`;
        }

        // Repopulate table rows for this sheet
        populateSheetTable(sheetNo);
    }

    function populateSheetTable(sheetNo) {
        const tbody = document.getElementById('sheetTableBody');
        if (!tbody) return;

        const rows = tbody.querySelectorAll('tr[data-reg-no]');
        rows.forEach(row => {
            const regNo = row.getAttribute('data-reg-no');
            const stSheets = window.r21DrawingConfig.sheetData[regNo] || [];
            const eval = stSheets.find(s => s.sheet_no === sheetNo);

            const timelyInput = row.querySelector('.sheet-timely');
            const appearanceInput = row.querySelector('.sheet-appearance');
            const absentCheckbox = row.querySelector('.sheet-absent');
            const remarksInput = row.querySelector('.sheet-remarks');
            const badge = row.querySelector('.row-total-badge');

            if (eval) {
                const timely = eval.timely_completion !== null ? eval.timely_completion : '';
                const appearance = eval.appearance_organization !== null ? eval.appearance_organization : '';
                const isAbsent = Boolean(eval.is_absent);
                const total = eval.total_score_100 !== null ? parseFloat(eval.total_score_100) : 0;

                timelyInput.value = timely;
                appearanceInput.value = appearance;
                absentCheckbox.checked = isAbsent;
                remarksInput.value = eval.remarks || '';

                updateSheetBadge(badge, total, isAbsent);
                timelyInput.disabled = isAbsent;
                appearanceInput.disabled = isAbsent;
            } else {
                timelyInput.value = '';
                appearanceInput.value = '';
                absentCheckbox.checked = false;
                remarksInput.value = '';
                timelyInput.disabled = false;
                appearanceInput.disabled = false;
                updateSheetBadge(badge, 0, false);
            }
        });
    }

    function calculateSheetRowTotal(input) {
        const row = input.closest('tr');
        if (!row) return;

        const timely = parseFloat(row.querySelector('.sheet-timely').value) || 0;
        const appearance = parseFloat(row.querySelector('.sheet-appearance').value) || 0;
        const absent = row.querySelector('.sheet-absent').checked;
        const badge = row.querySelector('.row-total-badge');

        const total = absent ? 0 : Math.min(100, Math.max(0, timely + appearance));
        updateSheetBadge(badge, total, absent);
        scheduleAutoSave('sheet');
    }

    function toggleSheetAbsent(checkbox) {
        const row = checkbox.closest('tr');
        if (!row) return;

        const timelyInput = row.querySelector('.sheet-timely');
        const appearanceInput = row.querySelector('.sheet-appearance');
        const badge = row.querySelector('.row-total-badge');

        if (checkbox.checked) {
            timelyInput.value = '';
            appearanceInput.value = '';
            timelyInput.disabled = true;
            appearanceInput.disabled = true;
            updateSheetBadge(badge, 0, true);
        } else {
            timelyInput.disabled = false;
            appearanceInput.disabled = false;
            calculateSheetRowTotal(timelyInput);
        }
        scheduleAutoSave('sheet');
    }

    function updateSheetBadge(badge, total, isAbsent) {
        if (!badge) return;
        badge.className = 'inline-flex items-center justify-center min-w-[50px] px-2.5 py-1 rounded-lg text-xs font-bold border row-total-badge';
        if (isAbsent) {
            badge.classList.add('bg-rose-50', 'text-rose-700', 'border-rose-200');
            badge.textContent = 'ABS';
        } else if (total >= 80) {
            badge.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
            badge.textContent = total;
        } else if (total >= 50) {
            badge.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-200');
            badge.textContent = total;
        } else {
            badge.classList.add('bg-amber-50', 'text-amber-700', 'border-amber-200');
            badge.textContent = total;
        }
    }

    // 3. Save Active Sheet Marks via AJAX
    async function saveActiveSheetMarks() {
        const statusEl = document.getElementById('sheetSaveStatus');
        const sheetNo = window.r21DrawingConfig.currentSheet;
        const rows = document.querySelectorAll('#sheetTableBody tr[data-reg-no]');
        const evaluations = [];

        rows.forEach(row => {
            const regNo = row.getAttribute('data-reg-no');
            const isAbsent = row.querySelector('.sheet-absent').checked;
            const timely = row.querySelector('.sheet-timely').value;
            const appearance = row.querySelector('.sheet-appearance').value;
            const remarks = row.querySelector('.sheet-remarks').value;

            if (isAbsent || timely !== '' || appearance !== '') {
                evaluations.push({
                    reg_no: regNo,
                    is_absent: isAbsent,
                    timely_completion: timely !== '' ? parseFloat(timely) : 0,
                    appearance_organization: appearance !== '' ? parseFloat(appearance) : 0,
                    remarks: remarks || null
                });
            }
        });

        if (statusEl) statusEl.innerHTML = '<span class="text-blue-600">Saving...</span>';

        try {
            const resp = await fetch(`/r21/classroom/drawing/${window.r21DrawingConfig.batchSubjectId}/save-sheet-marks`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.r21DrawingConfig.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    sheet_no: sheetNo,
                    evaluations: evaluations
                })
            });
            const data = await resp.json();
            if (data.status === 'success') {
                if (statusEl) statusEl.innerHTML = '<span class="text-emerald-600 font-bold">&check; Saved</span>';
                indicateGlobalSaved();
            } else {
                throw new Error(data.message || 'Error saving marks');
            }
        } catch (e) {
            if (statusEl) statusEl.innerHTML = '<span class="text-rose-600 font-bold">&cross; Failed</span>';
            console.error('Error saving sheet marks:', e);
        }
    }

    // 4. Series Test Switching & Logic
    function switchSeriesTest(testNo, btnElement) {
        window.r21DrawingConfig.currentTest = testNo;

        document.querySelectorAll('.test-pill').forEach(pill => {
            pill.classList.remove('bg-indigo-50/80', 'border-indigo-300', 'text-indigo-700', 'shadow-2xs', 'active');
            pill.classList.add('bg-slate-50', 'border-slate-200', 'text-slate-600');
        });
        if (btnElement) {
            btnElement.classList.remove('bg-slate-50', 'border-slate-200', 'text-slate-600');
            btnElement.classList.add('bg-indigo-50/80', 'border-indigo-300', 'text-indigo-700', 'shadow-2xs', 'active');
        }

        document.getElementById('activeTestPillLabel').textContent = testNo;
        document.getElementById('activeTestTitle').textContent = testNo === 'Test 1' ? 'Series Test 1 • Modules I & II' : 'Series Test 2 • Modules III & IV';

        populateTestTable(testNo);
    }

    function populateTestTable(testNo) {
        const tbody = document.getElementById('testTableBody');
        if (!tbody) return;

        const rows = tbody.querySelectorAll('tr[data-reg-no]');
        rows.forEach(row => {
            const regNo = row.getAttribute('data-reg-no');
            const stTests = window.r21DrawingConfig.testData[regNo] || [];
            const eval = stTests.find(t => t.test_no === testNo);

            const pInput = row.querySelector('.test-procedure');
            const fInput = row.querySelector('.test-final');
            const dInput = row.querySelector('.test-dimensioning');
            const nInput = row.querySelector('.test-neatness');
            const absentCheckbox = row.querySelector('.test-absent');
            const remarksInput = row.querySelector('.test-remarks');
            const badge = row.querySelector('.test-row-total-badge');

            if (eval) {
                const isAbsent = Boolean(eval.is_absent);
                pInput.value = eval.procedure_drawing !== null ? eval.procedure_drawing : '';
                fInput.value = eval.final_drawing !== null ? eval.final_drawing : '';
                dInput.value = eval.dimensioning !== null ? eval.dimensioning : '';
                nInput.value = eval.neatness !== null ? eval.neatness : '';
                absentCheckbox.checked = isAbsent;
                remarksInput.value = eval.remarks || '';

                const total = eval.total_score_100 !== null ? parseFloat(eval.total_score_100) : 0;
                updateTestBadge(badge, total, isAbsent);
                [pInput, fInput, dInput, nInput].forEach(inp => inp.disabled = isAbsent);
            } else {
                [pInput, fInput, dInput, nInput].forEach(inp => { inp.value = ''; inp.disabled = false; });
                absentCheckbox.checked = false;
                remarksInput.value = '';
                updateTestBadge(badge, 0, false);
            }
        });
    }

    function calculateTestRowTotal(input) {
        const row = input.closest('tr');
        if (!row) return;

        const p = parseFloat(row.querySelector('.test-procedure').value) || 0;
        const f = parseFloat(row.querySelector('.test-final').value) || 0;
        const d = parseFloat(row.querySelector('.test-dimensioning').value) || 0;
        const n = parseFloat(row.querySelector('.test-neatness').value) || 0;
        const absent = row.querySelector('.test-absent').checked;
        const badge = row.querySelector('.test-row-total-badge');

        const total = absent ? 0 : Math.min(100, Math.max(0, p + f + d + n));
        updateTestBadge(badge, total, absent);
        scheduleAutoSave('test');
    }

    function toggleTestAbsent(checkbox) {
        const row = checkbox.closest('tr');
        if (!row) return;

        const inputs = [
            row.querySelector('.test-procedure'),
            row.querySelector('.test-final'),
            row.querySelector('.test-dimensioning'),
            row.querySelector('.test-neatness')
        ];
        const badge = row.querySelector('.test-row-total-badge');

        if (checkbox.checked) {
            inputs.forEach(inp => { inp.value = ''; inp.disabled = true; });
            updateTestBadge(badge, 0, true);
        } else {
            inputs.forEach(inp => inp.disabled = false);
            calculateTestRowTotal(inputs[0]);
        }
        scheduleAutoSave('test');
    }

    function updateTestBadge(badge, total, isAbsent) {
        if (!badge) return;
        badge.className = 'inline-flex items-center justify-center min-w-[50px] px-2.5 py-1 rounded-lg text-xs font-bold border test-row-total-badge';
        if (isAbsent) {
            badge.classList.add('bg-rose-50', 'text-rose-700', 'border-rose-200');
            badge.textContent = 'ABS';
        } else if (total >= 80) {
            badge.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
            badge.textContent = total;
        } else if (total >= 50) {
            badge.classList.add('bg-indigo-50', 'text-indigo-700', 'border-indigo-200');
            badge.textContent = total;
        } else {
            badge.classList.add('bg-amber-50', 'text-amber-700', 'border-amber-200');
            badge.textContent = total;
        }
    }

    // 5. Save Series Test Marks via AJAX
    async function saveActiveSeriesTestMarks() {
        const statusEl = document.getElementById('testSaveStatus');
        const testNo = window.r21DrawingConfig.currentTest;
        const rows = document.querySelectorAll('#testTableBody tr[data-reg-no]');
        const evaluations = [];

        rows.forEach(row => {
            const regNo = row.getAttribute('data-reg-no');
            const isAbsent = row.querySelector('.test-absent').checked;
            const p = row.querySelector('.test-procedure').value;
            const f = row.querySelector('.test-final').value;
            const d = row.querySelector('.test-dimensioning').value;
            const n = row.querySelector('.test-neatness').value;
            const remarks = row.querySelector('.test-remarks').value;

            if (isAbsent || p !== '' || f !== '' || d !== '' || n !== '') {
                evaluations.push({
                    reg_no: regNo,
                    is_absent: isAbsent,
                    procedure_drawing: p !== '' ? parseFloat(p) : 0,
                    final_drawing: f !== '' ? parseFloat(f) : 0,
                    dimensioning: d !== '' ? parseFloat(d) : 0,
                    neatness: n !== '' ? parseFloat(n) : 0,
                    remarks: remarks || null
                });
            }
        });

        if (statusEl) statusEl.innerHTML = '<span class="text-indigo-600">Saving...</span>';

        try {
            const resp = await fetch(`/r21/classroom/drawing/${window.r21DrawingConfig.batchSubjectId}/save-series-test`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.r21DrawingConfig.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    test_no: testNo,
                    evaluations: evaluations
                })
            });
            const data = await resp.json();
            if (data.status === 'success') {
                if (statusEl) statusEl.innerHTML = '<span class="text-emerald-600 font-bold">&check; Saved</span>';
                indicateGlobalSaved();
            } else {
                throw new Error(data.message || 'Error saving tests');
            }
        } catch (e) {
            if (statusEl) statusEl.innerHTML = '<span class="text-rose-600 font-bold">&cross; Failed</span>';
            console.error('Error saving series test marks:', e);
        }
    }

    // 6. Attendance Recalculation & Saving
    function recalculateAttendanceRow(regNo) {
        const row = document.querySelector(`#r21AttendanceTable tr[data-reg-no="${regNo}"]`);
        if (!row) return;

        const overrideVal = row.querySelector('.att-override-input').value;
        const systemVal = parseFloat(row.querySelector('.system-att-mark').textContent) || 0;
        const finalSpan = row.querySelector('.final-att-mark');

        if (overrideVal !== '') {
            finalSpan.textContent = parseFloat(overrideVal).toFixed(1);
        } else {
            finalSpan.textContent = systemVal.toFixed(1);
        }
        scheduleAutoSave('attendance');
    }

    async function saveAllAttendance() {
        const rows = document.querySelectorAll('#r21AttendanceTable tbody tr[data-reg-no]');
        const evaluations = [];

        rows.forEach(row => {
            const regNo = row.getAttribute('data-reg-no');
            const overrideVal = row.querySelector('.att-override-input').value;
            const reasonVal = row.querySelector('.att-reason-input').value;

            evaluations.push({
                reg_no: regNo,
                override_mark: overrideVal !== '' ? parseFloat(overrideVal) : null,
                remarks: reasonVal || null
            });
        });

        try {
            const resp = await fetch(`/r21/classroom/drawing/${window.r21DrawingConfig.batchSubjectId}/save-attendance`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.r21DrawingConfig.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ evaluations: evaluations })
            });
            const data = await resp.json();
            if (data.status === 'success') {
                indicateGlobalSaved();
            }
        } catch (e) {
            console.error('Error saving attendance:', e);
        }
    }

    // 7. Keyboard Grid Navigation (Arrow Keys)
    function handleGridNavigation(e, input) {
        const row = input.closest('tr');
        if (!row) return;

        let targetInput = null;
        if (e.key === 'ArrowDown') {
            const nextRow = row.nextElementSibling;
            if (nextRow) {
                const colIdx = Array.from(row.querySelectorAll('input:not([type=checkbox])')).indexOf(input);
                const nextInputs = nextRow.querySelectorAll('input:not([type=checkbox])');
                targetInput = nextInputs[colIdx];
            }
        } else if (e.key === 'ArrowUp') {
            const prevRow = row.previousElementSibling;
            if (prevRow) {
                const colIdx = Array.from(row.querySelectorAll('input:not([type=checkbox])')).indexOf(input);
                const prevInputs = prevRow.querySelectorAll('input:not([type=checkbox])');
                targetInput = prevInputs[colIdx];
            }
        }

        if (targetInput) {
            e.preventDefault();
            targetInput.focus();
            targetInput.select();
        }
    }

    // 8. Autosave Debounce Helper
    let autoSaveTimeout = null;
    function scheduleAutoSave(type) {
        clearTimeout(autoSaveTimeout);
        const indicator = document.getElementById('globalAutoSaveIndicator');
        if (indicator) {
            indicator.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 transition-all';
            indicator.innerHTML = '<span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span><span>Unsaved Changes...</span>';
        }

        autoSaveTimeout = setTimeout(() => {
            if (type === 'sheet') saveActiveSheetMarks();
            else if (type === 'test') saveActiveSeriesTestMarks();
            else if (type === 'attendance') saveAllAttendance();
        }, 1500);
    }

    function indicateGlobalSaved() {
        const indicator = document.getElementById('globalAutoSaveIndicator');
        if (indicator) {
            indicator.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 transition-all';
            indicator.innerHTML = '<svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>All Changes Saved</span>';
        }
    }
</script>
