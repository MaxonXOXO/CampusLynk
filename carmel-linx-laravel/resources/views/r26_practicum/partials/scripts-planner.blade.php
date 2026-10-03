        function filterTheoryPlannerRows() {
            const search = (document.getElementById('theoryPlannerSearch')?.value || '').toLowerCase().trim();
            const co = document.getElementById('theoryPlannerCOFilter')?.value || 'ALL';
            const status = document.getElementById('theoryPlannerStatusFilter')?.value || 'ALL';

            const rows = document.querySelectorAll('#theory-subcontent-planner tr.theory-planner-row');
            let visibleCount = 0;

            rows.forEach(tr => {
                const rowCo = tr.getAttribute('data-co') || '';
                const rowStatus = tr.getAttribute('data-status') || '';
                const topic = (tr.querySelector('textarea[id^="lp-topic-"]')?.value || '').toLowerCase();
                const sessionText = (tr.querySelector('td:first-child')?.innerText || '').toLowerCase();
                const remarks = (tr.querySelector('input[id^="lp-remarks-"]')?.value || '').toLowerCase();
                const pedagogy = (tr.querySelector('select[id^="lp-pedagogy-"]')?.value || '').toLowerCase();

                let matchSearch = !search || topic.includes(search) || sessionText.includes(search) || remarks.includes(search) || pedagogy.includes(search) || rowCo.toLowerCase().includes(search);
                let matchCo = (co === 'ALL') || (rowCo === co);
                let matchStatus = (status === 'ALL') || (rowStatus === status);

                if (matchSearch && matchCo && matchStatus) {
                    tr.classList.remove('hidden');
                    visibleCount++;
                } else {
                    tr.classList.add('hidden');
                }
            });

            const countEl = document.getElementById('theoryPlannerCount');
            if (countEl) {
                countEl.innerText = `Showing ${visibleCount} of ${rows.length} sessions`;
            }
        }

        function onTheoryActualDateChange(planId, value) {
            const tr = document.getElementById('lp-row-' + planId);
            const pill = document.getElementById('lp-status-pill-' + planId);
            const isCompleted = Boolean(value && value.trim() !== '');

            if (tr) {
                tr.setAttribute('data-status', isCompleted ? 'Completed' : 'Pending');
                if (isCompleted) {
                    tr.classList.add('bg-emerald-50/15');
                } else {
                    tr.classList.remove('bg-emerald-50/15');
                }
            }

            if (pill) {
                if (isCompleted) {
                    pill.className = "px-2.5 py-1 rounded-full text-xs font-bold border shadow-2xs bg-emerald-50 text-emerald-700 border-emerald-200/80";
                    pill.innerText = 'Completed';
                } else {
                    pill.className = "px-2.5 py-1 rounded-full text-xs font-bold border shadow-2xs bg-amber-50 text-amber-700 border-amber-200/80";
                    pill.innerText = 'Pending';
                }
            }

            updateTheoryMetrics();
        }

        function updateTheoryMetrics() {
            const rows = document.querySelectorAll('#theory-subcontent-planner tr.theory-planner-row');
            let completed = 0;

            rows.forEach(tr => {
                const actInput = tr.querySelector('input[id^="lp-act-"]');
                if (actInput && actInput.value && actInput.value.trim() !== '') {
                    completed++;
                }
            });

            const total = 45;
            const remaining = Math.max(0, total - completed);
            const coverage = Math.round((completed / total) * 100);

            const completedEl = document.getElementById('theoryMetricCompleted');
            const completedSubEl = document.getElementById('theoryMetricCompletedSub');
            const remainingEl = document.getElementById('theoryMetricRemaining');
            const remainingSubEl = document.getElementById('theoryMetricRemainingSub');
            const coverageEl = document.getElementById('theoryMetricCoverage');
            const coverageBadge = document.getElementById('theoryMetricCoverageBadge');
            const progressBar = document.getElementById('theoryMetricProgressBar');

            if (completedEl) completedEl.innerHTML = `${completed} <span class="text-xs font-bold text-emerald-600/70">Hrs</span>`;
            if (completedSubEl) completedSubEl.innerText = `${completed} sessions conducted`;
            if (remainingEl) remainingEl.innerHTML = `${remaining} <span class="text-xs font-bold text-slate-500">Hrs</span>`;
            if (remainingSubEl) remainingSubEl.innerText = `${remaining} sessions pending`;
            if (coverageEl) coverageEl.innerText = `${coverage}%`;
            if (coverageBadge) coverageBadge.innerText = `${coverage}%`;
            if (progressBar) progressBar.style.width = `${coverage}%`;
        }

        function filterLabPlannerRows() {
            const search = (document.getElementById('labPlannerSearch')?.value || '').toLowerCase().trim();
            const co = document.getElementById('labPlannerCOFilter')?.value || 'ALL';
            const batch = document.getElementById('labPlannerBatchFilter')?.value || 'ALL';
            const status = document.getElementById('labPlannerStatusFilter')?.value || 'ALL';

            const rows = document.querySelectorAll('#lab-subcontent-planner tr.lab-planner-row');
            let visibleCount = 0;

            rows.forEach(tr => {
                const rowCo = tr.getAttribute('data-co') || '';
                const rowBatch = tr.getAttribute('data-batch') || '';
                const rowStatus = tr.getAttribute('data-status') || '';
                const topic = (tr.querySelector('textarea[id^="lp-topic-"]')?.value || '').toLowerCase();
                const sessionText = (tr.querySelector('td:first-child')?.innerText || '').toLowerCase();
                const remarks = (tr.querySelector('input[id^="lp-remarks-"]')?.value || '').toLowerCase();
                const pedagogy = (tr.querySelector('select[id^="lp-pedagogy-"]')?.value || '').toLowerCase();

                let matchSearch = !search || topic.includes(search) || sessionText.includes(search) || remarks.includes(search) || pedagogy.includes(search) || rowCo.toLowerCase().includes(search) || rowBatch.toLowerCase().includes(search);
                let matchCo = (co === 'ALL') || (rowCo === co);
                let matchBatch = (batch === 'ALL') || (rowBatch === batch);
                let matchStatus = (status === 'ALL') || (rowStatus === status);

                if (matchSearch && matchCo && matchBatch && matchStatus) {
                    tr.classList.remove('hidden');
                    visibleCount++;
                } else {
                    tr.classList.add('hidden');
                }
            });

            const countEl = document.getElementById('labPlannerCount');
            if (countEl) {
                countEl.innerText = `Showing ${visibleCount} of ${rows.length} lab sessions`;
            }
        }

        function onLabActualDateChange(planId, value) {
            const tr = document.getElementById('lp-row-' + planId);
            const pill = document.getElementById('lp-status-pill-' + planId);
            const isCompleted = Boolean(value && value.trim() !== '');

            if (tr) {
                tr.setAttribute('data-status', isCompleted ? 'Completed' : 'Pending');
                if (isCompleted) {
                    tr.classList.add('bg-emerald-50/15');
                } else {
                    tr.classList.remove('bg-emerald-50/15');
                }
            }

            if (pill) {
                if (isCompleted) {
                    pill.className = "px-2.5 py-1 rounded-full text-xs font-bold border shadow-2xs bg-emerald-50 text-emerald-700 border-emerald-200/80";
                    pill.innerText = 'Completed';
                } else {
                    pill.className = "px-2.5 py-1 rounded-full text-xs font-bold border shadow-2xs bg-amber-50 text-amber-700 border-amber-200/80";
                    pill.innerText = 'Pending';
                }
            }

            updateLabMetrics();
        }

        function onLabBatchChange(planId, value) {
            const tr = document.getElementById('lp-row-' + planId);
            if (tr) {
                tr.setAttribute('data-batch', value);
            }
            filterLabPlannerRows();
        }

        function updateLabMetrics() {
            const rows = document.querySelectorAll('#lab-subcontent-planner tr.lab-planner-row');
            let completedBlocks = 0;

            rows.forEach(tr => {
                const actInput = tr.querySelector('input[id^="lp-act-"]');
                if (actInput && actInput.value && actInput.value.trim() !== '') {
                    completedBlocks++;
                }
            });

            const totalSessions = 15;
            const totalHours = 45;
            const completedHours = completedBlocks * 3;
            const remainingHours = Math.max(0, totalHours - completedHours);
            const remainingBlocks = Math.max(0, totalSessions - completedBlocks);
            const coverage = Math.round((completedHours / totalHours) * 100);

            const completedEl = document.getElementById('labMetricCompleted');
            const completedSubEl = document.getElementById('labMetricCompletedSub');
            const remainingEl = document.getElementById('labMetricRemaining');
            const remainingSubEl = document.getElementById('labMetricRemainingSub');
            const coverageEl = document.getElementById('labMetricCoverage');
            const coverageBadge = document.getElementById('labMetricCoverageBadge');
            const progressBar = document.getElementById('labMetricProgressBar');

            if (completedEl) completedEl.innerHTML = `${completedHours} <span class="text-xs font-bold text-emerald-600/70">Hrs</span>`;
            if (completedSubEl) completedSubEl.innerText = `${completedBlocks} of ${totalSessions} sessions completed`;
            if (remainingEl) remainingEl.innerHTML = `${remainingHours} <span class="text-xs font-bold text-slate-500">Hrs</span>`;
            if (remainingSubEl) remainingSubEl.innerText = `${remainingBlocks} sessions pending`;
            if (coverageEl) coverageEl.innerText = `${coverage}%`;
            if (coverageBadge) coverageBadge.innerText = `${coverage}%`;
            if (progressBar) progressBar.style.width = `${coverage}%`;
        }

        function saveAllLessonPlans() {
            const rows = document.querySelectorAll('tr[id^="lp-row-"]');
            const plans = [];

            rows.forEach(tr => {
                const planId = tr.getAttribute('data-plan-id');
                if (!planId) return;

                const blockIdsAttr = tr.getAttribute('data-block-ids');
                const targetIds = blockIdsAttr ? blockIdsAttr.split(',') : [planId];

                const pedagogy = document.getElementById('lp-pedagogy-' + planId)?.value || 'Lecture (L)';
                const propDate = document.getElementById('lp-prop-' + planId)?.value || '';
                const actDate = document.getElementById('lp-act-' + planId)?.value || '';
                const topic = document.getElementById('lp-topic-' + planId)?.value || '';
                const coId = document.getElementById('lp-co-' + planId)?.value || 'CO1';
                const batch = document.getElementById('lp-batch-' + planId)?.value || '';
                const remarks = document.getElementById('lp-remarks-' + planId)?.value || '';

                targetIds.forEach(id => {
                    plans.push({
                        id: id,
                        pedagogy: pedagogy,
                        proposed_date: propDate,
                        actual_date: actDate,
                        topic_content: topic,
                        co_id: coId,
                        sub_batch: batch,
                        remarks: remarks
                    });
                });
            });

            const buttons = [
                document.getElementById('btnSaveTheoryPlanner'),
                document.getElementById('btnSaveLabPlanner')
            ].filter(Boolean);

            const originalBtnHtmls = buttons.map(b => b.innerHTML);
            buttons.forEach(b => {
                b.disabled = true;
                b.innerHTML = `<svg class="w-3.5 h-3.5 animate-spin text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> <span>Saving...</span>`;
            });

            Swal.fire({
                title: 'Saving All 90 Hours...',
                text: 'Updating complete Practicum lesson plan',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/lesson-plan/save-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ plans: plans })
            })
            .then(res => res.json())
            .then(data => {
                buttons.forEach((b, idx) => {
                    b.disabled = false;
                    b.innerHTML = originalBtnHtmls[idx];
                });
                if (data.status === 'SUCCESS') {
                    Swal.fire('Saved Successfully!', data.message, 'success');
                    updateTheoryMetrics();
                    updateLabMetrics();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => {
                buttons.forEach((b, idx) => {
                    b.disabled = false;
                    b.innerHTML = originalBtnHtmls[idx];
                });
                Swal.fire('Error', err.message, 'error');
            });
        }

        function onPedagogyChange(planId, val) {
            const batchTd = document.getElementById('lp-batch-td-' + planId);
            const hoursTd = document.getElementById('lp-hours-td-' + planId);
            const select = document.getElementById('lp-pedagogy-' + planId);
            if (!batchTd) return;

            const isLab = val.includes('Practical') || val.includes('Lab') || val.includes('(P)') || val.includes('(SP)');

            if (select) {
                select.className = "w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-sm font-medium transition-all outline-none cursor-pointer " + 
                    (isLab ? "text-emerald-700" : (val.includes('Series') ? "text-purple-700" : "text-blue-700"));
            }

            if (hoursTd) {
                if (isLab) {
                    hoursTd.innerHTML = `<span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-xs font-bold">3 Hours</span>`;
                } else {
                    hoursTd.innerHTML = `<span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200/80 text-xs font-bold">1 Hour</span>`;
                }
            }

            if (isLab) {
                batchTd.innerHTML = `
                    <select id="lp-batch-${planId}" onchange="onLabBatchChange(${planId}, this.value)" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 rounded-xl px-2.5 py-2 font-bold text-xs text-emerald-700 outline-none cursor-pointer">
                        <option value="Batch A & B" selected>Batch A & B (Combined)</option>
                        <option value="Batch A">Batch A</option>
                        <option value="Batch B">Batch B</option>
                    </select>
                `;
            } else {
                batchTd.innerHTML = `
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-bold text-xs border border-slate-200/80 inline-block">
                        All Students
                    </span>
                    <input type="hidden" id="lp-batch-${planId}" value="All Students">
                `;
            }
        function insertLessonPlanRowAfter(targetRowId, defaultMode) {
            const targetTr = document.getElementById(targetRowId);
            if (!targetTr) return;

            const tbody = targetTr.closest('tbody');
            if (!tbody) return;

            const isLab = defaultMode === 'P' || targetTr.closest('#lab-subcontent-planner') !== null;
            const mode = isLab ? 'P' : 'L';
            const newId = 'new_' + Date.now() + '_' + Math.floor(Math.random() * 1000);

            const targetPlanId = targetTr.getAttribute('data-plan-id');
            const targetCo = document.getElementById('lp-co-' + targetPlanId)?.value || 'CO1';
            const targetPropDate = document.getElementById('lp-prop-' + targetPlanId)?.value || '';

            const tr = document.createElement('tr');
            tr.id = `lp-row-${newId}`;
            tr.setAttribute('data-plan-id', newId);
            tr.setAttribute('data-co', targetCo);
            tr.setAttribute('data-status', 'Pending');
            tr.className = `${isLab ? 'lab-planner-row' : 'theory-planner-row'} hover:bg-slate-50/70 transition-colors bg-sky-50/30 border-l-4 border-sky-500`;

            tr.innerHTML = `
                <td class="p-3 font-mono font-bold text-center text-slate-900 text-sm">
                    <span class="px-2 py-0.5 rounded-md bg-sky-50 text-sky-700 border border-sky-200 font-bold text-[11px]">NEW</span>
                </td>
                <td class="p-2.5">
                    <select id="lp-pedagogy-${newId}" onchange="onPedagogyChange('${newId}', this.value)" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-sm font-medium transition-all outline-none cursor-pointer ${mode === 'P' ? 'text-emerald-700' : 'text-blue-700'}">
                        <option value="Lecture (L)" ${mode === 'L' ? 'selected' : ''}>Lecture (L)</option>
                        <option value="Practical Lab (P)" ${mode === 'P' ? 'selected' : ''}>Practical Lab (P)</option>
                        <option value="Theory Series Exam (ST)">Theory Series Exam (ST)</option>
                        <option value="Practical Series Exam (SP)">Practical Series Exam (SP)</option>
                        <option value="PPT Presentation">PPT Presentation</option>
                        <option value="Demonstration">Demonstration</option>
                        <option value="Group Activity">Group Activity</option>
                    </select>
                </td>
                <td class="p-2.5">
                    <input type="date" id="lp-prop-${newId}" value="${targetPropDate}" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-slate-800 text-sm font-mono transition-all outline-none">
                </td>
                <td class="p-2.5">
                    <input type="date" id="lp-act-${newId}" value="" onchange="${isLab ? `onLabActualDateChange('${newId}', this.value)` : `onTheoryActualDateChange('${newId}', this.value)`}" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-slate-800 text-sm font-mono transition-all outline-none">
                </td>
                <td class="p-2.5">
                    <textarea id="lp-topic-${newId}" rows="2" placeholder="Enter lesson topic description..." class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-3 py-2 text-slate-900 text-sm font-normal transition-all outline-none resize-y leading-snug"></textarea>
                </td>
                <td class="p-2.5 text-center">
                    <select id="lp-co-${newId}" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 rounded-xl px-2 py-1.5 font-mono text-xs font-bold text-slate-700 outline-none cursor-pointer">
                        <option value="CO1" ${targetCo === 'CO1' ? 'selected' : ''}>CO1</option>
                        <option value="CO2" ${targetCo === 'CO2' ? 'selected' : ''}>CO2</option>
                        <option value="CO3" ${targetCo === 'CO3' ? 'selected' : ''}>CO3</option>
                        <option value="CO4" ${targetCo === 'CO4' ? 'selected' : ''}>CO4</option>
                        <option value="CO5" ${targetCo === 'CO5' ? 'selected' : ''}>CO5</option>
                        <option value="CO6" ${targetCo === 'CO6' ? 'selected' : ''}>CO6</option>
                    </select>
                </td>
                <td id="lp-batch-td-${newId}" class="p-2.5 text-center">
                    <select id="lp-batch-${newId}" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 rounded-xl px-2 py-1.5 font-mono text-xs text-emerald-700 outline-none cursor-pointer text-center">
                        <option value="ALL" ${mode === 'L' ? 'selected' : ''}>ALL</option>
                        <option value="Batch A & B" ${mode === 'P' ? 'selected' : ''}>Batch A & B</option>
                        <option value="Batch A">Batch A</option>
                        <option value="Batch B">Batch B</option>
                    </select>
                </td>
                <td id="lp-hours-td-${newId}" class="p-2.5 text-center font-normal">
                    <span class="px-2.5 py-1 rounded-lg ${mode === 'P' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/80' : 'bg-blue-50 text-blue-700 border border-blue-200/80'} text-xs font-bold">${mode === 'P' ? '3 Hours' : '1 Hour'}</span>
                </td>
                <td id="lp-status-td-${newId}" class="p-2.5 text-center">
                    <span id="lp-status-pill-${newId}" class="px-2.5 py-1 rounded-full text-xs font-bold border shadow-2xs bg-amber-50 text-amber-700 border-amber-200/80">Pending</span>
                </td>
                <td class="p-2.5">
                    <div class="flex items-center gap-1.5">
                        <input type="text" id="lp-remarks-${newId}" value="" placeholder="Status/Remarks" class="flex-1 bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-3 py-2 text-slate-800 text-sm font-normal transition-all outline-none">
                        <button type="button" onclick="removeLessonPlanRow('lp-row-${newId}')" title="Remove row" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 transition-all text-sm font-bold cursor-pointer shrink-0">&times;</button>
                    </div>
                </td>
            `;

            targetTr.after(tr);
            const topicTextarea = document.getElementById('lp-topic-' + newId);
            if (topicTextarea) {
                topicTextarea.focus();
            }
        }

        function removeLessonPlanRow(rowId) {
            const tr = document.getElementById(rowId);
            if (tr) tr.remove();
        }

    // =====================================================================
    // Series QP Generator — Preview / Edit Modal System
    // =====================================================================

    const SUBJECT_ID = {{ $batchSubject->id }};
    const QP_PATTERN = '{{ ($subjectType['pattern'] ?? 'table_4_1_standard') }}';
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    let _currentSeries = '', _currentCo = '', _currentPattern = QP_PATTERN, _draftQp = {};
 
