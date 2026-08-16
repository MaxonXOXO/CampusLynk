@props([
    'variant' => 'info', // success, warning, error, info
    'title' => null,
    'dismissible' => true
])

@php
    $variants = [
        'success' => [
            'box' => 'bg-emerald-50/70 border-emerald-200/70 text-emerald-900',
            'icon' => 'check-circle-2',
            'iconColor' => 'text-emerald-600'
        ],
        'warning' => [
            'box' => 'bg-amber-50/70 border-amber-200/70 text-amber-900',
            'icon' => 'alert-triangle',
            'iconColor' => 'text-amber-600'
        ],
        'error' => [
            'box' => 'bg-rose-50/70 border-rose-200/70 text-rose-900',
            'icon' => 'alert-octagon',
            'iconColor' => 'text-rose-600'
        ],
        'info' => [
            'box' => 'bg-blue-50/60 border-blue-200/60 text-slate-800',
            'icon' => 'info',
            'iconColor' => 'text-blue-600'
        ]
    ];

    $current = $variants[$variant] ?? $variants['info'];
@endphp

<div {{ $attributes->merge(['class' => 'border rounded-xl p-4 flex items-start justify-between text-sm transition-all ' . $current['box']]) }} role="alert">
    <div class="flex items-start gap-3">
        <div class="mt-0.5 shrink-0">
            <i data-lucide="{{ $current['icon'] }}" class="w-4 h-4 {{ $current['iconColor'] }}"></i>
        </div>
        <div>
            @if($title)
                <h4 class="font-semibold text-sm mb-0.5 text-slate-900">{{ $title }}</h4>
            @endif
            <div class="text-sm font-normal text-slate-600">{{ $slot }}</div>
        </div>
    </div>
    @if($dismissible)
        <button type="button" class="text-slate-400 hover:text-slate-600 ml-3 p-1 rounded-lg hover:bg-black/5 transition-colors" onclick="this.closest('[role=alert]').remove()" aria-label="Dismiss Alert">
            <i data-lucide="x" class="w-3.5 h-3.5"></i>
        </button>
    @endif
</div>
