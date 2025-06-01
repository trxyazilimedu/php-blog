<?php

use Router;

/**
 * Routes Tanımlamaları
 * CodeIgniter tarzında gruplanmış route sistemi
 */

// ===========================================
// Public Routes (Misafir Erişimi)
// ===========================================

Router::get('/', 'Home@index');
Router::get('/about', 'Home@about');

// İletişim sayfası - GET ve POST
Router::get('/contact', 'Home@contact');
Router::post('/contact', 'Home@contact');

// ===========================================
// Guest Routes (Sadece giriş yapmamışlar)
// ===========================================

Router::group(['middleware' => ['guest']], function() {
    Router::get('/login', 'Auth@loginForm');
    Router::post('/login', 'Auth@login');
    Router::get('/register', 'Auth@registerForm');
    Router::post('/register', 'Auth@register');
    Router::get('/forgot-password', 'Auth@forgotPasswordForm');
    Router::post('/forgot-password', 'Auth@forgotPassword');
});

// ===========================================
// Auth Routes (Giriş yapmış kullanıcılar)
// ===========================================

Router::group(['middleware' => ['auth']], function() {
    Router::post('/logout', 'Auth@logout');
    Router::get('/profile', 'User@profile');
    Router::get('/dashboard', 'Dashboard@index');
});

// ===========================================
// User Management Routes
// ===========================================

Router::group(['prefix' => 'users', 'middleware' => ['auth']], function() {
    
    // Herkes görebilir (giriş yapmış)
    Router::get('/', 'User@index');
    Router::get('/api', 'User@api');
    Router::get('/{id}', 'User@show');
    
    // Sadece admin
    Router::group(['middleware' => ['admin']], function() {
        Router::get('/create', 'User@create');
        Router::post('/', 'User@store');
        Router::get('/{id}/edit', 'User@edit');
        Router::put('/{id}', 'User@update');
        Router::delete('/{id}', 'User@destroy');
    });
});

// ===========================================
// Admin Panel Routes
// ===========================================

Router::group(['prefix' => 'admin', 'middleware' => ['admin']], function() {
    
    // Admin Dashboard
    Router::get('/', 'Admin\Dashboard@index');
    Router::get('/dashboard', 'Admin\Dashboard@index');
    
    // Kullanıcı Yönetimi
    Router::group(['prefix' => 'users'], function() {
        Router::resource('/', 'Admin\User'); // CRUD routes
        Router::get('/export', 'Admin\User@export');
        Router::post('/import', 'Admin\User@import');
        Router::post('/{id}/ban', 'Admin\User@ban');
        Router::post('/{id}/unban', 'Admin\User@unban');
    });
    
    // İçerik Yönetimi
    Router::group(['prefix' => 'content'], function() {
        Router::resource('/pages', 'Admin\Page');
        Router::resource('/posts', 'Admin\Post');
        Router::resource('/categories', 'Admin\Category');
    });
    
    // Sistem Ayarları
    Router::group(['prefix' => 'settings'], function() {
        Router::get('/', 'Admin\Settings@index');
        Router::post('/', 'Admin\Settings@update');
        Router::get('/cache', 'Admin\Settings@cache');
        Router::post('/cache/clear', 'Admin\Settings@clearCache');
        Router::get('/logs', 'Admin\Settings@logs');
    });
    
    // Raporlar
    Router::group(['prefix' => 'reports'], function() {
        Router::get('/', 'Admin\Reports@index');
        Router::get('/users', 'Admin\Reports@users');
        Router::get('/activity', 'Admin\Reports@activity');
        Router::get('/export/{type}', 'Admin\Reports@export');
    });
});

// ===========================================
// API Routes
// ===========================================

Router::group(['prefix' => 'api/v1', 'middleware' => ['auth']], function() {
    
    // User API
    Router::get('/user', 'Api\User@profile');
    Router::put('/user', 'Api\User@update');
    Router::get('/users', 'Api\User@index');
    
    // Admin API
    Router::group(['middleware' => ['admin']], function() {
        Router::get('/admin/stats', 'Api\Admin@stats');
        Router::get('/admin/users', 'Api\Admin@users');
        Router::post('/admin/users/{id}/toggle-status', 'Api\Admin@toggleUserStatus');
    });
});

// ===========================================
// File & Upload Routes
// ===========================================

Router::group(['prefix' => 'uploads', 'middleware' => ['auth']], function() {
    Router::post('/avatar', 'Upload@avatar');
    Router::post('/document', 'Upload@document');
    Router::get('/file/{id}', 'Upload@download');
    Router::delete('/file/{id}', 'Upload@delete');
});

// ===========================================
// Developer Routes (Sadece development)
// ===========================================

if (config('app.debug', false)) {
    Router::group(['prefix' => 'dev'], function() {
        Router::get('/routes', function() {
            $routes = Router::getRoutes();
            echo '<h1>Registered Routes</h1>';
            echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
            echo '<tr><th>Method</th><th>URI</th><th>Action</th><th>Middleware</th></tr>';
            
            foreach ($routes as $route) {
                $middleware = implode(', ', $route['middleware']);
                echo "<tr>";
                echo "<td>{$route['method']}</td>";
                echo "<td>{$route['uri']}</td>";
                echo "<td>{$route['action']}</td>";
                echo "<td>{$middleware}</td>";
                echo "</tr>";
            }
            echo '</table>';
        });
        
        Router::get('/phpinfo', function() {
            phpinfo();
        });
        
        Router::get('/test-auth', function() {
            $auth = Authorization::getCurrentUser();
            dd([
                'is_logged_in' => Authorization::isLoggedIn(),
                'is_admin' => Authorization::isAdmin(),
                'user' => $auth
            ]);
        });
    });
}

// ===========================================
// Fallback Route (404)
// ===========================================

//Router::any('/.*', function() {
//    http_response_code(404);
//    if (file_exists(APP_PATH . '/views/errors/404.php')) {
//        require APP_PATH . '/views/errors/404.php';
//    } else {
//        echo '<h1>404 - Sayfa Bulunamadı</h1>';
//        echo '<p>Aradığınız sayfa bulunamadı.</p>';
//        echo '<a href="/">Ana Sayfaya Dön</a>';
//    }
//});
