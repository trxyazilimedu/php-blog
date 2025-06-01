<?php
abstract class BaseController extends Controller
{
    /**
     * Constructor - Uygulama seviyesi başlatma işlemleri
     */
    public function __construct()
    {
        // Core Controller'ı başlat (tüm temel işlevler orada)
        parent::__construct();
        
        // Uygulama seviyesi özelleştirmeler
        $this->initializeCustomServices();
        $this->setApplicationSpecificGlobals();
    }

    private function initializeCustomServices()
    {


        // $this->services['email'] = null; // Lazy load edilecek
    }

    /**
     * Uygulama seviyesi global veriler
     * Framework global verileri Core\Controller'da ayarlanıyor
     */
    private function setApplicationSpecificGlobals()
    {
        // Sadece bu uygulamaya özel global veriler
        $this->addGlobalData('app_theme', 'default');
        $this->addGlobalData('app_locale', 'tr_TR');

        $user = $this->getLoggedInUser();
        if ($user && isset($user['theme_preference'])) {
            $this->addGlobalData('app_theme', $user['theme_preference']);
        }
        $this->addGlobalData('navigation_items', $this->getNavigationItems());
        

    }

    private function getNavigationItems()
    {
        $menuItems = [];
        
        // Admin kullanıcıları için özel menü
        if ($this->isUserLoggedIn() && $this->isAdmin()) {
            $menuItems[] = [
                'title' => 'Admin Panel',
                'url' => '/admin',
                'icon' => '⚙️'
            ];
        }
        
        // Kullanıcı girişi yapmışsa özel menü
        if ($this->isUserLoggedIn()) {
            $menuItems[] = [
                'title' => 'Profilim',
                'url' => '/users/profile',
                'icon' => '👤'
            ];
        }
        
        return $menuItems;
    }




    /**
     * Kullanıcı aktivitesi kaydet
     * Bu uygulamaya özel business logic
     */
    protected function logUserActivity($action, $details = [])
    {
        if (!$this->isUserLoggedIn()) {
            return false;
        }
        
        $user = $this->getLoggedInUser();
        
        $activityData = [
            'user_id' => $user['id'],
            'action' => $action,
            'details' => json_encode($details),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Activity log tablosuna kaydet (varsa)
        try {
            // $activityModel = $this->model('UserActivity');
            // $activityModel->create($activityData);
            
            // Şimdilik log'a yaz
            $this->log('User Activity', 'info', $activityData);
            
        } catch (Exception $e) {
            $this->log('Failed to log user activity: ' . $e->getMessage(), 'error');
        }
        
        return true;
    }

    /**
     * Sistem bakım modu kontrolü
     */
    protected function checkMaintenanceMode()
    {
        $maintenanceFile = storage_path('maintenance.flag');
        
        if (file_exists($maintenanceFile)) {
            // Admin'ler bakım modunda da erişebilir
            if ($this->isUserLoggedIn() && $this->isAdmin()) {
                $this->flash('warning', 'Site bakım modunda - sadece admin erişimi aktif');
                return true;
            }
            
            // Normal kullanıcılar için bakım sayfası
            $this->setStatusCode(503);
            $this->view('errors/maintenance', [
                'title' => 'Site Bakımda',
                'message' => 'Sitemiz şu anda bakım çalışması nedeniyle kapalıdır. Lütfen daha sonra tekrar deneyin.'
            ]);
            exit;
        }
        
        return true;
    }

    // ===========================================
    // Deprecated Methods (Geriye Uyumluluk)
    // ===========================================

    /**
     * @deprecated Core\Controller'da tanımlı
     * Geriye uyumluluk için korundu
     */
    protected function isLoggedIn()
    {
        return $this->isUserLoggedIn();
    }

    /**
     * @deprecated Core\Controller'da tanımlı  
     * Geriye uyumluluk için korundu
     */
    protected function getCurrentUser()
    {
        return $this->getLoggedInUser();
    }
}
