<?php

class AdminController extends BaseController
{
    private $userManagementService;
    private $blogService;
    private $contentService;

    public function __construct()
    {
        parent::__construct();
        $this->userManagementService = $this->service('userManagement');
        $this->blogService = $this->service('blog');
        $this->contentService = $this->service('content');
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
            'stats' => $stats
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
            $this->flash('error', 'Geçersiz CSRF token!');
            $this->redirect('/admin/users');
            return;
        }

        $currentUser = $this->getLoggedInUser();
        $result = $this->userManagementService->approveUser($userId, $currentUser['id']);

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
            $this->flash('error', 'Geçersiz CSRF token!');
            $this->redirect('/admin/users');
            return;
        }

        $currentUser = $this->getLoggedInUser();
        $result = $this->userManagementService->rejectUser($userId, $currentUser['id']);

        if ($result['success']) {
            $this->flash('success', $result['message']);
        } else {
            $this->flash('error', $result['message']);
        }

        $this->redirect('/admin/users');
    }

    /**
     * Gönderi yönetimi
     */
    public function posts()
    {
        $this->requireAdmin();

        $postModel = $this->model('BlogPost');
        $posts = $postModel->query("
            SELECT p.*, u.name as author_name, c.name as category_name 
            FROM blog_posts p 
            LEFT JOIN users u ON p.author_id = u.id 
            LEFT JOIN blog_post_categories bpc ON p.id = bpc.post_id
            LEFT JOIN blog_categories c ON bpc.category_id = c.id 
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

            $this->redirect('/admin/categories');
            return;
        }

        $categories = $this->blogService->getCategoriesWithPostCount();

        $data = [
            'page_title' => 'Kategori Yönetimi',
            'categories' => $categories
        ];

        $this->view('admin/categories', $data);
    }

    /**
     * İçerik yönetimi
     */
    public function content()
    {
        $this->requireAdmin();

        if ($this->isPost()) {
            $contentData = $this->input('content', []);
            
            if (!empty($contentData)) {
                $result = $this->contentService->updateMultipleContent($contentData);
                
                if ($result['success']) {
                    $this->flash('success', $result['message']);
                } else {
                    $this->flash('error', $result['message']);
                }
            }

            $this->redirect('/admin/content');
            return;
        }

        $allContent = $this->contentService->getAllContent();

        $data = [
            'page_title' => 'İçerik Yönetimi',
            'content' => $allContent
        ];

        $this->view('admin/content', $data);
    }

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

        if ($this->isPost()) {
            $settings = [
                'site_title' => $this->input('site_title'),
                'site_description' => $this->input('site_description'),
                'hero_title' => $this->input('hero_title'),
                'hero_subtitle' => $this->input('hero_subtitle'),
                'footer_text' => $this->input('footer_text'),
                'sidebar_about' => $this->input('sidebar_about')
            ];

            $result = $this->contentService->updateMultipleContent($settings);
            
            if ($result['success']) {
                $this->flash('success', $result['message']);
            } else {
                $this->flash('error', $result['message']);
            }

            $this->redirect('/admin/settings');
            return;
        }

        $data = [
            'page_title' => 'Site Ayarları',
            'site_title' => $this->contentService->getContent('site_title'),
            'site_description' => $this->contentService->getContent('site_description'),
            'hero_title' => $this->contentService->getContent('hero_title'),
            'hero_subtitle' => $this->contentService->getContent('hero_subtitle'),
            'footer_text' => $this->contentService->getContent('footer_text'),
            'sidebar_about' => $this->contentService->getContent('sidebar_about')
        ];

        $this->view('admin/settings', $data);
    }
}
