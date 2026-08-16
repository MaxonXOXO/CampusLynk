@props([
    'placeholder' => 'Search pages, modules, batches... (Ctrl+K)',
    'role' => null
])

@php
    $userRole = strtolower($role ?? session('userRole', session('role', 'staff')));
    $isStudent = str_contains($userRole, 'student');
    $isAdmin = str_contains($userRole, 'admin') || str_contains($userRole, 'principal') || str_contains($userRole, 'chairman');

    if ($isStudent) {
        $searchItems = [
            ['id' => 'exams', 'title' => 'Works To Do', 'desc' => 'Assignments, continuous evaluation and active series tests', 'icon' => 'clipboard-check', 'url' => '/dashboard/student?tab=exams'],
            ['id' => 'marks', 'title' => 'Academic Stats & CIE Marks', 'desc' => 'Semester marksheets, CGPA gauge, SGPA progression', 'icon' => 'bar-chart-3', 'url' => '/dashboard/student?tab=marks'],
            ['id' => 'profile', 'title' => 'My Student Profile', 'desc' => 'Institutional register number, profile photo, password settings', 'icon' => 'user-round', 'url' => '/dashboard/student?tab=profile'],
            ['id' => 'mentoring', 'title' => 'Mentoring Diary (360° Portfolio)', 'desc' => 'Personal info, guardian details, family, education, mentor logs', 'icon' => 'book-open', 'url' => '/dashboard/student?tab=mentoring'],
            ['id' => 'activity', 'title' => 'Activity Points Portfolio', 'desc' => 'Claim extracurricular credits, verify graduation target status', 'icon' => 'award', 'url' => '/dashboard/student?tab=activity'],
            ['id' => 'seminar', 'title' => 'My Seminar Presentation', 'desc' => 'Register presentation topic, schedule, and view advisor review', 'icon' => 'presentation', 'url' => '/dashboard/student?tab=seminar'],
            ['id' => 'attendance', 'title' => 'Attendance Review & Daily Log', 'desc' => '6-period daily schedule, subject-wise attendance %, leave records', 'icon' => 'calendar-check-2', 'url' => '/dashboard/student?tab=attendance'],
            ['id' => 'mock_test', 'title' => 'Practice Test & Assessment Hub', 'desc' => 'Timed MCQ mock examinations and syllabus practice assessments', 'icon' => 'rocket', 'url' => '/dashboard/student?tab=mock_test'],
        ];
    } elseif ($isAdmin) {
        $searchItems = [
            ['id' => 'dashboard', 'title' => 'Dashboard Overview', 'desc' => 'Institutional metrics, attendance summaries, FDPs, pass rates', 'icon' => 'layout-dashboard', 'url' => '/dashboard/principal?tab=dashboard'],
            ['id' => 'directory', 'title' => 'User Accounts Directory', 'desc' => 'Search and manage student & staff accounts across departments', 'icon' => 'users', 'url' => '/dashboard/principal?tab=directory'],
            ['id' => 'backups', 'title' => 'Database Sync & Drive Backups', 'desc' => 'Automated database dumps and Google Drive cloud synchronizations', 'icon' => 'database', 'url' => '/dashboard/principal?tab=backups'],
            ['id' => 'audit', 'title' => 'System Audit Trail', 'desc' => 'Platform security logs, credential resets, and activity audit trails', 'icon' => 'receipt', 'url' => '/dashboard/principal?tab=audit'],
            ['id' => 'settings', 'title' => 'System Settings & AI Controls', 'desc' => 'Configure global AI engine parameters, syllabus parser, and API credits', 'icon' => 'settings', 'url' => '/dashboard/principal?tab=settings'],
            ['id' => 'prof_activities', 'title' => 'Faculty Professional Activities', 'desc' => 'FDP certifications, publications, MOOCs, research papers, awards', 'icon' => 'award', 'url' => '/dashboard/principal?tab=prof_activities'],
            ['id' => 'leave_ledger', 'title' => 'All-Dept Master Leave Ledger', 'desc' => 'College-wide faculty leave balances, applications, and monthly logs', 'icon' => 'calendar-range', 'url' => '/dashboard/principal?tab=leave_ledger'],
            ['id' => 'sf_attendance', 'title' => 'SF Staff Attendance Master Log', 'desc' => 'Self-financing staff biometric & GPS face attendance ledger', 'icon' => 'user-check', 'url' => '/dashboard/principal?tab=sf_attendance'],
            ['id' => 'profile', 'title' => 'Executive Profile & Security', 'desc' => 'Update administrator credentials, phone, email, and password', 'icon' => 'user-cog', 'url' => '/dashboard/principal?tab=profile'],
            ['id' => 'dept_el', 'title' => 'Electronics Engg (EL) HOD Console', 'desc' => 'Supervise Electronics department batches, course files & timetable', 'icon' => 'cpu', 'url' => '/dashboard/principal/department/EL'],
            ['id' => 'dept_me', 'title' => 'Mechanical Engg (ME) HOD Console', 'desc' => 'Supervise Mechanical department batches, course files & timetable', 'icon' => 'cog', 'url' => '/dashboard/principal/department/ME'],
            ['id' => 'dept_ce', 'title' => 'Civil Engg (CE) HOD Console', 'desc' => 'Supervise Civil department batches, course files & timetable', 'icon' => 'building', 'url' => '/dashboard/principal/department/CE'],
            ['id' => 'dept_eee', 'title' => 'Electrical Engg (EEE) HOD Console', 'desc' => 'Supervise Electrical department batches, course files & timetable', 'icon' => 'zap', 'url' => '/dashboard/principal/department/EEE'],
            ['id' => 'dept_ct', 'title' => 'Computer Engg (CT) HOD Console', 'desc' => 'Supervise Computer department batches, course files & timetable', 'icon' => 'monitor', 'url' => '/dashboard/principal/department/CT'],
            ['id' => 'dept_au', 'title' => 'Automobile Engg (AU) HOD Console', 'desc' => 'Supervise Automobile department batches, course files & timetable', 'icon' => 'car', 'url' => '/dashboard/principal/department/AU'],
            ['id' => 'dept_gen_aided', 'title' => 'General Dept (Aided) Console', 'desc' => 'Supervise General Science & Humanities Aided department', 'icon' => 'calculator', 'url' => '/dashboard/principal/department/GEN_AIDED'],
            ['id' => 'dept_gen_sf', 'title' => 'General Dept (Self Finance) Console', 'desc' => 'Supervise General Science & Humanities Self Finance department', 'icon' => 'binary', 'url' => '/dashboard/principal/department/GEN_SF'],
        ];
    } else {
        $searchItems = [
            ['id' => 'dashboard', 'title' => 'Faculty Dashboard', 'desc' => 'Course files, lesson plans, assignments, and series test evaluations', 'icon' => 'layout-dashboard', 'url' => '/dashboard/lecturer'],
            ['id' => 'academics', 'title' => 'Classroom & Course Files', 'desc' => 'Curriculum delivery, syllabus tracker, series examinations', 'icon' => 'book-open', 'url' => '/dashboard/lecturer?tab=academics'],
            ['id' => 'attendance', 'title' => 'Daily Attendance Entry', 'desc' => 'Period-wise student attendance entry and batch logs', 'icon' => 'calendar-check-2', 'url' => '/dashboard/lecturer?tab=attendance'],
            ['id' => 'prof_activities', 'title' => 'My Professional Activities', 'desc' => 'Submit FDPs, journal papers, webinars, industrial visits', 'icon' => 'award', 'url' => '/staff/professional-activities'],
            ['id' => 'leave', 'title' => 'My Leave Application & Balance', 'desc' => 'Apply for casual, duty, compensatory, and medical leaves', 'icon' => 'calendar-range', 'url' => '/staff/leave/reports'],
        ];
    }
@endphp

<div class="relative w-full max-w-sm sm:max-w-md lg:max-w-lg" id="universal-search-wrapper">
    <div class="relative flex items-center">
        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
            <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
        </div>
        <input 
            type="text" 
            id="universal-search-input"
            autocomplete="off"
            placeholder="{{ $placeholder }}" 
            onfocus="openUniversalSearchMenu()"
            oninput="filterUniversalSearch(this.value)"
            class="w-full min-h-[40px] pl-10 pr-12 py-2 bg-slate-50 border border-slate-200 text-slate-900 placeholder:text-slate-400 rounded-xl focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none text-xs sm:text-sm transition-all"
        />
        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
            <kbd class="hidden sm:inline-flex items-center gap-0.5 px-1.5 py-0.5 text-[10px] font-semibold font-mono text-slate-400 bg-white border border-slate-200 rounded-md shadow-2xs">
                ⌘K
            </kbd>
        </div>
    </div>

    <!-- Floating Spotlight Results Dropdown -->
    <div 
        id="universal-search-dropdown" 
        class="hidden absolute left-0 right-0 mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden z-50 transition-all divide-y divide-slate-100"
    >
        <div class="p-2 max-h-80 overflow-y-auto space-y-1 custom-scrollbar" id="universal-search-results">
            <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Direct Navigation &amp; Portals</div>
            
            @foreach($searchItems as $item)
                <button 
                    type="button" 
                    onclick="executeSearchNavigation('{{ $item['id'] }}', '{{ $item['url'] }}')" 
                    class="search-item w-full flex items-center gap-3 px-3 py-2 rounded-xl text-left hover:bg-blue-50 text-slate-700 hover:text-blue-700 transition-colors group"
                >
                    <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-blue-100 text-slate-600 group-hover:text-blue-600 flex items-center justify-center shrink-0">
                        <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-xs font-semibold text-slate-900 group-hover:text-blue-700 truncate">{{ $item['title'] }}</p>
                      <p class="text-[10px] text-slate-500 truncate">{{ $item['desc'] }}</p>
                    </div>
                </button>
            @endforeach
        </div>
        <div class="px-4 py-2 bg-slate-50 text-[10px] text-slate-500 flex justify-between items-center font-medium">
            <span>Navigation Quick Jump</span>
            <span>Press <kbd class="px-1 py-0.5 bg-white border border-slate-200 rounded text-[9px] font-mono">ESC</kbd> to exit</span>
        </div>
    </div>
</div>

<script>
    function openUniversalSearchMenu() {
        const dd = document.getElementById('universal-search-dropdown');
        if (dd) {
            dd.classList.remove('hidden');
            if (window.lucide) window.lucide.createIcons();
        }
    }

    function closeUniversalSearchMenu() {
        const dd = document.getElementById('universal-search-dropdown');
        if (dd) dd.classList.add('hidden');
    }

    function filterUniversalSearch(query) {
        openUniversalSearchMenu();
        const q = query.toLowerCase().trim();
        const items = document.querySelectorAll('#universal-search-results .search-item');
        items.forEach(el => {
            const text = el.innerText.toLowerCase();
            if (!q || text.includes(q)) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        });
    }

    function executeSearchNavigation(panelId, urlFallback) {
        closeUniversalSearchMenu();
        const input = document.getElementById('universal-search-input');
        if (input) input.value = '';
        
        if (panelId.startsWith('dept_')) {
            window.location.href = urlFallback;
            return;
        }

        if (typeof switchPanel === 'function') {
            switchPanel(panelId);
            if (typeof selectSidebarNav === 'function') selectSidebarNav(panelId);
        } else {
            window.location.href = urlFallback;
        }
    }

    // Dismiss search dropdown on click outside
    document.addEventListener('click', (e) => {
        const wrapper = document.getElementById('universal-search-wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            closeUniversalSearchMenu();
        }
    });

    // Global keyboard shortcut: Ctrl+K or Cmd+K
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            const input = document.getElementById('universal-search-input');
            if (input) {
                input.focus();
                input.select();
                openUniversalSearchMenu();
            }
        } else if (e.key === 'Escape') {
            closeUniversalSearchMenu();
        }
    });
</script>
