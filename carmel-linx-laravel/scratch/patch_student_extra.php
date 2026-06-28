<?php

$content = file_get_contents("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\student_mentoring_panel.blade.php");

$oldExtra = '      <!-- Extracurricular Tab -->
      <div id="smdExtra" class="smd-content-pane hidden space-y-4">
        <h4 class="text-sm font-bold text-white border-b border-slate-800/60 pb-2 mb-4">Extracurricular Activities</h4>
        <div class="overflow-x-auto rounded-xl border border-slate-800/60">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-slate-900/40 text-slate-400 border-b border-slate-800/60">
                <th class="p-3">Semester</th>
                <th class="p-3">Activity</th>
                <th class="p-3">Achievement</th>
                <th class="p-3">Verification Status</th>
                <th class="p-3"></th>
              </tr>
            </thead>
            <tbody id="smdExtraList">
              <!-- JS rendered -->
            </tbody>
          </table>
        </div>
        <button onclick="addExtraRow()" class="mt-2 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded text-[10px] font-bold cursor-pointer">+ Add Activity</button>
      </div>';

$newExtra = '      <!-- Extracurricular Tab -->
      <div id="smdExtra" class="smd-content-pane hidden space-y-4">
        <div class="flex justify-between items-end border-b border-slate-800 pb-3">
            <h4 class="text-sm font-bold text-white">Extracurricular Achievements</h4>
            <button onclick="openStudentActivityModal()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1"><span class="material-symbols-rounded text-sm">add</span> Add Activity</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="md:col-span-2 space-y-3">
              <h3 class="text-xs font-black text-slate-200">Activity Points Tracker</h3>
              <div class="relative w-full h-2.5 bg-slate-900 rounded-full overflow-hidden border border-slate-800/60 shadow-inner">
                <div id="studentActivityProgressBar" class="absolute top-0 left-0 h-full bg-gradient-to-r from-amber-500 to-orange-400 transition-all duration-1000 ease-out" style="width: 0%"></div>
              </div>
              <div class="flex justify-between text-[10px] font-bold text-slate-500">
                <span>0</span>
                <span>Goal: 100</span>
              </div>
            </div>
            
            <div class="bg-slate-950/40 rounded-xl p-3 border border-slate-800/60 flex flex-col justify-between">
              <div class="text-right">
                <span class="block text-[8px] text-slate-400 font-bold uppercase tracking-wider">Verified Total</span>
                <span class="text-2xl font-black text-amber-400" id="studentTotalActivityPoints">0</span>
              </div>
              <div class="mt-2 border-t border-slate-800/40 pt-2" id="studentActivitySplitList">
                <div class="text-[9px] text-slate-500 py-1">Loading...</div>
              </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-800/60">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-slate-900/40 text-slate-400 border-b border-slate-800/60">
                <th class="p-3">Sem</th>
                <th class="p-3 w-1/3">Activity Name</th>
                <th class="p-3">Level / Segment</th>
                <th class="p-3">Pts Claimed</th>
                <th class="p-3">Status</th>
                <th class="p-3 text-right">Action</th>
              </tr>
            </thead>
            <tbody id="smdExtraList">
              <!-- JS rendered -->
            </tbody>
          </table>
        </div>
      </div>';

$content = str_replace($oldExtra, $newExtra, $content);

$modalHtml = '

  <!-- STUDENT ACTIVITY MODAL -->
  <div id="addStudentActivityModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[70] hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-black text-white" id="studentActivityModalTitle">Add Activity</h3>
        <button onclick="closeStudentActivityModal()" class="text-slate-400 hover:text-white"><span class="material-symbols-rounded">close</span></button>
      </div>
      <form id="studentActivityForm" onsubmit="saveStudentActivity(event)">
        <input type="hidden" id="studentActivityId">
        <div class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-400 mb-1">Segment</label>
            <select id="studentActivitySegment" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
              <option value="NCC">NCC</option>
              <option value="NSS">NSS</option>
              <option value="Sports & Games">Sports & Games</option>
              <option value="Cultural Activities">Cultural Activities</option>
              <option value="Professional Self Initiatives">Prof. Self Initiatives</option>
              <option value="Entrepreneurship and Innovation">Entrepreneurship & Innovation</option>
              <option value="Leadership & Management">Leadership & Management</option>
              <option value="Disaster Management">Disaster Management</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-400 mb-1">Activity Name</label>
            <input type="text" id="studentActivityName" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-400 mb-1">Level (e.g. State, College)</label>
            <input type="text" id="studentActivityLevel" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-400 mb-1">Points Claimed</label>
            <input type="number" id="studentActivityPtsClaimed" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
          </div>
          <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-bold text-sm">Submit Activity for Verification</button>
        </div>
      </form>
    </div>
  </div>
';

// append modalHtml to the end of the file
$content .= $modalHtml;

file_put_contents("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\student_mentoring_panel.blade.php", $content);
echo "Updated student_mentoring_panel.blade.php\n";
