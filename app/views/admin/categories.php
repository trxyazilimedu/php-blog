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

.category-form {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr 120px auto;
    gap: 1rem;
    align-items: end;
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

.btn-danger {
    background: #dc3545;
    color: white;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.btn-danger:hover {
    background: #c82333;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.category-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    border-left: 4px solid;
    transition: transform 0.3s ease;
}

.category-card:hover {
    transform: translateY(-2px);
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.category-name {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.category-stats {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: #667eea;
    display: block;
}

.stat-label {
    font-size: 0.8rem;
    color: #666;
}

.category-description {
    color: #666;
    margin-bottom: 1rem;
}

.category-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.category-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
}

.category-link:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .admin-container {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="admin-container">
    <div class="admin-sidebar">
        <h6>Admin Panel</h6>
        <ul class="admin-nav">
            <li><a href="/admin">Dashboard</a></li>
            <li><a href="/admin/users">Kullanıcılar</a></li>
            <li><a href="/admin/posts">Blog Yazıları</a></li>
            <li><a href="/admin/categories" class="active">Kategoriler</a></li>
            <li><a href="/admin/content">İçerik Yönetimi</a></li>
            <li><a href="/admin/settings">Ayarlar</a></li>
        </ul>
    </div>
    
    <div class="admin-main">
        <h2>Kategori Yönetimi</h2>
        
        <div class="category-form">
            <h3>Yeni Kategori Ekle</h3>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">Kategori Adı</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Açıklama</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="İsteğe bağlı">
                    </div>
                    
                    <div class="form-group">
                        <label for="color" class="form-label">Renk</label>
                        <input type="color" class="form-control" id="color" name="color" value="#667eea">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Ekle</button>
                    </div>
                </div>
                
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            </form>
        </div>
        
        <h3>Mevcut Kategoriler</h3>
        
        <?php if (!empty($categories)): ?>
            <div class="categories-grid">
                <?php foreach ($categories as $category): ?>
                    <div class="category-card" style="border-left-color: <?= htmlspecialchars($category['color'] ?? '#667eea') ?>">
                        <div class="category-header">
                            <h4 class="category-name"><?= htmlspecialchars($category['name']) ?></h4>
                            <div class="category-actions">
                                <a href="/blog/category/<?= htmlspecialchars($category['slug']) ?>" class="category-link" target="_blank">
                                    Görüntüle
                                </a>
                                <form style="display: inline-block;" method="POST" action="/admin/categories/delete/<?= $category['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Bu kategoriyi silmek istediğinizden emin misiniz?')"
                                            title="Kategoriyi Sil">
                                        Sil
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="category-stats">
                            <div class="stat-item">
                                <span class="stat-number"><?= $category['post_count'] ?? 0 ?></span>
                                <span class="stat-label">Yazı</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?= $category['views'] ?? 0 ?></span>
                                <span class="stat-label">Görüntülenme</span>
                            </div>
                        </div>
                        
                        <?php if (!empty($category['description'])): ?>
                            <div class="category-description">
                                <?= htmlspecialchars($category['description']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div style="font-size: 0.8rem; color: #666; margin-top: 1rem;">
                            Oluşturulma: <?= date('d.m.Y', strtotime($category['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 3rem; color: #666; background: #f8f9fa; border-radius: 10px;">
                <h4>Henüz kategori eklenmemiş</h4>
                <p>Yukarıdaki formu kullanarak ilk kategoriyi ekleyebilirsiniz.</p>
            </div>
        <?php endif; ?>
    </div>
</div>