<?php

function patchDashboardLink($filePath) {
    if (!file_exists($filePath)) return;
    $content = file_get_contents($filePath);

    // Add Sidebar Link
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

      <a href=\"/course-files\" class=\"w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-amber-400 hover:bg-amber-900/30 cursor-pointer no-underline block\">
        <span class=\"material-symbols-rounded text-lg\">folder_special</span> Course Files
      </a>";
      
    if (strpos($content, 'href="/course-files"') === false) {
        $content = str_replace($oldLink, $newLink, $content);
        file_put_contents($filePath, $content);
        echo "Patched sidebar in " . basename($filePath) . "\n";
    }
}

patchDashboardLink("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\lecturer_dashboard.blade.php");

// Demonstrator is slightly different because it doesn't have mentoring
function patchDemLink($filePath) {
    if (!file_exists($filePath)) return;
    $content = file_get_contents($filePath);

    // Find the security nav button to insert before it
    $oldLink = "      <button id=\"navSecurity\" onclick=\"switchPanel('security')\" class=\"w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer\">";
    
    $newLink = "      <a href=\"/course-files\" class=\"w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-amber-400 hover:bg-amber-900/30 cursor-pointer no-underline block\">
        <span class=\"material-symbols-rounded text-lg\">folder_special</span> Course Files
      </a>
      
      <button id=\"navSecurity\" onclick=\"switchPanel('security')\" class=\"w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer\">";

    if (strpos($content, 'href="/course-files"') === false) {
        $content = str_replace($oldLink, $newLink, $content);
        file_put_contents($filePath, $content);
        echo "Patched sidebar in " . basename($filePath) . "\n";
    }
}

patchDemLink("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\demonstrator_dashboard.blade.php");

