<!-- Page Header -->
<div class="bg-gradient-to-r from-orange-600 to-amber-600 text-white rounded-2xl p-8 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-edit mr-3"></i>Blog Yazısını Düzenle
            </h1>
            <p class="text-white/80">
                "<?= htmlspecialchars($post['title'] ?? '') ?>" yazısını düzenleyin ve değişikliklerinizi kaydedin.
            </p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-edit text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Main Form -->
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <form method="POST" enctype="multipart/form-data" id="blog-form" action="/blog/edit/<?= $post['id'] ?? '' ?>">
                <!-- Form Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Yazı Bilgileri</h2>
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-edit mr-2"></i>Blog yazısı düzenleniyor
                        </div>
                    </div>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Title and Slug Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="space-y-2">
                            <label for="title" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-heading mr-2 text-primary-500"></i>Başlık
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="<?= isset($post) ? htmlspecialchars($post['title'] ?? '') : '' ?>" 
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
                                       value="<?= isset($post) ? htmlspecialchars($post['slug'] ?? '') : '' ?>"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                       placeholder="otomatik-olusturulur">
                            </div>
                            <p class="text-sm text-gray-500">Boş bırakılırsa başlıktan otomatik oluşturulur</p>
                        </div>
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
                                  placeholder="Yazınızın kısa bir özetini girin (isteğe bağlı)..."><?= isset($post) ? htmlspecialchars($post['excerpt'] ?? '') : '' ?></textarea>
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
                            <!-- Quill Editor Container -->
                            <div id="quill-editor" style="min-height: 300px;" class="bg-white border border-gray-300 rounded-lg"></div>
                            <textarea id="content" name="content" style="display: none;"><?= isset($post) ? htmlspecialchars($post['content'] ?? '') : '' ?></textarea>
                        </div>
                        <div class="flex justify-between">
                            <p class="text-sm text-gray-500">Zengin metin editörü ile kolayca formatlayın</p>
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
                                    class="w-full">
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>"
                                            <?= (isset($postCategories) && in_array($category['id'], array_column($postCategories, 'id'))) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <p class="text-sm text-gray-500">Birden fazla kategori seçebilirsiniz</p>
                        </div>
                        
                        <!-- Status Info -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-info-circle mr-2 text-primary-500"></i>Yazı Durumu
                            </label>
                            <div class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50">
                                <?php
                                $currentStatus = isset($post) ? $post['status'] : 'draft';
                                $statusConfig = [
                                    'draft' => [
                                        'icon' => '📝',
                                        'text' => 'Taslak',
                                        'color' => 'text-yellow-700',
                                        'bg' => 'bg-yellow-100'
                                    ],
                                    'published' => [
                                        'icon' => '✅',
                                        'text' => 'Yayınlandı',
                                        'color' => 'text-green-700',
                                        'bg' => 'bg-green-100'
                                    ],
                                    'archived' => [
                                        'icon' => '📦',
                                        'text' => 'Arşivlendi',
                                        'color' => 'text-gray-700',
                                        'bg' => 'bg-gray-100'
                                    ]
                                ];
                                $status = $statusConfig[$currentStatus] ?? $statusConfig['draft'];
                                ?>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full <?= $status['bg'] ?> <?= $status['color'] ?>">
                                            <span class="mr-1"><?= $status['icon'] ?></span>
                                            <span class="text-sm font-medium"><?= $status['text'] ?></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-lightbulb mr-1"></i>
                                Yazı durumu, sayfanın altındaki aksiyon butonları ile değiştirilir.
                                <?php if ($currentStatus === 'draft'): ?>
                                    "Yayınla" butonunu kullanarak yazıyı yayınlayabilirsiniz.
                                <?php elseif ($currentStatus === 'published'): ?>
                                    "Değişiklikleri Kaydet" ile güncelleyebilirsiniz.
                                <?php else: ?>
                                    Bu yazı arşivlenmiştir.
                                <?php endif; ?>
                            </p>
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
                                <img src="<?= htmlspecialchars($post['featured_image'] ?? '') ?>" 
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
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Meta Title -->
                            <div class="space-y-2">
                                <label for="meta_title" class="block text-sm font-medium text-gray-700">SEO Başlık</label>
                                <input type="text" 
                                       id="meta_title" 
                                       name="meta_title" 
                                       value="<?= isset($post) ? htmlspecialchars($post['meta_title'] ?? '') : '' ?>"
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
                                          placeholder="Arama motorları için açıklama"><?= isset($post) ? htmlspecialchars($post['meta_description'] ?? '') : '' ?></textarea>
                                <div id="meta-desc-counter" class="text-sm text-gray-500">0 / 160 karakter</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                    <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                        <a href="/blog/my-posts" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors text-center">
                            <i class="fas fa-arrow-left mr-2"></i>Geri Dön
                        </a>
                        <button type="button" id="preview-btn" class="w-full sm:w-auto bg-blue-100 hover:bg-blue-200 text-blue-700 px-6 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-eye mr-2"></i>Önizle
                        </button>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                        <button type="submit" data-action="save" class="w-full sm:w-auto bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-save mr-2"></i>Değişiklikleri Kaydet
                        </button>
                        <?php if (!isset($post) || $post['status'] === 'draft'): ?>
                            <button type="submit" data-action="publish" class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-globe mr-2"></i>Yayınla
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <input type="hidden" name="action" value="save" id="action-input">
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

<!-- Additional CSS for Quill and Select2 -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Custom styles for better integration -->
<style>
.ql-editor {
    min-height: 300px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    font-size: 16px;
    line-height: 1.6;
}

.ql-toolbar {
    border-top: 1px solid #d1d5db !important;
    border-left: 1px solid #d1d5db !important;
    border-right: 1px solid #d1d5db !important;
    border-bottom: none !important;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.ql-container {
    border-bottom: 1px solid #d1d5db !important;
    border-left: 1px solid #d1d5db !important;
    border-right: 1px solid #d1d5db !important;
    border-top: none !important;
    border-radius: 0 0 0.5rem 0.5rem !important;
}

.select2-container {
    width: 100% !important;
}

.select2-container--default .select2-selection--multiple {
    border: 1px solid #d1d5db !important;
    border-radius: 0.5rem !important;
    min-height: 48px !important;
    padding: 6px 12px !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #3b82f6 !important;
    border: 1px solid #2563eb !important;
    color: white !important;
    border-radius: 6px !important;
    padding: 4px 8px !important;
    margin: 4px 4px 0 0 !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: white !important;
    margin-right: 6px !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #fca5a5 !important;
}

.select2-dropdown {
    border: 1px solid #d1d5db !important;
    border-radius: 0.5rem !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #3b82f6 !important;
}
</style>

<!-- Additional Scripts -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Quill Editor
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Yazınızın içeriğini buraya yazın...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['blockquote', 'code-block'],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Set initial content if editing
    var initialContent = $('#content').val();
    if (initialContent) {
        quill.root.innerHTML = initialContent;
    }

    // Update hidden textarea on content change
    quill.on('text-change', function() {
        var html = quill.root.innerHTML;
        $('#content').val(html);
        
        // Update word count
        var text = quill.getText();
        var words = text.trim() ? text.trim().split(/\s+/).length : 0;
        $('#content-counter').text(words + ' kelime');
        $('#word-count').text(words);
        $('#char-count').text(text.length);
        $('#reading-time').text(Math.ceil(words / 200) + ' dk');
    });

    // Initialize Select2 for categories
    $('#categories').select2({
        placeholder: 'Kategorileri seçin...',
        allowClear: true,
        multiple: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Sonuç bulunamadı";
            },
            searching: function() {
                return "Aranıyor...";
            }
        }
    });

    // Prevent multiple form submissions
    var isSubmitting = false;
    
    // Button click handlers to set action
    $('button[data-action]').on('click', function() {
        const action = $(this).data('action');
        $('#action-input').val(action);
    });
    
    // Form submission - ensure Quill content is saved
    $('#blog-form').on('submit', function(e) {
        // Prevent multiple submissions
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }
        
        var html = quill.root.innerHTML;
        $('#content').val(html);
        
        // Validation
        var title = $('#title').val().trim();
        var content = quill.getText().trim();
        
        if (!title) {
            e.preventDefault();
            alert('Başlık alanı zorunludur!');
            return false;
        }
        
        if (!content || content.length < 10) {
            e.preventDefault();
            alert('İçerik en az 10 karakter olmalıdır!');
            return false;
        }
        
        // Set submitting flag
        isSubmitting = true;
        
        // Show loading state and disable all submit buttons
        var $submitButtons = $(this).find('button[type="submit"]');
        $submitButtons.prop('disabled', true);
        
        // Update button text based on action - only for submit buttons
        var action = $('#action-input').val();
        var $activeButton = $('button[data-action="' + action + '"]');
        if ($activeButton.length) {
            var loadingText = action === 'publish' ? 
                '<i class="fas fa-spinner fa-spin mr-2"></i>Yayınlanıyor...' : 
                '<i class="fas fa-spinner fa-spin mr-2"></i>Güncelleniyor...';
            
            $activeButton.html(loadingText);
        }
        
        // Re-enable form after timeout (fallback)
        setTimeout(function() {
            if (isSubmitting) {
                isSubmitting = false;
                $submitButtons.prop('disabled', false);
                $submitButtons.each(function() {
                    var $btn = $(this);
                    var action = $btn.data('action');
                    if (action === 'publish') {
                        $btn.html('<i class="fas fa-globe mr-2"></i>Yayınla');
                    } else {
                        $btn.html('<i class="fas fa-save mr-2"></i>Değişiklikleri Kaydet');
                    }
                });
            }
        }, 10000); // 10 second timeout
    });

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
    
    // Content statistics are now handled by Quill editor
    
    // File upload preview
    $('#featured_image').on('change', function() {
        const file = this.files[0];
        const $container = $(this).closest('.space-y-2');
        
        if (file) {
            // File size check (2MB = 2048000 bytes)
            if (file.size > 2048000) {
                alert('Dosya boyutu 2MB\'dan büyük olamaz!');
                $(this).val('');
                return;
            }
            
            // File type check
            if (!file.type.startsWith('image/')) {
                alert('Lütfen geçerli bir görsel dosyası seçin!');
                $(this).val('');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                // Remove existing preview
                $container.find('.preview-container').remove();
                
                // Create preview
                const preview = $(`
                    <div class="preview-container mt-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm text-gray-600">Seçilen görsel:</p>
                            <button type="button" class="remove-image text-red-500 hover:text-red-700 text-sm">
                                <i class="fas fa-times mr-1"></i>Kaldır
                            </button>
                        </div>
                        <img src="${e.target.result}" class="h-20 w-auto rounded border border-gray-200" alt="Önizleme">
                        <p class="text-xs text-gray-500 mt-2">${file.name} (${Math.round(file.size / 1024)} KB)</p>
                    </div>
                `);
                $container.append(preview);
                
                // Update upload area text
                const $uploadArea = $container.find('label[for="featured_image"]');
                $uploadArea.removeClass('border-gray-300').addClass('border-green-300 bg-green-50');
                $uploadArea.find('.flex-col').html(`
                    <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                    <p class="mb-2 text-sm text-green-600"><span class="font-semibold">Görsel seçildi!</span> Değiştirmek için tıklayın</p>
                    <p class="text-xs text-green-500">${file.name}</p>
                `);
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Remove image functionality
    $(document).on('click', '.remove-image', function() {
        const $container = $(this).closest('.space-y-2');
        const $fileInput = $container.find('#featured_image');
        
        // Clear file input
        $fileInput.val('');
        
        // Remove preview
        $container.find('.preview-container').remove();
        
        // Reset upload area
        const $uploadArea = $container.find('label[for="featured_image"]');
        $uploadArea.removeClass('border-green-300 bg-green-50').addClass('border-gray-300 bg-gray-50');
        $uploadArea.find('.flex-col').html(`
            <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Tıklayın</span> veya dosyayı sürükleyin</p>
            <p class="text-xs text-gray-500">PNG, JPG veya GIF (MAX. 2MB)</p>
        `);
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
    
    // Auto-save removed to prevent unwanted draft creation
    
    // Initialize counters
    $('#title').trigger('input');
    $('#excerpt').trigger('input');
    $('#meta_title').trigger('input');
    $('#meta_description').trigger('input');
    
    // Initialize word count for Quill
    if (quill.getText().trim()) {
        var text = quill.getText();
        var words = text.trim() ? text.trim().split(/\s+/).length : 0;
        $('#content-counter').text(words + ' kelime');
        $('#word-count').text(words);
        $('#char-count').text(text.length);
        $('#reading-time').text(Math.ceil(words / 200) + ' dk');
    }
});
</script>