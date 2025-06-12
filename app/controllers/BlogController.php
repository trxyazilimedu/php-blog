<?php

class BlogController extends BaseController
{
    private $blogService;
    private $contentService;

    public function __construct()
    {
        parent::__construct();
        $this->blogService = $this->service('blog');
        $this->contentService = $this->service('content');
    }

    /**
     * Blog ana sayfası
     */
    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $posts = $this->blogService->getHomePosts($page, 6);
        
        // Default içerikleri oluştur (eğer yoksa)
        $this->createDefaultBlogContent();
        
        $data = [
            'page_title' => $this->contentService->getContent('site_title', 'Modern Blog'),
            'posts' => $posts,
            'popular_posts' => $this->blogService->getPopularPosts(5),
            'recent_posts' => $this->blogService->getRecentPosts(5),
            'categories' => $this->blogService->getCategoriesWithPostCount(),
            'contentService' => $this->contentService,
            'current_page' => $page,
            'navigation' => $this->getNavigation()
        ];

        $this->view('blog/index', $data);
    }

    /**
     * Gönderi detay sayfası
     */
    public function show($slug)
    {
        $post = $this->blogService->getPostDetail($slug);
        
        if (!$post) {
            $this->flash('error', 'Gönderi bulunamadı!');
            $this->redirect('/blog');
            return;
        }

        $data = [
            'page_title' => $post['title'],
            'post' => $post,
            'popular_posts' => $this->blogService->getPopularPosts(5),
            'recent_posts' => $this->blogService->getRecentPosts(5),
            'categories' => $this->blogService->getCategoriesWithPostCount(),
            'csrf_token' => $this->generateCSRFToken()
        ];

        $this->view('blog/show', $data);
    }

    /**
     * Kategori sayfası
     */
    public function category($slug)
    {
        $result = $this->blogService->getPostsByCategory($slug);
        
        if (!$result) {
            $this->flash('error', 'Kategori bulunamadı!');
            $this->redirect('/blog');
            return;
        }

        $data = [
            'page_title' => $result['category']['name'] . ' Kategorisi',
            'category' => $result['category'],
            'posts' => $result['posts'],
            'popular_posts' => $this->blogService->getPopularPosts(5),
            'recent_posts' => $this->blogService->getRecentPosts(5),
            'categories' => $this->blogService->getCategoriesWithPostCount()
        ];

        $this->view('blog/category', $data);
    }

    /**
     * Arama sayfası
     */
    public function search()
    {
        $query = $_GET['q'] ?? '';
        $posts = [];
        
        if (!empty($query)) {
            $posts = $this->blogService->searchPosts($query, 20);
        }

        $data = [
            'page_title' => 'Arama Sonuçları',
            'query' => $query,
            'posts' => $posts,
            'popular_posts' => $this->blogService->getPopularPosts(5),
            'recent_posts' => $this->blogService->getRecentPosts(5),
            'categories' => $this->blogService->getCategoriesWithPostCount()
        ];

        $this->view('blog/search', $data);
    }

    /**
     * Yazı oluşturma sayfası
     */
    public function create()
    {
        // Kullanıcı giriş yapmış ve blog yazma yetkisi var mı?
        $this->requireAuth();
        
        $userService = $this->service('userManagement');
        $currentUser = $this->getLoggedInUser();
        
        if (!$userService->canUserWriteBlog($currentUser['id'])) {
            $this->flash('error', 'Blog yazma yetkiniz bulunmuyor. Admin onayı bekleniyor.');
            $this->redirect('/blog');
            return;
        }

        if ($this->isPost()) {
            $postData = [
                'title' => $this->input('title'),
                'content' => $this->input('content'),
                'excerpt' => $this->input('excerpt'),
                'categories' => $this->input('categories', []),
                'status' => $this->input('status', 'draft')
            ];


            // Handle featured image upload
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleImageUpload($_FILES['featured_image']);
                if ($uploadResult['success']) {
                    $postData['featured_image'] = $uploadResult['path'];
                } else {
                    $this->flash('error', 'Görsel yükleme hatası: ' . $uploadResult['error']);
                }
            }

            $result = $this->blogService->createPost($postData, $currentUser['id']);
            
            if ($result['success']) {
                $this->flash('success', $result['message']);
                $this->redirect('/blog/my-posts');
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
            'page_title' => 'Yeni Gönderi',
            'categories' => $this->blogService->getCategoriesWithPostCount()
        ];

        $this->view('blog/create', $data);
    }

    /**
     * Yazı düzenleme sayfası
     */
    public function edit($id)
    {
        $this->requireAuth();
        
        $postModel = $this->model('BlogPost');
        $post = $postModel->findById($id);
        
        if (!$post) {
            $this->flash('error', 'Gönderi bulunamadı!');
            $this->redirect('/blog/my-posts');
            return;
        }

        $currentUser = $this->getLoggedInUser();
        $isAdmin = $currentUser['role'] === 'admin';
        
        // Yetki kontrolü
        if (!$isAdmin && $post['author_id'] != $currentUser['id']) {
            $this->flash('error', 'Bu gönderiyi düzenleme yetkiniz yok!');
            $this->redirect('/blog/my-posts');
            return;
        }

        if ($this->isPost()) {
            $postData = [
                'title' => $this->input('title'),
                'content' => $this->input('content'),
                'excerpt' => $this->input('excerpt'),
                'categories' => $this->input('categories', []),
                'status' => $this->input('status', 'draft')
            ];


            // Handle featured image upload
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleImageUpload($_FILES['featured_image']);
                if ($uploadResult['success']) {
                    $postData['featured_image'] = $uploadResult['path'];
                } else {
                    $this->flash('error', 'Görsel yükleme hatası: ' . $uploadResult['error']);
                }
            }

            $result = $this->blogService->updatePost($id, $postData, $currentUser['id'], $isAdmin);
            
            if ($result['success']) {
                $this->flash('success', $result['message']);
                $this->redirect('/blog/post/' . $post['slug']);
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

        // Get post categories for editing
        $postCategories = $this->model('BlogPost')->query(
            "SELECT c.* FROM blog_categories c 
             JOIN blog_post_categories pc ON c.id = pc.category_id 
             WHERE pc.post_id = ?", 
            [$id]
        );

        $data = [
            'page_title' => 'Gönderi Düzenle',
            'post' => $post,
            'postCategories' => $postCategories,
            'categories' => $this->blogService->getCategoriesWithPostCount()
        ];

        $this->view('blog/edit', $data);
    }

    /**
     * Kullanıcının kendi gönderileri
     */
    public function myPosts()
    {
        $this->requireAuth();
        
        $currentUser = $this->getLoggedInUser();
        $postModel = $this->model('BlogPost');
        
        $posts = $postModel->getAllWithCategories();
        
        // Sadece bu kullanıcının postlarını filtrele
        $userPosts = array_filter($posts, function($post) use ($currentUser) {
            return $post['author_id'] == $currentUser['id'];
        });

        $stats = $this->blogService->getUserStats($currentUser['id']);

        $data = [
            'page_title' => 'Gönderilerim',
            'posts' => $userPosts,
            'stats' => $stats
        ];

        $this->view('blog/my-posts', $data);
    }

    /**
     * Gönderi silme
     */
    public function delete($id)
    {
        $this->requireAuth();
        
        if (!$this->validateCSRFToken($this->input('csrf_token'))) {
            $this->flash('error', 'Geçersiz CSRF token!');
            $this->redirect('/blog/my-posts');
            return;
        }

        $currentUser = $this->getLoggedInUser();
        $isAdmin = $currentUser['role'] === 'admin';
        
        $result = $this->blogService->deletePost($id, $currentUser['id'], $isAdmin);
        
        if ($result['success']) {
            $this->flash('success', $result['message']);
        } else {
            $this->flash('error', $result['message']);
        }
        
        $this->redirect('/blog/my-posts');
    }

    /**
     * Blog yazısına yorum ekleme
     */
    public function addComment($slug)
    {
        // POST request kontrolü
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/blog/post/' . $slug);
            return;
        }

        // CSRF token kontrolü (eğer varsa)
        if (isset($_POST['csrf_token']) && !$this->validateCSRFToken($_POST['csrf_token'])) {
            $this->flash('error', 'Geçersiz güvenlik token!');
            $this->redirect('/blog/post/' . $slug);
            return;
        }

        // Form verilerini al
        $name = trim($this->input('name', ''));
        $email = trim($this->input('email', ''));
        $website = trim($this->input('website', ''));
        $comment = trim($this->input('comment', ''));

        // Validasyon
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Ad soyad alanı zorunludur.';
        }
        
        if (empty($email)) {
            $errors[] = 'E-posta alanı zorunludur.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Geçerli bir e-posta adresi girin.';
        }
        
        if (empty($comment)) {
            $errors[] = 'Yorum alanı zorunludur.';
        }
        
        if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
            $errors[] = 'Geçerli bir website adresi girin.';
        }

        // Blog yazısını bul
        $blogPost = $this->model('BlogPost');
        $post = $blogPost->getBySlug($slug);
        
        if (!$post) {
            $this->flash('error', 'Blog yazısı bulunamadı!');
            $this->redirect('/blog');
            return;
        }

        // Hata varsa geri dön
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->flash('error', $error);
            }
            $this->redirect('/blog/post/' . $slug);
            return;
        }

        // Yorumu kaydet
        try {
            $commentModel = $this->model('BlogComment');
            
            $commentData = [
                'post_id' => $post['id'],
                'name' => $name,
                'email' => $email,
                'website' => $website ?: null,
                'content' => $comment,
                'status' => 'pending', // Moderasyon için
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $result = $commentModel->create($commentData);
            
            if ($result) {
                $this->flash('success', 'Yorumunuz başarıyla gönderildi! Moderatör onayından sonra yayınlanacaktır.');
            } else {
                $this->flash('error', 'Yorum gönderilirken bir hata oluştu. Lütfen tekrar deneyin.');
            }
            
        } catch (Exception $e) {
            $this->flash('error', 'Yorum gönderilirken bir hata oluştu: ' . $e->getMessage());
        }

        $this->redirect('/blog/post/' . $slug);
    }

    /**
     * Image upload handler
     */
    private function handleImageUpload($file)
    {
        // Validate file
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'error' => 'Desteklenmeyen dosya türü. Sadece JPG, PNG, GIF ve WebP dosyaları yükleyebilirsiniz.'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'Dosya boyutu çok büyük. Maksimum 2MB olmalıdır.'];
        }
        
        // Create uploads directory if it doesn't exist
        $uploadDir = __DIR__ . '/../../public/uploads/blog/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('blog_') . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;
        $publicPath = '/uploads/blog/' . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => true, 'path' => $publicPath];
        } else {
            return ['success' => false, 'error' => 'Dosya yüklenirken bir hata oluştu.'];
        }
    }

    /**
     * Default blog page content oluştur
     */
    private function createDefaultBlogContent()
    {
        $defaults = [
            'hero_title' => 'Teknoloji Dünyasına Hoş Geldiniz',
            'hero_subtitle' => 'Yazılım, teknoloji trendleri ve dijital dünya hakkında kaliteli içerikler keşfedin',
            'latest_posts_title' => 'Son Blog Yazıları',
            'no_posts_title' => 'Henüz blog yazısı yok',
            'no_posts_description' => 'İlk blog yazısını yazmak için aşağıdaki butona tıklayın.',
            'categories_widget_title' => 'Kategoriler',
            'popular_posts_title' => 'Popüler Yazılar',
            'newsletter_title' => 'Bültenimize Abone Olun',
            'newsletter_description' => 'Yeni yazılarımızdan haberdar olmak için e-posta adresinizi bırakın.',
            'about_widget_title' => 'Hakkımızda',
            'about_widget_content' => 'Teknoloji dünyasındaki en son gelişmeleri takip ediyor, yazılım geliştirme süreçleri hakkında içerikler üretiyoruz.'
        ];
        
        foreach ($defaults as $key => $value) {
            $existing = $this->contentService->getContent($key);
            if (empty($existing)) {
                $this->contentService->updateContent($key, $value, 'blog', 'main');
            }
        }
    }

}
