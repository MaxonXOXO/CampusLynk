@props([
    'currentPage' => 1,
    'totalPages' => 1,
    'totalItems' => 0
])

<div class="flex items-center justify-between border-t border-slate-200 bg-white px-4 py-3 sm:px-6 rounded-b-2xl">
    <div class="flex flex-1 justify-between sm:hidden">
        <button class="relative inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Previous</button>
        <button class="relative ml-3 inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Next</button>
    </div>
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-slate-600">
                Showing page <span class="font-medium text-slate-900">{{ $currentPage }}</span> of <span class="font-medium text-slate-900">{{ $totalPages }}</span>
            </p>
        </div>
        <div>
            <nav class="isolate inline-flex -space-x-px rounded-xl shadow-sm" aria-label="Pagination">
                <button class="relative inline-flex items-center rounded-l-xl px-3 py-2 text-slate-400 ring-1 ring-inset ring-slate-300 hover:bg-slate-50 focus:z-20 focus:outline-offset-0">
                    ‹
                </button>
                <button class="relative z-10 inline-flex items-center bg-blue-600 px-4 py-2 text-sm font-semibold text-white focus:z-20">
                    {{ $currentPage }}
                </button>
                <button class="relative inline-flex items-center rounded-r-xl px-3 py-2 text-slate-400 ring-1 ring-inset ring-slate-300 hover:bg-slate-50 focus:z-20 focus:outline-offset-0">
                    ›
                </button>
            </nav>
        </div>
    </div>
</div>
