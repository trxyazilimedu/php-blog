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

.content-sections {
    display: grid;
    gap: 2rem;
}

.content-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    border-left: 4px solid #667eea;
}

.section-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #ddd;
}

.content-items {
    display: grid;
    gap: 1rem;
}

.content-item {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    border: 1px solid #e0e0e0;
}

.content-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.content-key {
    font-weight: 600;
    color: #333;
    font-family: monospace;
    background: #e9ecef;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.9rem;
}

.content-type {
    background: #667eea;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
    font-size: 0.9rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
}

.form-text {
    font-size: 0.8rem;
    color: #666;
    margin-top: 0.25rem;
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
}

.btn-success:hover {
    background: #218838;
}

.btn-warning {
    background: #ffc107;
    color: #212529;
}

.btn-warning:hover {
    background: #e0a800;
}

.save-section {
    text-align: right;
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

.content-preview {
    background: white;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 1rem;
    margin-top: 0.5rem;
    min-height: 60px;
}

.content-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    font-size: 0.8rem;
    color: #666;
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid #eee;
}

.no-content {
    text-align: center;
    padding: 3rem;
    color: #666;
    background: #f8f9fa;
    border-radius: 10px;
}

.edit-mode-toggle {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    z-index: 1000;
    cursor: pointer;
    transition: all 0.3s ease;
}

.edit-mode-toggle:hover {
    background: #5a6fd8;
    transform: translateY(-1px);
}

.edit-mode-active {
    background: #28a745 !important;
}

@media (max-width: 768px) {
    .admin-container {
        grid-template-columns: 1fr;
    }
    
    .content-meta {
        grid-template-columns: 1fr;
    }
    
    .edit-mode-toggle {
        position: static;
        margin-bottom: 1rem;
        border-radius: 6px;
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
            <li><a href="/admin/categories">Kategoriler</a></li>
            <li><a href="/admin/content" class="active">İçerik Yönetimi</a></li>
            <li><a href="/admin/settings">Ayarlar</a></li>
        </ul>
    </div>
    
    <div class="admin-main">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2>İçerik Yönetimi</h2>
            <button class="edit-mode-toggle" onclick="toggleEditMode()">
                🔧 Canlı Düzenleme Modu
            </button>
        </div>
        
        <form method="POST">
            <div class="content-sections">
                <?php if (!empty($content)): ?>
                    <?php 
                    $groupedContent = [];
                    foreach ($content as $item) {
                        $page = $item['page'] ?? 'general';
                        $section = $item['section'] ?? 'default';
                        $groupedContent[$page][$section][] = $item;
                    }
                    ?>
                    
                    <?php foreach ($groupedContent as $page => $sections): ?>
                        <div class="content-section">
                            <h3 class="section-title">
                                📄 <?= ucfirst(htmlspecialchars($page)) ?> Sayfası
                            </h3>
                            
                            <?php foreach ($sections as $section => $items): ?>
                                <?php if ($section !== 'default'): ?>
                                    <h4 style="margin: 1rem 0 0.5rem 0; color: #667eea; font-size: 1rem;">
                                        📂 <?= ucfirst(htmlspecialchars($section)) ?>
                                    </h4>
                                <?php endif; ?>
                                
                                <div class="content-items">
                                    <?php foreach ($items as $item): ?>
                                        <div class="content-item">
                                            <div class="content-item-header">
                                                <span class="content-key"><?= htmlspecialchars($item['content_key']) ?></span>
                                                <span class="content-type"><?= htmlspecialchars($item['content_type'] ?? 'html') ?></span>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="form-label" for="content_<?= htmlspecialchars($item['content_key']) ?>">
                                                    İçerik Değeri
                                                </label>
                                                
                                                <?php if ($item['content_type'] === 'textarea' || strlen($item['content_value']) > 100): ?>
                                                    <textarea 
                                                        class="form-control" 
                                                        id="content_<?= htmlspecialchars($item['content_key']) ?>"
                                                        name="content[<?= htmlspecialchars($item['content_key']) ?>]" 
                                                        rows="4"
                                                        placeholder="İçerik değerini buraya girin..."
                                                    ><?= htmlspecialchars($item['content_value']) ?></textarea>
                                                <?php else: ?>
                                                    <input 
                                                        type="text" 
                                                        class="form-control" 
                                                        id="content_<?= htmlspecialchars($item['content_key']) ?>"
                                                        name="content[<?= htmlspecialchars($item['content_key']) ?>]" 
                                                        value="<?= htmlspecialchars($item['content_value']) ?>"
                                                        placeholder="İçerik değerini buraya girin..."
                                                    >
                                                <?php endif; ?>
                                                
                                                <div class="form-text">
                                                    Anahtar: <code><?= htmlspecialchars($item['content_key']) ?></code>
                                                </div>
                                            </div>
                                            
                                            <?php if (!empty($item['content_value'])): ?>
                                                <div>
                                                    <label class="form-label">Önizleme:</label>
                                                    <div class="content-preview">
                                                        <?php if ($item['content_type'] === 'html'): ?>
                                                            <?= $item['content_value'] ?>
                                                        <?php else: ?>
                                                            <?= htmlspecialchars($item['content_value']) ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="content-meta">
                                                <div>
                                                    <strong>Son Güncelleme:</strong><br>
                                                    <?= date('d.m.Y H:i', strtotime($item['updated_at'] ?? $item['created_at'])) ?>
                                                </div>
                                                <div>
                                                    <strong>Güncelleyen:</strong><br>
                                                    <?= htmlspecialchars($item['updated_by'] ?? 'Sistem') ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="save-section">
                        <button type="button" class="btn btn-warning" onclick="previewChanges()">
                            👁️ Değişiklikleri Önizle
                        </button>
                        <button type="submit" class="btn btn-primary">
                            💾 Tüm Değişiklikleri Kaydet
                        </button>
                    </div>
                    
                <?php else: ?>
                    <div class="no-content">
                        <h4>Henüz dinamik içerik yok</h4>
                        <p>Site kullanıldıkça dinamik içerikler burada görünecek.</p>
                        <a href="/?edit=1" class="btn btn-success" style="margin-top: 1rem;" target="_blank">
                            🔧 Siteyi Düzenleme Modunda Aç
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        </form>
    </div>
</div>

<script>
// Edit mode toggle
function toggleEditMode() {
    const button = document.querySelector('.edit-mode-toggle');
    const isActive = button.classList.contains('edit-mode-active');
    
    if (isActive) {
        // Disable edit mode
        button.classList.remove('edit-mode-active');
        button.innerHTML = '🔧 Canlı Düzenleme Modu';
        
        // Remove edit parameter from URL if present
        if (window.location.search.includes('edit=1')) {
            const newUrl = window.location.href.replace(/[?&]edit=1/, '');
            window.history.replaceState({}, '', newUrl);
        }
    } else {
        // Enable edit mode - open site in new tab with edit parameter
        button.classList.add('edit-mode-active');
        button.innerHTML = '✅ Düzenleme Modu Aktif';
        
        window.open('/?edit=1', '_blank');
    }
}

// Preview changes
function previewChanges() {
    // Open the site in a new tab to see current content
    window.open('/', '_blank');
}

// Auto-save functionality
let saveTimeout;
function autoSave() {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
        // Get all form data
        const formData = new FormData(document.querySelector('form'));
        
        // Show saving indicator
        const saveButton = document.querySelector('button[type="submit"]');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '💾 Kaydediliyor...';
        saveButton.disabled = true;
        
        // Send AJAX request
        fetch('/admin/content', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                saveButton.innerHTML = '✅ Kaydedildi';
                setTimeout(() => {
                    saveButton.innerHTML = originalText;
                    saveButton.disabled = false;
                }, 2000);
            } else {
                saveButton.innerHTML = '❌ Hata';
                setTimeout(() => {
                    saveButton.innerHTML = originalText;
                    saveButton.disabled = false;
                }, 2000);
            }
        })
        .catch(error => {
            saveButton.innerHTML = '❌ Hata';
            setTimeout(() => {
                saveButton.innerHTML = originalText;
                saveButton.disabled = false;
            }, 2000);
        });
    }, 1000); // Save after 1 second of inactivity
}

// Attach auto-save to all content inputs
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[name^="content["], textarea[name^="content["]');
    inputs.forEach(input => {
        input.addEventListener('input', autoSave);
    });
});

// Form submission handling
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = document.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    submitButton.innerHTML = '💾 Kaydediliyor...';
    submitButton.disabled = true;
    
    fetch('/admin/content', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            submitButton.innerHTML = '✅ Başarıyla Kaydedildi!';
            setTimeout(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 3000);
        } else {
            alert('Hata: ' + data.message);
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }
    })
    .catch(error => {
        alert('Bir hata oluştu: ' + error.message);
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
});
</script>