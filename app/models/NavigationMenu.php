<?php

class NavigationMenu extends Model
{
    protected $table = 'navigation_menu';

    /**
     * Aktif menü öğelerini getir
     */
    public function getActiveMenuItems($userRole = 'all')
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1";
        $params = [];
        
        // Role hierarchy logic
        if ($userRole === 'admin') {
            // Admin sees everything - no additional filter needed
        } elseif ($userRole === 'writer') {
            // Writer sees: all, user, writer
            $sql .= " AND permission_role IN ('all', 'user', 'writer')";
        } elseif ($userRole === 'user') {
            // User sees: all, user
            $sql .= " AND permission_role IN ('all', 'user')";
        } else {
            // Guest/not logged in: only 'all'
            $sql .= " AND permission_role = 'all'";
        }
        
        $sql .= " ORDER BY sort_order ASC";
        
        return $this->query($sql, $params);
    }

    /**
     * Tüm menü öğelerini getir (admin için)
     */
    public function getAllMenuItems()
    {
        $sql = "SELECT nm.*, parent.title as parent_title 
                FROM {$this->table} nm
                LEFT JOIN {$this->table} parent ON nm.parent_id = parent.id
                ORDER BY nm.sort_order ASC";
        
        return $this->query($sql);
    }

    /**
     * Hierarchical menu structure oluştur
     */
    public function getMenuTree($userRole = 'all')
    {
        $items = $this->getActiveMenuItems($userRole);
        $tree = [];
        $lookup = [];

        // İlk önce tüm öğeleri lookup array'ine ekle
        foreach ($items as $item) {
            $item['children'] = [];
            $lookup[$item['id']] = $item;
        }

        // Parent-child ilişkilerini kur
        foreach ($lookup as $item) {
            if ($item['parent_id'] === null) {
                $tree[] = $item;
            } else {
                if (isset($lookup[$item['parent_id']])) {
                    $lookup[$item['parent_id']]['children'][] = $item;
                }
            }
        }

        return $tree;
    }

    /**
     * Menü öğesi oluştur
     */
    public function createMenuItem($data)
    {
        // Validation
        if (empty($data['title']) || empty($data['url'])) {
            return false;
        }

        // Sort order otomatik ayarla
        if (!isset($data['sort_order'])) {
            $maxOrder = $this->getMaxSortOrder();
            $data['sort_order'] = $maxOrder + 1;
        }

        return $this->create($data);
    }

    /**
     * Menü öğesi güncelle
     */
    public function updateMenuItem($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Menü öğesi sil
     */
    public function deleteMenuItem($id)
    {
        // Alt öğeleri de sil (CASCADE)
        return $this->delete($id);
    }

    /**
     * En yüksek sort order'ı getir
     */
    private function getMaxSortOrder()
    {
        $sql = "SELECT MAX(sort_order) as max_order FROM {$this->table}";
        $result = $this->query($sql);
        return $result ? (int)$result[0]['max_order'] : 0;
    }

    /**
     * Menü öğelerini yeniden sırala
     */
    public function reorderMenuItems($items)
    {
        foreach ($items as $order => $id) {
            $this->update($id, ['sort_order' => $order + 1]);
        }
        return true;
    }

    /**
     * Parent seçeneklerini getir
     */
    public function getPossibleParents($excludeId = null)
    {
        $sql = "SELECT id, title FROM {$this->table} WHERE parent_id IS NULL";
        $params = [];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $sql .= " ORDER BY sort_order ASC";
        
        return $this->query($sql, $params);
    }
}