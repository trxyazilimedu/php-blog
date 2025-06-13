<?php
// Aktif sayfayı belirlemek için mevcut URI'yi kontrol et
$currentPath = $_SERVER['REQUEST_URI'] ?? '';

// Admin menü öğeleri
$adminMenuItems = [
    [
        'url' => '/admin',
        'title' => 'Dashboard',
        'icon' => 'fas fa-tachometer-alt',
        'active_pattern' => '/^\/admin\/?$/'
    ],
    [
        'url' => '/admin/users',
        'title' => 'Kullanıcılar',
        'icon' => 'fas fa-users',
        'active_pattern' => '/^\/admin\/users/'
    ],
    [
        'url' => '/admin/posts',
        'title' => 'Blog Yazıları',
        'icon' => 'fas fa-edit',
        'active_pattern' => '/^\/admin\/posts/'
    ],
    [
        'url' => '/admin/categories',
        'title' => 'Kategoriler',
        'icon' => 'fas fa-tags',
        'active_pattern' => '/^\/admin\/categories/'
    ],
    [
        'url' => '/admin/settings',
        'title' => 'Ayarlar',
        'icon' => 'fas fa-cog',
        'active_pattern' => '/^\/admin\/settings/'
    ],
    [
        'url' => '/admin/cache-management',
        'title' => 'Cache Yönetimi',
        'icon' => 'fas fa-tachometer-alt',
        'active_pattern' => '/^\/admin\/cache-management/'
    ]
];

function isActiveMenuItem($pattern, $currentPath) {
    return preg_match($pattern, $currentPath);
}
?>

<div class="lg:col-span-1">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-24">
        <!-- Navigation Header -->
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white p-6">
            <h3 class="text-lg font-semibold">
                <i class="fas fa-cog mr-2"></i>Yönetim Paneli
            </h3>
            <p class="text-gray-300 text-sm mt-1"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Admin') ?></p>
        </div>
        
        <!-- Navigation Links -->
        <nav class="p-2">
            <?php foreach ($adminMenuItems as $item): ?>
                <?php $isActive = isActiveMenuItem($item['active_pattern'], $currentPath); ?>
                <a href="<?= $item['url'] ?>" 
                   class="flex items-center px-4 py-3 rounded-lg mb-1 transition-colors
                   <?= $isActive 
                       ? 'text-gray-700 bg-primary-50 border-r-4 border-primary-500 font-medium' 
                       : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' ?>">
                    <i class="<?= $item['icon'] ?> w-5 h-5 mr-3 <?= $isActive ? 'text-primary-500' : '' ?>"></i>
                    <?= $item['title'] ?>
                </a>
            <?php endforeach; ?>
            
            <div class="border-t border-gray-200 my-4"></div>
            
            <!-- Quick Actions -->
            <div class="px-2 mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-2">Hızlı İşlemler</p>
                <a href="/blog/create" class="flex items-center px-4 py-2 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg mb-1 transition-colors text-sm">
                    <i class="fas fa-plus w-4 h-4 mr-3"></i>
                    Yeni Yazı Ekle
                </a>
                <a href="/admin/users/create" class="flex items-center px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg mb-1 transition-colors text-sm">
                    <i class="fas fa-user-plus w-4 h-4 mr-3"></i>
                    Kullanıcı Ekle
                </a>
            </div>
            
            <div class="border-t border-gray-200 my-4"></div>
            
            <!-- External Links -->
            <a href="/" target="_blank" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-green-600 rounded-lg mb-1 transition-colors">
                <i class="fas fa-external-link-alt w-5 h-5 mr-3"></i>
                Siteyi Görüntüle
            </a>
            
            <a href="/logout" class="flex items-center px-4 py-3 text-gray-600 hover:bg-red-50 hover:text-red-600 rounded-lg mb-1 transition-colors"
               onclick="return confirm('Çıkış yapmak istediğinizden emin misiniz?')">
                <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                Çıkış Yap
            </a>
        </nav>
        
        <!-- Admin Stats Widget -->
        <div class="border-t border-gray-200 p-4 bg-gray-50">
            <div class="text-center">
                <div class="text-sm text-gray-600 mb-2">
                    <i class="fas fa-clock mr-1"></i>
                    Son Giriş: <?= date('d.m.Y H:i') ?>
                </div>
                <div class="flex justify-center space-x-4 text-xs text-gray-500">
                    <span><i class="fas fa-users mr-1"></i><?= $stats['users']['total'] ?? 0 ?></span>
                    <span><i class="fas fa-file-alt mr-1"></i><?= $stats['posts']['total_posts'] ?? 0 ?></span>
                    <span><i class="fas fa-eye mr-1"></i><?= number_format($stats['posts']['total_views'] ?? 0) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>