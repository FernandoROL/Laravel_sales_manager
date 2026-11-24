#!/bin/sh
set -eu
mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs
composer install --no-interaction --prefer-dist --no-progress
if ! php -r 'require "vendor/autoload.php"; $app = require "bootstrap/app.php"; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); exit(filled(config("app.key")) ? 0 : 1);'; then
    php artisan key:generate --no-interaction
fi
php artisan migrate --force --no-interaction
exec php-fpm
