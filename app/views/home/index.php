<h1><?= htmlspecialchars($page_title ?? 'Ana Sayfa') ?></h1>

<div style="text-align: center; margin: 2rem 0;">
    <p style="font-size: 1.2rem; color: #666; margin-bottom: 2rem;">
        <?= htmlspecialchars($message) ?>
    </p>
</div>

<div style="margin: 2rem 0;">
    <h3 style="color: #333; margin-bottom: 1rem;">🚀 Framework Özellikleri:</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
        <?php foreach ($features as $feature): ?>
            <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; border-left: 4px solid #667eea;">
                <span style="color: #333;">✅ <?= htmlspecialchars($feature) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div style="margin: 2rem 0; padding: 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px; text-align: center;">
    <h3 style="margin-bottom: 1rem;">🎯 Hızlı Başlangıç</h3>
    <p style="margin-bottom: 1rem;">Framework'ünüz kullanıma hazır! Aşağıdaki özellikleri test edebilirsiniz:</p>
    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="/users" style="background: rgba(255,255,255,0.2); color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 500;">
            👥 Kullanıcı Yönetimi
        </a>
        <a href="/contact" style="background: rgba(255,255,255,0.2); color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 500;">
            📧 İletişim Formu
        </a>
        <a href="/users/api" style="background: rgba(255,255,255,0.2); color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 500;">
            🔗 API Endpoint
        </a>
    </div>
</div>

<?php if ($user): ?>
    <div style="margin: 2rem 0; padding: 1rem; background: #d4edda; color: #155724; border-radius: 8px; border: 1px solid #c3e6cb;">
        <strong>👋 Hoş geldin, <?= htmlspecialchars($user['name']) ?>!</strong>
        <p style="margin: 0.5rem 0 0 0;">Framework'ün tüm özelliklerini keşfedebilirsin.</p>
    </div>
<?php endif; ?>
