<?php

/**
 * Framework helper sınıfı
 * BaseController'dan temel işlevleri ayırarak core katmanına taşındı
 */
class FrameworkHelper
{
    /**
     * View render etme (global verilerle birlikte)
     */
    public static function renderView($view, $data = [], $globalData = [])
    {
        // Global veriyi local veriyle birleştir
        $mergedData = array_merge($globalData, $data);
        
        // Flash mesajları ekle
        $mergedData['flash_messages'] = self::getFlashMessages();
        
        // Veriyi değişkenlere çevir
        extract($mergedData);
        
        // Layout kontrolü
        $useLayout = $data['layout'] ?? true;
        
        if ($useLayout && !isset($data['no_layout'])) {
            // Layout ile render et
            self::renderWithLayout($view, $mergedData);
        } else {
            // Sadece view'ı render et
            self::renderViewOnly($view, $mergedData);
        }
    }

    /**
     * Layout ile render etme
     */
    public static function renderWithLayout($view, $data)
    {
        extract($data);
        
        // Content'i buffer'a al
        ob_start();
        self::renderViewOnly($view, $data);
        $content = ob_get_clean();
        
        // Layout dosyasını kontrol et ve yükle
        $layoutFile = APP_PATH . '/views/layouts/main.php';
        
        if (file_exists($layoutFile)) {
            require_once $layoutFile;
        } else {
            // Layout yoksa sadece content'i göster
            echo $content;
        }
    }

    /**
     * Sadece view render etme
     */
    public static function renderViewOnly($view, $data)
    {
        extract($data);
        
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new Exception("View dosyası bulunamadı: " . $view);
        }
    }

    /**
     * Model yükleme
     */
    public static function loadModel($model)
    {
        $modelFile = APP_PATH . '/models/' . $model . '.php';
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            throw new Exception("Model dosyası bulunamadı: " . $model);
        }
    }

    /**
     * Service yükleme (lazy loading)
     */
    public static function loadService($serviceName, &$services = [])
    {
        if (!isset($services[$serviceName])) {
            switch ($serviceName) {
                case 'session':
                    require_once APP_PATH . '/services/SessionService.php';
                    $services[$serviceName] = new SessionService();
                    break;
                case 'auth':
                    require_once APP_PATH . '/services/AuthService.php';
                    $services[$serviceName] = new AuthService();
                    break;
                case 'validation':
                    require_once APP_PATH . '/services/ValidationService.php';
                    $services[$serviceName] = new ValidationService();
                    break;
                case 'flash':
                    require_once APP_PATH . '/services/FlashMessageService.php';
                    $services[$serviceName] = new FlashMessageService();
                    break;
                default:
                    throw new Exception("Service bulunamadı: " . $serviceName);
            }
        }
        
        return $services[$serviceName];
    }

    /**
     * Session başlatma
     */
    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            return true;
        }
        return false;
    }

    /**
     * CSRF token oluşturma
     */
    public static function generateCSRFToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $token = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $token;
        }
        
        return $_SESSION['csrf_token'];
    }

    /**
     * CSRF token doğrulama
     */
    public static function validateCSRFToken($token)
    {
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        return hash_equals($sessionToken, $token);
    }

    /**
     * Flash mesaj ekleme
     */
    public static function addFlashMessage($type, $message)
    {
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        
        if (!isset($_SESSION['flash_messages'][$type])) {
            $_SESSION['flash_messages'][$type] = [];
        }
        
        $_SESSION['flash_messages'][$type][] = $message;
    }

    /**
     * Flash mesajları alma
     */
    public static function getFlashMessages()
    {
        $flashMessages = $_SESSION['flash_messages'] ?? [];
        $_SESSION['flash_messages'] = [];
        return $flashMessages;
    }

    /**
     * Kullanıcı giriş kontrolü
     */
    public static function isUserLoggedIn()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Giriş yapan kullanıcı bilgisi
     */
    public static function getLoggedInUser()
    {
        if (!self::isUserLoggedIn()) {
            return null;
        }
        
        static $user = null;
        if ($user === null) {
            $userId = $_SESSION['user_id'];
            $userModel = self::loadModel('User');
            $user = $userModel->findById($userId);
        }
        
        return $user;
    }



    /**
     * Global data oluşturma
     */
    public static function buildGlobalData()
    {
        return [
            'app_name' => config('app.name', 'Simple Framework'),
            'app_version' => '1.0.0',
            'current_year' => date('Y'),
            'current_url' => $_SERVER['REQUEST_URI'] ?? '/',
            'is_logged_in' => self::isUserLoggedIn(),
            'user' => self::getLoggedInUser(),
            'navigation' => self::getNavigationItems(),
            'csrf_token' => self::generateCSRFToken()
        ];
    }

    /**
     * JSON response
     */
    public static function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Yönlendirme
     */
    public static function redirect($url, $statusCode = 302)
    {
        // Output buffer'ı temizle
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        http_response_code($statusCode);
        header('Location: ' . $url);
        exit;
    }

    /**
     * Input validation
     */
    public static function validateInput($data, $rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $ruleSet) {
            $value = $data[$field] ?? null;
            $fieldRules = is_string($ruleSet) ? explode('|', $ruleSet) : $ruleSet;
            
            foreach ($fieldRules as $rule) {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleValue = $ruleParts[1] ?? null;
                
                switch ($ruleName) {
                    case 'required':
                        if (empty($value) && $value !== '0') {
                            $errors[$field][] = "{$field} alanı zorunludur.";
                        }
                        break;
                        
                    case 'email':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = "{$field} geçerli bir e-posta adresi olmalıdır.";
                        }
                        break;
                        
                    case 'min':
                        if (!empty($value) && strlen($value) < $ruleValue) {
                            $errors[$field][] = "{$field} en az {$ruleValue} karakter olmalıdır.";
                        }
                        break;
                        
                    case 'max':
                        if (!empty($value) && strlen($value) > $ruleValue) {
                            $errors[$field][] = "{$field} en fazla {$ruleValue} karakter olmalıdır.";
                        }
                        break;
                        
                    case 'numeric':
                        if (!empty($value) && !is_numeric($value)) {
                            $errors[$field][] = "{$field} sayısal bir değer olmalıdır.";
                        }
                        break;
                        
                    case 'unique':
                        if (!empty($value)) {
                            $parts = explode(',', $ruleValue);
                            $table = $parts[0];
                            $column = $parts[1] ?? $field;
                            $exceptId = $parts[2] ?? null;
                            
                            if (self::checkUnique($table, $column, $value, $exceptId)) {
                                $errors[$field][] = "{$field} daha önce kullanılmış.";
                            }
                        }
                        break;
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Benzersizlik kontrolü
     */
    private static function checkUnique($table, $column, $value, $exceptId = null)
    {
        try {
            $db = Database::getInstance();
            
            $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
            $params = [$value];
            
            if ($exceptId) {
                $sql .= " AND id != ?";
                $params[] = $exceptId;
            }
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            
            return $result['count'] > 0;
        } catch (Exception $e) {
            // Hata durumunda false dön (validation geçsin)
            return false;
        }
    }

    /**
     * File upload helper
     */
    public static function handleFileUpload($fileInput, $uploadPath = 'uploads/')
    {
        if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Dosya yüklenemedi.'];
        }

        $file = $_FILES[$fileInput];
        $uploadDir = storage_path($uploadPath);
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = time() . '_' . basename($file['name']);
        $destination = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'success' => true,
                'filename' => $filename,
                'path' => $destination,
                'url' => '/storage/' . $uploadPath . $filename
            ];
        }

        return ['success' => false, 'message' => 'Dosya taşınamadı.'];
    }

    /**
     * Cache helper
     */
    public static function cache($key, $data = null, $ttl = 3600)
    {
        $cacheDir = storage_path('cache/');
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $cacheFile = $cacheDir . md5($key) . '.cache';

        // Veri set etme
        if ($data !== null) {
            $cacheData = [
                'data' => $data,
                'expires' => time() + $ttl
            ];
            file_put_contents($cacheFile, serialize($cacheData));
            return $data;
        }

        // Veri okuma
        if (file_exists($cacheFile)) {
            $cacheData = unserialize(file_get_contents($cacheFile));
            if ($cacheData['expires'] > time()) {
                return $cacheData['data'];
            } else {
                unlink($cacheFile); // Süresi dolmuş cache'i sil
            }
        }

        return null;
    }

    /**
     * Log helper
     */
    public static function log($message, $level = 'info', $context = [])
    {
        $logDir = storage_path('logs/');
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $logFile = $logDir . date('Y-m-d') . '.log';
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
        
        $logEntry = "[{$timestamp}] {$level}: {$message}{$contextStr}" . PHP_EOL;
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}
