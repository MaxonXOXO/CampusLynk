                </div>
              </div>

            </div>
          </section>

          <!-- 3. Score Report Section -->
          <section id="mockResultSection" class="hidden space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6 text-center">
              
              <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 mx-auto">
                <i data-lucide="award" class="w-8 h-8"></i>
              </div>

              <div>
                <h2 class="text-2xl font-bold text-slate-900">Practice Session Completed!</h2>
                <p class="text-xs text-slate-500 mt-1" id="mockResultSubjectSubtitle">Assessment results and competency breakdown</p>
              </div>

              <!-- Score Pill -->
              <div class="max-w-xs mx-auto p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Final Test Score</span>
                <p class="text-3xl font-bold text-blue-700" id="mockFinalScoreText">0 / 15</p>
                <p class="text-xs font-semibold text-emerald-700" id="mockFinalPercentageText">0% Proficiency</p>
              </div>

              <!-- Detailed Answer Review List -->
              <div class="text-left space-y-3 pt-4 border-t border-slate-100" id="mockDetailedReviewList"></div>

              <div class="pt-4 flex justify-center">
                <button type="button" onclick="resetMockPracticeTest()" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition-all shadow-sm">
                  Back to Practice Hub
                </button>
              </div>

            </div>
          </section>

        </div>

      </main>
    </div>
  </div>

  <!-- Study Materials Vault Modal -->
  <div id="vlmVaultModal" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl max-w-2xl w-full p-6 shadow-2xl space-y-4">
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <div class="flex items-center gap-2">
          <i data-lucide="folder-archive" class="w-5 h-5 text-blue-600"></i>
          <h3 class="text-base font-bold text-slate-900">Study Materials & Learning Vault</h3>
        </div>
        <button onclick="closeVlmVaultModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><i data-lucide="x" class="w-5 h-5"></i></button>
      </div>
      <div id="vlmVaultContent" class="space-y-3 max-h-96 overflow-y-auto">
        <p class="text-xs text-slate-500">Access course lecture notes, question banks, and pre-class videos uploaded by your faculty.</p>
      </div>
    </div>
  </div>

  <!-- Interactive Dashboard Controller Scripts -->
