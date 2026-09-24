<!-- Practical Lab Batch Setup Modal -->
<div id="labBatchSetupModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-[100] hidden items-center justify-center p-2 sm:p-3 lg:p-4 transition-all duration-200">
    <div id="labBatchSetupModalDialog" class="bg-slate-900 border border-slate-700/80 rounded-2xl w-full max-w-[96vw] xl:max-w-[1500px] h-[95vh] max-h-[95vh] flex flex-col shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150 transition-all">
        
        <!-- Header -->
        <div class="px-5 py-3.5 bg-slate-950/95 border-b border-slate-800 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-500/20 border border-blue-500/40 flex items-center justify-center text-blue-400 shrink-0 shadow-sm">
                    <span class="material-symbols-rounded text-xl">group_work</span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm sm:text-base font-bold text-white leading-tight">Practical Lab Batch Setup</h3>
                        <span class="px-2 py-0.5 rounded-md bg-blue-500/20 border border-blue-500/30 text-blue-300 text-[10px] font-bold uppercase tracking-wider hidden sm:inline">Workspace</span>
                    </div>
                    <p class="text-[11px] text-slate-400 leading-tight mt-0.5" id="batchSetupSubjectSubtitle">Configure Full vs Split Batch &amp; Cutoff Roll Numbers</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5">
                <button type="button" onclick="toggleLabBatchSetupFullscreen()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition flex items-center justify-center cursor-pointer" title="Toggle Fullscreen View">
                    <span class="material-symbols-rounded text-lg" id="batchSetupFullscreenIcon">fullscreen</span>
                </button>
                <button type="button" onclick="closeLabBatchSetupModal()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition flex items-center justify-center cursor-pointer" title="Close">
                    <span class="material-symbols-rounded text-lg">close</span>
                </button>
            </div>
        </div>

        <!-- Body: 2 Columns on Desktop (lg:) -->
        <div class="flex-1 overflow-hidden p-3 sm:p-4 lg:p-5 flex flex-col lg:flex-row gap-4 lg:gap-6 min-h-0 text-xs">

            <!-- LEFT COLUMN: Setup Configuration Controls -->
            <div class="lg:w-[420px] xl:w-[460px] shrink-0 flex flex-col overflow-y-auto custom-scrollbar space-y-4 pr-1">
                
                <!-- Notice Alert Box -->
                <div id="batchSetupNoticeBox" class="p-3.5 rounded-xl bg-blue-950/40 border border-blue-500/30 text-blue-200 flex items-start gap-2.5 shrink-0 shadow-sm">
                    <span class="material-symbols-rounded text-blue-400 text-lg shrink-0 mt-0.5">info</span>
                    <div class="text-[11px] leading-relaxed">
                        <span class="font-bold block text-blue-100 mb-0.5">Faculty Batch Division Authority</span>
                        Faculty have complete control to set this practical as <strong>Full Batch</strong> (whole class) or <strong>Split Batch</strong> (2 batches) based on lab space, workstation availability, and syllabus requirements.
                    </div>
                </div>

                <!-- Mode Selection: Split vs Full -->
                <div class="space-y-1.5 shrink-0">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">Select Lab Mode</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        
                        <!-- Split Batch Card -->
                        <label class="relative flex items-start gap-2.5 p-3 rounded-xl border border-slate-700/80 bg-slate-950/70 hover:border-blue-500/70 transition cursor-pointer group">
                            <input type="radio" name="labBatchModeRadio" value="split" checked onchange="onLabBatchModeChange()" class="mt-0.5 text-blue-600 focus:ring-blue-500 bg-slate-900 border-slate-700">
                            <div class="space-y-0.5">
                                <span class="block text-xs font-bold text-white group-hover:text-blue-300 transition">Split Batch</span>
                                <p class="text-[10px] text-slate-400 leading-snug">Divide into Batch 1 &amp; Batch 2 for practical slots.</p>
                            </div>
                        </label>

                        <!-- Full Batch Card -->
                        <label class="relative flex items-start gap-2.5 p-3 rounded-xl border border-slate-700/80 bg-slate-950/70 hover:border-blue-500/70 transition cursor-pointer group">
                            <input type="radio" name="labBatchModeRadio" value="full" onchange="onLabBatchModeChange()" class="mt-0.5 text-blue-600 focus:ring-blue-500 bg-slate-900 border-slate-700">
                            <div class="space-y-0.5">
                                <span class="block text-xs font-bold text-white group-hover:text-blue-300 transition">Full Batch</span>
                                <p class="text-[10px] text-slate-400 leading-snug">Whole class conducts practicals together.</p>
                            </div>
                        </label>

                    </div>
                </div>

                <!-- Split Configuration Controls (Hidden when Full Batch selected) -->
                <div id="splitBatchConfigArea" class="space-y-4 pt-3 border-t border-slate-800/80">
                    
                    <!-- Cutoff Input & Quick Presets -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-[11px] font-bold text-slate-300">Batch 1 Cutoff Roll Number</label>
                            <span class="text-[10.5px] text-slate-400 font-mono">(Roll 1 to X)</span>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                            <!-- Stepper Container -->
                            <div class="sm:col-span-6 flex items-center rounded-xl border border-slate-700 bg-slate-950 focus-within:border-blue-500 shadow-inner transition overflow-hidden">
                                <button type="button" onclick="stepCutoff(-1)" class="w-10 h-9 flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer select-none" title="Decrease Roll Cutoff">
                                    <span class="material-symbols-rounded text-lg">remove</span>
                                </button>
                                <input type="number" id="batchSetupCutoffInput" min="1" max="200" placeholder="25" oninput="onCutoffInputChange()" class="w-full bg-transparent text-center py-1.5 text-sm text-white font-mono font-bold outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" onclick="stepCutoff(1)" class="w-10 h-9 flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer select-none" title="Increase Roll Cutoff">
                                    <span class="material-symbols-rounded text-lg">add</span>
                                </button>
                            </div>

                            <!-- Quick Preset Buttons -->
                            <div class="sm:col-span-6 flex items-center gap-1.5">
                                <button type="button" onclick="applyEqualSplit()" class="flex-1 px-2 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 rounded-xl text-[10.5px] font-bold transition cursor-pointer flex items-center justify-center gap-1 shadow-sm">
                                    <span class="material-symbols-rounded text-xs">pie_chart</span> 50/50
                                </button>
                                <button type="button" id="btnPreset25" onclick="setPresetCutoff(25)" class="flex-1 px-2 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 rounded-xl text-[10.5px] font-bold transition cursor-pointer flex items-center justify-center shadow-sm">
                                    1-25 &amp; 26+
                                </button>
                                <button type="button" onclick="resetToInitialCutoff()" class="px-2 py-2 text-slate-400 hover:text-slate-200 text-[10.5px] font-semibold transition cursor-pointer">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Live Batch Summary Cards -->
                    <div class="grid grid-cols-2 gap-3 p-3 bg-slate-950/80 border border-slate-800 rounded-xl shadow-sm">
                        <div class="p-2.5 bg-blue-950/40 border border-blue-500/40 rounded-xl text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-400 block">Batch 1</span>
                            <div class="text-base font-bold text-white font-mono mt-0.5">
                                <span id="setupB1CountText">0</span> <span class="text-[11px] font-normal text-blue-300">Students</span>
                            </div>
                            <div class="text-[10.5px] text-slate-400 font-mono mt-0.5" id="setupB1RangeText">Roll 1 - 25</div>
                        </div>
                        <div class="p-2.5 bg-sky-950/40 border border-sky-500/40 rounded-xl text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-sky-400 block">Batch 2</span>
                            <div class="text-base font-bold text-white font-mono mt-0.5">
                                <span id="setupB2CountText">0</span> <span class="text-[11px] font-normal text-sky-300">Students</span>
                            </div>
                            <div class="text-[10.5px] text-slate-400 font-mono mt-0.5" id="setupB2RangeText">Roll 26 - 51</div>
                        </div>
                    </div>

                </div>

                <!-- Apply to all subjects checkbox -->
                <div class="pt-3 border-t border-slate-800/80 mt-auto">
                    <label class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-950/60 border border-slate-800/80 hover:border-slate-700 cursor-pointer select-none transition">
                        <input type="checkbox" id="batchSetupApplyAllCheckbox" checked class="mt-0.5 rounded text-blue-600 focus:ring-blue-500 bg-slate-900 border-slate-700 shrink-0">
                        <div class="space-y-0.5">
                            <span class="text-xs font-semibold text-slate-200 block">Apply to all practicals in this semester</span>
                            <p class="text-[10.5px] text-slate-400 leading-snug">Synchronizes Batch 1 &amp; Batch 2 across all practical subjects in this classroom so student groupings remain identical.</p>
                        </div>
                    </label>
                </div>

            </div>

            <!-- RIGHT COLUMN: High-Density Student Roster Workspace -->
            <div class="flex-1 flex flex-col min-h-0 bg-slate-950/70 border border-slate-800 rounded-xl overflow-hidden shadow-sm">
                
                <!-- Roster Toolbar -->
                <div class="p-3 bg-slate-900/90 border-b border-slate-800 flex flex-wrap items-center justify-between gap-2.5 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <span class="font-bold text-slate-200 text-xs flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-blue-400 text-base">badge</span>
                            Student Roster &amp; Batch Assignments
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 font-mono text-[10.5px] font-bold" id="rosterTotalCountBadge">0 Students</span>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- Filter Tabs -->
                        <div class="inline-flex rounded-lg p-0.5 bg-slate-950 border border-slate-800 text-[11px]">
                            <button type="button" onclick="filterBatchSetupRosterTab('all')" id="rosterTab_all" class="px-2.5 py-0.5 rounded-md font-bold transition bg-blue-600 text-white cursor-pointer">All</button>
                            <button type="button" onclick="filterBatchSetupRosterTab('1')" id="rosterTab_1" class="px-2.5 py-0.5 rounded-md font-semibold transition text-slate-400 hover:text-slate-200 cursor-pointer">Batch 1</button>
                            <button type="button" onclick="filterBatchSetupRosterTab('2')" id="rosterTab_2" class="px-2.5 py-0.5 rounded-md font-semibold transition text-slate-400 hover:text-slate-200 cursor-pointer">Batch 2</button>
                        </div>

                        <!-- Search Box -->
                        <div class="relative">
                            <input type="text" id="batchSetupStudentSearch" placeholder="Search roll, name..." oninput="filterBatchSetupStudentList()" class="bg-slate-950 border border-slate-700 rounded-lg pl-7 pr-2.5 py-1 text-xs text-white placeholder-slate-500 outline-none focus:border-blue-500 w-36 sm:w-48 transition">
                            <span class="material-symbols-rounded text-xs text-slate-500 absolute left-2 top-1.5 pointer-events-none">search</span>
                        </div>
                    </div>
                </div>

                <!-- High-Density Student Table: Fills full available height -->
                <div class="flex-1 overflow-y-auto custom-scrollbar p-0">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="sticky top-0 bg-slate-900/95 backdrop-blur-md border-b border-slate-800 text-slate-400 font-bold uppercase text-[10px] tracking-wider z-10 shadow-sm">
                            <tr>
                                <th class="py-2.5 px-3 w-16 text-center">Roll</th>
                                <th class="py-2.5 px-3 w-32">Register No</th>
                                <th class="py-2.5 px-3">Student Name</th>
                                <th class="py-2.5 px-3 text-center w-36">Current Batch</th>
                                <th class="py-2.5 px-3 text-center w-28">Quick Action</th>
                            </tr>
                        </thead>
                        <tbody id="batchSetupStudentTbody" class="divide-y divide-slate-800/40">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>

                <!-- Roster Bottom Status Bar -->
                <div class="px-3.5 py-2 bg-slate-900/80 border-t border-slate-800 flex items-center justify-between text-[11px] text-slate-400 shrink-0">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-rounded text-blue-400 text-xs">touch_app</span>
                        <span>Click <strong class="text-blue-400">B1</strong> or <strong class="text-sky-400">B2</strong> to manually toggle any individual student.</span>
                    </div>
                    <div id="rosterShowingCounter" class="font-mono text-slate-400">Showing 51 students</div>
                </div>

            </div>

        </div>

        <!-- Footer -->
        <div class="px-5 py-3 bg-slate-950/95 border-t border-slate-800 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2 text-xs text-slate-400 hidden sm:flex">
                <span class="material-symbols-rounded text-emerald-400 text-sm">verified</span>
                <span>Configuring this batch updates practical attendance and continuous evaluation tables instantly.</span>
            </div>
            <div class="flex items-center gap-2 ms-auto">
                <button type="button" onclick="closeLabBatchSetupModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-semibold transition cursor-pointer">
                    Cancel
                </button>
                <button type="button" id="btnSaveLabBatchSetup" onclick="saveLabBatchSetup()" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-lg shadow-blue-600/30 cursor-pointer">
                    <span class="material-symbols-rounded text-base">check</span>
                    <span>Save Batch Configuration</span>
                </button>
            </div>
        </div>

    </div>
</div>

@include('partials.lab_batch_setup_scripts')
