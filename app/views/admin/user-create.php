<!-- Page Header -->
<div class="bg-gradient-to-r from-green-600 via-blue-500 to-green-700 text-white rounded-2xl p-8 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-user-plus mr-3"></i>Yeni Kullanıcı Ekle
            </h1>
            <p class="text-white/80">Sisteme yeni bir kullanıcı ekleyin ve rolünü belirleyin.</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-user-circle text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Navigation -->
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <form method="POST" id="user-create-form">
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
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                   placeholder="Kullanıcının tam adını girin..."
                                   required>
                            <div id="name-error" class="text-sm text-red-500 hidden"></div>
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
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                   placeholder="ornek@email.com"
                                   required>
                            <div id="email-error" class="text-sm text-red-500 hidden"></div>
                        </div>
                    </div>
                    
                    <!-- Password Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-lock mr-2 text-primary-500"></i>Şifre
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all pr-12"
                                       placeholder="En az 8 karakter..."
                                       required>
                                <button type="button" 
                                        onclick="togglePassword('password')"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="password-eye"></i>
                                </button>
                            </div>
                            <div class="text-sm text-gray-500">
                                <div id="password-strength" class="flex items-center space-x-2">
                                    <div class="flex space-x-1">
                                        <div class="w-2 h-2 rounded-full bg-gray-200" id="strength-1"></div>
                                        <div class="w-2 h-2 rounded-full bg-gray-200" id="strength-2"></div>
                                        <div class="w-2 h-2 rounded-full bg-gray-200" id="strength-3"></div>
                                        <div class="w-2 h-2 rounded-full bg-gray-200" id="strength-4"></div>
                                    </div>
                                    <span id="strength-text">Şifre gücü</span>
                                </div>
                            </div>
                            <div id="password-error" class="text-sm text-red-500 hidden"></div>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label for="password_confirm" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-lock mr-2 text-primary-500"></i>Şifre Tekrar
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       id="password_confirm" 
                                       name="password_confirm" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all pr-12"
                                       placeholder="Şifrenizi tekrar girin..."
                                       required>
                                <button type="button" 
                                        onclick="togglePassword('password_confirm')"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="password_confirm-eye"></i>
                                </button>
                            </div>
                            <div id="password-match" class="text-sm hidden">
                                <span class="text-green-600"><i class="fas fa-check mr-1"></i>Şifreler eşleşiyor</span>
                            </div>
                            <div id="password-confirm-error" class="text-sm text-red-500 hidden"></div>
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
                                <option value="">Rol seçin...</option>
                                <option value="user">👤 Kullanıcı - Temel üye</option>
                                <option value="writer">✍️ Yazar - Blog yazabilir</option>
                                <option value="admin">🔐 Admin - Tüm yetkiler</option>
                            </select>
                            <div class="text-sm text-gray-500">
                                <div id="role-description" class="mt-2 p-3 bg-gray-50 rounded-lg hidden">
                                    <strong class="text-gray-700">Bu rol:</strong>
                                    <ul id="role-permissions" class="mt-1 space-y-1 text-xs"></ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-toggle-on mr-2 text-primary-500"></i>Hesap Durumu
                            </label>
                            <select name="status" 
                                    id="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                                <option value="active">✅ Aktif - Hemen kullanabilir</option>
                                <option value="pending">⏳ Onay Bekliyor - E-posta doğrulama gerekli</option>
                                <option value="suspended">⛔ Askıya Alınmış - Giriş yapamaz</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-info-circle mr-2 text-primary-500"></i>Ek Bilgiler
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Bio -->
                            <div class="space-y-2">
                                <label for="bio" class="block text-sm font-medium text-gray-700">Kısa Biyografi</label>
                                <textarea id="bio" 
                                          name="bio" 
                                          rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                          placeholder="Kullanıcı hakkında kısa bilgi (isteğe bağlı)..."></textarea>
                                <div class="flex justify-between">
                                    <p class="text-sm text-gray-500">Profil sayfasında gösterilecek</p>
                                    <div id="bio-counter" class="text-sm text-gray-500">0 / 500 karakter</div>
                                </div>
                            </div>
                            
                            <!-- Send Email -->
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       id="send_email" 
                                       name="send_email" 
                                       value="1"
                                       checked
                                       class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500">
                                <label for="send_email" class="text-sm font-medium text-gray-700">
                                    <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                    Kullanıcıya hoş geldin e-postası gönder
                                </label>
                            </div>
                            
                            <!-- Force Password Change -->
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       id="force_password_change" 
                                       name="force_password_change" 
                                       value="1"
                                       class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500">
                                <label for="force_password_change" class="text-sm font-medium text-gray-700">
                                    <i class="fas fa-key mr-2 text-orange-500"></i>
                                    İlk girişte şifre değiştirmeye zorla
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                    <div class="flex items-center space-x-4">
                        <a href="/admin/users" class="text-gray-600 hover:text-gray-800 font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>Kullanıcı Listesine Dön
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <button type="button" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-eye mr-2"></i>Önizle
                        </button>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-8 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-user-plus mr-2"></i>Kullanıcı Oluştur
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
    
    // Password strength checker
    $('#password').on('input', function() {
        const password = $(this).val();
        let strength = 0;
        let strengthText = 'Çok Zayıf';
        let strengthColor = 'bg-red-500';
        
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;
        
        // Update strength indicators
        for (let i = 1; i <= 4; i++) {
            const indicator = $('#strength-' + i);
            if (i <= strength) {
                if (strength <= 1) indicator.removeClass().addClass('w-2 h-2 rounded-full bg-red-500');
                else if (strength <= 2) indicator.removeClass().addClass('w-2 h-2 rounded-full bg-orange-500');
                else if (strength <= 3) indicator.removeClass().addClass('w-2 h-2 rounded-full bg-yellow-500');
                else indicator.removeClass().addClass('w-2 h-2 rounded-full bg-green-500');
            } else {
                indicator.removeClass().addClass('w-2 h-2 rounded-full bg-gray-200');
            }
        }
        
        // Update strength text
        if (strength <= 1) strengthText = 'Çok Zayıf';
        else if (strength <= 2) strengthText = 'Zayıf';
        else if (strength <= 3) strengthText = 'Orta';
        else strengthText = 'Güçlü';
        
        $('#strength-text').text(strengthText);
    });
    
    // Password confirmation checker
    $('#password_confirm').on('input', function() {
        const password = $('#password').val();
        const confirm = $(this).val();
        const matchDiv = $('#password-match');
        
        if (confirm && password === confirm) {
            matchDiv.removeClass('hidden');
        } else {
            matchDiv.addClass('hidden');
        }
    });
    
    // Role description
    const roleDescriptions = {
        'user': [
            'Kendi profilini düzenleyebilir',
            'Blog yazılarını okuyabilir ve yorum yapabilir',
            'Temel kullanıcı işlemlerini gerçekleştirebilir'
        ],
        'writer': [
            'Kullanıcı yetkilerine ek olarak:',
            'Blog yazısı oluşturabilir ve düzenleyebilir',
            'Kendi yazılarını yönetebilir',
            'Kategorileri görüntüleyebilir'
        ],
        'admin': [
            'Tüm sistem yetkilerine sahiptir:',
            'Kullanıcıları yönetebilir (ekleme, düzenleme, silme)',
            'Tüm blog yazılarını yönetebilir',
            'Kategorileri yönetebilir',
            'Site ayarlarını değiştirebilir'
        ]
    };
    
    $('#role').on('change', function() {
        const role = $(this).val();
        const descDiv = $('#role-description');
        const permList = $('#role-permissions');
        
        if (role && roleDescriptions[role]) {
            permList.empty();
            roleDescriptions[role].forEach(function(perm) {
                permList.append('<li>• ' + perm + '</li>');
            });
            descDiv.removeClass('hidden');
        } else {
            descDiv.addClass('hidden');
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
    
    // Email validation
    $('#email').on('blur', function() {
        const email = $(this).val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const errorDiv = $('#email-error');
        
        if (email && !emailRegex.test(email)) {
            errorDiv.text('Geçerli bir e-posta adresi girin').removeClass('hidden');
            $(this).addClass('border-red-500');
        } else {
            errorDiv.addClass('hidden');
            $(this).removeClass('border-red-500');
        }
    });
    
    // Form validation
    $('#user-create-form').submit(function(e) {
        let isValid = true;
        
        // Clear previous errors
        $('.text-red-500').addClass('hidden');
        $('input, select').removeClass('border-red-500');
        
        // Validate required fields
        const requiredFields = ['name', 'email', 'password', 'password_confirm', 'role'];
        requiredFields.forEach(function(field) {
            const value = $('#' + field).val().trim();
            if (!value) {
                $('#' + field + '-error').text('Bu alan zorunludur').removeClass('hidden');
                $('#' + field).addClass('border-red-500');
                isValid = false;
            }
        });
        
        // Validate password match
        if ($('#password').val() !== $('#password_confirm').val()) {
            $('#password-confirm-error').text('Şifreler eşleşmiyor').removeClass('hidden');
            $('#password_confirm').addClass('border-red-500');
            isValid = false;
        }
        
        // Validate password strength
        if ($('#password').val().length < 8) {
            $('#password-error').text('Şifre en az 8 karakter olmalıdır').removeClass('hidden');
            $('#password').addClass('border-red-500');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('.border-red-500').first().offset().top - 100
            }, 500);
        } else {
            // Show loading state
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Kullanıcı Oluşturuluyor...');
        }
    });
});
</script>