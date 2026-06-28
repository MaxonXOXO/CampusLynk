<?php

$filePath = "c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\mentoring_diary_modal.blade.php";
$content = file_get_contents($filePath);

// Patch the modal form HTML
$oldModalHTML = '      <form id="activityForm" onsubmit="saveActivity(event)">
        <input type="hidden" id="activity_id">
        <div class="space-y-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Segment</label>';
            
$newModalHTML = '      <form id="activityForm" onsubmit="saveActivity(event)">
        <input type="hidden" id="activity_id">
        <div class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Semester</label>
              <select id="activity_semester" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:border-indigo-500 outline-none">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
              </select>
            </div>
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Segment</label>';
              
$content = str_replace($oldModalHTML, $newModalHTML, $content);

// Patch the JS editActivity to load the semester
$oldEditJS = '    document.getElementById("activity_id").value = act.activity_id || "";
    document.getElementById("activity_segment").value = act.segment || "NCC";';

$newEditJS = '    document.getElementById("activity_id").value = act.activity_id || "";
    document.getElementById("activity_semester").value = act.semester || "1";
    document.getElementById("activity_segment").value = act.segment || "NCC";';

$content = str_replace($oldEditJS, $newEditJS, $content);

// Patch the JS payload in saveActivity
$oldPayloadJS = '    const payload = {
      reg_no: currentMentoringRegNo,
      activity_id: document.getElementById("activity_id").value,
      activity_segment: document.getElementById("activity_segment").value,';
        
$newPayloadJS = '    const payload = {
      reg_no: currentMentoringRegNo,
      activity_id: document.getElementById("activity_id").value,
      semester: document.getElementById("activity_semester").value,
      activity_segment: document.getElementById("activity_segment").value,';

$content = str_replace($oldPayloadJS, $newPayloadJS, $content);

file_put_contents($filePath, $content);
echo "Updated mentoring_diary_modal.blade.php form.\n";
