<h1><?= htmlspecialchars($page_title ?? 'Kullanıcı Düzenle') ?></h1>

<div style="max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <a href="/users" style="color: #667eea; text-decoration: none; font-weight: 500;">
            ← Kullanıcılar Listesine Dön
        </a>
    </div>

    <?php if (isset($errors) && !empty($errors)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 6px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
            <strong>Lütfen aşağıdaki hataları düzeltin:</strong>
            <ul style="margin: 0.5rem 0 0 1.5rem;">
                <?php foreach ($errors as $field => $fieldErrors): ?>
                    <?php foreach ($fieldErrors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="/users/edit/<?= $user['id'] ?>" style="background: #f8f9fa; padding: 2rem; border-radius: 10px; border: 1px solid #e9ecef;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        
        <div style="margin-bottom: 1.5rem;">
            <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">Ad Soyad *</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="<?= htmlspecialchars($old_data['name'] ?? $user['name']) ?>"
                required
                style="width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 6px; font-size: 1rem; box-sizing: border-box;"
            >
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">E-posta Adresi *</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="<?= htmlspecialchars($old_data['email'] ?? $user['email']) ?>"
                required
                style="width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 6px; font-size: 1rem; box-sizing: border-box;"
            >
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label for="role" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">Rol *</label>
            <select 
                id="role" 
                name="role" 
                required
                style="width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 6px; font-size: 1rem; box-sizing: border-box;"
            >
                <option value="user" <?= ($old_data['role'] ?? $user['role'] ?? 'user') === 'user' ? 'selected' : '' ?>>Kullanıcı</option>
                <option value="admin" <?= ($old_data['role'] ?? $user['role'] ?? 'user') === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">Yeni Şifre</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                placeholder="Şifreyi değiştirmek istemiyorsanız boş bırakın"
                style="width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 6px; font-size: 1rem; box-sizing: border-box;"
            >
            <small style="color: #666; font-size: 0.85rem;">En az 6 karakter olmalıdır</small>
        </div>
        
        <div style="display: flex; gap: 1rem; justify-content: space-between; flex-wrap: wrap;">
            <button 
                type="submit"
                style="background: #28a745; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; flex: 1; min-width: 150px;"
            >
                💾 Değişiklikleri Kaydet
            </button>
            <a href="/users/show/<?= $user['id'] ?>" 
               style="background: #6c757d; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600; text-align: center; flex: 1; min-width: 150px; display: flex; align-items: center; justify-content: center;">
                ❌ İptal
            </a>
        </div>
    </form>
</div>
