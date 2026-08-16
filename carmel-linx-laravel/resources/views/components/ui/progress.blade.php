@props([
    'percentage' => 0,
    'label' => null,
    'showValue' => true
])

<div class="w-full">
    @if($label || $showValue)
        <div class="flex justify-between items-center text-xs font-medium text-slate-600 mb-1.5">
            @if($label) <span>{{ $label }}</span> @endif
            @if($showValue) <span>{{ $percentage }}%</span> @endif
        </div>
    @endif
    <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
        <div 
            class="h-full bg-blue-600 rounded-full transition-all duration-300" 
            style="width: {{ min(max((int)$percentage, 0), 100) }}%"
        ></div>
    </div>
</div>
