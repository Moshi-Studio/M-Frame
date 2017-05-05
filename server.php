<?php

// php -S localhost:8000 -t public/ server.php

$publicPath = 'public';

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$requested = $publicPath . $uri;

if (($uri !== '/') && file_exists($requested)) {
    return false;
}

require_once __DIR__ . '/' . $publicPath . '/index.php';