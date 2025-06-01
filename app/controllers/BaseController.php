<?php

abstract class BaseController
{
    protected $db;
    protected $globalData = [];
    protected $services = [];

    public function __construct()
    {
        // Session başlat
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // FrameworkHelper'i yükle
        require_once CORE_PATH . '/FrameworkHelper.php';
        
        // Helper işlemlerini başlat
        FrameworkHelper::startSession();
        $this->db = Database::getInstance();
        $this->globalData = FrameworkHelper::buildGlobalData();
    }

    /**
     * View render etme
     */
    protected function view($view, $data = [])
    {
        FrameworkHelper::renderView($view, $data, $this->globalData);
    }

    /**
     * Model yükleme
     */
    protected function model($model)
    {
        return FrameworkHelper::loadModel($model);
    }

    /**
     * Service'e erişim
     */
    protected function service($serviceName)
    {
        return FrameworkHelper::loadService($serviceName, $this->services);
    }

    /**
     * Yönlendirme
     */
    protected function redirect($url, $statusCode = 302)
    {
        FrameworkHelper::redirect($url, $statusCode);
    }

    /**
     * JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        FrameworkHelper::jsonResponse($data, $statusCode);
    }

    /**
     * Global veri ekleme
     */
    protected function addGlobalData($key, $value)
    {
        $this->globalData[$key] = $value;
    }

    /**
     * Global veri alma
     */
    protected function getGlobalData($key = null)
    {
        if ($key === null) {
            return $this->globalData;
        }
        
        return $this->globalData[$key] ?? null;
    }

    /**
     * Flash mesaj ekleme
     */
    protected function flash($type, $message)
    {
        FrameworkHelper::addFlashMessage($type, $message);
    }

    /**
     * CSRF token doğrulama
     */
    protected function validateCSRFToken($token)
    {
        return FrameworkHelper::validateCSRFToken($token);
    }

    /**
     * Input validation
     */
    protected function validate($data, $rules)
    {
        return FrameworkHelper::validateInput($data, $rules);
    }

    /**
     * Dosya upload
     */
    protected function uploadFile($fileInput, $uploadPath = 'uploads/')
    {
        return FrameworkHelper::handleFileUpload($fileInput, $uploadPath);
    }

    /**
     * Cache
     */
    protected function cache($key, $data = null, $ttl = 3600)
    {
        return FrameworkHelper::cache($key, $data, $ttl);
    }

    /**
     * Log
     */
    protected function log($message, $level = 'info', $context = [])
    {
        FrameworkHelper::log($message, $level, $context);
    }

    // ===========================================
    // Yetkilendirme Helper Methods
    // ===========================================

    /**
     * Kullanıcı giriş kontrolü
     */
    protected function isUserLoggedIn()
    {
        return FrameworkHelper::isUserLoggedIn();
    }

    /**
     * Giriş yapan kullanıcı bilgisi
     */
    protected function getLoggedInUser()
    {
        return FrameworkHelper::getLoggedInUser();
    }

    /**
     * Giriş yapmış kullanıcı gerekli
     */
    protected function requireAuth()
    {
        return Authorization::requireAuth();
    }

    /**
     * Admin yetkisi gerekli
     */
    protected function requireAdmin()
    {
        return Authorization::requireAdmin();
    }

    /**
     * Belirli rol gerekli
     */
    protected function requireRole($role)
    {
        return Authorization::requireRole($role);
    }

    /**
     * Kaynak sahibi veya admin gerekli
     */
    protected function requireOwnerOrAdmin($resourceUserId)
    {
        return Authorization::requireOwnerOrAdmin($resourceUserId);
    }

    // ===========================================
    // Request Helper Methods
    // ===========================================

    /**
     * Request method kontrolü
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Request method kontrolü
     */
    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * AJAX request kontrolü
     */
    protected function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * Input değeri alma
     */
    protected function input($key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    /**
     * Tüm input'ları alma
     */
    protected function all()
    {
        return array_merge($_GET, $_POST);
    }

    /**
     * Sadece belirtilen key'leri alma
     */
    protected function only(...$keys)
    {
        $all = $this->all();
        $result = [];
        
        foreach ($keys as $key) {
            if (isset($all[$key])) {
                $result[$key] = $all[$key];
            }
        }
        
        return $result;
    }

    /**
     * Belirtilen key'ler hariç tümünü alma
     */
    protected function except(...$keys)
    {
        $all = $this->all();
        
        foreach ($keys as $key) {
            unset($all[$key]);
        }
        
        return $all;
    }
}
