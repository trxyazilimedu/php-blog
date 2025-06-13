<!-- User Edit Header -->
<div class="bg-gradient-to-r from-blue-600 via-purple-500 to-blue-700 text-white rounded-2xl p-8 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-user-edit mr-3"></i>Kullanıcı Düzenle
            </h1>
            <p class="text-white/80"><?= htmlspecialchars($user['name']) ?> kullanıcısının bilgilerini düzenleyin</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-edit text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Navigation -->
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <form method="POST" id="user-edit-form">
                <!-- Form Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Kullanıcı Bilgileri</h2>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Zorunlu alanlar</span>
                            <span class="text-red-500">*</span>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Personal Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-user mr-2 text-primary-500"></i>Ad Soyad
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="<?= htmlspecialchars($user['name']) ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                   placeholder="Kullanıcının tam adını girin..."
                                   required>
                        </div>
                        
                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-envelope mr-2 text-primary-500"></i>E-posta Adresi
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($user['email']) ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                   placeholder="ornek@email.com"
                                   required>
                        </div>
                    </div>
                    
                    <!-- Password Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-key mr-2 text-primary-500"></i>Şifre Değiştir
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">Şifreyi değiştirmek istemiyorsanız bu alanları boş bırakın.</p>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div class="space-y-2">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-lock mr-2 text-primary-500"></i>Yeni Şifre
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all pr-12"
                                           placeholder="En az 6 karakter...">
                                    <button type="button" 
                                            onclick="togglePassword('password')"
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye" id="password-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Confirm Password -->
                            <div class="space-y-2">
                                <label for="password_confirm" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-lock mr-2 text-primary-500"></i>Şifre Tekrar
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password_confirm" 
                                           name="password_confirm" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all pr-12"
                                           placeholder="Şifreyi tekrar girin...">
                                    <button type="button" 
                                            onclick="togglePassword('password_confirm')"
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye" id="password_confirm-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Role and Status -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Role -->
                        <div class="space-y-2">
                            <label for="role" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-user-tag mr-2 text-primary-500"></i>Kullanıcı Rolü
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select name="role" 
                                    id="role"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                    required>
                                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>👤 Kullanıcı - Temel üye</option>
                                <option value="writer" <?= $user['role'] === 'writer' ? 'selected' : '' ?>>✍️ Yazar - Blog yazabilir</option>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>🔐 Admin - Tüm yetkiler</option>
                            </select>
                        </div>
                        
                        <!-- Status -->
                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-toggle-on mr-2 text-primary-500"></i>Hesap Durumu
                            </label>
                            <select name="status" 
                                    id="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                                <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>✅ Aktif - Hemen kullanabilir</option>
                                <option value="pending" <?= $user['status'] === 'pending' ? 'selected' : '' ?>>⏳ Onay Bekliyor - E-posta doğrulama gerekli</option>
                                <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>⛔ Askıya Alınmış - Giriş yapamaz</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Bio -->
                    <div class="space-y-2">
                        <label for="bio" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-info-circle mr-2 text-primary-500"></i>Kısa Biyografi
                        </label>
                        <textarea id="bio" 
                                  name="bio" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                  placeholder="Kullanıcı hakkında kısa bilgi (isteğe bağlı)..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                        <div class="flex justify-between">
                            <p class="text-sm text-gray-500">Profil sayfasında gösterilecek</p>
                            <div id="bio-counter" class="text-sm text-gray-500">
                                <?= strlen($user['bio'] ?? '') ?> / 500 karakter
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                    <div class="flex items-center space-x-4">
                        <a href="/admin/users/<?= $user['id'] ?>" class="text-gray-600 hover:text-gray-800 font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>Kullanıcı Detayına Dön
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <a href="/admin/users" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-times mr-2"></i>İptal
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-save mr-2"></i>Değişiklikleri Kaydet
                        </button>
                    </div>
                </div>
                
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Password visibility toggle
    window.togglePassword = function(fieldId) {
        const field = $('#' + fieldId);
        const eye = $('#' + fieldId + '-eye');
        
        if (field.attr('type') === 'password') {
            field.attr('type', 'text');
            eye.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            field.attr('type', 'password');
            eye.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    };
    
    // Password confirmation checker
    $('#password_confirm').on('input', function() {
        const password = $('#password').val();
        const confirm = $(this).val();
        
        if (password && confirm && password !== confirm) {
            $(this).addClass('border-red-500');
        } else {
            $(this).removeClass('border-red-500');
        }
    });
    
    // Bio character counter
    $('#bio').on('input', function() {
        const count = $(this).val().length;
        const counter = $('#bio-counter');
        counter.text(count + ' / 500 karakter');
        
        if (count > 450) {
            counter.addClass('text-red-500').removeClass('text-gray-500');
        } else {
            counter.addClass('text-gray-500').removeClass('text-red-500');
        }
    });
    
    // Form validation
    $('#user-edit-form').submit(function(e) {
        let isValid = true;
        
        // Clear previous errors
        $('input, select').removeClass('border-red-500');
        
        // Check password match if passwords are entered
        const password = $('#password').val();
        const passwordConfirm = $('#password_confirm').val();
        
        if (password || passwordConfirm) {
            if (password !== passwordConfirm) {
                $('#password, #password_confirm').addClass('border-red-500');
                alert('Şifreler eşleşmiyor!');
                isValid = false;
            }
            
            if (password.length > 0 && password.length < 6) {
                $('#password').addClass('border-red-500');
                alert('Şifre en az 6 karakter olmalıdır!');
                isValid = false;
            }
        }
        
        // Validate required fields
        const requiredFields = ['name', 'email', 'role', 'status'];
        requiredFields.forEach(function(field) {
            const value = $('#' + field).val().trim();
            if (!value) {
                $('#' + field).addClass('border-red-500');
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('.border-red-500').first().offset().top - 100
            }, 500);
        } else {
            // Show loading state
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Kaydediliyor...');
        }
    });
});
</script>