<!-- Page Header -->
<div class="bg-gradient-to-r from-orange-600 via-red-500 to-orange-700 text-white rounded-2xl p-8 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-cogs mr-3"></i>Site Ayarları
            </h1>
            <p class="text-white/80">Site ayarlarını ve navigasyon menüsünü yönetin.</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-sliders-h text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<style>

.settings-sections {
    display: grid;
    gap: 2rem;
}

.settings-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
}

.section-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #ddd;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
}

.form-text {
    font-size: 0.8rem;
    color: #666;
    margin-top: 0.25rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a6fd8;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.save-section {
    text-align: right;
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

.preview-section {
    background: white;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 1rem;
    margin-top: 1rem;
}

.preview-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #666;
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Navigation -->
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2>Site Ayarları</h2>
        
        <form method="POST">
            <div class="settings-sections">
                <!-- Genel Ayarlar -->
                <div class="settings-section">
                    <h3 class="section-title">Genel Ayarlar</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="site_title" class="form-label">Site Başlığı</label>
                            <input type="text" class="form-control" id="site_title" name="site_title" 
                                   value="<?= htmlspecialchars($site_title ?? 'Teknoloji Blog') ?>">
                            <div class="form-text">Sitenizin ana başlığı</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="site_tagline" class="form-label">Site Sloganı</label>
                            <input type="text" class="form-control" id="site_tagline" name="site_tagline" 
                                   value="<?= htmlspecialchars($site_tagline ?? 'Modern Teknoloji Blogu') ?>">
                            <div class="form-text">Kısa ve akılda kalıcı slogan</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="site_description" class="form-label">Site Açıklaması</label>
                        <textarea class="form-control" id="site_description" name="site_description" rows="3"><?= htmlspecialchars($site_description ?? 'Teknoloji, yazılım ve dijital dünya hakkında güncel içerikler.') ?></textarea>
                        <div class="form-text">SEO için meta açıklama olarak kullanılır</div>
                    </div>
                </div>
                
                <!-- SMTP E-posta Ayarları -->
                <div class="settings-section">
                    <h3 class="section-title">SMTP E-posta Ayarları</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="smtp_host" class="form-label">SMTP Sunucu</label>
                            <input type="text" class="form-control" id="smtp_host" name="smtp_host" 
                                   value="<?= htmlspecialchars($smtp_host ?? '') ?>" placeholder="smtp.gmail.com">
                            <div class="form-text">E-posta gönderim sunucusu</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="smtp_port" class="form-label">SMTP Port</label>
                            <input type="number" class="form-control" id="smtp_port" name="smtp_port" 
                                   value="<?= htmlspecialchars($smtp_port ?? '587') ?>" placeholder="587">
                            <div class="form-text">Genellikle 587 (TLS) veya 465 (SSL)</div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="smtp_username" class="form-label">SMTP Kullanıcı Adı</label>
                            <input type="email" class="form-control" id="smtp_username" name="smtp_username" 
                                   value="<?= htmlspecialchars($smtp_username ?? '') ?>" placeholder="your-email@gmail.com">
                            <div class="form-text">E-posta adresiniz</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="smtp_password" class="form-label">SMTP Şifre</label>
                            <input type="password" class="form-control" id="smtp_password" name="smtp_password" 
                                   value="<?= htmlspecialchars($smtp_password ?? '') ?>" placeholder="Uygulama şifreniz">
                            <div class="form-text">Gmail için uygulama şifresi</div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="smtp_encryption" class="form-label">Şifreleme</label>
                            <select class="form-control" id="smtp_encryption" name="smtp_encryption">
                                <option value="tls" <?= ($smtp_encryption ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS</option>
                                <option value="ssl" <?= ($smtp_encryption ?? 'tls') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                <option value="none" <?= ($smtp_encryption ?? 'tls') === 'none' ? 'selected' : '' ?>>Yok</option>
                            </select>
                            <div class="form-text">Güvenlik protokolü</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="smtp_from_name" class="form-label">Gönderen Adı</label>
                            <input type="text" class="form-control" id="smtp_from_name" name="smtp_from_name" 
                                   value="<?= htmlspecialchars($smtp_from_name ?? $site_title ?? 'Teknoloji Blog') ?>">
                            <div class="form-text">E-postalarda görünecek gönderen adı</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="button" class="btn btn-success" onclick="testSmtpConnection()" id="smtp-test-btn">
                            <i class="fas fa-envelope-open-text mr-2"></i>SMTP Bağlantısını Test Et
                        </button>
                        <button type="button" class="btn btn-primary" onclick="testSmtpConnection(true)" id="smtp-email-test-btn" style="margin-left: 10px; display: none;">
                            <i class="fas fa-paper-plane mr-2"></i>Test E-postası Gönder
                        </button>
                        <div id="smtp-test-result" style="margin-top: 1rem;"></div>
                    </div>
                </div>
                
                <!-- Sistem Ayarları -->
                <div class="settings-section">
                    <h3 class="section-title">Sistem Ayarları</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="timezone" class="form-label">Zaman Dilimi</label>
                            <select class="form-control" id="timezone" name="timezone">
                                <option value="Europe/Istanbul" <?= ($timezone ?? 'Europe/Istanbul') === 'Europe/Istanbul' ? 'selected' : '' ?>>Türkiye (UTC+3)</option>
                                <option value="UTC" <?= ($timezone ?? 'Europe/Istanbul') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                                <option value="Europe/London" <?= ($timezone ?? 'Europe/Istanbul') === 'Europe/London' ? 'selected' : '' ?>>Londra (UTC+0)</option>
                                <option value="America/New_York" <?= ($timezone ?? 'Europe/Istanbul') === 'America/New_York' ? 'selected' : '' ?>>New York (UTC-5)</option>
                            </select>
                            <div class="form-text">Site için varsayılan zaman dilimi</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="date_format" class="form-label">Tarih Formatı</label>
                            <select class="form-control" id="date_format" name="date_format">
                                <option value="d.m.Y" <?= ($date_format ?? 'd.m.Y') === 'd.m.Y' ? 'selected' : '' ?>>31.12.2025</option>
                                <option value="Y-m-d" <?= ($date_format ?? 'd.m.Y') === 'Y-m-d' ? 'selected' : '' ?>>2025-12-31</option>
                                <option value="m/d/Y" <?= ($date_format ?? 'd.m.Y') === 'm/d/Y' ? 'selected' : '' ?>>12/31/2025</option>
                                <option value="d F Y" <?= ($date_format ?? 'd.m.Y') === 'd F Y' ? 'selected' : '' ?>>31 Aralık 2025</option>
                            </select>
                            <div class="form-text">Tarih gösterim formatı</div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="upload_max_size" class="form-label">Maksimum Dosya Boyutu (MB)</label>
                            <input type="number" class="form-control" id="upload_max_size" name="upload_max_size" 
                                   value="<?= htmlspecialchars($upload_max_size ?? '10') ?>" min="1" max="100">
                            <div class="form-text">Yüklenen dosyaların maksimum boyutu</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="posts_per_page" class="form-label">Sayfa Başına Post</label>
                            <input type="number" class="form-control" id="posts_per_page" name="posts_per_page" 
                                   value="<?= htmlspecialchars($posts_per_page ?? '10') ?>" min="5" max="50">
                            <div class="form-text">Blog listesinde gösterilecek post sayısı</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Bakım Modu</label>
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="radio" name="maintenance_mode" value="0" <?= ($maintenance_mode ?? '0') === '0' ? 'checked' : '' ?>>
                                <span>Kapalı</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="radio" name="maintenance_mode" value="1" <?= ($maintenance_mode ?? '0') === '1' ? 'checked' : '' ?>>
                                <span>Açık</span>
                            </label>
                        </div>
                        <div class="form-text">Bakım modunda site sadece adminlere açık olur</div>
                    </div>
                </div>
                
                <!-- SEO Ayarları -->
                <div class="settings-section">
                    <h3 class="section-title">SEO Ayarları</h3>
                    
                    <div class="form-group">
                        <label for="meta_keywords" class="form-label">Ana Anahtar Kelimeler</label>
                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                               value="<?= htmlspecialchars($meta_keywords ?? 'teknoloji, yazılım, blog, programlama, web geliştirme') ?>">
                        <div class="form-text">Virgülle ayrılmış anahtar kelimeler</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="google_analytics" class="form-label">Google Analytics ID</label>
                            <input type="text" class="form-control" id="google_analytics" name="google_analytics" 
                                   value="<?= htmlspecialchars($google_analytics ?? '') ?>" placeholder="G-XXXXXXXXXX">
                            <div class="form-text">Google Analytics takip kodu</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="google_search_console" class="form-label">Google Search Console</label>
                            <input type="text" class="form-control" id="google_search_console" name="google_search_console" 
                                   value="<?= htmlspecialchars($google_search_console ?? '') ?>" placeholder="google1234567890abcdef.html">
                            <div class="form-text">Doğrulama dosyası adı</div>
                        </div>
                    </div>
                </div>
                
                <!-- Sosyal Medya Ayarları -->
                <div class="settings-section">
                    <h3 class="section-title">Sosyal Medya</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="twitter_url" class="form-label">Twitter</label>
                            <input type="url" class="form-control" id="twitter_url" name="twitter_url" 
                                   value="<?= htmlspecialchars($twitter_url ?? '') ?>" placeholder="https://twitter.com/username">
                        </div>
                        
                        <div class="form-group">
                            <label for="linkedin_url" class="form-label">LinkedIn</label>
                            <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" 
                                   value="<?= htmlspecialchars($linkedin_url ?? '') ?>" placeholder="https://linkedin.com/in/username">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="github_url" class="form-label">GitHub</label>
                            <input type="url" class="form-control" id="github_url" name="github_url" 
                                   value="<?= htmlspecialchars($github_url ?? '') ?>" placeholder="https://github.com/username">
                        </div>
                        
                        <div class="form-group">
                            <label for="youtube_url" class="form-label">YouTube</label>
                            <input type="url" class="form-control" id="youtube_url" name="youtube_url" 
                                   value="<?= htmlspecialchars($youtube_url ?? '') ?>" placeholder="https://youtube.com/c/channelname">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="save-section">
                <button type="submit" class="btn btn-primary">Ayarları Kaydet</button>
                <button type="button" class="btn btn-success" onclick="previewSite()">Siteyi Önizle</button>
            </div>
            
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        </form>
        </div>
    </div>
</div>

<script>
function previewSite() {
    window.open('/', '_blank');
}

// SMTP Test Function
function testSmtpConnection(sendTestEmail = false) {
    const resultDiv = document.getElementById('smtp-test-result');
    const testBtn = document.getElementById('smtp-test-btn');
    const emailBtn = document.getElementById('smtp-email-test-btn');
    const activeButton = sendTestEmail ? emailBtn : testBtn;
    
    // Get SMTP settings
    const smtpData = {
        smtp_host: document.getElementById('smtp_host').value,
        smtp_port: document.getElementById('smtp_port').value,
        smtp_username: document.getElementById('smtp_username').value,
        smtp_password: document.getElementById('smtp_password').value,
        smtp_encryption: document.getElementById('smtp_encryption').value,
        smtp_from_name: document.getElementById('smtp_from_name').value,
        send_test_email: sendTestEmail ? 'true' : 'false',
        csrf_token: document.querySelector('input[name="csrf_token"]').value
    };
    
    // Check if required fields are filled
    if (!smtpData.smtp_host || !smtpData.smtp_username || !smtpData.smtp_password) {
        resultDiv.innerHTML = '<div style="color: #dc3545; padding: 0.5rem; background: #f8d7da; border-radius: 4px;">Lütfen SMTP ayarlarını doldurun!</div>';
        return;
    }
    
    // Show loading
    activeButton.disabled = true;
    if (sendTestEmail) {
        activeButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>E-posta gönderiliyor...';
        resultDiv.innerHTML = '<div style="color: #0c5460; padding: 0.5rem; background: #bee5eb; border-radius: 4px;">Test e-postası gönderiliyor...</div>';
    } else {
        activeButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Test ediliyor...';
        resultDiv.innerHTML = '<div style="color: #0c5460; padding: 0.5rem; background: #bee5eb; border-radius: 4px;">SMTP bağlantısı test ediliyor...</div>';
    }
    
    // Send AJAX request
    fetch('/admin/test-smtp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(smtpData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = '<div style="color: #155724; padding: 0.5rem; background: #d4edda; border-radius: 4px;"><i class="fas fa-check mr-2"></i>' + data.message + '</div>';
            
            // İlk test başarılıysa e-posta gönderme butonunu göster
            if (!sendTestEmail && data.success) {
                emailBtn.style.display = 'inline-block';
            }
        } else {
            resultDiv.innerHTML = '<div style="color: #721c24; padding: 0.5rem; background: #f8d7da; border-radius: 4px;"><i class="fas fa-times mr-2"></i>' + data.message + '</div>';
            emailBtn.style.display = 'none';
        }
    })
    .catch(error => {
        resultDiv.innerHTML = '<div style="color: #721c24; padding: 0.5rem; background: #f8d7da; border-radius: 4px;"><i class="fas fa-exclamation-triangle mr-2"></i>Test sırasında hata oluştu!</div>';
        emailBtn.style.display = 'none';
    })
    .finally(() => {
        testBtn.disabled = false;
        emailBtn.disabled = false;
        testBtn.innerHTML = '<i class="fas fa-envelope-open-text mr-2"></i>SMTP Bağlantısını Test Et';
        emailBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Test E-postası Gönder';
    });
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['site_title', 'site_description'];
    let isValid = true;
    
    requiredFields.forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field.value.trim()) {
            field.style.borderColor = '#dc3545';
            isValid = false;
        } else {
            field.style.borderColor = '#ddd';
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Lütfen tüm gerekli alanları doldurun.');
    }
});

// Show current system info
document.addEventListener('DOMContentLoaded', function() {
    // Add PHP info display
    const systemInfo = document.createElement('div');
    systemInfo.innerHTML = `
        <div style="margin-top: 2rem; padding: 1rem; background: #e9ecef; border-radius: 6px; font-size: 0.9rem;">
            <strong>Sistem Bilgileri:</strong><br>
            PHP Versiyonu: <?= PHP_VERSION ?><br>
            Maksimum Upload: <?= ini_get('upload_max_filesize') ?><br>
            Maksimum Post: <?= ini_get('post_max_size') ?><br>
            Memory Limit: <?= ini_get('memory_limit') ?>
        </div>
    `;
    document.querySelector('.save-section').parentNode.insertBefore(systemInfo, document.querySelector('.save-section'));
});
</script>