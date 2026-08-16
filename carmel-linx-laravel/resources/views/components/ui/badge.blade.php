@props([
    'variant' => 'active', // active, pending, completed, on_hold, cancelled, draft
    'dot' => true
])

@php
    $variants = [
        'active' => [
            'badge' => 'bg-emerald-50 text-emerald-800 border border-emerald-200/60',
            'dot' => 'bg-emerald-500'
        ],
        'pending' => [
            'badge' => 'bg-amber-50 text-amber-800 border border-amber-200/60',
            'dot' => 'bg-amber-500'
        ],
        'completed' => [
            'badge' => 'bg-blue-50 text-blue-800 border border-blue-200/60',
            'dot' => 'bg-blue-500'
        ],
        'on_hold' => [
            'badge' => 'bg-slate-50 text-slate-700 border border-slate-200',
            'dot' => 'bg-slate-400'
        ],
        'cancelled' => [
            'badge' => 'bg-rose-50 text-rose-800 border border-rose-200/60',
            'dot' => 'bg-rose-500'
        ],
        'draft' => [
            'badge' => 'bg-slate-50 text-slate-600 border border-slate-200',
            'dot' => 'bg-slate-400'
        ]
    ];

    $current = $variants[$variant] ?? $variants['active'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium select-none ' . $current['badge']]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $current['dot'] }}"></span>
    @endif
    {{ $slot }}
</span>
