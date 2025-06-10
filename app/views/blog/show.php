<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <article class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Featured Image -->
                <?php if (!empty($post['featured_image'])): ?>
                    <div class="aspect-w-16 aspect-h-9">
                        <img src="<?= htmlspecialchars($post['featured_image']) ?>" 
                             class="w-full h-64 md:h-80 object-cover" 
                             alt="<?= htmlspecialchars($post['title']) ?>">
                    </div>
                <?php endif; ?>
                
                <div class="p-6 md:p-8">
                    <!-- Title -->
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                        <?= htmlspecialchars($post['title']) ?>
                    </h1>
                    
                    <!-- Meta Information -->
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6 pb-6 border-b border-gray-200">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-calendar text-primary-500"></i>
                            <span><?= date('d.m.Y H:i', strtotime($post['published_at'])) ?></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-user text-primary-500"></i>
                            <span><?= htmlspecialchars($post['author_name']) ?></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-eye text-primary-500"></i>
                            <span><?= $post['views'] ?> görüntülenme</span>
                        </div>
                    </div>
                    
                    <!-- Categories -->
                    <?php if (!empty($categories)): ?>
                        <div class="mb-6">
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($categories as $category): ?>
                                    <a href="/blog/category/<?= htmlspecialchars($category['slug']) ?>" 
                                       class="bg-primary-100 hover:bg-primary-200 text-primary-700 px-3 py-1 rounded-full text-sm font-medium transition-colors">
                                        <?= htmlspecialchars($category['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Content -->
                    <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed">
                        <?= $post['content'] ?>
                    </div>
                </div>
                
                <!-- Author Bio -->
                <?php if (!empty($post['author_bio'])): ?>
                    <div class="mx-6 md:mx-8 mb-6 md:mb-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex flex-col sm:flex-row items-start space-y-4 sm:space-y-0 sm:space-x-4">
                            <?php if (!empty($post['author_avatar'])): ?>
                                <img src="<?= htmlspecialchars($post['author_avatar']) ?>" 
                                     class="w-16 h-16 rounded-full object-cover flex-shrink-0" 
                                     alt="<?= htmlspecialchars($post['author_name']) ?>">
                            <?php else: ?>
                                <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-xl font-bold">
                                        <?= strtoupper(substr($post['author_name'], 0, 1)) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    <?= htmlspecialchars($post['author_name']) ?>
                                </h3>
                                <p class="text-gray-600 leading-relaxed">
                                    <?= htmlspecialchars($post['author_bio']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </article>
            
            <!-- Similar Posts -->
            <?php if (!empty($similarPosts)): ?>
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Benzer Yazılar</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($similarPosts as $similarPost): ?>
                            <article class="bg-white rounded-xl shadow-lg overflow-hidden hover-lift">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-2">
                                        <a href="/blog/post/<?= htmlspecialchars($similarPost['slug']) ?>" 
                                           class="hover:text-primary-600 transition-colors">
                                            <?= htmlspecialchars($similarPost['title']) ?>
                                        </a>
                                    </h3>
                                    <div class="text-sm text-gray-600">
                                        <?= date('d.m.Y', strtotime($similarPost['published_at'])) ?> • 
                                        <?= htmlspecialchars($similarPost['author_name']) ?>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Comments Section -->
            <div class="mt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Yorumlar</h2>
                
                <!-- Existing Comments -->
                <?php if (!empty($comments)): ?>
                    <div class="space-y-6 mb-8">
                        <?php foreach ($comments as $comment): ?>
                            <div class="bg-white rounded-xl shadow-lg p-6">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                                    <h4 class="font-semibold text-gray-900">
                                        <?= htmlspecialchars($comment['name']) ?>
                                    </h4>
                                    <span class="text-sm text-gray-500">
                                        <?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?>
                                    </span>
                                </div>
                                <p class="text-gray-700 leading-relaxed">
                                    <?= nl2br(htmlspecialchars($comment['comment'])) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="bg-gray-50 rounded-xl p-8 text-center mb-8">
                        <i class="fas fa-comments text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600">Henüz yorum yok. İlk yorumu siz yapın!</p>
                    </div>
                <?php endif; ?>
                
                <!-- Comment Form -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-6 py-4">
                        <h3 class="text-xl font-semibold text-white">Yorum Yap</h3>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="/blog/post/<?= htmlspecialchars($post['slug']) ?>/comment" class="space-y-6">
                            <!-- Name and Email Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Ad Soyad <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           required
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        E-posta <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           required
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                                </div>
                            </div>
                            
                            <!-- Website -->
                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                                    Website (isteğe bağlı)
                                </label>
                                <input type="url" 
                                       id="website" 
                                       name="website"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                            </div>
                            
                            <!-- Comment -->
                            <div>
                                <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                                    Yorum <span class="text-red-500">*</span>
                                </label>
                                <textarea id="comment" 
                                          name="comment" 
                                          rows="5" 
                                          required
                                          class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none"
                                          placeholder="Yorumunuzu buraya yazın..."></textarea>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" 
                                    class="w-full sm:w-auto bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-8 py-3 rounded-lg font-semibold hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Yorum Gönder
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Categories -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Kategoriler</h3>
                </div>
                <div class="p-6">
                    <?php if (!empty($allCategories)): ?>
                        <div class="space-y-3">
                            <?php foreach ($allCategories as $category): ?>
                                <a href="/blog/category/<?= htmlspecialchars($category['slug']) ?>" 
                                   class="block p-3 rounded-lg border border-gray-200 hover:border-primary-300 hover:bg-primary-50 transition-all group">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium text-gray-900 group-hover:text-primary-700">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </span>
                                        <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                            <?= $category['post_count'] ?>
                                        </span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Popular Posts -->
            <?php if (!empty($popularPosts)): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Popüler Yazılar</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php foreach ($popularPosts as $index => $popularPost): ?>
                                <div class="flex items-start space-x-4">
                                    <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-sm font-bold">
                                            <?= $index + 1 ?>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <a href="/blog/post/<?= htmlspecialchars($popularPost['slug']) ?>" 
                                           class="font-medium text-gray-900 hover:text-primary-600 transition-colors line-clamp-2 leading-tight">
                                            <?= htmlspecialchars($popularPost['title']) ?>
                                        </a>
                                        <div class="flex items-center space-x-2 mt-2 text-xs text-gray-500">
                                            <i class="fas fa-eye"></i>
                                            <span><?= $popularPost['views'] ?> görüntülenme</span>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($index < count($popularPosts) - 1): ?>
                                    <hr class="border-gray-200">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Back to Blog -->
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-xl p-6 text-center text-white">
                <i class="fas fa-arrow-left text-2xl mb-4"></i>
                <h3 class="text-lg font-semibold mb-2">Tüm Yazıları Görüntüle</h3>
                <p class="text-gray-300 text-sm mb-4">Blog ana sayfasına dönün ve diğer yazıları keşfedin</p>
                <a href="/blog" 
                   class="inline-block bg-white text-gray-900 px-6 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                    Blog Ana Sayfa
                </a>
            </div>
        </div>
    </div>
</div>