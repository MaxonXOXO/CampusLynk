<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$cf = DB::table('course_files')->where('batch_subject_id', 5)->first();
if ($cf) {
    $parser = new \Smalot\PdfParser\Parser();
    $pdf = $parser->parseFile(storage_path('app/public/' . str_replace('/storage/', '', $cf->syllabus_pdf_path)));
    $text = $pdf->getText();
    echo "TEXT LENGTH: " . strlen($text) . "\n";
    echo substr($text, 0, 4000);
} else {
    echo "No CourseFile for ID 5\n";
}
