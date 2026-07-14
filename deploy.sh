#!/usr/bin/env bash

set -euo pipefail

APP_NAME="nexora"
TIMESTAMP="$(date +%Y%m%d-%H%M%S)"
ZIP_NAME="${APP_NAME}-production-${TIMESTAMP}.zip"

echo "Nexora ERP deployment paketi hazırlanıyor..."
echo "=========================================="

if [ ! -f artisan ] || [ ! -f composer.json ] || [ ! -f package.json ]; then
    echo "Hata: Bu script Laravel proje kökünde çalıştırılmalı."
    exit 1
fi

echo ""
echo "1/5 Vite geliştirme dosyası temizleniyor..."
rm -f public/hot

echo ""
echo "2/5 Frontend production build hazırlanıyor..."
npm run build

if [ ! -f public/build/manifest.json ]; then
    echo "Hata: public/build/manifest.json oluşturulamadı."
    exit 1
fi

echo ""
echo "3/5 Composer autoload optimize ediliyor..."
composer dump-autoload --optimize

echo ""
echo "4/5 Laravel cache temizleniyor..."
php artisan optimize:clear

if php artisan list --raw | grep -qx "test"; then
    echo ""
    echo "Laravel testleri çalıştırılıyor..."
    php artisan test
else
    echo ""
    echo "Laravel test komutu bulunamadı, test adımı atlandı."
fi

echo ""
echo "Eski deployment zip dosyaları temizleniyor..."
rm -f nexora-production-*.zip

echo ""
echo "5/5 Zip dosyası oluşturuluyor: ${ZIP_NAME}"
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
echo "=========================================="
echo "Hazır: ${ZIP_NAME}"
echo "=========================================="
echo ""
echo "cPanel yayın sırası:"
echo "1. ZIP'i /home/nexoraer/nexora dizinine yükle."
echo "2. ZIP'i aynı dizine çıkar ve üzerine yaz."
echo "3. /home/nexoraer/nexora/public/hot olmadığını kontrol et."
echo "4. public_html/build klasörünü sil."
echo "5. nexora/public/build klasörünü public_html içine kopyala."
echo "6. Yeni migration varsa canlıda çalıştır: php artisan migrate --force"
echo "7. Cache temizle/yenile: php artisan optimize:clear && php artisan optimize"
echo "8. Siteyi gizli sekmede veya sert yenilemeyle test et."
echo ""
echo "Not: Canlı .env dosyasını ezme. MAIL_MAILER=log kalırsa mail gönderilmez."
