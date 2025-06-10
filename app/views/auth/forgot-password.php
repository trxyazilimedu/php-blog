<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 to-blue-50 p-4">
    <div class="w-full max-w-md">
        <!-- Forgot Password Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover-lift">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-8 py-10 text-center text-white">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-key text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">Şifremi Unuttum</h2>
                <p class="text-white/90 text-sm">Şifre sıfırlama bağlantısı göndereceğiz</p>
            </div>
            
            <!-- Body -->
            <div class="p-8">
                <!-- Success Message -->
                <?php if (!empty($success)): ?>
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3"></i>
                            <span class="text-sm"><?= htmlspecialchars($success) ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Error Message -->
                <?php if (!empty($error)): ?>
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3"></i>
                            <span class="text-sm"><?= htmlspecialchars($error) ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Info Message -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle mr-3 mt-0.5"></i>
                        <div class="text-sm">
                            <p class="font-medium mb-1">Şifre sıfırlama nasıl çalışır?</p>
                            <p>E-posta adresinizi girin, size şifre sıfırlama bağlantısı gönderelim. Bu bağlantı 24 saat geçerli olacaktır.</p>
                        </div>
                    </div>
                </div>
                
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
                        <p class="text-xs text-gray-500 mt-1">Kayıtlı e-posta adresinizi girin</p>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-primary-500 to-secondary-500 text-white py-3 px-6 rounded-lg font-semibold hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Sıfırlama Bağlantısı Gönder
                    </button>
                </form>
                
                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Şifrenizi hatırladınız mı? 
                        <a href="/login" class="text-primary-600 hover:text-primary-700 font-semibold transition-colors">
                            Giriş yapın
                        </a>
                    </p>
                </div>
                
                <!-- Additional Help -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="text-center">
                        <p class="text-xs text-gray-500 mb-2">Yardıma mı ihtiyacınız var?</p>
                        <a href="/contact" class="text-xs text-primary-600 hover:text-primary-700 transition-colors">
                            Bizimle iletişime geçin
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Security Notice -->
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-shield-alt text-yellow-600 mr-3 mt-0.5"></i>
                <div class="text-sm text-yellow-800">
                    <p class="font-medium mb-1">Güvenlik Hatırlatması</p>
                    <p>Şifre sıfırlama e-postası sadece kayıtlı e-posta adresinize gönderilir. E-postadaki bağlantıya tıklamadan önce gönderen adresini kontrol edin.</p>
                </div>
            </div>
        </div>
    </div>
</div>