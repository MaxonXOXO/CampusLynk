@props([
    'id',
    'title' => null,
    'maxWidth' => 'max-w-lg'
])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('{{ $id }}').classList.add('hidden')"></div>

    <div class="flex min-h-screen items-center justify-center p-4 text-center">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full {{ $maxWidth }} border border-slate-200">
            @if($title)
                <div class="border-b border-slate-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900" id="modal-title">{{ $title }}</h3>
                    <button type="button" class="text-slate-400 hover:text-slate-600 font-bold" onclick="document.getElementById('{{ $id }}').classList.add('hidden')">✕</button>
                </div>
            @endif
            
            <div class="px-6 py-5">
                {{ $slot }}
            </div>

            @if(isset($footer))
                <div class="bg-slate-50 border-t border-slate-200 px-6 py-3 flex items-center justify-end gap-3 rounded-b-2xl">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
