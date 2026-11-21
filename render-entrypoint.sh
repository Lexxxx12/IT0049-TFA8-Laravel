#!/bin/sh

set -eu

if [ "${DB_CONNECTION:-}" = "pgsql" ] && [ -n "${DB_SCHEMA:-}" ]; then
    php -r '
        $schema = getenv("DB_SCHEMA");

        if (! preg_match("/^[a-z][a-z0-9_]*$/", $schema)) {
            throw new RuntimeException("DB_SCHEMA must be a safe PostgreSQL identifier.");
        }

        $parts = parse_url(getenv("DB_URL"));
        $dsn = sprintf(
            "pgsql:host=%s;port=%s;dbname=%s",
            $parts["host"],
            $parts["port"] ?? 5432,
            ltrim($parts["path"], "/")
        );
        $pdo = new PDO($dsn, urldecode($parts["user"] ?? ""), urldecode($parts["pass"] ?? ""));
        $pdo->exec(sprintf("CREATE SCHEMA IF NOT EXISTS %s", $schema));
    '
fi

php artisan migrate --force
php artisan db:seed --force
php artisan storage:link || true
php artisan optimize

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
