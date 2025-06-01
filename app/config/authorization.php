<?php

class Authorization
{
    /**
     * Middleware kontrolü
     */
    public static function checkMiddleware($middleware)
    {
        switch ($middleware) {
            case 'auth':
                return self::requireAuth();
            
            case 'guest':
                return self::requireGuest();
            
            case 'admin':
                return self::requireAdmin();
            
            case 'user':
                return self::requireUser();
            
            default:
                // Custom middleware
                $methodName = 'middleware' . ucfirst($middleware);
                if (method_exists(self::class, $methodName)) {
                    return self::$methodName();
                }
                return true;
        }
    }

    /**
     * Giriş yapmış kullanıcı gerekli
     */
    public static function requireAuth()
    {
        if (!self::isLoggedIn()) {
            self::redirectToLogin('Bu sayfaya erişmek için giriş yapmanız gerekiyor.');
            return false;
        }
        return true;
    }

    /**
     * Misafir kullanıcı gerekli (giriş yapmamış)
     */
    public static function requireGuest()
    {
        if (self::isLoggedIn()) {
            self::redirect('/', 'Zaten giriş yapmışsınız.');
            return false;
        }
        return true;
    }

    /**
     * Admin yetkisi gerekli
     */
    public static function requireAdmin()
    {
        if (!self::requireAuth()) {
            return false;
        }

        if (!self::isAdmin()) {
            self::redirect('/', 'Bu sayfaya erişim yetkiniz bulunmuyor.');
            return false;
        }
        return true;
    }

    /**
     * Normal kullanıcı yetkisi gerekli
     */
    public static function requireUser()
    {
        if (!self::requireAuth()) {
            return false;
        }

        if (self::isAdmin()) {
            // Admin'ler de user sayfalarına erişebilir
            return true;
        }

        $user = self::getCurrentUser();
        if (!$user || $user['role'] !== 'user') {
            self::redirect('/', 'Bu sayfa sadece normal kullanıcılar içindir.');
            return false;
        }
        return true;
    }

    /**
     * Özel rol kontrolü
     */
    public static function requireRole($role)
    {
        if (!self::requireAuth()) {
            return false;
        }

        $user = self::getCurrentUser();
        if (!$user || $user['role'] !== $role) {
            self::redirect('/', "Bu sayfa sadece {$role} rolüne sahip kullanıcılar içindir.");
            return false;
        }
        return true;
    }

    /**
     * Çoklu rol kontrolü
     */
    public static function requireAnyRole($roles)
    {
        if (!self::requireAuth()) {
            return false;
        }

        $user = self::getCurrentUser();
        if (!$user || !in_array($user['role'], $roles)) {
            $roleList = implode(', ', $roles);
            self::redirect('/', "Bu sayfa sadece şu rollere sahip kullanıcılar içindir: {$roleList}");
            return false;
        }
        return true;
    }

    /**
     * Kullanıcının kendi kaynağına erişim kontrolü
     */
    public static function requireOwnerOrAdmin($resourceUserId)
    {
        if (!self::requireAuth()) {
            return false;
        }

        $currentUser = self::getCurrentUser();
        
        // Admin her şeye erişebilir
        if (self::isAdmin()) {
            return true;
        }

        // Kaynak kullanıcıya ait mi?
        if ($currentUser && $currentUser['id'] == $resourceUserId) {
            return true;
        }

        self::redirect('/', 'Bu kaynağa erişim yetkiniz bulunmuyor.');
        return false;
    }

    /**
     * IP bazlı erişim kontrolü
     */
    public static function requireIP($allowedIPs)
    {
        $clientIP = self::getClientIP();
        
        if (!in_array($clientIP, $allowedIPs)) {
            http_response_code(403);
            die('Bu IP adresinden erişim yasaktır.');
            return false;
        }
        return true;
    }

    /**
     * Rate limiting kontrolü
     */
    public static function requireRateLimit($maxRequests = 60, $timeWindow = 3600)
    {
        $ip = self::getClientIP();
        $key = 'rate_limit_' . md5($ip);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'count' => 1,
                'start_time' => time()
            ];
            return true;
        }

        $data = $_SESSION[$key];
        $elapsed = time() - $data['start_time'];

        if ($elapsed > $timeWindow) {
            // Zaman aşımı, sıfırla
            $_SESSION[$key] = [
                'count' => 1,
                'start_time' => time()
            ];
            return true;
        }

        if ($data['count'] >= $maxRequests) {
            http_response_code(429);
            die('Çok fazla istek gönderdiniz. Lütfen bekleyin.');
            return false;
        }

        $_SESSION[$key]['count']++;
        return true;
    }

    // =====================================
    // Helper Methods
    // =====================================

    /**
     * Kullanıcı giriş yapmış mı?
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Admin mi?
     */
    public static function isAdmin()
    {
        return self::isLoggedIn() && ($_SESSION['user_role'] ?? 'user') === 'admin';
    }

    /**
     * Mevcut kullanıcıyı al
     */
    public static function getCurrentUser()
    {
        if (!self::isLoggedIn()) {
            return null;
        }

        // Cache için static variable
        static $currentUser = null;
        
        if ($currentUser === null) {
            require_once APP_PATH . '/models/User.php';
            $userModel = new User();
            $currentUser = $userModel->findById($_SESSION['user_id']);
        }

        return $currentUser;
    }

    /**
     * Client IP adresini al
     */
    public static function getClientIP()
    {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                return $ip;
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Login sayfasına yönlendir
     */
    private static function redirectToLogin($message = null)
    {
        if ($message) {
            self::setFlashMessage('error', $message);
        }
        
        // Output buffer'ı temizle
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        header('Location: /login');
        exit;
    }

    /**
     * Yönlendirme
     */
    private static function redirect($url, $message = null)
    {
        if ($message) {
            self::setFlashMessage('error', $message);
        }
        
        // Output buffer'ı temizle
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        header('Location: ' . $url);
        exit;
    }

    /**
     * Flash mesaj set et
     */
    private static function setFlashMessage($type, $message)
    {
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        
        if (!isset($_SESSION['flash_messages'][$type])) {
            $_SESSION['flash_messages'][$type] = [];
        }
        
        $_SESSION['flash_messages'][$type][] = $message;
    }
}
