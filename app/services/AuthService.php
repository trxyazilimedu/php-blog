<?php

class AuthService
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        // Session zaten başlatılmış
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Kullanıcı girişi
     */
    public function login($email, $password)
    {
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Session'a kullanıcı bilgilerini kaydet
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'] ?? 'user';

            // Son giriş tarihini güncelle
            $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

            return [
                'success' => true,
                'user' => $user,
                'message' => 'Giriş başarılı!'
            ];
        }

        return [
            'success' => false,
            'message' => 'E-posta veya şifre hatalı!'
        ];
    }

    /**
     * Kullanıcı çıkışı
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        
        return [
            'success' => true,
            'message' => 'Çıkış yapıldı!'
        ];
    }

    /**
     * Kullanıcı kaydı
     */
    public function register($userData)
    {
        $userModel = new User();

        // E-posta kontrolü
        if ($userModel->findByEmail($userData['email'])) {
            return [
                'success' => false,
                'message' => 'Bu e-posta adresi zaten kullanılıyor!'
            ];
        }

        // Şifreyi hash'le
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        $userData['role'] = $userData['role'] ?? 'user';
        $userData['status'] = 'active';

        if ($userModel->create($userData)) {
            return [
                'success' => true,
                'message' => 'Kayıt başarılı! Giriş yapabilirsiniz.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Kayıt sırasında bir hata oluştu!'
        ];
    }

    /**
     * Şifre değiştirme
     */
    public function changePassword($userId, $currentPassword, $newPassword)
    {
        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Kullanıcı bulunamadı!'
            ];
        }

        if (!password_verify($currentPassword, $user['password'])) {
            return [
                'success' => false,
                'message' => 'Mevcut şifre hatalı!'
            ];
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        if ($userModel->update($userId, ['password' => $hashedPassword])) {
            return [
                'success' => true,
                'message' => 'Şifre başarıyla değiştirildi!'
            ];
        }

        return [
            'success' => false,
            'message' => 'Şifre değiştirilemedi!'
        ];
    }

    /**
     * Giriş yapan kullanıcı
     */
    public function getUser()
    {
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            return null;
        }

        $userModel = new User();
        return $userModel->findById($userId);
    }

    /**
     * Kullanıcı giriş kontrolü
     */
    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Admin kontrolü
     */
    public function isAdmin()
    {
        $role = $_SESSION['user_role'] ?? 'user';
        return $role === 'admin';
    }

    /**
     * Belirli rol kontrolü
     */
    public function hasRole($role)
    {
        $userRole = $_SESSION['user_role'] ?? 'user';
        return $userRole === $role;
    }
}
