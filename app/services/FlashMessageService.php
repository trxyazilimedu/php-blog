<?php

class FlashMessageService
{
    public function __construct()
    {
        // Session zaten başlatılmış durumda
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Flash mesaj ekleme
     */
    public function add($type, $message)
    {
        $flashMessages = $_SESSION['flash_messages'] ?? [];
        
        if (!isset($flashMessages[$type])) {
            $flashMessages[$type] = [];
        }
        
        $flashMessages[$type][] = $message;
        $_SESSION['flash_messages'] = $flashMessages;
    }

    /**
     * Başarı mesajı ekleme
     */
    public function success($message)
    {
        $this->add('success', $message);
    }

    /**
     * Hata mesajı ekleme
     */
    public function error($message)
    {
        $this->add('error', $message);
    }

    /**
     * Uyarı mesajı ekleme
     */
    public function warning($message)
    {
        $this->add('warning', $message);
    }

    /**
     * Bilgi mesajı ekleme
     */
    public function info($message)
    {
        $this->add('info', $message);
    }

    /**
     * Tüm flash mesajları alma ve temizleme
     */
    public function getAll()
    {
        $flashMessages = $_SESSION['flash_messages'] ?? [];
        $_SESSION['flash_messages'] = [];
        return $flashMessages;
    }

    /**
     * Belirli tip flash mesajları alma
     */
    public function get($type)
    {
        $allMessages = $this->getAll();
        return $allMessages[$type] ?? [];
    }

    /**
     * Flash mesaj var mı kontrolü
     */
    public function has($type = null)
    {
        $flashMessages = $_SESSION['flash_messages'] ?? [];
        
        if ($type === null) {
            return !empty($flashMessages);
        }
        
        return isset($flashMessages[$type]) && !empty($flashMessages[$type]);
    }

    /**
     * Flash mesajları temizleme
     */
    public function clear($type = null)
    {
        if ($type === null) {
            $_SESSION['flash_messages'] = [];
        } else {
            $flashMessages = $_SESSION['flash_messages'] ?? [];
            unset($flashMessages[$type]);
            $_SESSION['flash_messages'] = $flashMessages;
        }
    }

    /**
     * HTML formatında mesajları alma
     */
    public function renderHtml()
    {
        $messages = $this->getAll();
        $html = '';

        foreach ($messages as $type => $typeMessages) {
            foreach ($typeMessages as $message) {
                $alertClass = $this->getAlertClass($type);
                $html .= "<div class=\"alert alert-{$alertClass} alert-dismissible\">";
                $html .= "<button type=\"button\" class=\"close\" onclick=\"this.parentElement.remove()\">&times;</button>";
                $html .= htmlspecialchars($message);
                $html .= "</div>";
            }
        }

        return $html;
    }

    /**
     * Alert CSS sınıfı alma
     */
    private function getAlertClass($type)
    {
        $classes = [
            'success' => 'success',
            'error' => 'danger',
            'warning' => 'warning',
            'info' => 'info'
        ];

        return $classes[$type] ?? 'info';
    }

    /**
     * JavaScript formatında mesajları alma
     */
    public function renderJs()
    {
        $messages = $this->getAll();
        $js = '';

        foreach ($messages as $type => $typeMessages) {
            foreach ($typeMessages as $message) {
                $js .= "showAlert('{$type}', '" . addslashes($message) . "');\n";
            }
        }

        return $js;
    }
}
