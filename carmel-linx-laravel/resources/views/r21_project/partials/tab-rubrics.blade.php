<div id="tab-rubrics" class="tab-content hidden space-y-6">
    <!-- Regulation Header -->
    <div class="bg-white/5 border border-slate-700/60 rounded-xl p-4">
        <h2 class="text-sm font-bold text-white flex items-center gap-2">
            <x-ui.icon name="file-text" class="w-4 h-4 text-blue-400" />
            <span>State Board of Technical Education (SBTE) Kerala — Revision 2021 Regulation Guide</span>
        </h2>
        <p class="text-xs text-slate-400 mt-1">
            Major Project Evaluation Scheme: Total 125 Marks (CIA 75M + ESE 50M) — Clauses 11.2.5 and 11.3.4.
        </p>
    </div>

    <!-- Clauses 11.2.5 & 11.3.4 Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Clause 11.2.5: CIA -->
        <x-ui.card>
            <div class="space-y-3">
                <div class="flex items-center justify-between border-b border-slate-700/60 pb-2">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Clause 11.2.5 — CIA Split-up (75 Marks)</h3>
                    <x-ui.badge variant="success">Max 75M</x-ui.badge>
                </div>
                <div class="space-y-2 text-xs">
                    <div class="flex items-start justify-between gap-2 p-2 rounded-lg bg-slate-900/60 border border-slate-800">
                        <div>
                            <div class="font-bold text-white">1. Weekly Project Diary</div>
                            <div class="text-[11px] text-slate-400">Continuous progressive evaluation maintained weekly by the faculty guide.</div>
                        </div>
                        <span class="font-mono font-bold text-emerald-400 shrink-0">30 Marks</span>
                    </div>

                    <div class="flex items-start justify-between gap-2 p-2 rounded-lg bg-slate-900/60 border border-slate-800">
                        <div>
                            <div class="font-bold text-white">2. Department Level Review</div>
                            <div class="text-[11px] text-slate-400">Summative departmental review conducted before the project committee.</div>
                        </div>
                        <span class="font-mono font-bold text-emerald-400 shrink-0">30 Marks</span>
                    </div>

                    <div class="flex items-start justify-between gap-2 p-2 rounded-lg bg-slate-900/60 border border-slate-800">
                        <div>
                            <div class="font-bold text-white">3. Attendance Marks</div>
                            <div class="text-[11px] text-slate-400">Scaled statutory attendance (≥90%: 15M, ≥80%: 12M, ≥75%: 9M, ≥70%: 6M, ≥65%: 3M).</div>
                        </div>
                        <span class="font-mono font-bold text-emerald-400 shrink-0">15 Marks</span>
                    </div>
                </div>
                <div class="text-[11px] text-slate-400 italic pt-1">
                    * Minimum 30.0 Marks (40%) required in CIA for ESE eligibility.
                </div>
            </div>
        </x-ui.card>

        <!-- Clause 11.3.4: ESE -->
        <x-ui.card>
            <div class="space-y-3">
                <div class="flex items-center justify-between border-b border-slate-700/60 pb-2">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Clause 11.3.4 — ESE 8-Rubrics (50 Marks)</h3>
                    <x-ui.badge variant="info">Max 50M</x-ui.badge>
                </div>
                <div class="space-y-1.5 text-xs">
                    <div class="flex items-center justify-between p-1.5 rounded bg-slate-900/60 border border-slate-800">
                        <span class="text-slate-300">1. Prototype / System Quality</span>
                        <span class="font-mono font-bold text-sky-400">10.0 M</span>
                    </div>
                    <div class="flex items-center justify-between p-1.5 rounded bg-slate-900/60 border border-slate-800">
                        <span class="text-slate-300">2. Utilization of Modern Tools</span>
                        <span class="font-mono font-bold text-sky-400">5.0 M</span>
                    </div>
                    <div class="flex items-center justify-between p-1.5 rounded bg-slate-900/60 border border-slate-800">
                        <span class="text-slate-300">3. Technical Presentation</span>
                        <span class="font-mono font-bold text-sky-400">7.5 M</span>
                    </div>
                    <div class="flex items-center justify-between p-1.5 rounded bg-slate-900/60 border border-slate-800">
                        <span class="text-slate-300">4. Innovativeness / Novelty</span>
                        <span class="font-mono font-bold text-sky-400">2.5 M</span>
                    </div>
                    <div class="flex items-center justify-between p-1.5 rounded bg-slate-900/60 border border-slate-800">
                        <span class="text-slate-300">5. Viva-Voce / Technical Defence</span>
                        <span class="font-mono font-bold text-sky-400">7.5 M</span>
                    </div>
                    <div class="flex items-center justify-between p-1.5 rounded bg-slate-900/60 border border-slate-800">
                        <span class="text-slate-300">6. Individual Contribution</span>
                        <span class="font-mono font-bold text-sky-400">7.5 M</span>
                    </div>
                    <div class="flex items-center justify-between p-1.5 rounded bg-slate-900/60 border border-slate-800">
                        <span class="text-slate-300">7. Teamwork &amp; Group Activity</span>
                        <span class="font-mono font-bold text-sky-400">5.0 M</span>
                    </div>
                    <div class="flex items-center justify-between p-1.5 rounded bg-slate-900/60 border border-slate-800">
                        <span class="text-slate-300">8. Project Report &amp; Documentation</span>
                        <span class="font-mono font-bold text-sky-400">5.0 M</span>
                    </div>
                </div>
                <div class="text-[11px] text-slate-400 italic pt-1">
                    * Minimum 20.0 Marks (40%) in ESE and 50.0 Marks (40%) in Grand Total (125M) required to pass.
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- SBTE Grading Scale Reference -->
    <div>
        <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-3">SBTE Kerala 9-Point Grading Scale</h3>
        <x-ui.table :headers="['Grade', 'Grade Point', 'Percentage Range', 'Performance Description']">
            <tr class="hover:bg-slate-800/40 transition-colors">
                <td class="py-2 px-4 text-xs font-bold text-emerald-400">S</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">10.0</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">≥ 90%</td>
                <td class="py-2 px-4 text-xs text-slate-300">Outstanding</td>
            </tr>
            <tr class="hover:bg-slate-800/40 transition-colors">
                <td class="py-2 px-4 text-xs font-bold text-sky-400">A+</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">9.0</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">85% – 89%</td>
                <td class="py-2 px-4 text-xs text-slate-300">Excellent</td>
            </tr>
            <tr class="hover:bg-slate-800/40 transition-colors">
                <td class="py-2 px-4 text-xs font-bold text-sky-400">A</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">8.5</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">80% – 84%</td>
                <td class="py-2 px-4 text-xs text-slate-300">Very Good</td>
            </tr>
            <tr class="hover:bg-slate-800/40 transition-colors">
                <td class="py-2 px-4 text-xs font-bold text-blue-400">B+</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">8.0</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">75% – 79%</td>
                <td class="py-2 px-4 text-xs text-slate-300">Good</td>
            </tr>
            <tr class="hover:bg-slate-800/40 transition-colors">
                <td class="py-2 px-4 text-xs font-bold text-blue-400">B</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">7.5</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">70% – 74%</td>
                <td class="py-2 px-4 text-xs text-slate-300">Above Average</td>
            </tr>
            <tr class="hover:bg-slate-800/40 transition-colors">
                <td class="py-2 px-4 text-xs font-bold text-amber-400">C+</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">7.0</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">65% – 69%</td>
                <td class="py-2 px-4 text-xs text-slate-300">Average</td>
            </tr>
            <tr class="hover:bg-slate-800/40 transition-colors">
                <td class="py-2 px-4 text-xs font-bold text-amber-400">C</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">6.5</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">60% – 64%</td>
                <td class="py-2 px-4 text-xs text-slate-300">Satisfactory</td>
            </tr>
            <tr class="hover:bg-slate-800/40 transition-colors">
                <td class="py-2 px-4 text-xs font-bold text-purple-400">D</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">6.0</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">50% – 59%</td>
                <td class="py-2 px-4 text-xs text-slate-300">Pass (Minimum)</td>
            </tr>
            <tr class="hover:bg-slate-800/40 transition-colors">
                <td class="py-2 px-4 text-xs font-bold text-rose-500">F</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">0.0</td>
                <td class="py-2 px-4 text-xs font-mono text-slate-200">&lt; 50%</td>
                <td class="py-2 px-4 text-xs text-rose-400">Fail</td>
            </tr>
        </x-ui.table>
    </div>
</div>
