<?php

$filePath = "c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\student_dashboard.blade.php";
$content = file_get_contents($filePath);

$oldForm = '<form id="activityClaimForm" onsubmit="submitActivityClaim(event)" class="bg-slate-950/40 border border-slate-800/40 p-4 rounded-xl grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="lg:col-span-1">
              <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Segment</label>';

$newForm = '<form id="activityClaimForm" onsubmit="submitActivityClaim(event)" class="bg-slate-950/40 border border-slate-800/40 p-4 rounded-xl grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
            <div class="lg:col-span-1">
              <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Semester</label>
              <select name="semester" required class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
                <option value="1">Sem 1</option>
                <option value="2">Sem 2</option>
                <option value="3">Sem 3</option>
                <option value="4">Sem 4</option>
                <option value="5">Sem 5</option>
                <option value="6">Sem 6</option>
              </select>
            </div>
            <div class="lg:col-span-1">
              <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Segment</label>';

$content = str_replace($oldForm, $newForm, $content);

// In the Document Evidence row, it was lg:col-span-5. Change to lg:col-span-6.
$oldDocRow = '<div class="lg:col-span-5">
              <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Document Evidence (Describe what you are submitting to Tutor)</label>';
$newDocRow = '<div class="lg:col-span-6">
              <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Document Evidence (Describe what you are submitting to Tutor)</label>';

$content = str_replace($oldDocRow, $newDocRow, $content);

file_put_contents($filePath, $content);
echo "Updated student_dashboard.blade.php form.\n";
