<div class="w-full flex flex-col gap-8">
    <!-- Bottom is the A4 Grid -->
    <div class="max-w-[100%] overflow-x-auto mx-auto bg-white text-black p-10 min-h-[297mm] shadow-[0_0_15px_rgba(0,0,0,0.1)] font-serif relative border border-slate-200">
        
        <!-- A4 Header -->
        <div class="text-center border-b-2 border-black pb-4 mb-6">
            <h1 class="text-base font-black uppercase tracking-wider">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
            @php
                $branchCode = $cf->batchSubject->classroom->branch ?? '';
                $fullBranch = match($branchCode) {
                    'CT' => 'Computer Engineering',
                    'EL' => 'Electronics Engineering',
                    'ME' => 'Mechanical Engineering',
                    'AU' => 'Automobile Engineering',
                    'CE' => 'Civil Engineering',
                    'EE' => 'Electrical & Electronics Engineering',
                    default => $branchCode ?: 'General'
                };
            @endphp
            <p class="text-[10px] font-bold uppercase mt-1">Department of {{ $fullBranch }}</p>
            <p class="text-xs font-semibold mt-1">Course File - {{ $cf->academic_year }}</p>
        </div>

        <!-- Document Title -->
        <div class="text-center mb-6">
            <h2 class="text-sm font-bold uppercase underline decoration-2 underline-offset-4">Course Outcomes & CO-PO Mapping</h2>
        </div>

        <!-- Metadata -->
        <div class="grid grid-cols-2 gap-4 mb-6 text-[10px] font-bold">
            <div>Subject Code: <span class="font-normal">{{ $cf->batchSubject->subject_code ?? 'N/A' }}</span></div>
            <div class="text-right">Semester: <span class="font-normal">{{ $cf->batchSubject->semester ?? 'N/A' }}</span></div>
            <div>Subject Name: <span class="font-normal">{{ $cf->batchSubject->subject_name ?? 'N/A' }}</span></div>
            <div class="text-right">Batch: <span class="font-normal">{{ $cf->batchSubject->classroom->batch_year ?? 'N/A' }} Admission</span></div>
        </div>

        <p class="text-[10px] text-slate-500 italic mb-4">Instructions: Enter your Course Outcomes below, and map them to the Program Outcomes (PO1-PO11) and Program Specific Outcomes (PSO1-PSO3). Enter 1, 2, or 3 for the mapping strength. Leave blank if not mapped.</p>

        <!-- Table -->
        <table class="w-full border-collapse border border-black text-[10px] mb-10 text-center" id="copoTable">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-black px-1 py-2 w-8">CO</th>
                    <th class="border border-black px-1 py-2 text-left min-w-[200px]">Course Outcome Description</th>
                    @for($i=1; $i<=11; $i++)
                        <th class="border border-black px-0 py-2 w-6">PO{{ $i }}</th>
                    @endfor
                    @for($i=1; $i<=3; $i++)
                        <th class="border border-black px-0 py-2 w-6">PSO{{ $i }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($mapping as $index => $row)
                <tr class="hover:bg-yellow-50 transition-colors copo-row">
                    <td class="border border-black px-1 py-1 font-bold">{{ $row['co'] }}</td>
                    <td class="border border-black px-1 py-1 text-left">
                        <input type="text" class="w-full outline-none bg-transparent d6-desc font-sans" value="{{ $row['description'] ?? '' }}" placeholder="Desc for {{ $row['co'] }}">
                    </td>
                    @for($i=1; $i<=11; $i++)
                        <td class="border border-black px-0 py-0"><input type="number" min="1" max="3" class="w-full h-full py-1 text-center outline-none bg-transparent d6-po{{ $i }} font-sans" value="{{ $row['po'.$i] ?? '' }}"></td>
                    @endfor
                    @for($i=1; $i<=3; $i++)
                        <td class="border border-black px-0 py-0"><input type="number" min="1" max="3" class="w-full h-full py-1 text-center outline-none bg-transparent d6-pso{{ $i }} font-sans" value="{{ $row['pso'.$i] ?? '' }}"></td>
                    @endfor
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 text-xs font-semibold text-slate-500">
            * Note: This mapping is globally linked to Subject Code {{ $cf->batchSubject->subject_code ?? 'N/A' }} and will be shared across all batches automatically.
        </div>
    </div>
</div>

<script>
    // Overwrite the Verify & Approve button logic for Doc 6
    document.getElementById('btnVerifyDoc').onclick = function() {
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-rounded text-[18px] animate-spin">sync</span> Saving...';
        btn.disabled = true;

        // Collect table data
        let mappingData = [];
        let i = 1;
        document.querySelectorAll('.copo-row').forEach(row => {
            let rowObj = {
                co: 'CO' + i,
                description: row.querySelector('.d6-desc').value
            };
            for(let j=1; j<=11; j++) {
                rowObj['po' + j] = row.querySelector('.d6-po' + j).value;
            }
            for(let k=1; k<=3; k++) {
                rowObj['pso' + k] = row.querySelector('.d6-pso' + k).value;
            }
            mappingData.push(rowObj);
            i++;
        });

        // Send to save endpoint
        fetch(`/api/course-files/{{ $cf->id }}/document/6/save-copo`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ co_po_mapping: mappingData })
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            if (data.status === 'SUCCESS') {
                const cb = document.getElementById('cf_check_6');
                if (cb) cb.checked = true;
                
                saveCourseFileProgress();
                closePreviewModal();
                showGlobalMessage('CO-PO Mapping successfully verified and saved.');
            } else {
                showGlobalMessage(data.message || 'Error saving document.', true);
            }
        })
        .catch(err => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            showGlobalMessage('Network error while saving.', true);
        });
    };
</script>
