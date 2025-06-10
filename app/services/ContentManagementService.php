<?php

class ContentManagementService
{
    private $siteContentModel;

    public function __construct()
    {
        $this->siteContentModel = new SiteContent();
    }

    /**
     * Site içeriğini getir
     */
    public function getContent($key, $default = '')
    {
        return $this->siteContentModel->getValue($key, $default);
    }

    /**
     * Site içeriğini güncelle
     */
    public function updateContent($key, $value, $type = 'html', $page = null, $section = null)
    {
        return $this->siteContentModel->setValue($key, $value, $type, $page, $section);
    }

    /**
     * Çoklu içerik güncellemesi
     */
    public function updateMultipleContent($contentData)
    {
        $successCount = 0;
        $totalCount = count($contentData);

        foreach ($contentData as $key => $value) {
            if ($this->updateContent($key, $value)) {
                $successCount++;
            }
        }

        return [
            'success' => $successCount === $totalCount,
            'updated' => $successCount,
            'total' => $totalCount,
            'message' => $successCount === $totalCount 
                ? 'Tüm içerikler başarıyla güncellendi!'
                : "{$successCount}/{$totalCount} içerik güncellendi."
        ];
    }

    /**
     * Sayfa için tüm içeriği getir
     */
    public function getPageContent($page)
    {
        return $this->siteContentModel->getByPage($page);
    }

    /**
     * Tüm site içeriğini getir
     */
    public function getAllContent()
    {
        return $this->siteContentModel->getAllContent();
    }

    /**
     * İçerik sil
     */
    public function deleteContent($key)
    {
        return $this->siteContentModel->deleteByKey($key);
    }

    /**
     * Admin düzenleme modunu kontrol et
     */
    public function isEditModeActive()
    {
        return isset($_SESSION['admin_edit_mode']) && $_SESSION['admin_edit_mode'] === true;
    }

    /**
     * Admin düzenleme modunu aç/kapat
     */
    public function toggleEditMode()
    {
        if ($this->isEditModeActive()) {
            $_SESSION['admin_edit_mode'] = false;
            return ['status' => 'disabled', 'message' => 'Düzenleme modu kapatıldı'];
        } else {
            $_SESSION['admin_edit_mode'] = true;
            return ['status' => 'enabled', 'message' => 'Düzenleme modu açıldı'];
        }
    }

    /**
     * Varsayılan site içeriklerini oluştur
     */
    public function createDefaultContent()
    {
        $defaultContent = [
            'site_title' => 'Modern Blog',
            'site_description' => 'Teknoloji, yaşam ve daha fazlası hakkında yazılar',
            'hero_title' => 'Hoş Geldiniz',
            'hero_subtitle' => 'Bu blogda en güncel yazıları bulabilirsiniz',
            'about_title' => 'Hakkımızda',
            'about_content' => 'Bu blog sitesi hakkında bilgiler buraya gelecek...',
            'footer_text' => '© 2025 Modern Blog. Tüm hakları saklıdır.',
            'contact_title' => 'İletişim',
            'contact_content' => 'Bizimle iletişime geçmek için aşağıdaki bilgileri kullanabilirsiniz.',
            'sidebar_about' => 'Bu blog sitesinde teknoloji, yaşam ve birçok konuda yazılar bulabilirsiniz.'
        ];

        $created = 0;
        foreach ($defaultContent as $key => $value) {
            if (!$this->siteContentModel->getByKey($key)) {
                if ($this->updateContent($key, $value)) {
                    $created++;
                }
            }
        }

        return $created;
    }
}
