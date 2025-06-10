<?php

/**
 * Admin Middleware
 * Kullanıcının admin yetkisi olup olmadığını kontrol eder
 */
class AdminMiddleware
{
    public function handle()
    {
        // Session başlat
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Önce giriş yapmış mı kontrol et
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            $_SESSION['flash_messages']['error'][] = 'Bu sayfaya erişmek için giriş yapmalısınız.';
            header('Location: /login');
            exit;
        }

        // Kullanıcı bilgilerini al
        try {
            $userModel = new User();
            $user = $userModel->findById($_SESSION['user_id']);
            
            if (!$user || $user['role'] !== 'admin') {
                $_SESSION['flash_messages']['error'][] = 'Bu sayfaya erişim yetkiniz bulunmuyor.';
                header('Location: /');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['flash_messages']['error'][] = 'Yetki kontrolü sırasında bir hata oluştu.';
            header('Location: /');
            exit;
        }

        return true;
    }
}