                    <td class="py-3 px-4 text-slate-700 text-xs">${ex.prize || 'Participated'}</td>
                    <td class="py-3 px-4 text-center text-xs font-bold text-blue-700">${ex.points_awarded || 0}</td>
                    <td class="py-3 px-4 text-right">
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold ${ex.status === 'Verified' || ex.status === 'Approved' ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800'}">${ex.status}</span>
                    </td>
                  </tr>
                `).join('');
              }
            }

            // Mentor Meetings
            const mList = document.getElementById('smdMeetingsList');
            if (mList && data.meetings) {
              if (data.meetings.length === 0) {
                mList.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-slate-400">No mentor meetings recorded yet.</td></tr>`;
              } else {
                mList.innerHTML = data.meetings.map(m => `
                  <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-3 px-4 text-xs font-semibold text-slate-900">${m.meeting_date || '-'}</td>
                    <td class="py-3 px-4 text-xs text-slate-700">${m.discussion_points || '-'}</td>
                    <td class="py-3 px-4 text-xs text-slate-600">${m.mentor_remarks || '-'}</td>
                    <td class="py-3 px-4 text-xs text-blue-700 font-medium">${m.action_taken || '-'}</td>
                  </tr>
                `).join('');
              }
            }
          }
        })
        .catch(err => console.error('Mentoring data fetch error:', err));
    }

    function saveStudentMentoringData() {
      const payload = {
        annual_income: document.getElementById('smd_annual_income').value,
        residential_status: document.getElementById('smd_residential_status').value,
        scholarships: document.getElementById('smd_scholarships').value,
        is_fee_waiver: document.getElementById('smd_fee_waiver').checked ? 1 : 0,
        guardian_name: document.getElementById('smd_guardian_name').value,
        guardian_relationship: document.getElementById('smd_guardian_relationship').value,
        guardian_mobile: document.getElementById('smd_guardian_mobile').value,
        guardian_address: document.getElementById('smd_guardian_address').value
      };

      fetch('/api/student/mentoring/save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(d => {
        alert(d.message || "Mentoring profile details saved successfully.");
      })
      .catch(() => alert("Error saving mentoring details."));
    }

    function downloadMentoringPdf() {
      window.open('/student/mentoring/pdf', '_blank');
    }

    function loadPreClassVlmBanner() {
      fetch('/api/student/pre-class-prep-alert')
        .then(res => res.json())
        .then(data => {
          if (data && data.active) {
            document.getElementById('vlmAlertTitle').innerText = data.title || 'Evening Study Materials Available';
            document.getElementById('vlmAlertInstruction').innerText = data.instruction || 'Review lesson objectives before tomorrow morning classroom session.';
            document.getElementById('vlmAlertTargetDate').innerText = data.target_date || '';
            document.getElementById('vlmPreClassAlertBanner').classList.remove('hidden');
          }
        })
        .catch(() => {});
    }

    function openVlmVaultModal() {
      document.getElementById('vlmVaultModal').classList.remove('hidden');
      if (window.initLucide) window.initLucide();
    }
    function closeVlmVaultModal() {
      document.getElementById('vlmVaultModal').classList.add('hidden');
    }

    function changePassword() {
      const oldPwd = document.getElementById('oldPwd').value;
      const newPwd = document.getElementById('newPwd').value;
      const alertEl = document.getElementById('pwdAlert');
      if (!oldPwd || !newPwd) {
        alertEl.className = "p-3 rounded-xl text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200/60 block";
        alertEl.innerText = "Please provide both old and new password.";
        alertEl.classList.remove('hidden');
        return;
      }
      fetch('/student/update-password', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ old_password: oldPwd, new_password: newPwd })
      })
      .then(res => res.json())
      .then(d => {
        alertEl.className = "p-3 rounded-xl text-xs font-semibold " + (d.status === 'SUCCESS' ? "bg-emerald-50 text-emerald-800 border border-emerald-200/60 block" : "bg-rose-50 text-rose-800 border border-rose-200/60 block");
        alertEl.innerText = d.message || "Password updated successfully.";
        alertEl.classList.remove('hidden');
      });
    }

    function updateSbteRegNo() {
      const val = document.getElementById('sbteRegNoInput').value.trim();
      const alertEl = document.getElementById('sbteAlert');
      if (!val) return;
      fetch('/student/update-sbte-reg-no', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ sbte_reg_no: val })
      })
      .then(res => res.json())
      .then(d => {
        alertEl.className = "p-3 rounded-xl text-xs font-semibold " + (d.status === 'SUCCESS' ? "bg-emerald-50 text-emerald-800 border border-emerald-200/60 block" : "bg-rose-50 text-rose-800 border border-rose-200/60 block");
        alertEl.innerText = d.message || "SBTE Register Number saved.";
        alertEl.classList.remove('hidden');
      });
    }

    function handlePhotoUpload(e) {
      const file = e.target.files[0];
      if (!file) return;
      const statusEl = document.getElementById('photoUploadStatus');
      statusEl.className = "text-xs font-semibold text-blue-600 block";
      statusEl.innerText = "Uploading photo...";
      statusEl.classList.remove('hidden');

      const fd = new FormData();
      fd.append('photo', file);
      fetch('/student/upload-photo', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: fd
      })
      .then(res => res.json())
      .then(d => {
        if (d.status === 'SUCCESS') {
          statusEl.className = "text-xs font-semibold text-emerald-600 block";
          statusEl.innerText = "Profile photo updated.";
          if (d.photo_url) {
            const img = document.getElementById('studentProfileImg');
            if (img) img.src = d.photo_url;
          }
        } else {
          statusEl.className = "text-xs font-semibold text-rose-600 block";
          statusEl.innerText = d.message || "Failed to upload photo.";
        }
      });
    }

    function submitActivityClaim(e) {
      e.preventDefault();
      const form = document.getElementById('activityClaimForm');
      const fd = new FormData(form);
      fetch('/student/activity-points/submit', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: fd
      })
      .then(res => res.json())
      .then(d => {
        alert(d.message || "Activity claim submitted to faculty tutor.");
        form.reset();
        loadActivityPoints();
      });
    }

    function submitSeminarRegistration(e) {
      e.preventDefault();
      const topic = document.getElementById('semRegTopic').value;
      const date = document.getElementById('semRegDate').value;
      const guide = document.getElementById('semRegGuide').value;

      fetch('/student/seminar/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ topic, presentation_date: date, guide_id: guide })
      })
      .then(res => res.json())
      .then(d => {
        const toast = document.getElementById('seminarToast');
        toast.className = "p-3.5 rounded-xl text-xs font-semibold " + (d.status === 'SUCCESS' ? "bg-emerald-50 text-emerald-800 border border-emerald-200/60 block" : "bg-rose-50 text-rose-800 border border-rose-200/60 block");
        toast.innerText = d.message || "Seminar details registered.";
        toast.classList.remove('hidden');
        loadSeminarRegistration();
      });
    }

    // =========================================================================
    // ATTENDANCE REVIEW CONTROLLER
    // =========================================================================
    function loadStudentAttendanceData() {
      fetch('/api/student/attendance/data')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            attendanceLoaded = true;
            document.getElementById('attendanceLoader').classList.add('hidden');
            document.getElementById('attendanceContent').classList.remove('hidden');

            const pct = data.overall_percentage || 100;
            document.getElementById('attOverallPctText').innerText = pct + '%';
            
            const circle = document.getElementById('attGaugeCircle');
            const offset = 251.2 - ((pct / 100) * 251.2);
            circle.style.strokeDashoffset = offset;
            circle.setAttribute('stroke', pct >= 75 ? '#10B981' : (pct >= 65 ? '#F59E0B' : '#EF4444'));

            const badge = document.getElementById('attEligibilityBadge');
            badge.innerText = pct >= 75 ? 'Satisfactory & Eligible' : 'Shortage Alert (Condonation Required)';
            badge.className = 'font-bold ' + (pct >= 75 ? 'text-emerald-700' : 'text-rose-700');

            document.getElementById('attTotalConducted').innerText = (data.total_conducted || 0) + ' Hours';
            document.getElementById('attTotalAttended').innerText = (data.total_attended || 0) + ' Hours';
            document.getElementById('attTotalAbsent').innerText = Math.max(0, (data.total_conducted || 0) - (data.total_attended || 0)) + ' Hours';

            if (data.classroom) document.getElementById('attClassroomId').innerText = data.classroom.classroom_id || '-';
            if (data.tutor) {
              document.getElementById('attTutorName').innerText = data.tutor.name || 'Department Faculty Assigned';
              if (data.tutor.mobile_no) document.getElementById('attTutorContact').innerText = 'Contact: ' + data.tutor.mobile_no;
            }

            // Render hourly grid
            const hourlyGrid = document.getElementById('attHourlyGrid');
            if (data.hourly_status && data.hourly_status.length > 0) {
              hourlyGrid.innerHTML = data.hourly_status.map(p => `
                <div class="p-3.5 rounded-xl border ${p.status === 'Present' ? 'bg-emerald-50/50 border-emerald-200/80' : (p.status === 'Absent' ? 'bg-rose-50/50 border-rose-200/80' : 'bg-slate-50 border-slate-200/80')} flex flex-col justify-between space-y-2">
                  <div>
                    <div class="flex items-center justify-between">
                      <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        ${p.period === 7 ? 'Hour 7' : 'Hour ' + p.period}
                      </span>
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${p.status === 'Present' ? 'bg-emerald-100 text-emerald-800' : (p.status === 'Absent' ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-600')}">
                        ${p.status}
                      </span>
                    </div>
                    <p class="text-xs font-bold text-slate-900 mt-2 line-clamp-1" title="${p.subject_name}">
                      ${p.subject_name}
                    </p>
                    <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1" title="${p.topic}">
                      ${p.topic || 'Session'}
                    </p>
                  </div>
                  <div class="text-[10px] font-mono text-slate-400 border-t border-slate-200/60 pt-2">
                    ${p.time_slot}
                  </div>
                </div>
              `).join('');
            }

            // Render subject stats
            const subTbody = document.getElementById('attSubjectStatsList');
            if (data.subject_stats && data.subject_stats.length > 0) {
              subTbody.innerHTML = data.subject_stats.map(sub => `
                <tr class="hover:bg-slate-50/70 transition-colors">
                  <td class="py-3.5 px-4">
                    <p class="font-semibold text-slate-900 text-xs">${sub.subject_code} - ${sub.subject_name}</p>
                  </td>
                  <td class="py-3.5 px-4 text-center font-mono font-semibold text-slate-700">${sub.conducted}</td>
                  <td class="py-3.5 px-4 text-center font-mono font-bold text-blue-700">${sub.attended}</td>
                  <td class="py-3.5 px-4 text-center font-bold font-mono ${sub.percentage >= 75 ? 'text-emerald-700' : 'text-rose-600'}">
                    ${sub.percentage}%
                  </td>
                  <td class="py-3.5 px-4 text-right">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold ${sub.percentage >= 75 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/60' : 'bg-rose-50 text-rose-800 border border-rose-200/60'}">
                      ${sub.percentage >= 75 ? 'Eligible' : 'Shortage (<75%)'}
                    </span>
                  </td>
                </tr>
              `).join('');
            } else {
              subTbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-400">No subject attendance logs recorded.</td></tr>';
            }

            // Render leave records
            const leaveTbody = document.getElementById('attLeaveRecordsList');
            if (data.leave_records && data.leave_records.length > 0) {
              leaveTbody.innerHTML = data.leave_records.map(leave => `
                <tr class="hover:bg-slate-50/70 transition-colors">
                  <td class="py-3.5 px-4 font-mono font-semibold text-slate-900">${leave.leave_date}</td>
                  <td class="py-3.5 px-4 text-slate-700">${leave.reason || 'Medical / Personal Leave'}</td>
                  <td class="py-3.5 px-4 text-slate-600">${leave.period_range || 'Full Day'}</td>
                  <td class="py-3.5 px-4 text-right">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold ${leave.status === 'Approved' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/60' : 'bg-amber-50 text-amber-800 border border-amber-200/60'}">
                      ${leave.status || 'Verified by Tutor'}
                    </span>
                  </td>
                </tr>
              `).join('');
            } else {
              leaveTbody.innerHTML = '<tr><td colspan="4" class="p-6 text-center text-slate-400">No formal leave requests recorded for this semester.</td></tr>';
            }

            if (window.initLucide) window.initLucide();
          }
        })
        .catch(() => {
          document.getElementById('attendanceLoader').innerHTML = '<p class="text-xs text-rose-600 font-semibold">Error loading attendance logs.</p>';
        });
    }

    // =========================================================================
    // PRACTICE TEST ENGINE CONTROLLER
    // =========================================================================
    let mockQuestions = [];
    let mockStudentAnswers = {};
    let mockCurrentIdx = 0;
    let mockTimerInterval = null;
    let mockRemainingSeconds = 900;

    function loadMockSubjects() {
      fetch('/api/student/mock-test/subjects')
        .then(res => res.json())
        .then(d => {
          mockSubjectsLoaded = true;
          document.getElementById('mockSetupLoader').classList.add('hidden');
          document.getElementById('mockSetupForm').classList.remove('hidden');

          const grid = document.getElementById('mockSubjectGrid');
          const subjects = (d.data && d.data.subjects) ? d.data.subjects : [];
          if (subjects.length > 0) {
            grid.innerHTML = subjects.map(s => `
              <div onclick="selectMockSubjectCard('${s.subject_code}', this)" class="mock-sub-card p-4 rounded-xl border border-slate-200 hover:border-blue-500 cursor-pointer transition-all bg-white hover:bg-blue-50/30 flex items-start justify-between">
                <div>
                  <p class="text-xs font-bold text-slate-900">${s.subject_code}</p>
                  <p class="text-xs text-slate-600 mt-0.5 line-clamp-1">${s.subject_name}</p>
                  <span class="inline-block mt-2 px-2 py-0.5 rounded-full text-[10px] font-semibold ${s.already_attempted_today ? 'bg-amber-50 text-amber-800' : 'bg-emerald-50 text-emerald-800'}">
                    ${s.already_attempted_today ? 'Attempted Today' : 'Available'}
                  </span>
                </div>
                <i data-lucide="circle" class="w-4 h-4 text-slate-300 mock-card-check"></i>
              </div>
            `).join('');
            if (window.initLucide) window.initLucide();
          } else {
            grid.innerHTML = '<div class="col-span-full py-8 text-center text-slate-400 text-xs font-medium">No registered subjects available for practice test.</div>';
          }
        })
        .catch(() => {
          document.getElementById('mockSetupLoader').innerHTML = '<p class="text-xs text-rose-600 font-semibold">Error loading practice subjects.</p>';
        });
    }

    function selectMockSubjectCard(code, el) {
      document.querySelectorAll('.mock-sub-card').forEach(c => {
        c.classList.remove('border-blue-600', 'bg-blue-50/50');
        const ic = c.querySelector('.mock-card-check');
        if (ic) ic.setAttribute('data-lucide', 'circle');
      });
      el.classList.add('border-blue-600', 'bg-blue-50/50');
      const ic = el.querySelector('.mock-card-check');
      if (ic) ic.setAttribute('data-lucide', 'check-circle-2');
      document.getElementById('mockSelectedSubject').value = code;
      if (window.initLucide) window.initLucide();
    }

    function initiateMockTest() {
      const subject = document.getElementById('mockSelectedSubject').value;
      const count = parseInt(document.getElementById('mockQuestionCount').value) || 15;
      const coTag = document.getElementById('mockModuleScope').value || 'all';

      if (!subject) {
        alert('Please select a subject to start practice test.');
        return;
      }

      const btn = document.getElementById('btnStartMockTest');
      btn.disabled = true;
      btn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Generating test...';

      fetch('/api/student/mock-test/start', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ subject_code: subject, co_tag: coTag === 'all' ? 'All' : coTag, num_questions: count })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="play" class="w-4 h-4"></i><span>Launch Practice Test</span>';

        if (data.status === 'SUCCESS' && data.data && data.data.questions && data.data.questions.length > 0) {
          mockQuestions = data.data.questions;
          mockStudentAnswers = {};
          mockCurrentIdx = 0;
          mockRemainingSeconds = count * 60;

          document.getElementById('mockSetupSection').classList.add('hidden');
          document.getElementById('mockExamSection').classList.remove('hidden');
          document.getElementById('mockActiveSubjectTitle').innerText = subject + ' Practice Assessment';

          renderMockQuestionNavigator();
          displayMockCurrentQuestion();
          startMockTestTimer();
          if (window.initLucide) window.initLucide();
        } else {
          alert(data.message || 'Unable to generate practice questions.');
        }
      })
      .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<span>Launch Practice Test</span>';
        alert('Server error generating test.');
      });
    }

    function renderMockQuestionNavigator() {
      const container = document.getElementById('mockQuestionNavigator');
      container.innerHTML = mockQuestions.map((_, i) => `
        <button type="button" onclick="jumpToMockQuestion(${i})" id="mockNavDot-${i}" class="w-8 h-8 rounded-lg text-xs font-semibold border transition-all ${i === 0 ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'}">
          ${i + 1}
        </button>
      `).join('');
    }

    function displayMockCurrentQuestion() {
      const q = mockQuestions[mockCurrentIdx];
      if (!q) return;

      document.getElementById('mockActiveQuestionCounter').innerText = `Question ${mockCurrentIdx + 1} of ${mockQuestions.length}`;
      document.getElementById('mockQBadge').innerText = `Question ${mockCurrentIdx + 1} (${q.co_tag || 'Syllabus Topic'})`;
      document.getElementById('mockQText').innerText = q.question_text || q.question;

      const optsBox = document.getElementById('mockOptionsContainer');
      const selectedOpt = mockStudentAnswers[mockCurrentIdx];
      const options = q.options || [];

      optsBox.innerHTML = options.map((opt, optIdx) => `
        <label onclick="recordMockAnswer(${mockCurrentIdx}, '${opt}')" class="flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all ${selectedOpt === opt ? 'bg-blue-50 border-blue-600 text-blue-900 font-semibold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'}">
          <input type="radio" name="mockOptRadio" value="${opt}" ${selectedOpt === opt ? 'checked' : ''} class="w-4 h-4 text-blue-600 border-slate-300">
          <span class="text-xs">${opt}</span>
        </label>
      `).join('');

      document.getElementById('btnMockPrevQ').disabled = (mockCurrentIdx === 0);
      if (mockCurrentIdx === mockQuestions.length - 1) {
        document.getElementById('btnMockNextQ').classList.add('hidden');
        document.getElementById('btnMockSubmitTest').classList.remove('hidden');
      } else {
        document.getElementById('btnMockNextQ').classList.remove('hidden');
        document.getElementById('btnMockSubmitTest').classList.add('hidden');
      }

      mockQuestions.forEach((_, i) => {
        const dot = document.getElementById(`mockNavDot-${i}`);
        if (!dot) return;
        if (i === mockCurrentIdx) {
          dot.className = "w-8 h-8 rounded-lg text-xs font-semibold border bg-blue-600 text-white border-blue-600 shadow-xs";
        } else if (mockStudentAnswers[i] !== undefined) {
