#!/bin/bash

echo "🚀 Simple Framework Test Sunucusu Başlatılıyor..."
echo "📁 Klasör: $(pwd)/public"
echo "🌐 URL: http://localhost:8000"
echo ""
echo "Sunucuyu durdurmak için Ctrl+C kullanın"
echo ""

cd public
php -S localhost:8000
