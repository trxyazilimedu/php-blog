<!-- Page Header -->
<div class="bg-gradient-to-r from-purple-600 via-indigo-500 to-purple-700 text-white rounded-2xl p-8 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-tachometer-alt mr-3"></i>Cache Yönetimi
            </h1>
            <p class="text-white/80">Site performansını artırmak için cache yönetimi yapın.</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-server text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Navigation -->
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="lg:col-span-3">
        <!-- OPcache Status -->
        <?php if ($cache_stats['opcache']['enabled']): ?>
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-microchip mr-2 text-blue-500"></i>OPcache Durumu
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Memory Usage -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-blue-700">Bellek Kullanımı</span>
                            <span class="text-lg font-bold text-blue-900"><?= $cache_stats['opcache']['memory_usage']['usage_percentage'] ?>%</span>
                        </div>
                        <div class="w-full bg-blue-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: <?= $cache_stats['opcache']['memory_usage']['usage_percentage'] ?>%"></div>
                        </div>
                        <div class="text-xs text-blue-600 mt-1">
                            Kullanılan: <?= number_format($cache_stats['opcache']['memory_usage']['used'] / 1024 / 1024, 1) ?> MB
                        </div>
                    </div>
                    
                    <!-- Hit Rate -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-green-700">Başarı Oranı</span>
                            <span class="text-lg font-bold text-green-900"><?= $cache_stats['opcache']['hit_rate'] ?>%</span>
                        </div>
                        <div class="w-full bg-green-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: <?= $cache_stats['opcache']['hit_rate'] ?>%"></div>
                        </div>
                        <div class="text-xs text-green-600 mt-1">
                            Cache'den okunan dosyalar
                        </div>
                    </div>
                    
                    <!-- Cached Files -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-purple-700">Cache'li Dosyalar</span>
                            <span class="text-lg font-bold text-purple-900"><?= $cache_stats['opcache']['cached_files'] ?></span>
                        </div>
                        <div class="w-full bg-purple-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: <?= ($cache_stats['opcache']['cached_files'] / $cache_stats['opcache']['max_files']) * 100 ?>%"></div>
                        </div>
                        <div class="text-xs text-purple-600 mt-1">
                            Maksimum: <?= $cache_stats['opcache']['max_files'] ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-8">
                <h3 class="text-lg font-semibold text-red-800 mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>OPcache Devre Dışı
                </h3>
                <p class="text-red-700">OPcache sisteminizde yüklü değil veya devre dışı. Performans artışı için OPcache'i etkinleştirmenizi öneririz.</p>
            </div>
        <?php endif; ?>
        
        <!-- File Cache Status -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">
                <i class="fas fa-file mr-2 text-green-500"></i>Dosya Cache Durumu
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600"><?= $cache_stats['file_cache']['total_files'] ?></div>
                    <div class="text-sm text-gray-500">Toplam Dosya</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600"><?= $cache_stats['file_cache']['valid_files'] ?></div>
                    <div class="text-sm text-gray-500">Geçerli Dosya</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600"><?= $cache_stats['file_cache']['expired_files'] ?></div>
                    <div class="text-sm text-gray-500">Süresi Dolmuş</div>
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <span class="text-lg font-semibold">Toplam Boyut: <?= $cache_stats['file_cache']['size_formatted'] ?></span>
            </div>
        </div>
        
        <!-- Cache Actions -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">
                <i class="fas fa-tools mr-2 text-orange-500"></i>Cache İşlemleri
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Clear OPcache -->
                <?php if ($cache_stats['opcache']['enabled']): ?>
                <form method="POST" class="inline">
                    <input type="hidden" name="action" value="clear_opcache">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <button type="submit" 
                            onclick="return confirm('OPcache\'i tamamen temizlemek istediğinizden emin misiniz?')"
                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-trash mr-2"></i>OPcache Temizle
                    </button>
                </form>
                <?php endif; ?>
                
                <!-- Clear File Cache -->
                <form method="POST" class="inline">
                    <input type="hidden" name="action" value="clear_file_cache">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <button type="submit" 
                            onclick="return confirm('Dosya cache\'ini tamamen temizlemek istediğinizden emin misiniz?')"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-file-archive mr-2"></i>Dosya Cache Temizle
                    </button>
                </form>
                
                <!-- Clear Blog Cache -->
                <form method="POST" class="inline">
                    <input type="hidden" name="action" value="clear_blog_cache">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <button type="submit" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-blog mr-2"></i>Blog Cache Temizle
                    </button>
                </form>
                
                <!-- Clear Admin Cache -->
                <form method="POST" class="inline">
                    <input type="hidden" name="action" value="clear_admin_cache">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <button type="submit" 
                            class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-user-cog mr-2"></i>Admin Cache Temizle
                    </button>
                </form>
                
                <!-- Cleanup Expired -->
                <form method="POST" class="inline">
                    <input type="hidden" name="action" value="cleanup_expired">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <button type="submit" 
                            class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-broom mr-2"></i>Süresi Dolmuş Temizle
                    </button>
                </form>
                
                <!-- Refresh Page -->
                <button onclick="window.location.reload()" 
                        class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-lg transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Durumu Yenile
                </button>
            </div>
            
            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                <h4 class="font-semibold text-blue-800 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Cache Hakkında
                </h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li><strong>OPcache:</strong> PHP kodlarını derlenmiş halde bellekte tutar</li>
                    <li><strong>Dosya Cache:</strong> Veritabanı sorgularını ve hesaplamaları saklar</li>
                    <li><strong>Akıllı Temizlik:</strong> Sadece ilgili dosyalar temizlenir</li>
                    <li><strong>Otomatik Temizlik:</strong> Blog/admin işlemleri sonrası otomatik temizlenir</li>
                </ul>
            </div>
        </div>
    </div>
</div>