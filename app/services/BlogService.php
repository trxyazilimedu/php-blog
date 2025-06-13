<?php

class BlogService
{
    private $postModel;
    private $categoryModel;
    private $userModel;
    private $cacheService;

    public function __construct()
    {
        $this->postModel = new BlogPost();
        $this->categoryModel = new BlogCategory();
        $this->userModel = new User();
        $this->cacheService = new CacheService();
    }

    /**
     * Ana sayfa için gönderileri getir
     */
    public function getHomePosts($page = 1, $perPage = 6)
    {
        $limit = $perPage;
        return $this->postModel->getPublished($limit);
    }

    /**
     * Kategori gönderilerini getir
     */
    public function getPostsByCategory($categorySlug, $limit = 10)
    {
        $category = $this->categoryModel->findBySlug($categorySlug);
        if (!$category) {
            return false;
        }

        // Blog post kategorilerinde many-to-many ilişki olduğu için farklı sorgu
        $posts = $this->postModel->query(
            "SELECT DISTINCT p.*, u.name as author_name
             FROM blog_posts p
             LEFT JOIN users u ON p.author_id = u.id
             LEFT JOIN blog_post_categories pc ON p.id = pc.post_id
             WHERE pc.category_id = ? AND p.status = 'published'
             ORDER BY p.published_at DESC
             LIMIT ?",
            [$category['id'], $limit]
        );

        return [
            'category' => $category,
            'posts' => $posts
        ];
    }

    /**
     * Gönderi detayını getir
     */
    public function getPostDetail($slug)
    {
        $post = $this->postModel->findBySlug($slug);
        
        if ($post && $post['status'] === 'published') {
            // IP-based visit tracking
            $this->trackVisit($post['id']);
            
            // Post kategorilerini getir
            $post['categories'] = $this->getPostCategories($post['id']);
            
            return $post;
        }

        return false;
    }

    /**
     * Arama yap
     */
    public function searchPosts($query, $limit = 20)
    {
        if (strlen(trim($query)) < 2) {
            return [];
        }

        return $this->postModel->searchPosts($query, $limit);
    }

    /**
     * Popüler gönderileri getir
     */
    public function getPopularPosts($limit = 5)
    {
        $cacheKey = "popular_posts_{$limit}";
        
        // Cache'den veri çek
        $cachedData = $this->cacheService->getFileCache($cacheKey);
        if ($cachedData !== null) {
            return $cachedData;
        }
        
        $posts = $this->postModel->getPopularPosts($limit);
        
        // Cache'le (1 saat)
        $this->cacheService->setFileCache($cacheKey, $posts, 3600);
        
        return $posts;
    }

    /**
     * Son gönderileri getir
     */
    public function getRecentPosts($limit = 5)
    {
        $cacheKey = "recent_posts_{$limit}";
        
        // Cache'den veri çek
        $cachedData = $this->cacheService->getFileCache($cacheKey);
        if ($cachedData !== null) {
            return $cachedData;
        }
        
        $posts = $this->postModel->getRecentPosts($limit);
        
        // Cache'le (30 dakika)
        $this->cacheService->setFileCache($cacheKey, $posts, 1800);
        
        return $posts;
    }

    /**
     * Kategorileri post sayısıyla getir
     */
    public function getCategoriesWithPostCount()
    {
        $cacheKey = "categories_with_post_count";
        
        // Cache'den veri çek
        $cachedData = $this->cacheService->getFileCache($cacheKey);
        if ($cachedData !== null) {
            return $cachedData;
        }
        
        $categories = $this->categoryModel->getAllWithPostCounts();
        
        // Cache'le (2 saat)
        $this->cacheService->setFileCache($cacheKey, $categories, 7200);
        
        return $categories;
    }

    /**
     * Yeni gönderi oluştur
     */
    public function createPost($data, $authorId)
    {
        $data['author_id'] = $authorId;
        
        // Validation
        $errors = $this->validatePostData($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Slug oluştur
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }

        // Kategorileri ayrı işle
        $categories = $data['categories'] ?? [];
        unset($data['categories']);
        
        // Yayınlanma tarihi ayarla
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        $postId = $this->postModel->create($data);
        
        if ($postId) {
            // Kategorileri bağla
            if (!empty($categories)) {
                $this->attachCategoriesToPost($postId, $categories);
            }
            
            return ['success' => true, 'message' => 'Gönderi başarıyla oluşturuldu!'];
        }

        return ['success' => false, 'message' => 'Gönderi oluşturulurken bir hata oluştu.'];
    }

    /**
     * Gönderi güncelle
     */
    public function updatePost($id, $data, $userId, $isAdmin = false)
    {
        $post = $this->postModel->findById($id);
        
        if (!$post) {
            return ['success' => false, 'message' => 'Gönderi bulunamadı.'];
        }

        // Yetki kontrolü
        if (!$isAdmin && $post['author_id'] != $userId) {
            return ['success' => false, 'message' => 'Bu gönderiyi düzenleme yetkiniz yok.'];
        }

        // Validation
        $errors = $this->validatePostData($data, $id);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Slug güncelle (eğer başlık değişmişse)
        if (empty($data['slug']) || ($data['title'] !== $post['title'] && empty($data['slug']))) {
            $data['slug'] = $this->generateSlug($data['title'], $id);
        }

        // Kategorileri ayrı işle
        $categories = $data['categories'] ?? [];
        unset($data['categories']);
        
        // Yayınlanma tarihi kontrol et
        if ($data['status'] === 'published' && empty($post['published_at'])) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        if ($this->postModel->update($id, $data)) {
            // Kategorileri güncelle
            $this->updatePostCategories($id, $categories);
            
            return ['success' => true, 'message' => 'Gönderi başarıyla güncellendi!'];
        }

        return ['success' => false, 'message' => 'Gönderi güncellenirken bir hata oluştu.'];
    }

    /**
     * Gönderi sil
     */
    public function deletePost($id, $userId, $isAdmin = false)
    {
        $post = $this->postModel->findById($id);
        
        if (!$post) {
            return ['success' => false, 'message' => 'Gönderi bulunamadı.'];
        }

        // Yetki kontrolü
        if (!$isAdmin && $post['author_id'] != $userId) {
            return ['success' => false, 'message' => 'Bu gönderiyi silme yetkiniz yok.'];
        }

        if ($this->postModel->delete($id)) {
            return ['success' => true, 'message' => 'Gönderi başarıyla silindi!'];
        }

        return ['success' => false, 'message' => 'Gönderi silinirken bir hata oluştu.'];
    }

    /**
     * Kategori oluştur
     */
    public function createCategory($data)
    {
        // Validation
        $errors = $this->validateCategoryData($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Slug oluştur
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateCategorySlug($data['name']);
        }

        if ($this->categoryModel->create($data)) {
            return ['success' => true, 'message' => 'Kategori başarıyla oluşturuldu!'];
        }

        return ['success' => false, 'message' => 'Kategori oluşturulurken bir hata oluştu.'];
    }

    /**
     * Kategori güncelle
     */
    public function updateCategory($id, $data)
    {
        // Validation
        $errors = $this->validateCategoryData($data, $id);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Slug kontrolü ve güncelleme
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateCategorySlug($data['name'], $id);
        }

        if ($this->categoryModel->update($id, $data)) {
            return ['success' => true, 'message' => 'Kategori başarıyla güncellendi!'];
        }

        return ['success' => false, 'message' => 'Kategori güncellenirken bir hata oluştu.'];
    }

    /**
     * Kategori sil
     */
    public function deleteCategory($id)
    {
        $category = $this->categoryModel->findById($id);
        
        if (!$category) {
            return ['success' => false, 'message' => 'Kategori bulunamadı.'];
        }

        // Bu kategoriye ait yazı var mı kontrol et
        $postCount = $this->postModel->query(
            "SELECT COUNT(*) as count FROM blog_post_categories WHERE category_id = ?",
            [$id]
        );
        
        if (($postCount[0]['count'] ?? 0) > 0) {
            // Kategoriye ait yazıları genel kategoriye taşı (id: 1 olduğunu varsayıyoruz)
            $this->postModel->query(
                "UPDATE blog_post_categories SET category_id = 1 WHERE category_id = ?",
                [$id]
            );
        }

        if ($this->categoryModel->delete($id)) {
            return ['success' => true, 'message' => 'Kategori başarıyla silindi!'];
        }

        return ['success' => false, 'message' => 'Kategori silinirken bir hata oluştu.'];
    }

    /**
     * Post validation
     */
    private function validatePostData($data, $excludeId = null)
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'Başlık zorunludur.';
        } elseif (strlen($data['title']) < 3) {
            $errors['title'] = 'Başlık en az 3 karakter olmalıdır.';
        } elseif (strlen($data['title']) > 255) {
            $errors['title'] = 'Başlık en fazla 255 karakter olmalıdır.';
        }

        if (empty($data['content'])) {
            $errors['content'] = 'İçerik zorunludur.';
        } elseif (strlen($data['content']) < 10) {
            $errors['content'] = 'İçerik en az 10 karakter olmalıdır.';
        }

        if (isset($data['categories']) && !empty($data['categories']) && is_array($data['categories'])) {
            foreach ($data['categories'] as $categoryId) {
                // Skip empty values
                if (empty($categoryId)) continue;
                
                $category = $this->categoryModel->findById($categoryId);
                if (!$category) {
                    $errors['categories'] = 'Geçersiz kategori seçimi: ' . $categoryId;
                    break;
                }
            }
        }

        if (isset($data['status']) && !in_array($data['status'], ['draft', 'published', 'archived'])) {
            $errors['status'] = 'Geçersiz durum seçimi.';
        }

        return $errors;
    }

    /**
     * Slug oluştur
     */
    private function generateSlug($title, $excludeId = null)
    {
        // Türkçe karakterleri değiştir
        $slug = str_replace(
            ['ş', 'ğ', 'ü', 'ç', 'ı', 'ö', 'Ş', 'Ğ', 'Ü', 'Ç', 'İ', 'Ö'],
            ['s', 'g', 'u', 'c', 'i', 'o', 'S', 'G', 'U', 'C', 'I', 'O'],
            $title
        );
        
        // Küçük harfe çevir, özel karakterleri kaldır
        $slug = strtolower(trim($slug));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Boşsa default değer
        if (empty($slug)) {
            $slug = 'post-' . time();
        }
        
        // Benzersizlik kontrolü
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Slug var mı kontrol et
     */
    private function slugExists($slug, $excludeId = null)
    {
        $sql = "SELECT id FROM blog_posts WHERE slug = ?";
        $params = [$slug];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->postModel->query($sql, $params);
        return !empty($result);
    }

    /**
     * Category validation
     */
    private function validateCategoryData($data, $excludeId = null)
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Kategori adı zorunludur.';
        } elseif (strlen($data['name']) < 2) {
            $errors['name'] = 'Kategori adı en az 2 karakter olmalıdır.';
        } elseif (strlen($data['name']) > 100) {
            $errors['name'] = 'Kategori adı en fazla 100 karakter olmalıdır.';
        }

        if (isset($data['color']) && !empty($data['color'])) {
            if (!preg_match('/^#[a-fA-F0-9]{6}$/', $data['color'])) {
                $errors['color'] = 'Geçersiz renk kodu.';
            }
        }

        return $errors;
    }

    /**
     * Post'a kategorileri bağla
     */
    private function attachCategoriesToPost($postId, $categoryIds)
    {
        if (empty($categoryIds)) {
            return;
        }

        foreach ($categoryIds as $categoryId) {
            $this->postModel->execute(
                "INSERT IGNORE INTO blog_post_categories (post_id, category_id) VALUES (?, ?)",
                [$postId, $categoryId]
            );
        }
    }

    /**
     * Post kategorilerini güncelle
     */
    private function updatePostCategories($postId, $categoryIds)
    {
        // Mevcut kategorileri sil
        $this->postModel->execute(
            "DELETE FROM blog_post_categories WHERE post_id = ?",
            [$postId]
        );

        // Yeni kategorileri ekle
        $this->attachCategoriesToPost($postId, $categoryIds);
    }

    /**
     * Post kategorilerini getir
     */
    private function getPostCategories($postId)
    {
        return $this->postModel->query(
            "SELECT c.* FROM blog_categories c 
             JOIN blog_post_categories pc ON c.id = pc.category_id 
             WHERE pc.post_id = ?
             ORDER BY c.name",
            [$postId]
        );
    }

    /**
     * IP-based visit tracking
     */
    private function trackVisit($postId)
    {
        $userIP = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $sessionId = session_id();
        
        // Check if this IP/session already viewed this post in last 24 hours
        $existingView = $this->postModel->query(
            "SELECT id FROM blog_post_views 
             WHERE post_id = ? AND (ip_address = ? OR session_id = ?) 
             AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)",
            [$postId, $userIP, $sessionId]
        );
        
        if (empty($existingView)) {
            // Record new view
            $this->postModel->execute(
                "INSERT INTO blog_post_views (post_id, ip_address, session_id, user_agent, created_at) 
                 VALUES (?, ?, ?, ?, NOW())",
                [$postId, $userIP, $sessionId, $userAgent]
            );
            
            // Increment view counter
            $this->postModel->incrementViews($postId);
        }
    }

    /**
     * Kullanıcı blog istatistikleri
     */
    public function getUserStats($userId)
    {
        $stats = $this->postModel->query(
            "SELECT 
                COUNT(*) as total_posts,
                SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published_posts,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft_posts,
                SUM(views) as total_views
            FROM blog_posts WHERE author_id = ?",
            [$userId]
        );

        return $stats[0] ?? [
            'total_posts' => 0,
            'published_posts' => 0,
            'draft_posts' => 0,
            'total_views' => 0
        ];
    }

    /**
     * Kategori slug oluştur
     */
    private function generateCategorySlug($name, $excludeId = null)
    {
        // Türkçe karakterleri değiştir
        $slug = str_replace(
            ['ş', 'ğ', 'ü', 'ç', 'ı', 'ö', 'Ş', 'Ğ', 'Ü', 'Ç', 'İ', 'Ö'],
            ['s', 'g', 'u', 'c', 'i', 'o', 'S', 'G', 'U', 'C', 'I', 'O'],
            $name
        );
        
        // Küçük harfe çevir, özel karakterleri kaldır
        $slug = strtolower(trim($slug));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Boşsa default değer
        if (empty($slug)) {
            $slug = 'kategori-' . time();
        }
        
        // Benzersizlik kontrolü
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->categorySlugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Kategori slug var mı kontrol et
     */
    private function categorySlugExists($slug, $excludeId = null)
    {
        $sql = "SELECT id FROM blog_categories WHERE slug = ?";
        $params = [$slug];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->categoryModel->query($sql, $params);
        return !empty($result);
    }
}
