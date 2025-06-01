#!/bin/bash

echo "🚀 Simple Framework Test Sunucusu Başlatılıyor..."
echo "📁 Klasör: $(pwd)/public"
echo "🌐 URL: http://localhost:8000"
echo ""
echo "📋 Test URL'leri:"
echo "✅ Ana Sayfa: http://localhost:8000/"
echo "✅ Hakkında: http://localhost:8000/about"
echo "✅ İletişim: http://localhost:8000/contact"
echo "✅ Kullanıcılar: http://localhost:8000/users"
echo "✅ API: http://localhost:8000/users/api"
echo ""
echo "🔧 Debug Modu: http://localhost:8000/users?debug=1"
echo ""

# PHP extension kontrolü
echo "🔍 PHP Extension Kontrolü:"
if php -m | grep -q "pdo_mysql"; then
    echo "✅ PDO MySQL: Yüklü"
else
    echo "⚠️  PDO MySQL: Yüklü değil"
fi

if php -m | grep -q "pdo_sqlite"; then
    echo "✅ PDO SQLite: Yüklü (Framework SQLite kullanacak)"
else
    echo "❌ PDO SQLite: Yüklü değil"
    echo "⚠️  Lütfen PHP PDO extension'ı yükleyin:"
    echo "   Ubuntu/Debian: sudo apt-get install php-mysql php-sqlite3"
    echo "   CentOS/RHEL: sudo yum install php-mysql php-pdo"
    exit 1
fi

echo ""
echo "Sunucuyu durdurmak için Ctrl+C kullanın"
echo ""

cd public
php -S localhost:8000 router.php
