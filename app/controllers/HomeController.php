<?php

class HomeController extends BaseController
{
    public function index()
    {
        // Blog ana sayfasına yönlendir
        $this->redirect('/blog');
    }

    public function about()
    {
        $contentService = $this->service('content');
        
        // Default içerikleri oluştur (eğer yoksa)
        $this->createDefaultAboutContent($contentService);
        
        $data = [
            'page_title' => $contentService->getContent('about_hero_title', 'Hakkımızda'),
            'contentService' => $contentService,
            'framework_info' => [
                'version' => '1.0.0',
                'author' => 'Simple Framework Team',
                'license' => 'MIT',
                'github' => 'https://github.com/simple-framework'
            ]
        ];

        $this->view('home/about', $data);
    }
    
    /**
     * Default about page content oluştur
     */
    private function createDefaultAboutContent($contentService)
    {
        $defaults = [
            'about_hero_title' => 'Hakkımızda',
            'about_hero_subtitle' => 'Teknoloji dünyasındaki gelişmeleri takip edin, bilgi ve deneyimlerimizi paylaştığımız platformumuza hoş geldiniz.',
            'about_mission_title' => 'Misyonumuz',
            'about_mission_content' => 'Teknoloji alanındaki güncel gelişmeleri takip etmek ve bu bilgileri toplulukla paylaşarak kolektif öğrenmeyi desteklemek.',
            'about_vision_title' => 'Vizyonumuz',
            'about_vision_content' => 'Türkiye\'de teknoloji bilgisinin en güvenilir ve erişilebilir kaynaklarından biri olmak.',
            'about_team_title' => 'Ekibimiz',
            'about_team_content' => 'Deneyimli yazılımcılar ve teknoloji meraklılarından oluşan ekibimiz, sürekli öğrenme ve paylaşım odaklı yaklaşımla içerikler üretiyor.'
        ];
        
        foreach ($defaults as $key => $value) {
            $existing = $contentService->getContent($key);
            if (empty($existing)) {
                $contentService->updateContent($key, $value, 'about', 'main');
            }
        }
    }

    public function contact()
    {
        $contentService = $this->service('content');
        
        if ($this->isPost()) {
            $contactData = [
                'name' => $this->input('name', ''),
                'email' => $this->input('email', ''),
                'message' => $this->input('message', '')
            ];
            
            // Validation service kullanımı
            $errors = $this->validate($contactData, [
                'name' => 'required|min:2|max:100',
                'email' => 'required|email|max:255',
                'message' => 'required|min:10|max:1000'
            ]);
            
            if (empty($errors)) {
                // Veritabanına kaydet
                $contactModel = $this->model('Contact');
                if ($contactModel->createMessage($contactData)) {
                    $this->flash('success', 'Mesajınız başarıyla gönderildi!');
                } else {
                    $this->flash('error', 'Mesaj gönderilirken bir hata oluştu.');
                }
                
                // POST-Redirect-GET pattern
                $this->redirect('/contact');
                return;
            } else {
                // Hataları flash'e kaydet
                foreach ($errors as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        $this->flash('error', $error);
                    }
                }
                
                // Eski input'ları sakla
                $_SESSION['old_input'] = $contactData;
                
                // Redirect ile hata sayfasına dön
                $this->redirect('/contact');
                return;
            }
        }
        
        // GET request - normal sayfa gösterimi
        $data = [
            'page_title' => $contentService->getContent('contact_title', 'İletişim'),
            'contact_content' => $contentService->getContent('contact_content', 'Bizimle iletişime geçin'),
            'old_data' => $_SESSION['old_input'] ?? []
        ];
        
        // Eski input'ları temizle
        unset($_SESSION['old_input']);
        
        $data['navigation'] = $this->getNavigation();
        $this->view('home/contact', $data);
    }


}
