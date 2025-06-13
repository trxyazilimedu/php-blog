<?php

class CacheService
{
    private static $cacheEnabled = false;
    private static $cacheDir = null;

    public function __construct()
    {
        self::$cacheDir = ROOT_PATH . '/storage/cache';
        self::$cacheEnabled = $this->isCacheEnabled();
        
        // Cache dizinini oluştur
        $this->ensureCacheDirectoryExists();
    }

    /**
     * Cache'in aktif olup olmadığını kontrol et
     */
    private function isCacheEnabled()
    {
        return function_exists('opcache_get_status') && opcache_get_status() !== false;
    }

    /**
     * Cache dizininin varlığını kontrol et ve oluştur
     */
    private function ensureCacheDirectoryExists()
    {
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0755, true);
        }
    }

    /**
     * OPcache durumunu getir
     */
    public function getOpcacheStatus()
    {
        if (!self::$cacheEnabled) {
            return [
                'enabled' => false,
                'message' => 'OPcache yüklü değil veya devre dışı'
            ];
        }

        $status = opcache_get_status();
        $config = opcache_get_configuration();

        return [
            'enabled' => true,
            'status' => $status,
            'config' => $config,
            'memory_usage' => [
                'used' => $status['memory_usage']['used_memory'],
                'free' => $status['memory_usage']['free_memory'],
                'wasted' => $status['memory_usage']['wasted_memory'],
                'usage_percentage' => round(($status['memory_usage']['used_memory'] / 
                    ($status['memory_usage']['used_memory'] + $status['memory_usage']['free_memory'])) * 100, 2)
            ],
            'hit_rate' => round(($status['opcache_statistics']['hits'] / 
                ($status['opcache_statistics']['hits'] + $status['opcache_statistics']['misses'])) * 100, 2),
            'cached_files' => $status['opcache_statistics']['num_cached_scripts'],
            'max_files' => $status['opcache_statistics']['max_cached_keys']
        ];
    }

    /**
     * OPcache'i tamamen temizle
     */
    public function clearOpcache()
    {
        if (!self::$cacheEnabled) {
            return [
                'success' => false,
                'message' => 'OPcache kullanılamıyor'
            ];
        }

        $success = opcache_reset();
        
        return [
            'success' => $success,
            'message' => $success ? 'OPcache başarıyla temizlendi' : 'OPcache temizlenemedi'
        ];
    }

    /**
     * Belirli bir dosyayı cache'den kaldır
     */
    public function invalidateFile($filePath)
    {
        if (!self::$cacheEnabled) {
            return false;
        }

        // Dosya yolu mutlak yol olmalı
        if (!is_file($filePath)) {
            return false;
        }

        return opcache_invalidate($filePath, true);
    }

    /**
     * Blog ile ilgili dosyaları cache'den temizle
     */
    public function clearBlogCache()
    {
        $clearedFiles = [];
        
        // Blog controller ve model dosyalarını temizle
        $blogFiles = [
            APP_PATH . '/controllers/BlogController.php',
            APP_PATH . '/models/BlogPost.php',
            APP_PATH . '/models/BlogCategory.php',
            APP_PATH . '/models/BlogComment.php',
            APP_PATH . '/services/BlogService.php'
        ];

        foreach ($blogFiles as $file) {
            if ($this->invalidateFile($file)) {
                $clearedFiles[] = basename($file);
            }
        }

        // Blog ile ilgili file cache'leri temizle
        $this->clearBlogFileCache();

        return [
            'success' => !empty($clearedFiles),
            'cleared_files' => $clearedFiles,
            'message' => 'Blog cache temizlendi: ' . implode(', ', $clearedFiles)
        ];
    }

    /**
     * Blog file cache'lerini temizle
     */
    public function clearBlogFileCache()
    {
        $cacheFiles = glob(self::$cacheDir . '/*.cache');
        $cleared = 0;
        
        // Blog ile ilgili cache key'leri
        $blogCacheKeys = [
            'blog_index_page_',
            'blog_post_',
            'blog_category_',
            'popular_posts_',
            'recent_posts_',
            'categories_with_post_count'
        ];
        
        foreach ($cacheFiles as $file) {
            $filename = basename($file, '.cache');
            
            // Her bir blog cache key'i için kontrol et
            foreach ($blogCacheKeys as $prefix) {
                $hashedPrefix = md5($prefix);
                if (strpos($filename, $hashedPrefix) === 0 || $filename === md5($prefix)) {
                    if (unlink($file)) {
                        $cleared++;
                    }
                    break;
                }
            }
        }
        
        return $cleared;
    }

    /**
     * Admin işlemleri sonrası cache temizleme
     */
    public function clearAdminCache()
    {
        $clearedFiles = [];
        
        // Admin ile ilgili dosyaları temizle
        $adminFiles = [
            APP_PATH . '/controllers/AdminController.php',
            APP_PATH . '/services/UserManagementService.php',
            APP_PATH . '/services/ContentManagementService.php',
            APP_PATH . '/services/SettingsService.php',
            APP_PATH . '/models/User.php',
            APP_PATH . '/models/SiteSetting.php'
        ];

        foreach ($adminFiles as $file) {
            if ($this->invalidateFile($file)) {
                $clearedFiles[] = basename($file);
            }
        }

        return [
            'success' => !empty($clearedFiles),
            'cleared_files' => $clearedFiles,
            'message' => 'Admin cache temizlendi: ' . implode(', ', $clearedFiles)
        ];
    }

    /**
     * View dosyalarını cache'den temizle
     */
    public function clearViewCache()
    {
        $clearedFiles = [];
        
        // Ana layout dosyasını temizle
        $layoutFile = APP_PATH . '/views/layouts/main.php';
        if ($this->invalidateFile($layoutFile)) {
            $clearedFiles[] = 'main.php';
        }

        return [
            'success' => !empty($clearedFiles),
            'cleared_files' => $clearedFiles,
            'message' => 'View cache temizlendi: ' . implode(', ', $clearedFiles)
        ];
    }

    /**
     * Akıllı cache temizleme - sadece değişen dosyalara göre
     */
    public function smartClearCache($context = 'general')
    {
        switch ($context) {
            case 'blog_post':
                return $this->clearBlogCache();
            
            case 'blog_category':
                return $this->clearBlogCache();
            
            case 'blog_comment':
                return $this->clearBlogCache();
            
            case 'user_management':
                return $this->clearAdminCache();
            
            case 'site_settings':
                $result1 = $this->clearAdminCache();
                $result2 = $this->clearViewCache();
                return [
                    'success' => $result1['success'] || $result2['success'],
                    'cleared_files' => array_merge($result1['cleared_files'], $result2['cleared_files']),
                    'message' => 'Ayarlar cache temizlendi'
                ];
            
            case 'navigation':
                return $this->clearViewCache();
            
            default:
                return $this->clearOpcache();
        }
    }

    /**
     * File-based cache sistemi (OPcache dışında)
     */
    public function setFileCache($key, $data, $expiration = 3600)
    {
        // PDO veya nesne içerip içermediğini kontrol edip temizle
        $cleanData = $this->cleanCacheData($data);

        $cacheFile = self::$cacheDir . '/' . md5($key) . '.cache';
        $cacheData = [
            'data' => $cleanData,
            'expiration' => time() + $expiration,
            'created' => time()
        ];

        return file_put_contents($cacheFile, serialize($cacheData)) !== false;
    }
    /**
     * Cache'e konulamayacak (PDO vb) nesneleri çıkar
     */
    private function cleanCacheData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->cleanCacheData($value);
            }
            return $data;
        }

        if (is_object($data)) {
            // Eğer PDO ise null döndür
            if ($data instanceof PDO) {
                return null;
            }
            // Diğer nesneleri serialize etmeye çalışmak hata verir
            // İsterseniz nesneyi dizeye çevirebilirsiniz veya null yapabilirsiniz
            return null;
        }

        // Diğer veri tipleri (string, int, vb) olduğu gibi döner
        return $data;
    }

    /**
     * File-based cache'den veri oku
     */
    public function getFileCache($key)
    {
        $cacheFile = self::$cacheDir . '/' . md5($key) . '.cache';
        
        if (!file_exists($cacheFile)) {
            return null;
        }
        
        $cacheData = unserialize(file_get_contents($cacheFile));
        
        // Expire kontrolü
        if ($cacheData['expiration'] < time()) {
            unlink($cacheFile);
            return null;
        }
        
        return $cacheData['data'];
    }

    /**
     * File-based cache'i temizle
     */
    public function clearFileCache($key = null)
    {
        if ($key) {
            // Belirli bir key'i temizle
            $cacheFile = self::$cacheDir . '/' . md5($key) . '.cache';
            if (file_exists($cacheFile)) {
                return unlink($cacheFile);
            }
            return true;
        }
        
        // Tüm file cache'i temizle
        $files = glob(self::$cacheDir . '/*.cache');
        $cleared = 0;
        
        foreach ($files as $file) {
            if (unlink($file)) {
                $cleared++;
            }
        }
        
        return [
            'success' => true,
            'cleared_files' => $cleared,
            'message' => "$cleared cache dosyası temizlendi"
        ];
    }

    /**
     * Cache istatistikleri
     */
    public function getCacheStats()
    {
        $stats = [
            'opcache' => $this->getOpcacheStatus(),
            'file_cache' => $this->getFileCacheStats()
        ];
        
        return $stats;
    }

    /**
     * File cache istatistikleri
     */
    private function getFileCacheStats()
    {
        $files = glob(self::$cacheDir . '/*.cache');
        $totalSize = 0;
        $validFiles = 0;
        $expiredFiles = 0;
        
        foreach ($files as $file) {
            $size = filesize($file);
            $totalSize += $size;
            
            $cacheData = unserialize(file_get_contents($file));
            if ($cacheData['expiration'] < time()) {
                $expiredFiles++;
            } else {
                $validFiles++;
            }
        }
        
        return [
            'total_files' => count($files),
            'valid_files' => $validFiles,
            'expired_files' => $expiredFiles,
            'total_size' => $totalSize,
            'size_formatted' => $this->formatBytes($totalSize)
        ];
    }

    /**
     * Byte formatlamak için yardımcı fonksiyon
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Expired cache dosyalarını temizle
     */
    public function cleanupExpiredCache()
    {
        $files = glob(self::$cacheDir . '/*.cache');
        $cleaned = 0;
        
        foreach ($files as $file) {
            $cacheData = unserialize(file_get_contents($file));
            if ($cacheData['expiration'] < time()) {
                if (unlink($file)) {
                    $cleaned++;
                }
            }
        }
        
        return [
            'success' => true,
            'cleaned_files' => $cleaned,
            'message' => "$cleaned süresi dolmuş cache dosyası temizlendi"
        ];
    }
}