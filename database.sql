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