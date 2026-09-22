    <div id="modal-midsem-survey-init-practicum" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
      <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-4xl p-6 space-y-4 shadow-2xl max-h-[88vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
          <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <span class="material-symbols-rounded text-indigo-600">rate_review</span>
            <span>Preview &amp; Edit Mid-Semester Survey Questions</span>
          </h3>
          <button type="button" onclick="closeMidsemInitModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all cursor-pointer">
            &times;
          </button>
        </div>
        
        <p class="text-xs text-slate-500 leading-relaxed">
          Review or edit the survey questions below before activating. Once published, active survey notifications will automatically appear on the student dashboard ("Works to do").
        </p>

        <form id="form-midsem-init-practicum" onsubmit="submitPracticumMidsemInit(event)" class="space-y-4">
          <div class="space-y-3.5">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q1. CO1 - Course Outcomes Communication</label>
              <input type="text" id="p-ms-q5" value="The teacher clearly communicates the Course Outcomes (COs) and learning goals at the start of new topics." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q2. CO1 - Syllabus Delivery Pace</label>
              <input type="text" id="p-ms-q6" value="The pace, speed, and coverage of the syllabus completed so far is appropriate." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q3. CO2 - Concept Clarity &amp; Application</label>
              <input type="text" id="p-ms-q7" value="The teacher explains complex concepts clearly and links classroom theory to real-world industrial or field applications." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q4. CO2 - Effectiveness of Teaching &amp; Lab Demonstrations</label>
              <input type="text" id="p-ms-q8" value="The use of teaching tools, animations, lab demonstrations, or ICT tools is effective." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q5. CO3 - Doubt Clearing &amp; Classroom Interaction</label>
              <input type="text" id="p-ms-q9" value="The teacher encourages student questions, manages classroom discussions well, and clears doubts patiently." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q6. CO3 - Test &amp; Practical Assignment Relevance</label>
              <input type="text" id="p-ms-q10" value="Internal assessment test questions and practical assignments match the topics taught in class." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q7. CO4 - Fairness in Evaluation</label>
              <input type="text" id="p-ms-q11" value="Evaluation of mid-semester tests or practical submissions is fair, timely, and transparent." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q8. CO4 - Guidance &amp; Support for Students</label>
              <input type="text" id="p-ms-q12" value="The teacher provides extra guidance, remedial tips, or support to students needing assistance." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-indigo-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
          </div>

          <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
            <button type="button" onclick="closeMidsemInitModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors cursor-pointer">Cancel</button>
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-xs transition-colors cursor-pointer">Activate &amp; Publish Survey</button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL: COURSE EXIT SURVEY INITIATION PREVIEW & EDIT -->
    <div id="modal-exit-survey-init-practicum" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
      <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-4xl p-6 space-y-4 shadow-2xl max-h-[88vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
          <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <span class="material-symbols-rounded text-teal-600">assignment_turned_in</span>
            <span>Preview &amp; Edit Course Exit Survey Questions</span>
          </h3>
          <button type="button" onclick="closeExitInitModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all cursor-pointer">
            &times;
          </button>
        </div>
        
        <p class="text-xs text-slate-500 leading-relaxed">
          Review or edit the Course Exit questions below before activating. Students will submit responses to calculate Indirect CO Attainment.
        </p>

        <form id="form-exit-init-practicum" onsubmit="submitPracticumExitInit(event)" class="space-y-4">
          <div class="space-y-3.5">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q1. CO1 - Theoretical Principles &amp; Fundamentals</label>
              <input type="text" id="p-ex-q1" value="How well did the course help you understand and remember core academic principles, models, and structural fundamentals?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q2. CO1 - Outcome &amp; Syllabus Alignment</label>
              <input type="text" id="p-ex-q2" value="How clearly were the course objectives, scope, and basic terms aligned with class lectures and lab demonstrations?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q3. CO2 - Analytical Ability &amp; Logic</label>
              <input type="text" id="p-ex-q3" value="How effectively did the course build your reasoning skills, mathematical derivations, or logical analysis capabilities?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q4. CO2 - Design &amp; Troubleshooting Skills</label>
              <input type="text" id="p-ex-q4" value="To what extent can you design models, troubleshoot bugs, or conduct lab experiments based on class lessons?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q5. CO3 - Modern Tools &amp; Practical Execution</label>
              <input type="text" id="p-ex-q5" value="How confident are you in using modern software, lab apparatus, or engineering software for tasks?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q6. CO3 - Problem Solving in Field &amp; Lab</label>
              <input type="text" id="p-ex-q6" value="How effectively can you apply core theoretical principles to solve practical or field problems?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q7. CO4 - Ethics, Teamwork &amp; Professional Conduct</label>
              <input type="text" id="p-ex-q7" value="Did the course foster professional ethics, group collaboration, and responsible work habits?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Q8. CO4 - Communication &amp; Report Writing</label>
              <input type="text" id="p-ex-q8" value="How well did the course improve your technical documentation, presentation skills, and report writing?" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-800 text-xs focus:bg-white focus:border-teal-500 outline-none transition-colors shadow-2xs font-normal">
            </div>
          </div>

          <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
            <button type="button" onclick="closeExitInitModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition-colors cursor-pointer">Cancel</button>
            <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-xs shadow-xs transition-colors cursor-pointer">Activate &amp; Publish Survey</button>
          </div>
        </form>
      </div>
    </div>
    <!-- Modern CampusLynk Syllabus Upload & Document Processing Modal -->
    <div id="syllabus-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-50 backdrop-blur-xs hidden p-4">
      <div class="bg-white border border-slate-200/90 rounded-2xl max-w-xl w-full p-6 shadow-2xl space-y-4">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <div class="flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
              <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h6"/><path d="M9 11h6"/></svg>
            </span>
            <div>
              <h3 class="text-base font-bold text-slate-900">Upload Practicum Syllabus PDF</h3>
              <p class="text-xs text-slate-500">Automatically extract Theory modules, COs &amp; Lab experiments</p>
            </div>
          </div>
          <button type="button" onclick="closeSyllabusModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        @if($practicumCourseFile && $practicumCourseFile->syllabus_pdf_path)
        <!-- Current Active Syllabus Banner -->
        <div class="p-3.5 bg-emerald-50/70 border border-emerald-200/80 rounded-xl flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <div>
              <h4 class="text-xs font-bold text-emerald-950">Active Syllabus Document Loaded</h4>
              <p class="text-xs text-emerald-700">Ready to replace or re-parse anytime</p>
            </div>
          </div>
          <a href="/storage/{{ $practicumCourseFile->syllabus_pdf_path }}" target="_blank" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-lg border border-emerald-200 flex items-center gap-1 shadow-2xs">
            <svg class="w-3.5 h-3.5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/></svg>
            <span>View PDF</span>
          </a>
        </div>
        @endif

        <!-- Drag & Drop Zone -->
        <div id="practicumSyllabusDropzone" ondragover="handlePracticumDragOver(event)" ondragleave="handlePracticumDragLeave(event)" ondrop="handlePracticumFileDrop(event)" onclick="document.getElementById('practicumSyllabusFileInput').click()" class="border-2 border-dashed border-slate-300 hover:border-blue-500 bg-slate-50/70 hover:bg-blue-50/40 rounded-2xl p-6 text-center space-y-2.5 transition cursor-pointer">
          <div class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-600 mx-auto flex items-center justify-center border border-blue-200">
