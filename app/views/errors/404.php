<div style="text-align: center;  font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div style="background: rgba(255,255,255,0.1); padding: 3rem; border-radius: 20px; backdrop-filter: blur(10px);">
        <h1 style="font-size: 8rem; margin: 0; opacity: 0.8;">404</h1>
        <h2 style="margin: 1rem 0; font-weight: 300;">Sayfa Bulunamadı</h2>
        <p style="margin: 1.5rem 0; opacity: 0.9;">Aradığınız sayfa mevcut değil veya taşınmış olabilir.</p>
        
        <div style="margin: 2rem 0;">
            <a href="/" style="background: rgba(255,255,255,0.2); color: white; padding: 1rem 2rem; border-radius: 50px; text-decoration: none; font-weight: 600; margin: 0 0.5rem; display: inline-block; transition: all 0.3s;">
                🏠 Ana Sayfa
            </a>
            <a href="/contact" style="background: rgba(255,255,255,0.2); color: white; padding: 1rem 2rem; border-radius: 50px; text-decoration: none; font-weight: 600; margin: 0 0.5rem; display: inline-block; transition: all 0.3s;">
                📧 İletişim
            </a>
        </div>
        
        <div style="margin-top: 2rem; opacity: 0.7; font-size: 0.9rem;">
            <p>URL: <?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '') ?></p>
        </div>
    </div>
</div>
