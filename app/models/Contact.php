<?php

class Contact extends Model
{
    protected $table = 'contact_messages';

    /**
     * İletişim mesajı oluştur
     */
    public function createMessage($data)
    {
        // Verileri temizle ve IP bilgilerini ekle
        $cleanData = [
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'subject' => isset($data['subject']) ? trim($data['subject']) : null,
            'message' => trim($data['message']),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'status' => 'new'
        ];
        
        return $this->create($cleanData);
    }

    /**
     * Tüm mesajları getir (sayfalama ile)
     */
    public function getAllMessages($limit = 20, $offset = 0, $status = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if ($status) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->query($sql, $params);
    }

    /**
     * Mesaj sayısını getir
     */
    public function getMessagesCount($status = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $params = [];
        
        if ($status) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
        }
        
        $result = $this->query($sql, $params);
        return $result[0]['count'] ?? 0;
    }

    /**
     * Son mesajları getir
     */
    public function getRecentMessages($limit = 10)
    {
        return $this->query(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ?", 
            [$limit]
        );
    }

    /**
     * Email'e göre mesajları getir
     */
    public function getMessagesByEmail($email)
    {
        return $this->query(
            "SELECT * FROM {$this->table} WHERE email = ? ORDER BY created_at DESC", 
            [$email]
        );
    }

    /**
     * Mesaj durumunu güncelle
     */
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    /**
     * Okunmamış mesaj sayısı
     */
    public function getUnreadCount()
    {
        return $this->getMessagesCount('new');
    }

    /**
     * Durum istatistikleri
     */
    public function getStatusStats()
    {
        $sql = "SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status";
        $results = $this->query($sql);
        
        $stats = [
            'new' => 0,
            'read' => 0,
            'replied' => 0,
            'total' => 0
        ];
        
        foreach ($results as $result) {
            $stats[$result['status']] = (int)$result['count'];
            $stats['total'] += (int)$result['count'];
        }
        
        return $stats;
    }

    /**
     * ID ile mesaj getir
     */
    public function getMessageById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->query($sql, [$id]);
        return $result[0] ?? null;
    }

    /**
     * Mesajı sil
     */
    public function deleteMessage($id)
    {
        return $this->delete($id);
    }

    /**
     * Toplu durum güncelleme
     */
    public function bulkUpdateStatus($ids, $status)
    {
        if (empty($ids)) {
            return false;
        }
        
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "UPDATE {$this->table} SET status = ? WHERE id IN ({$placeholders})";
        
        $params = [$status];
        $params = array_merge($params, $ids);
        
        return $this->execute($sql, $params);
    }
}
