#!/bin/bash
set -e

echo "🔄 Running migrations..."
php artisan migrate --force

echo "🔄 Running config cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🔄 Starting queue worker in background..."
php artisan queue:work --tries=3 --timeout=90 &

echo "🚀 Starting PHP-FPM or Artisan serve..."
php artisan serve --host=0.0.0.0 --port=$PORT