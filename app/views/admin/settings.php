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
                
                <!-- Ana Sayfa Ayarları -->
                <div class="settings-section">
                    <h3 class="section-title">Ana Sayfa Ayarları</h3>
                    
                    <div class="form-group">
                        <label for="hero_title" class="form-label">Ana Başlık</label>
                        <input type="text" class="form-control" id="hero_title" name="hero_title" 
                               value="<?= htmlspecialchars($hero_title ?? 'Teknoloji Dünyasına Hoş Geldiniz') ?>">
                        <div class="form-text">Ana sayfadaki büyük başlık</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="hero_subtitle" class="form-label">Alt Başlık</label>
                        <textarea class="form-control" id="hero_subtitle" name="hero_subtitle" rows="2"><?= htmlspecialchars($hero_subtitle ?? 'Yazılım, teknoloji trendleri ve dijital dünya hakkında kaliteli içerikler keşfedin.') ?></textarea>
                        <div class="form-text">Ana sayfadaki açıklama metni</div>
                    </div>
                    
                    <div class="preview-section">
                        <div class="preview-title">Önizleme:</div>
                        <h2 style="margin: 0; color: #333;" id="hero_preview_title"><?= htmlspecialchars($hero_title ?? 'Teknoloji Dünyasına Hoş Geldiniz') ?></h2>
                        <p style="margin: 0.5rem 0 0 0; color: #666;" id="hero_preview_subtitle"><?= htmlspecialchars($hero_subtitle ?? 'Yazılım, teknoloji trendleri ve dijital dünya hakkında kaliteli içerikler keşfedin.') ?></p>
                    </div>
                </div>
                
                <!-- Footer Ayarları -->
                <div class="settings-section">
                    <h3 class="section-title">Footer Ayarları</h3>
                    
                    <div class="form-group">
                        <label for="footer_text" class="form-label">Footer Metni</label>
                        <input type="text" class="form-control" id="footer_text" name="footer_text" 
                               value="<?= htmlspecialchars($footer_text ?? '© 2025 Teknoloji Blog - Tüm hakları saklıdır.') ?>">
                        <div class="form-text">Sayfanın altındaki telif hakkı metni</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_email" class="form-label">İletişim E-postası</label>
                        <input type="email" class="form-control" id="contact_email" name="contact_email" 
                               value="<?= htmlspecialchars($contact_email ?? 'info@teknolojiblog.com') ?>">
                        <div class="form-text">Genel iletişim için e-posta adresi</div>
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
// Live preview for hero section
document.getElementById('hero_title').addEventListener('input', function() {
    document.getElementById('hero_preview_title').textContent = this.value || 'Teknoloji Dünyasına Hoş Geldiniz';
});

document.getElementById('hero_subtitle').addEventListener('input', function() {
    document.getElementById('hero_preview_subtitle').textContent = this.value || 'Yazılım, teknoloji trendleri ve dijital dünya hakkında kaliteli içerikler keşfedin.';
});

function previewSite() {
    window.open('/', '_blank');
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['site_title', 'site_description', 'hero_title'];
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
</script>