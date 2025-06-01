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
        if ($this->isPost()) {
            $contactData = [
                'name' => $this->input('name', ''),
                'email' => $this->input('email', ''),
                'message' => $this->input('message', '')
            ];
            
            // Validation kuralları
            $rules = [
                'name' => 'required|min:2|max:100',
                'email' => 'required|email|max:255',
                'message' => 'required|min:10|max:1000'
            ];
            
            // Validation yap ve hata varsa redirect et
            if (!$this->validateOrRedirect($contactData, $rules, '/contact')) {
                return;
            }
            
            // Veritabanına kaydet
            $contactModel = $this->model('Contact');
            if ($contactModel->createMessage($contactData)) {
                $this->redirectWithSuccess('/contact', 'Mesajınız başarıyla gönderildi!');
            } else {
                $this->redirectWithError('/contact', 'Mesaj gönderilirken bir hata oluştu.');
            }
            return;
        }
        
        // GET request - normal sayfa gösterimi
        $data = [
            'page_title' => 'İletişim',
            'old_data' => $_SESSION['old_input'] ?? []
        ];
        
        // Eski input'ları temizle
        unset($_SESSION['old_input']);
        
        $this->view('home/contact', $data);
    }
}
