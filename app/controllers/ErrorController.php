<?php

class ErrorController extends BaseController
{
    /**
     * 404 sayfa bulunamadı hatası
     */
    public function notFound($message = "Sayfa bulunamadı")
    {
        // http_response_code zaten Router::show404'te set ediliyor
        
        $data = [
            'page_title' => '404 - Sayfa Bulunamadı',
            'error_message' => $message
        ];
        
        $this->view('errors/404', $data);
    }
    
    /**
     * 500 sunucu hatası
     */
    public function serverError($message = "Sunucu hatası")
    {
        http_response_code(500);
        
        $data = [
            'page_title' => '500 - Sunucu Hatası',
            'error_message' => $message
        ];
        
        $this->view('errors/500', $data);
    }
    
    /**
     * 403 erişim engellendi
     */
    public function forbidden($message = "Bu sayfaya erişim yetkiniz bulunmuyor")
    {
        http_response_code(403);
        
        $data = [
            'page_title' => '403 - Erişim Engellendi',
            'error_message' => $message
        ];
        
        $this->view('errors/403', $data);
    }
}