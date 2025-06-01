<?php

class UserController extends BaseController
{
    public function index()
    {
        $userModel = $this->model('User');
        $users = $userModel->findAll();
        
        $data = [
            'page_title' => 'Kullanıcılar',
            'users' => $users
        ];
        
        $this->view('users/index', $data);
    }

    public function show($id)
    {
        $userModel = $this->model('User');
        $user = $userModel->findById($id);
        
        if (!$user) {
            $this->flash('error', 'Kullanıcı bulunamadı.');
            $this->redirect('/users');
            return;
        }
        
        $data = [
            'page_title' => 'Kullanıcı Detayı',
            'user' => $user
        ];
        
        $this->view('users/show', $data);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'role' => $_POST['role'] ?? 'user'
            ];
            
            // Validation service kullanımı
            $validator = $this->service('validation');
            $rules = [
                'name' => 'required|min:2|max:100',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|min:6|max:255',
                'role' => 'required|in:user,admin'
            ];
            
            if ($validator->validate($userData, $rules)) {
                $authService = $this->service('auth');
                $result = $authService->register($userData);
                
                if ($result['success']) {
                    $this->flash('success', $result['message']);
                    $this->redirect('/users');
                } else {
                    $this->flash('error', $result['message']);
                }
            } else {
                $data = [
                    'page_title' => 'Yeni Kullanıcı',
                    'errors' => $validator->getErrors(),
                    'old_data' => $userData
                ];
                $this->view('users/create', $data);
                return;
            }
        }
        
        $data = [
            'page_title' => 'Yeni Kullanıcı'
        ];
        
        $this->view('users/create', $data);
    }

    public function edit($id)
    {
        $userModel = $this->model('User');
        $user = $userModel->findById($id);
        
        if (!$user) {
            $this->flash('error', 'Kullanıcı bulunamadı.');
            $this->redirect('/users');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'role' => $_POST['role'] ?? 'user'
            ];
            
            // Şifre varsa ekle
            if (!empty($_POST['password'])) {
                $userData['password'] = $_POST['password'];
            }
            
            // Validation service kullanımı
            $validator = $this->service('validation');
            $rules = [
                'name' => 'required|min:2|max:100',
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'role' => 'required|in:user,admin'
            ];
            
            if (!empty($userData['password'])) {
                $rules['password'] = 'min:6|max:255';
            }
            
            if ($validator->validate($userData, $rules)) {
                // Şifre varsa hash'le
                if (isset($userData['password'])) {
                    $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
                }
                
                if ($userModel->update($id, $userData)) {
                    $this->flash('success', 'Kullanıcı başarıyla güncellendi!');
                    $this->redirect('/users/show/' . $id);
                } else {
                    $this->flash('error', 'Kullanıcı güncellenirken bir hata oluştu.');
                }
            } else {
                $data = [
                    'page_title' => 'Kullanıcı Düzenle',
                    'user' => $user,
                    'errors' => $validator->getErrors(),
                    'old_data' => $userData
                ];
                $this->view('users/edit', $data);
                return;
            }
        }
        
        $data = [
            'page_title' => 'Kullanıcı Düzenle',
            'user' => $user
        ];
        
        $this->view('users/edit', $data);
    }

    public function delete($id)
    {
        // CSRF token kontrolü
        if (!$this->validateCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->flash('error', 'Geçersiz CSRF token!');
            $this->redirect('/users');
            return;
        }
        
        $userModel = $this->model('User');
        
        // Kendi hesabını silmeye çalışıyorsa engelle
        $currentUser = $this->getLoggedInUser();
        if ($currentUser && $currentUser['id'] == $id) {
            $this->flash('error', 'Kendi hesabınızı silemezsiniz!');
            $this->redirect('/users');
            return;
        }
        
        if ($userModel->delete($id)) {
            $this->flash('success', 'Kullanıcı başarıyla silindi!');
        } else {
            $this->flash('error', 'Kullanıcı silinirken bir hata oluştu!');
        }
        
        $this->redirect('/users');
    }

    // API endpoint örneği
    public function api()
    {
        $userModel = $this->model('User');
        $users = $userModel->getActiveUsers();
        
        $this->json([
            'success' => true,
            'data' => $users,
            'count' => count($users),
            'timestamp' => date('c')
        ]);
    }
    
    public function profile()
    {
        // Giriş yapmış kullanıcı gerekli
        $this->requireAuth();
        
        $user = $this->getLoggedInUser();
        
        $data = [
            'page_title' => 'Profilim',
            'user' => $user
        ];
        
        $this->view('users/profile', $data);
    }
}
