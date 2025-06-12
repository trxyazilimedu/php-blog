<!-- Admin Users Header -->
<div class="bg-gradient-to-r from-purple-600 via-blue-500 to-purple-700 text-white rounded-2xl p-8 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-users mr-3"></i>Kullanıcı Yönetimi
            </h1>
            <p class="text-white/80">Kullanıcıları yönetin, onaylayın ve rollerini düzenleyin.</p>
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
    <div class="lg:col-span-3">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Toplam Kullanıcı</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?? 0 ?></p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Active Users -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Aktif Kullanıcı</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['active'] ?? 0 ?></p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-check text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Pending Users -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Bekleyen Kullanıcı</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['pending'] ?? 0 ?></p>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Writers -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Yazar</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['writers'] ?? 0 ?></p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-pen text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Users Management -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold">
                        <i class="fas fa-list mr-2"></i>Tüm Kullanıcılar
                    </h3>
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="relative">
                            <input type="text" 
                                   id="search-users" 
                                   placeholder="Kullanıcı ara..." 
                                   class="bg-white/20 text-white placeholder-white/70 px-4 py-2 pr-10 rounded-lg focus:outline-none focus:ring-2 focus:ring-white/50">
                            <i class="fas fa-search absolute right-3 top-3 text-white/70"></i>
                        </div>
                        
                        <!-- Filter -->
                        <select id="status-filter" class="bg-white/20 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-white/50">
                            <option value="">Tüm Durumlar</option>
                            <option value="active">Aktif</option>
                            <option value="pending">Bekleyen</option>
                            <option value="inactive">Pasif</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-4 px-6 font-semibold text-gray-700">Kullanıcı</th>
                            <th class="text-left py-4 px-6 font-semibold text-gray-700">E-posta</th>
                            <th class="text-left py-4 px-6 font-semibold text-gray-700">Rol</th>
                            <th class="text-left py-4 px-6 font-semibold text-gray-700">Durum</th>
                            <th class="text-left py-4 px-6 font-semibold text-gray-700">Kayıt Tarihi</th>
                            <th class="text-left py-4 px-6 font-semibold text-gray-700">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-50 transition-colors user-row" data-status="<?= $user['status'] ?>" data-name="<?= htmlspecialchars($user['name']) ?>" data-email="<?= htmlspecialchars($user['email']) ?>">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                                                <span class="text-white text-sm font-semibold">
                                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                                </span>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900"><?= htmlspecialchars($user['name']) ?></p>
                                                <p class="text-sm text-gray-500">ID: <?= $user['id'] ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="text-gray-900"><?= htmlspecialchars($user['email']) ?></span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            <?= $user['role'] === 'admin' ? 'bg-red-100 text-red-800' : 
                                                ($user['role'] === 'writer' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') ?>">
                                            <?= ucfirst($user['role']) ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                                                ($user['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                            <?= ucfirst($user['status']) ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="text-gray-600"><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center space-x-2">
                                            <a href="/admin/users/<?= $user['id'] ?>" 
                                               class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-sm transition-colors">
                                                <i class="fas fa-eye mr-1"></i>Görüntüle
                                            </a>
                                            
                                            <?php if ($user['status'] === 'pending'): ?>
                                                <button onclick="approveUser(<?= $user['id'] ?>)" 
                                                        class="inline-flex items-center px-3 py-1 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg text-sm transition-colors">
                                                    <i class="fas fa-check mr-1"></i>Onayla
                                                </button>
                                                <button onclick="rejectUser(<?= $user['id'] ?>)" 
                                                        class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm transition-colors">
                                                    <i class="fas fa-times mr-1"></i>Reddet
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-users text-gray-400 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 text-lg">Kullanıcı bulunamadı</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pending Users Section -->
        <?php if (!empty($pending_users)): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white p-6">
                    <h3 class="text-lg font-semibold">
                        <i class="fas fa-clock mr-2"></i>Onay Bekleyen Kullanıcılar
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($pending_users as $user): ?>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold">
                                                <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                            </span>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($user['name']) ?></h4>
                                            <p class="text-sm text-gray-600"><?= htmlspecialchars($user['email']) ?></p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </div>
                                
                                <div class="text-sm text-gray-600 mb-4">
                                    <p><i class="fas fa-calendar mr-2"></i>Kayıt: <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></p>
                                </div>
                                
                                <div class="flex space-x-3">
                                    <form method="POST" action="/admin/approve-user/<?= $user['id'] ?>" class="flex-1">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                        <button type="submit" 
                                                onclick="return confirm('Bu kullanıcıyı onaylamak istediğinizden emin misiniz?')"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                                            <i class="fas fa-check mr-2"></i>Onayla
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="/admin/reject-user/<?= $user['id'] ?>" class="flex-1">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                        <button type="submit" 
                                                onclick="return confirm('Bu kullanıcıyı reddetmek istediğinizden emin misiniz?')"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                                            <i class="fas fa-times mr-2"></i>Reddet
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    // Search functionality
    $('#search-users').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterUsers();
    });
    
    // Status filter functionality
    $('#status-filter').on('change', function() {
        filterUsers();
    });
    
    function filterUsers() {
        const searchTerm = $('#search-users').val().toLowerCase();
        const statusFilter = $('#status-filter').val();
        
        $('.user-row').each(function() {
            const $row = $(this);
            const name = $row.data('name').toLowerCase();
            const email = $row.data('email').toLowerCase();
            const status = $row.data('status');
            
            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
            const matchesStatus = !statusFilter || status === statusFilter;
            
            if (matchesSearch && matchesStatus) {
                $row.show();
            } else {
                $row.hide();
            }
        });
    }
    
    // Real-time user count update
    function updateUserCounts() {
        const visibleRows = $('.user-row:visible');
        const total = visibleRows.length;
        const active = visibleRows.filter('[data-status="active"]').length;
        const pending = visibleRows.filter('[data-status="pending"]').length;
        
        // Update stats cards if needed
    }
    
    // Call update after filtering
    $('#search-users, #status-filter').on('input change', function() {
        setTimeout(updateUserCounts, 100);
    });
});

// User approval/rejection functions
function approveUser(userId) {
    if (confirm('Bu kullanıcıyı onaylamak istediğinizden emin misiniz?')) {
        $.ajax({
            url: `/admin/approve-user/${userId}`,
            method: 'POST',
            data: {
                csrf_token: window.csrfToken
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Hata: ' + response.message);
                }
            },
            error: function() {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            }
        });
    }
}

function rejectUser(userId) {
    if (confirm('Bu kullanıcıyı reddetmek istediğinizden emin misiniz?')) {
        $.ajax({
            url: `/admin/reject-user/${userId}`,
            method: 'POST',
            data: {
                csrf_token: window.csrfToken
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Hata: ' + response.message);
                }
            },
            error: function() {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            }
        });
    }
}
</script>