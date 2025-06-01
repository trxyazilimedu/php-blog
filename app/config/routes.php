<?php

/**
 * Routes Tanımlamaları
 * Basit route sistemi
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
// User Management Routes
// ===========================================

Router::get('/users', 'User@index');
Router::get('/users/api', 'User@api');
Router::get('/users/create', 'User@create');
Router::post('/users/create', 'User@create');
Router::get('/users/show/{id}', 'User@show');
Router::get('/users/edit/{id}', 'User@edit');
Router::post('/users/edit/{id}', 'User@edit');
Router::post('/users/delete/{id}', 'User@delete');

// ===========================================
// Test Routes (Core Controller Test)
// ===========================================

Router::get('/test', 'Test@index');
Router::get('/test/api-test', 'Test@apiTest');
Router::get('/test/rate-limit-test', 'Test@rateLimitTest');
Router::get('/test/cache-test', 'Test@cacheTest');
Router::get('/test/export-csv', 'Test@exportCsv');
Router::get('/test/export-xml', 'Test@exportXml');
Router::get('/test/debug-info', 'Test@debugInfo');

