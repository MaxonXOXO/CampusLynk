<div id="tab-rubrics" class="tab-pane hidden space-y-6">
    <!-- Regulation Clause Header -->
    <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-700/70 flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-500/40 text-blue-400 flex items-center justify-center shrink-0">
            <x-ui.icon name="file-text" class="w-5 h-5" />
        </div>
        <div>
            <h4 class="text-sm font-bold text-white">State Board of Technical Education (SBTE) Kerala — Revision 2021</h4>
            <p class="text-xs text-slate-400 mt-0.5">Regulation Clause 11.2.6: Rules of Assessment for Seminar (Course Code: 6008 / 5008)</p>
        </div>
    </div>

    <!-- Statutory Rubrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Rubric Breakdown -->
        <x-ui.card>
            <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-3 flex items-center gap-2">
                <x-ui.icon name="award" class="w-4 h-4 text-blue-400" />
                <span>Statutory 6-Rubric Distribution (Total 75 Marks)</span>
            </h5>
            <div class="space-y-2 text-xs">
                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-800/60 border border-slate-700/40">
                    <span class="text-slate-300">1. Relevance of the topic to engineering field</span>
                    <span class="font-mono font-bold text-white">7.5 Marks (10%)</span>
                </div>
                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-800/60 border border-slate-700/40">
                    <span class="text-slate-300">2. Literature survey and collection of technical data</span>
                    <span class="font-mono font-bold text-white">7.5 Marks (10%)</span>
                </div>
                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-800/60 border border-slate-700/40">
                    <span class="text-slate-300">3. Presentation: slides quality, delivery &amp; explanation</span>
                    <span class="font-mono font-bold text-emerald-400">37.5 Marks (50%)</span>
                </div>
                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-800/60 border border-slate-700/40">
                    <span class="text-slate-300">4. Interaction: viva voce &amp; technical defense</span>
                    <span class="font-mono font-bold text-white">7.5 Marks (10%)</span>
                </div>
                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-800/60 border border-slate-700/40">
                    <span class="text-slate-300">5. Seminar Report quality &amp; formatting</span>
                    <span class="font-mono font-bold text-white">7.5 Marks (10%)</span>
                </div>
                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-800/60 border border-slate-700/40">
                    <span class="text-slate-300">6. Attendance and punctuality in seminar sessions</span>
                    <span class="font-mono font-bold text-amber-400">7.5 Marks (10%)</span>
                </div>
            </div>
        </x-ui.card>

        <!-- Attendance Slabs & Committee Evaluation -->
        <div class="space-y-4">
            <x-ui.card>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-3 flex items-center gap-2">
                    <x-ui.icon name="check-circle" class="w-4 h-4 text-amber-400" />
                    <span>SBTE Attendance Slab Conversion (Max 7.5 Marks)</span>
                </h5>
                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 text-center text-xs">
                    <div class="p-2 rounded-lg bg-slate-800/60 border border-slate-700/40">
                        <div class="text-[10px] text-slate-400">&gt;= 90%</div>
                        <div class="font-mono font-bold text-emerald-400 mt-0.5">7.5M</div>
                    </div>
                    <div class="p-2 rounded-lg bg-slate-800/60 border border-slate-700/40">
                        <div class="text-[10px] text-slate-400">80 - 89%</div>
                        <div class="font-mono font-bold text-sky-400 mt-0.5">6.0M</div>
                    </div>
                    <div class="p-2 rounded-lg bg-slate-800/60 border border-slate-700/40">
                        <div class="text-[10px] text-slate-400">75 - 79%</div>
                        <div class="font-mono font-bold text-blue-400 mt-0.5">4.5M</div>
                    </div>
                    <div class="p-2 rounded-lg bg-slate-800/60 border border-slate-700/40">
                        <div class="text-[10px] text-slate-400">70 - 74%</div>
                        <div class="font-mono font-bold text-indigo-400 mt-0.5">3.0M</div>
                    </div>
                    <div class="p-2 rounded-lg bg-slate-800/60 border border-slate-700/40">
                        <div class="text-[10px] text-slate-400">65 - 69%</div>
                        <div class="font-mono font-bold text-amber-400 mt-0.5">1.5M</div>
                    </div>
                    <div class="p-2 rounded-lg bg-slate-800/60 border border-slate-700/40">
                        <div class="text-[10px] text-slate-400">&lt; 65%</div>
                        <div class="font-mono font-bold text-rose-400 mt-0.5">0.0M</div>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-2 flex items-center gap-2">
                    <x-ui.icon name="users" class="w-4 h-4 text-purple-400" />
                    <span>Faculty Committee Evaluation Rules</span>
                </h5>
                <ul class="text-xs text-slate-300 space-y-1.5 list-disc list-inside">
                    <li>The seminar shall be evaluated by a committee consisting of the faculty guide and at least one other senior faculty member.</li>
                    <li>Each committee member evaluates the presentation independently across all 6 rubrics.</li>
                    <li>The final Continuous Internal Assessment (CIA) score out of 75 marks is the exact mathematical average of all committee members' evaluations.</li>
                    <li>Minimum pass mark is 50% (37.5 marks out of 75).</li>
                </ul>
            </x-ui.card>
        </div>
    </div>
</div>
