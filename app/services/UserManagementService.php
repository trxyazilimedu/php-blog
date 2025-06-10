<?php

class UserManagementService
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Kullanıcı kayıt işlemi (admin onayına düşer)
     */
    public function registerUser($userData)
    {
        // E-posta kontrolü
        if ($this->userModel->findByEmail($userData['email'])) {
            return [
                'success' => false,
                'message' => 'Bu e-posta adresi zaten kullanılıyor!'
            ];
        }

        // Şifreyi hash'le
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        $userData['role'] = $userData['role'] ?? 'user'; // Role kontrolü
        
        // Writer ise admin onayı bekler, user ise direkt aktif
        if (($userData['role'] ?? 'user') === 'writer') {
            $userData['status'] = 'pending'; // Admin onayı bekliyor
        } else {
            $userData['status'] = 'active'; // Direkt aktif
        }

        if ($this->userModel->create($userData)) {
            $message = 'Kayıt başarılı!';
            if ($userData['status'] === 'pending') {
                $message .= ' Admin onayı bekleniyor. Onaylandıktan sonra blog yazabilirsiniz.';
            } else {
                $message .= ' Artık giriş yapabilirsiniz.';
            }
            
            return [
                'success' => true,
                'message' => $message
            ];
        }

        return [
            'success' => false,
            'message' => 'Kayıt sırasında bir hata oluştu!'
        ];
    }

    /**
     * Kullanıcıları onayla
     */
    public function approveUser($userId, $adminId)
    {
        $user = $this->userModel->findById($userId);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Kullanıcı bulunamadı!'];
        }

        if ($user['status'] === 'active') {
            return ['success' => false, 'message' => 'Kullanıcı zaten onaylanmış!'];
        }

        $updateData = [
            'status' => 'active'
        ];

        if ($this->userModel->update($userId, $updateData)) {
            return [
                'success' => true,
                'message' => 'Kullanıcı başarıyla onaylandı!'
            ];
        }

        return ['success' => false, 'message' => 'Kullanıcı onaylanırken bir hata oluştu!'];
    }

    /**
     * Kullanıcıyı reddet
     */
    public function rejectUser($userId, $adminId)
    {
        $user = $this->userModel->findById($userId);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Kullanıcı bulunamadı!'];
        }

        $updateData = [
            'status' => 'inactive'
        ];

        if ($this->userModel->update($userId, $updateData)) {
            return [
                'success' => true,
                'message' => 'Kullanıcı reddedildi!'
            ];
        }

        return ['success' => false, 'message' => 'Kullanıcı reddedilirken bir hata oluştu!'];
    }

    /**
     * Onay bekleyen kullanıcıları getir
     */
    public function getPendingUsers()
    {
        return $this->userModel->query(
            "SELECT * FROM users WHERE status = 'pending' ORDER BY created_at DESC"
        );
    }

    /**
     * Onaylı yazarları getir
     */
    public function getApprovedAuthors()
    {
        return $this->userModel->query(
            "SELECT u.*, COUNT(p.id) as post_count 
             FROM users u 
             LEFT JOIN blog_posts p ON u.id = p.author_id 
             WHERE u.status = 'active' AND u.role = 'user'
             GROUP BY u.id 
             ORDER BY u.name"
        );
    }

    /**
     * Kullanıcı blog yazma yetkisi var mı?
     */
    public function canUserWriteBlog($userId)
    {
        $user = $this->userModel->findById($userId);
        
        if (!$user) {
            return false;
        }

        // Admin her zaman yazabilir
        if ($user['role'] === 'admin') {
            return true;
        }

        // Normal kullanıcı onaylı olmalı
        return $user['status'] === 'active';
    }

    /**
     * Kullanıcı istatistikleri
     */
    public function getUserStatistics()
    {
        $stats = [];
        
        // Toplam kullanıcı
        $result = $this->userModel->query("SELECT COUNT(*) as total FROM users");
        $stats['total'] = $result[0]['total'] ?? 0;
        
        // Onay bekleyen
        $result = $this->userModel->query("SELECT COUNT(*) as pending FROM users WHERE status = 'pending'");
        $stats['pending'] = $result[0]['pending'] ?? 0;
        
        // Aktif
        $result = $this->userModel->query("SELECT COUNT(*) as active FROM users WHERE status = 'active'");
        $stats['active'] = $result[0]['active'] ?? 0;
        
        // Deaktif
        $result = $this->userModel->query("SELECT COUNT(*) as inactive FROM users WHERE status = 'inactive'");
        $stats['inactive'] = $result[0]['inactive'] ?? 0;
        
        // Admin sayısı
        $result = $this->userModel->query("SELECT COUNT(*) as admins FROM users WHERE role = 'admin'");
        $stats['admins'] = $result[0]['admins'] ?? 0;

        return $stats;
    }

    /**
     * Kullanıcının rolünü değiştir
     */
    public function changeUserRole($userId, $newRole)
    {
        $allowedRoles = ['user', 'admin'];
        
        if (!in_array($newRole, $allowedRoles)) {
            return ['success' => false, 'message' => 'Geçersiz rol!'];
        }

        $user = $this->userModel->findById($userId);
        if (!$user) {
            return ['success' => false, 'message' => 'Kullanıcı bulunamadı!'];
        }

        if ($this->userModel->update($userId, ['role' => $newRole])) {
            return [
                'success' => true,
                'message' => "Kullanıcı rolü '{$newRole}' olarak güncellendi!"
            ];
        }

        return ['success' => false, 'message' => 'Rol güncellenirken bir hata oluştu!'];
    }

    /**
     * Kullanıcı durumunu değiştir
     */
    public function changeUserStatus($userId, $status)
    {
        $allowedStatuses = ['active', 'inactive'];
        
        if (!in_array($status, $allowedStatuses)) {
            return ['success' => false, 'message' => 'Geçersiz durum!'];
        }

        $user = $this->userModel->findById($userId);
        if (!$user) {
            return ['success' => false, 'message' => 'Kullanıcı bulunamadı!'];
        }

        if ($this->userModel->update($userId, ['status' => $status])) {
            return [
                'success' => true,
                'message' => "Kullanıcı durumu '{$status}' olarak güncellendi!"
            ];
        }

        return ['success' => false, 'message' => 'Durum güncellenirken bir hata oluştu!'];
    }
}
