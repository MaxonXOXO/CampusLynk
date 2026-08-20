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
@endphp

<!-- Sidebar Backdrop (Mobile Only) -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity" onclick="toggleMobileSidebar()"></div>

<!-- Master Sidebar Container: Deep Grayish Blue Tone (#0F172A) -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0F172A] border-r border-slate-800 flex flex-col justify-between transition-all duration-300 ease-in-out shrink-0 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 -translate-x-full shadow-xl select-none">
    
    <div class="flex flex-col flex-1 min-h-0">
        <!-- Brand Header & Collapse Toggle -->
        <div class="sidebar-header h-[70px] px-4 flex items-center justify-between border-b border-slate-800/80 shrink-0">
            <div 
                onclick="handleSidebarLogoClick()" 
                class="sidebar-brand-wrapper flex items-center gap-3 overflow-hidden min-w-0 group cursor-pointer" 
                title="CampusLynk Portal"
            >
                <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-sm shrink-0 group-hover:bg-blue-500 transition-colors">
                    C
                </div>
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
            >
                <i data-lucide="panel-left-close" class="w-4 h-4"></i>
            </button>

            <!-- Mobile Close Button -->
            <button 
                type="button" 
                onclick="toggleMobileSidebar()" 
                class="lg:hidden p-1.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg"
                aria-label="Close sidebar"
            >
                <i data-lucide="x" class="w-5 h-5"></i>
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
                            <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 {{ $isActive ? 'text-blue-400' : 'text-slate-400 group-hover:text-slate-200' }} transition-colors"></i>
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
                            <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 {{ $isActive ? 'text-blue-400' : 'text-slate-400 group-hover:text-slate-200' }} transition-colors"></i>
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
                <i data-lucide="log-out" class="w-4 h-4"></i>
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
                window.location.href = '/student/dashboard';
            @elseif($isAdmin)
                window.location.href = '/dashboard/principal';
            @else
                window.location.href = '/dashboard/hod';
            @endif
        }
    }

    function handleSidebarAvatarClick() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar && sidebar.classList.contains('is-collapsed')) {
            expandSidebar();
        }
        if (typeof handleHodSidebarNav === 'function' && window.location.pathname.includes('/dashboard/hod')) {
            handleHodSidebarNav('profile');
        } else if (typeof openExecutiveProfileModal === 'function') {
            openExecutiveProfileModal();
        } else if (typeof handleStudentSidebarNav === 'function') {
            handleStudentSidebarNav('profile');
        } else {
            window.location.href = '/dashboard/hod?panel=profile';
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
        localStorage.setItem('campuslynk_sidebar_collapsed', 'true');
        if (window.initLucide) window.initLucide();
    }

    function expandSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (!sidebar) return;

        sidebar.classList.remove('is-collapsed');
        localStorage.setItem('campuslynk_sidebar_collapsed', 'false');
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

    // Restore state
    document.addEventListener('DOMContentLoaded', () => {
        const savedState = localStorage.getItem('campuslynk_sidebar_collapsed');
        if (savedState === 'true' && window.innerWidth >= 1024) {
            collapseSidebar();
        }
    });

    // Shortcut
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'b') {
            e.preventDefault();
            toggleSidebarCollapse();
        }
    });
</script>
