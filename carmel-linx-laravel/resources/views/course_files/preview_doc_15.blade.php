<div class="print:p-0 p-8 max-w-[297mm] mx-auto bg-white min-h-[210mm]">
    <style>
        @media print {
            @page { size: landscape; margin: 10mm; }
            .print-hide { display: none !important; }
            .print-no-border { border: none !important; background: transparent !important; }
        }
    </style>

    <!-- Header -->
    <div class="text-center mb-4 border-b-2 border-slate-900 pb-2 relative">
        <h1 class="text-sm font-black uppercase tracking-wider">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
        <h2 class="text-md font-bold mt-1 uppercase text-slate-800">Continuous Evaluation (CE) Report - <span id="reportTypeTitle">{{ $isPractical ? 'Practical' : 'Theory' }}</span></h2>
        <p class="text-xs font-semibold mt-1">As per SBTE Norms (Revision {{ $revision }})</p>
        
        <div class="absolute top-0 right-0 print-hide">
            <input type="hidden" id="courseType" value="{{ $isPractical ? 'practical' : 'theory' }}">
        </div>
    </div>

    <!-- Meta Details -->
    <div class="grid grid-cols-2 gap-2 mb-4 text-xs font-bold border p-2 bg-slate-50">
        <div>
            <p>Subject: <span class="font-normal">{{ $subjectName }} ({{ $subjectCode }})</span></p>
            <p>Branch: <span class="font-normal">{{ $branchFull }}</span></p>
            <p>Year: <span class="font-normal">{{ $currentYear }}</span></p>
        </div>
        <div class="text-right">
            <p>Batch: <span class="font-normal">{{ $batchYearSpan }}</span></p>
            <p>Semester: <span class="font-normal">{{ $semester }}</span></p>
        </div>
    </div>

    <form id="doc15Form" onsubmit="event.preventDefault(); saveDoc15();">
        <table class="w-full text-[10px] border-collapse border border-slate-400 mb-8 table-fixed">
            <thead>
                <tr class="bg-slate-100 print:bg-slate-100 text-center">
                    <th class="border border-slate-400 p-1 w-8" rowspan="2">No</th>
                    <th class="border border-slate-400 p-1 w-16" rowspan="2">Reg No</th>
                    <th class="border border-slate-400 p-1 text-left w-32" rowspan="2">Student Name</th>
                    <th class="border border-slate-400 p-1" colspan="5">Summative Tests (Best 2)</th>
                    <th class="border border-slate-400 p-1" colspan="5">Formative Assignments (Best 2)</th>
                    <th class="border border-slate-400 p-1 w-14" rowspan="2">Attend<br><span id="lblAttMax">(10)</span></th>
                    <th class="border border-slate-400 p-1 w-14 font-black" rowspan="2">Total<br><span id="lblCIA">(50)</span></th>
                    <th class="border border-slate-400 p-1 w-16" rowspan="2">Remarks</th>
                </tr>
                <tr class="bg-slate-50 print:bg-slate-50 text-center">
                    <th class="border border-slate-400 p-1 w-10">CO1</th>
                    <th class="border border-slate-400 p-1 w-10">CO2</th>
                    <th class="border border-slate-400 p-1 w-10">CO3</th>
                    <th class="border border-slate-400 p-1 w-10">CO4</th>
                    <th class="border border-slate-400 p-1 w-12 bg-indigo-50 font-bold">Avg <span id="lblTestMax">(20)</span></th>
                    <th class="border border-slate-400 p-1 w-10">A1</th>
                    <th class="border border-slate-400 p-1 w-10">A2</th>
                    <th class="border border-slate-400 p-1 w-10">A3</th>
                    <th class="border border-slate-400 p-1 w-10">A4</th>
                    <th class="border border-slate-400 p-1 w-12 bg-indigo-50 font-bold">Avg <span id="lblAssignMax">(20)</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($ceData as $index => $row)
                <tr class="hover:bg-slate-50 student-row" data-reg="{{ $row->reg_no }}">
                    <td class="border border-slate-400 p-1 text-center">{{ $index + 1 }}</td>
                    <td class="border border-slate-400 p-1 text-center">{{ $row->reg_no }}</td>
                    <td class="border border-slate-400 p-1 font-semibold truncate" title="{{ $row->name }}">{{ $row->name }}</td>
                    
                    @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $co)
                        @php
                            $savedT = $row->saved_data['t_'.$co] ?? null;
                            $val = $savedT !== null ? $savedT : ($row->tests[$co]->obtained ?? '');
                            $val = $val !== '' ? round($val) : '';
                            $max = $row->tests[$co]->max ?? 20;
                        @endphp
                        <td class="border border-slate-400 p-0">
                            <input type="number" class="w-full text-center p-1 print-no-border outline-none t-in bg-transparent" data-co="{{ $co }}" data-max="{{ $max }}" value="{{ $val }}" step="1">
                        </td>
                    @endforeach
                    <td class="border border-slate-400 p-1 text-center font-bold bg-indigo-50/50 t-avg">0</td>

                    @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $co)
                        @php
                            $savedA = $row->saved_data['a_'.$co] ?? null;
                            $val = $savedA !== null ? $savedA : ($row->assigns[$co]->obtained ?? '');
                            $val = $val !== '' ? round($val) : '';
                            $max = $row->assigns[$co]->max ?? 10;
                        @endphp
                        <td class="border border-slate-400 p-0">
                            <input type="number" class="w-full text-center p-1 print-no-border outline-none a-in bg-transparent" data-co="{{ $co }}" data-max="{{ $max }}" value="{{ $val }}" step="1">
                        </td>
                    @endforeach
                    <td class="border border-slate-400 p-1 text-center font-bold bg-indigo-50/50 a-avg">0</td>

                    <td class="border border-slate-400 p-0">
                        <input type="number" class="w-full text-center p-1 print-no-border outline-none att-in bg-transparent" value="{{ $row->saved_data['att'] ?? $row->default_att }}" step="1">
                    </td>
                    <td class="border border-slate-400 p-1 text-center font-black cia-tot">0</td>
                    <td class="border border-slate-400 p-0"><input type="text" class="w-full p-1 print-no-border outline-none bg-transparent"></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Actions -->
        <div class="print-hide flex justify-end gap-3 mt-4 border-t pt-4">
            <button type="button" onclick="window.printPreviewDoc()" class="bg-amber-500 hover:bg-amber-400 text-slate-900 px-5 py-2 rounded-xl text-[10px] font-black flex items-center gap-2"><span class="material-symbols-rounded">print</span> Print Report</button>
            <button type="submit" id="btnSave15" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2 rounded-xl text-[10px] font-bold flex items-center gap-2"><span class="material-symbols-rounded">save</span> Save Adjustments</button>
        </div>
    </form>

    <div class="mt-12 flex justify-between px-8">
        <div class="text-center">
            <div class="w-48 border-b border-slate-400 mb-2"></div>
            <p class="text-xs font-bold uppercase">Staff in Charge</p>
        </div>
        <div class="text-center">
            <div class="w-48 border-b border-slate-400 mb-2"></div>
            <p class="text-xs font-bold uppercase">Head of Department</p>
        </div>
    </div>
</div>

<script>
    function recalculateAll() {
        const typeElem = document.getElementById('courseType');
        const type = typeElem ? typeElem.value : 'theory';
        
        const maxTest = type === 'theory' ? 20 : 15;
        const maxAssign = type === 'theory' ? 20 : 45;
        const maxAtt = type === 'theory' ? 10 : 15;
        const maxCIA = type === 'theory' ? 50 : 75;

        if (document.getElementById('reportTypeTitle')) document.getElementById('reportTypeTitle').innerText = type === 'theory' ? 'Theory' : 'Practical';
        if (document.getElementById('lblTestMax')) document.getElementById('lblTestMax').innerText = `(${maxTest})`;
        if (document.getElementById('lblAssignMax')) document.getElementById('lblAssignMax').innerText = `(${maxAssign})`;
        if (document.getElementById('lblAttMax')) document.getElementById('lblAttMax').innerText = `(${maxAtt})`;
        if (document.getElementById('lblCIA')) document.getElementById('lblCIA').innerText = `(${maxCIA})`;

        const rows = document.querySelectorAll('.student-row');
        for (let i = 0; i < rows.length; i++) {
            let row = rows[i];

            // Tests calculation
            let tPercents = [];
            let tInputs = row.querySelectorAll('.t-in');
            for (let j = 0; j < tInputs.length; j++) {
                if (tInputs[j].value !== '') {
                    let v = parseFloat(tInputs[j].value);
                    if (!isNaN(v)) {
                        let m = parseFloat(tInputs[j].getAttribute('data-max')) || 20;
                        if (m > 0) tPercents.push((v / m) * 100);
                    }
                }
            }
            tPercents.sort(function(a, b) { return b - a; });
            let tAvg = 0;
            if (tPercents.length >= 2) tAvg = (tPercents[0] + tPercents[1]) / 2;
            else if (tPercents.length === 1) tAvg = tPercents[0];
            
            let tFinal = Math.round(tAvg * (maxTest / 100));
            let tCell = row.querySelector('.t-avg');
            if (tCell) tCell.innerText = tFinal;

            // Assign calculation
            let aPercents = [];
            let aInputs = row.querySelectorAll('.a-in');
            for (let j = 0; j < aInputs.length; j++) {
                if (aInputs[j].value !== '') {
                    let v = parseFloat(aInputs[j].value);
                    if (!isNaN(v)) {
                        let m = parseFloat(aInputs[j].getAttribute('data-max')) || 10;
                        if (m > 0) aPercents.push((v / m) * 100);
                    }
                }
            }
            aPercents.sort(function(a, b) { return b - a; });
            let aAvg = 0;
            if (aPercents.length >= 2) aAvg = (aPercents[0] + aPercents[1]) / 2;
            else if (aPercents.length === 1) aAvg = aPercents[0];
            
            let aFinal = Math.round(aAvg * (maxAssign / 100));
            let aCell = row.querySelector('.a-avg');
            if (aCell) aCell.innerText = aFinal;

            // Att
            let attIn = row.querySelector('.att-in');
            let attVal = 0;
            if (attIn && attIn.value !== '') {
                let av = parseFloat(attIn.value);
                if (!isNaN(av)) attVal = Math.round(av);
            }
            if (attVal > maxAtt) { 
                attVal = maxAtt; 
                if (attIn) attIn.value = maxAtt; 
            }

            // Total
            let totCell = row.querySelector('.cia-tot');
            if (totCell) totCell.innerText = tFinal + aFinal + attVal;
        }
    }

    document.getElementById('doc15Form').addEventListener('input', recalculateAll);
    recalculateAll(); // initial call

    function saveDoc15() {
        const btn = document.getElementById('btnSave15');
        btn.innerHTML = '<span class="material-symbols-rounded animate-spin">sync</span> Saving...';
        
        let payload = {};
        document.querySelectorAll('.student-row').forEach(row => {
            const reg = row.dataset.reg;
            let stuData = { att: parseFloat(row.querySelector('.att-in').value) || 0 };
            
            row.querySelectorAll('.t-in').forEach(input => {
                if (input.value !== '') stuData['t_' + input.dataset.co] = parseFloat(input.value);
            });
            row.querySelectorAll('.a-in').forEach(input => {
                if (input.value !== '') stuData['a_' + input.dataset.co] = parseFloat(input.value);
            });
            
            payload[reg] = stuData;
        });

        fetch(`/api/course-files/{{ $cf->id }}/document/15/save`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ payload: JSON.stringify(payload) })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                showGlobalMessage('Document 15 CE Marks saved successfully!');
            } else {
                alert(data.message || 'Error saving Document 15.');
            }
        })
        .catch(err => {
            console.error(err);
            alert("Network error saving Document 15");
        })
        .finally(() => {
            btn.innerHTML = '<span class="material-symbols-rounded">save</span> Save Adjustments';
        });
    }
</script>
