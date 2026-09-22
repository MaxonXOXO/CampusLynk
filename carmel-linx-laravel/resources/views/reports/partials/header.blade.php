@props([
    'title' => 'STATUTORY ACADEMIC REPORT',
    'subtitle' => null,
    'department' => null,
    'academicYear' => null,
    'semester' => null,
    'documentNo' => null,
    'date' => null,
])

<div class="border-b-2 border-slate-900 pb-4 mb-6">
    <div class="flex items-center justify-between gap-4">
        {{-- College Emblem & Institutional Heading --}}
        <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-900 text-white font-black text-xl tracking-wider shadow-sm">
                CPC
            </div>
            <div>
                <h1 class="text-lg font-bold uppercase tracking-wide text-slate-950 font-['Poppins'] leading-tight">
                    Carmel Polytechnic College, Punnapra
                </h1>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-600">
                    Government Aided & Autonomous Technical Institution · AICTE Approved
                </p>
                @if($department)
                    <p class="text-xs font-bold text-indigo-900 uppercase mt-0.5">
                        Department of {{ $department }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Document Metadata Badge --}}
        <div class="text-right text-xs text-slate-600 font-mono space-y-0.5">
            @if($documentNo)
                <div class="font-bold text-slate-900">DOC: {{ $documentNo }}</div>
            @endif
            @if($academicYear)
                <div>AY: <span class="font-semibold text-slate-800">{{ $academicYear }}</span></div>
            @endif
            @if($semester)
                <div>Sem: <span class="font-semibold text-slate-800">{{ $semester }}</span></div>
            @endif
            <div>Date: <span class="font-semibold text-slate-800">{{ $date ?? date('d-m-Y') }}</span></div>
        </div>
    </div>

    {{-- Report Title Banner --}}
    <div class="mt-4 pt-3 border-t border-slate-200 text-center">
        <h2 class="text-base font-bold uppercase tracking-wider text-slate-900 font-['Poppins']">
            {{ $title }}
        </h2>
        @if($subtitle)
            <p class="text-xs text-slate-600 mt-0.5 font-medium">
                {{ $subtitle }}
            </p>
        @endif
    </div>
</div>
