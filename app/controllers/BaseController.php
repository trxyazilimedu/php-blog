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
        // Blog servisleri ekle
        $this->services['blog'] = null; // Lazy load edilecek
        $this->services['user'] = null; // Lazy load edilecek  
        $this->services['content'] = null; // Lazy load edilecek
        $this->services['navigation'] = null; // Lazy load edilecek
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
        $this->addGlobalData('navigation', $this->getNavigation());
        
        // Footer içeriklerini ekle
        $contentService = $this->service('content');
        $this->addGlobalData('contentService', $contentService);
        $this->createDefaultFooterContent($contentService);
        
        // Footer navigation ekle
        try {
            $navigationService = $this->service('navigation');
            $this->addGlobalData('footerNavigation', $navigationService->getFooterNavigation(5));
        } catch (Exception $e) {
            $this->log('Footer navigation error: ' . $e->getMessage(), 'warning');
            $this->addGlobalData('footerNavigation', []);
        }

    }

    public function getNavigation()
    {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '/';
        
        try {
            $navigationService = $this->service('navigation');
            $user = $this->getLoggedInUser();
            $userRole = $user ? $user['role'] : 'all';
            
            return $navigationService->getNavigationForUser($userRole, $currentUrl);
        } catch (Exception $e) {
            // Fallback to default navigation if database not available
            $this->log('Navigation service error: ' . $e->getMessage(), 'warning');
            
            $menuItems = [];
            
            // Ana menü öğeleri
            $menuItems[] = [
                'title' => 'Anasayfa',
                'url' => '/',
                'active' => $currentUrl === '/'
            ];
            
            $menuItems[] = [
                'title' => 'Hakkında',
                'url' => '/about',
                'active' => $currentUrl === '/about'
            ];
            
            $menuItems[] = [
                'title' => 'İletişim',
                'url' => '/contact',
                'active' => $currentUrl === '/contact'
            ];
            
            // Kullanıcı durumuna göre menü öğeleri
            if ($this->isUserLoggedIn()) {
                // Admin kullanıcıları için admin panel
                if ($this->isAdmin()) {
                    $menuItems[] = [
                        'title' => 'Admin Panel',
                        'url' => '/admin',
                        'active' => strpos($currentUrl, '/admin') === 0
                    ];
                }
                
                // Blog yazma yetkisi olan kullanıcılar için
                $user = $this->getLoggedInUser();
                if ($user && ($user['role'] === 'admin' || $user['role'] === 'writer')) {
                    $menuItems[] = [
                        'title' => 'Blog Yaz',
                        'url' => '/blog/create',
                        'active' => $currentUrl === '/blog/create'
                    ];
                }
                
                $menuItems[] = [
                    'title' => 'Profilim',
                    'url' => '/profile',
                    'active' => $currentUrl === '/profile'
                ];
            }
            return $menuItems;
        }
    }

    /**
     * Kullanıcının admin olup olmadığını kontrol et
     */
    protected function isAdmin()
    {
        $user = $this->getLoggedInUser();
        return $user && isset($user['role']) && $user['role'] === 'admin';
    }

    /**
     * Kullanıcının writer olup olmadığını kontrol et
     */
    protected function isWriter()
    {
        $user = $this->getLoggedInUser();
        return $user && isset($user['role']) && in_array($user['role'], ['writer', 'admin']);
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

    /**
     * Default footer content oluştur
     */
    private function createDefaultFooterContent($contentService)
    {
        $defaults = [
            'footer_description' => 'Modern web teknolojileri, yazılım geliştirme ve dijital dünya hakkında güncel içerikler paylaşıyoruz.',
            'contact_email' => 'info@trxyazilim.com',
            'contact_phone' => '+90 (555) 605 40 22',
            'contact_address' => 'Kırşehir, Türkiye',
            'footer_copyright' => 'Tüm hakları saklıdır.'
        ];
        
        foreach ($defaults as $key => $value) {
            $existing = $contentService->getContent($key);
            if (empty($existing)) {
                $contentService->updateContent($key, $value, 'footer', 'contact');
            }
        }
    }
}
