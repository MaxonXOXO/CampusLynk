@props([
    'items' => [] // Array of ['label' => '...', 'url' => '...']
])

<nav class="flex text-sm font-medium text-slate-500 mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-2">
        <li class="inline-flex items-center">
            <a href="/modern/ui-playground" class="hover:text-slate-900 inline-flex items-center text-slate-400 hover:text-blue-600 transition-colors" title="Home">
                <x-ui.icon name="home" class="w-4 h-4" />
            </a>
        </li>
        @foreach($items as $item)
            <li>
                <div class="flex items-center">
                    <x-ui.icon name="chevron-right" class="w-3.5 h-3.5 text-slate-400 mx-1" />
                    @if(isset($item['url']) && !$loop->last)
                        <a href="{{ $item['url'] }}" class="hover:text-slate-900 transition-colors">{{ $item['label'] }}</a>
                    @else
                        <span class="text-slate-900 font-semibold">{{ $item['label'] }}</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
