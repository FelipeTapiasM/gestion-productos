#!/bin/sh
set -e

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Running seeders..."
php artisan db:seed --force

echo "==> Creating storage link..."
php artisan storage:link || true

echo "==> Clearing caches..."
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo "==> Starting FrankenPHP on port ${PORT:-80}..."
exec frankenphp run --config /app/Caddyfile --adapter caddyfile