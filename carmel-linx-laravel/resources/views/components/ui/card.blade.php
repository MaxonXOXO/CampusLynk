@props([
    'title' => null,
    'subtitle' => null,
    'action' => null
])

<div {{ $attributes->merge(['class' => 'bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-200']) }}>
    @if($title || $action)
        <div class="flex items-center justify-between mb-4">
            <div>
                @if($title)
                    <h3 class="text-base font-semibold text-slate-900">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-xs text-slate-500 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if($action)
                <div>{{ $action }}</div>
            @endif
        </div>
    @endif
    <div>
        {{ $slot }}
    </div>
</div>
