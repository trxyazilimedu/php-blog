<?php

/**
 * Blog Post Model
 */
class BlogPost extends Model
{
    protected $table = 'blog_posts';

    /**
     * Tüm post'ları kategorileri ile getir
     */
    public function getAllWithCategories()
    {
        $sql = "SELECT p.*, u.name as author_name,
                       GROUP_CONCAT(c.name) as category_names
                FROM {$this->table} p 
                LEFT JOIN users u ON p.author_id = u.id
                LEFT JOIN blog_post_categories pc ON p.id = pc.post_id
                LEFT JOIN blog_categories c ON pc.category_id = c.id
                GROUP BY p.id
                ORDER BY p.created_at DESC";
        
        return $this->query($sql);
    }

    /**
     * Published post'ları getir
     */
    public function getPublished($limit = null)
    {
        $sql = "SELECT p.*, u.name as author_name, u.avatar as author_avatar
                FROM {$this->table} p 
                LEFT JOIN users u ON p.author_id = u.id
                WHERE p.status = 'published' AND p.published_at <= NOW()
                ORDER BY p.published_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        return $this->query($sql);
    }

    /**
     * Slug ile post getir
     */
    public function findBySlug($slug)
    {
        $sql = "SELECT p.*, u.name as author_name, u.bio as author_bio, u.avatar as author_avatar
                FROM {$this->table} p 
                LEFT JOIN users u ON p.author_id = u.id
                WHERE p.slug = ? AND p.status = 'published'";
        
        $result = $this->query($sql, [$slug]);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Benzer post'ları getir
     */
    public function getSimilarPosts($postId, $limit = 3)
    {
        $sql = "SELECT DISTINCT p.*, u.name as author_name
                FROM {$this->table} p
                LEFT JOIN users u ON p.author_id = u.id
                LEFT JOIN blog_post_categories pc1 ON p.id = pc1.post_id
                WHERE p.id != ? AND p.status = 'published'
                AND pc1.category_id IN (
                    SELECT pc2.category_id 
                    FROM blog_post_categories pc2 
                    WHERE pc2.post_id = ?
                )
                ORDER BY p.published_at DESC
                LIMIT ?";
        
        return $this->query($sql, [$postId, $postId, $limit]);
    }

    /**
     * Popüler post'ları getir
     */
    public function getPopular($limit = 5)
    {
        $sql = "SELECT p.*, u.name as author_name
                FROM {$this->table} p 
                LEFT JOIN users u ON p.author_id = u.id
                WHERE p.status = 'published'
                ORDER BY p.views DESC, p.published_at DESC
                LIMIT ?";
        
        return $this->query($sql, [$limit]);
    }

    /**
     * Post view sayısını artır
     */
    public function incrementViews($id)
    {
        return $this->execute("UPDATE {$this->table} SET views = views + 1 WHERE id = ?", [$id]);
    }

    /**
     * Son blog yazılarını getir
     */
    public function getRecentPosts($limit = 5)
    {
        $sql = "SELECT p.*, u.name as author_name, u.avatar as author_avatar
                FROM {$this->table} p 
                LEFT JOIN users u ON p.author_id = u.id
                WHERE p.status = 'published'
                ORDER BY p.published_at DESC
                LIMIT ?";
        
        return $this->query($sql, [$limit]);
    }

    /**
     * Arama yap
     */
    public function searchPosts($query, $limit = 20)
    {
        $searchTerm = '%' . $query . '%';
        $sql = "SELECT p.*, u.name as author_name, u.avatar as author_avatar
                FROM {$this->table} p 
                LEFT JOIN users u ON p.author_id = u.id
                WHERE (p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?) 
                    AND p.status = 'published'
                ORDER BY p.published_at DESC
                LIMIT ?";
        
        return $this->query($sql, [$searchTerm, $searchTerm, $searchTerm, $limit]);
    }

    /**
     * Popüler blog yazılarını getir (backward compatibility)
     */
    public function getPopularPosts($limit = 5)
    {
        return $this->getPopular($limit);
    }
}
