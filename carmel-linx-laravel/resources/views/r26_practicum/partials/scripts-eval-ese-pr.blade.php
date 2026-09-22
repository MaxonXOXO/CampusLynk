        function openEsePracticalModal() {
            document.getElementById('ese-practical-modal').classList.remove('hidden');
            const sel = document.getElementById('ese-student-select');
            if (sel && sel.value) {
                loadEseStudent(sel.value);
            }
        }

        function closeEsePracticalModal() {
            document.getElementById('ese-practical-modal').classList.add('hidden');
        }

        const eseRubrics = [
            { key: 'writeup', label: 'Procedure & Writeup', max: 10 },
            { key: 'setup', label: 'Setup & Circuit Execution', max: 10 },
            { key: 'result', label: 'Observation & Result', max: 8 },
            { key: 'viva', label: 'Viva-Voce Examination', max: 8 },
            { key: 'record', label: 'Record & Logbook', max: 4 }
        ];

        function loadEseStudent(regNo) {
            const container = document.getElementById('ese-sliders-container');
            if (!container) return;

            if (!eseSplitupState[regNo]) {
                eseSplitupState[regNo] = { writeup: 0, setup: 0, result: 0, viva: 0, record: 0 };
            }

            let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-3">';

            eseRubrics.forEach(rub => {
                const currentVal = eseSplitupState[regNo][rub.key] || 0;
                html += `
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-700 text-xs">${rub.label}</span>
                            <span id="ese-badge-${rub.key}" class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-mono text-xs font-bold border border-blue-200">
                                ${parseFloat(currentVal).toFixed(1)} / ${rub.max}.0
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="stepEseSlider('${regNo}', '${rub.key}', -0.5, ${rub.max})" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 font-extrabold text-slate-700 text-base flex items-center justify-center transition-colors border border-slate-300">-</button>
                            <input type="range" id="ese-slider-${rub.key}" min="0" max="${rub.max}" step="0.5" value="${currentVal}" oninput="syncEseSlider('${regNo}', '${rub.key}', this.value, ${rub.max})" class="flex-1 accent-blue-600 h-2 rounded-lg cursor-pointer">
                            <button type="button" onclick="stepEseSlider('${regNo}', '${rub.key}', 0.5, ${rub.max})" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 font-extrabold text-slate-700 text-base flex items-center justify-center transition-colors border border-slate-300">+</button>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            container.innerHTML = html;
            calculateEseLiveTotal(regNo);
        }

        function syncEseSlider(regNo, key, val, maxVal) {
            const num = Math.min(maxVal, Math.max(0, parseFloat(val) || 0));
            if (!eseSplitupState[regNo]) eseSplitupState[regNo] = {};
            eseSplitupState[regNo][key] = num;

            const badge = document.getElementById(`ese-badge-${key}`);
            if (badge) badge.innerText = `${num.toFixed(1)} / ${maxVal}.0`;

            calculateEseLiveTotal(regNo);
        }

        function stepEseSlider(regNo, key, delta, maxVal) {
            const slider = document.getElementById(`ese-slider-${key}`);
            if (!slider) return;

            let current = parseFloat(slider.value) || 0;
            let next = Math.max(0, Math.min(maxVal, current + delta));
            slider.value = next;
            syncEseSlider(regNo, key, next, maxVal);
        }

        function calculateEseLiveTotal(regNo) {
            const data = eseSplitupState[regNo] || {};
            const total = (data.writeup || 0) + (data.setup || 0) + (data.result || 0) + (data.viva || 0) + (data.record || 0);

            const rawElem = document.getElementById('ese-student-total-raw');
            const gradeBadge = document.getElementById('ese-student-grade-badge');

            if (rawElem) rawElem.innerText = `${Math.round(total)} / 40 Marks`;

            const pct = (total / 40) * 100;
            let grade = 'F';
            let gClass = 'text-rose-600 bg-rose-500/20 border-rose-500/30';
            if (pct >= 90) { grade = 'S'; gClass = 'text-emerald-600 bg-emerald-500/20 border-emerald-500/30'; }
            else if (pct >= 80) { grade = 'A'; gClass = 'text-blue-700 bg-blue-50 border border-blue-200'; }
            else if (pct >= 70) { grade = 'B'; gClass = 'text-indigo-600 bg-indigo-500/20 border-indigo-500/30'; }
            else if (pct >= 60) { grade = 'C'; gClass = 'text-violet-600 bg-purple-500/20 border-purple-500/30'; }
            else if (pct >= 50) { grade = 'D'; gClass = 'text-amber-600 bg-amber-500/20 border-amber-500/30'; }
            else if (pct >= 40) { grade = 'E'; gClass = 'text-orange-400 bg-orange-500/20 border-orange-500/30'; }

            if (gradeBadge) {
                gradeBadge.innerText = grade;
                gradeBadge.className = `font-black text-base px-3 py-0.5 rounded-full border ${gClass}`;
            }

            const row = document.getElementById(`ese-row-${regNo}`);
            if (row) {
                const w = row.querySelector('.ese-val-writeup'); if (w) w.innerText = (data.writeup || 0).toFixed(1);
                const s = row.querySelector('.ese-val-setup'); if (s) s.innerText = (data.setup || 0).toFixed(1);
                const r = row.querySelector('.ese-val-result'); if (r) r.innerText = (data.result || 0).toFixed(1);
                const v = row.querySelector('.ese-val-viva'); if (v) v.innerText = (data.viva || 0).toFixed(1);
                const rec = row.querySelector('.ese-val-record'); if (rec) rec.innerText = (data.record || 0).toFixed(1);
                const tot = row.querySelector('.ese-val-total'); if (tot) tot.innerText = Math.round(total);
                const gr = row.querySelector('.ese-val-grade');
                if (gr) gr.innerHTML = `<span class="px-2.5 py-0.5 rounded-full border text-xs font-bold ${gClass}">${grade}</span>`;
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
            const sel = document.getElementById('ese-student-select');
            if (!sel) return;
            if (sel.selectedIndex < sel.options.length - 1) {
                sel.selectedIndex++;
                loadEseStudent(sel.value);
            } else {
                Swal.fire('End of List', 'Reached last student in list.', 'info');
            }
        }

        function saveAllEseMarks() {
            const marksData = studentsList.map(st => {
                const splitup = eseSplitupState[st.reg_no] || { writeup: 0, setup: 0, result: 0, viva: 0, record: 0 };
                const totalScore = (splitup.writeup || 0) + (splitup.setup || 0) + (splitup.result || 0) + (splitup.viva || 0) + (splitup.record || 0);
                return {
                    reg_no: st.reg_no,
                    ese_practical_marks: totalScore
                };
            });

            Swal.fire({
                title: 'Saving Practical ESE Marks...',
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
                    closeEsePracticalModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved Successfully!',
                        text: 'Practical ESE marks and grades saved!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to save ESE marks', 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function openEseTheoryModal() {
            const modal = document.getElementById('ese-theory-modal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeEseTheoryModal() {
            const modal = document.getElementById('ese-theory-modal');
            if (modal) modal.classList.add('hidden');
        }

        function saveAllEseTheoryGrades() {
            const marksData = studentsList.map(st => {
                const elem = document.getElementById('ese-theory-grade-' + st.reg_no);
                return {
                    reg_no: st.reg_no,
                    ese_theory_grade: elem ? elem.value : ''
                };
            });

            Swal.fire({
                title: 'Saving Theory ESE Grades...',
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
                        text: 'Board Theory ESE grades saved!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed to save ESE grades', 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function printSubtabReport(reportTitle, containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;

            const clone = container.cloneNode(true);
            
            // Remove non-printable elements, buttons, inputs, dropdowns, QP generator cards
            clone.querySelectorAll('button, select, input, .no-print, #qp-gen-status').forEach(el => el.remove());

            const collegeName = "CARMEL COLLEGE OF ENGINEERING & TECHNOLOGY, ALAPPUZHA";
            const branchName = @json(function_exists('getFullBranchName') ? getFullBranchName($classroom->department ?? $classroom->branch ?? '') : ($classroom->department ?? $classroom->branch));
            const subjectName = @json($batchSubject->subject_name);
            const subjectCode = @json($batchSubject->subject_code);
            const batchCode = @json($batchSubject->classroom_id);
            const semester = @json($practicumCourseFile->semester);
            const facultyName = @json(Session::get('userName') ?? 'Faculty In-Charge');
            const eseMarks = @json($practicumCourseFile->ese_marks);
            const todayStr = new Date().toLocaleDateString('en-GB');

            const printWin = window.open('', '_blank', 'width=1150,height=850');
            printWin.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${reportTitle} - ${subjectName}</title>
                    <style>
                        @page {
                            size: A4 landscape;
                            margin: 12mm 10mm 12mm 10mm;
                        }
                        body {
                            font-family: 'Times New Roman', Times, serif;
                            color: #000;
                            background: #fff;
                            margin: 0;
                            padding: 10px;
                            font-size: 11px;
                            line-height: 1.35;
                        }
                        .header-container {
                            text-align: center;
                            border-bottom: 2px double #000;
                            padding-bottom: 8px;
                            margin-bottom: 12px;
                        }
                        .college-title {
                            font-size: 17px;
                            font-weight: bold;
                            text-transform: uppercase;
                            margin-bottom: 3px;
                            color: #000;
                            letter-spacing: 0.5px;
                        }
                        .dept-title {
                            font-size: 12px;
                            font-weight: bold;
                            text-transform: uppercase;
                            margin-bottom: 4px;
                            color: #111;
                        }
                        .report-badge {
                            font-size: 13px;
                            font-weight: bold;
                            text-transform: uppercase;
                            margin: 6px 0;
                            color: #000;
                            text-decoration: underline;
                        }
                        .meta-table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 8px;
                            margin-bottom: 12px;
                            font-size: 11px;
                        }
                        .meta-table td {
                            padding: 5px 8px;
                            border: 1px solid #000;
                            width: 50%;
                            color: #000;
                            background: #fafafa;
                        }
                        .meta-table td strong {
                            color: #000;
                        }
                        /* FORCE CLEAN BLACK AND WHITE FOR PRINT CONTENT */
                        .print-content * {
                            box-shadow: none !important;
                            text-shadow: none !important;
                        }
                        .print-content div {
                            border-radius: 0 !important;
                            background: transparent !important;
                            border: none !important;
                        }
                        .print-content p, .print-content span, .print-content h3, .print-content h4 {
                            color: #000 !important;
                        }
                        table {
                            width: 100% !important;
                            border-collapse: collapse !important;
                            margin-top: 8px !important;
                            margin-bottom: 12px !important;
                            page-break-inside: auto;
                        }
                        tr {
                            page-break-inside: avoid;
                            page-break-after: auto;
                        }
                        th {
                            border: 1px solid #000 !important;
                            padding: 6px 6px !important;
                            background-color: #f1f5f9 !important;
                            color: #000 !important;
                            font-size: 11px !important;
                            font-weight: bold !important;
                            text-transform: uppercase !important;
                            text-align: center !important;
                        }
                        td {
                            border: 1px solid #000 !important;
                            padding: 5px 6px !important;
                            color: #000 !important;
                            font-size: 11px !important;
                            background: #fff !important;
                        }
                        td.text-center, th.text-center {
                            text-align: center !important;
                        }
                        .signatures-table {
                            width: 100%;
                            margin-top: 45px;
                            page-break-inside: avoid;
                        }
                        .signatures-table td {
                            width: 33.33%;
                            text-align: center;
                            padding-top: 5px;
                            font-weight: bold;
                            font-size: 12px;
                            border: none !important;
                            border-top: 1px solid #000 !important;
                            background: transparent !important;
                            color: #000 !important;
                        }
                        .footer-note {
                            margin-top: 20px;
                            font-size: 9px;
                            text-align: right;
                            color: #555;
                            border-top: 1px dashed #ccc;
                            padding-top: 4px;
                        }
                        @media print {
                            body { padding: 0; margin: 0; }
                            button, select, input, .no-print { display: none !important; }
                            .meta-table td, th {
                                background-color: #f1f5f9 !important;
                                -webkit-print-color-adjust: exact !important;
                                print-color-adjust: exact !important;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="header-container">
                        <div class="college-title">${collegeName}</div>
                        <div class="dept-title">DEPARTMENT OF ${branchName.toUpperCase()}</div>
                        <div class="report-badge">${reportTitle}</div>
                        
                        <table class="meta-table">
                            <tr>
                                <td><strong>Course Name & Code:</strong> ${subjectName} (${subjectCode})</td>
                                <td><strong>Batch Code:</strong> ${batchCode}</td>
                            </tr>
                            <tr>
                                <td><strong>Branch:</strong> ${branchName}</td>
                                <td><strong>Semester / Scheme:</strong> Semester ${semester} (Rev 2026)</td>
                            </tr>
                            <tr>
                                <td><strong>Assessment Year:</strong> 2026 – 2027</td>
                                <td><strong>Date of Report:</strong> ${todayStr}</td>
                            </tr>
                            <tr>
                                <td><strong>Faculty In-Charge:</strong> ${facultyName}</td>
                                <td><strong>Evaluation Scheme:</strong> CIA: 40 Marks | Theory ESE: ${eseMarks} Marks</td>
                            </tr>
                        </table>
                    </div>

                    <div class="print-content">
                        ${clone.innerHTML}
                    </div>

                    <table class="signatures-table">
                        <tr>
                            <td>Signature of Faculty In-Charge</td>
                            <td>Signature of Head of Department (HOD)</td>
                            <td>Signature of Principal</td>
                        </tr>
                    </table>

                    <div class="footer-note">
                        Generated via Practicum Virtual Classroom System • Carmel College of Engineering & Technology, Alappuzha
                    </div>
