<?php
$path = 'routes/web.php';
$content = file_get_contents($path);

// Insert the save route after previewDocument
$search = "Route::get('/api/course-files/{id}/preview/{docNo}', [App\Http\Controllers\CourseFileController::class, 'previewDocument']);";
$replace = $search . "\n    Route::post('/api/course-files/{id}/document/{docNo}/save', [App\Http\Controllers\CourseFileController::class, 'saveDocumentPayload']);";

if (strpos($content, 'saveDocumentPayload') === false) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($path, $content);
    echo "Added saveDocumentPayload route.\n";
} else {
    echo "Route already exists.\n";
}
