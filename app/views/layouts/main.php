<?php
// Site ayarlarını getir
$settingsService = new SettingsService();
$siteTitle = $settingsService->getSiteTitle();
$siteTagline = $settingsService->getSiteTagline();
$siteDescription = $settingsService->getSiteDescription();
?>
<!DOCTYPE html>
<html lang="tr" class="scroll-smooth" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?><?= htmlspecialchars($siteTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($siteDescription) ?>">
    
    <?php 
    // SEO ayarları
    $seoSettings = $settingsService->getSeoSettings();
    if (!empty($seoSettings['meta_keywords'])): ?>
    <meta name="keywords" content="<?= htmlspecialchars($seoSettings['meta_keywords']) ?>">
    <?php endif; ?>
    
    <?php if (!empty($seoSettings['google_analytics'])): ?>
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($seoSettings['google_analytics']) ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?= htmlspecialchars($seoSettings['google_analytics']) ?>');
    </script>
    <?php endif; ?>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a'
                        },
                        secondary: {
                            500: '#6366f1',
                            600: '#4f46e5'
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'bounce-gentle': 'bounce 1s infinite'
                    }
                }
            }
        }
    </script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .editable-content {
            transition: all 0.3s ease;
            border-radius: 8px;
            position: relative;
        }
        
        /*.editable-content:hover {*/
        /*    background: rgba(59, 130, 246, 0.05);*/
        /*    outline: 2px dashed rgba(59, 130, 246, 0.3);*/
        /*}*/
        
        .editable-content[contenteditable="true"] {
            background: rgba(34, 197, 94, 0.05);
            outline: 2px solid rgba(34, 197, 94, 0.5);
            padding: 8px;
        }
        
        .edit-indicator {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #22c55e;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .editable-content[contenteditable="true"] .edit-indicator {
            opacity: 1;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Edit Mode Styles */
        .editable-content[contenteditable="true"] {
            border: 2px dashed #3b82f6;
            border-radius: 4px;
            padding: 8px;
            position: relative;
            min-height: 24px;
            background-color: rgba(59, 130, 246, 0.05);
            transition: all 0.3s ease;
        }
        
        .editable-content[contenteditable="true"]:hover {
            border-color: #2563eb;
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        .editable-content[contenteditable="true"]:focus {
            outline: none;
            border-color: #1d4ed8;
            background-color: rgba(59, 130, 246, 0.15);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .editable-content[contenteditable="true"]::after {
            content: '✏️';
            position: absolute;
            top: -10px;
            right: -10px;
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            z-index: 10;
            pointer-events: none;
        }
        
        /* Navigation edit mode styles */
        .nav-link.edit-mode {
            position: relative;
            cursor: pointer;
            background: rgba(59, 130, 246, 0.1) !important;
            border: 2px dashed #3b82f6 !important;
            pointer-events: auto !important;
        }
        
        .nav-link.edit-mode:hover {
            background: rgba(59, 130, 246, 0.2) !important;
            border-color: #2563eb !important;
        }
        
        .nav-link.edit-mode::after {
            content: '✏️ Düzenle';
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            background: #3b82f6;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            white-space: nowrap;
            z-index: 20;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .nav-link.edit-mode:hover::after {
            opacity: 1;
        }
        
        /* Add menu button */
        .add-menu-btn {
            background: #10b981;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .add-menu-btn:hover {
            background: #059669;
            transform: scale(1.1);
        }
        
        /* Disabled elements during edit mode */
        .disabled-for-edit {
            position: relative;
        }
        
        .disabled-for-edit::before {
            content: '🚫';
            position: absolute;
            top: 0;
            right: 0;
            background: rgba(239, 68, 68, 0.9);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            z-index: 5;
            pointer-events: none;
            transform: translate(50%, -50%);
        }
        
        .disabled-for-edit:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            white-space: nowrap;
            z-index: 10;
            margin-bottom: 5px;
        }
        
        /* Editable buttons and links in edit mode */
        .editable-button, .editable-link {
            position: relative;
            cursor: pointer !important;
        }
        
        .editable-button::after, .editable-link::after {
            content: '✏️ Düzenle';
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            background: #3b82f6;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            white-space: nowrap;
            z-index: 20;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .editable-button:hover::after, .editable-link:hover::after {
            opacity: 1;
        }
    </style>
    
    <?php if (isset($additional_css)): ?>
        <?= $additional_css ?>
    <?php endif; ?>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 dark:from-gray-900 dark:to-slate-800 min-h-screen transition-colors duration-300" id="body-element">
    <!-- Edit Mode Toggle (Admin Only) -->
    <?php if ($is_logged_in && $user && $user['role'] === 'admin'): ?>
        <div id="edit-toggle" class="fixed bottom-6 right-6 z-50">
            <button id="edit-mode-btn" class="bg-primary-600 hover:bg-primary-700 text-white p-4 rounded-full shadow-xl transition-all duration-300 hover:scale-110 group border-2 border-white">
                <i id="edit-icon" class="fas fa-lock text-xl"></i>
                <span id="edit-tooltip" class="absolute right-full mr-4 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-sm px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg">
                    Düzenleme Modunu Aç
                </span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <header class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 z-40 transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-4 flex-shrink-0">
                    <a href="/" class="flex items-center space-x-2 lg:space-x-3 group">
                        <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-code text-white text-sm lg:text-base"></i>
                        </div>
                        <h1 class="text-base md:text-lg lg:text-xl font-bold gradient-text dark:text-white editable-content" data-content-key="site_name">
                            <?= htmlspecialchars($siteTitle) ?>
                        </h1>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex lg:space-x-3 md:space-x-2 flex-1 justify-center items-center max-w-3xl mx-4">
                    <?php foreach ($navigation as $nav_item): ?>
                        <div class="relative group flex items-center">
                            <a href="<?= htmlspecialchars($nav_item['url']) ?>" 
                               class="nav-link inline-flex items-center px-3 lg:px-5 py-2 rounded-lg transition-all duration-200 text-sm lg:text-base <?= $nav_item['active'] ? 'bg-primary-500 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-primary-400' ?>"
                               data-nav-id="<?= $nav_item['id'] ?? '' ?>"
                               data-nav-title="<?= htmlspecialchars($nav_item['title']) ?>"
                               data-nav-url="<?= htmlspecialchars($nav_item['url']) ?>"
                               data-nav-role="<?= htmlspecialchars($nav_item['permission_role'] ?? 'all') ?>">
                                <?php if (!empty($nav_item['icon'])): ?>
                                    <i class="<?= htmlspecialchars($nav_item['icon']) ?> mr-2"></i>
                                <?php endif; ?>
                                <span class="nav-title whitespace-nowrap">
                                    <?= htmlspecialchars($nav_item['title']) ?>
                                </span>
                            </a>
                            
                            <!-- Dropdown menu for children -->
                            <?php if (!empty($nav_item['children'])): ?>
                                <div class="absolute top-full left-0 mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity z-50">
                                    <?php foreach ($nav_item['children'] as $child): ?>
                                        <a href="<?= htmlspecialchars($child['url']) ?>" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 first:rounded-t-lg last:rounded-b-lg">
                                            <?php if (!empty($child['icon'])): ?>
                                                <i class="<?= htmlspecialchars($child['icon']) ?> mr-2"></i>
                                            <?php endif; ?>
                                            <?= htmlspecialchars($child['title']) ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </nav>
                
                <!-- User Info / Auth (Desktop Only) -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Add Menu Button (Only visible in edit mode for admin) -->
                    <?php if ($is_logged_in && $user && $user['role'] === 'admin'): ?>
                        <button id="add-menu-btn" class="add-menu-btn hidden" title="Yeni Menü Öğesi Ekle">
                            <i class="fas fa-plus"></i>
                        </button>
                    <?php endif; ?>
                    
                    <!-- Theme Toggle -->
                    <button id="theme-toggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" title="Tema Değiştir">
                        <i id="theme-icon" class="fas fa-moon text-gray-700 dark:text-gray-300"></i>
                    </button>
                    
                    <?php if ($is_logged_in && $user): ?>
                        <!-- Profile Dropdown -->
                        <div class="relative" id="profile-dropdown">
                            <button class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" id="profile-button">
                                <?php if (!empty($user['avatar'])): ?>
                                    <img src="<?= htmlspecialchars($user['avatar']) ?>" 
                                         alt="Profil Fotoğrafı" 
                                         class="w-8 h-8 rounded-full object-cover">
                                <?php else: ?>
                                    <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-semibold">
                                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <div class="hidden lg:block">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($user['name']) ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><?= ucfirst($user['role']) ?></p>
                                </div>
                                <i class="fas fa-chevron-down text-sm text-gray-500 dark:text-gray-400 transition-transform duration-200" id="profile-chevron"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 z-50 hidden" id="profile-menu">
                                <div class="py-2">
                                    <a href="/logout" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        Çıkış Yap
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="flex space-x-2">
                            <a href="/login" class="bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-3 lg:px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 transition-colors">
                                <i class="fas fa-sign-in-alt lg:mr-2"></i>
                                <span class="hidden lg:inline">Giriş</span>
                            </a>
                            <a href="/register" class="bg-primary-500 hover:bg-primary-600 text-white px-3 lg:px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-user-plus lg:mr-2"></i>
                                <span class="hidden lg:inline">Kayıt</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors flex-shrink-0">
                    <i class="fas fa-bars text-gray-700 dark:text-gray-300 text-lg"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 hidden transition-colors duration-300">
            <div class="container mx-auto px-4 py-4 space-y-2">
                <!-- Navigation Links -->
                <?php foreach ($navigation as $nav_item): ?>
                    <a href="<?= htmlspecialchars($nav_item['url']) ?>" 
                       class="block px-4 py-3 rounded-lg transition-colors <?= $nav_item['active'] ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100' ?>">
                        <?php if (!empty($nav_item['icon'])): ?>
                            <i class="<?= htmlspecialchars($nav_item['icon']) ?> mr-2"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars($nav_item['title']) ?>
                    </a>
                    
                    <!-- Mobile dropdown children -->
                    <?php if (!empty($nav_item['children'])): ?>
                        <div class="ml-4 space-y-1">
                            <?php foreach ($nav_item['children'] as $child): ?>
                                <a href="<?= htmlspecialchars($child['url']) ?>" 
                                   class="block px-4 py-2 text-sm rounded-lg transition-colors text-gray-600 hover:bg-gray-50">
                                    <?php if (!empty($child['icon'])): ?>
                                        <i class="<?= htmlspecialchars($child['icon']) ?> mr-2"></i>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($child['title']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <!-- Mobile Theme Toggle -->
                <div class="pt-4 border-t border-gray-200">
                    <button id="mobile-theme-toggle" class="block w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg transition-colors text-center font-medium">
                        <i id="mobile-theme-icon" class="fas fa-moon mr-2"></i>
                        <span id="mobile-theme-text">Karanlık Mod</span>
                    </button>
                </div>
                
                <!-- Mobile Auth Section -->
                <div class="pt-4 border-t border-gray-200 space-y-2">
                    <?php if ($is_logged_in && $user): ?>
                        <!-- User Info -->
                        <div class="px-4 py-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold">
                                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($user['name']) ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?= ucfirst($user['role']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Logout Button -->
                        <a href="/logout" 
                           class="block px-4 py-3 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors text-center font-medium">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Çıkış Yap
                        </a>
                    <?php else: ?>
                        <!-- Login/Register Buttons -->
                        <a href="/login" 
                           class="block px-4 py-3 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg transition-colors text-center font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Giriş Yap
                        </a>
                        <a href="/register" 
                           class="block px-4 py-3 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors text-center font-medium">
                            <i class="fas fa-user-plus mr-2"></i>
                            Kayıt Ol
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Flash Messages -->
    <?php if (!empty($flash_messages)): ?>
        <div class="container mx-auto px-4 pt-4">
            <?php foreach ($flash_messages as $type => $messages): ?>
                <?php foreach ($messages as $message): ?>
                    <div class="mb-4 p-4 rounded-lg border animate-fade-in alert-<?= $type === 'error' ? 'danger' : $type ?>">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-<?= $type === 'error' ? 'exclamation-circle' : ($type === 'success' ? 'check-circle' : 'info-circle') ?> mr-3"></i>
                                <span><?= htmlspecialchars($message) ?></span>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="flex-1">
        <div class="container mx-auto px-4 py-8">
            <?= $content ?>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-20">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-code text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold"><?= htmlspecialchars($app_name) ?></h3>
                    </div>
                    <p class="text-gray-300 mb-4 editable-content" data-content-key="footer_description">
                        <?= htmlspecialchars($contentService->getContent('footer_description', 'Modern web teknolojileri, yazılım geliştirme ve dijital dünya hakkında güncel içerikler paylaşıyoruz.')) ?>
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-github text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-youtube text-xl"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Hızlı Linkler</h4>
                    <ul class="space-y-2">
                        <?php if (!empty($navigation)): ?>
                            <?php foreach ($navigation as $link): ?>
                                <li>
                                    <a href="<?= htmlspecialchars($link['url']) ?>" 
                                       class="text-gray-300 hover:text-white transition-colors flex items-center"
                                       <?= $link['target'] === '_blank' ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                                        <?php if (!empty($link['icon'])): ?>
                                            <i class="<?= htmlspecialchars($link['icon']) ?> mr-2 text-sm"></i>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($link['title']) ?>
                                        <?php if ($link['target'] === '_blank'): ?>
                                            <i class="fas fa-external-link-alt ml-1 text-xs opacity-60"></i>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Fallback links if no navigation items -->
                            <li><a href="/about" class="text-gray-300 hover:text-white transition-colors">Hakkımızda</a></li>
                            <li><a href="/contact" class="text-gray-300 hover:text-white transition-colors">İletişim</a></li>
                            <li><a href="/blog" class="text-gray-300 hover:text-white transition-colors">Blog</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">İletişim</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            <span class="editable-content" data-content-key="contact_email">
                                <?= htmlspecialchars($contentService->getContent('contact_email', 'info@teknolojiblog.com')) ?>
                            </span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-2"></i>
                            <span class="editable-content" data-content-key="contact_phone">
                                <?= htmlspecialchars($contentService->getContent('contact_phone', '+90 (555) 123 45 67')) ?>
                            </span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span class="editable-content" data-content-key="contact_address">
                                <?= htmlspecialchars($contentService->getContent('contact_address', 'İstanbul, Türkiye')) ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">

                    <span class="editable-content" data-content-key="footer_copyright">
                        <?= htmlspecialchars($contentService->getContent('footer_copyright', 'Tüm hakları saklıdır.')) ?>
                    </span>
                </p>
            </div>
        </div>
    </footer>
    
    <?php if (isset($additional_js)): ?>
        <?= $additional_js ?>
    <?php endif; ?>
    
    <!-- Alert Styles -->
    <style>
        .alert-success {
            @apply bg-green-50 border-green-200 text-green-800;
        }
        .alert-danger {
            @apply bg-red-50 border-red-200 text-red-800;
        }
        .alert-warning {
            @apply bg-yellow-50 border-yellow-200 text-yellow-800;
        }
        .alert-info {
            @apply bg-blue-50 border-blue-200 text-blue-800;
        }
    </style>
    
    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            // CSRF Token for AJAX requests
            window.csrfToken = '<?= htmlspecialchars($csrf_token) ?>';
            
            // Mobile menu toggle
            $('#mobile-menu-btn').click(function() {
                $('#mobile-menu').toggleClass('hidden');
                $(this).find('i').toggleClass('fa-bars fa-times');
            });
            
            // Close mobile menu when clicking outside or on links
            $(document).click(function(e) {
                if (!$(e.target).closest('#mobile-menu, #mobile-menu-btn').length) {
                    $('#mobile-menu').addClass('hidden');
                    $('#mobile-menu-btn i').removeClass('fa-times').addClass('fa-bars');
                }
            });
            
            // Close mobile menu when clicking on navigation links
            $('#mobile-menu a').click(function() {
                $('#mobile-menu').addClass('hidden');
                $('#mobile-menu-btn i').removeClass('fa-times').addClass('fa-bars');
            });
            
            // Alert auto-close
            setTimeout(function() {
                $('.alert-success, .alert-info').fadeOut(500);
            }, 5000);
            
            // Edit mode functionality
            let editMode = false;
            
            $('#edit-mode-btn').click(function() {
                editMode = !editMode;
                toggleEditMode(editMode);
            });
            
            function toggleEditMode(enabled) {
                const $btn = $('#edit-mode-btn');
                const $icon = $('#edit-icon');
                const $tooltip = $('#edit-tooltip');
                
                if (enabled) {
                    $btn.removeClass('bg-primary-600 hover:bg-primary-700').addClass('bg-green-600 hover:bg-green-700');
                    $icon.removeClass('fa-lock').addClass('fa-unlock');
                    $tooltip.text('Düzenleme Modunu Kapat');
                    
                    // Enable direct text editing for regular content
                    $('[data-content-key]:not(:has(a), :has(button))').each(function() {
                        // Store original classes
                        $(this).data('original-class', $(this).attr('class'));
                        
                        // Remove text-related classes and add editable class
                        let currentClass = $(this).attr('class') || '';
                        let cleanedClass = currentClass
                            .replace(/text-\S+/g, '') // Remove text-* classes (color, size, etc)
                            .replace(/gradient-text/g, '') // Remove gradient text
                            .replace(/dark:text-\S+/g, '') // Remove dark mode text classes
                            .replace(/\s+/g, ' ') // Clean up multiple spaces
                            .trim();
                        
                        $(this).attr('class', cleanedClass + ' editable-content');
                        $(this).attr('contenteditable', 'true');
                        
                        // Add basic editable styling
                        $(this).css({
                            'color': '#1f2937',
                            'background': 'rgba(255, 255, 255, 0.9)',
                            'padding': '4px 8px',
                            'border-radius': '6px',
                            'text-shadow': 'none'
                        });
                    });
                    
                    // Enable modal editing for buttons and links
                    $('button:not(#edit-mode-btn, #save-nav-edit, #cancel-nav-edit), a:not(.nav-link, .edit-mode)').each(function() {
                        if ($(this).find('[data-content-key]').length > 0 || $(this).attr('data-content-key')) {
                            $(this).addClass('editable-button editable-link');
                            $(this).on('click.modalEdit', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                openElementEditModal($(this));
                                return false;
                            });
                        }
                    });
                    
                    // Enable navigation editing
                    $('.nav-link').each(function() {
                        $(this).addClass('edit-mode');
                    });
                    
                    // Show add menu button
                    $('#add-menu-btn').removeClass('hidden');
                    
                    // Disable other buttons and links (except those with editable content)
                    $('button:not(#edit-mode-btn, #save-nav-edit, #cancel-nav-edit, #add-menu-btn), a:not(.nav-link, .edit-mode, [href^="#"])').each(function() {
                        // Skip if this element or its children contain editable content
                        if ($(this).find('[data-content-key]').length > 0 || $(this).attr('data-content-key') || $(this).closest('[data-content-key]').length > 0) {
                            return; // Skip this element from being disabled
                        }
                        
                        if (!$(this).hasClass('disabled-for-edit')) {
                            $(this).addClass('disabled-for-edit');
                            $(this).data('original-pointer-events', $(this).css('pointer-events'));
                            $(this).css({
                                'pointer-events': 'none',
                                'opacity': '0.6',
                                'cursor': 'not-allowed'
                            });
                            
                            // Add a tooltip to explain why disabled
                            if (!$(this).attr('title')) {
                                $(this).attr('title', 'Düzenleme modunda devre dışı');
                            }
                        }
                    });
                    
                    // Disable form submissions
                    $('form').each(function() {
                        if (!$(this).hasClass('disabled-for-edit')) {
                            $(this).addClass('disabled-for-edit');
                            $(this).on('submit.editMode', function(e) {
                                e.preventDefault();
                                showNotification('Düzenleme modunda formlar devre dışıdır!', 'warning');
                                return false;
                            });
                        }
                    });
                    
                    // Show notification
                    showNotification('Düzenleme modu aktif! İçeriklere ve menü öğelerine tıklayarak düzenleyebilirsiniz. Diğer işlemler devre dışı.', 'success');
                    
                } else {
                    $btn.removeClass('bg-green-600 hover:bg-green-700').addClass('bg-primary-600 hover:bg-primary-700');
                    $icon.removeClass('fa-unlock').addClass('fa-lock');
                    $tooltip.text('Düzenleme Modunu Aç');
                    
                    // Disable editing
                    $('[data-content-key]').each(function() {
                        // Restore original classes
                        const originalClass = $(this).data('original-class');
                        if (originalClass) {
                            $(this).attr('class', originalClass);
                        } else {
                            $(this).removeClass('editable-content');
                        }
                        
                        $(this).attr('contenteditable', 'false');
                        
                        // Clear inline styles
                        $(this).css({
                            'color': '',
                            'background': '',
                            'padding': '',
                            'border-radius': '',
                            'text-shadow': ''
                        });
                    });
                    
                    // Remove modal edit events from buttons and links
                    $('.editable-button, .editable-link').removeClass('editable-button editable-link').off('click.modalEdit');
                    
                    // Disable navigation editing
                    $('.nav-link').each(function() {
                        $(this).removeClass('edit-mode');
                    });
                    
                    // Hide add menu button
                    $('#add-menu-btn').addClass('hidden');
                    
                    // Re-enable buttons and links
                    $('.disabled-for-edit').each(function() {
                        $(this).removeClass('disabled-for-edit');
                        const originalPointerEvents = $(this).data('original-pointer-events') || 'auto';
                        $(this).css({
                            'pointer-events': originalPointerEvents,
                            'opacity': '1',
                            'cursor': 'auto'
                        });
                        $(this).removeAttr('title');
                    });
                    
                    // Re-enable form submissions
                    $('form').off('submit.editMode');
                    
                    showNotification('Düzenleme modu kapatıldı. Tüm işlemler aktif.', 'info');
                }
            }
            
            // Save content on blur
            $(document).on('blur', '[data-content-key][contenteditable="true"]', function() {
                const $element = $(this);
                const key = $element.data('content-key');
                
                // Temiz içeriği al (edit indicator'ları ve diğer ekstra HTML'leri kaldır)
                let value = $element.html();
                // Edit indicator div'lerini kaldır
                value = value.replace(/<div class="edit-indicator">.*?<\/div>/g, '');
                // Boş div'leri kaldır
                value = value.replace(/<div[^>]*><\/div>/g, '');
                // Trim whitespace
                value = value.trim();
                
                if (key) {
                    saveContent(key, value, $element);
                }
            });
            
            function saveContent(key, value, $element) {
                // Show saving indicator
                $element.addClass('opacity-50');
                
                $.ajax({
                    url: '/admin/update-content',
                    method: 'POST',
                    data: {
                        key: key,
                        value: value,
                        csrf_token: window.csrfToken
                    },
                    success: function(response) {
                        $element.removeClass('opacity-50');
                        if (response.success) {
                            // Show success indicator
                            $element.addClass('ring-2 ring-green-500');
                            setTimeout(function() {
                                $element.removeClass('ring-2 ring-green-500');
                            }, 1000);
                        } else {
                            showNotification('Kaydetme hatası: ' + response.message, 'error');
                        }
                    },
                    error: function() {
                        $element.removeClass('opacity-50');
                        showNotification('Kaydetme sırasında bir hata oluştu.', 'error');
                    }
                });
            }
            
            function showNotification(message, type) {
                const typeClasses = {
                    success: 'bg-green-50 border-green-200 text-green-800',
                    error: 'bg-red-50 border-red-200 text-red-800',
                    warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
                    info: 'bg-blue-50 border-blue-200 text-blue-800'
                };
                
                const icons = {
                    success: 'check-circle',
                    error: 'exclamation-circle',
                    warning: 'exclamation-triangle',
                    info: 'info-circle'
                };
                
                const $notification = $(`
                    <div class="fixed top-20 right-4 z-50 p-4 rounded-lg border animate-fade-in ${typeClasses[type]} max-w-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-${icons[type]} mr-3"></i>
                                <span>${message}</span>
                            </div>
                            <button onclick="$(this).closest('div').remove()" class="text-gray-500 hover:text-gray-700 ml-4">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `);
                
                $('body').append($notification);
                
                setTimeout(function() {
                    $notification.fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
            }
            
            // Navigation editing functionality - Click to edit
            $(document).on('click', '.nav-link.edit-mode', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const $navLink = $(this);
                const navId = $navLink.data('nav-id');
                const currentTitle = $navLink.data('nav-title');
                const currentUrl = $navLink.data('nav-url');
                const currentIcon = $navLink.find('i').attr('class') || '';
                const currentRole = $navLink.data('nav-role') || 'all';
                
                if (navId) {
                    openAdvancedNavEditModal(navId, currentTitle, currentUrl, currentIcon, currentRole, $navLink, false);
                }
            });
            
            // Add new menu item
            $(document).on('click', '#add-menu-btn', function(e) {
                e.preventDefault();
                openAdvancedNavEditModal('', '', '', '', 'all', null, true);
            });
            
            function openAdvancedNavEditModal(navId, currentTitle, currentUrl, currentIcon, currentRole, $navLink, isNew) {
                const iconOptions = [
                    { class: '', name: 'Icon Yok' },
                    { class: 'fas fa-home', name: 'Ana Sayfa' },
                    { class: 'fas fa-blog', name: 'Blog' },
                    { class: 'fas fa-info-circle', name: 'Hakkımızda' },
                    { class: 'fas fa-envelope', name: 'İletişim' },
                    { class: 'fas fa-user', name: 'Kullanıcı' },
                    { class: 'fas fa-users', name: 'Kullanıcılar' },
                    { class: 'fas fa-cog', name: 'Ayarlar' },
                    { class: 'fas fa-star', name: 'Yıldız' },
                    { class: 'fas fa-heart', name: 'Kalp' },
                    { class: 'fas fa-bookmark', name: 'Yer İmi' },
                    { class: 'fas fa-tag', name: 'Etiket' },
                    { class: 'fas fa-search', name: 'Arama' },
                    { class: 'fas fa-edit', name: 'Düzenle' },
                    { class: 'fas fa-plus', name: 'Ekle' }
                ];
                
                const roleOptions = [
                    { value: 'all', name: 'Herkes' },
                    { value: 'guest', name: 'Misafir' },
                    { value: 'user', name: 'Üye' },
                    { value: 'writer', name: 'Yazar' },
                    { value: 'admin', name: 'Admin' }
                ];
                
                const modalTitle = isNew ? 'Yeni Menü Öğesi Ekle' : 'Menü Öğesini Düzenle';
                const buttonText = isNew ? 'Ekle' : 'Güncelle';
                
                // Create modal HTML
                const modalHtml = `
                    <div id="nav-edit-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl">
                            <div class="p-6 border-b border-gray-200 relative">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-${isNew ? 'plus' : 'edit'} text-blue-500 mr-2"></i>
                                    ${modalTitle}
                                </h3>
                                ${!isNew ? `<button id="delete-nav-item" class="absolute top-6 right-6 text-red-500 hover:text-red-700 transition-colors p-2 rounded-lg hover:bg-red-50" title="Menü Öğesini Sil">
                                    <i class="fas fa-trash"></i>
                                </button>` : ''}
                            </div>
                            <form id="nav-edit-form" class="p-6 space-y-4">
                                <div>
                                    <label for="nav-title" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-heading mr-1"></i>Menü Başlığı
                                    </label>
                                    <input type="text" 
                                           id="nav-title" 
                                           name="title" 
                                           value="${currentTitle}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Menü başlığını girin"
                                           required>
                                </div>
                                <div>
                                    <label for="nav-url" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-link mr-1"></i>URL
                                    </label>
                                    <input type="text" 
                                           id="nav-url" 
                                           name="url" 
                                           value="${currentUrl}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="/sayfa-url"
                                           required>
                                </div>
                                <div>
                                    <label for="nav-icon" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-icons mr-1"></i>Icon
                                    </label>
                                    <select id="nav-icon" name="icon" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        ${iconOptions.map(option => `
                                            <option value="${option.class}" ${option.class === currentIcon ? 'selected' : ''}>
                                                ${option.name}
                                            </option>
                                        `).join('')}
                                    </select>
                                    <div id="icon-preview" class="mt-2 flex items-center">
                                        <span class="text-sm text-gray-600 mr-2">Önizleme:</span>
                                        <i id="preview-icon" class="${currentIcon || 'fas fa-question'} text-lg"></i>
                                    </div>
                                </div>
                                <div>
                                    <label for="nav-role" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user-shield mr-1"></i>Görünürlük
                                    </label>
                                    <select id="nav-role" name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        ${roleOptions.map(option => `
                                            <option value="${option.value}" ${option.value === currentRole ? 'selected' : ''}>
                                                ${option.name}
                                            </option>
                                        `).join('')}
                                    </select>
                                </div>
                                <input type="hidden" name="id" value="${navId}">
                                <input type="hidden" name="csrf_token" value="${window.csrfToken}">
                            </form>
                            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-end space-x-3">
                                <button type="button" 
                                        id="cancel-nav-edit" 
                                        class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium transition-colors">
                                    İptal
                                </button>
                                <button type="button" 
                                        id="save-nav-edit" 
                                        class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                                    <i class="fas fa-save mr-1"></i>${buttonText}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Add modal to page
                $('body').append(modalHtml);
                
                // Focus on title input
                $('#nav-title').focus().select();
                
                // Icon preview functionality
                $('#nav-icon').change(function() {
                    const selectedIcon = $(this).val();
                    $('#preview-icon').attr('class', selectedIcon || 'fas fa-question');
                });
                
                // Handle save
                $('#save-nav-edit').click(function() {
                    const newTitle = $('#nav-title').val().trim();
                    const newUrl = $('#nav-url').val().trim();
                    const newIcon = $('#nav-icon').val();
                    const newRole = $('#nav-role').val();
                    
                    if (!newTitle || !newUrl) {
                        showNotification('Başlık ve URL alanları zorunludur!', 'error');
                        return;
                    }
                    
                    // Show loading
                    $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Kaydediliyor...');
                    if ($navLink) $navLink.addClass('opacity-50');
                    
                    // Update or create navigation
                    const data = { title: newTitle, url: newUrl, icon: newIcon, permission_role: newRole };
                    const url = isNew ? '/admin/navigation/create' : '/admin/navigation/update';
                    
                    handleNavigation(url, navId, data, $navLink, function() {
                        $('#nav-edit-modal').remove();
                        if (isNew) {
                            // Reload page to show new menu item
                            location.reload();
                        }
                    });
                });
                
                // Handle delete (only for existing items)
                if (!isNew) {
                    $('#delete-nav-item').click(function() {
                        if (confirm('Bu menü öğesini silmek istediğinizden emin misiniz?')) {
                            deleteNavigation(navId, $navLink, function() {
                                $('#nav-edit-modal').remove();
                            });
                        }
                    });
                }
                
                // Handle cancel
                $('#cancel-nav-edit, #nav-edit-modal').click(function(e) {
                    if (e.target === this) {
                        $('#nav-edit-modal').remove();
                    }
                });
                
                // Handle Enter key
                $('#nav-edit-form input').keypress(function(e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        $('#save-nav-edit').click();
                    }
                });
                
                // Handle Escape key
                $(document).on('keydown.navEdit', function(e) {
                    if (e.keyCode === 27) {
                        $('#nav-edit-modal').remove();
                        $(document).off('keydown.navEdit');
                    }
                });
            }
            
            function handleNavigation(url, navId, data, $navLink, callback) {
                const requestData = { ...data, csrf_token: window.csrfToken };
                if (navId) requestData.id = navId;
                
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: requestData,
                    success: function(response) {
                        if (response.success) {
                            if ($navLink) {
                                // Update existing navigation item
                                if (data.title) {
                                    $navLink.find('.nav-title').text(data.title);
                                    $navLink.data('nav-title', data.title);
                                }
                                
                                if (data.url) {
                                    $navLink.attr('href', data.url);
                                    $navLink.data('nav-url', data.url);
                                }
                                
                                if (data.icon !== undefined) {
                                    const $icon = $navLink.find('i').first();
                                    if (data.icon) {
                                        if ($icon.length) {
                                            $icon.attr('class', data.icon + ' mr-2');
                                        } else {
                                            $navLink.find('.nav-title').before(`<i class="${data.icon} mr-2"></i>`);
                                        }
                                    } else {
                                        $icon.remove();
                                    }
                                }
                                
                                $navLink.removeClass('opacity-50');
                            }
                            
                            showNotification(navId ? 'Menü başarıyla güncellendi!' : 'Yeni menü öğesi eklendi!', 'success');
                            
                            // Execute callback (close modal)
                            if (callback) callback();
                        } else {
                            if ($navLink) $navLink.removeClass('opacity-50');
                            $('#save-nav-edit').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>' + (navId ? 'Güncelle' : 'Ekle'));
                            showNotification('İşlem hatası: ' + response.message, 'error');
                        }
                    },
                    error: function() {
                        if ($navLink) $navLink.removeClass('opacity-50');
                        $('#save-nav-edit').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>' + (navId ? 'Güncelle' : 'Ekle'));
                        showNotification('Bağlantı hatası oluştu.', 'error');
                    }
                });
            }
            
            function deleteNavigation(navId, $navLink, callback) {
                $.ajax({
                    url: `/admin/navigation/delete/${navId}`,
                    method: 'POST',
                    data: { csrf_token: window.csrfToken },
                    success: function(response) {
                        if (response.success) {
                            if ($navLink) {
                                $navLink.fadeOut(300, function() {
                                    $(this).remove();
                                });
                            }
                            showNotification('Menü öğesi silindi!', 'success');
                            if (callback) callback();
                        } else {
                            showNotification('Silme hatası: ' + response.message, 'error');
                        }
                    },
                    error: function() {
                        showNotification('Bağlantı hatası oluştu.', 'error');
                    }
                });
            }
            
            // Modal edit functionality for buttons and links
            function openElementEditModal($element) {
                // Get editable content key and current values
                let contentKey = '';
                let currentText = '';
                let currentHref = '';
                
                // Find data-content-key either on element or its children
                if ($element.attr('data-content-key')) {
                    contentKey = $element.attr('data-content-key');
                    currentText = $element.text().trim();
                } else {
                    const $editableChild = $element.find('[data-content-key]').first();
                    if ($editableChild.length > 0) {
                        contentKey = $editableChild.attr('data-content-key');
                        currentText = $editableChild.text().trim();
                    }
                }
                
                // Get href if it's a link
                if ($element.is('a')) {
                    currentHref = $element.attr('href') || '';
                }
                
                if (!contentKey) return;
                
                // Create modal HTML
                const isLink = $element.is('a');
                const modalHtml = `
                    <div id="element-edit-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-edit text-blue-500 mr-2"></i>
                                    ${isLink ? 'Link Düzenle' : 'Button Düzenle'}
                                </h3>
                            </div>
                            <form id="element-edit-form" class="p-6 space-y-4">
                                <div>
                                    <label for="element-text" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-font mr-1"></i>Metin
                                    </label>
                                    <input type="text" 
                                           id="element-text" 
                                           name="text" 
                                           value="${currentText}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Metin girin"
                                           required>
                                </div>
                                ${isLink ? `
                                <div>
                                    <label for="element-href" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-link mr-1"></i>Link URL
                                    </label>
                                    <input type="text" 
                                           id="element-href" 
                                           name="href" 
                                           value="${currentHref}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="/sayfa-url">
                                </div>
                                ` : ''}
                                <input type="hidden" name="key" value="${contentKey}">
                                <input type="hidden" name="csrf_token" value="${window.csrfToken}">
                            </form>
                            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-end space-x-3">
                                <button type="button" 
                                        id="cancel-element-edit" 
                                        class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium transition-colors">
                                    İptal
                                </button>
                                <button type="button" 
                                        id="save-element-edit" 
                                        class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                                    <i class="fas fa-save mr-1"></i>Kaydet
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Add modal to page
                $('body').append(modalHtml);
                
                // Focus on text input
                $('#element-text').focus().select();
                
                // Handle save
                $('#save-element-edit').click(function() {
                    const newText = $('#element-text').val().trim();
                    const newHref = $('#element-href').val().trim();
                    
                    if (!newText) {
                        showNotification('Metin alanı boş olamaz!', 'error');
                        return;
                    }
                    
                    // Show loading
                    $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Kaydediliyor...');
                    
                    // Update content
                    updateElementContent(contentKey, newText, newHref, $element, isLink, function() {
                        $('#element-edit-modal').remove();
                    });
                });
                
                // Handle cancel
                $('#cancel-element-edit, #element-edit-modal').click(function(e) {
                    if (e.target === this) {
                        $('#element-edit-modal').remove();
                    }
                });
                
                // Handle Enter key
                $('#element-edit-form input').keypress(function(e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        $('#save-element-edit').click();
                    }
                });
                
                // Handle Escape key
                $(document).on('keydown.elementEdit', function(e) {
                    if (e.keyCode === 27) {
                        $('#element-edit-modal').remove();
                        $(document).off('keydown.elementEdit');
                    }
                });
            }
            
            function updateElementContent(contentKey, newText, newHref, $element, isLink, callback) {
                // Update content via AJAX
                $.ajax({
                    url: '/admin/update-content',
                    method: 'POST',
                    data: {
                        key: contentKey,
                        value: newText,
                        csrf_token: window.csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update element immediately
                            if ($element.attr('data-content-key')) {
                                $element.text(newText);
                            } else {
                                $element.find('[data-content-key]').text(newText);
                            }
                            
                            // Update href if it's a link
                            if (isLink && newHref) {
                                $element.attr('href', newHref);
                            }
                            
                            showNotification('İçerik başarıyla güncellendi!', 'success');
                            
                            // Execute callback (close modal)
                            if (callback) callback();
                        } else {
                            $('#save-element-edit').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Kaydet');
                            showNotification('Güncelleme hatası: ' + response.message, 'error');
                        }
                    },
                    error: function() {
                        $('#save-element-edit').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Kaydet');
                        showNotification('Bağlantı hatası oluştu.', 'error');
                    }
                });
            }
            
            // Theme toggle functionality
            initThemeToggle();
        });
        
        function initThemeToggle() {
            // Get theme from localStorage or default to light
            const currentTheme = localStorage.getItem('theme') || 'light';
            
            // Apply theme on page load
            applyTheme(currentTheme);
            
            // Desktop theme toggle
            $('#theme-toggle').click(function() {
                toggleTheme();
            });
            
            // Mobile theme toggle
            $('#mobile-theme-toggle').click(function() {
                toggleTheme();
            });
            
            function toggleTheme() {
                const currentTheme = localStorage.getItem('theme') || 'light';
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                localStorage.setItem('theme', newTheme);
                applyTheme(newTheme);
            }
            
            function applyTheme(theme) {
                const htmlElement = document.getElementById('html-root');
                const themeIcon = document.getElementById('theme-icon');
                const mobileThemeIcon = document.getElementById('mobile-theme-icon');
                const mobileThemeText = document.getElementById('mobile-theme-text');
                
                if (theme === 'dark') {
                    htmlElement.classList.add('dark');
                    if (themeIcon) {
                        themeIcon.classList.remove('fa-moon');
                        themeIcon.classList.add('fa-sun');
                    }
                    if (mobileThemeIcon) {
                        mobileThemeIcon.classList.remove('fa-moon');
                        mobileThemeIcon.classList.add('fa-sun');
                    }
                    if (mobileThemeText) {
                        mobileThemeText.textContent = 'Aydınlık Mod';
                    }
                } else {
                    htmlElement.classList.remove('dark');
                    if (themeIcon) {
                        themeIcon.classList.remove('fa-sun');
                        themeIcon.classList.add('fa-moon');
                    }
                    if (mobileThemeIcon) {
                        mobileThemeIcon.classList.remove('fa-sun');
                        mobileThemeIcon.classList.add('fa-moon');
                    }
                    if (mobileThemeText) {
                        mobileThemeText.textContent = 'Karanlık Mod';
                    }
                }
            }
        }
        
        // Profile Dropdown Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profileButton = document.getElementById('profile-button');
            const profileMenu = document.getElementById('profile-menu');
            const profileChevron = document.getElementById('profile-chevron');
            
            if (profileButton && profileMenu) {
                profileButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = profileMenu.classList.contains('hidden');
                    
                    if (isHidden) {
                        profileMenu.classList.remove('hidden');
                        profileChevron.style.transform = 'rotate(180deg)';
                    } else {
                        profileMenu.classList.add('hidden');
                        profileChevron.style.transform = 'rotate(0deg)';
                    }
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
                        profileMenu.classList.add('hidden');
                        profileChevron.style.transform = 'rotate(0deg)';
                    }
                });
            }
        });
    </script>
</body>
</html>