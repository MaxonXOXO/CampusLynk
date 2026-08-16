@props([
    'type' => 'text',
    'label' => null,
    'name' => null,
    'id' => null,
    'placeholder' => null,
    'value' => null,
    'icon' => null,
    'error' => null,
    'disabled' => false
])

@php
    $inputId = $id ?? $name ?? 'input-' . uniqid();
    $inputClasses = "w-full min-h-[44px] px-4 py-2.5 bg-white border text-slate-900 placeholder:text-slate-400 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none text-sm transition-all disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed";
    
    if ($icon) {
        $inputClasses .= " pl-10";
    }

    if ($error) {
        $inputClasses .= " border-rose-400 focus:border-rose-500 focus:ring-rose-500/10";
    } else {
        $inputClasses .= " border-slate-200 hover:border-slate-300";
    }
@endphp

<div class="w-full">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-slate-700 mb-1.5">
            {{ $label }}
        </label>
    @endif
    
    <div class="relative rounded-xl">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                {{ $icon }}
            </div>
        @endif
        
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $inputId }}" 
            placeholder="{{ $placeholder }}" 
            value="{{ $value }}"
            @disabled($disabled)
            {{ $attributes->merge(['class' => $inputClasses]) }}
        />
    </div>

    @if($error)
        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $error }}</p>
    @endif
</div>
