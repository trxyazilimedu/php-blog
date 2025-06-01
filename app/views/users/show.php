<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; background-color: #f4f4f4; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; }
        .nav { text-align: center; margin: 20px 0; }
        .nav a { margin: 0 10px; color: #007bff; text-decoration: none; }
        .nav a:hover { text-decoration: underline; }
        .user-info { background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .user-info h3 { margin-top: 0; color: #495057; }
        .info-row { margin-bottom: 10px; }
        .info-label { font-weight: bold; color: #6c757d; }
        .btn { padding: 8px 16px; margin: 2px; text-decoration: none; border-radius: 4px; display: inline-block; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-danger { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($title) ?></h1>
        
        <div class="nav">
            <a href="/">Ana Sayfa</a>
            <a href="/users">Kullanıcılar</a>
            <a href="/users/create">Yeni Kullanıcı</a>
        </div>
        
        <div class="user-info">
            <h3>Kullanıcı Bilgileri</h3>
            
            <div class="info-row">
                <span class="info-label">ID:</span>
                <?= htmlspecialchars($user['id']) ?>
            </div>
            
            <div class="info-row">
                <span class="info-label">Ad Soyad:</span>
                <?= htmlspecialchars($user['name']) ?>
            </div>
            
            <div class="info-row">
                <span class="info-label">E-posta:</span>
                <?= htmlspecialchars($user['email']) ?>
            </div>
            
            <div class="info-row">
                <span class="info-label">Durum:</span>
                <span style="color: <?= $user['status'] === 'active' ? 'green' : 'red' ?>">
                    <?= $user['status'] === 'active' ? 'Aktif' : 'Pasif' ?>
                </span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Kayıt Tarihi:</span>
                <?= date('d.m.Y H:i:s', strtotime($user['created_at'])) ?>
            </div>
            
            <?php if (isset($user['updated_at'])): ?>
            <div class="info-row">
                <span class="info-label">Güncellenme Tarihi:</span>
                <?= date('d.m.Y H:i:s', strtotime($user['updated_at'])) ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="/users" class="btn btn-primary">Geri Dön</a>
            <a href="/users/delete/<?= $user['id'] ?>" class="btn btn-danger" 
               onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')">Kullanıcıyı Sil</a>
        </div>
    </div>
</body>
</html>
