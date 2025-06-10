<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="text-center mb-16">
        <div class="w-24 h-24 mx-auto bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mb-6">
            <i class="fas fa-info-circle text-4xl text-white"></i>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6"><?= htmlspecialchars($page_title ?? 'Hakkımızda') ?></h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
            <?= htmlspecialchars($content) ?>
        </p>
    </div>

    <!-- Framework Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
        <!-- Framework Information Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 hover-lift">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-code text-blue-600 text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Framework Bilgileri</h2>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="font-medium text-gray-700">Versiyon:</span>
                    <span class="text-primary-600 font-semibold"><?= htmlspecialchars($framework_info['version']) ?></span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="font-medium text-gray-700">Geliştirici:</span>
                    <span class="text-gray-900"><?= htmlspecialchars($framework_info['author']) ?></span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="font-medium text-gray-700">Lisans:</span>
                    <span class="text-gray-900"><?= htmlspecialchars($framework_info['license']) ?></span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="font-medium text-gray-700">GitHub:</span>
                    <a href="<?= htmlspecialchars($framework_info['github']) ?>" 
                       target="_blank" 
                       class="text-primary-600 hover:text-primary-700 transition-colors font-medium">
                        Repository <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Framework Structure -->
        <div class="bg-white rounded-2xl shadow-xl p-8 hover-lift">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-sitemap text-green-600 text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Framework Yapısı</h2>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4 overflow-x-auto">
                <pre class="text-sm text-gray-700 font-mono leading-relaxed">
<span class="text-primary-600 font-semibold">simple-framework/</span>
├── <span class="text-blue-600">app/</span>
│   ├── <span class="text-green-600">controllers/</span>     # Controller sınıfları
│   ├── <span class="text-green-600">models/</span>         # Model sınıfları
│   ├── <span class="text-green-600">views/</span>          # View dosyaları
│   ├── <span class="text-green-600">services/</span>       # Service katmanı
│   └── <span class="text-green-600">config/</span>         # Konfigürasyon
├── <span class="text-blue-600">core/</span>               # Framework çekirdeği
│   ├── App.php
│   ├── Controller.php
│   ├── Database.php
│   └── Model.php
└── <span class="text-blue-600">public/</span>             # Web erişimi
    └── index.php       # Ana giriş
                </pre>
            </div>
        </div>
    </div>

    <!-- Core Principles -->
    <div class="bg-gradient-to-r from-primary-500 to-secondary-500 rounded-3xl p-8 md:p-12 text-white mb-16">
        <div class="text-center mb-8">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Temel Prensipler</h2>
            <p class="text-white/90 text-lg max-w-2xl mx-auto">
                Framework'ümüzün dayandığı temel prensipleri ve avantajları
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center hover:bg-white/20 transition-all">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-layer-group text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">MVC Mimarisi</h3>
                <p class="text-white/90 text-sm">
                    Model, View, Controller ayrımı ile temiz ve sürdürülebilir kod yapısı.
                </p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center hover:bg-white/20 transition-all">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Güvenlik</h3>
                <p class="text-white/90 text-sm">
                    PDO, CSRF koruması ve güvenli oturum yönetimi ile maksimum güvenlik.
                </p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center hover:bg-white/20 transition-all">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-rocket text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Performans</h3>
                <p class="text-white/90 text-sm">
                    Singleton pattern ve optimize edilmiş veritabanı bağlantıları.
                </p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center hover:bg-white/20 transition-all">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-puzzle-piece text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Genişletilebilir</h3>
                <p class="text-white/90 text-sm">
                    Service katmanı ve modüler yapı ile kolay geliştirme imkanı.
                </p>
            </div>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="mb-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Özellikler</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Framework'ümüzün sunduğu güçlü özellikler ve araçlar
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-route text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Routing Sistemi</h3>
                <p class="text-gray-600 text-sm">
                    Esnek ve güçlü routing sistemi ile URL yönetimi ve middleware desteği.
                </p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-database text-indigo-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">ORM Desteği</h3>
                <p class="text-gray-600 text-sm">
                    Basit ve etkili ORM sistemi ile veritabanı işlemlerini kolaylaştırır.
                </p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-user-shield text-pink-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Kimlik Doğrulama</h3>
                <p class="text-gray-600 text-sm">
                    Hazır kullanıcı kimlik doğrulama sistemi ve rol tabanlı yetkilendirme.
                </p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-eye text-yellow-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Template Engine</h3>
                <p class="text-gray-600 text-sm">
                    PHP tabanlı template sistemi ile dinamik sayfa oluşturma.
                </p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-bug text-red-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Hata Yönetimi</h3>
                <p class="text-gray-600 text-sm">
                    Gelişmiş hata yakalama ve loglama sistemi ile kolay debugging.
                </p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-cogs text-teal-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Konfigürasyon</h3>
                <p class="text-gray-600 text-sm">
                    Merkezi konfigürasyon sistemi ile kolay ayar yönetimi.
                </p>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="bg-gray-50 rounded-3xl p-8 md:p-12 mb-16">
        <div class="text-center mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">İstatistikler</h2>
            <p class="text-lg text-gray-600">Framework'ümüzün gelişim süreci</p>
        </div>
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl font-bold text-primary-600 mb-2">50+</div>
                <div class="text-gray-600">Dosya</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-secondary-600 mb-2">1000+</div>
                <div class="text-gray-600">Kod Satırı</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-green-600 mb-2">10+</div>
                <div class="text-gray-600">Modül</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-orange-600 mb-2">v1.0</div>
                <div class="text-gray-600">Versiyon</div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="text-center">
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Başlamaya Hazır mısınız?</h3>
            <p class="text-gray-600 mb-6">
                Framework'ümüzü keşfedin ve hemen projenizi geliştirmeye başlayın.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
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
        </div>
    </div>
</div>