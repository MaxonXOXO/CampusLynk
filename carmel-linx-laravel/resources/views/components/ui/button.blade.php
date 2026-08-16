@props([
    'variant' => 'primary', // primary, secondary, tertiary, icon, danger
    'type' => 'button',
    'icon' => null,
    'disabled' => false,
    'loading' => false
])

@php
    $baseStyles = "inline-flex items-center justify-center font-medium rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed select-none text-sm";
    
    $variants = [
        // Level 4: Full-Strength Action Blue (CTA ONLY)
        'primary' => 'bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white shadow-sm focus:ring-blue-500 min-h-[44px] px-5 py-2.5',
        
        // Level 1/2: Refined Secondary Button (Neutral Surface + Subtle Border)
        'secondary' => 'bg-white hover:bg-slate-50 active:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200 shadow-sm focus:ring-slate-300 min-h-[44px] px-5 py-2.5',
        
        // Level 3: Tertiary Link / Ghost Action
        'tertiary' => 'bg-transparent hover:bg-slate-100 text-slate-600 hover:text-blue-600 focus:ring-blue-500 min-h-[40px] px-3.5 py-2',
        
        // Icon Action Square (44×44px Neutral Elevation)
        'icon' => 'w-11 h-11 bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 hover:text-slate-900 shadow-sm focus:ring-slate-300 p-2.5',
        
        // Semantic Destructive
        'danger' => 'bg-rose-600 hover:bg-rose-700 text-white shadow-sm focus:ring-rose-500 min-h-[44px] px-5 py-2.5'
    ];

    $classes = $baseStyles . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} @disabled($disabled || $loading)>
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @elseif($icon)
        <span class="mr-2 inline-flex items-center">{{ $icon }}</span>
    @endif
    {{ $slot }}
</button>
