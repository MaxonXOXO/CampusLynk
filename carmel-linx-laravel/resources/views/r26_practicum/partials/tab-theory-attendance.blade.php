            <div id="theory-subcontent-attendance" class="space-y-5 hidden">
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 rounded-xl border border-slate-200 space-y-4">
                    <div>
                        <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                            <span>📅 Course Attendance Reports</span>
                        </h3>
                        <p class="text-slate-400 text-xs mt-1">Select and print the detailed session attendance register or the consolidated final attendance report.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Card 1: Detailed Register -->
                        <div class="p-5 rounded-2xl bg-slate-50 border border-cyan-500/20 hover:border-cyan-500/50 shadow-lg transition-all duration-300 group flex flex-col justify-between space-y-4">
                            <div class="flex justify-between items-start">
                                <div class="space-y-1">
                                    <h4 class="text-base font-bold text-slate-100 group-hover:text-cyan-600 transition-all">Detailed Attendance Register</h4>
                                    <p class="text-slate-400 text-xs leading-relaxed">View and print the complete, session-by-session student attendance grid with specific dates, hourly remarks, percentage logs, and marks calculation.</p>
                                </div>
                                <span class="material-symbols-rounded text-cyan-600 bg-cyan-500/10 p-3 rounded-xl text-2xl flex-shrink-0">view_list</span>
                            </div>
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-report" target="_blank" class="w-full text-center px-4 py-2.5 rounded-xl font-bold text-xs bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-cyan-300 hover:text-white border border-cyan-500/40 hover:border-cyan-400 transition-all shadow-md no-underline block">
                                Open Detailed Register
                            </a>
                        </div>

                        <!-- Card 2: Consolidated Report -->
                        <div class="p-5 rounded-2xl bg-slate-50 border border-emerald-500/20 hover:border-emerald-500/50 shadow-lg transition-all duration-300 group flex flex-col justify-between space-y-4">
                            <div class="flex justify-between items-start">
                                <div class="space-y-1">
                                    <h4 class="text-base font-bold text-slate-100 group-hover:text-emerald-600 transition-all">Consolidated Attendance Report</h4>
                                    <p class="text-slate-400 text-xs leading-relaxed">View and print the consolidated A4 report showing the total theory conducted/present, practical conducted/present, and the final average attendance percentage for CIA preparation.</p>
                                </div>
                                <span class="material-symbols-rounded text-emerald-600 bg-emerald-500/10 p-3 rounded-xl text-2xl flex-shrink-0">analytics</span>
                            </div>
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-consolidated" target="_blank" class="w-full text-center px-4 py-2.5 rounded-xl font-bold text-xs bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-emerald-300 hover:text-white border border-emerald-500/40 hover:border-emerald-400 transition-all shadow-md no-underline block">
                                Open Consolidated Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- MODE B: VIRTUAL LAB (PRACTICUM)                                           -->
