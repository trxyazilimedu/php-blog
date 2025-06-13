<div class="max-w-4xl mx-auto">
    <!-- Hero Section -->
    <div class="text-center mb-16">
        <div class="w-20 h-20 mx-auto bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mb-6">
            <i class="fas fa-users text-3xl text-white"></i>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6" data-content-key="about_hero_title"><?= htmlspecialchars($contentService->getContent('about_hero_title', 'Hakkımızda')) ?></h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed" data-content-key="about_hero_subtitle">
            <?= $contentService->getContent('about_hero_subtitle', 'Teknoloji dünyasındaki gelişmeleri takip edin, bilgi ve deneyimlerimizi paylaştığımız platformumuza hoş geldiniz.') ?>
        </p>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        <!-- Mission -->
        <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-bullseye text-blue-600 text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900" data-content-key="about_mission_title"><?= $contentService->getContent('about_mission_title', 'Misyonumuz') ?></h2>
            </div>
            <p class="text-gray-600 leading-relaxed" data-content-key="about_mission_content">
                <?= $contentService->getContent('about_mission_content', 'Teknoloji alanındaki güncel gelişmeleri takip etmek ve bu bilgileri toplulukla paylaşarak kolektif öğrenmeyi desteklemek.') ?>
            </p>
        </div>

        <!-- Vision -->
        <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-eye text-green-600 text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900" data-content-key="about_vision_title"><?= $contentService->getContent('about_vision_title', 'Vizyonumuz') ?></h2>
            </div>
            <p class="text-gray-600 leading-relaxed" data-content-key="about_vision_content">
                <?= $contentService->getContent('about_vision_content', 'Türkiye\'de teknoloji bilgisinin en güvenilir ve erişilebilir kaynaklarından biri olmak.') ?>
            </p>
        </div>
    </div>

    <!-- Team Section -->
    <div class="bg-gradient-to-r from-primary-500 to-secondary-500 rounded-3xl p-8 md:p-12 text-white mb-16">
        <div class="text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6" data-content-key="about_team_title"><?= $contentService->getContent('about_team_title', 'Ekibimiz') ?></h2>
            <p class="text-white/90 text-lg max-w-3xl mx-auto leading-relaxed" data-content-key="about_team_content">
                <?= $contentService->getContent('about_team_content', 'Deneyimli yazılımcılar ve teknoloji meraklılarından oluşan ekibimiz, sürekli öğrenme ve paylaşım odaklı yaklaşımla içerikler üretiyor.') ?>
            </p>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="mb-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 editable-content" data-content-key="about_services_title"><?= $contentService->getContent('about_services_title', 'Neler Sunuyoruz?') ?></h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto editable-content" data-content-key="about_services_subtitle">
                <?= $contentService->getContent('about_services_subtitle', 'Teknoloji dünyasında güvenilir bilgi kaynağınız') ?>
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-code text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3 editable-content" data-content-key="about_service1_title"><?= $contentService->getContent('about_service1_title', 'Yazılım Geliştirme') ?></h3>
                <p class="text-gray-600 text-sm editable-content" data-content-key="about_service1_desc">
                    <?= $contentService->getContent('about_service1_desc', 'Modern programlama dilleri ve framework\'ler hakkında detaylı rehberler ve ipuçları.') ?>
                </p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-mobile-alt text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3 editable-content" data-content-key="about_service2_title"><?= $contentService->getContent('about_service2_title', 'Mobil Teknolojiler') ?></h3>
                <p class="text-gray-600 text-sm editable-content" data-content-key="about_service2_desc">
                    <?= $contentService->getContent('about_service2_desc', 'iOS, Android ve cross-platform geliştirme konularında güncel içerikler.') ?>
                </p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift text-center">
                <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-cloud text-pink-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3 editable-content" data-content-key="about_service3_title"><?= $contentService->getContent('about_service3_title', 'Bulut Teknolojileri') ?></h3>
                <p class="text-gray-600 text-sm editable-content" data-content-key="about_service3_desc">
                    <?= $contentService->getContent('about_service3_desc', 'AWS, Azure, Google Cloud ve DevOps pratikleri hakkında kapsamlı yazılar.') ?>
                </p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-brain text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3 editable-content" data-content-key="about_service4_title"><?= $contentService->getContent('about_service4_title', 'Yapay Zeka') ?></h3>
                <p class="text-gray-600 text-sm editable-content" data-content-key="about_service4_desc">
                    <?= $contentService->getContent('about_service4_desc', 'Machine Learning, Deep Learning ve AI uygulamaları üzerine araştırmalar.') ?>
                </p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3 editable-content" data-content-key="about_service5_title"><?= $contentService->getContent('about_service5_title', 'Siber Güvenlik') ?></h3>
                <p class="text-gray-600 text-sm editable-content" data-content-key="about_service5_desc">
                    <?= $contentService->getContent('about_service5_desc', 'Web güvenliği, penetration testing ve güvenli kodlama teknikleri.') ?>
                </p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift text-center">
                <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-line text-teal-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3 editable-content" data-content-key="about_service6_title"><?= $contentService->getContent('about_service6_title', 'Veri Analizi') ?></h3>
                <p class="text-gray-600 text-sm editable-content" data-content-key="about_service6_desc">
                    <?= $contentService->getContent('about_service6_desc', 'Big Data, veri görselleştirme ve business intelligence konuları.') ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="bg-gray-50 rounded-3xl p-8 md:p-12 mb-16">
        <div class="text-center mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 editable-content" data-content-key="about_stats_title"><?= $contentService->getContent('about_stats_title', 'Sayılarla Platformumuz') ?></h2>
            <p class="text-lg text-gray-600 editable-content" data-content-key="about_stats_subtitle"><?= $contentService->getContent('about_stats_subtitle', 'Büyüyen topluluğumuzun bir parçası olun') ?></p>
        </div>
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl font-bold text-primary-600 mb-2 editable-content" data-content-key="about_stat1_number"><?= $contentService->getContent('about_stat1_number', '50+') ?></div>
                <div class="text-gray-600 editable-content" data-content-key="about_stat1_label"><?= $contentService->getContent('about_stat1_label', 'Teknoloji Yazısı') ?></div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-secondary-600 mb-2 editable-content" data-content-key="about_stat2_number"><?= $contentService->getContent('about_stat2_number', '1000+') ?></div>
                <div class="text-gray-600 editable-content" data-content-key="about_stat2_label"><?= $contentService->getContent('about_stat2_label', 'Aktif Okuyucu') ?></div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-green-600 mb-2 editable-content" data-content-key="about_stat3_number"><?= $contentService->getContent('about_stat3_number', '15+') ?></div>
                <div class="text-gray-600 editable-content" data-content-key="about_stat3_label"><?= $contentService->getContent('about_stat3_label', 'Kategori') ?></div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-orange-600 mb-2 editable-content" data-content-key="about_stat4_number"><?= $contentService->getContent('about_stat4_number', '2024') ?></div>
                <div class="text-gray-600 editable-content" data-content-key="about_stat4_label"><?= $contentService->getContent('about_stat4_label', 'Kuruluş') ?></div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="text-center">
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto">
            <h3 class="text-2xl font-bold text-gray-900 mb-4 editable-content" data-content-key="about_cta_title"><?= $contentService->getContent('about_cta_title', 'Topluluğumuza Katılın') ?></h3>
            <p class="text-gray-600 mb-6 editable-content" data-content-key="about_cta_description">
                <?= $contentService->getContent('about_cta_description', 'Teknoloji dünyasındaki son gelişmeleri kaçırmayın. Blog yazılarımızı takip edin ve bilgi birikimlerinizi artırın.') ?>
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/blog" 
                   class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-8 py-3 rounded-lg font-semibold hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-book-open mr-2"></i>
                    Blog Yazılarını İncele
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