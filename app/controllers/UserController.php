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
        if ($this->isPost()) {
            $userData = $this->only('name', 'email', 'password', 'role');
            $userData['role'] = $userData['role'] ?? 'user';
            
            // Validation kuralları
            $rules = [
                'name' => 'required|min:2|max:100',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|min:6|max:255',
                'role' => 'required|in:user,admin'
            ];
            
            // Validation yap ve hata varsa redirect et
            if (!$this->validateOrRedirect($userData, $rules, '/users/create')) {
                return;
            }
            
            // Kullanıcı oluştur
            $authService = $this->service('auth');
            $result = $authService->register($userData);
            
            if ($result['success']) {
                $this->redirectWithSuccess('/users', $result['message']);
            } else {
                $this->redirectWithError('/users/create', $result['message']);
            }
            return;
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
        $this->verifyCsrfOrFail();
        
        $userModel = $this->model('User');
        
        // Kendi hesabını silmeye çalışıyorsa engelle
        $currentUser = $this->getLoggedInUser();
        if ($currentUser && $currentUser['id'] == $id) {
            $this->redirectWithError('/users', 'Kendi hesabınızı silemezsiniz!');
            return;
        }
        
        if ($userModel->delete($id)) {
            $this->redirectWithSuccess('/users', 'Kullanıcı başarıyla silindi!');
        } else {
            $this->redirectWithError('/users', 'Kullanıcı silinirken bir hata oluştu!');
        }
    }

    // API endpoint örneği
    public function api()
    {
        $userModel = $this->model('User');
        $users = $userModel->getActiveUsers();
        
        $this->apiSuccess($users, 'Kullanıcılar başarıyla getirildi');
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

    public function editProfile()
    {
        // Giriş yapmış kullanıcı gerekli
        $this->requireAuth();
        
        $currentUser = $this->getLoggedInUser();
        $userModel = $this->model('User');
        
        if ($this->isPost()) {
            // CSRF token kontrolü
            if (!$this->validateCSRFToken($this->input('csrf_token'))) {
                $this->flash('error', 'Güvenlik hatası! Sayfayı yenileyin ve tekrar deneyin.');
                $this->redirect('/profile/edit');
                return;
            }
            
            // Validasyon
            $errors = [];
            
            $name = trim($this->input('name', ''));
            $email = trim($this->input('email', ''));
            $bio = trim($this->input('bio', ''));
            $password = $this->input('password', '');
            $passwordConfirm = $this->input('password_confirm', '');
            
            // Ad soyad kontrolü
            if (empty($name)) {
                $errors['name'] = 'Ad soyad alanı zorunludur.';
            } elseif (strlen($name) < 2) {
                $errors['name'] = 'Ad soyad en az 2 karakter olmalıdır.';
            }
            
            // Email kontrolü
            if (empty($email)) {
                $errors['email'] = 'E-posta alanı zorunludur.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Geçerli bir e-posta adresi girin.';
            } else {
                // Email benzersizlik kontrolü (kendi emaili hariç)
                $existingUser = $userModel->findByEmail($email);
                if ($existingUser && $existingUser['id'] != $currentUser['id']) {
                    $errors['email'] = 'Bu e-posta adresi başka bir kullanıcı tarafından kullanılıyor.';
                }
            }
            
            // Şifre kontrolü (eğer girilmişse)
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $errors['password'] = 'Şifre en az 6 karakter olmalıdır.';
                }
                if ($password !== $passwordConfirm) {
                    $errors['password_confirm'] = 'Şifreler eşleşmiyor.';
                }
            }
            
            // Bio kontrolü
            if (strlen($bio) > 500) {
                $errors['bio'] = 'Biyografi en fazla 500 karakter olmalıdır.';
            }
            
            // Profil fotoğrafı yükleme kontrolü
            $avatarImage = null;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleProfileImageUpload($_FILES['avatar']);
                if ($uploadResult['success']) {
                    $avatarImage = $uploadResult['path'];
                } else {
                    $errors['avatar'] = $uploadResult['error'];
                }
            }
            
            if (empty($errors)) {
                // Güncelleme verilerini hazırla
                $updateData = [
                    'name' => $name,
                    'email' => $email,
                    'bio' => $bio
                ];
                
                // Şifre varsa ekle
                if (!empty($password)) {
                    $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
                }
                
                // Profil fotoğrafı varsa ekle
                if ($avatarImage) {
                    // Eski fotoğrafı sil
                    if (!empty($currentUser['avatar'])) {
                        $oldImagePath = __DIR__ . '/../../public' . $currentUser['avatar'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    $updateData['avatar'] = $avatarImage;
                }
                
                if ($userModel->update($currentUser['id'], $updateData)) {
                    $this->flash('success', 'Profiliniz başarıyla güncellendi!');
                    $this->redirect('/profile');
                } else {
                    $this->flash('error', 'Profil güncellenirken bir hata oluştu!');
                }
            } else {
                // Hataları göster
                foreach ($errors as $error) {
                    $this->flash('error', $error);
                }
            }
        }
        
        $data = [
            'page_title' => 'Profili Düzenle',
            'user' => $currentUser,
            'csrf_token' => $this->generateCSRFToken()
        ];
        
        $this->view('users/edit-profile', $data);
    }

    /**
     * Profil fotoğrafı yükleme işleyicisi
     */
    private function handleProfileImageUpload($file)
    {
        // Dosya doğrulama
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'error' => 'Desteklenmeyen dosya türü. Sadece JPG, PNG, GIF ve WebP dosyaları yükleyebilirsiniz.'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'Dosya boyutu çok büyük. Maksimum 2MB olmalıdır.'];
        }
        
        // Uploads dizinini oluştur
        $uploadDir = __DIR__ . '/../../public/uploads/profiles/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                return ['success' => false, 'error' => 'Upload dizini oluşturulamadı.'];
            }
        }
        
        // Dizin yazılabilir mi kontrol et
        if (!is_writable($uploadDir)) {
            return ['success' => false, 'error' => 'Upload dizinine yazma izni yok.'];
        }
        
        // Benzersiz dosya adı oluştur
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . uniqid() . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;
        $publicPath = '/uploads/profiles/' . $filename;
        
        // Dosyayı taşı
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Resmi yeniden boyutlandır (isteğe bağlı)
            $this->resizeProfileImage($uploadPath, 300, 300);
            return ['success' => true, 'path' => $publicPath];
        } else {
            $error = 'Dosya yüklenirken bir hata oluştu.';
            
            // Debug bilgisi ekle
            if (!file_exists($file['tmp_name'])) {
                $error .= ' Geçici dosya bulunamadı.';
            }
            if (!is_writable(dirname($uploadPath))) {
                $error .= ' Hedef dizin yazılabilir değil.';
            }
            
            return ['success' => false, 'error' => $error];
        }
    }

    /**
     * Profil resmini yeniden boyutlandır
     */
    private function resizeProfileImage($imagePath, $width, $height)
    {
        if (!extension_loaded('gd')) {
            return; // GD extension yoksa boyutlandırma yapma
        }

        $imageInfo = getimagesize($imagePath);
        if (!$imageInfo) {
            return;
        }

        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $imageType = $imageInfo[2];

        // Eğer resim zaten istenen boyuttan küçükse işlem yapma
        if ($originalWidth <= $width && $originalHeight <= $height) {
            return;
        }

        // Orantılı boyutlandırma hesapla
        $ratio = min($width / $originalWidth, $height / $originalHeight);
        $newWidth = (int)($originalWidth * $ratio);
        $newHeight = (int)($originalHeight * $ratio);

        // Kaynak resmi oluştur
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($imagePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($imagePath);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($imagePath);
                break;
            case IMAGETYPE_WEBP:
                $sourceImage = imagecreatefromwebp($imagePath);
                break;
            default:
                return;
        }

        if (!$sourceImage) {
            return;
        }

        // Yeni resim oluştur
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // PNG ve GIF için şeffaflığı koru
        if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Resmi yeniden boyutlandır
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        // Resmi kaydet
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($newImage, $imagePath, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($newImage, $imagePath, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($newImage, $imagePath);
                break;
            case IMAGETYPE_WEBP:
                imagewebp($newImage, $imagePath, 90);
                break;
        }

        // Belleği temizle
        imagedestroy($sourceImage);
        imagedestroy($newImage);
    }

    /**
     * Profil fotoğrafını sil
     */
    public function deleteProfileImage()
    {
        $this->requireAuth();
        
        if (!$this->validateCSRFToken($this->input('csrf_token'))) {
            $this->flash('error', 'Güvenlik hatası!');
            $this->redirect('/profile/edit');
            return;
        }

        $currentUser = $this->getLoggedInUser();
        $userModel = $this->model('User');

        if (!empty($currentUser['avatar'])) {
            // Dosyayı sil
            $imagePath = __DIR__ . '/../../public' . $currentUser['avatar'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Veritabanından kaldır
            if ($userModel->update($currentUser['id'], ['avatar' => null])) {
                $this->flash('success', 'Profil fotoğrafı başarıyla silindi!');
            } else {
                $this->flash('error', 'Profil fotoğrafı silinirken bir hata oluştu!');
            }
        } else {
            $this->flash('info', 'Silinecek profil fotoğrafı bulunamadı.');
        }

        $this->redirect('/profile/edit');
    }
}
