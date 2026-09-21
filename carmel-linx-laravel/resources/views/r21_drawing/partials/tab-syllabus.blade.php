<div class="space-y-6">
    <!-- Course Outcomes & Syllabus Upload Bar -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <x-ui.icon name="file-text" class="w-5 h-5 text-rose-600" />
                    <span>Course Outcomes (COs) &amp; Curriculum Specifications</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Defined as per Kerala SBTE R-2021 curriculum for {{ $batchSubject->subject_name }} ({{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}).
                </p>
            </div>
            <form action="/r21/classroom/drawing/{{ $batchSubject->id }}/upload-syllabus" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition-colors shadow-2xs cursor-pointer">
                    <x-ui.icon name="upload" class="w-4 h-4 text-slate-500" />
                    <span>Upload Syllabus PDF</span>
                    <input type="file" name="syllabus_file" accept=".pdf" class="hidden" onchange="this.form.submit()" />
                </label>
            </form>
        </div>

        <!-- COs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($drawingCourseFile->parsed_cos ?? [] as $co)
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="inline-block px-2.5 py-0.5 rounded-md font-bold text-xs bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $co['id'] ?? 'CO' }}
                        </span>
                        <span class="text-[11px] font-semibold text-slate-500">
                            Bloom's Level: <strong class="text-slate-700">{{ $co['cognitive_level'] ?? 'Apply' }}</strong>
                        </span>
                    </div>
                    <p class="text-xs text-slate-700 leading-relaxed font-medium">
                        {{ $co['description'] ?? '' }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 4 Modules Breakdown -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <x-ui.icon name="layers" class="w-5 h-5 text-indigo-600" />
            <span>Module Breakdown &amp; Contact Hours (Total: {{ $drawingCourseFile->contact_hours ?? 60 }} Hours)</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($drawingCourseFile->parsed_modules ?? [] as $module)
                <div class="border border-slate-200 rounded-xl p-4 bg-white space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs text-indigo-700 uppercase tracking-wider">
                            Module {{ $module['module_id'] ?? '' }}: {{ $module['title'] ?? '' }}
                        </span>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600">
                            {{ $module['hours'] ?? 15 }} Hrs
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $module['content'] ?? '' }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- CO-PO Correlation Matrix -->
    @php
        $copo = $drawingCourseFile->parsed_copo ?? [];
        $mappings = $copo['mappings'] ?? [];
        $poList = ['PO1', 'PO2', 'PO3', 'PO4', 'PO5', 'PO6', 'PO7', 'PO8', 'PO9', 'PO10'];
    @endphp
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <x-ui.icon name="grid" class="w-5 h-5 text-emerald-600" />
                <span>CO-PO Articulation Matrix</span>
            </h3>
            <span class="text-xs text-slate-500">Correlation Level: 3 = Substantial &bull; 2 = Moderate &bull; 1 = Slight &bull; - = No Correlation</span>
        </div>

        <div class="overflow-x-auto border border-slate-200 rounded-xl">
            <table class="w-full text-xs text-center text-slate-700 divide-y divide-slate-200">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[11px]">
                    <tr>
                        <th class="py-2.5 px-3 text-left w-20">Course Outcome</th>
                        @foreach($poList as $po)
                            <th class="py-2.5 px-2">{{ $po }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coId)
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-2 px-3 text-left font-bold text-slate-900 bg-slate-50/50">{{ $coId }}</td>
                            @foreach($poList as $po)
                                @php
                                    $val = $mappings[$coId][$po] ?? '-';
                                    $cellBg = $val === '3' ? 'bg-emerald-50 text-emerald-800 font-bold' :
                                        ($val === '2' ? 'bg-blue-50 text-blue-800 font-semibold' :
                                        ($val === '1' ? 'bg-amber-50 text-amber-800' : 'text-slate-400'));
                                @endphp
                                <td class="py-2 px-2 {{ $cellBg }}">{{ $val }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
