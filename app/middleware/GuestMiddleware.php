<?php

/**
 * Guest Middleware
 * Sadece giriş yapmamış kullanıcıların erişebileceği sayfalar için
 * (login, register sayfaları gibi)
 */
class GuestMiddleware
{
    public function handle()
    {
        // Session başlat
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kullanıcı zaten giriş yapmışsa anasayfaya yönlendir
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $_SESSION['flash_messages']['info'][] = 'Zaten giriş yapmışsınız.';
            header('Location: /');
            exit;
        }

        return true;
    }
}