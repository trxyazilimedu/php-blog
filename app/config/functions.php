<?php

/**
 * Sistem genelinde kullanılabilir helper fonksiyonlar
 * Bu dosyaya eklediğiniz fonksiyonlar framework genelinde kullanılabilir
 */

// ===========================================
// URL & Route Helpers
// ===========================================

if (!function_exists('url')) {
    /**
     * Base URL oluştur
     */
    function url($path = '')
    {
        $base = rtrim($_SERVER['HTTP_HOST'] ?? 'localhost', '/');
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        return $protocol . $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    /**
     * Hızlı yönlendirme
     */
    function redirect($url, $statusCode = 302)
    {
        http_response_code($statusCode);
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('back')) {
    /**
     * Önceki sayfaya dön
     */
    function back()
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($referer);
    }
}

// ===========================================
// View & Output Helpers
// ===========================================

if (!function_exists('e')) {
    /**
     * HTML escape (XSS koruması)
     */
    function e($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die (debug için)
     */
    function dd(...$vars)
    {
        echo '<pre style="background: #1e1e1e; color: #f8f8f2; padding: 15px; margin: 10px; border-radius: 5px; font-family: monospace;">';
        foreach ($vars as $var) {
            var_dump($var);
            echo "\n" . str_repeat('-', 50) . "\n";
        }
        echo '</pre>';
        die();
    }
}

if (!function_exists('dump')) {
    /**
     * Dump without die
     */
    function dump(...$vars)
    {
        echo '<pre style="background: #1e1e1e; color: #f8f8f2; padding: 15px; margin: 10px; border-radius: 5px; font-family: monospace;">';
        foreach ($vars as $var) {
            var_dump($var);
            echo "\n" . str_repeat('-', 50) . "\n";
        }
        echo '</pre>';
    }
}

// ===========================================
// Array & Object Helpers
// ===========================================

if (!function_exists('array_get')) {
    /**
     * Array'den güvenli değer al
     */
    function array_get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        // Dot notation desteği (user.name)
        if (strpos($key, '.') !== false) {
            $keys = explode('.', $key);
            foreach ($keys as $k) {
                if (is_array($array) && isset($array[$k])) {
                    $array = $array[$k];
                } else {
                    return $default;
                }
            }
            return $array;
        }

        return $default;
    }
}

if (!function_exists('collect')) {
    /**
     * Array'i basit collection'a çevir
     */
    function collect($items = [])
    {
        return new class($items) {
            private $items;

            public function __construct($items)
            {
                $this->items = is_array($items) ? $items : [$items];
            }

            public function map($callback)
            {
                return new self(array_map($callback, $this->items));
            }

            public function filter($callback = null)
            {
                return new self($callback ? array_filter($this->items, $callback) : array_filter($this->items));
            }

            public function first()
            {
                return !empty($this->items) ? reset($this->items) : null;
            }

            public function last()
            {
                return !empty($this->items) ? end($this->items) : null;
            }

            public function count()
            {
                return count($this->items);
            }

            public function toArray()
            {
                return $this->items;
            }

            public function pluck($key)
            {
                return new self(array_column($this->items, $key));
            }
        };
    }
}

// ===========================================
// String Helpers
// ===========================================

if (!function_exists('str_limit')) {
    /**
     * String'i belirli karakterde kes
     */
    function str_limit($value, $limit = 100, $end = '...')
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }

        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
    }
}

if (!function_exists('str_slug')) {
    /**
     * URL slug oluştur
     */
    function str_slug($title, $separator = '-')
    {
        $title = mb_strtolower($title, 'UTF-8');
        
        // Türkçe karakterleri çevir
        $turkish = ['ç', 'ğ', 'ı', 'ö', 'ş', 'ü', 'Ç', 'Ğ', 'I', 'İ', 'Ö', 'Ş', 'Ü'];
        $english = ['c', 'g', 'i', 'o', 's', 'u', 'c', 'g', 'i', 'i', 'o', 's', 'u'];
        $title = str_replace($turkish, $english, $title);
        
        // Sadece alfanumerik karakterler ve tire bırak
        $title = preg_replace('/[^a-z0-9\s-]/', '', $title);
        $title = preg_replace('/[\s-]+/', $separator, $title);
        
        return trim($title, $separator);
    }
}

if (!function_exists('str_random')) {
    /**
     * Random string oluştur
     */
    function str_random($length = 16)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $randomString;
    }
}

// ===========================================
// Validation Helpers
// ===========================================

if (!function_exists('is_email')) {
    /**
     * Email validation
     */
    function is_email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('is_url')) {
    /**
     * URL validation
     */
    function is_url($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}

if (!function_exists('is_phone')) {
    /**
     * Türkiye telefon numarası validation
     */
    function is_phone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return preg_match('/^(05)[0-9]{9}$/', $phone);
    }
}

// ===========================================
// Date & Time Helpers
// ===========================================

if (!function_exists('now')) {
    /**
     * Şu anki tarih/saat
     */
    function now($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }
}

if (!function_exists('carbon')) {
    /**
     * Basit date helper
     */
    function carbon($date = null, $format = null)
    {
        $timestamp = $date ? strtotime($date) : time();
        
        return new class($timestamp, $format) {
            private $timestamp;
            private $format;

            public function __construct($timestamp, $format)
            {
                $this->timestamp = $timestamp;
                $this->format = $format;
            }

            public function format($format)
            {
                return date($format, $this->timestamp);
            }

            public function diffForHumans()
            {
                $diff = time() - $this->timestamp;
                
                if ($diff < 60) return $diff . ' saniye önce';
                if ($diff < 3600) return floor($diff / 60) . ' dakika önce';
                if ($diff < 86400) return floor($diff / 3600) . ' saat önce';
                if ($diff < 2592000) return floor($diff / 86400) . ' gün önce';
                if ($diff < 31536000) return floor($diff / 2592000) . ' ay önce';
                
                return floor($diff / 31536000) . ' yıl önce';
            }

            public function __toString()
            {
                return $this->format($this->format ?: 'Y-m-d H:i:s');
            }
        };
    }
}

// ===========================================
// File & Path Helpers
// ===========================================

if (!function_exists('storage_path')) {
    /**
     * Storage path oluştur
     */
    function storage_path($path = '')
    {
        $storagePath = ROOT_PATH . '/storage';
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        return $storagePath . ($path ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('public_path')) {
    /**
     * Public path oluştur
     */
    function public_path($path = '')
    {
        return ROOT_PATH . '/public' . ($path ? '/' . ltrim($path, '/') : '');
    }
}

// ===========================================
// Session & Auth Helpers
// ===========================================

if (!function_exists('auth')) {
    /**
     * Auth helper
     */
    function auth()
    {
        return new class {
            public function check()
            {
                return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
            }

            public function user()
            {
                if (!$this->check()) return null;
                
                static $user = null;
                if ($user === null) {
                    require_once APP_PATH . '/models/User.php';
                    $userModel = new User();
                    $user = $userModel->findById($_SESSION['user_id']);
                }
                return $user;
            }

            public function id()
            {
                return $_SESSION['user_id'] ?? null;
            }

            public function isAdmin()
            {
                return $this->check() && ($_SESSION['user_role'] ?? 'user') === 'admin';
            }
        };
    }
}

if (!function_exists('old')) {
    /**
     * Form input değerlerini geri getir
     */
    function old($key, $default = '')
    {
        return $_SESSION['old_input'][$key] ?? $default;
    }
}

// ===========================================
// Configuration Helpers
// ===========================================

if (!function_exists('config')) {
    /**
     * Config değeri al
     */
    function config($key, $default = null)
    {
        static $config = null;
        
        if ($config === null) {
            $config = require APP_PATH . '/config/app.php';
        }

        return array_get($config, $key, $default);
    }
}

if (!function_exists('env')) {
    /**
     * Environment variable al
     */
    function env($key, $default = null)
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        
        if ($value === false) {
            return $default;
        }

        // Boolean string'leri çevir
        if (in_array(strtolower($value), ['true', 'false'])) {
            return strtolower($value) === 'true';
        }

        return $value;
    }
}

// ===========================================
// Response Helpers
// ===========================================

if (!function_exists('response')) {
    /**
     * Response helper
     */
    function response()
    {
        return new class {
            public function json($data, $status = 200)
            {
                http_response_code($status);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                exit;
            }

            public function download($file, $name = null)
            {
                if (!file_exists($file)) {
                    http_response_code(404);
                    die('File not found');
                }

                $name = $name ?: basename($file);
                
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $name . '"');
                header('Content-Length: ' . filesize($file));
                
                readfile($file);
                exit;
            }
        };
    }
}

// ===========================================
// Custom Functions Area
// ===========================================

/*
 * Buraya kendi custom fonksiyonlarınızı ekleyebilirsiniz
 * Örnek:
 * 
 * function my_custom_function($param) {
 *     return "Custom: " . $param;
 * }
 */
