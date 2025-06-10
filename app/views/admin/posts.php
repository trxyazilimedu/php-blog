<style>
.admin-container {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

.admin-sidebar {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: fit-content;
}

.admin-sidebar h6 {
    margin-bottom: 1rem;
    color: #333;
    border-bottom: 2px solid #667eea;
    padding-bottom: 0.5rem;
}

.admin-nav {
    list-style: none;
    padding: 0;
}

.admin-nav li {
    margin-bottom: 0.5rem;
}

.admin-nav a {
    display: block;
    padding: 0.75rem 1rem;
    text-decoration: none;
    color: #555;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.admin-nav a:hover {
    background: #f0f0f0;
    color: #333;
}

.admin-nav a.active {
    background: #667eea;
    color: white;
}

.admin-main {
    background: white;
    border-radius: 10px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.posts-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #eee;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a6fd8;
}

.btn-success {
    background: #28a745;
    color: white;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.btn-success:hover {
    background: #218838;
}

.btn-warning {
    background: #ffc107;
    color: #212529;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.btn-warning:hover {
    background: #e0a800;
}

.btn-danger {
    background: #dc3545;
    color: white;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.btn-danger:hover {
    background: #c82333;
}

.posts-filters {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-size: 0.9rem;
    font-weight: 500;
    color: #333;
}

.filter-control {
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
}

.posts-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.posts-table th,
.posts-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.posts-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
    position: sticky;
    top: 0;
}

.posts-table tr:hover {
    background: #f8f9fa;
}

.post-title {
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.post-title a {
    color: #333;
    text-decoration: none;
    font-weight: 500;
}

.post-title a:hover {
    color: #667eea;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: bold;
}

.status-published {
    background: #d4edda;
    color: #155724;
}

.status-draft {
    background: #fff3cd;
    color: #856404;
}

.status-archived {
    background: #f8d7da;
    color: #721c24;
}

.author-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.author-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.8rem;
}

.post-stats {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    font-size: 0.8rem;
    color: #666;
}

.post-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.no-posts {
    text-align: center;
    padding: 3rem;
    color: #666;
    background: #f8f9fa;
    border-radius: 10px;
}

.no-posts h4 {
    margin-bottom: 1rem;
    color: #333;
}

.bulk-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin: 1rem 0;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.bulk-checkbox {
    margin-right: 0.5rem;
}

@media (max-width: 768px) {
    .admin-container {
        grid-template-columns: 1fr;
    }
    
    .posts-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .posts-filters {
        flex-direction: column;
    }
    
    .posts-table {
        font-size: 0.8rem;
    }
    
    .posts-table th,
    .posts-table td {
        padding: 0.5rem;
    }
    
    .post-title {
        max-width: 200px;
    }
    
    .post-actions {
        flex-direction: column;
    }
}
</style>

<div class="admin-container">
    <div class="admin-sidebar">
        <h6>Admin Panel</h6>
        <ul class="admin-nav">
            <li><a href="/admin">Dashboard</a></li>
            <li><a href="/admin/users">Kullanıcılar</a></li>
            <li><a href="/admin/posts" class="active">Blog Yazıları</a></li>
            <li><a href="/admin/categories">Kategoriler</a></li>
            <li><a href="/admin/content">İçerik Yönetimi</a></li>
            <li><a href="/admin/settings">Ayarlar</a></li>
        </ul>
    </div>
    
    <div class="admin-main">
        <div class="posts-header">
            <h2>Blog Yazıları Yönetimi</h2>
            <a href="/blog/create" class="btn btn-primary">+ Yeni Yazı</a>
        </div>
        
        <div class="posts-filters">
            <div class="filter-group">
                <label class="filter-label">Durum</label>
                <select class="filter-control" id="status-filter">
                    <option value="">Tüm Durumlar</option>
                    <option value="published">Yayınlanan</option>
                    <option value="draft">Taslak</option>
                    <option value="archived">Arşivlenen</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Yazar</label>
                <select class="filter-control" id="author-filter">
                    <option value="">Tüm Yazarlar</option>
                    <!-- Yazarlar dinamik olarak yüklenecek -->
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Kategori</label>
                <select class="filter-control" id="category-filter">
                    <option value="">Tüm Kategoriler</option>
                    <!-- Kategoriler dinamik olarak yüklenecek -->
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Arama</label>
                <input type="text" class="filter-control" id="search-filter" placeholder="Başlık veya içerik ara...">
            </div>
        </div>
        
        <?php if (!empty($posts)): ?>
            <div class="bulk-actions">
                <input type="checkbox" class="bulk-checkbox" id="select-all"> 
                <label for="select-all">Tümünü Seç</label>
                <select id="bulk-action" class="filter-control" style="margin-left: auto;">
                    <option value="">Toplu İşlem Seçin</option>
                    <option value="publish">Yayınla</option>
                    <option value="draft">Taslağa Al</option>
                    <option value="archive">Arşivle</option>
                    <option value="delete">Sil</option>
                </select>
                <button class="btn btn-warning" onclick="executeBulkAction()">Uygula</button>
            </div>
            
            <table class="posts-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="header-checkbox"></th>
                        <th>Başlık</th>
                        <th>Yazar</th>
                        <th>Kategori</th>
                        <th>Durum</th>
                        <th>İstatistikler</th>
                        <th>Tarih</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr data-post-id="<?= $post['id'] ?>">
                            <td>
                                <input type="checkbox" class="post-checkbox" value="<?= $post['id'] ?>">
                            </td>
                            
                            <td class="post-title">
                                <a href="/blog/post/<?= htmlspecialchars($post['slug']) ?>" target="_blank">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                                <?php if (!empty($post['excerpt'])): ?>
                                    <div style="font-size: 0.8rem; color: #666; margin-top: 0.25rem;">
                                        <?= htmlspecialchars(substr($post['excerpt'], 0, 100)) ?>...
                                    </div>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <div class="author-info">
                                    <div class="author-avatar">
                                        <?= strtoupper(substr($post['author_name'] ?? 'U', 0, 1)) ?>
                                    </div>
                                    <span><?= htmlspecialchars($post['author_name'] ?? 'Bilinmeyen') ?></span>
                                </div>
                            </td>
                            
                            <td>
                                <?php if (!empty($post['category_name'])): ?>
                                    <span style="background: #667eea; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">
                                        <?= htmlspecialchars($post['category_name']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: #666; font-style: italic;">Kategorisiz</span>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <span class="status-badge status-<?= $post['status'] ?>">
                                    <?= ucfirst($post['status']) ?>
                                </span>
                            </td>
                            
                            <td>
                                <div class="post-stats">
                                    <span>👁️ <?= number_format($post['views'] ?? 0) ?> görüntülenme</span>
                                    <span>💬 <?= $post['comment_count'] ?? 0 ?> yorum</span>
                                    <span>❤️ <?= $post['likes'] ?? 0 ?> beğeni</span>
                                </div>
                            </td>
                            
                            <td>
                                <div style="font-size: 0.8rem;">
                                    <div><strong>Oluşturulma:</strong></div>
                                    <div><?= date('d.m.Y H:i', strtotime($post['created_at'])) ?></div>
                                    <?php if ($post['updated_at'] !== $post['created_at']): ?>
                                        <div style="margin-top: 0.25rem;"><strong>Güncelleme:</strong></div>
                                        <div><?= date('d.m.Y H:i', strtotime($post['updated_at'])) ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <td>
                                <div class="post-actions">
                                    <a href="/blog/post/<?= htmlspecialchars($post['slug']) ?>" 
                                       class="btn btn-success" target="_blank" title="Görüntüle">
                                        👁️
                                    </a>
                                    <a href="/blog/edit/<?= $post['id'] ?>" 
                                       class="btn btn-warning" title="Düzenle">
                                        ✏️
                                    </a>
                                    <form style="display: inline-block;" method="POST" action="/blog/delete/<?= $post['id'] ?>">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('Bu yazıyı silmek istediğinizden emin misiniz?')"
                                                title="Sil">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-posts">
                <h4>Henüz blog yazısı yok</h4>
                <p>İlk blog yazınızı oluşturmak için yukarıdaki "Yeni Yazı" butonunu kullanın.</p>
                <a href="/blog/create" class="btn btn-primary" style="margin-top: 1rem;">İlk Yazımı Oluştur</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Filtering functionality
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('status-filter');
    const authorFilter = document.getElementById('author-filter');
    const categoryFilter = document.getElementById('category-filter');
    const searchFilter = document.getElementById('search-filter');
    const selectAll = document.getElementById('select-all');
    const headerCheckbox = document.getElementById('header-checkbox');
    
    // Apply filters
    function applyFilters() {
        const rows = document.querySelectorAll('.posts-table tbody tr');
        const statusValue = statusFilter.value.toLowerCase();
        const authorValue = authorFilter.value.toLowerCase();
        const categoryValue = categoryFilter.value.toLowerCase();
        const searchValue = searchFilter.value.toLowerCase();
        
        rows.forEach(row => {
            const status = row.querySelector('.status-badge').textContent.toLowerCase().trim();
            const author = row.querySelector('.author-info span').textContent.toLowerCase();
            const category = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const title = row.querySelector('.post-title a').textContent.toLowerCase();
            
            const statusMatch = !statusValue || status.includes(statusValue);
            const authorMatch = !authorValue || author.includes(authorValue);
            const categoryMatch = !categoryValue || category.includes(categoryValue);
            const searchMatch = !searchValue || title.includes(searchValue);
            
            if (statusMatch && authorMatch && categoryMatch && searchMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Attach filter events
    [statusFilter, authorFilter, categoryFilter, searchFilter].forEach(filter => {
        filter.addEventListener('change', applyFilters);
        filter.addEventListener('input', applyFilters);
    });
    
    // Select all functionality
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.post-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    if (headerCheckbox) {
        headerCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.post-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});

// Bulk actions
function executeBulkAction() {
    const selectedPosts = Array.from(document.querySelectorAll('.post-checkbox:checked')).map(cb => cb.value);
    const action = document.getElementById('bulk-action').value;
    
    if (selectedPosts.length === 0) {
        alert('Lütfen en az bir yazı seçin.');
        return;
    }
    
    if (!action) {
        alert('Lütfen bir işlem seçin.');
        return;
    }
    
    const actionNames = {
        'publish': 'yayınla',
        'draft': 'taslağa al',
        'archive': 'arşivle',
        'delete': 'sil'
    };
    
    if (confirm(`Seçili ${selectedPosts.length} yazıyı ${actionNames[action]}mak istediğinizden emin misiniz?`)) {
        // AJAX request to perform bulk action
        fetch('/admin/posts/bulk-action', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                posts: selectedPosts,
                action: action,
                csrf_token: window.csrfToken
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Hata: ' + data.message);
            }
        })
        .catch(error => {
            alert('Bir hata oluştu: ' + error.message);
        });
    }
}
</script>