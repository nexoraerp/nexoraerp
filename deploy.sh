#!/usr/bin/env bash

set -euo pipefail

APP_NAME="nexora"
TIMESTAMP="$(date +%Y%m%d-%H%M%S)"
ZIP_NAME="${APP_NAME}-production-${TIMESTAMP}.zip"

echo "Nexora ERP deployment paketi hazırlanıyor..."

if [ ! -f artisan ] || [ ! -f composer.json ] || [ ! -f package.json ]; then
    echo "Hata: Bu script Laravel proje kökünde çalıştırılmalı."
    exit 1
fi

echo "1/4 Frontend production build hazırlanıyor..."
npm run build

echo "2/4 Composer autoload optimize ediliyor..."
composer dump-autoload --optimize

echo "3/4 Laravel cache temizleniyor..."
php artisan optimize:clear

echo "4/4 Zip dosyası oluşturuluyor: ${ZIP_NAME}"
zip -rq "${ZIP_NAME}" \
    app \
    bootstrap \
    config \
    database \
    public \
    resources \
    routes \
    storage \
    vendor \
    artisan \
    composer.json \
    composer.lock \
    package.json \
    package-lock.json \
    vite.config.js \
    tailwind.config.js \
    postcss.config.js \
    .htaccess \
    -x \
    "*.env" \
    ".env" \
    ".git/*" \
    "node_modules/*" \
    "tests/*" \
    "public/hot" \
    "storage/logs/*" \
    "storage/framework/cache/*" \
    "storage/framework/sessions/*" \
    "storage/framework/views/*" \
    "*.zip" \
    ".DS_Store"

echo ""
echo "Hazır: ${ZIP_NAME}"
echo ""
echo "Canlı sunucuda zip çıkarıldıktan sonra çalıştır:"
echo "php artisan migrate --force"
echo "php artisan optimize:clear"
echo "php artisan optimize"
echo ""
echo "Not: Canlı .env dosyasını ezme. MAIL_MAILER=log kalırsa mail gönderilmez."
