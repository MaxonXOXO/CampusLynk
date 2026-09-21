<div class="mt-8 pt-4 border-t border-slate-300 text-xs">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <!-- Faculty Guide -->
        <div class="space-y-1">
            <div class="h-12 border-b border-dashed border-slate-400"></div>
            <div class="font-bold text-slate-900">{{ $guideName ?? 'Faculty Guide' }}</div>
            <div class="text-[10px] text-slate-500">Project Guide / Coordinator</div>
        </div>

        <!-- Internal Examiner -->
        <div class="space-y-1">
            <div class="h-12 border-b border-dashed border-slate-400"></div>
            <div class="font-bold text-slate-900">{{ $examiners['internal_name'] ?? 'Internal Examiner' }}</div>
            <div class="text-[10px] text-slate-500">{{ $examiners['internal_designation'] ?? 'Lecturer' }}</div>
        </div>

        <!-- External Examiner -->
        <div class="space-y-1">
            <div class="h-12 border-b border-dashed border-slate-400"></div>
            <div class="font-bold text-slate-900">{{ $examiners['external_name'] ?? 'External Examiner' }}</div>
            <div class="text-[10px] text-slate-500">{{ $examiners['external_designation'] ?? 'External Examiner' }}</div>
        </div>

        <!-- Head of Department -->
        <div class="space-y-1">
            <div class="h-12 border-b border-dashed border-slate-400"></div>
            <div class="font-bold text-slate-900">Head of Department</div>
            <div class="text-[10px] text-slate-500">Department of {{ $department ?? 'Engineering' }}</div>
        </div>
    </div>

    <div class="flex justify-between items-center mt-6 text-[10px] text-slate-500">
        <div>Exam Date: <strong>{{ $examiners['exam_date'] ?? date('d/m/Y') }}</strong></div>
        <div>Carmel Polytechnic College, Alappuzha</div>
        <div>Principal / Institutional Seal</div>
    </div>
</div>
