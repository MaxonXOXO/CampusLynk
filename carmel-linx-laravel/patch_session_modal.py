import os

with open("resources/views/tutor_student_diary_full.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

modal_html = """
    <!-- Mentor Meeting Modal -->
    <div id="addSessionModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[70] hidden items-center justify-center p-4">
      <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md overflow-hidden shadow-2xl">
        <div class="p-6 border-b border-slate-800 flex justify-between items-center">
          <h3 class="font-black text-white text-lg" id="sessionModalTitle">Record Mentoring Session</h3>
          <button onclick="closeSessionModal()" class="text-slate-400 hover:text-white"><span class="material-symbols-rounded">close</span></button>
        </div>
        <form id="sessionForm" onsubmit="saveMentoringSession(event)">
          <input type="hidden" id="sessionId">
          <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Semester</label>
                <input type="number" id="sessionSem" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
              </div>
              <div>
                <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Date</label>
                <input type="date" id="sessionDate" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
              </div>
            </div>
            <div>
              <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Discussion Points</label>
              <textarea id="sessionDiscussion" required rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 resize-none text-sm"></textarea>
            </div>
            <div>
              <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Action Items (Optional)</label>
              <textarea id="sessionAction" rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 resize-none text-sm"></textarea>
            </div>
          </div>
          <div class="p-6 border-t border-slate-800 flex justify-end gap-3 bg-slate-900/50">
            <button type="button" onclick="closeSessionModal()" class="px-4 py-2 text-slate-400 font-bold hover:text-white transition-colors text-[10px] text-xs">Cancel</button>
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg transition-colors text-[10px] text-xs">Save Session</button>
          </div>
        </form>
      </div>
    </div>
"""

# Inject before @include('student_mentoring_scripts')
if "<!-- Mentor Meeting Modal -->" not in content:
    content = content.replace("@include('student_mentoring_scripts')", modal_html + "\n  @include('student_mentoring_scripts')")
    with open("resources/views/tutor_student_diary_full.blade.php", "w", encoding="utf-8") as f:
        f.write(content)
    print("Added Mentor Meeting modal")
else:
    print("Mentor Meeting modal already exists")
