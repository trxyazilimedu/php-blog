<?php

class UserController extends Controller
{
    public function index()
    {
        $userModel = $this->model('User');
        $users = $userModel->findAll();
        
        $data = [
            'title' => 'Kullanıcılar',
            'users' => $users
        ];
        
        $this->view('users/index', $data);
    }

    public function show($id)
    {
        $userModel = $this->model('User');
        $user = $userModel->findById($id);
        
        if (!$user) {
            $this->redirect('/users');
            return;
        }
        
        $data = [
            'title' => 'Kullanıcı Detayı',
            'user' => $user
        ];
        
        $this->view('users/show', $data);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->model('User');
            
            $userData = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? ''
            ];
            
            // Basit validasyon
            if (empty($userData['name']) || empty($userData['email']) || empty($userData['password'])) {
                $data = [
                    'title' => 'Yeni Kullanıcı',
                    'error' => 'Tüm alanları doldurmanız gerekiyor.'
                ];
                $this->view('users/create', $data);
                return;
            }
            
            if ($userModel->createUser($userData)) {
                $this->redirect('/users?success=1');
            } else {
                $data = [
                    'title' => 'Yeni Kullanıcı',
                    'error' => 'Kullanıcı oluşturulurken bir hata oluştu.'
                ];
                $this->view('users/create', $data);
            }
        } else {
            $data = ['title' => 'Yeni Kullanıcı'];
            $this->view('users/create', $data);
        }
    }

    public function delete($id)
    {
        $userModel = $this->model('User');
        
        if ($userModel->delete($id)) {
            $this->redirect('/users?deleted=1');
        } else {
            $this->redirect('/users?error=1');
        }
    }

    // API endpoint örneği
    public function api()
    {
        $userModel = $this->model('User');
        $users = $userModel->getActiveUsers();
        
        $this->json([
            'success' => true,
            'data' => $users,
            'count' => count($users)
        ]);
    }
}
