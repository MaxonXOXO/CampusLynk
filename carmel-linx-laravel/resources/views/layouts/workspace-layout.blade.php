@props([
    'title' => 'Workspace',
    'subjectCode' => '',
    'subjectName' => '',
    'activeNav' => 'academics'
])

<x-layouts.app-shell :title="$title" :activeNav="$activeNav">
    <div class="space-y-6">
        <!-- Workspace Header Banner -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">{{ $subjectCode }}</span>
                    <span class="text-xs text-slate-500 font-medium">Virtual Classroom Studio</span>
                </div>
                <h1 class="text-xl font-bold text-slate-900">{{ $subjectName }}</h1>
            </div>
            @if(isset($headerActions))
                <div class="flex items-center gap-2">
                    {{ $headerActions }}
                </div>
            @endif
        </div>

        <!-- Main Workspace Canvas -->
        <div>
            {{ $slot }}
        </div>
    </div>
</x-layouts.app-shell>
