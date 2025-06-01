<?php

class Contact extends Model
{
    protected $table = 'contact_messages';

    public function createMessage($data)
    {
        // Verileri temizle
        $cleanData = [
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'message' => trim($data['message'])
        ];
        
        return $this->create($cleanData);
    }

    public function getRecentMessages($limit = 10)
    {
        return $this->query(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ?", 
            [$limit]
        );
    }

    public function getMessagesByEmail($email)
    {
        return $this->query(
            "SELECT * FROM {$this->table} WHERE email = ? ORDER BY created_at DESC", 
            [$email]
        );
    }

    public function getMessagesCount()
    {
        $result = $this->query("SELECT COUNT(*) as total FROM {$this->table}");
        return $result[0]['total'] ?? 0;
    }
}
