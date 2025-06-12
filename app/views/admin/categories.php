<!-- Page Header -->
<div class="bg-gradient-to-r from-green-600 via-emerald-500 to-green-700 text-white rounded-2xl p-8 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-tags mr-3"></i>Kategori Yönetimi
            </h1>
            <p class="text-white/80">Blog kategorilerini oluşturun, düzenleyin ve organize edin.</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-folder-open text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Navigation -->
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="lg:col-span-3 space-y-8">
        <!-- Add New Category Form -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-6">
                <h2 class="text-xl font-semibold">
                    <i class="fas fa-plus mr-2"></i>Yeni Kategori Ekle
                </h2>
            </div>
            
            <form method="POST" class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Category Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-tag mr-2 text-primary-500"></i>Kategori Adı
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                               placeholder="Kategori adını girin..."
                               required>
                    </div>
                    
                    <!-- Description -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-align-left mr-2 text-primary-500"></i>Açıklama
                        </label>
                        <input type="text" 
                               id="description" 
                               name="description" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                               placeholder="Kısa açıklama (isteğe bağlı)">
                    </div>
                    
                    <!-- Color and Submit -->
                    <div class="space-y-2">
                        <label for="color" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-palette mr-2 text-primary-500"></i>Renk
                        </label>
                        <div class="flex space-x-3">
                            <input type="color" 
                                   id="color" 
                                   name="color" 
                                   value="#10b981"
                                   class="w-16 h-12 border border-gray-300 rounded-lg cursor-pointer">
                            <button type="submit" 
                                    class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                                <i class="fas fa-plus mr-2"></i>Kategori Ekle
                            </button>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            </form>
        </div>
        
        <!-- Categories List -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold">
                        <i class="fas fa-list mr-2"></i>Kategoriler
                        <span class="bg-white/20 text-sm px-2 py-1 rounded-full ml-2">
                            <?= count($categories ?? []) ?> kategori
                        </span>
                    </h2>
                    
                    <!-- Search and Filter -->
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <input type="text" 
                                   id="category-search" 
                                   placeholder="Kategori ara..."
                                   class="w-64 px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/70 focus:outline-none focus:bg-white/20">
                            <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-white/70"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <?php if (!empty($categories)): ?>
                    <!-- Categories Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6" id="categories-grid">
                        <?php foreach ($categories as $category): ?>
                            <div class="category-card bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-all duration-300 border-l-4" 
                                 style="border-left-color: <?= htmlspecialchars($category['color'] ?? '#10b981') ?>"
                                 data-category-name="<?= strtolower(htmlspecialchars($category['name'])) ?>">
                                
                                <!-- Category Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </h3>
                                        <?php if (!empty($category['description'])): ?>
                                            <p class="text-sm text-gray-600 mb-3">
                                                <?= htmlspecialchars($category['description']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Color Badge -->
                                    <div class="w-6 h-6 rounded-full border-2 border-white shadow-sm" 
                                         style="background-color: <?= htmlspecialchars($category['color'] ?? '#10b981') ?>"></div>
                                </div>
                                
                                <!-- Statistics -->
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="text-center bg-white rounded-lg p-3">
                                        <div class="text-2xl font-bold text-blue-600">
                                            <?= $category['post_count'] ?? 0 ?>
                                        </div>
                                        <div class="text-xs text-gray-500">Blog Yazısı</div>
                                    </div>
                                    <div class="text-center bg-white rounded-lg p-3">
                                        <div class="text-2xl font-bold text-green-600">
                                            <?= number_format($category['views'] ?? 0) ?>
                                        </div>
                                        <div class="text-xs text-gray-500">Görüntülenme</div>
                                    </div>
                                </div>
                                
                                <!-- Meta Information -->
                                <div class="text-xs text-gray-500 mb-4 flex items-center space-x-4">
                                    <span>
                                        <i class="fas fa-calendar mr-1"></i>
                                        <?= date('d.m.Y', strtotime($category['created_at'])) ?>
                                    </span>
                                    <span>
                                        <i class="fas fa-link mr-1"></i>
                                        <?= htmlspecialchars($category['slug'] ?? '') ?>
                                    </span>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                    <div class="flex items-center space-x-2">
                                        <a href="/blog/category/<?= htmlspecialchars($category['slug']) ?>" 
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm rounded-lg transition-colors">
                                            <i class="fas fa-eye mr-1.5"></i>
                                            Görüntüle
                                        </a>
                                        
                                        <button onclick="editCategory(<?= $category['id'] ?>, '<?= htmlspecialchars($category['name']) ?>', '<?= htmlspecialchars($category['description'] ?? '') ?>', '<?= htmlspecialchars($category['color'] ?? '#10b981') ?>')"
                                                class="inline-flex items-center px-3 py-1.5 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-sm rounded-lg transition-colors">
                                            <i class="fas fa-edit mr-1.5"></i>
                                            Düzenle
                                        </button>
                                    </div>
                                    
                                    <button onclick="deleteCategory(<?= $category['id'] ?>, '<?= htmlspecialchars($category['name']) ?>')"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-sm rounded-lg transition-colors">
                                        <i class="fas fa-trash mr-1.5"></i>
                                        Sil
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Empty Search Results -->
                    <div id="no-results" class="hidden text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Kategori bulunamadı</h3>
                        <p class="text-gray-600">Arama kriterlerinize uygun kategori bulunmuyor.</p>
                    </div>
                    
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-tags text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Henüz kategori eklenmemiş
                        </h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            Blog yazılarınızı organize etmek için kategoriler oluşturun. Yukarıdaki formu kullanarak ilk kategoriyi ekleyebilirsiniz.
                        </p>
                        <button onclick="document.getElementById('name').focus()" 
                                class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i>İlk Kategorini Oluştur
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-edit mr-2 text-primary-500"></i>Kategoriyi Düzenle
                </h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="edit-form" method="POST">
                <input type="hidden" id="edit-id" name="id">
                <input type="hidden" name="action" value="edit">
                
                <div class="space-y-4">
                    <div>
                        <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-2">Kategori Adı</label>
                        <input type="text" 
                               id="edit-name" 
                               name="name" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               required>
                    </div>
                    
                    <div>
                        <label for="edit-description" class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                        <input type="text" 
                               id="edit-description" 
                               name="description" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="edit-color" class="block text-sm font-medium text-gray-700 mb-2">Renk</label>
                        <input type="color" 
                               id="edit-color" 
                               name="color" 
                               class="w-full h-12 border border-gray-300 rounded-lg cursor-pointer">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            onclick="closeEditModal()"
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium transition-colors">
                        İptal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                        <i class="fas fa-save mr-1"></i>Kaydet
                    </button>
                </div>
                
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-trash text-red-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Kategoriyi Sil</h3>
                    <p class="text-sm text-gray-600">Bu işlem geri alınamaz</p>
                </div>
            </div>
            
            <p class="text-gray-700 mb-6">
                "<span id="delete-category-name"></span>" kategorisini silmek istediğinizden emin misiniz?
                Bu kategorideki tüm yazılar "Genel" kategorisine taşınacaktır.
            </p>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium transition-colors">
                    İptal
                </button>
                <button type="button" 
                        id="confirm-delete"
                        class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-colors">
                    <i class="fas fa-trash mr-1"></i>Sil
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Search functionality
    $('#category-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        const categories = $('.category-card');
        let visibleCount = 0;
        
        categories.each(function() {
            const categoryName = $(this).data('category-name');
            if (categoryName.includes(searchTerm)) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
        });
        
        if (visibleCount === 0 && searchTerm !== '') {
            $('#no-results').removeClass('hidden');
        } else {
            $('#no-results').addClass('hidden');
        }
    });
});

// Edit category functionality
let editCategoryId = null;

function editCategory(id, name, description, color) {
    editCategoryId = id;
    $('#edit-id').val(id);
    $('#edit-name').val(name);
    $('#edit-description').val(description);
    $('#edit-color').val(color);
    $('#edit-modal').removeClass('hidden');
}

function closeEditModal() {
    $('#edit-modal').addClass('hidden');
    editCategoryId = null;
}

// Delete category functionality
let deleteCategoryId = null;

function deleteCategory(id, name) {
    deleteCategoryId = id;
    $('#delete-category-name').text(name);
    $('#delete-modal').removeClass('hidden');
}

function closeDeleteModal() {
    $('#delete-modal').addClass('hidden');
    deleteCategoryId = null;
}

// Handle delete confirmation
$('#confirm-delete').click(function() {
    if (!deleteCategoryId) return;
    
    const $btn = $(this);
    const originalText = $btn.html();
    
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Siliniyor...');
    
    $.ajax({
        url: `/admin/categories/delete/${deleteCategoryId}`,
        method: 'POST',
        data: {
            csrf_token: '<?= htmlspecialchars($csrf_token) ?>'
        },
        success: function(response) {
            if (response && response.success !== false) {
                window.location.reload();
            } else {
                $btn.prop('disabled', false).html(originalText);
                alert('Silme işlemi başarısız oldu.');
            }
        },
        error: function() {
            $btn.prop('disabled', false).html(originalText);
            alert('Bir hata oluştu.');
        }
    });
});

// Close modals on outside click
$('#edit-modal, #delete-modal').click(function(e) {
    if (e.target === this) {
        if (this.id === 'edit-modal') closeEditModal();
        if (this.id === 'delete-modal') closeDeleteModal();
    }
});

// Close modals on Escape key
$(document).keydown(function(e) {
    if (e.keyCode === 27) {
        closeEditModal();
        closeDeleteModal();
    }
});
</script>