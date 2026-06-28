<div class="w-full h-full min-h-[700px] flex flex-col">
    @if($pdfUrl)
        <div class="mb-4 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-800">Course Information Sheet (CIS)</h3>
            <div class="flex items-center gap-3">
                <button onclick="document.getElementById('cisPdfInput').click()" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-[10px] rounded-lg hover:bg-slate-200 transition-colors flex items-center gap-2">
                    <span class="material-symbols-rounded text-[18px]">upload_file</span> Replace PDF
                </button>
                <a href="{{ $pdfUrl }}" target="_blank" class="px-4 py-2 bg-indigo-50 text-indigo-600 font-bold text-[10px] rounded-lg hover:bg-indigo-100 transition-colors flex items-center gap-2">
                    <span class="material-symbols-rounded text-[18px]">open_in_new</span> Open Full Screen
                </a>
            </div>
            <!-- Hidden input for replacing -->
            <input type="file" id="cisPdfInput" class="hidden" accept="application/pdf" onchange="uploadCis(this.files[0])">
        </div>
        <div class="bg-slate-100 rounded-xl border-2 border-dashed border-slate-300 p-2 relative h-[700px]">
            <iframe src="{{ $pdfUrl }}#toolbar=0" class="w-full h-full rounded-lg" frameborder="0"></iframe>
        </div>
    @else
        <div class="flex-1 flex flex-col items-center justify-center text-center p-10 bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl">
            <div class="w-20 h-20 bg-indigo-50 text-indigo-400 rounded-full flex items-center justify-center mb-4">
                <span class="material-symbols-rounded text-xl">upload_file</span>
            </div>
            <h3 class="text-sm font-black text-slate-800 mb-2">Upload Course Information Sheet</h3>
            <p class="text-slate-500 max-w-md mb-6">Upload the CIS PDF for Subject <strong class="text-indigo-600">{{ $cf->batchSubject->subject_code ?? 'Unknown' }}</strong>. Once uploaded, it will be automatically shared with all batches taking this subject.</p>
            
            <button onclick="document.getElementById('cisPdfInput').click()" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 hover:scale-105 transition-all flex items-center gap-2">
                <span class="material-symbols-rounded">cloud_upload</span> Select PDF File
            </button>
            <input type="file" id="cisPdfInput" class="hidden" accept="application/pdf" onchange="uploadCis(this.files[0])">
            
            <div id="uploadStatus" class="mt-4 text-[10px] font-bold text-emerald-600 hidden">Uploading... Please wait.</div>
        </div>
    @endif
</div>

<script>
    function uploadCis(file) {
        if (!file) return;
        
        const statusDiv = document.getElementById('uploadStatus');
        if (statusDiv) {
            statusDiv.classList.remove('hidden');
            statusDiv.innerHTML = '<span class="material-symbols-rounded animate-spin align-middle text-[16px]">sync</span> Uploading...';
        }
        
        const formData = new FormData();
        formData.append('cis_pdf', file);

        fetch(`/api/course-files/{{ $cf->id }}/document/5/upload-cis`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                showGlobalMessage('CIS PDF successfully uploaded!');
                // Auto-verify it
                const cb = document.getElementById('cf_check_5');
                if (cb) cb.checked = true;
                saveCourseFileProgress();
                
                // Refresh the modal to show the iframe
                openPreviewModal(5); 
            } else {
                showGlobalMessage(data.message || 'Error uploading file.', true);
                if (statusDiv) statusDiv.classList.add('hidden');
            }
        })
        .catch(err => {
            showGlobalMessage('Network error during upload.', true);
            if (statusDiv) statusDiv.classList.add('hidden');
        });
    }
</script>
