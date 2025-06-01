<h1><?= htmlspecialchars($page_title ?? 'Profilim') ?></h1>

<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; margin-bottom: 2rem;">
        <!-- Profil Fotoğrafı ve Temel Bilgiler -->
        <div style="background: #f8f9fa; padding: 2rem; border-radius: 10px; text-align: center; border: 1px solid #e9ecef;">
            <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 600; margin: 0 auto 1rem;">
                <?= strtoupper(substr($user['name'], 0, 1)) ?>
            </div>
            <h3 style="color: #333; margin-bottom: 0.5rem;"><?= htmlspecialchars($user['name']) ?></h3>
            <p style="color: #666; margin-bottom: 1rem;"><?= htmlspecialchars($user['email']) ?></p>
            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 500; background: <?= ($user['role'] ?? 'user') === 'admin' ? '#dc3545' : '#28a745' ?>; color: white;">
                <?= ucfirst($user['role'] ?? 'user') ?>
            </span>
        </div>
        
        <!-- Profil Bilgileri -->
        <div style="background: white; padding: 2rem; border-radius: 10px; border: 1px solid #e9ecef;">
            <h3 style="color: #333; margin-bottom: 1.5rem; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem;">Profil Bilgileri</h3>
            
            <div style="space-y: 1rem;">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 600; color: #666; margin-bottom: 0.25rem;">Kullanıcı ID:</label>
                    <span style="color: #333; font-size: 1.1rem;">#<?= $user['id'] ?></span>
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 600; color: #666; margin-bottom: 0.25rem;">Ad Soyad:</label>
                    <span style="color: #333; font-size: 1.1rem;"><?= htmlspecialchars($user['name']) ?></span>
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 600; color: #666; margin-bottom: 0.25rem;">E-posta Adresi:</label>
                    <span style="color: #333; font-size: 1.1rem;"><?= htmlspecialchars($user['email']) ?></span>
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 600; color: #666; margin-bottom: 0.25rem;">Rol:</label>
                    <span style="color: #333; font-size: 1.1rem;"><?= ucfirst($user['role'] ?? 'user') ?></span>
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 600; color: #666; margin-bottom: 0.25rem;">Durum:</label>
                    <span style="color: <?= ($user['status'] ?? 'active') === 'active' ? '#28a745' : '#dc3545' ?>; font-size: 1.1rem; font-weight: 500;">
                        <?= ($user['status'] ?? 'active') === 'active' ? '✓ Aktif' : '✗ Pasif' ?>
                    </span>
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 600; color: #666; margin-bottom: 0.25rem;">Kayıt Tarihi:</label>
                    <span style="color: #333; font-size: 1.1rem;"><?= date('d F Y, H:i', strtotime($user['created_at'])) ?></span>
                </div>
                
                <?php if (isset($user['last_login']) && $user['last_login']): ?>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 600; color: #666; margin-bottom: 0.25rem;">Son Giriş:</label>
                    <span style="color: #333; font-size: 1.1rem;"><?= date('d F Y, H:i', strtotime($user['last_login'])) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e9ecef;">
                <h4 style="color: #333; margin-bottom: 1rem;">Hızlı İşlemler</h4>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="/users/edit/<?= $user['id'] ?>" 
                       style="background: #ffc107; color: #212529; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 0.9rem;">
                        ✏️ Profili Düzenle
                    </a>
                    <a href="/users" 
                       style="background: #6c757d; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 0.9rem;">
                        👥 Tüm Kullanıcılar
                    </a>
                    <a href="/" 
                       style="background: #667eea; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 0.9rem;">
                        🏠 Ana Sayfa
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Aktivite Özeti -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 10px; text-align: center;">
        <h3 style="margin-bottom: 1rem;">📊 Hesap Özeti</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div style="background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px;">
                <h4 style="margin-bottom: 0.5rem;">🗓️ Hesap Yaşı</h4>
                <p style="font-size: 1.2rem; margin: 0;">
                    <?php 
                        $accountAge = time() - strtotime($user['created_at']);
                        $days = floor($accountAge / (60 * 60 * 24));
                        echo $days . ' gün';
                    ?>
                </p>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px;">
                <h4 style="margin-bottom: 0.5rem;">🎯 Durum</h4>
                <p style="font-size: 1.2rem; margin: 0;">
                    <?= ($user['status'] ?? 'active') === 'active' ? 'Aktif Kullanıcı' : 'Pasif Kullanıcı' ?>
                </p>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px;">
                <h4 style="margin-bottom: 0.5rem;">🔑 Yetki Seviyesi</h4>
                <p style="font-size: 1.2rem; margin: 0;">
                    <?= ($user['role'] ?? 'user') === 'admin' ? 'Yönetici' : 'Standart Kullanıcı' ?>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        div[style*="grid-template-columns: 1fr 2fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
