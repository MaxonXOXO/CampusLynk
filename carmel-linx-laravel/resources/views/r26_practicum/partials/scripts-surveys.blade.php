                    <script>
                        window.onload = function() {
                            setTimeout(function() { window.print(); }, 400);
                        }
                    <\/script>
                </body>
                </html>
            `);
            printWin.document.close();
        }

        function openMidsemInitModal() {
            document.getElementById('modal-midsem-survey-init-practicum').classList.remove('hidden');
        }
        function closeMidsemInitModal() {
            document.getElementById('modal-midsem-survey-init-practicum').classList.add('hidden');
        }
        function openExitInitModal() {
            document.getElementById('modal-exit-survey-init-practicum').classList.remove('hidden');
        }
        function closeExitInitModal() {
            document.getElementById('modal-exit-survey-init-practicum').classList.add('hidden');
        }

        function submitPracticumMidsemInit(event) {
            event.preventDefault();
            const questions = {
                q5: document.getElementById('p-ms-q5').value.trim(),
                q6: document.getElementById('p-ms-q6').value.trim(),
                q7: document.getElementById('p-ms-q7').value.trim(),
                q8: document.getElementById('p-ms-q8').value.trim(),
                q9: document.getElementById('p-ms-q9').value.trim(),
                q10: document.getElementById('p-ms-q10').value.trim(),
                q11: document.getElementById('p-ms-q11').value.trim(),
                q12: document.getElementById('p-ms-q12').value.trim()
            };

            fetch('/api/classroom/{{ $batchSubject->id }}/survey/initiate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ questions })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    Swal.fire('Published!', 'Mid-Semester survey initiated successfully and sent to student portal!', 'success');
                    closeMidsemInitModal();
                    checkPracticumSurveyStatuses();
                } else {
                    Swal.fire('Error', data.message || 'Failed to initiate survey', 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function submitPracticumExitInit(event) {
            event.preventDefault();
            const questions = {
                q1: document.getElementById('p-ex-q1').value.trim(),
                q2: document.getElementById('p-ex-q2').value.trim(),
                q3: document.getElementById('p-ex-q3').value.trim(),
                q4: document.getElementById('p-ex-q4').value.trim(),
                q5: document.getElementById('p-ex-q5').value.trim(),
                q6: document.getElementById('p-ex-q6').value.trim(),
                q7: document.getElementById('p-ex-q7').value.trim(),
                q8: document.getElementById('p-ex-q8').value.trim()
            };

            fetch('/api/classroom/{{ $batchSubject->id }}/course-exit/initiate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ questions })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    Swal.fire('Published!', 'Course Exit survey initiated successfully! Students notified in their Works To Do.', 'success');
                    closeExitInitModal();
                    checkPracticumSurveyStatuses();
                } else {
                    Swal.fire('Error', data.message || 'Failed to initiate survey', 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function controlPracticumSurvey(type, action) {
            const endpoint = type === 'midsem' ? '/api/classroom/{{ $batchSubject->id }}/survey/' + action : '/api/classroom/{{ $batchSubject->id }}/course-exit/' + action;
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    Swal.fire('Updated!', data.message, 'success');
                    checkPracticumSurveyStatuses();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function checkPracticumSurveyStatuses() {
            fetch('/api/classroom/{{ $batchSubject->id }}/survey/results')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('midsem-practicum-status-badge');
                    const openBtn = document.getElementById('btn-open-midsem-practicum');
                    const closeBtn = document.getElementById('btn-close-midsem-practicum');
                    if (data.status === 'INACTIVE') {
                        if (badge) { badge.innerText = 'Not Initiated'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-400 border border-slate-200'; }
                        if (openBtn) openBtn.classList.remove('hidden');
                        if (closeBtn) closeBtn.classList.add('hidden');
                    } else if (data.data && data.data.survey) {
                        const st = data.data.survey.status;
                        if (st === 'Active') {
                            if (badge) { badge.innerText = 'Active (' + data.data.responded_count + ' Submitted)'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40'; }
                            if (openBtn) openBtn.classList.add('hidden');
                            if (closeBtn) closeBtn.classList.remove('hidden');
                        } else {
                            if (badge) { badge.innerText = 'Closed / Locked (' + data.data.responded_count + ' Submitted)'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-700 border border-slate-200'; }
                            if (openBtn) openBtn.classList.remove('hidden');
                            if (closeBtn) closeBtn.classList.add('hidden');
                        }
                    }
                }).catch(() => {});

            fetch('/api/classroom/{{ $batchSubject->id }}/course-exit/results')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('exit-practicum-status-badge');
                    const openBtn = document.getElementById('btn-open-exit-practicum');
                    const closeBtn = document.getElementById('btn-close-exit-practicum');
                    if (data.status === 'INACTIVE') {
                        if (badge) { badge.innerText = 'Not Initiated'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-400 border border-slate-200'; }
                        if (openBtn) openBtn.classList.remove('hidden');
                        if (closeBtn) closeBtn.classList.add('hidden');
                    } else if (data.data && data.data.survey) {
                        const st = data.data.survey.status;
                        if (st === 'Active') {
                            if (badge) { badge.innerText = 'Active (' + data.data.responded_count + ' Submitted)'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-teal-500/20 text-teal-300 border border-teal-500/40'; }
                            if (openBtn) openBtn.classList.add('hidden');
                            if (closeBtn) closeBtn.classList.remove('hidden');
                        } else {
                            if (badge) { badge.innerText = 'Closed / Locked (' + data.data.responded_count + ' Submitted)'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-700 border border-slate-200'; }
                            if (openBtn) openBtn.classList.remove('hidden');
                            if (closeBtn) closeBtn.classList.add('hidden');
                        }
                    }
                }).catch(() => {});
        }

        document.addEventListener('DOMContentLoaded', function() {
            checkPracticumSurveyStatuses();
        });
    </script>
