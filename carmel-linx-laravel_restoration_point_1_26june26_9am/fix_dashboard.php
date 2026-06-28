<?php
$file = 'resources/views/lecturer_dashboard.blade.php';
$content = file_get_contents($file);

// 1. Fix co_targets
$content = str_replace('data.data.co_targets);', 'data.data.cos);', $content);

// 2. Remove Key button
$content = preg_replace('/<button onclick="viewOnlineTestKey.*?>.*?<\/button>/is', '', $content);
$content = str_replace('onclick="deleteOnlineTest(\'\\', \'\\')" class="w-full py-1', 'onclick="deleteOnlineTest(\'\\', \'\\')" class="col-span-2 w-full py-1', $content);

// 3. Add Custom Name and ensure q_count is there
$findInput = '<label class="block text-[9px] text-slate-500 font-bold mb-1 uppercase">Start Time</label>';
$insertInput = <div class="col-span-2 mb-4">\n<label class="block text-[9px] text-slate-500 font-bold mb-1 uppercase">Number of Questions</label>\n<input type="number" id="online_test_q_count" value="10" min="1" max="50" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">\n</div>\n<div class="col-span-2 mb-4">\n<label class="block text-[9px] text-slate-500 font-bold mb-1 uppercase">Custom Test ID/Name (Optional)</label>\n<input type="text" id="online_test_name" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500" placeholder="e.g. Midterm Test 1">\n</div>\n . $findInput;
$content = str_replace($findInput, $insertInput, $content);

// 4. Update publishOnlineTest button to have ID
$content = str_replace('<button onclick="publishOnlineTest(\'\\')"', '<button id="btnPublishOnlineTest" onclick="publishOnlineTest(\'\\')"', $content);

// 5. Update publishOnlineTest javascript
$oldJS = 'function publishOnlineTest(subjectId) {';
$newJS = $oldJS . \nconst customName = document.getElementById('online_test_name') ? document.getElementById('online_test_name').value : '';\nconst btn = document.getElementById('btnPublishOnlineTest');;
$content = str_replace($oldJS, $newJS, $content);

$content = str_replace('const duration = document.getElementById(\'online_test_duration\').value;', 'const duration = document.getElementById(\'online_test_duration\').value;
const q_count = document.getElementById(\'online_test_q_count\').value;', $content);

$content = str_replace('body: JSON.stringify({ cos: selectedCos, attempts, duration, start, end })', 'body: JSON.stringify({ cos: selectedCos, attempts, duration, q_count, start, end, custom_name: customName })', $content);
$content = str_replace('body: JSON.stringify({ cos: selectedCos, attempts, duration, q_count, start, end })', 'body: JSON.stringify({ cos: selectedCos, attempts, duration, q_count, start, end, custom_name: customName })', $content);

$lockCode = if (btn) { btn.disabled = true; btn.innerHTML = 'Generating...'; btn.classList.add('opacity-50', 'cursor-not-allowed'); };
$content = str_replace('fetch(/api/classroom//publish-online-test, {', $lockCode . \n . 'fetch(/api/classroom//publish-online-test, {', $content);

$unlockCode = if (btn) { btn.disabled = false; btn.innerHTML = \'<span class=\"material-symbols-rounded text-sm\">rocket_launch</span> Generate & Publish to Students\'; btn.classList.remove('opacity-50', 'cursor-not-allowed'); };
$content = str_replace('if (data.status === \'SUCCESS\') {', $unlockCode . \n . 'if (data.status === \'SUCCESS\') {', $content);

$content = str_replace('if (document.getElementById(\'online_test_start\')._flatpickr)', if (document.getElementById('online_test_name')) document.getElementById('online_test_name').value = '';\n . 'if (document.getElementById(\'online_test_start\')._flatpickr)', $content);

file_put_contents($file, $content);
echo done;
?>
