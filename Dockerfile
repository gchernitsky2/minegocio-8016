FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git curl unzip libzip-dev libpng-dev libonig-dev libxml2-dev libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql mbstring zip exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data \
    bootstrap/cache database \
    && touch database/database.sqlite \
    && cp .env.example .env \
    && php -r "echo 'APP_KEY=base64:'.base64_encode(random_bytes(32)).PHP_EOL;" >> .env

RUN composer install --no-dev --optimize-autoloader || true

RUN php artisan migrate --force || true

RUN rm -f .env \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache database

EXPOSE 10000
CMD ["bash", "-lc", "php -S 0.0.0.0:10000 -t public"]
