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
        $blogService = $this->service('blog');
        
        $data = [
            'page_title' => 'Hakkımızda',
            'about_title' => $contentService->getContent('about_title', 'Hakkımızda'),
            'about_content' => $contentService->getContent('about_content', 'Bu blog sitesi hakkında bilgiler...'),
            'popular_posts' => $blogService->getPopularPosts(5),
            'categories' => $blogService->getCategoriesWithPostCount()
        ];

        $data['navigation'] = $this->getNavigation();
        $this->view('home/about', $data);
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
