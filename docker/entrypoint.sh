#!/bin/sh
set -e

if [ "${APP_SERVICE_ROLE:-web}" = "queue" ]; then
    exec php artisan queue:work database --sleep=2 --tries=3 --timeout=120
fi

if [ "${APP_SERVICE_ROLE:-web}" = "reverb" ]; then
    exec php artisan reverb:start --host="${REVERB_SERVER_HOST:-0.0.0.0}" --port="${REVERB_SERVER_PORT:-8080}"
fi

if ! php -r '
    $key = getenv("APP_KEY") ?: "";
    $cipher = strtolower(getenv("APP_CIPHER") ?: "aes-256-cbc");
    $expectedLength = str_contains($cipher, "128") ? 16 : 32;
    if (!str_starts_with($key, "base64:")) {
        exit(1);
    }
    $decoded = base64_decode(substr($key, 7), true);
    exit($decoded !== false && strlen($decoded) === $expectedLength ? 0 : 1);
'; then
    export APP_KEY="base64:$(php -r '
        $cipher = strtolower(getenv("APP_CIPHER") ?: "aes-256-cbc");
        $length = str_contains($cipher, "128") ? 16 : 32;
        echo base64_encode(random_bytes($length));
    ')"
fi

mkdir -p storage/framework/sessions storage/framework/cache storage/framework/views
chown -R www-data:www-data storage bootstrap/cache

php artisan storage:link || true
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground
