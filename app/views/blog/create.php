<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-600 to-secondary-600 text-white rounded-2xl p-8 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-<?= isset($post) ? 'edit' : 'plus' ?> mr-3"></i>
                <?= isset($post) ? 'Blog Yazısını Düzenle' : 'Yeni Blog Yazısı' ?>
            </h1>
            <p class="text-white/80">
                <?= isset($post) ? 'Mevcut yazınızı düzenleyin ve değişikliklerinizi kaydedin.' : 'Yeni bir blog yazısı oluşturun ve okuyucularınızla paylaşın.' ?>
            </p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-pen-fancy text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Main Form -->
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <form method="POST" enctype="multipart/form-data" id="blog-form">
                <!-- Form Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Yazı Bilgileri</h2>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Otomatik Kaydet:</span>
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-heading mr-2 text-primary-500"></i>Başlık
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="<?= isset($post) ? htmlspecialchars($post['title']) : '' ?>" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                               placeholder="Yazınızın başlığını girin..."
                               required>
                        <div id="title-counter" class="text-sm text-gray-500">0 / 100 karakter</div>
                    </div>
                    
                    <!-- Slug -->
                    <div class="space-y-2">
                        <label for="slug" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-link mr-2 text-primary-500"></i>URL Slug
                        </label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                /blog/post/
                            </span>
                            <input type="text" 
                                   id="slug" 
                                   name="slug" 
                                   value="<?= isset($post) ? htmlspecialchars($post['slug']) : '' ?>"
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                   placeholder="otomatik-olusturulur">
                        </div>
                        <p class="text-sm text-gray-500">Boş bırakılırsa başlıktan otomatik oluşturulur</p>
                    </div>
                    
                    <!-- Excerpt -->
                    <div class="space-y-2">
                        <label for="excerpt" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-quote-left mr-2 text-primary-500"></i>Özet
                        </label>
                        <textarea id="excerpt" 
                                  name="excerpt" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                  placeholder="Yazınızın kısa bir özetini girin (isteğe bağlı)..."><?= isset($post) ? htmlspecialchars($post['excerpt']) : '' ?></textarea>
                        <div class="flex justify-between">
                            <p class="text-sm text-gray-500">Boş bırakılırsa içerikten otomatik oluşturulur</p>
                            <div id="excerpt-counter" class="text-sm text-gray-500">0 / 250 karakter</div>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="space-y-2">
                        <label for="content" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-file-alt mr-2 text-primary-500"></i>İçerik
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <textarea id="content" 
                                      name="content" 
                                      rows="15"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all font-mono"
                                      placeholder="Yazınızın içeriğini buraya yazın..."
                                      required><?= isset($post) ? htmlspecialchars($post['content']) : '' ?></textarea>
                            <!-- Formatting Toolbar -->
                            <div class="absolute top-2 right-2 flex space-x-1 opacity-70 hover:opacity-100 transition-opacity">
                                <button type="button" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded" title="Kalın">
                                    <i class="fas fa-bold"></i>
                                </button>
                                <button type="button" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded" title="İtalik">
                                    <i class="fas fa-italic"></i>
                                </button>
                                <button type="button" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded" title="Kod">
                                    <i class="fas fa-code"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <p class="text-sm text-gray-500">Markdown formatını destekler</p>
                            <div id="content-counter" class="text-sm text-gray-500">0 kelime</div>
                        </div>
                    </div>
                    
                    <!-- Categories and Status Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Categories -->
                        <div class="space-y-2">
                            <label for="categories" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-tags mr-2 text-primary-500"></i>Kategoriler
                            </label>
                            <select name="categories[]" 
                                    id="categories"
                                    multiple 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>"
                                            <?= (isset($postCategories) && in_array($category['id'], array_column($postCategories, 'id'))) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <p class="text-sm text-gray-500">Ctrl tuşu ile birden fazla kategori seçebilirsiniz</p>
                        </div>
                        
                        <!-- Status -->
                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-circle mr-2 text-primary-500"></i>Durum
                            </label>
                            <select name="status" 
                                    id="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                    required>
                                <option value="draft" <?= (isset($post) && $post['status'] === 'draft') ? 'selected' : '' ?>>
                                    📝 Taslak
                                </option>
                                <option value="published" <?= (isset($post) && $post['status'] === 'published') ? 'selected' : '' ?>>
                                    ✅ Yayınlandı
                                </option>
                                <option value="archived" <?= (isset($post) && $post['status'] === 'archived') ? 'selected' : '' ?>>
                                    📦 Arşivlendi
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Featured Image -->
                    <div class="space-y-2">
                        <label for="featured_image" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-image mr-2 text-primary-500"></i>Öne Çıkan Görsel
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="featured_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Tıklayın</span> veya dosyayı sürükleyin</p>
                                    <p class="text-xs text-gray-500">PNG, JPG veya GIF (MAX. 2MB)</p>
                                </div>
                                <input id="featured_image" name="featured_image" type="file" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <?php if (isset($post) && !empty($post['featured_image'])): ?>
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-2">Mevcut görsel:</p>
                                <img src="<?= htmlspecialchars($post['featured_image']) ?>" 
                                     class="h-20 w-auto rounded border border-gray-200" 
                                     alt="Mevcut görsel">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- SEO Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-search mr-2 text-primary-500"></i>SEO Ayarları
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Meta Title -->
                            <div class="space-y-2">
                                <label for="meta_title" class="block text-sm font-medium text-gray-700">SEO Başlık</label>
                                <input type="text" 
                                       id="meta_title" 
                                       name="meta_title" 
                                       value="<?= isset($post) ? htmlspecialchars($post['meta_title']) : '' ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                       placeholder="Arama motorları için başlık">
                                <div id="meta-title-counter" class="text-sm text-gray-500">0 / 60 karakter</div>
                            </div>
                            
                            <!-- Meta Description -->
                            <div class="space-y-2">
                                <label for="meta_description" class="block text-sm font-medium text-gray-700">SEO Açıklama</label>
                                <textarea id="meta_description" 
                                          name="meta_description" 
                                          rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                          placeholder="Arama motorları için açıklama"><?= isset($post) ? htmlspecialchars($post['meta_description']) : '' ?></textarea>
                                <div id="meta-desc-counter" class="text-sm text-gray-500">0 / 160 karakter</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                    <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                        <a href="/blog" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors text-center">
                            <i class="fas fa-arrow-left mr-2"></i>İptal
                        </a>
                        <button type="button" id="preview-btn" class="w-full sm:w-auto bg-blue-100 hover:bg-blue-200 text-blue-700 px-6 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-eye mr-2"></i>Önizle
                        </button>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                        <button type="submit" name="action" value="save" class="w-full sm:w-auto bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-save mr-2"></i><?= isset($post) ? 'Güncelle' : 'Taslak Kaydet' ?>
                        </button>
                        <?php if (!isset($post) || $post['status'] === 'draft'): ?>
                            <button type="submit" name="action" value="publish" class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-globe mr-2"></i>Yayınla
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            </form>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Writing Tips -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Yazım İpuçları
            </h3>
            <div class="space-y-3 text-sm text-gray-600">
                <div class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                    <span>Başlığınız açık ve çekici olsun</span>
                </div>
                <div class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                    <span>Özet kısmını mutlaka doldurun</span>
                </div>
                <div class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                    <span>Uygun kategoriler seçin</span>
                </div>
                <div class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                    <span>SEO ayarlarını unutmayın</span>
                </div>
                <div class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                    <span>Öne çıkan görsel ekleyin</span>
                </div>
            </div>
        </div>
        
        <!-- Markdown Guide -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fab fa-markdown text-blue-500 mr-2"></i>Markdown Rehberi
            </h3>
            <div class="space-y-2 text-sm font-mono">
                <div><span class="text-blue-600"># Başlık 1</span></div>
                <div><span class="text-blue-600">## Başlık 2</span></div>
                <div><span class="text-blue-600">**Kalın metin**</span></div>
                <div><span class="text-blue-600">*İtalik metin*</span></div>
                <div><span class="text-blue-600">`kod`</span></div>
                <div><span class="text-blue-600">[Link](url)</span></div>
                <div><span class="text-blue-600">![Görsel](url)</span></div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-line text-primary-500 mr-2"></i>İstatistikler
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Kelime Sayısı:</span>
                    <span id="word-count" class="text-sm font-semibold text-gray-900">0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Karakter Sayısı:</span>
                    <span id="char-count" class="text-sm font-semibold text-gray-900">0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Tahmini Okuma:</span>
                    <span id="reading-time" class="text-sm font-semibold text-gray-900">< 1 dk</span>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Preview Modal -->
<div id="preview-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-eye mr-2 text-primary-500"></i>Yazı Önizleme
                </h3>
                <button id="close-preview" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="preview-content" class="p-6 overflow-y-auto max-h-[70vh]">
                <!-- Preview content will be inserted here -->
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Character counters
    function updateCounter(input, counterId, maxLength) {
        const count = input.val().length;
        const counter = $('#' + counterId);
        counter.text(count + ' / ' + maxLength + ' karakter');
        
        if (count > maxLength * 0.9) {
            counter.addClass('text-red-500').removeClass('text-gray-500');
        } else {
            counter.addClass('text-gray-500').removeClass('text-red-500');
        }
    }
    
    // Title counter
    $('#title').on('input', function() {
        updateCounter($(this), 'title-counter', 100);
        
        // Auto-generate slug
        const slug = $(this).val()
            .toLowerCase()
            .replace(/ç/g, 'c')
            .replace(/ğ/g, 'g')
            .replace(/ı/g, 'i')
            .replace(/ö/g, 'o')
            .replace(/ş/g, 's')
            .replace(/ü/g, 'u')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
            
        if ($('#slug').val() === '' || $('#slug').data('auto') !== false) {
            $('#slug').val(slug).data('auto', true);
        }
    });
    
    // Slug manual edit
    $('#slug').on('input', function() {
        $(this).data('auto', false);
    });
    
    // Excerpt counter
    $('#excerpt').on('input', function() {
        updateCounter($(this), 'excerpt-counter', 250);
    });
    
    // Meta title counter
    $('#meta_title').on('input', function() {
        updateCounter($(this), 'meta-title-counter', 60);
    });
    
    // Meta description counter
    $('#meta_description').on('input', function() {
        updateCounter($(this), 'meta-desc-counter', 160);
    });
    
    // Content statistics
    $('#content').on('input', function() {
        const content = $(this).val();
        const words = content.trim() ? content.trim().split(/\s+/).length : 0;
        const chars = content.length;
        const readingTime = Math.ceil(words / 200);
        
        $('#word-count').text(words);
        $('#char-count').text(chars);
        $('#reading-time').text(readingTime + ' dk');
        $('#content-counter').text(words + ' kelime');
    });
    
    // File upload preview
    $('#featured_image').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create preview
                const preview = $(`
                    <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Seçilen görsel:</p>
                        <img src="${e.target.result}" class="h-20 w-auto rounded border border-gray-200" alt="Önizleme">
                    </div>
                `);
                $(this).parent().parent().find('.mt-3').remove();
                $(this).parent().parent().append(preview);
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Preview functionality
    $('#preview-btn').click(function() {
        const title = $('#title').val() || 'Başlıksız Yazı';
        const content = $('#content').val() || 'İçerik bulunamadı';
        const excerpt = $('#excerpt').val();
        
        const previewHtml = `
            <article class="prose max-w-none">
                <header class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">${title}</h1>
                    ${excerpt ? `<p class="text-lg text-gray-600 italic">${excerpt}</p>` : ''}
                </header>
                <div class="prose-content">
                    ${content.replace(/\n/g, '<br>')}
                </div>
            </article>
        `;
        
        $('#preview-content').html(previewHtml);
        $('#preview-modal').removeClass('hidden');
    });
    
    // Close preview
    $('#close-preview, #preview-modal').click(function(e) {
        if (e.target === this) {
            $('#preview-modal').addClass('hidden');
        }
    });
    
    // Auto-save functionality
    let autoSaveTimeout;
    function autoSave() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            const formData = $('#blog-form').serialize() + '&action=autosave';
            
            $.ajax({
                url: window.location.href,
                method: 'POST',
                data: formData,
                success: function(response) {
                    // Show auto-save indicator
                    $('.animate-pulse').removeClass('bg-green-500').addClass('bg-blue-500');
                    setTimeout(function() {
                        $('.animate-pulse').removeClass('bg-blue-500').addClass('bg-green-500');
                    }, 1000);
                }
            });
        }, 5000); // Auto-save after 5 seconds of inactivity
    }
    
    // Attach auto-save to form inputs
    $('#blog-form input, #blog-form textarea, #blog-form select').on('input change', autoSave);
    
    // Initialize counters
    $('#title').trigger('input');
    $('#excerpt').trigger('input');
    $('#meta_title').trigger('input');
    $('#meta_description').trigger('input');
    $('#content').trigger('input');
    
    // Form validation
    $('#blog-form').submit(function(e) {
        const title = $('#title').val().trim();
        const content = $('#content').val().trim();
        
        if (!title || !content) {
            e.preventDefault();
            alert('Başlık ve içerik alanları zorunludur!');
            return false;
        }
        
        // Show loading state
        $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Kaydediliyor...');
    });
});
</script>