<?php

$path = 'app/Http/Controllers/CourseFileController.php';
$content = file_get_contents($path);

// Add the preview method
$previewMethod = "
    public function previewDocument(\$id, \$docNo)
    {
        \$cf = CfCourseFile::find(\$id);
        if (!\$cf) {
            return response()->json(['status' => 'ERROR', 'message' => 'Course file not found']);
        }

        \$html = \"<div class='p-8 text-center'>
            <h2 class='text-2xl font-black mb-4'>Document \".\$docNo.\" Preview</h2>
            <p class='text-gray-600 mb-8'>This is a live preview of Document \".\$docNo.\". The exact A4 print layout will be integrated here once the models are provided.</p>
            <div class='border-4 border-dashed border-gray-200 rounded-2xl p-12 bg-gray-50 text-gray-400 font-bold'>
                A4 Document Layout Placeholder
            </div>
        </div>\";

        return response()->json(['status' => 'SUCCESS', 'html' => \$html]);
    }
";

if (strpos($content, 'function previewDocument') === false) {
    // Insert before the last closing brace
    $pos = strrpos($content, '}');
    $content = substr_replace($content, $previewMethod . "\n}", $pos, 1);
    file_put_contents($path, $content);
    echo "Added previewDocument to CourseFileController.\n";
}

// Add route for preview
$routePath = 'routes/web.php';
$routeContent = file_get_contents($routePath);
$routeReplacement = "Route::get('/api/course-files/{id}/preview/{docNo}', [App\Http\Controllers\CourseFileController::class, 'previewDocument']);\n    Route::get('/api/course-files/{id}/pdf',";

if (strpos($routeContent, 'preview/{docNo}') === false) {
    $routeContent = str_replace("Route::get('/api/course-files/{id}/pdf',", $routeReplacement, $routeContent);
    file_put_contents($routePath, $routeContent);
    echo "Added preview route to web.php.\n";
}
