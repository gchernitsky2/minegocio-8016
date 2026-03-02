FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git curl unzip libzip-dev libpng-dev libonig-dev libxml2-dev libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql mbstring zip exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Create temp .env so artisan scripts work during composer install
RUN cp .env.example .env \
    && php -r "echo 'APP_KEY=base64:'.base64_encode(random_bytes(32)).PHP_EOL;" >> .env

RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data \
    bootstrap/cache database

# Install deps — allow post-scripts to fail (filament:upgrade may fail without DB)
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader --no-interaction 2>&1; \
    test -f vendor/autoload.php && echo "vendor OK" || exit 1

RUN touch database/database.sqlite \
    && php artisan migrate --force 2>&1 || true

# Clean temp .env, set permissions
RUN rm -f .env \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache database

EXPOSE 10000

CMD ["bash", "-c", "php artisan config:cache 2>/dev/null; php artisan view:cache 2>/dev/null; php -S 0.0.0.0:10000 -t public"]
