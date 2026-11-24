FROM php:8.2-fpm-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libonig-dev openssl \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini
WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-interaction --no-scripts --no-autoloader
COPY . .
RUN mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && composer dump-autoload --optimize --no-interaction \
    && chown -R www-data:www-data storage bootstrap/cache
