<?php

class SiteContent extends Model
{
    protected $table = 'site_content';

    public function getByKey($key)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE content_key = ?");
        $stmt->execute([$key]);
        return $stmt->fetch();
    }

    public function getValue($key, $default = '')
    {
        $content = $this->getByKey($key);
        return $content ? $content['content_value'] : $default;
    }

    public function setValue($key, $value, $type = 'html', $page = null, $section = null)
    {
        $existing = $this->getByKey($key);
        
        if ($existing) {
            return $this->updateByKey($key, $value);
        } else {
            return $this->create([
                'content_key' => $key,
                'content_value' => $value,
                'content_type' => $type,
                'page' => $page,
                'section' => $section
            ]);
        }
    }

    public function updateByKey($key, $value)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET content_value = ?, updated_at = NOW() 
            WHERE content_key = ?
        ");
        return $stmt->execute([$value, $key]);
    }

    public function getByPage($page)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE page = ? ORDER BY section, content_key");
        $stmt->execute([$page]);
        return $stmt->fetchAll();
    }

    public function getAllContent()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY page, section, content_key");
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        $content = [];
        foreach ($results as $item) {
            $content[$item['content_key']] = $item['content_value'];
        }
        
        return $content;
    }

    public function deleteByKey($key)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE content_key = ?");
        return $stmt->execute([$key]);
    }
}
