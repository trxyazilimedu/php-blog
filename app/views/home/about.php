<h1><?= htmlspecialchars($page_title ?? 'Hakkında') ?></h1>

<p style="font-size: 1.1rem; color: #666; margin-bottom: 2rem;">
    <?= htmlspecialchars($content) ?>
</p>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin: 2rem 0;">
    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border: 1px solid #e9ecef;">
        <h3 style="color: #495057; margin-bottom: 1rem;">📋 Framework Bilgileri</h3>
        <div style="space-y: 0.5rem;">
            <div style="margin-bottom: 0.5rem;">
                <strong>Versiyon:</strong> <?= htmlspecialchars($framework_info['version']) ?>
            </div>
            <div style="margin-bottom: 0.5rem;">
                <strong>Geliştirici:</strong> <?= htmlspecialchars($framework_info['author']) ?>
            </div>
            <div style="margin-bottom: 0.5rem;">
                <strong>Lisans:</strong> <?= htmlspecialchars($framework_info['license']) ?>
            </div>
            <div>
                <strong>GitHub:</strong> 
                <a href="<?= htmlspecialchars($framework_info['github']) ?>" target="_blank" style="color: #007bff;">
                    <?= htmlspecialchars($framework_info['github']) ?>
                </a>
            </div>
        </div>
    </div>
    
    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; border: 1px solid #e9ecef;">
        <h3 style="color: #495057; margin-bottom: 1rem;">🏗️ Framework Yapısı</h3>
        <pre style="background: #fff; padding: 1rem; border-radius: 6px; font-size: 0.85rem; overflow-x: auto; color: #333;">
simple-framework/
├── app/
│   ├── controllers/     # Controller sınıfları
│   ├── models/         # Model sınıfları
│   ├── views/          # View dosyaları
│   ├── services/       # Service katmanı
│   └── config/         # Konfigürasyon
├── core/               # Framework çekirdeği
│   ├── App.php
│   ├── BaseController.php
│   ├── Database.php
│   └── Model.php
└── public/             # Web erişimi
    └── index.php       # Ana giriş
        </pre>
    </div>
</div>

<div style="margin: 2rem 0; padding: 1.5rem; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 10px;">
    <h3 style="margin-bottom: 1rem;">🎯 Temel Prensipler</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div style="background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px;">
            <h4 style="margin-bottom: 0.5rem;">🏛️ MVC Mimarisi</h4>
            <p style="font-size: 0.9rem; margin: 0;">Model, View, Controller ayrımı ile temiz kod yapısı.</p>
        </div>
        <div style="background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px;">
            <h4 style="margin-bottom: 0.5rem;">🔒 Güvenlik</h4>
            <p style="font-size: 0.9rem; margin: 0;">PDO, CSRF koruması ve güvenli oturum yönetimi.</p>
        </div>
        <div style="background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px;">
            <h4 style="margin-bottom: 0.5rem;">⚡ Performans</h4>
            <p style="font-size: 0.9rem; margin: 0;">Singleton pattern ve optimize edilmiş veritabanı bağlantıları.</p>
        </div>
        <div style="background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px;">
            <h4 style="margin-bottom: 0.5rem;">🔧 Genişletilebilir</h4>
            <p style="font-size: 0.9rem; margin: 0;">Service katmanı ve modüler yapı ile kolay geliştirme.</p>
        </div>
    </div>
</div>

<div style="text-align: center; margin: 2rem 0;">
    <a href="/" style="background: #667eea; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
        🏠 Ana Sayfaya Dön
    </a>
</div>
