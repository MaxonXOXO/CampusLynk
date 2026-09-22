                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-indigo-50 text-indigo-700 border border-indigo-200/80">
                                    R2026 · PRACTICUM (JOINT THEORY + LAB)
                                </span>
                                <span class="px-2.5 py-0.5 rounded-md font-bold text-xs bg-purple-50 text-purple-700 border border-purple-200/80">
                                    Semester {{ $batchSubject->semester }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-md font-mono font-bold text-xs bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $batchSubject->subject_code }}
                                </span>
                            </div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                                {{ $batchSubject->subject_name }}
                            </h2>
                        </div>

                        <div class="flex items-center gap-2 flex-wrap">
                            @if($practicumCourseFile && $practicumCourseFile->syllabus_pdf_path)
                                <a href="/storage/{{ $practicumCourseFile->syllabus_pdf_path }}" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 rounded-xl text-xs font-bold transition-all border border-slate-200 flex items-center gap-1.5 shadow-2xs">
                                    <span class="material-symbols-rounded text-base text-rose-600">picture_as_pdf</span>
                                    <span>View Syllabus PDF</span>
                                </a>
                            @endif
                            <button onclick="openSyllabusModal()" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs cursor-pointer">
                                <span class="material-symbols-rounded text-base">upload_file</span>
                                <span>Upload Syllabus</span>
                            </button>
                        </div>
                    </div>

                    <!-- Dual 90-Hour Allocation Metrics -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="bg-blue-50/50 border border-blue-200/70 rounded-xl p-3">
                            <span class="text-xs font-semibold text-blue-700 block uppercase tracking-wider">Theory Component</span>
                            <span class="text-base font-bold text-blue-950 mt-0.5 block">45 Lecture Hours</span>
                        </div>
                        <div class="bg-emerald-50/50 border border-emerald-200/70 rounded-xl p-3">
                            <span class="text-xs font-semibold text-emerald-700 block uppercase tracking-wider">Lab Component</span>
                            <span class="text-base font-bold text-emerald-950 mt-0.5 block">45 Practical Hours</span>
                        </div>
                        <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3">
                            <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Total Workload</span>
                            <span class="text-base font-bold text-slate-900 mt-0.5 block">90 Total Hours</span>
                        </div>
                        <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3">
                            <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Evaluation</span>
                            <span class="text-base font-bold text-slate-900 mt-0.5 block">CIE 60M | ESE 40M</span>
                        </div>
                    </div>
                </div>
