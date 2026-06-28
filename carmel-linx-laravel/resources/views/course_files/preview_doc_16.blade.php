<div class="print:p-0 p-8 w-full max-w-full mx-auto bg-white print:min-h-0 min-h-[210mm]">
    <style>
        @media print {
            @page { size: A4 landscape; margin: 10mm; }
            .print-hide { display: none !important; }
            .print-no-border { border: none !important; background: transparent !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            table { width: 100% !important; table-layout: fixed; }
            td, th { padding: 4px !important; }
            input { font-size: 10px !important; }
        }
    </style>

    <!-- Header -->
    <div class="text-center mb-4 border-b-2 border-slate-900 pb-2">
        <h1 class="text-sm font-black uppercase tracking-wider">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
        <h2 class="text-md font-bold mt-1 uppercase text-slate-800">GRADE SHEET - PROOF OF CO EVALUATIONS</h2>
        <p class="text-xs font-semibold mt-1">As per SBTE Norms (Revision {{ $revision }})</p>
    </div>

    <!-- Meta Details -->
    <div class="grid grid-cols-2 gap-2 mb-4 text-xs font-bold border p-2 bg-slate-50">
        <div>
            <p>Subject: <span class="font-normal">{{ $subjectName }} ({{ $subjectCode }})</span></p>
            <p>Branch: <span class="font-normal">{{ $branchFull }}</span></p>
        </div>
        <div class="text-right">
            <p>Batch: <span class="font-normal">{{ $batchYearSpan }}</span></p>
            <p>Semester: <span class="font-normal">{{ $semester }}</span></p>
        </div>
    </div>

    <form id="doc16Form" onsubmit="event.preventDefault(); saveDoc16();">
        <div class="overflow-x-auto">
            <table class="w-full text-[9px] border-collapse border border-slate-400 mb-8 whitespace-nowrap table-auto">
                <thead>
                    <tr class="bg-slate-100 print:bg-slate-100 text-center">
                        <th class="border border-slate-400 p-1 w-6" rowspan="2">Roll<br>No</th>
                        <th class="border border-slate-400 p-1 w-16" rowspan="2">Reg No</th>
                        <th class="border border-slate-400 p-1 text-left w-32" rowspan="2">Name of Student</th>
                        
                        <th class="border border-slate-400 p-1" colspan="4">Summative Tests</th>
                        <th class="border border-slate-400 p-1" colspan="4">MCQs</th>
                        <th class="border border-slate-400 p-1" colspan="4">Manual Tests</th>
                        <th class="border border-slate-400 p-1" colspan="4">Assignments</th>
                        
                        <th class="border border-slate-400 p-1 w-8" rowspan="2">Attn<br>%</th>
                        
                        <th class="border border-slate-400 p-1" colspan="4">CO Totals</th>
                        <th class="border border-slate-400 p-1 w-10 font-black" rowspan="2">Grand<br>Total</th>
                        <th class="border border-slate-400 p-1 w-12" rowspan="2">Cognitive<br>Level</th>
                    </tr>
                    <tr class="bg-slate-50 print:bg-slate-50 text-center">
                        <!-- Tests -->
                        <th class="border border-slate-400 p-1 w-6">T1</th>
                        <th class="border border-slate-400 p-1 w-6">T2</th>
                        <th class="border border-slate-400 p-1 w-6">T3</th>
                        <th class="border border-slate-400 p-1 w-6">T4</th>
                        <!-- MCQs -->
                        <th class="border border-slate-400 p-1 w-6">M1</th>
                        <th class="border border-slate-400 p-1 w-6">M2</th>
                        <th class="border border-slate-400 p-1 w-6">M3</th>
                        <th class="border border-slate-400 p-1 w-6">M4</th>
                        <!-- Manual Tests -->
                        <th class="border border-slate-400 p-1 w-6">MT1</th>
                        <th class="border border-slate-400 p-1 w-6">MT2</th>
                        <th class="border border-slate-400 p-1 w-6">MT3</th>
                        <th class="border border-slate-400 p-1 w-6">MT4</th>
                        <!-- Assignments -->
                        <th class="border border-slate-400 p-1 w-6">A1</th>
                        <th class="border border-slate-400 p-1 w-6">A2</th>
                        <th class="border border-slate-400 p-1 w-6">A3</th>
                        <th class="border border-slate-400 p-1 w-6">A4</th>
                        
                        <!-- CO Totals -->
                        <th class="border border-slate-400 p-1 w-8 bg-indigo-50 font-bold">CO1</th>
                        <th class="border border-slate-400 p-1 w-8 bg-indigo-50 font-bold">CO2</th>
                        <th class="border border-slate-400 p-1 w-8 bg-indigo-50 font-bold">CO3</th>
                        <th class="border border-slate-400 p-1 w-8 bg-indigo-50 font-bold">CO4</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ceData as $row)
                    <tr class="hover:bg-slate-50 student-row" data-reg="{{ $row->reg_no }}">
                        <td class="border border-slate-400 p-1 text-center font-bold">{{ $row->roll_no }}</td>
                        <td class="border border-slate-400 p-1 text-center">{{ $row->reg_no }}</td>
                        <td class="border border-slate-400 p-1 font-semibold truncate max-w-[120px]" title="{{ $row->name }}">{{ $row->name }}</td>
                        
                        <!-- Tests -->
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $co)
                            @php
                                $saved = $row->saved['t_'.$co] ?? null;
                                $dbMark = $row->marks[$co]['t'] ?? '';
                                $val = $saved !== null ? $saved : ($dbMark !== '' ? round($dbMark) : '');
                            @endphp
                            <td class="border border-slate-400 p-0 text-center">
                                <input type="number" class="w-full text-center p-0.5 print-no-border outline-none bg-transparent t-in" data-co="{{ $co }}" value="{{ $val }}" step="1">
                            </td>
                        @endforeach
                        
                        <!-- MCQs -->
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $co)
                            @php
                                $saved = $row->saved['mcq_'.$co] ?? null;
                                $dbMark = $row->marks[$co]['mcq'] ?? '';
                                $val = $saved !== null ? $saved : ($dbMark !== '' ? round($dbMark) : '');
                            @endphp
                            <td class="border border-slate-400 p-0 text-center">
                                <input type="number" class="w-full text-center p-0.5 print-no-border outline-none bg-transparent mcq-in" data-co="{{ $co }}" value="{{ $val }}" step="1">
                            </td>
                        @endforeach
                        
                        <!-- Manual Tests -->
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $co)
                            @php
                                $saved = $row->saved['mt_'.$co] ?? null;
                                $dbMark = $row->marks[$co]['mt'] ?? '';
                                $val = $saved !== null ? $saved : ($dbMark !== '' ? round($dbMark) : '');
                            @endphp
                            <td class="border border-slate-400 p-0 text-center">
                                <input type="number" class="w-full text-center p-0.5 print-no-border outline-none bg-transparent mt-in" data-co="{{ $co }}" value="{{ $val }}" step="1">
                            </td>
                        @endforeach
                        
                        <!-- Assignments -->
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $co)
                            @php
                                $saved = $row->saved['a_'.$co] ?? null;
                                $dbMark = $row->marks[$co]['a'] ?? '';
                                $val = $saved !== null ? $saved : ($dbMark !== '' ? round($dbMark) : '');
                            @endphp
                            <td class="border border-slate-400 p-0 text-center">
                                <input type="number" class="w-full text-center p-0.5 print-no-border outline-none bg-transparent a-in" data-co="{{ $co }}" value="{{ $val }}" step="1">
                            </td>
                        @endforeach
                        
                        <!-- Att % -->
                        <td class="border border-slate-400 p-0 text-center">
                            <input type="number" class="w-full text-center p-0.5 print-no-border outline-none bg-transparent att-in" value="{{ $row->saved['att'] ?? '' }}" step="1">
                        </td>
                        
                        <!-- CO Totals -->
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $co)
                            <td class="border border-slate-400 p-1 text-center font-bold bg-indigo-50/50 co-tot" data-co="{{ $co }}">0</td>
                        @endforeach
                        
                        <!-- Grand Total -->
                        <td class="border border-slate-400 p-1 text-center font-black grand-tot bg-amber-50/50 text-[10px]">0</td>
                        
                        <!-- Cognitive Level -->
                        <td class="border border-slate-400 p-0">
                            <input type="text" class="w-full text-center p-0.5 print-no-border outline-none bg-transparent cog-in font-bold uppercase" value="{{ $row->saved['cog'] ?? '' }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Actions -->
        <div class="print-hide flex justify-end gap-3 mt-4 border-t pt-4">
            <button type="button" onclick="window.printPreviewDoc()" class="bg-amber-500 hover:bg-amber-400 text-slate-900 px-5 py-2 rounded-xl text-[10px] font-black flex items-center gap-2"><span class="material-symbols-rounded">print</span> Print Report</button>
            <button type="submit" id="btnSave16" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2 rounded-xl text-[10px] font-bold flex items-center gap-2"><span class="material-symbols-rounded">save</span> Save Data</button>
        </div>
    </form>
</div>

<script>
    function recalculateTotals() {
        document.querySelectorAll('.student-row').forEach(row => {
            let grandTotal = 0;
            
            ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
                let sum = 0;
                
                let tIn = row.querySelector(`.t-in[data-co="${co}"]`);
                let mcqIn = row.querySelector(`.mcq-in[data-co="${co}"]`);
                let mtIn = row.querySelector(`.mt-in[data-co="${co}"]`);
                let aIn = row.querySelector(`.a-in[data-co="${co}"]`);
                
                if (tIn && tIn.value) sum += parseFloat(tIn.value);
                if (mcqIn && mcqIn.value) sum += parseFloat(mcqIn.value);
                if (mtIn && mtIn.value) sum += parseFloat(mtIn.value);
                if (aIn && aIn.value) sum += parseFloat(aIn.value);
                
                row.querySelector(`.co-tot[data-co="${co}"]`).innerText = sum;
                grandTotal += sum;
            });
            
            row.querySelector('.grand-tot').innerText = grandTotal;
        });
    }

    document.getElementById('doc16Form').addEventListener('input', recalculateTotals);
    recalculateTotals();

    function saveDoc16() {
        const btn = document.getElementById('btnSave16');
        btn.innerHTML = '<span class="material-symbols-rounded animate-spin">sync</span> Saving...';
        
        let payload = {};
        document.querySelectorAll('.student-row').forEach(row => {
            const reg = row.dataset.reg;
            let stuData = {
                att: parseFloat(row.querySelector('.att-in').value) || '',
                cog: row.querySelector('.cog-in').value || ''
            };
            
            ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
                let tVal = row.querySelector(`.t-in[data-co="${co}"]`).value;
                let mcqVal = row.querySelector(`.mcq-in[data-co="${co}"]`).value;
                let mtVal = row.querySelector(`.mt-in[data-co="${co}"]`).value;
                let aVal = row.querySelector(`.a-in[data-co="${co}"]`).value;
                
                if(tVal !== '') stuData['t_' + co] = parseFloat(tVal);
                if(mcqVal !== '') stuData['mcq_' + co] = parseFloat(mcqVal);
                if(mtVal !== '') stuData['mt_' + co] = parseFloat(mtVal);
                if(aVal !== '') stuData['a_' + co] = parseFloat(aVal);
            });
            
            payload[reg] = stuData;
        });

        fetch(`/api/course-files/{{ $cf->id }}/document/16/save`, {
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
                showGlobalMessage('Document 16 Grade Sheet saved successfully!');
            } else {
                alert(data.message || 'Error saving Document 16.');
            }
        })
        .catch(err => {
            console.error(err);
            alert("Network error saving Document 16");
        })
        .finally(() => {
            btn.innerHTML = '<span class="material-symbols-rounded">save</span> Save Data';
        });
    }
</script>
