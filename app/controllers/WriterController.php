<?php

/**
 * Writer Controller
 * Onaylı kullanıcıların blog yazması için
 */
class WriterController extends BaseController
{
    private $blogService;
    private $userService;

    public function __construct()
    {
        parent::__construct();
        $this->blogService = $this->service('blog');
        $this->userService = $this->service('user');
        
        // Yazma yetkin var mı kontrol et
        $this->requireWritePermission();
    }

    /**
     * Yazar dashboard'u
     */
    public function dashboard()
    {
        $user = $this->getLoggedInUser();
        
        // Kullanıcının post'larını getir
        $userPosts = $this->blogService->getUserPosts($user['id']);
        
        // İstatistikler
        $stats = [
            'total_posts' => count($userPosts),
            'published_posts' => count(array_filter($userPosts, fn($p) => $p['status'] === 'published')),
            'draft_posts' => count(array_filter($userPosts, fn($p) => $p['status'] === 'draft')),
            'total_views' => array_sum(array_column($userPosts, 'views'))
        ];
        
        $data = [
            'page_title' => 'Yazar Paneli',
            'user_posts' => $userPosts,
            'stats' => $stats
        ];
        
        $this->view('writer/dashboard', $data);
    }

    /**
     * Yeni post oluştur
     */
    public function create()
    {
        if ($this->isPost()) {
            $this->verifyCsrfOrFail();
            
            $postData = $this->only('title', 'content', 'excerpt', 'status', 'categories');
            $postData['author_id'] = $this->getLoggedInUser()['id'];
            
            $rules = [
                'title' => 'required|min:3|max:255',
                'content' => 'required|min:50',
                'status' => 'required|in:draft,published'
            ];

            if (!$this->validateOrRedirect($postData, $rules, '/writer/create')) {
                return;
            }

            $result = $this->blogService->createPost($postData);

            if ($result['success']) {
                $this->redirectWithSuccess('/writer/dashboard', $result['message']);
            } else {
                $this->redirectWithError('/writer/create', $result['message']);
            }
            return;
        }

        $data = [
            'page_title' => 'Yeni Post',
            'categories' => $this->blogService->getAllCategories()
        ];

        $this->view('writer/create', $data);
    }

    /**
     * Post düzenle
     */
    public function edit($id)
    {
        $postModel = $this->model('BlogPost');
        $post = $postModel->findById($id);
        
        if (!$post) {
            $this->redirectWithError('/writer/dashboard', 'Post bulunamadı!');
            return;
        }

        // Kullanıcı bu post'u düzenleyebilir mi?
        $user = $this->getLoggedInUser();
        if (!$this->userService->canUserEditPost($user['id'], $post['author_id'])) {
            $this->redirectWithError('/writer/dashboard', 'Bu post\'u düzenleme yetkiniz yok!');
            return;
        }

        if ($this->isPost()) {
            $this->verifyCsrfOrFail();
            
            $postData = $this->only('title', 'content', 'excerpt', 'status', 'categories');
            
            $rules = [
                'title' => 'required|min:3|max:255',
                'content' => 'required|min:50',
                'status' => 'required|in:draft,published'
            ];

            if (!$this->validateOrRedirect($postData, $rules, '/writer/edit/' . $id)) {
                return;
            }

            $result = $this->blogService->updatePost($id, $postData);

            if ($result['success']) {
                $this->redirectWithSuccess('/writer/dashboard', $result['message']);
            } else {
                $this->redirectWithError('/writer/edit/' . $id, $result['message']);
            }
            return;
        }

        // Post kategorilerini getir
        $post['categories'] = $this->blogService->getPostCategories($id);

        $data = [
            'page_title' => 'Post Düzenle',
            'post' => $post,
            'categories' => $this->blogService->getAllCategories()
        ];

        $this->view('writer/edit', $data);
    }

    /**
     * Post sil
     */
    public function delete($id)
    {
        $this->verifyCsrfOrFail();
        
        $postModel = $this->model('BlogPost');
        $post = $postModel->findById($id);
        
        if (!$post) {
            $this->redirectWithError('/writer/dashboard', 'Post bulunamadı!');
            return;
        }

        // Kullanıcı bu post'u silebilir mi?
        $user = $this->getLoggedInUser();
        if (!$this->userService->canUserEditPost($user['id'], $post['author_id'])) {
            $this->redirectWithError('/writer/dashboard', 'Bu post\'u silme yetkiniz yok!');
            return;
        }

        $result = $this->blogService->deletePost($id);

        if ($result['success']) {
            $this->redirectWithSuccess('/writer/dashboard', $result['message']);
        } else {
            $this->redirectWithError('/writer/dashboard', $result['message']);
        }
    }

    // ===========================================
    // Yardımcı Metodlar
    // ===========================================

    /**
     * Yazma yetkisi kontrolü
     */
    private function requireWritePermission()
    {
        $this->requireAuth();
        
        $user = $this->getLoggedInUser();
        
        if (!$this->userService->canUserWriteBlog($user['id'])) {
            if ($user['status'] === 'pending') {
                $this->redirectWithWarning('/', 'Hesabınız henüz onaylanmamış. Blog yazabilmek için admin onayı beklemeniz gerekiyor.');
            } else {
                $this->redirectWithError('/', 'Blog yazma yetkiniz bulunmuyor.');
            }
            exit;
        }
    }
}
