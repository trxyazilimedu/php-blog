<?php

/**
 * Blog Comment Model
 */
class BlogComment extends Model
{
    protected $table = 'blog_comments';

    /**
     * Post'un onaylı yorumlarını getir
     */
    public function getApprovedComments($postId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE post_id = ? AND status = 'approved' 
                ORDER BY created_at DESC";
        
        return $this->query($sql, [$postId]);
    }

    /**
     * Onay bekleyen yorumları getir
     */
    public function getPendingComments()
    {
        $sql = "SELECT c.*, p.title as post_title
                FROM {$this->table} c
                LEFT JOIN blog_posts p ON c.post_id = p.id
                WHERE c.status = 'pending'
                ORDER BY c.created_at DESC";
        
        return $this->query($sql);
    }

    /**
     * Yorum durumunu güncelle
     */
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    /**
     * Post'un yorum sayısını getir
     */
    public function getCommentCount($postId)
    {
        $result = $this->query("SELECT COUNT(*) as count FROM {$this->table} WHERE post_id = ? AND status = 'approved'", [$postId]);
        return $result[0]['count'] ?? 0;
    }
}
