<?php

/**
 * Authentication Middleware
 * Kullanıcının giriş yapıp yapmadığını kontrol eder
 */
class AuthMiddleware
{
    public function handle()
    {
        // Session başlat
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kullanıcı giriş yapmış mı kontrol et
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            // Flash mesaj ekle
            $_SESSION['flash_messages']['error'][] = 'Bu sayfaya erişmek için giriş yapmalısınız.';
            
            // Giriş sayfasına yönlendir
            header('Location: /login');
            exit;
        }

        return true;
    }
}