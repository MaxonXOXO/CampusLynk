    <form id="seminarEvaluationForm" onsubmit="submitSeminarEvaluation(event)" class="p-5 space-y-4 max-h-[80vh] overflow-y-auto">
      <div>
        <label class="block text-xs text-slate-600 font-bold uppercase tracking-wider mb-1">Student</label>
        <div id="semStudentName" class="text-base font-bold text-slate-900"></div>
        <input type="hidden" id="semStudentRegNo">
      </div>

      <!-- Relevance Slider & Input -->
      <div class="bg-slate-50 border border-slate-200 p-3.5 rounded-xl space-y-2 shadow-2xs">
        <div class="flex justify-between items-center">
          <label class="block text-xs font-bold text-slate-800">Relevance (Max 7.5)</label>
          <input type="number" step="0.1" min="0" max="7.5" id="semRelevance" required
            oninput="syncSlider('semRelevance','semRelevanceSlider',7.5); calculateSeminarTotal()"
            class="w-16 bg-white border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 shadow-2xs outline-none">
        </div>
        <input type="range" id="semRelevanceSlider" min="0" max="7.5" step="0.1" value="0"
          oninput="document.getElementById('semRelevance').value = this.value; calculateSeminarTotal()"
          class="w-full h-2 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
      </div>

      <!-- Literature Survey Slider & Input -->
      <div class="bg-slate-50 border border-slate-200 p-3.5 rounded-xl space-y-2 shadow-2xs">
        <div class="flex justify-between items-center">
          <label class="block text-xs font-bold text-slate-800">Literature Survey (Max 7.5)</label>
          <input type="number" step="0.1" min="0" max="7.5" id="semLiterature" required
            oninput="syncSlider('semLiterature','semLiteratureSlider',7.5); calculateSeminarTotal()"
            class="w-16 bg-white border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 shadow-2xs outline-none">
        </div>
        <input type="range" id="semLiteratureSlider" min="0" max="7.5" step="0.1" value="0"
          oninput="document.getElementById('semLiterature').value = this.value; calculateSeminarTotal()"
          class="w-full h-2 rounded-full accent-indigo-600 bg-slate-200 cursor-pointer">
      </div>

      <!-- Presentation Slider & Input -->
      <div class="bg-slate-50 border border-slate-200 p-3.5 rounded-xl space-y-2 shadow-2xs">
        <div class="flex justify-between items-center">
          <label class="block text-xs font-bold text-slate-800">Presentation Quality (Max 37.5)</label>
          <input type="number" step="0.5" min="0" max="37.5" id="semPresentation" required
            oninput="syncSlider('semPresentation','semPresentationSlider',37.5); calculateSeminarTotal()"
            class="w-16 bg-white border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 shadow-2xs outline-none">
        </div>
        <input type="range" id="semPresentationSlider" min="0" max="37.5" step="0.5" value="0"
          oninput="document.getElementById('semPresentation').value = this.value; calculateSeminarTotal()"
          class="w-full h-2 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
      </div>

      <!-- Compact 3 Column Input Grid -->
      <div class="grid grid-cols-3 gap-3">
        <!-- Interaction -->
        <div class="bg-slate-50 border border-slate-200 p-2.5 rounded-xl text-center space-y-1.5 shadow-2xs">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Interaction</label>
          <input type="number" step="0.5" min="0" max="7.5" id="semInteraction" required
            oninput="syncSlider(this.id,null,7.5); calculateSeminarTotal()"
            class="w-full bg-white border border-slate-200 rounded-lg px-1.5 py-1.5 text-xs font-bold text-slate-900 text-center focus:border-blue-500 shadow-2xs outline-none">
          <div class="text-[10px] text-slate-500 font-medium">max 7.5</div>
        </div>

        <!-- Report -->
        <div class="bg-slate-50 border border-slate-200 p-2.5 rounded-xl text-center space-y-1.5 shadow-2xs">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Report</label>
          <input type="number" step="0.5" min="0" max="7.5" id="semReport" required
            oninput="syncSlider(this.id,null,7.5); calculateSeminarTotal()"
            class="w-full bg-white border border-slate-200 rounded-lg px-1.5 py-1.5 text-xs font-bold text-slate-900 text-center focus:border-blue-500 shadow-2xs outline-none">
          <div class="text-[10px] text-slate-500 font-medium">max 7.5</div>
        </div>

        <!-- Attendance -->
        <div class="bg-slate-50 border border-slate-200 p-2.5 rounded-xl text-center space-y-1.5 shadow-2xs">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Attendance</label>
          <input type="number" step="0.5" min="0" max="7.5" id="semAttendance" required
            oninput="syncSlider(this.id,null,7.5); calculateSeminarTotal()"
            class="w-full bg-white border border-slate-200 rounded-lg px-1.5 py-1.5 text-xs font-bold text-slate-900 text-center focus:border-blue-500 shadow-2xs outline-none">
          <div class="text-[10px] text-slate-500 font-medium">max 7.5</div>
        </div>
      </div>

      <!-- Total Score Banner -->
      <div class="pt-4 border-t border-slate-200 flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-200 shadow-2xs">
        <div>
          <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Total Score:</span>
          <span id="semTotalScoreLabel" class="text-xl font-black text-blue-600 ml-2">0.00 / 75</span>
        </div>
        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-xs transition-premium cursor-pointer">
          Save Evaluation
        </button>
      </div>
    </form>
  </div>
</div>

<script>
    function syncSlider(inputId, sliderId, max) {
      const input = document.getElementById(inputId);
      if (!input) return;
      let val = parseFloat(input.value);
      if (isNaN(val)) val = 0;
      if (val > max) val = max;
      if (val < 0) val = 0;
      input.value = val;
      if (sliderId) {
        const slider = document.getElementById(sliderId);
        if (slider) slider.value = val;
      }
    }

    let activeSeminarData = [];

    function fetchSeminarEvaluations() {
      const tbody = document.getElementById('seminarEvaluationsTableBody');
      tbody.innerHTML = '<tr><td colspan="14" class="p-8 text-center text-slate-500 font-bold text-xs animate-pulse">Loading evaluations data...</td></tr>';
      
      const printBtn = document.getElementById('printSeminarReportBtn');
      if (printBtn) {
        printBtn.href = `/classroom/${currentSubjectId}/seminar-report`;
      }

      fetch(`/api/classroom/${currentSubjectId}/seminar/evaluations`)
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          activeSeminarData = res.data;
          renderSeminarEvaluations();
        } else {
          tbody.innerHTML = `<tr><td colspan="14" class="p-8 text-center text-red-400 font-bold text-xs">${res.message}</td></tr>`;
        }
      })
      .catch(err => {
        console.error(err);
        tbody.innerHTML = '<tr><td colspan="14" class="p-8 text-center text-red-400 font-bold text-xs">Failed to load seminar evaluations.</td></tr>';
      });
    }

    function renderSeminarEvaluations() {
      const tbody = document.getElementById('seminarEvaluationsTableBody');
      tbody.innerHTML = '';

      if (activeSeminarData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="14" class="p-8 text-center text-slate-500 italic text-xs">No students enrolled in this batch.</td></tr>';
        return;
      }

      activeSeminarData.forEach(student => {
        const me = student.my_evaluation;
        const row = document.createElement('tr');
        row.className = 'border-b border-slate-100 hover:bg-slate-50/70 text-sm font-semibold text-slate-800';
        
        row.innerHTML = `
          <td class="p-3 font-mono">${student.roll_no || '-'}</td>
          <td class="p-3 font-extrabold text-white">${student.name}</td>
          <td class="p-3 font-medium max-w-[200px] truncate" title="${student.topic || '-'}">${student.topic || '<span class="text-slate-600 italic">Not Registered</span>'}</td>
          <td class="p-3 text-slate-600">${student.guide_name || '-'}</td>
          <td class="p-3 text-center">${student.presentation_date || '-'}</td>
          <td class="p-3 text-center">${me ? me.relevance : '-'}</td>
          <td class="p-3 text-center">${me ? me.literature : '-'}</td>
          <td class="p-3 text-center">${me ? me.presentation : '-'}</td>
          <td class="p-3 text-center">${me ? me.interaction : '-'}</td>
          <td class="p-3 text-center">${me ? me.report : '-'}</td>
          <td class="p-3 text-center">${me ? me.attendance : '-'}</td>
          <td class="p-3 text-center font-bold text-slate-900">${me ? me.total_score : '-'}</td>
          <td class="p-3 text-center font-bold text-teal-700 font-bold">${student.average_score} <span class="text-[10px] text-slate-500 font-normal">(${student.evaluators_count} assessors)</span></td>
          <td class="p-3 text-center">
            <button onclick="openSeminarEvaluationModal('${student.reg_no}')" class="px-3 py-1.5 bg-blue-500/10 hover:bg-blue-500 text-blue-400 hover:text-white rounded-lg font-bold text-[11px] transition-premium cursor-pointer border border-blue-500/25">
              ${me ? 'Modify' : 'Evaluate'}
            </button>
          </td>
        `;
        tbody.appendChild(row);
      });
    }

    function openSeminarEvaluationModal(regNo) {
      const student = activeSeminarData.find(s => s.reg_no === regNo);
      if (!student) return;

      document.getElementById('semStudentName').innerText = `${student.name} (${student.reg_no})`;
      document.getElementById('semStudentRegNo').value = regNo;

      const me = student.my_evaluation;
      document.getElementById('semRelevance').value = me ? me.relevance : '';
      document.getElementById('semLiterature').value = me ? me.literature : '';
      document.getElementById('semPresentation').value = me ? me.presentation : '';
      
      document.getElementById('semRelevanceSlider').value = me ? me.relevance : 0;
      document.getElementById('semLiteratureSlider').value = me ? me.literature : 0;
      document.getElementById('semPresentationSlider').value = me ? me.presentation : 0;

      document.getElementById('semInteraction').value = me ? me.interaction : '';
      document.getElementById('semReport').value = me ? me.report : '';
      document.getElementById('semAttendance').value = me ? me.attendance : '';

      calculateSeminarTotal();
      document.getElementById('seminarEvaluationModal').classList.remove('hidden');
      document.getElementById('seminarEvaluationModal').classList.add('flex');
    }

    function closeSeminarEvaluationModal() {
      document.getElementById('seminarEvaluationModal').classList.add('hidden');
      document.getElementById('seminarEvaluationModal').classList.remove('flex');
    }

    function calculateSeminarTotal() {
      const relevance = parseFloat(document.getElementById('semRelevance').value) || 0;
      const literature = parseFloat(document.getElementById('semLiterature').value) || 0;
      const presentation = parseFloat(document.getElementById('semPresentation').value) || 0;
      const interaction = parseFloat(document.getElementById('semInteraction').value) || 0;
      const report = parseFloat(document.getElementById('semReport').value) || 0;
      const attendance = parseFloat(document.getElementById('semAttendance').value) || 0;

      const total = relevance + literature + presentation + interaction + report + attendance;
      document.getElementById('semTotalScoreLabel').innerText = `${total.toFixed(0)} / 75`;
    }

    function submitSeminarEvaluation(e) {
      e.preventDefault();
      const regNo = document.getElementById('semStudentRegNo').value;
      const relevance = parseFloat(document.getElementById('semRelevance').value) || 0;
      const literature = parseFloat(document.getElementById('semLiterature').value) || 0;
      const presentation = parseFloat(document.getElementById('semPresentation').value) || 0;
      const interaction = parseFloat(document.getElementById('semInteraction').value) || 0;
      const report = parseFloat(document.getElementById('semReport').value) || 0;
      const attendance = parseFloat(document.getElementById('semAttendance').value) || 0;

      fetch(`/api/classroom/${currentSubjectId}/seminar/evaluate`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          reg_no: regNo,
          relevance: relevance,
          literature: literature,
          presentation: presentation,
          interaction: interaction,
          report: report,
          attendance: attendance
        })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          alert('Seminar evaluation saved successfully!');
          closeSeminarEvaluationModal();
          fetchSeminarEvaluations();
        } else {
          alert(res.message);
        }
      })
      .catch(err => {
        console.error(err);
        alert('Failed to save seminar evaluation.');
      });
    }

    let todaySeminarsData = [];
    let mobSemCurrentRegNo = null;

    function clampMobSem(input, max) {
      const v = parseFloat(input.value);
      if (!isNaN(v) && v > max) input.value = max;
      if (!isNaN(v) && v < 0) input.value = 0;
      // Sync range slider sibling if present
      const sliders = input.closest('.bg-slate-100\/40, .bg-slate-100\/40.border')?.querySelectorAll('input[type=range]');
      if (sliders && sliders.length) sliders[0].value = input.value;
    }

    function showMobileSemToast(msg, type = 'success') {
      const toast = document.getElementById('mobileSemToast');
      if (!toast) return;
      
      const isSuccess = (type === 'success');
      const isWarning = (type === 'warning');
      
      toast.className = `mb-4 px-4 py-3.5 rounded-2xl text-sm font-semibold flex items-center justify-between gap-3 shadow-md transition-all border ${
        isSuccess 
          ? 'bg-white/95 border-emerald-200 text-slate-900 shadow-emerald-950/10 ring-1 ring-emerald-500/10' 
          : isWarning
            ? 'bg-white/95 border-amber-200 text-slate-900 shadow-amber-950/10 ring-1 ring-amber-500/10'
            : 'bg-white/95 border-rose-200 text-slate-900 shadow-rose-950/10 ring-1 ring-rose-500/10'
      }`;
      
      const badgeClass = isSuccess ? 'bg-emerald-50 text-emerald-600 border border-emerald-200/80' : isWarning ? 'bg-amber-50 text-amber-600 border border-amber-200/80' : 'bg-rose-50 text-rose-600 border border-rose-200/80';
      const iconSvg = isSuccess 
        ? '<svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>'
        : isWarning
          ? '<svg class="w-4 h-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>'
          : '<svg class="w-4 h-4 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';

      toast.innerHTML = `
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 ${badgeClass}">
            ${iconSvg}
          </div>
          <span class="text-slate-900 font-semibold text-sm leading-snug">${msg}</span>
        </div>
        <button type="button" onclick="this.closest(\'#mobileSemToast\').classList.add(\'hidden\')" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer text-sm font-bold shrink-0">✕</button>
      `;
      
      toast.classList.remove('hidden');
      setTimeout(() => toast.classList.add('hidden'), 5000);
    }

    function checkTodaySeminars() {
      fetch('/api/lecturer/today-seminars')
      .then(res => res.json())
      .then(res => {
        const container = document.getElementById('seminarNotificationsContainer');
        const mobContainer = document.getElementById('mobileSeminarNotificationsContainer');
        
        if (container) container.innerHTML = '';
        if (mobContainer) mobContainer.innerHTML = '';

        if (res.status === 'SUCCESS' && res.data.length > 0) {
          todaySeminarsData = res.data;

          // Group by classroom_id
          const groups = {};
          todaySeminarsData.forEach(item => {
            const cid = item.classroom_id || 'Unknown_Classroom';
            if (!groups[cid]) {
              groups[cid] = [];
            }
            groups[cid].push(item);
          });

          // Render cards
          Object.keys(groups).forEach(cid => {
            const items = groups[cid];
            const first = items[0];
            const count = items.length;

            // Desktop card
            if (container) {
              const card = document.createElement('div');
              card.className = "p-4 bg-gradient-to-br from-amber-500/20 via-orange-600/15 to-violet-950/40 border border-amber-500/40 hover:border-amber-400/80 rounded-2xl flex items-center justify-between shadow-[0_0_15px_rgba(245,158,11,0.1)] hover:shadow-[0_0_20px_rgba(245,158,11,0.2)] transition-premium cursor-pointer group relative overflow-hidden";
              card.onclick = () => {
                if (window.innerWidth < 768) {
                  openMobileSeminarEvaluation();
                } else {
                  openClassroom(cid, first.batch_subject_id, first.subject_name || 'Seminar');
                }
              };
              card.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                  <div class="bg-amber-500/10 p-2 rounded-xl text-amber-400 group-hover:bg-amber-500 group-hover:text-black transition-premium">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h20"/><path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3"/><path d="m7 21 5-5 5 5"/></svg>
                  </div>
                  <div class="min-w-0">
                    <h5 class="text-xs font-black text-amber-300 group-hover:text-white transition-premium truncate">Seminar Day (${count})</h5>
                    <p class="text-[11px] text-slate-600 mt-0.5 truncate">${cid} · ${first.subject_name || 'Seminar'}</p>
                  </div>
                </div>
                <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
              `;
              container.appendChild(card);
            }

            // Mobile card
            if (mobContainer) {
              const cardMob = document.createElement('div');
              cardMob.className = "p-4 bg-gradient-to-br from-amber-500/20 via-orange-600/15 to-violet-950/40 border border-amber-500/40 hover:border-amber-400/80 rounded-2xl flex items-center justify-between shadow-[0_0_15px_rgba(245,158,11,0.1)] transition-premium cursor-pointer group relative overflow-hidden";
              cardMob.onclick = () => {
                openMobileSeminarEvaluation();
              };
              cardMob.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                  <div class="bg-amber-500/10 p-2 rounded-xl text-amber-400 group-hover:bg-amber-500 group-hover:text-black transition-premium">
                    <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                  </div>
                  <div class="min-w-0">
                    <h5 class="text-xs font-black text-amber-300 group-hover:text-white transition-premium truncate">Active Seminar Day (${count})</h5>
                    <p class="text-[11px] text-slate-600 mt-0.5 truncate">${cid} · ${first.subject_name || 'Seminar'}</p>
                  </div>
                </div>
                <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
              `;
              mobContainer.appendChild(cardMob);
            }
          });

          if (container) container.classList.remove('hidden');
          if (mobContainer) mobContainer.classList.remove('hidden');
        } else {
          if (container) container.classList.add('hidden');
          if (mobContainer) mobContainer.classList.add('hidden');
        }
      })
      .catch(err => console.error('Failed to load today seminars:', err));
    }

    function goToVirtualSeminarClassroom() {
      // deprecated but kept as safe fallback
    }

    function openMobileSeminarEvaluation() {
      switchPanel('mobileSeminar');
      mobSemCurrentRegNo = null;
      document.getElementById('mobileSemStep1').classList.remove('hidden');
      document.getElementById('mobileSemStep2').classList.add('hidden');
      refreshMobileSeminarsList();
    }

    function backToSeminarList() {
      mobSemCurrentRegNo = null;
      document.getElementById('mobileSemStep1').classList.remove('hidden');
      document.getElementById('mobileSemStep2').classList.add('hidden');
    }

    function refreshMobileSeminarsList() {
      const pendingList = document.getElementById('mobilePendingInvitationsList');
      const attendingList = document.getElementById('mobileSemAttendingList');
      pendingList.innerHTML = '<div class="text-xs text-slate-500 text-center py-3">Loading...</div>';
      attendingList.innerHTML = '<div class="text-xs text-slate-500 text-center py-3">Loading...</div>';

      fetch('/api/lecturer/today-seminars')
      .then(res => res.json())
      .then(res => {
        if (res.status !== 'SUCCESS') { pendingList.innerHTML = '<div class="text-xs text-red-400 py-2">Failed to load.</div>'; return; }
        todaySeminarsData = res.data;

        // Pending invitations
        const pending = todaySeminarsData.filter(s => !s.accepted);
        if (pending.length === 0) {
          pendingList.innerHTML = '<div class="text-xs text-slate-500 text-center py-3">No pending invitations today.</div>';
        } else {
          pendingList.innerHTML = '';
          pending.forEach(s => {
            const card = document.createElement('div');
            card.className = 'bg-white border border-amber-700/30 rounded-xl p-4 space-y-3';
            card.innerHTML = `
              <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                  <div class="font-extrabold text-white text-sm truncate">${s.student_name}</div>
                  <div class="text-[10px] font-mono text-slate-600">${s.sbte_reg_no || '-'}</div>
                </div>
                <span class="shrink-0 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-900/60 text-amber-400 border border-amber-700/40">Pending</span>
              </div>
              <div class="bg-slate-100/60 rounded-lg px-3 py-2">
                <div class="text-[10px] text-slate-500 uppercase tracking-wide">Topic</div>
                <div class="text-xs text-white font-semibold mt-0.5 leading-snug">${s.topic || '-'}</div>
              </div>
              <div class="text-[10px] text-slate-500">Guide: <span class="text-slate-800">${s.guide_name || '-'}</span></div>
              <div class="grid grid-cols-2 gap-2">
                <button onclick="acceptMobileInvitation(${s.id})" class="py-2.5 bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center justify-center gap-1">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg> Accept
                </button>
                <button onclick="openMobSemEvaluation('${s.reg_no}')" class="py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-900 rounded-xl text-xs font-bold border border-slate-700 transition-premium cursor-pointer flex items-center justify-center gap-1">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> Evaluate
                </button>
              </div>
