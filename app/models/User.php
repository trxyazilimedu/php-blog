<?php

class User extends Model
{
    protected $table = 'users';

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function createUser($data)
    {
        // Şifreyi hash'le
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Varsayılan değerler
        $data['role'] = $data['role'] ?? 'user';
        $data['status'] = $data['status'] ?? 'active';
        
        return $this->create($data);
    }

    public function verifyPassword($email, $password)
    {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    public function getActiveUsers()
    {
        return $this->query("SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY name");
    }
    
    public function getUsersByRole($role)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE role = ? ORDER BY name", [$role]);
    }
    
    public function updateLastLogin($userId)
    {
        return $this->update($userId, [
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function changePassword($userId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($userId, ['password' => $hashedPassword]);
    }
    
    public function getUserStats()
    {
        $stats = [];
        
        // Toplam kullanıcı sayısı
        $result = $this->query("SELECT COUNT(*) as total FROM {$this->table}");
        $stats['total'] = $result[0]['total'] ?? 0;
        
        // Aktif kullanıcı sayısı
        $result = $this->query("SELECT COUNT(*) as active FROM {$this->table} WHERE status = 'active'");
        $stats['active'] = $result[0]['active'] ?? 0;
        
        // Admin sayısı
        $result = $this->query("SELECT COUNT(*) as admins FROM {$this->table} WHERE role = 'admin'");
        $stats['admins'] = $result[0]['admins'] ?? 0;
        
        // Bugün kayıt olanlar
        $result = $this->query("SELECT COUNT(*) as today FROM {$this->table} WHERE DATE(created_at) = CURDATE()");
        $stats['today'] = $result[0]['today'] ?? 0;
        
        return $stats;
    }
    
    public function searchUsers($searchTerm)
    {
        $searchTerm = '%' . $searchTerm . '%';
        return $this->query(
            "SELECT * FROM {$this->table} WHERE name LIKE ? OR email LIKE ? ORDER BY name",
            [$searchTerm, $searchTerm]
        );
    }
}
