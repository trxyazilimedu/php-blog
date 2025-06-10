<?php

/**
 * Writer Middleware
 * Kullanıcının writer veya admin yetkisi olup olmadığını kontrol eder
 */
class WriterMiddleware
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
            
            if (!$user || !in_array($user['role'], ['writer', 'admin'])) {
                $_SESSION['flash_messages']['error'][] = 'Bu sayfaya erişim yetkiniz bulunmuyor. Sadece yazarlar ve adminler erişebilir.';
                header('Location: /');
                exit;
            }

            // Writer ise ve durumu active değilse
            if ($user['role'] === 'writer' && $user['status'] !== 'active') {
                $_SESSION['flash_messages']['warning'][] = 'Hesabınız henüz onaylanmamış. Admin onayını bekleyiniz.';
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