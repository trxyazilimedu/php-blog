<?php

/**
 * Blog Category Model
 */
class BlogCategory extends Model
{
    protected $table = 'blog_categories';

    /**
     * Post sayıları ile kategorileri getir
     */
    public function getAllWithPostCounts()
    {
        $sql = "SELECT c.*, COUNT(p.id) as post_count
                FROM {$this->table} c
                LEFT JOIN blog_post_categories pc ON c.id = pc.category_id
                LEFT JOIN blog_posts p ON pc.post_id = p.id AND p.status = 'published'
                GROUP BY c.id
                ORDER BY c.name";
        
        return $this->query($sql);
    }

    /**
     * Kategori slug ile getir
     */
    public function findBySlug($slug)
    {
        $result = $this->query("SELECT * FROM {$this->table} WHERE slug = ?", [$slug]);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Post'un kategorilerini getir
     */
    public function getPostCategories($postId)
    {
        $sql = "SELECT c.* FROM {$this->table} c
                JOIN blog_post_categories pc ON c.id = pc.category_id
                WHERE pc.post_id = ?";
        
        return $this->query($sql, [$postId]);
    }
}
