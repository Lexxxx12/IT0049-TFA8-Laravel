#!/bin/sh

set -eu

php artisan migrate --force
php artisan db:seed --force
php artisan storage:link || true
php artisan optimize

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
