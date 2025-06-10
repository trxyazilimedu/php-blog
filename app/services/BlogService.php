<?php

class BlogService
{
    private $postModel;
    private $categoryModel;
    private $userModel;

    public function __construct()
    {
        $this->postModel = new BlogPost();
        $this->categoryModel = new BlogCategory();
        $this->userModel = new User();
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
            // Görüntülenme sayısını artır
            $this->postModel->incrementViews($post['id']);
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
        return $this->postModel->getPopularPosts($limit);
    }

    /**
     * Son gönderileri getir
     */
    public function getRecentPosts($limit = 5)
    {
        return $this->postModel->getRecentPosts($limit);
    }

    /**
     * Kategorileri post sayısıyla getir
     */
    public function getCategoriesWithPostCount()
    {
        return $this->categoryModel->getAllWithPostCounts();
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

        if ($this->categoryModel->update($id, $data)) {
            return ['success' => true, 'message' => 'Kategori başarıyla güncellendi!'];
        }

        return ['success' => false, 'message' => 'Kategori güncellenirken bir hata oluştu.'];
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

        if (isset($data['categories']) && !empty($data['categories'])) {
            foreach ($data['categories'] as $categoryId) {
                $category = $this->categoryModel->findById($categoryId);
                if (!$category) {
                    $errors['categories'] = 'Geçersiz kategori seçimi.';
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
}
