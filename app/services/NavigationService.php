<?php

class NavigationService
{
    private $navigationModel;

    public function __construct()
    {
        $this->navigationModel = new NavigationMenu();
    }

    /**
     * Kullanıcı rolüne göre navigation menüsü getir
     */
    public function getNavigationForUser($userRole = 'all', $currentUrl = '/')
    {
        $menuItems = $this->navigationModel->getMenuTree($userRole);
        
        // Current URL'e göre active state ekle
        return $this->markActiveItems($menuItems, $currentUrl);
    }

    /**
     * Active menü öğelerini işaretle
     */
    private function markActiveItems($items, $currentUrl)
    {
        foreach ($items as &$item) {
            $item['active'] = ($item['url'] === $currentUrl);
            
            if (!empty($item['children'])) {
                $item['children'] = $this->markActiveItems($item['children'], $currentUrl);
                
                // Eğer alt öğelerden biri aktifse parent'ı da aktif yap
                foreach ($item['children'] as $child) {
                    if ($child['active']) {
                        $item['active'] = true;
                        break;
                    }
                }
            }
        }
        
        return $items;
    }

    /**
     * Admin için tüm menü öğelerini getir
     */
    public function getAllMenuItems()
    {
        return $this->navigationModel->getAllMenuItems();
    }

    /**
     * Menü öğesi oluştur
     */
    public function createMenuItem($data)
    {
        return $this->navigationModel->createMenuItem($data);
    }

    /**
     * Menü öğesi güncelle
     */
    public function updateMenuItem($id, $data)
    {
        return $this->navigationModel->updateMenuItem($id, $data);
    }

    /**
     * Menü öğesi sil
     */
    public function deleteMenuItem($id)
    {
        return $this->navigationModel->deleteMenuItem($id);
    }

    /**
     * Menü öğelerini yeniden sırala
     */
    public function reorderMenuItems($items)
    {
        return $this->navigationModel->reorderMenuItems($items);
    }

    /**
     * Parent seçeneklerini getir
     */
    public function getPossibleParents($excludeId = null)
    {
        return $this->navigationModel->getPossibleParents($excludeId);
    }

    /**
     * Menü öğesi detayını getir
     */
    public function getMenuItem($id)
    {
        return $this->navigationModel->findById($id);
    }

    /**
     * Footer için navigation linklerini getir
     */
    public function getFooterNavigation($limit = 5)
    {
        try {
            $items = $this->navigationModel->getActiveMenuItems('all');
            
            // Sadece ana menü öğelerini al (parent_id null olanlar)
            $footerItems = array_filter($items, function($item) {
                return $item['parent_id'] === null;
            });
            
            // Limit uygula
            return array_slice($footerItems, 0, $limit);
            
        } catch (Exception $e) {
            // Fallback
            return $this->getDefaultNavigation();
        }
    }

    /**
     * Backup navigation (fallback)
     */
    public function getDefaultNavigation($currentUrl = '/')
    {
        return [
            [
                'title' => 'Ana Sayfa',
                'url' => '/',
                'active' => $currentUrl === '/',
                'icon' => 'fas fa-home',
                'children' => []
            ],
            [
                'title' => 'Hakkında',
                'url' => '/about',
                'active' => $currentUrl === '/about',
                'icon' => 'fas fa-info-circle',
                'children' => []
            ],
            [
                'title' => 'İletişim',
                'url' => '/contact',
                'active' => $currentUrl === '/contact',
                'icon' => 'fas fa-envelope',
                'children' => []
            ]
        ];
    }
}