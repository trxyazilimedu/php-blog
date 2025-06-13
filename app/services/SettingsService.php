<?php

class SettingsService
{
    private $settingModel;
    private static $cache = [];

    public function __construct()
    {
        $this->settingModel = new SiteSetting();
    }

    /**
     * Ayar değeri getir (cache ile)
     */
    public function get($key, $default = null)
    {
        if (!isset(self::$cache[$key])) {
            self::$cache[$key] = $this->settingModel->get($key, $default);
        }
        return self::$cache[$key];
    }

    /**
     * Ayar değeri kaydet
     */
    public function set($key, $value, $type = 'text', $description = '', $category = 'general')
    {
        // Cache'i temizle
        unset(self::$cache[$key]);
        
        return $this->settingModel->set($key, $value, $type, $description, $category);
    }

    /**
     * Birden çok ayarı güncelle
     */
    public function updateSettings($settings)
    {
        // Cache'i temizle
        self::$cache = [];
        
        return $this->settingModel->updateSettings($settings);
    }

    /**
     * Tüm ayarları getir
     */
    public function getAllSettings()
    {
        if (empty(self::$cache)) {
            self::$cache = $this->settingModel->getAllSettings();
        }
        return self::$cache;
    }

    /**
     * Kategoriye göre ayarları getir
     */
    public function getByCategory($category)
    {
        return $this->settingModel->getByCategory($category);
    }

    /**
     * Boolean değer getir
     */
    public function getBoolean($key, $default = false)
    {
        return $this->settingModel->getBoolean($key, $default);
    }

    /**
     * Integer değer getir
     */
    public function getInteger($key, $default = 0)
    {
        return $this->settingModel->getInteger($key, $default);
    }

    /**
     * Site başlığını getir
     */
    public function getSiteTitle()
    {
        return $this->get('site_title', 'Teknoloji Bloğum');
    }

    /**
     * Site sloganını getir
     */
    public function getSiteTagline()
    {
        return $this->get('site_tagline', 'Modern Teknoloji Blogu');
    }

    /**
     * Site açıklamasını getir
     */
    public function getSiteDescription()
    {
        return $this->get('site_description', 'Teknoloji, yazılım ve dijital dünya hakkında güncel içerikler.');
    }

    /**
     * SMTP ayarlarını getir
     */
    public function getSmtpSettings()
    {
        return [
            'host' => $this->get('smtp_host'),
            'port' => $this->getInteger('smtp_port', 587),
            'username' => $this->get('smtp_username'),
            'password' => $this->get('smtp_password'),
            'encryption' => $this->get('smtp_encryption', 'tls'),
            'from_name' => $this->get('smtp_from_name', $this->getSiteTitle())
        ];
    }

    /**
     * Sosyal medya linklerini getir
     */
    public function getSocialLinks()
    {
        return [
            'twitter' => $this->get('twitter_url'),
            'linkedin' => $this->get('linkedin_url'),
            'github' => $this->get('github_url'),
            'youtube' => $this->get('youtube_url')
        ];
    }

    /**
     * SEO ayarlarını getir
     */
    public function getSeoSettings()
    {
        return [
            'meta_keywords' => $this->get('meta_keywords'),
            'google_analytics' => $this->get('google_analytics'),
            'google_search_console' => $this->get('google_search_console')
        ];
    }

    /**
     * Sistem ayarlarını getir
     */
    public function getSystemSettings()
    {
        return [
            'timezone' => $this->get('timezone', 'Europe/Istanbul'),
            'date_format' => $this->get('date_format', 'd.m.Y'),
            'upload_max_size' => $this->getInteger('upload_max_size', 10),
            'posts_per_page' => $this->getInteger('posts_per_page', 10),
            'maintenance_mode' => $this->getBoolean('maintenance_mode', false)
        ];
    }

    /**
     * Bakım modu kontrolü
     */
    public function isMaintenanceMode()
    {
        return $this->getBoolean('maintenance_mode', false);
    }

    /**
     * Cache temizle
     */
    public function clearCache()
    {
        self::$cache = [];
    }

}