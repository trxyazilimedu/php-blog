<?php
// PHP Built-in Server için router
// Bu dosya sadece `php -S` ile kullanılır

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Statik dosyalar için (CSS, JS, resimler)
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Statik dosyayı serve et
}

// Tüm istekleri index.php'ye yönlendir
$_GET['url'] = trim($uri, '/');
require_once 'index.php';
