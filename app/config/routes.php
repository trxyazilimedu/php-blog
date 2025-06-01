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

