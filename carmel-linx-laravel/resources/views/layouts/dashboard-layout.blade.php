@props([
    'title' => 'Dashboard',
    'heading' => 'Dashboard',
    'subheading' => 'Welcome back',
    'activeNav' => 'dashboard',
    'breadcrumbs' => []
])

<x-layouts.app-shell :title="$title" :activeNav="$activeNav">
    <div class="space-y-6">
        @if(count($breadcrumbs) > 0)
            <x-ui.breadcrumb :items="$breadcrumbs" />
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $heading }}</h1>
                @if($subheading)
                    <p class="text-sm text-slate-500 mt-1">{{ $subheading }}</p>
                @endif
            </div>
            @if(isset($actions))
                <div class="flex items-center gap-3">
                    {{ $actions }}
                </div>
            @endif
        </div>

        <div>
            {{ $slot }}
        </div>
    </div>
</x-layouts.app-shell>
