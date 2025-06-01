<?php

// Hata raporlamayı aç
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Temel sabitler
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CORE_PATH', ROOT_PATH . '/core');

// Otomatik yükleme
spl_autoload_register(function ($class) {
    $paths = [
        CORE_PATH . '/' . $class . '.php',
        APP_PATH . '/controllers/' . $class . '.php',
        APP_PATH . '/models/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
});

// Framework'ü başlat
require_once CORE_PATH . '/App.php';

$app = new App();
