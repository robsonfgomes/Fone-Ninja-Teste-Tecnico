#!/bin/bash
set -e

chmod -R 777 /var/www/storage /var/www/bootstrap/cache

echo "Waiting for database connection..."
until php artisan db:show > /dev/null 2>&1; do
    sleep 2
done

php artisan migrate --force

exec "$@"
