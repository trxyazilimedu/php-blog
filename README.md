# 🚀 Simple PHP Framework

Modern ve güçlü bir PHP framework örneği. BaseController, Service katmanı ve gelişmiş özelliklerle donatılmış MVC mimarisi.

## ✨ Özellikler

### 🏗️ **Temel Mimari**
- **MVC Mimarisi** - Model, View, Controller ayrımı
- **BaseController** - Tüm controller'lar için ortak fonksiyonalite
- **Service Katmanı** - İş mantığı için ayrı servis sınıfları
- **Layout Sistemi** - Merkezi layout yönetimi
- **PDO Veritabanı** - Güvenli ve modern veritabanı erişimi

### 🔒 **Güvenlik**
- **CSRF Koruması** - Cross-Site Request Forgery koruması
- **SQL Injection Koruması** - PDO prepared statements
- **Password Hashing** - Güvenli şifre saklama
- **Session Yönetimi** - Güvenli oturum kontrolü
- **Input Validation** - Kapsamlı form doğrulama

### 🎯 **Gelişmiş Özellikler**
- **Flash Messages** - Tek kullanımlık mesaj sistemi
- **Global Data** - Tüm view'larda erişilebilir veriler
- **Authentication Service** - Kullanıcı giriş/çıkış yönetimi
- **Validation Service** - Esnek doğrulama kuralları
- **Autoloading** - Otomatik sınıf yükleme
- **API Support** - JSON response desteği

## 📁 Dosya Yapısı

```
simple-framework/
├── app/
│   ├── controllers/         # Controller sınıfları
│   │   ├── HomeController.php
│   │   └── UserController.php
│   ├── models/             # Model sınıfları
│   │   ├── User.php
│   │   └── Contact.php
│   ├── services/           # Service katmanı
│   │   ├── AuthService.php
│   │   ├── SessionService.php
│   │   ├── ValidationService.php
│   │   └── FlashMessageService.php
│   ├── views/              # View dosyaları
│   │   ├── layouts/        # Layout dosyaları
│   │   ├── home/          # Ana sayfa view'ları
│   │   └── users/         # Kullanıcı view'ları
│   └── config/            # Konfigürasyon
│       ├── app.php        # Ana konfigürasyon
│       └── routes.php     # Route tanımları
├── core/                  # Framework çekirdeği
│   ├── App.php           # Ana uygulama sınıfı
│   ├── BaseController.php # Temel controller
│   ├── Database.php      # Veritabanı sınıfı
│   └── Model.php         # Temel model sınıfı
├── public/               # Web erişilebilir dosyalar
│   ├── index.php         # Ana giriş noktası
│   └── .htaccess         # URL yönlendirme
├── database.sql          # Veritabanı şeması
└── start-server.sh       # Test sunucusu
```

## 🚀 Kurulum

### 1. Projeyi İndirin
```bash
git clone <repo-url> simple-framework
cd simple-framework
```

### 2. Veritabanını Kurun
```sql
mysql -u root -p < database.sql
```

### 3. Konfigürasyonu Ayarlayın
`app/config/app.php` dosyasında veritabanı bilgilerinizi güncelleyin:

```php
'database' => [
    'host' => 'localhost',
    'database' => 'simple_framework',
    'username' => 'root',
    'password' => 'your_password',
    'charset' => 'utf8mb4'
]
```

### 4. Test Sunucusunu Başlatın
```bash
# Linux/Mac
./start-server.sh

# Manuel
cd public && php -S localhost:8000
```

## 🎯 Kullanım Örnekleri

### Controller Oluşturma

```php
<?php

class ExampleController extends BaseController
{
    public function index()
    {
        // Global veri ekleme
        $this->addGlobalData('page_description', 'Örnek sayfa');
        
        // Service kullanımı
        $authService = $this->service('auth');
        
        // Validation
        $validator = $this->service('validation');
        $rules = [
            'email' => 'required|email',
            'name' => 'required|min:2|max:100'
        ];
        
        if ($validator->validate($_POST, $rules)) {
            // Geçerli veri
        } else {
            // Hatalar: $validator->getErrors()
        }
        
        // Flash mesaj
        $this->flash('success', 'İşlem başarılı!');
        
        // View render etme
        $data = ['page_title' => 'Örnek Sayfa'];
        $this->view('example/index', $data);
    }
}
```

### Model Oluşturma

```php
<?php

class Product extends Model
{
    protected $table = 'products';
    
    public function getActiveProducts()
    {
        return $this->query(
            "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY name"
        );
    }
    
    public function searchProducts($term)
    {
        return $this->query(
            "SELECT * FROM {$this->table} WHERE name LIKE ? OR description LIKE ?",
            ["%{$term}%", "%{$term}%"]
        );
    }
}
```

### Service Oluşturma

```php
<?php

class EmailService
{
    private $config;
    
    public function __construct()
    {
        $this->config = require APP_PATH . '/config/app.php';
    }
    
    public function sendWelcomeEmail($user)
    {
        // E-posta gönderme mantığı
        return true;
    }
    
    public function sendResetPassword($email, $token)
    {
        // Şifre sıfırlama e-postası
        return true;
    }
}
```

## 🔧 Ana Özellikler

### BaseController Özellikleri

- **Global Data Yönetimi**: Tüm view'larda kullanılacak veriler
- **Service Erişimi**: `$this->service('serviceName')`
- **Flash Messages**: `$this->flash('type', 'message')`
- **Authentication**: `$this->requireAuth()`, `$this->requireAdmin()`
- **CSRF Protection**: Otomatik token oluşturma ve doğrulama
- **Layout System**: Otomatik layout ile view render etme

### Validation Kuralları

- `required` - Zorunlu alan
- `email` - Geçerli e-posta
- `min:n` - Minimum karakter
- `max:n` - Maksimum karakter
- `numeric` - Sayısal değer
- `unique:table,column` - Benzersizlik kontrolü
- `confirmed` - Doğrulama alanı
- `in:val1,val2` - Belirli değerler

### Global Data Kullanımı

Tüm view'larda otomatik olarak mevcut:

```php
$app_name        // Uygulama adı
$app_version     // Versiyon
$current_year    // Mevcut yıl
$current_url     // Mevcut URL
$is_logged_in    // Giriş durumu
$user           // Giriş yapan kullanıcı
$navigation     // Menü öğeleri
$csrf_token     // CSRF token
$flash_messages // Flash mesajlar
```

## 🎪 Demo Özellikleri

### Ana Sayfalar
- **Ana Sayfa** (`/`) - Framework tanıtımı
- **Hakkında** (`/about`) - Framework bilgileri
- **İletişim** (`/contact`) - Form ile mesaj gönderme

### Kullanıcı Yönetimi
- **Kullanıcı Listesi** (`/users`) - Tüm kullanıcıları görüntüleme
- **Kullanıcı Detayı** (`/users/show/id`) - Detaylı bilgiler
- **Kullanıcı Oluşturma** (`/users/create`) - Yeni kullanıcı ekleme
- **Kullanıcı Düzenleme** (`/users/edit/id`) - Bilgi güncelleme
- **Profil** (`/users/profile`) - Kişisel profil sayfası

### API Endpoints
- **Kullanıcı API** (`/users/api`) - JSON formatında kullanıcı listesi

## 🛡️ Güvenlik Özellikleri

- **PDO Prepared Statements** - SQL injection koruması
- **CSRF Token** - Form güvenliği
- **Password Hashing** - Güvenli şifre saklama
- **Session Security** - Güvenli oturum yönetimi
- **Input Validation** - Kapsamlı veri doğrulama
- **XSS Protection** - htmlspecialchars ile çıktı temizleme

## 🔄 Geliştirme

### Yeni Controller Ekleme
1. `app/controllers/` klasöründe yeni PHP dosyası oluşturun
2. `BaseController`'dan extend edin
3. İhtiyaç duyduğunuz method'ları ekleyin

### Yeni Service Ekleme
1. `app/services/` klasöründe yeni PHP dosyası oluşturun
2. `BaseController`'da `initializeServices()` method'unda tanımlayın
3. `$this->service('serviceName')` ile erişin

### View Oluşturma
1. `app/views/` klasöründe ilgili klasörü oluşturun
2. PHP dosyası olarak view'ınızı yazın
3. Layout kullanmak için `$this->view('folder/file', $data)` kullanın

## 📚 Daha Fazla Bilgi

Bu framework eğitim amaçlı geliştirilmiştir. Production ortamında kullanmadan önce:

- Daha kapsamlı güvenlik testleri yapın
- Error handling'i geliştirin
- Logging sistemi ekleyin
- Cache mekanizması entegre edin
- Unit testler yazın

## 🤝 Katkıda Bulunma

1. Fork edin
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Commit edin (`git commit -m 'Add amazing feature'`)
4. Push edin (`git push origin feature/amazing-feature`)
5. Pull Request oluşturun

## 📄 Lisans

Bu proje MIT lisansı ile lisanslanmıştır.

---

**🎉 Framework'ünüz hazır! Test etmeye başlayabilirsiniz.**
