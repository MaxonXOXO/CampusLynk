@props([
    'items' => [],
    'emptyText' => 'No pending approvals at this time.'
])

<div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
            <x-ui.icon name="check-circle" class="w-4 h-4 text-amber-500" />
            <span>Action Required / Pending Approvals</span>
        </h3>
        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
            {{ count($items) }} Pending
        </span>
    </div>

    <div class="divide-y divide-slate-100">
        @forelse($items as $item)
            <div class="py-3 flex items-center justify-between gap-3 text-xs">
                <div class="space-y-0.5">
                    <p class="font-semibold text-slate-900">{{ $item['title'] ?? '' }}</p>
                    <p class="text-slate-500">{{ $item['subtitle'] ?? '' }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ $item['action_url'] ?? '#' }}" class="px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 transition-colors no-underline">
                        Review
                    </a>
                </div>
            </div>
        @empty
            <div class="py-6 text-center text-slate-400">
                <x-ui.icon name="check-circle-2" class="w-7 h-7 mx-auto mb-1.5 text-slate-300" />
                <p class="text-xs">{{ $emptyText }}</p>
            </div>
        @endforelse
    </div>
</div>
