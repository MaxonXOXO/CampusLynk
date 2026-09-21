@props([
    'title' => '',
    'value' => '0',
    'icon' => 'activity',
    'color' => 'blue',
    'subtext' => null,
    'trend' => null,
    'trendUp' => true
])

@php
    $colorClasses = match($color) {
        'emerald', 'green' => [
            'iconBg' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
            'border' => 'hover:border-emerald-300',
        ],
        'indigo' => [
            'iconBg' => 'bg-indigo-50 text-indigo-600 border-indigo-200',
            'border' => 'hover:border-indigo-300',
        ],
        'amber', 'yellow' => [
            'iconBg' => 'bg-amber-50 text-amber-600 border-amber-200',
            'border' => 'hover:border-amber-300',
        ],
        'rose', 'red' => [
            'iconBg' => 'bg-rose-50 text-rose-600 border-rose-200',
            'border' => 'hover:border-rose-300',
        ],
        'purple', 'violet' => [
            'iconBg' => 'bg-purple-50 text-purple-600 border-purple-200',
            'border' => 'hover:border-purple-300',
        ],
        default => [
            'iconBg' => 'bg-blue-50 text-blue-600 border-blue-200',
            'border' => 'hover:border-blue-300',
        ]
    };
@endphp

<div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs transition-all {{ $colorClasses['border'] }} group">
    <div class="flex items-start justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">{{ $title }}</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-slate-900 tracking-tight">{{ $value }}</span>
                @if($trend)
                    <span class="inline-flex items-center text-xs font-bold {{ $trendUp ? 'text-emerald-600' : 'text-rose-600' }}">
                        <x-ui.icon :name="$trendUp ? 'trending-up' : 'trending-down'" class="w-3.5 h-3.5 mr-0.5" />
                        {{ $trend }}
                    </span>
                @endif
            </div>
            @if($subtext)
                <p class="text-xs text-slate-500 mt-1 font-medium">{{ $subtext }}</p>
            @endif
        </div>
        <div class="w-11 h-11 rounded-xl flex items-center justify-center border {{ $colorClasses['iconBg'] }} shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
            <x-ui.icon :name="$icon" class="w-5 h-5" />
        </div>
    </div>
</div>
