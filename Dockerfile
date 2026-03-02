FROM composer:2 AS deps

WORKDIR /app
COPY composer.json composer.lock ./

# Install without scripts to avoid needing full app
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .

# Now generate autoload with full app present
RUN composer dump-autoload --no-dev --optimize

# ─────────────────────────────────────

FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev libpng-dev libonig-dev libxml2-dev libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql mbstring zip bcmath gd \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=deps /app .

RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data \
    bootstrap/cache database \
    && touch database/database.sqlite \
    && cp .env.example .env \
    && php -r "echo 'APP_KEY=base64:'.base64_encode(random_bytes(32)).PHP_EOL;" >> .env \
    && php artisan package:discover --ansi 2>/dev/null || true \
    && php artisan migrate --force 2>/dev/null || true \
    && rm -f .env \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache database

EXPOSE 10000

CMD ["bash", "-c", "php artisan config:cache 2>/dev/null; php artisan view:cache 2>/dev/null; php -S 0.0.0.0:10000 -t public"]
