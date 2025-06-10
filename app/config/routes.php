<?php

/**
 * Blog Sitesi Routes
 */

// ===========================================
// Ana Sayfa ve Statik Sayfalar
// ===========================================

Router::get('/', 'Home@index');
Router::get('/about', 'Home@about');
Router::get('/contact', 'Home@contact');
Router::post('/contact', 'Home@contact');

// ===========================================
// Blog Routes
// ===========================================

Router::get('/blog', 'Blog@index');
Router::get('/blog/post/{slug}', 'Blog@show');
Router::get('/blog/category/{slug}', 'Blog@category');
Router::get('/blog/search', 'Blog@search');

// Yazar routes (authentication gerekli)
Router::get('/blog/create', 'Blog@create');
Router::post('/blog/create', 'Blog@create');
Router::get('/blog/edit/{id}', 'Blog@edit');
Router::post('/blog/edit/{id}', 'Blog@edit');
Router::post('/blog/delete/{id}', 'Blog@delete');
Router::get('/blog/my-posts', 'Blog@myPosts');

// ===========================================
// Auth Routes
// ===========================================

Router::get('/auth/login', 'Auth@login');
Router::post('/auth/login', 'Auth@login');
Router::get('/auth/register', 'Auth@register');
Router::post('/auth/register', 'Auth@register');
Router::get('/auth/logout', 'Auth@logout');
Router::get('/auth/forgot-password', 'Auth@forgotPassword');
Router::post('/auth/forgot-password', 'Auth@forgotPassword');

// Kısa yollar
Router::get('/login', 'Auth@login');
Router::post('/login', 'Auth@login');
Router::get('/register', 'Auth@register');
Router::post('/register', 'Auth@register');
Router::get('/logout', 'Auth@logout');

// ===========================================
// Admin Routes (admin yetkisi gerekli)
// ===========================================

Router::get('/admin', 'Admin@index');
Router::get('/admin/users', 'Admin@users');
Router::post('/admin/approve-user/{id}', 'Admin@approveUser');
Router::post('/admin/reject-user/{id}', 'Admin@rejectUser');
Router::get('/admin/posts', 'Admin@posts');
Router::get('/admin/categories', 'Admin@categories');
Router::post('/admin/categories', 'Admin@categories');
Router::get('/admin/content', 'Admin@content');
Router::post('/admin/content', 'Admin@content');
Router::get('/admin/settings', 'Admin@settings');
Router::post('/admin/settings', 'Admin@settings');

// AJAX endpoints
Router::post('/admin/toggle-edit-mode', 'Admin@toggleEditMode');
Router::post('/admin/update-content', 'Admin@updateContent');

// ===========================================
// Mevcut User Management Routes (backward compatibility)
// ===========================================

Router::get('/users', 'User@index');
Router::get('/users/api', 'User@api');
Router::get('/users/create', 'User@create');
Router::post('/users/create', 'User@create');
Router::get('/users/show/{id}', 'User@show');
Router::get('/users/edit/{id}', 'User@edit');
Router::post('/users/edit/{id}', 'User@edit');
Router::post('/users/delete/{id}', 'User@delete');
Router::get('/users/profile', 'User@profile');
