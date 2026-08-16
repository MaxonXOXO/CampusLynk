@props([
    'title' => null,
    'subtitle' => null,
    'showSearch' => true,
])

<header class="h-[70px] bg-white border-b border-slate-200 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-20 shrink-0 gap-4">
    <!-- Left: Mobile Toggle & Dynamic Title -->
    <div class="flex items-center gap-3 shrink-0 min-w-0">
        <button id="sidebar-toggle-btn" onclick="toggleMobileSidebar()" type="button" class="lg:hidden p-2 text-slate-500 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition-colors" aria-label="Toggle Sidebar Navigation">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
        <div class="min-w-0">
            <h1 id="panelTitle" class="text-base sm:text-lg font-bold text-slate-900 leading-tight truncate">{{ $title ?? 'Works To Do' }}</h1>
            <p id="panelSubtitle" class="text-xs text-slate-500 hidden xl:block truncate max-w-xl">{{ $subtitle ?? 'Manage your pending assignments, series examinations and learning schedule.' }}</p>
        </div>
    </div>

    <!-- Right Header Controls (Search Bar pinned directly next to Notification Bell & Profile) -->
    <div class="flex items-center gap-3 shrink-0 ml-auto">
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
