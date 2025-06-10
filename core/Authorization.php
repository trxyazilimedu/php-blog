<?php

/**
 * Authorization Core Class
 * Middleware sistemini yönetir
 */
class Authorization
{
    private static $middlewareMap = [
        'auth' => 'AuthMiddleware',
        'admin' => 'AdminMiddleware', 
        'writer' => 'WriterMiddleware',
        'guest' => 'GuestMiddleware'
    ];

    /**
     * Middleware'i çalıştır
     */
    public static function checkMiddleware($middlewareName)
    {
        if (!isset(self::$middlewareMap[$middlewareName])) {
            throw new Exception("Middleware '{$middlewareName}' bulunamadı.");
        }

        $middlewareClass = self::$middlewareMap[$middlewareName];
        $middlewareFile = APP_PATH . '/middleware/' . $middlewareClass . '.php';

        if (!file_exists($middlewareFile)) {
            throw new Exception("Middleware dosyası bulunamadı: {$middlewareFile}");
        }

        require_once $middlewareFile;

        if (!class_exists($middlewareClass)) {
            throw new Exception("Middleware sınıfı bulunamadı: {$middlewareClass}");
        }

        $middleware = new $middlewareClass();

        if (!method_exists($middleware, 'handle')) {
            throw new Exception("Middleware '{$middlewareClass}' sınıfında 'handle' metodu bulunamadı.");
        }

        return $middleware->handle();
    }

    /**
     * Yeni middleware kaydı
     */
    public static function registerMiddleware($name, $className)
    {
        self::$middlewareMap[$name] = $className;
    }

    /**
     * Middleware listesini al
     */
    public static function getMiddlewareMap()
    {
        return self::$middlewareMap;
    }
}