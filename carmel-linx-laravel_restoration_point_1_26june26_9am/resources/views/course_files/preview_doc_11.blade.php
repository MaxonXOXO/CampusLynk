<div class="max-w-[210mm] mx-auto bg-white text-black p-10 min-h-[297mm] shadow-[0_0_15px_rgba(0,0,0,0.1)] font-serif relative">
    
    <!-- A4 Header -->
    <div class="text-center border-b-2 border-black pb-4 mb-6">
        <h1 class="text-2xl font-black uppercase tracking-wider">Carmel Polytechnic College</h1>
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
        <p class="text-sm font-bold uppercase mt-1">Department of {{ $fullBranch }}</p>
        <p class="text-xs font-semibold mt-1">Course File - {{ $cf->academic_year }}</p>
    </div>

    <!-- Document Title -->
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold uppercase underline decoration-2 underline-offset-4">Internal Examination Result Analysis</h2>
    </div>

    <!-- Metadata -->
    <div class="grid grid-cols-2 gap-4 mb-6 text-sm font-bold">
        <div>Subject Code: <span class="font-normal">{{ $cf->batchSubject->subject_code ?? 'N/A' }}</span></div>
        <div class="text-right">Semester: <span class="font-normal">{{ $cf->batchSubject->semester ?? 'N/A' }}</span></div>
        <div>Subject Name: <span class="font-normal">{{ $cf->batchSubject->subject_name ?? 'N/A' }}</span></div>
        <div class="text-right">Batch: <span class="font-normal">{{ $cf->batchSubject->classroom->batch_year ?? 'N/A' }} Admission</span></div>
    </div>

    @if(count($students) == 0)
        <div class="text-center p-10 bg-red-50 text-red-600 rounded-lg border border-red-200 mt-10">
            <span class="material-symbols-rounded text-4xl mb-2">warning</span>
            <h3 class="text-lg font-bold">No Marks Found</h3>
            <p class="text-sm">There are no student semester marks recorded for this subject in the database.</p>
        </div>
    @else
        <!-- Table -->
        <div class="overflow-x-auto w-full">
            <table class="w-full border-collapse border border-black text-[10px] mb-10 text-center whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-black px-1 py-1" rowspan="2">Sl No.</th>
                        <th class="border border-black px-2 py-1" rowspan="2">Register Number</th>
                        <th class="border border-black px-2 py-1 text-left" rowspan="2">Student Name</th>
                        <th class="border border-black px-1 py-1" colspan="4">Written Tests</th>
                        <th class="border border-black px-1 py-1" colspan="4">Assignments</th>
                        <th class="border border-black px-1 py-1" colspan="4">Online Tests</th>
                        <th class="border border-black px-2 py-1 bg-indigo-100" rowspan="2">Final Marks<br>(Out of 100)</th>
                    </tr>
                    <tr class="bg-gray-100">
                        <th class="border border-black px-1 py-1">CO1<br>({{ $maxMarks['tests']['CO1'] }})</th>
                        <th class="border border-black px-1 py-1">CO2<br>({{ $maxMarks['tests']['CO2'] }})</th>
                        <th class="border border-black px-1 py-1">CO3<br>({{ $maxMarks['tests']['CO3'] }})</th>
                        <th class="border border-black px-1 py-1">CO4<br>({{ $maxMarks['tests']['CO4'] }})</th>
                        
                        <th class="border border-black px-1 py-1">CO1<br>({{ $maxMarks['assignments']['CO1'] }})</th>
                        <th class="border border-black px-1 py-1">CO2<br>({{ $maxMarks['assignments']['CO2'] }})</th>
                        <th class="border border-black px-1 py-1">CO3<br>({{ $maxMarks['assignments']['CO3'] }})</th>
                        <th class="border border-black px-1 py-1">CO4<br>({{ $maxMarks['assignments']['CO4'] }})</th>
                        
                        <th class="border border-black px-1 py-1">CO1<br>({{ $maxMarks['online']['CO1'] }})</th>
                        <th class="border border-black px-1 py-1">CO2<br>({{ $maxMarks['online']['CO2'] }})</th>
                        <th class="border border-black px-1 py-1">CO3<br>({{ $maxMarks['online']['CO3'] }})</th>
                        <th class="border border-black px-1 py-1">CO4<br>({{ $maxMarks['online']['CO4'] }})</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="border border-black px-1 py-1">{{ $index + 1 }}</td>
                        <td class="border border-black px-2 py-1 font-mono text-[9px]">{{ $student['reg_no'] }}</td>
                        <td class="border border-black px-2 py-1 text-left font-bold">{{ $student['name'] }}</td>
                        
                        <!-- Written Tests -->
                        <td class="border border-black px-1 py-1">{{ $student['tests']['CO1'] }}</td>
                        <td class="border border-black px-1 py-1">{{ $student['tests']['CO2'] }}</td>
                        <td class="border border-black px-1 py-1">{{ $student['tests']['CO3'] }}</td>
                        <td class="border border-black px-1 py-1">{{ $student['tests']['CO4'] }}</td>
                        
                        <!-- Assignments -->
                        <td class="border border-black px-1 py-1">{{ $student['assignments']['CO1'] }}</td>
                        <td class="border border-black px-1 py-1">{{ $student['assignments']['CO2'] }}</td>
                        <td class="border border-black px-1 py-1">{{ $student['assignments']['CO3'] }}</td>
                        <td class="border border-black px-1 py-1">{{ $student['assignments']['CO4'] }}</td>
                        
                        <!-- Online Tests -->
                        <td class="border border-black px-1 py-1">{{ $student['online']['CO1'] }}</td>
                        <td class="border border-black px-1 py-1">{{ $student['online']['CO2'] }}</td>
                        <td class="border border-black px-1 py-1">{{ $student['online']['CO3'] }}</td>
                        <td class="border border-black px-1 py-1">{{ $student['online']['CO4'] }}</td>
                        
                        <td class="border border-black px-2 py-1 font-bold text-indigo-700 bg-indigo-50">{{ $student['out_of_100'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-8 text-xs text-slate-500 text-center">
        Note: This is the raw itemized marksheet. The complex NBA Tier 3 calculations for CO-PO attainment weighting has been separated into the dedicated NBA Accreditation Module.
    </div>

</div>
