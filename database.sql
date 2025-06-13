-- Veritabanı Şeması - Simple Framework
-- Oluşturma Tarihi: 2025-01-10

-- Veritabanı oluştur
CREATE DATABASE IF NOT EXISTS simple_framework CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE simple_framework;

-- Users tablosu
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'writer', 'user') DEFAULT 'user',
    status ENUM('active', 'inactive', 'pending') DEFAULT 'active',
    bio TEXT,
    avatar VARCHAR(255),
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- Blog Categories tablosu (yeni blog sistemi)
CREATE TABLE blog_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    color VARCHAR(7) DEFAULT '#007bff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Blog Posts tablosu (yeni blog sistemi)
CREATE TABLE blog_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content LONGTEXT NOT NULL,
    excerpt TEXT,
    author_id INT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    views INT DEFAULT 0,
    featured_image VARCHAR(255),
    meta_title VARCHAR(255),
    meta_description TEXT,
    published_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Blog Post Categories pivot tablosu (many-to-many ilişki)
CREATE TABLE blog_post_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    post_id INT NOT NULL,
    category_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE CASCADE,
    UNIQUE KEY unique_post_category (post_id, category_id)
);

-- Blog Comments tablosu
CREATE TABLE blog_comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    post_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    website VARCHAR(255),
    comment TEXT NOT NULL,
    status ENUM('pending', 'approved', 'spam', 'rejected') DEFAULT 'pending',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE
);

-- Blog Post Views tablosu (IP-based tracking için)
CREATE TABLE blog_post_views (
    id INT PRIMARY KEY AUTO_INCREMENT,
    post_id INT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    session_id VARCHAR(255),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
    INDEX idx_post_ip_session (post_id, ip_address, session_id),
    INDEX idx_created_at (created_at)
);

-- Contact Messages tablosu
CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Site Content tablosu (dinamik içerik yönetimi)
CREATE TABLE site_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    content_key VARCHAR(255) UNIQUE NOT NULL,
    content_value LONGTEXT,
    content_type ENUM('text', 'html', 'json', 'markdown') DEFAULT 'html',
    page VARCHAR(100),
    section VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- İndeksler
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_status ON users(status);


CREATE INDEX idx_blog_posts_slug ON blog_posts(slug);
CREATE INDEX idx_blog_posts_status ON blog_posts(status);
CREATE INDEX idx_blog_posts_published_at ON blog_posts(published_at);
CREATE INDEX idx_blog_posts_author_id ON blog_posts(author_id);

CREATE INDEX idx_blog_categories_slug ON blog_categories(slug);

CREATE INDEX idx_blog_comments_post_id ON blog_comments(post_id);
CREATE INDEX idx_blog_comments_status ON blog_comments(status);

CREATE INDEX idx_contact_messages_status ON contact_messages(status);

CREATE INDEX idx_site_content_key ON site_content(content_key);
CREATE INDEX idx_site_content_page ON site_content(page);

-- Varsayılan veriler
INSERT INTO users (name, email, password, role, status) VALUES
('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active'),
('Writer User', 'writer@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'writer', 'active');


INSERT INTO blog_categories (name, slug, description) VALUES
('PHP', 'php', 'PHP programlama dili hakkında'),
('JavaScript', 'javascript', 'JavaScript ve frontend teknolojileri'),
('Veritabanı', 'veritabani', 'Veritabanı yönetimi ve optimizasyon'),
('Framework', 'framework', 'Çeşitli framework\'ler hakkında');

INSERT INTO site_content (content_key, content_value, content_type, page, section) VALUES
('site_title', 'Simple Framework Blog', 'text', 'global', 'header'),
('site_description', 'Modern ve basit blog framework\'ü', 'text', 'global', 'meta'),
('footer_text', '© 2025 Simple Framework. Tüm hakları saklıdır.', 'html', 'global', 'footer'),
('about_title', 'Hakkımızda', 'text', 'about', 'main'),
('about_content', '<p>Bu site Simple Framework ile geliştirilmiştir.</p>', 'html', 'about', 'main'),
('contact_title', 'İletişim', 'text', 'contact', 'main'),
('contact_content', '<p>Bizimle iletişime geçmek için aşağıdaki formu kullanabilirsiniz.</p>', 'html', 'contact', 'main');

-- Navigation menu tablosu
CREATE TABLE navigation_menu (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    url VARCHAR(500) NOT NULL,
    icon VARCHAR(100),
    parent_id INT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    target ENUM('_self', '_blank') DEFAULT '_self',
    permission_role ENUM('all', 'user', 'writer', 'admin') DEFAULT 'all',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES navigation_menu(id) ON DELETE CASCADE
);

-- Default navigation menu items
INSERT INTO navigation_menu (title, url, icon, sort_order, permission_role) VALUES
('Ana Sayfa', '/', 'fas fa-home', 1, 'all'),
('Hakkında', '/about', 'fas fa-info-circle', 2, 'all'),
('İletişim', '/contact', 'fas fa-envelope', 3, 'all'),
('Blog', '/blog', 'fas fa-blog', 4, 'all'),
('Admin Panel', '/admin', 'fas fa-cog', 5, 'admin'),
('Blog Yaz', '/blog/create', 'fas fa-pen', 6, 'writer'),
('Profil', '/profile', 'fas fa-user', 7, 'user');

-- Sample blog categories
INSERT INTO blog_categories (name, slug, description, color) VALUES
('Teknoloji', 'teknoloji', 'Teknoloji dünyasından haberler ve gelişmeler', '#3b82f6'),
('Yazılım', 'yazilim', 'Yazılım geliştirme, programlama dilleri ve araçları', '#10b981'),
('Web Geliştirme', 'web-gelistirme', 'Frontend ve backend web geliştirme teknikleri', '#f59e0b'),
('Mobil', 'mobil', 'Mobil uygulama geliştirme ve mobile teknolojiler', '#8b5cf6'),
('Yapay Zeka', 'yapay-zeka', 'AI, makine öğrenmesi ve derin öğrenme', '#ef4444'),
('DevOps', 'devops', 'DevOps araçları, CI/CD ve sistem yönetimi', '#06b6d4'),
('Güvenlik', 'guvenlik', 'Siber güvenlik, veri koruması ve güvenlik protokolleri', '#84cc16'),
('Veritabanı', 'veritabani', 'Veritabanı tasarımı, optimizasyon ve yönetimi', '#f97316');

-- Site Settings tablosu
CREATE TABLE site_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(255) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'boolean', 'email', 'url', 'json') DEFAULT 'text',
    description TEXT,
    category VARCHAR(100) DEFAULT 'general',
    is_sensitive BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Default site settings
INSERT INTO site_settings (setting_key, setting_value, setting_type, description, category) VALUES
-- Genel Ayarlar
('site_title', 'Teknoloji Bloğum', 'text', 'Site başlığı', 'general'),
('site_tagline', 'Modern Teknoloji Blogu', 'text', 'Site sloganı', 'general'),
('site_description', 'Teknoloji, yazılım ve dijital dünya hakkında güncel içerikler.', 'text', 'Site açıklaması (SEO)', 'general'),

-- SMTP Ayarları
('smtp_host', '', 'text', 'SMTP sunucu adresi', 'email'),
('smtp_port', '587', 'number', 'SMTP port numarası', 'email'),
('smtp_username', '', 'email', 'SMTP kullanıcı adı', 'email'),
('smtp_password', '', 'text', 'SMTP şifre', 'email'),
('smtp_encryption', 'tls', 'text', 'SMTP şifreleme türü', 'email'),
('smtp_from_name', 'Teknoloji Bloğum', 'text', 'E-posta gönderen adı', 'email'),

-- Sistem Ayarları
('timezone', 'Europe/Istanbul', 'text', 'Varsayılan zaman dilimi', 'system'),
('date_format', 'd.m.Y', 'text', 'Tarih format', 'system'),
('upload_max_size', '10', 'number', 'Maksimum dosya boyutu (MB)', 'system'),
('posts_per_page', '10', 'number', 'Sayfa başına post sayısı', 'system'),
('maintenance_mode', '0', 'boolean', 'Bakım modu durumu', 'system'),

-- SEO Ayarları
('meta_keywords', 'teknoloji, yazılım, blog, programlama, web geliştirme', 'text', 'Ana anahtar kelimeler', 'seo'),
('google_analytics', '', 'text', 'Google Analytics ID', 'seo'),
('google_search_console', '', 'text', 'Google Search Console doğrulama', 'seo'),

-- Sosyal Medya
('twitter_url', '', 'url', 'Twitter profil linki', 'social'),
('linkedin_url', '', 'url', 'LinkedIn profil linki', 'social'),
('github_url', '', 'url', 'GitHub profil linki', 'social'),
('youtube_url', '', 'url', 'YouTube kanal linki', 'social');