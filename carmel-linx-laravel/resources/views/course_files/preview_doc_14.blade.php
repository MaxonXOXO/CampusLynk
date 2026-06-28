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
    </div>

    <!-- Document Title -->
    <div class="text-center mb-8">
        <h2 class="text-sm font-black uppercase border-b border-gray-400 inline-block pb-1">Document 14: Assignment Questions & Evaluation</h2>
    </div>

    <!-- Metadata Table -->
    <div class="mb-8">
        <table class="w-full text-[10px] border border-black mb-6">
            <tbody>
                <tr>
                    <td class="border border-black p-2 font-bold w-1/4 bg-gray-100">Subject</td>
                    <td class="border border-black p-2 font-mono uppercase font-bold">{{ $cf->batchSubject->subject_code ?? 'N/A' }} - {{ $cf->batchSubject->subject_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="border border-black p-2 font-bold bg-gray-100">Batch</td>
                    <td class="border border-black p-2 font-mono uppercase">{{ $cf->batchSubject->classroom_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="border border-black p-2 font-bold bg-gray-100">Faculty</td>
                    <td class="border border-black p-2 font-bold uppercase">{{ session('userName') ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if(empty($assignmentQuestions))
        <div class="border-2 border-red-500 bg-red-50 p-6 text-center text-red-700 font-bold mb-8">
            NO ASSIGNMENT QUESTIONS FOUND.
            <p class="text-[10px] font-normal text-red-600 mt-2">Please go to Virtual Classroom and generate assignment questions first.</p>
        </div>
    @else
        <!-- Part A: Assignment Questions -->
        <h3 class="text-md font-black uppercase mb-3 border-b-2 border-black pb-1">Part A: Assignment Questions per CO</h3>
        <div class="mb-10 text-[10px]">
            @foreach($assignmentQuestions as $coTag => $questionsList)
                <div class="mb-4">
                    <h4 class="font-bold text-gray-800">{{ $coTag }}</h4>
                    <ol class="list-decimal list-inside ml-4 text-gray-700 mt-1 space-y-1">
                        @foreach($questionsList as $q)
                            <li>{{ preg_replace('/^\d+\.\s*/', '', $q) }}</li>
                        @endforeach
                    </ol>
                </div>
            @endforeach
        </div>

        <!-- Part B: Evaluation Mark List -->
        <h3 class="text-md font-black uppercase mb-3 border-b-2 border-black pb-1">Part B: Evaluation Marks List</h3>
        <div class="mb-10">
            @if(empty($studentsData))
                <p class="text-[10px] text-gray-500 italic">No enrolled students found.</p>
            @else
                <table class="w-full text-xs border border-black text-center border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-black p-2 w-10">No.</th>
                            <th class="border border-black p-2 w-24">Reg No</th>
                            <th class="border border-black p-2 text-left">Student Name</th>
                            @foreach(array_keys($assignmentQuestions) as $coTag)
                                <th class="border border-black p-2 w-16">{{ $coTag }}<br><span class="text-[10px] font-normal">Mark</span></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentsData as $idx => $student)
                            <tr>
                                <td class="border border-black p-1.5">{{ $idx + 1 }}</td>
                                <td class="border border-black p-1.5 font-mono">{{ $student->reg_no }}</td>
                                <td class="border border-black p-1.5 text-left font-bold text-gray-800">{{ $student->name }}</td>
                                @foreach(array_keys($assignmentQuestions) as $coTag)
                                    @php
                                        $markObj = $student->marks[$coTag] ?? null;
                                    @endphp
                                    <td class="border border-black p-1.5 {{ !$markObj ? 'text-gray-400' : 'font-bold' }}">
                                        {{ $markObj ? (float)$markObj->marks_obtained : '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Part C: Attainment Calculation -->
        <h3 class="text-md font-black uppercase mb-3 border-b-2 border-black pb-1">Part C: CO Attainment Calculation</h3>
        <div class="mb-10">
            <p class="text-xs text-gray-600 mb-3 italic">* Target Level: Students scoring >= 60% of Max Marks in the assignment.</p>
            <table class="w-full text-[10px] border border-black text-center border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-black p-2">Course Outcome</th>
                        <th class="border border-black p-2">Total Attempted</th>
                        <th class="border border-black p-2">Students >= Target</th>
                        <th class="border border-black p-2">Percentage</th>
                        <th class="border border-black p-2 font-black">Attainment Level</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attainment as $coTag => $data)
                        <tr>
                            <td class="border border-black p-2 font-bold">{{ $coTag }}</td>
                            <td class="border border-black p-2">{{ $data->attempted }}</td>
                            <td class="border border-black p-2">{{ $data->passed }}</td>
                            <td class="border border-black p-2 font-mono">{{ $data->percentage }}%</td>
                            <td class="border border-black p-2 font-black text-xs bg-gray-50">{{ $data->level }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-20 flex justify-between text-[10px] font-bold text-gray-700">
        <div class="text-center">
            <div class="border-t border-black w-32 mx-auto pt-1 mt-10">Prepared By</div>
            <p class="font-normal mt-1">{{ session('userName') ?? 'Faculty' }}</p>
        </div>
        <div class="text-center">
            <div class="border-t border-black w-32 mx-auto pt-1 mt-10">Approved By</div>
            <p class="font-normal mt-1">HOD / Principal</p>
        </div>
    </div>
</div>
