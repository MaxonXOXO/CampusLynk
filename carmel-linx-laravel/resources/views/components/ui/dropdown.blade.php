@props([
    'align' => 'right',
    'width' => 'w-48'
])

<div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false">
    <div>
        {{ $trigger }}
    </div>

    <div 
        x-show="open" 
        class="absolute z-50 mt-2 {{ $width }} rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none border border-slate-200 py-1"
        style="display: none;"
    >
        {{ $content }}
    </div>
</div>
