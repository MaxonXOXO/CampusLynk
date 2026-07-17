<?php
require 'vendor/autoload.php';
$p = new Smalot\PdfParser\Parser();
$pdf = $p->parseFile('C:/Users/fotonlabz/Downloads/5041 (1).pdf');
echo $pdf->getText();
