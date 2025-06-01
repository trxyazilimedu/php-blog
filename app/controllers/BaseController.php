<?php

/**
 * BaseController - Uygulama Seviyesi Controller
 * 
 * Bu sınıf, Core\Controller'dan extend eder.
 * Tüm temel işlevler artık Core\Controller'da bulunuyor.
 * 
 * Bu sınıf sadece uygulamaya özel özelleştirmeler için kullanılır.
 * Tüm uygulama controller'ları bu sınıftan extend edilmelidir.
 */
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

    // ===========================================
    // Application-Specific Initialization
    // ===========================================

    /**
     * Özel uygulama servislerini başlatma
     * Burada sadece bu uygulamaya özel servisler tanımlanır
     */
    private function initializeCustomServices()
    {
        // Örnek: Özel servisler
        // $this->emailService = new EmailService();
        // $this->paymentService = new PaymentService();
        
        // Lazy loading için services array'ine eklenebilir
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
        
        // Örnek: Kullanıcı tercihlerine göre tema
        $user = $this->getLoggedInUser();
        if ($user && isset($user['theme_preference'])) {
            $this->addGlobalData('app_theme', $user['theme_preference']);
        }
        
        // Örnek: Özel navigasyon menüsü
        $this->addGlobalData('custom_menu_items', $this->getCustomMenuItems());
    }

    // ===========================================
    // Application-Specific Helper Methods
    // ===========================================

    /**
     * Özel menü öğelerini al
     * Bu uygulamaya özel navigasyon öğeleri
     */
    private function getCustomMenuItems()
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




    // ===========================================
    // Business Logic Helpers (Uygulama Özel)
    // ===========================================

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
