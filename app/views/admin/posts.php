<!-- Page Header -->
<div class="bg-gradient-to-r from-blue-600 via-indigo-500 to-blue-700 text-white rounded-2xl p-8 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-newspaper mr-3"></i>Blog Yazıları Yönetimi
            </h1>
            <p class="text-white/80">Blog yazılarını görüntüleyin, düzenleyin ve yönetin.</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-edit text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Navigation -->
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="lg:col-span-3">
        <!-- Action Bar -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-list mr-2 text-blue-500"></i>
                        Blog Yazıları
                    </h2>
                    <p class="text-gray-600 text-sm">Toplam <?= count($posts) ?> yazı</p>
                </div>
                <a href="/blog/create" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Yeni Yazı Ekle
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                    <select id="status-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tüm Durumlar</option>
                        <option value="published">Yayınlanan</option>
                        <option value="draft">Taslak</option>
                        <option value="archived">Arşivlenen</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Yazar</label>
                    <select id="author-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tüm Yazarlar</option>
                        <?php 
                        $authors = [];
                        foreach ($posts as $post) {
                            if (!empty($post['author_name']) && !in_array($post['author_name'], $authors)) {
                                $authors[] = $post['author_name'];
                            }
                        }
                        foreach ($authors as $author): ?>
                            <option value="<?= htmlspecialchars($author) ?>"><?= htmlspecialchars($author) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select id="category-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tüm Kategoriler</option>
                        <?php 
                        $categories = [];
                        foreach ($posts as $post) {
                            if (!empty($post['category_names'])) {
                                $postCategories = explode(', ', $post['category_names']);
                                foreach ($postCategories as $cat) {
                                    $cat = trim($cat);
                                    if (!in_array($cat, $categories)) {
                                        $categories[] = $cat;
                                    }
                                }
                            }
                        }
                        sort($categories);
                        foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Arama</label>
                    <input type="text" id="search-filter" placeholder="Başlık ara..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Posts Grid -->
        <?php if (!empty($posts)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="posts-grid">
                <?php foreach ($posts as $post): ?>
                    <div class="post-card bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group" 
                         data-status="<?= $post['status'] ?>"
                         data-author="<?= htmlspecialchars($post['author_name'] ?? '') ?>"
                         data-categories="<?= htmlspecialchars($post['category_names'] ?? '') ?>"
                         data-title="<?= htmlspecialchars($post['title']) ?>">
                        
                        <!-- Card Header -->
                        <div class="relative h-48 bg-gradient-to-r from-blue-500 to-indigo-600 overflow-hidden">
                            <?php if (!empty($post['featured_image'])): ?>
                                <img src="<?= htmlspecialchars($post['featured_image']) ?>" 
                                     alt="<?= htmlspecialchars($post['title']) ?>"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-white text-4xl opacity-50"></i>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4">
                                <?php 
                                $statusConfig = [
                                    'published' => ['bg' => 'bg-green-500', 'text' => 'Yayında', 'icon' => 'fa-check-circle'],
                                    'draft' => ['bg' => 'bg-yellow-500', 'text' => 'Taslak', 'icon' => 'fa-edit'],
                                    'archived' => ['bg' => 'bg-red-500', 'text' => 'Arşivli', 'icon' => 'fa-archive']
                                ];
                                $config = $statusConfig[$post['status']] ?? ['bg' => 'bg-gray-500', 'text' => ucfirst($post['status']), 'icon' => 'fa-question'];
                                ?>
                                <span class="<?= $config['bg'] ?> text-white px-3 py-1 rounded-full text-sm font-semibold backdrop-blur-sm">
                                    <i class="fas <?= $config['icon'] ?> mr-1"></i>
                                    <?= $config['text'] ?>
                                </span>
                            </div>
                            
                            <!-- View Count -->
                            <div class="absolute top-4 right-4">
                                <span class="bg-black/50 text-white px-3 py-1 rounded-full text-sm backdrop-blur-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    <?= number_format($post['views'] ?? 0) ?>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Card Content -->
                        <div class="p-6">
                            <!-- Title -->
                            <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                <a href="/blog/post/<?= htmlspecialchars($post['slug']) ?>" target="_blank" class="hover:underline">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h3>
                            
                            <!-- Excerpt -->
                            <?php if (!empty($post['excerpt'])): ?>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    <?= htmlspecialchars($post['excerpt']) ?>
                                </p>
                            <?php endif; ?>
                            
                            <!-- Categories -->
                            <?php if (!empty($post['category_names'])): ?>
                                <div class="mb-4">
                                    <?php 
                                    $categories = array_slice(explode(', ', $post['category_names']), 0, 3);
                                    foreach ($categories as $category): 
                                    ?>
                                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-md text-xs font-medium mr-2 mb-1">
                                            <i class="fas fa-tag mr-1"></i>
                                            <?= htmlspecialchars(trim($category)) ?>
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if (count(explode(', ', $post['category_names'])) > 3): ?>
                                        <span class="text-xs text-gray-500">+<?= count(explode(', ', $post['category_names'])) - 3 ?> daha</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Author & Date -->
                            <div class="flex items-center justify-between mb-4 pt-4 border-t border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white text-sm font-semibold">
                                            <?= strtoupper(substr($post['author_name'] ?? 'U', 0, 1)) ?>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($post['author_name'] ?? 'Bilinmeyen') ?></p>
                                        <p class="text-xs text-gray-500"><?= date('d.m.Y', strtotime($post['created_at'])) ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Stats -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <span><i class="fas fa-comment mr-1"></i><?= $post['comment_count'] ?? 0 ?></span>
                                <span><i class="fas fa-heart mr-1"></i><?= $post['likes'] ?? 0 ?></span>
                                <span><i class="fas fa-calendar mr-1"></i><?= date('d.m', strtotime($post['created_at'])) ?></span>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <div class="flex space-x-2">
                                    <a href="/blog/post/<?= htmlspecialchars($post['slug']) ?>" 
                                       target="_blank"
                                       class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                                       title="Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/blog/edit/<?= $post['id'] ?>" 
                                       class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                                       title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                                
                                <form method="POST" action="/blog/delete/<?= $post['id'] ?>" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                    <button type="submit" 
                                            onclick="return confirm('Bu yazıyı silmek istediğinizden emin misiniz?')"
                                            class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                                            title="Sil">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-file-alt text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Henüz blog yazısı yok</h3>
                <p class="text-gray-600 mb-6">İlk blog yazınızı oluşturmak için aşağıdaki butona tıklayın.</p>
                <a href="/blog/create" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    İlk Yazımı Oluştur
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

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

.post-card {
    transition: all 0.3s ease;
}

.post-card:hover {
    transform: translateY(-4px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('status-filter');
    const authorFilter = document.getElementById('author-filter');
    const categoryFilter = document.getElementById('category-filter');
    const searchFilter = document.getElementById('search-filter');
    
    function applyFilters() {
        const cards = document.querySelectorAll('.post-card');
        const statusValue = statusFilter.value.toLowerCase();
        const authorValue = authorFilter.value.toLowerCase();
        const categoryValue = categoryFilter.value.toLowerCase();
        const searchValue = searchFilter.value.toLowerCase();
        
        cards.forEach(card => {
            const status = card.dataset.status.toLowerCase();
            const author = card.dataset.author.toLowerCase();
            const categories = card.dataset.categories.toLowerCase();
            const title = card.dataset.title.toLowerCase();
            
            const statusMatch = !statusValue || status === statusValue;
            const authorMatch = !authorValue || author.includes(authorValue);
            const categoryMatch = !categoryValue || categories.includes(categoryValue);
            const searchMatch = !searchValue || title.includes(searchValue);
            
            if (statusMatch && authorMatch && categoryMatch && searchMatch) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    [statusFilter, authorFilter, categoryFilter, searchFilter].forEach(filter => {
        filter.addEventListener('change', applyFilters);
        filter.addEventListener('input', applyFilters);
    });
});
</script>