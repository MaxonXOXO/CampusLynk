    <div id="qp-preview-modal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-start justify-center p-4 overflow-auto">
        <div class="w-full max-w-[98%] bg-white rounded-2xl shadow-2xl border border-slate-200 flex flex-col" style="max-height:95vh">

            <!-- Modal Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between px-6 py-3.5 border-b border-slate-200 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-t-2xl gap-3">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2" id="qp-modal-title">Series QP Preview</h2>
                    <p class="text-slate-500 text-xs mt-0.5">Edit questions, marking schemes, and model answers side-by-side — then Save to Question Bank</p>
                </div>
                <div class="flex items-center gap-2 self-end sm:self-auto flex-wrap">
                    <button type="button" onclick="printFromQpModal('qp')" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-50 hover:bg-slate-100 text-indigo-600 border border-indigo-200 flex items-center gap-1 cursor-pointer transition-all" title="Print Formatted Question Paper">
                        <span>🖨️ Print QP</span>
                    </button>
                    <button type="button" onclick="printFromQpModal('scheme')" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-50 hover:bg-slate-100 text-emerald-600 border border-emerald-200 flex items-center gap-1 cursor-pointer transition-all" title="Print Evaluation Scheme">
                        <span>📋 Scheme</span>
                    </button>
                    <button type="button" onclick="printFromQpModal('key')" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-50 hover:bg-slate-100 text-amber-600 border border-amber-200 flex items-center gap-1 cursor-pointer transition-all" title="Print Model Answer Key">
                        <span>🔑 Key</span>
                    </button>
                    <button onclick="closeQpModal()" class="text-slate-400 hover:text-slate-600 text-2xl font-bold leading-none ml-2 cursor-pointer">&times;</button>
                </div>
            </div>

            <!-- Tab Switcher Bar -->
            <div class="flex border-b border-slate-200 bg-white/90 px-6 py-2 gap-2 flex-shrink-0">
                <button type="button" onclick="switchQpEditorTab('qp')" id="qp-edit-btn-qp" class="px-4 py-2 text-xs font-bold rounded-lg bg-indigo-600 text-white transition-all">📝 1. Edit Questions (QP)</button>
                <button type="button" onclick="switchQpEditorTab('scheme')" id="qp-edit-btn-scheme" class="px-4 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-750 text-slate-700 transition-all">📋 2. Edit Evaluation Scheme</button>
                <button type="button" onclick="switchQpEditorTab('key')" id="qp-edit-btn-key" class="px-4 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-750 text-slate-700 transition-all">🔑 3. Edit Model Answer Key</button>
            </div>

            <!-- Editor Body -->
            <div id="qp-editor-body" class="flex-1 overflow-y-auto p-6 space-y-2">
                <div class="text-slate-500 text-sm text-center py-12">Loading…</div>
            </div>

            <!-- Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-4 border-t border-slate-200 bg-white rounded-b-2xl gap-3">
                <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-start">
                    <button onclick="closeQpModal()" class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs cursor-pointer">Cancel</button>
                    <button type="button" onclick="resetCurrentModalDraft()" class="px-3 py-2 rounded-lg bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 font-semibold text-xs cursor-pointer transition-all flex items-center gap-1.5" title="Reset current draft back to blank template">
                        <span>🔄 Reset to Template</span>
                    </button>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <span class="text-slate-500 text-xs hidden md:inline">QP, Scheme &amp; Answer Key are saved together</span>
                    <button id="qp-save-btn" onclick="saveQpFromModal()" class="px-5 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm shadow-lg transition-all cursor-pointer">
                        💾 Save &amp; Add to Question Bank
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================
         Enter Theory ESE Grades Modal
    ================================================================= -->
