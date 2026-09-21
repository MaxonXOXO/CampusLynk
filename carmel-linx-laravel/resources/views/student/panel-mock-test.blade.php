        <!-- 8. PANEL: PRACTICE TEST & ASSESSMENT HUB -->
        <!-- ========================================================================= -->
        <div id="panelMock_test" class="hidden space-y-6">
          
          <!-- 1. Setup Section -->
          <section id="mockSetupSection" class="space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
              
              <div class="border-b border-slate-100 pb-4">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200/60 text-blue-700 text-xs font-semibold uppercase tracking-wider mb-2">
                  <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                  <span>Practice Session Setup</span>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Select Subject & Test Scope</h2>
                <p class="text-xs text-slate-500 mt-1">Take self-assessment quizzes generated from your syllabus. Daily quota: 1 attempt per subject.</p>
              </div>

              <!-- Setup Loader -->
              <div id="mockSetupLoader" class="flex flex-col items-center justify-center py-12 text-center text-slate-400">
                <div class="w-8 h-8 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin mb-3"></div>
                <p class="text-xs font-semibold text-slate-500">Loading semester subjects & syllabus modules...</p>
              </div>

              <!-- Setup Form -->
              <form id="mockSetupForm" class="space-y-6 hidden" onsubmit="event.preventDefault(); initiateMockTest();">
                
                <!-- Subject Selection Cards Grid -->
                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-3">Available Semester Subjects</label>
                  <div id="mockSubjectGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5"></div>
                  <input type="hidden" id="mockSelectedSubject" required>
                </div>

                <!-- Test Parameters Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-5">
                  <div>
                    <x-ui.select id="mockQuestionCount" name="mock_question_count" label="Question Count" :options="['10'=>'10 Questions (Quick Practice ~ 10 mins)', '15'=>'15 Questions (Standard Evaluation ~ 15 mins)', '20'=>'20 Questions (Full Series Prep ~ 20 mins)']" value="15" />
                  </div>
                  <div>
                    <x-ui.select id="mockModuleScope" name="mock_module_scope" label="Syllabus Scope" :options="['all'=>'All Modules (Comprehensive Syllabus)', 'CO1'=>'CO1 Module Focus', 'CO2'=>'CO2 Module Focus', 'CO3'=>'CO3 Module Focus', 'CO4'=>'CO4 Module Focus']" value="all" />
                  </div>
                </div>

                <div class="pt-3 flex justify-end">
                  <button type="submit" id="btnStartMockTest" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
                    <i data-lucide="play" class="w-4 h-4"></i>
                    <span>Launch Practice Test</span>
                  </button>
                </div>

              </form>

            </div>
          </section>

          <!-- 2. Active Test Examination Section -->
          <section id="mockExamSection" class="hidden space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
              
              <!-- Test Header with Live Timer -->
              <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                  <h3 id="mockActiveSubjectTitle" class="text-base font-bold text-slate-900">Subject Practice Test</h3>
                  <p id="mockActiveQuestionCounter" class="text-xs text-slate-500 mt-0.5">Question 1 of 15</p>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-200/80 rounded-xl text-blue-700 font-mono text-sm font-bold shadow-2xs">
                  <i data-lucide="timer" class="w-4 h-4 text-blue-600"></i>
                  <span id="mockTestTimer">15:00</span>
                </div>
              </div>

              <!-- Question Navigator Dots -->
              <div class="flex flex-wrap gap-2 p-3 bg-slate-50 rounded-xl border border-slate-200/60" id="mockQuestionNavigator"></div>

              <!-- Current Question Box -->
              <div id="mockCurrentQuestionBox" class="space-y-4 pt-2">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                  <span class="text-xs font-semibold text-blue-700 uppercase tracking-wider" id="mockQBadge">Question 1</span>
                  <p class="text-sm font-semibold text-slate-900 mt-1" id="mockQText">Question text loading...</p>
                </div>

                <!-- Options List -->
                <div class="space-y-2.5" id="mockOptionsContainer"></div>
              </div>

              <!-- Navigation Controls -->
              <div class="flex items-center justify-between border-t border-slate-100 pt-5">
                <button type="button" onclick="navigateMockPrevQuestion()" id="btnMockPrevQ" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                  Previous
                </button>
                <div class="flex gap-2">
                  <button type="button" onclick="navigateMockNextQuestion()" id="btnMockNextQ" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all">
                    Next Question
                  </button>
                  <button type="button" onclick="submitMockFullTest()" id="btnMockSubmitTest" class="hidden px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition-all shadow-sm">
                    Submit Test
                  </button>
