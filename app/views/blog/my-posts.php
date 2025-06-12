<!-- Page Header -->
<div class="bg-gradient-to-r from-indigo-600 via-purple-500 to-indigo-700 text-white rounded-2xl p-8 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-file-alt mr-3"></i>Gönderilerim
            </h1>
            <p class="text-white/80">Yazdığınız blog gönderilerini görüntüleyin ve yönetin.</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-pen-fancy text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<?php if (!empty($stats)): ?>
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-alt text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Toplam Gönderi</p>
                <p class="text-2xl font-bold text-gray-900"><?= $stats['total_posts'] ?? 0 ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Yayınlanan</p>
                <p class="text-2xl font-bold text-gray-900"><?= $stats['published_posts'] ?? 0 ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-edit text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Taslak</p>
                <p class="text-2xl font-bold text-gray-900"><?= $stats['draft_posts'] ?? 0 ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-eye text-red-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Toplam Görüntüleme</p>
                <p class="text-2xl font-bold text-gray-900"><?= $stats['total_views'] ?? 0 ?></p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Action Bar -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div class="flex items-center space-x-4">
        <h2 class="text-xl font-semibold text-gray-900">Blog Gönderilerim</h2>
        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm">
            <?= count($posts) ?> gönderi
        </span>
    </div>
    
    <div class="flex items-center space-x-3">
        <select id="status-filter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <option value="all">Tüm Durumlar</option>
            <option value="published">Yayınlanan</option>
            <option value="draft">Taslak</option>
            <option value="archived">Arşivlenen</option>
        </select>
        
        <a href="/blog/create" 
           class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Yeni Gönderi
        </a>
    </div>
</div>

<!-- Posts List -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden">
    <?php if (!empty($posts)): ?>
        <div class="divide-y divide-gray-200">
            <?php foreach ($posts as $post): ?>
                <div class="p-6 hover:bg-gray-50 transition-colors post-item" data-status="<?= htmlspecialchars($post['status']) ?>">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 hover:text-primary-600 transition-colors">
                                    <a href="/blog/post/<?= htmlspecialchars($post['slug']) ?>">
                                        <?= htmlspecialchars($post['title']) ?>
                                    </a>
                                </h3>
                                
                                <!-- Status Badge -->
                                <?php 
                                $statusColors = [
                                    'published' => 'bg-green-100 text-green-800',
                                    'draft' => 'bg-yellow-100 text-yellow-800',
                                    'archived' => 'bg-gray-100 text-gray-800'
                                ];
                                $statusIcons = [
                                    'published' => 'fas fa-check-circle',
                                    'draft' => 'fas fa-edit',
                                    'archived' => 'fas fa-archive'
                                ];
                                $statusTexts = [
                                    'published' => 'Yayınlandı',
                                    'draft' => 'Taslak',
                                    'archived' => 'Arşivlendi'
                                ];
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusColors[$post['status']] ?? 'bg-gray-100 text-gray-800' ?>">
                                    <i class="<?= $statusIcons[$post['status']] ?? 'fas fa-file' ?> mr-1"></i>
                                    <?= $statusTexts[$post['status']] ?? ucfirst($post['status']) ?>
                                </span>
                            </div>
                            
                            <?php if (!empty($post['excerpt'])): ?>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                    <?= htmlspecialchars($post['excerpt']) ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="flex items-center text-sm text-gray-500 space-x-4">
                                <span>
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    <?= date('d.m.Y', strtotime($post['created_at'])) ?>
                                </span>
                                
                                <?php if ($post['status'] === 'published' && !empty($post['published_at'])): ?>
                                    <span>
                                        <i class="fas fa-globe mr-1"></i>
                                        <?= date('d.m.Y', strtotime($post['published_at'])) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <span>
                                    <i class="fas fa-eye mr-1"></i>
                                    <?= number_format($post['views'] ?? 0) ?> görüntüleme
                                </span>
                                
                                <?php if (!empty($post['category_names'])): ?>
                                    <span>
                                        <i class="fas fa-tags mr-1"></i>
                                        <?= htmlspecialchars($post['category_names']) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <span>
                                    <i class="fas fa-clock mr-1"></i>
                                    <?= ceil(str_word_count(strip_tags($post['content'])) / 200) ?> dk okuma
                                </span>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center space-x-2 ml-4">
                            <a href="/blog/post/<?= htmlspecialchars($post['slug']) ?>" 
                               class="text-gray-400 hover:text-primary-600 transition-colors p-2 rounded-lg hover:bg-gray-100"
                               title="Görüntüle">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <a href="/blog/edit/<?= $post['id'] ?>" 
                               class="text-gray-400 hover:text-blue-600 transition-colors p-2 rounded-lg hover:bg-gray-100"
                               title="Düzenle">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <button onclick="deletePost(<?= $post['id'] ?>, '<?= htmlspecialchars($post['title']) ?>')"
                                    class="text-gray-400 hover:text-red-600 transition-colors p-2 rounded-lg hover:bg-gray-100"
                                    title="Sil">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-file-alt text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">
                Henüz gönderi yazmadınız
            </h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                İlk blog gönderinizi oluşturmak için aşağıdaki butona tıklayın ve yazmaya başlayın.
            </p>
            <a href="/blog/create" 
               class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                İlk Gönderimi Oluştur
            </a>
        </div>
    <?php endif; ?>
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
                    <h3 class="text-lg font-semibold text-gray-900">Gönderiyi Sil</h3>
                    <p class="text-sm text-gray-600">Bu işlem geri alınamaz</p>
                </div>
            </div>
            
            <p class="text-gray-700 mb-6">
                "<span id="delete-post-title"></span>" başlıklı gönderiyi silmek istediğinizden emin misiniz?
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
    // Status filter
    $('#status-filter').change(function() {
        const selectedStatus = $(this).val();
        
        $('.post-item').each(function() {
            const postStatus = $(this).data('status');
            
            if (selectedStatus === 'all' || postStatus === selectedStatus) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});

// Delete post functionality
let deletePostId = null;

function deletePost(postId, postTitle) {
    deletePostId = postId;
    $('#delete-post-title').text(postTitle);
    $('#delete-modal').removeClass('hidden');
}

function closeDeleteModal() {
    $('#delete-modal').addClass('hidden');
    deletePostId = null;
}

// Handle delete confirmation
$('#confirm-delete').click(function() {
    if (!deletePostId) return;
    
    const $btn = $(this);
    const originalText = $btn.html();
    
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Siliniyor...');
    
    $.ajax({
        url: `/blog/delete/${deletePostId}`,
        method: 'POST',
        data: {
            csrf_token: window.csrfToken
        },
        success: function(response) {
            if (response && response.success !== false) {
                // Remove the post from list
                $(`.post-item[data-post-id="${deletePostId}"]`).fadeOut(300, function() {
                    $(this).remove();
                });
                
                // Show success message
                showNotification('Gönderi başarıyla silindi!', 'success');
                
                // Close modal
                closeDeleteModal();
                
                // Reload page to update stats
                setTimeout(() => window.location.reload(), 1500);
            } else {
                $btn.prop('disabled', false).html(originalText);
                showNotification('Silme işlemi başarısız oldu.', 'error');
            }
        },
        error: function() {
            $btn.prop('disabled', false).html(originalText);
            showNotification('Bir hata oluştu.', 'error');
        }
    });
});

// Close modal on outside click
$('#delete-modal').click(function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal on Escape key
$(document).keydown(function(e) {
    if (e.keyCode === 27) {
        closeDeleteModal();
    }
});

function showNotification(message, type) {
    const typeClasses = {
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
        info: 'bg-blue-50 border-blue-200 text-blue-800'
    };
    
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    
    const $notification = $(`
        <div class="fixed top-20 right-4 z-50 p-4 rounded-lg border animate-fade-in ${typeClasses[type]} max-w-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-${icons[type]} mr-3"></i>
                    <span>${message}</span>
                </div>
                <button onclick="$(this).closest('div').remove()" class="text-gray-500 hover:text-gray-700 ml-4">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `);
    
    $('body').append($notification);
    
    setTimeout(function() {
        $notification.fadeOut(500, function() {
            $(this).remove();
        });
    }, 5000);
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}
</style>