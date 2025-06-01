<?php

class App
{
    public function __construct()
    {
        // Output buffering başlat (header sorunlarını önlemek için)
        if (!ob_get_level()) {
            ob_start();
        }
        
        // Helper fonksiyonları yükle
        require_once APP_PATH . '/config/functions.php';
        
        // Authorization sınıfını yükle
        require_once APP_PATH . '/config/authorization.php';
        
        // Router sınıfını yükle
        require_once CORE_PATH . '/Router.php';
        
        // Route'ları yükle
        require_once APP_PATH . '/config/routes.php';
        
        // Request'ı işle
        $this->handleRequest();
    }

    private function handleRequest()
    {
        // URL ve method'u al
        $requestUri = $this->getRequestUri();
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // Debug modu
        if (isset($_GET['debug'])) {
            $this->debugRequest($requestUri, $requestMethod);
        }
        
        // Router ile işle
        $handled = Router::dispatch($requestUri, $requestMethod);
        
        // Eğer route bulunamazsa fallback controller sistemi
        if (!$handled) {
            $this->fallbackRouting($requestUri);
        }
    }
    
    private function getRequestUri()
    {
        $uri = '';
        
        // URL'i farklı kaynaklardan al
        if (isset($_GET['url'])) {
            $uri = $_GET['url'];
        } elseif (isset($_SERVER['PATH_INFO'])) {
            $uri = trim($_SERVER['PATH_INFO'], '/');
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $uri = trim($requestUri, '/');
        }
        
        return '/' . trim($uri, '/');
    }
    
    private function fallbackRouting($requestUri)
    {
        // Eski controller/method sistemi için fallback
        $controller = 'HomeController';
        $method = 'index';
        $params = [];
        
        $uri = trim($requestUri, '/');
        if (!empty($uri)) {
            $url = filter_var($uri, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            // Controller
            if (isset($url[0]) && !empty($url[0])) {
                $controller = ucfirst($url[0]) . 'Controller';
            }

            // Method
            if (isset($url[1]) && !empty($url[1])) {
                $method = $url[1];
            }

            // Parametreler
            if (isset($url[2])) {
                $params = array_slice($url, 2);
            }
        }
        
        $this->runController($controller, $method, $params);
    }
    
    private function runController($controller, $method, $params = [])
    {
        $controllerFile = APP_PATH . '/controllers/' . $controller . '.php';
        
        if (file_exists($controllerFile)) {
            // BaseController'ı önce yükle
            require_once APP_PATH . '/controllers/BaseController.php';
            require_once $controllerFile;
            
            if (class_exists($controller)) {
                $controllerInstance = new $controller();
                
                // Method var mı kontrol et
                if (method_exists($controllerInstance, $method)) {
                    call_user_func_array([$controllerInstance, $method], $params);
                } else {
                    $this->error404("Method bulunamadı: " . $method);
                }
            } else {
                $this->error404("Controller sınıfı bulunamadı: " . $controller);
            }
        } else {
           $this->error404("Controller dosyası bulunamadı: " . $controller);
        }
    }
    
    private function debugRequest($requestUri, $requestMethod)
    {
        echo "<div style='background: #1e1e1e; color: #f8f8f2; padding: 15px; margin: 10px; border-radius: 5px; font-family: monospace;'>";
        echo "<h3 style='color: #50fa7b;'>🔧 Debug Modu</h3>";
        echo "<strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "<br>";
        echo "<strong>Parsed URI:</strong> " . $requestUri . "<br>";
        echo "<strong>Method:</strong> " . $requestMethod . "<br>";
        echo "<strong>GET[url]:</strong> " . ($_GET['url'] ?? 'N/A') . "<br>";
        
        echo "<h4 style='color: #ff79c6;'>Registered Routes:</h4>";
        $routes = Router::getRoutes();
        echo "<table style='border-collapse: collapse; width: 100%; color: #f8f8f2;'>";
        echo "<tr style='background: #44475a;'><th style='padding: 5px; border: 1px solid #6272a4;'>Method</th><th style='padding: 5px; border: 1px solid #6272a4;'>URI</th><th style='padding: 5px; border: 1px solid #6272a4;'>Action</th><th style='padding: 5px; border: 1px solid #6272a4;'>Middleware</th></tr>";
        
        foreach ($routes as $route) {
            $middleware = implode(', ', $route['middleware']);
            echo "<tr>";
            echo "<td style='padding: 5px; border: 1px solid #6272a4;'>{$route['method']}</td>";
            echo "<td style='padding: 5px; border: 1px solid #6272a4;'>{$route['uri']}</td>";
            echo "<td style='padding: 5px; border: 1px solid #6272a4;'>{$route['action']}</td>";
            echo "<td style='padding: 5px; border: 1px solid #6272a4;'>{$middleware}</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    }

    private function error404($message = "Sayfa bulunamadı")
    {

        
        // Custom 404 sayfası varsa kullan
        if (file_exists(APP_PATH . '/views/errors/404.php')) {
            require_once APP_PATH . '/views/errors/404.php';
        } else {
            echo "<div style='text-align: center; padding: 50px; font-family: Arial, sans-serif;'>";
            echo "<h1 style='color: #e74c3c;'>404 - Sayfa Bulunamadı</h1>";
            echo "<p style='color: #666;'>" . htmlspecialchars($message) . "</p>";
            echo "<a href='/' style='color: #3498db; text-decoration: none;'>Ana Sayfaya Dön</a>";
            echo "</div>";
        }
        exit;
    }
}
