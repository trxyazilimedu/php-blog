# Simple PHP Framework

Bu, PDO veritabanı bağlantısı ile basit bir PHP framework'üdür.

## Kurulum

1. Projeyi web sunucunuzun document root klasörüne kopyalayın
2. `app/config/app.php` dosyasında veritabanı ayarlarını düzenleyin
3. Web sunucunuzun document root'unu `public` klasörüne yönlendirin

## Veritabanı Kurulumu

Örnek kullanıcı tablosu:

```sql
CREATE DATABASE simple_framework;
USE simple_framework;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Kullanım

### Controller Oluşturma

```php
<?php

class ExampleController extends Controller
{
    public function index()
    {
        $data = ['title' => 'Örnek Sayfa'];
        $this->view('example/index', $data);
    }
}
```

### Model Oluşturma

```php
<?php

class Example extends Model
{
    protected $table = 'examples';
    
    public function customMethod()
    {
        return $this->query("SELECT * FROM {$this->table} WHERE status = ?", ['active']);
    }
}
```

### View Oluşturma

View dosyaları `app/views/` klasöründe bulunur ve PHP dosyalarıdır.

## Framework Özellikleri

- MVC mimarisi
- PDO ile güvenli veritabanı bağlantısı
- Otomatik sınıf yükleme
- Basit routing sistemi
- Singleton veritabanı bağlantısı
- Temel CRUD işlemleri

## Dosya Yapısı

```
simple-framework/
├── app/
│   ├── controllers/     # Controller sınıfları
│   ├── models/         # Model sınıfları
│   ├── views/          # View dosyaları
│   └── config/         # Konfigürasyon dosyaları
├── core/               # Framework çekirdek dosyaları
└── public/             # Genel erişilebilir dosyalar
    └── index.php       # Ana giriş noktası
```

## Örnek Kullanım

Framework'ü test etmek için:

1. Web sunucunuzu başlatın
2. Tarayıcınızda projenize gidin
3. Ana sayfa, hakkında ve iletişim sayfalarını test edin

## Geliştirme

Yeni controller, model veya view eklemek için ilgili klasörlerde dosya oluşturun ve mevcut örnekleri takip edin.
