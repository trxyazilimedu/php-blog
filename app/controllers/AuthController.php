<?php

class AuthController extends BaseController
{
    private $authService;
    private $userManagementService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = $this->service('auth');
        $this->userManagementService = $this->service('userManagement');
    }

    /**
     * Giriş sayfası
     */
    public function login()
    {
        // Zaten giriş yapmışsa yönlendir
        if ($this->isUserLoggedIn()) {
            $this->redirect('/blog');
            return;
        }

        if ($this->isPost()) {
            $email = $this->input('email');
            $password = $this->input('password');

            if (empty($email) || empty($password)) {
                $this->flash('error', 'E-posta ve şifre alanları zorunludur!');
                $this->redirect('/login');
                return;
            }

            $result = $this->authService->login($email, $password);

            if ($result['success']) {
                $this->flash('success', $result['message']);
                
                // Admin ise admin paneline, değilse blog ana sayfasına yönlendir
                if ($result['user']['role'] === 'admin') {
                    $this->redirect('/admin');
                } else {
                    $this->redirect('/blog');
                }
            } else {
                $this->flash('error', $result['message']);
                $this->redirect('/login');
            }
            return;
        }

        $data = [
            'page_title' => 'Giriş Yap'
        ];

        $this->view('auth/login', $data);
    }

    /**
     * Kayıt sayfası
     */
    public function register()
    {
        // Zaten giriş yapmışsa yönlendir
        if ($this->isUserLoggedIn()) {
            $this->redirect('/blog');
            return;
        }

        if ($this->isPost()) {
            $userData = [
                'name' => $this->input('name'),
                'email' => $this->input('email'),
                'password' => $this->input('password'),
                'role' => $this->input('role', 'user'),
                'bio' => $this->input('bio', '')
            ];

            $passwordConfirm = $this->input('password_confirm');

            // Validation
            $errors = [];

            if (empty($userData['name'])) {
                $errors[] = 'Ad soyad alanı zorunludur!';
            } elseif (strlen($userData['name']) < 2) {
                $errors[] = 'Ad soyad en az 2 karakter olmalıdır!';
            }

            if (empty($userData['email'])) {
                $errors[] = 'E-posta alanı zorunludur!';
            } elseif (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Geçerli bir e-posta adresi giriniz!';
            }

            if (empty($userData['password'])) {
                $errors[] = 'Şifre alanı zorunludur!';
            } elseif (strlen($userData['password']) < 6) {
                $errors[] = 'Şifre en az 6 karakter olmalıdır!';
            }

            if ($userData['password'] !== $passwordConfirm) {
                $errors[] = 'Şifre doğrulama eşleşmiyor!';
            }

            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $this->flash('error', $error);
                }
                $this->redirect('/auth/register');
                return;
            }

            $result = $this->userManagementService->registerUser($userData);

            if ($result['success']) {
                // Auto-login after successful registration
                $loginResult = $this->authService->login($userData['email'], $userData['password']);
                
                if ($loginResult['success']) {
                    $this->flash('success', 'Kayıt başarılı! Hoş geldiniz.');
                    
                    // Admin ise admin paneline, değilse blog ana sayfasına yönlendir
                    if ($loginResult['user']['role'] === 'admin') {
                        $this->redirect('/admin');
                    } else {
                        $this->redirect('/blog');
                    }
                } else {
                    $this->flash('success', $result['message']);
                    $this->redirect('/login');
                }
            } else {
                $this->flash('error', $result['message']);
                $this->redirect('/auth/register');
            }
            return;
        }

        $data = [
            'page_title' => 'Kayıt Ol'
        ];

        $this->view('auth/register', $data);
    }

    /**
     * Çıkış işlemi
     */
    public function logout()
    {
        $result = $this->authService->logout();
        
        if ($result['success']) {
            $this->flash('success', $result['message']);
        }
        
        $this->redirect('/');
    }

    /**
     * Şifre unutma
     */
    public function forgotPassword()
    {
        if ($this->isPost()) {
            $email = $this->input('email');
            
            if (empty($email)) {
                $this->flash('error', 'E-posta adresi gereklidir!');
                $this->redirect('/auth/forgot-password');
                return;
            }

            // Burada şifre sıfırlama e-postası gönderme işlemi yapılabilir
            $this->flash('info', 'Bu özellik henüz aktif değil. Lütfen yönetici ile iletişime geçin.');
            $this->redirect('/auth/forgot-password');
            return;
        }

        $data = [
            'page_title' => 'Şifremi Unuttum'
        ];

        $this->view('auth/forgot-password', $data);
    }
}
