        function openSlConfigModal() { document.getElementById('sl-config-modal').classList.remove('hidden'); }
        function closeSlConfigModal() { document.getElementById('sl-config-modal').classList.add('hidden'); }

        function saveSlConfig(e) {
            e.preventDefault();
            const form = document.getElementById('sl-config-form');
            const formData = new FormData(form);
            const configs = {};

            formData.forEach((val, key) => {
                const matches = key.match(/configs\[(.*?)\]\[(.*?)\]/);
                if (matches) {
                    const co = matches[1];
                    const act = matches[2];
                    if (!configs[co]) configs[co] = { assignment: true, mcq: true };
                    configs[co][act] = true;
                }
            });

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/self-learning/configs', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ configs: configs })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    closeSlConfigModal();
                    Swal.fire('Configured!', data.message, 'success');
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }

        const slConfigs = @json($slConfigs);
        const slSplitupState = @json($slStudentSplitup);
        const studentsList = @json($studentResults->values()->all());

        const activityLabels = {
            'assignment': 'Assignment',
            'mcq': 'MCQ',
            'case_study': 'Case Study',
            'quiz': 'Quiz',
            'activity': 'Activity',
            'microproject': 'Microproject',
            'mini_project': 'Mini Project',
            'report': 'Report',
            'exercises': 'Exercises',
            'presentation': 'Presentation'
        };

        function openSlMarksModal() {
            document.getElementById('sl-marks-modal').classList.remove('hidden');
            const sel = document.getElementById('sl-student-select');
            if (sel && sel.value) {
                loadSlStudent(sel.value);
            }
        }

        function closeSlMarksModal() {
            document.getElementById('sl-marks-modal').classList.add('hidden');
        }

        function loadSlStudent(regNo) {
            const container = document.getElementById('sl-sliders-container');
            if (!container) return;

            if (!slSplitupState[regNo]) {
                slSplitupState[regNo] = {
                    'CO1': { assignment: 0, mcq: 0 },
                    'CO2': { assignment: 0, mcq: 0 },
                    'CO3': { assignment: 0, mcq: 0 },
                    'CO4': { assignment: 0, mcq: 0 }
                };
            }

            let html = '';
            const cos = ['CO1', 'CO2', 'CO3', 'CO4'];

            cos.forEach(co => {
                const activeActs = slConfigs[co] || { assignment: true, mcq: true };
                const actKeys = Object.keys(activeActs).filter(k => activeActs[k]);

                html += `
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-200 font-mono text-xs">${co}</span>
                                <span>Assessment Activities</span>
                                <span class="text-xs text-slate-500 font-medium">(${actKeys.length} Active)</span>
                            </h4>
                            <span id="co-sum-${co}" class="text-xs font-bold text-slate-700 bg-white px-2.5 py-1 rounded-md border border-slate-200 shadow-2xs font-mono">
                                Avg: 0.0 / 15.0
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                `;

                actKeys.forEach(actKey => {
                    const label = activityLabels[actKey] || actKey.toUpperCase();
                    const currentVal = slSplitupState[regNo][co] ? (slSplitupState[regNo][co][actKey] || 0) : 0;

                    html += `
                        <div class="p-3.5 rounded-xl bg-white border border-slate-200 shadow-2xs space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 text-xs uppercase tracking-wide">${label}</span>
                                <span id="badge-${co}-${actKey}" class="px-2.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-mono text-xs font-bold border border-indigo-200">
                                    ${parseFloat(currentVal).toFixed(1)} / 15.0
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="stepSlSlider('${regNo}', '${co}', '${actKey}', -0.5)" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 border border-slate-200 font-bold text-slate-800 text-base flex items-center justify-center transition-colors shadow-2xs cursor-pointer">-</button>
                                <input type="range" id="slider-${co}-${actKey}" min="0" max="15" step="0.5" value="${currentVal}" oninput="syncSlSlider('${regNo}', '${co}', '${actKey}', this.value)" class="flex-1 accent-indigo-600 h-2 bg-slate-200 rounded-lg cursor-pointer">
                                <button type="button" onclick="stepSlSlider('${regNo}', '${co}', '${actKey}', 0.5)" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 border border-slate-200 font-bold text-slate-800 text-base flex items-center justify-center transition-colors shadow-2xs cursor-pointer">+</button>
                            </div>
                        </div>
                    `;
                });

                html += `
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            calculateSlLiveTotal(regNo);
        }

        function syncSlSlider(regNo, co, actKey, val) {
            const num = parseFloat(val) || 0;
            if (!slSplitupState[regNo]) slSplitupState[regNo] = {};
            if (!slSplitupState[regNo][co]) slSplitupState[regNo][co] = {};
            slSplitupState[regNo][co][actKey] = num;

            const badge = document.getElementById(`badge-${co}-${actKey}`);
            if (badge) badge.innerText = `${num.toFixed(1)} / 15.0`;

            calculateSlLiveTotal(regNo);
        }

        function stepSlSlider(regNo, co, actKey, delta) {
            const slider = document.getElementById(`slider-${co}-${actKey}`);
            if (!slider) return;

            let current = parseFloat(slider.value) || 0;
            let next = Math.max(0, Math.min(15, current + delta));
            slider.value = next;
            syncSlSlider(regNo, co, actKey, next);
        }

        function calculateSlLiveTotal(regNo) {
            const data = slSplitupState[regNo] || {};
            let totalScore = 0;
            let totalCount = 0;

            ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
                const coData = data[co] || {};
                let coSum = 0;
                let coCnt = 0;
                Object.values(coData).forEach(val => {
                    coSum += parseFloat(val) || 0;
                    coCnt++;
                });

                const coSumSpan = document.getElementById(`co-sum-${co}`);
                if (coSumSpan) {
                    const coAvg = coCnt > 0 ? (coSum / coCnt) : 0;
                    coSumSpan.innerText = `Avg: ${coAvg.toFixed(2)} / 15.0`;
                }

                totalScore += coSum;
                totalCount += coCnt;
            });

            const overallAvg = totalCount > 0 ? (totalScore / totalCount) : 0;
            const ciaConverted = Math.min(5.0, (overallAvg / 15.0) * 5.0);

            const rawElem = document.getElementById('sl-student-total-raw');
            const ciaElem = document.getElementById('sl-student-converted-cia');

            if (rawElem) rawElem.innerText = `${overallAvg.toFixed(2)} / 15.00 M`;
            if (ciaElem) ciaElem.innerText = `${ciaConverted.toFixed(2)} / 5.00 M`;
        }

        function prevSlStudent() {
            const sel = document.getElementById('sl-student-select');
            if (!sel || sel.selectedIndex <= 0) return;
            sel.selectedIndex--;
            loadSlStudent(sel.value);
        }

        function nextSlStudent() {
            const sel = document.getElementById('sl-student-select');
            if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
            sel.selectedIndex++;
            loadSlStudent(sel.value);
        }

        function saveAndNextSlStudent() {
            nextSlStudent();
        }

        function saveAllSlMarks() {
            const marksData = [];
            Object.keys(slSplitupState).forEach(regNo => {
                marksData.push({
                    reg_no: regNo,
                    co_details: slSplitupState[regNo]
                });
            });

            Swal.fire({
                title: 'Saving All Student Marks...',
                text: 'Updating activity-wise splitup for CA1',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/self-learning/marks', {
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
                    closeSlMarksModal();
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

