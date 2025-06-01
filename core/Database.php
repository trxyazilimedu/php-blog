<?php

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        try {
            $config = require APP_PATH . '/config/app.php';
            $db = $config['database'];

            $dsn = "mysql:host={$db['host']};dbname={$db['database']};charset={$db['charset']}";
            
            $this->pdo = new PDO($dsn, $db['username'], $db['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
            
        } catch (PDOException $e) {
            throw new Exception("Veritabanı bağlantısı başarısız: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }

    // Bağlantıyı klonlamayı engelle
    private function __clone() {}
    
    // Serileştirmeyi engelle
    public function __wakeup()
    {
        throw new Exception("Singleton sınıfı serileştirilemez");
    }
}
