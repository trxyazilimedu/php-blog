<?php

/**
 * User Management Service
 * Kullanıcı kayıt, onay ve yönetim işlemleri
 */
class UserService
{
    private $db;
    private $userModel;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->userModel = new User();
    }

    // ===========================================
    // Kullanıcı Kayıt ve Onay İşlemleri
    // ===========================================

    /**
     * Kullanıcı kaydı (admin onayı gerekli)
     */
    public function registerUser($data)
    {
        try {
            // E-posta kontrolü
            if ($this->userModel->findByEmail($data['email'])) {
                return [
                    'success' => false,
                    'message' => 'Bu e-posta adresi zaten kullanılıyor!'
                ];
            }

            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'bio' => $data['bio'] ?? '',
                'role' => 'user',
                'status' => 'pending' // Admin onayı bekliyor
            ];

            $userId = $this->userModel->create($userData);

            // Admin'e bildirim gönder (log olarak)
            $this->logUserRegistration($userId, $data);

            return [
                'success' => true,
                'user_id' => $userId,
                'message' => 'Kayıt başarılı! Hesabınız admin onayı bekliyor. Onaylandığında e-posta ile bilgilendirileceksiniz.'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Kayıt sırasında hata oluştu: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Kullanıcı durumunu güncelle (admin işlemi)
     */
    public function updateUserStatus($userId, $status, $adminId)
    {
        try {
            $user = $this->userModel->findById($userId);
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Kullanıcı bulunamadı!'
                ];
            }

            $this->userModel->update($userId, ['status' => $status]);

            // İşlemi logla
            $this->logStatusChange($userId, $user['status'], $status, $adminId);

            $statusText = [
                'active' => 'onaylandı',
                'inactive' => 'reddedildi',
                'pending' => 'beklemede'
            ];

            return [
                'success' => true,
                'message' => "Kullanıcı durumu '{$statusText[$status]}' olarak güncellendi!"
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Durum güncellenirken hata oluştu: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Onay bekleyen kullanıcıları getir
     */
    public function getPendingUsers()
    {
        $sql = "SELECT id, name, email, bio, created_at 
                FROM users 
                WHERE status = 'pending' 
                ORDER BY created_at DESC";
        
        return $this->userModel->query($sql);
    }

    /**
     * Aktif kullanıcıları getir
     */
    public function getActiveUsers()
    {
        $sql = "SELECT id, name, email, bio, role, created_at, last_login
                FROM users 
                WHERE status = 'active' 
                ORDER BY created_at DESC";
        
        return $this->userModel->query($sql);
    }

    // ===========================================
    // Kullanıcı Profil İşlemleri
    // ===========================================

    /**
     * Kullanıcı profilini güncelle
     */
    public function updateProfile($userId, $data)
    {
        try {
            $updateData = [];

            // İzin verilen alanları güncelle
            if (isset($data['name'])) {
                $updateData['name'] = $data['name'];
            }

            if (isset($data['bio'])) {
                $updateData['bio'] = $data['bio'];
            }

            if (isset($data['avatar'])) {
                $updateData['avatar'] = $data['avatar'];
            }

            // E-posta güncellemesi (benzersizlik kontrolü)
            if (isset($data['email'])) {
                $existingUser = $this->userModel->findByEmail($data['email']);
                if ($existingUser && $existingUser['id'] != $userId) {
                    return [
                        'success' => false,
                        'message' => 'Bu e-posta adresi başka bir kullanıcı tarafından kullanılıyor!'
                    ];
                }
                $updateData['email'] = $data['email'];
            }

            $this->userModel->update($userId, $updateData);

            return [
                'success' => true,
                'message' => 'Profil başarıyla güncellendi!'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Profil güncellenirken hata oluştu: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Şifre değiştir
     */
    public function changePassword($userId, $currentPassword, $newPassword)
    {
        try {
            $user = $this->userModel->findById($userId);
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Kullanıcı bulunamadı!'
                ];
            }

            // Mevcut şifreyi kontrol et
            if (!password_verify($currentPassword, $user['password'])) {
                return [
                    'success' => false,
                    'message' => 'Mevcut şifre hatalı!'
                ];
            }

            // Yeni şifreyi hashle ve güncelle
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $this->userModel->update($userId, ['password' => $hashedPassword]);

            return [
                'success' => true,
                'message' => 'Şifre başarıyla değiştirildi!'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Şifre değiştirilirken hata oluştu: ' . $e->getMessage()
            ];
        }
    }

    // ===========================================
    // İstatistik ve Raporlama
    // ===========================================

    /**
     * Kullanıcı istatistikleri
     */
    public function getUserStats()
    {
        $stats = [];
        
        // Toplam kullanıcı sayısı
        $result = $this->userModel->query("SELECT COUNT(*) as total FROM users");
        $stats['total_users'] = $result[0]['total'];
        
        // Aktif kullanıcılar
        $result = $this->userModel->query("SELECT COUNT(*) as active FROM users WHERE status = 'active'");
        $stats['active_users'] = $result[0]['active'];
        
        // Onay bekleyen kullanıcılar
        $result = $this->userModel->query("SELECT COUNT(*) as pending FROM users WHERE status = 'pending'");
        $stats['pending_users'] = $result[0]['pending'];
        
        // Deaktif kullanıcılar
        $result = $this->userModel->query("SELECT COUNT(*) as inactive FROM users WHERE status = 'inactive'");
        $stats['inactive_users'] = $result[0]['inactive'];
        
        // Bu ay kayıt olanlar
        $result = $this->userModel->query("SELECT COUNT(*) as monthly FROM users WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
        $stats['monthly_registrations'] = $result[0]['monthly'];
        
        // Aktif yazarlar (post yazmış olanlar)
        $result = $this->userModel->query("SELECT COUNT(DISTINCT author_id) as active_authors FROM blog_posts WHERE status = 'published'");
        $stats['active_authors'] = $result[0]['active_authors'];
        
        return $stats;
    }

    /**
     * Yazarları ve post sayılarını getir
     */
    public function getAuthorsWithPostCounts()
    {
        $sql = "SELECT u.id, u.name, u.email, u.bio, u.avatar, u.created_at,
                       COUNT(p.id) as post_count,
                       SUM(p.views) as total_views
                FROM users u
                LEFT JOIN blog_posts p ON u.id = p.author_id AND p.status = 'published'
                WHERE u.status = 'active' AND u.role IN ('user', 'writer')
                GROUP BY u.id
                ORDER BY post_count DESC, u.name";
        
        return $this->userModel->query($sql);
    }

    /**
     * En aktif yazarları getir
     */
    public function getTopAuthors($limit = 5)
    {
        $sql = "SELECT u.id, u.name, u.avatar, COUNT(p.id) as post_count
                FROM users u
                JOIN blog_posts p ON u.id = p.author_id
                WHERE u.status = 'active' AND p.status = 'published'
                GROUP BY u.id
                ORDER BY post_count DESC
                LIMIT ?";
        
        return $this->userModel->query($sql, [$limit]);
    }

    // ===========================================
    // Yetkilendirme İşlemleri
    // ===========================================

    /**
     * Kullanıcı blog yazabilir mi kontrol et
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
        
        // Normal kullanıcı aktif olmalı
        return $user['status'] === 'active';
    }

    /**
     * Kullanıcı admin mi kontrol et
     */
    public function isAdmin($userId)
    {
        $user = $this->userModel->findById($userId);
        return $user && $user['role'] === 'admin';
    }

    /**
     * Kullanıcı post'u düzenleyebilir mi
     */
    public function canUserEditPost($userId, $postAuthorId)
    {
        // Admin her post'u düzenleyebilir
        if ($this->isAdmin($userId)) {
            return true;
        }
        
        // Kullanıcı sadece kendi post'larını düzenleyebilir
        return $userId == $postAuthorId;
    }

    // ===========================================
    // Loglama İşlemleri
    // ===========================================

    /**
     * Kullanıcı kaydını logla
     */
    private function logUserRegistration($userId, $data)
    {
        $logData = [
            'action' => 'user_registration',
            'user_id' => $userId,
            'details' => [
                'name' => $data['name'],
                'email' => $data['email'],
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]
        ];
        
        error_log('User Registration: ' . json_encode($logData));
    }

    /**
     * Durum değişikliğini logla
     */
    private function logStatusChange($userId, $oldStatus, $newStatus, $adminId)
    {
        $logData = [
            'action' => 'user_status_change',
            'user_id' => $userId,
            'admin_id' => $adminId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        error_log('User Status Change: ' . json_encode($logData));
    }

    /**
     * Default avatar URL'i getir
     */
    public function getDefaultAvatarUrl($name)
    {
        // İlk harfleri kullanarak basit avatar oluştur
        $initials = '';
        $words = explode(' ', $name);
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
            if (strlen($initials) >= 2) break;
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&background=667eea&color=fff&size=128';
    }
}
