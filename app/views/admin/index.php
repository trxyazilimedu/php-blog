<!-- Admin Dashboard Header -->
<div class="bg-gradient-to-r from-primary-600 via-secondary-500 to-primary-700 text-white rounded-2xl p-8 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-tachometer-alt mr-3"></i>Admin Dashboard
            </h1>
            <p class="text-white/80">Site yönetiminize hoş geldiniz. Tüm işlemlerinizi buradan yönetebilirsiniz.</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-crown text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Navigation -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-24">
            <!-- Navigation Header -->
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white p-6">
                <h3 class="text-lg font-semibold">
                    <i class="fas fa-cog mr-2"></i>Yönetim Paneli
                </h3>
            </div>
            
            <!-- Navigation Links -->
            <nav class="p-2">
                <a href="/admin" class="flex items-center px-4 py-3 text-gray-700 bg-primary-50 border-r-4 border-primary-500 rounded-lg mb-1 font-medium">
                    <i class="fas fa-tachometer-alt w-5 h-5 mr-3 text-primary-500"></i>
                    Dashboard
                </a>
                <a href="/admin/users" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary-600 rounded-lg mb-1 transition-colors">
                    <i class="fas fa-users w-5 h-5 mr-3"></i>
                    Kullanıcılar
                </a>
                <a href="/admin/posts" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary-600 rounded-lg mb-1 transition-colors">
                    <i class="fas fa-edit w-5 h-5 mr-3"></i>
                    Blog Yazıları
                </a>
                <a href="/admin/categories" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary-600 rounded-lg mb-1 transition-colors">
                    <i class="fas fa-tags w-5 h-5 mr-3"></i>
                    Kategoriler
                </a>
                <a href="/admin/settings" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary-600 rounded-lg mb-1 transition-colors">
                    <i class="fas fa-cog w-5 h-5 mr-3"></i>
                    Ayarlar
                </a>
                
                <div class="border-t border-gray-200 my-4"></div>
                
                <a href="/" target="_blank" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-green-600 rounded-lg mb-1 transition-colors">
                    <i class="fas fa-external-link-alt w-5 h-5 mr-3"></i>
                    Siteyi Görüntüle
                </a>
            </nav>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="lg:col-span-3">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Toplam Kullanıcı</p>
                        <p class="text-3xl font-bold text-gray-900"><?= $stats['users']['total'] ?? 0 ?></p>
                        <p class="text-sm text-green-600">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +<?= $stats['users']['active'] ?? 0 ?> aktif
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Published Posts -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Yayınlanan Yazı</p>
                        <p class="text-3xl font-bold text-gray-900"><?= $stats['posts']['published_posts'] ?? 0 ?></p>
                        <p class="text-sm text-yellow-600">
                            <i class="fas fa-edit mr-1"></i>
                            <?= $stats['posts']['draft_posts'] ?? 0 ?> taslak
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-alt text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Total Views -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Toplam Görüntülenme</p>
                        <p class="text-3xl font-bold text-gray-900"><?= number_format($stats['posts']['total_views'] ?? 0) ?></p>
                        <p class="text-sm text-purple-600">
                            <i class="fas fa-eye mr-1"></i>
                            Bu ay
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-eye text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Pending Comments -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Bekleyen Yorum</p>
                        <p class="text-3xl font-bold text-gray-900"><?= $stats['comments']['pending'] ?? 0 ?></p>
                        <p class="text-sm text-orange-600">
                            <i class="fas fa-clock mr-1"></i>
                            Onay bekliyor
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-comments text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity Section -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- Recent Posts -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6">
                    <h3 class="text-lg font-semibold">
                        <i class="fas fa-newspaper mr-2"></i>Son Blog Yazıları
                    </h3>
                </div>
                
                <div class="p-6">
                    <?php if (!empty($recentPosts)): ?>
                        <div class="space-y-4">
                            <?php foreach ($recentPosts as $post): ?>
                                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900 truncate">
                                            <?= htmlspecialchars($post['title']) ?>
                                        </h4>
                                        <p class="text-sm text-gray-600">
                                            <?= htmlspecialchars($post['author_name'] ?? 'Bilinmeyen') ?> - 
                                            <?= date('d.m.Y', strtotime($post['created_at'])) ?>
                                        </p>
                                        <div class="flex items-center mt-2">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                <?= $post['status'] === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                                <?= ucfirst($post['status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end text-sm text-gray-500">
                                        <span><i class="fas fa-eye mr-1"></i><?= $post['views'] ?? 0 ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-6 text-center">
                            <a href="/admin/posts" class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors">
                                <i class="fas fa-arrow-right mr-2"></i>Tüm Yazıları Görüntüle
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 mb-4">Henüz blog yazısı yok</p>
                            <a href="/blog/create" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>İlk Yazıyı Oluştur
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Recent Users -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6">
                    <h3 class="text-lg font-semibold">
                        <i class="fas fa-user-plus mr-2"></i>Son Kayıt Olan Kullanıcılar
                    </h3>
                </div>
                
                <div class="p-6">
                    <?php if (!empty($recentUsers)): ?>
                        <div class="space-y-4">
                            <?php foreach ($recentUsers as $user): ?>
                                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div class="w-10 h-10 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-semibold">
                                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900">
                                            <?= htmlspecialchars($user['name']) ?>
                                        </h4>
                                        <p class="text-sm text-gray-600 truncate">
                                            <?= htmlspecialchars($user['email']) ?>
                                        </p>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                            <?= ucfirst($user['status']) ?>
                                        </span>
                                        <span class="text-xs text-gray-500 mt-1">
                                            <?= date('d.m.Y', strtotime($user['created_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-6 text-center">
                            <a href="/admin/users" class="inline-flex items-center px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-colors">
                                <i class="fas fa-arrow-right mr-2"></i>Tüm Kullanıcıları Görüntüle
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500">Bekleyen kullanıcı yok</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">
                <i class="fas fa-bolt text-yellow-500 mr-2"></i>Hızlı İşlemler
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="/blog/create" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors group">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Yeni Yazı</span>
                </a>
                
                <a href="/admin/categories" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-colors group">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-tags text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Kategori Ekle</span>
                </a>
                
                <a href="/admin/users" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-xl transition-colors group">
                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-check text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Kullanıcı Onayla</span>
                </a>
                
                <a href="/admin/settings" class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-xl transition-colors group">
                    <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-cog text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Site Ayarları</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-refresh dashboard every 30 seconds
    setInterval(function() {
        // Update statistics without page reload
        $.ajax({
            url: '/admin/dashboard-stats',
            method: 'GET',
            success: function(data) {
                if (data.success) {
                    // Update stats in real time
                    // This would be implemented with actual endpoint
                }
            }
        });
    }, 30000);
    
    // Add pulse animation to new content
    $('.hover-lift').hover(
        function() {
            $(this).addClass('scale-105');
        },
        function() {
            $(this).removeClass('scale-105');
        }
    );
});
</script>