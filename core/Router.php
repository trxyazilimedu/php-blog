<?php

class Router
{
    private static $routes = [];
    private static $currentGroup = null;
    private static $groupStack = [];

    /**
     * Route grubu oluşturma
     */
    public static function group($attributes, $callback)
    {
        // Grup özelliklerini stack'e ekle
        self::$groupStack[] = $attributes;
        self::$currentGroup = $attributes;
        
        // Callback'i çalıştır
        $callback();
        
        // Grup bittiğinde stack'ten çıkar
        array_pop(self::$groupStack);
        self::$currentGroup = !empty(self::$groupStack) ? end(self::$groupStack) : null;
    }

    /**
     * GET route tanımlama
     */
    public static function get($uri, $action)
    {
        self::addRoute('GET', $uri, $action);
    }

    /**
     * POST route tanımlama
     */
    public static function post($uri, $action)
    {
        self::addRoute('POST', $uri, $action);
    }

    /**
     * PUT route tanımlama
     */
    public static function put($uri, $action)
    {
        self::addRoute('PUT', $uri, $action);
    }

    /**
     * DELETE route tanımlama
     */
    public static function delete($uri, $action)
    {
        self::addRoute('DELETE', $uri, $action);
    }

    /**
     * Tüm HTTP methodları için route
     */
    public static function any($uri, $action)
    {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        foreach ($methods as $method) {
            self::addRoute($method, $uri, $action);
        }
    }

    /**
     * Resource route'lar (CRUD)
     */
    public static function resource($name, $controller)
    {
        $routes = [
            'GET' => [
                $name => $controller . '@index',
                $name . '/create' => $controller . '@create',
                $name . '/{id}' => $controller . '@show',
                $name . '/{id}/edit' => $controller . '@edit',
            ],
            'POST' => [
                $name => $controller . '@store',
            ],
            'PUT' => [
                $name . '/{id}' => $controller . '@update',
            ],
            'DELETE' => [
                $name . '/{id}' => $controller . '@destroy',
            ]
        ];

        foreach ($routes as $method => $methodRoutes) {
            foreach ($methodRoutes as $uri => $action) {
                self::addRoute($method, $uri, $action);
            }
        }
    }

    /**
     * Route ekleme
     */
    private static function addRoute($method, $uri, $action)
    {
        // URI'yi normalize et
        $uri = trim($uri, '/');
        
        // Grup prefix'i ekle
        if (self::$currentGroup && isset(self::$currentGroup['prefix'])) {
            $prefix = trim(self::$currentGroup['prefix'], '/');
            if (!empty($prefix)) {
                $uri = !empty($uri) ? $prefix . '/' . $uri : $prefix;
            }
        }

        // Final URI format
        $uri = !empty($uri) ? '/' . $uri : '';

        // Route bilgilerini hazırla
        $route = [
            'uri' => $uri,
            'action' => $action,
            'method' => $method,
            'middleware' => self::getRouteMiddleware(),
            'name' => null
        ];

        // Route'u kaydet
        self::$routes[] = $route;
    }

    /**
     * Middleware'leri topla
     */
    private static function getRouteMiddleware()
    {
        $middleware = [];
        
        // Tüm grup stack'inden middleware'leri topla
        foreach (self::$groupStack as $group) {
            if (isset($group['middleware'])) {
                if (is_array($group['middleware'])) {
                    $middleware = array_merge($middleware, $group['middleware']);
                } else {
                    $middleware[] = $group['middleware'];
                }
            }
        }

        return array_unique($middleware);
    }

    /**
     * Route'u çözümle ve çalıştır
     */
    public static function dispatch($requestUri, $requestMethod = 'GET')
    {
        // URI'yi normalize et
        $requestUri = trim($requestUri, '/');
        $requestUri = !empty($requestUri) ? '/' . $requestUri : '';

        foreach (self::$routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = self::uriToRegex($route['uri']);
            
            // Debug için
            if (isset($_GET['debug'])) {
                echo "<div style='background: #eee; padding: 5px; margin: 2px; font-size: 12px;'>";
                echo "Checking: '{$route['uri']}' (pattern: {$pattern}) against: '{$requestUri}'";
                echo "</div>";
            }
            
            if (preg_match($pattern, $requestUri, $matches)) {
                // Debug için
                if (isset($_GET['debug'])) {
                    echo "<div style='background: green; color: white; padding: 5px; margin: 2px;'>MATCH FOUND!</div>";
                }
                
                // Parametreleri ayıkla
                $params = array_slice($matches, 1);
                
                // Middleware'leri çalıştır
                if (!self::runMiddleware($route['middleware'])) {
                    return false;
                }

                // Action'ı çalıştır
                return self::runAction($route['action'], $params);
            }
        }

        return false; // Route bulunamadı
    }

    /**
     * URI'yi regex pattern'e çevir
     */
    private static function uriToRegex($uri)
    {
        // Boş URI için özel durum
        if (empty($uri)) {
            return '/^$/';
        }
        
        $pattern = str_replace('/', '\/', $uri);
        $pattern = preg_replace('/\{([^}]+)\}/', '([^\/]+)', $pattern);
        return '/^' . $pattern . '$/';
    }

    /**
     * Middleware'leri çalıştır
     */
    private static function runMiddleware($middleware)
    {
        foreach ($middleware as $middlewareName) {
            try {
                if (!Authorization::checkMiddleware($middlewareName)) {
                    if (isset($_GET['debug'])) {
                        echo "<div style='background: red; color: white; padding: 5px;'>Middleware failed: {$middlewareName}</div>";
                    }
                    return false;
                }
            } catch (Exception $e) {
                // Middleware hatası durumunda false dön
                if (isset($_GET['debug'])) {
                    echo "<div style='background: red; color: white; padding: 5px;'>Middleware error: {$e->getMessage()}</div>";
                }
                return false;
            }
        }
        return true;
    }

    /**
     * Action'ı çalıştır
     */
    private static function runAction($action, $params = [])
    {
        if (is_string($action) && strpos($action, '@') !== false) {
            list($controller, $method) = explode('@', $action);
            
            $controllerClass = $controller . 'Controller';
            $controllerFile = APP_PATH . '/controllers/' . $controllerClass . '.php';
            
            // Debug için
            if (isset($_GET['debug'])) {
                echo "<div style='background: orange; padding: 5px; margin: 2px;'>";
                echo "Trying to load: {$controllerClass} -> {$method}() from {$controllerFile}";
                echo "</div>";
            }
            
            if (file_exists($controllerFile)) {
                require_once CORE_PATH . '/Controller.php';
                require_once APP_PATH . '/controllers/BaseController.php';
                require_once $controllerFile;
                
                if (class_exists($controllerClass)) {
                    $controllerInstance = new $controllerClass();
                    
                    if (method_exists($controllerInstance, $method)) {
                        call_user_func_array([$controllerInstance, $method], $params);
                        return true; // Başarılı olarak işlendi
                    } else {
                        if (isset($_GET['debug'])) {
                            echo "<div style='background: red; color: white; padding: 5px;'>Method {$method} not found in {$controllerClass}</div>";
                        }
                    }
                } else {
                    if (isset($_GET['debug'])) {
                        echo "<div style='background: red; color: white; padding: 5px;'>Class {$controllerClass} not found</div>";
                    }
                }
            } else {
                if (isset($_GET['debug'])) {
                    echo "<div style='background: red; color: white; padding: 5px;'>Controller file not found: {$controllerFile}</div>";
                }
            }
        } elseif (is_callable($action)) {
            call_user_func_array($action, $params);
            return true;
        }

        return false;
    }

    /**
     * Tüm route'ları al
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    /**
     * Route'ları temizle
     */
    public static function clear()
    {
        self::$routes = [];
        self::$currentGroup = null;
        self::$groupStack = [];
    }
}
