<?php

$files = [
    "c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\lecturer_dashboard.blade.php",
    "c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\demonstrator_dashboard.blade.php"
];

foreach ($files as $filePath) {
    if (!file_exists($filePath)) continue;
    
    $content = file_get_contents($filePath);

    // 1. Add courseFiles to the panels array
    $oldPanels = "const panels = ['dashboard', 'security', 'classroom'];";
    $newPanels = "const panels = ['dashboard', 'security', 'classroom', 'courseFiles'];";
    $content = str_replace($oldPanels, $newPanels, $content);
    
    // In demonstrator dashboard, panels might be different:
    $oldPanelsDem = "const panels = ['dashboard', 'security'];";
    $newPanelsDem = "const panels = ['dashboard', 'security', 'courseFiles'];";
    $content = str_replace($oldPanelsDem, $newPanelsDem, $content);

    // 2. Add courseFiles to titles
    $oldTitles = "      const titles = {
        'dashboard': 'My Batches',
        'security': 'My Profile Security Log',
        'classroom': 'Virtual Classroom'
      };";
    $newTitles = "      const titles = {
        'dashboard': 'My Batches',
        'security': 'My Profile Security Log',
        'classroom': 'Virtual Classroom',
        'courseFiles': 'Course File Builder'
      };";
    $content = str_replace($oldTitles, $newTitles, $content);
    
    // Demonstrator titles
    $oldTitlesDem = "      const titles = {
        'dashboard': 'Lab Workspaces',
        'security': 'My Profile Security Log'
      };";
    $newTitlesDem = "      const titles = {
        'dashboard': 'Lab Workspaces',
        'security': 'My Profile Security Log',
        'courseFiles': 'Course File Builder'
      };";
    $content = str_replace($oldTitlesDem, $newTitlesDem, $content);

    // 3. Add loadCourseFiles trigger
    $oldLoad = "if (panelId === 'dashboard') loadLecturerBatches();";
    $newLoad = "if (panelId === 'dashboard') loadLecturerBatches();\n      if (panelId === 'courseFiles') loadCourseFiles();";
    $content = str_replace($oldLoad, $newLoad, $content);

    // Demonstrator load
    $oldLoadDem = "if (panelId === 'dashboard') {}"; // Or maybe it doesn't have one
    // Let's just insert it after panelTitle
    $oldTitleSet = "document.getElementById('panelTitle').innerText = titles[panelId] || 'Lecturer Console';";
    $newTitleSet = "document.getElementById('panelTitle').innerText = titles[panelId] || 'Lecturer Console';\n      if (panelId === 'courseFiles') loadCourseFiles();";
    if (strpos($content, "if (panelId === 'courseFiles') loadCourseFiles();") === false) {
        $content = str_replace($oldTitleSet, $newTitleSet, $content);
    }
    
    $oldTitleSetDem = "document.getElementById('panelTitle').innerText = titles[panelId] || 'Demonstrator Console';";
    $newTitleSetDem = "document.getElementById('panelTitle').innerText = titles[panelId] || 'Demonstrator Console';\n      if (panelId === 'courseFiles') loadCourseFiles();";
    if (strpos($content, "if (panelId === 'courseFiles') loadCourseFiles();") === false) {
        $content = str_replace($oldTitleSetDem, $newTitleSetDem, $content);
    }

    // 4. Also fix the CSS classes for the active nav. My original inject put it as amber text. We want it to be styled correctly when active.
    $navLogicOld = "          if (nav) nav.className = \"w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500\";";
    $navLogicNew = "          if (nav) {
            if (id === 'courseFiles') {
              nav.className = \"w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-amber-500/10 text-amber-400 border-l-2 border-amber-500\";
            } else {
              nav.className = \"w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500\";
            }
          }";
    $content = str_replace($navLogicOld, $navLogicNew, $content);
    
    // And for inactive
    $navLogicOld2 = "          if (nav) nav.className = \"w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer\";";
    $navLogicNew2 = "          if (nav) {
            if (id === 'courseFiles') {
              nav.className = \"w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-amber-400/70 hover:bg-amber-900/30 hover:text-amber-400 cursor-pointer\";
            } else {
              nav.className = \"w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer\";
            }
          }";
    $content = str_replace($navLogicOld2, $navLogicNew2, $content);

    file_put_contents($filePath, $content);
    echo "Patched switchPanel in " . basename($filePath) . "\n";
}
