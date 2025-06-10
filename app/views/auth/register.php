<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 to-blue-50 p-4">
    <div class="w-full max-w-2xl">
        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover-lift">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-8 py-10 text-center text-white">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">Kayıt Olun</h2>
                <p class="text-white/90 text-sm">Hemen ücretsiz hesap oluşturun</p>
            </div>
            
            <!-- Body -->
            <div class="p-8">
                <!-- Error Messages -->
                <?php if (!empty($errors)): ?>
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <!-- Success Message -->
                <?php if (!empty($success)): ?>
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3"></i>
                            <span class="text-sm"><?= htmlspecialchars($success) ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="space-y-4">
                    <!-- Name and Email Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Ad Soyad</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" 
                                   placeholder="Adınız ve soyadınız" 
                                   required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-posta Adresi</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                                   placeholder="ornek@email.com" 
                                   required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                    
                    <!-- Password and Password Confirm Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Şifre</label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Güçlü bir şifre seçin" 
                                   required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                            <p class="text-xs text-gray-500 mt-1">En az 6 karakter olmalıdır</p>
                            <div id="password-strength" class="mt-2 text-xs"></div>
                        </div>
                        
                        <!-- Password Confirm -->
                        <div>
                            <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">Şifre Tekrar</label>
                            <input type="password" 
                                   id="password_confirm" 
                                   name="password_confirm" 
                                   placeholder="Şifrenizi tekrar girin" 
                                   required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                            <div id="password-match" class="mt-1 text-xs"></div>
                        </div>
                    </div>
                    
                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Hesap Türü</label>
                        <select id="role" 
                                name="role"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                            <option value="user" <?= (($_POST['role'] ?? '') === 'user') ? 'selected' : '' ?>>Okuyucu</option>
                            <option value="writer" <?= (($_POST['role'] ?? '') === 'writer') ? 'selected' : '' ?>>Yazar</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Yazar olarak kayıt olursanız admin onayı gereklidir</p>
                    </div>
                    
                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Biyografi (isteğe bağlı)</label>
                        <textarea id="bio" 
                                  name="bio" 
                                  rows="3" 
                                  placeholder="Kendiniz hakkında kısa bilgi"
                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none"><?= htmlspecialchars($_POST['bio'] ?? '') ?></textarea>
                    </div>
                    
                    <!-- Terms -->
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" 
                               id="terms" 
                               name="terms" 
                               required
                               class="mt-1 w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500">
                        <label for="terms" class="text-sm text-gray-600 leading-5">
                            <a href="/terms" target="_blank" class="text-primary-600 hover:text-primary-700 underline">Kullanım şartlarını</a> ve 
                            <a href="/privacy" target="_blank" class="text-primary-600 hover:text-primary-700 underline">gizlilik politikasını</a> kabul ediyorum
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-primary-500 to-secondary-500 text-white py-3 px-6 rounded-lg font-semibold hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-user-plus mr-2"></i>
                        Hesap Oluştur
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="my-6 relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">veya</span>
                    </div>
                </div>
                
                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Zaten hesabınız var mı? 
                        <a href="/login" class="text-primary-600 hover:text-primary-700 font-semibold transition-colors">
                            Giriş yapın
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthDiv = document.getElementById('password-strength');
    
    if (password.length === 0) {
        strengthDiv.textContent = '';
        return;
    }
    
    let strength = 0;
    if (password.length >= 6) strength++;
    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    if (strength <= 2) {
        strengthDiv.textContent = 'Zayıf şifre';
        strengthDiv.className = 'mt-2 text-xs text-red-600';
    } else if (strength <= 3) {
        strengthDiv.textContent = 'Orta güçte şifre';
        strengthDiv.className = 'mt-2 text-xs text-yellow-600';
    } else {
        strengthDiv.textContent = 'Güçlü şifre';
        strengthDiv.className = 'mt-2 text-xs text-green-600';
    }
});

// Password match checker
document.getElementById('password_confirm').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    const matchDiv = document.getElementById('password-match');
    
    if (confirmPassword.length === 0) {
        matchDiv.textContent = '';
        return;
    }
    
    if (password === confirmPassword) {
        matchDiv.textContent = '✓ Şifreler eşleşiyor';
        matchDiv.className = 'mt-1 text-xs text-green-600';
    } else {
        matchDiv.textContent = '✗ Şifreler eşleşmiyor';
        matchDiv.className = 'mt-1 text-xs text-red-600';
    }
});
</script>