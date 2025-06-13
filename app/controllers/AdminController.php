<?php

class AdminController extends BaseController
{
    private $userManagementService;
    private $blogService;
    private $contentService;
    private $navigationService;
    private $cacheService;

    public function __construct()
    {
        parent::__construct();
        $this->userManagementService = $this->service('userManagement');
        $this->blogService = $this->service('blog');
        $this->contentService = $this->service('content');
        $this->navigationService = $this->service('navigation');
        $this->cacheService = new CacheService();
    }

    /**
     * Admin panel ana sayfası
     */
    public function index()
    {
        $this->requireAdmin();

        // İstatistikler
        $userStats = $this->userManagementService->getUserStatistics();
        $postModel = $this->model('BlogPost');
        
        $postStats = $postModel->query("
            SELECT 
                COUNT(*) as total_posts,
                SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published_posts,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft_posts,
                SUM(views) as total_views
            FROM blog_posts
        ");
        
        $categoryStats = $this->model('BlogCategory')->query("SELECT COUNT(*) as total FROM blog_categories");

        $data = [
            'page_title' => 'Admin Panel',
            'stats' => [
                'users' => $userStats,
                'posts' => $postStats[0] ?? [],
                'comments' => ['pending' => 0]
            ],
            'recentPosts' => $this->blogService->getRecentPosts(5),
            'pendingComments' => [],
            'recentUsers' => $this->userManagementService->getPendingUsers()
        ];

        $this->view('admin/index', $data);
    }

    /**
     * Kullanıcı yönetimi
     */
    public function users()
    {
        $this->requireAdmin();

        $userModel = $this->model('User');
        $users = $userModel->findAll();
        $pendingUsers = $this->userManagementService->getPendingUsers();
        $stats = $this->userManagementService->getUserStatistics();

        $data = [
            'page_title' => 'Kullanıcı Yönetimi',
            'users' => $users,
            'pending_users' => $pendingUsers,
            'stats' => $stats,
            'csrf_token' => $this->generateCSRFToken()
        ];

        $this->view('admin/users', $data);
    }

    /**
     * Kullanıcı onaylama
     */
    public function approveUser($userId)
    {
        $this->requireAdmin();

        if (!$this->validateCSRFToken($this->input('csrf_token'))) {
            // If AJAX request, return JSON
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Geçersiz CSRF token!']);
                return;
            }
            
            $this->flash('error', 'Geçersiz CSRF token!');
            $this->redirect('/admin/users');
            return;
        }

        $currentUser = $this->getLoggedInUser();
        $result = $this->userManagementService->approveUser($userId, $currentUser['id']);

        // If AJAX request, return JSON
        if ($this->isAjax()) {
            $this->json($result);
            return;
        }

        if ($result['success']) {
            $this->flash('success', $result['message']);
        } else {
            $this->flash('error', $result['message']);
        }

        $this->redirect('/admin/users');
    }

    /**
     * Kullanıcı reddetme
     */
    public function rejectUser($userId)
    {
        $this->requireAdmin();

        if (!$this->validateCSRFToken($this->input('csrf_token'))) {
            // If AJAX request, return JSON
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Geçersiz CSRF token!']);
                return;
            }
            
            $this->flash('error', 'Geçersiz CSRF token!');
            $this->redirect('/admin/users');
            return;
        }

        $currentUser = $this->getLoggedInUser();
        $result = $this->userManagementService->rejectUser($userId, $currentUser['id']);

        // If AJAX request, return JSON
        if ($this->isAjax()) {
            $this->json($result);
            return;
        }

        if ($result['success']) {
            $this->flash('success', $result['message']);
        } else {
            $this->flash('error', $result['message']);
        }

        $this->redirect('/admin/users');
    }

    /**
     * Kullanıcı oluşturma sayfası
     */
    public function createUser()
    {
        $this->requireAdmin();

        if ($this->isPost()) {
            // CSRF token kontrolü
            if (!$this->validateCSRFToken($this->input('csrf_token'))) {
                $this->flash('error', 'Geçersiz CSRF token!');
                $this->redirect('/admin/users/create');
                return;
            }

            $userData = [
                'name' => $this->input('name'),
                'email' => $this->input('email'),
                'password' => $this->input('password'),
                'password_confirm' => $this->input('password_confirm'),
                'role' => $this->input('role', 'user'),
                'bio' => $this->input('bio', ''),
                'status' => 'active'
            ];

            $currentUser = $this->getLoggedInUser();
            $result = $this->userManagementService->createUser($userData, $currentUser['id']);

            if ($result['success']) {
                $this->flash('success', $result['message']);
                $this->redirect('/admin/users');
            } else {
                if (isset($result['errors'])) {
                    foreach ($result['errors'] as $field => $error) {
                        $this->flash('error', $error);
                    }
                } else {
                    $this->flash('error', $result['message']);
                }
            }
        }

        $data = [
            'page_title' => 'Yeni Kullanıcı Oluştur',
            'csrf_token' => $this->generateCSRFToken()
        ];

        $this->view('admin/user-create', $data);
    }

    /**
     * Kullanıcı detayları sayfası
     */
    public function userDetail($userId)
    {
        $this->requireAdmin();

        $userModel = $this->model('User');
        $user = $userModel->findById($userId);

        if (!$user) {
            $this->flash('error', 'Kullanıcı bulunamadı!');
            $this->redirect('/admin/users');
            return;
        }

        // Kullanıcının blog yazıları
        $postModel = $this->model('BlogPost');
        $userPosts = $postModel->query(
            "SELECT * FROM blog_posts WHERE author_id = ? ORDER BY created_at DESC LIMIT 10",
            [$userId]
        );

        // Kullanıcının yorumları (eğer user tablosunda email ile eşleşirse)
        $commentModel = $this->model('BlogComment');
        $userComments = $commentModel->query(
            "SELECT bc.*, bp.title as post_title 
             FROM blog_comments bc 
             LEFT JOIN blog_posts bp ON bc.post_id = bp.id 
             WHERE bc.email = ? 
             ORDER BY bc.created_at DESC LIMIT 10",
            [$user['email']]
        );

        $data = [
            'page_title' => 'Kullanıcı Detayları - ' . $user['name'],
            'user' => $user,
            'user_posts' => $userPosts,
            'user_comments' => $userComments
        ];

        $this->view('admin/user-detail', $data);
    }

    /**
     * Kullanıcı düzenleme sayfası
     */
    public function editUser($userId)
    {
        $this->requireAdmin();

        $userModel = $this->model('User');
        $user = $userModel->findById($userId);

        if (!$user) {
            $this->flash('error', 'Kullanıcı bulunamadı!');
            $this->redirect('/admin/users');
            return;
        }

        if ($this->isPost()) {
            // CSRF token kontrolü
            if (!$this->validateCSRFToken($this->input('csrf_token'))) {
                $this->flash('error', 'Geçersiz CSRF token!');
                $this->redirect('/admin/users/edit/' . $userId);
                return;
            }

            $updateData = [
                'name' => $this->input('name'),
                'email' => $this->input('email'),
                'role' => $this->input('role'),
                'bio' => $this->input('bio', ''),
                'status' => $this->input('status')
            ];

            // Şifre değişikliği varsa
            if (!empty($this->input('password'))) {
                if ($this->input('password') !== $this->input('password_confirm')) {
                    $this->flash('error', 'Şifreler eşleşmiyor!');
                    $this->redirect('/admin/users/edit/' . $userId);
                    return;
                }
                $updateData['password'] = password_hash($this->input('password'), PASSWORD_DEFAULT);
            }

            if ($userModel->update($userId, $updateData)) {
                $this->flash('success', 'Kullanıcı başarıyla güncellendi!');
                $this->redirect('/admin/users/' . $userId);
            } else {
                $this->flash('error', 'Kullanıcı güncellenirken bir hata oluştu!');
            }
        }

        $data = [
            'page_title' => 'Kullanıcı Düzenle - ' . $user['name'],
            'user' => $user,
            'csrf_token' => $this->generateCSRFToken()
        ];

        $this->view('admin/user-edit', $data);
    }

    /**
     * Kullanıcı durumu değiştirme
     */
    public function changeUserStatus($userId)
    {
        $this->requireAdmin();

        if (!$this->validateCSRFToken($this->input('csrf_token'))) {
            $this->flash('error', 'Geçersiz CSRF token!');
            $this->redirect('/admin/users/' . $userId);
            return;
        }

        $status = $this->input('status');
        $allowedStatuses = ['active', 'inactive', 'pending'];
        
        if (!in_array($status, $allowedStatuses)) {
            $this->flash('error', 'Geçersiz durum!');
            $this->redirect('/admin/users/' . $userId);
            return;
        }

        $userModel = $this->model('User');
        $user = $userModel->findById($userId);

        if (!$user) {
            $this->flash('error', 'Kullanıcı bulunamadı!');
            $this->redirect('/admin/users');
            return;
        }

        if ($userModel->update($userId, ['status' => $status])) {
            $statusText = [
                'active' => 'aktif',
                'inactive' => 'deaktif',
                'pending' => 'beklemede'
            ];
            $this->flash('success', 'Kullanıcı durumu "' . $statusText[$status] . '" olarak güncellendi!');
        } else {
            $this->flash('error', 'Kullanıcı durumu güncellenirken bir hata oluştu!');
        }

        $this->redirect('/admin/users/' . $userId);
    }

    /**
     * Gönderi yönetimi
     */
    public function posts()
    {
        $this->requireAdmin();

        $postModel = $this->model('BlogPost');
        $posts = $postModel->query("
            SELECT p.*, u.name as author_name, 
                   GROUP_CONCAT(DISTINCT c.name ORDER BY c.name SEPARATOR ', ') as category_names,
                   COUNT(DISTINCT c.id) as category_count
            FROM blog_posts p 
            LEFT JOIN users u ON p.author_id = u.id 
            LEFT JOIN blog_post_categories bpc ON p.id = bpc.post_id
            LEFT JOIN blog_categories c ON bpc.category_id = c.id 
            GROUP BY p.id, u.name
            ORDER BY p.created_at DESC
        ");

        $data = [
            'page_title' => 'Gönderi Yönetimi',
            'posts' => $posts
        ];

        $this->view('admin/posts', $data);
    }

    /**
     * Kategori yönetimi
     */
    public function categories()
    {
        $this->requireAdmin();

        if ($this->isPost()) {
            $action = $this->input('action', 'create');
            
            if ($action === 'edit') {
                // Kategori düzenleme
                $categoryId = $this->input('id');
                $categoryData = [
                    'name' => $this->input('name'),
                    'description' => $this->input('description'),
                    'color' => $this->input('color', '#667eea')
                ];

                $result = $this->blogService->updateCategory($categoryId, $categoryData);
                
                if ($result['success']) {
                    $this->flash('success', $result['message']);
                } else {
                    if (isset($result['errors'])) {
                        foreach ($result['errors'] as $field => $error) {
                            $this->flash('error', $error);
                        }
                    } else {
                        $this->flash('error', $result['message']);
                    }
                }
            } else {
                // Kategori oluşturma
                $categoryData = [
                    'name' => $this->input('name'),
                    'description' => $this->input('description'),
                    'color' => $this->input('color', '#667eea')
                ];

                $result = $this->blogService->createCategory($categoryData);
                
                if ($result['success']) {
                    $this->flash('success', $result['message']);
                } else {
                    if (isset($result['errors'])) {
                        foreach ($result['errors'] as $field => $error) {
                            $this->flash('error', $error);
                        }
                    } else {
                        $this->flash('error', $result['message']);
                    }
                }
            }

            $this->redirect('/admin/categories');
            return;
        }

        $categories = $this->blogService->getCategoriesWithPostCount();

        $data = [
            'page_title' => 'Kategori Yönetimi',
            'categories' => $categories,
            'csrf_token' => $this->generateCSRFToken()
        ];

        $this->view('admin/categories', $data);
    }

    /**
     * Kategori silme
     */
    public function deleteCategory($categoryId)
    {
        $this->requireAdmin();

        if (!$this->validateCSRFToken($this->input('csrf_token'))) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Geçersiz CSRF token!']);
                return;
            }
            $this->flash('error', 'Geçersiz CSRF token!');
            $this->redirect('/admin/categories');
            return;
        }

        $result = $this->blogService->deleteCategory($categoryId);
        
        if ($this->isAjax()) {
            $this->json($result);
            return;
        }
        
        if ($result['success']) {
            $this->flash('success', $result['message']);
        } else {
            $this->flash('error', $result['message']);
        }
        
        $this->redirect('/admin/categories');
    }

    /**
     * İçerik yönetimi
     */

    /**
     * Düzenleme modunu aç/kapat
     */
    public function toggleEditMode()
    {
        $this->requireAdmin();

        $result = $this->contentService->toggleEditMode();
        $this->json($result);
    }

    /**
     * AJAX ile içerik güncelleme
     */
    public function updateContent()
    {
        $this->requireAdmin();

        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Geçersiz istek']);
            return;
        }

        // CSRF token validation
        if (!$this->validateCSRFToken($this->input('csrf_token'))) {
            $this->json(['success' => false, 'message' => 'Geçersiz CSRF token']);
            return;
        }

        $key = $this->input('key');
        $value = $this->input('value');

        if (empty($key)) {
            $this->json(['success' => false, 'message' => 'İçerik anahtarı gerekli']);
            return;
        }

        $currentUser = $this->getLoggedInUser();
        $result = $this->contentService->updateContent($key, $value, 'general', null, 'html', $currentUser['id']);

        if ($result['success']) {
            $this->json(['success' => true, 'message' => 'İçerik güncellendi']);
        } else {
            $this->json(['success' => false, 'message' => $result['message']]);
        }
    }

    /**
     * Site ayarları
     */
    public function settings()
    {
        $this->requireAdmin();

        $settingsService = new SettingsService();

        if ($this->isPost()) {
            if (!$this->validateCSRFToken($this->input('csrf_token'))) {
                $this->flash('error', 'Güvenlik hatası!');
                $this->redirect('/admin/settings');
                return;
            }

            $settings = [
                // Genel Ayarlar
                'site_title' => $this->input('site_title'),
                'site_tagline' => $this->input('site_tagline'),
                'site_description' => $this->input('site_description'),
                
                // SMTP Ayarları
                'smtp_host' => $this->input('smtp_host'),
                'smtp_port' => $this->input('smtp_port'),
                'smtp_username' => $this->input('smtp_username'),
                'smtp_password' => $this->input('smtp_password'),
                'smtp_encryption' => $this->input('smtp_encryption'),
                'smtp_from_name' => $this->input('smtp_from_name'),
                
                // Sistem Ayarları
                'timezone' => $this->input('timezone'),
                'date_format' => $this->input('date_format'),
                'upload_max_size' => $this->input('upload_max_size'),
                'posts_per_page' => $this->input('posts_per_page'),
                'maintenance_mode' => $this->input('maintenance_mode', '0'),
                
                // SEO Ayarları
                'meta_keywords' => $this->input('meta_keywords'),
                'google_analytics' => $this->input('google_analytics'),
                'google_search_console' => $this->input('google_search_console'),
                
                // Sosyal Medya
                'twitter_url' => $this->input('twitter_url'),
                'linkedin_url' => $this->input('linkedin_url'),
                'github_url' => $this->input('github_url'),
                'youtube_url' => $this->input('youtube_url')
            ];

            if ($settingsService->updateSettings($settings)) {
                // Site ayarları güncellendiğinde cache'i temizle
                $this->cacheService->smartClearCache('site_settings');
                
                $this->flash('success', 'Site ayarları başarıyla güncellendi!');
            } else {
                $this->flash('error', 'Ayarlar güncellenirken bir hata oluştu!');
            }

            $this->redirect('/admin/settings');
            return;
        }

        // Mevcut ayarları getir
        $allSettings = $settingsService->getAllSettings();

        $data = array_merge([
            'page_title' => 'Site Ayarları',
            'csrf_token' => $this->generateCSRFToken()
        ], $allSettings);

        $this->view('admin/settings', $data);
    }

    /**
     * Cache yönetimi
     */
    public function cacheManagement()
    {
        $this->requireAdmin();

        if ($this->isPost()) {
            $action = $this->input('action');
            
            if (!$this->validateCSRFToken($this->input('csrf_token'))) {
                $this->flash('error', 'Güvenlik hatası!');
                $this->redirect('/admin/cache-management');
                return;
            }

            switch ($action) {
                case 'clear_opcache':
                    $result = $this->cacheService->clearOpcache();
                    break;
                case 'clear_file_cache':
                    $result = $this->cacheService->clearFileCache();
                    break;
                case 'clear_blog_cache':
                    $result = $this->cacheService->smartClearCache('blog_post');
                    break;
                case 'clear_admin_cache':
                    $result = $this->cacheService->smartClearCache('user_management');
                    break;
                case 'cleanup_expired':
                    $result = $this->cacheService->cleanupExpiredCache();
                    break;
                default:
                    $result = ['success' => false, 'message' => 'Geçersiz işlem'];
            }

            if ($result['success']) {
                $this->flash('success', $result['message']);
            } else {
                $this->flash('error', $result['message']);
            }

            $this->redirect('/admin/cache-management');
            return;
        }

        $data = [
            'page_title' => 'Cache Yönetimi',
            'cache_stats' => $this->cacheService->getCacheStats(),
            'csrf_token' => $this->generateCSRFToken()
        ];

        $this->view('admin/cache-management', $data);
    }

    /**
     * SMTP test
     */
    public function testSmtp()
    {
        $this->requireAdmin();

        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Geçersiz istek']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$this->validateCSRFToken($input['csrf_token'])) {
            $this->json(['success' => false, 'message' => 'Güvenlik hatası']);
            return;
        }

        $mailService = new MailService();
        
        // Test için geçici SMTP ayarları
        $testSettings = [
            'host' => $input['smtp_host'],
            'port' => (int)$input['smtp_port'],
            'username' => $input['smtp_username'],
            'password' => $input['smtp_password'],
            'encryption' => $input['smtp_encryption'],
            'from_name' => $input['smtp_from_name']
        ];

        // Önce bağlantı testini yap
        $connectionResult = $mailService->testConnection($testSettings);
        
        if (!$connectionResult['success']) {
            $this->json($connectionResult);
            return;
        }

        // Bağlantı başarılıysa test e-postası göndermeyi teklif et
        $inputData = json_decode(file_get_contents('php://input'), true);
        $testEmailOption = $inputData['send_test_email'] ?? 'false';
        
        if ($testEmailOption === 'true') {
            // Test e-postası gönder
            $mailService->updateSmtpSettings($testSettings);
            $emailResult = $mailService->sendTestEmail($testSettings['username']);
            
            if ($emailResult['success']) {
                $this->json([
                    'success' => true,
                    'message' => 'SMTP bağlantısı başarılı! Test e-postası gönderildi: ' . $testSettings['username']
                ]);
            } else {
                $this->json([
                    'success' => false,
                    'message' => 'SMTP bağlantısı başarılı ancak e-posta gönderilemedi: ' . $emailResult['message']
                ]);
            }
        } else {
            // Sadece bağlantı testi sonucu döndür
            $this->json([
                'success' => true,
                'message' => $connectionResult['message'] . ' Test e-postası göndermek için butona tekrar tıklayın.'
            ]);
        }
    }

    /**
     * Navigation güncelleme
     */
    public function updateNavigation()
    {
        $this->requireAdmin();

        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Geçersiz istek']);
            return;
        }

        if (!$this->validateCSRFToken($this->input('csrf_token'))) {
            $this->json(['success' => false, 'message' => 'Güvenlik hatası']);
            return;
        }

        $id = $this->input('id');
        $title = $this->input('title');
        $url = $this->input('url');

        if (empty($id) || empty($title) || empty($url)) {
            $this->json(['success' => false, 'message' => 'Tüm alanlar gereklidir']);
            return;
        }

        $data = [
            'title' => $title,
            'url' => $url
        ];

        $result = $this->navigationService->updateMenuItem($id, $data);

        if ($result) {
            $this->json(['success' => true, 'message' => 'Menü güncellendi']);
        } else {
            $this->json(['success' => false, 'message' => 'Güncelleme başarısız']);
        }
    }

    /**
     * Navigation öğesi oluştur
     */
    public function createNavigation()
    {
        $this->requireAdmin();

        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Geçersiz istek']);
            return;
        }

        if (!$this->validateCSRFToken($this->input('csrf_token'))) {
            $this->json(['success' => false, 'message' => 'Güvenlik hatası']);
            return;
        }

        $data = [
            'title' => $this->input('title'),
            'url' => $this->input('url'),
            'icon' => $this->input('icon'),
            'parent_id' => $this->input('parent_id') ?: null,
            'permission_role' => $this->input('permission_role', 'all'),
            'target' => $this->input('target', '_self')
        ];

        $result = $this->navigationService->createMenuItem($data);

        if ($result) {
            $this->json(['success' => true, 'message' => 'Menü öğesi başarıyla oluşturuldu']);
        } else {
            $this->json(['success' => false, 'message' => 'Menü öğesi oluşturulamadı']);
        }
    }

    /**
     * Navigation öğesi sil
     */
    public function deleteNavigation($id)
    {
        $this->requireAdmin();

        if (!$this->validateCSRFToken($this->input('csrf_token'))) {
            $this->json(['success' => false, 'message' => 'Güvenlik hatası']);
            return;
        }

        $result = $this->navigationService->deleteMenuItem($id);

        if ($result) {
            $this->json(['success' => true, 'message' => 'Menü öğesi silindi']);
        } else {
            $this->json(['success' => false, 'message' => 'Silme başarısız']);
        }
    }
}
