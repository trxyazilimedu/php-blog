<!-- Hero Section -->
<div class="relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50 to-secondary-50 opacity-50"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        <div class="text-center">
            <!-- Main Title -->
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-gray-900 mb-6">
                <?= htmlspecialchars($page_title ?? 'Ana Sayfa') ?>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed">
                <?= htmlspecialchars($message) ?>
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                <a href="/blog" 
                   class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-8 py-4 rounded-lg font-semibold hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-blog mr-2"></i>
                    Blog'u Keşfet
                </a>
                <a href="/about" 
                   class="bg-white text-gray-700 px-8 py-4 rounded-lg font-semibold border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-info-circle mr-2"></i>
                    Hakkımızda
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Framework Features -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
    <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            <i class="fas fa-rocket text-primary-500 mr-3"></i>
            Framework Özellikleri
        </h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Modern web geliştirme için ihtiyacınız olan her şey burada
        </p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        <?php foreach ($features as $index => $feature): ?>
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift border border-gray-100">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-white text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-900 font-medium leading-relaxed">
                            <?= htmlspecialchars($feature) ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Quick Start Section -->
<div class="bg-gradient-to-r from-primary-500 to-secondary-500 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <div class="mb-8">
            <h3 class="text-3xl md:text-4xl font-bold mb-4">
                <i class="fas fa-play-circle mr-3"></i>
                Hızlı Başlangıç
            </h3>
            <p class="text-xl text-white/90 max-w-2xl mx-auto">
                Framework'ünüz kullanıma hazır! Aşağıdaki özellikleri test edebilirsiniz
            </p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-w-4xl mx-auto">
            <a href="/users" 
               class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-6 rounded-xl transition-all duration-200 hover:transform hover:scale-105 group">
                <div class="text-3xl mb-3">👥</div>
                <div class="font-semibold text-lg mb-1">Kullanıcı Yönetimi</div>
                <div class="text-white/80 text-sm">Kullanıcı CRUD işlemleri</div>
            </a>
            
            <a href="/contact" 
               class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-6 rounded-xl transition-all duration-200 hover:transform hover:scale-105 group">
                <div class="text-3xl mb-3">📧</div>
                <div class="font-semibold text-lg mb-1">İletişim Formu</div>
                <div class="text-white/80 text-sm">Form doğrulama ve gönderim</div>
            </a>
            
            <a href="/blog" 
               class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-6 rounded-xl transition-all duration-200 hover:transform hover:scale-105 group">
                <div class="text-3xl mb-3">📝</div>
                <div class="font-semibold text-lg mb-1">Blog Sistemi</div>
                <div class="text-white/80 text-sm">İçerik yönetim sistemi</div>
            </a>
        </div>
    </div>
</div>

<!-- User Welcome Message -->
<?php if ($user): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6 md:p-8">
            <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6">
                <!-- Avatar -->
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-2xl font-bold">
                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                    </span>
                </div>
                
                <!-- Welcome Content -->
                <div class="flex-1">
                    <h3 class="text-xl md:text-2xl font-bold text-green-900 mb-2">
                        👋 Hoş geldin, <?= htmlspecialchars($user['name']) ?>!
                    </h3>
                    <p class="text-green-700 text-base md:text-lg">
                        Framework'ün tüm özelliklerini keşfedebilirsin. 
                        <span class="font-medium">Rol: <?= ucfirst($user['role']) ?></span>
                    </p>
                </div>
                
                <!-- Quick Actions -->
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <a href="/profile" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center">
                        <i class="fas fa-user mr-2"></i>
                        Profil
                    </a>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="/admin" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center">
                            <i class="fas fa-cog mr-2"></i>
                            Admin Panel
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Footer CTA -->
<div class="bg-gray-50 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
            Başlamaya Hazır mısınız?
        </h3>
        <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
            Framework'ümüzü keşfedin ve güçlü web uygulamaları geliştirmeye başlayın
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/register" 
               class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-8 py-3 rounded-lg font-semibold hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-user-plus mr-2"></i>
                Hemen Kayıt Ol
            </a>
            <a href="/contact" 
               class="bg-white text-gray-700 px-8 py-3 rounded-lg font-semibold border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-envelope mr-2"></i>
                İletişime Geç
            </a>
        </div>
    </div>
</div>