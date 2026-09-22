        function switchMode(mode) {
            document.getElementById('mode-theory-container').classList.add('hidden');
            document.getElementById('mode-lab-container').classList.add('hidden');
            
            document.getElementById('mode-btn-theory').classList.remove('active', 'text-white');
            document.getElementById('mode-btn-lab').classList.remove('active', 'text-white');

            if (mode === 'theory') {
                document.getElementById('mode-theory-container').classList.remove('hidden');
                document.getElementById('mode-btn-theory').classList.add('active', 'text-white');
            } else {
                document.getElementById('mode-lab-container').classList.remove('hidden');
                document.getElementById('mode-btn-lab').classList.add('active', 'text-white');
            }
            localStorage.setItem('active_mode', mode);
        }

        function switchTheorySubtab(tab) {
            ['overview', 'planner', 'sl', 'series', 'ese', 'surveys', 'attendance', 'materials'].forEach(t => {
                document.getElementById('theory-subcontent-' + t)?.classList.add('hidden');
                document.getElementById('theory-tab-' + t)?.classList.remove('active', 'text-white');
            });
            document.getElementById('theory-subcontent-' + tab)?.classList.remove('hidden');
            document.getElementById('theory-tab-' + tab)?.classList.add('active', 'text-white');
            localStorage.setItem('active_theory_subtab', tab);
        }

        function switchLabSubtab(tab) {
            ['roster', 'planner', 'eval', 'series', 'ese'].forEach(t => {
                document.getElementById('lab-subcontent-' + t)?.classList.add('hidden');
                document.getElementById('lab-tab-' + t)?.classList.remove('active', 'text-white');
            });
            document.getElementById('lab-subcontent-' + tab)?.classList.remove('hidden');
            document.getElementById('lab-tab-' + tab)?.classList.add('active', 'text-white');
            localStorage.setItem('active_lab_subtab', tab);
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    alert(`Error attempting to enable fullscreen mode: ${err.message}`);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        function openSyllabusModal() { 
          const modal = document.getElementById('syllabus-modal');
          if (modal) modal.classList.remove('hidden'); 
        }
        function closeSyllabusModal() { 
          const modal = document.getElementById('syllabus-modal');
          if (modal) modal.classList.add('hidden'); 
        }

        function handlePracticumDragOver(e) {
          e.preventDefault();
          e.stopPropagation();
          const dropzone = document.getElementById('practicumSyllabusDropzone');
          if (dropzone) dropzone.classList.add('border-blue-500', 'bg-blue-50/60');
        }

        function handlePracticumDragLeave(e) {
          e.preventDefault();
          e.stopPropagation();
          const dropzone = document.getElementById('practicumSyllabusDropzone');
          if (dropzone) dropzone.classList.remove('border-blue-500', 'bg-blue-50/60');
        }

        function handlePracticumFileDrop(e) {
          e.preventDefault();
          e.stopPropagation();
          const dropzone = document.getElementById('practicumSyllabusDropzone');
          if (dropzone) dropzone.classList.remove('border-blue-500', 'bg-blue-50/60');

          const files = e.dataTransfer.files;
          if (!files || files.length === 0) return;
          const file = files[0];
          if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
            alert('Please drop a valid PDF file.');
            return;
          }
          const input = document.getElementById('practicumSyllabusFileInput');
          if (input) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
            showPracticumFilePreview(file);
          }
        }

        function handlePracticumFileInput(input) {
          if (!input.files || input.files.length === 0) return;
          showPracticumFilePreview(input.files[0]);
        }

        function showPracticumFilePreview(file) {
          const dropzone = document.getElementById('practicumSyllabusDropzone');
          const preview = document.getElementById('practicumFilePreview');
          const nameEl = document.getElementById('practicumFileName');
          const sizeEl = document.getElementById('practicumFileSize');
          const errBox = document.getElementById('practicumErrorAlert');

          if (errBox) errBox.classList.add('hidden');
          if (nameEl) nameEl.innerText = file.name;
          if (sizeEl) {
            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
            sizeEl.innerText = `${sizeMB} MB · Ready`;
          }
          if (dropzone) dropzone.classList.add('hidden');
          if (preview) preview.classList.remove('hidden');
        }

        function cancelPracticumSelectedFile(e) {
          if (e) { e.preventDefault(); e.stopPropagation(); }
          const input = document.getElementById('practicumSyllabusFileInput');
          if (input) input.value = '';
          const dropzone = document.getElementById('practicumSyllabusDropzone');
          const preview = document.getElementById('practicumFilePreview');
          const processing = document.getElementById('practicumProcessingState');
          const errBox = document.getElementById('practicumErrorAlert');

          if (preview) preview.classList.add('hidden');
          if (processing) processing.classList.add('hidden');
          if (errBox) errBox.classList.add('hidden');
          if (dropzone) dropzone.classList.remove('hidden');
        }

        function submitPracticumSyllabus() {
          const input = document.getElementById('practicumSyllabusFileInput');
          if (!input || !input.files || input.files.length === 0) {
            alert('Please select a syllabus PDF first.');
            return;
          }
          const file = input.files[0];
          const formData = new FormData();
          formData.append('syllabus_file', file);
          formData.append('_token', "{{ csrf_token() }}");

          const preview = document.getElementById('practicumFilePreview');
          const processing = document.getElementById('practicumProcessingState');
          const errBox = document.getElementById('practicumErrorAlert');
          const errMsg = document.getElementById('practicumErrorMessage');
          const btnSubmit = document.getElementById('btnSubmitPracticumSyllabus');

          if (preview) preview.classList.add('hidden');
          if (errBox) errBox.classList.add('hidden');
          if (processing) processing.classList.remove('hidden');
          if (btnSubmit) btnSubmit.disabled = true;

          fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/syllabus', {
            method: 'POST',
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if (processing) processing.classList.add('hidden');
            if (btnSubmit) btnSubmit.disabled = false;
            if (data.status === 'SUCCESS') {
              window.location.reload();
            } else {
              if (errMsg) errMsg.innerText = data.message || 'Extraction failed. Please check PDF.';
              if (errBox) errBox.classList.remove('hidden');
              const dropzone = document.getElementById('practicumSyllabusDropzone');
              if (dropzone) dropzone.classList.remove('hidden');
            }
          })
          .catch(err => {
            if (processing) processing.classList.add('hidden');
            if (btnSubmit) btnSubmit.disabled = false;
            if (errMsg) errMsg.innerText = 'Upload Error: ' + err.message;
            if (errBox) errBox.classList.remove('hidden');
            const dropzone = document.getElementById('practicumSyllabusDropzone');
            if (dropzone) dropzone.classList.remove('hidden');
          });
        }

