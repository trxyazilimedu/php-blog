<div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <div class="w-20 h-20 mx-auto bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mb-6">
            <i class="fas fa-envelope text-3xl text-white"></i>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($page_title ?? 'İletişim') ?></h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Bizimle iletişime geçmek için aşağıdaki formu kullanabilirsiniz. Size en kısa sürede dönüş yapacağız.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contact Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-xl p-8 hover-lift">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Mesaj Gönderin</h2>
                
                <form method="POST" action="/contact" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Adınız <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="<?= htmlspecialchars($old_data['name'] ?? old('name')) ?>"
                               required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                               placeholder="Adınızı girin">
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            E-posta Adresiniz <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="<?= htmlspecialchars($old_data['email'] ?? old('email')) ?>"
                               required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                               placeholder="ornek@email.com">
                    </div>
                    
                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            Konu
                        </label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               value="<?= htmlspecialchars($old_data['subject'] ?? old('subject')) ?>"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                               placeholder="Mesajınızın konusu">
                    </div>
                    
                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Mesajınız <span class="text-red-500">*</span>
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="6" 
                                  required
                                  placeholder="Mesajınızı buraya yazın... (En az 10 karakter)"
                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none"><?= htmlspecialchars($old_data['message'] ?? old('message')) ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 10 karakter gereklidir.</p>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-primary-500 to-secondary-500 text-white py-3 px-6 rounded-lg font-semibold hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Mesajı Gönder
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Contact Info Sidebar -->
        <div class="space-y-6">
            <!-- Contact Information -->
            <div class="bg-white rounded-2xl shadow-xl p-6 hover-lift">
                <h3 class="text-xl font-bold text-gray-900 mb-4">İletişim Bilgileri</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-primary-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">E-posta</p>
                            <p class="text-gray-600 text-sm">info@simpleframework.com</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-primary-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Telefon</p>
                            <p class="text-gray-600 text-sm">+90 (555) 123 45 67</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-primary-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Adres</p>
                            <p class="text-gray-600 text-sm">İstanbul, Türkiye</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-primary-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Çalışma Saatleri</p>
                            <p class="text-gray-600 text-sm">Pzt-Cum 09:00-18:00</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Response Time -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl shadow-xl p-6 text-white">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bolt text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold">Hızlı Yanıt</h3>
                </div>
                <p class="text-white/90 text-sm">
                    Mesajlarınıza genellikle 24 saat içinde yanıt veriyoruz. Acil durumlar için telefon numaramızı kullanabilirsiniz.
                </p>
            </div>
            
            <!-- Social Media -->
            <div class="bg-white rounded-2xl shadow-xl p-6 hover-lift">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Sosyal Medya</h3>
                <p class="text-gray-600 text-sm mb-4">Bizi sosyal medyada takip edin:</p>
                <div class="flex space-x-3">
                    <a href="#" class="w-10 h-10 bg-blue-100 hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fab fa-twitter text-blue-600"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-blue-100 hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fab fa-linkedin text-blue-600"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fab fa-github text-gray-700"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-red-100 hover:bg-red-200 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fab fa-youtube text-red-600"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="mt-16">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Sık Sorulan Sorular</h2>
            <p class="text-gray-600">Merak ettiğiniz soruların cevapları burada olabilir.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-2">Ne kadar sürede yanıt alırım?</h3>
                <p class="text-gray-600 text-sm">Genellikle 24 saat içinde tüm mesajlara yanıt veriyoruz.</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-2">Hangi konularda yardım alabilir?</h3>
                <p class="text-gray-600 text-sm">Teknik destek, genel sorular ve iş birlikleri için bizimle iletişime geçebilirsiniz.</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-2">Telefon desteği var mı?</h3>
                <p class="text-gray-600 text-sm">Evet, çalışma saatleri içinde telefon desteği sağlıyoruz.</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-2">Mesajım gizli kalır mı?</h3>
                <p class="text-gray-600 text-sm">Elbette, tüm iletişimleriniz gizli tutulur ve üçüncü taraflarla paylaşılmaz.</p>
            </div>
        </div>
    </div>
</div>