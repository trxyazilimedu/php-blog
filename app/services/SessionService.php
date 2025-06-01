<?php

class SessionService
{
    public function __construct()
    {
        // Session zaten BaseController'da başlatılmış olabilir
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Session değeri kaydetme
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Session değeri alma
     */
    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Session değeri varlık kontrolü
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Session değeri silme
     */
    public function remove($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Tüm session'ı temizleme
     */
    public function clear()
    {
        $_SESSION = [];
    }

    /**
     * Session'ı tamamen yok etme
     */
    public function destroy()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
            
            // Session cookie'sini de sil
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
        }
    }

    /**
     * Session ID'yi yenileme (güvenlik için)
     */
    public function regenerate()
    {
        session_regenerate_id(true);
    }

    /**
     * Flash message kaydetme (bir kez gösterilecek mesajlar)
     */
    public function flash($key, $value)
    {
        if (!isset($_SESSION['_flash'])) {
            $_SESSION['_flash'] = [];
        }
        $_SESSION['_flash'][$key] = $value;
    }

    /**
     * Flash message alma ve silme
     */
    public function getFlash($key, $default = null)
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        
        if (isset($_SESSION['_flash'][$key])) {
            unset($_SESSION['_flash'][$key]);
        }
        
        return $value;
    }

    /**
     * Tüm flash message'ları alma
     */
    public function getAllFlash()
    {
        $flashMessages = $_SESSION['_flash'] ?? [];
        $_SESSION['_flash'] = [];
        return $flashMessages;
    }

    /**
     * Session verilerini array olarak alma
     */
    public function all()
    {
        return $_SESSION;
    }

    /**
     * Session'ın aktif olup olmadığını kontrol etme
     */
    public function isActive()
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }
}
