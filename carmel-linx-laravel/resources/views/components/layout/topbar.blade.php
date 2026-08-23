@props([
    'title' => null,
    'subtitle' => null,
    'showSearch' => true,
])

<header class="h-[70px] bg-white border-b border-slate-200 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-20 shrink-0 gap-4">
    <!-- Left: Mobile Toggle & Dynamic Title -->
    <div class="flex items-center gap-3 shrink-0 min-w-0">
        <button id="sidebar-toggle-btn" onclick="toggleMobileSidebar()" type="button" class="lg:hidden p-2 text-slate-500 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition-colors" aria-label="Toggle Sidebar Navigation">
            <x-ui.icon name="menu" class="w-5 h-5" />
        </button>
        <div class="min-w-0">
            <h1 id="panelTitle" class="text-base sm:text-lg font-bold text-slate-900 leading-tight truncate">{{ $title ?? 'Works To Do' }}</h1>
            <p id="panelSubtitle" class="text-xs text-slate-500 hidden xl:block truncate max-w-xl">{{ $subtitle ?? 'Manage your pending assignments, series examinations and learning schedule.' }}</p>
        </div>
    </div>

    <!-- Right Header Controls (AI Status, Search Bar, Notifications, User Menu) -->
    <div class="flex items-center gap-3 shrink-0 ml-auto">
        <!-- Live AI System Status Indicator Pill in Topbar -->
        <div id="aiStatusBadge" class="hidden"></div>
        <div id="loadingIndicator" class="hidden items-center gap-2 text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200">
            <div class="w-3.5 h-3.5 border-2 border-slate-300 border-t-blue-600 rounded-full animate-spin"></div>
            <span>Syncing...</span>
        </div>

        @if($showSearch)
            <div class="w-64 lg:w-80 hidden md:block">
                <x-ui.search />
            </div>
        @endif

        <!-- Notification Center -->
        <x-layout.notifications />

        <!-- User Profile & Quick Settings Menu -->
        <x-layout.user-menu />
    </div>
</header>

