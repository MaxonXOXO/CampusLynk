<div class="max-w-[210mm] mx-auto bg-white text-black p-10 min-h-[297mm] shadow-[0_0_15px_rgba(0,0,0,0.1)] font-serif relative">
    
    <!-- A4 Header -->
    <div class="text-center border-b-2 border-black pb-4 mb-6">
        <h1 class="text-2xl font-black uppercase tracking-wider">Carmel Polytechnic College</h1>
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
        <p class="text-sm font-bold uppercase mt-1">Department of {{ $fullBranch }}</p>
        <p class="text-xs font-semibold mt-1">Course File - {{ $cf->academic_year }}</p>
    </div>

    <!-- Document Title -->
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold uppercase underline decoration-2 underline-offset-4">Student List</h2>
    </div>

    <!-- Metadata -->
    <div class="grid grid-cols-2 gap-4 mb-6 text-sm font-bold">
        <div>Subject Code: <span class="font-normal">{{ $cf->batchSubject->subject_code ?? 'N/A' }}</span></div>
        <div class="text-right">Semester: <span class="font-normal">{{ $cf->batchSubject->semester ?? 'N/A' }}</span></div>
        <div>Subject Name: <span class="font-normal">{{ $cf->batchSubject->subject_name ?? 'N/A' }}</span></div>
        <div class="text-right">Batch: <span class="font-normal">{{ $cf->batchSubject->classroom->batch_year ?? 'N/A' }} Admission</span></div>
    </div>

    <!-- Table -->
    <table class="w-full border-collapse border border-black text-sm mb-10">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-black px-2 py-2 w-16 text-center">Roll No</th>
                <th class="border border-black px-2 py-2 w-32 text-center">Reg No</th>
                <th class="border border-black px-2 py-2 text-left">Student Name</th>
                <th class="border border-black px-2 py-2 w-24 text-center">Type</th>
                <th class="border border-black px-2 py-2 text-left">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payload as $row)
            <tr class="doc3-row hover:bg-yellow-50 transition-colors" data-reg="{{ $row['reg_no'] }}" data-name="{{ $row['name'] }}">
                <td class="border border-black px-1 py-1 text-center">
                    <input type="number" class="d3-roll w-full text-center outline-none bg-transparent" value="{{ $row['roll_no'] }}">
                </td>
                <td class="border border-black px-2 py-1 text-center font-mono text-xs">{{ $row['reg_no'] }}</td>
                <td class="border border-black px-2 py-1 font-semibold">{{ $row['name'] }}</td>
                <td class="border border-black px-1 py-1 text-center">
                    <select class="d3-type w-full text-center outline-none bg-transparent">
                        <option value="Regular" {{ ($row['type'] ?? 'Regular') == 'Regular' ? 'selected' : '' }}>Regular</option>
                        <option value="LET" {{ ($row['type'] ?? '') == 'LET' ? 'selected' : '' }}>LET</option>
                    </select>
                </td>
                <td class="border border-black px-1 py-1">
                    <input type="text" class="d3-rem w-full outline-none bg-transparent text-xs" value="{{ $row['remarks'] ?? '' }}" placeholder="Add remark...">
                </td>
            </tr>
            @endforeach
            @if(empty($payload))
            <tr>
                <td colspan="5" class="border border-black px-2 py-8 text-center text-gray-400 italic">No students assigned to this classroom.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Edit Notice Overlay -->
    <div class="absolute top-4 right-4 bg-amber-100 text-amber-800 border border-amber-300 px-3 py-1.5 rounded shadow-sm text-xs font-bold flex items-center gap-1 opacity-70 hover:opacity-100 transition-opacity">
        <span class="material-symbols-rounded text-[16px]">edit</span> Editable Preview
    </div>
</div>

<script>
    // Overwrite the Verify & Approve button logic for Doc 3
    document.getElementById('btnVerifyDoc').onclick = function() {
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-rounded text-[18px] animate-spin">sync</span> Saving...';
        btn.disabled = true;

        // Collect table data
        let payload = [];
        document.querySelectorAll('.doc3-row').forEach(row => {
            payload.push({
                roll_no: row.querySelector('.d3-roll').value,
                reg_no: row.dataset.reg,
                name: row.dataset.name,
                type: row.querySelector('.d3-type').value,
                remarks: row.querySelector('.d3-rem').value
            });
        });

        // Send to save endpoint
        fetch(`/api/course-files/{{ $cf->id }}/document/3/save`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ payload: JSON.stringify(payload) })
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            if (data.status === 'SUCCESS') {
                // Manually trigger the checklist UI update
                const cb = document.getElementById('cf_check_3');
                if (cb) cb.checked = true;
                
                // Fallback to standard save progress to update global db state
                saveCourseFileProgress();
                closePreviewModal();
                showGlobalMessage('Student List successfully verified and saved.');
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
