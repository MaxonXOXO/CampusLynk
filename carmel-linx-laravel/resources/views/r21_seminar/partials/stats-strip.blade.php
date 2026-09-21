<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
    <x-ui.card>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-blue-400 shrink-0">
                <x-ui.icon name="users" class="w-5 h-5" />
            </div>
            <div>
                <div class="text-[0.7rem] text-slate-400 uppercase font-semibold tracking-wider">Enrolled</div>
                <div class="text-xl font-extrabold text-white">{{ $totalStudents }}</div>
            </div>
        </div>
    </x-ui.card>

    <x-ui.card>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shrink-0">
                <x-ui.icon name="check-circle" class="w-5 h-5" />
            </div>
            <div>
                <div class="text-[0.7rem] text-slate-400 uppercase font-semibold tracking-wider">Evaluated</div>
                <div class="text-xl font-extrabold text-emerald-400" id="statCompletedCount">{{ $completedCount }}</div>
            </div>
        </div>
    </x-ui.card>

    <x-ui.card>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400 shrink-0">
                <x-ui.icon name="clock" class="w-5 h-5" />
            </div>
            <div>
                <div class="text-[0.7rem] text-slate-400 uppercase font-semibold tracking-wider">Pending</div>
                <div class="text-xl font-extrabold text-amber-400" id="statPendingCount">{{ $pendingCount }}</div>
            </div>
        </div>
    </x-ui.card>

    <x-ui.card>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400 shrink-0">
                <x-ui.icon name="award" class="w-5 h-5" />
            </div>
            <div>
                <div class="text-[0.7rem] text-slate-400 uppercase font-semibold tracking-wider">Class Avg</div>
                <div class="text-xl font-extrabold text-purple-400" id="statClassAvg">{{ number_format($classAvg, 1) }}</div>
            </div>
        </div>
    </x-ui.card>

    <x-ui.card>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-sky-500/10 border border-sky-500/30 flex items-center justify-center text-sky-400 shrink-0">
                <x-ui.icon name="layers" class="w-5 h-5" />
            </div>
            <div>
                <div class="text-[0.7rem] text-slate-400 uppercase font-semibold tracking-wider">Batches</div>
                <div class="text-sm font-bold text-sky-300">
                    B1: {{ $batch1Count }} | B2: {{ $batch2Count }}
                </div>
            </div>
        </div>
    </x-ui.card>

    <x-ui.card>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400 shrink-0">
                <x-ui.icon name="user" class="w-5 h-5" />
            </div>
            <div>
                <div class="text-[0.7rem] text-slate-400 uppercase font-semibold tracking-wider">Assessor</div>
                <div class="text-xs font-bold text-indigo-200 truncate max-w-[110px]" title="{{ $activeStaff->name ?? 'Faculty' }}">
                    {{ $activeStaff->name ?? 'Faculty' }}
                </div>
            </div>
        </div>
    </x-ui.card>
</div>
