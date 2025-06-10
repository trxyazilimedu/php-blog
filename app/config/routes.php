<?php

/**
 * Modern Route System with Groups and Middleware
 */

// ===========================================
// Ana Sayfa ve Statik Sayfalar
// ===========================================

Router::get('/', 'Blog@index');
Router::get('/about', 'Home@about');
Router::get('/contact', 'Home@contact');
Router::post('/contact', 'Home@contact');

// ===========================================
// Public Blog Routes
// ===========================================

Router::get('/blog', 'Blog@index');
Router::get('/blog/post/{slug}', 'Blog@show');
Router::get('/blog/category/{slug}', 'Blog@category');
Router::get('/blog/search', 'Blog@search');

// ===========================================
// Authentication Routes (Guest Only)
// ===========================================

Router::group(['middleware' => ['guest']], function() {
    Router::get('/login', 'Auth@login');
    Router::post('/login', 'Auth@login');
    Router::get('/register', 'Auth@register');
    Router::post('/register', 'Auth@register');
    Router::get('/auth/forgot-password', 'Auth@forgotPassword');
    Router::post('/auth/forgot-password', 'Auth@forgotPassword');
});

// Logout (authenticated users only)
Router::group(['middleware' => ['auth']], function() {
    Router::get('/logout', 'Auth@logout');
});

// ===========================================
// Writer Routes (Writer/Admin Only)
// ===========================================

Router::group(['middleware' => ['writer']], function() {
    Router::get('/blog/create', 'Blog@create');
    Router::post('/blog/create', 'Blog@create');
    Router::get('/blog/edit/{id}', 'Blog@edit');
    Router::post('/blog/edit/{id}', 'Blog@edit');
    Router::post('/blog/delete/{id}', 'Blog@delete');
    Router::get('/blog/my-posts', 'Blog@myPosts');
});

// ===========================================
// Admin Routes (Admin Only)
// ===========================================

Router::group(['prefix' => 'admin', 'middleware' => ['admin']], function() {
    Router::get('/', 'Admin@index');
    Router::get('/users', 'Admin@users');
    Router::post('/approve-user/{id}', 'Admin@approveUser');
    Router::post('/reject-user/{id}', 'Admin@rejectUser');
    Router::get('/posts', 'Admin@posts');
    Router::get('/categories', 'Admin@categories');
    Router::post('/categories', 'Admin@categories');
    Router::get('/content', 'Admin@content');
    Router::post('/content', 'Admin@content');
    Router::get('/settings', 'Admin@settings');
    Router::post('/settings', 'Admin@settings');
    
    // AJAX endpoints
    Router::post('/toggle-edit-mode', 'Admin@toggleEditMode');
    Router::post('/update-content', 'Admin@updateContent');
    Router::post('/comments/approve/{id}', 'Admin@approveComment');
    Router::post('/comments/reject/{id}', 'Admin@rejectComment');
});

// ===========================================
// User Profile Routes (Auth Required)
// ===========================================

Router::group(['middleware' => ['auth']], function() {
    Router::get('/profile', 'User@profile');
    Router::post('/profile', 'User@profile');
    Router::get('/profile/edit', 'User@edit');
    Router::post('/profile/edit', 'User@edit');
});
