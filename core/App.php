<?php

class App
{
    private $controller = 'HomeController';
    private $method = 'index';
    private $params = [];

    public function __construct()
    {
        // Konfigürasyon dosyalarını yükle
        require_once APP_PATH . '/config/app.php';
        
        // Route'ları işle
        $this->parseUrl();
        
        // İlgili controller'ı çalıştır
        $this->run();
    }

    private function parseUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            // Controller
            if (isset($url[0]) && !empty($url[0])) {
                $this->controller = ucfirst($url[0]) . 'Controller';
            }

            // Method
            if (isset($url[1]) && !empty($url[1])) {
                $this->method = $url[1];
            }

            // Parametreler
            if (isset($url[2])) {
                $this->params = array_slice($url, 2);
            }
        }
    }

    private function run()
    {
        // Controller dosyası var mı kontrol et
        $controllerFile = APP_PATH . '/controllers/' . $this->controller . '.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            if (class_exists($this->controller)) {
                $controller = new $this->controller();
                
                // Method var mı kontrol et
                if (method_exists($controller, $this->method)) {
                    call_user_func_array([$controller, $this->method], $this->params);
                } else {
                    $this->error404("Method bulunamadı: " . $this->method);
                }
            } else {
                $this->error404("Controller sınıfı bulunamadı: " . $this->controller);
            }
        } else {
            $this->error404("Controller dosyası bulunamadı: " . $this->controller);
        }
    }

    private function error404($message = "Sayfa bulunamadı")
    {
        http_response_code(404);
        echo "<h1>404 - Sayfa Bulunamadı</h1>";
        echo "<p>" . htmlspecialchars($message) . "</p>";
        exit;
    }
}
