@props([
    'items' => [], // Array of ['id' => '...', 'label' => '...', 'active' => true/false]
])

<div class="border-b border-slate-200">
    <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
        @foreach($items as $item)
            @php $isActive = $item['active'] ?? false; @endphp
            <a 
                href="{{ $item['url'] ?? '#' }}" 
                class="whitespace-nowrap py-3.5 px-1 border-b-2 font-medium text-sm transition-all {{ $isActive ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}"
            >
                {{ $item['label'] }}
            </a>
        @endforeach
        {{ $slot }}
    </nav>
</div>
