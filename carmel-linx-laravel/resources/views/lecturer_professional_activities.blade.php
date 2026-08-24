<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CampusLynk - Faculty Professional Activities</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Modern Typography (Poppins) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Pre-Paint Synchronous Sidebar State Hydration (Anti-FOUC) -->
  <script>
    (function() {
      try {
        var isCollapsed = localStorage.getItem('campuslynk_sidebar_collapsed') === 'true' || 
                          document.cookie.indexOf('campuslynk_sidebar_collapsed=true') !== -1;
        if (isCollapsed && window.innerWidth >= 1024) {
          document.documentElement.classList.add('sidebar-is-collapsed');
        }
      } catch(e) {}
    })();
  </script>

  <!-- Vite Assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
  </style>
</head>
<body class="bg-[#FAFAFB] text-slate-900 min-h-screen font-sans antialiased sidebar-preload">

  <!-- Master Application Shell -->
  <div class="flex min-h-screen bg-[#FAFAFB]">

    <!-- Global Sidebar Navigation Component -->
    @php
      $role = Session::get('userRole');
      $sidebarRole = match($role) {
          'Super_Admin' => 'super_admin',
          'Admin' => 'admin',
          'Principal' => 'principal',
          'HOD' => 'hod',
          'Gen_Dept_Coordinator_Aided' => 'coordinator_aided',
          'Gen_Dept_Coordinator_Self_Finance' => 'coordinator_sf',
          'Demonstrator' => 'demonstrator',
          'Trade_Instructor' => 'trade_instructor',
          'Workshop_Superintendent' => 'workshop_superintendent',
          default => 'faculty'
      };
    @endphp
    <x-layout.sidebar :role="$sidebarRole" active="prof_activities" />

    <!-- Main Viewport Container -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#FAFAFB]">
      
      <!-- Global Topbar Header Component -->
      <x-layout.topbar title="Faculty Professional Activities" subtitle="FDPs, research publications, guided projects, and curriculum enhancements." />

      <!-- Scrollable Main Workspace -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
        
        <!-- Header & Academic Year Filter Card (Exact Principal Dashboard UI Sequence) -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-slate-200 p-5 rounded-2xl gap-3 shadow-xs">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-indigo-600 flex items-center justify-center shrink-0 border border-purple-200/80">
              <x-ui.icon name="school" class="w-5 h-5 text-indigo-600" />
            </div>
            <div>
              <h3 class="text-base sm:text-lg font-bold text-slate-900">Faculty Academic &amp; Professional Activities</h3>
              <p class="text-xs sm:text-sm text-slate-500 mt-0.5">FDP certifications, publications, guided projects, industrial trainings, and syllabus gap records.</p>
            </div>
          </div>
          
          <div class="flex items-center gap-2.5 w-full sm:w-auto self-stretch sm:self-auto justify-end">
            <select id="profActAyFilter" onchange="loadProfActivities()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs cursor-pointer">
              @php
                $sYear = 2020;
                $eYear = date('Y') + 3;
                $currentAy = date('Y') . '-' . (date('Y') + 1);
              @endphp
              @for($y = $eYear; $y >= $sYear; $y--)
                @php $yr = $y . '-' . ($y + 1); @endphp
                <option value="{{ $yr }}" {{ $yr === $currentAy ? 'selected' : '' }}>AY {{ $yr }}</option>
              @endfor
            </select>

            <button onclick="loadProfActivities()" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition cursor-pointer border border-slate-200 shadow-2xs" title="Refresh">
              <x-ui.icon name="sync" class="w-4 h-4 text-slate-700" />
            </button>
          </div>
        </div>

        <!-- Main Workspace Grid (5 Col Form / 7 Col Registry) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
          
          <!-- Left Column: Record New Faculty Activity Form (5 cols) -->
          <div class="lg:col-span-5 bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <x-ui.icon name="add_circle" class="w-4 h-4 text-emerald-600" />
                <span>Record New Faculty Activity</span>
              </h4>
              <span class="text-xs text-slate-400 font-mono font-bold" id="profActAyLabel">AY {{ $currentAy }}</span>
            </div>

            <form id="profActivityForm" onsubmit="submitProfActivity(event)" class="space-y-3.5">
              <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Activity Category</label>
                <select id="profActType" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-2xs cursor-pointer">
                  <option value="fdp_attended">Faculty Development Program (FDP) / Training</option>
                  <option value="workshop_attended">Technical Workshop / Hands-on BootCamp</option>
                  <option value="course_attended">Online Certification / MOOC / NPTEL Course</option>
                  <option value="project_guided">Student Major / Minor Project Guided</option>
                  <option value="seminar_guided">Student Technical Seminar Guided</option>
                  <option value="publication">Journal / Conference Research Publication</option>
                  <option value="book_published">Authored Book / Book Chapter</option>
                  <option value="gap_in_syllabus">Curriculum Gap / Industrial Bridge Topic</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Activity Title / Topic</label>
                <input type="text" id="profActTitle" required placeholder="e.g. Advanced Embedded IoT Systems FDP" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-2xs font-medium">
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Organizing Body</label>
                  <input type="text" id="profActOrganizer" required placeholder="e.g. DTE / NITTTR" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-2xs font-medium">
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Duration / Days</label>
                  <input type="text" id="profActDuration" required placeholder="e.g. 5 Days / 40 Hrs" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-2xs font-medium">
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Start Date</label>
                  <input type="date" id="profActStartDate" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-2xs font-medium">
                </div>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Description / Key Learnings</label>
                <textarea id="profActDesc" rows="2" placeholder="Brief summary of the program coverage and implementation in curriculum..." class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-2xs resize-none font-medium"></textarea>
              </div>

              <div id="profActAlert" class="hidden p-3 rounded-xl font-semibold border text-xs"></div>

              <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                <x-ui.icon name="check" class="w-4 h-4 text-white" />
                <span>Save Activity Record</span>
              </button>
            </form>
          </div>

          <!-- Right Column: Stats & Registry (7 cols) -->
          <div class="lg:col-span-7 space-y-4">
            
            <!-- 3 Stat KPI Cards -->
            <div class="grid grid-cols-3 gap-3">
              <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs text-center">
                <span class="text-xs text-slate-500 font-semibold block">Total Recorded</span>
                <span id="profActTotalCount" class="text-xl font-bold text-slate-900 block mt-0.5">0</span>
              </div>
              <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs text-center">
                <span class="text-xs text-slate-500 font-semibold block">FDPs &amp; Workshops</span>
                <span id="profActFdpCount" class="text-xl font-bold text-indigo-600 block mt-0.5">0</span>
              </div>
              <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-xs text-center">
                <span class="text-xs text-slate-500 font-semibold block">Publications</span>
                <span id="profActPubCount" class="text-xl font-bold text-emerald-600 block mt-0.5">0</span>
              </div>
            </div>

            <!-- Verified Activities Registry Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
              <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                  <x-ui.icon name="format_list_bulleted" class="w-4 h-4 text-blue-600" />
                  <span>Verified Activities Registry</span>
                </h4>
                <span id="profActRegistryCount" class="text-xs text-slate-500 font-medium">0 records</span>
              </div>

              <div id="profActListContainer" class="space-y-3 max-h-[520px] overflow-y-auto custom-scrollbar">
                <div class="p-8 text-center text-slate-400 text-sm">Loading activity records...</div>
              </div>
            </div>

          </div>

        </div>

      </main>
    </div>
  </div>

  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    // 1. PROFESSIONAL ACTIVITIES LOADER (Filtered to staff member's records)
    async function loadProfActivities() {
      const ay = document.getElementById('profActAyFilter')?.value || '{{ $currentAy }}';
      const container = document.getElementById('profActListContainer');
      const ayLabel = document.getElementById('profActAyLabel');
      if (ayLabel) ayLabel.innerText = ay;

      if (container) container.innerHTML = `<div class="p-8 text-center text-slate-400 text-sm">Loading activity records...</div>`;

      try {
        const query = new URLSearchParams({ academic_year: ay, only_mine: '1' }).toString();
        const res = await fetch(`/api/staff/professional-activities/fetch?${query}`);
        const data = await res.json();

        if (data.status === 'SUCCESS' && data.records) {
          document.getElementById('profActTotalCount').innerText = data.records.length;
          const fdpCount = data.records.filter(r => r.activity_type?.includes('fdp') || r.activity_type?.includes('workshop')).length;
          const pubCount = data.records.filter(r => r.activity_type?.includes('publication') || r.activity_type?.includes('book')).length;
          document.getElementById('profActFdpCount').innerText = fdpCount;
          document.getElementById('profActPubCount').innerText = pubCount;
          document.getElementById('profActRegistryCount').innerText = `${data.records.length} records in AY ${ay}`;

          if (data.records.length > 0) {
            container.innerHTML = data.records.map(r => {
              const details = r.details || {};
              const badgeStyle = getCategoryBadgeStyle(r.activity_type);
              return `
                <div class="p-4 bg-slate-50/70 border border-slate-200 rounded-2xl flex items-start justify-between gap-4 hover:border-blue-300 hover:bg-white transition-all shadow-2xs">
                  <div class="space-y-1 min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                      <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold uppercase tracking-wider border ${badgeStyle}">${(r.activity_type || 'Activity').replace(/_/g, ' ')}</span>
                      <span class="px-2 py-0.5 bg-slate-200 text-slate-700 rounded-md text-xs font-semibold">${r.department || '{{ Session::get("userBranch", "General") }}'}</span>
                      <span class="text-xs text-slate-500 font-medium">• ${r.staff_name || '{{ Session::get("userName", "Faculty") }}'}</span>
                    </div>
                    <h5 class="font-bold text-slate-900 text-sm leading-snug pt-0.5">${details.title || details.topic || 'Professional Activity'}</h5>
                    <p class="text-xs text-slate-600 leading-relaxed">${details.description || details.organizer || 'Organized program / workshop'}</p>
                    <div class="text-xs text-slate-500 flex items-center gap-3 pt-1 flex-wrap font-medium">
                      ${details.start_date ? `<span class="font-semibold text-blue-700">📅 Start Date: <strong>${details.start_date}</strong></span><span>•</span>` : ''}
                      <span><strong>Duration:</strong> ${details.duration || '-'}</span>
                      <span>•</span>
                      <span><strong>Organizer:</strong> ${details.organizer || '-'}</span>
                    </div>
                  </div>
                  <button onclick="deleteProfActivity(${r.id})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer shrink-0 border border-transparent hover:border-rose-200 shadow-2xs" title="Delete record">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                  </button>
                </div>
              `;
            }).join('');
          } else {
            container.innerHTML = `<div class="p-8 text-center text-slate-400 text-sm">No professional activity records found for AY ${ay}.</div>`;
          }
        }
      } catch (err) {
        if (container) container.innerHTML = `<div class="p-8 text-center text-rose-500 text-sm">Failed to load professional activities.</div>`;
      }
    }

    function getCategoryBadgeStyle(type) {
      if (!type) return 'bg-slate-100 text-slate-700 border-slate-200';
      if (type.includes('fdp')) return 'bg-blue-50 text-blue-700 border-blue-200';
      if (type.includes('workshop')) return 'bg-indigo-50 text-indigo-700 border-indigo-200';
      if (type.includes('course')) return 'bg-teal-50 text-teal-700 border-teal-200';
      if (type.includes('publication') || type.includes('book')) return 'bg-emerald-50 text-emerald-700 border-emerald-200';
      if (type.includes('gap')) return 'bg-amber-50 text-amber-700 border-amber-200';
      if (type.includes('project') || type.includes('seminar')) return 'bg-purple-50 text-purple-700 border-purple-200';
      return 'bg-slate-100 text-slate-700 border-slate-200';
    }

    // 2. SUBMIT PROFESSIONAL ACTIVITY
    async function submitProfActivity(e) {
      e.preventDefault();
      const alertEl = document.getElementById('profActAlert');
      const ay = document.getElementById('profActAyFilter')?.value || '{{ $currentAy }}';
      const type = document.getElementById('profActType').value;
      const title = document.getElementById('profActTitle').value;
      const organizer = document.getElementById('profActOrganizer').value;
      const duration = document.getElementById('profActDuration').value;
      const startDate = document.getElementById('profActStartDate')?.value || '';
      const description = document.getElementById('profActDesc').value;

      try {
        const res = await fetch('/staff/professional-activities/save', {
          method: 'POST',
          headers: { 
            'Content-Type': 'application/json', 
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken 
          },
          body: JSON.stringify({
            academic_year: ay,
            activity_type: type,
            details: { title, organizer, duration, start_date: startDate, description }
          })
        });

        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-emerald-50 text-emerald-700 border-emerald-200';
        alertEl.innerText = 'Professional activity recorded successfully!';
        document.getElementById('profActivityForm').reset();
        loadProfActivities();
        setTimeout(() => alertEl.classList.add('hidden'), 3500);
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Error saving activity record.';
      }
    }

    // 3. DELETE PROFESSIONAL ACTIVITY
    async function deleteProfActivity(id) {
      if (!confirm('Are you sure you want to delete this activity record?')) return;
      try {
        await fetch(`/staff/professional-activities/delete/${id}`, {
          method: 'POST',
          headers: { 
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken 
          }
        });
        loadProfActivities();
      } catch (err) {
        alert('Failed to delete activity.');
      }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
      loadProfActivities();
    });
  </script>
</body>
</html>
