<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Card 1: Enrolled Students -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Enrolled Students</span>
            <span class="text-2xl font-bold text-slate-900 mt-1 block">{{ $students->count() }} Students</span>
            <span class="text-xs text-slate-400 mt-0.5 block">Batch: {{ $classroom->classroom_id ?? $batchSubject->classroom_id }}</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center shrink-0">
            <x-ui.icon name="users" class="w-6 h-6" />
        </div>
    </div>

    <!-- Card 2: Formative Assessment (40%) -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Formative Assessment (40%)</span>
            <span class="text-2xl font-bold text-blue-600 mt-1 block">{{ $formativeMax }} Marks</span>
            <span class="text-xs text-slate-400 mt-0.5 block">Min 2 Sheets / Module &bull; Timely (50%) + App. (50%)</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-sky-50 border border-sky-200 text-sky-600 flex items-center justify-center shrink-0">
            <x-ui.icon name="pen-tool" class="w-6 h-6" />
        </div>
    </div>

    <!-- Card 3: Summative Assessment (40%) -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Summative Assessment (40%)</span>
            <span class="text-2xl font-bold text-indigo-600 mt-1 block">{{ $summativeMax }} Marks</span>
            <span class="text-xs text-slate-400 mt-0.5 block">Avg of 2 Tests &bull; Procedure, Final, Dimen, Neat</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-600 flex items-center justify-center shrink-0">
            <x-ui.icon name="clipboard-check" class="w-6 h-6" />
        </div>
    </div>

    <!-- Card 4: Attendance & Total CIA -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Attendance (20%) &amp; Total CIA</span>
            <span class="text-2xl font-bold text-emerald-600 mt-1 block">{{ $attMax }}M + {{ $formativeMax + $summativeMax }}M = {{ $ciaMax }}M CIA</span>
            <span class="text-xs text-slate-400 mt-0.5 block">Attendance excluded from direct CO attainment</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0">
            <x-ui.icon name="pie-chart" class="w-6 h-6" />
        </div>
    </div>
</div>
