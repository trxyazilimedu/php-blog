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
        
        $data = [
            'page_title' => $this->contentService->getContent('site_title', 'Modern Blog'),
            'posts' => $posts,
            'popular_posts' => $this->blogService->getPopularPosts(5),
            'recent_posts' => $this->blogService->getRecentPosts(5),
            'categories' => $this->blogService->getCategoriesWithPostCount(),
            'hero_title' => $this->contentService->getContent('hero_title', 'Hoş Geldiniz'),
            'hero_subtitle' => $this->contentService->getContent('hero_subtitle', 'En güncel yazıları keşfedin'),
            'sidebar_about' => $this->contentService->getContent('sidebar_about', 'Bu blogda en kaliteli içerikleri bulabilirsiniz.'),
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
            'categories' => $this->blogService->getCategoriesWithPostCount()
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
                'category_id' => $this->input('category_id'),
                'status' => $this->input('status', 'draft')
            ];

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
                'category_id' => $this->input('category_id'),
                'status' => $this->input('status', 'draft')
            ];

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

        $data = [
            'page_title' => 'Gönderi Düzenle',
            'post' => $post,
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


}
