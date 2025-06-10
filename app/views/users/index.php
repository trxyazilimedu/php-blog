<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
        <div class="mb-4 lg:mb-0">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                <?= htmlspecialchars($page_title ?? 'Kullanıcılar') ?>
            </h1>
            <p class="text-lg text-gray-600">Sistemdeki tüm kullanıcıları görüntüle ve yönet</p>
        </div>
        <div>
            <a href="/users/create" 
               class="inline-flex items-center bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-3 rounded-lg font-semibold hover:from-green-600 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-plus mr-2"></i>
                Yeni Kullanıcı
            </a>
        </div>
    </div>

    <?php if (empty($users)): ?>
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-users text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Henüz kullanıcı bulunmuyor</h3>
            <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
                İlk kullanıcıyı oluşturmak için aşağıdaki butona tıklayın.
            </p>
            <a href="/users/create" 
               class="inline-flex items-center bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-8 py-4 rounded-lg font-semibold hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-user-plus mr-2"></i>
                Yeni Kullanıcı Oluştur
            </a>
        </div>
    <?php else: ?>
        <!-- Users Grid (Mobile) and Table (Desktop) -->
        
        <!-- Mobile Cards (Hidden on Desktop) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:hidden mb-8">
            <?php foreach ($users as $user): ?>
                <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                    <!-- User Header -->
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-lg">
                                <?= strtoupper(substr($user['name'], 0, 1)) ?>
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">
                                <?= htmlspecialchars($user['name']) ?>
                            </h3>
                            <p class="text-sm text-gray-600 truncate">
                                <?= htmlspecialchars($user['email']) ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                #<?= $user['id'] ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- User Details -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= ($user['role'] ?? 'user') === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' ?>">
                                    <?= ucfirst($user['role'] ?? 'user') ?>
                                </span>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= ($user['status'] ?? 'active') === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                    <?= ($user['status'] ?? 'active') === 'active' ? 'Aktif' : 'Pasif' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Kayıt Tarihi</span>
                        <p class="text-sm text-gray-900 mt-1">
                            <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?>
                        </p>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="/users/show/<?= $user['id'] ?>" 
                           class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                            <i class="fas fa-eye mr-1"></i>
                            Görüntüle
                        </a>
                        <a href="/users/edit/<?= $user['id'] ?>" 
                           class="flex-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                            <i class="fas fa-edit mr-1"></i>
                            Düzenle
                        </a>
                        <form method="POST" action="/users/delete/<?= $user['id'] ?>" class="flex-1">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                            <button type="submit" 
                                    onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')"
                                    class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-trash mr-1"></i>
                                Sil
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Desktop Table (Hidden on Mobile) -->
        <div class="hidden lg:block bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kullanıcı
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                E-posta
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rol
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Durum
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kayıt Tarihi
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                İşlemler
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-semibold">
                                                <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($user['name']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                ID: #<?= $user['id'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($user['email']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= ($user['role'] ?? 'user') === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' ?>">
                                        <?= ucfirst($user['role'] ?? 'user') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= ($user['status'] ?? 'active') === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                        <?= ($user['status'] ?? 'active') === 'active' ? 'Aktif' : 'Pasif' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="/users/show/<?= $user['id'] ?>" 
                                           class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-eye mr-1"></i>
                                            Görüntüle
                                        </a>
                                        <a href="/users/edit/<?= $user['id'] ?>" 
                                           class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-edit mr-1"></i>
                                            Düzenle
                                        </a>
                                        <form method="POST" action="/users/delete/<?= $user['id'] ?>" class="inline">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                            <button type="submit" 
                                                    onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')"
                                                    class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                                <i class="fas fa-trash mr-1"></i>
                                                Sil
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Summary -->
        <div class="mt-8 text-center">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-6">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-users text-primary-500"></i>
                        <span class="text-gray-600">Toplam Kullanıcı:</span>
                        <span class="font-bold text-gray-900"><?= count($users) ?></span>
                    </div>
                    <div class="hidden sm:block w-px h-6 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-clock text-gray-500"></i>
                        <span class="text-gray-600">Son Güncelleme:</span>
                        <span class="font-medium text-gray-900"><?= date('d.m.Y H:i') ?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>