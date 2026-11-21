#!/bin/sh

set -eu

if [ "${DB_CONNECTION:-}" = "sqlite" ]; then
    tfa8_db_file="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    mkdir -p "$(dirname "$tfa8_db_file")"
    touch "$tfa8_db_file"
fi

php artisan migrate --force
php artisan db:seed --force
php artisan storage:link || true
php artisan optimize

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
