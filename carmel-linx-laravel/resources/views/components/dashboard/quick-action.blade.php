@props([
    'title' => '',
    'description' => '',
    'icon' => 'arrow-right',
    'href' => '#',
    'color' => 'blue'
])

@php
    $colorClass = match($color) {
        'emerald', 'green' => 'bg-emerald-50 text-emerald-600 border-emerald-200 group-hover:bg-emerald-600 group-hover:text-white',
        'indigo' => 'bg-indigo-50 text-indigo-600 border-indigo-200 group-hover:bg-indigo-600 group-hover:text-white',
        'amber', 'yellow' => 'bg-amber-50 text-amber-600 border-amber-200 group-hover:bg-amber-600 group-hover:text-white',
        'rose', 'red' => 'bg-rose-50 text-rose-600 border-rose-200 group-hover:bg-rose-600 group-hover:text-white',
        'purple', 'violet' => 'bg-purple-50 text-purple-600 border-purple-200 group-hover:bg-purple-600 group-hover:text-white',
        default => 'bg-blue-50 text-blue-600 border-blue-200 group-hover:bg-blue-600 group-hover:text-white'
    };
@endphp

<a href="{{ $href }}" class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs hover:shadow-md hover:border-slate-300 transition-all flex items-center gap-3.5 group no-underline text-slate-800">
    <div class="w-10 h-10 rounded-xl flex items-center justify-center border {{ $colorClass }} shrink-0 transition-colors shadow-2xs">
        <x-ui.icon :name="$icon" class="w-5 h-5" />
    </div>
    <div class="min-w-0 flex-1">
        <h4 class="text-xs font-bold text-slate-900 group-hover:text-blue-600 transition-colors truncate">{{ $title }}</h4>
        @if($description)
            <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ $description }}</p>
        @endif
    </div>
    <x-ui.icon name="chevron-right" class="w-4 h-4 text-slate-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all shrink-0" />
</a>
