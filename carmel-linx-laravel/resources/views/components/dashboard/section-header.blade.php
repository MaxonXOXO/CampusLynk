@props([
    'title' => '',
    'subtitle' => null,
    'icon' => null
])

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-slate-100">
    <div class="flex items-center gap-2.5">
        @if($icon)
            <div class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center shrink-0">
                <x-ui.icon :name="$icon" class="w-4 h-4" />
            </div>
        @endif
        <div>
            <h3 class="text-sm sm:text-base font-bold text-slate-900">{{ $title }}</h3>
            @if($subtitle)
                <p class="text-xs text-slate-500 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @if(isset($actions))
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
