<?php

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    /**
     * Ayar değeri getir
     */
    public function get($key, $default = null)
    {
        $setting = $this->findByKey($key);
        return $setting ? $setting['setting_value'] : $default;
    }

    /**
     * Ayar değeri kaydet
     */
    public function set($key, $value, $type = 'text', $description = '', $category = 'general')
    {
        $existing = $this->findByKey($key);
        
        if ($existing) {
            return $this->update($existing['id'], [
                'setting_value' => $value,
                'setting_type' => $type
            ]);
        } else {
            return $this->create([
                'setting_key' => $key,
                'setting_value' => $value,
                'setting_type' => $type,
                'description' => $description,
                'category' => $category
            ]);
        }
    }

    /**
     * Key'e göre ayar bul
     */
    public function findByKey($key)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE setting_key = ?");
        $stmt->execute([$key]);
        return $stmt->fetch();
    }

    /**
     * Kategoriye göre ayarları getir
     */
    public function getByCategory($category)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE category = ? ORDER BY setting_key");
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    }

    /**
     * Tüm ayarları key-value array olarak getir
     */
    public function getAllSettings()
    {
        $settings = [];
        $results = $this->query("SELECT setting_key, setting_value FROM {$this->table}");
        
        foreach ($results as $result) {
            $settings[$result['setting_key']] = $result['setting_value'];
        }
        
        return $settings;
    }

    /**
     * Birden çok ayarı toplu güncelle
     */
    public function updateSettings($settings)
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value);
        }
        return true;
    }

    /**
     * Boolean değer kontrolü
     */
    public function getBoolean($key, $default = false)
    {
        $value = $this->get($key, $default);
        return in_array(strtolower($value), ['1', 'true', 'yes', 'on']);
    }

    /**
     * Integer değer kontrolü
     */
    public function getInteger($key, $default = 0)
    {
        return (int) $this->get($key, $default);
    }

    /**
     * JSON değer kontrolü
     */
    public function getJson($key, $default = [])
    {
        $value = $this->get($key);
        if ($value) {
            $decoded = json_decode($value, true);
            return $decoded !== null ? $decoded : $default;
        }
        return $default;
    }

    /**
     * Hassas bilgileri filtrele (şifreler vb.)
     */
    public function getSafeSettings()
    {
        $settings = [];
        $results = $this->query("SELECT setting_key, setting_value FROM {$this->table} WHERE is_sensitive = 0");
        
        foreach ($results as $result) {
            $settings[$result['setting_key']] = $result['setting_value'];
        }
        
        return $settings;
    }
}