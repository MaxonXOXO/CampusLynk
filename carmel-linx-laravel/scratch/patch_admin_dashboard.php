<?php

$filePath = "c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\admin_dashboard.blade.php";
$content = file_get_contents($filePath);

// Check if navCourseFileLibrary already exists
if (strpos($content, 'id="navCourseFileLibrary"') === false) {

    // Add Sidebar Link
    $oldLink = "      <button id=\"navSecurity\" onclick=\"switchPanel('security')\" class=\"w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer\">
        <span class=\"material-symbols-rounded text-lg\">security</span> My Security Log
      </button>";

    $newLink = "      <button id=\"navCourseFileLibrary\" onclick=\"switchPanel('courseFileLibrary')\" class=\"w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-amber-400 hover:bg-amber-900/30\">
        <span class=\"material-symbols-rounded text-lg\">library_books</span> Course File Library
      </button>

      <button id=\"navSecurity\" onclick=\"switchPanel('security')\" class=\"w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer\">
        <span class=\"material-symbols-rounded text-lg\">security</span> My Security Log
      </button>";

    $content = str_replace($oldLink, $newLink, $content);

    // Add Panel
    $panelHTML = '
      <!-- PANEL: COURSE FILE LIBRARY -->
      <div id="panelCourseFileLibrary" class="hidden space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center justify-between">
          <div>
            <h3 class="text-sm font-black text-slate-200 flex items-center gap-2 mt-1">
              <span class="material-symbols-rounded text-amber-400 text-lg">library_books</span> Course File Library
            </h3>
            <p class="text-[10px] text-slate-400 font-medium">Browse and download finalized NBA Course Files for all batches.</p>
          </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 text-center text-slate-400">
            <span class="material-symbols-rounded text-5xl text-amber-500/50 block mb-3">construction</span>
            <h3 class="text-lg font-bold text-slate-200">Library Under Construction</h3>
            <p class="text-xs mt-2">The Admin viewer will be fully implemented in a future update to allow batch-wise filtering.</p>
        </div>
      </div>
';

    // Find the end of the panel container
    $pos = strrpos($content, '    </div> <!-- End Panel Container -->');
    if ($pos !== false) {
        $content = substr_replace($content, $panelHTML . "\n", $pos, 0);
    } else {
        // Fallback: Just insert before the last </div></main>
        $content = str_replace('</main>', "    </div>\n" . $panelHTML . "\n</main>", $content);
    }

    file_put_contents($filePath, $content);
    echo "Injected Admin Course File Library.\n";
} else {
    echo "Admin Course File Library already exists.\n";
}
