#!/usr/bin/env bash

set -euo pipefail

APP_NAME="nexora"
TIMESTAMP="$(date +%Y%m%d-%H%M%S)"
ZIP_NAME="${APP_NAME}-production-${TIMESTAMP}.zip"

echo "========================================"
echo "Nexora ERP deployment paketi hazırlanıyor"
echo "========================================"

if [ ! -f "artisan" ] || [ ! -f "composer.json" ] || [ ! -f "package.json" ]; then
    echo "HATA: Bu script Laravel proje kökünde çalıştırılmalı."
    exit 1
fi

echo ""
echo "1/5 public/hot temizleniyor..."
rm -f public/hot

echo ""
echo "2/5 Frontend production build hazırlanıyor..."
npm run build

if [ ! -f "public/build/manifest.json" ]; then
    echo "HATA: public/build/manifest.json oluşturulamadı."
    exit 1
fi

echo ""
echo "3/5 Composer autoload optimize ediliyor..."
composer dump-autoload --optimize

echo ""
echo "4/5 Laravel testleri kontrol ediliyor..."
if php artisan list --raw | grep -qx "test"; then
    echo "Laravel testleri çalıştırılıyor..."
    php artisan test
else
    echo "Laravel test komutu bulunamadı, test adımı atlandı."
fi

echo ""
echo "5/5 Zip dosyası oluşturuluyor: ${ZIP_NAME}"
rm -f nexora-production-*.zip

zip -rq "${ZIP_NAME}" \
    app \
    bootstrap \
    config \
    database/migrations \
    database/seeders \
    public \
    resources \
    routes \
    vendor \
    artisan \
    composer.json \
    composer.lock \
    -x \
    "*.env" \
    ".env" \
    ".git/*" \
    "node_modules/*" \
    "tests/*" \
    "public/hot" \
    "database/database.sqlite" \
    "storage/*" \
    "bootstrap/cache/config.php" \
    "bootstrap/cache/events.php" \
    "bootstrap/cache/routes-*.php" \
    "storage/logs/*" \
    "storage/framework/cache/*" \
    "storage/framework/sessions/*" \
    "storage/framework/views/*" \
    "*.zip" \
    "*/.DS_Store" \
    ".DS_Store"

echo ""
echo "========================================"
echo "HAZIR: ${ZIP_NAME}"
echo "========================================"
echo ""
echo "cPanel sırası:"
echo "1. ZIP'i /home/nexoraer/nexora içine yükle."
echo "2. Aynı klasöre çıkar ve üzerine yaz."
echo "3. nexora/public/hot olmadığını kontrol et."
echo "4. public_html/build klasörünü sil."
echo "5. nexora/public/build klasörünü public_html içine kopyala."
echo "6. Yeni migration varsa migrate çalıştır."
echo "7. Cache temizle/yenile: php artisan optimize:clear && php artisan optimize"
echo "8. Siteyi sert yenile ve test et."
echo ""
echo "Not: Canlı .env dosyasını ezme. MAIL_MAILER=log kalırsa mail gönderilmez."
