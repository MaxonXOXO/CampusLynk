@props([
    'removable' => true
])

<button type="button" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-medium bg-white border border-slate-200 text-slate-700 hover:border-slate-300 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-500']) }}>
    <span>{{ $slot }}</span>
    @if($removable)
        <span class="text-slate-400 hover:text-slate-600 font-bold ml-0.5">✕</span>
    @endif
</button>
