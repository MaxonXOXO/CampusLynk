    <div id="qp-preview-modal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-start justify-center p-4 overflow-auto">
        <div class="w-full max-w-[98%] bg-white rounded-2xl shadow-2xl border border-slate-200 flex flex-col" style="max-height:95vh">

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-t-2xl">
                <div>
                    <h2 class="text-lg font-bold text-white" id="qp-modal-title">Series QP Preview</h2>
                    <p class="text-slate-400 text-xs mt-0.5">Edit questions, marking schemes, and model answers side-by-side — then Save to Question Bank</p>
                </div>
                <button onclick="closeQpModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none">&times;</button>
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
            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-200 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-b-2xl">
                <button onclick="closeQpModal()" class="px-5 py-2.5 rounded-lg bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-semibold text-sm shadow-xs">Cancel</button>
                <div class="flex items-center gap-3">
                    <span class="text-slate-500 text-xs">Questions, schemes, and model answers are saved together in one step</span>
                    <button id="qp-save-btn" onclick="saveQpFromModal()" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-lg transition-all">
                        💾 Save &amp; Add to Question Bank
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================
         Enter Theory ESE Grades Modal
    ================================================================= -->
