<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?><?= htmlspecialchars($app_name) ?></title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info span {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .btn {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-outline {
            background: transparent;
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
        }
        
        .btn-outline:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        /* Navigation */
        .nav {
            background: white;
            padding: 1rem 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .nav ul {
            list-style: none;
            display: flex;
            gap: 2rem;
        }
        
        .nav li a {
            color: #555;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .nav li a:hover {
            background: #f0f0f0;
            color: #333;
        }
        
        .nav li a.active {
            background: #667eea;
            color: white;
        }
        
        /* Main Content */
        .main {
            padding: 2rem 0;
            min-height: calc(100vh - 200px);
        }
        
        .content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Alert Messages */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 6px;
            position: relative;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border-color: #ffeaa7;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-color: #bee5eb;
        }
        
        .alert .close {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            opacity: 0.6;
        }
        
        .alert .close:hover {
            opacity: 1;
        }
        
        /* Footer */
        .footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: auto;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav ul {
                flex-wrap: wrap;
                gap: 1rem;
            }
            
            .content {
                padding: 1rem;
            }
        }
    </style>
    
    <?php if (isset($additional_css)): ?>
        <?= $additional_css ?>
    <?php endif; ?>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1><?= htmlspecialchars($app_name) ?></h1>
                </div>
                
                <div class="user-info">
                    <?php if ($is_logged_in && $user): ?>
                        <span>Hoş geldin, <?= htmlspecialchars($user['name']) ?>!</span>
                        <a href="/logout" class="btn btn-outline">Çıkış</a>
                    <?php else: ?>
                        <a href="/login" class="btn btn-outline">Giriş</a>
                        <a href="/register" class="btn btn-outline">Kayıt</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Navigation -->
    <nav class="nav">
        <div class="container">
            <ul>
                <?php foreach ($navigation as $nav_item): ?>
                    <li>
                        <a href="<?= htmlspecialchars($nav_item['url']) ?>" 
                           <?= $nav_item['active'] ? 'class="active"' : '' ?>>
                            <?= htmlspecialchars($nav_item['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <!-- Flash Messages -->
            <?php if (!empty($flash_messages)): ?>
                <?php foreach ($flash_messages as $type => $messages): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?>">
                            <button type="button" class="close" onclick="this.parentElement.remove()">&times;</button>
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <div class="content">
                <!-- Page Content -->
                <?= $content ?>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= $current_year ?> <?= htmlspecialchars($app_name) ?> - v<?= htmlspecialchars($app_version) ?></p>
        </div>
    </footer>
    
    <?php if (isset($additional_js)): ?>
        <?= $additional_js ?>
    <?php endif; ?>
    
    <script>
        // CSRF Token for AJAX requests
        window.csrfToken = '<?= htmlspecialchars($csrf_token) ?>';
        
        // Alert auto-close
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                if (!alert.querySelector('.close:hover')) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
    </script>
</body>
</html>
