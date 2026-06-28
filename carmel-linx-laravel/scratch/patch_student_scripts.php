<?php

$filePath = "c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\student_mentoring_scripts.blade.php";
$content = file_get_contents($filePath);

// Patch the modal form HTML
$oldModalHTML = '        <div class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-400 mb-1">Segment</label>';
            
$newModalHTML = '        <div class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">Semester</label>
              <select id="studentActivitySemester" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">Segment</label>';
              
$content = str_replace($oldModalHTML, $newModalHTML, $content);

// Patch the JS editStudentActivity to load the semester
$oldEditJS = '    document.getElementById("studentActivityId").value = act.activity_id || "";
    document.getElementById("studentActivitySegment").value = act.segment || "NCC";';

$newEditJS = '    document.getElementById("studentActivityId").value = act.activity_id || "";
    document.getElementById("studentActivitySemester").value = act.semester || "1";
    document.getElementById("studentActivitySegment").value = act.segment || "NCC";';

$content = str_replace($oldEditJS, $newEditJS, $content);

// Patch the JS payload in saveStudentActivity
$oldPayloadJS = '    const payload = {
        activity_id: document.getElementById("studentActivityId").value,
        segment: document.getElementById("studentActivitySegment").value,';
        
$newPayloadJS = '    const payload = {
        activity_id: document.getElementById("studentActivityId").value,
        semester: document.getElementById("studentActivitySemester").value,
        segment: document.getElementById("studentActivitySegment").value,';

$content = str_replace($oldPayloadJS, $newPayloadJS, $content);

file_put_contents($filePath, $content);
echo "Updated student_mentoring_scripts.blade.php form.\n";
