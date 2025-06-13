<!-- Profile Header -->
<div class="bg-gradient-to-r from-purple-600 via-blue-500 to-purple-700 text-white rounded-2xl p-8 mb-8">
    <div class="flex flex-col md:flex-row items-center justify-between">
        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
            <!-- Avatar -->
            <?php if (!empty($user['avatar'])): ?>
                <img src="<?= htmlspecialchars($user['avatar']) ?>" 
                     alt="Profil Fotoğrafı" 
                     class="w-24 h-24 rounded-full object-cover backdrop-blur-sm border-2 border-white/30">
            <?php else: ?>
                <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center text-3xl font-bold backdrop-blur-sm border-2 border-white/30">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                </div>
            <?php endif; ?>
            
            <!-- User Info -->
            <div class="text-center md:text-left">
                <h1 class="text-3xl font-bold mb-2">
                    <?= htmlspecialchars($user['name']) ?>
                </h1>
                <p class="text-white/80 mb-2">
                    <?= htmlspecialchars($user['email']) ?>
                </p>
                <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium">
                        <i class="fas fa-user-tag mr-1"></i>
                        <?php
                        $roleNames = [
                            'admin' => 'Yönetici',
                            'writer' => 'Yazar',
                            'user' => 'Kullanıcı'
                        ];
                        echo $roleNames[$user['role'] ?? 'user'] ?? ucfirst($user['role'] ?? 'user');
                        ?>
                    </span>
                    <span class="px-3 py-1 <?= ($user['status'] ?? 'active') === 'active' ? 'bg-green-500/20 text-green-100' : 'bg-red-500/20 text-red-100' ?> rounded-full text-sm font-medium">
                        <i class="fas fa-<?= ($user['status'] ?? 'active') === 'active' ? 'check-circle' : 'times-circle' ?> mr-1"></i>
                        <?= ($user['status'] ?? 'active') === 'active' ? 'Aktif' : 'Pasif' ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Member Since -->
        <div class="hidden md:block text-center">
            <div class="text-white/60 text-sm">Üye Olma Tarihi</div>
            <div class="text-xl font-semibold">
                <?= date('d.m.Y', strtotime($user['created_at'])) ?>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Profile Information -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Account Details -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-user-circle mr-3 text-blue-500"></i>
                    Hesap Bilgileri
                </h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User ID -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500">Kullanıcı ID</label>
                        <div class="flex items-center space-x-2">
                            <span class="text-lg font-mono text-gray-900">#<?= $user['id'] ?></span>
                            <button onclick="copyToClipboard('<?= $user['id'] ?>')" 
                                    class="text-gray-400 hover:text-blue-500 transition-colors" 
                                    title="Kopyala">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Full Name -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500">Ad Soyad</label>
                        <div class="text-lg text-gray-900"><?= htmlspecialchars($user['name']) ?></div>
                    </div>
                    
                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500">E-posta Adresi</label>
                        <div class="flex items-center space-x-2">
                            <span class="text-lg text-gray-900"><?= htmlspecialchars($user['email']) ?></span>
                            <button onclick="copyToClipboard('<?= htmlspecialchars($user['email']) ?>')" 
                                    class="text-gray-400 hover:text-blue-500 transition-colors" 
                                    title="Kopyala">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Role -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500">Rol & Yetkiler</label>
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 <?php
                                $roleColors = [
                                    'admin' => 'bg-red-100 text-red-800',
                                    'writer' => 'bg-blue-100 text-blue-800',
                                    'user' => 'bg-green-100 text-green-800'
                                ];
                                echo $roleColors[$user['role'] ?? 'user'] ?? 'bg-gray-100 text-gray-800';
                            ?> rounded-lg text-sm font-medium">
                                <i class="fas fa-<?php
                                    $roleIcons = [
                                        'admin' => 'crown',
                                        'writer' => 'pen',
                                        'user' => 'user'
                                    ];
                                    echo $roleIcons[$user['role'] ?? 'user'] ?? 'user';
                                ?> mr-1"></i>
                                <?= $roleNames[$user['role'] ?? 'user'] ?? ucfirst($user['role'] ?? 'user') ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Registration Date -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500">Kayıt Tarihi</label>
                        <div class="text-lg text-gray-900">
                            <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                            <?= date('d F Y, H:i', strtotime($user['created_at'])) ?>
                        </div>
                    </div>
                    
                    <!-- Last Login -->
                    <?php if (isset($user['last_login']) && $user['last_login']): ?>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-500">Son Giriş</label>
                            <div class="text-lg text-gray-900">
                                <i class="fas fa-clock mr-2 text-gray-400"></i>
                                <?= date('d F Y, H:i', strtotime($user['last_login'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Account Statistics -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-green-50 p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-chart-line mr-3 text-green-500"></i>
                    Hesap İstatistikleri
                </h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Account Age -->
                    <div class="text-center p-4 bg-blue-50 rounded-xl">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-birthday-cake text-white"></i>
                        </div>
                        <div class="text-2xl font-bold text-blue-600 mb-1">
                            <?php 
                                $accountAge = time() - strtotime($user['created_at']);
                                $days = floor($accountAge / (60 * 60 * 24));
                                echo $days;
                            ?> gün
                        </div>
                        <div class="text-sm text-gray-600">Hesap Yaşı</div>
                    </div>
                    
                    <!-- Status -->
                    <div class="text-center p-4 <?= ($user['status'] ?? 'active') === 'active' ? 'bg-green-50' : 'bg-red-50' ?> rounded-xl">
                        <div class="w-12 h-12 <?= ($user['status'] ?? 'active') === 'active' ? 'bg-green-500' : 'bg-red-500' ?> rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-<?= ($user['status'] ?? 'active') === 'active' ? 'check' : 'times' ?> text-white"></i>
                        </div>
                        <div class="text-2xl font-bold <?= ($user['status'] ?? 'active') === 'active' ? 'text-green-600' : 'text-red-600' ?> mb-1">
                            <?= ($user['status'] ?? 'active') === 'active' ? 'Aktif' : 'Pasif' ?>
                        </div>
                        <div class="text-sm text-gray-600">Hesap Durumu</div>
                    </div>
                    
                    <!-- Role Level -->
                    <div class="text-center p-4 bg-purple-50 rounded-xl">
                        <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-<?= $roleIcons[$user['role'] ?? 'user'] ?? 'user' ?> text-white"></i>
                        </div>
                        <div class="text-2xl font-bold text-purple-600 mb-1">
                            <?php
                                $roleLevels = ['user' => 1, 'writer' => 2, 'admin' => 3];
                                echo 'Seviye ' . ($roleLevels[$user['role'] ?? 'user'] ?? 1);
                            ?>
                        </div>
                        <div class="text-sm text-gray-600">Yetki Seviyesi</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Actions -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-orange-50 p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-bolt mr-2 text-orange-500"></i>
                    Hızlı İşlemler
                </h3>
            </div>
            
            <div class="p-6 space-y-3">
                <a href="/profile/edit" 
                   class="block w-full bg-yellow-500 hover:bg-yellow-600 text-white text-center py-3 px-4 rounded-lg font-medium transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Profili Düzenle
                </a>
                
                <?php if ($user['role'] === 'admin' || $user['role'] === 'writer'): ?>
                    <a href="/blog/my-posts" 
                       class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-3 px-4 rounded-lg font-medium transition-colors">
                        <i class="fas fa-file-alt mr-2"></i>
                        Yazılarım
                    </a>
                    
                    <a href="/blog/create" 
                       class="block w-full bg-green-500 hover:bg-green-600 text-white text-center py-3 px-4 rounded-lg font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Yeni Yazı Yaz
                    </a>
                <?php endif; ?>
                
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="/admin" 
                       class="block w-full bg-red-500 hover:bg-red-600 text-white text-center py-3 px-4 rounded-lg font-medium transition-colors">
                        <i class="fas fa-cog mr-2"></i>
                        Admin Panel
                    </a>
                <?php endif; ?>
                
                <a href="/" 
                   class="block w-full bg-gray-500 hover:bg-gray-600 text-white text-center py-3 px-4 rounded-lg font-medium transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Ana Sayfa
                </a>
            </div>
        </div>
        
        <!-- Role Permissions -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-indigo-50 p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-shield-alt mr-2 text-indigo-500"></i>
                    Yetki ve İzinler
                </h3>
            </div>
            
            <div class="p-6">
                <div class="space-y-3">
                    <?php
                    $permissions = [
                        'user' => [
                            'Profil görüntüleme ve düzenleme',
                            'Blog yazılarını okuma',
                            'Yorum yapma'
                        ],
                        'writer' => [
                            'Kullanıcı izinlerine ek olarak:',
                            'Blog yazısı oluşturma',
                            'Kendi yazılarını düzenleme',
                            'Yazı kategorilerini görüntüleme'
                        ],
                        'admin' => [
                            'Tüm sistem yetkilerine sahip:',
                            'Kullanıcı yönetimi',
                            'Tüm blog yazılarını yönetme',
                            'Site ayarlarını değiştirme',
                            'Kategori yönetimi'
                        ]
                    ];
                    
                    $userPermissions = $permissions[$user['role'] ?? 'user'] ?? $permissions['user'];
                    ?>
                    
                    <?php foreach ($userPermissions as $permission): ?>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                            <span class="text-sm text-gray-700"><?= $permission ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Security Info -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-red-50 p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-lock mr-2 text-red-500"></i>
                    Güvenlik
                </h3>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Şifre Güvenliği</span>
                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Güçlü</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">İki Faktörlü Doğrulama</span>
                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">Kapalı</span>
                </div>
                
                <div class="pt-4 border-t">
                    <button class="w-full bg-red-100 hover:bg-red-200 text-red-700 py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-key mr-2"></i>
                        Şifre Değiştir
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(() => {
            showNotification('Panoya kopyalandı!', 'success');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('Panoya kopyalandı!', 'success');
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => document.body.removeChild(notification), 300);
    }, 2000);
}
</script>