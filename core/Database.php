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

            // PDO MySQL driver'ı kontrolü
            if (extension_loaded('pdo_mysql')) {
                $dsn = "mysql:host={$db['host']};dbname={$db['database']};charset={$db['charset']}";
                
                $this->pdo = new PDO($dsn, $db['username'], $db['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            }
            // SQLite fallback (development için)
            else if (extension_loaded('pdo_sqlite')) {
                $dbPath = ROOT_PATH . '/database.sqlite';
                $dsn = "sqlite:" . $dbPath;
                
                $this->pdo = new PDO($dsn, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
                

            }
            else {
                throw new Exception("Ne MySQL ne de SQLite PDO driver'ı bulunamadı. Lütfen php-mysql veya php-sqlite3 paketini yükleyin.");
            }
            
        } catch (PDOException $e) {

            throw new Exception("Veritabanı bağlantısı başarısız: " . $e->getMessage() . ". PDO driver kontrolü yapın.");
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
