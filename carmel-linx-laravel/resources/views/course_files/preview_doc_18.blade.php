<div class="print:p-0 p-8 w-full max-w-full mx-auto bg-white print:min-h-0 min-h-[210mm]">
    <style>
        @media print {
            @page { size: A4 landscape; margin: 10mm; }
            .print-hide { display: none !important; }
            .print-no-border { border: none !important; background: transparent !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            table { width: 100% !important; table-layout: fixed; }
            td, th { padding: 4px !important; }
            input, select { font-size: 10px !important; padding: 2px !important; }
        }
    </style>

    <!-- Header -->
    <div class="text-center mb-4 border-b-2 border-slate-900 pb-2">
        <h1 class="text-sm font-black uppercase tracking-wider">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
        <h2 class="text-md font-bold mt-1 uppercase text-slate-800">SBTE EXAMINATION RESULT</h2>
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

    <form id="doc18Form" onsubmit="event.preventDefault(); saveDoc18();">
        <div class="overflow-x-auto">
            <table class="w-full text-[10px] border-collapse border border-slate-400 mb-8 table-auto">
                <thead>
                    <tr class="bg-slate-100 print:bg-slate-100 text-center">
                        <th class="border border-slate-400 p-2 w-8">Roll<br>No</th>
                        <th class="border border-slate-400 p-2 w-20">Reg No</th>
                        <th class="border border-slate-400 p-2 text-left w-40">Name of Student</th>
                        <th class="border border-slate-400 p-2 w-24">Exam<br>Month/Year</th>
                        <th class="border border-slate-400 p-2 w-16">Internal<br>Marks</th>
                        <th class="border border-slate-400 p-2 w-16">External<br>Marks</th>
                        <th class="border border-slate-400 p-2 w-16 bg-slate-200">Total<br>Marks</th>
                        <th class="border border-slate-400 p-2 w-16">Grade</th>
                        <th class="border border-slate-400 p-2 w-16">Passed</th>
                        <th class="border border-slate-400 p-2 w-16">Chances<br>Taken</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($boardData as $row)
                    <tr class="hover:bg-slate-50 student-row" data-reg="{{ $row->reg_no }}">
                        <td class="border border-slate-400 p-1 text-center font-bold">{{ $row->roll_no }}</td>
                        <td class="border border-slate-400 p-1 text-center font-medium">{{ $row->reg_no }}</td>
                        <td class="border border-slate-400 p-1 font-semibold">{{ $row->name }}</td>
                        
                        <td class="border border-slate-400 p-0 text-center">
                            <input type="text" class="w-full text-center p-1 print-no-border outline-none bg-transparent m-y-in" value="{{ $row->exam_month_year }}" placeholder="e.g. Apr 2026">
                        </td>
                        
                        <td class="border border-slate-400 p-0 text-center">
                            <input type="number" class="w-full text-center p-1 print-no-border outline-none bg-transparent int-in" value="{{ $row->internal_marks }}" step="1">
                        </td>
                        
                        <td class="border border-slate-400 p-0 text-center">
                            <input type="number" class="w-full text-center p-1 print-no-border outline-none bg-transparent ext-in" value="{{ $row->external_marks }}" step="1">
                        </td>
                        
                        <td class="border border-slate-400 p-1 text-center font-black bg-slate-50/50 tot-out text-[11px]">{{ $row->total_marks }}</td>
                        
                        <td class="border border-slate-400 p-0 text-center">
                            <input type="text" class="w-full text-center p-1 print-no-border outline-none bg-transparent grade-in font-bold uppercase" value="{{ $row->grade }}">
                        </td>
                        
                        <td class="border border-slate-400 p-0 text-center">
                            <select class="w-full text-center p-1 print-no-border outline-none bg-transparent pass-in font-semibold">
                                <option value="1" {{ $row->passed == 1 ? "selected" : "" }}>Yes</option>
                                <option value="0" {{ $row->passed == 0 ? "selected" : "" }}>No</option>
                            </select>
                        </td>
                        
                        <td class="border border-slate-400 p-0 text-center">
                            <input type="number" class="w-full text-center p-1 print-no-border outline-none bg-transparent chances-in font-bold" value="{{ $row->chances_taken }}" min="1" step="1">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Actions -->
        <div class="print-hide flex justify-end gap-3 mt-4 border-t pt-4">
            <button type="button" onclick="window.printPreviewDoc()" class="bg-amber-500 hover:bg-amber-400 text-slate-900 px-5 py-2 rounded-xl text-[10px] font-black flex items-center gap-2"><span class="material-symbols-rounded">print</span> Print Report</button>
            <button type="submit" id="btnSave18" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2 rounded-xl text-[10px] font-bold flex items-center gap-2"><span class="material-symbols-rounded">save</span> Save Data</button>
        </div>
    </form>
</div>

<script>
    function recalculateDoc18() {
        document.querySelectorAll(".student-row").forEach(row => {
            let intVal = parseFloat(row.querySelector(".int-in").value);
            let extVal = parseFloat(row.querySelector(".ext-in").value);
            
            let total = 0;
            let hasTotal = false;
            if (!isNaN(intVal)) { total += intVal; hasTotal = true; }
            if (!isNaN(extVal)) { total += extVal; hasTotal = true; }
            
            row.querySelector(".tot-out").innerText = hasTotal ? total : "";
        });
    }

    document.getElementById("doc18Form").addEventListener("input", recalculateDoc18);
    recalculateDoc18();

    function saveDoc18() {
        const btn = document.getElementById("btnSave18");
        btn.innerHTML = "<span class=\"material-symbols-rounded animate-spin\">sync</span> Saving...";
        
        let payload = {};
        document.querySelectorAll(".student-row").forEach(row => {
            const reg = row.dataset.reg;
            let intVal = parseFloat(row.querySelector(".int-in").value);
            let extVal = parseFloat(row.querySelector(".ext-in").value);
            let total = 0;
            let hasTotal = false;
            if (!isNaN(intVal)) { total += intVal; hasTotal = true; }
            if (!isNaN(extVal)) { total += extVal; hasTotal = true; }

            payload[reg] = {
                exam_month_year: row.querySelector(".m-y-in").value,
                internal_marks: isNaN(intVal) ? null : intVal,
                external_marks: isNaN(extVal) ? null : extVal,
                total_marks: hasTotal ? total : null,
                grade: row.querySelector(".grade-in").value.toUpperCase(),
                passed: row.querySelector(".pass-in").value,
                chances_taken: parseInt(row.querySelector(".chances-in").value) || 1
            };
        });

        fetch("/api/course-files/{{ $cf->id }}/document/18/save", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name=\"csrf-token\"]").content
            },
            body: JSON.stringify({ payload: JSON.stringify(payload) })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "SUCCESS") {
                showGlobalMessage("Document 18 SBTE Results saved successfully!");
            } else {
                alert(data.message || "Error saving Document 18.");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Network error saving Document 18");
        })
        .finally(() => {
            btn.innerHTML = "<span class=\"material-symbols-rounded\">save</span> Save Data";
        });
    }
</script>
