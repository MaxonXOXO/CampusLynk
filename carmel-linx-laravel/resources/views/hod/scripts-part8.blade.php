            `;
            if (window.initLucide) window.initLucide();
          }
        }
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-12 text-center text-rose-600 font-semibold text-sm">Failed to load leave records. Please check network connection.</td></tr>`;
      }
    }

    async function processLeaveApproval(id, decision) {
      const remarks = prompt(`Enter optional remarks for ${decision.toLowerCase()}ing leave application:`, decision === 'Approve' ? 'Recommended/Approved by HOD' : 'Rejected');
      if (remarks === null) return;

      try {
        const res = await fetch('/api/staff/leave/process-approval', {
          method: 'POST',
          headers: getHeaders(),
          body: JSON.stringify({
            leave_id: id,
            stage: 'HOD',
            decision: decision,
            remarks: remarks
          })
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          showGlobalMessage(`Leave application ${decision.toLowerCase()}d successfully.`, false);
          loadLeaveLedger();
        } else {
          showGlobalMessage(data.message || 'Error processing leave decision.', true);
        }
      } catch (err) {
        showGlobalMessage('Network error updating leave decision.', true);
      }
    }

    // =========================================================================
    // PROFESSIONAL ACTIVITIES HANDLERS (CAMPUSLYNK 5/7 ARCHETYPE)
    // =========================================================================
    const profActSchemas = {
      fdp_attended: [
        { label: 'Title of FDP / Training Program', name: 'title', type: 'text', placeholder: 'e.g. Advanced Laravel & Microservices', fullWidth: true, required: true },
        { label: 'Duration (Days / Hours)', name: 'duration', type: 'text', placeholder: 'e.g. 5 Days / 40 Hrs', required: true },
        { label: 'Start Date', name: 'date', type: 'date', required: false },
        { label: 'Organizing Venue / Institution', name: 'venue', type: 'text', placeholder: 'e.g. Carmel Polytechnic / NITTTR', fullWidth: true, required: true }
      ],
      workshop_attended: [
        { label: 'Title of Workshop / BootCamp', name: 'title', type: 'text', placeholder: 'e.g. IoT Systems & Embedded Networks', fullWidth: true, required: true },
        { label: 'Duration (Days / Hours)', name: 'duration', type: 'text', placeholder: 'e.g. 2 Days', required: true },
        { label: 'Date', name: 'date', type: 'date', required: false },
        { label: 'Organizing Body / Venue', name: 'venue', type: 'text', placeholder: 'e.g. Govt Polytechnic College', fullWidth: true, required: true }
      ],
      course_attended: [
        { label: 'Course / Certification Title', name: 'title', type: 'text', placeholder: 'e.g. NPTEL Data Structures & Algorithms', fullWidth: true, required: true },
        { label: 'Duration', name: 'duration', type: 'text', placeholder: 'e.g. 8 Weeks', required: true },
        { label: 'Platform / Certifying Body', name: 'venue', type: 'text', placeholder: 'e.g. NPTEL / Swayam / Coursera', required: true }
      ],
      gap_in_syllabus: [
        { label: 'Subject Name & Code', name: 'subject', type: 'text', placeholder: 'e.g. Computer Networks (CN-302)', fullWidth: true, required: true },
        { label: 'Identified Curricular Gap Details', name: 'gap_details', type: 'textarea', placeholder: 'Identify details where syllabus falls short of industrial expectations...', fullWidth: true, required: true },
        { label: 'Action Taken / Bridge Course Plan', name: 'action_taken', type: 'text', placeholder: 'e.g. Conducted a 3-hour hands-on seminar on IPv6 Routing', fullWidth: true, required: true }
      ],
      project_guided: [
        { label: 'Project Title', name: 'title', type: 'text', placeholder: 'e.g. Smart Campus Face Recognition System', fullWidth: true, required: true },
        { label: 'Batch / Academic Year', name: 'batch', type: 'text', placeholder: 'e.g. 2023-2026 Batch', required: true },
        { label: 'Student Names', name: 'students', type: 'text', placeholder: 'e.g. Arjun, Vishnu, Rahul', required: true }
      ],
      seminar_guided: [
        { label: 'Seminar Topic', name: 'title', type: 'text', placeholder: 'e.g. Introduction to Quantum Computing & Cryptography', fullWidth: true, required: true },
        { label: 'Student Name', name: 'students', type: 'text', placeholder: 'e.g. Anjali Nair', required: true },
        { label: 'Date Presented', name: 'date', type: 'date', required: false }
      ],
      publication: [
        { label: 'Paper / Research Title', name: 'title', type: 'text', placeholder: 'e.g. AI-driven Automated Grading Engines', fullWidth: true, required: true },
        { label: 'Journal / Conference Name', name: 'journal', type: 'text', placeholder: 'e.g. International Journal of Engineering & Tech', required: true },
        { label: 'Publication Year', name: 'year', type: 'number', placeholder: 'e.g. 2026', required: true }
      ],
      book_published: [
        { label: 'Book Title', name: 'title', type: 'text', placeholder: 'e.g. Fundamentals of Embedded Systems & C', fullWidth: true, required: true },
        { label: 'Publisher Name', name: 'publisher', type: 'text', placeholder: 'e.g. Pearson India', required: true },
        { label: 'ISBN Number', name: 'isbn', type: 'text', placeholder: 'e.g. 978-3-16-148410-0', required: true },
        { label: 'Year of Publication', name: 'year', type: 'number', placeholder: 'e.g. 2025', required: true }
      ]
    };

    function toggleProfActFields(type) {
      const container = document.getElementById('profActDynamicFields');
      if (!container) return;
      container.innerHTML = '';
      
      const fields = profActSchemas[type] || [];
      fields.forEach(f => {
        const wrap = document.createElement('div');
        wrap.className = f.fullWidth ? 'space-y-1' : 'space-y-1';
        
        const label = document.createElement('label');
        label.className = 'block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1';
        label.innerHTML = `${f.label} ${f.required ? '<span class="text-rose-500">*</span>' : ''}`;
        wrap.appendChild(label);
        
        if (f.type === 'textarea') {
          const textarea = document.createElement('textarea');
          textarea.name = `details[${f.name}]`;
          textarea.id = `field_${f.name}`;
          textarea.placeholder = f.placeholder;
          textarea.rows = 2;
          textarea.className = 'w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all resize-none';
          if (f.required) textarea.required = true;
          wrap.appendChild(textarea);
        } else {
          const input = document.createElement('input');
          input.type = f.type;
          input.name = `details[${f.name}]`;
          input.id = `field_${f.name}`;
          input.placeholder = f.placeholder;
          input.className = 'w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all';
          if (f.required) input.required = true;
          wrap.appendChild(input);
        }
        
        container.appendChild(wrap);
      });
    }
    window.toggleProfActFields = toggleProfActFields;

    async function loadProfActivities() {
      const ay = document.getElementById('profActAyFilter')?.value || '';
      const dept = document.getElementById('profActDeptFilter')?.value || '';
      const container = document.getElementById('profActListContainer');
      const ayLabel = document.getElementById('profActAyLabel');
      if (ayLabel) ayLabel.innerText = ay;

      if (container) container.innerHTML = `<div class="p-8 text-center text-slate-400 text-sm">Loading activity records...</div>`;

      try {
        const query = new URLSearchParams({ academic_year: ay, department: dept }).toString();
        const res = await fetch(`/api/staff/professional-activities/fetch?${query}`);
        const data = await res.json();

        if (data.status === 'SUCCESS' && data.records) {
          const totalCountEl = document.getElementById('profActTotalCount');
          const fdpCountEl = document.getElementById('profActFdpCount');
          const pubCountEl = document.getElementById('profActPubCount');
          const regCountEl = document.getElementById('profActRegistryCount');

          if (totalCountEl) totalCountEl.innerText = data.records.length;
          const fdpCount = data.records.filter(r => r.activity_type?.includes('fdp') || r.activity_type?.includes('workshop') || r.activity_type?.includes('course')).length;
          const pubCount = data.records.filter(r => r.activity_type?.includes('publication') || r.activity_type?.includes('book')).length;
          if (fdpCountEl) fdpCountEl.innerText = fdpCount;
          if (pubCountEl) pubCountEl.innerText = pubCount;
          if (regCountEl) regCountEl.innerText = `${data.records.length} records in AY ${ay}`;

          if (data.records.length > 0) {
            container.innerHTML = data.records.map(r => {
              const details = r.details || {};
              const actTypeFormatted = (r.activity_type || 'Activity').replace(/_/g, ' ');
              const canDelete = (r.lecturer_mobile_no === window.currentUserId) || ('{{ session('userId') }}' === r.lecturer_mobile_no);

              let detailsSnippet = '';
              if (r.activity_type === 'gap_in_syllabus') {
                detailsSnippet = `
                  <div class="text-xs text-slate-600 space-y-0.5 mt-1">
                    <div><strong class="text-slate-700">Subject:</strong> ${escapeHtml(details.subject || '-')}</div>
                    <div><strong class="text-slate-700">Identified Gap:</strong> ${escapeHtml(details.gap_details || '-')}</div>
                    <div><strong class="text-slate-700">Action Plan:</strong> ${escapeHtml(details.action_taken || '-')}</div>
                  </div>
                `;
              } else if (r.activity_type === 'project_guided' || r.activity_type === 'seminar_guided') {
                detailsSnippet = `
                  <div class="text-xs text-slate-500 flex items-center gap-2.5 pt-1 flex-wrap font-medium">
                    ${details.batch ? `<span><strong>Batch:</strong> ${escapeHtml(details.batch)}</span><span>•</span>` : ''}
                    <span><strong>Students:</strong> ${escapeHtml(details.students || '-')}</span>
                    ${details.date ? `<span>•</span><span><strong>Date:</strong> ${escapeHtml(details.date)}</span>` : ''}
                  </div>
                `;
              } else if (r.activity_type === 'publication' || r.activity_type === 'book_published') {
                detailsSnippet = `
                  <div class="text-xs text-slate-500 flex items-center gap-2.5 pt-1 flex-wrap font-medium">
                    ${details.journal ? `<span><strong>Journal:</strong> ${escapeHtml(details.journal)}</span><span>•</span>` : ''}
                    ${details.publisher ? `<span><strong>Publisher:</strong> ${escapeHtml(details.publisher)}</span><span>•</span>` : ''}
                    ${details.isbn ? `<span><strong>ISBN:</strong> ${escapeHtml(details.isbn)}</span><span>•</span>` : ''}
                    <span><strong>Year:</strong> ${escapeHtml(details.year || '-')}</span>
                  </div>
                `;
              } else {
                detailsSnippet = `
                  <div class="text-xs text-slate-500 flex items-center gap-2.5 pt-1 flex-wrap font-medium">
                    ${details.duration ? `<span><strong>Duration:</strong> ${escapeHtml(details.duration)}</span><span>•</span>` : ''}
                    ${details.venue ? `<span><strong>Venue/Platform:</strong> ${escapeHtml(details.venue)}</span><span>•</span>` : ''}
                    ${details.date ? `<span><strong>Date:</strong> ${escapeHtml(details.date)}</span>` : ''}
                  </div>
                `;
              }

              return `
                <div class="p-4 bg-white border border-slate-200/80 rounded-2xl flex items-start justify-between gap-4 hover:border-slate-300 transition-all shadow-2xs">
                  <div class="space-y-1 min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                      <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-200/80 rounded-full text-xs font-bold uppercase tracking-wider">${escapeHtml(actTypeFormatted)}</span>
                      <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md text-xs font-semibold">${escapeHtml(r.department || 'General')}</span>
                      <span class="text-xs text-slate-500 font-medium">• ${escapeHtml(r.staff_name || 'Faculty')} (${escapeHtml(r.designation || 'Lecturer')})</span>
                    </div>
                    <h5 class="font-bold text-slate-900 text-sm mt-1">${escapeHtml(details.title || details.subject || 'Professional Activity')}</h5>
                    ${detailsSnippet}
                  </div>
                  ${canDelete ? `
                    <button type="button" onclick="deleteProfActivity(${r.id})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition cursor-pointer shrink-0" title="Delete record">
                      <i data-lucide="trash-2" class="w-4 h-4 text-rose-500"></i>
                    </button>
                  ` : ''}
                </div>
              `;
            }).join('');
          } else {
            container.innerHTML = `
              <div class="p-12 text-center text-slate-400 text-sm space-y-2">
                <i data-lucide="award" class="w-8 h-8 text-slate-300 mx-auto block"></i>
                <p>No professional activity records found for AY ${escapeHtml(ay)}.</p>
                <p class="text-xs text-slate-400">Use the form on the left to record new faculty activities.</p>
              </div>
            `;
          }
          if (window.initLucide) window.initLucide();
        }
      } catch (err) {
        if (container) container.innerHTML = `<div class="p-8 text-center text-rose-500 text-sm">Failed to load professional activities.</div>`;
      }
    }
    window.loadProfActivities = loadProfActivities;

    async function submitProfActivity(e) {
      e.preventDefault();
      const alertEl = document.getElementById('profActAlert');
      const ay = document.getElementById('profActAyFilter')?.value || '{{ date('Y') }}-{{ date('Y') + 1 }}';
      const type = document.getElementById('profActType').value;
      const form = document.getElementById('profActivityForm');

      // Build details object dynamically from input schema
      const details = {};
      const inputs = form.querySelectorAll('[name^="details["]');
      inputs.forEach(inp => {
        const match = inp.name.match(/details\[(.*?)\]/);
        if (match && match[1]) {
          details[match[1]] = inp.value;
        }
      });

      const btn = document.getElementById('btnSaveProfAct');
      if (btn) btn.disabled = true;

      try {
        const res = await fetch('/staff/professional-activities/save', {
          method: 'POST',
          headers: getHeaders(),
          body: JSON.stringify({
            academic_year: ay,
            activity_type: type,
            details: details
          })
        });

        if (res.ok) {
          alertEl.classList.remove('hidden');
          alertEl.className = 'p-3 rounded-xl font-semibold border text-sm bg-emerald-50 text-emerald-800 border-emerald-200';
          alertEl.innerText = 'Professional activity recorded successfully!';
          form.reset();
          toggleProfActFields(type);
          loadProfActivities();
          setTimeout(() => alertEl.classList.add('hidden'), 3500);
        } else {
          throw new Error('Server returned error response');
        }
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-sm bg-rose-50 text-rose-800 border-rose-200';
        alertEl.innerText = 'Error saving activity record. Please check inputs.';
      } finally {
        if (btn) btn.disabled = false;
      }
    }
    window.submitProfActivity = submitProfActivity;

    async function deleteProfActivity(id) {
      if (!confirm('Are you sure you want to delete this activity record?')) return;
      try {
        const res = await fetch(`/staff/professional-activities/delete/${id}`, {
          method: 'POST',
          headers: getHeaders()
        });
        if (res.ok) {
          showGlobalMessage('Activity deleted successfully.', false);
          loadProfActivities();
        } else {
          showGlobalMessage('Failed to delete activity record.', true);
        }
      } catch (err) {
        showGlobalMessage('Network error deleting activity.', true);
      }
    }
    window.deleteProfActivity = deleteProfActivity;
  </script>

  <!-- SUBJECT PROGRESS POPUP CARD -->
  <div id="subjectProgressPopup" class="fixed hidden bg-white border border-slate-200 rounded-2xl p-4 shadow-xl z-[60] w-72 pointer-events-none transition-all flex flex-col gap-3">
    <div class="flex justify-between items-center border-b border-slate-100 pb-2">
      <h4 id="popupSubjName" class="font-bold text-sm text-slate-900 truncate w-48">Subject Name</h4>
      <span id="popupSubjCode" class="font-mono text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-md">ENG101</span>
    </div>
    <div class="space-y-2 text-xs">
      <div class="flex justify-between items-center">
        <span class="text-slate-500">Allotted Hours:</span>
        <span id="popupAllottedHours" class="font-semibold text-slate-900">0 hrs</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-500">Completed Hours:</span>
        <span id="popupCompletedHours" class="font-semibold text-slate-900">0 hrs</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-500">Assignment Initiated:</span>
        <span id="popupAssignmentStatus" class="font-semibold text-slate-400">Not Initiated</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-500">Written Test Initiated:</span>
        <span id="popupWrittenTestStatus" class="font-semibold text-slate-400">Not Initiated</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-500">MCQ Status:</span>
        <span id="popupMcqStatus" class="font-semibold text-slate-400">Not Initiated</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-500">Mid-Sem Survey:</span>
        <span id="popupMidSemStatus" class="font-semibold text-slate-400">Not Initiated</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-500">End-Sem Survey:</span>
        <span id="popupEndSemStatus" class="font-semibold text-slate-400">Not Initiated</span>
      </div>
    </div>
  </div>

  @include('mentoring_diary_modal')
  @include('partials.support_desk_overlay')
