@props([
    'align' => 'right'
])

@php
    $userName = session('userName', 'User');
    $userRole = session('userRole', 'Student');
    $userPhoto = session('userPhoto', '');
    $userInitials = strtoupper(substr($userName, 0, 2));
    $isStudent = str_contains(strtolower($userRole), 'student');
@endphp

<div class="relative inline-block text-left" id="user-menu-container">
    <!-- Trigger Button -->
    <button 
        type="button" 
        onclick="toggleUserMenuDropdown()" 
        id="user-menu-trigger"
        class="flex items-center gap-3 p-1 rounded-2xl hover:bg-slate-100/80 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/20"
        aria-expanded="false" 
        aria-haspopup="true"
    >
        @if($userPhoto)
            <img src="{{ $userPhoto }}" alt="{{ $userName }}" width="36" height="36" decoding="sync" fetchpriority="high" class="w-9 h-9 rounded-xl object-cover border border-slate-200 shadow-sm shrink-0">
        @else
            <div class="w-9 h-9 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-sm shrink-0">
                {{ $userInitials }}
            </div>
        @endif
        
        <div class="hidden sm:block text-left pr-1 min-w-0">
            <p class="text-xs font-semibold text-slate-900 leading-tight truncate max-w-[120px]">{{ $userName }}</p>
            <p class="text-[10px] text-slate-500 font-medium leading-tight truncate max-w-[120px]">{{ $userRole }}</p>
        </div>

        <x-ui.icon name="chevron-down" class="w-3.5 h-3.5 text-slate-400 hidden sm:block transition-transform duration-200" id="user-menu-chevron" />
    </button>

    <!-- Floating Dropdown Menu -->
    <div 
        id="user-menu-dropdown" 
        class="hidden absolute right-0 mt-2 w-56 bg-white border border-slate-200/90 rounded-2xl shadow-xl py-1.5 z-50 transition-all duration-150 transform opacity-0 scale-95"
        role="menu" 
        aria-orientation="vertical" 
        tabindex="-1"
    >
        <!-- Header Info -->
        <div class="px-4 py-2.5 border-b border-slate-100">
            <p class="text-xs font-bold text-slate-900 truncate">{{ $userName }}</p>
            <p class="text-[11px] text-slate-500 truncate">{{ session('userId', session('email', 'Account')) }}</p>
        </div>

        <div class="py-1">
            <!-- Profile Option -->
            <button 
                type="button" 
                onclick="handleUserMenuProfileClick()"
                class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors"
                role="menuitem"
            >
                <x-ui.icon name="user-round" class="w-4 h-4 text-slate-500" />
                <span>My Profile</span>
            </button>

            @if($isStudent)
                <!-- Mentoring Diary Quick Access -->
                <button 
                    type="button" 
                    onclick="handleUserMenuMentoringClick()"
                    class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors"
                    role="menuitem"
                >
                    <x-ui.icon name="book-open" class="w-4 h-4 text-slate-500" />
                    <span>Mentoring Diary</span>
                </button>
            @endif
        </div>

        <!-- Sign Out Option -->
        <div class="border-t border-slate-100 pt-1">
            <a 
                href="{{ url('/logout') }}" 
                onclick="return confirm('Are you sure you want to sign out?')" 
                class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors"
                role="menuitem"
            >
                <x-ui.icon name="log-out" class="w-4 h-4 text-rose-500" />
                <span>Sign Out</span>
            </a>
        </div>
    </div>
</div>

<script>
    function toggleUserMenuDropdown() {
        const dropdown = document.getElementById('user-menu-dropdown');
        const chevron = document.getElementById('user-menu-chevron');
        if (!dropdown) return;

        const isHidden = dropdown.classList.contains('hidden');
        if (isHidden) {
            dropdown.classList.remove('hidden');
            setTimeout(() => {
                dropdown.classList.remove('opacity-0', 'scale-95');
                dropdown.classList.add('opacity-100', 'scale-100');
            }, 10);
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        } else {
            dropdown.classList.remove('opacity-100', 'scale-100');
            dropdown.classList.add('opacity-0', 'scale-95');
            setTimeout(() => dropdown.classList.add('hidden'), 150);
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
        if (window.initLucide) window.initLucide();
    }

    function closeUserMenuDropdown() {
        const dropdown = document.getElementById('user-menu-dropdown');
        const chevron = document.getElementById('user-menu-chevron');
        if (!dropdown || dropdown.classList.contains('hidden')) return;

        dropdown.classList.remove('opacity-100', 'scale-100');
        dropdown.classList.add('opacity-0', 'scale-95');
        setTimeout(() => dropdown.classList.add('hidden'), 150);
        if (chevron) chevron.style.transform = 'rotate(0deg)';
    }

    function handleUserMenuProfileClick() {
        closeUserMenuDropdown();
        if (typeof switchPanel === 'function') {
            switchPanel('profile');
            if (typeof selectSidebarNav === 'function') selectSidebarNav('profile');
        } else {
            window.location.href = '/dashboard/student?tab=profile';
        }
    }

    function handleUserMenuMentoringClick() {
        closeUserMenuDropdown();
        if (typeof switchPanel === 'function') {
            switchPanel('mentoring');
            if (typeof selectSidebarNav === 'function') selectSidebarNav('mentoring');
        } else {
            window.location.href = '/dashboard/student?tab=mentoring';
        }
    }

    // Dismiss on click outside
    document.addEventListener('click', (e) => {
        const menuContainer = document.getElementById('user-menu-container');
        if (menuContainer && !menuContainer.contains(e.target)) {
            closeUserMenuDropdown();
        }
    });
</script>
