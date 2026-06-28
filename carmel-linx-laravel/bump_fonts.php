<?php
$dir = new DirectoryIterator("resources/views");
foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot() && $fileinfo->getExtension() === "php") {
        $path = $fileinfo->getPathname();
        $content = file_get_contents($path);
        
        // Bump text classes
        $content = str_replace("text-[9px]", "text-[11px]", $content);
        $content = str_replace("text-[10px]", "text-xs", $content);
        $content = str_replace("text-[11px]", "text-sm", $content);
        $content = preg_replace("/\btext-xs\b/", "text-sm", $content);
        $content = preg_replace("/\btext-sm\b/", "text-base", $content);
        
        file_put_contents($path, $content);
    }
}
echo "Done";
