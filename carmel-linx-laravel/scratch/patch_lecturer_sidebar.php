<?php

$filePath = "c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\lecturer_dashboard.blade.php";
$content = file_get_contents($filePath);

$oldLink = "      @if(\$isTutor || \$isMentor)
      <a href=\"/dashboard/tutor\" onclick=\"sessionStorage.setItem('openMentoring', 'true')\" class=\"w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-emerald-400 hover:bg-emerald-900/30 cursor-pointer no-underline block\">
        <span class=\"material-symbols-rounded text-lg\">diversity_3</span> My Mentoring
      </a>
      @endif";

$newLink = "      @if(\$isTutor || \$isMentor)
      <a href=\"/dashboard/tutor\" onclick=\"sessionStorage.setItem('openMentoring', 'true')\" class=\"w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-emerald-400 hover:bg-emerald-900/30 cursor-pointer no-underline block\">
        <span class=\"material-symbols-rounded text-lg\">diversity_3</span> My Mentoring
      </a>
      @endif

      <button id=\"navCourseFiles\" onclick=\"switchPanel('courseFiles')\" class=\"w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-amber-400 hover:bg-amber-900/30\">
        <span class=\"material-symbols-rounded text-lg\">folder_special</span> Course Files
      </button>";

$content = str_replace($oldLink, $newLink, $content);

file_put_contents($filePath, $content);
echo "Added Course Files link to lecturer dashboard.\n";
