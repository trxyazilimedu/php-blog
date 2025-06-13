<!-- User Detail Header -->
<div class="bg-gradient-to-r from-indigo-600 via-purple-500 to-indigo-700 text-white rounded-2xl p-8 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-user-circle mr-3"></i><?= htmlspecialchars($user['name']) ?>
            </h1>
            <p class="text-white/80">Kullanıcı detayları ve aktivite geçmişi</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-user-cog text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Navigation -->
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="lg:col-span-3 space-y-8">
        
        <!-- User Information Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-id-card mr-2 text-indigo-600"></i>Kullanıcı Bilgileri
                    </h2>
                    <div class="flex items-center space-x-2">
                        <?php 
                        $statusColors = [
                            'active' => 'bg-green-100 text-green-800',
                            'pending' => 'bg-yellow-100 text-yellow-800', 
                            'inactive' => 'bg-red-100 text-red-800'
                        ];
                        $statusColor = $statusColors[$user['status']] ?? 'bg-gray-100 text-gray-800';
                        ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $statusColor ?>">
                            <?= ucfirst($user['status']) ?>
                        </span>
                        
                        <?php 
                        $roleColors = [
                            'admin' => 'bg-red-100 text-red-800',
                            'writer' => 'bg-blue-100 text-blue-800',
                            'user' => 'bg-gray-100 text-gray-800'
                        ];
                        $roleColor = $roleColors[$user['role']] ?? 'bg-gray-100 text-gray-800';
                        ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $roleColor ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Basic Info -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Ad Soyad</label>
                            <p class="text-gray-900 font-medium"><?= htmlspecialchars($user['name']) ?></p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">E-posta</label>
                            <p class="text-gray-900"><?= htmlspecialchars($user['email']) ?></p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Rol</label>
                            <p class="text-gray-900 capitalize"><?= htmlspecialchars($user['role']) ?></p>
                        </div>
                    </div>
                    
                    <!-- Status & Dates -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Durum</label>
                            <p class="text-gray-900 capitalize"><?= htmlspecialchars($user['status']) ?></p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Kayıt Tarihi</label>
                            <p class="text-gray-900"><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></p>
                        </div>
                        
                        <?php if (!empty($user['updated_at'])): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Son Güncelleme</label>
                            <p class="text-gray-900"><?= date('d.m.Y H:i', strtotime($user['updated_at'])) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Bio -->
                    <?php if (!empty($user['bio'])): ?>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Biyografi</label>
                            <p class="text-gray-900 text-sm leading-relaxed"><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- User Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Blog Posts -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Blog Yazıları</p>
                        <p class="text-2xl font-bold text-gray-900"><?= count($user_posts) ?></p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Comments -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Yorumlar</p>
                        <p class="text-2xl font-bold text-gray-900"><?= count($user_comments) ?></p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-comments text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Account Age -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Hesap Yaşı</p>
                        <p class="text-2xl font-bold text-gray-900">
                            <?php 
                            $created = new DateTime($user['created_at']);
                            $now = new DateTime();
                            $diff = $now->diff($created);
                            echo $diff->days . ' gün';
                            ?>
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Blog Posts -->
        <?php if (!empty($user_posts)): ?>
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-blog mr-2 text-blue-600"></i>Son Blog Yazıları
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <?php foreach (array_slice($user_posts, 0, 5) as $post): ?>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 mb-1">
                                <a href="/blog/post/<?= htmlspecialchars($post['slug']) ?>" 
                                   class="hover:text-blue-600 transition-colors" 
                                   target="_blank">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h4>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span><?= date('d.m.Y', strtotime($post['created_at'])) ?></span>
                                <span class="flex items-center">
                                    <i class="fas fa-eye mr-1"></i>
                                    <?= $post['views'] ?> görüntülenme
                                </span>
                                <span class="px-2 py-1 bg-<?= $post['status'] === 'published' ? 'green' : 'yellow' ?>-100 text-<?= $post['status'] === 'published' ? 'green' : 'yellow' ?>-800 rounded-full text-xs">
                                    <?= $post['status'] ?>
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="/blog/edit/<?= $post['id'] ?>" 
                               class="text-blue-600 hover:text-blue-700 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/blog/post/<?= htmlspecialchars($post['slug']) ?>" 
                               target="_blank"
                               class="text-gray-600 hover:text-gray-700 transition-colors">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($user_posts) > 5): ?>
                <div class="mt-4 text-center">
                    <span class="text-sm text-gray-500">Ve <?= count($user_posts) - 5 ?> yazı daha...</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Recent Comments -->
        <?php if (!empty($user_comments)): ?>
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-comments mr-2 text-green-600"></i>Son Yorumlar
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <?php foreach (array_slice($user_comments, 0, 5) as $comment): ?>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 text-sm mb-1">
                                    <?= htmlspecialchars($comment['post_title'] ?? 'Yazı bulunamadı') ?>
                                </h4>
                                <p class="text-gray-700 text-sm leading-relaxed">
                                    <?= htmlspecialchars(strlen($comment['comment']) > 150 ? 
                                        substr($comment['comment'], 0, 150) . '...' : 
                                        $comment['comment']) ?>
                                </p>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <span class="px-2 py-1 bg-<?= $comment['status'] === 'approved' ? 'green' : 'yellow' ?>-100 text-<?= $comment['status'] === 'approved' ? 'green' : 'yellow' ?>-800 rounded-full text-xs">
                                    <?= $comment['status'] ?>
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span><?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?></span>
                            <?php if ($comment['post_id']): ?>
                            <a href="/blog/post/<?= htmlspecialchars($comment['post_title']) ?>" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-700 transition-colors">
                                <i class="fas fa-external-link-alt mr-1"></i>Yazıyı Görüntüle
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($user_comments) > 5): ?>
                <div class="mt-4 text-center">
                    <span class="text-sm text-gray-500">Ve <?= count($user_comments) - 5 ?> yorum daha...</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Action Buttons -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <a href="/admin/users" 
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kullanıcı Listesi
                    </a>
                </div>
                
                <div class="flex items-center space-x-3">
                    <a href="/admin/users/edit/<?= $user['id'] ?>" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-edit mr-2"></i>Düzenle
                    </a>
                    
                    <?php if ($user['status'] === 'active'): ?>
                    <form method="POST" action="/admin/users/status/<?= $user['id'] ?>" class="inline">
                        <input type="hidden" name="status" value="inactive">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                        <button type="submit" 
                                onclick="return confirm('Bu kullanıcıyı deaktif etmek istediğinizden emin misiniz?')"
                                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-ban mr-2"></i>Deaktif Et
                        </button>
                    </form>
                    <?php else: ?>
                    <form method="POST" action="/admin/users/status/<?= $user['id'] ?>" class="inline">
                        <input type="hidden" name="status" value="active">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                        <button type="submit" 
                                onclick="return confirm('Bu kullanıcıyı aktif etmek istediğinizden emin misiniz?')"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-check mr-2"></i>Aktif Et
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>