<h1><?= htmlspecialchars($page_title ?? 'Controller Test') ?></h1>

<div style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
    
    <!-- Framework bilgileri -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 10px; margin-bottom: 2rem; text-align: center;">
        <h2 style="margin-bottom: 1rem;">🧪 Core Controller Test Sonuçları</h2>
        <p style="font-size: 1.1rem; margin: 0;">Tüm metodlar başarıyla core katmanına taşındı!</p>
    </div>

    <!-- Performance bilgileri -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 3rem;">
        <div style="background: #28a745; color: white; padding: 1rem; border-radius: 8px; text-align: center;">
            <h4 style="margin-bottom: 0.5rem;">⚡ Execution Time</h4>
            <p style="font-size: 1.2rem; margin: 0;"><?= number_format($execution_time * 1000, 2) ?> ms</p>
        </div>
        <div style="background: #17a2b8; color: white; padding: 1rem; border-radius: 8px; text-align: center;">
            <h4 style="margin-bottom: 0.5rem;">💾 Memory Usage</h4>
            <p style="font-size: 1.2rem; margin: 0;"><?= $memory_usage['formatted']['current'] ?></p>
        </div>
        <div style="background: #ffc107; color: #212529; padding: 1rem; border-radius: 8px; text-align: center;">
            <h4 style="margin-bottom: 0.5rem;">📊 Peak Memory</h4>
            <p style="font-size: 1.2rem; margin: 0;"><?= $memory_usage['formatted']['peak'] ?></p>
        </div>
    </div>

    <!-- Test sonuçları -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">
        
        <!-- Core Controller testleri -->
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="color: #333; margin-bottom: 1rem; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem;">
                🏗️ Core Controller Methods
            </h3>
            <div style="space-y: 0.5rem;">
                <?php foreach ($test_results['core_controller'] as $test => $result): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; margin-bottom: 0.5rem; background: #f8f9fa; border-radius: 6px;">
                        <span style="font-weight: 500;"><?= str_replace('_', ' ', ucfirst($test)) ?></span>
                        <span style="font-size: 1.2rem;"><?= $result ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- BaseController testleri -->
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="color: #333; margin-bottom: 1rem; border-bottom: 2px solid #28a745; padding-bottom: 0.5rem;">
                🎯 Enhanced Methods
            </h3>
            <div style="space-y: 0.5rem;">
                <?php foreach ($test_results['base_controller'] as $test => $result): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; margin-bottom: 0.5rem; background: #f8f9fa; border-radius: 6px;">
                        <span style="font-weight: 500;"><?= str_replace('_', ' ', ucfirst($test)) ?></span>
                        <span style="font-size: 1.2rem;"><?= $result ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Performance testleri -->
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="color: #333; margin-bottom: 1rem; border-bottom: 2px solid #ffc107; padding-bottom: 0.5rem;">
                ⚡ Performance Features
            </h3>
            <div style="space-y: 0.5rem;">
                <?php foreach ($test_results['performance'] as $test => $result): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; margin-bottom: 0.5rem; background: #f8f9fa; border-radius: 6px;">
                        <span style="font-weight: 500;"><?= str_replace('_', ' ', ucfirst($test)) ?></span>
                        <span style="font-size: 1.2rem;"><?= $result ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Security testleri -->
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="color: #333; margin-bottom: 1rem; border-bottom: 2px solid #dc3545; padding-bottom: 0.5rem;">
                🔒 Security Features
            </h3>
            <div style="space-y: 0.5rem;">
                <?php foreach ($test_results['security'] as $test => $result): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; margin-bottom: 0.5rem; background: #f8f9fa; border-radius: 6px;">
                        <span style="font-weight: 500;"><?= str_replace('_', ' ', ucfirst($test)) ?></span>
                        <span style="font-size: 1.2rem;"><?= $result ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- API testleri -->
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="color: #333; margin-bottom: 1rem; border-bottom: 2px solid #17a2b8; padding-bottom: 0.5rem;">
                🔗 API & Export Features
            </h3>
            <div style="space-y: 0.5rem;">
                <?php foreach ($test_results['api'] as $test => $result): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; margin-bottom: 0.5rem; background: #f8f9fa; border-radius: 6px;">
                        <span style="font-weight: 500;"><?= str_replace('_', ' ', ucfirst($test)) ?></span>
                        <span style="font-size: 1.2rem;"><?= $result ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Test endpoint'leri -->
    <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-top: 2rem;">
        <h3 style="color: #333; margin-bottom: 1.5rem; text-align: center;">🧪 Test Endpoint'leri</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
            <a href="/test/api-test" style="background: #28a745; color: white; padding: 1rem; border-radius: 8px; text-decoration: none; text-align: center; font-weight: 500; transition: all 0.3s;">
                🔗 API Test
            </a>
            <a href="/test/rate-limit-test" style="background: #ffc107; color: #212529; padding: 1rem; border-radius: 8px; text-decoration: none; text-align: center; font-weight: 500; transition: all 0.3s;">
                ⏰ Rate Limit Test
            </a>
            <a href="/test/cache-test" style="background: #17a2b8; color: white; padding: 1rem; border-radius: 8px; text-decoration: none; text-align: center; font-weight: 500; transition: all 0.3s;">
                💾 Cache Test
            </a>
            <a href="/test/export-csv" style="background: #6f42c1; color: white; padding: 1rem; border-radius: 8px; text-decoration: none; text-align: center; font-weight: 500; transition: all 0.3s;">
                📊 CSV Export
            </a>
            <a href="/test/export-xml" style="background: #fd7e14; color: white; padding: 1rem; border-radius: 8px; text-decoration: none; text-align: center; font-weight: 500; transition: all 0.3s;">
                📄 XML Export
            </a>
            <a href="/test/debug-info" style="background: #dc3545; color: white; padding: 1rem; border-radius: 8px; text-decoration: none; text-align: center; font-weight: 500; transition: all 0.3s;">
                🐛 Debug Info
            </a>
        </div>
    </div>

    <!-- Refactoring özeti -->
    <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 2rem; border-radius: 10px; margin-top: 2rem; text-align: center;">
        <h3 style="margin-bottom: 1rem;">✅ Refactoring Başarıyla Tamamlandı!</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
            <div style="background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px;">
                <h4 style="margin-bottom: 0.5rem;">🏗️ Core/App Ayrımı</h4>
                <p style="font-size: 0.9rem; margin: 0;">Temiz mimari yapısı</p>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px;">
                <h4 style="margin-bottom: 0.5rem;">🚀 Gelişmiş Özellikler</h4>
                <p style="font-size: 0.9rem; margin: 0;">API, Cache, Performance</p>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px;">
                <h4 style="margin-bottom: 0.5rem;">🔒 Güçlü Güvenlik</h4>
                <p style="font-size: 0.9rem; margin: 0;">CSRF, Rate Limit, Auth</p>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 8px;">
                <h4 style="margin-bottom: 0.5rem;">📝 Temiz Kod</h4>
                <p style="font-size: 0.9rem; margin: 0;">DRY, SOLID principles</p>
            </div>
        </div>
    </div>

    <!-- Navigasyon -->
    <div style="text-align: center; margin-top: 2rem;">
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="/" style="background: #667eea; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
                🏠 Ana Sayfa
            </a>
            <a href="/users" style="background: #6c757d; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
                👥 Kullanıcı Yönetimi
            </a>
            <a href="/contact" style="background: #28a745; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
                📧 İletişim
            </a>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
        div[style*="display: flex"] {
            flex-direction: column !important;
        }
    }
    
    a:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
    }
</style>
