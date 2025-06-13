<?php 
$statusLabels = [
    'new' => ['text' => 'Yeni', 'class' => 'bg-blue-100 text-blue-800'],
    'read' => ['text' => 'Okundu', 'class' => 'bg-gray-100 text-gray-800'],
    'replied' => ['text' => 'Cevaplandı', 'class' => 'bg-green-100 text-green-800']
];
?>

<!-- Admin Contacts Header -->
<div class="bg-gradient-to-r from-green-600 via-blue-500 to-green-700 text-white rounded-2xl p-8 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-envelope mr-3"></i>İletişim Mesajları
            </h1>
            <p class="text-white/80">Siteden gelen mesajları görüntüleyin ve yönetin</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-comments text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Navigation -->
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="lg:col-span-3">
        <!-- İstatistikler -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Yeni Mesajlar</p>
                        <p class="text-2xl font-bold text-blue-600"><?= $stats['new'] ?? 0 ?></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-envelope text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Okundu</p>
                        <p class="text-2xl font-bold text-gray-600"><?= $stats['read'] ?? 0 ?></p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-eye text-gray-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Cevaplandı</p>
                        <p class="text-2xl font-bold text-green-600"><?= $stats['replied'] ?? 0 ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-reply text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Toplam</p>
                        <p class="text-2xl font-bold text-primary-600"><?= $stats['total'] ?? 0 ?></p>
                    </div>
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-inbox text-primary-600"></i>
                    </div>
                </div>
            </div>
        </div>

    <!-- Filtreler ve Arama -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="flex gap-4">
                <!-- Durum Filtresi -->
                <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Tüm Durumlar</option>
                    <option value="new" <?= $currentStatus === 'new' ? 'selected' : '' ?>>Yeni</option>
                    <option value="read" <?= $currentStatus === 'read' ? 'selected' : '' ?>>Okundu</option>
                    <option value="replied" <?= $currentStatus === 'replied' ? 'selected' : '' ?>>Cevaplandı</option>
                </select>
                
                <!-- Sayfa başına gösterim -->
                <select id="perPageSelect" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="20" <?= $perPage == 20 ? 'selected' : '' ?>>20 mesaj</option>
                    <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50 mesaj</option>
                    <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100 mesaj</option>
                </select>
            </div>
            
            <!-- Toplu İşlemler -->
            <div class="flex gap-2">
                <button id="markAsReadBtn" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors disabled:opacity-50" disabled>
                    <i class="fas fa-eye mr-2"></i>Okundu İşaretle
                </button>
                <button id="deleteSelectedBtn" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors disabled:opacity-50" disabled>
                    <i class="fas fa-trash mr-2"></i>Seçilenleri Sil
                </button>
            </div>
        </div>
    </div>

    <!-- Mesajlar Tablosu -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    Mesajlar 
                    <?php if ($currentStatus): ?>
                        <span class="text-sm font-normal text-gray-500">- <?= ucfirst($currentStatus) ?></span>
                    <?php endif; ?>
                </h2>
                
                <label class="flex items-center">
                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-600">Tümünü seç</span>
                </label>
            </div>
        </div>

        <?php if (empty($messages)): ?>
            <div class="text-center py-12">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Mesaj Bulunamadı</h3>
                <p class="text-gray-500">
                    <?php if ($currentStatus): ?>
                        Bu durumda mesaj bulunmuyor.
                    <?php else: ?>
                        Henüz hiç mesaj gelmemiş.
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="selectAllTable" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gönderen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesaj</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($messages as $message): ?>
                            <tr class="hover:bg-gray-50 <?= $message['status'] === 'new' ? 'bg-blue-50' : '' ?>" data-message-id="<?= $message['id'] ?>">
                                <td class="px-6 py-4">
                                    <input type="checkbox" class="message-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500" value="<?= $message['id'] ?>">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($message['name']) ?></div>
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($message['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($message['subject'])): ?>
                                        <div class="text-sm font-medium text-gray-900 mb-1">
                                            Konu: <?= htmlspecialchars($message['subject']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="text-sm text-gray-900">
                                        <?= htmlspecialchars(substr($message['message'], 0, 100)) ?><?= strlen($message['message']) > 100 ? '...' : '' ?>
                                    </div>
                                    <?php if (!empty($message['ip_address'])): ?>
                                        <div class="text-xs text-gray-400 mt-1">IP: <?= htmlspecialchars($message['ip_address']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusLabels[$message['status']]['class'] ?>">
                                        <?= $statusLabels[$message['status']]['text'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div><?= date('d.m.Y H:i', strtotime($message['created_at'])) ?></div>
                                    <div class="text-xs text-gray-400">
                                        <?php 
                                        $time = time() - strtotime($message['created_at']);
                                        if ($time < 60) echo 'Az önce';
                                        elseif ($time < 3600) echo floor($time/60) . ' dakika önce';
                                        elseif ($time < 86400) echo floor($time/3600) . ' saat önce';
                                        elseif ($time < 2592000) echo floor($time/86400) . ' gün önce';
                                        elseif ($time < 31536000) echo floor($time/2592000) . ' ay önce';
                                        else echo floor($time/31536000) . ' yıl önce';
                                        ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="viewMessage(<?= $message['id'] ?>)" 
                                                class="text-primary-600 hover:text-primary-900 transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <?php if ($message['status'] !== 'replied'): ?>
                                            <button onclick="markAsReplied(<?= $message['id'] ?>)" 
                                                    class="text-green-600 hover:text-green-900 transition-colors" 
                                                    title="Cevaplandı olarak işaretle">
                                                <i class="fas fa-reply"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <button onclick="deleteMessage(<?= $message['id'] ?>)" 
                                                class="text-red-600 hover:text-red-900 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Sayfalama -->
            <?php if ($totalPages > 1): ?>
                <div class="bg-white px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Toplam <span class="font-medium"><?= $totalMessages ?></span> mesaj, 
                            <span class="font-medium"><?= (($currentPage - 1) * $perPage) + 1 ?></span> - 
                            <span class="font-medium"><?= min($currentPage * $perPage, $totalMessages) ?></span> arası gösteriliyor
                        </div>
                        
                        <nav class="flex space-x-1">
                            <?php if ($currentPage > 1): ?>
                                <a href="?page=<?= $currentPage - 1 ?><?= $currentStatus ? '&status=' . $currentStatus : '' ?>&per_page=<?= $perPage ?>" 
                                   class="px-3 py-2 text-sm bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 rounded-md">
                                    Önceki
                                </a>
                            <?php endif; ?>
                            
                            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                <a href="?page=<?= $i ?><?= $currentStatus ? '&status=' . $currentStatus : '' ?>&per_page=<?= $perPage ?>" 
                                   class="px-3 py-2 text-sm <?= $i === $currentPage ? 'bg-primary-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' ?> rounded-md">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?page=<?= $currentPage + 1 ?><?= $currentStatus ? '&status=' . $currentStatus : '' ?>&per_page=<?= $perPage ?>" 
                                   class="px-3 py-2 text-sm bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 rounded-md">
                                    Sonraki
                                </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Mesaj Görüntüleme Modal -->
<div id="messageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Mesaj Detayı</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div id="modalContent" class="px-6 py-4">
                <!-- Mesaj içeriği buraya yüklenecek -->
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    Kapat
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Checkbox seçimi
    const selectAll = document.getElementById('selectAllTable');
    const messageCheckboxes = document.querySelectorAll('.message-checkbox');
    const bulkButtons = document.querySelectorAll('#markAsReadBtn, #deleteSelectedBtn');
    
    selectAll.addEventListener('change', function() {
        messageCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkButtons();
    });
    
    messageCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkButtons);
    });
    
    function updateBulkButtons() {
        const selectedCount = document.querySelectorAll('.message-checkbox:checked').length;
        bulkButtons.forEach(btn => {
            btn.disabled = selectedCount === 0;
        });
    }
    
    // Filtre değişiklikleri
    document.getElementById('statusFilter').addEventListener('change', function() {
        const url = new URL(window.location);
        if (this.value) {
            url.searchParams.set('status', this.value);
        } else {
            url.searchParams.delete('status');
        }
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    });
    
    document.getElementById('perPageSelect').addEventListener('change', function() {
        const url = new URL(window.location);
        url.searchParams.set('per_page', this.value);
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    });
    
    // Toplu işlemler
    document.getElementById('markAsReadBtn').addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.message-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length > 0) {
            bulkUpdateStatus(selectedIds, 'read');
        }
    });
    
    document.getElementById('deleteSelectedBtn').addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.message-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length > 0 && confirm('Seçili mesajları silmek istediğinizden emin misiniz?')) {
            bulkDelete(selectedIds);
        }
    });
});

// Mesaj görüntüleme
function viewMessage(id) {
    fetch(`/admin/contacts/view/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalContent').innerHTML = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gönderen</label>
                            <p class="mt-1 text-sm text-gray-900">${data.message.name}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">E-posta</label>
                            <p class="mt-1 text-sm text-gray-900">${data.message.email}</p>
                        </div>
                        ${data.message.subject ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Konu</label>
                            <p class="mt-1 text-sm text-gray-900">${data.message.subject}</p>
                        </div>
                        ` : ''}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tarih</label>
                            <p class="mt-1 text-sm text-gray-900">${new Date(data.message.created_at).toLocaleString('tr-TR')}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mesaj</label>
                            <div class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">${data.message.message}</div>
                        </div>
                    </div>
                `;
                document.getElementById('messageModal').classList.remove('hidden');
                
                // Mesajı okundu olarak işaretle
                if (data.message.status === 'new') {
                    updateMessageStatus(id, 'read');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Mesaj yüklenirken hata oluştu.');
        });
}

// Modal kapatma
function closeModal() {
    document.getElementById('messageModal').classList.add('hidden');
}

// Mesaj durumu güncelleme
function updateMessageStatus(id, status) {
    fetch(`/admin/contacts/update-status/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status: status, csrf_token: window.csrfToken })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Cevaplandı olarak işaretle
function markAsReplied(id) {
    updateMessageStatus(id, 'replied');
}

// Mesaj silme
function deleteMessage(id) {
    if (confirm('Bu mesajı silmek istediğinizden emin misiniz?')) {
        fetch(`/admin/contacts/delete/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ csrf_token: window.csrfToken })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Mesaj silinirken hata oluştu.');
            }
        });
    }
}

// Toplu durum güncelleme
function bulkUpdateStatus(ids, status) {
    fetch('/admin/contacts/bulk-update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ ids: ids, status: status, csrf_token: window.csrfToken })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('İşlem sırasında hata oluştu.');
        }
    });
}

// Toplu silme
function bulkDelete(ids) {
    fetch('/admin/contacts/bulk-delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ ids: ids, csrf_token: window.csrfToken })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Silme işlemi sırasında hata oluştu.');
        }
    });
}
</script>

    </div>
</div>