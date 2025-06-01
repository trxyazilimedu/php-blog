<h1><?= htmlspecialchars($page_title ?? 'Kullanıcılar') ?></h1>

<div style="display: flex; justify-content: between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <p style="color: #666; margin: 0;">Sistemdeki tüm kullanıcıları görüntüle ve yönet</p>
    </div>
    <div>
        <a href="/users/create" style="background: #28a745; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 500;">
            ➕ Yeni Kullanıcı
        </a>
    </div>
</div>

<?php if (empty($users)): ?>
    <div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 10px; border: 1px solid #e9ecef;">
        <h3 style="color: #666; margin-bottom: 1rem;">👥 Henüz kullanıcı bulunmuyor</h3>
        <p style="color: #666; margin-bottom: 1.5rem;">İlk kullanıcıyı oluşturmak için aşağıdaki butona tıklayın.</p>
        <a href="/users/create" style="background: #667eea; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
            Yeni Kullanıcı Oluştur
        </a>
    </div>
<?php else: ?>
    <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #e9ecef; font-weight: 600;">ID</th>
                    <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #e9ecef; font-weight: 600;">Ad Soyad</th>
                    <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #e9ecef; font-weight: 600;">E-posta</th>
                    <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #e9ecef; font-weight: 600;">Rol</th>
                    <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #e9ecef; font-weight: 600;">Durum</th>
                    <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #e9ecef; font-weight: 600;">Kayıt Tarihi</th>
                    <th style="padding: 1rem; text-align: center; border-bottom: 2px solid #e9ecef; font-weight: 600;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s;" 
                        onmouseover="this.style.backgroundColor='#f8f9fa'" 
                        onmouseout="this.style.backgroundColor='transparent'">
                        <td style="padding: 1rem; font-weight: 500; color: #667eea;">#<?= $user['id'] ?></td>
                        <td style="padding: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #667eea; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.8rem;">
                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                </div>
                                <span style="font-weight: 500;"><?= htmlspecialchars($user['name']) ?></span>
                            </div>
                        </td>
                        <td style="padding: 1rem; color: #666;"><?= htmlspecialchars($user['email']) ?></td>
                        <td style="padding: 1rem;">
                            <span style="padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem; font-weight: 500; background: <?= ($user['role'] ?? 'user') === 'admin' ? '#dc3545' : '#28a745' ?>; color: white;">
                                <?= ucfirst($user['role'] ?? 'user') ?>
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <span style="padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem; font-weight: 500; background: <?= ($user['status'] ?? 'active') === 'active' ? '#28a745' : '#6c757d' ?>; color: white;">
                                <?= ($user['status'] ?? 'active') === 'active' ? 'Aktif' : 'Pasif' ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; color: #666; font-size: 0.9rem;">
                            <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <div style="display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap;">
                                <a href="/users/show/<?= $user['id'] ?>" 
                                   style="background: #17a2b8; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; text-decoration: none; font-size: 0.8rem; font-weight: 500;">
                                    👁️ Görüntüle
                                </a>
                                <a href="/users/edit/<?= $user['id'] ?>" 
                                   style="background: #ffc107; color: #212529; padding: 0.25rem 0.5rem; border-radius: 4px; text-decoration: none; font-size: 0.8rem; font-weight: 500;">
                                    ✏️ Düzenle
                                </a>
                                <form method="POST" action="/users/delete/<?= $user['id'] ?>" style="display: inline-block;">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                    <button type="submit" 
                                            onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')"
                                            style="background: #dc3545; color: white; padding: 0.25rem 0.5rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.8rem; font-weight: 500;">
                                        🗑️ Sil
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 2rem; text-align: center; color: #666;">
        <p style="margin: 0;">Toplam <?= count($users) ?> kullanıcı gösteriliyor</p>
    </div>
<?php endif; ?>
