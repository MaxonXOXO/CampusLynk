@php
    $branchNames = [
        'EL' => 'Electronics Engineering',
        'ME' => 'Mechanical Engineering',
        'CE' => 'Civil Engineering',
        'EEE' => 'Electrical & Electronics Engineering',
        'CT' => 'Computer Engineering',
        'AU' => 'Automobile Engineering'
    ];
    $fullBranchName = $branchNames[$student->branch] ?? $student->branch;
@endphp

<x-layouts.report-layout 
    title="Mentoring Diary - {{ $student->reg_no }}" 
    orientation="portrait" 
    documentNo="{{ $student->reg_no }}/MENTOR">

    {{-- PAGE 1: Personal Profile, Family, Prior Education --}}
    @include('reports.partials.header', [
        'title' => 'STUDENT MENTORING DIARY',
        'subtitle' => 'Comprehensive Academic & Personal Mentoring Record',
        'department' => $fullBranchName,
        'academicYear' => $student->admission_year,
        'semester' => "Batch: {$student->classroom_id}",
        'documentNo' => "{$student->reg_no}/MENTOR"
    ])

    <div class="bg-indigo-950 text-white font-bold text-xs uppercase px-3 py-1.5 rounded-lg mb-3">I. Personal Profile</div>
    
    <div class="flex gap-4 mb-4">
        <div class="w-32 h-40 border-2 border-slate-300 rounded-lg flex items-center justify-center bg-slate-50 text-slate-400 text-xs text-center flex-shrink-0 overflow-hidden">
            @if($student->photo_url)
                <img src="{{ $student->photo_url }}" alt="Student Photo" class="w-full h-full object-cover">
            @else
                <span>Affix<br>Passport<br>Photo</span>
            @endif
        </div>
        
        <table class="w-full text-xs border border-slate-300">
            <tbody>
                <tr>
                    <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold w-1/3">Student Name</th>
                    <td colspan="3" class="p-1.5 border border-slate-300 font-bold">{{ $student->name }}</td>
                </tr>
                <tr>
                    <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold">Login ID / Reg No</th>
                    <td class="p-1.5 border border-slate-300 font-mono">{{ $student->reg_no }}</td>
                    <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold">SBTE Reg No</th>
                    <td class="p-1.5 border border-slate-300 font-mono">{{ $student->sbte_reg_no ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold">Admission Year</th>
                    <td class="p-1.5 border border-slate-300">{{ $student->admission_year ?? '-' }}</td>
                    <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold">Admission Type</th>
                    <td class="p-1.5 border border-slate-300">{{ $student->admission_type ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold">Branch</th>
                    <td class="p-1.5 border border-slate-300">{{ $student->branch ?? '-' }}</td>
                    <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold">Gender</th>
                    <td class="p-1.5 border border-slate-300">{{ $extended_profile->gender ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold">Email</th>
                    <td class="p-1.5 border border-slate-300">{{ $student->email ?? '-' }}</td>
                    <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold">Mobile</th>
                    <td class="p-1.5 border border-slate-300 font-mono">{{ $student->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold">Communication Address</th>
                    <td colspan="3" class="p-1.5 border border-slate-300">{{ $extended_profile->communication_address ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="bg-indigo-950 text-white font-bold text-xs uppercase px-3 py-1.5 rounded-lg mb-3 mt-4">II. Parent / Guardian Details</div>
    <table class="w-full text-xs border border-slate-300 mb-4">
        <tbody>
            <tr>
                <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold w-1/4">Father's Name</th>
                <td class="p-1.5 border border-slate-300">{{ $extended_profile->father_name ?? '-' }}</td>
                <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold w-1/4">Occupation</th>
                <td class="p-1.5 border border-slate-300">{{ $extended_profile->father_occupation ?? '-' }}</td>
            </tr>
            <tr>
                <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold">Mother's Name</th>
                <td class="p-1.5 border border-slate-300">{{ $extended_profile->mother_name ?? '-' }}</td>
                <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold">Occupation</th>
                <td class="p-1.5 border border-slate-300">{{ $extended_profile->mother_occupation ?? '-' }}</td>
            </tr>
            <tr>
                <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold">Parent Contact</th>
                <td class="p-1.5 border border-slate-300 font-mono">{{ $student->parent_phone ?? '-' }}</td>
                <th class="p-1.5 bg-slate-50 border border-slate-300 font-semibold">Annual Income</th>
                <td class="p-1.5 border border-slate-300 font-mono">{{ $extended_profile->annual_income ? '₹' . number_format($extended_profile->annual_income) : '-' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="bg-indigo-950 text-white font-bold text-xs uppercase px-3 py-1.5 rounded-lg mb-3 mt-4">III. Prior Educational Qualifications</div>
    <table class="w-full text-xs border border-slate-300 mb-6">
        <thead class="bg-slate-100">
            <tr>
                <th class="p-1.5 border border-slate-300">Course / Degree</th>
                <th class="p-1.5 border border-slate-300">Institution</th>
                <th class="p-1.5 border border-slate-300 w-28 text-center">Year</th>
                <th class="p-1.5 border border-slate-300 w-28 text-center">% / Grade</th>
            </tr>
        </thead>
        <tbody>
            @if(count((array)$education) > 0)
                @foreach($education as $edu)
                <tr>
                    <td class="p-1.5 border border-slate-300">{{ $edu->course ?? '-' }}</td>
                    <td class="p-1.5 border border-slate-300">{{ $edu->institution ?? '-' }}</td>
                    <td class="p-1.5 border border-slate-300 text-center font-mono">{{ $edu->year_of_completion ?? '-' }}</td>
                    <td class="p-1.5 border border-slate-300 text-center font-bold font-mono">{{ $edu->total_percentage ?? '-' }}</td>
                </tr>
                @endforeach
            @else
                <tr><td colspan="4" class="p-3 text-center text-slate-400">No prior education records found.</td></tr>
            @endif
        </tbody>
    </table>

    {{-- Academic Progress, Board Exams, Activities, Meetings --}}
    @include('partials.mentoring_diary_academics')

    {{-- Placement & Training --}}
    <div class="page-break pt-6">
        <div class="bg-indigo-950 text-white font-bold text-xs uppercase px-3 py-1.5 rounded-lg mb-3">VIII. Placement and Training Details</div>
        <table class="w-full text-xs border border-slate-300 mb-8">
            <tbody>
                <tr>
                    <th class="p-2 bg-slate-50 border border-slate-300 w-1/3">Placement Status / Company Details</th>
                    <td class="p-2 border border-slate-300">{{ $student->placement_details ?? 'No placement records found.' }}</td>
                </tr>
                <tr>
                    <th class="p-2 bg-slate-50 border border-slate-300">Higher Studies Remark</th>
                    <td class="p-2 border border-slate-300">{{ $student->higher_studies_remark ?? 'No records found.' }}</td>
                </tr>
                <tr>
                    <th class="p-2 bg-slate-50 border border-slate-300">Scholarships Availed</th>
                    <td class="p-2 border border-slate-300">{{ $student->scholarships ?? 'No records found.' }}</td>
                </tr>
            </tbody>
        </table>

        @include('reports.partials.signatures', [
            'facultyLabel' => 'Signature of Student',
            'hodLabel' => 'Signature of Mentor',
            'principalLabel' => 'Signature of HOD'
        ])
    </div>
</x-layouts.report-layout>
