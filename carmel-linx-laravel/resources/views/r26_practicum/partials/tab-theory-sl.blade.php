            <div id="theory-subcontent-sl" class="space-y-5 hidden">
                <!-- 1. Header & Quick Actions Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 font-mono">CONTINUOUS ASSESSMENT (CA1)</span>
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100 font-mono">5 CIA Marks</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mt-1">Self-Learning Evaluation & Customization</h3>
                            <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">
                                Mandatory Core Activities: <span class="font-bold text-amber-700">Assignment</span> & <span class="font-bold text-emerald-700">MCQ</span> (Evaluated out of 15 Marks). Optional custom catalog per CO: Case Study, Quiz, Microproject, Mini Project, Report, Exercises, Presentation.
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <button type="button" onclick="openSlConfigModal()" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition-all flex items-center gap-1.5 shadow-2xs cursor-pointer">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Customize Activities</span>
                            </button>

                            <button type="button" onclick="openSlMarksModal()" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs flex items-center gap-1.5 transition-all cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Enter CA Marks</span>
                            </button>

                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-self-learning-splitup" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition-all flex items-center gap-1.5 shadow-2xs no-underline cursor-pointer">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                <span>Splitup Report</span>
                            </a>

                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-self-learning-summary" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition-all flex items-center gap-1.5 shadow-2xs no-underline cursor-pointer">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Summary Report</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. Student Evaluation Table Workspace Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                    <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Enrolled Students Continuous Assessment Register</span>
                        </div>
                        <span class="text-xs font-bold text-slate-500 font-mono">{{ count($studentResults) }} Students</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[760px]">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-700 font-bold text-xs uppercase tracking-wider">
                                    <th class="p-3.5 pl-4 text-center w-16">Roll</th>
                                    <th class="p-3.5 w-40">SBTE Reg No</th>
                                    <th class="p-3.5">Student Name</th>
                                    <th class="p-3.5 w-48">Core Activities</th>
                                    <th class="p-3.5 text-center w-36">Raw Score (/15M)</th>
                                    <th class="p-3.5 text-center w-36">Converted CIA (5M)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm font-normal text-slate-700">
                                @forelse($studentResults as $res)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-3 pl-4 text-center font-mono font-bold text-slate-900">{{ $res['roll_no'] ?: '—' }}</td>
                                    <td class="p-3 font-mono font-bold text-indigo-700 text-xs">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-3 text-slate-900 font-medium">{{ $res['name'] }}</td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-1.5">
                                            <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200/80 font-bold text-xs font-mono">Assignment</span>
                                            <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200/80 font-bold text-xs font-mono">MCQ</span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-center font-mono font-bold text-slate-800">{{ number_format(($res['sl_marks'] / 5.0) * 15.0, 2) }} / 15.00</td>
                                    <td class="p-3 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono border shadow-2xs bg-emerald-50 text-emerald-700 border-emerald-200/80">
                                            {{ number_format($res['sl_marks'], 2) }} / 5.00
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-400 font-normal">No student assessment records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

