@props([
    'unreadCount' => 2
])

<div class="relative">
    <button type="button" class="group w-10 h-10 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 flex items-center justify-center shadow-sm transition-all relative focus:outline-none focus:ring-2 focus:ring-blue-500" title="Notifications">
        <i data-lucide="bell" class="w-4 h-4 text-slate-600 group-hover:text-blue-600 animate-hover-bell transition-colors"></i>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-white">
                {{ $unreadCount }}
            </span>
        @endif
    </button>
</div>
