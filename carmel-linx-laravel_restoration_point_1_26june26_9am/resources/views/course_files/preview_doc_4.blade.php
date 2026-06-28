<div class="w-full h-full min-h-[700px] flex flex-col">
    @if($pdfUrl)
        <div class="mb-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-800">Course Syllabus</h3>
            <a href="{{ $pdfUrl }}" target="_blank" class="px-4 py-2 bg-indigo-50 text-indigo-600 font-bold text-sm rounded-lg hover:bg-indigo-100 transition-colors flex items-center gap-2">
                <span class="material-symbols-rounded text-[18px]">open_in_new</span> Open Original PDF
            </a>
        </div>
        <div class="bg-slate-100 rounded-xl border-2 border-dashed border-slate-300 p-2 relative h-[700px]">
            <iframe src="{{ $pdfUrl }}#toolbar=0" class="w-full h-full rounded-lg" frameborder="0"></iframe>
        </div>
    @else
        <div class="flex-1 flex flex-col items-center justify-center text-center p-10 bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl">
            <div class="w-20 h-20 bg-red-50 text-red-400 rounded-full flex items-center justify-center mb-4">
                <span class="material-symbols-rounded text-4xl">warning</span>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2">No Syllabus PDF Found</h3>
            <p class="text-slate-500 max-w-md">The lecturer has not uploaded the Course Syllabus PDF during the Lesson Plan preparation phase for this batch.</p>
        </div>
    @endif
</div>
