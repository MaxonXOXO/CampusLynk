<div class="max-w-[210mm] mx-auto bg-white text-black p-10 min-h-[297mm] shadow-[0_0_15px_rgba(0,0,0,0.1)] font-serif relative">
    
    <!-- A4 Header -->
    <div class="text-center border-b-2 border-black pb-4 mb-6">
        <h1 class="text-base font-black uppercase tracking-wider">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
        @php
            $branchCode = $cf->batchSubject->classroom->branch ?? '';
            $fullBranch = match($branchCode) {
                'CT' => 'Computer Engineering',
                'EL' => 'Electronics Engineering',
                'ME' => 'Mechanical Engineering',
                'AU' => 'Automobile Engineering',
                'CE' => 'Civil Engineering',
                'EE' => 'Electrical & Electronics Engineering',
                default => $branchCode ?: 'General'
            };
        @endphp
        <p class="text-[10px] font-bold uppercase mt-1">Department of {{ $fullBranch }}</p>
        <p class="text-xs font-semibold mt-1">Course File - {{ $cf->academic_year }}</p>
    </div>

    <!-- Document Title -->
    <div class="text-center mb-6">
        <h2 class="text-sm font-bold uppercase underline decoration-2 underline-offset-4">Course Plan</h2>
    </div>

    <!-- Metadata -->
    <div class="grid grid-cols-2 gap-4 mb-6 text-[10px] font-bold">
        <div>Subject Code: <span class="font-normal">{{ $cf->batchSubject->subject_code ?? 'N/A' }}</span></div>
        <div class="text-right">Semester: <span class="font-normal">{{ $cf->batchSubject->semester ?? 'N/A' }}</span></div>
        <div>Subject Name: <span class="font-normal">{{ $cf->batchSubject->subject_name ?? 'N/A' }}</span></div>
        <div class="text-right">Batch: <span class="font-normal">{{ $cf->batchSubject->classroom->batch_year ?? 'N/A' }} Admission</span></div>
    </div>

    @if(count($lessonPlans) == 0)
        <div class="text-center p-10 bg-red-50 text-red-600 rounded-lg border border-red-200 mt-10">
            <span class="material-symbols-rounded text-xl mb-2">warning</span>
            <h3 class="text-xs font-bold">No Lesson Plan Found</h3>
            <p class="text-[10px]">Please generate your Lesson Plan from the Virtual Classroom first.</p>
        </div>
    @else
        <!-- Table -->
        <table class="w-full border-collapse border border-black text-[10px] mb-10 text-center">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-black px-1 py-2 w-12">Day No.</th>
                    <th class="border border-black px-2 py-2 text-left">Topic Content</th>
                    <th class="border border-black px-1 py-2 w-20">Hours as per Syllabus</th>
                    <th class="border border-black px-2 py-2 w-24">Proposed Date</th>
                    <th class="border border-black px-2 py-2 w-24">Actual Date</th>
                    <th class="border border-black px-1 py-2 w-20">Hours Engaged</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cumulativeSyllabus = 0;
                    $cumulativeEngaged = 0;
                @endphp
                @foreach($lessonPlans as $plan)
                @php
                    $cumulativeSyllabus += floatval($plan->allocated_hours);
                    $cumulativeEngaged += floatval($plan->actual_hours);
                @endphp
                <tr>
                    <td class="border border-black px-1 py-2 font-bold">{{ $plan->day_no }}</td>
                    <td class="border border-black px-2 py-2 text-left">{{ $plan->topic_content }}</td>
                    <td class="border border-black px-1 py-2">{{ $plan->allocated_hours }}</td>
                    <td class="border border-black px-2 py-2">{{ $plan->proposed_date ? \Carbon\Carbon::parse($plan->proposed_date)->format('d-m-Y') : '-' }}</td>
                    <td class="border border-black px-2 py-2">{{ $plan->actual_date ? \Carbon\Carbon::parse($plan->actual_date)->format('d-m-Y') : '-' }}</td>
                    <td class="border border-black px-1 py-2">{{ $plan->actual_hours ?: '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 font-bold">
                    <td colspan="2" class="border border-black px-2 py-2 text-right">Total:</td>
                    <td class="border border-black px-1 py-2">{{ $cumulativeSyllabus }}</td>
                    <td colspan="2" class="border border-black px-2 py-2"></td>
                    <td class="border border-black px-1 py-2">{{ $cumulativeEngaged }}</td>
                </tr>
            </tfoot>
        </table>
    @endif

    <div class="mt-8 text-xs text-slate-500 text-center">
        Note: This Course Plan is automatically extracted from your Virtual Classroom Lesson Plans. To make edits, please update the records directly from the Lecturer Dashboard.
    </div>

</div>
