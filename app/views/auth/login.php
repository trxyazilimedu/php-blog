<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 to-blue-50 p-4">
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover-lift">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-8 py-10 text-center text-white">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-sign-in-alt text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">Hoş Geldiniz</h2>
                <p class="text-white/90 text-sm">Hesabınıza giriş yapın</p>
            </div>
            
            <!-- Body -->
            <div class="p-8">
                <!-- Error Message -->
                <?php if (!empty($error)): ?>
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3"></i>
                            <span class="text-sm"><?= htmlspecialchars($error) ?></span>
                        </div>
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
                
                <form method="POST" class="space-y-6">
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
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Şifre</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Şifrenizi girin" 
                               required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="remember" 
                                   name="remember"
                                   class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500">
                            <label for="remember" class="ml-2 text-sm text-gray-600">Beni hatırla</label>
                        </div>
                        <a href="/auth/forgot-password" class="text-sm text-primary-600 hover:text-primary-700 transition-colors">
                            Şifrenizi mi unuttunuz?
                        </a>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-primary-500 to-secondary-500 text-white py-3 px-6 rounded-lg font-semibold hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Giriş Yap
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
                
                <!-- Register Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Hesabınız yok mu? 
                        <a href="/register" class="text-primary-600 hover:text-primary-700 font-semibold transition-colors">
                            Hemen kayıt olun
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>