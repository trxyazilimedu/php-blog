<!-- Profile Edit Header -->
<div class="bg-gradient-to-r from-blue-600 via-purple-500 to-blue-700 text-white rounded-2xl p-8 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-user-edit mr-3"></i>Profili Düzenle
            </h1>
            <p class="text-white/80">Kişisel bilgilerinizi güncelleyin</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-edit text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <form method="POST" id="profile-edit-form" enctype="multipart/form-data">
                    <!-- Form Header -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                            Kişisel Bilgiler
                        </h2>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Personal Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="Adınız ve soyadınız"
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
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="ornek@email.com"
                                       required>
                            </div>
                        </div>
                        
                        <!-- Bio -->
                        <div class="space-y-2">
                            <label for="bio" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-info-circle mr-2 text-primary-500"></i>Hakkınızda
                            </label>
                            <textarea id="bio" 
                                      name="bio" 
                                      rows="4"
                                      maxlength="500"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                                      placeholder="Kendiniz hakkında birkaç kelime..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                            <div class="flex justify-between">
                                <p class="text-sm text-gray-500">Profil sayfanızda gösterilecek</p>
                                <div id="bio-counter" class="text-sm text-gray-500">
                                    <?= strlen($user['bio'] ?? '') ?> / 500 karakter
                                </div>
                            </div>
                        </div>
                        
                        <!-- Profile Image Upload -->
                        <div class="space-y-2">
                            <label for="avatar" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-camera mr-2 text-purple-500"></i>Profil Fotoğrafı
                            </label>
                            <div class="flex items-center space-x-6">
                                <!-- Current Avatar Preview -->
                                <div class="flex-shrink-0">
                                    <?php if (!empty($user['avatar'])): ?>
                                        <img src="<?= htmlspecialchars($user['avatar']) ?>" 
                                             alt="Profil Fotoğrafı" 
                                             class="w-16 h-16 rounded-full object-cover border-2 border-gray-200"
                                             id="avatar-preview">
                                    <?php else: ?>
                                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-xl font-bold border-2 border-gray-200"
                                             id="avatar-preview">
                                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Upload Controls -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <label for="avatar" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg cursor-pointer font-medium transition-colors">
                                            <i class="fas fa-upload mr-2"></i>
                                            Fotoğraf Seç
                                        </label>
                                        <input type="file" 
                                               id="avatar" 
                                               name="avatar" 
                                               accept="image/*"
                                               class="hidden"
                                               onchange="previewAvatar(this)">
                                        
                                        <?php if (!empty($user['avatar'])): ?>
                                        <form method="POST" action="/profile/delete-avatar" class="inline">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                            <button type="submit" 
                                                    onclick="return confirm('Profil fotoğrafınızı silmek istediğinizden emin misiniz?')"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                                <i class="fas fa-trash mr-2"></i>
                                                Sil
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-2">
                                        JPG, PNG, GIF veya WebP. Maksimum 2MB.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Password Section -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-key mr-2 text-orange-500"></i>Şifre Değiştir
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">Şifrenizi değiştirmek istemiyorsanız bu alanları boş bırakın.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Password -->
                                <div class="space-y-2">
                                    <label for="password" class="block text-sm font-medium text-gray-700">
                                        <i class="fas fa-lock mr-2 text-orange-500"></i>Yeni Şifre
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="password" 
                                               name="password" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12"
                                               placeholder="En az 6 karakter...">
                                        <button type="button" 
                                                onclick="togglePassword('password')"
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-eye" id="password-eye"></i>
                                        </button>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <div id="password-strength" class="flex items-center space-x-2 hidden">
                                            <div class="flex space-x-1">
                                                <div class="w-2 h-2 rounded-full bg-gray-200" id="strength-1"></div>
                                                <div class="w-2 h-2 rounded-full bg-gray-200" id="strength-2"></div>
                                                <div class="w-2 h-2 rounded-full bg-gray-200" id="strength-3"></div>
                                                <div class="w-2 h-2 rounded-full bg-gray-200" id="strength-4"></div>
                                            </div>
                                            <span id="strength-text">Şifre gücü</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Confirm Password -->
                                <div class="space-y-2">
                                    <label for="password_confirm" class="block text-sm font-medium text-gray-700">
                                        <i class="fas fa-lock mr-2 text-orange-500"></i>Şifre Tekrarı
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="password_confirm" 
                                               name="password_confirm" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12"
                                               placeholder="Şifreyi tekrar girin...">
                                        <button type="button" 
                                                onclick="togglePassword('password_confirm')"
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-eye" id="password_confirm-eye"></i>
                                        </button>
                                    </div>
                                    <div id="password-match" class="text-sm text-green-600 hidden">
                                        <i class="fas fa-check mr-1"></i>Şifreler eşleşiyor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                        <div class="flex items-center space-x-4">
                            <a href="/profile" class="text-gray-600 hover:text-gray-800 font-medium">
                                <i class="fas fa-arrow-left mr-2"></i>Profile Dön
                            </a>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <a href="/profile" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
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
        
        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Current Avatar -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-user-circle mr-2 text-purple-500"></i>
                        Profil Resmi
                    </h3>
                </div>
                <div class="p-6 text-center">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?= htmlspecialchars($user['avatar']) ?>" 
                             alt="Profil Fotoğrafı" 
                             class="w-24 h-24 rounded-full object-cover mx-auto mb-4 border-4 border-purple-200">
                        <p class="text-sm text-gray-600 mb-4">
                            Mevcut profil fotoğrafınız
                        </p>
                    <?php else: ?>
                        <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-3xl font-bold text-white mx-auto mb-4">
                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            Şu anda başharflerinizi kullanıyoruz
                        </p>
                    <?php endif; ?>
                    <p class="text-xs text-gray-500">
                        Sol taraftaki formdan yeni fotoğraf yükleyebilirsiniz
                    </p>
                </div>
            </div>
            
            <!-- Account Info -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-info-circle mr-2 text-green-500"></i>
                        Hesap Bilgileri
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Kullanıcı ID</span>
                        <span class="font-mono text-sm text-gray-900">#<?= $user['id'] ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Rol</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                            <?php
                            $roleNames = [
                                'admin' => 'Yönetici',
                                'writer' => 'Yazar', 
                                'user' => 'Kullanıcı'
                            ];
                            echo $roleNames[$user['role']] ?? ucfirst($user['role']);
                            ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Durum</span>
                        <span class="px-2 py-1 <?= ($user['status'] ?? 'active') === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> rounded text-xs font-medium">
                            <?= ($user['status'] ?? 'active') === 'active' ? 'Aktif' : 'Pasif' ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Üyelik</span>
                        <span class="text-sm text-gray-900">
                            <?php 
                                $days = floor((time() - strtotime($user['created_at'])) / (60 * 60 * 24));
                                echo $days . ' gün';
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Security Tips -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-red-50 to-orange-50 p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-shield-alt mr-2 text-red-500"></i>
                        Güvenlik İpuçları
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                            <span>Güçlü bir şifre kullanın (en az 8 karakter)</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                            <span>Şifrenizi başkalarıyla paylaşmayın</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                            <span>E-posta adresinizi güncel tutun</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                            <span>Şüpheli aktiviteleri bildirin</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Avatar preview function
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // File size check (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Dosya boyutu çok büyük! Maksimum 2MB olmalıdır.');
            input.value = '';
            return;
        }
        
        // File type check
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Desteklenmeyen dosya türü! Sadece JPG, PNG, GIF ve WebP dosyaları yükleyebilirsiniz.');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            preview.innerHTML = `<img src="${e.target.result}" alt="Önizleme" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">`;
        };
        reader.readAsDataURL(file);
    }
}

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
        const strengthDiv = $('#password-strength');
        
        if (password.length === 0) {
            strengthDiv.addClass('hidden');
            return;
        }
        
        strengthDiv.removeClass('hidden');
        
        let strength = 0;
        
        if (password.length >= 6) strength++;
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
        let strengthText = 'Çok Zayıf';
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
        
        if (password.length > 0 && confirm.length > 0) {
            if (password === confirm) {
                matchDiv.removeClass('hidden');
                $(this).removeClass('border-red-500');
            } else {
                matchDiv.addClass('hidden');
                $(this).addClass('border-red-500');
            }
        } else {
            matchDiv.addClass('hidden');
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
    $('#profile-edit-form').submit(function(e) {
        let isValid = true;
        
        // Clear previous errors
        $('input, textarea').removeClass('border-red-500');
        
        // Validate required fields
        const name = $('#name').val().trim();
        const email = $('#email').val().trim();
        
        if (!name) {
            $('#name').addClass('border-red-500');
            isValid = false;
        }
        
        if (!email) {
            $('#email').addClass('border-red-500');
            isValid = false;
        }
        
        // Validate password match if passwords are entered
        const password = $('#password').val();
        const passwordConfirm = $('#password_confirm').val();
        
        if (password || passwordConfirm) {
            if (password !== passwordConfirm) {
                $('#password, #password_confirm').addClass('border-red-500');
                isValid = false;
            }
            
            if (password.length > 0 && password.length < 6) {
                $('#password').addClass('border-red-500');
                isValid = false;
            }
        }
        
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