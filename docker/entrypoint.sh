#!/bin/sh
set -e

case "${APP_KEY:-}" in
    base64:*)
        ;;
    *)
        export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
        ;;
esac

php artisan storage:link || true
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground
