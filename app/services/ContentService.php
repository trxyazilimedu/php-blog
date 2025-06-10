<?php

/**
 * Site Content Service
 * Düzenlenebilir site içeriği yönetimi
 */
class ContentService
{
    private $db;
    private $contentModel;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->contentModel = new SiteContent();
    }

    /**
     * İçeriği key ile getir
     */
    public function getContent($key, $default = '')
    {
        $sql = "SELECT content_value FROM site_content WHERE content_key = ?";
        $result = $this->contentModel->query($sql, [$key]);
        
        if (empty($result)) {
            return $default;
        }
        
        return $result[0]['content_value'];
    }

    /**
     * İçerik güncelle veya oluştur
     */
    public function updateContent($key, $value, $page = 'general', $section = null, $type = 'html', $userId = null)
    {
        try {
            // Mevcut içeriği kontrol et
            $existing = $this->contentModel->query("SELECT id FROM site_content WHERE content_key = ?", [$key]);
            
            if (!empty($existing)) {
                // Güncelle
                $updateData = [
                    'content_value' => $value,
                    'content_type' => $type,
                    'updated_by' => $userId
                ];
                
                $this->contentModel->update($existing[0]['id'], $updateData);
            } else {
                // Yeni oluştur
                $createData = [
                    'content_key' => $key,
                    'content_value' => $value,
                    'content_type' => $type,
                    'page' => $page,
                    'section' => $section,
                    'updated_by' => $userId
                ];
                
                $this->contentModel->create($createData);
            }

            return [
                'success' => true,
                'message' => 'İçerik başarıyla güncellendi!'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'İçerik güncellenirken hata oluştu: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Toplu içerik güncelleme
     */
    public function updateMultipleContent($contents, $userId = null)
    {
        try {
            $this->db->beginTransaction();
            
            $updated = 0;
            $errors = [];
            
            foreach ($contents as $key => $data) {
                $value = $data['value'] ?? '';
                $page = $data['page'] ?? 'general';
                $section = $data['section'] ?? null;
                $type = $data['type'] ?? 'html';
                
                $result = $this->updateContent($key, $value, $page, $section, $type, $userId);
                
                if ($result['success']) {
                    $updated++;
                } else {
                    $errors[] = "Key: {$key} - " . $result['message'];
                }
            }
            
            $this->db->commit();
            
            return [
                'success' => true,
                'updated' => $updated,
                'errors' => $errors,
                'message' => "{$updated} içerik başarıyla güncellendi!"
            ];

        } catch (Exception $e) {
            $this->db->rollBack();
            return [
                'success' => false,
                'message' => 'Toplu güncelleme sırasında hata oluştu: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Düzenlenebilir içerik wrapper'ı oluştur
     */
    public function editableContent($key, $default = '', $tag = 'div', $page = 'general', $section = null)
    {
        $content = $this->getContent($key, $default);
        
        // Admin girişi yapılmışsa düzenlenebilir yap
        $isAdmin = false;
        if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
            $isAdmin = ($_SESSION['user_role'] === 'admin');
        }
        
        if ($isAdmin) {
            $attributes = [
                'data-editable' => 'true',
                'data-key' => $key,
                'data-page' => $page,
                'data-section' => $section ?: '',
                'contenteditable' => 'true',
                'class' => 'editable-content'
            ];
            
            $attributeString = '';
            foreach ($attributes as $attr => $value) {
                $attributeString .= ' ' . $attr . '="' . htmlspecialchars($value) . '"';
            }
            
            return "<{$tag}{$attributeString}>{$content}</{$tag}>";
        } else {
            return "<{$tag}>{$content}</{$tag}>";
        }
    }

    /**
     * Admin edit modu CSS ve JS
     */
    public function getEditModeAssets()
    {
        $css = '
        <style>
        .editable-content {
            position: relative;
            min-height: 20px;
            padding: 5px;
            border: 2px dashed transparent;
            transition: all 0.3s ease;
        }
        
        .editable-content:hover {
            border-color: #007bff;
            background-color: rgba(0, 123, 255, 0.1);
        }
        
        .editable-content:focus {
            outline: none;
            border-color: #28a745;
            background-color: rgba(40, 167, 69, 0.1);
        }
        
        #admin-edit-panel {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            z-index: 9999;
            min-width: 200px;
        }
        </style>';
        
        $js = '
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.location.search.includes("edit=1")) {
                const editPanel = document.createElement("div");
                editPanel.id = "admin-edit-panel";
                editPanel.innerHTML = `
                    <div style="background: #28a745; color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px; margin-bottom: 10px;">🔧 Edit Mode</div>
                    <button onclick="saveAllChanges()" style="background: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 4px; margin-right: 5px;">💾 Kaydet</button>
                    <div id="save-status" style="margin-top: 10px; font-size: 12px;"></div>
                `;
                document.body.appendChild(editPanel);
            }
            
            const editableElements = document.querySelectorAll(".editable-content");
            editableElements.forEach(function(element) {
                element.addEventListener("blur", function() {
                    saveContent(element);
                });
            });
        });
        
        function saveContent(element) {
            const key = element.getAttribute("data-key");
            const value = element.innerHTML;
            const page = element.getAttribute("data-page") || "general";
            const section = element.getAttribute("data-section") || "";
            
            fetch("/admin/content/update", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    key: key,
                    value: value,
                    page: page,
                    section: section,
                    csrf_token: window.csrfToken
                })
            })
            .then(response => response.json())
            .then(data => {
                const status = document.getElementById("save-status");
                if (status) {
                    if (data.success) {
                        status.innerHTML = `<span style="color: green;">✅ Kaydedildi</span>`;
                        setTimeout(() => { status.innerHTML = ""; }, 2000);
                    } else {
                        status.innerHTML = `<span style="color: red;">❌ Hata</span>`;
                    }
                }
            });
        }
        </script>';
        
        return ['css' => $css, 'js' => $js];
    }

    /**
     * Tüm içerikleri getir
     */
    public function getAllContent()
    {
        $sql = "SELECT * FROM site_content ORDER BY page, section, content_key";
        return $this->contentModel->query($sql);
    }

    /**
     * Site ayarını getir
     */
    public function getSetting($key, $default = null)
    {
        $sql = "SELECT setting_value, setting_type FROM site_settings WHERE setting_key = ?";
        $result = $this->db->prepare($sql);
        $result->execute([$key]);
        $row = $result->fetch();
        
        if (!$row) {
            return $default;
        }
        
        return $this->convertSettingValue($row['setting_value'], $row['setting_type']);
    }

    /**
     * Ayar değerini tipine göre dönüştür
     */
    private function convertSettingValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return in_array(strtolower($value), ['true', '1', 'yes', 'on']);
            case 'number':
                return is_numeric($value) ? (int)$value : 0;
            case 'json':
                return json_decode($value, true) ?? [];
            default:
                return $value;
        }
    }
}
