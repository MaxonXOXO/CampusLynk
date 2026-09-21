<div class="mt-8 pt-4 border-t border-slate-300 text-xs">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <!-- Faculty Guide -->
        <div class="space-y-1">
            <div class="h-12 border-b border-dashed border-slate-400"></div>
            <div class="font-bold text-slate-900">Faculty Guide / Assessor</div>
            <div class="text-[10px] text-slate-500">Seminar Guide</div>
        </div>

        <!-- Committee Member 1 -->
        <div class="space-y-1">
            <div class="h-12 border-b border-dashed border-slate-400"></div>
            <div class="font-bold text-slate-900">Committee Member 1</div>
            <div class="text-[10px] text-slate-500">Faculty Assessor</div>
        </div>

        <!-- Committee Member 2 -->
        <div class="space-y-1">
            <div class="h-12 border-b border-dashed border-slate-400"></div>
            <div class="font-bold text-slate-900">Committee Member 2</div>
            <div class="text-[10px] text-slate-500">Senior Faculty</div>
        </div>

        <!-- Head of Department -->
        <div class="space-y-1">
            <div class="h-12 border-b border-dashed border-slate-400"></div>
            <div class="font-bold text-slate-900">Head of Department</div>
            <div class="text-[10px] text-slate-500">Department of {{ $fullDepartment ?? $classroom->branch ?? 'Engineering' }}</div>
        </div>
    </div>

    <div class="flex justify-between items-center mt-6 text-[10px] text-slate-500">
        <div>Evaluation Date: <strong>{{ date('d/m/Y') }}</strong></div>
        <div>Carmel Polytechnic College, Alappuzha</div>
        <div>Principal / Institutional Seal</div>
    </div>
</div>
