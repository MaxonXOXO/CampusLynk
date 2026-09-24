<script>
(function() {
    window.labBatchSetupState = {
        subjectId: null,
        mode: 'split',
        cutoff: null,
        students: [],
        initialCutoff: null,
        onSavedCallback: null
    };

    window.activeRosterTab = 'all';

    window.openLabBatchSetupModal = async function(subjectId, callback) {
        const sid = subjectId || (typeof currentSubjectId !== 'undefined' ? currentSubjectId : null) || (document.getElementById('subjectSelect') ? document.getElementById('subjectSelect').value : null);
        if (!sid) {
            alert("Please select a subject first.");
            return;
        }

        window.labBatchSetupState.subjectId = sid;
        window.labBatchSetupState.onSavedCallback = callback || null;

        const modal = document.getElementById('labBatchSetupModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Fetch current setup from API
        try {
            const res = await fetch(`/api/classroom/${sid}/practical/batch-setup`);
            const data = await res.json();
            if (data.status === 'SUCCESS') {
                window.labBatchSetupState.mode = data.lab_batch_mode || 'split';
                window.labBatchSetupState.cutoff = data.lab_batch_cutoff || null;
                window.labBatchSetupState.initialCutoff = data.lab_batch_cutoff || null;
                window.labBatchSetupState.students = (data.students || []).map(s => ({
                    reg_no: s.reg_no,
                    name: s.name,
                    roll_no: s.roll_no,
                    lab_batch: String(s.lab_batch || '1')
                }));

                // Set Subtitle
                const subEl = document.getElementById('batchSetupSubjectSubtitle');
                if (subEl) {
                    subEl.innerText = `${data.subject_name || 'Practical Lab'} • ${data.total_students || 0} Students enrolled`;
                }

                // If no cutoff configured, calculate default 50/50 cutoff
                if (!window.labBatchSetupState.cutoff && window.labBatchSetupState.students.length > 0) {
                    const mid = Math.ceil(window.labBatchSetupState.students.length / 2);
                    const midStudent = window.labBatchSetupState.students[mid - 1];
                    window.labBatchSetupState.cutoff = midStudent && midStudent.roll_no ? parseInt(midStudent.roll_no) : mid;
                }

                initLabBatchSetupUI();
            } else {
                alert(data.message || "Failed to load lab batch configuration.");
            }
        } catch (err) {
            console.error("Error loading lab batch setup:", err);
            alert("Could not load lab batch details.");
        }
    };

    window.closeLabBatchSetupModal = function() {
        const modal = document.getElementById('labBatchSetupModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };

    window.toggleLabBatchSetupFullscreen = function() {
        const dialog = document.getElementById('labBatchSetupModalDialog');
        const icon = document.getElementById('batchSetupFullscreenIcon');
        if (!dialog) return;

        if (dialog.classList.contains('is-fullscreen')) {
            dialog.classList.remove('is-fullscreen', '!w-full', '!h-full', '!max-w-none', '!max-h-none', '!rounded-none');
            dialog.classList.add('rounded-2xl', 'max-w-[96vw]', 'xl:max-w-[1500px]', 'h-[95vh]', 'max-h-[95vh]');
            if (icon) icon.innerText = 'fullscreen';
        } else {
            dialog.classList.add('is-fullscreen', '!w-full', '!h-full', '!max-w-none', '!max-h-none', '!rounded-none');
            dialog.classList.remove('rounded-2xl', 'max-w-[96vw]', 'xl:max-w-[1500px]', 'h-[95vh]', 'max-h-[95vh]');
            if (icon) icon.innerText = 'fullscreen_exit';
        }
    };

    function initLabBatchSetupUI() {
        const state = window.labBatchSetupState;
        
        // Set Mode Radio
        const modeRadios = document.querySelectorAll('input[name="labBatchModeRadio"]');
        modeRadios.forEach(r => {
            r.checked = (r.value === state.mode);
        });

        // Set Cutoff Input
        const cutoffInput = document.getElementById('batchSetupCutoffInput');
        if (cutoffInput) {
            cutoffInput.value = state.cutoff || '';
        }

        onLabBatchModeChange();
        recalculateBatchesFromCutoff();
    }

    window.onLabBatchModeChange = function() {
        const selected = document.querySelector('input[name="labBatchModeRadio"]:checked');
        const mode = selected ? selected.value : 'split';
        window.labBatchSetupState.mode = mode;

        const splitArea = document.getElementById('splitBatchConfigArea');
        if (splitArea) {
            if (mode === 'full') {
                splitArea.classList.add('hidden');
            } else {
                splitArea.classList.remove('hidden');
            }
        }
    };

    window.onCutoffInputChange = function() {
        const val = document.getElementById('batchSetupCutoffInput').value;
        const cutoff = parseInt(val);
        if (!isNaN(cutoff) && cutoff > 0) {
            window.labBatchSetupState.cutoff = cutoff;
            recalculateBatchesFromCutoff();
        }
    };

    window.stepCutoff = function(delta) {
        const inp = document.getElementById('batchSetupCutoffInput');
        if (!inp) return;
        let cur = parseInt(inp.value) || 25;
        cur = Math.max(1, cur + delta);
        inp.value = cur;
        window.labBatchSetupState.cutoff = cur;
        recalculateBatchesFromCutoff();
    };

    window.setPresetCutoff = function(num) {
        window.labBatchSetupState.cutoff = num;
        const inp = document.getElementById('batchSetupCutoffInput');
        if (inp) inp.value = num;
        recalculateBatchesFromCutoff();
    };

    window.applyEqualSplit = function() {
        const students = window.labBatchSetupState.students;
        if (!students || students.length === 0) return;
        const mid = Math.ceil(students.length / 2);
        const midStudent = students[mid - 1];
        const cutoff = (midStudent && midStudent.roll_no) ? parseInt(midStudent.roll_no) : mid;
        setPresetCutoff(cutoff);
    };

    window.resetToInitialCutoff = function() {
        if (window.labBatchSetupState.initialCutoff) {
            setPresetCutoff(window.labBatchSetupState.initialCutoff);
        } else {
            applyEqualSplit();
        }
    };

    function recalculateBatchesFromCutoff() {
        const state = window.labBatchSetupState;
        const cutoff = state.cutoff;
        if (!cutoff) return;

        state.students.forEach((s, idx) => {
            if (s.roll_no !== null && s.roll_no !== undefined) {
                s.lab_batch = (parseInt(s.roll_no) <= cutoff) ? '1' : '2';
            } else {
                s.lab_batch = (idx < cutoff) ? '1' : '2';
            }
        });

        renderBatchSetupRoster();
        updateBatchSetupCounters();
    }

    function updateBatchSetupCounters() {
        const students = window.labBatchSetupState.students;
        const b1 = students.filter(s => s.lab_batch === '1');
        const b2 = students.filter(s => s.lab_batch === '2');

        const b1CountEl = document.getElementById('setupB1CountText');
        const b2CountEl = document.getElementById('setupB2CountText');
        if (b1CountEl) b1CountEl.innerText = b1.length;
        if (b2CountEl) b2CountEl.innerText = b2.length;

        const b1RangeEl = document.getElementById('setupB1RangeText');
        const b2RangeEl = document.getElementById('setupB2RangeText');

        if (b1.length > 0 && b1RangeEl) {
            const minR = Math.min(...b1.map(s => s.roll_no ? parseInt(s.roll_no) : 1));
            const maxR = Math.max(...b1.map(s => s.roll_no ? parseInt(s.roll_no) : b1.length));
            b1RangeEl.innerText = `Roll ${minR} - ${maxR}`;
        }
        if (b2.length > 0 && b2RangeEl) {
            const minR = Math.min(...b2.map(s => s.roll_no ? parseInt(s.roll_no) : (b1.length + 1)));
            const maxR = Math.max(...b2.map(s => s.roll_no ? parseInt(s.roll_no) : students.length));
            b2RangeEl.innerText = `Roll ${minR} - ${maxR}`;
        }
    }

    function renderBatchSetupRoster() {
        const tbody = document.getElementById('batchSetupStudentTbody');
        if (!tbody) return;

        const students = window.labBatchSetupState.students;
        tbody.innerHTML = '';

        const badgeEl = document.getElementById('rosterTotalCountBadge');
        if (badgeEl) badgeEl.innerText = `${students.length} Students`;

        students.forEach((s, idx) => {
            const tr = document.createElement('tr');
            tr.className = "border-b border-slate-800/40 hover:bg-slate-900/50 transition";
            tr.id = `batchSetupRow_${s.reg_no}`;
            tr.setAttribute('data-batch', s.lab_batch);

            const isB1 = (s.lab_batch === '1');
            tr.innerHTML = `
                <td class="py-2.5 px-3 text-center font-bold font-mono ${isB1 ? 'text-blue-400' : 'text-sky-400'}">${s.roll_no || (idx + 1)}</td>
                <td class="py-2.5 px-3 text-slate-400 font-mono text-[11px]">${s.reg_no}</td>
                <td class="py-2.5 px-3 font-medium text-slate-200">
                    <span class="text-white font-semibold">${s.name}</span>
                </td>
                <td class="py-2.5 px-3 text-center">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold ${isB1 ? 'bg-blue-950/70 text-blue-300 border border-blue-600/50' : 'bg-sky-950/70 text-sky-300 border border-sky-600/50'}">
                        <span class="w-1.5 h-1.5 rounded-full ${isB1 ? 'bg-blue-400' : 'bg-sky-400'}"></span>
                        ${isB1 ? 'Batch 1' : 'Batch 2'}
                    </span>
                </td>
                <td class="py-2.5 px-3 text-center">
                    <div class="inline-flex rounded-lg p-0.5 bg-slate-900 border border-slate-800 shadow-sm">
                        <button type="button" onclick="toggleStudentLabBatch('${s.reg_no}', '1')" class="px-2.5 py-1 rounded text-[10.5px] font-bold transition cursor-pointer ${isB1 ? 'bg-blue-600 text-white shadow' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800'}">B1</button>
                        <button type="button" onclick="toggleStudentLabBatch('${s.reg_no}', '2')" class="px-2.5 py-1 rounded text-[10.5px] font-bold transition cursor-pointer ${!isB1 ? 'bg-sky-600 text-white shadow' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800'}">B2</button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });

        filterBatchSetupStudentList();
    }

    window.toggleStudentLabBatch = function(regNo, newBatch) {
        const student = window.labBatchSetupState.students.find(s => s.reg_no === regNo);
        if (student) {
            student.lab_batch = newBatch;
            renderBatchSetupRoster();
            updateBatchSetupCounters();
        }
    };

    window.filterBatchSetupRosterTab = function(tab) {
        window.activeRosterTab = tab;
        ['all', '1', '2'].forEach(t => {
            const btn = document.getElementById(`rosterTab_${t}`);
            if (btn) {
                if (t === tab) {
                    btn.classList.add('bg-blue-600', 'text-white');
                    btn.classList.remove('text-slate-400');
                } else {
                    btn.classList.remove('bg-blue-600', 'text-white');
                    btn.classList.add('text-slate-400');
                }
            }
        });
        filterBatchSetupStudentList();
    };

    window.filterBatchSetupStudentList = function() {
        const q = (document.getElementById('batchSetupStudentSearch').value || '').toLowerCase().trim();
        const tab = window.activeRosterTab || 'all';
        const rows = document.querySelectorAll('#batchSetupStudentTbody tr');
        let visibleCount = 0;

        rows.forEach(r => {
            const rowBatch = r.getAttribute('data-batch') || '';
            const text = r.innerText.toLowerCase();
            const matchesSearch = !q || text.includes(q);
            const matchesTab = (tab === 'all') || (rowBatch === tab);

            if (matchesSearch && matchesTab) {
                r.style.display = '';
                visibleCount++;
            } else {
                r.style.display = 'none';
            }
        });

        const counter = document.getElementById('rosterShowingCounter');
        if (counter) counter.innerText = `Showing ${visibleCount} students`;
    };

    window.saveLabBatchSetup = async function() {
        const state = window.labBatchSetupState;
        if (!state.subjectId) return;

        const btn = document.getElementById('btnSaveLabBatchSetup');
        const origHtml = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="material-symbols-rounded text-sm animate-spin">progress_activity</span> Saving...`;
        }

        const applyAll = document.getElementById('batchSetupApplyAllCheckbox') ? document.getElementById('batchSetupApplyAllCheckbox').checked : true;

        const payload = {
            mode: state.mode,
            cutoff_roll: state.mode === 'split' ? state.cutoff : null,
            apply_to_classroom: applyAll,
            assignments: state.students.map(s => ({
                reg_no: s.reg_no,
                lab_batch: state.mode === 'full' ? '1' : s.lab_batch
            }))
        };

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            const res = await fetch(`/api/classroom/${state.subjectId}/practical/batch-setup`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (data.status === 'SUCCESS') {
                closeLabBatchSetupModal();
                if (typeof state.onSavedCallback === 'function') {
                    state.onSavedCallback(data);
                } else {
                    window.location.reload();
                }
            } else {
                alert(data.message || "Failed to save batch division.");
            }
        } catch (err) {
            console.error("Error saving lab batch division:", err);
            alert("An error occurred while saving lab batch division.");
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = origHtml;
            }
        }
    };

})();
</script>
