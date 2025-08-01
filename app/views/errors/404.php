<div class="min-h-[70vh] flex items-center justify-center p-4">
    <div class="text-center">
        <!-- Error Icon -->
        <div class="mb-8">
            <div class="w-32 h-32 mx-auto bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center shadow-2xl">
                <i class="fas fa-search text-5xl text-white"></i>
            </div>
        </div>
        
        <!-- 404 Text -->
        <div class="mb-8">
            <h1 class="text-8xl md:text-9xl font-bold text-transparent bg-gradient-to-r from-primary-500 to-secondary-500 bg-clip-text mb-4">
                404
            </h1>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Sayfa Bulunamadı</h2>
            <p class="text-lg text-gray-600 max-w-md mx-auto leading-relaxed">
                Aradığınız sayfa mevcut değil veya taşınmış olabilir. Lütfen URL'yi kontrol edin.
            </p>
        </div>
        
        <!-- URL Info -->
        <div class="mb-8 p-4 bg-white rounded-xl shadow-lg border border-gray-200 max-w-md mx-auto">
            <p class="text-sm text-gray-500 mb-1">Aranan URL:</p>
            <p class="text-sm font-mono text-gray-700 break-all">
                <?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '') ?>
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="/" 
               class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-8 py-3 rounded-lg font-semibold hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-home mr-2"></i>
                Ana Sayfaya Dön
            </a>
            
            <a href="/contact" 
               class="bg-white text-gray-700 px-8 py-3 rounded-lg font-semibold border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-envelope mr-2"></i>
                İletişime Geç
            </a>
        </div>
        
        <!-- Helpful Links -->
        <div class="mt-12">
            <p class="text-sm text-gray-500 mb-4">Belki bunlardan birini arıyorsunuz:</p>
            <div class="flex flex-wrap gap-2 justify-center">
                <a href="/about" class="text-primary-600 hover:text-primary-700 text-sm bg-white px-3 py-1 rounded-full border border-gray-200 hover:border-primary-300 transition-colors shadow-sm">
                    Hakkımızda
                </a>
                <a href="/blog" class="text-primary-600 hover:text-primary-700 text-sm bg-white px-3 py-1 rounded-full border border-gray-200 hover:border-primary-300 transition-colors shadow-sm">
                    Blog
                </a>
                <?php if (!isset($_SESSION['user'])): ?>
                    <a href="/login" class="text-primary-600 hover:text-primary-700 text-sm bg-white px-3 py-1 rounded-full border border-gray-200 hover:border-primary-300 transition-colors shadow-sm">
                        Giriş Yap
                    </a>
                    <a href="/register" class="text-primary-600 hover:text-primary-700 text-sm bg-white px-3 py-1 rounded-full border border-gray-200 hover:border-primary-300 transition-colors shadow-sm">
                        Kayıt Ol
                    </a>
                <?php else: ?>
                    <a href="/users/profile" class="text-primary-600 hover:text-primary-700 text-sm bg-white px-3 py-1 rounded-full border border-gray-200 hover:border-primary-300 transition-colors shadow-sm">
                        Profilim
                    </a>
                    <a href="/blog/my-posts" class="text-primary-600 hover:text-primary-700 text-sm bg-white px-3 py-1 rounded-full border border-gray-200 hover:border-primary-300 transition-colors shadow-sm">
                        Yazılarım
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Floating Animation Elements -->
<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.floating-element {
    animation: float 3s ease-in-out infinite;
}

.floating-element:nth-child(2) {
    animation-delay: 1s;
}

.floating-element:nth-child(3) {
    animation-delay: 2s;
}

.floating-element:nth-child(4) {
    animation-delay: 0.5s;
}
</style>

<!-- Background Animation -->
<div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
    <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-primary-400/20 rounded-full floating-element"></div>
    <div class="absolute top-3/4 right-1/4 w-3 h-3 bg-secondary-400/20 rounded-full floating-element"></div>
    <div class="absolute top-1/2 left-1/6 w-1 h-1 bg-primary-500/30 rounded-full floating-element"></div>
    <div class="absolute bottom-1/4 right-1/3 w-2 h-2 bg-secondary-500/20 rounded-full floating-element"></div>
</div>