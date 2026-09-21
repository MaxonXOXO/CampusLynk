<div class="text-center mb-4 border-b-2 border-slate-900 pb-3">
    <div class="text-base font-extrabold text-slate-900 uppercase tracking-wide">
        Carmel Polytechnic College, Alappuzha
    </div>
    <div class="text-xs font-bold text-slate-700 uppercase tracking-wider mt-0.5">
        Department of {{ $fullDepartment ?? $department ?? 'Engineering' }}
    </div>
    <div class="text-[11px] text-slate-600 font-semibold mt-0.5">
        State Board of Technical Education (SBTE) Kerala — Revision 2021 Regulation
    </div>
    <div class="mt-2">
        <span class="inline-block px-4 py-1 bg-slate-200 text-slate-900 font-extrabold text-xs uppercase tracking-wider rounded">
            {{ $reportTitle ?? 'Consolidated Major Project Evaluation Register' }}
        </span>
    </div>
</div>

<!-- Meta Information Box -->
<div class="w-full border border-slate-300 rounded-lg p-3 mb-4 bg-slate-50/60 text-xs grid grid-cols-2 md:grid-cols-4 gap-3">
    <div>
        <span class="text-[10px] text-slate-500 uppercase block font-semibold">Subject Code &amp; Name</span>
        <span class="font-bold text-slate-900">{{ $subject->subject_code }} — {{ $subject->subject_name }}</span>
    </div>
    <div>
        <span class="text-[10px] text-slate-500 uppercase block font-semibold">Class / Semester</span>
        <span class="font-bold text-slate-900">{{ $classroom->classroom_id ?? 'VI Sem' }} (Semester {{ $classroom->current_semester ?? $subject->semester ?? 'VI' }})</span>
    </div>
    <div>
        <span class="text-[10px] text-slate-500 uppercase block font-semibold">Scheme &amp; Academic Year</span>
        <span class="font-bold text-slate-900">R-2021 • {{ $currentYear ?? date('Y') }}</span>
    </div>
    <div>
        <span class="text-[10px] text-slate-500 uppercase block font-semibold">Maximum Marks Allocation</span>
        <span class="font-bold text-slate-900">CIA: 75M | ESE: 50M | Total: 125M</span>
    </div>
</div>
