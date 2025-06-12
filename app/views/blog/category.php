<!-- Category Header -->
<section class="relative bg-gradient-to-r from-primary-600 via-secondary-500 to-primary-700 text-white py-16 mb-12 rounded-3xl overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>
    
    <div class="relative z-10 text-center">
        <div class="mb-4">
            <span class="bg-white/20 px-4 py-2 rounded-full text-sm font-medium">
                <i class="fas fa-tag mr-2"></i>Kategori
            </span>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold mb-4 animate-fade-in">
            <?= htmlspecialchars($category['name']) ?>
        </h1>
        <?php if (!empty($category['description'])): ?>
            <p class="text-xl text-white/90 max-w-2xl mx-auto mb-6 animate-fade-in">
                <?= htmlspecialchars($category['description']) ?>
            </p>
        <?php endif; ?>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in">
            <div class="flex items-center space-x-6 text-white/80">
                <span class="flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>
                    <?= count($posts) ?> yazı
                </span>
                <?php if (isset($category['total_views'])): ?>
                    <span class="flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        <?= number_format($category['total_views']) ?> görüntülenme
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Decorative Elements -->
    <div class="absolute top-10 left-10 w-16 h-16 rounded-full blur-xl opacity-30" 
         style="background-color: <?= $category['color'] ?? '#3B82F6' ?>"></div>
    <div class="absolute bottom-10 right-10 w-24 h-24 rounded-full blur-xl opacity-20"
         style="background-color: <?= $category['color'] ?? '#3B82F6' ?>"></div>
</section>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-3">
        <!-- Posts Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-list mr-3 text-primary-500"></i>
                    <?= htmlspecialchars($category['name']) ?> Yazıları
                    <span class="ml-2 text-sm bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                        <?= count($posts) ?>
                    </span>
                </h2>
                
                <!-- Sort Options -->
                <div class="flex items-center space-x-2">
                    <select id="sort-posts" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="newest">En Yeni</option>
                        <option value="oldest">En Eski</option>
                        <option value="popular">En Popüler</option>
                        <option value="title">Başlık (A-Z)</option>
                    </select>
                </div>
            </div>
            
            <?php if (!empty($posts)): ?>
                <div class="space-y-8" id="posts-container">
                    <?php foreach ($posts as $post): ?>
                        <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100"
                                 data-post-date="<?= strtotime($post['created_at']) ?>"
                                 data-post-views="<?= $post['views'] ?? 0 ?>"
                                 data-post-title="<?= strtolower($post['title']) ?>">
                            <div class="md:flex">
                                <!-- Featured Image -->
                                <div class="md:w-1/3">
                                    <?php if (!empty($post['featured_image'])): ?>
                                        <img src="<?= htmlspecialchars($post['featured_image']) ?>" 
                                             alt="<?= htmlspecialchars($post['title']) ?>"
                                             class="w-full h-48 md:h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-48 md:h-full bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center">
                                            <i class="fas fa-file-alt text-white text-4xl opacity-50"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Content -->
                                <div class="md:w-2/3 p-6 md:p-8">
                                    <!-- Meta Info -->
                                    <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                                        <span class="flex items-center">
                                            <i class="fas fa-user mr-2"></i>
                                            <?= htmlspecialchars($post['author_name'] ?? 'Bilinmeyen Yazar') ?>
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-2"></i>
                                            <?= date('d.m.Y', strtotime($post['created_at'])) ?>
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-eye mr-2"></i>
                                            <?= number_format($post['views'] ?? 0) ?>
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-clock mr-2"></i>
                                            <?= ceil(str_word_count(strip_tags($post['content'])) / 200) ?> dk okuma
                                        </span>
                                    </div>
                                    
                                    <!-- Title -->
                                    <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3 hover:text-primary-600 transition-colors">
                                        <a href="/blog/post/<?= htmlspecialchars($post['slug']) ?>" class="block">
                                            <?= htmlspecialchars($post['title']) ?>
                                        </a>
                                    </h3>
                                    
                                    <!-- Excerpt -->
                                    <p class="text-gray-600 mb-4 line-clamp-3">
                                        <?= htmlspecialchars($post['excerpt'] ?? substr(strip_tags($post['content']), 0, 200) . '...') ?>
                                    </p>
                                    
                                    <!-- Tags and Read More -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <?php if (!empty($post['tags'])): ?>
                                                <?php $tags = is_string($post['tags']) ? explode(',', $post['tags']) : $post['tags']; ?>
                                                <?php foreach (array_slice($tags, 0, 3) as $tag): ?>
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                                        #<?= htmlspecialchars(trim($tag)) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <a href="/blog/post/<?= htmlspecialchars($post['slug']) ?>"
                                           class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold transition-colors">
                                            Devamını Oku
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                
                <!-- Load More Button (if needed) -->
                <?php if (count($posts) >= 10): ?>
                    <div class="text-center mt-8">
                        <button id="load-more" 
                                class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i>Daha Fazla Yazı Yükle
                        </button>
                    </div>
                <?php endif; ?>
                
            <?php else: ?>
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-file-alt text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        Bu kategoride henüz yazı bulunmuyor
                    </h3>
                    <p class="text-gray-600 mb-6">
                        <?= htmlspecialchars($category['name']) ?> kategorisinde henüz paylaşılmış bir yazı yok.
                    </p>
                    <a href="/blog" 
                       class="inline-flex items-center bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Tüm Yazılara Geri Dön
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-8">
        <!-- Category Info Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4" 
             style="border-left-color: <?= $category['color'] ?? '#3B82F6' ?>">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle mr-2" style="color: <?= $category['color'] ?? '#3B82F6' ?>"></i>
                Kategori Bilgileri
            </h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Toplam Yazı:</span>
                    <span class="font-semibold"><?= count($posts) ?></span>
                </div>
                <?php if (isset($category['total_views'])): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Toplam Görüntülenme:</span>
                        <span class="font-semibold"><?= number_format($category['total_views']) ?></span>
                    </div>
                <?php endif; ?>
                <div class="flex justify-between">
                    <span class="text-gray-600">Oluşturulma:</span>
                    <span class="font-semibold"><?= date('d.m.Y', strtotime($category['created_at'])) ?></span>
                </div>
            </div>
        </div>
        
        <!-- Other Categories -->
        <?php if (!empty($categories) && count($categories) > 1): ?>
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-tags text-primary-500 mr-2"></i>Diğer Kategoriler
                </h3>
                
                <div class="space-y-3">
                    <?php foreach ($categories as $cat): ?>
                        <?php if ($cat['id'] !== $category['id']): ?>
                            <a href="/blog/category/<?= htmlspecialchars($cat['slug']) ?>" 
                               class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full mr-3" 
                                         style="background-color: <?= $cat['color'] ?? '#6B7280' ?>"></div>
                                    <span class="font-medium text-gray-900 group-hover:text-primary-600">
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </span>
                                </div>
                                <span class="text-sm text-gray-500 bg-white px-2 py-1 rounded">
                                    <?= $cat['post_count'] ?? 0 ?>
                                </span>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Popular Posts -->
        <?php if (!empty($popular_posts)): ?>
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-fire text-orange-500 mr-2"></i>Popüler Yazılar
                </h3>
                
                <div class="space-y-4">
                    <?php foreach (array_slice($popular_posts, 0, 5) as $index => $popular_post): ?>
                        <a href="/blog/<?= htmlspecialchars($popular_post['slug']) ?>" 
                           class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                            <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-bold">
                                <?= $index + 1 ?>
                            </span>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 group-hover:text-primary-600 line-clamp-2">
                                    <?= htmlspecialchars($popular_post['title']) ?>
                                </h4>
                                <div class="flex items-center mt-1 text-xs text-gray-500">
                                    <i class="fas fa-eye mr-1"></i>
                                    <?= number_format($popular_post['views'] ?? 0) ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Recent Posts -->
        <?php if (!empty($recent_posts)): ?>
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-clock text-blue-500 mr-2"></i>Son Yazılar
                </h3>
                
                <div class="space-y-4">
                    <?php foreach (array_slice($recent_posts, 0, 5) as $recent_post): ?>
                        <a href="/blog/<?= htmlspecialchars($recent_post['slug']) ?>" 
                           class="block p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-primary-600 line-clamp-2 mb-2">
                                <?= htmlspecialchars($recent_post['title']) ?>
                            </h4>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                <?= date('d.m.Y', strtotime($recent_post['created_at'])) ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    // Sort functionality
    $('#sort-posts').change(function() {
        const sortBy = $(this).val();
        const $container = $('#posts-container');
        const $posts = $container.children('article').get();
        
        $posts.sort(function(a, b) {
            switch(sortBy) {
                case 'newest':
                    return $(b).data('post-date') - $(a).data('post-date');
                case 'oldest':
                    return $(a).data('post-date') - $(b).data('post-date');
                case 'popular':
                    return $(b).data('post-views') - $(a).data('post-views');
                case 'title':
                    return $(a).data('post-title').localeCompare($(b).data('post-title'));
                default:
                    return 0;
            }
        });
        
        $container.empty().append($posts);
    });
    
    // Smooth scroll animation for posts
    $('article').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
        $(this).addClass('animate-fade-in-up');
    });
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
}

.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>