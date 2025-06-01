<?php

class HomeController extends BaseController
{
    public function index()
    {
        // Ana sayfa için özel veriler
        $this->addGlobalData('page_description', 'Modern ve güçlü PHP Framework');
        
        $data = [
            'page_title' => 'Ana Sayfa',
            'message' => 'Simple Framework\'e hoş geldiniz!',
            'features' => [
                'MVC (Model-View-Controller) mimarisi',
                'PDO ile veritabanı bağlantısı',
                'Otomatik sınıf yükleme (Autoloading)',
                'Basit routing sistemi',
                'Singleton veritabanı bağlantısı',
                'Temel CRUD işlemleri',
                'Service katmanı',
                'Flash mesaj sistemi',
                'CSRF koruması'
            ]
        ];
        
        $this->view('home/index', $data);
    }

    public function about()
    {
        $data = [
            'page_title' => 'Hakkında',
            'content' => 'Bu basit bir PHP framework örneğidir.',
            'framework_info' => [
                'version' => $this->getGlobalData('app_version'),
                'author' => 'Simple Framework Team',
                'license' => 'MIT License',
                'github' => 'https://github.com/simple-framework'
            ]
        ];
        
        $this->view('home/about', $data);
    }

    public function contact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactData = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'message' => $_POST['message'] ?? ''
            ];
            
            // Validation service kullanımı
            $validator = $this->service('validation');
            $rules = [
                'name' => 'required|min:2|max:100',
                'email' => 'required|email|max:255',
                'message' => 'required|min:10|max:1000'
            ];
            
            if ($validator->validate($contactData, $rules)) {
                // Veritabanına kaydet
                $contactModel = $this->model('Contact');
                if ($contactModel->createMessage($contactData)) {
                    $this->flash('success', 'Mesajınız başarıyla gönderildi!');
                    $this->redirect('/contact');
                } else {
                    $this->flash('error', 'Mesaj gönderilirken bir hata oluştu.');
                }
            } else {
                $data = [
                    'page_title' => 'İletişim',
                    'errors' => $validator->getErrors(),
                    'old_data' => $contactData
                ];
                $this->view('home/contact', $data);
                return;
            }
        }
        
        $data = [
            'page_title' => 'İletişim'
        ];
        
        $this->view('home/contact', $data);
    }
}
