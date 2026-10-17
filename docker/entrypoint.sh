#!/bin/sh
set -e

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
    export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
fi

php artisan storage:link || true
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground
