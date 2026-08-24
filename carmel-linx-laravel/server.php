<?php

$publicPath = __DIR__ . '/public';

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// Only return false for actual static files on disk
if ($uri !== '/' && is_file($publicPath . $uri)) {
    return false;
}

$_SERVER['SCRIPT_NAME'] = '/index.php';

require_once $publicPath . '/index.php';
