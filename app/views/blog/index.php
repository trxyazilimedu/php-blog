<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-primary-600 via-secondary-500 to-primary-700 text-white py-20 mb-16 rounded-3xl overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>
    
    <div class="relative z-10 text-center">
        <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in editable-content" data-content-key="hero_title">
            Teknoloji Dünyasına Hoş Geldiniz
        </h1>
        <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto mb-8 animate-fade-in editable-content" data-content-key="hero_subtitle">
            Yazılım, teknoloji trendleri ve dijital dünya hakkında kaliteli içerikler keşfedin
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in">
            <a href="#latest-posts" class="bg-white text-primary-600 hover:bg-gray-100 px-8 py-3 rounded-full font-semibold transition-all duration-300 hover:scale-105 shadow-lg">
                <i class="fas fa-arrow-down mr-2"></i>Son Yazılar
            </a>
            <?php if ($is_logged_in && $user && ($user['role'] === 'admin' || $user['role'] === 'writer')): ?>
                <a href="/blog/create" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-primary-600 px-8 py-3 rounded-full font-semibold transition-all duration-300 hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>Yeni Yazı Yaz
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Decorative Elements -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
    <div class="absolute bottom-20 right-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
</section>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-3">
        <div id="latest-posts" class="mb-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900 editable-content" data-content-key="latest_posts_title">
                    <i class="fas fa-newspaper text-primary-500 mr-3"></i>Son Blog Yazıları
                </h2>
                
                <!-- Filter Buttons -->
                <div class="flex space-x-2">
                    <button class="filter-btn active bg-primary-500 text-white px-4 py-2 rounded-lg transition-colors" data-filter="all">
                        Tümü
                    </button>
                    <?php if (!empty($categories)): ?>
                        <?php foreach (array_slice($categories, 0, 4) as $category): ?>
                            <button class="filter-btn bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors" data-filter="<?= htmlspecialchars($category['slug']) ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </button>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Posts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $index => $post): ?>
                        <article class="bg-white rounded-2xl shadow-lg hover-lift overflow-hidden group animate-fade-in" style="animation-delay: <?= $index * 0.1 ?>s">
                            <!-- Post Image -->
                            <div class="relative h-48 bg-gradient-to-r from-primary-500 to-secondary-500 overflow-hidden">
                                <?php if (!empty($post['featured_image'])): ?>
                                    <img src="<?= htmlspecialchars($post['featured_image']) ?>" 
                                         alt="<?= htmlspecialchars($post['title']) ?>"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-image text-white text-4xl opacity-50"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Category Badge -->
                                <?php if (!empty($post['category_names'])): ?>
                                    <div class="absolute top-4 left-4">
                                        <span class="bg-white/90 text-primary-600 px-3 py-1 rounded-full text-sm font-semibold backdrop-blur-sm">
                                            <?= htmlspecialchars(explode(',', $post['category_names'])[0]) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Reading Time -->
                                <div class="absolute top-4 right-4">
                                    <span class="bg-black/50 text-white px-3 py-1 rounded-full text-sm backdrop-blur-sm">
                                        <i class="fas fa-clock mr-1"></i><?= ceil(str_word_count(strip_tags($post['content'])) / 200) ?> dk
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Post Content -->
                            <div class="p-6">
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <div class="flex items-center mr-4">
                                        <div class="w-6 h-6 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mr-2">
                                            <span class="text-white text-xs font-semibold">
                                                <?= strtoupper(substr($post['author_name'] ?? 'U', 0, 1)) ?>
                                            </span>
                                        </div>
                                        <span><?= htmlspecialchars($post['author_name'] ?? 'Bilinmeyen') ?></span>
                                    </div>
                                    <span><i class="fas fa-calendar mr-1"></i><?= date('d.m.Y', strtotime($post['published_at'] ?? $post['created_at'])) ?></span>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors">
                                    <a href="/blog/post/<?= htmlspecialchars($post['slug']) ?>" class="hover:underline">
                                        <?= htmlspecialchars($post['title']) ?>
                                    </a>
                                </h3>
                                
                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    <?= htmlspecialchars($post['excerpt'] ?: substr(strip_tags($post['content']), 0, 150) . '...') ?>
                                </p>
                                
                                <div class="flex items-center justify-between">
                                    <a href="/blog/post/<?= htmlspecialchars($post['slug']) ?>" 
                                       class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold group">
                                        Devamını Oku
                                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                    
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span><i class="fas fa-eye mr-1"></i><?= number_format($post['views'] ?? 0) ?></span>
                                        <span><i class="fas fa-heart mr-1"></i><?= $post['likes'] ?? 0 ?></span>
                                        <span><i class="fas fa-comment mr-1"></i><?= $post['comment_count'] ?? 0 ?></span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="md:col-span-2">
                        <div class="text-center py-16 bg-white rounded-2xl shadow-lg">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-file-alt text-4xl text-gray-400"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 editable-content" data-content-key="no_posts_title">
                                Henüz blog yazısı yok
                            </h3>
                            <p class="text-gray-600 mb-6 editable-content" data-content-key="no_posts_description">
                                İlk blog yazısını yazmak için aşağıdaki butona tıklayın.
                            </p>
                            <?php if ($is_logged_in && $user && ($user['role'] === 'admin' || $user['role'] === 'writer')): ?>
                                <a href="/blog/create" class="bg-primary-500 hover:bg-primary-600 text-white px-8 py-3 rounded-full font-semibold transition-colors inline-flex items-center">
                                    <i class="fas fa-plus mr-2"></i>İlk Yazımı Oluştur
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Load More Button -->
            <?php if (!empty($posts) && count($posts) >= 6): ?>
                <div class="text-center mt-12">
                    <button id="load-more" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-8 py-3 rounded-full font-semibold transition-colors">
                        <i class="fas fa-spinner mr-2"></i>Daha Fazla Yükle
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Categories Widget -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 hover-lift">
            <h3 class="text-xl font-bold text-gray-900 mb-6 editable-content" data-content-key="categories_widget_title">
                <i class="fas fa-tags text-primary-500 mr-2"></i>Kategoriler
            </h3>
            
            <div class="space-y-3">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <a href="/blog/category/<?= htmlspecialchars($category['slug']) ?>" 
                           class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-3" style="background-color: <?= htmlspecialchars($category['color'] ?? '#3b82f6') ?>"></div>
                                <span class="font-medium text-gray-700 group-hover:text-primary-600">
                                    <?= htmlspecialchars($category['name']) ?>
                                </span>
                            </div>
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-sm">
                                <?= $category['post_count'] ?? 0 ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-folder-open text-3xl mb-3 opacity-50"></i>
                        <p>Henüz kategori yok</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Popular Posts Widget -->
        <?php if (!empty($popular_posts)): ?>
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 hover-lift">
                <h3 class="text-xl font-bold text-gray-900 mb-6 editable-content" data-content-key="popular_posts_title">
                    <i class="fas fa-fire text-orange-500 mr-2"></i>Popüler Yazılar
                </h3>
                
                <div class="space-y-4">
                    <?php foreach ($popular_posts as $index => $post): ?>
                        <div class="flex space-x-3 group">
                            <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold"><?= $index + 1 ?></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors line-clamp-2">
                                    <a href="/blog/post/<?= htmlspecialchars($post['slug']) ?>">
                                        <?= htmlspecialchars($post['title']) ?>
                                    </a>
                                </h4>
                                <div class="flex items-center mt-1 text-xs text-gray-500">
                                    <span><i class="fas fa-eye mr-1"></i><?= number_format($post['views'] ?? 0) ?></span>
                                    <span class="mx-2">•</span>
                                    <span><?= date('d.m.Y', strtotime($post['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Newsletter Widget -->
        <div class="bg-gradient-to-r from-primary-500 to-secondary-500 rounded-2xl shadow-lg p-6 text-white mb-8">
            <div class="text-center">
                <i class="fas fa-envelope-open text-3xl mb-4 opacity-90"></i>
                <h3 class="text-xl font-bold mb-3 editable-content" data-content-key="newsletter_title">
                    Bültenimize Abone Olun
                </h3>
                <p class="text-white/90 mb-6 editable-content" data-content-key="newsletter_description">
                    Yeni yazılarımızdan haberdar olmak için e-posta adresinizi bırakın.
                </p>
                
                <form class="space-y-3">
                    <input type="email" placeholder="E-posta adresiniz" 
                           class="w-full px-4 py-3 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/50">
                    <button type="submit" 
                            class="w-full bg-white text-primary-600 hover:bg-gray-100 py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Abone Ol
                    </button>
                </form>
            </div>
        </div>
        
        <!-- About Widget -->
        <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
            <h3 class="text-xl font-bold text-gray-900 mb-4 editable-content" data-content-key="about_widget_title">
                <i class="fas fa-info-circle text-primary-500 mr-2"></i>Hakkımızda
            </h3>
            <p class="text-gray-600 mb-4 editable-content" data-content-key="about_widget_content">
                Teknoloji dünyasındaki en son gelişmeleri takip ediyor, yazılım geliştirme süreçleri hakkında içerikler üretiyoruz.
            </p>
            <a href="/about" class="text-primary-600 hover:text-primary-700 font-semibold inline-flex items-center group">
                Daha Fazla Bilgi
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Smooth scroll to sections
    $('a[href^="#"]').click(function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });
    
    // Filter functionality
    $('.filter-btn').click(function() {
        const filter = $(this).data('filter');
        
        // Update active state
        $('.filter-btn').removeClass('active bg-primary-500 text-white').addClass('bg-gray-200 text-gray-700');
        $(this).removeClass('bg-gray-200 text-gray-700').addClass('active bg-primary-500 text-white');
        
        // Filter posts (this would be implemented with AJAX in a real application)
        if (filter === 'all') {
            $('article').fadeIn(300);
        } else {
            $('article').hide();
            $(`article[data-category="${filter}"]`).fadeIn(300);
        }
    });
    
    // Load more functionality
    $('#load-more').click(function() {
        const $btn = $(this);
        const originalText = $btn.html();
        
        $btn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Yükleniyor...');
        
        // Simulate loading (replace with actual AJAX call)
        setTimeout(function() {
            $btn.html(originalText);
            // Add your load more logic here
        }, 1000);
    });
    
    // Newsletter form
    $('form').submit(function(e) {
        e.preventDefault();
        const email = $(this).find('input[type="email"]').val();
        
        if (email) {
            // Here you would send the email to your backend
            alert('Teşekkürler! E-posta listemize eklendik.');
            $(this).find('input[type="email"]').val('');
        }
    });
    
    // Add category data attributes to articles (for filtering)
    $('article').each(function() {
        // This would be dynamically added based on post categories
        // $(this).attr('data-category', 'category-slug');
    });
});
</script>