<?php

return [
    'app' => [
        'name' => 'Simple Framework',
        'url' => 'http://localhost:8000',
        'debug' => true,
        'timezone' => 'Europe/Istanbul'
    ],
    
    'database' => [
        'driver' => 'mysql', // mysql veya sqlite
        'host' => 'localhost',
        'database' => 'simple_framework',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        
        // SQLite ayarları (fallback)
        'sqlite_path' => ROOT_PATH . '/database.sqlite'
    ]
];
