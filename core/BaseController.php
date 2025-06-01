<?php

abstract class BaseController
{
    protected $db;
    protected $globalData = [];
    protected $services = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->initializeGlobalData();
        $this->initializeServices();
    }

    /**
     * Global veriyi başlat (tüm view'larda kullanılacak veriler)
     */
    protected function initializeGlobalData()
    {
        $this->globalData = [
            'app_name' => 'Simple Framework',
            'app_version' => '1.0.0',
            'current_year' => date('Y'),
            'current_url' => $_SERVER['REQUEST_URI'] ?? '/',
            'is_logged_in' => $this->isUserLoggedIn(),
            'user' => $this->getLoggedInUser(),
            'navigation' => $this->getNavigationItems(),
            'csrf_token' => $this->generateCSRFToken()
        ];
    }

    /**
     * Servisleri başlat
     */
    protected function initializeServices()
    {
        $this->services = [
            'auth' => new AuthService(),
            'session' => new SessionService(),
            'validation' => new ValidationService(),
            'flash' => new FlashMessageService()
        ];
    }

    /**
     * View render etme (global verilerle birlikte)
     */
    protected function view($view, $data = [])
    {
        // Global veriyi local veriyle birleştir
        $mergedData = array_merge($this->globalData, $data);
        
        // Flash mesajları ekle
        $mergedData['flash_messages'] = $this->services['flash']->getAll();
        
        // Veriyi değişkenlere çevir
        extract($mergedData);
        
        // Layout kontrolü
        $useLayout = $data['layout'] ?? true;
        
        if ($useLayout && !isset($data['no_layout'])) {
            // Layout ile render et
            $this->renderWithLayout($view, $mergedData);
        } else {
            // Sadece view'ı render et
            $this->renderView($view, $mergedData);
        }
    }

    /**
     * Layout ile render etme
     */
    protected function renderWithLayout($view, $data)
    {
        extract($data);
        
        // Content'i buffer'a al
        ob_start();
        $this->renderView($view, $data);
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
    protected function renderView($view, $data)
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
    protected function model($model)
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
     * Service'e erişim
     */
    protected function service($serviceName)
    {
        if (isset($this->services[$serviceName])) {
            return $this->services[$serviceName];
        }
        
        throw new Exception("Service bulunamadı: " . $serviceName);
    }

    /**
     * Yönlendirme
     */
    protected function redirect($url, $statusCode = 302)
    {
        http_response_code($statusCode);
        header('Location: ' . $url);
        exit;
    }

    /**
     * JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
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
     * Kullanıcı giriş kontrolü
     */
    protected function isUserLoggedIn()
    {
        return $this->service('session')->has('user_id');
    }

    /**
     * Giriş yapan kullanıcı bilgisi
     */
    protected function getLoggedInUser()
    {
        if (!$this->isUserLoggedIn()) {
            return null;
        }
        
        $userId = $this->service('session')->get('user_id');
        $userModel = $this->model('User');
        return $userModel->findById($userId);
    }

    /**
     * Navigasyon menüsü
     */
    protected function getNavigationItems()
    {
        return [
            ['url' => '/', 'title' => 'Ana Sayfa', 'active' => $this->isCurrentUrl('/')],
            ['url' => '/about', 'title' => 'Hakkında', 'active' => $this->isCurrentUrl('/about')],
            ['url' => '/contact', 'title' => 'İletişim', 'active' => $this->isCurrentUrl('/contact')],
            ['url' => '/users', 'title' => 'Kullanıcılar', 'active' => $this->isCurrentUrl('/users')]
        ];
    }

    /**
     * Mevcut URL kontrolü
     */
    protected function isCurrentUrl($url)
    {
        $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $currentUrl === $url;
    }

    /**
     * CSRF token oluşturma
     */
    protected function generateCSRFToken()
    {
        if (!$this->service('session')->has('csrf_token')) {
            $token = bin2hex(random_bytes(32));
            $this->service('session')->set('csrf_token', $token);
        }
        
        return $this->service('session')->get('csrf_token');
    }

    /**
     * CSRF token doğrulama
     */
    protected function validateCSRFToken($token)
    {
        $sessionToken = $this->service('session')->get('csrf_token');
        return hash_equals($sessionToken, $token);
    }

    /**
     * Flash mesaj ekleme
     */
    protected function flash($type, $message)
    {
        $this->service('flash')->add($type, $message);
    }

    /**
     * Yetki kontrolü
     */
    protected function requireAuth()
    {
        if (!$this->isUserLoggedIn()) {
            $this->flash('error', 'Bu sayfaya erişmek için giriş yapmanız gerekiyor.');
            $this->redirect('/login');
        }
    }

    /**
     * Admin yetkisi kontrolü
     */
    protected function requireAdmin()
    {
        $this->requireAuth();
        
        $user = $this->getLoggedInUser();
        if (!$user || $user['role'] !== 'admin') {
            $this->flash('error', 'Bu sayfaya erişim yetkiniz bulunmuyor.');
            $this->redirect('/');
        }
    }
}
