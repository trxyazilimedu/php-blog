<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private $settingsService;
    private $smtpSettings;

    public function __construct()
    {
        $this->settingsService = new SettingsService();
        $this->smtpSettings = $this->settingsService->getSmtpSettings();
    }

    /**
     * E-posta gönder
     */
    public function send($to, $subject, $body, $altBody = '', $attachments = [])
    {
        try {
            // SMTP ayarları kontrolü
            if (empty($this->smtpSettings['host']) || empty($this->smtpSettings['username'])) {
                return [
                    'success' => false,
                    'message' => 'SMTP ayarları yapılandırılmamış!'
                ];
            }

            return $this->sendWithPHPMailer($to, $subject, $body, $altBody, $attachments);

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'E-posta gönderim hatası: ' . $e->getMessage()
            ];
        }
    }

    /**
     * PHPMailer ile e-posta gönder
     */
    private function sendWithPHPMailer($to, $subject, $body, $altBody = '', $attachments = [])
    {
        try {
            $mail = new PHPMailer(true);

            // SMTP Ayarları
            $mail->isSMTP();
            $mail->Host = $this->smtpSettings['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpSettings['username'];
            $mail->Password = $this->smtpSettings['password'];
            
            // Şifreleme ayarı
            if ($this->smtpSettings['encryption'] === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($this->smtpSettings['encryption'] === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            
            $mail->Port = $this->smtpSettings['port'];

            // Karakter seti
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // Debug (production'da kapatılmalı)
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;

            // Gönderen bilgileri
            $mail->setFrom($this->smtpSettings['username'], $this->smtpSettings['from_name']);
            $mail->addReplyTo($this->smtpSettings['username'], $this->smtpSettings['from_name']);

            // Alıcı bilgileri
            if (is_array($to)) {
                foreach ($to as $recipient) {
                    $mail->addAddress($recipient);
                }
            } else {
                $mail->addAddress($to);
            }

            // İçerik ayarları
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            
            if (!empty($altBody)) {
                $mail->AltBody = $altBody;
            }

            // Ek dosyalar
            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    if (is_array($attachment)) {
                        $mail->addAttachment($attachment['path'], $attachment['name'] ?? '');
                    } else {
                        $mail->addAttachment($attachment);
                    }
                }
            }

            // E-postayı gönder
            $mail->send();

            return [
                'success' => true,
                'message' => 'E-posta başarıyla gönderildi!'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'E-posta gönderim hatası: ' . $e->getMessage()
            ];
        }
    }

    /**
     * SMTP bağlantısını test et
     */
    public function testConnection($smtpSettings = null)
    {
        if ($smtpSettings === null) {
            $smtpSettings = $this->smtpSettings;
        }

        try {
            $mail = new PHPMailer(true);

            // SMTP Ayarları
            $mail->isSMTP();
            $mail->Host = $smtpSettings['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $smtpSettings['username'];
            $mail->Password = $smtpSettings['password'];
            
            // Şifreleme ayarı
            if ($smtpSettings['encryption'] === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($smtpSettings['encryption'] === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            
            $mail->Port = $smtpSettings['port'];

            // Timeout ayarları
            $mail->Timeout = 10;
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            // Debug mode açık
            $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
            
            // Buffer'ı temizle ve output'u yakala
            ob_start();
            
            // SMTP bağlantısını test et
            if ($mail->smtpConnect()) {
                $mail->smtpClose();
                
                // Debug output'unu temizle
                $debugOutput = ob_get_clean();
                
                return [
                    'success' => true,
                    'message' => 'SMTP bağlantısı başarılı! Host: ' . $smtpSettings['host'] . ':' . $smtpSettings['port']
                ];
            } else {
                $debugOutput = ob_get_clean();
                return [
                    'success' => false,
                    'message' => 'SMTP bağlantısı başarısız!'
                ];
            }

        } catch (Exception $e) {
            // Buffer'ı temizle
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            return [
                'success' => false,
                'message' => 'SMTP bağlantı hatası: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Test e-postası gönder
     */
    public function sendTestEmail($toEmail = null)
    {
        if ($toEmail === null) {
            $toEmail = $this->smtpSettings['username'];
        }

        $subject = 'SMTP Test - ' . $this->settingsService->getSiteTitle();
        $body = $this->generateTestEmailBody();

        return $this->send($toEmail, $subject, $body);
    }

    /**
     * Test e-postası HTML içeriği
     */
    private function generateTestEmailBody()
    {
        $siteTitle = $this->settingsService->getSiteTitle();
        $currentTime = date('d.m.Y H:i:s');
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>SMTP Test</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { color: #2563eb; margin-bottom: 20px; }
                .info-box { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .footer { border-top: 1px solid #dee2e6; margin-top: 30px; padding-top: 15px; font-size: 12px; color: #6c757d; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2 class='header'>🎉 SMTP Test Başarılı!</h2>
                <p>Merhaba,</p>
                <p>Bu e-posta <strong>{$siteTitle}</strong> sitesinden SMTP ayarlarınızı test etmek için gönderilmiştir.</p>
                
                <div class='info-box'>
                    <h3 style='margin-top: 0; color: #495057;'>Test Detayları:</h3>
                    <ul style='margin: 0;'>
                        <li><strong>Gönderim Zamanı:</strong> {$currentTime}</li>
                        <li><strong>SMTP Host:</strong> {$this->smtpSettings['host']}</li>
                        <li><strong>SMTP Port:</strong> {$this->smtpSettings['port']}</li>
                        <li><strong>Şifreleme:</strong> {$this->smtpSettings['encryption']}</li>
                        <li><strong>Kullanıcı:</strong> {$this->smtpSettings['username']}</li>
                    </ul>
                </div>
                
                <p>Eğer bu e-postayı alıyorsanız, SMTP ayarlarınız doğru şekilde yapılandırılmıştır ve PHPMailer başarıyla çalışmaktadır.</p>
                
                <div class='footer'>
                    <p>Bu e-posta {$siteTitle} tarafından otomatik olarak gönderilmiştir.<br>
                    Bu e-postayı yanıtlamanıza gerek yoktur.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Hoş geldin e-postası gönder
     */
    public function sendWelcomeEmail($userEmail, $userName)
    {
        $siteTitle = $this->settingsService->getSiteTitle();
        $subject = "{$siteTitle} - Hoş Geldiniz!";
        
        $body = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Hoş Geldiniz</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { color: #2563eb; margin-bottom: 20px; }
                .info-box { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .footer { border-top: 1px solid #dee2e6; margin-top: 30px; padding-top: 15px; font-size: 12px; color: #6c757d; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2 class='header'>🎉 {$siteTitle} ailesine hoş geldiniz!</h2>
                <p>Merhaba <strong>{$userName}</strong>,</p>
                <p>Kaydınız başarıyla tamamlanmıştır. Artık sitemizin tüm özelliklerinden faydalanabilirsiniz.</p>
                
                <div class='info-box'>
                    <h3 style='margin-top: 0; color: #495057;'>Neler yapabilirsiniz?</h3>
                    <ul style='margin: 0;'>
                        <li>Blog yazılarını okuyabilir ve yorum yapabilirsiniz</li>
                        <li>Profil bilgilerinizi güncelleyebilirsiniz</li>
                        <li>Yazar olmak için başvurabilirsiniz</li>
                    </ul>
                </div>
                
                <p>Sorularınız için bizimle iletişime geçebilirsiniz.</p>
                <p>İyi günler dileriz!</p>
                
                <div class='footer'>
                    <p>Bu e-posta {$siteTitle} tarafından otomatik olarak gönderilmiştir.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return $this->send($userEmail, $subject, $body);
    }

    /**
     * Şifre sıfırlama e-postası gönder
     */
    public function sendPasswordResetEmail($userEmail, $userName, $resetToken)
    {
        $siteTitle = $this->settingsService->getSiteTitle();
        $subject = "{$siteTitle} - Şifre Sıfırlama";
        $resetLink = "https://your-domain.com/reset-password?token=" . $resetToken;
        
        $body = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Şifre Sıfırlama</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { color: #dc3545; margin-bottom: 20px; }
                .button { background: #dc3545; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; }
                .footer { border-top: 1px solid #dee2e6; margin-top: 30px; padding-top: 15px; font-size: 12px; color: #6c757d; }
                .warning { font-size: 14px; color: #6c757d; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2 class='header'>🔐 Şifre Sıfırlama Talebi</h2>
                <p>Merhaba <strong>{$userName}</strong>,</p>
                <p>Şifrenizi sıfırlamak için bir talep aldık. Aşağıdaki linke tıklayarak yeni şifrenizi belirleyebilirsiniz:</p>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$resetLink}' class='button'>Şifremi Sıfırla</a>
                </div>
                
                <p class='warning'>
                    Bu linkin geçerlilik süresi 24 saattir. Eğer şifre sıfırlama talebinde bulunmadıysanız bu e-postayı görmezden gelebilirsiniz.
                </p>
                
                <div class='footer'>
                    <p>Bu e-posta {$siteTitle} tarafından otomatik olarak gönderilmiştir.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return $this->send($userEmail, $subject, $body);
    }

    /**
     * Yorum onaylandı bildirimi
     */
    public function sendCommentApprovedEmail($userEmail, $userName, $postTitle, $postUrl)
    {
        $siteTitle = $this->settingsService->getSiteTitle();
        $subject = "{$siteTitle} - Yorumunuz Onaylandı";
        
        $body = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Yorum Onaylandı</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { color: #28a745; margin-bottom: 20px; }
                .button { background: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; }
                .footer { border-top: 1px solid #dee2e6; margin-top: 30px; padding-top: 15px; font-size: 12px; color: #6c757d; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2 class='header'>✅ Yorumunuz Onaylandı!</h2>
                <p>Merhaba <strong>{$userName}</strong>,</p>
                <p><strong>{$postTitle}</strong> başlıklı yazıya yaptığınız yorum onaylanmış ve yayınlanmıştır.</p>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$postUrl}' class='button'>Yorumu Görüntüle</a>
                </div>
                
                <p>Katkınız için teşekkür ederiz!</p>
                
                <div class='footer'>
                    <p>Bu e-posta {$siteTitle} tarafından otomatik olarak gönderilmiştir.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return $this->send($userEmail, $subject, $body);
    }

    /**
     * İletişim formu e-postası
     */
    public function sendContactFormEmail($name, $email, $subject, $message)
    {
        $siteTitle = $this->settingsService->getSiteTitle();
        $adminEmail = $this->smtpSettings['username'];
        $emailSubject = "{$siteTitle} - İletişim Formu: {$subject}";
        
        $body = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>İletişim Formu</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { color: #2563eb; margin-bottom: 20px; }
                .info-box { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .message-box { background: #fff; border: 1px solid #dee2e6; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .footer { border-top: 1px solid #dee2e6; margin-top: 30px; padding-top: 15px; font-size: 12px; color: #6c757d; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2 class='header'>📧 Yeni İletişim Formu Mesajı</h2>
                
                <div class='info-box'>
                    <h3 style='margin-top: 0; color: #495057;'>Gönderen Bilgileri:</h3>
                    <ul style='margin: 0;'>
                        <li><strong>Ad Soyad:</strong> {$name}</li>
                        <li><strong>E-posta:</strong> {$email}</li>
                        <li><strong>Konu:</strong> {$subject}</li>
                        <li><strong>Tarih:</strong> " . date('d.m.Y H:i:s') . "</li>
                    </ul>
                </div>
                
                <div class='message-box'>
                    <h3 style='margin-top: 0; color: #495057;'>Mesaj:</h3>
                    <p>" . nl2br(htmlspecialchars($message)) . "</p>
                </div>
                
                <div class='footer'>
                    <p>Bu e-posta {$siteTitle} iletişim formundan otomatik olarak gönderilmiştir.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return $this->send($adminEmail, $emailSubject, $body);
    }

    /**
     * SMTP ayarlarını güncelle
     */
    public function updateSmtpSettings($settings)
    {
        $this->smtpSettings = $settings;
    }

    /**
     * Ayarları doğrula
     */
    public function validateSettings($settings = null)
    {
        if ($settings === null) {
            $settings = $this->smtpSettings;
        }

        $errors = [];

        if (empty($settings['host'])) {
            $errors[] = 'SMTP sunucu adresi gerekli';
        }

        if (empty($settings['port']) || !is_numeric($settings['port'])) {
            $errors[] = 'Geçerli bir SMTP port numarası gerekli';
        }

        if (empty($settings['username'])) {
            $errors[] = 'SMTP kullanıcı adı gerekli';
        }

        if (empty($settings['password'])) {
            $errors[] = 'SMTP şifre gerekli';
        }

        if (!in_array($settings['encryption'], ['tls', 'ssl', 'none'])) {
            $errors[] = 'Geçerli bir şifreleme türü seçin';
        }

        return empty($errors) ? ['valid' => true] : ['valid' => false, 'errors' => $errors];
    }
}