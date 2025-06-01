<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; background-color: #f4f4f4; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; }
        .nav { text-align: center; margin: 20px 0; }
        .nav a { margin: 0 10px; color: #007bff; text-decoration: none; }
        .nav a:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; }
        .btn { padding: 8px 16px; margin: 2px; text-decoration: none; border-radius: 4px; display: inline-block; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-success { background-color: #28a745; color: white; }
        .alert { padding: 15px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
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
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Kullanıcı başarıyla oluşturuldu!</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Kullanıcı başarıyla silindi!</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">İşlem sırasında bir hata oluştu!</div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ad Soyad</th>
                    <th>E-posta</th>
                    <th>Durum</th>
                    <th>Kayıt Tarihi</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Henüz kullanıcı bulunmuyor.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span style="color: <?= $user['status'] === 'active' ? 'green' : 'red' ?>">
                                    <?= $user['status'] === 'active' ? 'Aktif' : 'Pasif' ?>
                                </span>
                            </td>
                            <td><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></td>
                            <td>
                                <a href="/users/show/<?= $user['id'] ?>" class="btn btn-primary">Görüntüle</a>
                                <a href="/users/delete/<?= $user['id'] ?>" class="btn btn-danger" 
                                   onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')">Sil</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
