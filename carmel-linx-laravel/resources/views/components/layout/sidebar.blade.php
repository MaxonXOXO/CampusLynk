@props([
    'active' => 'dashboard',
    'role' => null,
    'customNav' => null,
    'items' => null
])

@php
    $resolvedRole = $role ?? session('userRole', session('role', 'faculty'));
    $navItems = $items ?? $customNav ?? \App\Services\NavigationService::getNavigationItems($resolvedRole, $active);
    $deskSubtitle = \App\Services\NavigationService::getDeskSubtitle($resolvedRole);
    $isStudent = \App\Services\NavigationService::resolveRoleKey($resolvedRole) === 'student';
    $isAdmin = in_array(\App\Services\NavigationService::resolveRoleKey($resolvedRole), ['admin', 'super_admin', 'principal']);
    $isCollapsed = request()->cookie('campuslynk_sidebar_collapsed') === 'true';
@endphp

<!-- Sidebar Backdrop (Mobile Only) -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity" onclick="toggleMobileSidebar()"></div>

<!-- Master Sidebar Container: Deep Grayish Blue Tone (#0F172A) -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 {{ $isCollapsed ? 'is-collapsed' : '' }} w-64 bg-[#0F172A] border-r border-slate-800 flex flex-col justify-between transition-all duration-300 ease-in-out shrink-0 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 -translate-x-full shadow-xl select-none" aria-expanded="{{ $isCollapsed ? 'false' : 'true' }}">
    
    <div class="flex flex-col flex-1 min-h-0">
        <!-- Brand Header & Collapse Toggle -->
        <div class="sidebar-header h-[70px] px-4 flex items-center justify-between border-b border-slate-800/80 shrink-0">
            <div 
                onclick="handleSidebarLogoClick()" 
                class="sidebar-brand-wrapper flex items-center gap-3 overflow-hidden min-w-0 group cursor-pointer" 
                title="CampusLynk Portal"
            >
                <img src="{{ asset('logo.svg') }}" alt="CampusLynk Logo" class="w-10 h-10 object-contain rounded-xl shrink-0 p-0.5 bg-white/5 group-hover:bg-white/10 transition-colors" />
                <div class="sidebar-label overflow-hidden transition-all duration-300">
                    <span class="font-bold text-base text-white tracking-tight block leading-tight truncate">CampusLynk</span>
                    <span class="text-xs text-slate-400 font-medium tracking-wide block truncate">
                        {{ $deskSubtitle }}
                    </span>
                </div>
            </div>

            <!-- Desktop Collapse Toggle Button (Hidden in collapsed mode) -->
            <button 
                type="button" 
                onclick="collapseSidebar()" 
                id="sidebar-collapse-btn"
                class="hidden lg:flex p-1.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all" 
                title="Collapse sidebar (Ctrl+B)"
                aria-label="Collapse Sidebar"
                aria-expanded="{{ $isCollapsed ? 'false' : 'true' }}"
            >
                <x-ui.icon name="panel-left-close" class="w-4 h-4" />
            </button>

            <!-- Mobile Close Button -->
            <button 
                type="button" 
                onclick="toggleMobileSidebar()" 
                class="lg:hidden p-1.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg"
                aria-label="Close sidebar"
            >
                <x-ui.icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="p-3 space-y-1 overflow-y-auto flex-1 scrollbar-hidden" id="sidebar-nav-container">
            @foreach($navItems as $item)
                @php 
                    $isActive = $active === $item['id']; 
                    $hasOnclick = isset($item['onclick']);
                @endphp

                @if($hasOnclick)
                    <button 
                        type="button"
                        id="nav-btn-{{ $item['id'] }}"
                        onclick="{{ $item['onclick'] }}"
                        class="w-full text-left group flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ $isActive ? 'bg-slate-800/90 text-white font-semibold border-l-2 border-blue-500 shadow-2xs' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}"
                        title="{{ $item['label'] }}"
                    >
                        <div class="w-5 h-5 flex items-center justify-center shrink-0">
                            <x-ui.icon :name="$item['icon']" class="w-4 h-4 {{ $isActive ? 'text-blue-400' : 'text-slate-400 group-hover:text-slate-200' }} transition-colors" />
                        </div>
                        <span class="sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">{{ $item['label'] }}</span>
                    </button>
                @else
                    <a 
                        href="{{ $item['url'] ?? '#' }}" 
                        @if(isset($item['target'])) target="{{ $item['target'] }}" @endif
                        id="nav-link-{{ $item['id'] }}"
                        class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ $isActive ? 'bg-slate-800/90 text-white font-semibold border-l-2 border-blue-500 shadow-2xs' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}"
                        title="{{ $item['label'] }}"
                    >
                        <div class="w-5 h-5 flex items-center justify-center shrink-0">
                            <x-ui.icon :name="$item['icon']" class="w-4 h-4 {{ $isActive ? 'text-blue-400' : 'text-slate-400 group-hover:text-slate-200' }} transition-colors" />
                        </div>
                        <span class="sidebar-label whitespace-nowrap overflow-hidden transition-all duration-300">{{ $item['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </nav>
    </div>

    <!-- Bottom User Profile Card -->
    <div class="sidebar-footer p-3 border-t border-slate-800/80 shrink-0">
        <div class="sidebar-footer-inner flex items-center gap-3 p-2.5 rounded-2xl bg-slate-800/70 border border-slate-700/60 transition-all hover:bg-slate-800">
            
            <!-- User Avatar -->
            <button 
                type="button" 
                onclick="handleSidebarAvatarClick()"
                class="relative group/avatar shrink-0 focus:outline-none flex items-center justify-center"
                title="View My Profile"
            >
                @if(session('userPhoto'))
                    <img src="{{ session('userPhoto') }}" alt="Profile" width="40" height="40" decoding="sync" fetchpriority="high" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" class="w-10 h-10 rounded-xl object-cover border border-slate-700 shadow-sm shrink-0">
                    <div style="display:none;" class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-sm shrink-0">
                        {{ strtoupper(substr(session('userName', 'U'), 0, 2)) }}
                    </div>
                @else
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-sm shrink-0">
                        {{ strtoupper(substr(session('userName', 'U'), 0, 2)) }}
                    </div>
                @endif
            </button>

            <!-- User Text Details -->
            <div 
                onclick="handleSidebarAvatarClick()"
                class="sidebar-label overflow-hidden flex-1 min-w-0 cursor-pointer transition-all duration-300"
                title="Click to view My Profile"
            >
                <p class="text-sm font-semibold text-white truncate hover:text-blue-400 transition-colors">{{ session('userName', 'Executive User') }}</p>
                <p class="text-xs text-slate-400 font-medium truncate">{{ session('userRole', 'Admin') }}</p>
            </div>

            <!-- Sign Out Button (Hidden in collapsed mode) -->
            <a href="{{ url('/logout') }}" onclick="return confirm('Are you sure you want to sign out of CampusLynk?')" class="sidebar-logout-btn p-1.5 text-slate-400 hover:text-rose-400 hover:bg-slate-700/60 rounded-lg transition-colors shrink-0" title="Sign Out">
                <x-ui.icon name="log-out" class="w-4 h-4" />
            </a>
        </div>
    </div>
</aside>

<script>
    // Universal Student Navigation Handler
    function handleStudentSidebarNav(id) {
        if (typeof switchPanel === 'function') {
            switchPanel(id);
            selectSidebarNav(id);
        } else {
            window.location.href = '/dashboard/student?tab=' + id;
        }
    }

    // Universal Admin / Principal Navigation Handler
    function handleAdminSidebarNav(id) {
        if (typeof switchPanel === 'function') {
            switchPanel(id);
            selectSidebarNav(id);
        } else {
            window.location.href = '/dashboard/principal?tab=' + id;
        }
    }

    // Universal HOD Navigation Handler
    function handleHodSidebarNav(id) {
        if (typeof window.switchPanel === 'function') {
            window.switchPanel(id);
            selectSidebarNav(id);
            try {
                const url = new URL(window.location);
                url.searchParams.set('panel', id);
                url.searchParams.delete('tab');
                window.history.replaceState({}, '', url);
            } catch (e) {}
        } else if (typeof switchPanel === 'function') {
            switchPanel(id);
            selectSidebarNav(id);
            try {
                const url = new URL(window.location);
                url.searchParams.set('panel', id);
                url.searchParams.delete('tab');
                window.history.replaceState({}, '', url);
            } catch (e) {}
        } else {
            window.location.href = '/dashboard/hod?panel=' + id;
        }
    }
    window.handleHodSidebarNav = handleHodSidebarNav;
    window.selectSidebarNav = selectSidebarNav;

    // Universal Sidebar Navigation Highlighting
    function selectSidebarNav(id) {
        document.querySelectorAll('#sidebar-nav-container button, #sidebar-nav-container a').forEach(el => {
            el.className = "w-full text-left group flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all text-slate-300 hover:bg-slate-800/70 hover:text-white";
            const icon = el.querySelector('i');
            if (icon) icon.className = "w-4 h-4 text-slate-400 group-hover:text-slate-200 transition-colors";
        });

        const activeEl = document.getElementById('nav-btn-' + id) || document.getElementById('nav-link-' + id);
        if (activeEl) {
            activeEl.className = "w-full text-left group flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all bg-slate-800/90 text-white border-l-2 border-blue-500 shadow-2xs";
            const icon = activeEl.querySelector('i');
            if (icon) icon.className = "w-4 h-4 text-blue-400 transition-colors";
        }
        if (window.initLucide) window.initLucide();
    }

    function handleSidebarLogoClick() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar && sidebar.classList.contains('is-collapsed')) {
            expandSidebar();
        } else {
            @if($isStudent)
                window.location.href = '/dashboard/student';
            @elseif($isAdmin)
                window.location.href = '/dashboard/principal';
            @elseif(session('userRole') === 'HOD')
                window.location.href = '/dashboard/hod';
            @elseif(in_array(session('userRole'), ['Demonstrator', 'Trade_Instructor', 'Workshop_Superintendent']))
                window.location.href = '/dashboard/demonstrator';
            @else
                window.location.href = '/dashboard/lecturer';
            @endif
        }
    }

    function handleSidebarAvatarClick() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar && sidebar.classList.contains('is-collapsed')) {
            expandSidebar();
        }
        if (typeof switchPanel === 'function') {
            switchPanel('security');
        } else if (typeof handleHodSidebarNav === 'function' && window.location.pathname.includes('/dashboard/hod')) {
            handleHodSidebarNav('profile');
        } else if (typeof openExecutiveProfileModal === 'function') {
            openExecutiveProfileModal();
        } else if (typeof handleStudentSidebarNav === 'function') {
            handleStudentSidebarNav('profile');
        } else {
            @if(session('userRole') === 'HOD')
                window.location.href = '/dashboard/hod?panel=profile';
            @elseif($isAdmin)
                window.location.href = '/dashboard/principal?tab=profile';
            @else
                window.location.href = '/dashboard/lecturer?panel=security';
            @endif
        }
    }

    function toggleSidebarCollapse() {
        const sidebar = document.getElementById('sidebar');
        if (!sidebar) return;
        if (sidebar.classList.contains('is-collapsed')) {
            expandSidebar();
        } else {
            collapseSidebar();
        }
    }

    function collapseSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (!sidebar) return;

        sidebar.classList.add('is-collapsed');
        document.documentElement.classList.add('sidebar-is-collapsed');
        const collapseBtn = document.getElementById('sidebar-collapse-btn');
        if (collapseBtn) collapseBtn.setAttribute('aria-expanded', 'false');

        try {
            localStorage.setItem('campuslynk_sidebar_collapsed', 'true');
            document.cookie = "campuslynk_sidebar_collapsed=true; path=/; max-age=31536000; SameSite=Lax";
        } catch(e) {}
        if (window.initLucide) window.initLucide();
    }

    function expandSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (!sidebar) return;

        sidebar.classList.remove('is-collapsed');
        document.documentElement.classList.remove('sidebar-is-collapsed');
        const collapseBtn = document.getElementById('sidebar-collapse-btn');
        if (collapseBtn) collapseBtn.setAttribute('aria-expanded', 'true');

        try {
            localStorage.setItem('campuslynk_sidebar_collapsed', 'false');
            document.cookie = "campuslynk_sidebar_collapsed=false; path=/; max-age=31536000; SameSite=Lax";
        } catch(e) {}
        if (window.initLucide) window.initLucide();
    }

    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        if (!sidebar || !backdrop) return;

        const isOpen = !sidebar.classList.contains('-translate-x-full');
        if (isOpen) {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        } else {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
        }
    }

    // Synchronize client-side state without animation layout shift
    (function() {
        try {
            const savedState = localStorage.getItem('campuslynk_sidebar_collapsed');
            const cookieState = document.cookie.indexOf('campuslynk_sidebar_collapsed=true') !== -1;
            const isCollapsed = (savedState === 'true' || (savedState === null && cookieState)) && window.innerWidth >= 1024;
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                if (isCollapsed) {
                    sidebar.classList.add('is-collapsed');
                    document.documentElement.classList.add('sidebar-is-collapsed');
                } else if (savedState === 'false') {
                    sidebar.classList.remove('is-collapsed');
                    document.documentElement.classList.remove('sidebar-is-collapsed');
                }
            }
        } catch(e) {}
    })();

    // Shortcut
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'b') {
            e.preventDefault();
            toggleSidebarCollapse();
        }
    });
</script>
