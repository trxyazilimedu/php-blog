<?php

/**
 * Core Controller Sınıfı
 * Tüm controller'ların extend edeceği temel sınıf
 * Temel framework işlevlerini sağlar
 */
abstract class Controller
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
        
        // Core seviyesi global veriler
        $this->setFrameworkGlobals();
    }

    /**
     * Framework global verilerini ayarlama
     */
    private function setFrameworkGlobals()
    {
        // Framework bilgileri
        $this->addGlobalData('framework_name', 'Simple Framework');
        $this->addGlobalData('framework_version', '2.0.0');
        
        // Debug modu kontrolü
        $this->addGlobalData('debug_mode', config('app.debug', false));
        
        // Uygulama URL'i
        $this->addGlobalData('app_url', config('app.url', 'http://localhost:8000'));
        
        // Performance tracking
        $this->addGlobalData('request_start_time', $_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true));
    }

    // ===========================================
    // Core Framework Methods
    // ===========================================

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

    // ===========================================
    // Data Management Methods
    // ===========================================

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

    // ===========================================
    // Security & Validation Methods
    // ===========================================

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

    // ===========================================
    // File & Storage Methods
    // ===========================================

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
    // Authentication & Authorization Methods
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
        if (!$this->isUserLoggedIn()) {
            $this->flash('error', 'Bu sayfaya erişmek için giriş yapmalısınız.');
            $this->redirect('/login');
            return false;
        }
        return true;
    }

    /**
     * Admin yetkisi gerekli
     */
    protected function requireAdmin()
    {
        if (!$this->requireAuth()) {
            return false;
        }

        $user = $this->getLoggedInUser();
        if (!$user || $user['role'] !== 'admin') {
            $this->flash('error', 'Bu sayfaya erişim yetkiniz bulunmuyor.');
            $this->redirect('/');
            return false;
        }
        return true;
    }

    /**
     * Writer yetkisi gerekli (writer veya admin)
     */
    protected function requireWriter()
    {
        if (!$this->requireAuth()) {
            return false;
        }

        $user = $this->getLoggedInUser();
        if (!$user || !in_array($user['role'], ['writer', 'admin'])) {
            $this->flash('error', 'Bu sayfaya erişim yetkiniz bulunmuyor. Sadece yazarlar ve adminler erişebilir.');
            $this->redirect('/');
            return false;
        }

        // Writer ise ve durumu active değilse
        if ($user['role'] === 'writer' && $user['status'] !== 'active') {
            $this->flash('warning', 'Hesabınız henüz onaylanmamış. Admin onayını bekleyiniz.');
            $this->redirect('/');
            return false;
        }

        return true;
    }

    /**
     * Belirli rol gerekli
     */
    protected function requireRole($role)
    {
        if (!$this->requireAuth()) {
            return false;
        }

        $user = $this->getLoggedInUser();
        if (!$user || $user['role'] !== $role) {
            $this->flash('error', "Bu sayfa sadece {$role} rolüne sahip kullanıcılar içindir.");
            $this->redirect('/');
            return false;
        }
        return true;
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
     * Request method kontrolü - POST
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Request method kontrolü - GET
     */
    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Request method kontrolü - PUT
     */
    protected function isPut()
    {
        return $_SERVER['REQUEST_METHOD'] === 'PUT';
    }

    /**
     * Request method kontrolü - DELETE
     */
    protected function isDelete()
    {
        return $_SERVER['REQUEST_METHOD'] === 'DELETE';
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

    // ===========================================
    // Response Helper Methods
    // ===========================================

    /**
     * HTTP status code ayarlama
     */
    protected function setStatusCode($code)
    {
        http_response_code($code);
        return $this;
    }

    /**
     * Header ayarlama
     */
    protected function setHeader($name, $value)
    {
        header($name . ': ' . $value);
        return $this;
    }

    /**
     * Content-Type ayarlama
     */
    protected function setContentType($type)
    {
        return $this->setHeader('Content-Type', $type);
    }

    /**
     * XML response
     */
    protected function xml($data, $statusCode = 200)
    {
        $this->setStatusCode($statusCode);
        $this->setContentType('application/xml; charset=utf-8');
        
        if (is_array($data)) {
            // Array'i basit XML'e çevir
            $xml = new SimpleXMLElement('<root/>');
            $this->arrayToXml($data, $xml);
            echo $xml->asXML();
        } else {
            echo $data;
        }
        exit;
    }

    /**
     * CSV response
     */
    protected function csv($data, $filename = 'export.csv', $statusCode = 200)
    {
        $this->setStatusCode($statusCode);
        $this->setHeader('Content-Type', 'text/csv');
        $this->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        if (is_array($data) && !empty($data)) {
            // Header satırını yaz
            if (is_array($data[0])) {
                fputcsv($output, array_keys($data[0]));
            }
            
            // Veriyi yaz
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
    }

    // ===========================================
    // Application Helper Methods
    // ===========================================

    /**
     * Success mesajı ile redirect
     */
    protected function redirectWithSuccess($url, $message)
    {
        $this->flash('success', $message);
        $this->redirect($url);
    }

    /**
     * Error mesajı ile redirect
     */
    protected function redirectWithError($url, $message)
    {
        $this->flash('error', $message);
        $this->redirect($url);
    }

    /**
     * Warning mesajı ile redirect
     */
    protected function redirectWithWarning($url, $message)
    {
        $this->flash('warning', $message);
        $this->redirect($url);
    }

    /**
     * Info mesajı ile redirect
     */
    protected function redirectWithInfo($url, $message)
    {
        $this->flash('info', $message);
        $this->redirect($url);
    }

    /**
     * Önceki sayfaya dön
     */
    protected function back($fallbackUrl = '/')
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? $fallbackUrl;
        $this->redirect($referer);
    }

    /**
     * Form validation ile birlikte hata yönetimi
     */
    protected function validateOrRedirect($data, $rules, $redirectUrl = null)
    {
        $errors = $this->validate($data, $rules);
        
        if (!empty($errors)) {
            // Eski input'ları session'a kaydet
            $_SESSION['old_input'] = $data;
            
            // Hataları flash mesaj olarak ekle
            foreach ($errors as $field => $fieldErrors) {
                foreach ($fieldErrors as $error) {
                    $this->flash('error', $error);
                }
            }
            
            // Redirect
            if ($redirectUrl) {
                $this->redirect($redirectUrl);
            } else {
                $this->back();
            }
            
            return false;
        }
        
        // Eski input'ları temizle
        unset($_SESSION['old_input']);
        
        return true;
    }

    /**
     * Model ile birlikte sayfalama
     */
    protected function paginate($model, $perPage = 15, $page = null)
    {
        $page = $page ?: (int)($this->input('page', 1));
        $offset = ($page - 1) * $perPage;
        
        // Model'den total count al
        if (method_exists($model, 'count')) {
            $total = $model->count();
        } else {
            // Fallback
            $allData = $model->findAll();
            $total = count($allData);
        }
        
        // Sayfalanmış veriyi al
        if (method_exists($model, 'limit')) {
            $data = $model->limit($perPage, $offset);
        } else {
            // Fallback
            $allData = $model->findAll();
            $data = array_slice($allData, $offset, $perPage);
        }
        
        $totalPages = ceil($total / $perPage);
        
        return [
            'data' => $data,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
                'has_prev' => $page > 1,
                'has_next' => $page < $totalPages,
                'prev_page' => $page > 1 ? $page - 1 : null,
                'next_page' => $page < $totalPages ? $page + 1 : null
            ]
        ];
    }

    // ===========================================
    // Enhanced Security Methods
    // ===========================================

    /**
     * CSRF token kontrolü yap ve hata ver
     */
    protected function verifyCsrfOrFail($token = null)
    {
        $token = $token ?: $this->input('csrf_token');
        
        if (!$this->validateCSRFToken($token)) {
            $this->setStatusCode(403);
            $this->flash('error', 'Güvenlik hatası: Geçersiz CSRF token!');
            $this->back();
            exit;
        }
        
        return true;
    }

    /**
     * Rate limiting kontrolü
     */
    protected function checkRateLimit($maxRequests = 60, $timeWindow = 3600)
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $key = 'rate_limit_' . md5($ip . $_SERVER['REQUEST_URI']);
        
        $cached = $this->cache($key);
        
        if ($cached === null) {
            // İlk istek
            $this->cache($key, ['count' => 1, 'start' => time()], $timeWindow);
            return true;
        }
        
        // Zaman aşımı kontrolü
        if (time() - $cached['start'] > $timeWindow) {
            // Reset
            $this->cache($key, ['count' => 1, 'start' => time()], $timeWindow);
            return true;
        }
        
        // Limit kontrolü
        if ($cached['count'] >= $maxRequests) {
            $this->setStatusCode(429);
            $this->json([
                'error' => 'Çok fazla istek gönderdiniz. Lütfen bekleyin.',
                'retry_after' => $timeWindow - (time() - $cached['start'])
            ]);
            exit;
        }
        
        // Sayacı artır
        $cached['count']++;
        $this->cache($key, $cached, $timeWindow);
        
        return true;
    }

    // ===========================================
    // Enhanced API Response Methods
    // ===========================================

    /**
     * API başarı response'u
     */
    protected function apiSuccess($data = null, $message = 'İşlem başarılı', $statusCode = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'timestamp' => date('c')
        ];
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        $this->json($response, $statusCode);
    }

    /**
     * API hata response'u
     */
    protected function apiError($message = 'Bir hata oluştu', $statusCode = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => date('c')
        ];
        
        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        
        $this->json($response, $statusCode);
    }

    /**
     * API validation hatası
     */
    protected function apiValidationError($errors, $message = 'Validation hatası')
    {
        $this->apiError($message, 422, $errors);
    }

    /**
     * API unauthorized response
     */
    protected function apiUnauthorized($message = 'Bu işlem için yetkiniz bulunmuyor')
    {
        $this->apiError($message, 401);
    }

    /**
     * API not found response
     */
    protected function apiNotFound($message = 'Kaynak bulunamadı')
    {
        $this->apiError($message, 404);
    }

    // ===========================================
    // Enhanced File & Media Methods
    // ===========================================

    /**
     * Dosya indirme zorla
     */
    protected function forceDownload($filePath, $fileName = null)
    {
        if (!file_exists($filePath)) {
            $this->setStatusCode(404);
            $this->apiError('Dosya bulunamadı', 404);
            return;
        }
        
        $fileName = $fileName ?: basename($filePath);
        
        $this->setHeader('Content-Type', 'application/octet-stream');
        $this->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        $this->setHeader('Content-Length', filesize($filePath));
        $this->setHeader('Pragma', 'no-cache');
        $this->setHeader('Expires', '0');
        
        readfile($filePath);
        exit;
    }

    /**
     * Resim resize ve gösterme
     */
    protected function resizeImage($imagePath, $width = null, $height = null)
    {
        if (!file_exists($imagePath)) {
            $this->setStatusCode(404);
            echo 'Resim bulunamadı';
            exit;
        }
        
        // Basit resize logic - gerçek uygulamada GD veya Imagick kullanılabilir
        $this->setContentType('image/jpeg');
        readfile($imagePath);
        exit;
    }

    /**
     * QR kod oluşturma
     */
    protected function generateQrCode($data, $size = 200)
    {
        // Basit QR kod implementasyonu
        // Gerçek uygulamada phpqrcode veya benzeri kütüphane kullanılabilir
        
        $this->setContentType('image/png');
        
        // Placeholder QR kod
        $placeholder = "data:image/svg+xml;base64," . base64_encode(
            '<svg width="' . $size . '" height="' . $size . '" xmlns="http://www.w3.org/2000/svg">
                <rect width="100%" height="100%" fill="white"/>
                <text x="50%" y="50%" text-anchor="middle" dy=".3em">' . htmlspecialchars($data) . '</text>
            </svg>'
        );
        
        echo $placeholder;
        exit;
    }

    // ===========================================
    // Enhanced Middleware Methods
    // ===========================================

    /**
     * AJAX request gerekliliği kontrolü
     */
    protected function requireAjax()
    {
        if (!$this->isAjax()) {
            $this->setStatusCode(400);
            $this->apiError('Bu endpoint sadece AJAX istekleri kabul eder');
            exit;
        }
        
        return true;
    }

    /**
     * JSON content type gerekliliği
     */
    protected function requireJson()
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if (strpos($contentType, 'application/json') === false) {
            $this->setStatusCode(415);
            $this->apiError('Content-Type application/json olmalıdır');
            exit;
        }
        
        return true;
    }

    /**
     * HTTPS gerekliliği kontrolü
     */
    protected function requireHttps()
    {
        if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
            $httpsUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $this->redirect($httpsUrl, 301);
            exit;
        }
        
        return true;
    }

    /**
     * Array'i XML'e çevirme helper
     */
    private function arrayToXml($data, $xmlData)
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item' . $key;
            }
            
            if (is_array($value)) {
                $subnode = $xmlData->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xmlData->addChild($key, htmlspecialchars($value));
            }
        }
    }

    /**
     * Debug helper
     */
    protected function debug($data, $die = false)
    {
        echo '<pre style="background: #1e1e1e; color: #f8f8f2; padding: 15px; margin: 10px; border-radius: 5px; font-family: monospace;">';
        var_dump($data);
        echo '</pre>';
        
        if ($die) {
            die();
        }
    }

    /**
     * Memory kullanımı
     */
    protected function getMemoryUsage()
    {
        return [
            'current' => memory_get_usage(true),
            'peak' => memory_get_peak_usage(true),
            'formatted' => [
                'current' => $this->formatBytes(memory_get_usage(true)),
                'peak' => $this->formatBytes(memory_get_peak_usage(true))
            ]
        ];
    }

    /**
     * Byte formatı
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Execution time
     */
    protected function getExecutionTime()
    {
        static $startTime = null;
        
        if ($startTime === null) {
            $startTime = $_SERVER['REQUEST_TIME_FLOAT'];
        }
        
        return microtime(true) - $startTime;
    }

    // ===========================================
    // Deprecated Methods (Geriye Uyumluluk)
    // ===========================================

    /**
     * @deprecated Use isUserLoggedIn() instead
     * Geriye uyumluluk için korundu
     */
    protected function isLoggedIn()
    {
        return $this->isUserLoggedIn();
    }

    /**
     * @deprecated Use getLoggedInUser() instead
     * Geriye uyumluluk için korundu
     */
    protected function getCurrentUser()
    {
        return $this->getLoggedInUser();
    }
}
