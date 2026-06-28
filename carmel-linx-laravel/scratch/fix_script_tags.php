<?php

$filePath = "c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\student_mentoring_scripts.blade.php";
$content = file_get_contents($filePath);

// Find </script>
$scriptEndPos = strpos($content, '</script>');

if ($scriptEndPos !== false) {
    // The raw functions are appended AFTER </script>
    $functionsText = substr($content, $scriptEndPos + strlen('</script>'));
    
    // Remove the appended text from the end
    $contentWithoutFunctions = substr($content, 0, $scriptEndPos);
    
    // Put the functions inside the script block
    $fixedContent = $contentWithoutFunctions . "\n" . trim($functionsText) . "\n</script>\n";
    
    file_put_contents($filePath, $fixedContent);
    echo "Fixed script tags.\n";
} else {
    echo "Could not find </script> tag.\n";
}
