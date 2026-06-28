<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$courseFile = Illuminate\Support\Facades\DB::table('course_files')->orderBy('id', 'desc')->first();
$parser = new \Smalot\PdfParser\Parser();
$path = storage_path('app/public/syllabi/' . basename($courseFile->syllabus_pdf_path));
$text = $parser->parseFile($path)->getText();
echo substr($text, 0, 5000);
